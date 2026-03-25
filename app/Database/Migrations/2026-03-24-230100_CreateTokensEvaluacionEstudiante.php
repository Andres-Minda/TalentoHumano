<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Migración 2: Crear tabla tokens_evaluacion_estudiante
 *
 * Gestiona los links anónimos de un solo uso que se entregan a los
 * estudiantes para evaluar a sus docentes sin necesidad de registro.
 *
 * Flujo:
 *   1. Admin genera N tokens para (id_evaluacion + id_docente + grupo_curso)
 *   2. Cada token se convierte en un link: /evaluacion/estudiante/{token}
 *   3. El estudiante abre el link, completa el formulario
 *   4. El sistema marca el token como usado y crea la evaluación anónima
 */
class CreateTokensEvaluacionEstudiante extends Migration
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
            // Evaluación a la que pertenece este lote de tokens
            'id_evaluacion' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => false,
            ],
            // Docente que será evaluado por el estudiante
            // NOTA: empleados.id_empleado es INT(11) signed
            'id_docente' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => false,
            ],
            // Hash SHA-256 único del token (se envía como parte del URL)
            'token' => [
                'type'       => 'VARCHAR',
                'constraint' => 64,
                'null'       => false,
            ],
            // Curso o grupo al que pertenece el estudiante
            'grupo_curso' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],
            // ¿Ya fue utilizado?
            'usado' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'null'       => false,
                'default'    => 0,
            ],
            // IP del estudiante al momento de llenar (auditoría)
            'ip_address' => [
                'type'       => 'VARCHAR',
                'constraint' => 45,
                'null'       => true,
            ],
            // Fecha en que se usó el token
            'fecha_uso' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            // Fecha límite para usar el token
            'fecha_expiracion' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('token');
        $this->forge->addKey('id_evaluacion');
        $this->forge->addKey('id_docente');
        $this->forge->addKey('usado');

        $this->forge->addForeignKey('id_evaluacion', 'evaluaciones', 'id_evaluacion', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('id_docente', 'empleados', 'id_empleado', 'CASCADE', 'CASCADE');

        $this->forge->createTable('tokens_evaluacion_estudiante');
    }

    public function down()
    {
        $this->forge->dropTable('tokens_evaluacion_estudiante', true);
    }
}
