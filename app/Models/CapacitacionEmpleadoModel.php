<?php

namespace App\Models;

use CodeIgniter\Model;

class CapacitacionEmpleadoModel extends Model
{
    protected $table            = 'capacitaciones_empleados';
    protected $primaryKey       = 'id_capacitacion_empleado';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields    = [
        'id_empleado', 'nombre_capacitacion', 'institucion', 'fecha_inicio',
        'fecha_fin', 'numero_horas', 'tipo_capacitacion', 'archivo_certificado',
        'estado', 'observaciones', 'fecha_registro'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $beforeInsert = ['setFechaRegistro'];

    protected $validationRules = [
        'id_empleado'           => 'required|integer',
        'nombre_capacitacion'   => 'required|min_length[5]|max_length[300]',
        'institucion'           => 'required|min_length[3]|max_length[200]',
        'fecha_inicio'          => 'required|valid_date',
        'fecha_fin'             => 'required|valid_date',
        'numero_horas'          => 'required|integer|greater_than[0]',
        'tipo_capacitacion'     => 'required|in_list[Obligatoria,Voluntaria,Especialización,Actualización]',
        'estado'                => 'required|in_list[En curso,Completada,Pendiente,Cancelada]',
        'observaciones'         => 'permit_empty|max_length[500]'
    ];

    protected $validationMessages = [
        'id_empleado' => [
            'required' => 'El ID del empleado es obligatorio',
            'integer' => 'El ID del empleado debe ser un número entero'
        ],
        'nombre_capacitacion' => [
            'required' => 'El nombre de la capacitación es obligatorio',
            'min_length' => 'El nombre debe tener al menos 5 caracteres',
            'max_length' => 'El nombre no puede exceder 300 caracteres'
        ],
        'institucion' => [
            'required' => 'La institución es obligatoria',
            'min_length' => 'La institución debe tener al menos 3 caracteres',
            'max_length' => 'La institución no puede exceder 200 caracteres'
        ],
        'fecha_inicio' => [
            'required' => 'La fecha de inicio es obligatoria',
            'valid_date' => 'La fecha de inicio debe ser una fecha válida'
        ],
        'fecha_fin' => [
            'required' => 'La fecha de fin es obligatoria',
            'valid_date' => 'La fecha de fin debe ser una fecha válida'
        ],
        'numero_horas' => [
            'required' => 'El número de horas es obligatorio',
            'integer' => 'El número de horas debe ser un número entero',
            'greater_than' => 'El número de horas debe ser mayor a 0'
        ],
        'tipo_capacitacion' => [
            'required' => 'El tipo de capacitación es obligatorio',
            'in_list' => 'El tipo debe ser: Obligatoria, Voluntaria, Especialización o Actualización'
        ],
        'estado' => [
            'required' => 'El estado es obligatorio',
            'in_list' => 'El estado debe ser: En curso, Completada, Pendiente o Cancelada'
        ]
    ];

    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    /**
     * Establece la fecha de registro antes de insertar
     */
    protected function setFechaRegistro($data)
    {
        if (!isset($data['data']['fecha_registro'])) {
            $data['data']['fecha_registro'] = date('Y-m-d H:i:s');
        }
        return $data;
    }

    /**
     * Obtiene capacitaciones por empleado
     */
    public function getCapacitacionesPorEmpleado($empleadoId)
    {
        return $this->where('id_empleado', $empleadoId)
                    ->orderBy('fecha_inicio', 'DESC')
                    ->findAll();
    }

    /**
     * Obtiene capacitaciones por estado
     */
    public function getCapacitacionesPorEstado($estado)
    {
        return $this->where('estado', $estado)
                    ->orderBy('fecha_inicio', 'DESC')
                    ->findAll();
    }

    /**
     * Obtiene capacitaciones por tipo
     */
    public function getCapacitacionesPorTipo($tipo)
    {
        return $this->where('tipo_capacitacion', $tipo)
                    ->orderBy('fecha_inicio', 'DESC')
                    ->findAll();
    }

    /**
     * Obtiene todas las capacitaciones con información del empleado
     */
    public function getCapacitacionesConEmpleado()
    {
        return $this->db->table('capacitaciones_empleados ce')
                        ->select('ce.*, e.nombres, e.apellidos, e.cedula')
                        ->join('empleados e', 'e.id_empleado = ce.id_empleado', 'left')
                        ->orderBy('ce.fecha_inicio', 'DESC')
                        ->get()
                        ->getResultArray();
    }

    /**
     * Obtiene capacitaciones recientes
     */
    public function getCapacitacionesRecientes($limit = 10)
    {
        return $this->orderBy('created_at', 'DESC')
                    ->limit($limit)
                    ->findAll();
    }

    /**
     * Obtiene capacitaciones por período
     */
    public function getCapacitacionesPorPeriodo($fechaInicio, $fechaFin)
    {
        return $this->where('fecha_inicio >=', $fechaInicio)
                    ->where('fecha_fin <=', $fechaFin)
                    ->orderBy('fecha_inicio', 'ASC')
                    ->findAll();
    }

    /**
     * Obtiene estadísticas de capacitaciones
     */
    public function getEstadisticasCapacitaciones()
    {
        $total = $this->countAll();
        $porEstado = $this->db->table('capacitaciones_empleados')
                              ->select('estado, COUNT(*) as total')
                              ->groupBy('estado')
                              ->get()
                              ->getResultArray();
        
        $porTipo = $this->db->table('capacitaciones_empleados')
                            ->select('tipo_capacitacion, COUNT(*) as total')
                            ->groupBy('tipo_capacitacion')
                            ->get()
                            ->getResultArray();

        $totalHoras = $this->db->table('capacitaciones_empleados')
                               ->select('SUM(numero_horas) as total_horas')
                               ->where('estado', 'Completada')
                               ->get()
                               ->getRow();

        return [
            'total' => $total,
            'por_estado' => $porEstado,
            'por_tipo' => $porTipo,
            'total_horas' => $totalHoras ? $totalHoras->total_horas : 0
        ];
    }

    /**
     * Obtiene capacitaciones pendientes de un empleado
     */
    public function getCapacitacionesPendientes($empleadoId)
    {
        return $this->where('id_empleado', $empleadoId)
                    ->whereIn('estado', ['En curso', 'Pendiente'])
                    ->orderBy('fecha_inicio', 'ASC')
                    ->findAll();
    }

    /**
     * Obtiene capacitaciones completadas de un empleado
     */
    public function getCapacitacionesCompletadas($empleadoId)
    {
        return $this->where('id_empleado', $empleadoId)
                    ->where('estado', 'Completada')
                    ->orderBy('fecha_fin', 'DESC')
                    ->findAll();
    }

    /**
     * Busca capacitaciones por criterios
     */
    public function buscarCapacitaciones($criterios = [])
    {
        $builder = $this->db->table('capacitaciones_empleados ce')
                            ->select('ce.*, e.nombres, e.apellidos, e.cedula')
                            ->join('empleados e', 'e.id_empleado = ce.id_empleado', 'left');

        if (!empty($criterios['tipo_capacitacion'])) {
            $builder->where('ce.tipo_capacitacion', $criterios['tipo_capacitacion']);
        }

        if (!empty($criterios['estado'])) {
            $builder->where('ce.estado', $criterios['estado']);
        }

        if (!empty($criterios['institucion'])) {
            $builder->like('ce.institucion', $criterios['institucion']);
        }

        if (!empty($criterios['fecha_desde'])) {
            $builder->where('ce.fecha_inicio >=', $criterios['fecha_desde']);
        }

        if (!empty($criterios['fecha_hasta'])) {
            $builder->where('ce.fecha_fin <=', $criterios['fecha_hasta']);
        }

        if (!empty($criterios['empleado'])) {
            $builder->like('e.nombres', $criterios['empleado'])
                    ->orLike('e.apellidos', $criterios['empleado'])
                    ->orLike('e.cedula', $criterios['empleado']);
        }

        return $builder->orderBy('ce.fecha_inicio', 'DESC')
                      ->get()
                      ->getResultArray();
    }

    /**
     * Obtiene el total de horas de capacitación de un empleado
     */
    public function getTotalHorasCapacitacion($empleadoId)
    {
        $result = $this->select('SUM(numero_horas) as total_horas')
                       ->where('id_empleado', $empleadoId)
                       ->where('estado', 'Completada')
                       ->first();
        
        return $result ? (int)$result['total_horas'] : 0;
    }

    /**
     * Obtiene capacitaciones por institución
     */
    public function getCapacitacionesPorInstitucion($institucion)
    {
        return $this->where('institucion', $institucion)
                    ->orderBy('fecha_inicio', 'DESC')
                    ->findAll();
    }

    /**
     * Verifica si un empleado tiene capacitaciones en curso
     */
    public function tieneCapacitacionesEnCurso($empleadoId)
    {
        return $this->where('id_empleado', $empleadoId)
                    ->where('estado', 'En curso')
                    ->countAllResults() > 0;
    }

    /**
     * Obtiene capacitaciones próximas a vencer
     */
    public function getCapacitacionesProximasAVencer($dias = 30)
    {
        $fechaLimite = date('Y-m-d', strtotime("+{$dias} days"));
        
        return $this->where('fecha_fin <=', $fechaLimite)
                    ->where('estado', 'En curso')
                    ->orderBy('fecha_fin', 'ASC')
                    ->findAll();
    }
}
