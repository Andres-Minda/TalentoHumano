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
        $builder->select('d.*, COUNT(e.id_empleado) as total_empleados');
        $builder->join('empleados e', 'e.id_departamento = d.id_departamento', 'left');
        $builder->groupBy('d.id_departamento');
        return $builder->get()->getResultArray();
    }

    public function getEstadisticasDepartamentos()
    {
        $total = $this->countAllResults();
        $conEmpleados = $this->join('empleados e', 'e.id_departamento = departamentos.id_departamento')
                             ->groupBy('departamentos.id_departamento')
                             ->countAllResults();
        
        return [
            'total' => $total,
            'con_empleados' => $conEmpleados,
            'sin_empleados' => $total - $conEmpleados
        ];
    }
} 