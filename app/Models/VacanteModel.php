<?php

namespace App\Models;

use CodeIgniter\Model;

class VacanteModel extends Model
{
    protected $table            = 'vacantes';
    protected $primaryKey       = 'id_vacante';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id_puesto', 'fecha_publicacion', 'fecha_cierre', 'estado', 
        'descripcion', 'requisitos', 'nombre', 'salario_min', 'salario_max'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules      = [
        'id_puesto' => 'required|integer',
        'fecha_publicacion' => 'required|valid_date',
        'fecha_cierre' => 'permit_empty|valid_date',
        'estado' => 'required|max_length[20]',
        'descripcion' => 'permit_empty|max_length[1000]',
        'requisitos' => 'permit_empty|max_length[1000]',
        'nombre' => 'permit_empty|max_length[200]',
        'salario_min' => 'permit_empty|decimal',
        'salario_max' => 'permit_empty|decimal'
    ];

    protected $validationMessages = [
        'id_puesto' => [
            'required' => 'El puesto es obligatorio',
            'integer' => 'El puesto debe ser un número entero'
        ],
        'fecha_publicacion' => [
            'required' => 'La fecha de publicación es obligatoria',
            'valid_date' => 'La fecha de publicación debe ser una fecha válida'
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
     * Obtiene vacantes completas con información de puesto y departamento
     */
    public function getVacantesCompletas()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('vacantes v');
        $builder->select('v.*, p.nombre as puesto_nombre, d.nombre as departamento_nombre, p.salario_base, COUNT(po.id_postulacion) as total_candidatos');
        $builder->join('puestos p', 'p.id_puesto = v.id_puesto');
        $builder->join('departamentos d', 'd.id_departamento = p.id_departamento');
        $builder->join('postulaciones po', 'po.id_vacante = v.id_vacante', 'left');
        $builder->groupBy('v.id_vacante');
        $builder->orderBy('v.fecha_publicacion', 'DESC');
        return $builder->get()->getResultArray();
    }

    /**
     * Obtiene vacantes activas
     */
    public function getVacantesActivas()
    {
        return $this->where('estado', 'Activa')
            ->where('fecha_cierre >=', date('Y-m-d'))
            ->findAll();
    }

    /**
     * Obtiene estadísticas de vacantes
     */
    public function getEstadisticasVacantes()
    {
        $db = $this->db;
        
        $totalVacantes = $db->table('vacantes')->countAllResults();
        $vacantesActivas = $db->table('vacantes')->where('estado', 'Activa')->countAllResults();
        $vacantesCerradas = $db->table('vacantes')->where('estado', 'Cerrada')->countAllResults();
        
        return [
            'total' => $totalVacantes,
            'activas' => $vacantesActivas,
            'cerradas' => $vacantesCerradas
        ];
    }

    /**
     * Obtiene estado de vacantes para gráficos
     */
    public function getEstadoVacantes()
    {
        return $this->db->table('vacantes')
            ->select('estado, COUNT(*) as total')
            ->groupBy('estado')
            ->get()
            ->getResultArray();
    }
} 