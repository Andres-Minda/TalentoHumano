<?php
// views/modules/reports.php

// Vista para la sección de Reportes.
// Aquí se generarían y visualizarían métricas y estadísticas clave.
?>

<h2 class="section-title">Generación de Reportes</h2>

<p>Accede a reportes detallados y estadísticas para obtener una visión completa del desempeño del talento humano.</p>

<div class="report-options" style="display: flex; flex-wrap: wrap; gap: 15px; margin-top: 20px;">
    <button class="btn btn-primary">
        <i class="fas fa-chart-bar"></i> Reporte de Empleados
    </button>
    <button class="btn btn-primary">
        <i class="fas fa-chart-pie"></i> Reporte de Asistencia
    </button>
    <button class="btn btn-primary">
        <i class="fas fa-file-invoice-dollar"></i> Reporte de Nómina
    </button>
    <button class="btn btn-primary">
        <i class="fas fa-chart-line"></i> Reporte de Evaluaciones
    </button>
    <button class="btn btn-primary">
        <i class="fas fa-book-reader"></i> Reporte de Capacitaciones
    </button>
    <button class="btn btn-primary">
        <i class="fas fa-users-cog"></i> Reporte de Departamentos
    </button>
</div>

<div class="report-area mt-4 p-4 border rounded" style="min-height: 200px; background-color: var(--light-gray);">
    <p class="text-center text-muted">Selecciona un tipo de reporte para visualizar los datos aquí.</p>
    <!-- Aquí se cargaría el contenido del reporte específico -->
</div>

<p class="info-message mt-4">Próximamente: Filtros de fecha, exportación a Excel/PDF y gráficos interactivos.</p>
