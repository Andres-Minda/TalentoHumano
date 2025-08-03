<?php
$sidebar = 'sidebar_docente';
?>

<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <!-- Page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">Mi Cuenta - Docente</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <!-- Cambiar Contraseña -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="ti ti-shield-lock me-2"></i>Cambiar Contraseña
                    </h5>
                </div>
                <div class="card-body">
                    <form id="passwordForm">
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
                                <i class="ti ti-shield-check me-1"></i>Cambiar Contraseña
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Configuración de Notificaciones -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="ti ti-bell me-2"></i>Configuración de Notificaciones
                    </h5>
                </div>
                <div class="card-body">
                    <form id="notificacionesForm">
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
                                        <small class="text-muted d-block">Notificaciones en tiempo real</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="notificaciones_sistema" id="notificacionesSistema" checked>
                                        <label class="form-check-label" for="notificacionesSistema">
                                            Notificaciones del Sistema
                                        </label>
                                        <small class="text-muted d-block">Alertas de seguridad y mantenimiento</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="text-end">
                            <button type="submit" class="btn btn-success">
                                <i class="ti ti-settings me-1"></i>Guardar Configuración
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Configuración de Privacidad -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="ti ti-lock me-2"></i>Configuración de Privacidad
                    </h5>
                </div>
                <div class="card-body">
                    <form id="privacidadForm">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="perfil_publico" id="perfilPublico">
                                        <label class="form-check-label" for="perfilPublico">
                                            Perfil Público
                                        </label>
                                        <small class="text-muted d-block">Permitir que otros usuarios vean mi información básica</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="mostrar_online" id="mostrarOnline" checked>
                                        <label class="form-check-label" for="mostrarOnline">
                                            Mostrar Estado Online
                                        </label>
                                        <small class="text-muted d-block">Mostrar cuando estoy conectado</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="sesiones_multiples" id="sesionesMultiples">
                                        <label class="form-check-label" for="sesionesMultiples">
                                            Sesiones Múltiples
                                        </label>
                                        <small class="text-muted d-block">Permitir iniciar sesión en múltiples dispositivos</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="autenticacion_dos_factores" id="autenticacionDosFactores">
                                        <label class="form-check-label" for="autenticacionDosFactores">
                                            Autenticación de Dos Factores
                                        </label>
                                        <small class="text-muted d-block">Mayor seguridad para la cuenta</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="text-end">
                            <button type="submit" class="btn btn-warning">
                                <i class="ti ti-shield me-1"></i>Guardar Privacidad
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
                        <i class="ti ti-user-circle me-2"></i>Información de la Cuenta
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <i class="ti ti-mail text-primary me-2"></i>
                        <span><?= session('email') ?></span>
                    </div>
                    <div class="d-flex align-items-center mb-3">
                        <i class="ti ti-calendar text-success me-2"></i>
                        <span>Miembro desde: <?= session('created_at') ? date('d/m/Y', strtotime(session('created_at'))) : 'N/A' ?></span>
                    </div>
                    <div class="d-flex align-items-center mb-3">
                        <i class="ti ti-clock text-warning me-2"></i>
                        <span>Último acceso: <?= session('last_login') ? date('d/m/Y H:i', strtotime(session('last_login'))) : 'Nunca' ?></span>
                    </div>
                    <div class="d-flex align-items-center">
                        <i class="ti ti-shield-check text-info me-2"></i>
                        <span>Estado: <?= session('activo') ? 'Activa' : 'Inactiva' ?></span>
                    </div>
                </div>
            </div>

            <!-- Sesiones Activas -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="ti ti-devices me-2"></i>Sesiones Activas
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="avatar-sm bg-primary rounded-circle me-3">
                            <span class="avatar-title text-white">
                                <i class="ti ti-device-desktop"></i>
                            </span>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="mb-1">Navegador Actual</h6>
                            <small class="text-muted">Chrome - Windows 10</small>
                        </div>
                        <span class="badge bg-success">Activa</span>
                    </div>
                    <div class="d-flex align-items-center mb-3">
                        <div class="avatar-sm bg-secondary rounded-circle me-3">
                            <span class="avatar-title text-white">
                                <i class="ti ti-device-mobile"></i>
                            </span>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="mb-1">Dispositivo Móvil</h6>
                            <small class="text-muted">Safari - iPhone</small>
                        </div>
                        <span class="badge bg-warning">Hace 2h</span>
                    </div>
                    <div class="text-center">
                        <button class="btn btn-outline-danger btn-sm" onclick="cerrarSesiones()">
                            <i class="ti ti-logout me-1"></i>Cerrar Todas las Sesiones
                        </button>
                    </div>
                </div>
            </div>

            <!-- Actividad Reciente -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="ti ti-activity me-2"></i>Actividad Reciente
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="avatar-sm bg-success rounded-circle me-3">
                            <span class="avatar-title text-white">
                                <i class="ti ti-login"></i>
                            </span>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="mb-1">Inicio de Sesión</h6>
                            <small class="text-muted">Hace 30 minutos</small>
                        </div>
                    </div>
                    <div class="d-flex align-items-center mb-3">
                        <div class="avatar-sm bg-info rounded-circle me-3">
                            <span class="avatar-title text-white">
                                <i class="ti ti-user-edit"></i>
                            </span>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="mb-1">Perfil Actualizado</h6>
                            <small class="text-muted">Hace 2 horas</small>
                        </div>
                    </div>
                    <div class="d-flex align-items-center">
                        <div class="avatar-sm bg-warning rounded-circle me-3">
                            <span class="avatar-title text-white">
                                <i class="ti ti-shield-check"></i>
                            </span>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="mb-1">Contraseña Cambiada</h6>
                            <small class="text-muted">Hace 1 día</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
// Cambiar contraseña
document.getElementById('passwordForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    // Validar que las contraseñas coincidan
    if (formData.get('password_nuevo') !== formData.get('password_confirmar')) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Las contraseñas no coinciden'
        });
        return;
    }
    
    fetch('<?= base_url('docente/cuenta/cambiar-password') ?>', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Éxito',
                text: 'Contraseña cambiada correctamente'
            }).then(() => {
                this.reset();
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: data.message || 'Error al cambiar contraseña'
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Error de conexión'
        });
    });
});

// Configuración de notificaciones
document.getElementById('notificacionesForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    fetch('<?= base_url('docente/cuenta/configurar-notificaciones') ?>', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Éxito',
                text: 'Configuración guardada correctamente'
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: data.message || 'Error al guardar configuración'
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Error de conexión'
        });
    });
});

// Configuración de privacidad
document.getElementById('privacidadForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    fetch('<?= base_url('docente/cuenta/configurar-privacidad') ?>', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Éxito',
                text: 'Configuración de privacidad guardada'
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: data.message || 'Error al guardar configuración'
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Error de conexión'
        });
    });
});

function cerrarSesiones() {
    Swal.fire({
        title: '¿Estás seguro?',
        text: "Se cerrarán todas las sesiones activas excepto la actual",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sí, cerrar sesiones',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch('<?= base_url('docente/cuenta/cerrar-sesiones') ?>', {
                method: 'POST'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Sesiones cerradas',
                        text: 'Todas las sesiones han sido cerradas'
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message || 'Error al cerrar sesiones'
                    });
                }
            });
        }
    });
}
</script>
<?= $this->endSection() ?> 