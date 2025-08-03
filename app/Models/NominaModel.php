<?php

namespace App\Models;

use CodeIgniter\Model;

class NominaModel extends Model
{
    protected $table = 'nominas';
    protected $primaryKey = 'id_nomina';
    protected $allowedFields = [
        'periodo', 'fecha_generacion', 'fecha_pago', 'estado'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $returnType = 'array';

    public function getNominasCompletas()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('nominas n');
        $builder->select('n.*, COUNT(dn.id_detalle) as total_empleados, SUM(dn.neto_pagar) as total_nomina');
        $builder->join('detalles_nomina dn', 'dn.id_nomina = n.id_nomina', 'left');
        $builder->groupBy('n.id_nomina');
        $builder->orderBy('n.fecha_generacion', 'DESC');
        return $builder->get()->getResultArray();
    }

    public function getNominasPorEstado($estado)
    {
        return $this->where('estado', $estado)->findAll();
    }
} 