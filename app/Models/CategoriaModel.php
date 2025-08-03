<?php

namespace App\Models;

use CodeIgniter\Model;

class CategoriaModel extends Model
{
    protected $table = 'categorias';
    protected $primaryKey = 'id_categoria';
    protected $allowedFields = [
        'nombre', 'descripcion', 'activo'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $returnType = 'array';

    public function getCategoriasActivas()
    {
        return $this->where('activo', 1)->findAll();
    }

    public function getCategoriaConEstadisticas($idCategoria)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('categorias c');
        $builder->select('c.*, COUNT(d.id_documento) as total_documentos');
        $builder->join('documentos d', 'd.id_categoria = c.id_categoria', 'left');
        $builder->where('c.id_categoria', $idCategoria);
        $builder->groupBy('c.id_categoria');
        return $builder->get()->getRowArray();
    }

    public function getCategoriasConEstadisticas()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('categorias c');
        $builder->select('c.*, COUNT(d.id_documento) as total_documentos');
        $builder->join('documentos d', 'd.id_categoria = c.id_categoria', 'left');
        $builder->where('c.activo', 1);
        $builder->groupBy('c.id_categoria');
        $builder->orderBy('c.nombre', 'ASC');
        return $builder->get()->getResultArray();
    }

    public function getEstadisticasCategorias()
    {
        $db = \Config\Database::connect();
        
        $query = $db->query("
            SELECT 
                COUNT(*) as total_categorias,
                SUM(CASE WHEN activo = 1 THEN 1 ELSE 0 END) as categorias_activas
            FROM categorias
        ");
        
        return $query->getRow();
    }
} 