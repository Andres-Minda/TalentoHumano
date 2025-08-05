<?php

namespace App\Models;

use CodeIgniter\Model;

class CategoriaModel extends Model
{
    protected $table = 'categorias';
    protected $primaryKey = 'id_categoria';
    protected $allowedFields = ['nombre', 'descripcion', 'activo'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $returnType = 'array';

    public function getCategoriasActivas()
    {
        return $this->where('activo', 1)->findAll();
    }

    public function getCategoriasConEstadisticas()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('categorias c');
        $builder->select('c.*, COUNT(d.id_documento) as total_documentos');
        $builder->join('documentos d', 'd.tipo_documento = c.nombre', 'left');
        $builder->where('c.activo', 1);
        $builder->groupBy('c.id_categoria');
        return $builder->get()->getResultArray();
    }
} 