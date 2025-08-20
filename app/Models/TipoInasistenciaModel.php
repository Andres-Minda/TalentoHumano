<?php

namespace App\Models;

use CodeIgniter\Model;

class TipoInasistenciaModel extends Model
{
    protected $table            = 'tipos_inasistencia';
    protected $primaryKey       = 'id_tipo';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields = [
        'nombre_tipo',
        'descripcion',
        'requiere_justificacion',
        'activo'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = false;

    // Validaciones
    protected $validationRules = [
        'nombre_tipo'            => 'required|min_length[3]|max_length[100]|is_unique[tipos_inasistencia.nombre_tipo,id_tipo,{id_tipo}]',
        'descripcion'            => 'permit_empty|max_length[500]',
        'requiere_justificacion' => 'permit_empty|in_list[0,1]',
        'activo'                 => 'permit_empty|in_list[0,1]'
    ];

    protected $validationMessages = [
        'nombre_tipo' => [
            'required'   => 'El nombre del tipo es obligatorio',
            'min_length' => 'El nombre debe tener al menos 3 caracteres',
            'max_length' => 'El nombre no puede exceder 100 caracteres',
            'is_unique'  => 'Ya existe un tipo con ese nombre'
        ],
        'requiere_justificacion' => [
            'in_list' => 'El valor debe ser 0 o 1'
        ],
        'activo' => [
            'in_list' => 'El valor debe ser 0 o 1'
        ]
    ];

    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    /**
     * Obtener tipos activos
     */
    public function getTiposActivos()
    {
        return $this->where('activo', 1)->findAll();
    }

    /**
     * Obtener tipos que requieren justificación
     */
    public function getTiposConJustificacion()
    {
        return $this->where('requiere_justificacion', 1)
                    ->where('activo', 1)
                    ->findAll();
    }

    /**
     * Obtener tipos que NO requieren justificación
     */
    public function getTiposSinJustificacion()
    {
        return $this->where('requiere_justificacion', 0)
                    ->where('activo', 1)
                    ->findAll();
    }

    /**
     * Obtener tipos por categoría
     */
    public function getTiposPorCategoria($categoria = null)
    {
        $builder = $this->builder();
        $builder->where('activo', 1);
        
        if ($categoria) {
            $builder->like('nombre_tipo', $categoria);
        }
        
        $builder->orderBy('nombre_tipo', 'ASC');
        
        return $builder->get()->getResultArray();
    }

    /**
     * Obtener estadísticas de uso de tipos
     */
    public function getEstadisticasUso()
    {
        $builder = $this->db->table('tipos_inasistencia ti');
        $builder->select('ti.id_tipo, ti.nombre_tipo, COUNT(i.id_inasistencia) as total_uso');
        $builder->join('inasistencias i', 'i.tipo_inasistencia = ti.nombre_tipo', 'left');
        $builder->where('ti.activo', 1);
        $builder->groupBy('ti.id_tipo, ti.nombre_tipo');
        $builder->orderBy('total_uso', 'DESC');
        
        return $builder->get()->getResultArray();
    }

    /**
     * Activar/desactivar tipo
     */
    public function cambiarEstado($idTipo, $nuevoEstado)
    {
        return $this->update($idTipo, ['activo' => $nuevoEstado]);
    }

    /**
     * Obtener tipos para formulario de selección
     */
    public function getTiposParaFormulario()
    {
        $tipos = $this->getTiposActivos();
        $opciones = [];
        
        foreach ($tipos as $tipo) {
            $opciones[$tipo['nombre_tipo']] = $tipo['nombre_tipo'];
        }
        
        return $opciones;
    }
}
