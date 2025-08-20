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
        'id_capacitacion', 'id_empleado', 'estado', 'fecha_inscripcion', 
        'fecha_completado', 'puntaje', 'observaciones', 'archivo_certificado'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules      = [
        'id_capacitacion' => 'required|integer|greater_than[0]',
        'id_empleado' => 'required|integer|greater_than[0]',
        'estado' => 'required|in_list[Inscrito,En Progreso,Completada,Cancelada]',
        'fecha_inscripcion' => 'required|valid_date',
        'fecha_completado' => 'permit_empty|valid_date',
        'puntaje' => 'permit_empty|integer|greater_than_equal_to[0]|less_than_equal_to[100]',
        'observaciones' => 'permit_empty|max_length[1000]'
    ];
    protected $validationMessages   = [
        'id_capacitacion' => [
            'required' => 'El ID de la capacitación es obligatorio',
            'integer' => 'El ID de la capacitación debe ser un número entero',
            'greater_than' => 'El ID de la capacitación debe ser mayor a 0'
        ],
        'id_empleado' => [
            'required' => 'El ID del empleado es obligatorio',
            'integer' => 'El ID del empleado debe ser un número entero',
            'greater_than' => 'El ID del empleado debe ser mayor a 0'
        ],
        'estado' => [
            'required' => 'El estado es obligatorio',
            'in_list' => 'El estado seleccionado no es válido'
        ],
        'fecha_inscripcion' => [
            'required' => 'La fecha de inscripción es obligatoria',
            'valid_date' => 'La fecha de inscripción no es válida'
        ],
        'fecha_completado' => [
            'valid_date' => 'La fecha de completado no es válida'
        ],
        'puntaje' => [
            'integer' => 'El puntaje debe ser un número entero',
            'greater_than_equal_to' => 'El puntaje debe ser mayor o igual a 0',
            'less_than_equal_to' => 'El puntaje no puede exceder 100'
        ],
        'observaciones' => [
            'max_length' => 'Las observaciones no pueden exceder 1000 caracteres'
        ]
    ];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = ['setFechas'];
    protected $beforeUpdate   = ['setFechas'];

    protected function setFechas(array $data)
    {
        if (isset($data['data']['estado']) && $data['data']['estado'] == 'Completada') {
            if (!isset($data['data']['fecha_completado'])) {
                $data['data']['fecha_completado'] = date('Y-m-d H:i:s');
            }
        }
        return $data;
    }

    /**
     * Obtener empleados por capacitación
     */
    public function getEmpleadosPorCapacitacion($idCapacitacion)
    {
        $builder = $this->db->table('empleado_capacitaciones ec');
        $builder->select('ec.*, e.nombres, e.apellidos, e.cedula, e.tipo_empleado, e.departamento, e.tipo_docente');
        $builder->join('empleados e', 'ec.id_empleado = e.id_empleado');
        $builder->where('ec.id_capacitacion', $idCapacitacion);
        $builder->orderBy('e.nombres', 'ASC');
        
        return $builder->get()->getResultArray();
    }

    /**
     * Obtener capacitaciones por empleado
     */
    public function getCapacitacionesPorEmpleado($idEmpleado)
    {
        $builder = $this->db->table('empleado_capacitaciones ec');
        $builder->select('ec.*, c.nombre, c.descripcion, c.tipo, c.proveedor, c.fecha_inicio, c.fecha_fin, c.costo');
        $builder->join('capacitaciones c', 'ec.id_capacitacion = c.id_capacitacion');
        $builder->where('ec.id_empleado', $idEmpleado);
        $builder->orderBy('c.fecha_inicio', 'DESC');
        
        return $builder->get()->getResultArray();
    }

    /**
     * Obtener estadísticas generales
     */
    public function getEstadisticasGenerales()
    {
        $estadisticas = [
            'total_inscripciones' => $this->countAll(),
            'empleados_con_capacitaciones' => 0,
            'total_capacitaciones' => 0,
            'promedio_por_empleado' => 0,
            'por_estado' => [],
            'por_tipo_empleado' => []
        ];

        // Empleados con capacitaciones
        $empleadosConCap = $this->select('COUNT(DISTINCT id_empleado) as total')
                               ->get()
                               ->getRowArray();
        $estadisticas['empleados_con_capacitaciones'] = $empleadosConCap['total'] ?? 0;

        // Total de capacitaciones únicas
        $totalCap = $this->select('COUNT(DISTINCT id_capacitacion) as total')
                         ->get()
                         ->getRowArray();
        $estadisticas['total_capacitaciones'] = $totalCap['total'] ?? 0;

        // Promedio por empleado
        if ($estadisticas['empleados_con_capacitaciones'] > 0) {
            $estadisticas['promedio_por_empleado'] = round(
                $estadisticas['total_inscripciones'] / $estadisticas['empleados_con_capacitaciones'], 
                2
            );
        }

        // Estadísticas por estado
        $porEstado = $this->select('estado, COUNT(*) as total')
                          ->groupBy('estado')
                          ->get()
                          ->getResultArray();
        
        foreach ($porEstado as $estado) {
            $estadisticas['por_estado'][$estado['estado']] = $estado['total'];
        }

        // Estadísticas por tipo de empleado
        $builder = $this->db->table('empleado_capacitaciones ec');
        $builder->select('e.tipo_empleado, COUNT(*) as total');
        $builder->join('empleados e', 'ec.id_empleado = e.id_empleado');
        $builder->groupBy('e.tipo_empleado');
        $porTipo = $builder->get()->getResultArray();
        
        foreach ($porTipo as $tipo) {
            $estadisticas['por_tipo_empleado'][$tipo['tipo_empleado']] = $tipo['total'];
        }

        return $estadisticas;
    }

    /**
     * Obtener estadísticas por empleado
     */
    public function getEstadisticasPorEmpleado($idEmpleado)
    {
        $estadisticas = [
            'total_inscripciones' => 0,
            'completadas' => 0,
            'en_progreso' => 0,
            'canceladas' => 0,
            'promedio_puntaje' => 0,
            'total_horas' => 0
        ];

        // Total de inscripciones
        $total = $this->where('id_empleado', $idEmpleado)->countAllResults();
        $estadisticas['total_inscripciones'] = $total;

        // Por estado
        $porEstado = $this->select('estado, COUNT(*) as total')
                          ->where('id_empleado', $idEmpleado)
                          ->groupBy('estado')
                          ->get()
                          ->getResultArray();
        
        foreach ($porEstado as $estado) {
            switch ($estado['estado']) {
                case 'Completada':
                    $estadisticas['completadas'] = $estado['total'];
                    break;
                case 'En Progreso':
                    $estadisticas['en_progreso'] = $estado['total'];
                    break;
                case 'Cancelada':
                    $estadisticas['canceladas'] = $estado['total'];
                    break;
            }
        }

        // Promedio de puntaje
        $puntaje = $this->select('AVG(puntaje) as promedio')
                        ->where('id_empleado', $idEmpleado)
                        ->where('estado', 'Completada')
                        ->where('puntaje IS NOT NULL')
                        ->get()
                        ->getRowArray();
        $estadisticas['promedio_puntaje'] = round($puntaje['promedio'] ?? 0, 2);

        // Total de horas
        $builder = $this->db->table('empleado_capacitaciones ec');
        $builder->select('SUM(c.duracion_horas) as total_horas');
        $builder->join('capacitaciones c', 'ec.id_capacitacion = c.id_capacitacion');
        $builder->where('ec.id_empleado', $idEmpleado);
        $builder->where('ec.estado', 'Completada');
        $horas = $builder->get()->getRowArray();
        $estadisticas['total_horas'] = $horas['total_horas'] ?? 0;

        return $estadisticas;
    }

    /**
     * Obtener empleados con capacitaciones
     */
    public function getEmpleadosConCapacitaciones()
    {
        $builder = $this->db->table('empleados e');
        $builder->select('e.*, 
                         COUNT(ec.id_empleado_capacitacion) as total_capacitaciones,
                         GROUP_CONCAT(DISTINCT c.nombre ORDER BY c.fecha_inicio DESC SEPARATOR "|") as capacitaciones_recientes');
        $builder->join('empleado_capacitaciones ec', 'e.id_empleado = ec.id_empleado', 'left');
        $builder->join('capacitaciones c', 'ec.id_capacitacion = c.id_capacitacion', 'left');
        $builder->groupBy('e.id_empleado');
        $builder->orderBy('e.nombres', 'ASC');
        
        $resultado = $builder->get()->getResultArray();
        
        // Procesar capacitaciones recientes
        foreach ($resultado as &$empleado) {
            if ($empleado['capacitaciones_recientes']) {
                $empleado['capacitaciones_recientes'] = array_slice(
                    explode('|', $empleado['capacitaciones_recientes']), 
                    0, 
                    3
                );
            } else {
                $empleado['capacitaciones_recientes'] = [];
            }
        }
        
        return $resultado;
    }

    /**
     * Verificar si un empleado ya está inscrito en una capacitación
     */
    public function empleadoInscrito($idEmpleado, $idCapacitacion)
    {
        return $this->where('id_empleado', $idEmpleado)
                    ->where('id_capacitacion', $idCapacitacion)
                    ->countAllResults() > 0;
    }

    /**
     * Inscribir empleado en capacitación
     */
    public function inscribirEmpleado($idEmpleado, $idCapacitacion)
    {
        if ($this->empleadoInscrito($idEmpleado, $idCapacitacion)) {
            return false; // Ya está inscrito
        }

        $datos = [
            'id_empleado' => $idEmpleado,
            'id_capacitacion' => $idCapacitacion,
            'estado' => 'Inscrito',
            'fecha_inscripcion' => date('Y-m-d H:i:s')
        ];

        return $this->insert($datos);
    }

    /**
     * Actualizar estado de capacitación del empleado
     */
    public function actualizarEstado($idEmpleadoCapacitacion, $estado, $puntaje = null, $observaciones = null)
    {
        $datos = ['estado' => $estado];
        
        if ($estado == 'Completada') {
            $datos['fecha_completado'] = date('Y-m-d H:i:s');
        }
        
        if ($puntaje !== null) {
            $datos['puntaje'] = $puntaje;
        }
        
        if ($observaciones !== null) {
            $datos['observaciones'] = $observaciones;
        }

        return $this->update($idEmpleadoCapacitacion, $datos);
    }

    /**
     * Obtener capacitaciones próximas a vencer para un empleado
     */
    public function getCapacitacionesProximasAVencer($idEmpleado, $dias = 7)
    {
        $fechaLimite = date('Y-m-d', strtotime("+{$dias} days"));
        
        $builder = $this->db->table('empleado_capacitaciones ec');
        $builder->select('ec.*, c.nombre, c.fecha_fin');
        $builder->join('capacitaciones c', 'ec.id_capacitacion = c.id_capacitacion');
        $builder->where('ec.id_empleado', $idEmpleado);
        $builder->where('ec.estado', 'Inscrito');
        $builder->where('c.fecha_fin <=', $fechaLimite);
        $builder->where('c.fecha_fin >=', date('Y-m-d'));
        $builder->orderBy('c.fecha_fin', 'ASC');
        
        return $builder->get()->getResultArray();
    }

    /**
     * Obtener reporte de capacitaciones por período
     */
    public function getReportePorPeriodo($fechaInicio, $fechaFin)
    {
        $builder = $this->db->table('empleado_capacitaciones ec');
        $builder->select('ec.*, e.nombres, e.apellidos, e.tipo_empleado, e.departamento, 
                         c.nombre as nombre_capacitacion, c.tipo, c.institucion, c.duracion_horas');
        $builder->join('empleados e', 'ec.id_empleado = e.id_empleado');
        $builder->join('capacitaciones c', 'ec.id_capacitacion = c.id_capacitacion');
        $builder->where('ec.fecha_inscripcion >=', $fechaInicio);
        $builder->where('ec.fecha_inscripcion <=', $fechaFin);
        $builder->orderBy('ec.fecha_inscripcion', 'DESC');
        
        return $builder->get()->getResultArray();
    }
} 