<?php
// views/modules/trainings.php

// Vista para la sección de Capacitaciones.
// Aquí se registrarían y harían seguimiento a los cursos y entrenamientos.
?>

<h2 class="section-title">Gestión de Capacitaciones</h2>

<p>Gestiona el plan de desarrollo profesional de los empleados a través de cursos y entrenamientos.</p>

<div class="flex justify-end mb-4">
    <button class="btn btn-primary">
        <i class="fas fa-plus"></i> Añadir Capacitación
    </button>
</div>

<div class="data-table-container">
    <table class="data-table">
        <thead>
            <tr>
                <th>ID Capacitación</th>
                <th>Nombre</th>
                <th>Tipo</th>
                <th>Fecha Inicio</th>
                <th>Costo</th>
                <th class="actions">Acciones</th>
            </tr>
        </thead>
        <tbody>
            <!-- Ejemplo de fila de datos (se llenaría con PHP desde la DB) -->
            <tr>
                <td>1</td>
                <td>Curso de Liderazgo Ágil</td>
                <td>Desarrollo de habilidades</td>
                <td>2025-08-01</td>
                <td>$500.00</td>
                <td class="actions">
                    <a href="#" title="Ver Detalle"><i class="fas fa-eye"></i></a>
                    <a href="#" title="Editar"><i class="fas fa-edit"></i></a>
                </td>
            </tr>
            <tr>
                <td>2</td>
                <td>Taller de MySQL Avanzado</td>
                <td>Técnico</td>
                <td>2025-07-10</td>
                <td>$300.00</td>
                <td class="actions">
                    <a href="#" title="Ver Detalle"><i class="fas fa-eye"></i></a>
                    <a href="#" title="Editar"><i class="fas fa-edit"></i></a>
                </td>
            </tr>
            <!-- Más filas... -->
        </tbody>
    </table>
</div>

<p class="info-message mt-4">Próximamente: Seguimiento de participantes, historial de capacitaciones.</p>
