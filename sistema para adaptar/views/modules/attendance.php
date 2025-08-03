<?php
// views/modules/attendance.php

// Vista para la sección de Asistencia.
// Aquí se registraría y controlaría la asistencia de los empleados.
?>

<h2 class="section-title">Control de Asistencia</h2>

<p>Monitorea y gestiona los registros de entrada y salida de los empleados, así como permisos y licencias.</p>

<div class="flex justify-end mb-4">
    <button class="btn btn-primary">
        <i class="fas fa-plus"></i> Registrar Asistencia
    </button>
</div>

<div class="data-table-container">
    <table class="data-table">
        <thead>
            <tr>
                <th>ID Empleado</th>
                <th>Fecha</th>
                <th>Hora Entrada</th>
                <th>Hora Salida</th>
                <th>Tipo</th>
                <th>Estado</th>
                <th class="actions">Acciones</th>
            </tr>
        </thead>
        <tbody>
            <!-- Ejemplo de fila de datos (se llenaría con PHP desde la DB) -->
            <tr>
                <td>1</td>
                <td>2025-06-27</td>
                <td>08:00</td>
                <td>17:00</td>
                <td>Normal</td>
                <td>Puntual</td>
                <td class="actions">
                    <a href="#" title="Ver Detalle"><i class="fas fa-eye"></i></a>
                </td>
            </tr>
            <tr>
                <td>2</td>
                <td>2025-06-27</td>
                <td>08:15</td>
                <td>17:00</td>
                <td>Normal</td>
                <td>Tardanza</td>
                <td class="actions">
                    <a href="#" title="Ver Detalle"><i class="fas fa-eye"></i></a>
                </td>
            </tr>
            <!-- Más filas... -->
        </tbody>
    </table>
</div>

<p class="info-message mt-4">Próximamente: Registros de asistencia y reportes detallados.</p>
