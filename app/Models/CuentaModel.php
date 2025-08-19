<?php

namespace App\Models;

use CodeIgniter\Model;

class CuentaModel extends Model
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
     * Obtiene información completa del usuario con rol
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
     * Actualiza la contraseña del usuario
     */
    public function actualizarPassword($usuarioId, $nuevaPassword)
    {
        $passwordHash = password_hash($nuevaPassword, PASSWORD_DEFAULT);
        
        return $this->update($usuarioId, [
            'password_hash' => $passwordHash,
            'updated_at' => date('Y-m-d H:i:s')
        ]);
    }

    /**
     * Verifica si la contraseña actual es correcta
     */
    public function verificarPassword($usuarioId, $passwordActual)
    {
        $usuario = $this->find($usuarioId);
        
        if (!$usuario) {
            return false;
        }
        
        return password_verify($passwordActual, $usuario['password_hash']);
    }

    /**
     * Actualiza el email del usuario
     */
    public function actualizarEmail($usuarioId, $nuevoEmail)
    {
        return $this->update($usuarioId, [
            'email' => $nuevoEmail,
            'updated_at' => date('Y-m-d H:i:s')
        ]);
    }

    /**
     * Obtiene sesiones activas del usuario
     */
    public function getSesionesActivas($usuarioId)
    {
        // En un sistema real, esto consultaría una tabla de sesiones
        // Por ahora retornamos un array vacío
        return [];
    }

    /**
     * Cierra todas las sesiones del usuario
     */
    public function cerrarTodasSesiones($usuarioId)
    {
        // En un sistema real, esto invalidaría las sesiones
        // Por ahora solo retornamos true
        return true;
    }

    /**
     * Obtiene historial de cambios de la cuenta
     */
    public function getHistorialCambios($usuarioId)
    {
        // En un sistema real, esto consultaría una tabla de logs
        // Por ahora retornamos un array vacío
        return [];
    }
}
