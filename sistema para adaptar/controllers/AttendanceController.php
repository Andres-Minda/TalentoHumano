<?php
// controllers/AttendanceController.php

/**
 * Controlador para la gestión de Asistencia.
 * Por ahora, solo carga la vista principal.
 */
class AttendanceController {
    public function index() {
        // Lógica para obtener y procesar datos de asistencia
        include_once '../views/modules/attendance.php';
    }
}
?>
