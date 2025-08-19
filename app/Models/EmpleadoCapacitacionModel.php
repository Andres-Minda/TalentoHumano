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
        'id_empleado', 'id_capacitacion', 'fecha_asignacion', 'estado',
        'fecha_completado', 'puntaje', 'observaciones', 'certificado_url'
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
        'fecha_asignacion' => 'required|valid_date',
        'estado' => 'required|in_list[ASIGNADA,EN_PROGRESO,COMPLETADA,CANCELADA]',
        'fecha_completado' => 'permit_empty|valid_date',
        'puntaje' => 'permit_empty|decimal',
        'observaciones' => 'permit_empty|max_length[1000]'
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
        'fecha_asignacion' => [
            'required' => 'La fecha de asignación es obligatoria',
            'valid_date' => 'La fecha de asignación debe ser una fecha válida'
        ],
        'estado' => [
            'required' => 'El estado es obligatorio',
            'in_list' => 'El estado debe ser ASIGNADA, EN_PROGRESO, COMPLETADA o CANCELADA'
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
            ->select('ec.*, e.nombres, e.apellidos, u.cedula, c.nombre as capacitacion_nombre, c.tipo as capacitacion_tipo')
            ->join('empleados e', 'e.id_empleado = ec.id_empleado', 'left')
            ->join('usuarios u', 'u.id_usuario = e.id_usuario', 'left')
            ->join('capacitaciones c', 'c.id_capacitacion = ec.id_capacitacion', 'left')
            ->orderBy('ec.fecha_asignacion', 'DESC')
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
            ->orderBy('ec.fecha_asignacion', 'DESC')
            ->get()
            ->getResultArray();
    }

    /**
     * Obtiene asignaciones por estado
     */
    public function getAsignacionesPorEstado($estado)
    {
        return $this->where('estado', $estado)
            ->orderBy('fecha_asignacion', 'DESC')
            ->findAll();
    }

    /**
     * Obtiene asignaciones recientes
     */
    public function getAsignacionesRecientes($limite = 10)
    {
        return $this->orderBy('fecha_asignacion', 'DESC')
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
        $asignacionesCompletadas = $db->table('empleados_capacitaciones')->where('estado', 'COMPLETADA')->countAllResults();
        $asignacionesEnProgreso = $db->table('empleados_capacitaciones')->where('estado', 'EN_PROGRESO')->countAllResults();
        
        return [
            'total' => $totalAsignaciones,
            'completadas' => $asignacionesCompletadas,
            'en_progreso' => $asignacionesEnProgreso
        ];
    }
} 