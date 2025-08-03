<?php

namespace App\Models;

use CodeIgniter\Model;

class ContratoModel extends Model
{
    protected $table = 'contratos';
    protected $primaryKey = 'id_contrato';
    protected $allowedFields = [
        'id_empleado', 'id_puesto', 'tipo_contrato', 'fecha_inicio', 
        'fecha_fin', 'salario', 'horas_semanales', 'archivo_url'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $returnType = 'array';

    public function getContratosCompletos()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('contratos c');
        $builder->select('c.*, e.nombres as empleado_nombres, e.apellidos as empleado_apellidos, p.nombre as puesto_nombre');
        $builder->join('empleados e', 'e.id_empleado = c.id_empleado');
        $builder->join('puestos p', 'p.id_puesto = c.id_puesto');
        $builder->orderBy('c.fecha_inicio', 'DESC');
        return $builder->get()->getResultArray();
    }

    public function getContratosActivos()
    {
        return $this->where('fecha_fin IS NULL OR fecha_fin >=', date('Y-m-d'))->findAll();
    }

    public function getContratosPorEmpleado($idEmpleado)
    {
        return $this->where('id_empleado', $idEmpleado)->findAll();
    }
} 