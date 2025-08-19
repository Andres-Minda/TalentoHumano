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
        'nombre', 'descripcion', 'categoria', 'nivel_requerido', 'estado'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules      = [
        'nombre' => 'required|min_length[3]|max_length[200]',
        'descripcion' => 'permit_empty|max_length[1000]',
        'categoria' => 'required|in_list[TECNICA,BLANDA,ADMINISTRATIVA,LIDERAZGO]',
        'nivel_requerido' => 'required|in_list[BASICO,INTERMEDIO,AVANZADO,EXPERTE]',
        'estado' => 'required|in_list[ACTIVA,INACTIVA]'
    ];

    protected $validationMessages = [
        'nombre' => [
            'required' => 'El nombre es obligatorio',
            'min_length' => 'El nombre debe tener al menos 3 caracteres',
            'max_length' => 'El nombre no puede exceder 200 caracteres'
        ],
        'categoria' => [
            'required' => 'La categoría es obligatoria',
            'in_list' => 'La categoría debe ser TECNICA, BLANDA, ADMINISTRATIVA o LIDERAZGO'
        ],
        'nivel_requerido' => [
            'required' => 'El nivel requerido es obligatorio',
            'in_list' => 'El nivel debe ser BASICO, INTERMEDIO, AVANZADO o EXPERTE'
        ],
        'estado' => [
            'required' => 'El estado es obligatorio',
            'in_list' => 'El estado debe ser ACTIVA o INACTIVA'
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
        return $this->where('estado', 'ACTIVA')
            ->orderBy('nombre', 'ASC')
            ->findAll();
    }

    /**
     * Obtiene competencias por categoría
     */
    public function getCompetenciasPorCategoria($categoria)
    {
        return $this->where('categoria', $categoria)
            ->where('estado', 'ACTIVA')
            ->orderBy('nombre', 'ASC')
            ->findAll();
    }

    /**
     * Obtiene competencias por nivel
     */
    public function getCompetenciasPorNivel($nivel)
    {
        return $this->where('nivel_requerido', $nivel)
            ->where('estado', 'ACTIVA')
            ->orderBy('nombre', 'ASC')
            ->findAll();
    }

    /**
     * Obtiene estadísticas de competencias
     */
    public function getEstadisticasCompetencias()
    {
        $db = $this->db;
        
        $totalCompetencias = $db->table('competencias')->countAllResults();
        $competenciasActivas = $db->table('competencias')->where('estado', 'ACTIVA')->countAllResults();
        $competenciasTecnicas = $db->table('competencias')->where('categoria', 'TECNICA')->where('estado', 'ACTIVA')->countAllResults();
        $competenciasBlandas = $db->table('competencias')->where('categoria', 'BLANDA')->where('estado', 'ACTIVA')->countAllResults();
        
        return [
            'total' => $totalCompetencias,
            'activas' => $competenciasActivas,
            'tecnicas' => $competenciasTecnicas,
            'blandas' => $competenciasBlandas
        ];
    }

    /**
     * Busca competencias por nombre
     */
    public function buscarCompetencias($termino)
    {
        return $this->like('nombre', $termino)
            ->orLike('descripcion', $termino)
            ->where('estado', 'ACTIVA')
            ->orderBy('nombre', 'ASC')
            ->findAll();
    }
} 