<?php

namespace App\Models;

use CodeIgniter\Model;

class UsuarioModel extends Model
{
    protected $table            = 'usuarios';
    protected $primaryKey       = 'id_usuario';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'cedula', 'email', 'password_hash', 'id_rol', 'activo'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules      = [
        'cedula' => 'required|min_length[10]|max_length[20]|is_unique[usuarios.cedula,id_usuario,{id_usuario}]',
        'email' => 'required|valid_email|max_length[255]|is_unique[usuarios.email,id_usuario,{id_usuario}]',
        'password_hash' => 'required|min_length[60]|max_length[255]',
        'id_rol' => 'required|integer',
        'activo' => 'required|in_list[0,1]'
    ];

    protected $validationMessages = [
        'cedula' => [
            'required' => 'La cédula es obligatoria',
            'min_length' => 'La cédula debe tener al menos 10 caracteres',
            'max_length' => 'La cédula no puede exceder 20 caracteres',
            'is_unique' => 'Esta cédula ya está registrada'
        ],
        'email' => [
            'required' => 'El email es obligatorio',
            'valid_email' => 'El email debe tener un formato válido',
            'max_length' => 'El email no puede exceder 255 caracteres',
            'is_unique' => 'Este email ya está registrado'
        ],
        'password_hash' => [
            'required' => 'La contraseña es obligatoria',
            'min_length' => 'La contraseña debe tener al menos 60 caracteres',
            'max_length' => 'La contraseña no puede exceder 255 caracteres'
        ],
        'id_rol' => [
            'required' => 'El rol es obligatorio',
            'integer' => 'El rol debe ser un número entero'
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
     * Obtiene usuario completo con información de rol
     */
    public function getUsuarioCompleto($usuarioId)
    {
        return $this->db->table('usuarios u')
            ->select('u.*, r.nombre_rol, r.descripcion as rol_descripcion')
            ->join('roles r', 'r.id_rol = u.id_rol', 'left')
            ->where('u.id_usuario', $usuarioId)
            ->get()
            ->getRowArray();
    }

    /**
     * Obtiene usuarios por rol
     */
    public function getUsuariosPorRol($rolId)
    {
        return $this->where('id_rol', $rolId)
            ->where('activo', 1)
            ->findAll();
    }

    /**
     * Obtiene usuarios activos
     */
    public function getUsuariosActivos()
    {
        return $this->where('activo', 1)
            ->findAll();
    }

    /**
     * Busca usuario por cédula o email
     */
    public function buscarUsuario($termino)
    {
        return $this->like('cedula', $termino)
            ->orLike('email', $termino)
            ->where('activo', 1)
            ->findAll();
    }

    /**
     * Verifica si existe un usuario con la cédula o email
     */
    public function existeUsuario($cedula = null, $email = null)
    {
        if ($cedula) {
            $existe = $this->where('cedula', $cedula)->first();
            if ($existe) return true;
        }
        
        if ($email) {
            $existe = $this->where('email', $email)->first();
            if ($existe) return true;
        }
        
        return false;
    }

    /**
     * Obtiene estadísticas de usuarios
     */
    public function getEstadisticasUsuarios()
    {
        $db = $this->db;
        
        $totalUsuarios = $db->table('usuarios')->countAllResults();
        $usuariosActivos = $db->table('usuarios')->where('activo', 1)->countAllResults();
        $usuariosInactivos = $db->table('usuarios')->where('activo', 0)->countAllResults();
        
        // Crear objeto con propiedades para compatibilidad
        $estadisticas = new \stdClass();
        $estadisticas->total_usuarios = $totalUsuarios;
        $estadisticas->usuarios_activos = $usuariosActivos;
        $estadisticas->usuarios_inactivos = $usuariosInactivos;
        $estadisticas->usuarios_con_acceso = $usuariosActivos; // Por defecto, usuarios activos tienen acceso
        
        return $estadisticas;
    }

    /**
     * Obtiene usuarios con información de empleado
     */
    public function getUsuariosConEmpleado()
    {
        return $this->db->table('usuarios u')
            ->select('u.*, r.nombre_rol, r.descripcion as rol_descripcion, 
                     e.nombres as empleado_nombres, e.apellidos as empleado_apellidos,
                     e.tipo_empleado, d.nombre as departamento_nombre')
            ->join('roles r', 'r.id_rol = u.id_rol', 'left')
            ->join('empleados e', 'e.id_usuario = u.id_usuario', 'left')
            ->join('departamentos d', 'd.id_departamento = e.id_departamento', 'left')
            ->orderBy('u.created_at', 'DESC')
            ->get()
            ->getResultArray();
    }

    /**
     * Obtiene todos los usuarios con información básica
     */
    public function getAllUsuarios()
    {
        return $this->db->table('usuarios u')
            ->select('u.*, r.nombre_rol')
            ->join('roles r', 'r.id_rol = u.id_rol', 'left')
            ->orderBy('u.created_at', 'DESC')
            ->get()
            ->getResultArray();
    }
} 