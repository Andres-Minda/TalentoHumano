<?php

namespace App\Models;

use CodeIgniter\Model;

class RespaldoModel extends Model
{
    protected $table            = 'respaldos';
    protected $primaryKey       = 'id_respaldo';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'nombre_archivo',
        'ruta_archivo',
        'tamano_bytes',
        'fecha_creacion',
        'creado_por',
        'tipo_respaldo',
        'estado'
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
        'nombre_archivo' => 'required|string|max_length[255]',
        'ruta_archivo' => 'required|string|max_length[500]',
        'tamano_bytes' => 'permit_empty|integer',
        'creado_por' => 'required|integer',
        'tipo_respaldo' => 'required|in_list[COMPLETO,INCREMENTAL]',
        'estado' => 'required|in_list[EXITOSO,FALLIDO,EN_PROGRESO]'
    ];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = ['setFechaCreacion'];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    /**
     * Callback para establecer la fecha de creación
     */
    protected function setFechaCreacion(array $data)
    {
        if (!isset($data['data']['fecha_creacion'])) {
            $data['data']['fecha_creacion'] = date('Y-m-d H:i:s');
        }
        return $data;
    }

    /**
     * Crear un nuevo respaldo
     */
    public function crearRespaldo($nombreArchivo, $rutaArchivo, $creadoPor, $tipoRespaldo = 'COMPLETO')
    {
        $tamanoBytes = file_exists($rutaArchivo) ? filesize($rutaArchivo) : 0;
        
        $data = [
            'nombre_archivo' => $nombreArchivo,
            'ruta_archivo' => $rutaArchivo,
            'tamano_bytes' => $tamanoBytes,
            'fecha_creacion' => date('Y-m-d H:i:s'),
            'creado_por' => $creadoPor,
            'tipo_respaldo' => $tipoRespaldo,
            'estado' => 'EN_PROGRESO'
        ];

        return $this->insert($data);
    }

    /**
     * Actualizar el estado de un respaldo
     */
    public function actualizarEstado($idRespaldo, $estado, $tamanoBytes = null)
    {
        $data = ['estado' => $estado];
        
        if ($tamanoBytes !== null) {
            $data['tamano_bytes'] = $tamanoBytes;
        }
        
        return $this->update($idRespaldo, $data);
    }

    /**
     * Obtener respaldos con información del usuario
     */
    public function obtenerRespaldosConUsuario($limite = 20, $offset = 0)
    {
        return $this->select('respaldos.*, usuarios.cedula, usuarios.email')
                    ->join('usuarios', 'usuarios.id_usuario = respaldos.creado_por', 'left')
                    ->orderBy('fecha_creacion', 'DESC')
                    ->limit($limite, $offset)
                    ->findAll();
    }

    /**
     * Obtener estadísticas de respaldos
     */
    public function obtenerEstadisticasRespaldos()
    {
        $db = \Config\Database::connect();
        
        $query = $db->query("
            SELECT 
                COUNT(*) as total_respaldos,
                COUNT(CASE WHEN estado = 'EXITOSO' THEN 1 END) as respaldos_exitosos,
                COUNT(CASE WHEN estado = 'FALLIDO' THEN 1 END) as respaldos_fallidos,
                COUNT(CASE WHEN estado = 'EN_PROGRESO' THEN 1 END) as respaldos_en_progreso,
                SUM(tamano_bytes) as tamano_total_bytes,
                AVG(tamano_bytes) as tamano_promedio_bytes,
                COUNT(CASE WHEN tipo_respaldo = 'COMPLETO' THEN 1 END) as respaldos_completos,
                COUNT(CASE WHEN tipo_respaldo = 'INCREMENTAL' THEN 1 END) as respaldos_incrementales
            FROM respaldos
        ");
        
        return $query->getRowArray();
    }

    /**
     * Generar respaldo de la base de datos
     */
    public function generarRespaldoBaseDatos($creadoPor)
    {
        try {
            $fecha = date('Y-m-d_H-i-s');
            $nombreArchivo = "backup_talent_human_db_{$fecha}.sql";
            $rutaArchivo = WRITEPATH . 'backups/' . $nombreArchivo;
            
            // Crear directorio si no existe
            if (!is_dir(WRITEPATH . 'backups/')) {
                mkdir(WRITEPATH . 'backups/', 0755, true);
            }
            
            // Registrar respaldo en progreso
            $idRespaldo = $this->crearRespaldo($nombreArchivo, $rutaArchivo, $creadoPor);
            
            // Ejecutar mysqldump
            $command = "C:\\xampp\\mysql\\bin\\mysqldump.exe -u root talent_human_db > \"{$rutaArchivo}\"";
            $output = [];
            $returnCode = 0;
            
            exec($command, $output, $returnCode);
            
            if ($returnCode === 0 && file_exists($rutaArchivo)) {
                $tamanoBytes = filesize($rutaArchivo);
                $this->actualizarEstado($idRespaldo, 'EXITOSO', $tamanoBytes);
                
                return [
                    'success' => true,
                    'message' => 'Respaldo creado exitosamente',
                    'archivo' => $nombreArchivo,
                    'tamano' => $this->formatearTamano($tamanoBytes),
                    'id_respaldo' => $idRespaldo
                ];
            } else {
                $this->actualizarEstado($idRespaldo, 'FALLIDO');
                
                return [
                    'success' => false,
                    'message' => 'Error al crear el respaldo',
                    'error' => implode("\n", $output)
                ];
            }
            
        } catch (\Exception $e) {
            if (isset($idRespaldo)) {
                $this->actualizarEstado($idRespaldo, 'FALLIDO');
            }
            
            return [
                'success' => false,
                'message' => 'Error al generar respaldo: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Limpiar respaldos antiguos
     */
    public function limpiarRespaldosAntiguos($diasRetencion = 30)
    {
        $fechaLimite = date('Y-m-d', strtotime("-{$diasRetencion} days"));
        
        // Obtener respaldos a eliminar
        $respaldosAEliminar = $this->where('DATE(fecha_creacion) <', $fechaLimite)->findAll();
        
        $eliminados = 0;
        foreach ($respaldosAEliminar as $respaldo) {
            // Eliminar archivo físico si existe
            if (file_exists($respaldo['ruta_archivo'])) {
                unlink($respaldo['ruta_archivo']);
            }
            
            // Eliminar registro de la base de datos
            if ($this->delete($respaldo['id_respaldo'])) {
                $eliminados++;
            }
        }
        
        return $eliminados;
    }

    /**
     * Formatear tamaño en bytes a formato legible
     */
    private function formatearTamano($bytes)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        
        $bytes /= pow(1024, $pow);
        
        return round($bytes, 2) . ' ' . $units[$pow];
    }
}
