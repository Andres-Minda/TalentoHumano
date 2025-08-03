<?php

namespace App\Models;

use CodeIgniter\Model;

class UsuarioModel extends Model
{
    protected $table = 'usuarios';
    protected $primaryKey = 'id_usuario';
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = [
        'cedula',
        'email',
        'password_hash',
        'id_rol',
        'activo',
        'last_login',
        'created_at',
        'updated_at'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $validationRules = [
        'cedula' => 'required|min_length[10]|max_length[20]',
        'email' => 'required|valid_email',
        'password_hash' => 'required',
        'id_rol' => 'required|integer'
    ];
    protected $validationMessages = [];
    protected $skipValidation = false;

    /**
     * Busca un usuario por cédula o email
     * @param string $identifier Cédula o email del usuario
     * @return array|null Datos del usuario o null si no existe
     */
    public function findUserByIdentifier($identifier)
    {
        $builder = $this->db->table('usuarios u');
        $builder->select('u.*, e.nombres, e.apellidos, e.tipo_empleado, r.nombre_rol');
        $builder->join('empleados e', 'e.id_usuario = u.id_usuario', 'left');
        $builder->join('roles r', 'r.id_rol = u.id_rol', 'left');
        $builder->groupStart();
        $builder->where('u.cedula', $identifier);
        $builder->orWhere('u.email', $identifier);
        $builder->groupEnd();
        $builder->where('u.activo', 1);
        
        $result = $builder->get()->getRowArray();
        
        return $result;
    }

    /**
     * Actualiza la fecha del último login
     * @param int $userId ID del usuario
     * @return bool True si se actualizó correctamente
     */
    public function updateLastLogin($userId)
    {
        return $this->update($userId, [
            'last_login' => date('Y-m-d H:i:s')
        ]);
    }

    /**
     * Obtiene usuarios por rol
     * @param int $roleId ID del rol
     * @return array Lista de usuarios
     */
    public function getUsersByRole($roleId)
    {
        $builder = $this->db->table('usuarios u');
        $builder->select('u.*, e.nombres, e.apellidos, e.tipo_empleado, r.nombre_rol');
        $builder->join('empleados e', 'e.id_usuario = u.id_usuario', 'left');
        $builder->join('roles r', 'r.id_rol = u.id_rol', 'left');
        $builder->where('u.id_rol', $roleId);
        $builder->where('u.activo', 1);
        
        return $builder->get()->getResultArray();
    }

    /**
     * Verifica si un usuario existe por cédula o email
     * @param string $identifier Cédula o email
     * @param int|null $excludeUserId ID del usuario a excluir (para actualizaciones)
     * @return bool True si existe
     */
    public function userExists($identifier, $excludeUserId = null)
    {
        $builder = $this->db->table('usuarios');
        $builder->groupStart();
        $builder->where('cedula', $identifier);
        $builder->orWhere('email', $identifier);
        $builder->groupEnd();
        
        if ($excludeUserId) {
            $builder->where('id_usuario !=', $excludeUserId);
        }
        
        return $builder->countAllResults() > 0;
    }

    /**
     * Obtener últimos usuarios registrados
     */
    public function getUltimosUsuarios($limit = 5)
    {
        return $this->select('usuarios.*, roles.nombre_rol')
                   ->join('roles', 'roles.id_rol = usuarios.id_rol')
                   ->orderBy('usuarios.created_at', 'DESC')
                   ->limit($limit)
                   ->findAll();
    }

    /**
     * Obtener usuarios con información de empleado
     */
    public function getUsuariosConEmpleado()
    {
        return $this->select('usuarios.*, roles.nombre_rol, empleados.nombres, empleados.apellidos, empleados.tipo_empleado')
                   ->join('roles', 'roles.id_rol = usuarios.id_rol')
                   ->join('empleados', 'empleados.id_usuario = usuarios.id_usuario', 'left')
                   ->findAll();
    }

    /**
     * Obtener estadísticas de usuarios
     */
    public function getEstadisticasUsuarios()
    {
        $db = \Config\Database::connect();
        
        $query = $db->query("
            SELECT 
                COUNT(*) as total_usuarios,
                SUM(CASE WHEN activo = 1 THEN 1 ELSE 0 END) as usuarios_activos,
                SUM(CASE WHEN activo = 0 THEN 1 ELSE 0 END) as usuarios_inactivos,
                COUNT(CASE WHEN last_login IS NOT NULL THEN 1 END) as usuarios_con_acceso
            FROM usuarios
        ");
        
        return $query->getRow();
    }
} 