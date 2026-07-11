<?php

namespace App\Controllers\AdminTH;

use App\Controllers\BaseController;
use App\Models\PoliticaInasistenciaModel;

class PoliticasController extends BaseController
{
    protected $politicaModel;

    public function __construct()
    {
        $this->politicaModel = new PoliticaInasistenciaModel();
    }

    // Guardar (Crear)
    public function store()
    {
        if (!$this->request->isAJAX()) {
            return redirect()->back();
        }

        // 1. Auditoría: Loguear qué llega exactamente del frontend
        log_message('debug', 'Datos recibidos en store: ' . print_r($this->request->getPost(), true));

        // Prueba Forzada Opcional: Descomentar para ver en consola del navegador (rompe el JSON success)
        // die(json_encode($this->request->getPost()));

        // Validación manual de campos requeridos si no depende del modelo
        $validation = \Config\Services::validation();
        $validation->setRules([
            'nombre_politica' => 'required',
            'limite_mensual'  => 'required|numeric',
            'limite_trimestral'=> 'required|numeric',
            'limite_anual'    => 'required|numeric',
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            $errorValidations = $validation->getErrors();
            return $this->response->setJSON([
                'success' => false,
                'status'  => 'error', 
                'message' => implode(' | ', $errorValidations)
            ]);
        }

        try {
            $data = [
                'nombre_politica' => $this->request->getPost('nombre_politica'),
                'max_inasistencias_mes' => $this->request->getPost('limite_mensual'),
                'max_inasistencias_trimestre' => $this->request->getPost('limite_trimestral'),
                'max_inasistencias_anio' => $this->request->getPost('limite_anual'),
                'descripcion' => $this->request->getPost('descripcion'),
                'activo' => $this->request->getPost('estado')
            ];
            
            if ($this->request->getPost('acciones_por_exceso')) {
                $data['descripcion'] .= "\nAcciones por exceso: " . $this->request->getPost('acciones_por_exceso');
            }

            // 2. Control de Inserción estricto
            $inserted = $this->politicaModel->insert($data);

            if (!$inserted) {
                // Guardar los errores del modelo o de la BD MySQL
                $errorArray = $this->politicaModel->errors();
                
                if (empty($errorArray)) {
                    $dbError = $this->politicaModel->db->error();
                    $error = (!empty($dbError['message'])) ? $dbError['message'] : 'La base de datos rechazó el registro';
                } else {
                    $error = implode(' | ', $errorArray);
                }
                
                return $this->response->setJSON([
                    'status'  => 'error',
                    'success' => false, 
                    'message' => $error
                ]);
            }

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Política guardada correctamente.'
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'status'  => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    // Obtener datos para el modal de edición
    public function edit($id)
    {
        if (!$this->request->isAJAX()) {
            return redirect()->back();
        }

        $politica = $this->politicaModel->find($id);

        if (!$politica) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Política no encontrada.'
            ]);
        }

        return $this->response->setJSON([
            'success' => true,
            'data' => $politica
        ]);
    }

    // Actualizar política
    public function update($id)
    {
        if (!$this->request->isAJAX()) {
            return redirect()->back();
        }

        log_message('debug', "Datos recibidos en update de politica {$id}: " . print_r($this->request->getPost(), true));

        try {
            $politica = $this->politicaModel->find($id);

            if (!$politica) {
                return $this->response->setJSON(['success' => false, 'message' => 'Política no encontrada.']);
            }

            $data = [
                'nombre_politica' => $this->request->getPost('nombre_politica'),
                'max_inasistencias_mes' => $this->request->getPost('limite_mensual'),
                'max_inasistencias_trimestre' => $this->request->getPost('limite_trimestral'),
                'max_inasistencias_anio' => $this->request->getPost('limite_anual'),
                'descripcion' => $this->request->getPost('descripcion'),
                'activo' => $this->request->getPost('estado')
            ];

            if ($this->request->getPost('acciones_por_exceso')) {
                $data['descripcion'] .= "\nAcciones por exceso: " . $this->request->getPost('acciones_por_exceso');
            }

            $updated = $this->politicaModel->update($id, $data);

            if (!$updated) {
                // Guardar los errores del modelo o de la BD MySQL
                $errorArray = $this->politicaModel->errors();
                
                if (empty($errorArray)) {
                    $dbError = $this->politicaModel->db->error();
                    $error = (!empty($dbError['message'])) ? $dbError['message'] : 'La base de datos rechazó la actualización';
                } else {
                    $error = implode(' | ', $errorArray);
                }
                
                return $this->response->setJSON([
                    'status'  => 'error',
                    'success' => false, 
                    'message' => $error
                ]);
            }

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Política actualizada correctamente.'
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'status'  => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    // Mostrar (Ver)
    public function show($id)
    {
        if (!$this->request->isAJAX()) {
            return redirect()->back();
        }

        $politica = $this->politicaModel->find($id);

        if (!$politica) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Política no encontrada.'
            ]);
        }

        return $this->response->setJSON([
            'success' => true,
            'data' => $politica
        ]);
    }

    // Eliminar
    public function delete($id)
    {
        if (!$this->request->isAJAX()) {
            return redirect()->back();
        }

        try {
            $politica = $this->politicaModel->find($id);

            if (!$politica) {
                return $this->response->setJSON(['success' => false, 'message' => 'Política no encontrada.']);
            }

            $this->politicaModel->delete($id);

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Política eliminada correctamente.'
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error en PoliticasController::delete - ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al eliminar la política.'
            ]);
        }
    }
}
