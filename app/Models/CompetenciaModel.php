<?php

namespace App\Models;

use CodeIgniter\Model;

class CompetenciaModel extends Model
{
    protected $table            = 'competencias';
    protected $primaryKey       = 'id_competencia';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'nombre', 'descripcion'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules      = [
        'nombre' => 'required|min_length[3]|max_length[100]',
        'descripcion' => 'permit_empty|max_length[1000]'
    ];

    protected $validationMessages = [
        'nombre' => [
            'required' => 'El nombre es obligatorio',
            'min_length' => 'El nombre debe tener al menos 3 caracteres',
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
     * Obtiene competencias activas
     */
    public function getCompetenciasActivas()
    {
        return $this->orderBy('nombre', 'ASC')->findAll();
    }

    /**
     * Obtiene competencias por nombre
     */
    public function getCompetenciasPorNombre($nombre)
    {
        return $this->like('nombre', $nombre)
            ->orderBy('nombre', 'ASC')
            ->findAll();
    }

    /**
     * Obtiene competencias con estadísticas de empleados
     */
    public function getCompetenciasConEstadisticas()
    {
        return $this->db->table('competencias c')
            ->select('c.*, 
                     (SELECT COUNT(*) FROM empleados_competencias ec WHERE ec.id_competencia = c.id_competencia) as total_empleados')
            ->orderBy('c.nombre', 'ASC')
            ->get()
            ->getResultArray();
    }

    /**
     * Obtiene estadísticas de competencias
     */
    public function getEstadisticasCompetencias()
    {
        $db = $this->db;
        
        $totalCompetencias = $db->table('competencias')->countAllResults();
        $competenciasConEmpleados = $db->table('competencias c')
            ->join('empleados_competencias ec', 'ec.id_competencia = c.id_competencia', 'left')
            ->where('ec.id_competencia IS NOT NULL')
            ->countAllResults();
        
        return [
            'total' => $totalCompetencias,
            'con_empleados' => $competenciasConEmpleados,
            'sin_empleados' => $totalCompetencias - $competenciasConEmpleados
        ];
    }
} 