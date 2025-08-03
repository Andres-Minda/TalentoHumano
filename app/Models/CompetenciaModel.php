<?php

namespace App\Models;

use CodeIgniter\Model;

class CompetenciaModel extends Model
{
    protected $table = 'competencias';
    protected $primaryKey = 'id_competencia';
    protected $allowedFields = [
        'nombre', 'descripcion'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $returnType = 'array';

    public function getCompetenciasConEvaluaciones()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('competencias c');
        $builder->select('c.*, COUNT(ec.id_empleado_competencia) as total_evaluaciones');
        $builder->join('empleados_competencias ec', 'ec.id_competencia = c.id_competencia', 'left');
        $builder->groupBy('c.id_competencia');
        return $builder->get()->getResultArray();
    }
} 