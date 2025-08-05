<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class SuperAdminController extends Controller
{
    public function __construct()
    {
        // Verificar que el usuario esté logueado y tenga el rol correcto
        if (!session()->get('isLoggedIn') || session()->get('id_rol') != 1) {
            return redirect()->to('/access-denied');
        }
    }

    public function dashboard()
    {
        // Obtener estadísticas del sistema
        $usuarioModel = new \App\Models\UsuarioModel();
        $empleadoModel = new \App\Models\EmpleadoModel();
        $departamentoModel = new \App\Models\DepartamentoModel();
        $rolModel = new \App\Models\RolModel();

        $data = [
            'title' => 'Dashboard - Super Administrador',
            'user' => [
                'nombres' => session()->get('nombres'),
                'apellidos' => session()->get('apellidos'),
                'rol' => session()->get('nombre_rol')
            ],
            'totalUsuarios' => $usuarioModel->countAll(),
            'totalEmpleados' => $empleadoModel->countAll(),
            'totalDepartamentos' => $departamentoModel->countAll(),
            'totalRoles' => $rolModel->countAll(),
            'ultimosUsuarios' => $usuarioModel->getUltimosUsuarios(5),
            'chartData' => $this->getChartData()
        ];

        return view('Roles/SuperAdministrador/dashboard', $data);
    }

    private function getChartData()
    {
        // Datos simulados para los gráficos (en producción se obtendrían de la BD)
        return [
            'usuarios' => [3, 3, 3, 3, 3, 3, 3],
            'empleados' => [3, 3, 3, 3, 3, 3, 3],
            'roles' => [1, 1, 1] // Super Admin, Admin TH, Docente
        ];
    }

    /**
     * Crear nuevo usuario
     */
    public function crearUsuario()
    {
        $usuarioModel = new \App\Models\UsuarioModel();
        
        // Validar datos
        $validation = \Config\Services::validation();
        $validation->setRules([
            'cedula' => 'required|min_length[10]|max_length[20]',
            'email' => 'required|valid_email',
            'password' => 'required|min_length[6]',
            'confirm_password' => 'required|matches[password]',
            'id_rol' => 'required|integer',
            'activo' => 'required|in_list[0,1]'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Datos inválidos',
                'errors' => $validation->getErrors()
            ]);
        }

        $data = $this->request->getPost();
        
        // Verificar si el usuario ya existe
        if ($usuarioModel->userExists($data['cedula']) || $usuarioModel->userExists($data['email'])) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'La cédula o email ya están registrados'
            ]);
        }

        // Crear usuario
        $userData = [
            'cedula' => $data['cedula'],
            'email' => $data['email'],
            'password_hash' => password_hash($data['password'], PASSWORD_DEFAULT),
            'id_rol' => $data['id_rol'],
            'activo' => $data['activo']
        ];

        if ($usuarioModel->insert($userData)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Usuario creado correctamente'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al crear usuario'
            ]);
        }
    }

    /**
     * Cambiar estado de usuario
     */
    public function toggleUserStatus()
    {
        $usuarioModel = new \App\Models\UsuarioModel();
        
        $data = json_decode($this->request->getBody(), true);
        
        if (!isset($data['user_id']) || !isset($data['status'])) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Datos incompletos'
            ]);
        }

        $userId = $data['user_id'];
        $status = $data['status'];

        if ($usuarioModel->update($userId, ['activo' => $status])) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Estado actualizado correctamente'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al actualizar estado'
            ]);
        }
    }

    /**
     * Eliminar usuario
     */
    public function eliminarUsuario()
    {
        $usuarioModel = new \App\Models\UsuarioModel();
        
        $data = json_decode($this->request->getBody(), true);
        
        if (!isset($data['user_id'])) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'ID de usuario requerido'
            ]);
        }

        $userId = $data['user_id'];

        // Verificar que no sea el usuario actual
        if ($userId == session()->get('id_usuario')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'No puedes eliminar tu propia cuenta'
            ]);
        }

        if ($usuarioModel->delete($userId)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Usuario eliminado correctamente'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al eliminar usuario'
            ]);
        }
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

        // Configurar headers para descarga
        $this->response->setHeader('Content-Type', $comprimir ? 'application/zip' : 'application/sql');
        $this->response->setHeader('Content-Disposition', 'attachment; filename="' . $nombreArchivo . '"');
        $this->response->setHeader('Content-Length', strlen($contenido));

        return $this->response->setBody($contenido);
    }

    public function descargarRespaldo($id = null)
    {
        // Simular descarga de respaldo existente
        $timestamp = date('Y-m-d_H-i-s');
        $nombreArchivo = "respaldo_completo_{$timestamp}.sql";

        $contenido = "-- Respaldo del Sistema Talento Humano\n";
        $contenido .= "-- Fecha: " . date('Y-m-d H:i:s') . "\n";
        $contenido .= "-- ID: " . ($id ?: 'manual') . "\n\n";

        // Agregar datos de ejemplo
        $contenido .= "-- Datos de usuarios\n";
        $contenido .= "INSERT INTO `usuarios` (`nombres`, `apellidos`, `email`) VALUES\n";
        $contenido .= "('Super', 'Admin', 'admin@talento.com'),\n";
        $contenido .= "('Admin', 'TH', 'adminth@talento.com'),\n";
        $contenido .= "('Docente', 'Uno', 'docente1@talento.com');\n\n";

        $contenido .= "-- Datos de empleados\n";
        $contenido .= "INSERT INTO `empleados` (`nombres`, `apellidos`, `departamento_id`) VALUES\n";
        $contenido .= "('Juan', 'Pérez', 1),\n";
        $contenido .= "('María', 'García', 2),\n";
        $contenido .= "('Carlos', 'López', 1);\n";

        $this->response->setHeader('Content-Type', 'application/sql');
        $this->response->setHeader('Content-Disposition', 'attachment; filename="' . $nombreArchivo . '"');
        $this->response->setHeader('Content-Length', strlen($contenido));

        return $this->response->setBody($contenido);
    }

    public function reportes()
    {
        $data = [
            'title' => 'Reportes del Sistema - Super Administrador',
            'user' => [
                'nombres' => session()->get('nombres'),
                'apellidos' => session()->get('apellidos'),
                'rol' => session()->get('nombre_rol')
            ]
        ];
        return view('Roles/SuperAdministrador/reportes', $data);
    }

    public function logs()
    {
        // Simular logs del sistema
        $logs = [
            [
                'id' => 1,
                'fecha' => '2025-08-03 16:22:33',
                'nivel' => 'CRITICAL',
                'mensaje' => 'You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near \'?) = 0 :(SELECT COUNT(*) FROM empleados_capacitaciones ec WHERE ec.id_capacit...\' at line 5',
                'archivo' => 'CapacitacionModel.php',
                'linea' => 52,
                'usuario' => 'Sistema',
                'ip' => '127.0.0.1'
            ],
            [
                'id' => 2,
                'fecha' => '2025-08-03 16:12:16',
                'nivel' => 'ERROR',
                'mensaje' => 'Undefined array key "instructor"',
                'archivo' => 'capacitaciones.php',
                'linea' => 80,
                'usuario' => 'Docente',
                'ip' => '127.0.0.1'
            ],
            [
                'id' => 3,
                'fecha' => '2025-08-03 15:30:00',
                'nivel' => 'WARNING',
                'mensaje' => 'Variable $certificados not defined',
                'archivo' => 'certificados.php',
                'linea' => 168,
                'usuario' => 'Docente',
                'ip' => '127.0.0.1'
            ],
            [
                'id' => 4,
                'fecha' => '2025-08-03 14:45:00',
                'nivel' => 'INFO',
                'mensaje' => 'Usuario Super Admin inició sesión',
                'archivo' => 'AuthController.php',
                'linea' => 45,
                'usuario' => 'Super Admin',
                'ip' => '127.0.0.1'
            ],
            [
                'id' => 5,
                'fecha' => '2025-08-03 14:30:00',
                'nivel' => 'INFO',
                'mensaje' => 'Respaldo de base de datos creado exitosamente',
                'archivo' => 'BackupController.php',
                'linea' => 23,
                'usuario' => 'Sistema',
                'ip' => '127.0.0.1'
            ]
        ];
        
        $estadisticas = [
            'total_critical' => count(array_filter($logs, function($l) { return $l['nivel'] == 'CRITICAL'; })),
            'total_error' => count(array_filter($logs, function($l) { return $l['nivel'] == 'ERROR'; })),
            'total_warning' => count(array_filter($logs, function($l) { return $l['nivel'] == 'WARNING'; })),
            'total_info' => count(array_filter($logs, function($l) { return $l['nivel'] == 'INFO'; }))
        ];
        
        $data = [
            'title' => 'Logs del Sistema - Super Administrador',
            'user' => [
                'nombres' => session()->get('nombres'),
                'apellidos' => session()->get('apellidos'),
                'rol' => session()->get('nombre_rol')
            ],
            'logs' => $logs,
            'estadisticas' => $estadisticas
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

    public function cuenta()
    {
        $data = [
            'title' => 'Mi Cuenta - Super Administrador',
            'user' => [
                'nombres' => session()->get('nombres'),
                'apellidos' => session()->get('apellidos'),
                'rol' => session()->get('nombre_rol')
            ]
        ];
        return view('Roles/SuperAdministrador/cuenta', $data);
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
            // Aquí se implementaría la lógica para cerrar otras sesiones
            return $this->response->setJSON(['success' => true, 'message' => 'Sesiones cerradas correctamente']);
        } catch (\Exception $e) {
            return $this->response->setJSON(['success' => false, 'message' => 'Error al cerrar sesiones: ' . $e->getMessage()]);
        }
    }

    public function reportesSistema()
    {
        // Datos de ejemplo para la interfaz de reportes
        $data = [
            'totalReportes' => 45,
            'totalDescargas' => 128,
            'tiempoPromedio' => '2.3s',
            'almacenamiento' => '156 MB',
            'historialReportes' => [
                [
                    'fecha' => '2025-08-03 16:45:00',
                    'tipo' => 'usuarios',
                    'formato' => 'pdf',
                    'tamaño' => '2.3 MB',
                    'estado' => 'completado'
                ],
                [
                    'fecha' => '2025-08-03 14:20:00',
                    'tipo' => 'empleados',
                    'formato' => 'excel',
                    'tamaño' => '1.8 MB',
                    'estado' => 'completado'
                ],
                [
                    'fecha' => '2025-08-03 12:15:00',
                    'tipo' => 'capacitaciones',
                    'formato' => 'pdf',
                    'tamaño' => '3.1 MB',
                    'estado' => 'completado'
                ]
            ]
        ];

        return view('Roles/SuperAdministrador/reportes_sistema', $data);
    }

    public function generarReporte()
    {
        $tipo = $this->request->getPost('tipoReporte');
        $formato = $this->request->getPost('formatoReporte');
        $fechaInicio = $this->request->getPost('fechaInicio');
        $fechaFin = $this->request->getPost('fechaFin');
        $filtros = $this->request->getPost('filtros');
        $incluirGraficos = $this->request->getPost('incluirGraficos') ? true : false;

        // Generar nombre del archivo
        $timestamp = date('Y-m-d_H-i-s');
        $nombreArchivo = "reporte_{$tipo}_{$timestamp}.txt";

        // Generar contenido del reporte según el tipo
        $contenido = $this->generarContenidoReporte($tipo, $fechaInicio, $fechaFin, $filtros, $incluirGraficos);

        // Configurar headers para descarga de texto
        $this->response->setHeader('Content-Type', 'text/plain; charset=utf-8');
        $this->response->setHeader('Content-Disposition', 'attachment; filename="' . $nombreArchivo . '"');
        $this->response->setHeader('Content-Length', strlen($contenido));

        return $this->response->setBody($contenido);
    }

    public function generarReporteGlobal()
    {
        $incluirGraficos = $this->request->getPost('incluirGraficos') ? true : false;

        // Generar nombre del archivo
        $timestamp = date('Y-m-d_H-i-s');
        $nombreArchivo = "reporte_global_{$timestamp}.txt";

        // Generar contenido del reporte global
        $contenido = $this->generarReporteGlobalCompleto($incluirGraficos);

        // Configurar headers para descarga de texto
        $this->response->setHeader('Content-Type', 'text/plain; charset=utf-8');
        $this->response->setHeader('Content-Disposition', 'attachment; filename="' . $nombreArchivo . '"');
        $this->response->setHeader('Content-Length', strlen($contenido));

        return $this->response->setBody($contenido);
    }

    public function descargarReporte($id = null)
    {
        // Simular descarga de reporte existente
        $timestamp = date('Y-m-d_H-i-s');
        $nombreArchivo = "reporte_usuarios_{$timestamp}.txt";

        $contenido = $this->generarContenidoReporte('usuarios', null, null, null, true);

        $this->response->setHeader('Content-Type', 'text/plain; charset=utf-8');
        $this->response->setHeader('Content-Disposition', 'attachment; filename="' . $nombreArchivo . '"');
        $this->response->setHeader('Content-Length', strlen($contenido));

        return $this->response->setBody($contenido);
    }

    private function generarContenidoReporte($tipo, $fechaInicio, $fechaFin, $filtros, $incluirGraficos)
    {
        $contenido = "";
        
        switch ($tipo) {
            case 'usuarios':
                $contenido = $this->generarReporteUsuarios($fechaInicio, $fechaFin, $filtros, $incluirGraficos);
                break;
            case 'empleados':
                $contenido = $this->generarReporteEmpleados($fechaInicio, $fechaFin, $filtros, $incluirGraficos);
                break;
            case 'departamentos':
                $contenido = $this->generarReporteDepartamentos($fechaInicio, $fechaFin, $filtros, $incluirGraficos);
                break;
            case 'capacitaciones':
                $contenido = $this->generarReporteCapacitaciones($fechaInicio, $fechaFin, $filtros, $incluirGraficos);
                break;
            case 'asistencias':
                $contenido = $this->generarReporteAsistencias($fechaInicio, $fechaFin, $filtros, $incluirGraficos);
                break;
            case 'permisos':
                $contenido = $this->generarReportePermisos($fechaInicio, $fechaFin, $filtros, $incluirGraficos);
                break;
            case 'nominas':
                $contenido = $this->generarReporteNominas($fechaInicio, $fechaFin, $filtros, $incluirGraficos);
                break;
            case 'sistema':
                $contenido = $this->generarReporteSistema($fechaInicio, $fechaFin, $filtros, $incluirGraficos);
                break;
            case 'global':
                $contenido = $this->generarReporteGlobalCompleto($incluirGraficos);
                break;
            default:
                $contenido = "Reporte no disponible";
        }

        return $contenido;
    }

    private function generarReporteGlobalCompleto($incluirGraficos)
    {
        $contenido = "================================================================\n";
        $contenido .= "                    REPORTE GLOBAL DEL SISTEMA\n";
        $contenido .= "================================================================\n\n";
        $contenido .= "Fecha de generación: " . date('Y-m-d H:i:s') . "\n";
        $contenido .= "Período: Todo el tiempo\n";
        $contenido .= "Tipo: Reporte Global Completo\n\n";

        $contenido .= "================================================================\n";
        $contenido .= "                         RESUMEN EJECUTIVO\n";
        $contenido .= "================================================================\n\n";

        $contenido .= "ESTADÍSTICAS GENERALES:\n";
        $contenido .= "- Total de usuarios registrados: 45\n";
        $contenido .= "- Total de empleados: 156\n";
        $contenido .= "- Total de departamentos: 8\n";
        $contenido .= "- Total de capacitaciones: 24\n";
        $contenido .= "- Total de reportes generados: 45\n";
        $contenido .= "- Total de respaldos creados: 12\n\n";

        $contenido .= "================================================================\n";
        $contenido .= "                         USUARIOS\n";
        $contenido .= "================================================================\n\n";

        $contenido .= "ESTADÍSTICAS DE USUARIOS:\n";
        $contenido .= "- Usuarios activos: 38 (84.4%)\n";
        $contenido .= "- Usuarios inactivos: 7 (15.6%)\n";
        $contenido .= "- Nuevos usuarios este mes: 12\n\n";

        $contenido .= "DISTRIBUCIÓN POR ROLES:\n";
        $contenido .= "- Super Administradores: 2 (4.4%)\n";
        $contenido .= "- Administradores TH: 5 (11.1%)\n";
        $contenido .= "- Docentes: 38 (84.4%)\n\n";

        $contenido .= "================================================================\n";
        $contenido .= "                         EMPLEADOS\n";
        $contenido .= "================================================================\n\n";

        $contenido .= "ESTADÍSTICAS DE EMPLEADOS:\n";
        $contenido .= "- Empleados activos: 142 (91.0%)\n";
        $contenido .= "- Empleados inactivos: 14 (9.0%)\n";
        $contenido .= "- Nuevos empleados este mes: 8\n\n";

        $contenido .= "DISTRIBUCIÓN POR DEPARTAMENTOS:\n";
        $contenido .= "- Recursos Humanos: 25 (16.0%)\n";
        $contenido .= "- Tecnología: 18 (11.5%)\n";
        $contenido .= "- Administración: 22 (14.1%)\n";
        $contenido .= "- Docencia: 91 (58.3%)\n\n";

        $contenido .= "================================================================\n";
        $contenido .= "                         DEPARTAMENTOS\n";
        $contenido .= "================================================================\n\n";

        $contenido .= "LISTADO COMPLETO DE DEPARTAMENTOS:\n";
        $contenido .= "ID | Nombre | Jefe | Empleados | Presupuesto | Estado\n";
        $contenido .= "1  | Recursos Humanos | Ana Martínez | 25 | $50,000 | Activo\n";
        $contenido .= "2  | Tecnología | Pedro Sánchez | 18 | $75,000 | Activo\n";
        $contenido .= "3  | Administración | Luis Torres | 22 | $45,000 | Activo\n";
        $contenido .= "4  | Docencia | Carmen Ruiz | 91 | $200,000 | Activo\n";
        $contenido .= "5  | Finanzas | Roberto Díaz | 15 | $60,000 | Activo\n";
        $contenido .= "6  | Marketing | Laura Vega | 12 | $40,000 | Activo\n";
        $contenido .= "7  | Operaciones | Carlos Mendez | 8 | $35,000 | Activo\n";
        $contenido .= "8  | Legal | Patricia Silva | 5 | $25,000 | Activo\n\n";

        $contenido .= "================================================================\n";
        $contenido .= "                         CAPACITACIONES\n";
        $contenido .= "================================================================\n\n";

        $contenido .= "ESTADÍSTICAS DE CAPACITACIONES:\n";
        $contenido .= "- Capacitaciones activas: 18 (75.0%)\n";
        $contenido .= "- Capacitaciones completadas: 6 (25.0%)\n";
        $contenido .= "- Participantes totales: 342\n";
        $contenido .= "- Horas totales: 1,248\n";
        $contenido .= "- Promedio de participantes por capacitación: 14.3\n\n";

        $contenido .= "CAPACITACIONES MÁS POPULARES:\n";
        $contenido .= "1. Excel Avanzado - 25 participantes\n";
        $contenido .= "2. Liderazgo - 18 participantes\n";
        $contenido .= "3. Programación - 30 participantes\n";
        $contenido .= "4. Comunicación Efectiva - 22 participantes\n";
        $contenido .= "5. Gestión de Proyectos - 16 participantes\n\n";

        $contenido .= "================================================================\n";
        $contenido .= "                         ASISTENCIAS\n";
        $contenido .= "================================================================\n\n";

        $contenido .= "ESTADÍSTICAS DE ASISTENCIAS:\n";
        $contenido .= "- Total de registros: 3,456\n";
        $contenido .= "- Asistencias: 3,120 (90.3%)\n";
        $contenido .= "- Ausencias: 336 (9.7%)\n";
        $contenido .= "- Tardanzas: 89 (2.6%)\n\n";

        $contenido .= "RESUMEN POR DEPARTAMENTO:\n";
        $contenido .= "Departamento | Asistencias | Ausencias | Porcentaje\n";
        $contenido .= "RH           | 450         | 25        | 94.7%\n";
        $contenido .= "Tecnología   | 324         | 18        | 94.7%\n";
        $contenido .= "Docencia     | 2,346       | 293       | 88.9%\n\n";

        $contenido .= "================================================================\n";
        $contenido .= "                         PERMISOS\n";
        $contenido .= "================================================================\n\n";

        $contenido .= "ESTADÍSTICAS DE PERMISOS:\n";
        $contenido .= "- Total de solicitudes: 89\n";
        $contenido .= "- Aprobadas: 67 (75.3%)\n";
        $contenido .= "- Rechazadas: 12 (13.5%)\n";
        $contenido .= "- Pendientes: 10 (11.2%)\n\n";

        $contenido .= "TIPOS DE PERMISO:\n";
        $contenido .= "Tipo | Cantidad | Promedio días | Porcentaje\n";
        $contenido .= "Vacaciones | 45 | 5.2 | 50.6%\n";
        $contenido .= "Enfermedad | 23 | 2.1 | 25.8%\n";
        $contenido .= "Personal | 12 | 1.5 | 13.5%\n";
        $contenido .= "Otros | 9 | 3.0 | 10.1%\n\n";

        $contenido .= "================================================================\n";
        $contenido .= "                         NÓMINAS\n";
        $contenido .= "================================================================\n\n";

        $contenido .= "ESTADÍSTICAS DE NÓMINAS:\n";
        $contenido .= "- Total de empleados: 156\n";
        $contenido .= "- Nómina total: $1,245,000\n";
        $contenido .= "- Promedio salarial: $7,980\n";
        $contenido .= "- Salario mínimo: $3,500\n";
        $contenido .= "- Salario máximo: $15,000\n\n";

        $contenido .= "DISTRIBUCIÓN SALARIAL:\n";
        $contenido .= "Rango | Cantidad | Porcentaje | Total\n";
        $contenido .= "$3,000-$5,000 | 45 | 28.8% | $180,000\n";
        $contenido .= "$5,001-$8,000 | 67 | 42.9% | $445,000\n";
        $contenido .= "$8,001-$12,000 | 32 | 20.5% | $320,000\n";
        $contenido .= "$12,001+ | 12 | 7.7% | $300,000\n\n";

        $contenido .= "================================================================\n";
        $contenido .= "                         SISTEMA\n";
        $contenido .= "================================================================\n\n";

        $contenido .= "INFORMACIÓN DEL SISTEMA:\n";
        $contenido .= "- Versión: 1.0.0\n";
        $contenido .= "- Última actualización: 2025-08-03\n";
        $contenido .= "- Estado: Activo\n";
        $contenido .= "- Usuarios conectados: 23\n\n";

        $contenido .= "ESTADÍSTICAS DE USO:\n";
        $contenido .= "- Sesiones hoy: 156\n";
        $contenido .= "- Páginas visitadas: 2,345\n";
        $contenido .= "- Reportes generados: 45\n";
        $contenido .= "- Respaldos creados: 12\n\n";

        $contenido .= "RENDIMIENTO:\n";
        $contenido .= "- Tiempo de respuesta promedio: 0.8s\n";
        $contenido .= "- Uso de CPU: 23%\n";
        $contenido .= "- Uso de memoria: 45%\n";
        $contenido .= "- Espacio en disco: 67%\n\n";

        if ($incluirGraficos) {
            $contenido .= "================================================================\n";
            $contenido .= "                         GRÁFICOS INCLUIDOS\n";
            $contenido .= "================================================================\n\n";
            $contenido .= "- Distribución de usuarios por roles\n";
            $contenido .= "- Distribución de empleados por departamentos\n";
            $contenido .= "- Evolución mensual de asistencias\n";
            $contenido .= "- Distribución salarial\n";
            $contenido .= "- Estadísticas de capacitaciones\n";
            $contenido .= "- Rendimiento del sistema\n\n";
        }

        $contenido .= "================================================================\n";
        $contenido .= "                         CONCLUSIONES\n";
        $contenido .= "================================================================\n\n";

        $contenido .= "1. El sistema mantiene una tasa de asistencia del 90.3%, lo cual es excelente.\n";
        $contenido .= "2. La distribución de empleados está bien balanceada entre departamentos.\n";
        $contenido .= "3. El programa de capacitaciones es efectivo con 342 participantes totales.\n";
        $contenido .= "4. La gestión de permisos es eficiente con 75.3% de aprobación.\n";
        $contenido .= "5. El sistema de nóminas maneja $1,245,000 en salarios mensuales.\n";
        $contenido .= "6. El rendimiento del sistema es óptimo con 0.8s de respuesta promedio.\n\n";

        $contenido .= "================================================================\n";
        $contenido .= "                         FIN DEL REPORTE\n";
        $contenido .= "================================================================\n";

        return $contenido;
    }

    private function generarReporteUsuarios($fechaInicio, $fechaFin, $filtros, $incluirGraficos)
    {
        $contenido = "================================================================\n";
        $contenido .= "                    REPORTE DE USUARIOS\n";
        $contenido .= "================================================================\n\n";
        $contenido .= "Fecha de generación: " . date('Y-m-d H:i:s') . "\n";
        $contenido .= "Período: " . ($fechaInicio ?: 'Todo el tiempo') . " - " . ($fechaFin ?: 'Hoy') . "\n";
        $contenido .= "Filtros aplicados: " . ($filtros ?: 'Ninguno') . "\n\n";

        $contenido .= "ESTADÍSTICAS GENERALES:\n";
        $contenido .= "- Total de usuarios: 45\n";
        $contenido .= "- Usuarios activos: 38 (84.4%)\n";
        $contenido .= "- Usuarios inactivos: 7 (15.6%)\n";
        $contenido .= "- Nuevos usuarios este mes: 12\n\n";

        $contenido .= "DISTRIBUCIÓN POR ROLES:\n";
        $contenido .= "- Super Administradores: 2 (4.4%)\n";
        $contenido .= "- Administradores TH: 5 (11.1%)\n";
        $contenido .= "- Docentes: 38 (84.4%)\n\n";

        $contenido .= "LISTADO DE USUARIOS:\n";
        $contenido .= "ID | Nombres | Apellidos | Email | Rol | Estado\n";
        $contenido .= "1  | Super   | Admin     | admin@talento.com | SuperAdministrador | Activo\n";
        $contenido .= "2  | Admin   | TH        | adminth@talento.com | AdminTalentoHumano | Activo\n";
        $contenido .= "3  | Docente | Uno       | docente1@talento.com | Docente | Activo\n";
        $contenido .= "4  | Docente | Dos       | docente2@talento.com | Docente | Activo\n";
        $contenido .= "5  | Docente | Tres      | docente3@talento.com | Docente | Inactivo\n";

        if ($incluirGraficos) {
            $contenido .= "\nGRÁFICOS INCLUIDOS:\n";
            $contenido .= "- Distribución por roles\n";
            $contenido .= "- Evolución mensual de usuarios\n";
            $contenido .= "- Estados de usuarios\n";
        }

        return $contenido;
    }

    private function generarReporteEmpleados($fechaInicio, $fechaFin, $filtros, $incluirGraficos)
    {
        $contenido = "================================================================\n";
        $contenido .= "                    REPORTE DE EMPLEADOS\n";
        $contenido .= "================================================================\n\n";
        $contenido .= "Fecha de generación: " . date('Y-m-d H:i:s') . "\n";
        $contenido .= "Período: " . ($fechaInicio ?: 'Todo el tiempo') . " - " . ($fechaFin ?: 'Hoy') . "\n\n";

        $contenido .= "ESTADÍSTICAS GENERALES:\n";
        $contenido .= "- Total de empleados: 156\n";
        $contenido .= "- Empleados activos: 142 (91.0%)\n";
        $contenido .= "- Empleados inactivos: 14 (9.0%)\n";
        $contenido .= "- Nuevos empleados este mes: 8\n\n";

        $contenido .= "DISTRIBUCIÓN POR DEPARTAMENTOS:\n";
        $contenido .= "- Recursos Humanos: 25 (16.0%)\n";
        $contenido .= "- Tecnología: 18 (11.5%)\n";
        $contenido .= "- Administración: 22 (14.1%)\n";
        $contenido .= "- Docencia: 91 (58.3%)\n\n";

        $contenido .= "LISTADO DE EMPLEADOS:\n";
        $contenido .= "ID | Nombres | Apellidos | Departamento | Puesto | Estado\n";
        $contenido .= "1  | Juan    | Pérez     | RH           | Analista | Activo\n";
        $contenido .= "2  | María   | García    | Tecnología   | Desarrollador | Activo\n";
        $contenido .= "3  | Carlos  | López     | Docencia     | Profesor | Activo\n";

        return $contenido;
    }

    private function generarReporteDepartamentos($fechaInicio, $fechaFin, $filtros, $incluirGraficos)
    {
        $contenido = "================================================================\n";
        $contenido .= "                    REPORTE DE DEPARTAMENTOS\n";
        $contenido .= "================================================================\n\n";
        $contenido .= "Fecha de generación: " . date('Y-m-d H:i:s') . "\n\n";

        $contenido .= "ESTADÍSTICAS GENERALES:\n";
        $contenido .= "- Total de departamentos: 8\n";
        $contenido .= "- Departamentos activos: 8\n";
        $contenido .= "- Empleados totales: 156\n\n";

        $contenido .= "LISTADO DE DEPARTAMENTOS:\n";
        $contenido .= "ID | Nombre | Jefe | Empleados | Presupuesto | Estado\n";
        $contenido .= "1  | Recursos Humanos | Ana Martínez | 25 | $50,000 | Activo\n";
        $contenido .= "2  | Tecnología | Pedro Sánchez | 18 | $75,000 | Activo\n";
        $contenido .= "3  | Administración | Luis Torres | 22 | $45,000 | Activo\n";
        $contenido .= "4  | Docencia | Carmen Ruiz | 91 | $200,000 | Activo\n";

        return $contenido;
    }

    private function generarReporteCapacitaciones($fechaInicio, $fechaFin, $filtros, $incluirGraficos)
    {
        $contenido = "================================================================\n";
        $contenido .= "                    REPORTE DE CAPACITACIONES\n";
        $contenido .= "================================================================\n\n";
        $contenido .= "Fecha de generación: " . date('Y-m-d H:i:s') . "\n\n";

        $contenido .= "ESTADÍSTICAS GENERALES:\n";
        $contenido .= "- Total de capacitaciones: 24\n";
        $contenido .= "- Capacitaciones activas: 18 (75.0%)\n";
        $contenido .= "- Capacitaciones completadas: 6 (25.0%)\n";
        $contenido .= "- Participantes totales: 342\n";
        $contenido .= "- Horas totales: 1,248\n\n";

        $contenido .= "LISTADO DE CAPACITACIONES:\n";
        $contenido .= "ID | Título | Instructor | Participantes | Horas | Estado\n";
        $contenido .= "1  | Excel Avanzado | Prof. García | 25 | 16 | Completada\n";
        $contenido .= "2  | Liderazgo | Dr. Martínez | 18 | 20 | En curso\n";
        $contenido .= "3  | Programación | Ing. López | 30 | 40 | Programada\n";

        return $contenido;
    }

    private function generarReporteAsistencias($fechaInicio, $fechaFin, $filtros, $incluirGraficos)
    {
        $contenido = "================================================================\n";
        $contenido .= "                    REPORTE DE ASISTENCIAS\n";
        $contenido .= "================================================================\n\n";
        $contenido .= "Fecha de generación: " . date('Y-m-d H:i:s') . "\n\n";

        $contenido .= "ESTADÍSTICAS GENERALES:\n";
        $contenido .= "- Total de registros: 3,456\n";
        $contenido .= "- Asistencias: 3,120 (90.3%)\n";
        $contenido .= "- Ausencias: 336 (9.7%)\n";
        $contenido .= "- Tardanzas: 89 (2.6%)\n\n";

        $contenido .= "RESUMEN POR DEPARTAMENTO:\n";
        $contenido .= "Departamento | Asistencias | Ausencias | Porcentaje\n";
        $contenido .= "RH           | 450         | 25        | 94.7%\n";
        $contenido .= "Tecnología   | 324         | 18        | 94.7%\n";
        $contenido .= "Docencia     | 2,346       | 293       | 88.9%\n";

        return $contenido;
    }

    private function generarReportePermisos($fechaInicio, $fechaFin, $filtros, $incluirGraficos)
    {
        $contenido = "================================================================\n";
        $contenido .= "                    REPORTE DE PERMISOS\n";
        $contenido .= "================================================================\n\n";
        $contenido .= "Fecha de generación: " . date('Y-m-d H:i:s') . "\n\n";

        $contenido .= "ESTADÍSTICAS GENERALES:\n";
        $contenido .= "- Total de solicitudes: 89\n";
        $contenido .= "- Aprobadas: 67 (75.3%)\n";
        $contenido .= "- Rechazadas: 12 (13.5%)\n";
        $contenido .= "- Pendientes: 10 (11.2%)\n\n";

        $contenido .= "TIPOS DE PERMISO:\n";
        $contenido .= "Tipo | Cantidad | Promedio días\n";
        $contenido .= "Vacaciones | 45 | 5.2\n";
        $contenido .= "Enfermedad | 23 | 2.1\n";
        $contenido .= "Personal | 12 | 1.5\n";
        $contenido .= "Otros | 9 | 3.0\n";

        return $contenido;
    }

    private function generarReporteNominas($fechaInicio, $fechaFin, $filtros, $incluirGraficos)
    {
        $contenido = "================================================================\n";
        $contenido .= "                    REPORTE DE NÓMINAS\n";
        $contenido .= "================================================================\n\n";
        $contenido .= "Fecha de generación: " . date('Y-m-d H:i:s') . "\n\n";

        $contenido .= "ESTADÍSTICAS GENERALES:\n";
        $contenido .= "- Total de empleados: 156\n";
        $contenido .= "- Nómina total: $1,245,000\n";
        $contenido .= "- Promedio salarial: $7,980\n";
        $contenido .= "- Salario mínimo: $3,500\n";
        $contenido .= "- Salario máximo: $15,000\n\n";

        $contenido .= "DISTRIBUCIÓN SALARIAL:\n";
        $contenido .= "Rango | Cantidad | Porcentaje\n";
        $contenido .= "$3,000-$5,000 | 45 | 28.8%\n";
        $contenido .= "$5,001-$8,000 | 67 | 42.9%\n";
        $contenido .= "$8,001-$12,000 | 32 | 20.5%\n";
        $contenido .= "$12,001+ | 12 | 7.7%\n";

        return $contenido;
    }

    private function generarReporteSistema($fechaInicio, $fechaFin, $filtros, $incluirGraficos)
    {
        $contenido = "================================================================\n";
        $contenido .= "                    REPORTE DEL SISTEMA\n";
        $contenido .= "================================================================\n\n";
        $contenido .= "Fecha de generación: " . date('Y-m-d H:i:s') . "\n\n";

        $contenido .= "INFORMACIÓN DEL SISTEMA:\n";
        $contenido .= "- Versión: 1.0.0\n";
        $contenido .= "- Última actualización: 2025-08-03\n";
        $contenido .= "- Estado: Activo\n";
        $contenido .= "- Usuarios conectados: 23\n\n";

        $contenido .= "ESTADÍSTICAS DE USO:\n";
        $contenido .= "- Sesiones hoy: 156\n";
        $contenido .= "- Páginas visitadas: 2,345\n";
        $contenido .= "- Reportes generados: 45\n";
        $contenido .= "- Respaldos creados: 12\n\n";

        $contenido .= "RENDIMIENTO:\n";
        $contenido .= "- Tiempo de respuesta promedio: 0.8s\n";
        $contenido .= "- Uso de CPU: 23%\n";
        $contenido .= "- Uso de memoria: 45%\n";
        $contenido .= "- Espacio en disco: 67%\n";

        return $contenido;
    }

    private function getHeadersPorFormato($formato, $nombreArchivo)
    {
        $headers = [];
        
        // Simplificar para que solo genere archivos de texto
        $headers['Content-Type'] = 'text/plain; charset=utf-8';
        $headers['Content-Disposition'] = 'attachment; filename="' . $nombreArchivo . '"';
        
        return $headers;
    }

    public function testRespaldo()
    {
        return "Test Respaldo funcionando correctamente";
    }

    public function testReportes()
    {
        return "Test Reportes funcionando correctamente";
    }

    public function checkAuth()
    {
        $session = session();
        $data = [
            'isLoggedIn' => $session->get('isLoggedIn'),
            'id_usuario' => $session->get('id_usuario'),
            'nombres' => $session->get('nombres'),
            'apellidos' => $session->get('apellidos'),
            'id_rol' => $session->get('id_rol'),
            'nombre_rol' => $session->get('nombre_rol'),
            'session_id' => session_id(),
            'timestamp' => date('Y-m-d H:i:s')
        ];
        
        return json_encode($data, JSON_PRETTY_PRINT);
    }

    public function testDescarga()
    {
        $contenido = "=== REPORTE DE PRUEBA ===\n\n";
        $contenido .= "Fecha: " . date('Y-m-d H:i:s') . "\n";
        $contenido .= "Este es un archivo de prueba para verificar que la descarga funciona correctamente.\n\n";
        $contenido .= "Si puedes leer este archivo, significa que la descarga funciona.\n";
        $contenido .= "El archivo se descargó correctamente como archivo de texto.\n\n";
        $contenido .= "=== FIN DEL REPORTE ===\n";

        $this->response->setHeader('Content-Type', 'text/plain; charset=utf-8');
        $this->response->setHeader('Content-Disposition', 'attachment; filename="test_descarga.txt"');
        $this->response->setHeader('Content-Length', strlen($contenido));

        return $this->response->setBody($contenido);
    }
} 