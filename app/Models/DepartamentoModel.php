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
        $db = \Config\Database::connect();
        $builder = $db->table('departamentos d');
        $builder->select('d.*, 
                         COUNT(e.id_empleado) as total_empleados,
                         j.nombres as jefe_nombres, 
                         j.apellidos as jefe_apellidos,
                         CONCAT(j.nombres, " ", j.apellidos) as jefe_nombre');
        $builder->join('empleados e', 'e.id_departamento = d.id_departamento', 'left');
        $builder->join('empleados j', 'j.id_empleado = d.id_jefe', 'left');
        $builder->groupBy('d.id_departamento');
        return $builder->get()->getResultArray();
    }

    /**
     * Obtiene estadísticas de departamentos
     */
    public function getEstadisticasDepartamentos()
    {
        $db = \Config\Database::connect();
        
        // Contar total de departamentos
        $query = $db->query("SELECT COUNT(*) as total FROM departamentos");
        $total = $query->getRow()->total;
        
        // Contar departamentos con empleados
        $query = $db->query("SELECT COUNT(DISTINCT d.id_departamento) as con_empleados 
                             FROM departamentos d 
                             JOIN empleados e ON e.id_departamento = d.id_departamento");
        $conEmpleados = $query->getRow()->con_empleados;
        
        // Contar departamentos sin empleados
        $query = $db->query("SELECT COUNT(*) as sin_empleados FROM departamentos d 
                             WHERE NOT EXISTS (SELECT 1 FROM empleados e WHERE e.id_departamento = d.id_departamento)");
        $sinEmpleados = $query->getRow()->sin_empleados;
        
        return [
            'total' => $total,
            'con_empleados' => $conEmpleados,
            'sin_empleados' => $sinEmpleados
        ];
    }
} 