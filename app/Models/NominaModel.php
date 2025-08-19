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
        'id_empleado', 'periodo', 'salario_base', 'bonificaciones', 'deducciones',
        'salario_neto', 'estado', 'fecha_pago', 'observaciones'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules      = [
        'id_empleado' => 'required|integer',
        'periodo' => 'required|max_length[20]',
        'salario_base' => 'required|decimal',
        'bonificaciones' => 'permit_empty|decimal',
        'deducciones' => 'permit_empty|decimal',
        'salario_neto' => 'required|decimal',
        'estado' => 'required|in_list[PENDIENTE,PAGADA,CANCELADA]',
        'fecha_pago' => 'permit_empty|valid_date',
        'observaciones' => 'permit_empty|max_length[1000]'
    ];

    protected $validationMessages = [
        'id_empleado' => [
            'required' => 'El empleado es obligatorio',
            'integer' => 'El empleado debe ser un número entero'
        ],
        'periodo' => [
            'required' => 'El periodo es obligatorio',
            'max_length' => 'El periodo no puede exceder 20 caracteres'
        ],
        'salario_base' => [
            'required' => 'El salario base es obligatorio',
            'decimal' => 'El salario base debe ser un número decimal'
        ],
        'salario_neto' => [
            'required' => 'El salario neto es obligatorio',
            'decimal' => 'El salario neto debe ser un número decimal'
        ],
        'estado' => [
            'required' => 'El estado es obligatorio',
            'in_list' => 'El estado debe ser PENDIENTE, PAGADA o CANCELADA'
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
     * Obtiene nóminas completas con información de empleado
     */
    public function getNominasCompletas()
    {
        return $this->db->table('nominas n')
            ->select('n.*, e.nombres, e.apellidos, u.cedula, p.nombre as puesto, d.nombre as departamento')
            ->join('empleados e', 'e.id_empleado = n.id_empleado', 'left')
            ->join('usuarios u', 'u.id_usuario = e.id_usuario', 'left')
            ->join('puestos p', 'p.id_puesto = e.id_puesto', 'left')
            ->join('departamentos d', 'd.id_departamento = e.id_departamento', 'left')
            ->orderBy('n.periodo', 'DESC')
            ->orderBy('e.apellidos', 'ASC')
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
            ->orderBy('e.apellidos', 'ASC')
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
        $nominasPendientes = $db->table('nominas')->where('estado', 'PENDIENTE')->countAllResults();
        $nominasPagadas = $db->table('nominas')->where('estado', 'PAGADA')->countAllResults();
        
        // Calcular totales
        $totalSalarios = $db->table('nominas')->selectSum('salario_neto')->where('estado', 'PAGADA')->get()->getRow();
        $totalBonificaciones = $db->table('nominas')->selectSum('bonificaciones')->where('estado', 'PAGADA')->get()->getRow();
        
        return [
            'total_nominas' => $totalNominas,
            'pendientes' => $nominasPendientes,
            'pagadas' => $nominasPagadas,
            'total_salarios' => $totalSalarios->salario_neto ?? 0,
            'total_bonificaciones' => $totalBonificaciones->bonificaciones ?? 0
        ];
    }
} 