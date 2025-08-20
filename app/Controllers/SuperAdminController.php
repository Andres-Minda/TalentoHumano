<?php

namespace App\Controllers;

use CodeIgniter\Controller;

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

    public function departamentos()
    {
        $departamentoModel = new \App\Models\DepartamentoModel();
        $empleadoModel = new \App\Models\EmpleadoModel();
        
        // Obtener departamentos con estadísticas
        $departamentos = $departamentoModel->getDepartamentosConEstadisticas();
        
        // Calcular estadísticas generales
        $totalDepartamentos = count($departamentos);
        $totalEmpleados = array_sum(array_column($departamentos, 'total_empleados'));
        $departamentosConJefe = count(array_filter($departamentos, function($d) { return !empty($d['jefe_nombre']); }));
        $departamentosSinEmpleados = count(array_filter($departamentos, function($d) { return $d['total_empleados'] == 0; }));
        
        $estadisticas = [
            'total_departamentos' => $totalDepartamentos,
            'total_empleados' => $totalEmpleados,
            'departamentos_con_jefe' => $departamentosConJefe,
            'departamentos_sin_empleados' => $departamentosSinEmpleados
        ];
        
        $data = [
            'title' => 'Gestión de Departamentos - Super Administrador',
            'user' => [
                'nombres' => session()->get('nombres'),
                'apellidos' => session()->get('apellidos'),
                'rol' => session()->get('nombre_rol')
            ],
            'departamentos' => $departamentos,
            'empleados' => $empleadoModel->getEmpleadosActivos(),
            'estadisticas' => $estadisticas
        ];
        
        return view('Roles/SuperAdministrador/departamentos', $data);
    }

    public function configuracion()
    {
        // Simular configuración del sistema
        $configuracion = [
            'nombre_institucion' => 'Instituto Tecnológico Superior',
            'direccion' => 'Av. Principal 123, Ciudad',
            'telefono' => '+593 2 1234567',
            'email' => 'info@instituto.edu.ec',
            'sitio_web' => 'https://www.instituto.edu.ec',
            'duracion_sesion' => 30,
            'intentos_login' => 3,
            'tiempo_bloqueo' => 15,
            'requerir_cambio_password' => true,
            'registrar_actividad' => true,
            'notificar_nuevas_solicitudes' => true,
            'notificar_capacitaciones' => true,
            'notificar_evaluaciones' => true,
            'notificar_permisos' => true,
            'tamano_bd' => '2.5 MB',
            'ultimo_respaldo' => '2025-08-03 10:30:00'
        ];
        
        $data = [
            'title' => 'Configuración del Sistema - Super Administrador',
            'user' => [
                'nombres' => session()->get('nombres'),
                'apellidos' => session()->get('apellidos'),
                'rol' => session()->get('nombre_rol')
            ],
            'configuracion' => $configuracion
        ];
        
        return view('Roles/SuperAdministrador/configuracion', $data);
    }

    public function backup()
    {
        $data = [
            'title' => 'Respaldo y Restauración - Super Administrador',
            'user' => [
                'nombres' => session()->get('nombres'),
                'apellidos' => session()->get('apellidos'),
                'rol' => session()->get('nombre_rol')
            ]
        ];
        return view('Roles/SuperAdministrador/backup', $data);
    }

    public function respaldo()
    {
        // Datos de ejemplo para la interfaz de respaldo
        $data = [
            'totalRespaldos' => 12,
            'ultimoRespaldo' => 'Hace 2 horas',
            'tamañoTotal' => '2.4 GB',
            'proximoRespaldo' => '23:00',
            'historialRespaldos' => [
                [
                    'fecha' => '2025-08-03 15:30:00',
                    'tipo' => 'completo',
                    'tamaño' => '245 MB',
                    'estado' => 'completado',
                    'descripcion' => 'Respaldo automático diario'
                ],
                [
                    'fecha' => '2025-08-02 23:00:00',
                    'tipo' => 'completo',
                    'tamaño' => '240 MB',
                    'estado' => 'completado',
                    'descripcion' => 'Respaldo automático diario'
                ],
                [
                    'fecha' => '2025-08-01 23:00:00',
                    'tipo' => 'incremental',
                    'tamaño' => '15 MB',
                    'estado' => 'completado',
                    'descripcion' => 'Respaldo incremental'
                ]
            ]
        ];

        return view('Roles/SuperAdministrador/respaldo', $data);
    }

    public function crearRespaldo()
    {
        $tipo = $this->request->getPost('tipoRespaldo');
        $descripcion = $this->request->getPost('descripcion');
        $comprimir = $this->request->getPost('comprimir') ? true : false;

        // Generar nombre del archivo
        $timestamp = date('Y-m-d_H-i-s');
        $nombreArchivo = "respaldo_{$tipo}_{$timestamp}.sql";

        // Crear contenido del respaldo (simulado)
        $contenido = "-- Respaldo del Sistema Talento Humano\n";
        $contenido .= "-- Fecha: " . date('Y-m-d H:i:s') . "\n";
        $contenido .= "-- Tipo: " . ucfirst($tipo) . "\n";
        $contenido .= "-- Descripción: " . ($descripcion ?: 'Sin descripción') . "\n\n";

        // Agregar estructura de tablas (simulado)
        $contenido .= "-- Estructura de la base de datos\n";
        $contenido .= "CREATE TABLE IF NOT EXISTS `usuarios` (\n";
        $contenido .= "  `id` int(11) NOT NULL AUTO_INCREMENT,\n";
        $contenido .= "  `nombres` varchar(100) NOT NULL,\n";
        $contenido .= "  `apellidos` varchar(100) NOT NULL,\n";
        $contenido .= "  `email` varchar(255) NOT NULL,\n";
        $contenido .= "  PRIMARY KEY (`id`)\n";
        $contenido .= ") ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;\n\n";

        $contenido .= "CREATE TABLE IF NOT EXISTS `empleados` (\n";
        $contenido .= "  `id` int(11) NOT NULL AUTO_INCREMENT,\n";
        $contenido .= "  `nombres` varchar(100) NOT NULL,\n";
        $contenido .= "  `apellidos` varchar(100) NOT NULL,\n";
        $contenido .= "  `departamento_id` int(11) NOT NULL,\n";
        $contenido .= "  PRIMARY KEY (`id`)\n";
        $contenido .= ") ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;\n\n";

        // Si se solicita compresión
        if ($comprimir) {
            $nombreArchivo = str_replace('.sql', '.zip', $nombreArchivo);
            $contenido = gzencode($contenido, 9);
        }

        // Guardar archivo
        $rutaArchivo = WRITEPATH . 'uploads/respaldos/' . $nombreArchivo;
        if (!is_dir(dirname($rutaArchivo))) {
            mkdir(dirname($rutaArchivo), 0755, true);
        }
        
        if (file_put_contents($rutaArchivo, $contenido)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Respaldo creado exitosamente',
                'archivo' => $nombreArchivo
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al crear el respaldo'
            ]);
        }
    }

    public function descargarRespaldo($id = null)
    {
        if ($id) {
            // Descargar respaldo específico por ID
            $rutaArchivo = WRITEPATH . 'uploads/respaldos/respaldo_' . $id . '.sql';
        } else {
            // Descargar el último respaldo
            $rutaArchivo = WRITEPATH . 'uploads/respaldos/respaldo_completo_' . date('Y-m-d') . '.sql';
        }

        if (file_exists($rutaArchivo)) {
            return $this->response->download($rutaArchivo, null);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Archivo de respaldo no encontrado'
            ]);
        }
    }

    public function reportesSistema()
    {
        $data = [
            'title' => 'Reportes del Sistema - Super Administrador',
            'user' => [
                'nombres' => session()->get('nombres'),
                'apellidos' => session()->get('apellidos'),
                'rol' => session()->get('nombre_rol')
            ]
        ];
        return view('Roles/SuperAdministrador/reportes_sistema', $data);
    }

    public function generarReporte()
    {
        $tipo = $this->request->getPost('tipoReporte');
        $fechaInicio = $this->request->getPost('fechaInicio');
        $fechaFin = $this->request->getPost('fechaFin');
        $formato = $this->request->getPost('formato') ?: 'pdf';

        // Generar contenido del reporte (simulado)
        $contenido = "REPORTE DEL SISTEMA TALENTO HUMANO\n";
        $contenido .= "================================================================\n";
        $contenido .= "Fecha de generación: " . date('Y-m-d H:i:s') . "\n";
        $contenido .= "Tipo de reporte: " . ucfirst($tipo) . "\n";
        $contenido .= "Período: {$fechaInicio} - {$fechaFin}\n";
        $contenido .= "================================================================\n\n";

        // Agregar contenido específico según el tipo
        switch ($tipo) {
            case 'usuarios':
                $contenido .= "ESTADÍSTICAS DE USUARIOS\n";
                $contenido .= "================================================================\n";
                $contenido .= "Total de usuarios: 150\n";
                $contenido .= "Usuarios activos: 142\n";
                $contenido .= "Usuarios inactivos: 8\n";
                $contenido .= "Nuevos usuarios este mes: 12\n";
                break;
                
            case 'empleados':
                $contenido .= "ESTADÍSTICAS DE EMPLEADOS\n";
                $contenido .= "================================================================\n";
                $contenido .= "Total de empleados: 180\n";
                $contenido .= "Docentes: 120\n";
                $contenido .= "Administrativos: 45\n";
                $contenido .= "Directivos: 15\n";
                break;
                
            case 'capacitaciones':
                $contenido .= "ESTADÍSTICAS DE CAPACITACIONES\n";
                $contenido .= "================================================================\n";
                $contenido .= "Total de capacitaciones: 25\n";
                $contenido .= "Capacitaciones completadas: 20\n";
                $contenido .= "Capacitaciones en curso: 3\n";
                $contenido .= "Capacitaciones programadas: 2\n";
                break;
                
            case 'evaluaciones':
                $contenido .= "ESTADÍSTICAS DE EVALUACIONES\n";
                $contenido .= "================================================================\n";
                $contenido .= "Total de evaluaciones: 180\n";
                $contenido .= "Evaluaciones completadas: 175\n";
                $contenido .= "Evaluaciones pendientes: 5\n";
                $contenido .= "Promedio general: 8.5/10\n";
                break;
        }

        $contenido .= "\n\nDETALLES ADICIONALES\n";
        $contenido .= "================================================================\n";
        $contenido .= "Este reporte fue generado automáticamente por el sistema.\n";
        $contenido .= "Para más información, contacte al administrador del sistema.\n";
        $contenido .= "================================================================\n";

        // Generar nombre del archivo
        $timestamp = date('Y-m-d_H-i-s');
        $nombreArchivo = "reporte_{$tipo}_{$timestamp}.{$formato}";

        // Guardar archivo
        $rutaArchivo = WRITEPATH . 'uploads/reportes/' . $nombreArchivo;
        if (!is_dir(dirname($rutaArchivo))) {
            mkdir(dirname($rutaArchivo), 0755, true);
        }
        
        if (file_put_contents($rutaArchivo, $contenido)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Reporte generado exitosamente',
                'archivo' => $nombreArchivo
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al generar el reporte'
            ]);
        }
    }

    public function generarReporteGlobal()
    {
        $fechaInicio = $this->request->getPost('fechaInicio');
        $fechaFin = $this->request->getPost('fechaFin');
        $formato = $this->request->getPost('formato') ?: 'pdf';

        // Generar contenido del reporte global (simulado)
        $contenido = "REPORTE GLOBAL DEL SISTEMA TALENTO HUMANO\n";
        $contenido .= "================================================================\n";
        $contenido .= "Fecha de generación: " . date('Y-m-d H:i:s') . "\n";
        $contenido .= "Período: {$fechaInicio} - {$fechaFin}\n";
        $contenido .= "================================================================\n\n";

        $contenido .= "RESUMEN EJECUTIVO\n";
        $contenido .= "================================================================\n";
        $contenido .= "El sistema ha funcionado de manera estable durante el período reportado.\n";
        $contenido .= "Se han procesado múltiples operaciones y se mantiene la integridad de los datos.\n\n";

        $contenido .= "ESTADÍSTICAS GENERALES\n";
        $contenido .= "================================================================\n";
        $contenido .= "Total de usuarios: 150\n";
        $contenido .= "Total de empleados: 180\n";
        $contenido .= "Total de departamentos: 12\n";
        $contenido .= "Total de capacitaciones: 25\n";
        $contenido .= "Total de evaluaciones: 180\n";
        $contenido .= "Total de solicitudes: 45\n\n";

        $contenido .= "ACTIVIDAD DEL SISTEMA\n";
        $contenido .= "================================================================\n";
        $contenido .= "Inicios de sesión: 1,250\n";
        $contenido .= "Operaciones realizadas: 3,450\n";
        $contenido .= "Archivos procesados: 890\n";
        $contenido .= "Reportes generados: 67\n\n";

        $contenido .= "RECOMENDACIONES\n";
        $contenido .= "================================================================\n";
        $contenido .= "1. Continuar con el mantenimiento preventivo del sistema\n";
        $contenido .= "2. Revisar y actualizar políticas de seguridad\n";
        $contenido .= "3. Capacitar usuarios en nuevas funcionalidades\n";
        $contenido .= "4. Optimizar consultas de base de datos\n";
        $contenido .= "5. Implementar monitoreo en tiempo real\n\n";

        $contenido .= "CONCLUSIONES\n";
        $contenido .= "================================================================\n";
        $contenido .= "El sistema cumple con las expectativas establecidas y mantiene un alto\n";
        $contenido .= "nivel de rendimiento. Se recomienda continuar con las mejoras planificadas.\n";
        $contenido .= "================================================================\n";

        // Generar nombre del archivo
        $timestamp = date('Y-m-d_H-i-s');
        $nombreArchivo = "reporte_global_{$timestamp}.{$formato}";

        // Guardar archivo
        $rutaArchivo = WRITEPATH . 'uploads/reportes/' . $nombreArchivo;
        if (!is_dir(dirname($rutaArchivo))) {
            mkdir(dirname($rutaArchivo), 0755, true);
        }
        
        if (file_put_contents($rutaArchivo, $contenido)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Reporte global generado exitosamente',
                'archivo' => $nombreArchivo
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al generar el reporte global'
            ]);
        }
    }

    public function descargarReporte($id = null)
    {
        if ($id) {
            // Descargar reporte específico por ID
            $rutaArchivo = WRITEPATH . 'uploads/reportes/reporte_' . $id . '.pdf';
        } else {
            // Descargar el último reporte
            $rutaArchivo = WRITEPATH . 'uploads/reportes/reporte_usuarios_' . date('Y-m-d') . '.pdf';
        }

        if (file_exists($rutaArchivo)) {
            return $this->response->download($rutaArchivo, null);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Archivo de reporte no encontrado'
            ]);
        }
    }

    public function testDescarga()
    {
        return "Test de descarga funcionando - " . date('Y-m-d H:i:s');
    }

    public function logs()
    {
        // Simular logs del sistema con todas las claves necesarias
        $logs = [
            [
                'id' => 1,
                'fecha' => '2025-08-19 21:00:36',
                'nivel' => 'INFO',
                'mensaje' => 'Usuario Super Admin accedió al sistema',
                'usuario' => 'superadmin@universidad.edu',
                'ip' => '192.168.1.100',
                'accion' => 'LOGIN',
                'archivo' => 'AuthController.php',
                'linea' => 45
            ],
            [
                'id' => 2,
                'fecha' => '2025-08-19 20:57:45',
                'nivel' => 'WARNING',
                'mensaje' => 'Intento de acceso fallido desde IP 192.168.1.101',
                'usuario' => 'usuario_desconocido',
                'ip' => '192.168.1.101',
                'accion' => 'LOGIN_FAILED',
                'archivo' => 'AuthController.php',
                'linea' => 52
            ],
            [
                'id' => 3,
                'fecha' => '2025-08-19 20:55:20',
                'nivel' => 'INFO',
                'mensaje' => 'Respaldo automático completado exitosamente',
                'usuario' => 'sistema',
                'ip' => 'localhost',
                'accion' => 'BACKUP',
                'archivo' => 'BackupService.php',
                'linea' => 78
            ],
            [
                'id' => 4,
                'fecha' => '2025-08-19 20:50:15',
                'nivel' => 'ERROR',
                'mensaje' => 'Error al conectar con base de datos',
                'usuario' => 'sistema',
                'ip' => 'localhost',
                'accion' => 'DB_ERROR',
                'archivo' => 'DatabaseManager.php',
                'linea' => 125
            ],
            [
                'id' => 5,
                'fecha' => '2025-08-19 20:45:30',
                'nivel' => 'INFO',
                'mensaje' => 'Usuario Admin TH creó nuevo empleado',
                'usuario' => 'admin@universidad.edu',
                'ip' => '192.168.1.102',
                'accion' => 'CREATE_EMPLOYEE',
                'archivo' => 'AdminTHController.php',
                'linea' => 89
            ],
            [
                'id' => 6,
                'fecha' => '2025-08-19 20:40:15',
                'nivel' => 'INFO',
                'mensaje' => 'Sistema iniciado correctamente',
                'usuario' => 'sistema',
                'ip' => 'localhost',
                'accion' => 'SYSTEM_START',
                'archivo' => 'Bootstrap.php',
                'linea' => 23
            ],
            [
                'id' => 7,
                'fecha' => '2025-08-19 20:35:22',
                'nivel' => 'WARNING',
                'mensaje' => 'Sesión de usuario expirada',
                'usuario' => 'docente@universidad.edu',
                'ip' => '192.168.1.103',
                'accion' => 'SESSION_EXPIRED',
                'archivo' => 'SessionManager.php',
                'linea' => 156
            ],
            [
                'id' => 8,
                'fecha' => '2025-08-19 20:30:10',
                'nivel' => 'INFO',
                'mensaje' => 'Reporte de nómina generado',
                'usuario' => 'admin@universidad.edu',
                'ip' => '192.168.1.102',
                'accion' => 'REPORT_GENERATED',
                'archivo' => 'NominaController.php',
                'linea' => 234
            ]
        ];

        $data = [
            'title' => 'Logs del Sistema - Super Administrador',
            'user' => [
                'nombres' => session()->get('nombres'),
                'apellidos' => session()->get('apellidos'),
                'rol' => session()->get('nombre_rol')
            ],
            'logs' => $logs
        ];
        return view('Roles/SuperAdministrador/logs', $data);
    }

    public function perfil()
    {
        $data = [
            'title' => 'Mi Perfil - Super Administrador',
            'user' => [
                'nombres' => session()->get('nombres'),
                'apellidos' => session()->get('apellidos'),
                'rol' => session()->get('nombre_rol')
            ]
        ];
        return view('Roles/SuperAdministrador/perfil', $data);
    }

    public function actualizarPerfil()
    {
        // Lógica para actualizar perfil
        return $this->response->setJSON(['success' => true]);
    }

    public function cuenta()
    {
        $data = [
            'title' => 'Configuración de Cuenta - Super Administrador',
            'user' => [
                'nombres' => session()->get('nombres'),
                'apellidos' => session()->get('apellidos'),
                'rol' => session()->get('nombre_rol')
            ]
        ];
        return view('Roles/SuperAdministrador/cuenta', $data);
    }

    public function cambiarPassword()
    {
        // Lógica para cambiar contraseña
        return $this->response->setJSON(['success' => true]);
    }

    public function configurarNotificaciones()
    {
        // Lógica para configurar notificaciones
        return $this->response->setJSON(['success' => true]);
    }

    public function configurarPrivacidad()
    {
        // Lógica para configurar privacidad
        return $this->response->setJSON(['success' => true]);
    }

    public function cerrarSesiones()
    {
        // Lógica para cerrar otras sesiones
        return $this->response->setJSON(['success' => true]);
    }

    public function crearUsuario()
    {
        // Lógica para crear usuario
        return $this->response->setJSON(['success' => true]);
    }

    public function toggleUserStatus()
    {
        // Lógica para cambiar estado de usuario
        return $this->response->setJSON(['success' => true]);
    }

    public function eliminarUsuario()
    {
        // Lógica para eliminar usuario
        return $this->response->setJSON(['success' => true]);
    }

    public function configuracionSistema()
    {
        $data = [
            'title' => 'Configuración del Sistema - Super Administrador',
            'user' => [
                'nombres' => session()->get('nombres'),
                'apellidos' => session()->get('apellidos'),
                'rol' => session()->get('nombre_rol')
            ]
        ];
        return view('Roles/SuperAdministrador/configuracion_sistema', $data);
    }
} 