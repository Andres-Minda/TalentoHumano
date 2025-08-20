<?php

namespace App\Models;

use CodeIgniter\Model;

class EmpleadoCapacitacionModel extends Model
{
    protected $table            = 'empleados_capacitaciones';
    protected $primaryKey       = 'id_empleado_capacitacion';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id_empleado', 'id_capacitacion', 'asistio', 'aprobo',
        'certificado_url'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules      = [
        'id_empleado' => 'required|integer',
        'id_capacitacion' => 'required|integer',
        'asistio' => 'permit_empty|in_list[0,1]',
        'aprobo' => 'permit_empty|in_list[0,1]'
    ];

    protected $validationMessages = [
        'id_empleado' => [
            'required' => 'El empleado es obligatorio',
            'integer' => 'El empleado debe ser un número entero'
        ],
        'id_capacitacion' => [
            'required' => 'La capacitación es obligatoria',
            'integer' => 'La capacitación debe ser un número entero'
        ],
        'asistio' => [
            'in_list' => 'El valor de asistencia debe ser 0 o 1'
        ],
        'aprobo' => [
            'in_list' => 'El valor de aprobación debe ser 0 o 1'
        ]
    ];

    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    /**
     * Obtiene asignaciones completas con información de empleado y capacitación
     */
    public function getAsignacionesCompletas()
    {
        return $this->db->table('empleados_capacitaciones ec')
            ->select('ec.*, e.nombres as empleado_nombres, e.apellidos as empleado_apellidos, u.cedula, c.nombre as capacitacion_nombre, c.tipo as capacitacion_tipo')
            ->join('empleados e', 'e.id_empleado = ec.id_empleado', 'left')
            ->join('usuarios u', 'u.id_usuario = e.id_usuario', 'left')
            ->join('capacitaciones c', 'c.id_capacitacion = ec.id_capacitacion', 'left')
            ->orderBy('ec.created_at', 'DESC')
            ->get()
            ->getResultArray();
    }

    /**
     * Obtiene asignaciones por empleado
     */
    public function getAsignacionesPorEmpleado($empleadoId)
    {
        return $this->db->table('empleados_capacitaciones ec')
            ->select('ec.*, c.nombre as capacitacion_nombre, c.tipo as capacitacion_tipo, c.fecha_inicio, c.fecha_fin')
            ->join('capacitaciones c', 'c.id_capacitacion = ec.id_capacitacion', 'left')
            ->where('ec.id_empleado', $empleadoId)
            ->orderBy('ec.created_at', 'DESC')
            ->get()
            ->getResultArray();
    }

    /**
     * Obtiene asignaciones por estado
     */
    public function getAsignacionesPorEstado($estado)
    {
        return $this->where('aprobo', $estado == 'COMPLETADA' ? 1 : 0)
            ->orderBy('created_at', 'DESC')
            ->findAll();
    }

    /**
     * Obtiene asignaciones recientes
     */
    public function getAsignacionesRecientes($limite = 10)
    {
        return $this->orderBy('created_at', 'DESC')
            ->limit($limite)
            ->findAll();
    }

    /**
     * Obtiene estadísticas de asignaciones
     */
    public function getEstadisticasAsignaciones()
    {
        $db = $this->db;
        
        $totalAsignaciones = $db->table('empleados_capacitaciones')->countAllResults();
        $asignacionesCompletadas = $db->table('empleados_capacitaciones')->where('aprobo', 1)->countAllResults();
        $asignacionesEnProgreso = $db->table('empleados_capacitaciones')->where('aprobo', 0)->countAllResults();
        
        return [
            'total' => $totalAsignaciones,
            'completadas' => $asignacionesCompletadas,
            'en_progreso' => $asignacionesEnProgreso
        ];
    }

    public function getCapacitacionesPorEmpleado($idEmpleado)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('empleados_capacitaciones ec');
        $builder->select('ec.*, c.nombre, c.tipo, c.fecha_inicio, c.fecha_fin, c.proveedor, c.costo, c.estado as capacitacion_estado');
        $builder->join('capacitaciones c', 'c.id_capacitacion = ec.id_capacitacion');
        $builder->where('ec.id_empleado', $idEmpleado);
        $builder->orderBy('ec.created_at', 'DESC');
        return $builder->get()->getResultArray();
    }
} 