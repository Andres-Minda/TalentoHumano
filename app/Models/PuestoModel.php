<?php

namespace App\Models;

use CodeIgniter\Model;

class PuestoModel extends Model
{
    protected $table            = 'puestos';
    protected $primaryKey       = 'id_puesto';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'nombre', 'descripcion', 'id_departamento', 'salario_min', 'salario_max', 'salario_base'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules      = [
        'nombre' => 'required|min_length[2]|max_length[100]',
        'descripcion' => 'permit_empty|max_length[500]',
        'id_departamento' => 'required|integer',
        'salario_min' => 'permit_empty|decimal',
        'salario_max' => 'permit_empty|decimal',
        'salario_base' => 'required|decimal'
    ];

    protected $validationMessages = [
        'nombre' => [
            'required' => 'El nombre del puesto es obligatorio',
            'min_length' => 'El nombre debe tener al menos 2 caracteres',
            'max_length' => 'El nombre no puede exceder 100 caracteres'
        ],
        'id_departamento' => [
            'required' => 'El departamento es obligatorio',
            'integer' => 'El departamento debe ser un número entero'
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
     * Obtiene puestos con departamento
     */
    public function getPuestosConDepartamento()
    {
        return $this->db->table('puestos p')
            ->select('p.*, d.nombre as departamento')
            ->join('departamentos d', 'd.id_departamento = p.id_departamento', 'left')
            ->get()
            ->getResultArray();
    }

    /**
     * Obtiene puestos con estadísticas
     */
    public function getPuestosConEstadisticas()
    {
        return $this->db->table('puestos p')
            ->select('p.*, d.nombre as departamento,
                     (SELECT COUNT(*) FROM empleados e WHERE e.id_puesto = p.id_puesto AND e.activo = 1) as total_empleados')
            ->join('departamentos d', 'd.id_departamento = p.id_departamento', 'left')
            ->get()
            ->getResultArray();
    }

    /**
     * Obtiene puestos activos
     */
    public function getPuestosActivos()
    {
        return $this->findAll();
    }

    /**
     * Obtiene puestos por departamento
     */
    public function getPuestosPorDepartamento($departamentoId)
    {
        return $this->where('id_departamento', $departamentoId)
            ->findAll();
    }
} 