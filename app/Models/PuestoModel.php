<?php

namespace App\Models;

use CodeIgniter\Model;

class PuestoModel extends Model
{
    protected $table = 'puestos';
    protected $primaryKey = 'id_puesto';
    protected $allowedFields = [
        'nombre', 'descripcion', 'salario_base', 'id_departamento'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $returnType = 'array';

    public function getPuestosConDepartamento()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('puestos p');
        $builder->select('p.*, d.nombre as departamento_nombre');
        $builder->join('departamentos d', 'd.id_departamento = p.id_departamento');
        return $builder->get()->getResultArray();
    }

    public function getPuestosPorDepartamento($idDepartamento)
    {
        return $this->where('id_departamento', $idDepartamento)->findAll();
    }

    public function getPuestosConEstadisticas()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('puestos p');
        $builder->select('p.*, d.nombre as departamento_nombre, COUNT(e.id_empleado) as total_empleados');
        $builder->join('departamentos d', 'd.id_departamento = p.id_departamento');
        $builder->join('empleados e', 'e.id_puesto = p.id_puesto', 'left');
        $builder->groupBy('p.id_puesto');
        return $builder->get()->getResultArray();
    }
} 