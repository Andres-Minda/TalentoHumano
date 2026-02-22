<?php

namespace App\Controllers\Empleado;

use App\Models\EmpleadoModel;
use App\Models\TituloAcademicoModel;
use App\Models\CapacitacionEmpleadoModel;
use App\Models\UsuarioModel;
use App\Models\InasistenciaModel;
use App\Models\NotificacionModel;
use CodeIgniter\Controller;

class EmpleadoController extends Controller
{
    protected $empleadoModel;
    protected $tituloAcademicoModel;
    protected $capacitacionModel;
    protected $usuarioModel;
    protected $inasistenciaModel;
    protected $notificacionModel;

    public function __construct()
    {
        $this->empleadoModel = new EmpleadoModel();
        $this->tituloAcademicoModel = new TituloAcademicoModel();
        $this->capacitacionModel = new CapacitacionEmpleadoModel();
        $this->usuarioModel = new UsuarioModel();
        $this->inasistenciaModel = new InasistenciaModel();
        $this->notificacionModel = new NotificacionModel();
    }

    /**
     * Dashboard del empleado
     */
    public function dashboard()
    {
        // Obtener datos del empleado desde la sesión
        $userId = session()->get('id_usuario');
        
        // Cargar modelo de empleado
        $empleadoModel = new \App\Models\EmpleadoModel();
        $empleado = $empleadoModel->getEmpleadoParaDashboard($userId);
        
        // Datos del usuario para la vista
        $user = [
            'nombres' => session()->get('nombres'),
            'apellidos' => session()->get('apellidos'),
            'rol' => session()->get('nombre_rol'),
            'email' => session()->get('email'),
            'cedula' => session()->get('cedula')
        ];
        
        // Descripción del dashboard según el tipo de empleado
        $descripcionDashboard = $this->getDescripcionDashboard(session()->get('tipo_empleado'));
        
        // Estadísticas básicas (simuladas por ahora)
        $estadisticas = [
            'total_capacitaciones' => 3,
            'total_documentos' => 8,
            'total_certificados' => 5,
            'total_solicitudes' => 2
        ];

        $data = [
            'titulo' => 'Dashboard Empleado',
            'user' => $user,
            'empleado' => $empleado,
            'descripcionDashboard' => $descripcionDashboard,
            'estadisticas' => $estadisticas
        ];

        return view('Roles/Empleado/dashboard', $data);
    }

    /**
     * Obtener descripción del dashboard según el tipo de empleado
     */
    private function getDescripcionDashboard($tipoEmpleado)
    {
        switch ($tipoEmpleado) {
            case 'DOCENTE':
                return 'Gestiona tus capacitaciones, documentos académicos y evaluaciones profesionales.';
            case 'ADMINISTRATIVO':
                return 'Accede a tus capacitaciones, documentos y solicitudes administrativas.';
            case 'DIRECTIVO':
                return 'Revisa tu desarrollo profesional, capacitaciones y evaluaciones de liderazgo.';
            case 'AUXILIAR':
                return 'Consulta tus capacitaciones, documentos y solicitudes de apoyo.';
            default:
                return 'Bienvenido al sistema de gestión de talento humano.';
        }
    }

    /**
     * Mi perfil
     */
    public function miPerfil()
    {
        // Obtener datos del empleado desde la sesión
        $userId = session()->get('id_usuario');
        
        // Cargar modelo de empleado
        $empleadoModel = new \App\Models\EmpleadoModel();
        $empleado = $empleadoModel->getEmpleadoParaDashboard($userId);
        
        // Datos del usuario para la vista
        $user = [
            'nombres' => session()->get('nombres'),
            'apellidos' => session()->get('apellidos'),
            'rol' => session()->get('nombre_rol'),
            'email' => session()->get('email'),
            'cedula' => session()->get('cedula')
        ];

        $data = [
            'titulo' => 'Mi Perfil',
            'user' => $user,
            'empleado' => $empleado
        ];

        return view('Roles/Empleado/mi_perfil', $data);
    }

    /**
     * Capacitaciones del empleado
     */
    public function capacitaciones()
    {
        $data = [
            'titulo' => 'Mis Capacitaciones',
            'usuario' => [
                'nombres' => session()->get('nombres'),
                'apellidos' => session()->get('apellidos'),
                'rol' => session()->get('nombre_rol')
            ]
        ];

        return view('Roles/Empleado/capacitaciones', $data);
    }

    /**
     * Títulos académicos del empleado
     */
    public function titulosAcademicos()
    {
        $data = [
            'titulo' => 'Mis Títulos Académicos',
            'usuario' => [
                'nombres' => session()->get('nombres'),
                'apellidos' => session()->get('apellidos'),
                'rol' => session()->get('nombre_rol')
            ]
        ];

        return view('Roles/Empleado/titulos_academicos', $data);
    }

    /**
     * Evaluaciones del empleado
     */
    public function evaluaciones()
    {
        $data = [
            'titulo' => 'Mis Evaluaciones',
            'usuario' => [
                'nombres' => session()->get('nombres'),
                'apellidos' => session()->get('apellidos'),
                'rol' => session()->get('nombre_rol')
            ]
        ];

        return view('Roles/Empleado/evaluaciones', $data);
    }

    /**
     * Inasistencias del empleado
     */
    public function inasistencias()
    {
        $data = [
            'titulo' => 'Mis Inasistencias',
            'usuario' => [
                'nombres' => session()->get('nombres'),
                'apellidos' => session()->get('apellidos'),
                'rol' => session()->get('nombre_rol')
            ]
        ];

        return view('Roles/Empleado/inasistencias/dashboard', $data);
    }

    /**
     * Solicitudes de capacitación del empleado
     */
    public function solicitudesCapacitacion()
    {
        $data = [
            'titulo' => 'Mis Solicitudes de Capacitación',
            'usuario' => [
                'nombres' => session()->get('nombres'),
                'apellidos' => session()->get('apellidos'),
                'rol' => session()->get('nombre_rol')
            ]
        ];

        return view('Roles/Empleado/solicitudes_capacitacion', $data);
    }

    /**
     * Permisos y vacaciones del empleado
     */
    public function permisosVacaciones()
    {
        $data = [
            'titulo' => 'Mis Permisos y Vacaciones',
            'usuario' => [
                'nombres' => session()->get('nombres'),
                'apellidos' => session()->get('apellidos'),
                'rol' => session()->get('nombre_rol')
            ]
        ];

        return view('Roles/Empleado/permisos_vacaciones', $data);
    }

    /**
     * Competencias del empleado
     */
    public function competencias()
    {
        $data = [
            'titulo' => 'Mis Competencias',
            'usuario' => [
                'nombres' => session()->get('nombres'),
                'apellidos' => session()->get('apellidos'),
                'rol' => session()->get('nombre_rol')
            ]
        ];

        return view('Roles/Empleado/competencias', $data);
    }

    /**
     * Asistencias del empleado
     */
    public function asistencias()
    {
        $data = [
            'titulo' => 'Mis Asistencias',
            'usuario' => [
                'nombres' => session()->get('nombres'),
                'apellidos' => session()->get('apellidos'),
                'rol' => session()->get('nombre_rol')
            ]
        ];

        return view('Roles/Empleado/asistencias', $data);
    }

    /**
     * Documentos del empleado
     */
    public function documentos()
    {
        $data = [
            'titulo' => 'Mis Documentos',
            'usuario' => [
                'nombres' => session()->get('nombres'),
                'apellidos' => session()->get('apellidos'),
                'rol' => session()->get('nombre_rol')
            ]
        ];

        return view('Roles/Empleado/documentos', $data);
    }

    /**
     * Solicitudes generales del empleado
     */
    public function solicitudesGenerales()
    {
        $data = [
            'titulo' => 'Mis Solicitudes Generales',
            'usuario' => [
                'nombres' => session()->get('nombres'),
                'apellidos' => session()->get('apellidos'),
                'rol' => session()->get('nombre_rol')
            ]
        ];

        return view('Roles/Empleado/solicitudes_generales', $data);
    }

    /**
     * Cambiar contraseña del empleado
     */
    public function cambiarPassword()
    {
        if (!$this->request->is('post')) {
            return $this->response->setJSON(['success' => false, 'message' => 'Método no permitido']);
        }

        try {
            $datos = $this->request->getPost();
            $userId = session()->get('id_usuario');
            
            // Validar datos
            if (empty($datos['password_actual']) || empty($datos['password_nuevo']) || empty($datos['password_confirmar'])) {
                return $this->response->setJSON(['success' => false, 'message' => 'Todos los campos son obligatorios']);
            }
            
            if ($datos['password_nuevo'] !== $datos['password_confirmar']) {
                return $this->response->setJSON(['success' => false, 'message' => 'Las contraseñas nuevas no coinciden']);
            }
            
            if (strlen($datos['password_nuevo']) < 6) {
                return $this->response->setJSON(['success' => false, 'message' => 'La contraseña debe tener al menos 6 caracteres']);
            }
            
            // Conectar a la base de datos
            $db = \Config\Database::connect();
            
            // Obtener usuario actual
            $usuario = $db->table('usuarios')->where('id_usuario', $userId)->get()->getRowArray();
            
            if (!$usuario) {
                return $this->response->setJSON(['success' => false, 'message' => 'Usuario no encontrado']);
            }
            
            // Verificar contraseña actual
            if (!password_verify($datos['password_actual'], $usuario['password_hash'])) {
                return $this->response->setJSON(['success' => false, 'message' => 'La contraseña actual es incorrecta']);
            }
            
            // Actualizar contraseña y marcar como cambiada
            $nuevaPasswordHash = password_hash($datos['password_nuevo'], PASSWORD_DEFAULT);
            
            $db->table('usuarios')->where('id_usuario', $userId)->update([
                'password_hash' => $nuevaPasswordHash,
                'password_changed' => 1
            ]);
            
            // Actualizar sesión
            session()->set('password_changed', 1);
            
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Contraseña cambiada exitosamente'
            ]);
            
        } catch (\Exception $e) {
            log_message('error', 'Error al cambiar contraseña: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al cambiar contraseña: ' . $e->getMessage()
            ]);
        }
    }
    
    /**
     * Acceso rápido del empleado
     */
    public function accesoRapido()
    {
        $data = [
            'titulo' => 'Acceso Rápido',
            'usuario' => [
                'nombres' => session()->get('nombres'),
                'apellidos' => session()->get('apellidos'),
                'rol' => session()->get('nombre_rol')
            ]
        ];

        return view('Roles/Empleado/acceso_rapido', $data);
    }

    /**
     * Actualizar perfil del empleado
     */
    public function actualizarPerfil()
    {
        if (!$this->request->is('post')) {
            return redirect()->back()->with('error', 'Método no permitido');
        }

        try {
            $userId = session()->get('id_usuario');
            $nombres = trim($this->request->getPost('nombres') ?? '');
            $apellidos = trim($this->request->getPost('apellidos') ?? '');
            $departamento = trim($this->request->getPost('departamento') ?? '');
            $observaciones = trim($this->request->getPost('observaciones') ?? '');

            if (empty($nombres) || empty($apellidos)) {
                return redirect()->back()->with('error', 'Nombres y apellidos son obligatorios');
            }

            $db = \Config\Database::connect();

            // Actualizar tabla empleados
            $updateData = [
                'nombres' => $nombres,
                'apellidos' => $apellidos,
            ];
            
            if (!empty($departamento)) {
                $updateData['departamento'] = $departamento;
            }

            $db->table('empleados')
                ->where('id_usuario', $userId)
                ->update($updateData);

            // Actualizar sesión
            session()->set('nombres', $nombres);
            session()->set('apellidos', $apellidos);
            if (!empty($departamento)) {
                session()->set('departamento', $departamento);
            }

            // Manejar foto de perfil si fue subida
            $foto = $this->request->getFile('foto_perfil');
            if ($foto && $foto->isValid() && !$foto->hasMoved()) {
                $newName = 'user_' . $userId . '_' . $foto->getRandomName();
                $foto->move(FCPATH . 'sistema/assets/images/profile/', $newName);
                
                $db->table('empleados')
                    ->where('id_usuario', $userId)
                    ->update(['foto_url' => $newName]);
                
                session()->set('foto_perfil', $newName);
            }

            return redirect()->to(base_url('index.php/empleado/mi-perfil'))->with('success', 'Perfil actualizado correctamente');

        } catch (\Exception $e) {
            log_message('error', 'Error al actualizar perfil: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al actualizar el perfil: ' . $e->getMessage());
        }
    }
}
