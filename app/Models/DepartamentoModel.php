<?php

namespace App\Models;

use CodeIgniter\Model;

class DepartamentoModel extends Model
{
    protected $table = 'departamentos';
    protected $primaryKey = 'id_departamento';
    protected $allowedFields = [
        'nombre', 'descripcion', 'id_jefe'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $returnType = 'array';

    public function getDepartamentosActivos()
    {
        return $this->findAll();
    }

    public function getDepartamentoConJefe($idDepartamento)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('departamentos d');
        $builder->select('d.*, e.nombres as jefe_nombres, e.apellidos as jefe_apellidos');
        $builder->join('empleados e', 'e.id_empleado = d.id_jefe', 'left');
        $builder->where('d.id_departamento', $idDepartamento);
        return $builder->get()->getRowArray();
    }

    public function getDepartamentosConEstadisticas()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('departamentos d');
        $builder->select('d.*, 
                         COUNT(e.id_empleado) as total_empleados,
                         j.nombres as jefe_nombres, 
                         j.apellidos as jefe_apellidos,
                         CONCAT(j.nombres, " ", j.apellidos) as jefe_nombre');
        $builder->join('empleados e', 'e.id_departamento = d.id_departamento', 'left');
        $builder->join('empleados j', 'j.id_empleado = d.id_jefe', 'left');
        $builder->groupBy('d.id_departamento');
        return $builder->get()->getResultArray();
    }

    public function getEstadisticasDepartamentos()
    {
        $db = \Config\Database::connect();
        
        // Contar total de departamentos
        $query = $db->query("SELECT COUNT(*) as total FROM departamentos");
        $total = $query->getRow()->total;
        
        // Contar departamentos con empleados
        $query = $db->query("SELECT COUNT(DISTINCT d.id_departamento) as con_empleados 
                             FROM departamentos d 
                             JOIN empleados e ON e.id_departamento = d.id_departamento");
        $conEmpleados = $query->getRow()->con_empleados;
        
        return [
            'total' => $total,
            'con_empleados' => $conEmpleados,
            'sin_empleados' => $total - $conEmpleados
        ];
    }
} 