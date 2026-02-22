<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\UsuarioModel;
use App\Models\SesionActivaModel;
use App\Models\LogSistemaModel;

class AuthController extends Controller
{
    public function index()
    {
        // Si ya está logueado, redirigir al dashboard correspondiente
        if (session()->get('isLoggedIn')) {
            return $this->redirectByRole();
        }
        return view('auth/login');
    }

    public function attemptLogin()
    {
        // 1. Obtener datos del formulario
        $identifier = $this->request->getPost('identificador'); // cédula o email
        $password = $this->request->getPost('password');
        
        // 2. Validar datos de entrada
        if (empty($identifier) || empty($password)) {
            return redirect()->back()->with('error', 'Por favor, complete todos los campos.');
        }
        
        try {
            // 3. Conectar a la base de datos
            $db = \Config\Database::connect();
            
            // 4. Buscar usuario en la tabla usuarios
            $builder = $db->table('usuarios u');
            $builder->select('u.id_usuario, u.cedula, u.email, u.id_rol, u.activo, u.password_hash, u.password_changed, r.nombre_rol as nombre_rol');
            $builder->join('roles r', 'r.id_rol = u.id_rol', 'left');
            $builder->groupStart();
            $builder->where('u.cedula', $identifier);
            $builder->orWhere('u.email', $identifier);
            $builder->groupEnd();
            $builder->where('u.activo', 1);
            
            $user = $builder->get()->getRowArray();
            
            // 4.1. Obtener información del empleado para todos los roles
            if ($user) {
                $empleadoBuilder = $db->table('empleados e');
                $empleadoBuilder->select('e.nombres, e.apellidos, e.tipo_empleado, e.departamento');
                $empleadoBuilder->where('e.id_usuario', $user['id_usuario']);
                $empleado = $empleadoBuilder->get()->getRowArray();
                
                if ($empleado) {
                    $user['nombres'] = $empleado['nombres'];
                    $user['apellidos'] = $empleado['apellidos'];
                    $user['tipo_empleado'] = $empleado['tipo_empleado'];
                    $user['departamento'] = $empleado['departamento'];
                } else {
                    // Valores por defecto si no tiene registro en empleados
                    if ($user['id_rol'] == 2) {
                        $user['nombres'] = 'Administrador';
                        $user['apellidos'] = 'Talento Humano';
                        $user['tipo_empleado'] = 'ADMIN_TH';
                        $user['departamento'] = 'Talento Humano';
                    } else {
                        $user['nombres'] = 'Empleado';
                        $user['apellidos'] = 'Usuario';
                        $user['tipo_empleado'] = 'EMPLEADO';
                        $user['departamento'] = 'Sin asignar';
                    }
                }
            }

            // 5. Verificar credenciales
            if ($user && password_verify($password, $user['password_hash'])) {
                // Verificar si el usuario está activo
                if ($user['activo'] != 1) {
                    return redirect()->back()->with('error', 'Su cuenta ha sido desactivada. Contacte al administrador.');
                }

                // 6. Establecer sesión
                $tokenSesion = $this->setSession($user);
                
                // 7. Registrar sesión activa
                $sesionModel = new SesionActivaModel();
                $sesionModel->crearSesion(
                    $user['id_usuario'],
                    $tokenSesion,
                    $this->request->getIPAddress(),
                    $this->request->getUserAgent()
                );
                
                // 8. Registrar log de inicio de sesión
                $logModel = new LogSistemaModel();
                $logModel->registrarLog(
                    $user['id_usuario'],
                    'LOGIN',
                    'AUTENTICACION',
                    'Usuario inició sesión exitosamente',
                    $this->request->getIPAddress()
                );
                
                // 9. Redirigir según el rol
                return $this->redirectByRole();

            } else {
                // Debug: mostrar información del usuario encontrado
                if ($user) {
                    log_message('error', 'Password verification failed for user: ' . $user['cedula']);
                    log_message('error', 'Password hash in DB: ' . $user['password_hash']);
                    log_message('error', 'Password provided: ' . $password);
                }
                return redirect()->back()->with('error', 'La cédula/correo o la contraseña son incorrectos.');
            }

        } catch (\Exception $e) {
            // En producción, no mostrar errores detallados
            log_message('error', 'Login error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error en el sistema. Por favor, intente nuevamente.');
        }
    }

    /**
     * Guarda los datos del usuario en la sesión.
     */
    private function setSession($user)
    {
        // Determinar el tipo de sidebar según el rol (solo 2 perfiles)
        $sidebarType = ($user['id_rol'] == 2) ? 'admin_th' : 'empleado';

        // Generar token único para la sesión
        $tokenSesion = bin2hex(random_bytes(32));

        $sessionData = [
            'id_usuario'      => $user['id_usuario'],
            'cedula'          => $user['cedula'],
            'email'           => $user['email'],
            'id_rol'          => $user['id_rol'],
            'nombre_rol'      => $user['nombre_rol'],
            'nombres'         => $user['nombres'] ?? 'Usuario',
            'apellidos'       => $user['apellidos'] ?? '',
            'tipo_empleado'   => $user['tipo_empleado'] ?? 'EMPLEADO',
            'departamento'    => $user['departamento'] ?? '',
            'password_changed' => $user['password_changed'] ?? 0,
            'sidebar_type'    => $sidebarType,
            'isLoggedIn'      => true,
            'login_time'      => time(),
            'token_sesion'    => $tokenSesion
        ];

        session()->set($sessionData);
        
        return $tokenSesion;
    }

    /**
     * Redirige al usuario según su rol
     */
    private function redirectByRole()
    {
        $roleId = session()->get('id_rol');
        
        if ($roleId == 2) {
            return redirect()->to(base_url('index.php/admin-th/dashboard'));
        }
        
        // Todos los demás (Empleado, rol 3) van al panel de empleado
        return redirect()->to(base_url('index.php/empleado/dashboard'));
    }
    
    /**
     * Cierra la sesión del usuario.
     */
    public function logout()
    {
        try {
            $idUsuario = session()->get('id_usuario');
            $tokenSesion = session()->get('token_sesion');
            
            if ($idUsuario && $tokenSesion) {
                // Cerrar sesión activa en la base de datos
                $sesionModel = new SesionActivaModel();
                $sesionModel->cerrarSesion($tokenSesion);
                
                // Registrar log de cierre de sesión
                $logModel = new LogSistemaModel();
                $logModel->registrarLog(
                    $idUsuario,
                    'LOGOUT',
                    'AUTENTICACION',
                    'Usuario cerró sesión',
                    $this->request->getIPAddress()
                );
            }
        } catch (\Exception $e) {
            log_message('error', 'Error en logout DB operations: ' . $e->getMessage());
        }
        
        // Destruye toda la sesión (siempre, incluso si hubo error en DB)
        session()->destroy();
        
        // Redirige al login
        return redirect()->to(base_url('index.php/login'))->with('success', 'Sesión cerrada correctamente');
    }

    /**
     * Cerrar todas las sesiones de un usuario excepto la actual
     */
    public function cerrarTodasLasSesiones()
    {
        $idUsuario = session()->get('id_usuario');
        $tokenActual = session()->get('token_sesion');
        
        if (!$idUsuario || !$tokenActual) {
            return redirect()->back()->with('error', 'Sesión no válida');
        }
        
        try {
            $sesionModel = new SesionActivaModel();
            $sesionModel->cerrarTodasLasSesiones($idUsuario, $tokenActual);
            
            // Registrar log
            $logModel = new LogSistemaModel();
            $logModel->registrarLog(
                $idUsuario,
                'CERRAR_TODAS_SESIONES',
                'SEGURIDAD',
                'Usuario cerró todas sus sesiones activas',
                $this->request->getIPAddress()
            );
            
            return redirect()->back()->with('success', 'Todas las sesiones han sido cerradas correctamente');
            
        } catch (\Exception $e) {
            log_message('error', 'Error cerrando sesiones: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al cerrar las sesiones');
        }
    }

    /**
     * Muestra página de acceso denegado
     */
    public function accessDenied()
    {
        return view('auth/access_denied');
    }
}