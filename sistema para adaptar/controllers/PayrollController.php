<?php
// controllers/PayrollController.php

/**
 * Controlador para la gestión de Nóminas.
 * Por ahora, solo carga la vista principal.
 */
class PayrollController {
    public function index() {
        // Lógica para obtener y procesar datos de nóminas
        include_once '../views/modules/payrolls.php';
    }
}
?>
