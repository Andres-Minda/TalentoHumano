<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class PeriodoAcademicoSeeder extends Seeder
{
    public function run()
    {
        $nuevoPeriodo = [
            'nombre'       => '2026 - 2027',
            'fecha_inicio' => '2026-05-01',
            'fecha_fin'    => '2027-04-30',
            'estado'       => 'Activo',
            'descripcion'  => 'Periodo académico actual (2026-2027)'
        ];

        // Desactivar todos los existentes
        $this->db->table('periodos_academicos')->update(['estado' => 'Inactivo']);
        
        // Insertar el nuevo periodo (2026 - 2027) 
        // Primero verificamos si ya existe para evitar duplicación
        $existeNuevo = $this->db->table('periodos_academicos')
                                ->where('nombre', '2026 - 2027')
                                ->countAllResults();

        if ($existeNuevo == 0) {
            $this->db->table('periodos_academicos')->insert($nuevoPeriodo);
        } else {
            // Asegurarnos de que el periodo 2026-2027 esté Activo
            $this->db->table('periodos_academicos')
                     ->where('nombre', '2026 - 2027')
                     ->update(['estado' => 'Activo']);
        }
    }
}
