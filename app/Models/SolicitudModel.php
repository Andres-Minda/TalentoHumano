<?php

namespace App\Models;

use CodeIgniter\Model;

class SolicitudModel extends Model
{
    protected $table = 'solicitudes';
    protected $primaryKey = 'id_solicitud';
    protected $allowedFields = [
        'id_empleado', 'tipo_solicitud', 'titulo', 'descripcion', 'estado',
        'resuelto_por', 'comentarios_resolucion', 'activo'
    ];
    protected $useTimestamps = false;
    protected $returnType = 'array';

    // Obtener solicitudes con información completa
    public function getSolicitudesCompletas()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('solicitudes s');
        $builder->select('s.*, e.nombres as empleado_nombres, e.apellidos as empleado_apellidos, r.nombres as resolutor_nombres, r.apellidos as resolutor_apellidos');
        $builder->join('empleados e', 'e.id_empleado = s.id_empleado');
        $builder->join('empleados r', 'r.id_empleado = s.resuelto_por', 'left');
        $builder->where('s.activo', 1);
        $builder->orderBy('s.fecha_solicitud', 'DESC');
        return $builder->get()->getResultArray();
    }

    // Obtener solicitudes de un empleado
    public function getSolicitudesEmpleado($idEmpleado)
    {
        return $this->where('id_empleado', $idEmpleado)
                    ->where('activo', 1)
                    ->orderBy('fecha_solicitud', 'DESC')
                    ->findAll();
    }

    // Obtener solicitudes por estado
    public function getSolicitudesPorEstado($estado)
    {
        return $this->where('estado', $estado)
                    ->where('activo', 1)
                    ->findAll();
    }
} 