<?php

namespace App\Models;

use CodeIgniter\Model;

class LogSistemaModel extends Model
{
    protected $table            = 'logs_sistema';
    protected $primaryKey       = 'id_log';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id_usuario',
        'accion',
        'modulo',
        'descripcion',
        'fecha_accion',
        'ip_address'
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = '';
    protected $updatedField  = '';
    protected $deletedField  = '';

    // Validation
    protected $validationRules      = [
        'id_usuario' => 'permit_empty|integer',
        'accion' => 'required|string|max_length[100]',
        'modulo' => 'required|string|max_length[50]',
        'descripcion' => 'permit_empty|string'
    ];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = ['setFechaAccion'];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    /**
     * Callback para establecer la fecha de acción
     */
    protected function setFechaAccion(array $data)
    {
        if (!isset($data['data']['fecha_accion'])) {
            $data['data']['fecha_accion'] = date('Y-m-d H:i:s');
        }
        return $data;
    }

    /**
     * Registrar un log del sistema
     */
    public function registrarLog($idUsuario, $accion, $modulo, $descripcion = '', $ipAddress = null)
    {
        $data = [
            'id_usuario' => $idUsuario,
            'accion' => $accion,
            'modulo' => $modulo,
            'descripcion' => $descripcion,
            'fecha_accion' => date('Y-m-d H:i:s'),
            'ip_address' => $ipAddress ?: $this->obtenerIpAddress()
        ];

        return $this->insert($data);
    }

    /**
     * Obtener logs con filtros
     */
    public function obtenerLogsConFiltros($filtros = [], $limit = 50, $offset = 0)
    {
        $builder = $this->select('logs_sistema.*, usuarios.cedula, usuarios.email')
                        ->join('usuarios', 'usuarios.id_usuario = logs_sistema.id_usuario', 'left')
                        ->orderBy('fecha_accion', 'DESC');

        // Aplicar filtros
        if (!empty($filtros['fecha_inicio'])) {
            $builder->where('DATE(fecha_accion) >=', $filtros['fecha_inicio']);
        }

        if (!empty($filtros['fecha_fin'])) {
            $builder->where('DATE(fecha_accion) <=', $filtros['fecha_fin']);
        }

        if (!empty($filtros['usuario'])) {
            $builder->groupStart()
                    ->like('usuarios.cedula', $filtros['usuario'])
                    ->orLike('usuarios.email', $filtros['usuario'])
                    ->groupEnd();
        }

        if (!empty($filtros['modulo'])) {
            $builder->where('modulo', $filtros['modulo']);
        }

        if (!empty($filtros['accion'])) {
            $builder->like('accion', $filtros['accion']);
        }

        return $builder->limit($limit, $offset)->findAll();
    }

    /**
     * Contar logs con filtros
     */
    public function contarLogsConFiltros($filtros = [])
    {
        $builder = $this->join('usuarios', 'usuarios.id_usuario = logs_sistema.id_usuario', 'left');

        // Aplicar filtros
        if (!empty($filtros['fecha_inicio'])) {
            $builder->where('DATE(fecha_accion) >=', $filtros['fecha_inicio']);
        }

        if (!empty($filtros['fecha_fin'])) {
            $builder->where('DATE(fecha_accion) <=', $filtros['fecha_fin']);
        }

        if (!empty($filtros['usuario'])) {
            $builder->groupStart()
                    ->like('usuarios.cedula', $filtros['usuario'])
                    ->orLike('usuarios.email', $filtros['usuario'])
                    ->groupEnd();
        }

        if (!empty($filtros['modulo'])) {
            $builder->where('modulo', $filtros['modulo']);
        }

        if (!empty($filtros['accion'])) {
            $builder->like('accion', $filtros['accion']);
        }

        return $builder->countAllResults();
    }

    /**
     * Obtener estadísticas de logs
     */
    public function obtenerEstadisticasLogs($dias = 7)
    {
        $db = \Config\Database::connect();
        
        $fechaInicio = date('Y-m-d', strtotime("-{$dias} days"));
        
        $query = $db->query("
            SELECT 
                DATE(fecha_accion) as fecha,
                COUNT(*) as total_acciones,
                COUNT(DISTINCT id_usuario) as usuarios_activos,
                COUNT(DISTINCT modulo) as modulos_utilizados
            FROM logs_sistema 
            WHERE DATE(fecha_accion) >= ?
            GROUP BY DATE(fecha_accion)
            ORDER BY fecha DESC
        ", [$fechaInicio]);
        
        return $query->getResultArray();
    }

    /**
     * Obtener módulos más utilizados
     */
    public function obtenerModulosMasUtilizados($limite = 10)
    {
        return $this->select('modulo, COUNT(*) as total_acciones')
                    ->groupBy('modulo')
                    ->orderBy('total_acciones', 'DESC')
                    ->limit($limite)
                    ->findAll();
    }

    /**
     * Limpiar logs antiguos
     */
    public function limpiarLogsAntiguos($diasRetencion = 90)
    {
        $fechaLimite = date('Y-m-d', strtotime("-{$diasRetencion} days"));
        return $this->where('DATE(fecha_accion) <', $fechaLimite)->delete();
    }

    /**
     * Obtener IP address del cliente
     */
    private function obtenerIpAddress()
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            return $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            return $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            return $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        }
    }
}
