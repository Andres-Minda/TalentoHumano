<?php

namespace App\Models;

use CodeIgniter\Model;

class NominaModel extends Model
{
    protected $table            = 'nominas';
    protected $primaryKey       = 'id_nomina';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'periodo', 'fecha_generacion', 'fecha_pago', 'estado'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules      = [
        'periodo' => 'required|max_length[20]',
        'fecha_generacion' => 'required|valid_date',
        'fecha_pago' => 'required|valid_date',
        'estado' => 'required|max_length[20]'
    ];

    protected $validationMessages = [
        'periodo' => [
            'required' => 'El periodo es obligatorio',
            'max_length' => 'El periodo no puede exceder 20 caracteres'
        ],
        'fecha_generacion' => [
            'required' => 'La fecha de generación es obligatoria',
            'valid_date' => 'La fecha de generación debe ser una fecha válida'
        ],
        'fecha_pago' => [
            'required' => 'La fecha de pago es obligatoria',
            'valid_date' => 'La fecha de pago debe ser una fecha válida'
        ],
        'estado' => [
            'required' => 'El estado es obligatorio',
            'max_length' => 'El estado no puede exceder 20 caracteres'
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
     * Obtiene nóminas completas
     */
    public function getNominasCompletas()
    {
        return $this->db->table('nominas n')
            ->select('n.*')
            ->orderBy('n.periodo', 'DESC')
            ->orderBy('n.fecha_generacion', 'DESC')
            ->get()
            ->getResultArray();
    }

    /**
     * Obtiene nóminas por empleado
     */
    public function getNominasPorEmpleado($empleadoId)
    {
        return $this->where('id_empleado', $empleadoId)
            ->orderBy('periodo', 'DESC')
            ->findAll();
    }

    /**
     * Obtiene nóminas por periodo
     */
    public function getNominasPorPeriodo($periodo)
    {
        return $this->where('periodo', $periodo)
            ->orderBy('fecha_generacion', 'DESC')
            ->findAll();
    }

    /**
     * Obtiene nóminas por estado
     */
    public function getNominasPorEstado($estado)
    {
        return $this->where('estado', $estado)
            ->orderBy('periodo', 'DESC')
            ->findAll();
    }

    /**
     * Obtiene empleados por tipo para nómina
     */
    public function getEmpleadosPorTipo($tipoEmpleado)
    {
        return $this->db->table('empleados e')
            ->select('e.*, p.nombre as puesto, d.nombre as departamento, n.salario_base, n.periodo')
            ->join('puestos p', 'p.id_puesto = e.id_puesto', 'left')
            ->join('departamentos d', 'd.id_departamento = e.id_departamento', 'left')
            ->join('nominas n', 'n.id_empleado = e.id_empleado', 'left')
            ->where('e.tipo_empleado', $tipoEmpleado)
            ->where('e.activo', 1)
            ->orderBy('e.apellidos', 'ASC')
            ->get()
            ->getResultArray();
    }

    /**
     * Obtiene estadísticas de nómina
     */
    public function getEstadisticasNomina()
    {
        $db = $this->db;
        
        $totalNominas = $db->table('nominas')->countAllResults();
        $nominasPendientes = $db->table('nominas')->where('estado', 'Pendiente')->countAllResults();
        $nominasPagadas = $db->table('nominas')->where('estado', 'Pagada')->countAllResults();
        
        // Calcular totales
        $totalSalarios = $db->table('detalles_nomina')->selectSum('neto_pagar')->where('id_nomina IN (SELECT id_nomina FROM nominas WHERE estado = "Pagada")')->get()->getRow();
        $totalBonificaciones = $db->table('detalles_nomina')->selectSum('bonos')->where('id_nomina IN (SELECT id_nomina FROM nominas WHERE estado = "Pagada")')->get()->getRow();
        
        return [
            'total_nominas' => $totalNominas,
            'pendientes' => $nominasPendientes,
            'pagadas' => $nominasPagadas,
            'total_salarios' => $totalSalarios->salario_neto ?? 0,
            'total_bonificaciones' => $totalBonificaciones->bonificaciones ?? 0
        ];
    }
} 