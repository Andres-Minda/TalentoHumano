<?php

namespace App\Models;

use CodeIgniter\Model;

class EmpleadoBeneficioModel extends Model
{
    protected $table = 'beneficios_empleados';
    protected $primaryKey = 'id_beneficio_empleado';
    protected $allowedFields = [
        'id_beneficio', 'id_empleado', 'fecha_inicio', 'fecha_fin', 'observaciones'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $returnType = 'array';

    public function getAsignacionesCompletas()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('beneficios_empleados be');
        $builder->select('be.*, e.nombres as empleado_nombres, e.apellidos as empleado_apellidos, b.nombre as beneficio_nombre, b.tipo as beneficio_tipo');
        $builder->join('empleados e', 'e.id_empleado = be.id_empleado');
        $builder->join('beneficios b', 'b.id_beneficio = be.id_beneficio');
        $builder->orderBy('be.fecha_inicio', 'DESC');
        return $builder->get()->getResultArray();
    }

    public function getBeneficiosEmpleado($idEmpleado)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('beneficios_empleados be');
        $builder->select('be.*, b.nombre as beneficio_nombre, b.tipo as beneficio_tipo');
        $builder->join('beneficios b', 'b.id_beneficio = be.id_beneficio');
        $builder->where('be.id_empleado', $idEmpleado);
        $builder->where('(be.fecha_fin IS NULL OR be.fecha_fin >= CURDATE())');
        return $builder->get()->getResultArray();
    }
} 