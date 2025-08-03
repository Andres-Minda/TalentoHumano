<?php
// views/employees/list.php

// Esta vista muestra la lista de empleados.
// Se carga dinámicamente en el área de contenido principal del dashboard.

// $stmt y $num son variables que vienen desde EmployeeController->index()
?>

<h2 class="section-title">Gestión de Empleados</h2>

<div class="flex justify-end mb-4">
    <a href="#" class="btn btn-primary sidebar-link" data-path="controller=Employee&action=create">
        <i class="fas fa-plus"></i> Añadir Empleado
    </a>
</div>

<?php if ($num > 0): ?>
    <table class="data-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Cédula</th>
                <th>Nombres</th>
                <th>Apellidos</th>
                <th>Email</th>
                <th>Fecha Ingreso</th>
                <th>Activo</th>
                <th class="actions">Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                <?php extract($row); // Extrae las variables del array asociativo ?>
                <tr>
                    <td><?php echo htmlspecialchars($id_empleado); ?></td>
                    <td><?php echo htmlspecialchars($cedula); ?></td>
                    <td><?php echo htmlspecialchars($nombres); ?></td>
                    <td><?php echo htmlspecialchars($apellidos); ?></td>
                    <td><?php echo htmlspecialchars($email); ?></td>
                    <td><?php echo htmlspecialchars($fecha_ingreso); ?></td>
                    <td><?php echo $activo ? '<span style="color: green;">Sí</span>' : '<span style="color: red;">No</span>'; ?></td>
                    <td class="actions">
                        <a href="#" class="sidebar-link" data-path="controller=Employee&action=edit&id=<?php echo htmlspecialchars($id_empleado); ?>" title="Editar">
                            <i class="fas fa-edit"></i>
                        </a>
                        <a href="#" class="delete-btn" data-path="controller=Employee&action=delete&id=<?php echo htmlspecialchars($id_empleado); ?>" title="Eliminar">
                            <i class="fas fa-trash-alt"></i>
                        </a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
<?php else: ?>
    <p class="info-message">No hay empleados registrados aún.</p>
<?php endif; ?>
    <script>
        // Este script se ejecuta después de que la vista se carga dinámicamente por AJAX.
        // Llama a la función global para re-adjuntar los event listeners a los nuevos elementos del DOM.
        if (window.attachAllEventListeners) {
            window.attachAllEventListeners();
        } else {
            console.error("Error: La función attachAllEventListeners no está definida. main.js podría no estar cargando correctamente o contener errores.");
        }
    </script>
    

