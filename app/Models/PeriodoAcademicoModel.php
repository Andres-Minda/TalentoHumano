<?php

namespace App\Models;

use CodeIgniter\Model;

class PeriodoAcademicoModel extends Model
{
    protected $table            = 'periodos_academicos';
    protected $primaryKey       = 'id_periodo';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'nombre', 'fecha_inicio', 'fecha_fin', 'estado', 'descripcion'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules      = [
        'nombre'       => 'required|min_length[3]|max_length[100]',
        'fecha_inicio' => 'required|valid_date',
        'fecha_fin'    => 'required|valid_date',
        'estado'       => 'required|in_list[ACTIVO,INACTIVO,PLANIFICADO,FINALIZADO]',
        'descripcion'  => 'permit_empty|max_length[500]'
    ];

    protected $validationMessages = [
        'nombre' => [
            'required' => 'El nombre del período es obligatorio',
            'min_length' => 'El nombre debe tener al menos 3 caracteres',
            'max_length' => 'El nombre no puede exceder 100 caracteres'
        ],
        'fecha_inicio' => [
            'required' => 'La fecha de inicio es obligatoria',
            'valid_date' => 'La fecha de inicio debe ser una fecha válida'
        ],
        'fecha_fin' => [
            'required' => 'La fecha de fin es obligatoria',
            'valid_date' => 'La fecha de fin debe ser una fecha válida'
        ],
        'estado' => [
            'required' => 'El estado es obligatorio',
            'in_list' => 'El estado debe ser ACTIVO, INACTIVO, PLANIFICADO o FINALIZADO'
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
     * Obtiene el período académico activo
     */
    public function getPeriodoActivo()
    {
        return $this->where('estado', 'ACTIVO')->first();
    }

    /**
     * Obtiene períodos disponibles (activos y planificados)
     */
    public function getPeriodosDisponibles()
    {
        return $this->whereIn('estado', ['ACTIVO', 'PLANIFICADO'])
            ->orderBy('fecha_inicio', 'ASC')
            ->findAll();
    }

    /**
     * Obtiene períodos por estado
     */
    public function getPeriodosPorEstado($estado)
    {
        return $this->where('estado', $estado)
            ->orderBy('fecha_inicio', 'DESC')
            ->findAll();
    }

    /**
     * Obtiene períodos por año
     */
    public function getPeriodosPorAno($ano)
    {
        return $this->where('YEAR(fecha_inicio)', $ano)
            ->orderBy('fecha_inicio', 'ASC')
            ->findAll();
    }

    /**
     * Obtiene períodos recientes
     */
    public function getPeriodosRecientes($limite = 5)
    {
        return $this->orderBy('created_at', 'DESC')
            ->limit($limite)
            ->findAll();
    }

    /**
     * Verifica si existe un período activo
     */
    public function tienePeriodoActivo()
    {
        return $this->where('estado', 'ACTIVO')->countAllResults() > 0;
    }

    /**
     * Obtiene el próximo período planificado
     */
    public function getProximoPeriodo()
    {
        return $this->where('estado', 'PLANIFICADO')
            ->where('fecha_inicio >', date('Y-m-d'))
            ->orderBy('fecha_inicio', 'ASC')
            ->first();
    }
} 