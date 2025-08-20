<?php

namespace App\Models;

use CodeIgniter\Model;

class TituloAcademicoModel extends Model
{
    protected $table            = 'titulos_academicos';
    protected $primaryKey       = 'id_titulo';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields    = [
        'id_empleado', 'universidad', 'tipo_titulo', 'nombre_titulo',
        'fecha_obtencion', 'pais', 'archivo_certificado', 'estado', 'observaciones'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules = [
        'id_empleado'      => 'required|integer',
        'universidad'      => 'required|min_length[3]|max_length[200]',
        'tipo_titulo'      => 'required|in_list[Tercer nivel,Cuarto nivel,Doctorado/PhD]',
        'nombre_titulo'    => 'required|min_length[5]|max_length[300]',
        'fecha_obtencion'  => 'required|valid_date',
        'pais'             => 'permit_empty|max_length[100]',
        'estado'           => 'required|in_list[Activo,Inactivo,Pendiente]'
    ];

    protected $validationMessages = [
        'id_empleado' => [
            'required' => 'El ID del empleado es obligatorio',
            'integer' => 'El ID del empleado debe ser un número entero'
        ],
        'universidad' => [
            'required' => 'La universidad es obligatoria',
            'min_length' => 'La universidad debe tener al menos 3 caracteres',
            'max_length' => 'La universidad no puede exceder 200 caracteres'
        ],
        'tipo_titulo' => [
            'required' => 'El tipo de título es obligatorio',
            'in_list' => 'El tipo de título debe ser: Tercer nivel, Cuarto nivel o Doctorado/PhD'
        ],
        'nombre_titulo' => [
            'required' => 'El nombre del título es obligatorio',
            'min_length' => 'El nombre del título debe tener al menos 5 caracteres',
            'max_length' => 'El nombre del título no puede exceder 300 caracteres'
        ],
        'fecha_obtencion' => [
            'required' => 'La fecha de obtención es obligatoria',
            'valid_date' => 'La fecha de obtención debe ser una fecha válida'
        ],
        'estado' => [
            'required' => 'El estado es obligatorio',
            'in_list' => 'El estado debe ser: Activo, Inactivo o Pendiente'
        ]
    ];

    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    /**
     * Obtiene títulos por empleado
     */
    public function getTitulosPorEmpleado($empleadoId)
    {
        return $this->where('id_empleado', $empleadoId)
                    ->orderBy('fecha_obtencion', 'DESC')
                    ->findAll();
    }

    /**
     * Obtiene títulos por tipo
     */
    public function getTitulosPorTipo($tipo)
    {
        return $this->where('tipo_titulo', $tipo)
                    ->orderBy('fecha_obtencion', 'DESC')
                    ->findAll();
    }

    /**
     * Obtiene títulos por universidad
     */
    public function getTitulosPorUniversidad($universidad)
    {
        return $this->like('universidad', $universidad)
                    ->orderBy('fecha_obtencion', 'DESC')
                    ->findAll();
    }

    /**
     * Obtiene todos los títulos con información del empleado
     */
    public function getTitulosCompletos()
    {
        return $this->db->table('titulos_academicos ta')
                        ->select('ta.*, e.nombres, e.apellidos, e.cedula')
                        ->join('empleados e', 'e.id_empleado = ta.id_empleado', 'left')
                        ->orderBy('ta.fecha_obtencion', 'DESC')
                        ->get()
                        ->getResultArray();
    }

    /**
     * Obtiene estadísticas de títulos
     */
    public function getEstadisticasTitulos()
    {
        $total = $this->countAll();
        $porTipo = $this->db->table('titulos_academicos')
                            ->select('tipo_titulo, COUNT(*) as total')
                            ->groupBy('tipo_titulo')
                            ->get()
                            ->getResultArray();
        
        $porUniversidad = $this->db->table('titulos_academicos')
                                   ->select('universidad, COUNT(*) as total')
                                   ->groupBy('universidad')
                                   ->orderBy('total', 'DESC')
                                   ->limit(10)
                                   ->get()
                                   ->getResultArray();

        return [
            'total' => $total,
            'por_tipo' => $porTipo,
            'por_universidad' => $porUniversidad
        ];
    }

    /**
     * Obtiene títulos recientes
     */
    public function getTitulosRecientes($limit = 10)
    {
        return $this->orderBy('created_at', 'DESC')
                    ->limit($limit)
                    ->findAll();
    }

    /**
     * Busca títulos por criterios
     */
    public function buscarTitulos($criterios = [])
    {
        $builder = $this->db->table('titulos_academicos ta')
                            ->select('ta.*, e.nombres, e.apellidos, e.cedula')
                            ->join('empleados e', 'e.id_empleado = ta.id_empleado', 'left');

        if (!empty($criterios['tipo_titulo'])) {
            $builder->where('ta.tipo_titulo', $criterios['tipo_titulo']);
        }

        if (!empty($criterios['universidad'])) {
            $builder->like('ta.universidad', $criterios['universidad']);
        }

        if (!empty($criterios['estado'])) {
            $builder->where('ta.estado', $criterios['estado']);
        }

        if (!empty($criterios['fecha_desde'])) {
            $builder->where('ta.fecha_obtencion >=', $criterios['fecha_desde']);
        }

        if (!empty($criterios['fecha_hasta'])) {
            $builder->where('ta.fecha_obtencion <=', $criterios['fecha_hasta']);
        }

        return $builder->orderBy('ta.fecha_obtencion', 'DESC')
                      ->get()
                      ->getResultArray();
    }

    /**
     * Verifica si un empleado tiene un título específico
     */
    public function tieneTitulo($empleadoId, $tipoTitulo)
    {
        return $this->where('id_empleado', $empleadoId)
                    ->where('tipo_titulo', $tipoTitulo)
                    ->where('estado', 'Activo')
                    ->first();
    }

    /**
     * Obtiene el título más alto de un empleado
     */
    public function getTituloMasAlto($empleadoId)
    {
        $titulos = $this->where('id_empleado', $empleadoId)
                        ->where('estado', 'Activo')
                        ->orderBy('fecha_obtencion', 'DESC')
                        ->findAll();

        if (empty($titulos)) {
            return null;
        }

        // Priorizar por tipo de título
        $prioridad = [
            'Doctorado/PhD' => 3,
            'Cuarto nivel' => 2,
            'Tercer nivel' => 1
        ];

        $tituloMasAlto = $titulos[0];
        foreach ($titulos as $titulo) {
            if ($prioridad[$titulo['tipo_titulo']] > $prioridad[$tituloMasAlto['tipo_titulo']]) {
                $tituloMasAlto = $titulo;
            }
        }

        return $tituloMasAlto;
    }
}
