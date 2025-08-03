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
        $data = [
            'title' => 'Gestión de Roles',
            'user' => [
                'nombres' => session()->get('nombres'),
                'apellidos' => session()->get('apellidos'),
                'rol' => session()->get('nombre_rol')
            ]
        ];
        
        return view('Roles/SuperAdministrador/roles', $data);
    }

    public function departamentos()
    {
        $data = [
            'title' => 'Gestión de Departamentos - Super Administrador',
            'user' => [
                'nombres' => session()->get('nombres'),
                'apellidos' => session()->get('apellidos'),
                'rol' => session()->get('nombre_rol')
            ]
        ];
        return view('Roles/SuperAdministrador/departamentos', $data);
    }

    public function configuracion()
    {
        $data = [
            'title' => 'Configuración del Sistema - Super Administrador',
            'user' => [
                'nombres' => session()->get('nombres'),
                'apellidos' => session()->get('apellidos'),
                'rol' => session()->get('nombre_rol')
            ]
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
        $data = [
            'title' => 'Logs del Sistema - Super Administrador',
            'user' => [
                'nombres' => session()->get('nombres'),
                'apellidos' => session()->get('apellidos'),
                'rol' => session()->get('nombre_rol')
            ]
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
} 