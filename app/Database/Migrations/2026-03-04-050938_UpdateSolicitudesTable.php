<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateSolicitudesTable extends Migration
{
    public function up()
    {
        $fields = [
            'fecha_inicio' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'fecha_fin' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'tipo_solicitud' => [
                'type' => 'VARCHAR',
                'constraint' => '50',
                'null' => false,
            ],
            'motivo_descripcion' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'fecha_solicitud' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ];

        // Verificar si las columnas no existen antes de agregarlas
        $dbFields = $this->db->getFieldNames('solicitudes');
        
        $fieldsToAdd = [];
        foreach ($fields as $fieldName => $fieldProps) {
            // Modificamos tipo_solicitud sin importar si existe
            if ($fieldName === 'tipo_solicitud' || $fieldName === 'motivo_descripcion') {
                continue; 
            }
            if (!in_array($fieldName, $dbFields)) {
                $fieldsToAdd[$fieldName] = $fieldProps;
            }
        }

        if (!empty($fieldsToAdd)) {
            $this->forge->addColumn('solicitudes', $fieldsToAdd);
        }

        // Modificar columna existente (tipo_solicitud para admitir "Certificado", "Vacaciones")
        $this->forge->modifyColumn('solicitudes', [
            'tipo_solicitud' => [
                'name' => 'tipo_solicitud',
                'type' => 'VARCHAR',
                'constraint' => '50',
            ],
            'descripcion' => [
                'name' => 'motivo_descripcion',
                'type' => 'TEXT',
                'null' => true
            ]
        ]);
    }

    public function down()
    {
        // $this->forge->dropColumn('solicitudes', 'fecha_inicio');
        // $this->forge->dropColumn('solicitudes', 'fecha_fin');
        // $this->forge->dropColumn('solicitudes', 'fecha_solicitud');
    }
}
