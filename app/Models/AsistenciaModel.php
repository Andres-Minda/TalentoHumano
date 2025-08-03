<?php

namespace App\Models;

use CodeIgniter\Model;

class AsistenciaModel extends Model
{
    protected $table = 'asistencias';
    protected $primaryKey = 'id_asistencia';
    protected $allowedFields = [
        'id_empleado', 'fecha', 'hora_entrada', 'hora_salida', 'tipo', 'estado', 'observaciones'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $returnType = 'array';

    public function getAsistenciasCompletas()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('asistencias a');
        $builder->select('a.*, CONCAT(e.nombres, " ", e.apellidos) as empleado_nombre');
        $builder->join('empleados e', 'e.id_empleado = a.id_empleado');
        $builder->orderBy('a.fecha', 'DESC');
        return $builder->get()->getResultArray();
    }

    public function getAsistenciasEmpleado($idEmpleado, $fechaInicio = null, $fechaFin = null)
    {
        $builder = $this->where('id_empleado', $idEmpleado);
        
        if ($fechaInicio) {
            $builder->where('fecha >=', $fechaInicio);
        }
        if ($fechaFin) {
            $builder->where('fecha <=', $fechaFin);
        }
        
        return $builder->orderBy('fecha', 'DESC')->findAll();
    }

    public function getEstadisticasAsistencias()
    {
        $db = \Config\Database::connect();
        
        $query = $db->query("
            SELECT 
                COUNT(*) as total_registros,
                SUM(CASE WHEN estado = 'Puntual' THEN 1 ELSE 0 END) as puntuales,
                SUM(CASE WHEN estado = 'Tardanza' THEN 1 ELSE 0 END) as tardanzas,
                SUM(CASE WHEN estado = 'Ausente' THEN 1 ELSE 0 END) as ausencias
            FROM asistencias
            WHERE fecha >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
        ");
        
        return $query->getRow();
    }

    public function getAsistenciasPorMes()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('asistencias');
        $builder->select('DATE_FORMAT(fecha, "%Y-%m") as mes, COUNT(*) as cantidad');
        $builder->where('fecha >=', date('Y-m-d', strtotime('-6 months')));
        $builder->groupBy('DATE_FORMAT(fecha, "%Y-%m")');
        $builder->orderBy('mes', 'ASC');
        return $builder->get()->getResultArray();
    }
} 