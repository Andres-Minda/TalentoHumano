<?php

namespace App\Models;

use CodeIgniter\Model;

class CandidatoModel extends Model
{
    protected $table            = 'candidatos';
    protected $primaryKey       = 'id_candidato';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'nombres', 'apellidos', 'cedula', 'email', 'telefono', 'cv_url', 'estado'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules      = [
        'nombres' => 'required|min_length[2]|max_length[100]',
        'apellidos' => 'required|min_length[2]|max_length[100]',
        'cedula' => 'required|min_length[10]|max_length[20]|is_unique[candidatos.cedula,id_candidato,{id_candidato}]',
        'email' => 'required|valid_email|max_length[100]',
        'telefono' => 'permit_empty|max_length[20]',
        'estado' => 'permit_empty|max_length[20]'
    ];

    protected $validationMessages = [
        'nombres' => [
            'required' => 'Los nombres son obligatorios',
            'min_length' => 'Los nombres deben tener al menos 2 caracteres',
            'max_length' => 'Los nombres no pueden exceder 100 caracteres'
        ],
        'apellidos' => [
            'required' => 'Los apellidos son obligatorios',
            'min_length' => 'Los apellidos deben tener al menos 2 caracteres',
            'max_length' => 'Los apellidos no pueden exceder 100 caracteres'
        ],
        'cedula' => [
            'required' => 'La cédula es obligatoria',
            'min_length' => 'La cédula debe tener al menos 10 caracteres',
            'max_length' => 'La cédula no puede exceder 20 caracteres',
            'is_unique' => 'Esta cédula ya está registrada'
        ],
        'email' => [
            'required' => 'El email es obligatorio',
            'valid_email' => 'El email debe tener un formato válido',
            'max_length' => 'El email no puede exceder 100 caracteres'
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
     * Obtiene candidatos completos con información de aplicaciones
     */
    public function getCandidatosCompletos()
    {
        return $this->db->table('candidatos c')
            ->select('c.*, 
                     (SELECT COUNT(*) FROM postulaciones p WHERE p.id_candidato = c.id_candidato) as total_aplicaciones,
                     (SELECT COUNT(*) FROM postulaciones p WHERE p.id_candidato = c.id_candidato AND p.puntaje_prueba >= 70) as aplicaciones_aprobadas')
            ->orderBy('c.created_at', 'DESC')
            ->get()
            ->getResultArray();
    }

    /**
     * Obtiene candidatos por estado
     */
    public function getCandidatosPorEstado($estado)
    {
        // Como no hay columna estado, filtramos por fecha de creación
        if ($estado === 'ACTIVO') {
            return $this->where('created_at >=', date('Y-m-d', strtotime('-1 year')))
                ->orderBy('created_at', 'DESC')
                ->findAll();
        } elseif ($estado === 'INACTIVO') {
            return $this->where('created_at <', date('Y-m-d', strtotime('-1 year')))
                ->orderBy('created_at', 'DESC')
                ->findAll();
        }
        
        return $this->orderBy('created_at', 'DESC')->findAll();
    }

    /**
     * Obtiene candidatos con estadísticas
     */
    public function getCandidatosConEstadisticas()
    {
        return $this->db->table('candidatos c')
            ->select('c.*, 
                     (SELECT COUNT(*) FROM postulaciones p WHERE p.id_candidato = c.id_candidato) as total_postulaciones,
                     (SELECT COUNT(*) FROM postulaciones p WHERE p.id_candidato = c.id_candidato AND p.puntaje_prueba IS NULL) as postulaciones_pendientes,
                     (SELECT COUNT(*) FROM postulaciones p WHERE p.id_candidato = c.id_candidato AND p.puntaje_prueba >= 70) as postulaciones_aprobadas,
                     (SELECT COUNT(*) FROM postulaciones p WHERE p.id_candidato = c.id_candidato AND p.puntaje_prueba < 70) as postulaciones_rechazadas')
            ->orderBy('c.created_at', 'DESC')
            ->get()
            ->getResultArray();
    }

    /**
     * Busca candidatos por término
     */
    public function buscarCandidatos($termino)
    {
        return $this->like('nombres', $termino)
            ->orLike('apellidos', $termino)
            ->orLike('cedula', $termino)
            ->orLike('email', $termino)
            ->findAll();
    }

    /**
     * Obtiene estadísticas de candidatos
     */
    public function getEstadisticasCandidatos()
    {
        $db = $this->db;
        
        $totalCandidatos = $db->table('candidatos')->countAllResults();
        $candidatosActivos = $db->table('candidatos')->where('created_at >=', date('Y-m-d', strtotime('-1 year')))->countAllResults();
        $candidatosInactivos = $db->table('candidatos')->where('created_at <', date('Y-m-d', strtotime('-1 year')))->countAllResults();
        
        return [
            'total' => $totalCandidatos,
            'activos' => $candidatosActivos,
            'inactivos' => $candidatosInactivos
        ];
    }
} 