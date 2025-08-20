<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\EvaluacionModel;
use App\Models\CategoriaEvaluacionModel;
use App\Models\PreguntaEvaluacionModel;
use App\Models\RespuestaEvaluacionModel;
use App\Models\EmpleadoModel;

class EvaluacionController extends Controller
{
    protected $evaluacionModel;
    protected $categoriaModel;
    protected $preguntaModel;
    protected $respuestaModel;
    protected $empleadoModel;

    public function __construct()
    {
        $this->evaluacionModel = new EvaluacionModel();
        $this->categoriaModel = new CategoriaEvaluacionModel();
        $this->preguntaModel = new PreguntaEvaluacionModel();
        $this->respuestaModel = new RespuestaEvaluacionModel();
        $this->empleadoModel = new EmpleadoModel();
    }

    /**
     * Vista principal de evaluaciones (para administradores)
     */
    public function index()
    {
        $data = [
            'title' => 'Gestión de Evaluaciones',
            'evaluaciones' => $this->evaluacionModel->getEvaluacionesCompletas(),
            'empleados' => $this->empleadoModel->getEmpleadosActivos(),
            'estadisticas' => $this->evaluacionModel->getEstadisticas()
        ];

        return view('evaluaciones/index', $data);
    }

    /**
     * Formulario para crear nueva evaluación
     */
    public function crear()
    {
        $data = [
            'title' => 'Nueva Evaluación',
            'empleados' => $this->empleadoModel->getEmpleadosActivos(),
            'tipos' => ['Desempeño', 'Competencias', 'Objetivos', '360 Grados', 'Autoevaluación', 'Otro'],
            'periodos' => $this->getPeriodosEvaluacion()
        ];

        return view('evaluaciones/crear', $data);
    }

    /**
     * Guardar nueva evaluación
     */
    public function guardar()
    {
        // Validar datos básicos de la evaluación
        $rules = [
            'nombre' => 'required|min_length[5]|max_length[255]',
            'descripcion' => 'required|min_length[10]',
            'tipo' => 'required|in_list[Desempeño,Competencias,Objetivos,360 Grados,Autoevaluación,Otro]',
            'periodo' => 'required',
            'fecha_inicio' => 'required|valid_date',
            'fecha_fin' => 'required|valid_date',
            'empleados_evaluar' => 'required'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Procesar empleados a evaluar
        $empleadosEvaluar = $this->request->getPost('empleados_evaluar');
        if (!is_array($empleadosEvaluar) || empty($empleadosEvaluar)) {
            return redirect()->back()->withInput()->with('error', 'Debe seleccionar al menos un empleado para evaluar');
        }

        // Guardar evaluación
        $datosEvaluacion = [
            'nombre' => $this->request->getPost('nombre'),
            'descripcion' => $this->request->getPost('descripcion'),
            'tipo' => $this->request->getPost('tipo'),
            'periodo' => $this->request->getPost('periodo'),
            'fecha_inicio' => $this->request->getPost('fecha_inicio'),
            'fecha_fin' => $this->request->getPost('fecha_fin'),
            'estado' => 'Activa',
            'creado_por' => session()->get('id_usuario')
        ];

        $idEvaluacion = $this->evaluacionModel->insert($datosEvaluacion);

        if ($idEvaluacion) {
            // Asignar empleados a evaluar
            foreach ($empleadosEvaluar as $idEmpleado) {
                $this->evaluacionModel->asignarEmpleado($idEvaluacion, $idEmpleado);
            }

            return redirect()->to('/evaluaciones/estructura/' . $idEvaluacion)->with('mensaje', 'Evaluación creada exitosamente. Ahora configure la estructura de categorías y preguntas.');
        } else {
            return redirect()->back()->withInput()->with('error', 'Error al crear la evaluación');
        }
    }

    /**
     * Configurar estructura de la evaluación (categorías y preguntas)
     */
    public function estructura($idEvaluacion)
    {
        $evaluacion = $this->evaluacionModel->find($idEvaluacion);
        
        if (!$evaluacion) {
            return redirect()->to('/evaluaciones')->with('error', 'Evaluación no encontrada');
        }

        $data = [
            'title' => 'Estructura de Evaluación: ' . $evaluacion['nombre'],
            'evaluacion' => $evaluacion,
            'categorias' => $this->categoriaModel->getCategoriasPorEvaluacion($idEvaluacion),
            'preguntas' => $this->preguntaModel->getPreguntasPorEvaluacion($idEvaluacion)
        ];

        return view('evaluaciones/estructura', $data);
    }

    /**
     * Guardar categoría de evaluación
     */
    public function guardarCategoria()
    {
        $rules = [
            'id_evaluacion' => 'required|integer|greater_than[0]',
            'nombre' => 'required|min_length[3]|max_length[255]',
            'descripcion' => 'permit_empty|max_length[500]',
            'peso' => 'required|integer|greater_than[0]|less_than_equal_to[100]'
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON(['success' => false, 'errors' => $this->validator->getErrors()]);
        }

        $datos = [
            'id_evaluacion' => $this->request->getPost('id_evaluacion'),
            'nombre' => $this->request->getPost('nombre'),
            'descripcion' => $this->request->getPost('descripcion'),
            'peso' => $this->request->getPost('peso')
        ];

        $idCategoria = $this->categoriaModel->insert($datos);

        if ($idCategoria) {
            return $this->response->setJSON([
                'success' => true, 
                'message' => 'Categoría guardada exitosamente',
                'categoria' => $this->categoriaModel->find($idCategoria)
            ]);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Error al guardar la categoría']);
        }
    }

    /**
     * Guardar pregunta de evaluación
     */
    public function guardarPregunta()
    {
        $rules = [
            'id_evaluacion' => 'required|integer|greater_than[0]',
            'id_categoria' => 'required|integer|greater_than[0]',
            'pregunta' => 'required|min_length[5]|max_length[500]',
            'tipo_respuesta' => 'required|in_list[Escala,Texto,Selección múltiple,Selección única]',
            'opciones' => 'permit_empty',
            'peso' => 'required|integer|greater_than[0]|less_than_equal_to[100]'
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON(['success' => false, 'errors' => $this->validator->getErrors()]);
        }

        $datos = [
            'id_evaluacion' => $this->request->getPost('id_evaluacion'),
            'id_categoria' => $this->request->getPost('id_categoria'),
            'pregunta' => $this->request->getPost('pregunta'),
            'tipo_respuesta' => $this->request->getPost('tipo_respuesta'),
            'opciones' => $this->request->getPost('opciones'),
            'peso' => $this->request->getPost('peso')
        ];

        $idPregunta = $this->preguntaModel->insert($datos);

        if ($idPregunta) {
            return $this->response->setJSON([
                'success' => true, 
                'message' => 'Pregunta guardada exitosamente',
                'pregunta' => $this->preguntaModel->find($idPregunta)
            ]);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Error al guardar la pregunta']);
        }
    }

    /**
     * Eliminar categoría
     */
    public function eliminarCategoria($idCategoria)
    {
        // Verificar que no tenga preguntas
        $preguntas = $this->preguntaModel->where('id_categoria', $idCategoria)->countAllResults();
        
        if ($preguntas > 0) {
            return $this->response->setJSON(['success' => false, 'message' => 'No se puede eliminar una categoría que tiene preguntas']);
        }

        if ($this->categoriaModel->delete($idCategoria)) {
            return $this->response->setJSON(['success' => true, 'message' => 'Categoría eliminada exitosamente']);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Error al eliminar la categoría']);
        }
    }

    /**
     * Eliminar pregunta
     */
    public function eliminarPregunta($idPregunta)
    {
        // Verificar que no tenga respuestas
        $respuestas = $this->respuestaModel->where('id_pregunta', $idPregunta)->countAllResults();
        
        if ($respuestas > 0) {
            return $this->response->setJSON(['success' => false, 'message' => 'No se puede eliminar una pregunta que tiene respuestas']);
        }

        if ($this->preguntaModel->delete($idPregunta)) {
            return $this->response->setJSON(['success' => true, 'message' => 'Pregunta eliminada exitosamente']);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Error al eliminar la pregunta']);
        }
    }

    /**
     * Vista para que los empleados realicen evaluaciones
     */
    public function realizar($idEvaluacion)
    {
        $evaluacion = $this->evaluacionModel->find($idEvaluacion);
        
        if (!$evaluacion) {
            return redirect()->to('/empleado/evaluaciones')->with('error', 'Evaluación no encontrada');
        }

        // Verificar que el empleado esté asignado a esta evaluación
        $idEmpleado = session()->get('id_empleado');
        if (!$this->evaluacionModel->empleadoAsignado($idEvaluacion, $idEmpleado)) {
            return redirect()->to('/empleado/evaluaciones')->with('error', 'No está asignado a esta evaluación');
        }

        // Verificar que no haya completado la evaluación
        if ($this->evaluacionModel->evaluacionCompletada($idEvaluacion, $idEmpleado)) {
            return redirect()->to('/empleado/evaluaciones')->with('error', 'Ya completó esta evaluación');
        }

        $data = [
            'title' => 'Realizar Evaluación: ' . $evaluacion['nombre'],
            'evaluacion' => $evaluacion,
            'categorias' => $this->categoriaModel->getCategoriasConPreguntas($idEvaluacion),
            'id_empleado' => $idEmpleado
        ];

        return view('evaluaciones/realizar', $data);
    }

    /**
     * Guardar respuestas de la evaluación
     */
    public function guardarRespuestas()
    {
        $idEvaluacion = $this->request->getPost('id_evaluacion');
        $idEmpleado = $this->request->getPost('id_empleado');
        $respuestas = $this->request->getPost('respuestas');

        if (!$respuestas || !is_array($respuestas)) {
            return $this->response->setJSON(['success' => false, 'message' => 'No se recibieron respuestas']);
        }

        // Verificar que no haya completado la evaluación
        if ($this->evaluacionModel->evaluacionCompletada($idEvaluacion, $idEmpleado)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Ya completó esta evaluación']);
        }

        $success = true;
        $mensajes = [];

        foreach ($respuestas as $idPregunta => $respuesta) {
            $datos = [
                'id_evaluacion' => $idEvaluacion,
                'id_empleado' => $idEmpleado,
                'id_pregunta' => $idPregunta,
                'respuesta' => $respuesta,
                'fecha_respuesta' => date('Y-m-d H:i:s')
            ];

            if (!$this->respuestaModel->insert($datos)) {
                $success = false;
                $mensajes[] = "Error al guardar respuesta para pregunta $idPregunta";
            }
        }

        if ($success) {
            // Marcar evaluación como completada
            $this->evaluacionModel->marcarCompletada($idEvaluacion, $idEmpleado);
            
            return $this->response->setJSON([
                'success' => true, 
                'message' => 'Evaluación completada exitosamente'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false, 
                'message' => 'Error al guardar algunas respuestas',
                'errors' => $mensajes
            ]);
        }
    }

    /**
     * Ver resultados de una evaluación
     */
    public function resultados($idEvaluacion)
    {
        $evaluacion = $this->evaluacionModel->find($idEvaluacion);
        
        if (!$evaluacion) {
            return redirect()->to('/evaluaciones')->with('error', 'Evaluación no encontrada');
        }

        $data = [
            'title' => 'Resultados de Evaluación: ' . $evaluacion['nombre'],
            'evaluacion' => $evaluacion,
            'resultados' => $this->evaluacionModel->getResultadosEvaluacion($idEvaluacion),
            'estadisticas' => $this->evaluacionModel->getEstadisticasEvaluacion($idEvaluacion)
        ];

        return view('evaluaciones/resultados', $data);
    }

    /**
     * Obtener estadísticas de evaluaciones
     */
    public function getEstadisticas()
    {
        $estadisticas = $this->evaluacionModel->getEstadisticas();
        return $this->response->setJSON($estadisticas);
    }

    /**
     * Lista de períodos de evaluación
     */
    private function getPeriodosEvaluacion()
    {
        return [
            'Enero-Junio 2024',
            'Julio-Diciembre 2024',
            'Enero-Junio 2025',
            'Julio-Diciembre 2025',
            'Anual 2024',
            'Anual 2025',
            'Otro'
        ];
    }
}
