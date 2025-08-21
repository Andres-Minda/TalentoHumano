<?php

namespace App\Models;

use CodeIgniter\Model;

class EmpleadoModel extends Model
{
    protected $table            = 'empleados';
    protected $primaryKey       = 'id_empleado';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields = [
        'id_usuario',
        'nombres',
        'apellidos',
        'tipo_empleado',
        'departamento',
        'fecha_ingreso',
        'estado',
        'salario',
        'fecha_nacimiento',
        'genero',
        'estado_civil',
        'direccion',
        'telefono',
        'activo'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validaciones
    protected $validationRules = [
        'id_usuario'      => 'required|integer',
        'nombres'         => 'required|min_length[2]|max_length[100]',
        'apellidos'       => 'required|min_length[2]|max_length[100]',
        'tipo_empleado'   => 'required|in_list[DOCENTE,ADMINISTRATIVO,DIRECTIVO,AUXILIAR]',
        'departamento'    => 'permit_empty|max_length[100]',
        'fecha_ingreso'   => 'permit_empty|valid_date',
        'fecha_contratacion' => 'permit_empty|valid_date',
        'estado'          => 'permit_empty|in_list[ACTIVO,INACTIVO,SUSPENDIDO]'
    ];

    protected $validationMessages = [
        'id_usuario' => [
            'required' => 'El usuario es obligatorio',
            'integer' => 'El ID del usuario debe ser un número'
        ],
        'nombres' => [
            'required'   => 'Los nombres son obligatorios',
            'min_length' => 'Los nombres deben tener al menos 2 caracteres',
            'max_length' => 'Los nombres no pueden exceder 100 caracteres'
        ],
        'apellidos' => [
            'required'   => 'Los apellidos son obligatorios',
            'min_length' => 'Los apellidos deben tener al menos 2 caracteres',
            'max_length' => 'Los apellidos no pueden exceder 100 caracteres'
        ],
        'tipo_empleado' => [
            'required' => 'El tipo de empleado es obligatorio',
            'in_list' => 'El tipo debe ser DOCENTE, ADMINISTRATIVO, DIRECTIVO o AUXILIAR'
        ],
        'departamento' => [
            'max_length' => 'El departamento no puede exceder 100 caracteres'
        ],
        'fecha_ingreso' => [
            'valid_date' => 'La fecha de ingreso debe ser válida'
        ],
        'fecha_contratacion' => [
            'valid_date' => 'La fecha de contratación debe ser válida'
        ],
        'estado' => [
            'in_list' => 'El estado debe ser ACTIVO, INACTIVO o SUSPENDIDO'
        ]
    ];

    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    /**
     * Obtener empleado por ID de usuario
     */
    public function getEmpleadoByUsuarioId($idUsuario)
    {
        return $this->where('id_usuario', $idUsuario)->first();
    }

    /**
     * Obtener empleados por tipo
     */
    public function getEmpleadosPorTipo($tipoEmpleado)
    {
        return $this->where('tipo_empleado', $tipoEmpleado)
                    ->where('estado', 'ACTIVO')
                    ->findAll();
    }

    /**
     * Obtener empleados por departamento
     */
    public function getEmpleadosPorDepartamento($departamento)
    {
        return $this->where('departamento', $departamento)
                    ->where('estado', 'ACTIVO')
                    ->findAll();
    }

    /**
     * Obtener empleados activos
     */
    public function getEmpleadosActivos()
    {
        return $this->where('estado', 'ACTIVO')->findAll();
    }

    /**
     * Obtener empleados con información de usuario
     */
    public function getEmpleadosConUsuario($filtros = [])
    {
        $builder = $this->db->table('empleados e');
        $builder->select('
            e.id_empleado,
            e.nombres,
            e.apellidos,
            e.tipo_empleado,
            e.departamento,
            e.estado,
            e.activo,
            u.cedula,
            u.email,
            u.id_usuario
        ');
        $builder->join('usuarios u', 'u.id_usuario = e.id_usuario');
        
        // Solo mostrar empleados activos por defecto, pero permitir mostrar todos si se especifica
        if (!isset($filtros['mostrar_todos']) || !$filtros['mostrar_todos']) {
            $builder->where('e.estado', 'ACTIVO');
        }
        
        // Aplicar filtros
        if (isset($filtros['tipo_empleado'])) {
            $builder->where('e.tipo_empleado', $filtros['tipo_empleado']);
        }
        
        if (isset($filtros['departamento'])) {
            $builder->where('e.departamento', $filtros['departamento']);
        }
        
        if (isset($filtros['busqueda'])) {
            $builder->groupStart();
            $builder->like('e.nombres', $filtros['busqueda']);
            $builder->orLike('e.apellidos', $filtros['busqueda']);
            $builder->orLike('u.cedula', $filtros['busqueda']);
            $builder->orLike('u.email', $filtros['busqueda']);
            $builder->groupEnd();
        }
        
        $builder->orderBy('e.apellidos', 'ASC');
        $builder->orderBy('e.nombres', 'ASC');
        
        return $builder->get()->getResultArray();
    }

    /**
     * Obtener estadísticas de empleados
     */
    public function getEstadisticasEmpleados()
    {
        $builder = $this->builder();
        $builder->select('
            COUNT(*) as total,
            SUM(CASE WHEN tipo_empleado = "DOCENTE" THEN 1 ELSE 0 END) as docentes,
            SUM(CASE WHEN tipo_empleado = "ADMINISTRATIVO" THEN 1 ELSE 0 END) as administrativos,
            SUM(CASE WHEN tipo_empleado = "DIRECTIVO" THEN 1 ELSE 0 END) as directivos,
            SUM(CASE WHEN tipo_empleado = "AUXILIAR" THEN 1 ELSE 0 END) as auxiliares,
            COUNT(DISTINCT departamento) as total_departamentos
        ');
        $builder->where('estado', 'ACTIVO');
        
        return $builder->get()->getRowArray();
    }

    /**
     * Obtener departamentos disponibles
     */
    public function getDepartamentos()
    {
        $builder = $this->builder();
        $builder->select('DISTINCT departamento');
        $builder->where('departamento IS NOT NULL');
        $builder->where('departamento !=', '');
        $builder->where('estado', 'ACTIVO');
        $builder->orderBy('departamento', 'ASC');
        
        $result = $builder->get()->getResultArray();
        
        $departamentos = [];
        foreach ($result as $row) {
            $departamentos[] = $row['departamento'];
        }
        
        return $departamentos;
    }

    /**
     * Cambiar estado del empleado
     */
    public function cambiarEstado($idEmpleado, $nuevoEstado)
    {
        $data = ['estado' => $nuevoEstado];
        
        return $this->update($idEmpleado, $data);
    }

    /**
     * Buscar empleados
     */
    public function buscarEmpleados($termino)
    {
        $builder = $this->builder();
        $builder->groupStart();
        $builder->like('nombres', $termino);
        $builder->orLike('apellidos', $termino);
        $builder->groupEnd();
        $builder->where('estado', 'ACTIVO');
        $builder->orderBy('apellidos', 'ASC');
        
        return $builder->get()->getResultArray();
    }

    /**
     * Obtener empleado para dashboard
     */
    public function getEmpleadoParaDashboard($idUsuario)
    {
        $builder = $this->db->table('empleados e');
        $builder->select('
            e.*,
            u.cedula,
            u.email
        ');
        $builder->join('usuarios u', 'u.id_usuario = e.id_usuario');
        $builder->where('e.id_usuario', $idUsuario);
        $builder->where('e.estado', 'ACTIVO');
        
        return $builder->get()->getRowArray();
    }

    /**
     * Obtener empleado por ID con información de usuario
     */
    public function getEmpleadoConUsuario($idEmpleado)
    {
        $builder = $this->db->table('empleados e');
        $builder->select('
            e.id_empleado,
            e.nombres,
            e.apellidos,
            e.tipo_empleado,
            e.departamento,
            e.estado,
            e.activo,
            e.fecha_ingreso,
            e.salario,
            u.cedula,
            u.email,
            u.id_usuario
        ');
        $builder->join('usuarios u', 'u.id_usuario = e.id_usuario');
        $builder->where('e.id_empleado', $idEmpleado);
        
        return $builder->get()->getRowArray();
    }

} 