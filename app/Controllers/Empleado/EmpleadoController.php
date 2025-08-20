<?php

namespace App\Controllers\Empleado;

use App\Models\EmpleadoModel;
use App\Models\TituloAcademicoModel;
use App\Models\CapacitacionEmpleadoModel;
use App\Models\UsuarioModel;
use CodeIgniter\Controller;

class EmpleadoController extends Controller
{
    protected $empleadoModel;
    protected $tituloAcademicoModel;
    protected $capacitacionModel;
    protected $usuarioModel;

    public function __construct()
    {
        $this->empleadoModel = new EmpleadoModel();
        $this->tituloAcademicoModel = new TituloAcademicoModel();
        $this->capacitacionModel = new CapacitacionEmpleadoModel();
        $this->usuarioModel = new UsuarioModel();
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
     * Configuración de cuenta
     */
    public function cuenta()
    {
        $data = [
            'titulo' => 'Configuración de Cuenta',
            'usuario' => [
                'nombres' => session()->get('nombres'),
                'apellidos' => session()->get('apellidos'),
                'rol' => session()->get('nombre_rol')
            ]
        ];

        return view('Roles/Empleado/cuenta', $data);
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
}
