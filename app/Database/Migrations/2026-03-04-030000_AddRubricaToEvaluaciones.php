<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddRubricaToEvaluaciones extends Migration
{
    public function up()
    {
        // Agregar 5 columnas de rúbrica a evaluaciones_empleados
        $fields = [
            'puntaje_responsabilidad' => [
                'type'    => 'INT',
                'null'    => true,
                'default' => null,
            ],
            'puntaje_equipo' => [
                'type'    => 'INT',
                'null'    => true,
                'default' => null,
            ],
            'puntaje_etica' => [
                'type'    => 'INT',
                'null'    => true,
                'default' => null,
            ],
            'puntaje_comunicacion' => [
                'type'    => 'INT',
                'null'    => true,
                'default' => null,
            ],
            'puntaje_compromiso' => [
                'type'    => 'INT',
                'null'    => true,
                'default' => null,
            ],
        ];

        $this->forge->addColumn('evaluaciones_empleados', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('evaluaciones_empleados', 'puntaje_responsabilidad');
        $this->forge->dropColumn('evaluaciones_empleados', 'puntaje_equipo');
        $this->forge->dropColumn('evaluaciones_empleados', 'puntaje_etica');
        $this->forge->dropColumn('evaluaciones_empleados', 'puntaje_comunicacion');
        $this->forge->dropColumn('evaluaciones_empleados', 'puntaje_compromiso');
    }
}
