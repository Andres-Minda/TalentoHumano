<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class DocenteController extends Controller
{
    public function __construct()
    {
        // Verificar que el usuario esté logueado y tenga el rol correcto
        if (!session()->get('isLoggedIn') || session()->get('id_rol') != 3) {
            return redirect()->to('/access-denied');
        }
    }

    public function dashboard()
    {
        $periodoModel = new \App\Models\PeriodoAcademicoModel();
        $empleadoModel = new \App\Models\EmpleadoModel();
        
        // Obtener empleado actual
        $empleado = $empleadoModel->getEmpleadoByUsuarioId(session()->get('id_usuario'));
        $periodoActivo = $periodoModel->getPeriodoActivo();
        
        // Obtener estadísticas del empleado
        $estadisticas = [
            'total_capacitaciones' => 0,
            'total_documentos' => 0,
            'total_certificados' => 0,
            'total_solicitudes' => 0
        ];
        
        // Si es empleado, obtener estadísticas reales
        if ($empleado) {
            // Aquí se pueden agregar las consultas para obtener estadísticas reales
            // Por ahora se mantienen en 0
        }
        
        // Determinar el título del dashboard según el tipo de empleado
        $tituloDashboard = 'Dashboard - Docente';
        $descripcionDashboard = 'Panel de control para docentes';
        
        if ($empleado && isset($empleado['tipo_empleado'])) {
            switch ($empleado['tipo_empleado']) {
                case 'DOCENTE':
                    $tituloDashboard = 'Dashboard - Docente';
                    $descripcionDashboard = 'Panel de control para docentes';
                    break;
                case 'ADMINISTRATIVO':
                    $tituloDashboard = 'Dashboard - Administrativo';
                    $descripcionDashboard = 'Panel de control para personal administrativo';
                    break;
                case 'DIRECTIVO':
                    $tituloDashboard = 'Dashboard - Directivo';
                    $descripcionDashboard = 'Panel de control para directivos';
                    break;
                case 'AUXILIAR':
                    $tituloDashboard = 'Dashboard - Auxiliar';
                    $descripcionDashboard = 'Panel de control para auxiliares';
                    break;
            }
            
            // Guardar el tipo de empleado en la sesión para el sidebar
            session()->set('tipo_empleado', $empleado['tipo_empleado']);
        }
        
        $data = [
            'title' => $tituloDashboard,
            'sidebar' => 'sidebar_empleado', // Forzar el sidebar de empleado
            'user' => [
                'nombres' => session()->get('nombres'),
                'apellidos' => session()->get('apellidos'),
                'rol' => session()->get('nombre_rol')
            ],
            'empleado' => $empleado,
            'periodo_activo' => $periodoActivo,
            'estadisticas' => $estadisticas,
            'titulo_dashboard' => $tituloDashboard,
            'descripcion_dashboard' => $descripcionDashboard
        ];
        
        // Usar el dashboard unificado de empleado
        return view('Roles/Empleado/dashboard', $data);
    }

    public function perfil()
    {
        $data = [
            'title' => 'Mi Perfil',
            'user' => [
                'nombres' => session()->get('nombres'),
                'apellidos' => session()->get('apellidos'),
                'rol' => session()->get('nombre_rol')
            ]
        ];
        
        return view('Roles/Docente/perfil', $data);
    }

    public function capacitaciones()
    {
        $capacitacionModel = new \App\Models\CapacitacionModel();
        $empleadoCapacitacionModel = new \App\Models\EmpleadoCapacitacionModel();
        $idEmpleado = session()->get('id_empleado');
        
        // Si no hay id_empleado en la sesión, obtenerlo del usuario
        if (!$idEmpleado) {
            $empleadoModel = new \App\Models\EmpleadoModel();
            $empleado = $empleadoModel->getEmpleadoByUsuarioId(session()->get('id_usuario'));
            $idEmpleado = $empleado ? $empleado['id_empleado'] : null;
        }
        
        // Obtener capacitaciones disponibles y mis capacitaciones
        $capacitacionesDisponibles = $idEmpleado ? $capacitacionModel->getCapacitacionesDisponibles($idEmpleado) : [];
        $misCapacitaciones = $idEmpleado ? $empleadoCapacitacionModel->getCapacitacionesPorEmpleado($idEmpleado) : [];
        
        // Estadísticas
        $estadisticas = [
            'total_disponibles' => count($capacitacionesDisponibles),
            'total_inscrito' => count($misCapacitaciones),
            'total_completadas' => count(array_filter($misCapacitaciones, function($c) { return $c['aprobo'] == 1; })),
            'total_pendientes' => count(array_filter($misCapacitaciones, function($c) { return $c['aprobo'] == 0; }))
        ];
        
        $data = [
            'title' => 'Mis Capacitaciones - Docente',
            'user' => [
                'nombres' => session()->get('nombres'),
                'apellidos' => session()->get('apellidos'),
                'rol' => session()->get('nombre_rol')
            ],
            'capacitaciones_disponibles' => $capacitacionesDisponibles,
            'mis_capacitaciones' => $misCapacitaciones,
            'estadisticas' => $estadisticas
        ];
        
        return view('Roles/Docente/capacitaciones', $data);
    }

    public function documentos()
    {
        $documentoModel = new \App\Models\DocumentoModel();
        $categoriaModel = new \App\Models\CategoriaModel();
        $idEmpleado = session()->get('id_empleado');
        
        // Obtener estadísticas
        $estadisticas = $documentoModel->getEstadisticasDocumentos($idEmpleado);
        
        $data = [
            'title' => 'Mis Documentos - Docente',
            'user' => [
                'nombres' => session()->get('nombres'),
                'apellidos' => session()->get('apellidos'),
                'rol' => session()->get('nombre_rol')
            ],
            'documentos' => $documentoModel->getDocumentosPorEmpleado($idEmpleado),
            'categorias' => $categoriaModel->findAll(),
            'estadisticas' => $estadisticas
        ];
        
        return view('Roles/Docente/documentos', $data);
    }

    public function certificados()
    {
        $certificadoModel = new \App\Models\CertificadoModel();
        $idEmpleado = session()->get('id_empleado');
        
        // Obtener certificados del empleado
        $certificados = $certificadoModel->getCertificadosPorEmpleado($idEmpleado);
        
        // Estadísticas
        $estadisticas = [
            'total_certificados' => count($certificados),
            'certificados_vigentes' => count(array_filter($certificados, function($c) { 
                return $c['estado'] == 'Vigente'; 
            })),
            'certificados_vencidos' => count(array_filter($certificados, function($c) { 
                return $c['estado'] == 'Vencido'; 
            })),
            'proximos_vencer' => count(array_filter($certificados, function($c) { 
                return $c['estado'] == 'Próximo a vencer'; 
            }))
        ];
        
        $data = [
            'title' => 'Mis Certificados - Docente',
            'user' => [
                'nombres' => session()->get('nombres'),
                'apellidos' => session()->get('apellidos'),
                'rol' => session()->get('nombre_rol')
            ],
            'certificados' => $certificados,
            'estadisticas' => $estadisticas
        ];
        
        return view('Roles/Docente/certificados', $data);
    }

    public function evaluaciones()
    {
        $evaluacionModel = new \App\Models\EvaluacionModel();
        $idEmpleado = session()->get('id_empleado');
        
        // Obtener evaluaciones del empleado
        $evaluaciones = $evaluacionModel->getEvaluacionesPorEmpleado($idEmpleado);
        
        // Calcular estadísticas
        $totalEvaluaciones = count($evaluaciones);
        $promedioPuntaje = $totalEvaluaciones > 0 ? array_sum(array_column($evaluaciones, 'puntaje_total')) / $totalEvaluaciones : 0;
        $ultimaEvaluacion = $totalEvaluaciones > 0 ? date('d/m/Y', strtotime($evaluaciones[0]['fecha_evaluacion'])) : 'N/A';
        
        $estadisticas = [
            'total_evaluaciones' => $totalEvaluaciones,
            'promedio_puntaje' => round($promedioPuntaje, 1),
            'ultima_evaluacion' => $ultimaEvaluacion,
            'competencias_evaluadas' => count(array_unique(array_column($evaluaciones, 'id_competencia')))
        ];
        
        $data = [
            'title' => 'Mis Evaluaciones - Docente',
            'user' => [
                'nombres' => session()->get('nombres'),
                'apellidos' => session()->get('apellidos'),
                'rol' => session()->get('nombre_rol')
            ],
            'evaluaciones' => $evaluaciones,
            'estadisticas' => $estadisticas
        ];
        
        return view('Roles/Docente/evaluaciones', $data);
    }

    public function competencias()
    {
        $empleadoCompetenciaModel = new \App\Models\EmpleadoCompetenciaModel();
        $idEmpleado = session()->get('id_empleado');
        
        // Obtener competencias del empleado
        $competencias = $empleadoCompetenciaModel->getCompetenciasPorEmpleado($idEmpleado);
        
        // Calcular estadísticas
        $totalCompetencias = count($competencias);
        $nivelExperto = count(array_filter($competencias, function($c) { return $c['nivel_actual'] == 'Experto'; }));
        $nivelAvanzado = count(array_filter($competencias, function($c) { return $c['nivel_actual'] == 'Avanzado'; }));
        
        $estadisticas = [
            'total_competencias' => $totalCompetencias,
            'nivel_experto' => $nivelExperto,
            'nivel_avanzado' => $nivelAvanzado,
            'promedio_nivel' => $totalCompetencias > 0 ? round(($nivelExperto + $nivelAvanzado) / $totalCompetencias * 100, 1) : 0
        ];
        
        $data = [
            'title' => 'Mis Competencias - Docente',
            'user' => [
                'nombres' => session()->get('nombres'),
                'apellidos' => session()->get('apellidos'),
                'rol' => session()->get('nombre_rol')
            ],
            'competencias' => $competencias,
            'estadisticas' => $estadisticas
        ];
        
        return view('Roles/Docente/competencias', $data);
    }

    public function asistencias()
    {
        $asistenciaModel = new \App\Models\AsistenciaModel();
        $empleadoId = session('id_empleado');
        
        // Obtener estadísticas de asistencias del docente
        $estadisticas = $asistenciaModel->getEstadisticasAsistencias();
        
        // Obtener asistencias del docente
        $asistencias = $asistenciaModel->getAsistenciasEmpleado($empleadoId);
        
        $data = [
            'title' => 'Control de Asistencias - Docente',
            'user' => [
                'nombres' => session('nombres'),
                'apellidos' => session('apellidos'),
                'rol' => session('nombre_rol')
            ],
            'estadisticas' => [
                'total_dias' => count($asistencias),
                'puntuales' => $estadisticas->puntuales ?? 0,
                'tardanzas' => $estadisticas->tardanzas ?? 0,
                'ausencias' => $estadisticas->ausencias ?? 0
            ],
            'asistencias' => $asistencias
        ];
        
        return view('Roles/Docente/asistencias', $data);
    }

    public function guardarAsistencia()
    {
        $asistenciaModel = new \App\Models\AsistenciaModel();
        $empleadoId = session('id_empleado');
        
        $data = [
            'id_empleado' => $empleadoId,
            'fecha' => $this->request->getPost('fecha'),
            'hora_entrada' => $this->request->getPost('hora_entrada'),
            'hora_salida' => $this->request->getPost('hora_salida'),
            'estado' => $this->request->getPost('estado'),
            'observaciones' => $this->request->getPost('observaciones')
        ];
        
        // Calcular horas trabajadas si hay entrada y salida
        if ($data['hora_entrada'] && $data['hora_salida']) {
            $entrada = strtotime($data['hora_entrada']);
            $salida = strtotime($data['hora_salida']);
            $data['horas_trabajadas'] = ($salida - $entrada) / 3600;
        }
        
        try {
            $asistenciaModel->insert($data);
            return $this->response->setJSON(['success' => true, 'message' => 'Asistencia registrada correctamente']);
        } catch (\Exception $e) {
            return $this->response->setJSON(['success' => false, 'message' => 'Error al registrar asistencia']);
        }
    }

    public function permisos()
    {
        $permisoModel = new \App\Models\PermisoModel();
        $idEmpleado = session()->get('id_empleado');
        
        // Obtener permisos del empleado
        $permisos = $permisoModel->getPermisosPorEmpleado($idEmpleado);
        
        // Calcular estadísticas
        $totalPermisos = count($permisos);
        $permisosAprobados = count(array_filter($permisos, function($p) { return $p['estado'] == 'Aprobado'; }));
        $permisosPendientes = count(array_filter($permisos, function($p) { return $p['estado'] == 'Solicitado'; }));
        $permisosRechazados = count(array_filter($permisos, function($p) { return $p['estado'] == 'Rechazado'; }));
        
        $estadisticas = [
            'total_permisos' => $totalPermisos,
            'permisos_aprobados' => $permisosAprobados,
            'permisos_pendientes' => $permisosPendientes,
            'permisos_rechazados' => $permisosRechazados
        ];
        
        $data = [
            'title' => 'Mis Permisos - Docente',
            'user' => [
                'nombres' => session()->get('nombres'),
                'apellidos' => session()->get('apellidos'),
                'rol' => session()->get('nombre_rol')
            ],
            'permisos' => $permisos,
            'estadisticas' => $estadisticas
        ];
        
        return view('Roles/Docente/permisos', $data);
    }

    public function nomina()
    {
        $nominaModel = new \App\Models\NominaModel();
        $detalleNominaModel = new \App\Models\DetalleNominaModel();
        $idEmpleado = session()->get('id_empleado');
        
        // Obtener nóminas del empleado
        $nominas = $detalleNominaModel->getNominasPorEmpleado($idEmpleado);
        
        // Calcular estadísticas
        $salarioBase = 0;
        $totalIngresos = 0;
        $totalDescuentos = 0;
        $netoPagar = 0;
        
        if (!empty($nominas)) {
            $salarioBase = $nominas[0]['salario_base'] ?? 0;
            $totalIngresos = array_sum(array_column($nominas, 'total_ingresos'));
            $totalDescuentos = array_sum(array_column($nominas, 'total_descuentos'));
            $netoPagar = array_sum(array_column($nominas, 'neto_pagar'));
        }
        
        $estadisticas = [
            'salario_base' => $salarioBase,
            'total_ingresos' => $totalIngresos,
            'total_descuentos' => $totalDescuentos,
            'neto_pagar' => $netoPagar
        ];
        
        $data = [
            'title' => 'Mi Nómina - Docente',
            'user' => [
                'nombres' => session()->get('nombres'),
                'apellidos' => session()->get('apellidos'),
                'rol' => session()->get('nombre_rol')
            ],
            'nominas' => $nominas,
            'estadisticas' => $estadisticas
        ];
        
        return view('Roles/Docente/nomina', $data);
    }



    public function solicitudes()
    {
        $solicitudModel = new \App\Models\SolicitudModel();
        $idEmpleado = session()->get('id_empleado');
        
        // Obtener solicitudes del empleado
        $solicitudes = $solicitudModel->getSolicitudesPorEmpleado($idEmpleado);
        
        // Calcular estadísticas
        $totalSolicitudes = count($solicitudes);
        $solicitudesAprobadas = count(array_filter($solicitudes, function($s) { return $s['estado'] == 'Aprobada'; }));
        $solicitudesPendientes = count(array_filter($solicitudes, function($s) { return $s['estado'] == 'Pendiente'; }));
        $solicitudesRechazadas = count(array_filter($solicitudes, function($s) { return $s['estado'] == 'Rechazada'; }));
        
        $estadisticas = [
            'total_solicitudes' => $totalSolicitudes,
            'solicitudes_aprobadas' => $solicitudesAprobadas,
            'solicitudes_pendientes' => $solicitudesPendientes,
            'solicitudes_rechazadas' => $solicitudesRechazadas
        ];
        
        $data = [
            'title' => 'Mis Solicitudes - Docente',
            'user' => [
                'nombres' => session()->get('nombres'),
                'apellidos' => session()->get('apellidos'),
                'rol' => session()->get('nombre_rol')
            ],
            'solicitudes' => $solicitudes,
            'estadisticas' => $estadisticas
        ];
        
        return view('Roles/Docente/solicitudes', $data);
    }

    public function nuevaSolicitud()
    {
        $data = [
            'title' => 'Nueva Solicitud - Docente',
            'user' => [
                'nombres' => session()->get('nombres'),
                'apellidos' => session()->get('apellidos'),
                'rol' => session()->get('nombre_rol')
            ]
        ];
        
        return view('Roles/Docente/nueva_solicitud', $data);
    }

    public function cuenta()
    {
        $data = [
            'title' => 'Mi Cuenta - Docente',
            'user' => [
                'nombres' => session()->get('nombres'),
                'apellidos' => session()->get('apellidos'),
                'rol' => session()->get('nombre_rol')
            ]
        ];
        return view('Roles/Docente/cuenta', $data);
    }

    public function actualizarPerfil()
    {
        try {
            $usuarioModel = new \App\Models\UsuarioModel();
            $empleadoModel = new \App\Models\EmpleadoModel();
            
            $idUsuario = session()->get('id_usuario');
            $data = [
                'nombres' => $this->request->getPost('nombres'),
                'apellidos' => $this->request->getPost('apellidos'),
                'email' => $this->request->getPost('email')
            ];
            
            // Actualizar usuario
            $usuarioModel->update($idUsuario, $data);
            
            // Actualizar empleado si existe
            $empleado = $empleadoModel->where('id_usuario', $idUsuario)->first();
            if ($empleado) {
                $empleadoData = [
                    'nombres' => $this->request->getPost('nombres'),
                    'apellidos' => $this->request->getPost('apellidos'),
                    'telefono' => $this->request->getPost('telefono'),
                    'fecha_nacimiento' => $this->request->getPost('fecha_nacimiento'),
                    'genero' => $this->request->getPost('genero'),
                    'estado_civil' => $this->request->getPost('estado_civil'),
                    'direccion' => $this->request->getPost('direccion')
                ];
                $empleadoModel->update($empleado['id_empleado'], $empleadoData);
            }
            
            // Actualizar sesión
            session()->set([
                'nombres' => $data['nombres'],
                'apellidos' => $data['apellidos'],
                'email' => $data['email']
            ]);
            
            return $this->response->setJSON(['success' => true, 'message' => 'Perfil actualizado correctamente']);
        } catch (\Exception $e) {
            return $this->response->setJSON(['success' => false, 'message' => 'Error al actualizar perfil: ' . $e->getMessage()]);
        }
    }

    public function cambiarPassword()
    {
        try {
            $usuarioModel = new \App\Models\UsuarioModel();
            $idUsuario = session()->get('id_usuario');
            
            $passwordActual = $this->request->getPost('password_actual');
            $passwordNuevo = $this->request->getPost('password_nuevo');
            
            // Verificar contraseña actual
            $usuario = $usuarioModel->find($idUsuario);
            if (!password_verify($passwordActual, $usuario['password'])) {
                return $this->response->setJSON(['success' => false, 'message' => 'La contraseña actual es incorrecta']);
            }
            
            // Actualizar contraseña
            $usuarioModel->update($idUsuario, [
                'password' => password_hash($passwordNuevo, PASSWORD_DEFAULT)
            ]);
            
            return $this->response->setJSON(['success' => true, 'message' => 'Contraseña cambiada correctamente']);
        } catch (\Exception $e) {
            return $this->response->setJSON(['success' => false, 'message' => 'Error al cambiar contraseña: ' . $e->getMessage()]);
        }
    }

    public function configurarNotificaciones()
    {
        try {
            // Aquí se implementaría la lógica para guardar configuración de notificaciones
            return $this->response->setJSON(['success' => true, 'message' => 'Configuración guardada correctamente']);
        } catch (\Exception $e) {
            return $this->response->setJSON(['success' => false, 'message' => 'Error al guardar configuración: ' . $e->getMessage()]);
        }
    }

    public function configurarPrivacidad()
    {
        try {
            // Aquí se implementaría la lógica para guardar configuración de privacidad
            return $this->response->setJSON(['success' => true, 'message' => 'Configuración de privacidad guardada']);
        } catch (\Exception $e) {
            return $this->response->setJSON(['success' => false, 'message' => 'Error al guardar configuración: ' . $e->getMessage()]);
        }
    }

    public function cerrarSesiones()
    {
        try {
            // Implementar lógica para cerrar todas las sesiones excepto la actual
            return $this->response->setJSON(['success' => true, 'message' => 'Sesiones cerradas correctamente']);
        } catch (\Exception $e) {
            return $this->response->setJSON(['success' => false, 'message' => 'Error al cerrar sesiones: ' . $e->getMessage()]);
        }
    }

    // Métodos para gestión de documentos
    public function subirDocumento()
    {
        try {
            $documentoModel = new \App\Models\DocumentoModel();
            $empleadoModel = new \App\Models\EmpleadoModel();
            
            // Obtener ID del empleado actual
            $empleado = $empleadoModel->getEmpleadoByUsuarioId(session()->get('id_usuario'));
            $idEmpleado = $empleado['id_empleado'] ?? null;
            
            if (!$idEmpleado) {
                return $this->response->setJSON(['success' => false, 'message' => 'Empleado no encontrado']);
            }
            
            $data = [
                'id_empleado' => $idEmpleado,
                'id_categoria' => $this->request->getPost('categoria'),
                'nombre' => $this->request->getPost('nombre'),
                'descripcion' => $this->request->getPost('descripcion'),
                'archivo_url' => 'documento_' . time() . '.pdf', // Simular nombre de archivo
                'tamaño' => '2.5 MB', // Simular tamaño
                'tipo_archivo' => 'PDF'
            ];
            
            $resultado = $documentoModel->subirDocumento($data);
            
            if ($resultado) {
                return $this->response->setJSON(['success' => true, 'message' => 'Documento subido correctamente']);
            } else {
                return $this->response->setJSON(['success' => false, 'message' => 'Error al subir documento']);
            }
        } catch (\Exception $e) {
            return $this->response->setJSON(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
    }

    public function verDocumento($idDocumento)
    {
        try {
            $documentoModel = new \App\Models\DocumentoModel();
            $documento = $documentoModel->getDocumentoCompleto($idDocumento);
            
            if (!$documento) {
                return $this->response->setJSON(['success' => false, 'message' => 'Documento no encontrado']);
            }
            
            $html = view('partials/documento_detalles', ['documento' => $documento]);
            
            return $this->response->setJSON(['success' => true, 'html' => $html]);
        } catch (\Exception $e) {
            return $this->response->setJSON(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
    }

    public function descargarDocumento($idDocumento)
    {
        try {
            $documentoModel = new \App\Models\DocumentoModel();
            $documento = $documentoModel->getDocumentoCompleto($idDocumento);
            
            if (!$documento) {
                return $this->response->setJSON(['success' => false, 'message' => 'Documento no encontrado']);
            }
            
            // Simular descarga
            return $this->response->setJSON(['success' => true, 'message' => 'Descarga iniciada']);
        } catch (\Exception $e) {
            return $this->response->setJSON(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
    }

    public function eliminarDocumento($idDocumento)
    {
        try {
            $documentoModel = new \App\Models\DocumentoModel();
            $resultado = $documentoModel->delete($idDocumento);
            
            if ($resultado) {
                return $this->response->setJSON(['success' => true, 'message' => 'Documento eliminado correctamente']);
            } else {
                return $this->response->setJSON(['success' => false, 'message' => 'Error al eliminar documento']);
            }
        } catch (\Exception $e) {
            return $this->response->setJSON(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
    }

    // Métodos específicos para docentes
    public function evaluacionesEstudiantiles()
    {
        $data = [
            'title' => 'Evaluaciones Estudiantiles - Docente',
            'user' => [
                'nombres' => session()->get('nombres'),
                'apellidos' => session()->get('apellidos'),
                'rol' => session()->get('nombre_rol')
            ]
        ];
        return view('Roles/Docente/evaluaciones_estudiantiles', $data);
    }

    public function metodologiaEnsenanza()
    {
        $data = [
            'title' => 'Metodología de Enseñanza - Docente',
            'user' => [
                'nombres' => session()->get('nombres'),
                'apellidos' => session()->get('apellidos'),
                'rol' => session()->get('nombre_rol')
            ]
        ];
        return view('Roles/Docente/metodologia_ensenanza', $data);
    }

    public function investigacion()
    {
        $data = [
            'title' => 'Investigación - Docente',
            'user' => [
                'nombres' => session()->get('nombres'),
                'apellidos' => session()->get('apellidos'),
                'rol' => session()->get('nombre_rol')
            ]
        ];
        return view('Roles/Docente/investigacion', $data);
    }

    public function gestionProcesos()
    {
        $data = [
            'title' => 'Gestión de Procesos - Docente',
            'user' => [
                'nombres' => session()->get('nombres'),
                'apellidos' => session()->get('apellidos'),
                'rol' => session()->get('nombre_rol')
            ]
        ];
        return view('Roles/Docente/gestion_procesos', $data);
    }

    public function reportesAdministrativos()
    {
        $data = [
            'title' => 'Reportes Administrativos - Docente',
            'user' => [
                'nombres' => session()->get('nombres'),
                'apellidos' => session()->get('apellidos'),
                'rol' => session()->get('nombre_rol')
            ]
        ];
        return view('Roles/Docente/reportes_administrativos', $data);
    }

    public function gestionEquipo()
    {
        $data = [
            'title' => 'Gestión de Equipo - Docente',
            'user' => [
                'nombres' => session()->get('nombres'),
                'apellidos' => session()->get('apellidos'),
                'rol' => session()->get('nombre_rol')
            ]
        ];
        return view('Roles/Docente/gestion_equipo', $data);
    }

    public function indicadoresGestion()
    {
        $data = [
            'title' => 'Indicadores de Gestión - Docente',
            'user' => [
                'nombres' => session()->get('nombres'),
                'apellidos' => session()->get('apellidos'),
                'rol' => session()->get('nombre_rol')
            ]
        ];
        return view('Roles/Docente/indicadores_gestion', $data);
    }

    public function titulosAcademicos()
    {
        $empleadoModel = new \App\Models\EmpleadoModel();
        $tituloModel = new \App\Models\TituloAcademicoModel();
        
        $empleado = $empleadoModel->getEmpleadoByUsuarioId(session()->get('id_usuario'));
        $titulos = $tituloModel->getTitulosPorEmpleado($empleado['id_empleado']);

        // Calcular estadísticas
        $totalTitulos = count($titulos);
        $titulosTercerNivel = count(array_filter($titulos, function($t) { 
            return in_array($t['tipo_titulo'] ?? '', ['Tercer Nivel', 'Licenciatura', 'Ingeniería']); 
        }));
        $titulosCuartoNivel = count(array_filter($titulos, function($t) { 
            return in_array($t['tipo_titulo'] ?? '', ['Cuarto Nivel', 'Maestría', 'Especialización']); 
        }));
        
        // Obtener universidades únicas
        $universidades = array_unique(array_filter(array_column($titulos, 'universidad')));
        sort($universidades);

        $estadisticas = [
            'total_titulos' => $totalTitulos,
            'titulos_tercer_nivel' => $titulosTercerNivel,
            'titulos_cuarto_nivel' => $titulosCuartoNivel,
            'universidades' => count($universidades)
        ];

        $data = [
            'title' => 'Mis Títulos Académicos - Docente',
            'user' => [
                'nombres' => session()->get('nombres'),
                'apellidos' => session()->get('apellidos'),
                'rol' => session()->get('nombre_rol')
            ],
            'empleado' => $empleado,
            'titulos' => $titulos,
            'estadisticas' => $estadisticas,
            'universidades' => $universidades
        ];

        return view('Roles/Docente/titulos_academicos', $data);
    }

    public function capacitacionesIndividuales()
    {
        $empleadoModel = new \App\Models\EmpleadoModel();
        $capacitacionModel = new \App\Models\CapacitacionEmpleadoModel();
        
        $empleado = $empleadoModel->getEmpleadoByUsuarioId(session()->get('id_usuario'));
        $capacitaciones = $capacitacionModel->getCapacitacionesPorEmpleado($empleado['id_empleado']);

        $data = [
            'title' => 'Mis Capacitaciones Individuales - Docente',
            'user' => [
                'nombres' => session()->get('nombres'),
                'apellidos' => session()->get('apellidos'),
                'rol' => session()->get('nombre_rol')
            ],
            'empleado' => $empleado,
            'capacitaciones' => $capacitaciones
        ];

        return view('Roles/Docente/capacitaciones_individuales', $data);
    }

    public function evaluacionesEmpleado()
    {
        $empleadoModel = new \App\Models\EmpleadoModel();
        $evaluacionModel = new \App\Models\EvaluacionModel();
        
        $empleado = $empleadoModel->getEmpleadoByUsuarioId(session()->get('id_usuario'));
        $evaluaciones = $evaluacionModel->getEvaluacionesPorEmpleado($empleado['id_empleado']);

        $data = [
            'title' => 'Mis Evaluaciones - Docente',
            'user' => [
                'nombres' => session()->get('nombres'),
                'apellidos' => session()->get('apellidos'),
                'rol' => session()->get('nombre_rol')
            ],
            'empleado' => $empleado,
            'evaluaciones' => $evaluaciones
        ];

        return view('Roles/Docente/evaluaciones_empleado', $data);
    }

    public function controlInasistencias()
    {
        $asistenciaModel = new \App\Models\AsistenciaModel();
        $empleadoId = session('id_empleado');
        
        $asistencias = $asistenciaModel->getAsistenciasEmpleado($empleadoId);
        
        $data = [
            'title' => 'Control de Inasistencias - Docente',
            'user' => [
                'nombres' => session('nombres'),
                'apellidos' => session('apellidos'),
                'rol' => session('nombre_rol')
            ],
            'asistencias' => $asistencias
        ];
        
        return view('Roles/Docente/control_inasistencias', $data);
    }

    public function solicitudesGenerales()
    {
        $solicitudModel = new \App\Models\SolicitudModel();
        $idEmpleado = session()->get('id_empleado');
        
        $solicitudes = $solicitudModel->getSolicitudesPorEmpleado($idEmpleado);
        
        $data = [
            'title' => 'Solicitudes Generales - Docente',
            'user' => [
                'nombres' => session()->get('nombres'),
                'apellidos' => session()->get('apellidos'),
                'rol' => session()->get('nombre_rol')
            ],
            'solicitudes' => $solicitudes
        ];
        
        return view('Roles/Docente/solicitudes_generales', $data);
    }

    public function permisosVacaciones()
    {
        $permisoModel = new \App\Models\PermisoModel();
        $idEmpleado = session()->get('id_empleado');
        
        $permisos = $permisoModel->getPermisosPorEmpleado($idEmpleado);
        
        $data = [
            'title' => 'Permisos y Vacaciones - Docente',
            'user' => [
                'nombres' => session()->get('nombres'),
                'apellidos' => session()->get('apellidos'),
                'rol' => session()->get('nombre_rol')
            ],
            'permisos' => $permisos
        ];
        
        return view('Roles/Docente/permisos_vacaciones', $data);
    }
} 