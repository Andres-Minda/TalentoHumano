<?php
// views/modules/evaluations.php

// Vista para la sección de Evaluaciones de Desempeño.
// Aquí se gestionarían las evaluaciones y el feedback.
?>

<h2 class="section-title">Gestión de Evaluaciones de Desempeño</h2>

<p>Administra las evaluaciones periódicas, registra resultados y genera planes de mejora para el personal.</p>

<div class="flex justify-end mb-4">
    <button class="btn btn-primary">
        <i class="fas fa-clipboard-list"></i> Crear Evaluación
    </button>
</div>

<div class="data-table-container">
    <table class="data-table">
        <thead>
            <tr>
                <th>ID Evaluación</th>
                <th>Nombre</th>
                <th>Fecha Inicio</th>
                <th>Fecha Fin</th>
                <th>Estado</th>
                <th class="actions">Acciones</th>
            </tr>
        </thead>
        <tbody>
            <!-- Ejemplo de fila de datos (se llenaría con PHP desde la DB) -->
            <tr>
                <td>1</td>
                <td>Evaluación Anual 2025</td>
                <td>2025-09-01</td>
                <td>2025-10-31</td>
                <td>Planificada</td>
                <td class="actions">
                    <a href="#" title="Ver Detalle"><i class="fas fa-eye"></i></a>
                    <a href="#" title="Editar"><i class="fas fa-edit"></i></a>
                </td>
            </tr>
            <tr>
                <td>2</td>
                <td>Evaluación Q2 2025</td>
                <td>2025-04-01</td>
                <td>2025-06-30</td>
                <td>Finalizada</td>
                <td class="actions">
                    <a href="#" title="Ver Detalle"><i class="fas fa-eye"></i></a>
                </td>
            </tr>
            <!-- Más filas... -->
        </tbody>
    </table>
</div>

<p class="info-message mt-4">Próximamente: Planes de mejora, historial de evaluaciones y competencias.</p>
