<?php
// views/modules/departments.php

// Vista para la sección de Departamentos.
// Aquí se mostraría la lista de departamentos y opciones CRUD.
?>

<h2 class="section-title">Gestión de Departamentos</h2>

<p>Aquí podrás gestionar los diferentes departamentos de la empresa. Ver, añadir, editar o eliminar departamentos.</p>

<div class="flex justify-end mb-4">
    <button class="btn btn-primary">
        <i class="fas fa-plus"></i> Añadir Departamento
    </button>
</div>

<div class="data-table-container">
    <table class="data-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Jefe</th>
                <th class="actions">Acciones</th>
            </tr>
        </thead>
        <tbody>
            <!-- Ejemplo de fila de datos (se llenaría con PHP desde la DB) -->
            <tr>
                <td>1</td>
                <td>Recursos Humanos</td>
                <td>Gestiona el talento y el bienestar del personal.</td>
                <td>Juan Pérez</td>
                <td class="actions">
                    <a href="#" title="Editar"><i class="fas fa-edit"></i></a>
                    <a href="#" title="Eliminar"><i class="fas fa-trash-alt"></i></a>
                </td>
            </tr>
            <tr>
                <td>2</td>
                <td>Tecnología</td>
                <td>Desarrollo y mantenimiento de la infraestructura tecnológica.</td>
                <td>Carlos Rodríguez</td>
                <td class="actions">
                    <a href="#" title="Editar"><i class="fas fa-edit"></i></a>
                    <a href="#" title="Eliminar"><i class="fas fa-trash-alt"></i></a>
                </td>
            </tr>
            <!-- Más filas... -->
        </tbody>
    </table>
</div>

<p class="info-message mt-4">Próximamente: Funcionalidad completa para la gestión de departamentos.</p>
