<?php

namespace App\Controllers\AdminTH;

use App\Models\PeriodoAcademicoModel;
use CodeIgniter\Controller;

class PeriodoAcademicoController extends Controller
{
    protected $periodoModel;

    public function __construct()
    {
        $this->periodoModel = new PeriodoAcademicoModel();
    }

    public function index()
    {
        $data = [
            'titulo' => 'Gestión de Periodos Académicos',
            'usuario' => [
                'nombres' => session()->get('nombres'),
                'apellidos' => session()->get('apellidos'),
                'rol' => session()->get('nombre_rol')
            ]
        ];

        return view('Roles/AdminTH/periodos_academicos', $data);
    }

    public function obtener()
    {
        try {
            $periodos = $this->periodoModel->orderBy('fecha_inicio', 'DESC')->findAll();
            return $this->response->setJSON([
                'success' => true,
                'data' => $periodos
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error al obtener periodos: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al obtener periodos: ' . $e->getMessage()
            ]);
        }
    }

    public function guardar()
    {
        if (!$this->request->is('post')) {
            return $this->response->setJSON(['success' => false, 'message' => 'Método no permitido']);
        }

        try {
            $datos = $this->request->getPost();
            
            if (empty($datos['nombre']) || empty($datos['fecha_inicio']) || empty($datos['fecha_fin'])) {
                return $this->response->setJSON(['success' => false, 'message' => 'Llene todos los campos requeridos (nombre, inicio, fin)']);
            }

            $db = \Config\Database::connect();
            
            if (!empty($datos['id_periodo'])) {
                $periodoExistente = $this->periodoModel->find($datos['id_periodo']);
                if ($periodoExistente && $periodoExistente['estado'] === 'Cerrado') {
                    return $this->response->setJSON(['success' => false, 'message' => 'No se puede modificar un periodo que ya está Cerrado (Histórico).']);
                }
            }

            $db->transStart();

            // Si se está guardando como Activo, desactivar los demás
            if (($datos['estado'] ?? '') === 'Activo') {
                $db->table('periodos_academicos')->update(['estado' => 'Inactivo'], ['estado' => 'Activo']);
            }

            $periodoData = [
                'nombre' => $datos['nombre'],
                'fecha_inicio' => $datos['fecha_inicio'],
                'fecha_fin' => $datos['fecha_fin'],
                'estado' => $datos['estado'] ?? 'Inactivo',
                'descripcion' => $datos['descripcion'] ?? ''
            ];

            if (!empty($datos['id_periodo'])) {
                // Actualizar
                $this->periodoModel->update($datos['id_periodo'], $periodoData);
                $mensaje = 'Periodo académico actualizado exitosamente';
            } else {
                // Crear
                $this->periodoModel->insert($periodoData);
                $mensaje = 'Periodo académico creado exitosamente';
            }

            $db->transComplete();

            if ($db->transStatus() === false) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Error de BD al guardar el periodo académico'
                ]);
            }

            return $this->response->setJSON([
                'success' => true,
                'message' => $mensaje
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Error al guardar periodo: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error general al guardar periodo: ' . $e->getMessage()
            ]);
        }
    }

    public function cambiarEstado()
    {
        if (!$this->request->is('post')) {
            return $this->response->setJSON(['success' => false, 'message' => 'Método no permitido']);
        }

        try {
            $id = $this->request->getPost('id_periodo');
            $nuevoEstado = $this->request->getPost('estado');

            if (empty($id) || empty($nuevoEstado)) {
                return $this->response->setJSON(['success' => false, 'message' => 'Datos incompletos para procesar el estado']);
            }

            $periodoExistente = $this->periodoModel->find($id);
            if ($periodoExistente && $periodoExistente['estado'] === 'Cerrado' && $nuevoEstado !== 'Cerrado') {
                return $this->response->setJSON(['success' => false, 'message' => 'Un periodo Cerrado (Histórico) no puede volver a cambiar de estado.']);
            }

            $db = \Config\Database::connect();
            $db->transStart();

            // Si se activa, desactivar a los demás
            if ($nuevoEstado === 'Activo') {
                $db->table('periodos_academicos')->update(['estado' => 'Inactivo'], ['estado' => 'Activo']);
            }

            $this->periodoModel->update($id, ['estado' => $nuevoEstado]);

            $db->transComplete();

            if ($db->transStatus() === false) {
                return $this->response->setJSON(['success' => false, 'message' => 'Fallo la transacción de BD']);
            }

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Estado cambiado exitosamente'
            ]);

        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Excepción lanzada: ' . $e->getMessage()
            ]);
        }
    }

    public function eliminar()
    {
        if (!$this->request->is('post')) {
            return $this->response->setJSON(['success' => false, 'message' => 'Método no permitido']);
        }

        try {
            $id = $this->request->getPost('id_periodo');
            if (empty($id)) {
                return $this->response->setJSON(['success' => false, 'message' => 'ID requerido']);
            }

            $periodo = $this->periodoModel->find($id);
            if ($periodo && $periodo['estado'] === 'Activo') {
                return $this->response->setJSON(['success' => false, 'message' => 'No se puede eliminar el periodo Activo.']);
            }
            if ($periodo && $periodo['estado'] === 'Cerrado') {
                return $this->response->setJSON(['success' => false, 'message' => 'No se puede eliminar un periodo Cerrado por fines de auditoría histórica.']);
            }

            $this->periodoModel->delete($id);

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Periodo eliminado exitosamente'
            ]);

        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al eliminar periodo: ' . $e->getMessage()
            ]);
        }
    }
}
