<?php

namespace App\Models;

use CodeIgniter\Model;

class BeneficioModel extends Model
{
    protected $table            = 'beneficios';
    protected $primaryKey       = 'id_beneficio';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'nombre', 'descripcion', 'tipo_beneficio', 'valor', 'estado',
        'fecha_inicio', 'fecha_fin', 'condiciones'
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
        'tipo_beneficio' => 'required|in_list[ECONOMICO,SERVICIO,PRODUCTO,EDUCATIVO,OTROS]',
        'valor' => 'permit_empty|decimal',
        'estado' => 'required|in_list[ACTIVO,INACTIVO,SUSPENDIDO]',
        'fecha_inicio' => 'permit_empty|valid_date',
        'fecha_fin' => 'permit_empty|valid_date',
        'condiciones' => 'permit_empty|max_length[1000]'
    ];

    protected $validationMessages = [
        'nombre' => [
            'required' => 'El nombre es obligatorio',
            'min_length' => 'El nombre debe tener al menos 3 caracteres',
            'max_length' => 'El nombre no puede exceder 200 caracteres'
        ],
        'tipo_beneficio' => [
            'required' => 'El tipo de beneficio es obligatorio',
            'in_list' => 'El tipo debe ser ECONOMICO, SERVICIO, PRODUCTO, EDUCATIVO u OTROS'
        ],
        'estado' => [
            'required' => 'El estado es obligatorio',
            'in_list' => 'El estado debe ser ACTIVO, INACTIVO o SUSPENDIDO'
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
     * Obtiene beneficios activos
     */
    public function getBeneficiosActivos()
    {
        return $this->where('estado', 'ACTIVO')
            ->orderBy('nombre', 'ASC')
            ->findAll();
    }

    /**
     * Obtiene beneficios por tipo
     */
    public function getBeneficiosPorTipo($tipo)
    {
        return $this->where('tipo_beneficio', $tipo)
            ->where('estado', 'ACTIVO')
            ->orderBy('nombre', 'ASC')
            ->findAll();
    }

    /**
     * Obtiene beneficios por estado
     */
    public function getBeneficiosPorEstado($estado)
    {
        return $this->where('estado', $estado)
            ->orderBy('nombre', 'ASC')
            ->findAll();
    }

    /**
     * Obtiene beneficios vigentes
     */
    public function getBeneficiosVigentes()
    {
        $hoy = date('Y-m-d');
        return $this->where('estado', 'ACTIVO')
            ->where('(fecha_inicio IS NULL OR fecha_inicio <=)', $hoy)
            ->where('(fecha_fin IS NULL OR fecha_fin >=)', $hoy)
            ->orderBy('nombre', 'ASC')
            ->findAll();
    }

    /**
     * Obtiene estadísticas de beneficios
     */
    public function getEstadisticasBeneficios()
    {
        $db = $this->db;
        
        $totalBeneficios = $db->table('beneficios')->countAllResults();
        $beneficiosActivos = $db->table('beneficios')->where('estado', 'ACTIVO')->countAllResults();
        $beneficiosEconomicos = $db->table('beneficios')->where('tipo_beneficio', 'ECONOMICO')->where('estado', 'ACTIVO')->countAllResults();
        $beneficiosServicios = $db->table('beneficios')->where('tipo_beneficio', 'SERVICIO')->where('estado', 'ACTIVO')->countAllResults();
        
        return [
            'total' => $totalBeneficios,
            'activos' => $beneficiosActivos,
            'economicos' => $beneficiosEconomicos,
            'servicios' => $beneficiosServicios
        ];
    }

    /**
     * Busca beneficios por nombre
     */
    public function buscarBeneficios($termino)
    {
        return $this->like('nombre', $termino)
            ->orLike('descripcion', $termino)
            ->where('estado', 'ACTIVO')
            ->orderBy('nombre', 'ASC')
            ->findAll();
    }
} 