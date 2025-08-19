<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\UsuarioModel;

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
        try {
            // 1. Validar los datos de entrada
            $rules = [
                'identificador' => 'required',
                'password'      => 'required'
            ];

            if (!$this->validate($rules)) {
                return redirect()->back()->withInput()->with('error', 'Por favor complete todos los campos.');
            }

            // 2. Obtener datos del formulario
            $identifier = $this->request->getPost('identificador');
            $password = $this->request->getPost('password');

            // 3. Probar conexión a la base de datos
            $db = \Config\Database::connect();
            
            // 4. Buscar usuario directamente
            $builder = $db->table('usuarios u');
            $builder->select('u.id_usuario, u.cedula, u.email, u.id_rol, u.activo, u.password_hash, r.nombre_rol');
            $builder->join('roles r', 'r.id_rol = u.id_rol', 'left');
            $builder->groupStart();
            $builder->where('u.cedula', $identifier);
            $builder->orWhere('u.email', $identifier);
            $builder->groupEnd();
            $builder->where('u.activo', 1);
            
            $user = $builder->get()->getRowArray();

            // 5. Verificar credenciales
            if ($user && password_verify($password, $user['password_hash'])) {
                // Verificar si el usuario está activo
                if (!$user['activo']) {
                    return redirect()->back()->with('error', 'Su cuenta ha sido desactivada. Contacte al administrador.');
                }

                // 6. Establecer sesión
                $this->setSession($user);
                
                // 7. Redirigir según el rol
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
            return redirect()->back()->with('error', 'Error en el sistema. Por favor, intente nuevamente.');
        }
    }

    /**
     * Guarda los datos del usuario en la sesión.
     */
    private function setSession($user)
    {
        $sessionData = [
            'id_usuario'    => $user['id_usuario'],
            'cedula'        => $user['cedula'],
            'email'         => $user['email'],
            'id_rol'        => $user['id_rol'],
            'nombre_rol'    => $user['nombre_rol'],
            'nombres'       => 'Super', // Por ahora valores por defecto
            'apellidos'     => 'Administrador',
            'tipo_empleado' => 'ADMINISTRATIVO',
            'isLoggedIn'    => true,
            'login_time'    => time()
        ];

        session()->set($sessionData);
    }

    /**
     * Redirige al usuario según su rol
     */
    private function redirectByRole()
    {
        $roleId = session()->get('id_rol');
        
        switch ($roleId) {
            case 1: // SuperAdministrador
                return redirect()->to(base_url('index.php/super-admin/dashboard'));
            case 2: // AdministradorTalentoHumano
                return redirect()->to(base_url('index.php/admin-th/dashboard'));
            case 3: // Docente
                return redirect()->to(base_url('index.php/docente/dashboard'));
            default:
                return redirect()->to(base_url('index.php/dashboard'));
        }
    }
    
    /**
     * Cierra la sesión del usuario.
     */
    public function logout()
    {
        // Destruye toda la sesión
        session()->destroy();
        // Redirige al login
        return redirect()->to('/index.php/login');
    }

    /**
     * Muestra página de acceso denegado
     */
    public function accessDenied()
    {
        return view('auth/access_denied');
    }
}