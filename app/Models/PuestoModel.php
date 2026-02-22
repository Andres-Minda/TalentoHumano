<?php

namespace App\Models;

use CodeIgniter\Model;

class PuestoModel extends Model
{
    protected $table            = 'puestos';
    protected $primaryKey       = 'id_puesto';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields = [
        'titulo',
        'descripcion',
        'id_departamento',
        'tipo_contrato',
        'salario_min',
        'salario_max',
        'experiencia_requerida',
        'educacion_requerida',
        'habilidades_requeridas',
        'responsabilidades',
        'beneficios',
        'estado',
        'activo',
        'url_postulacion',
        'fecha_limite',
        'vacantes_disponibles',
        'nivel_experiencia',
        'modalidad_trabajo',
        'ubicacion_trabajo'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validaciones
    protected $validationRules = [
        'titulo'                 => 'required|min_length[5]|max_length[200]',
        'descripcion'            => 'required|min_length[20]|max_length[2000]',
        'id_departamento'        => 'required|integer',
        'tipo_contrato'          => 'required|in_list[Tiempo Completo,Tiempo Parcial,Contrato,Práctica]',
        'salario_min'            => 'required|numeric|greater_than[0]',
        'salario_max'            => 'required|numeric|greater_than_equal_to[salario_min]',
        'experiencia_requerida'  => 'required|min_length[3]|max_length[500]',
        'educacion_requerida'    => 'required|min_length[3]|max_length[500]',
        'habilidades_requeridas' => 'required|min_length[10]|max_length[1000]',
        'responsabilidades'      => 'required|min_length[10]|max_length[1000]',
        'beneficios'             => 'permit_empty|max_length[1000]',
        'estado'                 => 'required|in_list[Abierto,Cerrado,Suspendido]',
        'activo'                 => 'required|in_list[0,1]',
        'fecha_limite'           => 'required|valid_date',
        'vacantes_disponibles'   => 'required|integer|greater_than[0]',
        'nivel_experiencia'      => 'required|in_list[Principiante,Intermedio,Avanzado,Experto]',
        'modalidad_trabajo'      => 'required|in_list[Presencial,Remoto,Híbrido]',
        'ubicacion_trabajo'      => 'required|max_length[200]'
    ];

    protected $validationMessages = [
        'titulo' => [
            'required'   => 'El título del puesto es obligatorio',
            'min_length' => 'El título debe tener al menos 5 caracteres',
            'max_length' => 'El título no puede exceder 200 caracteres'
        ],
        'descripcion' => [
            'required'   => 'La descripción del puesto es obligatoria',
            'min_length' => 'La descripción debe tener al menos 20 caracteres',
            'max_length' => 'La descripción no puede exceder 2000 caracteres'
        ],
        'id_departamento' => [
            'required' => 'Debe seleccionar un departamento',
            'integer'  => 'El departamento seleccionado no es válido'
        ],
        'tipo_contrato' => [
            'required' => 'Debe seleccionar un tipo de contrato',
            'in_list'  => 'El tipo de contrato seleccionado no es válido'
        ],
        'salario_min' => [
            'required'      => 'El salario mínimo es obligatorio',
            'numeric'       => 'El salario mínimo debe ser un número',
            'greater_than'  => 'El salario mínimo debe ser mayor a 0'
        ],
        'salario_max' => [
            'required'              => 'El salario máximo es obligatorio',
            'numeric'               => 'El salario máximo debe ser un número',
            'greater_than_equal_to' => 'El salario máximo debe ser mayor o igual al mínimo'
        ],
        'experiencia_requerida' => [
            'required'   => 'La experiencia requerida es obligatoria',
            'min_length' => 'La experiencia requerida debe tener al menos 3 caracteres',
            'max_length' => 'La experiencia requerida no puede exceder 500 caracteres'
        ],
        'educacion_requerida' => [
            'required'   => 'La educación requerida es obligatoria',
            'min_length' => 'La educación requerida debe tener al menos 3 caracteres',
            'max_length' => 'La educación requerida no puede exceder 500 caracteres'
        ],
        'habilidades_requeridas' => [
            'required'   => 'Las habilidades requeridas son obligatorias',
            'min_length' => 'Las habilidades deben tener al menos 10 caracteres',
            'max_length' => 'Las habilidades no pueden exceder 1000 caracteres'
        ],
        'responsabilidades' => [
            'required'   => 'Las responsabilidades son obligatorias',
            'min_length' => 'Las responsabilidades deben tener al menos 10 caracteres',
            'max_length' => 'Las responsabilidades no pueden exceder 1000 caracteres'
        ],
        'beneficios' => [
            'max_length' => 'Los beneficios no pueden exceder 1000 caracteres'
        ],
        'estado' => [
            'required' => 'El estado del puesto es obligatorio',
            'in_list'  => 'El estado seleccionado no es válido'
        ],
        'activo' => [
            'required' => 'El campo activo es obligatorio',
            'in_list'  => 'El campo activo debe ser 0 o 1'
        ],
        'fecha_limite' => [
            'required'   => 'La fecha límite es obligatoria',
            'valid_date' => 'La fecha límite debe ser una fecha válida'
        ],
        'vacantes_disponibles' => [
            'required'      => 'El número de vacantes es obligatorio',
            'integer'       => 'Las vacantes deben ser un número entero',
            'greater_than'  => 'Las vacantes deben ser mayores a 0'
        ],
        'nivel_experiencia' => [
            'required' => 'El nivel de experiencia es obligatorio',
            'in_list'  => 'El nivel de experiencia seleccionado no es válido'
        ],
        'modalidad_trabajo' => [
            'required' => 'La modalidad de trabajo es obligatoria',
            'in_list'  => 'La modalidad de trabajo seleccionada no es válida'
        ],
        'ubicacion_trabajo' => [
            'required'   => 'La ubicación de trabajo es obligatoria',
            'max_length' => 'La ubicación no puede exceder 200 caracteres'
        ]
    ];

    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    /**
     * Obtener puestos activos y abiertos
     */
    public function getPuestosActivos()
    {
        return $this->where('activo', 1)
                    ->where('estado', 'Abierto')
                    ->where('fecha_limite >=', date('Y-m-d'))
                    ->where('vacantes_disponibles >', 0)
                    ->orderBy('created_at', 'DESC')
                    ->findAll();
    }

    /**
     * Obtener puestos para dropdown
     */
    public function getPuestosParaDropdown()
    {
        $puestos = $this->select('id_puesto, titulo')
                        ->where('activo', 1)
                        ->where('estado', 'Abierto')
                        ->orderBy('titulo', 'ASC')
                        ->findAll();
        
        $dropdown = [];
        foreach ($puestos as $puesto) {
            $dropdown[$puesto['id_puesto']] = $puesto['titulo'];
        }
        
        return $dropdown;
    }

    /**
     * Obtener puesto completo con información del departamento
     */
    public function getPuestoCompleto($idPuesto)
    {
        $db = \Config\Database::connect();
        return $db->table('puestos p')
                  ->select('p.*, d.nombre as nombre_departamento')
                  ->join('departamentos d', 'd.id_departamento = p.id_departamento')
                  ->where('p.id_puesto', $idPuesto)
                  ->get()
                  ->getRowArray();
    }

    /**
     * Obtener puestos con información del departamento
     */
    public function getPuestosConDepartamento()
    {
        $db = \Config\Database::connect();
        return $db->table('puestos p')
                  ->select('p.*, d.nombre as nombre_departamento')
                  ->join('departamentos d', 'd.id_departamento = p.id_departamento')
                  ->orderBy('p.created_at', 'DESC')
                  ->get()
                  ->getResultArray();
    }

    /**
     * Generar URL única para postulación
     */
    public function generarUrlPostulacion($idPuesto)
    {
        $puesto = $this->find($idPuesto);
        if (!$puesto) {
            return false;
        }

        // Crear URL única basada en ID y título
        $url = 'postulacion-' . $idPuesto . '-' . url_title($puesto['titulo'], '-', true);
        
        // Actualizar el puesto con la URL generada
        $this->update($idPuesto, ['url_postulacion' => $url]);
        
        return $url;
    }

    /**
     * Obtener puesto por URL de postulación
     */
    public function getPuestoPorUrl($url)
    {
        return $this->where('url_postulacion', $url)
                    ->where('activo', 1)
                    ->where('estado', 'Abierto')
                    ->where('fecha_limite >=', date('Y-m-d'))
                    ->where('vacantes_disponibles >', 0)
                    ->first();
    }

    /**
     * Obtener puesto por ID con validaciones para postulación
     */
    public function getPuestoParaPostulacion($idPuesto)
    {
        return $this->where('id_puesto', $idPuesto)
                    ->where('activo', 1)
                    ->where('estado', 'Abierto')
                    ->where('fecha_limite >=', date('Y-m-d'))
                    ->where('vacantes_disponibles >', 0)
                    ->first();
    }

    /**
     * Buscar puestos por término
     */
    public function buscarPuestos($termino)
    {
        return $this->like('titulo', $termino)
                    ->orLike('descripcion', $termino)
                    ->orLike('habilidades_requeridas', $termino)
                    ->where('activo', 1)
                    ->where('estado', 'Abierto')
                    ->where('fecha_limite >=', date('Y-m-d'))
                    ->orderBy('created_at', 'DESC')
                    ->findAll();
    }

    /**
     * Cambiar estado del puesto
     */
    public function cambiarEstado($idPuesto, $nuevoEstado)
    {
        $data = [
            'estado' => $nuevoEstado,
            'activo' => ($nuevoEstado === 'Abierto') ? 1 : 0
        ];
        
        return $this->update($idPuesto, $data);
    }

    /**
     * Obtener estadísticas de puestos
     */
    public function getEstadisticasPuestos()
    {
        $builder = $this->builder();
        $builder->select('
            COUNT(*) as total,
            SUM(CASE WHEN estado = "Abierto" THEN 1 ELSE 0 END) as abiertos,
            SUM(CASE WHEN estado = "Cerrado" THEN 1 ELSE 0 END) as cerrados,
            SUM(CASE WHEN estado = "Suspendido" THEN 1 ELSE 0 END) as suspendidos,
            SUM(vacantes_disponibles) as total_vacantes
        ');
        
        return $builder->get()->getRowArray();
    }

    /**
     * Verificar si el puesto está en uso por empleados
     */
    public function puestoEnUso($idPuesto)
    {
        $db = \Config\Database::connect();
        $empleados = $db->table('empleados')
                        ->where('id_puesto', $idPuesto)
                        ->where('activo', 1)
                        ->countAllResults();
        
        return $empleados > 0;
    }

    /**
     * Obtener empleados por puesto
     */
    public function getEmpleadosPorPuesto($idPuesto)
    {
        $db = \Config\Database::connect();
        return $db->table('empleados e')
                  ->select('e.id_empleado, e.nombres, e.apellidos, e.tipo_empleado, e.estado')
                  ->where('e.id_puesto', $idPuesto)
                  ->where('e.activo', 1)
                  ->get()
                  ->getResultArray();
    }

    /**
     * Obtener puestos por departamento
     */
    public function getPuestosPorDepartamento($idDepartamento)
    {
        return $this->where('id_departamento', $idDepartamento)
                    ->where('activo', 1)
                    ->orderBy('titulo', 'ASC')
                    ->findAll();
    }

    /**
     * Actualizar vacantes disponibles
     */
    public function actualizarVacantes($idPuesto, $cantidad = 1)
    {
        $puesto = $this->find($idPuesto);
        if (!$puesto) {
            return false;
        }

        $nuevasVacantes = max(0, $puesto['vacantes_disponibles'] - $cantidad);
        
        return $this->update($idPuesto, [
            'vacantes_disponibles' => $nuevasVacantes,
            'estado' => ($nuevasVacantes == 0) ? 'Cerrado' : 'Abierto'
        ]);
    }
} 