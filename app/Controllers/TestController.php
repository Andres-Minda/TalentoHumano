<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class TestController extends Controller
{
    public function index()
    {
        try {
            $db = \Config\Database::connect();
            
            // Test basic database connection
            $result = $db->query("SELECT 1 as test")->getRow();
            
            // Test models
            $empleadoModel = new \App\Models\EmpleadoModel();
            $usuarios = $empleadoModel->getAllEmpleadosCompletos();
            
            $capacitacionModel = new \App\Models\CapacitacionModel();
            $capacitaciones = $capacitacionModel->getCapacitacionesConEstadisticas();
            
            $data = [
                'success' => true,
                'message' => 'Database connection successful',
                'empleados_count' => count($usuarios),
                'capacitaciones_count' => count($capacitaciones),
                'test_result' => $result
            ];
            
            return $this->response->setJSON($data);
            
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Database connection failed: ' . $e->getMessage(),
                'error' => $e->getTraceAsString()
            ]);
        }
    }
} 