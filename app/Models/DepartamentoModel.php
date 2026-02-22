<?php

namespace App\Models;

use CodeIgniter\Model;

class DepartamentoModel extends Model
{
    protected $table            = 'departamentos';
    protected $primaryKey       = 'id_departamento';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields = [
        'nombre',
        'descripcion',
        'responsable',
        'email_contacto',
        'telefono',
        'ubicacion',
        'estado',
        'activo'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validaciones
    protected $validationRules = [
        'nombre'         => 'required|min_length[3]|max_length[100]|is_unique[departamentos.nombre,id_departamento,{id_departamento}]',
        'descripcion'    => 'permit_empty|max_length[500]',
        'responsable'    => 'permit_empty|max_length[100]',
        'email_contacto' => 'permit_empty|valid_email|max_length[100]',
        'telefono'       => 'permit_empty|max_length[20]',
        'ubicacion'      => 'permit_empty|max_length[200]',
        'estado'         => 'permit_empty|in_list[Activo,Inactivo,Suspendido]',
        'activo'         => 'permit_empty|in_list[0,1]'
    ];

    protected $validationMessages = [
        'nombre' => [
            'required'   => 'El nombre del departamento es obligatorio',
            'min_length' => 'El nombre debe tener al menos 3 caracteres',
            'max_length' => 'El nombre no puede exceder 100 caracteres',
            'is_unique'  => 'Ya existe un departamento con ese nombre'
        ],
        'descripcion' => [
            'max_length' => 'La descripción no puede exceder 500 caracteres'
        ],
        'responsable' => [
            'max_length' => 'El nombre del responsable no puede exceder 100 caracteres'
        ],
        'email_contacto' => [
            'valid_email' => 'El email de contacto debe ser válido',
            'max_length'  => 'El email no puede exceder 100 caracteres'
        ],
        'telefono' => [
            'max_length' => 'El teléfono no puede exceder 20 caracteres'
        ],
        'ubicacion' => [
            'max_length' => 'La ubicación no puede exceder 200 caracteres'
        ],
        'estado' => [
            'in_list' => 'El estado debe ser Activo, Inactivo o Suspendido'
        ],
        'activo' => [
            'in_list' => 'El campo activo debe ser 0 o 1'
        ]
    ];

    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    /**
     * Obtener departamentos activos
     */
    public function getDepartamentosActivos()
    {
        return $this->where('activo', 1)
                    ->where('estado', 'Activo')
                    ->orderBy('nombre', 'ASC')
                    ->findAll();
    }

    /**
     * Obtener departamentos para dropdown
     */
    public function getDepartamentosParaDropdown()
    {
        $departamentos = $this->getDepartamentosActivos();
        $dropdown = [];
        
        foreach ($departamentos as $dept) {
            $dropdown[$dept['id_departamento']] = $dept['nombre'];
        }
        
        return $dropdown;
    }

    /**
     * Obtener departamento por ID con información completa
     */
    public function getDepartamentoCompleto($idDepartamento)
    {
        return $this->find($idDepartamento);
    }

    /**
     * Buscar departamentos por término
     */
    public function buscarDepartamentos($termino)
    {
        return $this->like('nombre', $termino)
                    ->orLike('descripcion', $termino)
                    ->orLike('responsable', $termino)
                    ->where('activo', 1)
                    ->orderBy('nombre', 'ASC')
                    ->findAll();
    }

    /**
     * Cambiar estado del departamento
     */
    public function cambiarEstado($idDepartamento, $nuevoEstado)
    {
        $data = [
            'estado' => $nuevoEstado,
            'activo' => ($nuevoEstado === 'Activo') ? 1 : 0
        ];
        
        return $this->update($idDepartamento, $data);
    }

    /**
     * Obtener estadísticas de departamentos
     */
    public function getEstadisticasDepartamentos()
    {
        $builder = $this->builder();
        $builder->select('
            COUNT(*) as total,
            SUM(CASE WHEN estado = "Activo" THEN 1 ELSE 0 END) as activos,
            SUM(CASE WHEN estado = "Inactivo" THEN 1 ELSE 0 END) as inactivos,
            SUM(CASE WHEN estado = "Suspendido" THEN 1 ELSE 0 END) as suspendidos
        ');
        
        return $builder->get()->getRowArray();
    }

    /**
     * Verificar si el departamento está en uso por empleados
     */
    public function departamentoEnUso($idDepartamento)
    {
        $db = \Config\Database::connect();
        $empleados = $db->table('empleados')
                        ->where('id_departamento', $idDepartamento)
                        ->where('activo', 1)
                        ->countAllResults();
        
        return $empleados > 0;
    }

    /**
     * Obtener empleados por departamento
     */
    public function getEmpleadosPorDepartamento($idDepartamento)
    {
        $db = \Config\Database::connect();
        return $db->table('empleados e')
                  ->select('e.id_empleado, e.nombres, e.apellidos, e.tipo_empleado, e.estado')
                  ->where('e.id_departamento', $idDepartamento)
                  ->where('e.activo', 1)
                  ->get()
                  ->getResultArray();
    }

    /**
     * Obtener estadísticas de departamentos por estado
     */
    public function getDepartamentosPorEstado()
    {
        $builder = $this->builder();
        $builder->select('
            estado,
            COUNT(*) as total
        ');
        $builder->groupBy('estado');
        $builder->orderBy('total', 'DESC');
        
        return $builder->get()->getResultArray();
    }
} 