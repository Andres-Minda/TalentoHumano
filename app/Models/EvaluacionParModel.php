<?php

namespace App\Models;

use CodeIgniter\Model;

class EvaluacionParModel extends Model
{
    protected $table            = 'evaluaciones_pares';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'evaluador_id', 'evaluado_id', 'estado',
        'observacion_clase', 'revision_materiales', 'retroalimentacion',
        'fecha_asignacion', 'fecha_evaluacion'
    ];

    protected $useTimestamps = false;

    protected $validationRules = [
        'evaluador_id'  => 'required|integer',
        'evaluado_id'   => 'required|integer',
        'estado'        => 'required|in_list[pendiente,en curso,completada]',
    ];

    /**
     * Obtener evaluaciones asignadas a un evaluador (JOIN con empleados)
     */
    public function getEvaluacionesPorEvaluador($evaluadorId)
    {
        $builder = $this->db->table('evaluaciones_pares ep');
        $builder->select('ep.*, 
            evaluado.nombres as evaluado_nombres, 
            evaluado.apellidos as evaluado_apellidos,
            evaluado.departamento as evaluado_departamento');
        $builder->join('empleados evaluado', 'ep.evaluado_id = evaluado.id_empleado');
        $builder->where('ep.evaluador_id', $evaluadorId);
        $builder->orderBy('ep.fecha_asignacion', 'DESC');

        return $builder->get()->getResultArray();
    }

    /**
     * Obtener retroalimentación recibida por un evaluado (solo completadas)
     */
    public function getRetroalimentacionRecibida($evaluadoId)
    {
        $builder = $this->db->table('evaluaciones_pares ep');
        $builder->select('ep.*, 
            evaluador.nombres as evaluador_nombres, 
            evaluador.apellidos as evaluador_apellidos,
            evaluador.departamento as evaluador_departamento');
        $builder->join('empleados evaluador', 'ep.evaluador_id = evaluador.id_empleado');
        $builder->where('ep.evaluado_id', $evaluadoId);
        $builder->where('ep.estado', 'completada');
        $builder->orderBy('ep.fecha_evaluacion', 'DESC');

        return $builder->get()->getResultArray();
    }

    /**
     * Obtener todas las evaluaciones con nombres (para AdminTH)
     */
    public function getTodasConNombres()
    {
        $builder = $this->db->table('evaluaciones_pares ep');
        $builder->select('ep.*, 
            evaluador.nombres as evaluador_nombres, 
            evaluador.apellidos as evaluador_apellidos,
            evaluado.nombres as evaluado_nombres, 
            evaluado.apellidos as evaluado_apellidos');
        $builder->join('empleados evaluador', 'ep.evaluador_id = evaluador.id_empleado');
        $builder->join('empleados evaluado', 'ep.evaluado_id = evaluado.id_empleado');
        $builder->orderBy('ep.fecha_asignacion', 'DESC');

        return $builder->get()->getResultArray();
    }

    /**
     * Obtener una evaluación con nombres
     */
    public function getEvaluacionConNombres($id)
    {
        $builder = $this->db->table('evaluaciones_pares ep');
        $builder->select('ep.*, 
            evaluador.nombres as evaluador_nombres, 
            evaluador.apellidos as evaluador_apellidos,
            evaluado.nombres as evaluado_nombres, 
            evaluado.apellidos as evaluado_apellidos');
        $builder->join('empleados evaluador', 'ep.evaluador_id = evaluador.id_empleado');
        $builder->join('empleados evaluado', 'ep.evaluado_id = evaluado.id_empleado');
        $builder->where('ep.id', $id);

        return $builder->get()->getRowArray();
    }
}
