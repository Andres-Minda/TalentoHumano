<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\SolicitudCapacitacionModel;
use App\Models\EmpleadoModel;
use App\Models\CapacitacionModel;

class SolicitudCapacitacionController extends Controller
{
    protected $solicitudModel;
    protected $empleadoModel;
    protected $capacitacionModel;

    public function __construct()
    {
        $this->solicitudModel = new SolicitudCapacitacionModel();
        $this->empleadoModel = new EmpleadoModel();
        $this->capacitacionModel = new CapacitacionModel();
    }

    /**
     * Vista principal de solicitudes de capacitación (para administradores)
     */
    public function index()
    {
        $data = [
            'title' => 'Solicitudes de Capacitación',
            'solicitudes' => $this->solicitudModel->getSolicitudesCompletas(),
            'estadisticas' => $this->solicitudModel->getEstadisticas()
        ];

        return view('solicitudes_capacitacion/index', $data);
    }

    /**
     * Formulario para crear nueva solicitud de capacitación
     */
    public function crear()
    {
        $data = [
            'title' => 'Nueva Solicitud de Capacitación',
            'tipos_capacitacion' => ['Técnica', 'Pedagógica', 'Administrativa', 'Soft Skills', 'Otra'],
            'prioridades' => ['Baja', 'Media', 'Alta', 'Crítica'],
            'justificaciones' => [
                'Actualización de conocimientos',
                'Requerimiento del puesto',
                'Desarrollo profesional',
                'Mejora de competencias',
                'Cumplimiento de estándares',
                'Otro'
            ]
        ];

        return view('solicitudes_capacitacion/crear', $data);
    }

    /**
     * Guardar nueva solicitud de capacitación
     */
    public function guardar()
    {
        // Validar datos
        $rules = [
            'tipo_capacitacion' => 'required|in_list[Técnica,Pedagógica,Administrativa,Soft Skills,Otra]',
            'nombre_capacitacion' => 'required|min_length[5]|max_length[255]',
            'descripcion' => 'required|min_length[10]|max_length[1000]',
            'justificacion' => 'required|min_length[10]|max_length[500]',
            'prioridad' => 'required|in_list[Baja,Media,Alta,Crítica]',
            'fecha_deseada' => 'required|valid_date',
            'duracion_estimada' => 'required|integer|greater_than[0]|less_than_equal_to[200]',
            'institucion_preferida' => 'permit_empty|min_length[2]|max_length[255]',
            'costo_estimado' => 'permit_empty|decimal|greater_than_equal_to[0]',
            'beneficios_esperados' => 'required|min_length[10]|max_length[500]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Guardar solicitud
        $datosSolicitud = [
            'id_empleado' => session()->get('id_empleado'),
            'tipo_capacitacion' => $this->request->getPost('tipo_capacitacion'),
            'nombre_capacitacion' => $this->request->getPost('nombre_capacitacion'),
            'descripcion' => $this->request->getPost('descripcion'),
            'justificacion' => $this->request->getPost('justificacion'),
            'prioridad' => $this->request->getPost('prioridad'),
            'fecha_deseada' => $this->request->getPost('fecha_deseada'),
            'duracion_estimada' => $this->request->getPost('duracion_estimada'),
            'institucion_preferida' => $this->request->getPost('institucion_preferida'),
            'costo_estimado' => $this->request->getPost('costo_estimado'),
            'beneficios_esperados' => $this->request->getPost('beneficios_esperados'),
            'estado' => 'Pendiente',
            'creado_por' => session()->get('id_usuario')
        ];

        $idSolicitud = $this->solicitudModel->insert($datosSolicitud);

        if ($idSolicitud) {
            return redirect()->to('/empleado/solicitudes-capacitacion')->with('mensaje', 'Solicitud de capacitación enviada exitosamente');
        } else {
            return redirect()->back()->withInput()->with('error', 'Error al enviar la solicitud');
        }
    }

    /**
     * Ver solicitud específica
     */
    public function ver($idSolicitud)
    {
        $solicitud = $this->solicitudModel->getSolicitudCompleta($idSolicitud);
        
        if (!$solicitud) {
            return redirect()->to('/solicitudes-capacitacion')->with('error', 'Solicitud no encontrada');
        }

        // Verificar permisos
        $idEmpleado = session()->get('id_empleado');
        $esAdmin = in_array(session()->get('nombre_rol'), ['AdminTalentoHumano', 'SuperAdministrador']);
        
        if (!$esAdmin && $solicitud['id_empleado'] != $idEmpleado) {
            return redirect()->to('/empleado/solicitudes-capacitacion')->with('error', 'No tiene permisos para ver esta solicitud');
        }

        $data = [
            'title' => 'Solicitud de Capacitación',
            'solicitud' => $solicitud,
            'es_admin' => $esAdmin
        ];

        return view('solicitudes_capacitacion/ver', $data);
    }

    /**
     * Aprobar solicitud de capacitación
     */
    public function aprobar($idSolicitud)
    {
        $solicitud = $this->solicitudModel->find($idSolicitud);
        
        if (!$solicitud) {
            return $this->response->setJSON(['success' => false, 'message' => 'Solicitud no encontrada']);
        }

        if ($solicitud['estado'] !== 'Pendiente') {
            return $this->response->setJSON(['success' => false, 'message' => 'La solicitud no está en estado pendiente']);
        }

        $datos = [
            'estado' => 'Aprobada',
            'fecha_aprobacion' => date('Y-m-d H:i:s'),
            'aprobado_por' => session()->get('id_usuario'),
            'observaciones_admin' => $this->request->getPost('observaciones')
        ];

        if ($this->solicitudModel->update($idSolicitud, $datos)) {
            return $this->response->setJSON(['success' => true, 'message' => 'Solicitud aprobada exitosamente']);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Error al aprobar la solicitud']);
        }
    }

    /**
     * Rechazar solicitud de capacitación
     */
    public function rechazar($idSolicitud)
    {
        $solicitud = $this->solicitudModel->find($idSolicitud);
        
        if (!$solicitud) {
            return $this->response->setJSON(['success' => false, 'message' => 'Solicitud no encontrada']);
        }

        if ($solicitud['estado'] !== 'Pendiente') {
            return $this->response->setJSON(['success' => false, 'message' => 'La solicitud no está en estado pendiente']);
        }

        $datos = [
            'estado' => 'Rechazada',
            'fecha_rechazo' => date('Y-m-d H:i:s'),
            'rechazado_por' => session()->get('id_usuario'),
            'motivo_rechazo' => $this->request->getPost('motivo_rechazo')
        ];

        if ($this->solicitudModel->update($idSolicitud, $datos)) {
            return $this->response->setJSON(['success' => true, 'message' => 'Solicitud rechazada exitosamente']);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Error al rechazar la solicitud']);
        }
    }

    /**
     * Convertir solicitud en capacitación
     */
    public function convertirEnCapacitacion($idSolicitud)
    {
        $solicitud = $this->solicitudModel->find($idSolicitud);
        
        if (!$solicitud) {
            return $this->response->setJSON(['success' => false, 'message' => 'Solicitud no encontrada']);
        }

        if ($solicitud['estado'] !== 'Aprobada') {
            return $this->response->setJSON(['success' => false, 'message' => 'Solo se pueden convertir solicitudes aprobadas']);
        }

        // Crear capacitación basada en la solicitud
        $datosCapacitacion = [
            'nombre' => $solicitud['nombre_capacitacion'],
            'descripcion' => $solicitud['descripcion'],
            'tipo' => $solicitud['tipo_capacitacion'],
            'fecha_inicio' => $solicitud['fecha_deseada'],
            'fecha_fin' => date('Y-m-d', strtotime($solicitud['fecha_deseada'] . ' +' . $solicitud['duracion_estimada'] . ' days')),
            'institucion' => $solicitud['institucion_preferida'] ?: 'Por definir',
            'duracion_horas' => $solicitud['duracion_estimada'],
            'estado' => 'Activa',
            'creado_por' => session()->get('id_usuario'),
            'origen_solicitud' => $idSolicitud
        ];

        $idCapacitacion = $this->capacitacionModel->insert($datosCapacitacion);

        if ($idCapacitacion) {
            // Asignar empleado que solicitó la capacitación
            $this->capacitacionModel->asignarEmpleado($idCapacitacion, $solicitud['id_empleado']);

            // Actualizar estado de la solicitud
            $this->solicitudModel->update($idSolicitud, [
                'estado' => 'Convertida en Capacitación',
                'id_capacitacion_creada' => $idCapacitacion,
                'fecha_conversion' => date('Y-m-d H:i:s')
            ]);

            return $this->response->setJSON([
                'success' => true, 
                'message' => 'Solicitud convertida en capacitación exitosamente',
                'id_capacitacion' => $idCapacitacion
            ]);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Error al crear la capacitación']);
        }
    }

    /**
     * Vista para empleados ver sus solicitudes
     */
    public function misSolicitudes()
    {
        $idEmpleado = session()->get('id_empleado');
        
        $data = [
            'title' => 'Mis Solicitudes de Capacitación',
            'solicitudes' => $this->solicitudModel->getSolicitudesPorEmpleado($idEmpleado),
            'estadisticas' => $this->solicitudModel->getEstadisticasPorEmpleado($idEmpleado)
        ];

        return view('solicitudes_capacitacion/mis_solicitudes', $data);
    }

    /**
     * Editar solicitud (solo si está pendiente)
     */
    public function editar($idSolicitud)
    {
        $solicitud = $this->solicitudModel->find($idSolicitud);
        
        if (!$solicitud) {
            return redirect()->to('/empleado/solicitudes-capacitacion')->with('error', 'Solicitud no encontrada');
        }

        // Verificar que sea el empleado que la creó y esté pendiente
        $idEmpleado = session()->get('id_empleado');
        if ($solicitud['id_empleado'] != $idEmpleado) {
            return redirect()->to('/empleado/solicitudes-capacitacion')->with('error', 'No tiene permisos para editar esta solicitud');
        }

        if ($solicitud['estado'] !== 'Pendiente') {
            return redirect()->to('/empleado/solicitudes-capacitacion')->with('error', 'Solo se pueden editar solicitudes pendientes');
        }

        $data = [
            'title' => 'Editar Solicitud de Capacitación',
            'solicitud' => $solicitud,
            'tipos_capacitacion' => ['Técnica', 'Pedagógica', 'Administrativa', 'Soft Skills', 'Otra'],
            'prioridades' => ['Baja', 'Media', 'Alta', 'Crítica'],
            'justificaciones' => [
                'Actualización de conocimientos',
                'Requerimiento del puesto',
                'Desarrollo profesional',
                'Mejora de competencias',
                'Cumplimiento de estándares',
                'Otro'
            ]
        ];

        return view('solicitudes_capacitacion/editar', $data);
    }

    /**
     * Actualizar solicitud
     */
    public function actualizar($idSolicitud)
    {
        $solicitud = $this->solicitudModel->find($idSolicitud);
        
        if (!$solicitud) {
            return redirect()->to('/empleado/solicitudes-capacitacion')->with('error', 'Solicitud no encontrada');
        }

        // Verificar permisos
        $idEmpleado = session()->get('id_empleado');
        if ($solicitud['id_empleado'] != $idEmpleado) {
            return redirect()->to('/empleado/solicitudes-capacitacion')->with('error', 'No tiene permisos para editar esta solicitud');
        }

        if ($solicitud['estado'] !== 'Pendiente') {
            return redirect()->to('/empleado/solicitudes-capacitacion')->with('error', 'Solo se pueden editar solicitudes pendientes');
        }

        // Validar datos
        $rules = [
            'tipo_capacitacion' => 'required|in_list[Técnica,Pedagógica,Administrativa,Soft Skills,Otra]',
            'nombre_capacitacion' => 'required|min_length[5]|max_length[255]',
            'descripcion' => 'required|min_length[10]|max_length[1000]',
            'justificacion' => 'required|min_length[10]|max_length[500]',
            'prioridad' => 'required|in_list[Baja,Media,Alta,Crítica]',
            'fecha_deseada' => 'required|valid_date',
            'duracion_estimada' => 'required|integer|greater_than[0]|less_than_equal_to[200]',
            'institucion_preferida' => 'permit_empty|min_length[2]|max_length[255]',
            'costo_estimado' => 'permit_empty|decimal|greater_than_equal_to[0]',
            'beneficios_esperados' => 'required|min_length[10]|max_length[500]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Actualizar solicitud
        $datos = [
            'tipo_capacitacion' => $this->request->getPost('tipo_capacitacion'),
            'nombre_capacitacion' => $this->request->getPost('nombre_capacitacion'),
            'descripcion' => $this->request->getPost('descripcion'),
            'justificacion' => $this->request->getPost('justificacion'),
            'prioridad' => $this->request->getPost('prioridad'),
            'fecha_deseada' => $this->request->getPost('fecha_deseada'),
            'duracion_estimada' => $this->request->getPost('duracion_estimada'),
            'institucion_preferida' => $this->request->getPost('institucion_preferida'),
            'costo_estimado' => $this->request->getPost('costo_estimado'),
            'beneficios_esperados' => $this->request->getPost('beneficios_esperados'),
            'fecha_modificacion' => date('Y-m-d H:i:s')
        ];

        if ($this->solicitudModel->update($idSolicitud, $datos)) {
            return redirect()->to('/empleado/solicitudes-capacitacion')->with('mensaje', 'Solicitud actualizada exitosamente');
        } else {
            return redirect()->back()->withInput()->with('error', 'Error al actualizar la solicitud');
        }
    }

    /**
     * Cancelar solicitud
     */
    public function cancelar($idSolicitud)
    {
        $solicitud = $this->solicitudModel->find($idSolicitud);
        
        if (!$solicitud) {
            return $this->response->setJSON(['success' => false, 'message' => 'Solicitud no encontrada']);
        }

        // Verificar permisos
        $idEmpleado = session()->get('id_empleado');
        if ($solicitud['id_empleado'] != $idEmpleado) {
            return $this->response->setJSON(['success' => false, 'message' => 'No tiene permisos para cancelar esta solicitud']);
        }

        if ($solicitud['estado'] !== 'Pendiente') {
            return $this->response->setJSON(['success' => false, 'message' => 'Solo se pueden cancelar solicitudes pendientes']);
        }

        $datos = [
            'estado' => 'Cancelada',
            'fecha_cancelacion' => date('Y-m-d H:i:s'),
            'motivo_cancelacion' => $this->request->getPost('motivo_cancelacion')
        ];

        if ($this->solicitudModel->update($idSolicitud, $datos)) {
            return $this->response->setJSON(['success' => true, 'message' => 'Solicitud cancelada exitosamente']);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Error al cancelar la solicitud']);
        }
    }

    /**
     * Obtener estadísticas de solicitudes
     */
    public function getEstadisticas()
    {
        $estadisticas = $this->solicitudModel->getEstadisticas();
        return $this->response->setJSON($estadisticas);
    }
}
