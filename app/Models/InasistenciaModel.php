<?php

namespace App\Models;

use CodeIgniter\Model;

class InasistenciaModel extends Model
{
    protected $table            = 'inasistencias';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields = [
        'empleado_id',
        'fecha_inasistencia',
        'hora_inasistencia',
        'motivo',
        'justificada',
        'tipo_inasistencia',
        'archivo_justificacion',
        'registrado_por'
    ];

    protected $useTimestamps = false;

    // Deshabilitar validación del model para gestionar en controlador
    protected $skipValidation = true;
    protected $validationRules = [
        'empleado_id'        => 'required|integer',
        'fecha_inasistencia' => 'required|valid_date',
        'hora_inasistencia'  => 'permit_empty|valid_date',
        'motivo'             => 'required|min_length[5]|max_length[500]',
        'justificada'        => 'permit_empty|in_list[0,1]',
        'tipo_inasistencia'  => 'permit_empty|in_list[Justificada,Injustificada,Permiso,Vacaciones,Licencia Médica]',
        'archivo_justificacion' => 'permit_empty|max_length[1000]',
        'registrado_por'     => 'required|integer'
    ];

    protected $validationMessages = [
        'empleado_id' => [
            'required' => 'El empleado es obligatorio',
            'integer' => 'El ID del empleado debe ser un número'
        ],
        'fecha_inasistencia' => [
            'required'   => 'La fecha de inasistencia es obligatoria',
            'valid_date' => 'La fecha debe ser válida'
        ],
        'hora_inasistencia' => [
            'valid_date' => 'La hora debe ser válida'
        ],
        'motivo' => [
            'required'   => 'El motivo es obligatorio',
            'min_length' => 'El motivo debe tener al menos 5 caracteres',
            'max_length' => 'El motivo no puede exceder 500 caracteres'
        ],
        'justificada' => [
            'in_list' => 'El campo justificada debe ser 0 o 1'
        ],
        'tipo_inasistencia' => [
            'in_list' => 'El tipo debe ser Justificada, Injustificada, Permiso, Vacaciones o Licencia Médica'
        ],
        'archivo_justificacion' => [
            'max_length' => 'El nombre del archivo no puede exceder 255 caracteres'
        ],
        'registrado_por' => [
            'required' => 'El usuario que registra es obligatorio',
            'integer' => 'El ID del usuario debe ser un número'
        ]
    ];

    protected $cleanValidationRules = true;

    /**
     * Obtener inasistencias de un empleado con todos los campos reales de la BD
     * Devuelve también estadísticas calculadas en el mismo resultado.
     */
    public function getInasistenciasEmpleado($idEmpleado, $fechaInicio = null, $fechaFin = null)
    {
        $builder = $this->builder();
        $builder->where('empleado_id', $idEmpleado);

        if ($fechaInicio) {
            $builder->where('fecha_inasistencia >=', $fechaInicio);
        }
        if ($fechaFin) {
            $builder->where('fecha_inasistencia <=', $fechaFin);
        }

        $builder->orderBy('fecha_inasistencia', 'DESC');
        return $builder->get()->getResultArray();
    }

    /**
     * Obtener mis inasistencias para el endpoint AJAX del empleado.
     * Usa los nombres reales de columnas de la tabla `inasistencias`.
     * Permite filtrar por fecha y tipo. Devuelve datos + estadísticas.
     */
    public function getMisInasistencias($idEmpleado, $fechaDesde = null, $fechaHasta = null, $tipo = null, $estado = null)
    {
        $builder = $this->db->table('inasistencias i');
        $builder->select('
            i.id,
            i.empleado_id,
            i.fecha_inasistencia,
            i.hora_inasistencia,
            i.motivo,
            i.justificada,
            i.tipo_inasistencia,
            i.archivo_justificacion,
            i.created_at,
            e.nombres as emp_nombres,
            e.apellidos as emp_apellidos
        ');
        $builder->join('empleados e', 'e.id_empleado = i.empleado_id', 'left');
        $builder->where('i.empleado_id', $idEmpleado);

        if ($fechaDesde) {
            $builder->where('i.fecha_inasistencia >=', $fechaDesde);
        }
        if ($fechaHasta) {
            $builder->where('i.fecha_inasistencia <=', $fechaHasta);
        }
        if ($tipo) {
            $builder->where('i.tipo_inasistencia', $tipo);
        }
        // Filtro de estado basado en campo `justificada`
        if ($estado === 'justificada') {
            $builder->where('i.justificada', 1);
        } elseif ($estado === 'pendiente') {
            $builder->where('i.justificada', 0);
        }

        $builder->orderBy('i.fecha_inasistencia', 'DESC');
        $registros = $builder->get()->getResultArray();

        // Calcular estadísticas en PHP (evita doble query)
        $total       = count($registros);
        $justificadas = 0;
        $pendientes   = 0;
        foreach ($registros as $r) {
            if ((int)$r['justificada'] === 1) {
                $justificadas++;
            } else {
                $pendientes++;
            }
        }

        return [
            'data'        => $registros,
            'total'       => $total,
            'justificadas'=> $justificadas,
            'pendientes'  => $pendientes,
        ];
    }


    /**
     * Obtener inasistencias por período
     */
    public function getInasistenciasPorPeriodo($fechaInicio, $fechaFin, $idEmpleado = null)
    {
        $builder = $this->builder();
        $builder->where('fecha_inasistencia >=', $fechaInicio);
        $builder->where('fecha_inasistencia <=', $fechaFin);

        
        if ($idEmpleado) {
            $builder->where('id_empleado', $idEmpleado);
        }
        
        $builder->orderBy('fecha_inasistencia', 'DESC');
        
        return $builder->get()->getResultArray();
    }

    /**
     * Obtener estadísticas de inasistencias
     */
    public function getEstadisticasInasistencias($idEmpleado = null, $fechaInicio = null, $fechaFin = null)
    {
        $builder = $this->builder();
        $builder->select('
            COUNT(*) as total,
            SUM(CASE WHEN tipo_inasistencia = "JUSTIFICADA" THEN 1 ELSE 0 END) as justificadas,
            SUM(CASE WHEN tipo_inasistencia = "NO_JUSTIFICADA" THEN 1 ELSE 0 END) as no_justificadas,
            SUM(CASE WHEN tipo_inasistencia = "PENDIENTE_JUSTIFICACION" THEN 1 ELSE 0 END) as pendientes
        ');
        

        
        if ($idEmpleado) {
            $builder->where('empleado_id', $idEmpleado);
        }
        
        if ($fechaInicio) {
            $builder->where('fecha_inasistencia >=', $fechaInicio);
        }
        
        if ($fechaFin) {
            $builder->where('fecha_inasistencia <=', $fechaFin);
        }
        
        return $builder->get()->getRowArray();
    }

    /**
     * Obtener inasistencias con información del empleado
     */
    public function getInasistenciasConEmpleado($filtros = [])
    {
        $builder = $this->db->table('inasistencias i');
        $builder->select('
            i.*,
            e.nombres,
            e.apellidos,
            e.tipo_empleado,
            e.departamento,
            u.cedula as registrado_por_cedula,
            u.email as registrado_por_email
        ');
        $builder->join('empleados e', 'e.id_empleado = i.empleado_id');
        $builder->join('usuarios u', 'u.id_usuario = i.registrado_por');

        
        // Aplicar filtros
        if (isset($filtros['empleado_id'])) {
            $builder->where('i.empleado_id', $filtros['empleado_id']);
        }
        
        if (isset($filtros['tipo_inasistencia'])) {
            $builder->where('i.tipo_inasistencia', $filtros['tipo_inasistencia']);
        }
        
        if (isset($filtros['fecha_inicio'])) {
            $builder->where('i.fecha_inasistencia >=', $filtros['fecha_inicio']);
        }
        
        if (isset($filtros['fecha_fin'])) {
            $builder->where('i.fecha_inasistencia <=', $filtros['fecha_fin']);
        }
        
        if (isset($filtros['departamento'])) {
            $builder->where('e.departamento', $filtros['departamento']);
        }
        
        $builder->orderBy('i.fecha_inasistencia', 'DESC');
        
        return $builder->get()->getResultArray();
    }



    /**
     * Justificar inasistencia
     */
    public function justificarInasistencia($idInasistencia, $justificacion, $documento = null)
    {
        $data = [
            'tipo_inasistencia' => 'Justificada',
            'justificada'       => 1
        ];
        
        if ($documento) {
            $data['archivo_justificacion'] = $justificacion;
        }
        
        return $this->update($idInasistencia, $data);
    }

    /**
     * Verificar si un empleado puede tener más inasistencias
     */
    public function verificarLimiteInasistencias($idEmpleado, $periodo = 'MENSUAL')
    {
        $fechaInicio = date('Y-m-01');
        $fechaFin = date('Y-m-t');
        
        if ($periodo === 'TRIMESTRAL') {
            $mes = date('n');
            $trimestre = ceil($mes / 3);
            $fechaInicio = date('Y-m-01', strtotime("-$mes months"));
            $fechaFin = date('Y-m-t', strtotime("+" . (3 - $mes % 3) . " months"));
        } elseif ($periodo === 'ANUAL') {
            $fechaInicio = date('Y-01-01');
            $fechaFin = date('Y-12-31');
        }
        
        $estadisticas = $this->getEstadisticasInasistencias($idEmpleado, $fechaInicio, $fechaFin);
        
        return [
            'total' => $estadisticas['total'] ?? 0,
            'justificadas' => $estadisticas['justificadas'] ?? 0,
            'no_justificadas' => $estadisticas['no_justificadas'] ?? 0,
            'pendientes' => $estadisticas['pendientes'] ?? 0,
            'fecha_inicio' => $fechaInicio,
            'fecha_fin' => $fechaFin
        ];
    }

    /**
     * Obtener total histórico de inasistencias de un empleado (Req 4)
     */
    public function obtenerTotalInasistencias($empleadoId)
    {
        return $this->where('empleado_id', $empleadoId)->countAllResults();
    }

    /**
     * Obtener todas las inasistencias con acumulado por empleado (Req 4)
     * Incluye subconsulta para contar el total histórico de cada empleado
     */
    public function obtenerInasistenciasConAcumulado()
    {
        return $this->db->table('inasistencias i')
            ->select('i.*, 
                      e.nombres, e.apellidos, e.tipo_empleado, e.departamento,
                      (SELECT COUNT(*) FROM inasistencias i2 WHERE i2.empleado_id = i.empleado_id) as total_acumulado')
            ->join('empleados e', 'e.id_empleado = i.empleado_id', 'left')
            ->orderBy('i.created_at', 'DESC')
            ->get()
            ->getResultArray();
    }
}
