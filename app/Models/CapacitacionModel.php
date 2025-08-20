<?php

namespace App\Models;

use CodeIgniter\Model;

class CapacitacionModel extends Model
{
    protected $table            = 'capacitaciones';
    protected $primaryKey       = 'id_capacitacion';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'nombre', 'descripcion', 'tipo', 'proveedor', 'fecha_inicio',
        'fecha_fin', 'costo', 'estado', 'cupo_maximo', 'periodo_academico_id'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules      = [
        'nombre'     => 'required|min_length[3]|max_length[100]',
        'descripcion' => 'permit_empty|max_length[1000]',
        'tipo'       => 'permit_empty|max_length[50]',
        'proveedor'  => 'permit_empty|max_length[100]',
        'fecha_inicio' => 'required|valid_date',
        'fecha_fin'   => 'permit_empty|valid_date',
        'costo'       => 'permit_empty|decimal',
        'estado'      => 'permit_empty|in_list[Planificada,En curso,Completada,Cancelada]',
        'cupo_maximo' => 'permit_empty|integer|greater_than_equal_to[0]',
        'periodo_academico_id' => 'permit_empty|integer'
    ];

    protected $validationMessages = [
        'nombre' => [
            'required' => 'El nombre de la capacitación es obligatorio',
            'min_length' => 'El nombre debe tener al menos 3 caracteres',
            'max_length' => 'El nombre no puede exceder 100 caracteres'
        ],
        'tipo' => [
            'max_length' => 'El tipo no puede exceder 50 caracteres'
        ],
        'proveedor' => [
            'max_length' => 'El proveedor no puede exceder 100 caracteres'
        ],
        'fecha_inicio' => [
            'required' => 'La fecha de inicio es obligatoria',
            'valid_date' => 'La fecha de inicio debe ser una fecha válida'
        ],
        'fecha_fin' => [
            'valid_date' => 'La fecha de fin debe ser una fecha válida'
        ]
    ];

    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Obtener capacitaciones disponibles para un empleado
    public function getCapacitacionesDisponibles($idEmpleado)
    {
        $db = \Config\Database::connect();
        
        // Query simple sin subconsultas complejas
        $sql = "SELECT c.*, 
                       (SELECT COUNT(*) FROM empleados_capacitaciones ec WHERE ec.id_capacitacion = c.id_capacitacion) as inscritos_actuales
                FROM capacitaciones c
                WHERE c.estado = 'Planificada'
                AND c.fecha_inicio >= CURDATE()";
        
        if ($idEmpleado) {
            $sql .= " AND c.id_capacitacion NOT IN (SELECT ec.id_capacitacion FROM empleados_capacitaciones ec WHERE ec.id_empleado = ?)";
        }
        
        $sql .= " AND (SELECT COUNT(*) FROM empleados_capacitaciones ec WHERE ec.id_capacitacion = c.id_capacitacion) < c.cupo_maximo";
        
        if ($idEmpleado) {
            $query = $db->query($sql, [$idEmpleado]);
        } else {
            $query = $db->query($sql);
        }
        
        return $query->getResultArray();
    }

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
     * Obtiene estadísticas básicas de capacitaciones
     */
    public function getEstadisticasCapacitaciones()
    {
        $db = $this->db;
        
        $totalCapacitaciones = $db->table('capacitaciones')
            ->where('estado', 'Planificada')
            ->countAllResults();
            
        $capacitacionesPorTipo = $db->table('capacitaciones')
            ->select('tipo, COUNT(*) as total')
            ->where('estado', 'Planificada')
            ->groupBy('tipo')
            ->get()
            ->getResultArray();
            
        $capacitacionesPorProveedor = $db->table('capacitaciones')
            ->select('proveedor, COUNT(*) as total')
            ->where('estado', 'Planificada')
            ->groupBy('proveedor')
            ->get()
            ->getResultArray();
            
        return [
            'total' => $totalCapacitaciones,
            'por_tipo' => $capacitacionesPorTipo,
            'por_proveedor' => $capacitacionesPorProveedor
        ];
    }

    /**
     * Obtiene capacitaciones con estadísticas
     */
    public function getCapacitacionesConEstadisticas()
    {
        return $this->db->table('capacitaciones c')
            ->select('c.*, 
                     (SELECT COUNT(*) FROM empleados_capacitaciones ec WHERE ec.id_capacitacion = c.id_capacitacion) as empleados_asignados,
                     (SELECT COUNT(*) FROM empleados_capacitaciones ec WHERE ec.id_capacitacion = c.id_capacitacion AND ec.aprobo = 1) as empleados_completados')
            ->where('c.estado', 'Planificada')
            ->get()
            ->getResultArray();
    }

    /**
     * Obtiene capacitaciones por tipo para gráficos
     */
    public function getCapacitacionesPorTipoParaGraficos()
    {
        return $this->db->table('capacitaciones')
            ->select('tipo, COUNT(*) as total')
            ->where('estado', 'Planificada')
            ->groupBy('tipo')
            ->get()
            ->getResultArray();
    }

    /**
     * Obtiene capacitaciones activas
     */
    public function getCapacitacionesActivas()
    {
        return $this->where('estado', 'Planificada')->findAll();
    }

    /**
     * Obtiene capacitaciones por institución
     */
    public function getCapacitacionesPorProveedor($proveedor = null)
    {
        $builder = $this->where('estado', 'Planificada');
        
        if ($proveedor) {
            $builder->where('proveedor', $proveedor);
        }
        
        return $builder->findAll();
    }

    /**
     * Obtiene capacitaciones por período
     */
    public function getCapacitacionesPorPeriodo($fechaInicio, $fechaFin)
    {
        return $this->where('estado', 'Planificada')
            ->where('fecha_inicio >=', $fechaInicio)
            ->where('fecha_fin <=', $fechaFin)
            ->findAll();
    }

    /**
     * Obtiene capacitaciones recientes
     */
    public function getCapacitacionesRecientes($limite = 10)
    {
        return $this->where('estado', 'Planificada')
            ->orderBy('created_at', 'DESC')
            ->limit($limite)
            ->findAll();
    }
} 