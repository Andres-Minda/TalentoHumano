<?php

namespace App\Models;

use CodeIgniter\Model;

class DocumentoModel extends Model
{
    protected $table      = 'documentos';
    protected $primaryKey = 'id_documento';
    protected $returnType = 'array';

    protected $allowedFields = [
        'id_empleado',
        'tipo_documento',
        'nombre',
        'descripcion',
        'archivo_url',
        'fecha_emision',
        'fecha_vencimiento',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = '';   // la tabla no tiene updated_at

    /**
     * Documentos de un empleado ordenados por fecha descendente
     */
    public function getDocumentosEmpleado(int $idEmpleado): array
    {
        return $this->where('id_empleado', $idEmpleado)
                    ->orderBy('created_at', 'DESC')
                    ->findAll();
    }

    /**
     * Busca un documento verificando que pertenezca al empleado indicado (seguridad)
     */
    public function getDocumentoSeguro(int $idDocumento, int $idEmpleado): ?array
    {
        return $this->where('id_documento', $idDocumento)
                    ->where('id_empleado', $idEmpleado)
                    ->first();
    }

    /**
     * Estadísticas rápidas: total, vigentes y vencidos
     */
    public function getEstadisticasEmpleado(int $idEmpleado): array
    {
        $db    = \Config\Database::connect();
        $today = date('Y-m-d');

        $row = $db->query("
            SELECT
                COUNT(*)                                                     AS total,
                SUM(fecha_vencimiento IS NULL OR fecha_vencimiento >= ?)     AS vigentes,
                SUM(fecha_vencimiento IS NOT NULL AND fecha_vencimiento < ?) AS vencidos
            FROM documentos
            WHERE id_empleado = ?
        ", [$today, $today, $idEmpleado])->getRowArray();

        return [
            'total'    => (int) ($row['total']    ?? 0),
            'vigentes' => (int) ($row['vigentes'] ?? 0),
            'vencidos' => (int) ($row['vencidos'] ?? 0),
        ];
    }
}
