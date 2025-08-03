<?php
// views/modules/recruitment.php

// Vista para la sección de Reclutamiento.
// Aquí se gestionarían las vacantes, candidatos y postulaciones.
?>

<h2 class="section-title">Gestión de Reclutamiento</h2>

<p>Administra el ciclo completo de reclutamiento: desde la publicación de vacantes hasta la contratación de nuevos talentos.</p>

<div class="flex justify-end mb-4">
    <button class="btn btn-primary">
        <i class="fas fa-briefcase"></i> Publicar Vacante
    </button>
    <button class="btn btn-secondary ml-2">
        <i class="fas fa-address-card"></i> Ver Candidatos
    </button>
</div>

<div class="data-table-container">
    <table class="data-table">
        <thead>
            <tr>
                <th>ID Vacante</th>
                <th>Puesto</th>
                <th>Fecha Publicación</th>
                <th>Fecha Cierre</th>
                <th>Estado</th>
                <th class="actions">Acciones</th>
            </tr>
        </thead>
        <tbody>
            <!-- Ejemplo de fila de datos (se llenaría con PHP desde la DB) -->
            <tr>
                <td>1</td>
                <td>Analista de Sistemas</td>
                <td>2025-06-01</td>
                <td>2025-07-15</td>
                <td>Abierta</td>
                <td class="actions">
                    <a href="#" title="Ver Postulaciones"><i class="fas fa-users"></i></a>
                    <a href="#" title="Editar"><i class="fas fa-edit"></i></a>
                </td>
            </tr>
            <tr>
                <td>2</td>
                <td>Profesor de Química</td>
                <td>2025-05-20</td>
                <td>2025-06-30</td>
                <td>Abierta</td>
                <td class="actions">
                    <a href="#" title="Ver Postulaciones"><i class="fas fa-users"></i></a>
                    <a href="#" title="Editar"><i class="fas fa-edit"></i></a>
                </td>
            </tr>
            <!-- Más filas... -->
        </tbody>
    </table>
</div>

<p class="info-message mt-4">Próximamente: Flujo de postulaciones, entrevistas y selección de personal.</p>
