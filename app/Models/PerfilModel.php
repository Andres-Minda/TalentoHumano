<?php

namespace App\Models;

use CodeIgniter\Model;

class PerfilModel extends Model
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
        'activo', 'foto_url', 'id_departamento', 'id_puesto', 'salario', 'estado'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules      = [
        'id_usuario' => 'required|integer',
        'tipo_empleado' => 'required|in_list[DOCENTE,ADMINISTRATIVO,DIRECTIVO,AUXILIAR]',
        'nombres' => 'required|min_length[2]|max_length[100]',
        'apellidos' => 'required|min_length[2]|max_length[100]',
        'fecha_nacimiento' => 'permit_empty|valid_date',
        'genero' => 'permit_empty|in_list[MASCULINO,FEMENINO,OTRO]',
        'estado_civil' => 'permit_empty|in_list[SOLTERO,CASADO,DIVORCIADO,VIUDO]',
        'direccion' => 'permit_empty|max_length[500]',
        'telefono' => 'permit_empty|max_length[20]',
        'fecha_ingreso' => 'permit_empty|valid_date',
        'activo' => 'required|in_list[0,1]',
        'salario' => 'permit_empty|decimal',
        'estado' => 'permit_empty|max_length[50]'
    ];

    protected $validationMessages = [
        'id_usuario' => [
            'required' => 'El usuario es obligatorio',
            'integer' => 'El usuario debe ser un número entero'
        ],
        'tipo_empleado' => [
            'required' => 'El tipo de empleado es obligatorio',
            'in_list' => 'El tipo debe ser DOCENTE, ADMINISTRATIVO, DIRECTIVO o AUXILIAR'
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
        ],
        'activo' => [
            'required' => 'El estado activo es obligatorio',
            'in_list' => 'El estado debe ser 0 o 1'
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
     * Obtiene perfil completo del empleado con información de usuario, departamento y puesto
     */
    public function getPerfilCompleto($empleadoId)
    {
        return $this->db->table('empleados e')
            ->select('e.*, u.email, u.cedula, r.nombre_rol, d.nombre as departamento_nombre, p.nombre as puesto_nombre')
            ->join('usuarios u', 'u.id_usuario = e.id_usuario', 'left')
            ->join('roles r', 'r.id_rol = u.id_rol', 'left')
            ->join('departamentos d', 'd.id_departamento = e.id_departamento', 'left')
            ->join('puestos p', 'p.id_puesto = e.id_puesto', 'left')
            ->where('e.id_empleado', $empleadoId)
            ->get()
            ->getRowArray();
    }

    /**
     * Obtiene perfil por ID de usuario
     */
    public function getPerfilPorUsuario($usuarioId)
    {
        return $this->db->table('empleados e')
            ->select('e.*, u.email, u.cedula, r.nombre_rol, d.nombre as departamento_nombre, p.nombre as puesto_nombre')
            ->join('usuarios u', 'u.id_usuario = e.id_usuario', 'left')
            ->join('roles r', 'r.id_rol = u.id_rol', 'left')
            ->join('departamentos d', 'd.id_departamento = e.id_departamento', 'left')
            ->join('puestos p', 'p.id_puesto = e.id_puesto', 'left')
            ->where('e.id_usuario', $usuarioId)
            ->get()
            ->getRowArray();
    }

    /**
     * Actualiza la foto del perfil
     */
    public function actualizarFoto($empleadoId, $fotoUrl)
    {
        return $this->update($empleadoId, [
            'foto_url' => $fotoUrl,
            'updated_at' => date('Y-m-d H:i:s')
        ]);
    }

    /**
     * Actualiza información personal del empleado
     */
    public function actualizarInformacionPersonal($empleadoId, $datos)
    {
        $datos['updated_at'] = date('Y-m-d H:i:s');
        
        return $this->update($empleadoId, $datos);
    }

    /**
     * Obtiene estadísticas del perfil del empleado
     */
    public function getEstadisticasPerfil($empleadoId)
    {
        $db = $this->db;
        
        // Contar capacitaciones
        $totalCapacitaciones = $db->table('empleados_capacitaciones')
            ->where('id_empleado', $empleadoId)
            ->countAllResults();
        
        $capacitacionesCompletadas = $db->table('empleados_capacitaciones')
            ->where('id_empleado', $empleadoId)
            ->where('estado', 'COMPLETADA')
            ->countAllResults();
        
        // Contar evaluaciones
        $totalEvaluaciones = $db->table('evaluaciones')
            ->where('id_empleado', $empleadoId)
            ->countAllResults();
        
        $evaluacionesCompletadas = $db->table('evaluaciones')
            ->where('id_empleado', $empleadoId)
            ->where('estado', 'COMPLETADA')
            ->countAllResults();
        
        // Contar títulos académicos
        $totalTitulos = $db->table('titulos_academicos')
            ->where('id_empleado', $empleadoId)
            ->countAllResults();
        
        return [
            'capacitaciones' => [
                'total' => $totalCapacitaciones,
                'completadas' => $capacitacionesCompletadas
            ],
            'evaluaciones' => [
                'total' => $totalEvaluaciones,
                'completadas' => $evaluacionesCompletadas
            ],
            'titulos_academicos' => $totalTitulos
        ];
    }
}
