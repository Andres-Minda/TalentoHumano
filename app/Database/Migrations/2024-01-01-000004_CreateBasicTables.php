<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateBasicTables extends Migration
{
    public function up()
    {
        // Crear tabla empleado_capacitaciones (sin foreign keys por ahora)
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'id_empleado' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => false,
            ],
            'id_capacitacion' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => false,
            ],
            'fecha_asignacion' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
            'estado' => [
                'type'       => 'ENUM',
                'constraint' => ['Asignado', 'En Progreso', 'Completado', 'Cancelado'],
                'null'       => false,
                'default'    => 'Asignado',
            ],
            'fecha_creacion' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('id_empleado');
        $this->forge->addKey('id_capacitacion');
        $this->forge->createTable('empleado_capacitaciones', true);

        // Crear tabla solicitudes_capacitacion (sin foreign keys por ahora)
        $this->forge->addField([
            'id_solicitud' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'id_empleado' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => false,
            ],
            'tipo_capacitacion' => [
                'type'       => 'ENUM',
                'constraint' => ['Técnica', 'Pedagógica', 'Administrativa', 'Soft Skills', 'Otra'],
                'null'       => false,
            ],
            'nombre_capacitacion' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => false,
            ],
            'descripcion' => [
                'type' => 'TEXT',
                'null' => false,
            ],
            'justificacion' => [
                'type'       => 'VARCHAR',
                'constraint' => 500,
                'null'       => false,
            ],
            'prioridad' => [
                'type'       => 'ENUM',
                'constraint' => ['Baja', 'Media', 'Alta', 'Crítica'],
                'null'       => false,
            ],
            'fecha_deseada' => [
                'type' => 'DATE',
                'null' => false,
            ],
            'duracion_estimada' => [
                'type'       => 'INT',
                'constraint' => 3,
                'null'       => false,
            ],
            'estado' => [
                'type'       => 'ENUM',
                'constraint' => ['Pendiente', 'Aprobada', 'Rechazada', 'Cancelada'],
                'null'       => false,
                'default'    => 'Pendiente',
            ],
            'fecha_creacion' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
        ]);

        $this->forge->addKey('id_solicitud', true);
        $this->forge->addKey('id_empleado');
        $this->forge->addKey('estado');
        $this->forge->createTable('solicitudes_capacitacion', true);

        // Agregar columna tipo_inasistencia a inasistencias si existe
        if ($this->db->tableExists('inasistencias')) {
            $this->forge->addColumn('inasistencias', [
                'tipo_inasistencia' => [
                    'type'       => 'ENUM',
                    'constraint' => ['Justificada', 'Injustificada', 'Permiso', 'Vacaciones', 'Licencia Médica'],
                    'null'       => true,
                    'after'      => 'justificada',
                ]
            ]);
        }
    }

    public function down()
    {
        $this->forge->dropTable('empleado_capacitaciones', true);
        $this->forge->dropTable('solicitudes_capacitacion', true);
        
        if ($this->db->tableExists('inasistencias')) {
            $this->forge->dropColumn('inasistencias', 'tipo_inasistencia');
        }
    }
}
