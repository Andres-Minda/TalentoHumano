<?php

namespace App\Models;

use CodeIgniter\Model;

class CertificadoModel extends Model
{
    protected $table = 'certificados';
    protected $primaryKey = 'id_certificado';
    protected $allowedFields = [
        'id_empleado', 'id_capacitacion', 'titulo', 'institucion', 'fecha_emision',
        'fecha_vencimiento', 'url_certificado', 'estado', 'activo'
    ];
    protected $useTimestamps = false;
    protected $returnType = 'array';

    // Obtener certificados de un empleado
    public function getCertificadosEmpleado($idEmpleado)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('certificados c');
        $builder->select('c.*, cap.titulo as capacitacion_titulo');
        $builder->join('capacitaciones cap', 'cap.id_capacitacion = c.id_capacitacion', 'left');
        $builder->where('c.id_empleado', $idEmpleado);
        $builder->where('c.activo', 1);
        $builder->orderBy('c.fecha_emision', 'DESC');
        return $builder->get()->getResultArray();
    }

    // Obtener certificados por estado
    public function getCertificadosPorEstado($estado)
    {
        return $this->where('estado', $estado)
                    ->where('activo', 1)
                    ->findAll();
    }
} 