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
        'institucion', 'duracion_horas', 'archivo_certificado', 'estado', 'creado_por'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules      = [
        'nombre' => 'required|min_length[5]|max_length[255]',
        'descripcion' => 'required|min_length[10]',
        'tipo' => 'required|in_list[Técnica,Pedagógica,Administrativa,Soft Skills,Otra]',
        'fecha_inicio' => 'required|valid_date',
        'fecha_fin' => 'required|valid_date',
        'institucion' => 'required|min_length[2]|max_length[255]',
        'duracion_horas' => 'required|integer|greater_than[0]',
        'estado' => 'required|in_list[Activa,Finalizada,Cancelada]'
    ];
    protected $validationMessages   = [
        'nombre' => [
            'required' => 'El nombre de la capacitación es obligatorio',
            'min_length' => 'El nombre debe tener al menos 5 caracteres',
            'max_length' => 'El nombre no puede exceder 255 caracteres'
        ],
        'descripcion' => [
            'required' => 'La descripción es obligatoria',
            'min_length' => 'La descripción debe tener al menos 10 caracteres'
        ],
        'tipo' => [
            'required' => 'El tipo de capacitación es obligatorio',
            'in_list' => 'El tipo seleccionado no es válido'
        ],
        'fecha_inicio' => [
            'required' => 'La fecha de inicio es obligatoria',
            'valid_date' => 'La fecha de inicio no es válida'
        ],
        'fecha_fin' => [
            'required' => 'La fecha de fin es obligatoria',
            'valid_date' => 'La fecha de fin no es válida'
        ],
        'institucion' => [
            'required' => 'La institución es obligatoria',
            'min_length' => 'La institución debe tener al menos 2 caracteres',
            'max_length' => 'La institución no puede exceder 255 caracteres'
        ],
        'duracion_horas' => [
            'required' => 'La duración en horas es obligatoria',
            'integer' => 'La duración debe ser un número entero',
            'greater_than' => 'La duración debe ser mayor a 0'
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
    protected $beforeUpdate   = ['setCreadoPor'];

    protected function setCreadoPor(array $data)
    {
        if (isset($data['data']['creado_por'])) {
            $data['data']['creado_por'] = session()->get('id_usuario') ?? 1;
        }
        return $data;
    }

    /**
     * Obtener capacitaciones completas con información de empleados inscritos
     */
    public function getCapacitacionesCompletas()
    {
        $builder = $this->db->table('capacitaciones c');
        $builder->select('c.*, COUNT(ec.id_empleado_capacitacion) as total_inscritos');
        $builder->join('empleado_capacitaciones ec', 'c.id_capacitacion = ec.id_capacitacion', 'left');
        $builder->groupBy('c.id_capacitacion');
        $builder->orderBy('c.created_at', 'DESC');
        
        return $builder->get()->getResultArray();
    }

    /**
     * Obtener capacitaciones disponibles (activas y futuras)
     */
    public function getCapacitacionesDisponibles()
    {
        $builder = $this->db->table('capacitaciones c');
        $builder->select('c.*');
        $builder->where('c.estado', 'Activa');
        $builder->where('c.fecha_inicio >=', date('Y-m-d'));
        $builder->orderBy('c.fecha_inicio', 'ASC');
        
        return $builder->get()->getResultArray();
    }

    /**
     * Obtener capacitaciones por tipo
     */
    public function getCapacitacionesPorTipo($tipo)
    {
        return $this->where('tipo', $tipo)
                    ->where('estado', 'Activa')
                    ->orderBy('fecha_inicio', 'ASC')
                    ->findAll();
    }

    /**
     * Obtener capacitaciones por empleado
     */
    public function getCapacitacionesPorEmpleado($idEmpleado)
    {
        $builder = $this->db->table('capacitaciones c');
        $builder->select('c.*, ec.estado as estado_empleado, ec.fecha_inscripcion, ec.puntaje, ec.observaciones');
        $builder->join('empleado_capacitaciones ec', 'c.id_capacitacion = ec.id_capacitacion');
        $builder->where('ec.id_empleado', $idEmpleado);
        $builder->orderBy('c.fecha_inicio', 'DESC');
        
        return $builder->get()->getResultArray();
    }

    /**
     * Obtener capacitaciones por departamento
     */
    public function getCapacitacionesPorDepartamento($departamento)
    {
        $builder = $this->db->table('capacitaciones c');
        $builder->select('c.*, COUNT(ec.id_empleado_capacitacion) as total_inscritos');
        $builder->join('empleado_capacitaciones ec', 'c.id_capacitacion = ec.id_capacitacion', 'left');
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
                    ->where('estado', 'Activa')
                    ->orderBy('fecha_inicio', 'ASC')
                    ->findAll();
    }

    /**
     * Obtener capacitaciones próximas a vencer
     */
    public function getCapacitacionesProximasAVencer($dias = 7)
    {
        $fechaLimite = date('Y-m-d', strtotime("+{$dias} days"));
        
        return $this->where('estado', 'Activa')
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
        return $this->where('estado', 'Activa')
                    ->where('fecha_fin <', date('Y-m-d'))
                    ->orderBy('fecha_fin', 'DESC')
                    ->findAll();
    }

    /**
     * Marcar capacitación como finalizada
     */
    public function marcarComoFinalizada($idCapacitacion)
    {
        return $this->update($idCapacitacion, ['estado' => 'Finalizada']);
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
        $builder->join('empleado_capacitaciones ec', 'c.id_capacitacion = ec.id_capacitacion', 'left');
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
        $empleadoCapacitacionModel = new \App\Models\EmpleadoCapacitacionModel();
        
        $datos = [
            'id_capacitacion' => $idCapacitacion,
            'id_empleado' => $idEmpleado,
            'fecha_asignacion' => date('Y-m-d H:i:s'),
            'estado' => 'Asignado'
        ];
        
        return $empleadoCapacitacionModel->insert($datos);
    }
} 