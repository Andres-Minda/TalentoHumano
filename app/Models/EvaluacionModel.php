<?php

namespace App\Models;

use CodeIgniter\Model;

class EvaluacionModel extends Model
{
    protected $table = 'evaluaciones';
    protected $primaryKey = 'id_evaluacion';
    protected $allowedFields = [
        'nombre', 'descripcion', 'fecha_inicio', 'fecha_fin', 'estado'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $returnType = 'array';

    public function getEvaluacionesCompletas()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('evaluaciones_empleados ee');
        $builder->select('ee.*, e.nombre as evaluacion_nombre, emp.nombres as empleado_nombres, emp.apellidos as empleado_apellidos, ev.nombres as evaluador_nombres, ev.apellidos as evaluador_apellidos');
        $builder->join('evaluaciones e', 'e.id_evaluacion = ee.id_evaluacion');
        $builder->join('empleados emp', 'emp.id_empleado = ee.id_empleado');
        $builder->join('empleados ev', 'ev.id_empleado = ee.id_evaluador');
        $builder->orderBy('ee.fecha_evaluacion', 'DESC');
        return $builder->get()->getResultArray();
    }

    public function getEvaluacionesActivas()
    {
        return $this->where('estado', 'En curso')->findAll();
    }
} 