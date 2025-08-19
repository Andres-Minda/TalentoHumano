<?php

namespace App\Models;

use CodeIgniter\Model;

class EvaluacionModel extends Model
{
    protected $table            = 'evaluaciones';
    protected $primaryKey       = 'id_evaluacion';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id_empleado', 'id_periodo_academico', 'tipo_evaluacion', 'fecha_inicio',
        'fecha_fin', 'estado', 'puntaje_total', 'observaciones'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules      = [
        'id_empleado' => 'required|integer',
        'id_periodo_academico' => 'required|integer',
        'tipo_evaluacion' => 'required|in_list[AUTOEVALUACION,COORDINADOR,PARES,ESTUDIANTES,SUPERVISOR]',
        'fecha_inicio' => 'required|valid_date',
        'fecha_fin' => 'required|valid_date',
        'estado' => 'required|in_list[PENDIENTE,EN_PROGRESO,COMPLETADA,CANCELADA]',
        'puntaje_total' => 'permit_empty|decimal',
        'observaciones' => 'permit_empty|max_length[1000]'
    ];

    protected $validationMessages = [
        'id_empleado' => [
            'required' => 'El empleado es obligatorio',
            'integer' => 'El empleado debe ser un número entero'
        ],
        'id_periodo_academico' => [
            'required' => 'El periodo académico es obligatorio',
            'integer' => 'El periodo académico debe ser un número entero'
        ],
        'tipo_evaluacion' => [
            'required' => 'El tipo de evaluación es obligatorio',
            'in_list' => 'El tipo debe ser AUTOEVALUACION, COORDINADOR, PARES, ESTUDIANTES o SUPERVISOR'
        ],
        'fecha_inicio' => [
            'required' => 'La fecha de inicio es obligatoria',
            'valid_date' => 'La fecha de inicio debe ser una fecha válida'
        ],
        'fecha_fin' => [
            'required' => 'La fecha de fin es obligatoria',
            'valid_date' => 'La fecha de fin debe ser una fecha válida'
        ],
        'estado' => [
            'required' => 'El estado es obligatorio',
            'in_list' => 'El estado debe ser PENDIENTE, EN_PROGRESO, COMPLETADA o CANCELADA'
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
     * Obtiene evaluaciones completas con información de empleado
     */
    public function getEvaluacionesCompletas()
    {
        return $this->db->table('evaluaciones e')
            ->select('e.*, emp.nombres, emp.apellidos, u.cedula, pa.nombre as periodo_nombre')
            ->join('empleados emp', 'emp.id_empleado = e.id_empleado', 'left')
            ->join('usuarios u', 'u.id_usuario = emp.id_usuario', 'left')
            ->join('periodos_academicos pa', 'pa.id_periodo_academico = e.id_periodo_academico', 'left')
            ->orderBy('e.fecha_inicio', 'DESC')
            ->get()
            ->getResultArray();
    }

    /**
     * Obtiene evaluaciones por empleado
     */
    public function getEvaluacionesPorEmpleado($empleadoId)
    {
        return $this->where('id_empleado', $empleadoId)
            ->orderBy('fecha_inicio', 'DESC')
            ->findAll();
    }

    /**
     * Obtiene evaluaciones por tipo
     */
    public function getEvaluacionesPorTipo($tipo)
    {
        return $this->where('tipo_evaluacion', $tipo)
            ->orderBy('fecha_inicio', 'DESC')
            ->findAll();
    }

    /**
     * Obtiene evaluaciones por estado
     */
    public function getEvaluacionesPorEstado($estado)
    {
        return $this->where('estado', $estado)
            ->orderBy('fecha_inicio', 'DESC')
            ->findAll();
    }

    /**
     * Obtiene evaluaciones pendientes
     */
    public function getEvaluacionesPendientes()
    {
        return $this->where('estado', 'PENDIENTE')
            ->where('fecha_fin >=', date('Y-m-d'))
            ->orderBy('fecha_fin', 'ASC')
            ->findAll();
    }

    /**
     * Obtiene evaluaciones por periodo académico
     */
    public function getEvaluacionesPorPeriodo($periodoId)
    {
        return $this->where('id_periodo_academico', $periodoId)
            ->orderBy('fecha_inicio', 'DESC')
            ->findAll();
    }

    /**
     * Obtiene estadísticas de evaluaciones
     */
    public function getEstadisticasEvaluaciones()
    {
        $db = $this->db;
        
        $totalEvaluaciones = $db->table('evaluaciones')->countAllResults();
        $evaluacionesPendientes = $db->table('evaluaciones')->where('estado', 'PENDIENTE')->countAllResults();
        $evaluacionesCompletadas = $db->table('evaluaciones')->where('estado', 'COMPLETADA')->countAllResults();
        
        return [
            'total' => $totalEvaluaciones,
            'pendientes' => $evaluacionesPendientes,
            'completadas' => $evaluacionesCompletadas
        ];
    }
} 