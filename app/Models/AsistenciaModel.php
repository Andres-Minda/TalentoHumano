<?php

namespace App\Models;

use CodeIgniter\Model;

class AsistenciaModel extends Model
{
    protected $table            = 'asistencias';
    protected $primaryKey       = 'id_asistencia';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id_empleado', 'fecha_asistencia', 'hora_entrada', 'hora_salida',
        'estado', 'observaciones', 'registrado_por'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules      = [
        'id_empleado' => 'required|integer',
        'fecha_asistencia' => 'required|valid_date',
        'hora_entrada' => 'permit_empty|valid_date',
        'hora_salida' => 'permit_empty|valid_date',
        'estado' => 'required|in_list[PRESENTE,AUSENTE,TARDANZA,PARCIAL]',
        'observaciones' => 'permit_empty|max_length[1000]',
        'registrado_por' => 'required|integer'
    ];

    protected $validationMessages = [
        'id_empleado' => [
            'required' => 'El empleado es obligatorio',
            'integer' => 'El empleado debe ser un número entero'
        ],
        'fecha_asistencia' => [
            'required' => 'La fecha de asistencia es obligatoria',
            'valid_date' => 'La fecha de asistencia debe ser una fecha válida'
        ],
        'estado' => [
            'required' => 'El estado es obligatorio',
            'in_list' => 'El estado debe ser PRESENTE, AUSENTE, TARDANZA o PARCIAL'
        ],
        'registrado_por' => [
            'required' => 'El usuario que registra es obligatorio',
            'integer' => 'El usuario debe ser un número entero'
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
     * Obtiene asistencias completas con información de empleado
     */
    public function getAsistenciasCompletas()
    {
        return $this->db->table('asistencias a')
            ->select('a.*, e.nombres as empleado_nombre, e.apellidos as empleado_apellidos')
            ->join('empleados e', 'e.id_empleado = a.id_empleado', 'left')
            ->orderBy('a.fecha', 'DESC')
            ->get()
            ->getResultArray();
    }

    /**
     * Obtiene asistencias por empleado
     */
    public function getAsistenciasPorEmpleado($empleadoId, $fechaInicio = null, $fechaFin = null)
    {
        $query = $this->where('id_empleado', $empleadoId);
        
        if ($fechaInicio) {
            $query->where('fecha >=', $fechaInicio);
        }
        
        if ($fechaFin) {
            $query->where('fecha <=', $fechaFin);
        }
        
        return $query->orderBy('fecha', 'DESC')
            ->findAll();
    }

    /**
     * Obtiene asistencias por fecha
     */
    public function getAsistenciasPorFecha($fecha)
    {
        return $this->where('fecha', $fecha)
            ->orderBy('hora_entrada', 'ASC')
            ->findAll();
    }

    /**
     * Obtiene asistencias por estado
     */
    public function getAsistenciasPorEstado($estado)
    {
        return $this->where('estado', $estado)
            ->orderBy('fecha', 'DESC')
            ->findAll();
    }

    /**
     * Obtiene asistencias por rango de fechas
     */
    public function getAsistenciasPorRango($fechaInicio, $fechaFin)
    {
        return $this->where('fecha >=', $fechaInicio)
            ->where('fecha <=', $fechaFin)
            ->orderBy('fecha', 'ASC')
            ->findAll();
    }

    /**
     * Obtiene estadísticas de asistencias
     */
    public function getEstadisticasAsistencias($fechaInicio = null, $fechaFin = null)
    {
        $query = $this->db->table('asistencias');
        
        if ($fechaInicio) {
            $query->where('fecha >=', $fechaInicio);
        }
        
        if ($fechaFin) {
            $query->where('fecha <=', $fechaFin);
        }
        
        $totalAsistencias = $query->countAllResults();
        $asistenciasPresente = $query->where('estado', 'PRESENTE')->countAllResults();
        $asistenciasAusente = $query->where('estado', 'AUSENTE')->countAllResults();
        $asistenciasTardanza = $query->where('estado', 'TARDANZA')->countAllResults();
        
        return [
            'total' => $totalAsistencias,
            'presente' => $asistenciasPresente,
            'ausente' => $asistenciasAusente,
            'tardanza' => $asistenciasTardanza
        ];
    }

    /**
     * Obtiene asistencias por mes
     */
    public function getAsistenciasPorMes($mes, $anio)
    {
        return $this->db->table('asistencias a')
            ->select('a.*, e.nombres as empleado_nombre, e.apellidos as empleado_apellidos')
            ->join('empleados e', 'e.id_empleado = a.id_empleado', 'left')
            ->where('MONTH(a.fecha)', $mes)
            ->where('YEAR(a.fecha)', $anio)
            ->orderBy('a.fecha', 'ASC')
            ->get()
            ->getResultArray();
    }
} 