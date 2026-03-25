<?php

namespace App\Controllers;

use App\Models\TokenEvaluacionModel;
use App\Models\EvaluacionModel;
use App\Models\EmpleadoModel;
use CodeIgniter\Controller;

/**
 * Controlador PÚBLICO para evaluaciones estudiantiles.
 * No requiere autenticación — los estudiantes acceden via token único.
 */
class EvaluacionEstudiantilController extends Controller
{
    protected $tokenModel;

    public function __construct()
    {
        $this->tokenModel = new TokenEvaluacionModel();
    }

    /**
     * GET /evaluacion-estudiantil/{token}
     * Muestra el formulario de evaluación si el token es válido.
     */
    public function index($token = null)
    {
        if (!$token) {
            return view('evaluacion_estudiantil/error', [
                'titulo'  => 'Enlace Inválido',
                'mensaje' => 'No se proporcionó un enlace de evaluación válido.'
            ]);
        }

        // Validar token
        $tokenData = $this->tokenModel->validarToken($token);

        if (!$tokenData) {
            return view('evaluacion_estudiantil/error', [
                'titulo'  => 'Enlace No Disponible',
                'mensaje' => 'Este enlace de evaluación ya fue utilizado, ha expirado o es inválido. Si crees que esto es un error, contacta al administrador.'
            ]);
        }

        // Obtener datos del docente
        $db = \Config\Database::connect();
        $docente = $db->table('empleados')
            ->select('id_empleado, nombres, apellidos, departamento, tipo_docente')
            ->where('id_empleado', $tokenData['id_docente'])
            ->get()
            ->getRowArray();

        if (!$docente) {
            return view('evaluacion_estudiantil/error', [
                'titulo'  => 'Error',
                'mensaje' => 'No se encontró el docente asociado a esta evaluación.'
            ]);
        }

        // Obtener la evaluación
        $evaluacion = $db->table('evaluaciones')
            ->where('id_evaluacion', $tokenData['id_evaluacion'])
            ->get()
            ->getRowArray();

        // Obtener categorías y preguntas activas
        $categorias = $db->table('categorias_evaluacion')
            ->where('activa', 1)
            ->orderBy('id', 'ASC')
            ->get()
            ->getResultArray();

        $preguntas = $db->table('preguntas_evaluacion')
            ->where('activa', 1)
            ->orderBy('categoria_id', 'ASC')
            ->orderBy('id', 'ASC')
            ->get()
            ->getResultArray();

        // Agrupar preguntas por categoría
        $preguntasPorCategoria = [];
        foreach ($preguntas as $p) {
            $preguntasPorCategoria[$p['categoria_id']][] = $p;
        }

        return view('evaluacion_estudiantil/formulario', [
            'titulo'                => 'Evaluación Docente',
            'token'                 => $token,
            'tokenData'             => $tokenData,
            'docente'               => $docente,
            'evaluacion'            => $evaluacion,
            'categorias'            => $categorias,
            'preguntasPorCategoria' => $preguntasPorCategoria,
        ]);
    }

    /**
     * POST /evaluacion-estudiantil/{token}
     * Procesa las respuestas del estudiante.
     */
    public function guardar($token = null)
    {
        if (!$this->request->is('post') || !$token) {
            return $this->response->setJSON(['success' => false, 'message' => 'Método no permitido']);
        }

        $db = \Config\Database::connect();

        try {
            // Re-validar token
            $tokenData = $this->tokenModel->validarToken($token);
            if (!$tokenData) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Este enlace ya no es válido.'
                ]);
            }

            $respuestas = $this->request->getPost('respuestas');
            $observaciones = $this->request->getPost('observaciones') ?? '';

            if (empty($respuestas) || !is_array($respuestas)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Debes responder al menos una pregunta.'
                ]);
            }

            $db->transStart();

            // 1. Calcular puntaje total promedio
            $puntajes = array_values($respuestas);
            $puntajeTotal = count($puntajes) > 0
                ? round(array_sum($puntajes) / count($puntajes), 2)
                : 0;

            // 2. Insertar en evaluaciones_empleados (evaluación anónima)
            $db->table('evaluaciones_empleados')->insert([
                'id_evaluacion'    => $tokenData['id_evaluacion'],
                'id_empleado'      => $tokenData['id_docente'],
                'id_evaluador'     => null,  // Anónimo
                'tipo_evaluador'   => 'Estudiante',
                'token_anonimo'    => $token,
                'grupo_curso'      => $tokenData['grupo_curso'],
                'fecha_evaluacion' => date('Y-m-d'),
                'puntaje_total'    => $puntajeTotal,
                'observaciones'    => $observaciones,
            ]);

            $idEvaluacionEmpleado = $db->insertID();

            // 3. Insertar detalles por pregunta en detalles_evaluacion
            foreach ($respuestas as $idPregunta => $puntaje) {
                $db->table('detalles_evaluacion')->insert([
                    'id_evaluacion_empleado' => $idEvaluacionEmpleado,
                    'id_competencia'         => (int)$idPregunta,
                    'puntaje'                => (float)$puntaje,
                    'comentarios'            => null,
                ]);
            }

            // 4. Marcar token como usado
            $this->tokenModel->marcarUsado($token, $this->request->getIPAddress());

            $db->transComplete();

            if ($db->transStatus() === false) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Error al guardar la evaluación. Intente nuevamente.'
                ]);
            }

            return $this->response->setJSON([
                'success' => true,
                'message' => '¡Gracias por tu participación! Tu evaluación ha sido registrada de manera anónima.'
            ]);

        } catch (\Exception $e) {
            $db->transRollback();
            log_message('error', 'Error en evaluación estudiantil: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Ocurrió un error inesperado. Intente nuevamente.'
            ]);
        }
    }
}
