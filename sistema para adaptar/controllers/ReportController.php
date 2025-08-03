<?php
// controllers/ReportController.php

/**
 * Controlador para la generación de Reportes.
 * Por ahora, solo carga la vista principal.
 */
class ReportController {
    public function index() {
        // Lógica para generar y mostrar diferentes tipos de reportes
        include_once '../views/modules/reports.php';
    }
}
?>
