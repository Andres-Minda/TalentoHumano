<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateVacacionesTables extends Migration
{
    public function up()
    {
        // 1. Añadir dias_vacaciones_disponibles a la tabla empleados
        $dbFieldsEmpleados = $this->db->getFieldNames('empleados');
        if (!in_array('dias_vacaciones_disponibles', $dbFieldsEmpleados)) {
            $this->forge->addColumn('empleados', [
                'dias_vacaciones_disponibles' => [
                    'type'       => 'INT',
                    'constraint' => 11,
                    'null'       => false,
                    'default'    => 15,
                    'after'      => 'activo' // Opcional, para ubicarlo al final
                ]
            ]);
        }

        // 2. Añadir dias_solicitados a la tabla solicitudes
        $dbFieldsSolicitudes = $this->db->getFieldNames('solicitudes');
        if (!in_array('dias_solicitados', $dbFieldsSolicitudes)) {
            $this->forge->addColumn('solicitudes', [
                'dias_solicitados' => [
                    'type'       => 'INT',
                    'constraint' => 11,
                    'null'       => true,
                    'after'      => 'motivo_descripcion' // Opcional
                ]
            ]);
        }
    }

    public function down()
    {
        // $this->forge->dropColumn('empleados', 'dias_vacaciones_disponibles');
        // $this->forge->dropColumn('solicitudes', 'dias_solicitados');
    }
}
