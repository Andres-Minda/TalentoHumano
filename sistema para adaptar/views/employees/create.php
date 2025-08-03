<?php
// views/employees/create.php

// Esta vista muestra el formulario para añadir un nuevo empleado.
// Los campos del formulario corresponden directamente a las columnas de la tabla 'empleados' en tu base de datos.
?>

<h2 class="section-title">Añadir Nuevo Empleado</h2>

<!-- El atributo 'action' define a dónde se enviarán los datos del formulario.
     'method="POST"' indica que los datos se enviarán usando el método POST.
     'class="ajax-form"' es crucial para que el JavaScript (main.js) intercepte el envío
     y lo maneje vía AJAX, evitando una recarga completa de la página. -->
<form action="index.php?controller=Employee&action=create" method="POST" class="ajax-form">
    <div class="form-group">
        <label for="tipo_empleado">Tipo de Empleado:</label>
        <select id="tipo_empleado" name="tipo_empleado" required>
            <option value="">Seleccione</option>
            <option value="Docente">Docente</option>
            <option value="Administrativo">Administrativo</option>
        </select>
    </div>

    <div class="form-group">
        <label for="cedula">Cédula:</label>
        <input type="text" id="cedula" name="cedula" required>
    </div>

    <div class="form-group">
        <label for="nombres">Nombres:</label>
        <input type="text" id="nombres" name="nombres" required>
    </div>

    <div class="form-group">
        <label for="apellidos">Apellidos:</label>
        <input type="text" id="apellidos" name="apellidos" required>
    </div>

    <div class="form-group">
        <label for="fecha_nacimiento">Fecha de Nacimiento:</label>
        <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" required>
    </div>

    <div class="form-group">
        <label for="genero">Género:</label>
        <select id="genero" name="genero">
            <option value="">Seleccione</option>
            <option value="Masculino">Masculino</option>
            <option value="Femenino">Femenino</option>
            <option value="Otro">Otro</option>
        </select>
    </div>

    <div class="form-group">
        <label for="estado_civil">Estado Civil:</label>
        <select id="estado_civil" name="estado_civil">
            <option value="">Seleccione</option>
            <option value="Soltero">Soltero</option>
            <option value="Casado">Casado</option>
            <option value="Divorciado">Divorciado</option>
            <option value="Viudo">Viudo</option>
            <option value="Union Libre">Unión Libre</option>
        </select>
    </div>

    <div class="form-group">
        <label for="direccion">Dirección:</label>
        <textarea id="direccion" name="direccion"></textarea>
    </div>

    <div class="form-group">
        <label for="telefono">Teléfono:</label>
        <input type="tel" id="telefono" name="telefono">
    </div>

    <div class="form-group">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
    </div>

    <div class="form-group">
        <label for="fecha_ingreso">Fecha de Ingreso:</label>
        <input type="date" id="fecha_ingreso" name="fecha_ingreso" required>
    </div>

    <div class="form-group">
        <label for="activo">Activo:</label>
        <!-- El checkbox envía '1' si está marcado, nada si no lo está.
             En el controlador, 'isset($_POST['activo']) ? 1 : 0' maneja esto. -->
        <input type="checkbox" id="activo" name="activo" value="1" checked>
    </div>

    <div class="form-group">
        <label for="foto_url">URL de la Foto:</label>
        <input type="text" id="foto_url" name="foto_url" placeholder="Ej: https://ejemplo.com/foto.jpg">
    </div>

    <div class="form-actions">
        <button type="submit" class="btn btn-primary">Guardar Empleado</button>
        <!-- Este botón "Cancelar" también usa AJAX para volver a la lista de empleados -->
        <a href="#" class="btn btn-secondary sidebar-link" data-path="controller=Employee&action=index">Cancelar</a>
    </div>
</form>

    <script>
        // Este script se ejecuta después de que la vista se carga dinámicamente por AJAX.
        // Llama a la función global para re-adjuntar los event listeners a los nuevos elementos del DOM.
        if (window.attachAllEventListeners) {
            window.attachAllEventListeners();
        } else {
            console.error("Error: La función attachAllEventListeners no está definida. main.js podría no estar cargando correctamente o contener errores.");
        }
    </script>
    