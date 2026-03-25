<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Migración 4: Crear tabla evaluaciones_necesidades_capacitacion
 *
 * Tabla puente que vincula las debilidades detectadas en evaluaciones
 * con sugerencias de capacitación. Sirve como insumo para el plan
 * de capacitaciones del módulo existente (tabla capacitaciones).
 *
 * NOTA: Se usa SQL directo para las FKs porque CI4 genera nombres de
 * constraint demasiado largos para MySQL (límite 64 caracteres).
 */
class CreateEvaluacionesNecesidadesCapacitacion extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            // Periodo académico de referencia
            'id_periodo_academico' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => false,
            ],
            // Categoría de evaluación donde se detectó la debilidad
            'id_categoria_evaluacion' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => false,
            ],
            // Docente que presentó debilidad (INT signed = empleados.id_empleado)
            'id_empleado' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => false,
            ],
            // Puntaje promedio obtenido en esa categoría
            'puntaje_promedio' => [
                'type'       => 'DECIMAL',
                'constraint' => '5,2',
                'null'       => true,
            ],
            // Nombre del curso o capacitación sugerida
            'sugerencia_curso' => [
                'type'       => 'VARCHAR',
                'constraint' => 500,
                'null'       => false,
            ],
            // Prioridad de la necesidad de capacitación
            'prioridad' => [
                'type'       => 'ENUM',
                'constraint' => ['Baja', 'Media', 'Alta', 'Crítica'],
                'null'       => false,
                'default'    => 'Media',
            ],
            // FK opcional hacia la tabla capacitaciones (cuando se asigna una)
            // INT signed para coincidir con capacitaciones.id_capacitacion
            'id_capacitacion' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => true,
                'default'    => null,
            ],
            // Estado de seguimiento
            'estado' => [
                'type'       => 'ENUM',
                'constraint' => ['Identificada', 'Planificada', 'En Curso', 'Completada'],
                'null'       => false,
                'default'    => 'Identificada',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('id_periodo_academico');
        $this->forge->addKey('id_categoria_evaluacion');
        $this->forge->addKey('id_empleado');
        $this->forge->addKey('id_capacitacion');
        $this->forge->addKey('estado');

        // No usamos addForeignKey() porque CI4 genera nombres de constraint
        // que exceden el límite de 64 caracteres de MySQL para esta tabla.
        $this->forge->createTable('evaluaciones_necesidades_capacitacion');

        // Agregar FKs manualmente con nombres cortos
        $this->db->query('
            ALTER TABLE `evaluaciones_necesidades_capacitacion`
            ADD CONSTRAINT `fk_enc_periodo` FOREIGN KEY (`id_periodo_academico`)
                REFERENCES `periodos_academicos`(`id_periodo`) ON DELETE CASCADE ON UPDATE CASCADE,
            ADD CONSTRAINT `fk_enc_categoria` FOREIGN KEY (`id_categoria_evaluacion`)
                REFERENCES `categorias_evaluacion`(`id`) ON DELETE CASCADE ON UPDATE CASCADE,
            ADD CONSTRAINT `fk_enc_empleado` FOREIGN KEY (`id_empleado`)
                REFERENCES `empleados`(`id_empleado`) ON DELETE CASCADE ON UPDATE CASCADE,
            ADD CONSTRAINT `fk_enc_capacitacion` FOREIGN KEY (`id_capacitacion`)
                REFERENCES `capacitaciones`(`id_capacitacion`) ON DELETE SET NULL ON UPDATE CASCADE
        ');
    }

    public function down()
    {
        $this->forge->dropTable('evaluaciones_necesidades_capacitacion', true);
    }
}
