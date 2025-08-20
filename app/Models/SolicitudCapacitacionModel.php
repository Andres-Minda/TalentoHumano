<?php

namespace App\Models;

use CodeIgniter\Model;

class SolicitudCapacitacionModel extends Model
{
    protected $table            = 'solicitudes';
    protected $primaryKey       = 'id_solicitud';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields = [
        'id_empleado',
        'tipo_solicitud',
        'titulo',
        'descripcion',
        'fecha_solicitud',
        'fecha_resolucion',
        'estado',
        'resuelto_por',
        'comentarios_resolucion',
        'activo'
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'fecha_solicitud';
    protected $updatedField  = 'updated_at';

    protected $validationRules = [
        'id_empleado'       => 'required|integer',
        'tipo_solicitud'    => 'required|in_list[Permiso,Capacitación,Beneficio,Cambio de horario,Otro]',
        'titulo'            => 'required|min_length[5]|max_length[200]',
        'descripcion'       => 'required|min_length[10]|max_length[1000]',
        'estado'            => 'permit_empty|in_list[Pendiente,En revisión,Aprobada,Rechazada,Cancelada]'
    ];

    protected $validationMessages = [
        'id_empleado' => [
            'required' => 'El empleado es obligatorio',
            'integer' => 'El ID del empleado debe ser un número entero'
        ],
        'tipo_solicitud' => [
            'required' => 'El tipo de solicitud es obligatorio',
            'in_list' => 'El tipo de solicitud debe ser válido'
        ],
        'titulo' => [
            'required' => 'El título es obligatorio',
            'min_length' => 'El título debe tener al menos 5 caracteres',
            'max_length' => 'El título no puede exceder 200 caracteres'
        ],
        'descripcion' => [
            'required' => 'La descripción es obligatoria',
            'min_length' => 'La descripción debe tener al menos 10 caracteres',
            'max_length' => 'La descripción no puede exceder 1000 caracteres'
        ],
        'estado' => [
            'in_list' => 'El estado debe ser válido'
        ]
    ];

    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $beforeInsert = ['setCreadoPor'];
    protected $beforeUpdate = ['setModificadoPor'];

    protected function setCreadoPor(array $data)
    {
        if (!isset($data['data']['creado_por'])) {
            $data['data']['creado_por'] = session()->get('id_usuario') ?? 1;
        }
        return $data;
    }

    protected function setModificadoPor(array $data)
    {
        $data['data']['fecha_modificacion'] = date('Y-m-d H:i:s');
        return $data;
    }

    /**
     * Obtener solicitudes completas con información del empleado
     */
    public function getSolicitudesCompletas()
    {
        return $this->select('
                sc.*,
                e.nombre as nombre_empleado,
                e.apellido as apellido_empleado,
                e.cedula,
                d.nombre as nombre_departamento,
                p.nombre as nombre_puesto
            ')
            ->join('empleados e', 'e.id_empleado = sc.id_empleado')
            ->join('departamentos d', 'd.id_departamento = e.id_departamento', 'left')
            ->join('puestos_trabajo p', 'p.id_puesto = e.id_puesto', 'left')
            ->orderBy('sc.fecha_creacion', 'DESC')
            ->findAll();
    }

    /**
     * Obtener solicitudes por empleado
     */
    public function getSolicitudesPorEmpleado($idEmpleado)
    {
        return $this->select('
                sc.*,
                e.nombre as nombre_empleado,
                e.apellido as apellido_empleado,
                d.nombre as nombre_departamento
            ')
            ->join('empleados e', 'e.id_empleado = sc.id_empleado')
            ->join('departamentos d', 'd.id_departamento = e.id_departamento', 'left')
            ->where('sc.id_empleado', $idEmpleado)
            ->orderBy('sc.fecha_creacion', 'DESC')
            ->findAll();
    }

    /**
     * Obtener solicitud completa por ID
     */
    public function getSolicitudCompleta($idSolicitud)
    {
        return $this->select('
                sc.*,
                e.nombre as nombre_empleado,
                e.apellido as apellido_empleado,
                e.cedula,
                e.email,
                d.nombre as nombre_departamento,
                p.nombre as nombre_puesto,
                ua.nombre as nombre_aprobador,
                ur.nombre as nombre_rechazador,
                uc.nombre as nombre_creador
            ')
            ->join('empleados e', 'e.id_empleado = sc.id_empleado')
            ->join('departamentos d', 'd.id_departamento = e.id_departamento', 'left')
            ->join('puestos_trabajo p', 'p.id_puesto = e.id_puesto', 'left')
            ->join('usuarios ua', 'ua.id_usuario = sc.aprobado_por', 'left')
            ->join('usuarios ur', 'ur.id_usuario = sc.rechazado_por', 'left')
            ->join('usuarios uc', 'uc.id_usuario = sc.creado_por', 'left')
            ->where('sc.id_solicitud', $idSolicitud)
            ->first();
    }

    /**
     * Obtener solicitudes con información del empleado para AdminTH
     */
    public function getSolicitudesConEmpleado($filtros = [])
    {
        $builder = $this->db->table('solicitudes s');
        $builder->select('
            s.*,
            e.nombres as nombre_empleado,
            e.apellidos as apellido_empleado,
            e.tipo_empleado,
            e.departamento,
            u.cedula,
            u.email
        ');
        $builder->join('empleados e', 'e.id_empleado = s.id_empleado');
        $builder->join('usuarios u', 'u.id_usuario = e.id_usuario');
        
        // Solo solicitudes de capacitación
        $builder->where('s.tipo_solicitud', 'Capacitación');
        
        // Aplicar filtros si existen
        if (isset($filtros['estado'])) {
            $builder->where('s.estado', $filtros['estado']);
        }
        
        if (isset($filtros['tipo_empleado'])) {
            $builder->where('e.tipo_empleado', $filtros['tipo_empleado']);
        }
        
        if (isset($filtros['departamento'])) {
            $builder->where('e.departamento', $filtros['departamento']);
        }
        
        if (isset($filtros['busqueda'])) {
            $builder->groupStart();
            $builder->like('e.nombres', $filtros['busqueda']);
            $builder->orLike('e.apellidos', $filtros['busqueda']);
            $builder->orLike('u.cedula', $filtros['busqueda']);
            $builder->orLike('s.titulo', $filtros['busqueda']);
            $builder->groupEnd();
        }
        
        $builder->orderBy('s.fecha_solicitud', 'DESC');
        
        return $builder->get()->getResultArray();
    }

    /**
     * Obtener estadísticas generales
     */
    public function getEstadisticas()
    {
        $estadisticas = [
            'total' => $this->countAll(),
            'pendientes' => $this->where('estado', 'Pendiente')->countAllResults(),
            'aprobadas' => $this->where('estado', 'Aprobada')->countAllResults(),
            'rechazadas' => $this->where('estado', 'Rechazada')->countAllResults(),
            'canceladas' => $this->where('estado', 'Cancelada')->countAllResults(),
            'convertidas' => $this->where('estado', 'Convertida en Capacitación')->countAllResults()
        ];

        // Estadísticas por prioridad
        $prioridades = $this->select('prioridad, COUNT(*) as total')
            ->groupBy('prioridad')
            ->findAll();
        
        $estadisticas['por_prioridad'] = [];
        foreach ($prioridades as $prioridad) {
            $estadisticas['por_prioridad'][$prioridad['prioridad']] = $prioridad['total'];
        }

        // Estadísticas por tipo
        $tipos = $this->select('tipo_capacitacion, COUNT(*) as total')
            ->groupBy('tipo_capacitacion')
            ->findAll();
        
        $estadisticas['por_tipo'] = [];
        foreach ($tipos as $tipo) {
            $estadisticas['por_tipo'][$tipo['tipo_capacitacion']] = $tipo['total'];
        }

        // Solicitudes del mes actual
        $mesActual = date('Y-m');
        $estadisticas['mes_actual'] = $this->where("DATE_FORMAT(fecha_creacion, '%Y-%m')", $mesActual)->countAllResults();

        return $estadisticas;
    }

    /**
     * Obtener estadísticas por empleado
     */
    public function getEstadisticasPorEmpleado($idEmpleado)
    {
        $estadisticas = [
            'total' => $this->where('id_empleado', $idEmpleado)->countAllResults(),
            'pendientes' => $this->where('id_empleado', $idEmpleado)->where('estado', 'Pendiente')->countAllResults(),
            'aprobadas' => $this->where('id_empleado', $idEmpleado)->where('estado', 'Aprobada')->countAllResults(),
            'rechazadas' => $this->where('id_empleado', $idEmpleado)->where('estado', 'Rechazada')->countAllResults(),
            'canceladas' => $this->where('id_empleado', $idEmpleado)->where('estado', 'Cancelada')->countAllResults(),
            'convertidas' => $this->where('id_empleado', $idEmpleado)->where('estado', 'Convertida en Capacitación')->countAllResults()
        ];

        return $estadisticas;
    }

    /**
     * Buscar solicitudes por criterios
     */
    public function buscarSolicitudes($criterios)
    {
        $builder = $this->select('
                sc.*,
                e.nombres as nombre_empleado,
                e.apellidos as apellido_empleado,
                e.departamento as nombre_departamento
            ')
            ->join('empleados e', 'e.id_empleado = sc.id_empleado');

        if (!empty($criterios['estado'])) {
            $builder->where('sc.estado', $criterios['estado']);
        }

        if (!empty($criterios['prioridad'])) {
            $builder->where('sc.prioridad', $criterios['prioridad']);
        }

        if (!empty($criterios['tipo_capacitacion'])) {
            $builder->where('sc.tipo_capacitacion', $criterios['tipo_capacitacion']);
        }

        if (!empty($criterios['departamento'])) {
            $builder->where('e.departamento', $criterios['departamento']);
        }

        if (!empty($criterios['fecha_desde'])) {
            $builder->where('sc.fecha_creacion >=', $criterios['fecha_desde']);
        }

        if (!empty($criterios['fecha_hasta'])) {
            $builder->where('sc.fecha_creacion <=', $criterios['fecha_hasta']);
        }

        if (!empty($criterios['buscar'])) {
            $buscar = $criterios['buscar'];
            $builder->groupStart()
                ->like('sc.nombre_capacitacion', $buscar)
                ->orLike('e.nombre', $buscar)
                ->orLike('e.apellido', $buscar)
                ->orLike('e.cedula', $buscar)
                ->groupEnd();
        }

        return $builder->orderBy('sc.fecha_creacion', 'DESC')->findAll();
    }

    /**
     * Obtener solicitudes por período
     */
    public function getSolicitudesPorPeriodo($fechaInicio, $fechaFin)
    {
        return $this->select('
                sc.*,
                e.nombre as nombre_empleado,
                e.apellido as apellido_empleado,
                d.nombre as nombre_departamento
            ')
            ->join('empleados e', 'e.id_empleado = sc.id_empleado')
            ->join('departamentos d', 'd.id_departamento = e.id_departamento', 'left')
            ->where('sc.fecha_creacion >=', $fechaInicio)
            ->where('sc.fecha_creacion <=', $fechaFin)
            ->orderBy('sc.fecha_creacion', 'DESC')
            ->findAll();
    }

    /**
     * Obtener solicitudes por departamento
     */
    public function getSolicitudesPorDepartamento($idDepartamento)
    {
        return $this->select('
                sc.*,
                e.nombre as nombre_empleado,
                e.apellido as apellido_empleado,
                e.cedula
            ')
            ->join('empleados e', 'e.id_empleado = sc.id_empleado')
            ->where('e.id_departamento', $idDepartamento)
            ->orderBy('sc.fecha_creacion', 'DESC')
            ->findAll();
    }

    /**
     * Obtener solicitudes urgentes (prioridad alta o crítica)
     */
    public function getSolicitudesUrgentes()
    {
        return $this->select('
                sc.*,
                e.nombre as nombre_empleado,
                e.apellido as apellido_empleado,
                d.nombre as nombre_departamento
            ')
            ->join('empleados e', 'e.id_empleado = sc.id_empleado')
            ->join('departamentos d', 'd.id_departamento = e.id_departamento', 'left')
            ->whereIn('sc.prioridad', ['Alta', 'Crítica'])
            ->where('sc.estado', 'Pendiente')
            ->orderBy('sc.prioridad', 'DESC')
            ->orderBy('sc.fecha_creacion', 'ASC')
            ->findAll();
    }

    /**
     * Obtener solicitudes próximas a vencer (fecha deseada cercana)
     */
    public function getSolicitudesProximasAVencer($dias = 7)
    {
        $fechaLimite = date('Y-m-d', strtotime("+{$dias} days"));
        
        return $this->select('
                sc.*,
                e.nombre as nombre_empleado,
                e.apellido as apellido_empleado,
                d.nombre as nombre_departamento
            ')
            ->join('empleados e', 'e.id_empleado = sc.id_empleado')
            ->join('departamentos d', 'd.id_departamento = e.id_departamento', 'left')
            ->where('sc.estado', 'Pendiente')
            ->where('sc.fecha_deseada <=', $fechaLimite)
            ->orderBy('sc.fecha_deseada', 'ASC')
            ->findAll();
    }

    /**
     * Obtener reporte de solicitudes por período
     */
    public function getReportePorPeriodo($fechaInicio, $fechaFin)
    {
        $reporte = [
            'resumen' => [
                'total' => 0,
                'pendientes' => 0,
                'aprobadas' => 0,
                'rechazadas' => 0,
                'canceladas' => 0,
                'convertidas' => 0
            ],
            'por_departamento' => [],
            'por_tipo' => [],
            'por_prioridad' => [],
            'solicitudes' => []
        ];

        // Obtener solicitudes del período
        $solicitudes = $this->getSolicitudesPorPeriodo($fechaInicio, $fechaFin);
        
        foreach ($solicitudes as $solicitud) {
            $reporte['resumen']['total']++;
            $reporte['resumen'][strtolower($solicitud['estado'])]++;
            
            // Contar por departamento
            $depto = $solicitud['nombre_departamento'] ?? 'Sin departamento';
            if (!isset($reporte['por_departamento'][$depto])) {
                $reporte['por_departamento'][$depto] = 0;
            }
            $reporte['por_departamento'][$depto]++;
            
            // Contar por tipo
            $tipo = $solicitud['tipo_capacitacion'];
            if (!isset($reporte['por_tipo'][$tipo])) {
                $reporte['por_tipo'][$tipo] = 0;
            }
            $reporte['por_tipo'][$tipo]++;
            
            // Contar por prioridad
            $prioridad = $solicitud['prioridad'];
            if (!isset($reporte['por_prioridad'][$prioridad])) {
                $reporte['por_prioridad'][$prioridad] = 0;
            }
            $reporte['por_prioridad'][$prioridad]++;
        }

        $reporte['solicitudes'] = $solicitudes;
        
        return $reporte;
    }

    /**
     * Verificar si un empleado tiene solicitudes pendientes
     */
    public function tieneSolicitudesPendientes($idEmpleado)
    {
        return $this->where('id_empleado', $idEmpleado)
            ->where('estado', 'Pendiente')
            ->countAllResults() > 0;
    }

    /**
     * Obtener solicitudes similares (para evitar duplicados)
     */
    public function getSolicitudesSimilares($nombreCapacitacion, $idEmpleado, $excludeId = null)
    {
        $builder = $this->where('id_empleado', $idEmpleado)
            ->like('nombre_capacitacion', $nombreCapacitacion, 'both');

        if ($excludeId) {
            $builder->where('id_solicitud !=', $excludeId);
        }

        return $builder->findAll();
    }

    /**
     * Aprobar una solicitud
     */
    public function aprobarSolicitud($idSolicitud, $idAdmin, $observaciones = null)
    {
        $datos = [
            'estado' => 'Aprobada',
            'fecha_aprobacion' => date('Y-m-d H:i:s'),
            'aprobado_por' => $idAdmin,
            'observaciones_admin' => $observaciones,
            'fecha_modificacion' => date('Y-m-d H:i:s')
        ];
        
        return $this->update($idSolicitud, $datos);
    }

    /**
     * Rechazar una solicitud
     */
    public function rechazarSolicitud($idSolicitud, $idAdmin, $motivoRechazo)
    {
        $datos = [
            'estado' => 'Rechazada',
            'fecha_rechazo' => date('Y-m-d H:i:s'),
            'rechazado_por' => $idAdmin,
            'motivo_rechazo' => $motivoRechazo,
            'fecha_modificacion' => date('Y-m-d H:i:s')
        ];
        
        return $this->update($idSolicitud, $datos);
    }

    /**
     * Cancelar una solicitud
     */
    public function cancelarSolicitud($idSolicitud, $motivoCancelacion)
    {
        $datos = [
            'estado' => 'Cancelada',
            'fecha_cancelacion' => date('Y-m-d H:i:s'),
            'motivo_cancelacion' => $motivoCancelacion,
            'fecha_modificacion' => date('Y-m-d H:i:s')
        ];
        
        return $this->update($idSolicitud, $datos);
    }

    /**
     * Convertir una solicitud aprobada en capacitación
     */
    public function convertirEnCapacitacion($idSolicitud, $idCapacitacion)
    {
        $datos = [
            'estado' => 'Convertida en Capacitación',
            'id_capacitacion_creada' => $idCapacitacion,
            'fecha_conversion' => date('Y-m-d H:i:s'),
            'fecha_modificacion' => date('Y-m-d H:i:s')
        ];
        
        return $this->update($idSolicitud, $datos);
    }

    /**
     * Obtener solicitudes por prioridad
     */
    public function getSolicitudesPorPrioridad($prioridad)
    {
        return $this->where('prioridad', $prioridad)
                    ->orderBy('fecha_creacion', 'DESC')
                    ->findAll();
    }

    /**
     * Obtener solicitudes por tipo
     */
    public function getSolicitudesPorTipo($tipo)
    {
        return $this->where('tipo_capacitacion', $tipo)
                    ->orderBy('fecha_creacion', 'DESC')
                    ->findAll();
    }

    /**
     * Obtener solicitudes próximas a la fecha deseada
     */
    public function getSolicitudesProximasAFecha($dias = 7)
    {
        $fechaLimite = date('Y-m-d', strtotime("+{$dias} days"));
        
        return $this->where('estado', 'Aprobada')
                    ->where('fecha_deseada <=', $fechaLimite)
                    ->where('fecha_deseada >=', date('Y-m-d'))
                    ->orderBy('fecha_deseada', 'ASC')
                    ->findAll();
    }
}
