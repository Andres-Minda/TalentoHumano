<?php

namespace App\Models;

use CodeIgniter\Model;

class CapacitacionModel extends Model
{
    protected $table = 'capacitaciones';
    protected $primaryKey = 'id_capacitacion';
    protected $allowedFields = [
        'nombre', 'descripcion', 'tipo', 'fecha_inicio', 'fecha_fin', 
        'costo', 'proveedor', 'estado', 'cupo_maximo', 'periodo_academico_id'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $returnType = 'array';

    // Obtener capacitaciones con estadísticas
    public function getCapacitacionesConEstadisticas()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('capacitaciones c');
        $builder->select('c.*, COUNT(ec.id_empleado_capacitacion) as total_inscritos');
        $builder->join('empleados_capacitaciones ec', 'ec.id_capacitacion = c.id_capacitacion', 'left');
        $builder->groupBy('c.id_capacitacion');
        $builder->orderBy('c.fecha_inicio', 'DESC');
        return $builder->get()->getResultArray();
    }

    // Obtener capacitaciones por tipo específico
    public function getCapacitacionesPorTipo($tipo)
    {
        return $this->where('tipo', $tipo)->findAll();
    }

    // Obtener capacitaciones disponibles para un empleado
    public function getCapacitacionesDisponibles($idEmpleado)
    {
        $db = \Config\Database::connect();
        
        // Query simple sin subconsultas complejas
        $sql = "SELECT c.*, 
                       (SELECT COUNT(*) FROM empleados_capacitaciones ec WHERE ec.id_capacitacion = c.id_capacitacion) as inscritos_actuales
                FROM capacitaciones c
                WHERE c.estado = 'Planificada'
                AND c.fecha_inicio >= CURDATE()";
        
        if ($idEmpleado) {
            $sql .= " AND c.id_capacitacion NOT IN (SELECT ec.id_capacitacion FROM empleados_capacitaciones ec WHERE ec.id_empleado = ?)";
        }
        
        $sql .= " AND (SELECT COUNT(*) FROM empleados_capacitaciones ec WHERE ec.id_capacitacion = c.id_capacitacion) < c.cupo_maximo";
        
        if ($idEmpleado) {
            $query = $db->query($sql, [$idEmpleado]);
        } else {
            $query = $db->query($sql);
        }
        
        return $query->getResultArray();
    }

    // Obtener estadísticas de capacitaciones
    public function getEstadisticasCapacitaciones()
    {
        $presencial = $this->where('tipo', 'Presencial')->countAllResults();
        $virtual = $this->where('tipo', 'Virtual')->countAllResults();
        $hibrido = $this->where('tipo', 'Híbrido')->countAllResults();
        $total = $this->countAllResults();
        
        return [
            'presencial' => $presencial,
            'virtual' => $virtual,
            'hibrido' => $hibrido,
            'total' => $total
        ];
    }

    // Obtener capacitaciones por tipo para gráficos
    public function getCapacitacionesPorTipoParaGraficos()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('capacitaciones');
        $builder->select('tipo, COUNT(*) as cantidad');
        $builder->groupBy('tipo');
        $result = $builder->get()->getResultArray();
        
        // Asegurar que todos los tipos estén representados
        $tipos = ['Presencial', 'Virtual', 'Híbrido'];
        $data = [];
        
        foreach ($tipos as $tipo) {
            $encontrado = false;
            foreach ($result as $row) {
                if ($row['tipo'] == $tipo) {
                    $data[] = $row;
                    $encontrado = true;
                    break;
                }
            }
            if (!$encontrado) {
                $data[] = ['tipo' => $tipo, 'cantidad' => 0];
            }
        }
        
        return $data;
    }
} 