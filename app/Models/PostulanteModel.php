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

    protected $allowedFields    = [
        'cedula', 'nombres', 'apellidos', 'email', 'telefono', 'direccion',
        'fecha_nacimiento', 'estado_civil', 'genero', 'nivel_educativo',
        'titulo_academico', 'universidad', 'experiencia_laboral', 'disponibilidad',
        'salario_esperado', 'archivo_cv', 'archivo_cedula', 'archivo_titulo',
        'archivo_referencias', 'estado', 'fecha_registro', 'observaciones'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $beforeInsert = ['setFechaRegistro'];

    protected $validationRules = [
        'cedula'              => 'required|min_length[10]|max_length[10]|is_unique[postulantes.cedula,id_postulante,{id_postulante}]',
        'nombres'             => 'required|min_length[2]|max_length[100]',
        'apellidos'           => 'required|min_length[2]|max_length[100]',
        'email'               => 'required|valid_email|is_unique[postulantes.email,id_postulante,{id_postulante}]',
        'telefono'            => 'required|min_length[7]|max_length[15]',
        'direccion'           => 'required|min_length[10]|max_length[300]',
        'fecha_nacimiento'    => 'required|valid_date',
        'estado_civil'        => 'required|in_list[Soltero,Casado,Divorciado,Viudo,Unión Libre]',
        'genero'              => 'required|in_list[Masculino,Femenino,Otro]',
        'nivel_educativo'     => 'required|in_list[Primaria,Secundaria,Técnico,Universitario,Postgrado]',
        'titulo_academico'    => 'permit_empty|max_length[200]',
        'universidad'         => 'permit_empty|max_length[200]',
        'experiencia_laboral' => 'required|min_length[10]|max_length[1000]',
        'disponibilidad'      => 'required|in_list[Inmediata,15 días,30 días,60 días,90 días]',
        'salario_esperado'    => 'required|numeric|greater_than[0]',
        'estado'              => 'required|in_list[Activo,Inactivo,Seleccionado,Rechazado]'
    ];

    protected $validationMessages = [
        'cedula' => [
            'required' => 'La cédula es obligatoria',
            'min_length' => 'La cédula debe tener 10 dígitos',
            'max_length' => 'La cédula debe tener 10 dígitos',
            'is_unique' => 'La cédula ya está registrada'
        ],
        'nombres' => [
            'required' => 'Los nombres son obligatorios',
            'min_length' => 'Los nombres deben tener al menos 2 caracteres',
            'max_length' => 'Los nombres no pueden exceder 100 caracteres'
        ],
        'apellidos' => [
            'required' => 'Los apellidos son obligatorios',
            'min_length' => 'Los apellidos deben tener al menos 2 caracteres',
            'max_length' => 'Los apellidos no pueden exceder 100 caracteres'
        ],
        'email' => [
            'required' => 'El email es obligatorio',
            'valid_email' => 'El email debe tener un formato válido',
            'is_unique' => 'El email ya está registrado'
        ],
        'telefono' => [
            'required' => 'El teléfono es obligatorio',
            'min_length' => 'El teléfono debe tener al menos 7 dígitos',
            'max_length' => 'El teléfono no puede exceder 15 dígitos'
        ],
        'direccion' => [
            'required' => 'La dirección es obligatoria',
            'min_length' => 'La dirección debe tener al menos 10 caracteres',
            'max_length' => 'La dirección no puede exceder 300 caracteres'
        ],
        'fecha_nacimiento' => [
            'required' => 'La fecha de nacimiento es obligatoria',
            'valid_date' => 'La fecha de nacimiento debe ser una fecha válida'
        ],
        'estado_civil' => [
            'required' => 'El estado civil es obligatorio',
            'in_list' => 'El estado civil debe ser: Soltero, Casado, Divorciado, Viudo o Unión Libre'
        ],
        'genero' => [
            'required' => 'El género es obligatorio',
            'in_list' => 'El género debe ser: Masculino, Femenino u Otro'
        ],
        'nivel_educativo' => [
            'required' => 'El nivel educativo es obligatorio',
            'in_list' => 'El nivel educativo debe ser: Primaria, Secundaria, Técnico, Universitario o Postgrado'
        ],
        'experiencia_laboral' => [
            'required' => 'La experiencia laboral es obligatoria',
            'min_length' => 'La experiencia laboral debe tener al menos 10 caracteres',
            'max_length' => 'La experiencia laboral no puede exceder 1000 caracteres'
        ],
        'disponibilidad' => [
            'required' => 'La disponibilidad es obligatoria',
            'in_list' => 'La disponibilidad debe ser: Inmediata, 15 días, 30 días, 60 días o 90 días'
        ],
        'salario_esperado' => [
            'required' => 'El salario esperado es obligatorio',
            'numeric' => 'El salario esperado debe ser un número',
            'greater_than' => 'El salario esperado debe ser mayor a 0'
        ],
        'estado' => [
            'required' => 'El estado es obligatorio',
            'in_list' => 'El estado debe ser: Activo, Inactivo, Seleccionado o Rechazado'
        ]
    ];

    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    /**
     * Establece la fecha de registro antes de insertar
     */
    protected function setFechaRegistro($data)
    {
        if (!isset($data['data']['fecha_registro'])) {
            $data['data']['fecha_registro'] = date('Y-m-d H:i:s');
        }
        return $data;
    }

    /**
     * Obtiene postulantes activos
     */
    public function getPostulantesActivos()
    {
        return $this->where('estado', 'Activo')
                    ->orderBy('fecha_registro', 'DESC')
                    ->findAll();
    }

    /**
     * Obtiene postulantes por estado
     */
    public function getPostulantesPorEstado($estado)
    {
        return $this->where('estado', $estado)
                    ->orderBy('fecha_registro', 'DESC')
                    ->findAll();
    }

    /**
     * Obtiene postulantes por nivel educativo
     */
    public function getPostulantesPorNivelEducativo($nivel)
    {
        return $this->where('nivel_educativo', $nivel)
                    ->where('estado', 'Activo')
                    ->orderBy('fecha_registro', 'DESC')
                    ->findAll();
    }

    /**
     * Obtiene postulantes por disponibilidad
     */
    public function getPostulantesPorDisponibilidad($disponibilidad)
    {
        return $this->where('disponibilidad', $disponibilidad)
                    ->where('estado', 'Activo')
                    ->orderBy('fecha_registro', 'DESC')
                    ->findAll();
    }

    /**
     * Busca postulantes por criterios
     */
    public function buscarPostulantes($criterios = [])
    {
        $builder = $this->db->table('postulantes p');

        if (!empty($criterios['nivel_educativo'])) {
            $builder->where('p.nivel_educativo', $criterios['nivel_educativo']);
        }

        if (!empty($criterios['estado'])) {
            $builder->where('p.estado', $criterios['estado']);
        }

        if (!empty($criterios['disponibilidad'])) {
            $builder->where('p.disponibilidad', $criterios['disponibilidad']);
        }

        if (!empty($criterios['salario_min'])) {
            $builder->where('p.salario_esperado >=', $criterios['salario_min']);
        }

        if (!empty($criterios['salario_max'])) {
            $builder->where('p.salario_esperado <=', $criterios['salario_max']);
        }

        if (!empty($criterios['busqueda'])) {
            $busqueda = $criterios['busqueda'];
            $builder->groupStart()
                    ->like('p.nombres', $busqueda)
                    ->orLike('p.apellidos', $busqueda)
                    ->orLike('p.cedula', $busqueda)
                    ->orLike('p.email', $busqueda)
                    ->orLike('p.titulo_academico', $busqueda)
                    ->orLike('p.universidad', $busqueda)
                    ->groupEnd();
        }

        return $builder->orderBy('p.fecha_registro', 'DESC')
                      ->get()
                      ->getResultArray();
    }

    /**
     * Obtiene estadísticas de postulantes
     */
    public function getEstadisticasPostulantes()
    {
        $total = $this->countAll();
        $porEstado = $this->db->table('postulantes')
                              ->select('estado, COUNT(*) as total')
                              ->groupBy('estado')
                              ->get()
                              ->getResultArray();
        
        $porNivelEducativo = $this->db->table('postulantes')
                                      ->select('nivel_educativo, COUNT(*) as total')
                                      ->groupBy('nivel_educativo')
                                      ->get()
                                      ->getResultArray();

        $porDisponibilidad = $this->db->table('postulantes')
                                      ->select('disponibilidad, COUNT(*) as total')
                                      ->groupBy('disponibilidad')
                                      ->get()
                                      ->getResultArray();

        $promedioSalario = $this->db->table('postulantes')
                                    ->select('AVG(salario_esperado) as promedio_salario')
                                    ->where('estado', 'Activo')
                                    ->get()
                                    ->getRow();

        return [
            'total' => $total,
            'por_estado' => $porEstado,
            'por_nivel_educativo' => $porNivelEducativo,
            'por_disponibilidad' => $porDisponibilidad,
            'promedio_salario' => $promedioSalario ? round($promedioSalario->promedio_salario, 2) : 0
        ];
    }

    /**
     * Obtiene postulantes recientes
     */
    public function getPostulantesRecientes($limit = 10)
    {
        return $this->orderBy('created_at', 'DESC')
                    ->limit($limit)
                    ->findAll();
    }

    /**
     * Verifica si una cédula ya existe
     */
    public function cedulaExiste($cedula, $excludeId = null)
    {
        $builder = $this->where('cedula', $cedula);
        
        if ($excludeId) {
            $builder->where('id_postulante !=', $excludeId);
        }
        
        return $builder->countAllResults() > 0;
    }

    /**
     * Verifica si un email ya existe
     */
    public function emailExiste($email, $excludeId = null)
    {
        $builder = $this->where('email', $email);
        
        if ($excludeId) {
            $builder->where('id_postulante !=', $excludeId);
        }
        
        return $builder->countAllResults() > 0;
    }

    /**
     * Obtiene postulantes por rango de salario
     */
    public function getPostulantesPorRangoSalario($salarioMin, $salarioMax)
    {
        return $this->where('salario_esperado >=', $salarioMin)
                    ->where('salario_esperado <=', $salarioMax)
                    ->where('estado', 'Activo')
                    ->orderBy('salario_esperado', 'ASC')
                    ->findAll();
    }

    /**
     * Obtiene postulantes con experiencia laboral
     */
    public function getPostulantesConExperiencia()
    {
        return $this->where('estado', 'Activo')
                    ->where('experiencia_laboral IS NOT NULL')
                    ->where('experiencia_laboral !=', '')
                    ->orderBy('fecha_registro', 'DESC')
                    ->findAll();
    }

    /**
     * Actualiza el estado de un postulante
     */
    public function actualizarEstado($postulanteId, $nuevoEstado, $observaciones = '')
    {
        $data = [
            'estado' => $nuevoEstado,
            'observaciones' => $observaciones
        ];

        return $this->update($postulanteId, $data);
    }

    /**
     * Obtiene postulantes por vacante específica
     */
    public function getPostulantesPorVacante($vacanteId)
    {
        return $this->db->table('postulaciones p')
                        ->select('p.*, po.*')
                        ->join('postulantes po', 'po.id_postulante = p.id_postulante', 'left')
                        ->where('p.id_vacante', $vacanteId)
                        ->orderBy('p.fecha_aplicacion', 'DESC')
                        ->get()
                        ->getResultArray();
    }

    /**
     * Obtiene el historial de aplicaciones de un postulante
     */
    public function getHistorialAplicaciones($postulanteId)
    {
        return $this->db->table('postulaciones p')
                        ->select('p.*, v.titulo as titulo_vacante, v.departamento, v.estado as estado_vacante')
                        ->join('vacantes v', 'v.id_vacante = p.id_vacante', 'left')
                        ->where('p.id_postulante', $postulanteId)
                        ->orderBy('p.fecha_aplicacion', 'DESC')
                        ->get()
                        ->getResultArray();
    }
}
