<?php

namespace App\Models;

use CodeIgniter\Model;

class EvaluacionModel extends Model
{
    protected $table            = 'evaluaciones';
    protected $primaryKey       = 'id_evaluacion';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'nombre', 'descripcion', 'tipo_evaluacion',
        'fecha_inicio', 'fecha_fin', 'estado'
    ];

    protected $useTimestamps = false;
    protected $createdField  = 'created_at';

    protected $skipValidation = true;

    /**
     * Obtener todas las evaluaciones (tabla evaluaciones)
     */
    public function getEvaluacionesLista()
    {
        return $this->orderBy('created_at', 'DESC')->findAll();
    }

    /**
     * Obtener evaluaciones con datos de evaluaciones_empleados (completas)
     */
    public function getEvaluacionesCompletas()
    {
        return $this->db->table('evaluaciones_empleados ee')
            ->select('ee.*, e.nombre as evaluacion_nombre, e.descripcion as evaluacion_descripcion,
                      e.tipo_evaluacion, e.estado as evaluacion_estado,
                      emp.nombres as empleado_nombres, emp.apellidos as empleado_apellidos,
                      CONCAT(evaluador.nombres, " ", evaluador.apellidos) as evaluador_nombre')
            ->join('evaluaciones e', 'e.id_evaluacion = ee.id_evaluacion', 'left')
            ->join('empleados emp', 'emp.id_empleado = ee.id_empleado', 'left')
            ->join('empleados evaluador', 'evaluador.id_empleado = ee.id_evaluador', 'left')
            ->orderBy('ee.fecha_evaluacion', 'DESC')
            ->get()
            ->getResultArray();
    }

    /**
     * Obtener evaluaciones por empleado
     */
    public function getEvaluacionesPorEmpleado($idEmpleado)
    {
        return $this->db->table('evaluaciones_empleados ee')
            ->select('ee.*, e.nombre as nombre_evaluacion,
                      CONCAT(evaluador.nombres, " ", evaluador.apellidos) as evaluador_nombre')
            ->join('evaluaciones e', 'e.id_evaluacion = ee.id_evaluacion')
            ->join('empleados evaluador', 'evaluador.id_empleado = ee.id_evaluador')
            ->where('ee.id_empleado', $idEmpleado)
            ->orderBy('ee.fecha_evaluacion', 'DESC')
            ->get()
            ->getResultArray();
    }

    /**
     * Obtener estadísticas de evaluaciones
     */
    public function getEstadisticasEvaluaciones()
    {
        $db = $this->db;

        $total = $db->table('evaluaciones')->countAllResults();
        $activas = $db->table('evaluaciones')->where('estado', 'ACTIVA')->countAllResults();
        $completadas = $db->table('evaluaciones')->where('estado', 'COMPLETADA')->countAllResults();

        return [
            'total' => $total,
            'activas' => $activas,
            'completadas' => $completadas
        ];
    }

    /**
     * Insertar en evaluaciones_empleados (evaluación individual)
     */
    public function insertarEvaluacionEmpleado($data)
    {
        return $this->db->table('evaluaciones_empleados')->insert($data);
    }

    /**
     * Obtener una evaluación_empleado con JOINs
     */
    public function getEvaluacionEmpleadoCompleta($id)
    {
        return $this->db->table('evaluaciones_empleados ee')
            ->select('ee.*, e.nombre as evaluacion_nombre, e.descripcion as evaluacion_descripcion,
                      e.tipo_evaluacion, e.estado as evaluacion_estado,
                      emp.nombres as empleado_nombres, emp.apellidos as empleado_apellidos,
                      CONCAT(evaluador.nombres, " ", evaluador.apellidos) as evaluador_nombre')
            ->join('evaluaciones e', 'e.id_evaluacion = ee.id_evaluacion', 'left')
            ->join('empleados emp', 'emp.id_empleado = ee.id_empleado', 'left')
            ->join('empleados evaluador', 'evaluador.id_empleado = ee.id_evaluador', 'left')
            ->where('ee.id_evaluacion_empleado', $id)
            ->get()
            ->getRowArray();
    }
}