<?php

namespace App\Controllers\Empleado;

use App\Models\EmpleadoModel;
use App\Models\TituloAcademicoModel;
use App\Models\CapacitacionEmpleadoModel;
use App\Models\CapacitacionModel;
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
     * Capacitaciones del empleado - Vista principal
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
     * Obtener capacitaciones del empleado (AJAX) - Req 2
     */
    public function obtenerCapacitacionesEmpleado()
    {
        try {
            $idEmpleado = session()->get('id_empleado');
            $capModel = new CapacitacionModel();
            
            $misCapacitaciones = [];
            if ($idEmpleado) {
                $misCapacitaciones = $capModel->getCapacitacionesPorEmpleado($idEmpleado);
            }

            return $this->response->setJSON([
                'success' => true,
                'capacitaciones' => $misCapacitaciones
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error obteniendo capacitaciones del empleado: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al obtener capacitaciones: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Obtener capacitaciones disponibles (ACTIVA) para inscribirse (AJAX) - Req 2+4
     */
    public function obtenerCapacitacionesDisponibles()
    {
        try {
            $capModel = new CapacitacionModel();
            $disponibles = $capModel->getCapacitacionesVisiblesEmpleado();

            return $this->response->setJSON([
                'success' => true,
                'capacitaciones' => $disponibles
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error obteniendo capacitaciones disponibles: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al obtener capacitaciones: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Inscribir empleado en una capacitación (AJAX) - Req 4
     * Solo permite inscripción si el estado de la capacitación es ACTIVA
     */
    public function inscribirCapacitacion()
    {
        try {
            $idCapacitacion = $this->request->getPost('id_capacitacion');
            $idEmpleado = session()->get('id_empleado');

            if (empty($idCapacitacion) || empty($idEmpleado)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Datos de inscripción incompletos'
                ]);
            }

            $capModel = new CapacitacionModel();
            $capacitacion = $capModel->find($idCapacitacion);

            if (!$capacitacion) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Capacitación no encontrada'
                ]);
            }

            // Validación de negocio: solo se puede inscribir si está ACTIVA
            if ($capacitacion['estado'] !== 'ACTIVA') {
                $mensajes = [
                    'EN_CURSO' => 'La capacitación está en curso y las inscripciones están cerradas.',
                    'INACTIVA' => 'La capacitación está inactiva y no acepta inscripciones.',
                    'COMPLETADA' => 'La capacitación ya finalizó.',
                    'CANCELADA' => 'La capacitación fue cancelada.'
                ];
                $msg = $mensajes[$capacitacion['estado']] ?? 'La capacitación no está disponible para inscripción.';
                
                return $this->response->setJSON([
                    'success' => false,
                    'message' => $msg
                ]);
            }

            // Verificar si ya está inscrito
            if ($capModel->empleadoInscrito($idCapacitacion, $idEmpleado)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Ya estás inscrito en esta capacitación'
                ]);
            }

            // Inscribir
            if ($capModel->asignarEmpleado($idCapacitacion, $idEmpleado)) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Te has inscrito exitosamente en la capacitación'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Error al inscribirse en la capacitación'
                ]);
            }

        } catch (\Exception $e) {
            log_message('error', 'Error inscribiendo en capacitación: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al procesar inscripción: ' . $e->getMessage()
            ]);
        }
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

    // ==================== EVALUACIONES - RÚBRICA ====================

    /**
     * Obtener evaluaciones asignadas al empleado actual como evaluador (AJAX)
     */
    public function misEvaluacionesJSON()
    {
        try {
            $idEmpleado = session()->get('id_empleado');

            if (!$idEmpleado) {
                return $this->response->setJSON(['success' => false, 'message' => 'Sesión no válida']);
            }

            $db = \Config\Database::connect();

            // Evaluaciones donde el empleado es el EVALUADOR
            $evaluaciones = $db->table('evaluaciones_empleados ee')
                ->select('ee.id_evaluacion_empleado as id, ee.id_evaluacion, ee.id_empleado, ee.id_evaluador,
                          ee.fecha_evaluacion, ee.puntaje_total, ee.observaciones,
                          ee.puntaje_responsabilidad, ee.puntaje_equipo, ee.puntaje_etica,
                          ee.puntaje_comunicacion, ee.puntaje_compromiso,
                          e.nombre as nombre_evaluacion, e.tipo_evaluacion, e.estado,
                          emp.nombres as nombres_evaluado, emp.apellidos as apellidos_evaluado')
                ->join('evaluaciones e', 'e.id_evaluacion = ee.id_evaluacion', 'left')
                ->join('empleados emp', 'emp.id_empleado = ee.id_empleado', 'left')
                ->where('ee.id_evaluador', $idEmpleado)
                ->orderBy('ee.fecha_evaluacion', 'DESC')
                ->get()
                ->getResultArray();

            // Clasificar: pendientes (sin puntaje) y completadas (con puntaje)
            $pendientes = [];
            $completadas = [];
            foreach ($evaluaciones as $ev) {
                if ($ev['puntaje_total'] === null || $ev['puntaje_total'] == 0) {
                    $pendientes[] = $ev;
                } else {
                    $completadas[] = $ev;
                }
            }

            return $this->response->setJSON([
                'success'     => true,
                'pendientes'  => $pendientes,
                'completadas' => $completadas,
                'total'       => count($evaluaciones),
                'total_pendientes' => count($pendientes),
                'total_completadas' => count($completadas)
            ]);

        } catch (\Exception $e) {
            return $this->response->setJSON(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
    }

    /**
     * Obtener detalle de una evaluación específica (AJAX)
     */
    public function detalleEvaluacionJSON($id)
    {
        try {
            $db = \Config\Database::connect();
            $eval = $db->table('evaluaciones_empleados ee')
                ->select('ee.*, e.nombre as nombre_evaluacion, e.tipo_evaluacion, e.estado as evaluacion_estado,
                          emp.nombres as nombres_evaluado, emp.apellidos as apellidos_evaluado,
                          CONCAT(evaluador.nombres, " ", evaluador.apellidos) as nombre_evaluador')
                ->join('evaluaciones e', 'e.id_evaluacion = ee.id_evaluacion', 'left')
                ->join('empleados emp', 'emp.id_empleado = ee.id_empleado', 'left')
                ->join('empleados evaluador', 'evaluador.id_empleado = ee.id_evaluador', 'left')
                ->where('ee.id_evaluacion_empleado', $id)
                ->get()
                ->getRowArray();

            if (!$eval) {
                return $this->response->setJSON(['success' => false, 'message' => 'Evaluación no encontrada']);
            }

            return $this->response->setJSON(['success' => true, 'evaluacion' => $eval]);

        } catch (\Exception $e) {
            return $this->response->setJSON(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
    }

    /**
     * Guardar rúbrica de evaluación (AJAX)
     * Recibe 5 puntajes (1-5), los suma en puntaje_total (max 25)
     */
    public function guardarRubrica()
    {
        try {
            $id = $this->request->getPost('id_evaluacion_empleado');
            $pResponsabilidad = (int) $this->request->getPost('puntaje_responsabilidad');
            $pEquipo          = (int) $this->request->getPost('puntaje_equipo');
            $pEtica           = (int) $this->request->getPost('puntaje_etica');
            $pComunicacion    = (int) $this->request->getPost('puntaje_comunicacion');
            $pCompromiso      = (int) $this->request->getPost('puntaje_compromiso');
            $observaciones    = $this->request->getPost('observaciones');

            // Validar que todos los campos estén entre 1 y 5
            $puntajes = [$pResponsabilidad, $pEquipo, $pEtica, $pComunicacion, $pCompromiso];
            foreach ($puntajes as $p) {
                if ($p < 1 || $p > 5) {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'Todos los criterios deben tener un valor entre 1 y 5'
                    ]);
                }
            }

            // Calcular puntaje total (suma de los 5 criterios, máximo 25)
            $puntajeTotal = array_sum($puntajes);

            $db = \Config\Database::connect();

            // Verificar que la evaluación existe y pertenece al evaluador actual
            $eval = $db->table('evaluaciones_empleados')
                ->where('id_evaluacion_empleado', $id)
                ->where('id_evaluador', session()->get('id_empleado'))
                ->get()
                ->getRowArray();

            if (!$eval) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Evaluación no encontrada o no tienes permisos'
                ]);
            }

            // Actualizar con los puntajes de la rúbrica
            $db->table('evaluaciones_empleados')
                ->where('id_evaluacion_empleado', $id)
                ->update([
                    'puntaje_responsabilidad' => $pResponsabilidad,
                    'puntaje_equipo'          => $pEquipo,
                    'puntaje_etica'           => $pEtica,
                    'puntaje_comunicacion'    => $pComunicacion,
                    'puntaje_compromiso'      => $pCompromiso,
                    'puntaje_total'           => $puntajeTotal,
                    'observaciones'           => $observaciones ?: null,
                    'fecha_evaluacion'        => date('Y-m-d')
                ]);

            // Verificar si TODAS las evaluaciones de este grupo ya fueron completadas
            $idEvaluacion = $eval['id_evaluacion'];
            $sinCompletar = $db->table('evaluaciones_empleados')
                ->where('id_evaluacion', $idEvaluacion)
                ->where('puntaje_total IS NULL OR puntaje_total = 0')
                ->countAllResults();

            if ($sinCompletar == 0) {
                // Todas completadas → marcar evaluación padre como Finalizada
                $db->table('evaluaciones')
                    ->where('id_evaluacion', $idEvaluacion)
                    ->update(['estado' => 'Finalizada']);
            } else {
                // Al menos una completada → marcar como En curso
                $db->table('evaluaciones')
                    ->where('id_evaluacion', $idEvaluacion)
                    ->update(['estado' => 'En curso']);
            }

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Evaluación completada correctamente. Puntaje total: ' . $puntajeTotal . '/25',
                'puntaje_total' => $puntajeTotal
            ]);

        } catch (\Exception $e) {
            return $this->response->setJSON(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
    }
}
