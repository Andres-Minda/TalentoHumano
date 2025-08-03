<?php
// controllers/DepartmentController.php

/**
 * Controlador para la gestión de Departamentos.
 * Por ahora, solo carga la vista principal.
 */
class DepartmentController {
    public function index() {
        // En una aplicación completa, aquí se obtendrían los datos de departamentos desde un modelo
        // $departments = $this->departmentModel->readAll();
        include_once '../views/modules/departments.php';
    }
}
?>
