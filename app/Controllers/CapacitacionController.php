<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\CapacitacionModel;
use App\Models\EmpleadoCapacitacionModel;
use App\Models\EmpleadoModel;

class CapacitacionController extends Controller
{
    protected $capacitacionModel;
    protected $empleadoCapacitacionModel;
    protected $empleadoModel;

    public function __construct()
    {
        $this->capacitacionModel = new CapacitacionModel();
        $this->empleadoCapacitacionModel = new EmpleadoCapacitacionModel();
        $this->empleadoModel = new EmpleadoModel();
    }

    /**
     * Vista principal de capacitaciones (para administradores)
     */
    public function index()
    {
        $data = [
            'title' => 'Gestión de Capacitaciones',
            'capacitaciones' => $this->capacitacionModel->getCapacitacionesCompletas(),
            'empleados' => $this->empleadoModel->getEmpleadosActivos()
        ];

        return view('capacitaciones/index', $data);
    }

    /**
     * Capacitaciones disponibles (informativo para empleados)
     */
    public function disponibles()
    {
        $data = [
            'title' => 'Capacitaciones Disponibles',
            'capacitaciones' => $this->capacitacionModel->getCapacitacionesDisponibles(),
            'empleados' => $this->empleadoModel->getEmpleadosActivos()
        ];

        return view('capacitaciones/disponibles', $data);
    }

    /**
     * Capacitaciones por empleado (registro de completadas)
     */
    public function empleados()
    {
        $data = [
            'title' => 'Capacitaciones por Empleado',
            'empleados' => $this->empleadoModel->getEmpleadosConCapacitaciones(),
            'estadisticas' => $this->empleadoCapacitacionModel->getEstadisticasGenerales()
        ];

        return view('capacitaciones/empleados', $data);
    }

    /**
     * Formulario para crear nueva capacitación
     */
    public function crear()
    {
        $data = [
            'title' => 'Nueva Capacitación',
            'empleados' => $this->empleadoModel->getEmpleadosActivos(),
            'tipos' => ['Técnica', 'Pedagógica', 'Administrativa', 'Soft Skills', 'Otra']
        ];

        return view('capacitaciones/crear', $data);
    }

    /**
     * Guardar nueva capacitación
     */
    public function guardar()
    {
        // Validar datos
        $rules = [
            'nombre' => 'required|min_length[5]|max_length[255]',
            'descripcion' => 'required|min_length[10]',
            'tipo' => 'required|in_list[Técnica,Pedagógica,Administrativa,Soft Skills,Otra]',
            'fecha_inicio' => 'required|valid_date',
            'fecha_fin' => 'required|valid_date',
            'institucion' => 'required|min_length[2]|max_length[255]',
            'duracion_horas' => 'required|integer|greater_than[0]',
            'empleados_seleccionados' => 'required'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Procesar archivo si se subió
        $archivoCertificado = '';
        $file = $this->request->getFile('archivo_certificado');
        
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            $file->move(WRITEPATH . 'uploads/capacitaciones/', $newName);
            $archivoCertificado = $newName;
        }

        // Guardar capacitación
        $datosCapacitacion = [
            'nombre' => $this->request->getPost('nombre'),
            'descripcion' => $this->request->getPost('descripcion'),
            'tipo' => $this->request->getPost('tipo'),
            'fecha_inicio' => $this->request->getPost('fecha_inicio'),
            'fecha_fin' => $this->request->getPost('fecha_fin'),
            'institucion' => $this->request->getPost('institucion'),
            'duracion_horas' => $this->request->getPost('duracion_horas'),
            'archivo_certificado' => $archivoCertificado,
            'estado' => 'Activa',
            'creado_por' => session()->get('id_usuario')
        ];

        $idCapacitacion = $this->capacitacionModel->insert($datosCapacitacion);

        if ($idCapacitacion) {
            // Asignar empleados seleccionados
            $empleadosSeleccionados = $this->request->getPost('empleados_seleccionados');
            foreach ($empleadosSeleccionados as $idEmpleado) {
                $this->empleadoCapacitacionModel->insert([
                    'id_capacitacion' => $idCapacitacion,
                    'id_empleado' => $idEmpleado,
                    'estado' => 'Inscrito',
                    'fecha_inscripcion' => date('Y-m-d H:i:s')
                ]);
            }

            return redirect()->to('/capacitaciones')->with('mensaje', 'Capacitación creada exitosamente');
        } else {
            return redirect()->back()->withInput()->with('error', 'Error al crear la capacitación');
        }
    }

    /**
     * Formulario para editar capacitación
     */
    public function editar($id)
    {
        $capacitacion = $this->capacitacionModel->find($id);
        
        if (!$capacitacion) {
            return redirect()->to('/capacitaciones')->with('error', 'Capacitación no encontrada');
        }

        $data = [
            'title' => 'Editar Capacitación',
            'capacitacion' => $capacitacion,
            'empleados' => $this->empleadoModel->getEmpleadosActivos(),
            'empleadosAsignados' => $this->empleadoCapacitacionModel->getEmpleadosPorCapacitacion($id),
            'tipos' => ['Técnica', 'Pedagógica', 'Administrativa', 'Soft Skills', 'Otra']
        ];

        return view('capacitaciones/editar', $data);
    }

    /**
     * Actualizar capacitación
     */
    public function actualizar($id)
    {
        // Validar datos
        $rules = [
            'nombre' => 'required|min_length[5]|max_length[255]',
            'descripcion' => 'required|min_length[10]',
            'tipo' => 'required|in_list[Técnica,Pedagógica,Administrativa,Soft Skills,Otra]',
            'fecha_inicio' => 'required|valid_date',
            'fecha_fin' => 'required|valid_date',
            'institucion' => 'required|min_length[2]|max_length[255]',
            'duracion_horas' => 'required|integer|greater_than[0]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Procesar archivo si se subió
        $archivoCertificado = '';
        $file = $this->request->getFile('archivo_certificado');
        
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            $file->move(WRITEPATH . 'uploads/capacitaciones/', $newName);
            $archivoCertificado = $newName;
        }

        // Preparar datos
        $datos = [
            'nombre' => $this->request->getPost('nombre'),
            'descripcion' => $this->request->getPost('descripcion'),
            'tipo' => $this->request->getPost('tipo'),
            'fecha_inicio' => $this->request->getPost('fecha_inicio'),
            'fecha_fin' => $this->request->getPost('fecha_fin'),
            'institucion' => $this->request->getPost('institucion'),
            'duracion_horas' => $this->request->getPost('duracion_horas'),
            'estado' => $this->request->getPost('estado')
        ];

        if ($archivoCertificado) {
            $datos['archivo_certificado'] = $archivoCertificado;
        }

        // Actualizar capacitación
        if ($this->capacitacionModel->update($id, $datos)) {
            return redirect()->to('/capacitaciones')->with('mensaje', 'Capacitación actualizada exitosamente');
        } else {
            return redirect()->back()->withInput()->with('error', 'Error al actualizar la capacitación');
        }
    }

    /**
     * Eliminar capacitación
     */
    public function eliminar($id)
    {
        // Verificar que no haya empleados inscritos
        $empleadosInscritos = $this->empleadoCapacitacionModel->where('id_capacitacion', $id)->countAllResults();
        
        if ($empleadosInscritos > 0) {
            return redirect()->to('/capacitaciones')->with('error', 'No se puede eliminar una capacitación con empleados inscritos');
        }

        if ($this->capacitacionModel->delete($id)) {
            return redirect()->to('/capacitaciones')->with('mensaje', 'Capacitación eliminada exitosamente');
        } else {
            return redirect()->to('/capacitaciones')->with('error', 'Error al eliminar la capacitación');
        }
    }

    /**
     * Marcar capacitación como completada para un empleado
     */
    public function marcarCompletada()
    {
        $idEmpleadoCapacitacion = $this->request->getPost('id_empleado_capacitacion');
        $puntaje = $this->request->getPost('puntaje');
        $observaciones = $this->request->getPost('observaciones');

        $datos = [
            'estado' => 'Completada',
            'puntaje' => $puntaje,
            'observaciones' => $observaciones,
            'fecha_completado' => date('Y-m-d H:i:s')
        ];

        if ($this->empleadoCapacitacionModel->update($idEmpleadoCapacitacion, $datos)) {
            return $this->response->setJSON(['success' => true, 'message' => 'Capacitación marcada como completada']);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Error al actualizar el estado']);
        }
    }

    /**
     * Obtener estadísticas de capacitaciones
     */
    public function getEstadisticas()
    {
        $estadisticas = [
            'total_capacitaciones' => $this->capacitacionModel->countAll(),
            'capacitaciones_activas' => $this->capacitacionModel->where('estado', 'Activa')->countAllResults(),
            'total_inscripciones' => $this->empleadoCapacitacionModel->countAll(),
            'completadas' => $this->empleadoCapacitacionModel->where('estado', 'Completada')->countAllResults(),
            'en_progreso' => $this->empleadoCapacitacionModel->where('estado', 'Inscrito')->countAllResults()
        ];

        return $this->response->setJSON($estadisticas);
    }
}
