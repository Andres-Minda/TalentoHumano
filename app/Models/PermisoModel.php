<?php

namespace App\Models;

use CodeIgniter\Model;

class PermisoModel extends Model
{
    protected $table            = 'permisos';
    protected $primaryKey       = 'id_permiso';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id_empleado', 'tipo_permiso', 'fecha_inicio', 'fecha_fin', 'hora_inicio',
        'hora_fin', 'motivo', 'estado', 'aprobado_por', 'fecha_aprobacion', 'observaciones'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules      = [
        'id_empleado' => 'required|integer',
        'tipo_permiso' => 'required|in_list[VACACIONES,ENFERMEDAD,PERSONAL,MATERNIDAD,PATERNIDAD,OTROS]',
        'fecha_inicio' => 'required|valid_date',
        'fecha_fin' => 'required|valid_date',
        'hora_inicio' => 'permit_empty|valid_date',
        'hora_fin' => 'permit_empty|valid_date',
        'motivo' => 'required|max_length[500]',
        'estado' => 'required|in_list[PENDIENTE,APROBADO,RECHAZADO,CANCELADO]',
        'aprobado_por' => 'permit_empty|integer',
        'observaciones' => 'permit_empty|max_length[1000]'
    ];

    protected $validationMessages = [
        'id_empleado' => [
            'required' => 'El empleado es obligatorio',
            'integer' => 'El empleado debe ser un número entero'
        ],
        'tipo_permiso' => [
            'required' => 'El tipo de permiso es obligatorio',
            'in_list' => 'El tipo debe ser VACACIONES, ENFERMEDAD, PERSONAL, MATERNIDAD, PATERNIDAD u OTROS'
        ],
        'fecha_inicio' => [
            'required' => 'La fecha de inicio es obligatoria',
            'valid_date' => 'La fecha de inicio debe ser una fecha válida'
        ],
        'fecha_fin' => [
            'required' => 'La fecha de fin es obligatoria',
            'valid_date' => 'La fecha de fin debe ser una fecha válida'
        ],
        'motivo' => [
            'required' => 'El motivo es obligatorio',
            'max_length' => 'El motivo no puede exceder 500 caracteres'
        ],
        'estado' => [
            'required' => 'El estado es obligatorio',
            'in_list' => 'El estado debe ser PENDIENTE, APROBADO, RECHAZADO o CANCELADO'
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
     * Obtiene permisos completos con información de empleado
     */
    public function getPermisosCompletos()
    {
        return $this->db->table('permisos p')
            ->select('p.*, e.nombres, e.apellidos, u_emp.cedula, u.nombres as aprobador_nombres')
            ->join('empleados e', 'e.id_empleado = p.id_empleado', 'left')
            ->join('usuarios u_emp', 'u_emp.id_usuario = e.id_usuario', 'left')
            ->join('usuarios u', 'u.id_usuario = p.aprobado_por', 'left')
            ->orderBy('p.fecha_inicio', 'DESC')
            ->get()
            ->getResultArray();
    }

    /**
     * Obtiene permisos por empleado
     */
    public function getPermisosPorEmpleado($empleadoId)
    {
        return $this->where('id_empleado', $empleadoId)
            ->orderBy('fecha_inicio', 'DESC')
            ->findAll();
    }

    /**
     * Obtiene permisos por estado
     */
    public function getPermisosPorEstado($estado)
    {
        return $this->where('estado', $estado)
            ->orderBy('fecha_inicio', 'DESC')
            ->findAll();
    }

    /**
     * Obtiene permisos por tipo
     */
    public function getPermisosPorTipo($tipo)
    {
        return $this->where('tipo_permiso', $tipo)
            ->orderBy('fecha_inicio', 'DESC')
            ->findAll();
    }

    /**
     * Obtiene permisos pendientes
     */
    public function getPermisosPendientes()
    {
        return $this->where('estado', 'PENDIENTE')
            ->orderBy('fecha_inicio', 'ASC')
            ->findAll();
    }

    /**
     * Obtiene permisos por rango de fechas
     */
    public function getPermisosPorRango($fechaInicio, $fechaFin)
    {
        return $this->where('fecha_inicio >=', $fechaInicio)
            ->where('fecha_inicio <=', $fechaFin)
            ->orderBy('fecha_inicio', 'ASC')
            ->findAll();
    }

    /**
     * Obtiene estadísticas de permisos
     */
    public function getEstadisticasPermisos()
    {
        $db = $this->db;
        
        $totalPermisos = $db->table('permisos')->countAllResults();
        $permisosPendientes = $db->table('permisos')->where('estado', 'PENDIENTE')->countAllResults();
        $permisosAprobados = $db->table('permisos')->where('estado', 'APROBADO')->countAllResults();
        $permisosRechazados = $db->table('permisos')->where('estado', 'RECHAZADO')->countAllResults();
        
        return [
            'total' => $totalPermisos,
            'pendientes' => $permisosPendientes,
            'aprobados' => $permisosAprobados,
            'rechazados' => $permisosRechazados
        ];
    }

    public function getPermisosPorEmpleado($idEmpleado)
    {
        return $this->where('id_empleado', $idEmpleado)
                    ->orderBy('fecha_inicio', 'DESC')
                    ->findAll();
    }
} 