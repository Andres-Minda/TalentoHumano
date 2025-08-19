<?php

namespace App\Models;

use CodeIgniter\Model;

class TituloAcademicoModel extends Model
{
    protected $table            = 'titulos_academicos';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'empleado_id', 'universidad', 'tipo_titulo', 'nombre_titulo',
        'fecha_obtencion', 'pais', 'archivo_certificado'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = '';
    protected $deletedField  = '';

    // Validation
    protected $validationRules      = [
        'empleado_id'      => 'required|integer',
        'universidad'      => 'required|min_length[2]|max_length[255]',
        'tipo_titulo'      => 'required|in_list[Tercer nivel,Cuarto nivel,Doctorado/PhD]',
        'nombre_titulo'    => 'required|min_length[5]|max_length[255]',
        'fecha_obtencion'  => 'required|valid_date',
        'pais'             => 'permit_empty|min_length[2]|max_length[100]',
        'archivo_certificado' => 'permit_empty|max_length[255]'
    ];
    
    protected $validationMessages   = [
        'empleado_id' => [
            'required' => 'El empleado es obligatorio',
            'integer' => 'El ID del empleado debe ser un número entero'
        ],
        'universidad' => [
            'required' => 'La universidad es obligatoria',
            'min_length' => 'La universidad debe tener al menos 2 caracteres',
            'max_length' => 'La universidad no puede exceder 255 caracteres'
        ],
        'tipo_titulo' => [
            'required' => 'El tipo de título es obligatorio',
            'in_list' => 'El tipo de título debe ser Tercer nivel, Cuarto nivel o Doctorado/PhD'
        ],
        'nombre_titulo' => [
            'required' => 'El nombre del título es obligatorio',
            'min_length' => 'El nombre del título debe tener al menos 5 caracteres',
            'max_length' => 'El nombre del título no puede exceder 255 caracteres'
        ],
        'fecha_obtencion' => [
            'required' => 'La fecha de obtención es obligatoria',
            'valid_date' => 'La fecha de obtención debe ser una fecha válida'
        ],
        'pais' => [
            'min_length' => 'El país debe tener al menos 2 caracteres',
            'max_length' => 'El país no puede exceder 100 caracteres'
        ],
        'archivo_certificado' => [
            'max_length' => 'El nombre del archivo no puede exceder 255 caracteres'
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
     * Obtiene títulos académicos de un empleado específico
     */
    public function getTitulosPorEmpleado($empleadoId)
    {
        return $this->where('empleado_id', $empleadoId)
                    ->orderBy('fecha_obtencion', 'DESC')
                    ->findAll();
    }

    /**
     * Obtiene títulos académicos con información del empleado
     */
    public function getTitulosConEmpleado()
    {
        return $this->select('titulos_academicos.*, empleados.nombres, empleados.apellidos, empleados.cedula')
                    ->join('empleados', 'empleados.id = titulos_academicos.empleado_id')
                    ->where('empleados.activo', 1)
                    ->orderBy('empleados.apellidos', 'ASC')
                    ->orderBy('titulos_academicos.fecha_obtencion', 'DESC')
                    ->findAll();
    }

    /**
     * Obtiene títulos por tipo
     */
    public function getTitulosPorTipo($tipo)
    {
        return $this->where('tipo_titulo', $tipo)
                    ->join('empleados', 'empleados.id = titulos_academicos.empleado_id')
                    ->where('empleados.activo', 1)
                    ->select('titulos_academicos.*, empleados.nombres, empleados.apellidos')
                    ->findAll();
    }

    /**
     * Obtiene estadísticas de títulos académicos
     */
    public function getEstadisticasTitulos()
    {
        $db = \Config\Database::connect();
        
        $query = $db->query("
            SELECT 
                tipo_titulo,
                COUNT(*) as cantidad
            FROM titulos_academicos ta
            JOIN empleados e ON e.id = ta.empleado_id
            WHERE e.activo = 1
            GROUP BY tipo_titulo
            ORDER BY cantidad DESC
        ");
        
        return $query->getResultArray();
    }

    /**
     * Busca títulos por universidad
     */
    public function buscarPorUniversidad($universidad)
    {
        return $this->like('universidad', $universidad)
                    ->join('empleados', 'empleados.id = titulos_academicos.empleado_id')
                    ->where('empleados.activo', 1)
                    ->select('titulos_academicos.*, empleados.nombres, empleados.apellidos')
                    ->findAll();
    }

    /**
     * Obtiene títulos recientes (últimos 6 meses)
     */
    public function getTitulosRecientes()
    {
        $fechaLimite = date('Y-m-d', strtotime('-6 months'));
        
        return $this->where('fecha_obtencion >=', $fechaLimite)
                    ->join('empleados', 'empleados.id = titulos_academicos.empleado_id')
                    ->where('empleados.activo', 1)
                    ->select('titulos_academicos.*, empleados.nombres, empleados.apellidos')
                    ->orderBy('fecha_obtencion', 'DESC')
                    ->findAll();
    }

    /**
     * Valida que la fecha de obtención no sea futura
     */
    public function validarFechaObtencion($fecha)
    {
        $fechaObtencion = new \DateTime($fecha);
        $fechaActual = new \DateTime();
        
        return $fechaObtencion <= $fechaActual;
    }
}
