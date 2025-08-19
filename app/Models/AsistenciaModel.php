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
            ->select('a.*, e.nombres, e.apellidos, u_emp.cedula, u.nombres as registrador_nombres')
            ->join('empleados e', 'e.id_empleado = a.id_empleado', 'left')
            ->join('usuarios u_emp', 'u_emp.id_usuario = e.id_usuario', 'left')
            ->join('usuarios u', 'u.id_usuario = a.registrado_por', 'left')
            ->orderBy('a.fecha_asistencia', 'DESC')
            ->orderBy('a.hora_entrada', 'DESC')
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
            $query->where('fecha_asistencia >=', $fechaInicio);
        }
        
        if ($fechaFin) {
            $query->where('fecha_asistencia <=', $fechaFin);
        }
        
        return $query->orderBy('fecha_asistencia', 'DESC')
            ->findAll();
    }

    /**
     * Obtiene asistencias por fecha
     */
    public function getAsistenciasPorFecha($fecha)
    {
        return $this->where('fecha_asistencia', $fecha)
            ->orderBy('hora_entrada', 'ASC')
            ->findAll();
    }

    /**
     * Obtiene asistencias por estado
     */
    public function getAsistenciasPorEstado($estado)
    {
        return $this->where('estado', $estado)
            ->orderBy('fecha_asistencia', 'DESC')
            ->findAll();
    }

    /**
     * Obtiene asistencias por rango de fechas
     */
    public function getAsistenciasPorRango($fechaInicio, $fechaFin)
    {
        return $this->where('fecha_asistencia >=', $fechaInicio)
            ->where('fecha_asistencia <=', $fechaFin)
            ->orderBy('fecha_asistencia', 'ASC')
            ->findAll();
    }

    /**
     * Obtiene estadísticas de asistencias
     */
    public function getEstadisticasAsistencias($fechaInicio = null, $fechaFin = null)
    {
        $query = $this->db->table('asistencias');
        
        if ($fechaInicio) {
            $query->where('fecha_asistencia >=', $fechaInicio);
        }
        
        if ($fechaFin) {
            $query->where('fecha_asistencia <=', $fechaFin);
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
} 