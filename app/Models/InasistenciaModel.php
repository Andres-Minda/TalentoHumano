<?php

namespace App\Models;

use CodeIgniter\Model;

class InasistenciaModel extends Model
{
    protected $table            = 'inasistencias';
    protected $primaryKey       = 'id_inasistencia';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields = [
        'id_empleado',
        'fecha_inasistencia',
        'tipo_inasistencia',
        'motivo_inasistencia',
        'justificacion',
        'documento_justificacion',
        'fecha_justificacion',
        'registrado_por',
        'estado',
        'observaciones'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'fecha_registro';
    protected $updatedField  = false;

    // Validaciones
    protected $validationRules = [
        'id_empleado'         => 'required|integer',
        'fecha_inasistencia' => 'required|valid_date',
        'tipo_inasistencia'  => 'required|in_list[JUSTIFICADA,NO_JUSTIFICADA,PENDIENTE_JUSTIFICACION]',
        'motivo_inasistencia'=> 'required|min_length[5]|max_length[500]',
        'registrado_por'     => 'required|integer',
        'estado'             => 'permit_empty|in_list[ACTIVA,ANULADA]'
    ];

    protected $validationMessages = [
        'id_empleado' => [
            'required' => 'El empleado es obligatorio',
            'integer' => 'El ID del empleado debe ser un número'
        ],
        'fecha_inasistencia' => [
            'required'   => 'La fecha de inasistencia es obligatoria',
            'valid_date' => 'La fecha debe ser válida'
        ],
        'tipo_inasistencia' => [
            'required' => 'El tipo de inasistencia es obligatorio',
            'in_list' => 'El tipo debe ser JUSTIFICADA, NO_JUSTIFICADA o PENDIENTE_JUSTIFICACION'
        ],
        'motivo_inasistencia' => [
            'required'   => 'El motivo es obligatorio',
            'min_length' => 'El motivo debe tener al menos 5 caracteres',
            'max_length' => 'El motivo no puede exceder 500 caracteres'
        ],
        'registrado_por' => [
            'required' => 'El usuario que registra es obligatorio',
            'integer' => 'El ID del usuario debe ser un número'
        ],
        'estado' => [
            'in_list' => 'El estado debe ser ACTIVA o ANULADA'
        ]
    ];

    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    /**
     * Obtener inasistencias de un empleado
     */
    public function getInasistenciasEmpleado($idEmpleado, $fechaInicio = null, $fechaFin = null)
    {
        $builder = $this->builder();
        $builder->where('id_empleado', $idEmpleado);
        $builder->where('estado', 'ACTIVA');
        
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
     * Obtener inasistencias por período
     */
    public function getInasistenciasPorPeriodo($fechaInicio, $fechaFin, $idEmpleado = null)
    {
        $builder = $this->builder();
        $builder->where('fecha_inasistencia >=', $fechaInicio);
        $builder->where('fecha_inasistencia <=', $fechaFin);
        $builder->where('estado', 'ACTIVA');
        
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
        
        $builder->where('estado', 'ACTIVA');
        
        if ($idEmpleado) {
            $builder->where('id_empleado', $idEmpleado);
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
            u.nombre as registrado_por_nombre,
            u.apellido as registrado_por_apellido
        ');
        $builder->join('empleados e', 'e.id_empleado = i.id_empleado');
        $builder->join('usuarios u', 'u.id = i.registrado_por');
        $builder->where('i.estado', 'ACTIVA');
        
        // Aplicar filtros
        if (isset($filtros['id_empleado'])) {
            $builder->where('i.id_empleado', $filtros['id_empleado']);
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
     * Cambiar estado de inasistencia
     */
    public function cambiarEstado($idInasistencia, $nuevoEstado, $observaciones = null)
    {
        $data = ['estado' => $nuevoEstado];
        
        if ($observaciones) {
            $data['observaciones'] = $observaciones;
        }
        
        return $this->update($idInasistencia, $data);
    }

    /**
     * Justificar inasistencia
     */
    public function justificarInasistencia($idInasistencia, $justificacion, $documento = null)
    {
        $data = [
            'tipo_inasistencia' => 'JUSTIFICADA',
            'justificacion'     => $justificacion,
            'fecha_justificacion' => date('Y-m-d')
        ];
        
        if ($documento) {
            $data['documento_justificacion'] = $documento;
        }
        
        return $this->update($idInasistencia, $data);
    }

    /**
     * Verificar si un empleado puede tener más inasistencias
     */
    public function verificarLimiteInasistencias($idEmpleado, $periodo = 'MENSUAL')
    {
        $fechaInicio = date('Y-m-01'); // Primer día del mes actual
        $fechaFin = date('Y-m-t');     // Último día del mes actual
        
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
}
