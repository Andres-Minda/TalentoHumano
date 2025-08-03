<?php
// views/employees/edit.php

// Esta vista muestra el formulario para editar un empleado existente.
// $employee_data contiene los datos del empleado a editar, cargados desde el controlador.
?>

<h2 class="section-title">Editar Empleado</h2>

<?php if (isset($employee_data) && $employee_data): ?>
<form action="index.php?controller=Employee&action=edit&id=<?php echo htmlspecialchars($employee_data['id_empleado']); ?>" method="POST" class="ajax-form">
    <div class="form-group">
        <label for="tipo_empleado">Tipo de Empleado:</label>
        <select id="tipo_empleado" name="tipo_empleado" required>
            <option value="">Seleccione</option>
            <option value="Docente" <?php echo ($employee_data['tipo_empleado'] == 'Docente') ? 'selected' : ''; ?>>Docente</option>
            <option value="Administrativo" <?php echo ($employee_data['tipo_empleado'] == 'Administrativo') ? 'selected' : ''; ?>>Administrativo</option>
        </select>
    </div>

    <div class="form-group">
        <label for="cedula">Cédula:</label>
        <input type="text" id="cedula" name="cedula" value="<?php echo htmlspecialchars($employee_data['cedula']); ?>" required>
    </div>

    <div class="form-group">
        <label for="nombres">Nombres:</label>
        <input type="text" id="nombres" name="nombres" value="<?php echo htmlspecialchars($employee_data['nombres']); ?>" required>
    </div>

    <div class="form-group">
        <label for="apellidos">Apellidos:</label>
        <input type="text" id="apellidos" name="apellidos" value="<?php echo htmlspecialchars($employee_data['apellidos']); ?>" required>
    </div>

    <div class="form-group">
        <label for="fecha_nacimiento">Fecha de Nacimiento:</label>
        <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" value="<?php echo htmlspecialchars($employee_data['fecha_nacimiento']); ?>" required>
    </div>

    <div class="form-group">
        <label for="genero">Género:</label>
        <select id="genero" name="genero">
            <option value="">Seleccione</option>
            <option value="Masculino" <?php echo ($employee_data['genero'] == 'Masculino') ? 'selected' : ''; ?>>Masculino</option>
            <option value="Femenino" <?php echo ($employee_data['genero'] == 'Femenino') ? 'selected' : ''; ?>>Femenino</option>
            <option value="Otro" <?php echo ($employee_data['genero'] == 'Otro') ? 'selected' : ''; ?>>Otro</option>
        </select>
    </div>

    <div class="form-group">
        <label for="estado_civil">Estado Civil:</label>
        <select id="estado_civil" name="estado_civil">
            <option value="">Seleccione</option>
            <option value="Soltero" <?php echo ($employee_data['estado_civil'] == 'Soltero') ? 'selected' : ''; ?>>Soltero</option>
            <option value="Casado" <?php echo ($employee_data['estado_civil'] == 'Casado') ? 'selected' : ''; ?>>Casado</option>
            <option value="Divorciado" <?php echo ($employee_data['estado_civil'] == 'Divorciado') ? 'selected' : ''; ?>>Divorciado</option>
            <option value="Viudo" <?php echo ($employee_data['estado_civil'] == 'Viudo') ? 'selected' : ''; ?>>Viudo</option>
            <option value="Union Libre" <?php echo ($employee_data['estado_civil'] == 'Union Libre') ? 'selected' : ''; ?>>Unión Libre</option>
        </select>
    </div>

    <div class="form-group">
        <label for="direccion">Dirección:</label>
        <textarea id="direccion" name="direccion"><?php echo htmlspecialchars($employee_data['direccion']); ?></textarea>
    </div>

    <div class="form-group">
        <label for="telefono">Teléfono:</label>
        <input type="tel" id="telefono" name="telefono" value="<?php echo htmlspecialchars($employee_data['telefono']); ?>">
    </div>

    <div class="form-group">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($employee_data['email']); ?>" required>
    </div>

    <div class="form-group">
        <label for="fecha_ingreso">Fecha de Ingreso:</label>
        <input type="date" id="fecha_ingreso" name="fecha_ingreso" value="<?php echo htmlspecialchars($employee_data['fecha_ingreso']); ?>" required>
    </div>

    <div class="form-group">
        <label for="activo">Activo:</label>
        <input type="checkbox" id="activo" name="activo" value="1" <?php echo $employee_data['activo'] ? 'checked' : ''; ?>>
    </div>

    <div class="form-group">
        <label for="foto_url">URL de la Foto:</label>
        <input type="text" id="foto_url" name="foto_url" value="<?php echo htmlspecialchars($employee_data['foto_url']); ?>">
    </div>

    <div class="form-actions">
        <button type="submit" class="btn btn-primary">Actualizar Empleado</button>
        <a href="#" class="btn btn-secondary sidebar-link" data-path="controller=Employee&action=index">Cancelar</a>
    </div>
</form>
<?php else: ?>
    <p class="error-message">Empleado no encontrado.</p>
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
    
