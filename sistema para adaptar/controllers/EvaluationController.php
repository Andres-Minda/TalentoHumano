<?php
// controllers/EvaluationController.php

/**
 * Controlador para la gestión de Evaluaciones de Desempeño.
 * Por ahora, solo carga la vista principal.
 */
class EvaluationController {
    public function index() {
        // Lógica para obtener y procesar datos de evaluaciones
        include_once '../views/modules/evaluations.php';
    }
}
?>
