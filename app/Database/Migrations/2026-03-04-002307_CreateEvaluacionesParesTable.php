<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateEvaluacionesParesTable extends Migration
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
            'evaluador_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => false,
            ],
            'evaluado_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => false,
            ],
            'estado' => [
                'type'       => 'ENUM',
                'constraint' => ['pendiente', 'en curso', 'completada'],
                'default'    => 'pendiente',
            ],
            'observacion_clase' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'revision_materiales' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'retroalimentacion' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'fecha_asignacion' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
            'fecha_evaluacion' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('evaluador_id');
        $this->forge->addKey('evaluado_id');
        $this->forge->createTable('evaluaciones_pares');
    }

    public function down()
    {
        $this->forge->dropTable('evaluaciones_pares');
    }
}
