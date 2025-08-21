<?php

namespace App\Models;

use CodeIgniter\Model;

class PostulanteModel extends Model
{
    protected $table            = 'postulantes';
    protected $primaryKey       = 'id_postulante';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields = [
        'id_usuario',
        'id_puesto',
        'nombres',
        'apellidos',
        'cedula',
        'email',
        'telefono',
        'fecha_nacimiento',
        'genero',
        'estado_civil',
        'direccion',
        'ciudad',
        'provincia',
        'codigo_postal',
        'nacionalidad',
        'estado_postulacion',
        'fecha_postulacion',
        'cv_path',
        'carta_motivacion',
        'experiencia_laboral',
        'educacion',
        'habilidades',
        'idiomas',
        'certificaciones',
        'referencias',
        'disponibilidad_inmediata',
        'expectativa_salarial',
        'notas_admin',
        'activo'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validaciones
    protected $validationRules = [
        'id_usuario'              => 'required|integer',
        'id_puesto'               => 'required|integer',
        'nombres'                 => 'required|min_length[2]|max_length[100]',
        'apellidos'               => 'required|min_length[2]|max_length[100]',
        'cedula'                  => 'required|min_length[10]|max_length[10]',
        'email'                   => 'required|valid_email|max_length[100]',
        'telefono'                => 'required|min_length[10]|max_length[15]',
        'fecha_nacimiento'        => 'required|valid_date',
        'genero'                  => 'required|in_list[Masculino,Femenino,No especificado]',
        'estado_civil'            => 'required|in_list[Soltero,Casado,Divorciado,Viudo,Unión libre]',
        'direccion'               => 'required|min_length[10]|max_length[200]',
        'ciudad'                  => 'required|min_length[2]|max_length[100]',
        'provincia'               => 'required|min_length[2]|max_length[100]',
        'codigo_postal'           => 'permit_empty|max_length[10]',
        'nacionalidad'            => 'required|min_length[2]|max_length[100]',
        'estado_postulacion'      => 'required|in_list[Pendiente,En revisión,Aprobada,Rechazada,Contratado]',
        'fecha_postulacion'       => 'required|valid_date',
        'cv_path'                 => 'permit_empty|max_length[500]',
        'carta_motivacion'        => 'permit_empty|max_length[2000]',
        'experiencia_laboral'     => 'permit_empty|max_length[2000]',
        'educacion'               => 'permit_empty|max_length[2000]',
        'habilidades'             => 'permit_empty|max_length[1000]',
        'idiomas'                 => 'permit_empty|max_length[500]',
        'certificaciones'         => 'permit_empty|max_length[1000]',
        'referencias'             => 'permit_empty|max_length[1000]',
        'disponibilidad_inmediata' => 'required|in_list[Sí,No,En 2 semanas,En 1 mes]',
        'expectativa_salarial'    => 'permit_empty|numeric|greater_than[0]',
        'notas_admin'             => 'permit_empty|max_length[1000]',
        'activo'                  => 'required|in_list[0,1]'
    ];

    protected $validationMessages = [
        'id_usuario' => [
            'required' => 'El ID de usuario es obligatorio',
            'integer'  => 'El ID de usuario debe ser un número entero'
        ],
        'id_puesto' => [
            'required' => 'El ID del puesto es obligatorio',
            'integer'  => 'El ID del puesto debe ser un número entero'
        ],
        'nombres' => [
            'required'   => 'Los nombres son obligatorios',
            'min_length' => 'Los nombres deben tener al menos 2 caracteres',
            'max_length' => 'Los nombres no pueden exceder 100 caracteres'
        ],
        'apellidos' => [
            'required'   => 'Los apellidos son obligatorios',
            'min_length' => 'Los apellidos deben tener al menos 2 caracteres',
            'max_length' => 'Los apellidos no pueden exceder 100 caracteres'
        ],
        'cedula' => [
            'required'   => 'La cédula es obligatoria',
            'min_length' => 'La cédula debe tener 10 dígitos',
            'max_length' => 'La cédula debe tener 10 dígitos'
        ],
        'email' => [
            'required'   => 'El email es obligatorio',
            'valid_email' => 'El email debe tener un formato válido',
            'max_length' => 'El email no puede exceder 100 caracteres'
        ],
        'telefono' => [
            'required'   => 'El teléfono es obligatorio',
            'min_length' => 'El teléfono debe tener al menos 10 dígitos',
            'max_length' => 'El teléfono no puede exceder 15 dígitos'
        ],
        'fecha_nacimiento' => [
            'required'   => 'La fecha de nacimiento es obligatoria',
            'valid_date' => 'La fecha de nacimiento debe ser una fecha válida'
        ],
        'genero' => [
            'required' => 'El género es obligatorio',
            'in_list'  => 'El género seleccionado no es válido'
        ],
        'estado_civil' => [
            'required' => 'El estado civil es obligatorio',
            'in_list'  => 'El estado civil seleccionado no es válido'
        ],
        'direccion' => [
            'required'   => 'La dirección es obligatoria',
            'min_length' => 'La dirección debe tener al menos 10 caracteres',
            'max_length' => 'La dirección no puede exceder 200 caracteres'
        ],
        'ciudad' => [
            'required'   => 'La ciudad es obligatoria',
            'min_length' => 'La ciudad debe tener al menos 2 caracteres',
            'max_length' => 'La ciudad no puede exceder 100 caracteres'
        ],
        'provincia' => [
            'required'   => 'La provincia es obligatoria',
            'min_length' => 'La provincia debe tener al menos 2 caracteres',
            'max_length' => 'La provincia no puede exceder 100 caracteres'
        ],
        'codigo_postal' => [
            'max_length' => 'El código postal no puede exceder 10 caracteres'
        ],
        'nacionalidad' => [
            'required'   => 'La nacionalidad es obligatoria',
            'min_length' => 'La nacionalidad debe tener al menos 2 caracteres',
            'max_length' => 'La nacionalidad no puede exceder 100 caracteres'
        ],
        'estado_postulacion' => [
            'required' => 'El estado de la postulación es obligatorio',
            'in_list'  => 'El estado de la postulación seleccionado no es válido'
        ],
        'fecha_postulacion' => [
            'required'   => 'La fecha de postulación es obligatoria',
            'valid_date' => 'La fecha de postulación debe ser una fecha válida'
        ],
        'cv_path' => [
            'max_length' => 'La ruta del CV no puede exceder 500 caracteres'
        ],
        'carta_motivacion' => [
            'max_length' => 'La carta de motivación no puede exceder 2000 caracteres'
        ],
        'experiencia_laboral' => [
            'max_length' => 'La experiencia laboral no puede exceder 2000 caracteres'
        ],
        'educacion' => [
            'max_length' => 'La educación no puede exceder 2000 caracteres'
        ],
        'habilidades' => [
            'max_length' => 'Las habilidades no pueden exceder 1000 caracteres'
        ],
        'idiomas' => [
            'max_length' => 'Los idiomas no pueden exceder 500 caracteres'
        ],
        'certificaciones' => [
            'max_length' => 'Las certificaciones no pueden exceder 1000 caracteres'
        ],
        'referencias' => [
            'max_length' => 'Las referencias no pueden exceder 1000 caracteres'
        ],
        'disponibilidad_inmediata' => [
            'required' => 'La disponibilidad inmediata es obligatoria',
            'in_list'  => 'La disponibilidad seleccionada no es válida'
        ],
        'expectativa_salarial' => [
            'numeric'      => 'La expectativa salarial debe ser un número',
            'greater_than' => 'La expectativa salarial debe ser mayor a 0'
        ],
        'notas_admin' => [
            'max_length' => 'Las notas administrativas no pueden exceder 1000 caracteres'
        ],
        'activo' => [
            'required' => 'El campo activo es obligatorio',
            'in_list'  => 'El campo activo debe ser 0 o 1'
        ]
    ];

    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    /**
     * Obtener postulantes con información del puesto
     */
    public function getPostulantesConPuesto()
    {
        $db = \Config\Database::connect();
        return $db->table('postulantes p')
                  ->select('p.*, pt.titulo as titulo_puesto, d.nombre as nombre_departamento')
                  ->join('puestos pt', 'pt.id_puesto = p.id_puesto')
                  ->join('departamentos d', 'd.id_departamento = pt.id_departamento')
                  ->orderBy('p.fecha_postulacion', 'DESC')
                  ->get()
                  ->getResultArray();
    }

    /**
     * Obtener postulante completo con información del puesto
     */
    public function getPostulanteCompleto($idPostulante)
    {
        $db = \Config\Database::connect();
        return $db->table('postulantes p')
                  ->select('p.*, pt.titulo as titulo_puesto, pt.descripcion as descripcion_puesto, d.nombre as nombre_departamento')
                  ->join('puestos pt', 'pt.id_puesto = p.id_puesto')
                  ->join('departamentos d', 'd.id_departamento = pt.id_departamento')
                  ->where('p.id_postulante', $idPostulante)
                  ->get()
                  ->getRowArray();
    }

    /**
     * Obtener postulantes por puesto
     */
    public function getPostulantesPorPuesto($idPuesto)
    {
        return $this->where('id_puesto', $idPuesto)
                    ->where('activo', 1)
                    ->orderBy('fecha_postulacion', 'DESC')
                    ->findAll();
    }

    /**
     * Obtener postulantes por usuario
     */
    public function getPostulantesPorUsuario($idUsuario)
    {
        return $this->where('id_usuario', $idUsuario)
                    ->where('activo', 1)
                    ->orderBy('fecha_postulacion', 'DESC')
                    ->findAll();
    }

    /**
     * Verificar si un usuario ya se postuló a un puesto
     */
    public function usuarioYaPostulado($idUsuario, $idPuesto)
    {
        return $this->where('id_usuario', $idUsuario)
                    ->where('id_puesto', $idPuesto)
                    ->where('activo', 1)
                    ->first();
    }

    /**
     * Cambiar estado de postulación
     */
    public function cambiarEstadoPostulacion($idPostulante, $nuevoEstado)
    {
        $data = [
            'estado_postulacion' => $nuevoEstado,
            'activo' => ($nuevoEstado === 'Rechazada') ? 0 : 1
        ];
        
        return $this->update($idPostulante, $data);
    }

    /**
     * Obtener estadísticas de postulaciones
     */
    public function getEstadisticasPostulaciones()
    {
        $builder = $this->builder();
        $builder->select('
            COUNT(*) as total,
            SUM(CASE WHEN estado_postulacion = "Pendiente" THEN 1 ELSE 0 END) as pendientes,
            SUM(CASE WHEN estado_postulacion = "En revisión" THEN 1 ELSE 0 END) as en_revision,
            SUM(CASE WHEN estado_postulacion = "Aprobada" THEN 1 ELSE 0 END) as aprobadas,
            SUM(CASE WHEN estado_postulacion = "Rechazada" THEN 1 ELSE 0 END) as rechazadas,
            SUM(CASE WHEN estado_postulacion = "Contratado" THEN 1 ELSE 0 END) as contratados
        ');
        
        return $builder->get()->getRowArray();
    }

    /**
     * Buscar postulantes por término
     */
    public function buscarPostulantes($termino)
    {
        return $this->like('nombres', $termino)
                    ->orLike('apellidos', $termino)
                    ->orLike('cedula', $termino)
                    ->orLike('email', $termino)
                    ->where('activo', 1)
                    ->orderBy('fecha_postulacion', 'DESC')
                    ->findAll();
    }

    /**
     * Obtener postulantes por estado
     */
    public function getPostulantesPorEstado($estado)
    {
        return $this->where('estado_postulacion', $estado)
                    ->where('activo', 1)
                    ->orderBy('fecha_postulacion', 'DESC')
                    ->findAll();
    }

    /**
     * Obtener postulantes recientes (últimos 30 días)
     {
        $fechaLimite = date('Y-m-d', strtotime('-30 days'));
        
        return $this->where('fecha_postulacion >=', $fechaLimite)
                    ->where('activo', 1)
                    ->orderBy('fecha_postulacion', 'DESC')
                    ->findAll();
    }

    /**
     * Actualizar CV del postulante
     */
    public function actualizarCV($idPostulante, $cvPath)
    {
        return $this->update($idPostulante, ['cv_path' => $cvPath]);
    }

    /**
     * Obtener postulantes con CV
     */
    public function getPostulantesConCV()
    {
        return $this->where('cv_path IS NOT NULL')
                    ->where('cv_path !=', '')
                    ->where('activo', 1)
                    ->findAll();
    }

    /**
     * Obtener postulantes por rango de fechas
     */
    public function getPostulantesPorRangoFechas($fechaInicio, $fechaFin)
    {
        return $this->where('fecha_postulacion >=', $fechaInicio)
                    ->where('fecha_postulacion <=', $fechaFin)
                    ->where('activo', 1)
                    ->orderBy('fecha_postulacion', 'DESC')
                    ->findAll();
    }
}
