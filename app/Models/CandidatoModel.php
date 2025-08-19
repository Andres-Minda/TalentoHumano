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
        'nombres', 'apellidos', 'cedula', 'email', 'telefono', 'fecha_nacimiento',
        'genero', 'direccion', 'cv_url', 'estado', 'fecha_postulacion'
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
        'email' => 'required|valid_email|max_length[255]',
        'telefono' => 'permit_empty|max_length[20]',
        'fecha_nacimiento' => 'permit_empty|valid_date',
        'genero' => 'permit_empty|in_list[MASCULINO,FEMENINO,OTRO]',
        'direccion' => 'permit_empty|max_length[500]',
        'estado' => 'permit_empty|in_list[ACTIVO,INACTIVO,SELECCIONADO,RECHAZADO]'
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
            'max_length' => 'El email no puede exceder 255 caracteres'
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
                     (SELECT COUNT(*) FROM aplicaciones_vacantes av WHERE av.id_candidato = c.id_candidato) as total_aplicaciones,
                     (SELECT COUNT(*) FROM aplicaciones_vacantes av WHERE av.id_candidato = c.id_candidato AND av.estado = "APROBADA") as aplicaciones_aprobadas')
            ->orderBy('c.fecha_postulacion', 'DESC')
            ->get()
            ->getResultArray();
    }

    /**
     * Obtiene candidatos por estado
     */
    public function getCandidatosPorEstado($estado)
    {
        return $this->where('estado', $estado)
            ->orderBy('fecha_postulacion', 'DESC')
            ->findAll();
    }

    /**
     * Obtiene candidatos recientes
     */
    public function getCandidatosRecientes($limite = 10)
    {
        return $this->orderBy('fecha_postulacion', 'DESC')
            ->limit($limite)
            ->findAll();
    }

    /**
     * Busca candidatos por nombre o cédula
     */
    public function buscarCandidatos($termino)
    {
        return $this->like('nombres', $termino)
            ->orLike('apellidos', $termino)
            ->orLike('cedula', $termino)
            ->orLike('email', $termino)
            ->findAll();
    }
} 