<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddTipoInasistenciaToInasistencias extends Migration
{
    public function up()
    {
        // Agregar columna tipo_inasistencia a inasistencias
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
        if ($this->db->tableExists('inasistencias')) {
            $this->forge->dropColumn('inasistencias', 'tipo_inasistencia');
        }
    }
}
