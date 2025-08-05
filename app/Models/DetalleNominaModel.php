<?php

namespace App\Models;

use CodeIgniter\Model;

class DetalleNominaModel extends Model
{
    protected $table = 'detalles_nomina';
    protected $primaryKey = 'id_detalle';
    protected $allowedFields = [
        'id_nomina', 'id_empleado', 'salario_base', 'horas_extras', 'valor_horas_extras',
        'bonos', 'comisiones', 'otros_ingresos', 'salud', 'pension', 'prestamos',
        'otros_descuentos', 'neto_pagar'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $returnType = 'array';

    public function getNominasPorEmpleado($idEmpleado)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('detalles_nomina dn');
        $builder->select('dn.*, n.periodo, n.fecha_generacion, n.fecha_pago, n.estado,
                         (dn.bonos + dn.comisiones + dn.otros_ingresos + dn.valor_horas_extras) as total_ingresos,
                         (dn.salud + dn.pension + dn.prestamos + dn.otros_descuentos) as total_descuentos');
        $builder->join('nominas n', 'n.id_nomina = dn.id_nomina');
        $builder->where('dn.id_empleado', $idEmpleado);
        $builder->orderBy('n.fecha_generacion', 'DESC');
        return $builder->get()->getResultArray();
    }
} 