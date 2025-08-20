<?php

namespace App\Controllers\AdminTH;

use App\Models\EmpleadoModel;
use App\Models\DepartamentoModel;
use App\Models\PuestoModel;
use App\Models\CapacitacionModel;
use App\Models\TituloAcademicoModel;
use App\Models\EvaluacionModel;
use App\Models\InasistenciaModel;
use App\Models\PoliticaInasistenciaModel;
use App\Models\SolicitudCapacitacionModel;
use CodeIgniter\Controller;

class AdminTHController extends Controller
{
    protected $empleadoModel;
    protected $departamentoModel;
    protected $puestoModel;
    protected $capacitacionModel;
    protected $tituloAcademicoModel;
    protected $evaluacionModel;
    protected $inasistenciaModel;
    protected $politicaInasistenciaModel;
    protected $solicitudCapacitacionModel;

    public function __construct()
    {
        $this->empleadoModel = new EmpleadoModel();
        $this->departamentoModel = new DepartamentoModel();
        $this->puestoModel = new PuestoModel();
        $this->capacitacionModel = new CapacitacionModel();
        $this->tituloAcademicoModel = new TituloAcademicoModel();
        $this->evaluacionModel = new EvaluacionModel();
        $this->inasistenciaModel = new InasistenciaModel();
        $this->politicaInasistenciaModel = new PoliticaInasistenciaModel();
        $this->solicitudCapacitacionModel = new SolicitudCapacitacionModel();
    }

    /**
     * Dashboard del Admin Talento Humano
     */
    public function dashboard()
    {
        $data = [
            'titulo' => 'Dashboard Admin Talento Humano',
            'usuario' => [
                'nombres' => session()->get('nombres'),
                'apellidos' => session()->get('apellidos'),
                'rol' => session()->get('nombre_rol')
            ]
        ];

        return view('Roles/AdminTH/dashboard', $data);
    }

    /**
     * Gestión de empleados
     */
    public function empleados()
    {
        $data = [
            'titulo' => 'Gestión de Empleados',
            'usuario' => [
                'nombres' => session()->get('nombres'),
                'apellidos' => session()->get('apellidos'),
                'rol' => session()->get('nombre_rol')
            ],
            'empleados' => $this->empleadoModel->getEmpleadosConUsuario()
        ];

        return view('Roles/AdminTH/empleados', $data);
    }

    /**
     * Gestión de departamentos
     */
    public function departamentos()
    {
        $data = [
            'titulo' => 'Gestión de Departamentos',
            'usuario' => [
                'nombres' => session()->get('nombres'),
                'apellidos' => session()->get('apellidos'),
                'rol' => session()->get('nombre_rol')
            ],
            'departamentos' => $this->departamentoModel->findAll()
        ];

        return view('Roles/AdminTH/departamentos', $data);
    }

    /**
     * Gestión de puestos
     */
    public function puestos()
    {
        $data = [
            'titulo' => 'Gestión de Puestos',
            'usuario' => [
                'nombres' => session()->get('nombres'),
                'apellidos' => session()->get('apellidos'),
                'rol' => session()->get('nombre_rol')
            ],
            'puestos' => $this->puestoModel->findAll()
        ];

        return view('Roles/AdminTH/puestos', $data);
    }

    /**
     * Gestión de capacitaciones
     */
    public function capacitaciones()
    {
        $data = [
            'titulo' => 'Gestión de Capacitaciones',
            'usuario' => [
                'nombres' => session()->get('nombres'),
                'apellidos' => session()->get('apellidos'),
                'rol' => session()->get('nombre_rol')
            ],
            'capacitaciones' => $this->capacitacionModel->findAll()
        ];

        return view('Roles/AdminTH/capacitaciones', $data);
    }

    /**
     * Gestión de títulos académicos
     */
    public function titulosAcademicos()
    {
        $data = [
            'titulo' => 'Gestión de Títulos Académicos',
            'usuario' => [
                'nombres' => session()->get('nombres'),
                'apellidos' => session()->get('apellidos'),
                'rol' => session()->get('nombre_rol')
            ],
            'titulos' => $this->tituloAcademicoModel->findAll()
        ];

        return view('Roles/AdminTH/titulos_academicos', $data);
    }

    /**
     * Gestión de evaluaciones
     */
    public function evaluaciones()
    {
        $data = [
            'titulo' => 'Gestión de Evaluaciones',
            'usuario' => [
                'nombres' => session()->get('nombres'),
                'apellidos' => session()->get('apellidos'),
                'rol' => session()->get('nombre_rol')
            ],
            'evaluaciones' => $this->evaluacionModel->findAll()
        ];

        return view('Roles/AdminTH/evaluaciones', $data);
    }

    /**
     * Gestión de inasistencias
     */
    public function inasistencias()
    {
        $data = [
            'titulo' => 'Gestión de Inasistencias',
            'usuario' => [
                'nombres' => session()->get('nombres'),
                'apellidos' => session()->get('apellidos'),
                'rol' => session()->get('nombre_rol')
            ],
            'inasistencias' => $this->inasistenciaModel->getInasistenciasConEmpleado()
        ];

        return view('Roles/AdminTH/inasistencias/dashboard', $data);
    }

    /**
     * Gestión de políticas de inasistencia
     */
    public function politicasInasistencia()
    {
        $data = [
            'titulo' => 'Políticas de Inasistencia',
            'usuario' => [
                'nombres' => session()->get('nombres'),
                'apellidos' => session()->get('apellidos'),
                'rol' => session()->get('nombre_rol')
            ],
            'politicas' => $this->politicaInasistenciaModel->findAll()
        ];

        return view('Roles/AdminTH/politicas_inasistencia', $data);
    }

    /**
     * Gestión de solicitudes de capacitación
     */
    public function solicitudesCapacitacion()
    {
        $data = [
            'titulo' => 'Solicitudes de Capacitación',
            'usuario' => [
                'nombres' => session()->get('nombres'),
                'apellidos' => session()->get('apellidos'),
                'rol' => session()->get('nombre_rol')
            ],
            'solicitudes' => $this->solicitudCapacitacionModel->getSolicitudesConEmpleado()
        ];

        return view('Roles/AdminTH/solicitudes_capacitacion', $data);
    }

    /**
     * Reportes
     */
    public function reportes()
    {
        $data = [
            'titulo' => 'Reportes',
            'usuario' => [
                'nombres' => session()->get('nombres'),
                'apellidos' => session()->get('apellidos'),
                'rol' => session()->get('nombre_rol')
            ]
        ];

        return view('Roles/AdminTH/reportes', $data);
    }

    /**
     * Estadísticas
     */
    public function estadisticas()
    {
        $data = [
            'titulo' => 'Estadísticas',
            'usuario' => [
                'nombres' => session()->get('nombres'),
                'apellidos' => session()->get('apellidos'),
                'rol' => session()->get('nombre_rol')
            ]
        ];

        return view('Roles/AdminTH/estadisticas', $data);
    }

    /**
     * Mi perfil
     */
    public function perfil()
    {
        $data = [
            'titulo' => 'Mi Perfil',
            'usuario' => [
                'nombres' => session()->get('nombres'),
                'apellidos' => session()->get('apellidos'),
                'rol' => session()->get('nombre_rol')
            ]
        ];

        return view('Roles/AdminTH/perfil', $data);
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

        return view('Roles/AdminTH/cuenta', $data);
    }
}
