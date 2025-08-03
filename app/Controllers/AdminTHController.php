<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class AdminTHController extends Controller
{
    public function __construct()
    {
        // Verificar que el usuario esté logueado y tenga el rol correcto
        if (!session()->get('isLoggedIn') || session()->get('id_rol') != 2) {
            return redirect()->to('/access-denied');
        }
    }

    public function dashboard()
    {
        $empleadoModel = new \App\Models\EmpleadoModel();
        $capacitacionModel = new \App\Models\CapacitacionModel();
        $periodoModel = new \App\Models\PeriodoAcademicoModel();
        
        // Obtener periodo académico activo
        $periodoActivo = $periodoModel->getPeriodoActivo();
        
        $data = [
            'title' => 'Dashboard - Administrador Talento Humano',
            'user' => [
                'nombres' => session()->get('nombres'),
                'apellidos' => session()->get('apellidos'),
                'rol' => session()->get('nombre_rol')
            ],
            'estadisticas' => [
                'empleados' => $empleadoModel->getEstadisticasEmpleados(),
                'capacitaciones' => $capacitacionModel->getEstadisticasCapacitaciones()
            ],
            'periodo_activo' => $periodoActivo,
            'periodos_disponibles' => $periodoModel->getPeriodosDisponibles()
        ];
        
        return view('Roles/AdminTalentoHumano/dashboard', $data);
    }

    public function empleados()
    {
        $empleadoModel = new \App\Models\EmpleadoModel();
        $departamentoModel = new \App\Models\DepartamentoModel();
        $puestoModel = new \App\Models\PuestoModel();
        
        $data = [
            'title' => 'Gestión de Empleados',
            'user' => [
                'nombres' => session()->get('nombres'),
                'apellidos' => session()->get('apellidos'),
                'rol' => session()->get('nombre_rol')
            ],
            'empleados' => $empleadoModel->getAllEmpleadosCompletos(),
            'departamentos' => $departamentoModel->getDepartamentosActivos(),
            'puestos' => $puestoModel->getPuestosConDepartamento(),
            'estadisticas' => $empleadoModel->getEstadisticasEmpleados()
        ];
        
        return view('Roles/AdminTalentoHumano/empleados', $data);
    }

    public function departamentos()
    {
        $departamentoModel = new \App\Models\DepartamentoModel();
        
        $data = [
            'title' => 'Departamentos',
            'user' => [
                'nombres' => session()->get('nombres'),
                'apellidos' => session()->get('apellidos'),
                'rol' => session()->get('nombre_rol')
            ],
            'departamentos' => $departamentoModel->getDepartamentosConEstadisticas()
        ];
        return view('Roles/AdminTalentoHumano/departamentos', $data);
    }

    public function puestos()
    {
        $puestoModel = new \App\Models\PuestoModel();
        $departamentoModel = new \App\Models\DepartamentoModel();
        
        $data = [
            'title' => 'Puestos de Trabajo',
            'user' => [
                'nombres' => session()->get('nombres'),
                'apellidos' => session()->get('apellidos'),
                'rol' => session()->get('nombre_rol')
            ],
            'puestos' => $puestoModel->getPuestosConEstadisticas(),
            'departamentos' => $departamentoModel->getDepartamentosActivos()
        ];
        return view('Roles/AdminTalentoHumano/puestos', $data);
    }

    public function vacantes()
    {
        $vacanteModel = new \App\Models\VacanteModel();
        $puestoModel = new \App\Models\PuestoModel();
        
        $data = [
            'title' => 'Gestión de Vacantes',
            'user' => [
                'nombres' => session()->get('nombres'),
                'apellidos' => session()->get('apellidos'),
                'rol' => session()->get('nombre_rol')
            ],
            'vacantes' => $vacanteModel->getVacantesCompletas(),
            'puestos' => $puestoModel->getPuestosConDepartamento()
        ];
        return view('Roles/AdminTalentoHumano/vacantes', $data);
    }

    public function candidatos()
    {
        $candidatoModel = new \App\Models\CandidatoModel();
        $vacanteModel = new \App\Models\VacanteModel();
        
        $data = [
            'title' => 'Candidatos',
            'user' => [
                'nombres' => session()->get('nombres'),
                'apellidos' => session()->get('apellidos'),
                'rol' => session()->get('nombre_rol')
            ],
            'candidatos' => $candidatoModel->getCandidatosCompletos(),
            'vacantes' => $vacanteModel->getVacantesActivas()
        ];
        return view('Roles/AdminTalentoHumano/candidatos', $data);
    }

    public function contratos()
    {
        $contratoModel = new \App\Models\ContratoModel();
        $empleadoModel = new \App\Models\EmpleadoModel();
        
        $data = [
            'title' => 'Contratos',
            'user' => [
                'nombres' => session()->get('nombres'),
                'apellidos' => session()->get('apellidos'),
                'rol' => session()->get('nombre_rol')
            ],
            'contratos' => $contratoModel->getContratosCompletos(),
            'empleados' => $empleadoModel->getEmpleadosActivos()
        ];
        return view('Roles/AdminTalentoHumano/contratos', $data);
    }

    public function capacitaciones()
    {
        $capacitacionModel = new \App\Models\CapacitacionModel();
        
        $data = [
            'title' => 'Gestión de Capacitaciones',
            'user' => [
                'nombres' => session()->get('nombres'),
                'apellidos' => session()->get('apellidos'),
                'rol' => session()->get('nombre_rol')
            ],
            'capacitaciones' => $capacitacionModel->getCapacitacionesConEstadisticas(),
            'estadisticas' => $capacitacionModel->getEstadisticasCapacitaciones()
        ];
        
        return view('Roles/AdminTalentoHumano/capacitaciones', $data);
    }

    public function empleadosCapacitaciones()
    {
        $empleadoCapacitacionModel = new \App\Models\EmpleadoCapacitacionModel();
        $empleadoModel = new \App\Models\EmpleadoModel();
        $capacitacionModel = new \App\Models\CapacitacionModel();
        
        $data = [
            'title' => 'Asignación de Capacitaciones',
            'user' => [
                'nombres' => session()->get('nombres'),
                'apellidos' => session()->get('apellidos'),
                'rol' => session()->get('nombre_rol')
            ],
            'asignaciones' => $empleadoCapacitacionModel->getAsignacionesCompletas(),
            'empleados' => $empleadoModel->getEmpleadosActivos(),
            'capacitaciones' => $capacitacionModel->findAll()
        ];
        return view('Roles/AdminTalentoHumano/empleados_capacitaciones', $data);
    }

    public function evaluaciones()
    {
        $evaluacionModel = new \App\Models\EvaluacionModel();
        $empleadoModel = new \App\Models\EmpleadoModel();
        
        $data = [
            'title' => 'Gestión de Evaluaciones',
            'user' => [
                'nombres' => session()->get('nombres'),
                'apellidos' => session()->get('apellidos'),
                'rol' => session()->get('nombre_rol')
            ],
            'evaluaciones' => $evaluacionModel->getEvaluacionesCompletas(),
            'empleados' => $empleadoModel->getEmpleadosActivos()
        ];
        
        return view('Roles/AdminTalentoHumano/evaluaciones', $data);
    }

    public function competencias()
    {
        $competenciaModel = new \App\Models\CompetenciaModel();
        $empleadoCompetenciaModel = new \App\Models\EmpleadoCompetenciaModel();
        
        $data = [
            'title' => 'Competencias',
            'user' => [
                'nombres' => session()->get('nombres'),
                'apellidos' => session()->get('apellidos'),
                'rol' => session()->get('nombre_rol')
            ],
            'competencias' => $competenciaModel->findAll(),
            'evaluaciones' => $empleadoCompetenciaModel->getEvaluacionesCompletas()
        ];
        return view('Roles/AdminTalentoHumano/competencias', $data);
    }

    public function asistencias()
    {
        $asistenciaModel = new \App\Models\AsistenciaModel();
        $empleadoModel = new \App\Models\EmpleadoModel();
        
        $data = [
            'title' => 'Control de Asistencias',
            'user' => [
                'nombres' => session()->get('nombres'),
                'apellidos' => session()->get('apellidos'),
                'rol' => session()->get('nombre_rol')
            ],
            'asistencias' => $asistenciaModel->getAsistenciasCompletas(),
            'empleados' => $empleadoModel->getEmpleadosActivos()
        ];
        return view('Roles/AdminTalentoHumano/asistencias', $data);
    }

    public function permisos()
    {
        $permisoModel = new \App\Models\PermisoModel();
        $empleadoModel = new \App\Models\EmpleadoModel();
        
        $data = [
            'title' => 'Gestión de Permisos',
            'user' => [
                'nombres' => session()->get('nombres'),
                'apellidos' => session()->get('apellidos'),
                'rol' => session()->get('nombre_rol')
            ],
            'permisos' => $permisoModel->getPermisosCompletos(),
            'empleados' => $empleadoModel->getEmpleadosActivos()
        ];
        return view('Roles/AdminTalentoHumano/permisos', $data);
    }

    public function nominas()
    {
        $nominaModel = new \App\Models\NominaModel();
        $empleadoModel = new \App\Models\EmpleadoModel();
        
        $data = [
            'title' => 'Gestión de Nóminas',
            'user' => [
                'nombres' => session()->get('nombres'),
                'apellidos' => session()->get('apellidos'),
                'rol' => session()->get('nombre_rol')
            ],
            'nominas' => $nominaModel->getNominasCompletas(),
            'empleados' => $empleadoModel->getEmpleadosActivos()
        ];
        
        return view('Roles/AdminTalentoHumano/nominas', $data);
    }

    public function beneficios()
    {
        $beneficioModel = new \App\Models\BeneficioModel();
        $empleadoBeneficioModel = new \App\Models\EmpleadoBeneficioModel();
        
        $data = [
            'title' => 'Beneficios',
            'user' => [
                'nombres' => session()->get('nombres'),
                'apellidos' => session()->get('apellidos'),
                'rol' => session()->get('nombre_rol')
            ],
            'beneficios' => $beneficioModel->findAll(),
            'asignaciones' => $empleadoBeneficioModel->getAsignacionesCompletas()
        ];
        return view('Roles/AdminTalentoHumano/beneficios', $data);
    }

    public function reportes()
    {
        $empleadoModel = new \App\Models\EmpleadoModel();
        $capacitacionModel = new \App\Models\CapacitacionModel();
        $vacanteModel = new \App\Models\VacanteModel();
        $asistenciaModel = new \App\Models\AsistenciaModel();
        $departamentoModel = new \App\Models\DepartamentoModel();
        
        // Obtener estadísticas generales
        $estadisticasEmpleados = $empleadoModel->getEstadisticasEmpleados();
        $estadisticasCapacitaciones = $capacitacionModel->getEstadisticasCapacitaciones();
        $estadisticasVacantes = $vacanteModel->getEstadisticasVacantes();
        $estadisticasDepartamentos = $departamentoModel->getEstadisticasDepartamentos();
        
        // Obtener datos para las tablas
        $empleados = $empleadoModel->getAllEmpleadosCompletos();
        $capacitaciones = $capacitacionModel->getCapacitacionesConEstadisticas();
        $vacantes = $vacanteModel->getVacantesCompletas();
        $asistencias = $asistenciaModel->getAsistenciasCompletas();
        
        // Preparar datos para gráficos
        $chartData = [
            'empleados_por_departamento' => $empleadoModel->getEmpleadosPorDepartamentoChart(),
            'estado_vacantes' => $vacanteModel->getEstadoVacantes(),
            'capacitaciones_por_tipo' => $capacitacionModel->getCapacitacionesPorTipo(),
            'asistencias_por_mes' => $asistenciaModel->getAsistenciasPorMes()
        ];
        
        $data = [
            'title' => 'Reportes y Analítica - Administrador Talento Humano',
            'user' => [
                'nombres' => session()->get('nombres'),
                'apellidos' => session()->get('apellidos'),
                'rol' => session()->get('nombre_rol')
            ],
            'estadisticas' => [
                'total_empleados' => $estadisticasEmpleados['total'] ?? 0,
                'total_departamentos' => $estadisticasDepartamentos['total'] ?? 0,
                'total_capacitaciones' => $estadisticasCapacitaciones['total'] ?? 0,
                'total_vacantes' => $estadisticasVacantes['total'] ?? 0
            ],
            'empleados' => $empleados,
            'capacitaciones' => $capacitaciones,
            'vacantes' => $vacantes,
            'asistencias' => $asistencias,
            'chartData' => $chartData
        ];
        
        return view('Roles/AdminTalentoHumano/reportes', $data);
    }

    public function perfil()
    {
        $data = [
            'title' => 'Mi Perfil - Administrador Talento Humano',
            'user' => [
                'nombres' => session()->get('nombres'),
                'apellidos' => session()->get('apellidos'),
                'rol' => session()->get('nombre_rol')
            ]
        ];
        return view('Roles/AdminTalentoHumano/perfil', $data);
    }

    public function cuenta()
    {
        $data = [
            'title' => 'Mi Cuenta - Administrador Talento Humano',
            'user' => [
                'nombres' => session()->get('nombres'),
                'apellidos' => session()->get('apellidos'),
                'rol' => session()->get('nombre_rol')
            ]
        ];
        return view('Roles/AdminTalentoHumano/cuenta', $data);
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
            if (!password_verify($passwordActual, $usuario['password_hash'])) {
                return $this->response->setJSON(['success' => false, 'message' => 'La contraseña actual es incorrecta']);
            }
            
            // Actualizar contraseña
            $usuarioModel->update($idUsuario, [
                'password_hash' => password_hash($passwordNuevo, PASSWORD_DEFAULT)
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