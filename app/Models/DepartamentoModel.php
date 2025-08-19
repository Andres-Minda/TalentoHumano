<?php

namespace App\Models;

use CodeIgniter\Model;

class DepartamentoModel extends Model
{
    protected $table            = 'departamentos';
    protected $primaryKey       = 'id_departamento';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'nombre', 'descripcion', 'id_jefe'
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
        'id_jefe' => 'permit_empty|integer'
    ];

    protected $validationMessages = [
        'nombre' => [
            'required' => 'El nombre del departamento es obligatorio',
            'min_length' => 'El nombre debe tener al menos 2 caracteres',
            'max_length' => 'El nombre no puede exceder 100 caracteres'
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
     * Obtiene departamentos activos
     */
    public function getDepartamentosActivos()
    {
        return $this->findAll();
    }

    /**
     * Obtiene departamentos con estadísticas
     */
    public function getDepartamentosConEstadisticas()
    {
        return $this->db->table('departamentos d')
            ->select('d.*, 
                     (SELECT COUNT(*) FROM empleados e WHERE e.id_departamento = d.id_departamento AND e.activo = 1) as total_empleados,
                     (SELECT COUNT(*) FROM puestos p WHERE p.id_departamento = d.id_departamento) as total_puestos')
            ->get()
            ->getResultArray();
    }

    /**
     * Obtiene estadísticas de departamentos
     */
    public function getEstadisticasDepartamentos()
    {
        $db = $this->db;
        
        $totalDepartamentos = $db->table('departamentos')
            ->countAllResults();
            
        $empleadosPorDepartamento = $db->table('departamentos d')
            ->select('d.nombre, COUNT(e.id_empleado) as total')
            ->join('empleados e', 'e.id_departamento = d.id_departamento', 'left')
            ->where('e.activo', 1)
            ->groupBy('d.id_departamento')
            ->get()
            ->getResultArray();
            
        return [
            'total' => $totalDepartamentos,
            'empleados_por_departamento' => $empleadosPorDepartamento
        ];
    }
} 