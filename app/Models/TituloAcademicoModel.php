<?php

namespace App\Models;

use CodeIgniter\Model;

class TituloAcademicoModel extends Model
{
    protected $table            = 'titulos_academicos';
    protected $primaryKey       = 'id_titulo_academico';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'empleado_id', 'universidad', 'tipo_titulo', 'nombre_titulo', 
        'fecha_obtencion', 'pais', 'archivo_titulo', 'observaciones', 'creado_por'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules      = [
        'empleado_id' => 'required|integer|greater_than[0]',
        'universidad' => 'required|min_length[2]|max_length[255]',
        'tipo_titulo' => 'required|in_list[Tercer Nivel,Cuarto Nivel,Ph.D.,Doctorado,Maestría,Especialización,Otro]',
        'nombre_titulo' => 'required|min_length[5]|max_length[255]',
        'fecha_obtencion' => 'required|valid_date',
        'pais' => 'permit_empty|min_length[2]|max_length[100]',
        'observaciones' => 'permit_empty|max_length[1000]'
    ];
    protected $validationMessages   = [
        'empleado_id' => [
            'required' => 'El empleado es obligatorio',
            'integer' => 'El ID del empleado debe ser un número entero',
            'greater_than' => 'El ID del empleado debe ser mayor a 0'
        ],
        'universidad' => [
            'required' => 'La universidad es obligatoria',
            'min_length' => 'La universidad debe tener al menos 2 caracteres',
            'max_length' => 'La universidad no puede exceder 255 caracteres'
        ],
        'tipo_titulo' => [
            'required' => 'El tipo de título es obligatorio',
            'in_list' => 'El tipo de título seleccionado no es válido'
        ],
        'nombre_titulo' => [
            'required' => 'El nombre del título es obligatorio',
            'min_length' => 'El nombre del título debe tener al menos 5 caracteres',
            'max_length' => 'El nombre del título no puede exceder 255 caracteres'
        ],
        'fecha_obtencion' => [
            'required' => 'La fecha de obtención es obligatoria',
            'valid_date' => 'La fecha de obtención no es válida'
        ],
        'pais' => [
            'min_length' => 'El país debe tener al menos 2 caracteres',
            'max_length' => 'El país no puede exceder 100 caracteres'
        ],
        'observaciones' => [
            'max_length' => 'Las observaciones no pueden exceder 1000 caracteres'
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
     * Obtener títulos académicos completos con información del empleado
     */
    public function getTitulosCompletos()
    {
        $builder = $this->db->table('titulos_academicos ta');
        $builder->select('ta.*, e.nombres, e.apellidos, e.cedula, e.tipo_empleado, e.departamento');
        $builder->join('empleados e', 'ta.empleado_id = e.id');
        $builder->orderBy('ta.fecha_obtencion', 'DESC');
        
        return $builder->get()->getResultArray();
    }

    /**
     * Obtener títulos académicos por empleado
     */
    public function getTitulosPorEmpleado($idEmpleado)
    {
        return $this->where('empleado_id', $idEmpleado)
                    ->orderBy('fecha_obtencion', 'DESC')
                    ->findAll();
    }

    /**
     * Obtener estadísticas generales
     */
    public function getEstadisticas()
    {
        $estadisticas = [
            'total_titulos' => $this->countAll(),
            'por_tipo' => [],
            'por_universidad' => [],
            'por_pais' => [],
            'por_ano' => []
        ];

        // Estadísticas por tipo de título
        $tipos = $this->select('tipo_titulo, COUNT(*) as total')
                      ->groupBy('tipo_titulo')
                      ->get()
                      ->getResultArray();
        
        foreach ($tipos as $tipo) {
            $estadisticas['por_tipo'][$tipo['tipo_titulo']] = $tipo['total'];
        }

        // Estadísticas por universidad
        $universidades = $this->select('universidad, COUNT(*) as total')
                             ->groupBy('universidad')
                             ->orderBy('total', 'DESC')
                             ->limit(10)
                             ->get()
                             ->getResultArray();
        
        foreach ($universidades as $universidad) {
            $estadisticas['por_universidad'][$universidad['universidad']] = $universidad['total'];
        }

        // Estadísticas por país
        $paises = $this->select('pais, COUNT(*) as total')
                       ->where('pais IS NOT NULL')
                       ->where('pais !=', '')
                       ->groupBy('pais')
                       ->orderBy('total', 'DESC')
                       ->get()
                       ->getResultArray();
        
        foreach ($paises as $pais) {
            $estadisticas['por_pais'][$pais['pais']] = $pais['total'];
        }

        // Estadísticas por año
        $anos = $this->select('YEAR(fecha_obtencion) as ano, COUNT(*) as total')
                     ->groupBy('YEAR(fecha_obtencion)')
                     ->orderBy('ano', 'DESC')
                     ->get()
                     ->getResultArray();
        
        foreach ($anos as $ano) {
            $estadisticas['por_ano'][$ano['ano']] = $ano['total'];
        }

        return $estadisticas;
    }

    /**
     * Obtener estadísticas por empleado
     */
    public function getEstadisticasPorEmpleado($idEmpleado)
    {
        $estadisticas = [
            'total_titulos' => 0,
            'por_tipo' => [],
            'por_universidad' => [],
            'ultimo_titulo' => null,
            'nivel_mas_alto' => null
        ];

        // Total de títulos
        $total = $this->where('empleado_id', $idEmpleado)->countAllResults();
        $estadisticas['total_titulos'] = $total;

        if ($total > 0) {
            // Por tipo
            $porTipo = $this->select('tipo_titulo, COUNT(*) as total')
                            ->where('empleado_id', $idEmpleado)
                            ->groupBy('tipo_titulo')
                            ->get()
                            ->getResultArray();
            
            foreach ($porTipo as $tipo) {
                $estadisticas['por_tipo'][$tipo['tipo_titulo']] = $tipo['total'];
            }

            // Por universidad
            $porUniversidad = $this->select('universidad, COUNT(*) as total')
                                   ->where('empleado_id', $idEmpleado)
                                   ->groupBy('universidad')
                                   ->get()
                                   ->getResultArray();
            
            foreach ($porUniversidad as $universidad) {
                $estadisticas['por_universidad'][$universidad['universidad']] = $universidad['total'];
            }

            // Último título obtenido
            $ultimo = $this->where('empleado_id', $idEmpleado)
                           ->orderBy('fecha_obtencion', 'DESC')
                           ->first();
            $estadisticas['ultimo_titulo'] = $ultimo;

            // Nivel más alto (prioridad: Ph.D. > Doctorado > Maestría > Cuarto Nivel > Tercer Nivel)
            $niveles = [
                'Ph.D.' => 5,
                'Doctorado' => 4,
                'Maestría' => 3,
                'Cuarto Nivel' => 2,
                'Tercer Nivel' => 1,
                'Especialización' => 2,
                'Otro' => 0
            ];

            $titulos = $this->where('empleado_id', $idEmpleado)->findAll();
            $nivelMasAlto = null;
            $prioridadMasAlta = -1;

            foreach ($titulos as $titulo) {
                $prioridad = $niveles[$titulo['tipo_titulo']] ?? 0;
                if ($prioridad > $prioridadMasAlta) {
                    $prioridadMasAlta = $prioridad;
                    $nivelMasAlto = $titulo;
                }
            }

            $estadisticas['nivel_mas_alto'] = $nivelMasAlto;
        }

        return $estadisticas;
    }

    /**
     * Buscar títulos académicos
     */
    public function buscarTitulos($termino = null, $tipo = null, $universidad = null)
    {
        $builder = $this->db->table('titulos_academicos ta');
        $builder->select('ta.*, e.nombres, e.apellidos, e.cedula, e.tipo_empleado, e.departamento');
        $builder->join('empleados e', 'ta.empleado_id = e.id_empleado');

        if ($termino) {
            $builder->groupStart()
                    ->like('ta.nombre_titulo', $termino)
                    ->orLike('ta.universidad', $termino)
                    ->orLike('e.nombres', $termino)
                    ->orLike('e.apellidos', $termino)
                    ->orLike('e.cedula', $termino)
                    ->groupEnd();
        }

        if ($tipo) {
            $builder->where('ta.tipo_titulo', $tipo);
        }

        if ($universidad) {
            $builder->like('ta.universidad', $universidad);
        }

        $builder->orderBy('ta.fecha_obtencion', 'DESC');
        
        return $builder->get()->getResultArray();
    }

    /**
     * Obtener títulos por período
     */
    public function getTitulosPorPeriodo($fechaInicio, $fechaFin)
    {
        $builder = $this->db->table('titulos_academicos ta');
        $builder->select('ta.*, e.nombres, e.apellidos, e.cedula, e.tipo_empleado, e.departamento');
        $builder->join('empleados e', 'ta.empleado_id = e.id_empleado');
        $builder->where('ta.fecha_obtencion >=', $fechaInicio);
        $builder->where('ta.fecha_obtencion <=', $fechaFin);
        $builder->orderBy('ta.fecha_obtencion', 'DESC');
        
        return $builder->get()->getResultArray();
    }

    /**
     * Obtener estadísticas por período
     */
    public function getEstadisticasPorPeriodo($fechaInicio, $fechaFin)
    {
        $estadisticas = [
            'total_titulos' => 0,
            'por_tipo' => [],
            'por_universidad' => [],
            'por_mes' => []
        ];

        // Total de títulos en el período
        $total = $this->where('fecha_obtencion >=', $fechaInicio)
                      ->where('fecha_obtencion <=', $fechaFin)
                      ->countAllResults();
        $estadisticas['total_titulos'] = $total;

        if ($total > 0) {
            // Por tipo en el período
            $tipos = $this->select('tipo_titulo, COUNT(*) as total')
                          ->where('fecha_obtencion >=', $fechaInicio)
                          ->where('fecha_obtencion <=', $fechaFin)
                          ->groupBy('tipo_titulo')
                          ->get()
                          ->getResultArray();
            
            foreach ($tipos as $tipo) {
                $estadisticas['por_tipo'][$tipo['tipo_titulo']] = $tipo['total'];
            }

            // Por universidad en el período
            $universidades = $this->select('universidad, COUNT(*) as total')
                                 ->where('fecha_obtencion >=', $fechaInicio)
                                 ->where('fecha_obtencion <=', $fechaFin)
                                 ->groupBy('universidad')
                                 ->orderBy('total', 'DESC')
                                 ->limit(10)
                                 ->get()
                                 ->getResultArray();
            
            foreach ($universidades as $universidad) {
                $estadisticas['por_universidad'][$universidad['universidad']] = $universidad['total'];
            }

            // Por mes en el período
            $meses = $this->select('DATE_FORMAT(fecha_obtencion, "%Y-%m") as mes, COUNT(*) as total')
                          ->where('fecha_obtencion >=', $fechaInicio)
                          ->where('fecha_obtencion <=', $fechaFin)
                          ->groupBy('mes')
                          ->orderBy('mes', 'ASC')
                          ->get()
                          ->getResultArray();
            
            foreach ($meses as $mes) {
                $estadisticas['por_mes'][$mes['mes']] = $mes['total'];
            }
        }

        return $estadisticas;
    }

    /**
     * Obtener títulos recientes
     */
    public function getTitulosRecientes($limite = 10)
    {
        $builder = $this->db->table('titulos_academicos ta');
        $builder->select('ta.*, e.nombres, e.apellidos, e.cedula');
        $builder->join('empleados e', 'ta.empleado_id = e.id_empleado');
        $builder->orderBy('ta.fecha_obtencion', 'DESC');
        $builder->limit($limite);
        
        return $builder->get()->getResultArray();
    }

    /**
     * Obtener títulos por universidad
     */
    public function getTitulosPorUniversidad($universidad)
    {
        $builder = $this->db->table('titulos_academicos ta');
        $builder->select('ta.*, e.nombres, e.apellidos, e.cedula, e.tipo_empleado, e.departamento');
        $builder->join('empleados e', 'ta.empleado_id = e.id_empleado');
        $builder->like('ta.universidad', $universidad);
        $builder->orderBy('ta.fecha_obtencion', 'DESC');
        
        return $builder->get()->getResultArray();
    }

    /**
     * Obtener empleados con más títulos académicos
     */
    public function getEmpleadosConMasTitulos($limite = 10)
    {
        $builder = $this->db->table('titulos_academicos ta');
        $builder->select('e.id_empleado, e.nombres, e.apellidos, e.cedula, e.tipo_empleado, e.departamento, COUNT(ta.id_titulo_academico) as total_titulos');
        $builder->join('empleados e', 'ta.empleado_id = e.id_empleado');
        $builder->groupBy('e.id_empleado');
        $builder->orderBy('total_titulos', 'DESC');
        $builder->limit($limite);
        
        return $builder->get()->getResultArray();
    }

    /**
     * Verificar si un empleado ya tiene un título similar
     */
    public function tieneTituloSimilar($idEmpleado, $tipoTitulo, $nombreTitulo, $excluirId = null)
    {
        $builder = $this->where('empleado_id', $idEmpleado)
                        ->where('tipo_titulo', $tipoTitulo)
                        ->like('nombre_titulo', $nombreTitulo);
        
        if ($excluirId) {
            $builder->where('id_titulo_academico !=', $excluirId);
        }
        
        return $builder->countAllResults() > 0;
    }
}
