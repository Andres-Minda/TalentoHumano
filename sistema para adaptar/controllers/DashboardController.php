<?php
// controllers/DashboardController.php

/**
 * Controlador para la sección principal del Dashboard.
 * Maneja la lógica para mostrar el resumen ejecutivo.
 */
class DashboardController {
    public function index() {
        // Lógica para obtener datos del resumen ejecutivo (métricas clave)
        // Por ahora, solo se carga la vista. En una aplicación real,
        // se interactuaría con los modelos para obtener datos.
        include_once '../views/dashboard/index.php';
    }

    public function home() {
        // Esta es la vista que se cargará por AJAX en el inicio.
        // Aquí podrías incluir métricas o un mensaje de bienvenida.
        echo "<h2>Bienvenido al Sistema de Gestión de Talento Humano</h2>";
        echo "<p>Selecciona una opción del menú lateral para comenzar.</p>";
        // Ejemplo de métricas ficticias para el resumen ejecutivo
        echo "<div class='summary-metrics'>";
        echo "  <div class='metric-card'><span class='metric-value'>150</span><span class='metric-label'>Empleados Activos</span></div>";
        echo "  <div class='metric-card'><span class='metric-value'>12</span><span class='metric-label'>Departamentos</span></div>";
        echo "  <div class='metric-card'><span class='metric-value'>5</span><span class='metric-label'>Vacantes Abiertas</span></div>";
        echo "  <div class='metric-card'><span class='metric-value'>95%</span><span class='metric-label'>Tasa de Asistencia</span></div>";
        echo "</div>";
    }
}
?>
