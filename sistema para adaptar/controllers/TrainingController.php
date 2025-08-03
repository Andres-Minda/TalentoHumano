<?php
// controllers/TrainingController.php

/**
 * Controlador para la gestión de Capacitaciones.
 * Por ahora, solo carga la vista principal.
 */
class TrainingController {
    public function index() {
        // Lógica para obtener y procesar datos de capacitaciones
        include_once '../views/modules/trainings.php';
    }
}
?>
