<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Migración 3: Crear tabla acuerdos_mejora
 *
 * Registra los acuerdos entre el Vicerrector/a y los docentes que
 * obtuvieron bajos porcentajes en sus evaluaciones. Permite hacer
 * seguimiento del plan de mejora y su firma/aceptación.
 */
class CreateAcuerdosMejora extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_acuerdo' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            // Evaluación que originó el acuerdo
            'id_evaluacion_empleado' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => false,
            ],
            // Docente con debilidad detectada (INT signed para coincidir con empleados.id_empleado)
            'id_docente' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => false,
            ],
            // Vicerrector/a que realiza el acuerdo (INT signed)
            'id_vicerrector' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => false,
            ],
            // Descripción de la debilidad encontrada
            'debilidad_detectada' => [
                'type' => 'TEXT',
                'null' => false,
            ],
            // Plan de actividades de mejora acordadas
            'actividades_mejora' => [
                'type' => 'TEXT',
                'null' => false,
            ],
            // Estado del acuerdo
            'estado' => [
                'type'       => 'ENUM',
                'constraint' => ['Pendiente de Firma', 'Firmado/Aceptado'],
                'null'       => false,
                'default'    => 'Pendiente de Firma',
            ],
            // Fecha en que se firmó/aceptó el acuerdo
            'fecha_firma' => [
                'type' => 'DATETIME',
                'null' => true,
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

        $this->forge->addKey('id_acuerdo', true);
        $this->forge->addKey('id_evaluacion_empleado');
        $this->forge->addKey('id_docente');
        $this->forge->addKey('id_vicerrector');
        $this->forge->addKey('estado');

        $this->forge->addForeignKey('id_evaluacion_empleado', 'evaluaciones_empleados', 'id_evaluacion_empleado', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('id_docente', 'empleados', 'id_empleado', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('id_vicerrector', 'empleados', 'id_empleado', 'CASCADE', 'CASCADE');

        $this->forge->createTable('acuerdos_mejora');
    }

    public function down()
    {
        $this->forge->dropTable('acuerdos_mejora', true);
    }
}
