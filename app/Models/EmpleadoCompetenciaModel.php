<?php

namespace App\Models;

use CodeIgniter\Model;

class EmpleadoCompetenciaModel extends Model
{
    protected $table            = 'empleados_competencias';
    protected $primaryKey       = 'id_empleado_competencia';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id_empleado', 'id_competencia', 'nivel_actual', 'nivel_objetivo',
        'fecha_evaluacion', 'evaluador', 'observaciones', 'estado'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules      = [
        'id_empleado' => 'required|integer',
        'id_competencia' => 'required|integer',
        'nivel_actual' => 'required|in_list[BASICO,INTERMEDIO,AVANZADO,EXPERTE]',
        'nivel_objetivo' => 'required|in_list[BASICO,INTERMEDIO,AVANZADO,EXPERTE]',
        'fecha_evaluacion' => 'required|valid_date',
        'evaluador' => 'required|integer',
        'observaciones' => 'permit_empty|max_length[1000]',
        'estado' => 'required|in_list[ACTIVA,INACTIVA,COMPLETADA]'
    ];

    protected $validationMessages = [
        'id_empleado' => [
            'required' => 'El empleado es obligatorio',
            'integer' => 'El empleado debe ser un número entero'
        ],
        'id_competencia' => [
            'required' => 'La competencia es obligatoria',
            'integer' => 'La competencia debe ser un número entero'
        ],
        'nivel_actual' => [
            'required' => 'El nivel actual es obligatorio',
            'in_list' => 'El nivel debe ser BASICO, INTERMEDIO, AVANZADO o EXPERTE'
        ],
        'nivel_objetivo' => [
            'required' => 'El nivel objetivo es obligatorio',
            'in_list' => 'El nivel debe ser BASICO, INTERMEDIO, AVANZADO o EXPERTE'
        ],
        'fecha_evaluacion' => [
            'required' => 'La fecha de evaluación es obligatoria',
            'valid_date' => 'La fecha de evaluación debe ser una fecha válida'
        ],
        'evaluador' => [
            'required' => 'El evaluador es obligatorio',
            'integer' => 'El evaluador debe ser un número entero'
        ],
        'estado' => [
            'required' => 'El estado es obligatorio',
            'in_list' => 'El estado debe ser ACTIVA, INACTIVA o COMPLETADA'
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
     * Obtiene evaluaciones completas con información de empleado y competencia
     */
    public function getEvaluacionesCompletas()
    {
        return $this->db->table('empleados_competencias ec')
            ->select('ec.*, e.nombres, e.apellidos, u.cedula, c.nombre as competencia_nombre, c.categoria as competencia_categoria')
            ->join('empleados e', 'e.id_empleado = ec.id_empleado', 'left')
            ->join('usuarios u', 'u.id_usuario = e.id_usuario', 'left')
            ->join('competencias c', 'c.id_competencia = ec.id_competencia', 'left')
            ->orderBy('ec.fecha_evaluacion', 'DESC')
            ->get()
            ->getResultArray();
    }

    /**
     * Obtiene evaluaciones por empleado
     */
    public function getEvaluacionesPorEmpleado($empleadoId)
    {
        return $this->db->table('empleados_competencias ec')
            ->select('ec.*, c.nombre as competencia_nombre, c.categoria as competencia_categoria, c.descripcion as competencia_descripcion')
            ->join('competencias c', 'c.id_competencia = ec.id_competencia', 'left')
            ->where('ec.id_empleado', $empleadoId)
            ->orderBy('ec.fecha_evaluacion', 'DESC')
            ->get()
            ->getResultArray();
    }

    /**
     * Obtiene evaluaciones por competencia
     */
    public function getEvaluacionesPorCompetencia($competenciaId)
    {
        return $this->db->table('empleados_competencias ec')
            ->select('ec.*, e.nombres, e.apellidos, e.cedula')
            ->join('empleados e', 'e.id_empleado = ec.id_empleado', 'left')
            ->where('ec.id_competencia', $competenciaId)
            ->orderBy('ec.fecha_evaluacion', 'DESC')
            ->get()
            ->getResultArray();
    }

    /**
     * Obtiene evaluaciones por nivel
     */
    public function getEvaluacionesPorNivel($nivel)
    {
        return $this->where('nivel_actual', $nivel)
            ->orderBy('fecha_evaluacion', 'DESC')
            ->findAll();
    }

    /**
     * Obtiene estadísticas de evaluaciones de competencias
     */
    public function getEstadisticasEvaluaciones()
    {
        $db = $this->db;
        
        $totalEvaluaciones = $db->table('empleados_competencias')->countAllResults();
        $evaluacionesActivas = $db->table('empleados_competencias')->where('estado', 'ACTIVA')->countAllResults();
        $evaluacionesCompletadas = $db->table('empleados_competencias')->where('estado', 'COMPLETADA')->countAllResults();
        
        return [
            'total' => $totalEvaluaciones,
            'activas' => $evaluacionesActivas,
            'completadas' => $evaluacionesCompletadas
        ];
    }
} 