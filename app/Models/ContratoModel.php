<?php

namespace App\Models;

use CodeIgniter\Model;

class ContratoModel extends Model
{
    protected $table            = 'contratos';
    protected $primaryKey       = 'id_contrato';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id_empleado', 'id_puesto', 'tipo_contrato', 'fecha_inicio', 'fecha_fin', 
        'salario', 'horas_semanales', 'archivo_url'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules      = [
        'id_empleado' => 'required|integer',
        'id_puesto' => 'required|integer',
        'tipo_contrato' => 'required|max_length[50]',
        'fecha_inicio' => 'required|valid_date',
        'fecha_fin' => 'permit_empty|valid_date',
        'salario' => 'required|decimal',
        'horas_semanales' => 'permit_empty|integer'
    ];

    protected $validationMessages = [
        'id_empleado' => [
            'required' => 'El empleado es obligatorio',
            'integer' => 'El empleado debe ser un número entero'
        ],
        'id_puesto' => [
            'required' => 'El puesto es obligatorio',
            'integer' => 'El puesto debe ser un número entero'
        ],
        'tipo_contrato' => [
            'required' => 'El tipo de contrato es obligatorio',
            'max_length' => 'El tipo de contrato no puede exceder 50 caracteres'
        ],
        'fecha_inicio' => [
            'required' => 'La fecha de inicio es obligatoria',
            'valid_date' => 'La fecha de inicio debe ser una fecha válida'
        ],
        'salario' => [
            'required' => 'El salario es obligatorio',
            'decimal' => 'El salario debe ser un número decimal'
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
     * Obtiene contratos completos con información de empleado
     */
    public function getContratosCompletos()
    {
        return $this->db->table('contratos c')
            ->select('c.*, e.nombres as empleado_nombres, e.apellidos as empleado_apellidos, p.nombre as puesto_nombre, d.nombre as departamento_nombre')
            ->join('empleados e', 'e.id_empleado = c.id_empleado', 'left')
            ->join('puestos p', 'p.id_puesto = c.id_puesto', 'left')
            ->join('departamentos d', 'd.id_departamento = p.id_departamento', 'left')
            ->orderBy('c.fecha_inicio', 'DESC')
            ->get()
            ->getResultArray();
    }

    /**
     * Obtiene contratos por empleado
     */
    public function getContratosPorEmpleado($idEmpleado)
    {
        return $this->where('id_empleado', $idEmpleado)
            ->orderBy('fecha_inicio', 'DESC')
            ->findAll();
    }

    /**
     * Obtiene contratos activos
     */
    public function getContratosActivos()
    {
        return $this->where('fecha_fin IS NULL OR fecha_fin >= CURDATE()')
            ->orderBy('fecha_inicio', 'DESC')
            ->findAll();
    }

    /**
     * Obtiene contratos por tipo
     */
    public function getContratosPorTipo($tipo)
    {
        return $this->where('tipo_contrato', $tipo)
            ->orderBy('fecha_inicio', 'DESC')
            ->findAll();
    }

    /**
     * Obtiene estadísticas de contratos
     */
    public function getEstadisticasContratos()
    {
        $db = $this->db;
        
        $totalContratos = $db->table('contratos')->countAllResults();
        $contratosActivos = $db->table('contratos')->where('fecha_fin IS NULL OR fecha_fin >= CURDATE()')->countAllResults();
        $contratosVencidos = $db->table('contratos')->where('fecha_fin < CURDATE()')->countAllResults();
        
        return [
            'total' => $totalContratos,
            'activos' => $contratosActivos,
            'vencidos' => $contratosVencidos
        ];
    }

    /**
     * Obtiene contratos por período
     */
    public function getContratosPorPeriodo($fechaInicio, $fechaFin)
    {
        return $this->where('fecha_inicio >=', $fechaInicio)
            ->where('fecha_inicio <=', $fechaFin)
            ->orderBy('fecha_inicio', 'DESC')
            ->findAll();
    }
} 