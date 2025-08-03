<?php

namespace App\Models;

use CodeIgniter\Model;

class EmpleadoModel extends Model
{
    protected $table = 'empleados';
    protected $primaryKey = 'id_empleado';
    protected $allowedFields = [
        'id_usuario', 'tipo_empleado', 'nombres', 'apellidos', 
        'fecha_nacimiento', 'genero', 'estado_civil', 'direccion', 
        'telefono', 'fecha_ingreso', 'id_departamento', 'id_puesto', 
        'salario', 'estado', 'activo', 'foto_url'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    /**
     * Obtener empleado por ID de usuario
     */
    public function getEmpleadoByUsuarioId($idUsuario)
    {
        return $this->where('id_usuario', $idUsuario)->first();
    }

    /**
     * Obtener empleados activos
     */
    public function getEmpleadosActivos()
    {
        return $this->where('activo', 1)->findAll();
    }

    /**
     * Obtener empleados por tipo
     */
    public function getEmpleadosPorTipo($tipo)
    {
        return $this->where('tipo_empleado', $tipo)
                   ->where('activo', 1)
                   ->findAll();
    }

    /**
     * Obtener empleado con información completa
     */
    public function getEmpleadoCompleto($idEmpleado)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('empleados e');
        $builder->select('e.*, u.email, u.cedula, r.nombre_rol, d.nombre as departamento_nombre, p.nombre as puesto_nombre');
        $builder->join('usuarios u', 'u.id_usuario = e.id_usuario');
        $builder->join('roles r', 'r.id_rol = u.id_rol');
        $builder->join('departamentos d', 'd.id_departamento = e.id_departamento', 'left');
        $builder->join('puestos p', 'p.id_puesto = e.id_puesto', 'left');
        $builder->where('e.id_empleado', $idEmpleado);
        return $builder->get()->getRowArray();
    }

    /**
     * Obtener todos los empleados con información completa
     */
    public function getAllEmpleadosCompletos()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('empleados e');
        $builder->select('e.*, u.email, u.cedula, r.nombre_rol, d.nombre as departamento_nombre, p.nombre as puesto_nombre');
        $builder->join('usuarios u', 'u.id_usuario = e.id_usuario');
        $builder->join('roles r', 'r.id_rol = u.id_rol');
        $builder->join('departamentos d', 'd.id_departamento = e.id_departamento', 'left');
        $builder->join('puestos p', 'p.id_puesto = e.id_puesto', 'left');
        $builder->where('e.activo', 1);
        $builder->orderBy('e.apellidos', 'ASC');
        return $builder->get()->getResultArray();
    }

    /**
     * Buscar empleados por nombre o apellido
     */
    public function buscarEmpleados($termino)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('empleados e');
        $builder->select('e.*, u.email, u.cedula, r.nombre_rol, d.nombre as departamento_nombre, p.nombre as puesto_nombre');
        $builder->join('usuarios u', 'u.id_usuario = e.id_usuario');
        $builder->join('roles r', 'r.id_rol = u.id_rol');
        $builder->join('departamentos d', 'd.id_departamento = e.id_departamento', 'left');
        $builder->join('puestos p', 'p.id_puesto = e.id_puesto', 'left');
        $builder->groupStart()
                ->like('e.nombres', $termino)
                ->orLike('e.apellidos', $termino)
                ->orLike('u.email', $termino)
                ->orLike('u.cedula', $termino)
                ->groupEnd();
        $builder->where('e.activo', 1);
        return $builder->get()->getResultArray();
    }

    /**
     * Obtener empleados por departamento
     */
    public function getEmpleadosPorDepartamento($idDepartamento)
    {
        return $this->where('id_departamento', $idDepartamento)
                    ->where('activo', 1)
                    ->findAll();
    }

    /**
     * Obtener empleados por puesto
     */
    public function getEmpleadosPorPuesto($idPuesto)
    {
        return $this->where('id_puesto', $idPuesto)
                    ->where('activo', 1)
                    ->findAll();
    }

    /**
     * Obtener estadísticas de empleados
     */
    public function getEstadisticasEmpleados()
    {
        $db = \Config\Database::connect();
        
        $query = $db->query("
            SELECT 
                COUNT(*) as total_empleados,
                SUM(CASE WHEN tipo_empleado = 'Docente' THEN 1 ELSE 0 END) as total_docentes,
                SUM(CASE WHEN tipo_empleado = 'Administrativo' THEN 1 ELSE 0 END) as total_administrativos,
                SUM(CASE WHEN activo = 1 THEN 1 ELSE 0 END) as empleados_activos
            FROM empleados
        ");
        
        return $query->getRow();
    }

    /**
     * Obtener empleados por departamento para gráficos
     */
    public function getEmpleadosPorDepartamentoChart()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('empleados e');
        $builder->select('d.nombre as departamento, COUNT(e.id_empleado) as cantidad');
        $builder->join('departamentos d', 'd.id_departamento = e.id_departamento', 'left');
        $builder->where('e.activo', 1);
        $builder->groupBy('d.id_departamento, d.nombre');
        $builder->orderBy('cantidad', 'DESC');
        return $builder->get()->getResultArray();
    }
} 