<?php

namespace App\Models;

use CodeIgniter\Model;

class BeneficioModel extends Model
{
    protected $table = 'beneficios';
    protected $primaryKey = 'id_beneficio';
    protected $allowedFields = [
        'nombre', 'descripcion', 'tipo'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $returnType = 'array';

    public function getBeneficiosConAsignaciones()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('beneficios b');
        $builder->select('b.*, COUNT(be.id_beneficio_empleado) as total_asignaciones');
        $builder->join('beneficios_empleados be', 'be.id_beneficio = b.id_beneficio', 'left');
        $builder->groupBy('b.id_beneficio');
        return $builder->get()->getResultArray();
    }
} 