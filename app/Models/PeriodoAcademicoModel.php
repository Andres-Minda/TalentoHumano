<?php

namespace App\Models;

use CodeIgniter\Model;

class PeriodoAcademicoModel extends Model
{
    protected $table = 'periodos_academicos';
    protected $primaryKey = 'id_periodo';
    protected $allowedFields = [
        'nombre', 'fecha_inicio', 'fecha_fin', 'estado', 'descripcion'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $returnType = 'array';

    public function getPeriodoActivo()
    {
        return $this->where('estado', 'Activo')->first();
    }

    public function getPeriodosActivos()
    {
        return $this->where('estado', 'Activo')->findAll();
    }

    public function getPeriodosDisponibles()
    {
        return $this->whereIn('estado', ['Activo', 'Inactivo'])->findAll();
    }

    public function activarPeriodo($idPeriodo)
    {
        // Desactivar todos los periodos
        $this->set('estado', 'Inactivo')->update();
        
        // Activar el periodo seleccionado
        return $this->update($idPeriodo, ['estado' => 'Activo']);
    }

    public function getEstadisticasPeriodo($idPeriodo)
    {
        $db = \Config\Database::connect();
        
        $query = $db->query("
            SELECT 
                COUNT(DISTINCT e.id_empleado) as total_empleados,
                COUNT(DISTINCT c.id_capacitacion) as total_capacitaciones,
                COUNT(DISTINCT a.id_asistencia) as total_asistencias
            FROM periodos_academicos pa
            LEFT JOIN empleados e ON e.periodo_academico_id = pa.id_periodo
            LEFT JOIN capacitaciones c ON c.periodo_academico_id = pa.id_periodo
            LEFT JOIN asistencias a ON a.id_empleado = e.id_empleado
            WHERE pa.id_periodo = ?
        ", [$idPeriodo]);
        
        return $query->getRow();
    }

    public function getEmpleadosPorPeriodo($idPeriodo)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('empleados e');
        $builder->select('e.*, u.email, u.cedula, r.nombre_rol, d.nombre as departamento_nombre, p.nombre as puesto_nombre');
        $builder->join('usuarios u', 'u.id_usuario = e.id_usuario');
        $builder->join('roles r', 'r.id_rol = u.id_rol');
        $builder->join('departamentos d', 'd.id_departamento = e.id_departamento', 'left');
        $builder->join('puestos p', 'p.id_puesto = e.id_puesto', 'left');
        $builder->where('e.periodo_academico_id', $idPeriodo);
        $builder->where('e.activo', 1);
        $builder->orderBy('e.apellidos', 'ASC');
        return $builder->get()->getResultArray();
    }

    public function getCapacitacionesPorPeriodo($idPeriodo)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('capacitaciones c');
        $builder->select('c.*, COUNT(ec.id_empleado_capacitacion) as total_inscritos');
        $builder->join('empleados_capacitaciones ec', 'ec.id_capacitacion = c.id_capacitacion', 'left');
        $builder->where('c.periodo_academico_id', $idPeriodo);
        $builder->groupBy('c.id_capacitacion');
        $builder->orderBy('c.fecha_inicio', 'DESC');
        return $builder->get()->getResultArray();
    }
} 