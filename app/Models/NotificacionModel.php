<?php

namespace App\Models;

use CodeIgniter\Model;

class NotificacionModel extends Model
{
    protected $table            = 'notificaciones';
    protected $primaryKey       = 'id_notificacion';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id_usuario',
        'tipo',
        'titulo',
        'mensaje',
        'datos_adicionales',
        'fecha_creacion',
        'fecha_leida',
        'leida',
        'url_accion'
    ];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'fecha_creacion';
    protected $updatedField  = null;
    protected $deletedField  = null;

    // Validation
    protected $validationRules = [
        'id_usuario' => 'required|integer',
        'tipo'       => 'required|max_length[50]',
        'titulo'     => 'required|max_length[255]',
        'mensaje'    => 'required'
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
     * Obtener notificaciones de un usuario
     */
    public function getNotificacionesPorUsuario($idUsuario, $limit = 50, $soloNoLeidas = false)
    {
        $builder = $this->where('id_usuario', $idUsuario);
        
        if ($soloNoLeidas) {
            $builder->where('leida', 0);
        }
        
        return $builder->orderBy('fecha_creacion', 'DESC')
                      ->limit($limit)
                      ->find();
    }

    /**
     * Marcar notificación como leída
     */
    public function marcarComoLeida($idNotificacion, $idUsuario = null)
    {
        $builder = $this->where('id_notificacion', $idNotificacion);
        
        if ($idUsuario) {
            $builder->where('id_usuario', $idUsuario);
        }
        
        return $builder->update([
            'leida' => 1,
            'fecha_leida' => date('Y-m-d H:i:s')
        ]);
    }

    /**
     * Marcar todas las notificaciones de un usuario como leídas
     */
    public function marcarTodasComoLeidas($idUsuario)
    {
        return $this->where('id_usuario', $idUsuario)
                   ->where('leida', 0)
                   ->update([
                       'leida' => 1,
                       'fecha_leida' => date('Y-m-d H:i:s')
                   ]);
    }

    /**
     * Contar notificaciones no leídas de un usuario
     */
    public function contarNoLeidas($idUsuario)
    {
        return $this->where('id_usuario', $idUsuario)
                   ->where('leida', 0)
                   ->countAllResults();
    }

    /**
     * Eliminar notificaciones antiguas
     */
    public function limpiarNotificacionesAntiguas($diasAntiguedad = 30)
    {
        $fechaLimite = date('Y-m-d H:i:s', strtotime("-{$diasAntiguedad} days"));
        
        return $this->where('fecha_creacion <', $fechaLimite)
                   ->where('leida', 1)
                   ->delete();
    }

    /**
     * Crear notificación masiva para múltiples usuarios
     */
    public function crearNotificacionMasiva($usuarios, $tipo, $titulo, $mensaje, $datosAdicionales = null, $urlAccion = null)
    {
        $notificaciones = [];
        $fechaCreacion = date('Y-m-d H:i:s');
        
        foreach ($usuarios as $usuario) {
            $notificaciones[] = [
                'id_usuario' => is_array($usuario) ? $usuario['id_usuario'] : $usuario,
                'tipo' => $tipo,
                'titulo' => $titulo,
                'mensaje' => $mensaje,
                'datos_adicionales' => $datosAdicionales ? json_encode($datosAdicionales) : null,
                'fecha_creacion' => $fechaCreacion,
                'leida' => 0,
                'url_accion' => $urlAccion
            ];
        }
        
        return $this->insertBatch($notificaciones);
    }

    /**
     * Obtener estadísticas de notificaciones por usuario
     */
    public function getEstadisticasUsuario($idUsuario)
    {
        return [
            'total' => $this->where('id_usuario', $idUsuario)->countAllResults(),
            'no_leidas' => $this->where('id_usuario', $idUsuario)->where('leida', 0)->countAllResults(),
            'leidas' => $this->where('id_usuario', $idUsuario)->where('leida', 1)->countAllResults(),
            'ultima_notificacion' => $this->where('id_usuario', $idUsuario)
                                         ->orderBy('fecha_creacion', 'DESC')
                                         ->first()
        ];
    }

    /**
     * Obtener notificaciones por tipo
     */
    public function getNotificacionesPorTipo($tipo, $limit = 100)
    {
        return $this->where('tipo', $tipo)
                   ->orderBy('fecha_creacion', 'DESC')
                   ->limit($limit)
                   ->find();
    }
}
