<?php

namespace App\Models;

use CodeIgniter\Model;

class VacanteModel extends Model
{
    protected $table = 'vacantes';
    protected $primaryKey = 'id_vacante';
    protected $allowedFields = [
        'id_puesto', 'fecha_publicacion', 'fecha_cierre', 'estado', 
        'descripcion', 'requisitos'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $returnType = 'array';

    public function getVacantesCompletas()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('vacantes v');
        $builder->select('v.*, p.nombre as puesto_nombre, d.nombre as departamento_nombre, p.salario_base, COUNT(c.id_candidato) as total_candidatos');
        $builder->join('puestos p', 'p.id_puesto = v.id_puesto');
        $builder->join('departamentos d', 'd.id_departamento = p.id_departamento');
        $builder->join('candidatos c', 'c.id_vacante = v.id_vacante', 'left');
        $builder->groupBy('v.id_vacante');
        $builder->orderBy('v.fecha_publicacion', 'DESC');
        return $builder->get()->getResultArray();
    }

    public function getVacantesActivas()
    {
        return $this->where('estado', 'Abierta')->findAll();
    }

    public function getEstadisticasVacantes()
    {
        $abiertas = $this->where('estado', 'Abierta')->countAllResults();
        $cerradas = $this->where('estado', 'Cerrada')->countAllResults();
        $canceladas = $this->where('estado', 'Cancelada')->countAllResults();
        
        return [
            'abiertas' => $abiertas,
            'cerradas' => $cerradas,
            'canceladas' => $canceladas,
            'total' => $abiertas + $cerradas + $canceladas
        ];
    }

    public function getEstadoVacantes()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('vacantes');
        $builder->select('estado, COUNT(*) as cantidad');
        $builder->groupBy('estado');
        $result = $builder->get()->getResultArray();
        
        // Asegurar que todos los estados estén representados
        $estados = ['Abierta', 'Cerrada', 'Cancelada'];
        $data = [];
        
        foreach ($estados as $estado) {
            $encontrado = false;
            foreach ($result as $row) {
                if ($row['estado'] == $estado) {
                    $data[] = $row;
                    $encontrado = true;
                    break;
                }
            }
            if (!$encontrado) {
                $data[] = ['estado' => $estado, 'cantidad' => 0];
            }
        }
        
        return $data;
    }
} 