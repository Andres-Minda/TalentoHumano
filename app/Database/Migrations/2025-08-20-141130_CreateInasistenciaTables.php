<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateInasistenciaTables extends Migration
{
    public function up()
    {
        // Crear tabla tipos_inasistencia
        $this->forge->addField([
            'id_tipo' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'nombre_tipo' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => false,
            ],
            'descripcion' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'requiere_justificacion' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'null'       => false,
                'default'    => 0,
            ],
            'activo' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'null'       => false,
                'default'    => 1,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
        ]);

        $this->forge->addKey('id_tipo', true);
        $this->forge->addUniqueKey('nombre_tipo');
        $this->forge->createTable('tipos_inasistencia', true);

        // Crear tabla politicas_inasistencia
        $this->forge->addField([
            'id_politica' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'nombre_politica' => [
                'type'       => 'VARCHAR',
                'constraint' => 200,
                'null'       => false,
            ],
            'descripcion' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'max_inasistencias_mes' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => false,
                'default'    => 3,
            ],
            'max_inasistencias_trimestre' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => false,
                'default'    => 9,
            ],
            'max_inasistencias_anio' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => false,
                'default'    => 36,
            ],
            'requiere_accion_disciplinaria' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'null'       => false,
                'default'    => 0,
            ],
            'activo' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'null'       => false,
                'default'    => 1,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
        ]);

        $this->forge->addKey('id_politica', true);
        $this->forge->createTable('politicas_inasistencia', true);

        // Insertar datos iniciales para tipos de inasistencia
        $tiposInasistencia = [
            [
                'nombre_tipo' => 'Justificada',
                'descripcion' => 'Inasistencia con justificación válida',
                'requiere_justificacion' => 1,
                'activo' => 1
            ],
            [
                'nombre_tipo' => 'Injustificada',
                'descripcion' => 'Inasistencia sin justificación',
                'requiere_justificacion' => 0,
                'activo' => 1
            ],
            [
                'nombre_tipo' => 'Permiso',
                'descripcion' => 'Permiso autorizado',
                'requiere_justificacion' => 1,
                'activo' => 1
            ],
            [
                'nombre_tipo' => 'Vacaciones',
                'descripcion' => 'Días de vacaciones',
                'requiere_justificacion' => 0,
                'activo' => 1
            ],
            [
                'nombre_tipo' => 'Licencia Médica',
                'descripcion' => 'Licencia por enfermedad',
                'requiere_justificacion' => 1,
                'activo' => 1
            ]
        ];

        $this->db->table('tipos_inasistencia')->insertBatch($tiposInasistencia);

        // Insertar política por defecto
        $politicaDefault = [
            'nombre_politica' => 'Política General de Inasistencias',
            'descripcion' => 'Política estándar para el control de inasistencias',
            'max_inasistencias_mes' => 3,
            'max_inasistencias_trimestre' => 9,
            'max_inasistencias_anio' => 36,
            'requiere_accion_disciplinaria' => 0,
            'activo' => 1
        ];

        $this->db->table('politicas_inasistencia')->insert($politicaDefault);
    }

    public function down()
    {
        $this->forge->dropTable('tipos_inasistencia', true);
        $this->forge->dropTable('politicas_inasistencia', true);
    }
}
