<?php

namespace App\Models;

use CodeIgniter\Model;

class CapacitacionEmpleadoModel extends Model
{
    protected $table            = 'capacitaciones_empleados';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'empleado_id', 'nombre_capacitacion', 'institucion', 'fecha_inicio',
        'fecha_fin', 'horas', 'tipo_capacitacion', 'archivo_certificado'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = '';
    protected $deletedField  = '';

    // Validation
    protected $validationRules      = [
        'empleado_id'           => 'required|integer',
        'nombre_capacitacion'   => 'required|min_length[5]|max_length[255]',
        'institucion'           => 'required|min_length[2]|max_length[255]',
        'fecha_inicio'          => 'required|valid_date',
        'fecha_fin'             => 'permit_empty|valid_date',
        'horas'                 => 'permit_empty|integer|greater_than[0]',
        'tipo_capacitacion'     => 'permit_empty|max_length[100]',
        'archivo_certificado'   => 'permit_empty|max_length[255]'
    ];
    
    protected $validationMessages   = [
        'empleado_id' => [
            'required' => 'El empleado es obligatorio',
            'integer' => 'El ID del empleado debe ser un número entero'
        ],
        'nombre_capacitacion' => [
            'required' => 'El nombre de la capacitación es obligatorio',
            'min_length' => 'El nombre debe tener al menos 5 caracteres',
            'max_length' => 'El nombre no puede exceder 255 caracteres'
        ],
        'institucion' => [
            'required' => 'La institución es obligatoria',
            'min_length' => 'La institución debe tener al menos 2 caracteres',
            'max_length' => 'La institución no puede exceder 255 caracteres'
        ],
        'fecha_inicio' => [
            'required' => 'La fecha de inicio es obligatoria',
            'valid_date' => 'La fecha de inicio debe ser una fecha válida'
        ],
        'fecha_fin' => [
            'valid_date' => 'La fecha de fin debe ser una fecha válida'
        ],
        'horas' => [
            'integer' => 'Las horas deben ser un número entero',
            'greater_than' => 'Las horas deben ser mayores a 0'
        ],
        'tipo_capacitacion' => [
            'max_length' => 'El tipo de capacitación no puede exceder 100 caracteres'
        ],
        'archivo_certificado' => [
            'max_length' => 'El nombre del archivo no puede exceder 255 caracteres'
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
     * Obtiene capacitaciones de un empleado específico
     */
    public function getCapacitacionesPorEmpleado($empleadoId)
    {
        return $this->where('empleado_id', $empleadoId)
                    ->orderBy('fecha_inicio', 'DESC')
                    ->findAll();
    }

    /**
     * Obtiene capacitaciones con información del empleado
     */
    public function getCapacitacionesConEmpleado()
    {
        return $this->select('capacitaciones_empleados.*, empleados.nombres, empleados.apellidos, empleados.cedula')
                    ->join('empleados', 'empleados.id = capacitaciones_empleados.empleado_id')
                    ->where('empleados.activo', 1)
                    ->orderBy('empleados.apellidos', 'ASC')
                    ->orderBy('capacitaciones_empleados.fecha_inicio', 'DESC')
                    ->findAll();
    }

    /**
     * Obtiene capacitaciones por tipo
     */
    public function getCapacitacionesPorTipo($tipo)
    {
        return $this->where('tipo_capacitacion', $tipo)
                    ->join('empleados', 'empleados.id = capacitaciones_empleados.empleado_id')
                    ->where('empleados.activo', 1)
                    ->select('capacitaciones_empleados.*, empleados.nombres, empleados.apellidos')
                    ->findAll();
    }

    /**
     * Obtiene capacitaciones por institución
     */
    public function getCapacitacionesPorInstitucion($institucion)
    {
        return $this->like('institucion', $institucion)
                    ->join('empleados', 'empleados.id = capacitaciones_empleados.empleado_id')
                    ->where('empleados.activo', 1)
                    ->select('capacitaciones_empleados.*, empleados.nombres, empleados.apellidos')
                    ->findAll();
    }

    /**
     * Obtiene capacitaciones recientes (últimos 12 meses)
     */
    public function getCapacitacionesRecientes()
    {
        $fechaLimite = date('Y-m-d', strtotime('-12 months'));
        
        return $this->where('fecha_inicio >=', $fechaLimite)
                    ->join('empleados', 'empleados.id = capacitaciones_empleados.empleado_id')
                    ->where('empleados.activo', 1)
                    ->select('capacitaciones_empleados.*, empleados.nombres, empleados.apellidos')
                    ->orderBy('fecha_inicio', 'DESC')
                    ->findAll();
    }

    /**
     * Obtiene estadísticas de capacitaciones
     */
    public function getEstadisticasCapacitaciones()
    {
        $db = \Config\Database::connect();
        
        $query = $db->query("
            SELECT 
                COUNT(*) as total_capacitaciones,
                SUM(horas) as total_horas,
                COUNT(DISTINCT empleado_id) as empleados_capacitados,
                AVG(horas) as promedio_horas
            FROM capacitaciones_empleados ce
            JOIN empleados e ON e.id = ce.empleado_id
            WHERE e.activo = 1
        ");
        
        return $query->getRowArray();
    }

    /**
     * Valida que la fecha de fin sea posterior a la fecha de inicio
     */
    public function validarFechas($fechaInicio, $fechaFin)
    {
        if (empty($fechaFin)) {
            return true; // Fecha fin es opcional
        }
        
        $inicio = new \DateTime($fechaInicio);
        $fin = new \DateTime($fechaFin);
        
        return $fin >= $inicio;
    }

    /**
     * Obtiene capacitaciones por período
     */
    public function getCapacitacionesPorPeriodo($fechaInicio, $fechaFin)
    {
        return $this->where('fecha_inicio >=', $fechaInicio)
                    ->where('fecha_inicio <=', $fechaFin)
                    ->join('empleados', 'empleados.id = capacitaciones_empleados.empleado_id')
                    ->where('empleados.activo', 1)
                    ->select('capacitaciones_empleados.*, empleados.nombres, empleados.apellidos')
                    ->orderBy('fecha_inicio', 'ASC')
                    ->findAll();
    }
}
