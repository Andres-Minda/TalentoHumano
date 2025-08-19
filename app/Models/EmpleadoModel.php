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
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id_usuario', 'tipo_empleado', 'nombres', 'apellidos', 'fecha_nacimiento',
        'genero', 'estado_civil', 'direccion', 'telefono', 'fecha_ingreso',
        'activo', 'foto_url', 'id_departamento', 'id_puesto', 'salario',
        'estado', 'periodo_academico_id'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules      = [
        'id_usuario'      => 'required|integer',
        'tipo_empleado'   => 'required|in_list[DOCENTE,ADMINISTRATIVO,DIRECTIVO,AUXILIAR]',
        'nombres'         => 'required|min_length[2]|max_length[100]',
        'apellidos'       => 'required|min_length[2]|max_length[100]',
        'fecha_nacimiento' => 'required|valid_date',
        'genero'          => 'permit_empty|in_list[MASCULINO,FEMENINO,OTRO]',
        'estado_civil'    => 'permit_empty|in_list[SOLTERO,CASADO,DIVORCIADO,VIUDO,UNION_LIBRE]',
        'direccion'       => 'permit_empty|max_length[500]',
        'telefono'        => 'permit_empty|max_length[20]',
        'fecha_ingreso'   => 'required|valid_date',
        'activo'          => 'permit_empty|in_list[0,1]',
        'id_departamento' => 'permit_empty|integer',
        'id_puesto'       => 'permit_empty|integer',
        'salario'         => 'permit_empty|decimal',
        'estado'          => 'permit_empty|in_list[Activo,Inactivo,Vacaciones,Licencia]'
    ];

    protected $validationMessages = [
        'id_usuario' => [
            'required' => 'El ID de usuario es obligatorio',
            'integer' => 'El ID de usuario debe ser un número entero'
        ],
        'tipo_empleado' => [
            'required' => 'El tipo de empleado es obligatorio',
            'in_list' => 'El tipo de empleado debe ser DOCENTE, ADMINISTRATIVO, DIRECTIVO o AUXILIAR'
        ],
        'nombres' => [
            'required' => 'Los nombres son obligatorios',
            'min_length' => 'Los nombres deben tener al menos 2 caracteres',
            'max_length' => 'Los nombres no pueden exceder 100 caracteres'
        ],
        'apellidos' => [
            'required' => 'Los apellidos son obligatorios',
            'min_length' => 'Los apellidos deben tener al menos 2 caracteres',
            'max_length' => 'Los apellidos no pueden exceder 100 caracteres'
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
     * Obtiene estadísticas básicas de empleados
     */
    public function getEstadisticasEmpleados()
    {
        $db = $this->db;
        
        $totalEmpleados = $db->table('empleados')
            ->where('activo', 1)
            ->countAllResults();
            
        $empleadosPorTipo = $db->table('empleados')
            ->select('tipo_empleado, COUNT(*) as total')
            ->where('activo', 1)
            ->groupBy('tipo_empleado')
            ->get()
            ->getResultArray();
            
        $empleadosPorDepartamento = $db->table('empleados e')
            ->select('d.nombre as departamento, COUNT(*) as total')
            ->join('departamentos d', 'd.id_departamento = e.id_departamento', 'left')
            ->where('e.activo', 1)
            ->groupBy('e.id_departamento')
            ->get()
            ->getResultArray();
            
        return (object) [
            'total_empleados' => $totalEmpleados,
            'empleados_por_tipo' => $empleadosPorTipo,
            'empleados_por_departamento' => $empleadosPorDepartamento
        ];
    }

    /**
     * Obtiene todos los empleados con información completa
     */
    public function getAllEmpleadosCompletos()
    {
        return $this->db->table('empleados e')
            ->select('e.*, d.nombre as departamento, p.nombre as puesto, u.email, u.cedula')
            ->join('usuarios u', 'u.id_usuario = e.id_usuario', 'left')
            ->join('departamentos d', 'd.id_departamento = e.id_departamento', 'left')
            ->join('puestos p', 'p.id_puesto = e.id_puesto', 'left')
            ->where('e.activo', 1)
            ->get()
            ->getResultArray();
    }

    /**
     * Obtiene empleados activos
     */
    public function getEmpleadosActivos()
    {
        return $this->db->table('empleados e')
            ->select('e.id_empleado, e.nombres, e.apellidos, e.tipo_empleado, d.nombre as departamento')
            ->join('departamentos d', 'd.id_departamento = e.id_departamento', 'left')
            ->where('e.activo', 1)
            ->get()
            ->getResultArray();
    }

    /**
     * Obtiene empleados por departamento para gráficos
     */
    public function getEmpleadosPorDepartamentoChart()
    {
        return $this->db->table('empleados e')
            ->select('d.nombre as departamento, COUNT(*) as total')
            ->join('departamentos d', 'd.id_departamento = e.id_departamento', 'left')
            ->where('e.activo', 1)
            ->groupBy('e.id_departamento')
            ->get()
            ->getResultArray();
    }

    /**
     * Obtiene empleados por tipo
     */
    public function getEmpleadosPorTipo($tipo = null)
    {
        $builder = $this->db->table('empleados e')
            ->select('e.*, d.nombre as departamento, p.nombre as puesto')
            ->join('departamentos d', 'd.id_departamento = e.id_departamento', 'left')
            ->join('puestos p', 'p.id_puesto = e.id_puesto', 'left')
            ->where('e.activo', 1);
            
        if ($tipo) {
            $builder->where('e.tipo_empleado', $tipo);
        }
        
        return $builder->get()->getResultArray();
    }

    /**
     * Obtiene empleados por departamento
     */
    public function getEmpleadosPorDepartamento($departamentoId = null)
    {
        $builder = $this->db->table('empleados e')
            ->select('e.*, d.nombre as departamento, p.nombre as puesto')
            ->join('departamentos d', 'd.id_departamento = e.id_departamento', 'left')
            ->join('puestos p', 'p.id_puesto = e.id_puesto', 'left')
            ->where('e.activo', 1);
            
        if ($departamentoId) {
            $builder->where('e.id_departamento', $departamentoId);
        }
        
        return $builder->get()->getResultArray();
    }

    /**
     * Obtiene empleados con información de usuario
     */
    public function getEmpleadosConUsuario()
    {
        return $this->db->table('empleados e')
            ->select('e.*, u.cedula, u.email, u.activo as usuario_activo')
            ->join('usuarios u', 'u.id_usuario = e.id_usuario', 'left')
            ->where('e.activo', 1)
            ->get()
            ->getResultArray();
    }

    /**
     * Valida el tipo de docente
     */
    public function validarTipoDocente($tipoDocente)
    {
        $tiposValidos = ['PROFESOR_TITULAR', 'PROFESOR_ASOCIADO', 'PROFESOR_AUXILIAR', 'INSTRUCTOR'];
        return in_array($tipoDocente, $tiposValidos);
    }

    /**
     * Asigna departamento automáticamente según tipo de empleado
     */
    public function asignarDepartamentoAutomatico($tipoEmpleado)
    {
        if (in_array($tipoEmpleado, ['DIRECTIVO', 'AUXILIAR'])) {
            // Buscar el departamento ITSI
            $departamento = $this->db->table('departamentos')
                ->where('nombre', 'LIKE', '%ITSI%')
                ->get()
                ->getRowArray();
                
            return $departamento ? $departamento['id_departamento'] : null;
        }
        
        return null;
    }

} 