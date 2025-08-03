<?php
// views/modules/edit_profile.php

// Vista para la sección de edición de perfil del administrador.
// Esta vista muestra un formulario para cambiar datos del administrador.

// Se asume que $message y $admin_data (si es GET) están disponibles desde el controlador.
// Si no están definidos (por ejemplo, si se accede directamente a la vista sin el controlador),
// se inicializan con valores por defecto.
$message = $message ?? '';
$admin_data = $admin_data ?? ['name' => '', 'email' => '', 'current_password' => ''];
?>

<h2 class="section-title">Editar Mi Perfil</h2>

<?php if ($message): ?>
    <?php echo $message; ?>
<?php endif; ?>

<form action="index.php?controller=User&action=editProfile" method="POST" class="ajax-form">
    <div class="form-group">
        <label for="name">Nombre Completo:</label>
        <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($admin_data['name']); ?>" required>
    </div>

    <div class="form-group">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($admin_data['email']); ?>" required>
    </div>

    <div class="form-group">
        <label for="new_password">Nueva Contraseña (dejar en blanco para no cambiar):</label>
        <input type="password" id="new_password" name="new_password">
        <small class="text-muted">Mínimo 8 caracteres, incluyendo mayúsculas, minúsculas y números.</small>
    </div>

    <div class="form-group">
        <label for="confirm_password">Confirmar Nueva Contraseña:</label>
        <input type="password" id="confirm_password" name="confirm_password">
    </div>

    <div class="form-actions">
        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
        <!-- Este botón "Cancelar" también usa AJAX para volver al Dashboard -->
        <a href="#" class="btn btn-secondary sidebar-link" data-path="controller=Dashboard&action=home">Cancelar</a>
    </div>
</form>

<script>
    // IMPORTANTE: Este script asegura que los enlaces AJAX y formularios funcionen correctamente
    // cuando esta vista es cargada dinámicamente.

    // Re-adjunta los event listeners a los enlaces que deben cargar contenido vía AJAX.
    document.querySelectorAll('.sidebar-link').forEach(link => {
        link.removeEventListener('click', handleSidebarLinkClick); // Evita duplicados
        link.addEventListener('click', handleSidebarLinkClick);
    });

    function handleSidebarLinkClick(event) {
        event.preventDefault();
        const path = this.getAttribute('data-path');
        if (path) {
            window.loadContent(`index.php?${path}`);
        }
    }

    // Nota: El manejo del submit del formulario (clase 'ajax-form') ya está en main.js
    // con un listener global, por lo que no es necesario re-adjuntarlo aquí.
</script>
