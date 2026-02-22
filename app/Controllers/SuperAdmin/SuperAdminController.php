<?php

namespace App\Controllers\SuperAdmin;

use CodeIgniter\Controller;
use App\Models\UsuarioModel;
use App\Models\RolModel;
use App\Models\SesionActivaModel;
use App\Models\LogSistemaModel;
use App\Models\RespaldoModel;

class SuperAdminController extends Controller
{
    public function dashboard()
    {
        $data = [
            'titulo' => 'Dashboard Super Administrador',
            'usuario' => [
                'nombres' => session()->get('nombres'),
                'apellidos' => session()->get('apellidos'),
                'rol' => session()->get('nombre_rol')
            ]
        ];

        return view('super_admin/dashboard', $data);
    }

    public function usuarios()
    {
        $usuarioModel = new \App\Models\UsuarioModel();
        $rolModel = new \App\Models\RolModel();
        $estadisticas = $usuarioModel->getEstadisticasUsuarios();
        
        $data = [
            'title' => 'Gestión de Usuarios',
            'user' => [
                'nombres' => session()->get('nombres'),
                'apellidos' => session()->get('apellidos'),
                'rol' => session()->get('nombre_rol')
            ],
            'usuarios' => $usuarioModel->getUsuariosConEmpleado(),
            'roles' => $rolModel->getRolesActivos(),
            'totalUsuarios' => $estadisticas->total_usuarios ?? 0,
            'usuariosActivos' => $estadisticas->usuarios_activos ?? 0,
            'usuariosInactivos' => $estadisticas->usuarios_inactivos ?? 0,
            'usuariosConAcceso' => $estadisticas->usuarios_con_acceso ?? 0
        ];
        
        return view('Roles/SuperAdministrador/usuarios', $data);
    }

    public function roles()
    {
        $rolModel = new \App\Models\RolModel();
        
        // Obtener roles con estadísticas
        $roles = $rolModel->getRolesConEstadisticas();
        
        // Calcular estadísticas generales
        $totalRoles = count($roles);
        $usuariosActivos = array_sum(array_column($roles, 'total_usuarios'));
        $rolMasUsado = $totalRoles > 0 ? $roles[0]['nombre_rol'] : 'N/A';
        $rolesSinUsuarios = count(array_filter($roles, function($r) { return $r['total_usuarios'] == 0; }));
        
        $estadisticas = [
            'total_roles' => $totalRoles,
            'usuarios_activos' => $usuariosActivos,
            'rol_mas_usado' => $rolMasUsado,
            'roles_sin_usuarios' => $rolesSinUsuarios
        ];
        
        $data = [
            'title' => 'Gestión de Roles - Super Administrador',
            'user' => [
                'nombres' => session()->get('nombres'),
                'apellidos' => session()->get('apellidos'),
                'rol' => session()->get('nombre_rol')
            ],
            'roles' => $roles,
            'estadisticas' => $estadisticas
        ];
        
        return view('Roles/SuperAdministrador/roles', $data);
    }

    public function configuracion()
    {
        $data = [
            'title' => 'Configuración del Sistema',
            'user' => [
                'nombres' => session()->get('nombres'),
                'apellidos' => session()->get('apellidos'),
                'rol' => session()->get('nombre_rol')
            ]
        ];
        
        return view('Roles/SuperAdministrador/configuracion', $data);
    }

    /**
     * Actualizar perfil de usuario
     */
    public function actualizarPerfil()
    {
        if (!$this->request->isAJAX()) {
            return redirect()->back();
        }

        try {
            $idUsuario = session()->get('id_usuario');
            $rules = [
                'nombres' => 'required|min_length[2]|max_length[50]',
                'apellidos' => 'required|min_length[2]|max_length[50]',
                'email' => 'required|valid_email|max_length[100]',
                'cedula' => 'required|min_length[8]|max_length[20]'
            ];

            if (!$this->validate($rules)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Datos de entrada inválidos',
                    'errors' => $this->validator->getErrors()
                ]);
            }

            $usuarioModel = new UsuarioModel();
            $data = [
                'nombres' => $this->request->getPost('nombres'),
                'apellidos' => $this->request->getPost('apellidos'),
                'email' => $this->request->getPost('email'),
                'cedula' => $this->request->getPost('cedula')
            ];

            if ($usuarioModel->update($idUsuario, $data)) {
                // Actualizar datos en sesión
                session()->set([
                    'nombres' => $data['nombres'],
                    'apellidos' => $data['apellidos'],
                    'email' => $data['email'],
                    'cedula' => $data['cedula']
                ]);

                // Registrar log
                $logModel = new LogSistemaModel();
                $logModel->registrarLog(
                    $idUsuario,
                    'ACTUALIZAR_PERFIL',
                    'PERFIL',
                    'Usuario actualizó su información de perfil'
                );

                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Perfil actualizado correctamente'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Error al actualizar el perfil'
                ]);
            }

        } catch (\Exception $e) {
            log_message('error', 'Error actualizando perfil: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error interno del servidor'
            ]);
        }
    }

    /**
     * Obtener sesiones activas del usuario
     */
    public function obtenerSesionesActivas()
    {
        if (!$this->request->isAJAX()) {
            return redirect()->back();
        }

        try {
            $idUsuario = session()->get('id_usuario');
            $sesionModel = new SesionActivaModel();
            $sesiones = $sesionModel->obtenerSesionesActivas($idUsuario);

            // Formatear los datos
            $sesionesFormateadas = [];
            foreach ($sesiones as $sesion) {
                $sesionesFormateadas[] = [
                    'id_sesion' => $sesion['id_sesion'],
                    'token_sesion' => substr($sesion['token_sesion'], 0, 8) . '...',
                    'fecha_inicio' => date('d/m/Y H:i:s', strtotime($sesion['fecha_inicio'])),
                    'ultima_actividad' => date('d/m/Y H:i:s', strtotime($sesion['fecha_ultima_actividad'])),
                    'ip_address' => $sesion['ip_address'],
                    'dispositivo' => $this->parseUserAgent($sesion['user_agent']),
                    'es_actual' => $sesion['token_sesion'] === session()->get('token_sesion')
                ];
            }

            return $this->response->setJSON([
                'success' => true,
                'sesiones' => $sesionesFormateadas
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Error obteniendo sesiones: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al obtener las sesiones'
            ]);
        }
    }

    /**
     * Cambiar contraseña
     */
    public function cambiarContrasena()
    {
        if (!$this->request->isAJAX()) {
            return redirect()->back();
        }

        try {
            $rules = [
                'password_actual' => 'required',
                'password_nuevo' => 'required|min_length[8]',
                'password_confirmar' => 'required|matches[password_nuevo]'
            ];

            if (!$this->validate($rules)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Datos de entrada inválidos',
                    'errors' => $this->validator->getErrors()
                ]);
            }

            $idUsuario = session()->get('id_usuario');
            $usuarioModel = new UsuarioModel();
            $usuario = $usuarioModel->find($idUsuario);

            // Verificar contraseña actual
            if (!password_verify($this->request->getPost('password_actual'), $usuario['password_hash'])) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'La contraseña actual es incorrecta'
                ]);
            }

            // Actualizar contraseña
            $nuevaPassword = password_hash($this->request->getPost('password_nuevo'), PASSWORD_DEFAULT);
            $usuarioModel->update($idUsuario, [
                'password_hash' => $nuevaPassword,
                'password_changed' => 1
            ]);

            // Registrar log
            $logModel = new LogSistemaModel();
            $logModel->registrarLog(
                $idUsuario,
                'CAMBIAR_CONTRASENA',
                'SEGURIDAD',
                'Usuario cambió su contraseña'
            );

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Contraseña actualizada correctamente'
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Error cambiando contraseña: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al cambiar la contraseña'
            ]);
        }
    }

    /**
     * Parse user agent string to get device info
     */
    private function parseUserAgent($userAgent)
    {
        if (strpos($userAgent, 'Chrome') !== false) {
            return 'Google Chrome';
        } elseif (strpos($userAgent, 'Firefox') !== false) {
            return 'Mozilla Firefox';
        } elseif (strpos($userAgent, 'Safari') !== false) {
            return 'Safari';
        } elseif (strpos($userAgent, 'Edge') !== false) {
            return 'Microsoft Edge';
        } else {
            return 'Navegador desconocido';
        }
    }

    public function respaldos()
    {
        try {
            $respaldoModel = new RespaldoModel();
            $respaldos = $respaldoModel->obtenerRespaldosConUsuario(20);
            $estadisticas = $respaldoModel->obtenerEstadisticasRespaldos();
            
            $data = [
                'title' => 'Gestión de Respaldos',
                'user' => [
                    'nombres' => session()->get('nombres'),
                    'apellidos' => session()->get('apellidos'),
                    'rol' => session()->get('nombre_rol')
                ],
                'respaldos' => $respaldos,
                'estadisticas' => $estadisticas
            ];
            
            return view('Roles/SuperAdministrador/respaldos', $data);
            
        } catch (\Exception $e) {
            log_message('error', 'Error cargando respaldos: ' . $e->getMessage());
            return view('Roles/SuperAdministrador/respaldos', [
                'title' => 'Gestión de Respaldos',
                'user' => [
                    'nombres' => session()->get('nombres'),
                    'apellidos' => session()->get('apellidos'),
                    'rol' => session()->get('nombre_rol')
                ],
                'respaldos' => [],
                'estadisticas' => [],
                'error' => 'Error al cargar los respaldos'
            ]);
        }
    }

    /**
     * Crear nuevo respaldo
     */
    public function crearRespaldo()
    {
        if (!$this->request->isAJAX()) {
            return redirect()->back();
        }

        try {
            $idUsuario = session()->get('id_usuario');
            $respaldoModel = new RespaldoModel();
            $resultado = $respaldoModel->generarRespaldoBaseDatos($idUsuario);
            
            // Registrar log
            $logModel = new LogSistemaModel();
            $accion = $resultado['success'] ? 'CREAR_RESPALDO_EXITOSO' : 'CREAR_RESPALDO_FALLIDO';
            $logModel->registrarLog(
                $idUsuario,
                $accion,
                'RESPALDOS',
                $resultado['message']
            );

            return $this->response->setJSON($resultado);

        } catch (\Exception $e) {
            log_message('error', 'Error creando respaldo: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error interno al crear respaldo'
            ]);
        }
    }

    /**
     * Descargar respaldo
     */
    public function descargarRespaldo($idRespaldo)
    {
        try {
            $respaldoModel = new RespaldoModel();
            $respaldo = $respaldoModel->find($idRespaldo);
            
            if (!$respaldo) {
                return redirect()->back()->with('error', 'Respaldo no encontrado');
            }
            
            if (!file_exists($respaldo['ruta_archivo'])) {
                return redirect()->back()->with('error', 'Archivo de respaldo no encontrado');
            }
            
            // Registrar log
            $logModel = new LogSistemaModel();
            $logModel->registrarLog(
                session()->get('id_usuario'),
                'DESCARGAR_RESPALDO',
                'RESPALDOS',
                'Descarga de respaldo: ' . $respaldo['nombre_archivo']
            );
            
            return $this->response->download($respaldo['ruta_archivo'], null, true);
            
        } catch (\Exception $e) {
            log_message('error', 'Error descargando respaldo: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al descargar el respaldo');
        }
    }

    /**
     * Eliminar respaldo
     */
    public function eliminarRespaldo()
    {
        if (!$this->request->isAJAX()) {
            return redirect()->back();
        }

        try {
            $idRespaldo = $this->request->getPost('id_respaldo');
            $respaldoModel = new RespaldoModel();
            $respaldo = $respaldoModel->find($idRespaldo);
            
            if (!$respaldo) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Respaldo no encontrado'
                ]);
            }
            
            // Eliminar archivo físico
            if (file_exists($respaldo['ruta_archivo'])) {
                unlink($respaldo['ruta_archivo']);
            }
            
            // Eliminar registro
            if ($respaldoModel->delete($idRespaldo)) {
                // Registrar log
                $logModel = new LogSistemaModel();
                $logModel->registrarLog(
                    session()->get('id_usuario'),
                    'ELIMINAR_RESPALDO',
                    'RESPALDOS',
                    'Eliminación de respaldo: ' . $respaldo['nombre_archivo']
                );
                
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Respaldo eliminado correctamente'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Error al eliminar el respaldo'
                ]);
            }
            
        } catch (\Exception $e) {
            log_message('error', 'Error eliminando respaldo: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error interno al eliminar respaldo'
            ]);
        }
    }

    public function logs()
    {
        try {
            $logModel = new LogSistemaModel();
            
            // Obtener filtros de la URL
            $filtros = [
                'fecha_inicio' => $this->request->getGet('fecha_inicio'),
                'fecha_fin' => $this->request->getGet('fecha_fin'),
                'usuario' => $this->request->getGet('usuario'),
                'modulo' => $this->request->getGet('modulo'),
                'accion' => $this->request->getGet('accion')
            ];
            
            // Paginación
            $limite = 50;
            $pagina = (int) ($this->request->getGet('page') ?? 1);
            $offset = ($pagina - 1) * $limite;
            
            $logs = $logModel->obtenerLogsConFiltros($filtros, $limite, $offset);
            $totalLogs = $logModel->contarLogsConFiltros($filtros);
            $estadisticas = $logModel->obtenerEstadisticasLogs();
            $modulosUtilizados = $logModel->obtenerModulosMasUtilizados();
            
            $data = [
                'title' => 'Registros del Sistema',
                'user' => [
                    'nombres' => session()->get('nombres'),
                    'apellidos' => session()->get('apellidos'),
                    'rol' => session()->get('nombre_rol')
                ],
                'logs' => $logs,
                'filtros' => $filtros,
                'totalLogs' => $totalLogs,
                'estadisticas' => $estadisticas,
                'modulosUtilizados' => $modulosUtilizados,
                'paginacion' => [
                    'pagina_actual' => $pagina,
                    'total_paginas' => ceil($totalLogs / $limite),
                    'limite' => $limite,
                    'total_registros' => $totalLogs
                ]
            ];
            
            return view('Roles/SuperAdministrador/logs', $data);
            
        } catch (\Exception $e) {
            log_message('error', 'Error cargando logs: ' . $e->getMessage());
            return view('Roles/SuperAdministrador/logs', [
                'title' => 'Registros del Sistema',
                'user' => [
                    'nombres' => session()->get('nombres'),
                    'apellidos' => session()->get('apellidos'),
                    'rol' => session()->get('nombre_rol')
                ],
                'logs' => [],
                'error' => 'Error al cargar los logs'
            ]);
        }
    }

    /**
     * Limpiar logs antiguos
     */
    public function limpiarLogs()
    {
        if (!$this->request->isAJAX()) {
            return redirect()->back();
        }

        try {
            $diasRetencion = (int) $this->request->getPost('dias_retencion') ?: 90;
            
            $logModel = new LogSistemaModel();
            $logsEliminados = $logModel->limpiarLogsAntiguos($diasRetencion);
            
            // Registrar acción
            $logModel->registrarLog(
                session()->get('id_usuario'),
                'LIMPIAR_LOGS',
                'SISTEMA',
                "Eliminados {$logsEliminados} logs anteriores a {$diasRetencion} días"
            );
            
            return $this->response->setJSON([
                'success' => true,
                'message' => "Se eliminaron {$logsEliminados} registros antiguos",
                'registros_eliminados' => $logsEliminados
            ]);
            
        } catch (\Exception $e) {
            log_message('error', 'Error limpiando logs: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al limpiar los logs'
            ]);
        }
    }

    /**
     * Exportar logs a CSV
     */
    public function exportarLogs()
    {
        try {
            $logModel = new LogSistemaModel();
            
            $filtros = [
                'fecha_inicio' => $this->request->getGet('fecha_inicio'),
                'fecha_fin' => $this->request->getGet('fecha_fin'),
                'usuario' => $this->request->getGet('usuario'),
                'modulo' => $this->request->getGet('modulo'),
                'accion' => $this->request->getGet('accion')
            ];
            
            $logs = $logModel->obtenerLogsConFiltros($filtros, 10000, 0); // Límite alto para exportación
            
            // Generar CSV
            $filename = 'logs_sistema_' . date('Y-m-d_H-i-s') . '.csv';
            $filepath = WRITEPATH . 'exports/' . $filename;
            
            // Crear directorio si no existe
            if (!is_dir(WRITEPATH . 'exports/')) {
                mkdir(WRITEPATH . 'exports/', 0755, true);
            }
            
            $file = fopen($filepath, 'w');
            
            // Encabezados
            fputcsv($file, [
                'ID',
                'Usuario',
                'Cédula',
                'Acción',
                'Módulo',
                'Descripción',
                'Fecha',
                'IP Address'
            ]);
            
            // Datos
            foreach ($logs as $log) {
                fputcsv($file, [
                    $log['id_log'],
                    $log['email'] ?? 'N/A',
                    $log['cedula'] ?? 'N/A',
                    $log['accion'],
                    $log['modulo'],
                    $log['descripcion'],
                    $log['fecha_accion'],
                    $log['ip_address']
                ]);
            }
            
            fclose($file);
            
            // Registrar log
            $logModel->registrarLog(
                session()->get('id_usuario'),
                'EXPORTAR_LOGS',
                'REPORTES',
                'Exportación de logs a CSV'
            );
            
            return $this->response->download($filepath, null, true);
            
        } catch (\Exception $e) {
            log_message('error', 'Error exportando logs: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al exportar los logs');
        }
    }

    public function estadisticas()
    {
        try {
            // Obtener estadísticas del sistema
            $estadisticas = $this->obtenerEstadisticasGlobales();
            
            $data = [
                'title' => 'Estadísticas del Sistema',
                'user' => [
                    'nombres' => session()->get('nombres'),
                    'apellidos' => session()->get('apellidos'),
                    'rol' => session()->get('nombre_rol')
                ],
                'estadisticas' => $estadisticas
            ];
            
            return view('Roles/SuperAdministrador/estadisticas', $data);
            
        } catch (\Exception $e) {
            log_message('error', 'Error cargando estadísticas: ' . $e->getMessage());
            return view('Roles/SuperAdministrador/estadisticas', [
                'title' => 'Estadísticas del Sistema',
                'user' => [
                    'nombres' => session()->get('nombres'),
                    'apellidos' => session()->get('apellidos'),
                    'rol' => session()->get('nombre_rol')
                ],
                'estadisticas' => [],
                'error' => 'Error al cargar las estadísticas'
            ]);
        }
    }

    /**
     * Obtener estadísticas globales del sistema
     */
    private function obtenerEstadisticasGlobales()
    {
        $db = \Config\Database::connect();
        
        // Estadísticas de usuarios
        $usuariosQuery = $db->query("
            SELECT 
                COUNT(*) as total_usuarios,
                COUNT(CASE WHEN activo = 1 THEN 1 END) as usuarios_activos,
                COUNT(CASE WHEN activo = 0 THEN 1 END) as usuarios_inactivos,
                COUNT(CASE WHEN DATE(fecha_registro) = CURDATE() THEN 1 END) as usuarios_hoy
            FROM usuarios
        ");
        $usuariosStats = $usuariosQuery->getRowArray();
        
        // Estadísticas de empleados
        $empleadosQuery = $db->query("
            SELECT 
                COUNT(*) as total_empleados,
                COUNT(CASE WHEN estado = 'ACTIVO' THEN 1 END) as empleados_activos,
                COUNT(CASE WHEN estado = 'INACTIVO' THEN 1 END) as empleados_inactivos,
                COUNT(DISTINCT departamento) as total_departamentos
            FROM empleados
        ");
        $empleadosStats = $empleadosQuery->getRowArray();
        
        // Estadísticas de sesiones
        $sesionesQuery = $db->query("
            SELECT 
                COUNT(*) as sesiones_activas,
                COUNT(DISTINCT id_usuario) as usuarios_conectados,
                COUNT(CASE WHEN DATE(fecha_inicio) = CURDATE() THEN 1 END) as sesiones_hoy
            FROM sesiones_activas 
            WHERE activa = 1
        ");
        $sesionesStats = $sesionesQuery->getRowArray();
        
        // Estadísticas de logs (últimos 7 días)
        $logsQuery = $db->query("
            SELECT 
                COUNT(*) as total_logs,
                COUNT(CASE WHEN DATE(fecha_accion) = CURDATE() THEN 1 END) as logs_hoy,
                COUNT(DISTINCT id_usuario) as usuarios_activos_logs,
                COUNT(DISTINCT modulo) as modulos_utilizados
            FROM logs_sistema 
            WHERE fecha_accion >= DATE_SUB(NOW(), INTERVAL 7 DAY)
        ");
        $logsStats = $logsQuery->getRowArray();
        
        // Estadísticas de respaldos
        $respaldosQuery = $db->query("
            SELECT 
                COUNT(*) as total_respaldos,
                COUNT(CASE WHEN estado = 'EXITOSO' THEN 1 END) as respaldos_exitosos,
                COUNT(CASE WHEN estado = 'FALLIDO' THEN 1 END) as respaldos_fallidos,
                SUM(tamano_bytes) as tamano_total_bytes
            FROM respaldos
        ");
        $respaldosStats = $respaldosQuery->getRowArray();
        
        // Actividad por módulos (últimos 7 días)
        $modulosQuery = $db->query("
            SELECT 
                modulo,
                COUNT(*) as total_acciones
            FROM logs_sistema 
            WHERE fecha_accion >= DATE_SUB(NOW(), INTERVAL 7 DAY)
            GROUP BY modulo
            ORDER BY total_acciones DESC
            LIMIT 10
        ");
        $modulosStats = $modulosQuery->getResultArray();
        
        // Usuarios más activos (últimos 7 días)
        $usuariosActivosQuery = $db->query("
            SELECT 
                u.cedula,
                u.email,
                COUNT(l.id_log) as total_acciones
            FROM logs_sistema l
            JOIN usuarios u ON u.id_usuario = l.id_usuario
            WHERE l.fecha_accion >= DATE_SUB(NOW(), INTERVAL 7 DAY)
            GROUP BY l.id_usuario
            ORDER BY total_acciones DESC
            LIMIT 10
        ");
        $usuariosActivosStats = $usuariosActivosQuery->getResultArray();
        
        // Tendencias diarias (últimos 7 días)
        $tendenciasQuery = $db->query("
            SELECT 
                DATE(fecha_accion) as fecha,
                COUNT(*) as total_acciones,
                COUNT(DISTINCT id_usuario) as usuarios_unicos
            FROM logs_sistema 
            WHERE fecha_accion >= DATE_SUB(NOW(), INTERVAL 7 DAY)
            GROUP BY DATE(fecha_accion)
            ORDER BY fecha DESC
        ");
        $tendenciasStats = $tendenciasQuery->getResultArray();
        
        return [
            'usuarios' => $usuariosStats,
            'empleados' => $empleadosStats,
            'sesiones' => $sesionesStats,
            'logs' => $logsStats,
            'respaldos' => $respaldosStats,
            'modulos_activos' => $modulosStats,
            'usuarios_activos' => $usuariosActivosStats,
            'tendencias' => $tendenciasStats
        ];
    }

    /**
     * Obtener estadísticas en tiempo real (AJAX)
     */
    public function obtenerEstadisticasTiempoReal()
    {
        if (!$this->request->isAJAX()) {
            return redirect()->back();
        }

        try {
            $estadisticas = $this->obtenerEstadisticasGlobales();
            
            return $this->response->setJSON([
                'success' => true,
                'estadisticas' => $estadisticas
            ]);
            
        } catch (\Exception $e) {
            log_message('error', 'Error obteniendo estadísticas tiempo real: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al obtener estadísticas'
            ]);
        }
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
        
        return view('Roles/SuperAdministrador/perfil', $data);
    }

    public function cuenta()
    {
        $data = [
            'title' => 'Configuración de Cuenta',
            'user' => [
                'nombres' => session()->get('nombres'),
                'apellidos' => session()->get('apellidos'),
                'rol' => session()->get('nombre_rol')
            ]
        ];
        
        return view('Roles/SuperAdministrador/cuenta', $data);
    }

    // ==================== GESTIÓN DE USUARIOS ====================

    /**
     * Obtener usuarios para DataTable
     */
    public function obtenerUsuarios()
    {
        if (!$this->request->isAJAX()) {
            return redirect()->back();
        }

        try {
            $usuarioModel = new UsuarioModel();
            $usuarios = $usuarioModel->getUsuariosConEmpleado();

            $data = [];
            foreach ($usuarios as $usuario) {
                $data[] = [
                    'id_usuario' => $usuario['id_usuario'],
                    'cedula' => $usuario['cedula'],
                    'email' => $usuario['email'],
                    'nombres' => $usuario['nombres'] ?? 'N/A',
                    'apellidos' => $usuario['apellidos'] ?? 'N/A',
                    'nombre_rol' => $usuario['nombre_rol'],
                    'activo' => $usuario['activo'] ? 'Activo' : 'Inactivo',
                    'fecha_registro' => date('d/m/Y', strtotime($usuario['fecha_registro'] ?? 'now')),
                    'acciones' => $this->generarAccionesUsuario($usuario)
                ];
            }

            return $this->response->setJSON([
                'data' => $data
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Error obteniendo usuarios: ' . $e->getMessage());
            return $this->response->setJSON([
                'error' => 'Error al obtener usuarios'
            ]);
        }
    }

    /**
     * Obtener usuario específico
     */
    public function obtenerUsuario($idUsuario)
    {
        if (!$this->request->isAJAX()) {
            return redirect()->back();
        }

        try {
            $usuarioModel = new UsuarioModel();
            $usuario = $usuarioModel->getUsuarioCompleto($idUsuario);

            if (!$usuario) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Usuario no encontrado'
                ]);
            }

            return $this->response->setJSON([
                'success' => true,
                'usuario' => $usuario
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Error obteniendo usuario: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al obtener el usuario'
            ]);
        }
    }

    /**
     * Editar usuario
     */
    public function editarUsuario()
    {
        if (!$this->request->isAJAX()) {
            return redirect()->back();
        }

        try {
            $rules = [
                'id_usuario' => 'required|integer',
                'cedula' => 'required|min_length[8]|max_length[20]',
                'email' => 'required|valid_email|max_length[100]',
                'id_rol' => 'required|integer',
                'activo' => 'required|in_list[0,1]'
            ];

            if (!$this->validate($rules)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Datos de entrada inválidos',
                    'errors' => $this->validator->getErrors()
                ]);
            }

            $idUsuario = $this->request->getPost('id_usuario');
            $usuarioModel = new UsuarioModel();
            
            // Verificar que no se esté editando a sí mismo para desactivar
            if ($idUsuario == session()->get('id_usuario') && $this->request->getPost('activo') == '0') {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'No puedes desactivar tu propia cuenta'
                ]);
            }

            $data = [
                'cedula' => $this->request->getPost('cedula'),
                'email' => $this->request->getPost('email'),
                'id_rol' => $this->request->getPost('id_rol'),
                'activo' => $this->request->getPost('activo')
            ];

            if ($usuarioModel->update($idUsuario, $data)) {
                // Registrar log
                $logModel = new LogSistemaModel();
                $logModel->registrarLog(
                    session()->get('id_usuario'),
                    'EDITAR_USUARIO',
                    'USUARIOS',
                    "Usuario editado: {$data['cedula']}"
                );

                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Usuario actualizado correctamente'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Error al actualizar el usuario'
                ]);
            }

        } catch (\Exception $e) {
            log_message('error', 'Error editando usuario: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error interno del servidor'
            ]);
        }
    }

    /**
     * Cambiar rol de usuario
     */
    public function cambiarRolUsuario()
    {
        if (!$this->request->isAJAX()) {
            return redirect()->back();
        }

        try {
            $rules = [
                'id_usuario' => 'required|integer',
                'id_rol' => 'required|integer'
            ];

            if (!$this->validate($rules)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Datos de entrada inválidos',
                    'errors' => $this->validator->getErrors()
                ]);
            }

            $idUsuario = $this->request->getPost('id_usuario');
            $idRol = $this->request->getPost('id_rol');
            
            // Verificar que no se esté cambiando su propio rol de Super Admin
            if ($idUsuario == session()->get('id_usuario') && session()->get('id_rol') == 1 && $idRol != 1) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'No puedes cambiar tu propio rol de Super Administrador'
                ]);
            }

            $usuarioModel = new UsuarioModel();
            $rolModel = new RolModel();
            
            // Verificar que el rol existe
            $rol = $rolModel->find($idRol);
            if (!$rol) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'El rol seleccionado no existe'
                ]);
            }

            if ($usuarioModel->update($idUsuario, ['id_rol' => $idRol])) {
                // Registrar log
                $logModel = new LogSistemaModel();
                $logModel->registrarLog(
                    session()->get('id_usuario'),
                    'CAMBIAR_ROL_USUARIO',
                    'USUARIOS',
                    "Rol cambiado a {$rol['nombre_rol']} para usuario ID: {$idUsuario}"
                );

                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Rol de usuario actualizado correctamente'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Error al actualizar el rol del usuario'
                ]);
            }

        } catch (\Exception $e) {
            log_message('error', 'Error cambiando rol: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error interno del servidor'
            ]);
        }
    }

    /**
     * Deshabilitar usuario
     */
    public function deshabilitarUsuario()
    {
        if (!$this->request->isAJAX()) {
            return redirect()->back();
        }

        try {
            $idUsuario = $this->request->getPost('id_usuario');
            
            // Verificar que no se esté deshabilitando a sí mismo
            if ($idUsuario == session()->get('id_usuario')) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'No puedes deshabilitar tu propia cuenta'
                ]);
            }

            $usuarioModel = new UsuarioModel();
            
            if ($usuarioModel->update($idUsuario, ['activo' => 0])) {
                // Cerrar todas las sesiones del usuario deshabilitado
                $sesionModel = new SesionActivaModel();
                $sesionModel->cerrarTodasLasSesiones($idUsuario);
                
                // Registrar log
                $logModel = new LogSistemaModel();
                $logModel->registrarLog(
                    session()->get('id_usuario'),
                    'DESHABILITAR_USUARIO',
                    'USUARIOS',
                    "Usuario deshabilitado ID: {$idUsuario}"
                );

                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Usuario deshabilitado correctamente'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Error al deshabilitar el usuario'
                ]);
            }

        } catch (\Exception $e) {
            log_message('error', 'Error deshabilitando usuario: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error interno del servidor'
            ]);
        }
    }

    /**
     * Habilitar usuario
     */
    public function habilitarUsuario()
    {
        if (!$this->request->isAJAX()) {
            return redirect()->back();
        }

        try {
            $idUsuario = $this->request->getPost('id_usuario');
            $usuarioModel = new UsuarioModel();
            
            if ($usuarioModel->update($idUsuario, ['activo' => 1])) {
                // Registrar log
                $logModel = new LogSistemaModel();
                $logModel->registrarLog(
                    session()->get('id_usuario'),
                    'HABILITAR_USUARIO',
                    'USUARIOS',
                    "Usuario habilitado ID: {$idUsuario}"
                );

                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Usuario habilitado correctamente'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Error al habilitar el usuario'
                ]);
            }

        } catch (\Exception $e) {
            log_message('error', 'Error habilitando usuario: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error interno del servidor'
            ]);
        }
    }

    /**
     * Generar HTML de acciones para cada usuario
     */
    private function generarAccionesUsuario($usuario)
    {
        $idUsuario = $usuario['id_usuario'];
        $esActivo = $usuario['activo'];
        $esMismoUsuario = $idUsuario == session()->get('id_usuario');
        
        $acciones = '<div class="btn-group" role="group">';
        
        // Botón editar
        $acciones .= '<button type="button" class="btn btn-sm btn-primary" onclick="editarUsuario(' . $idUsuario . ')" title="Editar">
                        <i class="fas fa-edit"></i>
                      </button>';
        
        if (!$esMismoUsuario) {
            // Botón habilitar/deshabilitar
            if ($esActivo) {
                $acciones .= '<button type="button" class="btn btn-sm btn-warning" onclick="deshabilitarUsuario(' . $idUsuario . ')" title="Deshabilitar">
                                <i class="fas fa-user-slash"></i>
                              </button>';
            } else {
                $acciones .= '<button type="button" class="btn btn-sm btn-success" onclick="habilitarUsuario(' . $idUsuario . ')" title="Habilitar">
                                <i class="fas fa-user-check"></i>
                              </button>';
            }
            
            // Botón cambiar rol
            $acciones .= '<button type="button" class="btn btn-sm btn-info" onclick="cambiarRol(' . $idUsuario . ')" title="Cambiar Rol">
                            <i class="fas fa-user-cog"></i>
                          </button>';
        }
        
        $acciones .= '</div>';
        
        return $acciones;
    }

    // ==================== GESTIÓN DE ROLES ====================

    /**
     * Obtener roles para DataTable
     */
    public function obtenerRoles()
    {
        if (!$this->request->isAJAX()) {
            return redirect()->back();
        }

        try {
            $rolModel = new RolModel();
            $roles = $rolModel->getRolesConEstadisticas();

            $data = [];
            foreach ($roles as $rol) {
                $data[] = [
                    'id_rol' => $rol['id_rol'],
                    'nombre_rol' => $rol['nombre_rol'],
                    'descripcion' => $rol['descripcion'] ?? 'Sin descripción',
                    'total_usuarios' => $rol['total_usuarios'] ?? 0,
                    'activo' => $rol['activo'] ? 'Activo' : 'Inactivo',
                    'fecha_creacion' => date('d/m/Y', strtotime($rol['fecha_creacion'] ?? 'now')),
                    'acciones' => $this->generarAccionesRol($rol)
                ];
            }

            return $this->response->setJSON([
                'data' => $data
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Error obteniendo roles: ' . $e->getMessage());
            return $this->response->setJSON([
                'error' => 'Error al obtener roles'
            ]);
        }
    }

    /**
     * Obtener rol específico
     */
    public function obtenerRol($idRol)
    {
        if (!$this->request->isAJAX()) {
            return redirect()->back();
        }

        try {
            $rolModel = new RolModel();
            $rol = $rolModel->find($idRol);

            if (!$rol) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Rol no encontrado'
                ]);
            }

            return $this->response->setJSON([
                'success' => true,
                'rol' => $rol
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Error obteniendo rol: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al obtener el rol'
            ]);
        }
    }

    /**
     * Crear nuevo rol
     */
    public function crearRol()
    {
        if (!$this->request->isAJAX()) {
            return redirect()->back();
        }

        try {
            $rules = [
                'nombre_rol' => 'required|min_length[3]|max_length[100]|is_unique[roles.nombre_rol]',
                'descripcion' => 'permit_empty|max_length[500]',
                'nivel_acceso' => 'required|integer|greater_than[0]|less_than[100]'
            ];

            if (!$this->validate($rules)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Datos de entrada inválidos',
                    'errors' => $this->validator->getErrors()
                ]);
            }

            $rolModel = new RolModel();
            $data = [
                'nombre_rol' => $this->request->getPost('nombre_rol'),
                'descripcion' => $this->request->getPost('descripcion'),
                'nivel_acceso' => $this->request->getPost('nivel_acceso'),
                'activo' => 1,
                'fecha_creacion' => date('Y-m-d H:i:s')
            ];

            if ($rolModel->insert($data)) {
                // Registrar log
                $logModel = new LogSistemaModel();
                $logModel->registrarLog(
                    session()->get('id_usuario'),
                    'CREAR_ROL',
                    'ROLES',
                    "Rol creado: {$data['nombre_rol']}"
                );

                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Rol creado correctamente'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Error al crear el rol'
                ]);
            }

        } catch (\Exception $e) {
            log_message('error', 'Error creando rol: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error interno del servidor'
            ]);
        }
    }

    /**
     * Editar rol
     */
    public function editarRol()
    {
        if (!$this->request->isAJAX()) {
            return redirect()->back();
        }

        try {
            $idRol = $this->request->getPost('id_rol');
            
            $rules = [
                'id_rol' => 'required|integer',
                'nombre_rol' => "required|min_length[3]|max_length[100]|is_unique[roles.nombre_rol,id_rol,{$idRol}]",
                'descripcion' => 'permit_empty|max_length[500]',
                'nivel_acceso' => 'required|integer|greater_than[0]|less_than[100]'
            ];

            if (!$this->validate($rules)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Datos de entrada inválidos',
                    'errors' => $this->validator->getErrors()
                ]);
            }

            // Verificar que no se esté editando el rol Super Admin (ID 1)
            if ($idRol == 1) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'No se puede modificar el rol Super Administrador'
                ]);
            }

            $rolModel = new RolModel();
            $data = [
                'nombre_rol' => $this->request->getPost('nombre_rol'),
                'descripcion' => $this->request->getPost('descripcion'),
                'nivel_acceso' => $this->request->getPost('nivel_acceso')
            ];

            if ($rolModel->update($idRol, $data)) {
                // Registrar log
                $logModel = new LogSistemaModel();
                $logModel->registrarLog(
                    session()->get('id_usuario'),
                    'EDITAR_ROL',
                    'ROLES',
                    "Rol editado: {$data['nombre_rol']}"
                );

                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Rol actualizado correctamente'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Error al actualizar el rol'
                ]);
            }

        } catch (\Exception $e) {
            log_message('error', 'Error editando rol: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error interno del servidor'
            ]);
        }
    }

    /**
     * Deshabilitar rol
     */
    public function deshabilitarRol()
    {
        if (!$this->request->isAJAX()) {
            return redirect()->back();
        }

        try {
            $idRol = $this->request->getPost('id_rol');
            
            // Verificar que no se esté deshabilitando el rol Super Admin (ID 1)
            if ($idRol == 1) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'No se puede deshabilitar el rol Super Administrador'
                ]);
            }

            $rolModel = new RolModel();
            $rol = $rolModel->find($idRol);
            
            if (!$rol) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Rol no encontrado'
                ]);
            }

            // Verificar si hay usuarios usando este rol
            $usuarioModel = new UsuarioModel();
            $usuariosConRol = $usuarioModel->where('id_rol', $idRol)->countAllResults();
            
            if ($usuariosConRol > 0) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => "No se puede deshabilitar el rol porque tiene {$usuariosConRol} usuario(s) asignado(s)"
                ]);
            }

            if ($rolModel->update($idRol, ['activo' => 0])) {
                // Registrar log
                $logModel = new LogSistemaModel();
                $logModel->registrarLog(
                    session()->get('id_usuario'),
                    'DESHABILITAR_ROL',
                    'ROLES',
                    "Rol deshabilitado: {$rol['nombre_rol']}"
                );

                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Rol deshabilitado correctamente'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Error al deshabilitar el rol'
                ]);
            }

        } catch (\Exception $e) {
            log_message('error', 'Error deshabilitando rol: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error interno del servidor'
            ]);
        }
    }

    /**
     * Habilitar rol
     */
    public function habilitarRol()
    {
        if (!$this->request->isAJAX()) {
            return redirect()->back();
        }

        try {
            $idRol = $this->request->getPost('id_rol');
            $rolModel = new RolModel();
            
            if ($rolModel->update($idRol, ['activo' => 1])) {
                $rol = $rolModel->find($idRol);
                
                // Registrar log
                $logModel = new LogSistemaModel();
                $logModel->registrarLog(
                    session()->get('id_usuario'),
                    'HABILITAR_ROL',
                    'ROLES',
                    "Rol habilitado: {$rol['nombre_rol']}"
                );

                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Rol habilitado correctamente'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Error al habilitar el rol'
                ]);
            }

        } catch (\Exception $e) {
            log_message('error', 'Error habilitando rol: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error interno del servidor'
            ]);
        }
    }

    /**
     * Generar HTML de acciones para cada rol
     */
    private function generarAccionesRol($rol)
    {
        $idRol = $rol['id_rol'];
        $esActivo = $rol['activo'];
        $esSuperAdmin = $idRol == 1;
        
        $acciones = '<div class="btn-group" role="group">';
        
        // Botón editar
        if (!$esSuperAdmin) {
            $acciones .= '<button type="button" class="btn btn-sm btn-primary" onclick="editarRol(' . $idRol . ')" title="Editar">
                            <i class="fas fa-edit"></i>
                          </button>';
        }
        
        // Botón habilitar/deshabilitar
        if (!$esSuperAdmin) {
            if ($esActivo) {
                $acciones .= '<button type="button" class="btn btn-sm btn-warning" onclick="deshabilitarRol(' . $idRol . ')" title="Deshabilitar">
                                <i class="fas fa-user-slash"></i>
                              </button>';
            } else {
                $acciones .= '<button type="button" class="btn btn-sm btn-success" onclick="habilitarRol(' . $idRol . ')" title="Habilitar">
                                <i class="fas fa-user-check"></i>
                              </button>';
            }
        }
        
        // Botón ver usuarios
        $acciones .= '<button type="button" class="btn btn-sm btn-info" onclick="verUsuariosRol(' . $idRol . ')" title="Ver Usuarios">
                        <i class="fas fa-users"></i>
                      </button>';
        
        $acciones .= '</div>';
        
        return $acciones;
    }
}
