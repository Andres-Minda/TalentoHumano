<?php

namespace App\Models;

use CodeIgniter\Model;

class DocumentoModel extends Model
{
    protected $table = 'documentos';
    protected $primaryKey = 'id_documento';
    protected $allowedFields = [
        'id_empleado', 'id_categoria', 'nombre', 'descripcion', 
        'archivo_url', 'tamaño', 'tipo_archivo', 'estado', 'fecha_subida'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $returnType = 'array';

    public function getDocumentosEmpleado($idEmpleado)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('documentos d');
        $builder->select('d.*, c.nombre as categoria_nombre');
        $builder->join('categorias c', 'c.id_categoria = d.id_categoria', 'left');
        $builder->where('d.id_empleado', $idEmpleado);
        $builder->orderBy('d.fecha_subida', 'DESC');
        return $builder->get()->getResultArray();
    }

    public function getDocumentoCompleto($idDocumento)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('documentos d');
        $builder->select('d.*, c.nombre as categoria_nombre, e.nombres as empleado_nombres, e.apellidos as empleado_apellidos');
        $builder->join('categorias c', 'c.id_categoria = d.id_categoria', 'left');
        $builder->join('empleados e', 'e.id_empleado = d.id_empleado');
        $builder->where('d.id_documento', $idDocumento);
        return $builder->get()->getRowArray();
    }

    public function getEstadisticasEmpleado($idEmpleado)
    {
        $db = \Config\Database::connect();
        
        $query = $db->query("
            SELECT 
                COUNT(*) as total_documentos,
                SUM(CASE WHEN estado = 'Aprobado' THEN 1 ELSE 0 END) as documentos_aprobados,
                SUM(CASE WHEN estado = 'Pendiente' THEN 1 ELSE 0 END) as documentos_pendientes,
                SUM(CASE WHEN estado = 'Rechazado' THEN 1 ELSE 0 END) as documentos_rechazados
            FROM documentos
            WHERE id_empleado = ?
        ", [$idEmpleado]);
        
        return $query->getRow();
    }

    public function getDocumentosPorCategoria($idCategoria, $idEmpleado = null)
    {
        $builder = $this->where('id_categoria', $idCategoria);
        if ($idEmpleado) {
            $builder->where('id_empleado', $idEmpleado);
        }
        return $builder->findAll();
    }

    public function getDocumentosPorEstado($estado, $idEmpleado = null)
    {
        $builder = $this->where('estado', $estado);
        if ($idEmpleado) {
            $builder->where('id_empleado', $idEmpleado);
        }
        return $builder->findAll();
    }

    public function subirDocumento($data)
    {
        try {
            $data['fecha_subida'] = date('Y-m-d H:i:s');
            $data['estado'] = 'Pendiente';
            
            return $this->insert($data);
        } catch (\Exception $e) {
            return false;
        }
    }

    public function actualizarEstado($idDocumento, $estado, $comentarios = null)
    {
        $data = ['estado' => $estado];
        if ($comentarios) {
            $data['comentarios'] = $comentarios;
        }
        
        return $this->update($idDocumento, $data);
    }
} 