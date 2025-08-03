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
        $builder = $db->table('capacitaciones c');
        $builder->select('c.*, COUNT(ec.id_empleado_capacitacion) as total_inscritos');
        $builder->join('empleados_capacitaciones ec', 'ec.id_capacitacion = c.id_capacitacion', 'left');
        $builder->where('c.fecha_inicio >=', date('Y-m-d'));
        $builder->where('c.estado', 'Planificada');
        $builder->groupBy('c.id_capacitacion');
        
        // Excluir capacitaciones en las que ya está inscrito
        $subquery = $db->table('empleados_capacitaciones')
                       ->select('id_capacitacion')
                       ->where('id_empleado', $idEmpleado);
        
        $builder->whereNotIn('c.id_capacitacion', $subquery);
        
        return $builder->get()->getResultArray();
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