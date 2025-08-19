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
        'id_empleado', 'tipo_contrato', 'fecha_inicio', 'fecha_fin', 'salario',
        'estado', 'descripcion', 'archivo_contrato'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules      = [
        'id_empleado' => 'required|integer',
        'tipo_contrato' => 'required|in_list[TEMPORAL,INDEFINIDO,OBRA_DETERMINADA,PRACTICAS]',
        'fecha_inicio' => 'required|valid_date',
        'fecha_fin' => 'permit_empty|valid_date',
        'salario' => 'required|decimal',
        'estado' => 'required|in_list[ACTIVO,INACTIVO,TERMINADO,RENOVADO]',
        'descripcion' => 'permit_empty|max_length[1000]'
    ];

    protected $validationMessages = [
        'id_empleado' => [
            'required' => 'El empleado es obligatorio',
            'integer' => 'El empleado debe ser un número entero'
        ],
        'tipo_contrato' => [
            'required' => 'El tipo de contrato es obligatorio',
            'in_list' => 'El tipo debe ser TEMPORAL, INDEFINIDO, OBRA_DETERMINADA o PRACTICAS'
        ],
        'fecha_inicio' => [
            'required' => 'La fecha de inicio es obligatoria',
            'valid_date' => 'La fecha de inicio debe ser una fecha válida'
        ],
        'salario' => [
            'required' => 'El salario es obligatorio',
            'decimal' => 'El salario debe ser un número decimal'
        ],
        'estado' => [
            'required' => 'El estado es obligatorio',
            'in_list' => 'El estado debe ser ACTIVO, INACTIVO, TERMINADO o RENOVADO'
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
            ->select('c.*, e.nombres, e.apellidos, u.cedula, p.nombre as puesto, d.nombre as departamento')
            ->join('empleados e', 'e.id_empleado = c.id_empleado', 'left')
            ->join('usuarios u', 'u.id_usuario = e.id_usuario', 'left')
            ->join('puestos p', 'p.id_puesto = e.id_puesto', 'left')
            ->join('departamentos d', 'd.id_departamento = e.id_departamento', 'left')
            ->orderBy('c.fecha_inicio', 'DESC')
            ->get()
            ->getResultArray();
    }

    /**
     * Obtiene contratos por estado
     */
    public function getContratosPorEstado($estado)
    {
        return $this->where('estado', $estado)
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
     * Obtiene contratos activos
     */
    public function getContratosActivos()
    {
        return $this->where('estado', 'ACTIVO')
            ->orderBy('fecha_inicio', 'DESC')
            ->findAll();
    }

    /**
     * Obtiene contratos por empleado
     */
    public function getContratosPorEmpleado($empleadoId)
    {
        return $this->where('id_empleado', $empleadoId)
            ->orderBy('fecha_inicio', 'DESC')
            ->findAll();
    }

    /**
     * Obtiene contratos próximos a vencer
     */
    public function getContratosProximosAVencer($dias = 30)
    {
        $fechaLimite = date('Y-m-d', strtotime("+$dias days"));
        return $this->where('estado', 'ACTIVO')
            ->where('fecha_fin <=', $fechaLimite)
            ->where('fecha_fin >=', date('Y-m-d'))
            ->orderBy('fecha_fin', 'ASC')
            ->findAll();
    }
} 