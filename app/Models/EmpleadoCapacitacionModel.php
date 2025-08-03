<?php

namespace App\Models;

use CodeIgniter\Model;

class EmpleadoCapacitacionModel extends Model
{
    protected $table = 'empleados_capacitaciones';
    protected $primaryKey = 'id_empleado_capacitacion';
    protected $allowedFields = [
        'id_capacitacion', 'id_empleado', 'asistio', 'aprobo', 'certificado_url'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $returnType = 'array';

    public function getAsignacionesCompletas()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('empleados_capacitaciones ec');
        $builder->select('ec.*, e.nombres as empleado_nombres, e.apellidos as empleado_apellidos, c.nombre as capacitacion_nombre');
        $builder->join('empleados e', 'e.id_empleado = ec.id_empleado');
        $builder->join('capacitaciones c', 'c.id_capacitacion = ec.id_capacitacion');
        $builder->orderBy('ec.created_at', 'DESC');
        return $builder->get()->getResultArray();
    }

    public function getCapacitacionesEmpleado($idEmpleado)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('empleados_capacitaciones ec');
        $builder->select('ec.*, c.nombre as capacitacion_nombre, c.tipo, c.fecha_inicio, c.fecha_fin');
        $builder->join('capacitaciones c', 'c.id_capacitacion = ec.id_capacitacion');
        $builder->where('ec.id_empleado', $idEmpleado);
        $builder->orderBy('c.fecha_inicio', 'DESC');
        return $builder->get()->getResultArray();
    }

    public function getEstadisticasEmpleado($idEmpleado)
    {
        $asistio = $this->where('id_empleado', $idEmpleado)->where('asistio', 1)->countAllResults();
        $aprobo = $this->where('id_empleado', $idEmpleado)->where('aprobo', 1)->countAllResults();
        $total = $this->where('id_empleado', $idEmpleado)->countAllResults();
        
        return [
            'asistio' => $asistio,
            'aprobo' => $aprobo,
            'total' => $total
        ];
    }
} 