<?php

namespace App\Models;

use CodeIgniter\Model;

class SesionActivaModel extends Model
{
    protected $table            = 'sesiones_activas';
    protected $primaryKey       = 'id_sesion';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id_usuario',
        'token_sesion',
        'fecha_inicio',
        'fecha_ultima_actividad',
        'ip_address',
        'user_agent',
        'activa'
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'fecha_inicio';
    protected $updatedField  = 'fecha_ultima_actividad';
    protected $deletedField  = '';

    // Validation
    protected $validationRules      = [
        'id_usuario' => 'required|integer',
        'token_sesion' => 'required|string|max_length[255]',
        'ip_address' => 'permit_empty|string|max_length[45]',
        'user_agent' => 'permit_empty|string'
    ];
    protected $validationMessages   = [];
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
     * Crear nueva sesión activa
     */
    public function crearSesion($idUsuario, $tokenSesion, $ipAddress = null, $userAgent = null)
    {
        $data = [
            'id_usuario' => $idUsuario,
            'token_sesion' => $tokenSesion,
            'fecha_inicio' => date('Y-m-d H:i:s'),
            'fecha_ultima_actividad' => date('Y-m-d H:i:s'),
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent,
            'activa' => true
        ];

        return $this->insert($data);
    }

    /**
     * Actualizar última actividad de la sesión
     */
    public function actualizarActividad($tokenSesion)
    {
        return $this->where('token_sesion', $tokenSesion)
                    ->where('activa', true)
                    ->set(['fecha_ultima_actividad' => date('Y-m-d H:i:s')])
                    ->update();
    }

    /**
     * Cerrar sesión específica
     */
    public function cerrarSesion($tokenSesion)
    {
        return $this->where('token_sesion', $tokenSesion)
                    ->set(['activa' => false])
                    ->update();
    }

    /**
     * Cerrar todas las sesiones de un usuario excepto la actual
     */
    public function cerrarTodasLasSesiones($idUsuario, $tokenActual = null)
    {
        $builder = $this->where('id_usuario', $idUsuario);
        
        if ($tokenActual) {
            $builder->where('token_sesion !=', $tokenActual);
        }
        
        return $builder->set(['activa' => false])->update();
    }

    /**
     * Obtener sesiones activas de un usuario
     */
    public function obtenerSesionesActivas($idUsuario)
    {
        return $this->select('id_sesion, token_sesion, fecha_inicio, fecha_ultima_actividad, ip_address, user_agent')
                    ->where('id_usuario', $idUsuario)
                    ->where('activa', true)
                    ->orderBy('fecha_ultima_actividad', 'DESC')
                    ->findAll();
    }

    /**
     * Limpiar sesiones expiradas
     */
    public function limpiarSesionesExpiradas($horasExpiracion = 24)
    {
        $fechaExpiracion = date('Y-m-d H:i:s', strtotime("-{$horasExpiracion} hours"));
        
        return $this->where('fecha_ultima_actividad <', $fechaExpiracion)
                    ->set(['activa' => false])
                    ->update();
    }

    /**
     * Obtener estadísticas de sesiones
     */
    public function obtenerEstadisticasSesiones()
    {
        $db = \Config\Database::connect();
        
        $query = $db->query("
            SELECT 
                COUNT(*) as total_sesiones_activas,
                COUNT(DISTINCT id_usuario) as usuarios_unicos_activos,
                DATE(fecha_inicio) as fecha,
                COUNT(*) as sesiones_por_dia
            FROM sesiones_activas 
            WHERE activa = 1
            GROUP BY DATE(fecha_inicio)
            ORDER BY fecha DESC
            LIMIT 7
        ");
        
        return $query->getResultArray();
    }
}
