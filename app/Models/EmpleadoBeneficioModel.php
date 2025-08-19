<?php

namespace App\Models;

use CodeIgniter\Model;

class EmpleadoBeneficioModel extends Model
{
    protected $table            = 'empleados_beneficios';
    protected $primaryKey       = 'id_empleado_beneficio';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id_empleado', 'id_beneficio', 'fecha_asignacion', 'fecha_vencimiento',
        'estado', 'observaciones', 'asignado_por'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules      = [
        'id_empleado' => 'required|integer',
        'id_beneficio' => 'required|integer',
        'fecha_asignacion' => 'required|valid_date',
        'fecha_vencimiento' => 'permit_empty|valid_date',
        'estado' => 'required|in_list[ACTIVO,INACTIVO,SUSPENDIDO,VENCIDO]',
        'observaciones' => 'permit_empty|max_length[1000]',
        'asignado_por' => 'required|integer'
    ];

    protected $validationMessages = [
        'id_empleado' => [
            'required' => 'El empleado es obligatorio',
            'integer' => 'El empleado debe ser un número entero'
        ],
        'id_beneficio' => [
            'required' => 'El beneficio es obligatorio',
            'integer' => 'El beneficio debe ser un número entero'
        ],
        'fecha_asignacion' => [
            'required' => 'La fecha de asignación es obligatoria',
            'valid_date' => 'La fecha de asignación debe ser una fecha válida'
        ],
        'estado' => [
            'required' => 'El estado es obligatorio',
            'in_list' => 'El estado debe ser ACTIVO, INACTIVO, SUSPENDIDO o VENCIDO'
        ],
        'asignado_por' => [
            'required' => 'El usuario que asigna es obligatorio',
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
     * Obtiene asignaciones completas con información de empleado y beneficio
     */
    public function getAsignacionesCompletas()
    {
        return $this->db->table('empleados_beneficios eb')
            ->select('eb.*, e.nombres, e.apellidos, u_emp.cedula, b.nombre as beneficio_nombre, b.tipo_beneficio, u.nombres as asignador_nombres')
            ->join('empleados e', 'e.id_empleado = eb.id_empleado', 'left')
            ->join('usuarios u_emp', 'u_emp.id_usuario = e.id_usuario', 'left')
            ->join('beneficios b', 'b.id_beneficio = eb.id_beneficio', 'left')
            ->join('usuarios u', 'u.id_usuario = eb.asignado_por', 'left')
            ->orderBy('eb.fecha_asignacion', 'DESC')
            ->get()
            ->getResultArray();
    }

    /**
     * Obtiene asignaciones por empleado
     */
    public function getAsignacionesPorEmpleado($empleadoId)
    {
        return $this->db->table('empleados_beneficios eb')
            ->select('eb.*, b.nombre as beneficio_nombre, b.tipo_beneficio, b.descripcion as beneficio_descripcion')
            ->join('beneficios b', 'b.id_beneficio = eb.id_beneficio', 'left')
            ->where('eb.id_empleado', $empleadoId)
            ->orderBy('eb.fecha_asignacion', 'DESC')
            ->get()
            ->getResultArray();
    }

    /**
     * Obtiene asignaciones por beneficio
     */
    public function getAsignacionesPorBeneficio($beneficioId)
    {
        return $this->db->table('empleados_beneficios eb')
            ->select('eb.*, e.nombres, e.apellidos, e.cedula')
            ->join('empleados e', 'e.id_empleado = eb.id_empleado', 'left')
            ->where('eb.id_beneficio', $beneficioId)
            ->orderBy('eb.fecha_asignacion', 'DESC')
            ->get()
            ->getResultArray();
    }

    /**
     * Obtiene asignaciones activas
     */
    public function getAsignacionesActivas()
    {
        return $this->where('estado', 'ACTIVO')
            ->where('(fecha_vencimiento IS NULL OR fecha_vencimiento >=)', date('Y-m-d'))
            ->orderBy('fecha_asignacion', 'DESC')
            ->findAll();
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
     * Obtiene estadísticas de asignaciones de beneficios
     */
    public function getEstadisticasAsignaciones()
    {
        $db = $this->db;
        
        $totalAsignaciones = $db->table('empleados_beneficios')->countAllResults();
        $asignacionesActivas = $db->table('empleados_beneficios')->where('estado', 'ACTIVO')->countAllResults();
        $asignacionesVencidas = $db->table('empleados_beneficios')->where('estado', 'VENCIDO')->countAllResults();
        
        return [
            'total' => $totalAsignaciones,
            'activas' => $asignacionesActivas,
            'vencidas' => $asignacionesVencidas
        ];
    }

    public function getBeneficiosPorEmpleado($idEmpleado)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('empleados_beneficios eb');
        $builder->select('eb.*, b.nombre as nombre_beneficio, b.descripcion as descripcion_beneficio, b.tipo as tipo_beneficio');
        $builder->join('beneficios b', 'b.id_beneficio = eb.id_beneficio');
        $builder->where('eb.id_empleado', $idEmpleado);
        $builder->orderBy('eb.fecha_asignacion', 'DESC');
        return $builder->get()->getResultArray();
    }
} 