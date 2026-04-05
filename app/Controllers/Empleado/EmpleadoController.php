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
        
        $idEmpleado = session()->get('id_empleado');
        
        $todasCapacitaciones = [];
        $todasSolicitudes = [];
        $total_documentos = 0;
        $total_certificados = 0;
        
        if ($idEmpleado) {
            if (class_exists('\App\Models\CapacitacionModel')) {
                $capModel = new \App\Models\CapacitacionModel();
                $todasCapacitaciones = $capModel->getCapacitacionesPorEmpleado($idEmpleado);
            }
            if (class_exists('\App\Models\SolicitudModel')) {
                $solModel = new \App\Models\SolicitudModel();
                $todasSolicitudes = $solModel->getSolicitudesPorEmpleado($idEmpleado);
            }
            if (class_exists('\App\Models\DocumentoModel')) {
                $docModel = new \App\Models\DocumentoModel();
                try { $total_documentos = $docModel->where('id_empleado', $idEmpleado)->countAllResults(); } catch (\Exception $e) {}
            }
            if (class_exists('\App\Models\CertificadoModel')) {
                $certModel = new \App\Models\CertificadoModel();
                try { $total_certificados = $certModel->where('id_empleado', $idEmpleado)->countAllResults(); } catch (\Exception $e) {}
            }
        }
        
        $estadisticas = [
            'total_capacitaciones' => count($todasCapacitaciones),
            'total_documentos' => $total_documentos,
            'total_certificados' => $total_certificados,
            'total_solicitudes' => count($todasSolicitudes)
        ];
        
        $capacitaciones_recientes = array_slice($todasCapacitaciones, 0, 5);
        $solicitudes_recientes = array_slice($todasSolicitudes, 0, 5);

        $data = [
            'titulo' => 'Dashboard Empleado',
            'user' => $user,
            'empleado' => $empleado,
            'descripcionDashboard' => $descripcionDashboard,
            'estadisticas' => $estadisticas,
            'capacitaciones_recientes' => $capacitaciones_recientes,
            'solicitudes_recientes' => $solicitudes_recientes
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
     * Obtener las capacitaciones en las que el empleado está inscrito (AJAX)
     * Ruta: GET empleado/capacitaciones/obtener
     *
     * Estructura real de la BD (verificada con DESCRIBE):
     *   - empleados_capacitaciones: id_empleado_capacitacion, id_capacitacion, id_empleado,
     *                               asistio (tinyint, default 0), aprobo (tinyint, default 0),
     *                               certificado_url, created_at
     *   - capacitaciones: id_capacitacion, nombre, descripcion, tipo, fecha_inicio, fecha_fin,
     *                     duracion_horas, modalidad, institucion, estado, ...
     */
    public function obtenerCapacitacionesEmpleado()
    {
        try {
            // Resolver id_empleado desde la BD (session solo guarda id_usuario en el login)
            $idUsuario = session()->get('id_usuario');
            if (!$idUsuario) {
                return $this->response->setJSON(['success' => true, 'capacitaciones' => []]);
            }

            $db = \Config\Database::connect();

            $empleado = $db->table('empleados')
                           ->where('id_usuario', $idUsuario)
                           ->get()
                           ->getRowArray();

            if (!$empleado) {
                return $this->response->setJSON(['success' => true, 'capacitaciones' => []]);
            }

            $idEmpleado = $empleado['id_empleado'];

            // JOIN entre la tabla de inscripciones y capacitaciones
            $inscripciones = $db->table('empleados_capacitaciones ec')
                ->select([
                    'c.id_capacitacion',
                    'c.nombre',
                    'c.descripcion',
                    'c.modalidad',
                    'c.fecha_inicio',
                    'c.fecha_fin',
                    'c.duracion_horas',
                    'c.institucion',
                    'c.estado AS estado_capacitacion',
                    'ec.asistio',
                    'ec.aprobo',
                    'ec.certificado_url',
                    'ec.created_at AS fecha_inscripcion',
                ])
                ->join('capacitaciones c', 'c.id_capacitacion = ec.id_capacitacion', 'inner')
                ->where('ec.id_empleado', $idEmpleado)
                ->orderBy('ec.created_at', 'DESC')
                ->get()
                ->getResultArray();

            // Derivar el campo 'estado' para el frontend desde asistio/aprobo
            // (la tabla EC no tiene columna estado propia)
            foreach ($inscripciones as &$row) {
                if ($row['aprobo'] == 1) {
                    $row['estado'] = 'COMPLETADA';
                } elseif ($row['asistio'] == 1) {
                    $row['estado'] = 'EN_CURSO';
                } else {
                    $row['estado'] = 'Inscrito';
                }
            }
            unset($row);

            return $this->response->setJSON([
                'success'        => true,
                'capacitaciones' => $inscripciones,
            ]);

        } catch (\Exception $e) {
            log_message('error', 'obtenerCapacitacionesEmpleado: ' . $e->getMessage());
            return $this->response->setJSON([
                'success'        => true,   // no romper el frontend
                'capacitaciones' => [],
                'debug'          => $e->getMessage(),
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
     * Solo permite inscripción si el estado de la capacitación es ACTIVA.
     * El id_empleado se resuelve desde la BD usando el id_usuario de sesión,
     * ya que AuthController::setSession() no persiste id_empleado en sesión.
     */
    public function inscribirCapacitacion()
    {
        try {
            $idCapacitacion = $this->request->getPost('id_capacitacion');

            if (empty($idCapacitacion)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Datos de inscripción incompletos'
                ]);
            }

            // Resolver id_empleado desde la BD usando id_usuario de sesión
            $idUsuario = session()->get('id_usuario');
            if (!$idUsuario) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Sesión no válida. Por favor, inicie sesión nuevamente.'
                ]);
            }

            $db = \Config\Database::connect();
            $empleado = $db->table('empleados')
                           ->where('id_usuario', $idUsuario)
                           ->get()
                           ->getRowArray();

            if (!$empleado) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'No se encontró el registro de empleado asociado a su cuenta.'
                ]);
            }

            $idEmpleado = $empleado['id_empleado'];

            // Validar que la capacitación existe
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
                    'EN_CURSO'   => 'La capacitación está en curso y las inscripciones están cerradas.',
                    'INACTIVA'   => 'La capacitación está inactiva y no acepta inscripciones.',
                    'COMPLETADA' => 'La capacitación ya finalizó.',
                    'CANCELADA'  => 'La capacitación fue cancelada.'
                ];
                $msg = $mensajes[$capacitacion['estado']] ?? 'La capacitación no está disponible para inscripción.';

                return $this->response->setJSON([
                    'success' => false,
                    'message' => $msg
                ]);
            }

            // Verificar inscripción duplicada en la tabla real
            $yaInscrito = $db->table('empleados_capacitaciones')
                             ->where('id_capacitacion', $idCapacitacion)
                             ->where('id_empleado', $idEmpleado)
                             ->countAllResults() > 0;

            if ($yaInscrito) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Ya estás inscrito en esta capacitación.'
                ]);
            }

            // Insertar — la tabla solo requiere id_capacitacion e id_empleado.
            // asistio=0, aprobo=0 y created_at son manejados por defaults de la BD.
            $resultado = $db->table('empleados_capacitaciones')->insert([
                'id_capacitacion' => $idCapacitacion,
                'id_empleado'     => $idEmpleado,
            ]);

            if ($resultado) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Inscripción realizada con éxito.'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Error al inscribirse en la capacitación.'
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
        $idUsuario = session()->get('id_usuario');
        $db = \Config\Database::connect();
        
        // 1. Cruzar id_usuario con empleados para obtener id_empleado
        $empleado = $db->table('empleados')->where('id_usuario', $idUsuario)->get()->getRowArray();
        $idEmpleado = $empleado ? $empleado['id_empleado'] : null;
        
        $pendientes = [];
        $completadas = [];
        
        if ($idEmpleado) {
            // Guardar en sesión para los métodos AJAX de la rúbrica
            session()->set('id_empleado', $idEmpleado);
            
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
                ->where('ee.oculto_para_empleado', 0)   // excluir las archivadas
                ->orderBy('ee.fecha_evaluacion', 'DESC')
                ->get()
                ->getResultArray();

            foreach ($evaluaciones as $ev) {
                if ($ev['puntaje_total'] === null || (float)$ev['puntaje_total'] == 0) {
                    $pendientes[] = $ev;
                } else {
                    $completadas[] = $ev;
                }
            }
        }

        $data = [
            'titulo' => 'Mis Evaluaciones',
            'usuario' => [
                'nombres' => session()->get('nombres'),
                'apellidos' => session()->get('apellidos'),
                'rol' => session()->get('nombre_rol')
            ],
            'pendientes' => $pendientes,
            'completadas' => $completadas,
            'total' => count($pendientes) + count($completadas),
            'total_pendientes' => count($pendientes),
            'total_completadas' => count($completadas),
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
            $idUsuario = session()->get('id_usuario');
            if (!$idUsuario) {
                return $this->response->setJSON(['success' => false, 'message' => 'Sesión no válida']);
            }

            $db = \Config\Database::connect();
            $empleado = $db->table('empleados')->where('id_usuario', $idUsuario)->get()->getRowArray();
            
            if (!$empleado || !$empleado['id_empleado']) {
                return $this->response->setJSON(['success' => false, 'message' => 'Registro de empleado no encontrado']);
            }
            
            $idEmpleado = $empleado['id_empleado'];
            session()->set('id_empleado', $idEmpleado);

            // Evaluaciones donde el empleado es el EVALUADOR (excluye archivadas)
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
                ->where('ee.oculto_para_empleado', 0)   // excluir las archivadas
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
     * Obtener detalle de una evaluación específica (AJAX) — con verificación strict de propiedad
     * Sólo devuelve datos si el registro le pertenece al empleado de la sesión activa.
     */
    public function detalleEvaluacionJSON($id)
    {
        try {
            $idUsuario = session()->get('id_usuario');
            if (!$idUsuario) {
                return $this->response->setStatusCode(401)
                    ->setJSON(['success' => false, 'message' => 'Sesión no válida']);
            }

            $db = \Config\Database::connect();

            // Resolver id_empleado desde la sesión para el filtro de seguridad
            $empleado = $db->table('empleados')->where('id_usuario', $idUsuario)->get()->getRowArray();
            if (!$empleado) {
                return $this->response->setStatusCode(403)
                    ->setJSON(['success' => false, 'message' => 'Empleado no encontrado']);
            }
            $idEmpleadoSesion = $empleado['id_empleado'];

            // Filtro estricto: el registro DEBE pertenecer al evaluador de la sesión
            $eval = $db->table('evaluaciones_empleados ee')
                ->select('ee.*, e.nombre as nombre_evaluacion, e.tipo_evaluacion, e.estado as evaluacion_estado,
                          emp.nombres as nombres_evaluado, emp.apellidos as apellidos_evaluado')
                ->join('evaluaciones e', 'e.id_evaluacion = ee.id_evaluacion', 'left')
                ->join('empleados emp', 'emp.id_empleado = ee.id_empleado', 'left')
                ->where('ee.id_evaluacion_empleado', (int)$id)
                ->where('ee.id_evaluador', $idEmpleadoSesion)  // FILTRO DE PRIVACIDAD ESTRICTO
                ->get()
                ->getRowArray();

            if (!$eval) {
                return $this->response->setStatusCode(403)
                    ->setJSON(['success' => false, 'message' => 'No tienes permiso para ver esta evaluación o no existe']);
            }

            return $this->response->setJSON(['success' => true, 'evaluacion' => $eval]);

        } catch (\Exception $e) {
            return $this->response->setStatusCode(500)
                ->setJSON(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
    }

    /**
     * Ocultar evaluación completada de la vista del empleado (soft-hide)
     * Ruta: POST empleado/evaluaciones/ocultar
     * NO elimina datos. El administrador sigue viendo todo.
     */
    public function ocultarEvaluacion()
    {
        try {
            $idUsuario = session()->get('id_usuario');
            if (!$idUsuario) {
                return $this->response->setJSON(['success' => false, 'message' => 'Sesión no válida']);
            }

            $idEval = (int) $this->request->getPost('id');
            if (!$idEval) {
                return $this->response->setJSON(['success' => false, 'message' => 'ID de evaluación no especificado']);
            }

            $db = \Config\Database::connect();

            // Resolver id_empleado desde sesión
            $empleado = $db->table('empleados')->where('id_usuario', $idUsuario)->get()->getRowArray();
            if (!$empleado) {
                return $this->response->setJSON(['success' => false, 'message' => 'Empleado no encontrado']);
            }
            $idEmpleado = $empleado['id_empleado'];

            // Verificar que la evaluación le pertenece y está completada
            $eval = $db->table('evaluaciones_empleados')
                ->where('id_evaluacion_empleado', $idEval)
                ->where('id_evaluador', $idEmpleado)
                ->get()->getRowArray();

            if (!$eval) {
                return $this->response->setJSON(['success' => false, 'message' => 'Evaluación no encontrada o sin permiso']);
            }

            if ($eval['puntaje_total'] === null || (float)$eval['puntaje_total'] == 0) {
                return $this->response->setJSON(['success' => false, 'message' => 'Solo se pueden archivar evaluaciones completadas']);
            }

            // Soft-hide: marca oculto_para_empleado = 1 (admin sigue viendo el registro)
            $db->table('evaluaciones_empleados')
               ->where('id_evaluacion_empleado', $idEval)
               ->update(['oculto_para_empleado' => 1]);

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Evaluación archivada correctamente. El administrador mantiene acceso a los datos.'
            ]);

        } catch (\Exception $e) {
            log_message('error', 'ocultarEvaluacion: ' . $e->getMessage());
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
