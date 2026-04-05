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
            return redirect()->to('/empleado/dashboard')->with('error', 'No se encontrÃ³ informaciÃ³n del empleado');
        }

        // Obtener estadÃ­sticas del empleado
        $estadisticas = $this->inasistenciaModel->getEstadisticasInasistencias($empleado['id_empleado']);
        
        // Obtener inasistencias recientes
        $inasistenciasRecientes = $this->inasistenciaModel->getInasistenciasEmpleado(
            $empleado['id_empleado'], 
            null, 
            null
        );

        // Verificar lÃ­mites segÃºn polÃ­tica
        $verificacionLimites = $this->politicaInasistenciaModel->verificarLimites(
            $empleado['id_empleado'], 
            $this->inasistenciaModel
        );

        $data = [
            'title' => 'Dashboard de Inasistencias',
            'sidebar' => 'partials/sidebar_empleado',
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
            return redirect()->to('/empleado/dashboard')->with('error', 'No se encontrÃ³ informaciÃ³n del empleado');
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
            'sidebar' => 'partials/sidebar_empleado',
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
            return redirect()->to('/empleado/dashboard')->with('error', 'No se encontrÃ³ informaciÃ³n del empleado');
        }

        // Obtener inasistencia
        $inasistencia = $this->inasistenciaModel->find($idInasistencia);

        if (!$inasistencia || $inasistencia['empleado_id'] != $empleado['id_empleado']) {
            return redirect()->to('/empleado/inasistencias')->with('error', 'Inasistencia no encontrada o sin acceso.');
        }

        $data = [
            'title' => 'Detalle de Inasistencia',
            'sidebar' => 'partials/sidebar_empleado',
            'empleado' => $empleado,
            'inasistencia' => $inasistencia
        ];

        return view('Roles/Empleado/inasistencias/ver_inasistencia', $data);
    }

    /**
     * Subir justificaciÃ³n
     */
    public function subirJustificacion()
    {
        $idUsuario = session()->get('id_usuario');
        $empleado = $this->empleadoModel->getEmpleadoByUsuarioId($idUsuario);

        if (!$empleado) {
            return redirect()->to('/empleado/dashboard')->with('error', 'No se encontrÃ³ informaciÃ³n del empleado');
        }

        if ($this->request->getMethod() === 'post') {
            $idInasistencia = $this->request->getPost('id_inasistencia');
            $justificacion = $this->request->getPost('justificacion');

            // Verificar que la inasistencia pertenece al empleado
            $inasistencia = $this->inasistenciaModel->find($idInasistencia);
            if (!$inasistencia || $inasistencia['empleado_id'] != $empleado['id_empleado']) {
                return redirect()->back()->with('error', 'Inasistencia no válida');
            }

            // ===== Subir archivo a Google Drive =====
            $archivo = $this->request->getFile('documento');
            $driveLink = null;

            if ($archivo && $archivo->isValid() && !$archivo->hasMoved()) {
                $clientSecretsPath = WRITEPATH . 'client_secrets.json';
                $tokenPath = WRITEPATH . 'token.json';

                if (!file_exists($clientSecretsPath) || !file_exists($tokenPath)) {
                    return redirect()->back()->with('error', 'Error del servidor: Google Drive no está configurado (faltan credenciales/token).');
                }

                try {
                    $client = new \Google\Client();
                    $client->setAuthConfig($clientSecretsPath);
                    $client->addScope(\Google\Service\Drive::DRIVE_FILE);

                    $accessToken = json_decode(file_get_contents($tokenPath), true);
                    $client->setAccessToken($accessToken);

                    if ($client->isAccessTokenExpired()) {
                        if ($client->getRefreshToken()) {
                            $newToken = $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
                            if (isset($newToken['error'])) throw new \Exception('Token inválido');
                            file_put_contents($tokenPath, json_encode($client->getAccessToken()));
                        } else {
                            throw new \Exception('Token expirado sin refresh_token');
                        }
                    }

                    $driveService = new \Google\Service\Drive($client);
                    
                    $extension = strtolower($archivo->getExtension());
                    $nombreLimpio = preg_replace('/[^A-Za-z0-9_]/', '_', ($empleado['nombres'] ?? '') . '_' . ($empleado['apellidos'] ?? ''));
                    $nombreFinal = 'Justificativo_' . $nombreLimpio . '_' . date('Ymd_His') . '.' . $extension;

                    // IMPORTANTE: PONER EL ID DE LA CARPETA "Justificantes - Google Drive" AQUÍ
                    $carpetaJustificantesId = '1dt77sqzWrHehQbAMA0vHrplXU-dxIxUZ';

                    $fileMetadata = new \Google\Service\Drive\DriveFile([
                        'name' => $nombreFinal,
                        'parents' => [$carpetaJustificantesId]
                    ]);

                    $content = file_get_contents($archivo->getTempName());
                    
                    $mime = 'application/octet-stream';
                    if (in_array($extension, ['jpg', 'jpeg'])) $mime = 'image/jpeg';
                    elseif ($extension == 'png') $mime = 'image/png';
                    elseif ($extension == 'pdf') $mime = 'application/pdf';
                    elseif (in_array($extension, ['doc', 'docx'])) $mime = 'application/msword';

                    $uploadedFile = $driveService->files->create($fileMetadata, [
                        'data' => $content,
                        'mimeType' => $mime,
                        'uploadType' => 'multipart',
                        'fields' => 'id, webViewLink'
                    ]);

                    $permission = new \Google\Service\Drive\Permission([
                        'role' => 'reader',
                        'type' => 'anyone'
                    ]);
                    $driveService->permissions->create($uploadedFile->id, $permission);

                    $driveLink = $uploadedFile->webViewLink;

                } catch (\Exception $e) {
                    log_message('error', 'Error Drive Justificación: ' . $e->getMessage());
                    return redirect()->back()->with('error', 'No se pudo subir el archivo: ' . $e->getMessage());
                }
            }

            // Actualizar inasistencia (Usando nombres reales de la BD)
            $data = [
                'tipo_inasistencia' => 'PENDIENTE_JUSTIFICACION'
            ];
            
            // Si el empleado escribió una justificación, la concatenamos al motivo existente para no perderla
            if (!empty($justificacion)) {
                $motivoPrevio = $inasistencia['motivo'] ?? '';
                $data['motivo'] = $motivoPrevio . "\n\nJustificación del empleado:\n" . $justificacion;
            }

            if ($driveLink) {
                // El campo correcto de la base de datos es archivo_justificacion
                $data['archivo_justificacion'] = $driveLink;
            }

            if ($this->inasistenciaModel->update($idInasistencia, $data)) {
                return redirect()->to('/empleado/inasistencias')->with('success', 'Justificante subido a Google Drive exitosamente.');
            } else {
                return redirect()->back()->with('error', 'Error al actualizar la base de datos de inasistencias.');
            }
        }

        // Obtener inasistencias pendientes de justificación para el dropdown
        $builder = $this->inasistenciaModel->builder();
        $builder->where('empleado_id', $empleado['id_empleado'])
                ->groupStart()
                    ->where('tipo_inasistencia', 'Injustificada')
                    ->orWhere('tipo_inasistencia', 'NO_JUSTIFICADA')
                    ->orWhere('justificada', 0)
                ->groupEnd()
                ->orderBy('fecha_inasistencia', 'DESC');
        $inasistenciasPendientes = $builder->get()->getResultArray();

        $data = [
            'title' => 'Subir Justificación',
            'sidebar' => 'partials/sidebar_empleado',
            'empleado' => $empleado,
            'inasistencias_pendientes' => $inasistenciasPendientes
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
            return redirect()->to('/empleado/dashboard')->with('error', 'No se encontrÃ³ informaciÃ³n del empleado');
        }

        // Obtener perÃ­odo del reporte
        $periodo = $this->request->getGet('periodo') ?: 'MENSUAL';
        
        // Calcular fechas segÃºn perÃ­odo
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

        // Obtener estadÃ­sticas
        $estadisticas = $this->inasistenciaModel->getEstadisticasInasistencias(
            $empleado['id_empleado'],
            $fechaInicio,
            $fechaFin
        );

        // Obtener inasistencias del perÃ­odo
        $inasistencias = $this->inasistenciaModel->getInasistenciasEmpleado(
            $empleado['id_empleado'],
            $fechaInicio,
            $fechaFin
        );

        // Verificar lÃ­mites
        $verificacionLimites = $this->politicaInasistenciaModel->verificarLimites(
            $empleado['id_empleado'], 
            $this->inasistenciaModel
        );

        $data = [
            'title' => 'Reporte de Inasistencias',
            'sidebar' => 'partials/sidebar_empleado',
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
     * API: Obtener estadÃ­sticas de inasistencias
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
     * API AJAX: Obtener mis inasistencias (datos + estadÃ­sticas) â€” Empleado logueado
     * GET empleado/inasistencias/obtener-mis-inasistencias
     */
    public function obtenerMisInasistencias()
    {
        try {
            $idUsuario = session()->get('id_usuario');
            if (!$idUsuario) {
                return $this->response->setJSON(['success' => false, 'message' => 'SesiÃ³n no vÃ¡lida']);
            }

            $empleado = $this->empleadoModel->getEmpleadoByUsuarioId($idUsuario);
            if (!$empleado) {
                return $this->response->setJSON(['success' => false, 'message' => 'Registro de empleado no encontrado']);
            }

            $idEmpleado = $empleado['id_empleado'];

            // Leer filtros opcionales del query string
            $fechaDesde = $this->request->getGet('fecha_desde') ?: null;
            $fechaHasta = $this->request->getGet('fecha_hasta') ?: null;
            $tipo       = $this->request->getGet('tipo')        ?: null;
            $estado     = $this->request->getGet('estado')      ?: null;

            $resultado = $this->inasistenciaModel->getMisInasistencias(
                $idEmpleado, $fechaDesde, $fechaHasta, $tipo, $estado
            );

            return $this->response->setJSON([
                'success'     => true,
                'data'        => $resultado['data'],
                'total'       => $resultado['total'],
                'justificadas'=> $resultado['justificadas'],
                'pendientes'  => $resultado['pendientes'],
            ]);

        } catch (\Exception $e) {
            log_message('error', 'obtenerMisInasistencias: ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'Error al obtener inasistencias: ' . $e->getMessage()]);
        }
    }

    /**
     * API: Obtener inasistencias por perÃ­odo
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

