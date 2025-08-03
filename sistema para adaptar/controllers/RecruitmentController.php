<?php
// controllers/RecruitmentController.php

/**
 * Controlador para la gestión de Reclutamiento.
 * Por ahora, solo carga la vista principal.
 */
class RecruitmentController {
    public function index() {
        // Lógica para obtener y procesar datos de reclutamiento (vacantes, candidatos, postulaciones)
        include_once '../views/modules/recruitment.php';
    }
}
?>
