<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>

<div class="page-wrapper">
    <div class="page-content">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <h4 class="mb-0">Mi Cuenta - Administrador Talento Humano</h4>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <!-- Cambiar Contraseña -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-shield-lock me-2"></i>Cambiar Contraseña
                        </h5>
                    </div>
                    <div class="card-body">
                        <form action="<?= base_url('index.php/cuenta/cambiarPassword') ?>" method="post">
                            <?= csrf_field() ?>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label class="form-label">Contraseña Actual</label>
                                        <input type="password" class="form-control" name="password_actual" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Nueva Contraseña</label>
                                        <input type="password" class="form-control" name="password_nuevo" minlength="6" required>
                                        <small class="text-muted">Mínimo 6 caracteres</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Confirmar Nueva Contraseña</label>
                                        <input type="password" class="form-control" name="password_confirmar" minlength="6" required>
                                    </div>
                                </div>
                            </div>
                            <div class="text-end">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-shield-check me-1"></i>Cambiar Contraseña
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Configuración de Notificaciones -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-bell me-2"></i>Configuración de Notificaciones
                        </h5>
                    </div>
                    <div class="card-body">
                        <form action="<?= base_url('index.php/cuenta/configuracionNotificaciones') ?>" method="post">
                            <?= csrf_field() ?>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="notificaciones_email" id="notificacionesEmail" checked>
                                            <label class="form-check-label" for="notificacionesEmail">
                                                Notificaciones por Email
                                            </label>
                                            <small class="text-muted d-block">Solicitudes nuevas, actualizaciones de estado</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="notificaciones_sms" id="notificacionesSMS">
                                            <label class="form-check-label" for="notificacionesSMS">
                                                Notificaciones por SMS
                                            </label>
                                            <small class="text-muted d-block">Alertas urgentes por mensaje de texto</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="notificaciones_push" id="notificacionesPush" checked>
                                            <label class="form-check-label" for="notificacionesPush">
                                                Notificaciones Push
                                            </label>
                                            <small class="text-muted d-block">Alertas en tiempo real del sistema</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="notificaciones_dashboard" id="notificacionesDashboard" checked>
                                            <label class="form-check-label" for="notificacionesDashboard">
                                                Notificaciones del Dashboard
                                            </label>
                                            <small class="text-muted d-block">Resúmenes diarios y reportes</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="text-end">
                                <button type="submit" class="btn btn-success">
                                    <i class="bi bi-save me-1"></i>Guardar Configuración
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Configuración de Privacidad -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-eye me-2"></i>Configuración de Privacidad
                        </h5>
                    </div>
                    <div class="card-body">
                        <form action="<?= base_url('index.php/cuenta/configuracionPrivacidad') ?>" method="post">
                            <?= csrf_field() ?>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="perfil_publico" id="perfilPublico">
                                            <label class="form-check-label" for="perfilPublico">
                                                Perfil Público
                                            </label>
                                            <small class="text-muted d-block">Permitir que otros usuarios vean tu información básica</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="mostrar_actividad" id="mostrarActividad" checked>
                                            <label class="form-check-label" for="mostrarActividad">
                                                Mostrar Actividad
                                            </label>
                                            <small class="text-muted d-block">Mostrar tu actividad reciente en el sistema</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="historial_acceso" id="historialAcceso" checked>
                                            <label class="form-check-label" for="historialAcceso">
                                                Historial de Acceso
                                            </label>
                                            <small class="text-muted d-block">Mantener registro de tus accesos al sistema</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="sesiones_multiples" id="sesionesMultiples">
                                            <label class="form-check-label" for="sesionesMultiples">
                                                Sesiones Múltiples
                                            </label>
                                            <small class="text-muted d-block">Permitir múltiples sesiones simultáneas</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="text-end">
                                <button type="submit" class="btn btn-info">
                                    <i class="bi bi-shield-check me-1"></i>Guardar Privacidad
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <!-- Información de la Cuenta -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-person-circle me-2"></i>Información de la Cuenta
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <i class="bi bi-person text-primary me-2"></i>
                            <span><strong>Usuario:</strong> <?= session()->get('nombres') ?> <?= session()->get('apellidos') ?></span>
                        </div>
                        <div class="d-flex align-items-center mb-3">
                            <i class="bi bi-envelope text-success me-2"></i>
                            <span><strong>Email:</strong> <?= session()->get('email') ?></span>
                        </div>
                        <div class="d-flex align-items-center mb-3">
                            <i class="bi bi-shield text-warning me-2"></i>
                            <span><strong>Rol:</strong> <?= session()->get('nombre_rol') ?></span>
                        </div>
                        <div class="d-flex align-items-center mb-3">
                            <i class="bi bi-calendar text-info me-2"></i>
                            <span><strong>Último Acceso:</strong> <?= date('d/m/Y H:i', session()->get('login_time') ?? time()) ?></span>
                        </div>
                        <div class="d-flex align-items-center">
                            <i class="bi bi-check-circle text-success me-2"></i>
                            <span><strong>Estado:</strong> Activo</span>
                        </div>
                    </div>
                </div>

                <!-- Acciones de Seguridad -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-shield-lock me-2"></i>Acciones de Seguridad
                        </h5>
                    </div>
                    <div class="card-body">
                        <button class="btn btn-warning w-100 mb-2" data-bs-toggle="modal" data-bs-target="#changePasswordModal">
                            <i class="bi bi-key me-2"></i>Cambiar Contraseña
                        </button>
                        <button class="btn btn-outline-danger w-100 mb-2" onclick="cerrarTodasSesiones()">
                            <i class="bi bi-x-circle me-2"></i>Cerrar Todas las Sesiones
                        </button>
                        <button class="btn btn-outline-secondary w-100" onclick="cerrarSesion()">
                            <i class="bi bi-box-arrow-right me-2"></i>Cerrar Sesión
                        </button>
                    </div>
                </div>

                <!-- Historial de Actividad -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-clock-history me-2"></i>Actividad Reciente
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="flex-shrink-0">
                                <div class="avatar-sm rounded-circle bg-success">
                                    <span class="avatar-title text-white">
                                        <i class="bi bi-box-arrow-in-right"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-1">Acceso al Sistema</h6>
                                <small class="text-muted">Hace 2 minutos</small>
                            </div>
                        </div>
                        <div class="d-flex align-items-center mb-3">
                            <div class="flex-shrink-0">
                                <div class="avatar-sm rounded-circle bg-info">
                                    <span class="avatar-title text-white">
                                        <i class="bi bi-gear"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-1">Configuración Actualizada</h6>
                                <small class="text-muted">Hace 1 hora</small>
                            </div>
                        </div>
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="avatar-sm rounded-circle bg-warning">
                                    <span class="avatar-title text-white">
                                        <i class="bi bi-shield-check"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-1">Contraseña Cambiada</h6>
                                <small class="text-muted">Hace 2 días</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Cambiar Contraseña -->
<div class="modal fade" id="changePasswordModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Cambiar Contraseña</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="changePasswordForm">
                    <div class="mb-3">
                        <label class="form-label">Contraseña Actual</label>
                        <input type="password" class="form-control" id="currentPassword" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nueva Contraseña</label>
                        <input type="password" class="form-control" id="newPassword" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Confirmar Nueva Contraseña</label>
                        <input type="password" class="form-control" id="confirmPassword" required>
                    </div>
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        La nueva contraseña debe tener al menos 6 caracteres.
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="cambiarContraseña()">Cambiar Contraseña</button>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    function cambiarContraseña() {
        const currentPassword = document.getElementById('currentPassword').value;
        const newPassword = document.getElementById('newPassword').value;
        const confirmPassword = document.getElementById('confirmPassword').value;

        if (!currentPassword || !newPassword || !confirmPassword) {
            alert('Por favor, complete todos los campos.');
            return;
        }

        if (newPassword.length < 6) {
            alert('La nueva contraseña debe tener al menos 6 caracteres.');
            return;
        }

        if (newPassword !== confirmPassword) {
            alert('Las contraseñas no coinciden.');
            return;
        }

        // Aquí iría la lógica para cambiar la contraseña
        alert('Funcionalidad de cambio de contraseña en desarrollo.');
        
        // Limpiar formulario y cerrar modal
        document.getElementById('changePasswordForm').reset();
        bootstrap.Modal.getInstance(document.getElementById('changePasswordModal')).hide();
    }

    function cerrarTodasSesiones() {
        if (confirm('¿Estás seguro de que quieres cerrar todas las sesiones activas? Esto te desconectará de todos los dispositivos.')) {
            alert('Funcionalidad de cierre de sesiones múltiples en desarrollo.');
        }
    }

    function cerrarSesion() {
        if (confirm('¿Estás seguro de que quieres cerrar sesión?')) {
            window.location.href = '<?= base_url('index.php/logout') ?>';
        }
    }

    // Mostrar información de la sesión en consola para debug
    console.log('Información de sesión:', {
        nombres: '<?= session()->get('nombres') ?>',
        apellidos: '<?= session()->get('apellidos') ?>',
        cedula: '<?= session()->get('cedula') ?>',
        email: '<?= session()->get('email') ?>',
        rol: '<?= session()->get('nombre_rol') ?>'
    });
</script>
<?= $this->endSection() ?>
