<?php

namespace App\Models;

use CodeIgniter\Model;

class CandidatoModel extends Model
{
    protected $table = 'candidatos';
    protected $primaryKey = 'id_candidato';
    protected $allowedFields = [
        'nombres', 'apellidos', 'cedula', 'email', 'telefono', 
        'cv_url', 'estado'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $returnType = 'array';

    public function getCandidatosCompletos()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('candidatos c');
        $builder->select('c.*, p.id_vacante, p.fecha_postulacion, p.puntaje_prueba, p.observaciones');
        $builder->join('postulaciones p', 'p.id_candidato = c.id_candidato', 'left');
        $builder->orderBy('c.created_at', 'DESC');
        return $builder->get()->getResultArray();
    }

    public function getCandidatosPorEstado($estado)
    {
        return $this->where('estado', $estado)->findAll();
    }

    public function getEstadisticasCandidatos()
    {
        $enRevision = $this->where('estado', 'En revisión')->countAllResults();
        $entrevista = $this->where('estado', 'Entrevista')->countAllResults();
        $contratado = $this->where('estado', 'Contratado')->countAllResults();
        $rechazado = $this->where('estado', 'Rechazado')->countAllResults();
        
        return [
            'en_revision' => $enRevision,
            'entrevista' => $entrevista,
            'contratado' => $contratado,
            'rechazado' => $rechazado,
            'total' => $enRevision + $entrevista + $contratado + $rechazado
        ];
    }
} 