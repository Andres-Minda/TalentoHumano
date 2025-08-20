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

class InasistenciaController extends Controller
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
     * Dashboard de inasistencias para empleados
     */
    public function dashboard()
    {
        $idUsuario = session()->get('id_usuario');
        $empleado = $this->empleadoModel->getEmpleadoByUsuarioId($idUsuario);

        if (!$empleado) {
            return redirect()->to('/empleado/dashboard')->with('error', 'No se encontró información del empleado');
        }

        // Obtener estadísticas del empleado
        $estadisticas = $this->inasistenciaModel->getEstadisticasInasistencias($empleado['id_empleado']);
        
        // Obtener inasistencias recientes
        $inasistenciasRecientes = $this->inasistenciaModel->getInasistenciasEmpleado(
            $empleado['id_empleado'], 
            null, 
            null
        );

        // Verificar límites según política
        $verificacionLimites = $this->politicaInasistenciaModel->verificarLimites(
            $empleado['id_empleado'], 
            $this->inasistenciaModel
        );

        $data = [
            'title' => 'Dashboard de Inasistencias',
            'sidebar' => 'sidebar_empleado',
            'empleado' => $empleado,
            'estadisticas' => $estadisticas,
            'inasistencias_recientes' => array_slice($inasistenciasRecientes, 0, 5),
            'verificacion_limites' => $verificacionLimites
        ];

        return view('Roles/Empleado/inasistencias/dashboard', $data);
    }

    /**
     * Listar inasistencias del empleado
     */
    public function misInasistencias()
    {
        $idUsuario = session()->get('id_usuario');
        $empleado = $this->empleadoModel->getEmpleadoByUsuarioId($idUsuario);

        if (!$empleado) {
            return redirect()->to('/empleado/dashboard')->with('error', 'No se encontró información del empleado');
        }

        // Obtener filtros
        $fechaInicio = $this->request->getGet('fecha_inicio');
        $fechaFin = $this->request->getGet('fecha_fin');
        $tipo = $this->request->getGet('tipo');

        // Obtener inasistencias
        $inasistencias = $this->inasistenciaModel->getInasistenciasEmpleado(
            $empleado['id_empleado'],
            $fechaInicio,
            $fechaFin
        );

        // Filtrar por tipo si se especifica
        if ($tipo) {
            $inasistencias = array_filter($inasistencias, function($inasistencia) use ($tipo) {
                return $inasistencia['tipo_inasistencia'] === $tipo;
            });
        }

        $data = [
            'title' => 'Mis Inasistencias',
            'sidebar' => 'sidebar_empleado',
            'empleado' => $empleado,
            'inasistencias' => $inasistencias,
            'filtros' => [
                'fecha_inicio' => $fechaInicio,
                'fecha_fin' => $fechaFin,
                'tipo' => $tipo
            ]
        ];

        return view('Roles/Empleado/inasistencias/mis_inasistencias', $data);
    }

    /**
     * Ver detalle de una inasistencia
     */
    public function verInasistencia($idInasistencia)
    {
        $idUsuario = session()->get('id_usuario');
        $empleado = $this->empleadoModel->getEmpleadoByUsuarioId($idUsuario);

        if (!$empleado) {
            return redirect()->to('/empleado/dashboard')->with('error', 'No se encontró información del empleado');
        }

        // Obtener inasistencia
        $inasistencia = $this->inasistenciaModel->find($idInasistencia);

        if (!$inasistencia || $inasistencia['id_empleado'] != $empleado['id_empleado']) {
            return redirect()->to('/empleado/inasistencias')->with('error', 'Inasistencia no encontrada');
        }

        $data = [
            'title' => 'Detalle de Inasistencia',
            'sidebar' => 'sidebar_empleado',
            'empleado' => $empleado,
            'inasistencia' => $inasistencia
        ];

        return view('Roles/Empleado/inasistencias/ver_inasistencia', $data);
    }

    /**
     * Subir justificación
     */
    public function subirJustificacion()
    {
        $idUsuario = session()->get('id_usuario');
        $empleado = $this->empleadoModel->getEmpleadoByUsuarioId($idUsuario);

        if (!$empleado) {
            return redirect()->to('/empleado/dashboard')->with('error', 'No se encontró información del empleado');
        }

        if ($this->request->getMethod() === 'post') {
            $idInasistencia = $this->request->getPost('id_inasistencia');
            $justificacion = $this->request->getPost('justificacion');

            // Verificar que la inasistencia pertenece al empleado
            $inasistencia = $this->inasistenciaModel->find($idInasistencia);
            if (!$inasistencia || $inasistencia['id_empleado'] != $empleado['id_empleado']) {
                return redirect()->back()->with('error', 'Inasistencia no válida');
            }

            // Procesar archivo si se subió
            $archivo = $this->request->getFile('documento');
            $nombreArchivo = null;

            if ($archivo && $archivo->isValid() && !$archivo->hasMoved()) {
                $nombreArchivo = $archivo->getRandomName();
                $archivo->move(WRITEPATH . 'uploads/justificaciones', $nombreArchivo);
            }

            // Actualizar inasistencia
            $data = [
                'justificacion' => $justificacion,
                'tipo_inasistencia' => 'PENDIENTE_JUSTIFICACION'
            ];

            if ($nombreArchivo) {
                $data['documento_justificacion'] = $nombreArchivo;
            }

            if ($this->inasistenciaModel->update($idInasistencia, $data)) {
                return redirect()->to('/empleado/inasistencias')->with('success', 'Justificación enviada exitosamente');
            } else {
                return redirect()->back()->with('error', 'Error al enviar la justificación');
            }
        }

        $data = [
            'title' => 'Subir Justificación',
            'sidebar' => 'sidebar_empleado',
            'empleado' => $empleado
        ];

        return view('Roles/Empleado/inasistencias/subir_justificacion', $data);
    }

    /**
     * Reporte de inasistencias del empleado
     */
    public function reporteInasistencias()
    {
        $idUsuario = session()->get('id_usuario');
        $empleado = $this->empleadoModel->getEmpleadoByUsuarioId($idUsuario);

        if (!$empleado) {
            return redirect()->to('/empleado/dashboard')->with('error', 'No se encontró información del empleado');
        }

        // Obtener período del reporte
        $periodo = $this->request->getGet('periodo') ?: 'MENSUAL';
        
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

        // Obtener estadísticas
        $estadisticas = $this->inasistenciaModel->getEstadisticasInasistencias(
            $empleado['id_empleado'],
            $fechaInicio,
            $fechaFin
        );

        // Obtener inasistencias del período
        $inasistencias = $this->inasistenciaModel->getInasistenciasEmpleado(
            $empleado['id_empleado'],
            $fechaInicio,
            $fechaFin
        );

        // Verificar límites
        $verificacionLimites = $this->politicaInasistenciaModel->verificarLimites(
            $empleado['id_empleado'], 
            $this->inasistenciaModel
        );

        $data = [
            'title' => 'Reporte de Inasistencias',
            'sidebar' => 'sidebar_empleado',
            'empleado' => $empleado,
            'periodo' => $periodo,
            'fecha_inicio' => $fechaInicio,
            'fecha_fin' => $fechaFin,
            'estadisticas' => $estadisticas,
            'inasistencias' => $inasistencias,
            'verificacion_limites' => $verificacionLimites
        ];

        return view('Roles/Empleado/inasistencias/reporte', $data);
    }

    /**
     * API: Obtener estadísticas de inasistencias
     */
    public function getEstadisticas()
    {
        $idUsuario = session()->get('id_usuario');
        $empleado = $this->empleadoModel->getEmpleadoByUsuarioId($idUsuario);

        if (!$empleado) {
            return $this->response->setJSON(['error' => 'Empleado no encontrado']);
        }

        $estadisticas = $this->inasistenciaModel->getEstadisticasInasistencias($empleado['id_empleado']);
        
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
        $idUsuario = session()->get('id_usuario');
        $empleado = $this->empleadoModel->getEmpleadoByUsuarioId($idUsuario);

        if (!$empleado) {
            return $this->response->setJSON(['error' => 'Empleado no encontrado']);
        }

        $fechaInicio = $this->request->getGet('fecha_inicio');
        $fechaFin = $this->request->getGet('fecha_fin');

        $inasistencias = $this->inasistenciaModel->getInasistenciasEmpleado(
            $empleado['id_empleado'],
            $fechaInicio,
            $fechaFin
        );

        return $this->response->setJSON([
            'success' => true,
            'data' => $inasistencias
        ]);
    }
}
