<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Migración 1: Adaptar evaluaciones_empleados para el Proceso de Evaluación ISTI
 *
 * - Agrega campo tipo_evaluador ENUM('Directiva','Par','Estudiante')
 * - Hace id_evaluador NULLable para evaluaciones anónimas de estudiantes
 * - Agrega campos token_anonimo y grupo_curso para vincular tokens estudiantiles
 */
class AdaptEvaluacionesEmpleadosForISTI extends Migration
{
    public function up()
    {
        // ── Paso 1: Eliminar la FK actual sobre id_evaluador ──
        // La FK actual (evaluaciones_empleados_ibfk_3) impide que id_evaluador sea NULL
        $this->db->query('ALTER TABLE `evaluaciones_empleados` DROP FOREIGN KEY `evaluaciones_empleados_ibfk_3`');

        // ── Paso 2: Modificar id_evaluador para permitir NULL ──
        // Cuando tipo_evaluador = 'Estudiante', id_evaluador será NULL (anónimo)
        // NOTA: empleados.id_empleado es INT(11) signed, mantener consistencia
        $this->forge->modifyColumn('evaluaciones_empleados', [
            'id_evaluador' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => true,
                'default'    => null,
            ],
        ]);

        // ── Paso 3: Re-crear la FK con ON DELETE SET NULL ──
        $this->db->query('
            ALTER TABLE `evaluaciones_empleados`
            ADD CONSTRAINT `fk_ee_evaluador`
            FOREIGN KEY (`id_evaluador`) REFERENCES `empleados`(`id_empleado`)
            ON DELETE SET NULL ON UPDATE CASCADE
        ');

        // ── Paso 4: Agregar nuevas columnas ──
        $this->forge->addColumn('evaluaciones_empleados', [
            // Tipo de evaluador según las 3 dimensiones del ISTI
            'tipo_evaluador' => [
                'type'       => 'ENUM',
                'constraint' => ['Directiva', 'Par', 'Estudiante'],
                'null'       => false,
                'default'    => 'Directiva',
                'after'      => 'id_evaluador',
            ],
            // Hash SHA-256 del token usado por el estudiante (para auditoría)
            'token_anonimo' => [
                'type'       => 'VARCHAR',
                'constraint' => 64,
                'null'       => true,
                'default'    => null,
                'after'      => 'tipo_evaluador',
            ],
            // Identificador del curso/grupo (ej. "3ro Sistemas A")
            'grupo_curso' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
                'default'    => null,
                'after'      => 'token_anonimo',
            ],
        ]);

        // ── Paso 5: Agregar índices ──
        $this->db->query('ALTER TABLE `evaluaciones_empleados` ADD INDEX `idx_tipo_evaluador` (`tipo_evaluador`)');
        $this->db->query('ALTER TABLE `evaluaciones_empleados` ADD UNIQUE INDEX `idx_token_anonimo` (`token_anonimo`)');
    }

    public function down()
    {
        // ── Revertir índices ──
        $this->db->query('ALTER TABLE `evaluaciones_empleados` DROP INDEX `idx_token_anonimo`');
        $this->db->query('ALTER TABLE `evaluaciones_empleados` DROP INDEX `idx_tipo_evaluador`');

        // ── Revertir columnas agregadas ──
        $this->forge->dropColumn('evaluaciones_empleados', 'grupo_curso');
        $this->forge->dropColumn('evaluaciones_empleados', 'token_anonimo');
        $this->forge->dropColumn('evaluaciones_empleados', 'tipo_evaluador');

        // ── Revertir FK: quitar la nueva y restaurar la original ──
        $this->db->query('ALTER TABLE `evaluaciones_empleados` DROP FOREIGN KEY `fk_ee_evaluador`');

        // Restaurar id_evaluador a NOT NULL (signed INT como el original)
        $this->forge->modifyColumn('evaluaciones_empleados', [
            'id_evaluador' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => false,
            ],
        ]);

        // Restaurar la FK original
        $this->db->query('
            ALTER TABLE `evaluaciones_empleados`
            ADD CONSTRAINT `evaluaciones_empleados_ibfk_3`
            FOREIGN KEY (`id_evaluador`) REFERENCES `empleados`(`id_empleado`)
        ');
    }
}
