<?php

namespace App\Models;

use CodeIgniter\Model;

class RolModel extends Model
{
    protected $table = 'roles';
    protected $primaryKey = 'id_rol';
    protected $allowedFields = [
        'nombre_rol', 'descripcion'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';

    /**
     * Obtener rol por nombre
     */
    public function getRolByNombre($nombreRol)
    {
        return $this->where('nombre_rol', $nombreRol)->first();
    }

    /**
     * Obtener roles activos
     */
    public function getRolesActivos()
    {
        return $this->findAll();
    }

    /**
     * Obtener usuarios por rol
     */
    public function getUsuariosPorRol($idRol)
    {
        $db = \Config\Database::connect();
        
        $query = $db->query("
            SELECT u.*, e.nombres, e.apellidos, e.tipo_empleado
            FROM usuarios u
            LEFT JOIN empleados e ON e.id_usuario = u.id_usuario
            WHERE u.id_rol = ? AND u.activo = 1
        ", [$idRol]);
        
        return $query->getResultArray();
    }

    /**
     * Obtener estadísticas de roles
     */
    public function getEstadisticasRoles()
    {
        $db = \Config\Database::connect();
        
        $query = $db->query("
            SELECT 
                r.nombre_rol,
                COUNT(u.id_usuario) as total_usuarios,
                SUM(CASE WHEN u.activo = 1 THEN 1 ELSE 0 END) as usuarios_activos
            FROM roles r
            LEFT JOIN usuarios u ON u.id_rol = r.id_rol
            GROUP BY r.id_rol, r.nombre_rol
        ");
        
        return $query->getResultArray();
    }

    /**
     * Verificar si un rol tiene usuarios asignados
     */
    public function rolTieneUsuarios($idRol)
    {
        $db = \Config\Database::connect();
        
        $query = $db->query("
            SELECT COUNT(*) as total
            FROM usuarios
            WHERE id_rol = ?
        ", [$idRol]);
        
        $result = $query->getRow();
        return $result->total > 0;
    }

    public function getRolesConEstadisticas()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('roles r');
        $builder->select('r.*, COUNT(u.id_usuario) as total_usuarios');
        $builder->join('usuarios u', 'u.id_rol = r.id_rol', 'left');
        $builder->groupBy('r.id_rol');
        $builder->orderBy('total_usuarios', 'DESC');
        return $builder->get()->getResultArray();
    }
} 