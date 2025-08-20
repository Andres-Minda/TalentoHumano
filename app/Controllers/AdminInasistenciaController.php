<?php

namespace App\Controllers;

use App\Models\InasistenciaModel;
use App\Models\EmpleadoModel;
use App\Models\TipoInasistenciaModel;
use App\Models\PoliticaInasistenciaModel;
use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class AdminInasistenciaController extends Controller
{
    protected $inasistenciaModel;
    protected $empleadoModel;
    protected $tipoInasistenciaModel;
    protected $politicaInasistenciaModel;

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);

        $this->inasistenciaModel = new InasistenciaModel();
        $this->empleadoModel = new EmpleadoModel();
        $this->tipoInasistenciaModel = new TipoInasistenciaModel();
        $this->politicaInasistenciaModel = new PoliticaInasistenciaModel();
    }

    /**
     * Dashboard de administración de inasistencias
     */
    public function dashboard()
    {
        // Obtener estadísticas generales
        $estadisticasGenerales = $this->inasistenciaModel->getEstadisticasInasistencias();
        
        // Obtener inasistencias recientes
        $inasistenciasRecientes = $this->inasistenciaModel->getInasistenciasConEmpleado([
            'fecha_inicio' => date('Y-m-01'),
            'fecha_fin' => date('Y-m-t')
        ]);

        // Obtener empleados con más inasistencias
        $empleadosConInasistencias = $this->obtenerEmpleadosConMasInasistencias();

        $data = [
            'title' => 'Administración de Inasistencias',
            'sidebar' => 'sidebar_admin_th',
            'estadisticas_generales' => $estadisticasGenerales,
            'inasistencias_recientes' => array_slice($inasistenciasRecientes, 0, 10),
            'empleados_con_inasistencias' => $empleadosConInasistencias
        ];

        return view('Roles/AdminTH/inasistencias/dashboard', $data);
    }

    /**
     * Listar todas las inasistencias
     */
    public function listarInasistencias()
    {
        // Obtener filtros
        $filtros = [
            'id_empleado' => $this->request->getGet('id_empleado'),
            'tipo_inasistencia' => $this->request->getGet('tipo_inasistencia'),
            'fecha_inicio' => $this->request->getGet('fecha_inicio'),
            'fecha_fin' => $this->request->getGet('fecha_fin'),
            'departamento' => $this->request->getGet('departamento')
        ];

        // Obtener inasistencias
        $inasistencias = $this->inasistenciaModel->getInasistenciasConEmpleado($filtros);

        // Obtener empleados para filtro
        $empleados = $this->empleadoModel->getEmpleadosActivos();
        $departamentos = $this->empleadoModel->getDepartamentos();
        $tipos = $this->tipoInasistenciaModel->getTiposActivos();

        $data = [
            'title' => 'Lista de Inasistencias',
            'sidebar' => 'sidebar_admin_th',
            'inasistencias' => $inasistencias,
            'empleados' => $empleados,
            'departamentos' => $departamentos,
            'tipos' => $tipos,
            'filtros' => $filtros
        ];

        return view('Roles/AdminTH/inasistencias/listar', $data);
    }

    /**
     * Registrar nueva inasistencia
     */
    public function registrarInasistencia()
    {
        if ($this->request->getMethod() === 'post') {
            // Validar datos
            $rules = [
                'id_empleado' => 'required|integer',
                'fecha_inasistencia' => 'required|valid_date',
                'tipo_inasistencia' => 'required',
                'motivo_inasistencia' => 'required|min_length[5]'
            ];

            if (!$this->validate($rules)) {
                return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
            }

            // Preparar datos
            $data = [
                'id_empleado' => $this->request->getPost('id_empleado'),
                'fecha_inasistencia' => $this->request->getPost('fecha_inasistencia'),
                'tipo_inasistencia' => $this->request->getPost('tipo_inasistencia'),
                'motivo_inasistencia' => $this->request->getPost('motivo_inasistencia'),
                'observaciones' => $this->request->getPost('observaciones'),
                'registrado_por' => session()->get('id_usuario')
            ];

            // Insertar inasistencia
            if ($this->inasistenciaModel->insert($data)) {
                return redirect()->to('/admin-th/inasistencias')->with('success', 'Inasistencia registrada exitosamente');
            } else {
                return redirect()->back()->withInput()->with('error', 'Error al registrar la inasistencia');
            }
        }

        // Obtener datos para el formulario
        $empleados = $this->empleadoModel->getEmpleadosActivos();
        $tipos = $this->tipoInasistenciaModel->getTiposActivos();

        $data = [
            'title' => 'Registrar Inasistencia',
            'sidebar' => 'sidebar_admin_th',
            'empleados' => $empleados,
            'tipos' => $tipos
        ];

        return view('Roles/AdminTH/inasistencias/registrar', $data);
    }

    /**
     * Editar inasistencia
     */
    public function editarInasistencia($idInasistencia)
    {
        $inasistencia = $this->inasistenciaModel->find($idInasistencia);

        if (!$inasistencia) {
            return redirect()->to('/admin-th/inasistencias')->with('error', 'Inasistencia no encontrada');
        }

        if ($this->request->getMethod() === 'post') {
            // Validar datos
            $rules = [
                'fecha_inasistencia' => 'required|valid_date',
                'tipo_inasistencia' => 'required',
                'motivo_inasistencia' => 'required|min_length[5]'
            ];

            if (!$this->validate($rules)) {
                return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
            }

            // Preparar datos
            $data = [
                'fecha_inasistencia' => $this->request->getPost('fecha_inasistencia'),
                'tipo_inasistencia' => $this->request->getPost('tipo_inasistencia'),
                'motivo_inasistencia' => $this->request->getPost('motivo_inasistencia'),
                'observaciones' => $this->request->getPost('observaciones')
            ];

            // Actualizar inasistencia
            if ($this->inasistenciaModel->update($idInasistencia, $data)) {
                return redirect()->to('/admin-th/inasistencias')->with('success', 'Inasistencia actualizada exitosamente');
            } else {
                return redirect()->back()->withInput()->with('error', 'Error al actualizar la inasistencia');
            }
        }

        // Obtener datos para el formulario
        $empleado = $this->empleadoModel->find($inasistencia['id_empleado']);
        $tipos = $this->tipoInasistenciaModel->getTiposActivos();

        $data = [
            'title' => 'Editar Inasistencia',
            'sidebar' => 'sidebar_admin_th',
            'inasistencia' => $inasistencia,
            'empleado' => $empleado,
            'tipos' => $tipos
        ];

        return view('Roles/AdminTH/inasistencias/editar', $data);
    }

    /**
     * Revisar justificación
     */
    public function revisarJustificacion($idInasistencia)
    {
        $inasistencia = $this->inasistenciaModel->find($idInasistencia);

        if (!$inasistencia) {
            return redirect()->to('/admin-th/inasistencias')->with('error', 'Inasistencia no encontrada');
        }

        if ($this->request->getMethod() === 'post') {
            $decision = $this->request->getPost('decision');
            $observaciones = $this->request->getPost('observaciones_revisor');

            $nuevoTipo = ($decision === 'APROBAR') ? 'JUSTIFICADA' : 'NO_JUSTIFICADA';

            $data = [
                'tipo_inasistencia' => $nuevoTipo,
                'observaciones' => $observaciones
            ];

            if ($this->inasistenciaModel->update($idInasistencia, $data)) {
                $mensaje = ($decision === 'APROBAR') ? 'Justificación aprobada' : 'Justificación rechazada';
                return redirect()->to('/admin-th/inasistencias')->with('success', $mensaje);
            } else {
                return redirect()->back()->with('error', 'Error al procesar la justificación');
            }
        }

        $empleado = $this->empleadoModel->find($inasistencia['id_empleado']);

        $data = [
            'title' => 'Revisar Justificación',
            'sidebar' => 'sidebar_admin_th',
            'inasistencia' => $inasistencia,
            'empleado' => $empleado
        ];

        return view('Roles/AdminTH/inasistencias/revisar_justificacion', $data);
    }

    /**
     * Generar reporte de inasistencias
     */
    public function generarReporte()
    {
        // Obtener parámetros del reporte
        $periodo = $this->request->getGet('periodo') ?: 'MENSUAL';
        $idEmpleado = $this->request->getGet('id_empleado');
        $departamento = $this->request->getGet('departamento');

        // Calcular fechas según período
        switch ($periodo) {
            case 'MENSUAL':
                $fechaInicio = date('Y-m-01');
                $fechaFin = date('Y-m-t');
                break;
            case 'TRIMESTRAL':
                $mes = date('n');
                $trimestre = ceil($mes / 3);
                $fechaInicio = date('Y-m-01', strtotime("-$mes months"));
                $fechaFin = date('Y-m-t', strtotime("+" . (3 - $mes % 3) . " months"));
                break;
            case 'ANUAL':
                $fechaInicio = date('Y-01-01');
                $fechaFin = date('Y-12-31');
                break;
            default:
                $fechaInicio = date('Y-m-01');
                $fechaFin = date('Y-m-t');
        }

        // Obtener inasistencias
        $filtros = [
            'fecha_inicio' => $fechaInicio,
            'fecha_fin' => $fechaFin
        ];

        if ($idEmpleado) {
            $filtros['id_empleado'] = $idEmpleado;
        }

        if ($departamento) {
            $filtros['departamento'] = $departamento;
        }

        $inasistencias = $this->inasistenciaModel->getInasistenciasConEmpleado($filtros);

        // Obtener estadísticas
        $estadisticas = $this->inasistenciaModel->getEstadisticasInasistencias(
            $idEmpleado,
            $fechaInicio,
            $fechaFin
        );

        // Obtener datos para filtros
        $empleados = $this->empleadoModel->getEmpleadosActivos();
        $departamentos = $this->empleadoModel->getDepartamentos();

        $data = [
            'title' => 'Reporte de Inasistencias',
            'sidebar' => 'sidebar_admin_th',
            'periodo' => $periodo,
            'fecha_inicio' => $fechaInicio,
            'fecha_fin' => $fechaFin,
            'inasistencias' => $inasistencias,
            'estadisticas' => $estadisticas,
            'empleados' => $empleados,
            'departamentos' => $departamentos,
            'filtros' => [
                'id_empleado' => $idEmpleado,
                'departamento' => $departamento
            ]
        ];

        return view('Roles/AdminTH/inasistencias/reporte', $data);
    }

    /**
     * Gestionar políticas de inasistencias
     */
    public function gestionarPoliticas()
    {
        if ($this->request->getMethod() === 'post') {
            $accion = $this->request->getPost('accion');

            switch ($accion) {
                case 'crear':
                    return $this->crearPolitica();
                case 'editar':
                    return $this->editarPolitica();
                case 'activar':
                    return $this->activarPolitica();
                case 'eliminar':
                    return $this->eliminarPolitica();
            }
        }

        $politicas = $this->politicaInasistenciaModel->getTodasLasPoliticas();
        $politicaActiva = $this->politicaInasistenciaModel->getPoliticaActiva();

        $data = [
            'title' => 'Gestionar Políticas de Inasistencias',
            'sidebar' => 'sidebar_admin_th',
            'politicas' => $politicas,
            'politica_activa' => $politicaActiva
        ];

        return view('Roles/AdminTH/inasistencias/politicas', $data);
    }

    /**
     * Crear nueva política
     */
    private function crearPolitica()
    {
        $data = [
            'nombre_politica' => $this->request->getPost('nombre_politica'),
            'descripcion' => $this->request->getPost('descripcion'),
            'max_inasistencias_mes' => $this->request->getPost('max_inasistencias_mes'),
            'max_inasistencias_trimestre' => $this->request->getPost('max_inasistencias_trimestre'),
            'max_inasistencias_anio' => $this->request->getPost('max_inasistencias_anio'),
            'requiere_accion_disciplinaria' => $this->request->getPost('requiere_accion_disciplinaria') ? 1 : 0,
            'activo' => $this->request->getPost('activo') ? 1 : 0
        ];

        if ($this->politicaInasistenciaModel->crearPolitica($data)) {
            return redirect()->to('/admin-th/inasistencias/politicas')->with('success', 'Política creada exitosamente');
        } else {
            return redirect()->back()->withInput()->with('error', 'Error al crear la política');
        }
    }

    /**
     * Obtener empleados con más inasistencias
     */
    private function obtenerEmpleadosConMasInasistencias()
    {
        $builder = $this->db->table('inasistencias i');
        $builder->select('
            e.id_empleado,
            e.nombres,
            e.apellidos,
            e.tipo_empleado,
            e.departamento,
            COUNT(i.id_inasistencia) as total_inasistencias
        ');
        $builder->join('empleados e', 'e.id_empleado = i.id_empleado');
        $builder->where('i.estado', 'ACTIVA');
        $builder->where('i.fecha_inasistencia >=', date('Y-m-01'));
        $builder->groupBy('e.id_empleado, e.nombres, e.apellidos, e.tipo_empleado, e.departamento');
        $builder->orderBy('total_inasistencias', 'DESC');
        $builder->limit(10);

        return $builder->get()->getResultArray();
    }

    /**
     * API: Obtener estadísticas generales
     */
    public function getEstadisticasGenerales()
    {
        $estadisticas = $this->inasistenciaModel->getEstadisticasInasistencias();
        
        return $this->response->setJSON([
            'success' => true,
            'data' => $estadisticas
        ]);
    }

    /**
     * API: Obtener inasistencias por período
     */
    public function getInasistenciasPeriodo()
    {
        $fechaInicio = $this->request->getGet('fecha_inicio');
        $fechaFin = $this->request->getGet('fecha_fin');
        $idEmpleado = $this->request->getGet('id_empleado');

        $inasistencias = $this->inasistenciaModel->getInasistenciasPorPeriodo(
            $fechaInicio,
            $fechaFin,
            $idEmpleado
        );

        return $this->response->setJSON([
            'success' => true,
            'data' => $inasistencias
        ]);
    }
}
