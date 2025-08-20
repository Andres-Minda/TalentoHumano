<?php

namespace App\Models;

use CodeIgniter\Model;

class PoliticaInasistenciaModel extends Model
{
    protected $table            = 'politicas_inasistencia';
    protected $primaryKey       = 'id_politica';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields = [
        'nombre_politica',
        'descripcion',
        'max_inasistencias_mes',
        'max_inasistencias_trimestre',
        'max_inasistencias_anio',
        'requiere_accion_disciplinaria',
        'activo'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validaciones
    protected $validationRules = [
        'nombre_politica'              => 'required|min_length[5]|max_length[200]',
        'descripcion'                  => 'permit_empty|max_length[1000]',
        'max_inasistencias_mes'       => 'required|integer|greater_than[0]|less_than_equal_to[31]',
        'max_inasistencias_trimestre' => 'required|integer|greater_than[0]|less_than_equal_to[93]',
        'max_inasistencias_anio'      => 'required|integer|greater_than[0]|less_than_equal_to[365]',
        'requiere_accion_disciplinaria'=> 'permit_empty|in_list[0,1]',
        'activo'                       => 'permit_empty|in_list[0,1]'
    ];

    protected $validationMessages = [
        'nombre_politica' => [
            'required'   => 'El nombre de la política es obligatorio',
            'min_length' => 'El nombre debe tener al menos 5 caracteres',
            'max_length' => 'El nombre no puede exceder 200 caracteres'
        ],
        'max_inasistencias_mes' => [
            'required'              => 'El límite mensual es obligatorio',
            'integer'              => 'El límite debe ser un número entero',
            'greater_than'         => 'El límite debe ser mayor a 0',
            'less_than_equal_to'   => 'El límite no puede exceder 31'
        ],
        'max_inasistencias_trimestre' => [
            'required'              => 'El límite trimestral es obligatorio',
            'integer'              => 'El límite debe ser un número entero',
            'greater_than'         => 'El límite debe ser mayor a 0',
            'less_than_equal_to'   => 'El límite no puede exceder 93'
        ],
        'max_inasistencias_anio' => [
            'required'              => 'El límite anual es obligatorio',
            'integer'              => 'El límite debe ser un número entero',
            'greater_than'         => 'El límite debe ser mayor a 0',
            'less_than_equal_to'   => 'El límite no puede exceder 365'
        ],
        'requiere_accion_disciplinaria' => [
            'in_list' => 'El valor debe ser 0 o 1'
        ],
        'activo' => [
            'in_list' => 'El valor debe ser 0 o 1'
        ]
    ];

    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    /**
     * Obtener política activa
     */
    public function getPoliticaActiva()
    {
        return $this->where('activo', 1)->first();
    }

    /**
     * Obtener todas las políticas
     */
    public function getTodasLasPoliticas()
    {
        return $this->orderBy('created_at', 'DESC')->findAll();
    }

    /**
     * Obtener política por ID
     */
    public function getPoliticaPorId($idPolitica)
    {
        return $this->find($idPolitica);
    }

    /**
     * Crear nueva política
     */
    public function crearPolitica($data)
    {
        // Desactivar políticas existentes si la nueva es activa
        if (isset($data['activo']) && $data['activo'] == 1) {
            $this->where('activo', 1)->set(['activo' => 0])->update();
        }
        
        return $this->insert($data);
    }

    /**
     * Actualizar política
     */
    public function actualizarPolitica($idPolitica, $data)
    {
        // Si se está activando esta política, desactivar las demás
        if (isset($data['activo']) && $data['activo'] == 1) {
            $this->where('id_politica !=', $idPolitica)
                 ->where('activo', 1)
                 ->set(['activo' => 0])
                 ->update();
        }
        
        return $this->update($idPolitica, $data);
    }

    /**
     * Activar política
     */
    public function activarPolitica($idPolitica)
    {
        // Desactivar todas las políticas
        $this->set(['activo' => 0])->update();
        
        // Activar la política seleccionada
        return $this->update($idPolitica, ['activo' => 1]);
    }

    /**
     * Desactivar política
     */
    public function desactivarPolitica($idPolitica)
    {
        return $this->update($idPolitica, ['activo' => 0]);
    }

    /**
     * Verificar límites de inasistencias para un empleado
     */
    public function verificarLimites($idEmpleado, $inasistenciaModel)
    {
        $politica = $this->getPoliticaActiva();
        
        if (!$politica) {
            return [
                'error' => 'No hay política activa configurada',
                'limites' => null,
                'estado' => 'SIN_POLITICA'
            ];
        }

        // Obtener estadísticas del empleado
        $estadisticasMes = $inasistenciaModel->verificarLimiteInasistencias($idEmpleado, 'MENSUAL');
        $estadisticasTrimestre = $inasistenciaModel->verificarLimiteInasistencias($idEmpleado, 'TRIMESTRAL');
        $estadisticasAnio = $inasistenciaModel->verificarLimiteInasistencias($idEmpleado, 'ANUAL');

        $resultado = [
            'politica' => $politica,
            'estadisticas' => [
                'mes' => $estadisticasMes,
                'trimestre' => $estadisticasTrimestre,
                'anio' => $estadisticasAnio
            ],
            'limites' => [
                'mes' => $politica['max_inasistencias_mes'],
                'trimestre' => $politica['max_inasistencias_trimestre'],
                'anio' => $politica['max_inasistencias_anio']
            ],
            'estado' => 'DENTRO_LIMITE'
        ];

        // Verificar si excede límites
        if ($estadisticasMes['total'] >= $politica['max_inasistencias_mes']) {
            $resultado['estado'] = 'EXCEDE_LIMITE_MENSUAL';
            $resultado['alerta'] = 'Ha excedido el límite mensual de inasistencias';
        } elseif ($estadisticasTrimestre['total'] >= $politica['max_inasistencias_trimestre']) {
            $resultado['estado'] = 'EXCEDE_LIMITE_TRIMESTRAL';
            $resultado['alerta'] = 'Ha excedido el límite trimestral de inasistencias';
        } elseif ($estadisticasAnio['total'] >= $politica['max_inasistencias_anio']) {
            $resultado['estado'] = 'EXCEDE_LIMITE_ANUAL';
            $resultado['alerta'] = 'Ha excedido el límite anual de inasistencias';
        }

        // Verificar si requiere acción disciplinaria
        if ($politica['requiere_accion_disciplinaria'] && $resultado['estado'] !== 'DENTRO_LIMITE') {
            $resultado['requiere_accion_disciplinaria'] = true;
            $resultado['accion'] = 'Se requiere acción disciplinaria según la política';
        }

        return $resultado;
    }

    /**
     * Obtener políticas para formulario de selección
     */
    public function getPoliticasParaFormulario()
    {
        $politicas = $this->findAll();
        $opciones = [];
        
        foreach ($politicas as $politica) {
            $opciones[$politica['id_politica']] = $politica['nombre_politica'];
        }
        
        return $opciones;
    }

    /**
     * Obtener estadísticas de uso de políticas
     */
    public function getEstadisticasUso()
    {
        $builder = $this->db->table('politicas_inasistencia p');
        $builder->select('
            p.id_politica,
            p.nombre_politica,
            p.activo,
            COUNT(r.id_reporte) as total_reportes
        ');
        $builder->join('reportes_inasistencia r', 'r.id_empleado > 0', 'left');
        $builder->groupBy('p.id_politica, p.nombre_politica, p.activo');
        $builder->orderBy('p.created_at', 'DESC');
        
        return $builder->get()->getResultArray();
    }
}
