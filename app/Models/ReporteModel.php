<?php

namespace App\Models;

use CodeIgniter\Model;

class ReporteModel extends Model
{
    protected $table            = 'reportes';
    protected $primaryKey       = 'id_reporte';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'nombre', 'descripcion', 'tipo_reporte', 'parametros', 'generado_por',
        'fecha_generacion', 'archivo_url', 'estado'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules      = [
        'nombre' => 'required|min_length[3]|max_length[200]',
        'descripcion' => 'permit_empty|max_length[1000]',
        'tipo_reporte' => 'required|in_list[EMPLEADOS,CAPACITACIONES,EVALUACIONES,ASISTENCIAS,NOMINA,COMPETENCIAS]',
        'parametros' => 'permit_empty|max_length[1000]',
        'generado_por' => 'required|integer',
        'estado' => 'required|in_list[PENDIENTE,COMPLETADO,ERROR]'
    ];

    protected $validationMessages = [
        'nombre' => [
            'required' => 'El nombre es obligatorio',
            'min_length' => 'El nombre debe tener al menos 3 caracteres',
            'max_length' => 'El nombre no puede exceder 200 caracteres'
        ],
        'tipo_reporte' => [
            'required' => 'El tipo de reporte es obligatorio',
            'in_list' => 'El tipo debe ser EMPLEADOS, CAPACITACIONES, EVALUACIONES, ASISTENCIAS, NOMINA o COMPETENCIAS'
        ],
        'generado_por' => [
            'required' => 'El usuario que genera es obligatorio',
            'integer' => 'El usuario debe ser un número entero'
        ],
        'estado' => [
            'required' => 'El estado es obligatorio',
            'in_list' => 'El estado debe ser PENDIENTE, COMPLETADO o ERROR'
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
     * Obtiene reportes completos con información del generador
     */
    public function getReportesCompletos()
    {
        return $this->db->table('reportes r')
            ->select('r.*, u.nombres as generador_nombres, u.apellidos as generador_apellidos')
            ->join('usuarios u', 'u.id_usuario = r.generado_por', 'left')
            ->orderBy('r.fecha_generacion', 'DESC')
            ->get()
            ->getResultArray();
    }

    /**
     * Obtiene reportes por tipo
     */
    public function getReportesPorTipo($tipo)
    {
        return $this->where('tipo_reporte', $tipo)
            ->orderBy('fecha_generacion', 'DESC')
            ->findAll();
    }

    /**
     * Obtiene reportes por estado
     */
    public function getReportesPorEstado($estado)
    {
        return $this->where('estado', $estado)
            ->orderBy('fecha_generacion', 'DESC')
            ->findAll();
    }

    /**
     * Obtiene reportes recientes
     */
    public function getReportesRecientes($limite = 10)
    {
        return $this->orderBy('fecha_generacion', 'DESC')
            ->limit($limite)
            ->findAll();
    }

    /**
     * Obtiene estadísticas de reportes
     */
    public function getEstadisticasReportes()
    {
        $db = $this->db;
        
        $totalReportes = $db->table('reportes')->countAllResults();
        $reportesCompletados = $db->table('reportes')->where('estado', 'COMPLETADO')->countAllResults();
        $reportesPendientes = $db->table('reportes')->where('estado', 'PENDIENTE')->countAllResults();
        $reportesError = $db->table('reportes')->where('estado', 'ERROR')->countAllResults();
        
        return [
            'total' => $totalReportes,
            'completados' => $reportesCompletados,
            'pendientes' => $reportesPendientes,
            'error' => $reportesError
        ];
    }

    /**
     * Genera reporte de empleados por departamento
     */
    public function generarReporteEmpleadosDepartamento()
    {
        return $this->db->table('empleados e')
            ->select('d.nombre as departamento, COUNT(*) as total_empleados, 
                     SUM(CASE WHEN e.activo = 1 THEN 1 ELSE 0 END) as empleados_activos,
                     SUM(CASE WHEN e.activo = 0 THEN 1 ELSE 0 END) as empleados_inactivos')
            ->join('departamentos d', 'd.id_departamento = e.id_departamento', 'left')
            ->groupBy('d.id_departamento, d.nombre')
            ->orderBy('d.nombre', 'ASC')
            ->get()
            ->getResultArray();
    }

    /**
     * Genera reporte de capacitaciones por periodo
     */
    public function generarReporteCapacitacionesPeriodo($periodoId = null)
    {
        $query = $this->db->table('capacitaciones c')
            ->select('c.nombre, c.tipo, c.proveedor, c.estado, c.fecha_inicio, c.fecha_fin,
                     COUNT(ec.id_empleado_capacitacion) as total_asignaciones,
                     SUM(CASE WHEN ec.estado = "COMPLETADA" THEN 1 ELSE 0 END) as completadas');
        
        if ($periodoId) {
            $query->where('c.periodo_academico_id', $periodoId);
        }
        
        return $query->join('empleados_capacitaciones ec', 'ec.id_capacitacion = c.id_capacitacion', 'left')
            ->groupBy('c.id_capacitacion')
            ->orderBy('c.fecha_inicio', 'DESC')
            ->get()
            ->getResultArray();
    }
}
