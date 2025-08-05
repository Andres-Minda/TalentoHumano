<?php

namespace App\Models;

use CodeIgniter\Model;

class PermisoModel extends Model
{
    protected $table = 'permisos';
    protected $primaryKey = 'id_permiso';
    protected $allowedFields = [
        'id_empleado', 'tipo_permiso', 'fecha_inicio', 'fecha_fin', 'dias', 
        'estado', 'motivo', 'archivo_url'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $returnType = 'array';

    public function getPermisosCompletos()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('permisos p');
        $builder->select('p.*, e.nombres as empleado_nombres, e.apellidos as empleado_apellidos');
        $builder->join('empleados e', 'e.id_empleado = p.id_empleado');
        $builder->orderBy('p.fecha_inicio', 'DESC');
        return $builder->get()->getResultArray();
    }

    public function getPermisosPorEstado($estado)
    {
        return $this->where('estado', $estado)->findAll();
    }

    public function getPermisosEmpleado($idEmpleado)
    {
        return $this->where('id_empleado', $idEmpleado)->orderBy('fecha_inicio', 'DESC')->findAll();
    }

    public function getPermisosPorEmpleado($idEmpleado)
    {
        return $this->where('id_empleado', $idEmpleado)
                    ->orderBy('fecha_inicio', 'DESC')
                    ->findAll();
    }
} 