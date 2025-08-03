<?php

namespace App\Models;

use CodeIgniter\Model;

class EmpleadoCompetenciaModel extends Model
{
    protected $table = 'empleados_competencias';
    protected $primaryKey = 'id_empleado_competencia';
    protected $allowedFields = [
        'id_empleado', 'id_competencia', 'nivel_actual', 'fecha_evaluacion', 'comentarios'
    ];
    protected $useTimestamps = false;
    protected $returnType = 'array';

    public function getEvaluacionesCompletas()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('empleados_competencias ec');
        $builder->select('ec.*, e.nombres as empleado_nombres, e.apellidos as empleado_apellidos, c.nombre as competencia_nombre');
        $builder->join('empleados e', 'e.id_empleado = ec.id_empleado');
        $builder->join('competencias c', 'c.id_competencia = ec.id_competencia');
        $builder->orderBy('ec.fecha_evaluacion', 'DESC');
        return $builder->get()->getResultArray();
    }

    public function getCompetenciasEmpleado($idEmpleado)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('empleados_competencias ec');
        $builder->select('ec.*, c.nombre as competencia_nombre, c.descripcion');
        $builder->join('competencias c', 'c.id_competencia = ec.id_competencia');
        $builder->where('ec.id_empleado', $idEmpleado);
        return $builder->get()->getResultArray();
    }
} 