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
        'nombre', 'descripcion', 'tipo', 'fecha_inicio', 'fecha_fin', 
        'duracion_horas', 'modalidad', 'institucion', 'archivo_certificado', 
        'estado', 'creado_por', 'cupo_maximo', 'costo', 'proveedor',
        'periodo_academico_id'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules      = [
        'nombre' => 'required|min_length[5]|max_length[255]',
        'descripcion' => 'permit_empty|max_length[2000]',
        'tipo' => 'permit_empty|max_length[100]',
        'fecha_inicio' => 'required|valid_date',
        'fecha_fin' => 'permit_empty|valid_date',
        'institucion' => 'permit_empty|max_length[255]',
        'duracion_horas' => 'permit_empty|integer|greater_than[0]',
        'modalidad' => 'permit_empty|in_list[PRESENCIAL,VIRTUAL,HIBRIDA]',
        'estado' => 'required|in_list[ACTIVA,INACTIVA,EN_CURSO,COMPLETADA,CANCELADA]'
    ];
    protected $validationMessages   = [
        'nombre' => [
            'required' => 'El nombre de la capacitación es obligatorio',
            'min_length' => 'El nombre debe tener al menos 5 caracteres',
            'max_length' => 'El nombre no puede exceder 255 caracteres'
        ],
        'descripcion' => [
            'max_length' => 'La descripción no puede exceder 2000 caracteres'
        ],
        'fecha_inicio' => [
            'valid_date' => 'La fecha de inicio no es válida'
        ],
        'fecha_fin' => [
            'valid_date' => 'La fecha de fin no es válida'
        ],
        'duracion_horas' => [
            'integer' => 'La duración debe ser un número entero',
            'greater_than' => 'La duración debe ser mayor a 0'
        ],
        'modalidad' => [
            'in_list' => 'La modalidad seleccionada no es válida'
        ],
        'estado' => [
            'required' => 'El estado es obligatorio',
            'in_list' => 'El estado seleccionado no es válido'
        ]
    ];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = ['setCreadoPor'];
    protected $beforeUpdate   = [];

    protected function setCreadoPor(array $data)
    {
        if (!isset($data['data']['creado_por']) || empty($data['data']['creado_por'])) {
            $data['data']['creado_por'] = session()->get('id_usuario') ?? 1;
        }
        return $data;
    }

    /**
     * Obtener capacitaciones con estadísticas (usado por obtenerCapacitaciones en AdminTH)
     */
    public function getCapacitacionesConEstadisticas()
    {
        $builder = $this->db->table('capacitaciones c');
        $builder->select('c.*, COUNT(ec.id_empleado_capacitacion) as total_inscritos');
        $builder->join('empleados_capacitaciones ec', 'c.id_capacitacion = ec.id_capacitacion', 'left');
        $builder->groupBy('c.id_capacitacion');
        $builder->orderBy('c.created_at', 'DESC');
        
        return $builder->get()->getResultArray();
    }

    /**
     * Obtener capacitaciones completas con información de empleados inscritos
     */
    public function getCapacitacionesCompletas()
    {
        $builder = $this->db->table('capacitaciones c');
        $builder->select('c.*, COUNT(ec.id_empleado_capacitacion) as total_inscritos');
        $builder->join('empleados_capacitaciones ec', 'c.id_capacitacion = ec.id_capacitacion', 'left');
        $builder->groupBy('c.id_capacitacion');
        $builder->orderBy('c.created_at', 'DESC');
        
        return $builder->get()->getResultArray();
    }

    /**
     * Obtener capacitaciones visibles para empleados (ACTIVA o EN_CURSO)
     */
    public function getCapacitacionesVisiblesEmpleado()
    {
        $builder = $this->db->table('capacitaciones c');
        $builder->select('c.*, COUNT(ec.id_empleado_capacitacion) as total_inscritos');
        $builder->join('empleados_capacitaciones ec', 'c.id_capacitacion = ec.id_capacitacion', 'left');
        $builder->whereIn('c.estado', ['ACTIVA', 'EN_CURSO', 'COMPLETADA']);
        $builder->groupBy('c.id_capacitacion');
        $builder->orderBy('c.fecha_inicio', 'ASC');
        
        return $builder->get()->getResultArray();
    }

    /**
     * Obtener capacitaciones disponibles (activas y futuras)
     */
    public function getCapacitacionesDisponibles()
    {
        $builder = $this->db->table('capacitaciones c');
        $builder->select('c.*');
        $builder->where('c.estado', 'ACTIVA');
        $builder->orderBy('c.fecha_inicio', 'ASC');
        
        return $builder->get()->getResultArray();
    }

    /**
     * Contar inscritos en una capacitación
     */
    public function contarInscritos($idCapacitacion)
    {
        $builder = $this->db->table('empleados_capacitaciones');
        $builder->where('id_capacitacion', $idCapacitacion);
        return $builder->countAllResults();
    }

    /**
     * Obtener capacitaciones por tipo
     */
    public function getCapacitacionesPorTipo($tipo)
    {
        return $this->where('tipo', $tipo)
                    ->where('estado', 'ACTIVA')
                    ->orderBy('fecha_inicio', 'ASC')
                    ->findAll();
    }

    /**
     * Obtener capacitaciones por empleado
     */
    public function getCapacitacionesPorEmpleado($idEmpleado)
    {
        $builder = $this->db->table('capacitaciones c');
        $builder->select('c.*, ec.asistio, ec.aprobo, ec.certificado_url');
        $builder->join('empleados_capacitaciones ec', 'c.id_capacitacion = ec.id_capacitacion');
        $builder->where('ec.id_empleado', $idEmpleado);
        $builder->orderBy('c.fecha_inicio', 'DESC');
        
        return $builder->get()->getResultArray();
    }

    /**
     * Verificar si un empleado ya está inscrito en una capacitación
     */
    public function empleadoInscrito($idCapacitacion, $idEmpleado)
    {
        $builder = $this->db->table('empleados_capacitaciones');
        $builder->where('id_capacitacion', $idCapacitacion);
        $builder->where('id_empleado', $idEmpleado);
        return $builder->countAllResults() > 0;
    }

    /**
     * Obtener capacitaciones por departamento
     */
    public function getCapacitacionesPorDepartamento($departamento)
    {
        $builder = $this->db->table('capacitaciones c');
        $builder->select('c.*, COUNT(ec.id_empleado_capacitacion) as total_inscritos');
        $builder->join('empleados_capacitaciones ec', 'c.id_capacitacion = ec.id_capacitacion', 'left');
        $builder->join('empleados e', 'ec.id_empleado = e.id_empleado', 'left');
        $builder->where('e.departamento', $departamento);
        $builder->groupBy('c.id_capacitacion');
        $builder->orderBy('c.fecha_inicio', 'DESC');
        
        return $builder->get()->getResultArray();
    }

    /**
     * Obtener estadísticas de capacitaciones
     */
    public function getEstadisticas()
    {
        $estadisticas = [
            'total' => $this->countAll(),
            'por_tipo' => [],
            'por_estado' => [],
            'por_mes' => []
        ];

        // Estadísticas por tipo
        $tipos = $this->select('tipo, COUNT(*) as total')
                      ->groupBy('tipo')
                      ->findAll();
        
        foreach ($tipos as $tipo) {
            $estadisticas['por_tipo'][$tipo['tipo']] = $tipo['total'];
        }

        // Estadísticas por estado
        $estados = $this->select('estado, COUNT(*) as total')
                        ->groupBy('estado')
                        ->findAll();
        
        foreach ($estados as $estado) {
            $estadisticas['por_estado'][$estado['estado']] = $estado['total'];
        }

        // Estadísticas por mes (últimos 12 meses)
        $meses = $this->select('DATE_FORMAT(fecha_inicio, "%Y-%m") as mes, COUNT(*) as total')
                      ->where('fecha_inicio >=', date('Y-m-01', strtotime('-11 months')))
                      ->groupBy('mes')
                      ->orderBy('mes', 'ASC')
                      ->findAll();
        
        foreach ($meses as $mes) {
            $estadisticas['por_mes'][$mes['mes']] = $mes['total'];
        }

        return $estadisticas;
    }

    /**
     * Buscar capacitaciones por texto
     */
    public function buscarCapacitaciones($texto)
    {
        return $this->like('nombre', $texto)
                    ->orLike('descripcion', $texto)
                    ->orLike('institucion', $texto)
                    ->orLike('tipo', $texto)
                    ->where('estado', 'ACTIVA')
                    ->orderBy('fecha_inicio', 'ASC')
                    ->findAll();
    }

    /**
     * Obtener capacitaciones próximas a vencer
     */
    public function getCapacitacionesProximasAVencer($dias = 7)
    {
        $fechaLimite = date('Y-m-d', strtotime("+{$dias} days"));
        
        return $this->where('estado', 'ACTIVA')
                    ->where('fecha_fin <=', $fechaLimite)
                    ->where('fecha_fin >=', date('Y-m-d'))
                    ->orderBy('fecha_fin', 'ASC')
                    ->findAll();
    }

    /**
     * Obtener capacitaciones vencidas
     */
    public function getCapacitacionesVencidas()
    {
        return $this->where('estado', 'ACTIVA')
                    ->where('fecha_fin <', date('Y-m-d'))
                    ->orderBy('fecha_fin', 'DESC')
                    ->findAll();
    }

    /**
     * Marcar capacitación como finalizada
     */
    public function marcarComoFinalizada($idCapacitacion)
    {
        return $this->update($idCapacitacion, ['estado' => 'COMPLETADA']);
    }

    /**
     * Obtener capacitaciones por período
     */
    public function getCapacitacionesPorPeriodo($fechaInicio, $fechaFin)
    {
        return $this->where('fecha_inicio >=', $fechaInicio)
                    ->where('fecha_fin <=', $fechaFin)
                    ->orderBy('fecha_inicio', 'ASC')
                    ->findAll();
    }

    /**
     * Obtener capacitaciones más populares (con más empleados inscritos)
     */
    public function getCapacitacionesPopulares($limite = 10)
    {
        $builder = $this->db->table('capacitaciones c');
        $builder->select('c.*, COUNT(ec.id_empleado_capacitacion) as total_inscritos');
        $builder->join('empleados_capacitaciones ec', 'c.id_capacitacion = ec.id_capacitacion', 'left');
        $builder->groupBy('c.id_capacitacion');
        $builder->orderBy('total_inscritos', 'DESC');
        $builder->limit($limite);
        
        return $builder->get()->getResultArray();
    }

    /**
     * Asignar empleado a una capacitación
     */
    public function asignarEmpleado($idCapacitacion, $idEmpleado)
    {
        $db = \Config\Database::connect();
        
        $datos = [
            'id_capacitacion' => $idCapacitacion,
            'id_empleado' => $idEmpleado
        ];
        
        return $db->table('empleados_capacitaciones')->insert($datos);
    }
}