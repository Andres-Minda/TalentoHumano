<?php 
// Detectar automáticamente el tipo de empleado para mostrar el sidebar correcto
$tipoEmpleado = ($empleado && isset($empleado['tipo_empleado'])) ? $empleado['tipo_empleado'] : 'DOCENTE';
$sidebar = 'sidebar_empleado'; // Forzar sidebar de empleado

// Usar la información del controlador si está disponible
$tituloDashboard = $titulo ?? 'Dashboard - Empleado';
$descripcionDashboard = $descripcionDashboard ?? 'Panel de control para empleados';

// Si no hay información del controlador, determinar por tipo de empleado
if (!isset($titulo)) {
    switch ($tipoEmpleado) {
        case 'DOCENTE':
            $tituloDashboard = 'Dashboard - Docente';
            $descripcionDashboard = 'Panel de control para docentes';
            break;
        case 'ADMINISTRATIVO':
            $tituloDashboard = 'Dashboard - Administrativo';
            $descripcionDashboard = 'Panel de control para personal administrativo';
            break;
        case 'DIRECTIVO':
            $tituloDashboard = 'Dashboard - Directivo';
            $descripcionDashboard = 'Panel de control para directivos';
            break;
        case 'AUXILIAR':
            $tituloDashboard = 'Dashboard - Auxiliar';
            $descripcionDashboard = 'Panel de control para auxiliares';
            break;
    }
}
?>

<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>

<div class="page-wrapper">
    <div class="page-content">
        <!-- Header -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <h4 class="mb-0"><i class="bi bi-speedometer2"></i> <?= $tituloDashboard ?></h4>
                    <div class="page-title-right">
                        <span class="text-muted">Última actualización: <?= date('d/m/Y H:i') ?></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Welcome Message -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center">
                        <h2 class="text-primary mb-3">¡Bienvenido, <?= $user['nombres'] ?> <?= $user['apellidos'] ?>!</h2>
                        <p class="lead mb-2"><?= $descripcionDashboard ?></p>
                        <?php if ($empleado && isset($empleado['tipo_empleado'])): ?>
                            <p class="text-muted">
                                Tipo: <?= $empleado['tipo_empleado'] ?> 
                                | Departamento: <?= $empleado['departamento'] ?? 'No asignado' ?>
                            </p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Advertencia de Contraseña -->
        <?php if (session()->get('password_changed') == 0): ?>
        <div class="row">
            <div class="col-12">
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="ti ti-alert-triangle me-3 fs-4"></i>
                        <div class="flex-grow-1">
                            <h5 class="alert-heading mb-2">⚠️ ADVERTENCIA DE SEGURIDAD</h5>
                            <p class="mb-2">Su cuenta aún utiliza las credenciales de creación inicial. Por seguridad, debe cambiar su contraseña inmediatamente.</p>
                            <p class="mb-0">
                                <strong>Credenciales actuales:</strong><br>
                                Email: <code><?= $user['email'] ?></code><br>
                                Contraseña: <code>123456</code>
                            </p>
                        </div>
                    </div>
                    <div class="mt-3">
                        <button type="button" class="btn btn-warning btn-sm" onclick="mostrarModalCambiarPassword()">
                            <i class="ti ti-key me-1"></i> Cambiar Contraseña Ahora
                        </button>
                        <button type="button" class="btn btn-outline-warning btn-sm ms-2" onclick="recordarDespues()">
                            <i class="ti ti-clock me-1"></i> Recordar Después
                        </button>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Summary Cards -->
        <div class="row">
            <div class="col-xl-3 col-lg-6 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h4 class="mb-0"><?= $estadisticas['total_capacitaciones'] ?? 0 ?></h4>
                                <p class="text-muted mb-0">Mis Capacitaciones</p>
                            </div>
                            <div class="text-primary">
                                <i class="bi bi-mortarboard fs-1"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h4 class="mb-0"><?= $estadisticas['total_documentos'] ?? 0 ?></h4>
                                <p class="text-muted mb-0">Documentos</p>
                            </div>
                            <div class="text-success">
                                <i class="bi bi-file-earmark-text fs-1"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h4 class="mb-0"><?= $estadisticas['total_certificados'] ?? 0 ?></h4>
                                <p class="text-muted mb-0">Certificados</p>
                            </div>
                            <div class="text-warning">
                                <i class="bi bi-award fs-1"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h4 class="mb-0"><?= $estadisticas['total_solicitudes'] ?? 0 ?></h4>
                                <p class="text-muted mb-0">Solicitudes</p>
                            </div>
                            <div class="text-info">
                                <i class="bi bi-clipboard-data fs-1"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-lightning text-warning"></i> Acciones Rápidas
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3 col-sm-6 mb-3">
                                <a href="<?= base_url('empleado/capacitaciones') ?>" class="btn btn-primary w-100">
                                    <i class="bi bi-mortarboard me-2"></i>Mis Capacitaciones
                                </a>
                            </div>
                            <div class="col-md-3 col-sm-6 mb-3">
                                <a href="<?= base_url('empleado/documentos') ?>" class="btn btn-success w-100">
                                    <i class="bi bi-file-earmark-text me-2"></i>Documentos
                                </a>
                            </div>
                            <div class="col-md-3 col-sm-6 mb-3">
                                <a href="<?= base_url('empleado/certificados') ?>" class="btn btn-warning w-100">
                                    <i class="bi bi-award me-2"></i>Certificados
                                </a>
                            </div>
                            <div class="col-md-3 col-sm-6 mb-3">
                                <a href="<?= base_url('empleado/nueva-solicitud') ?>" class="btn btn-info w-100">
                                    <i class="bi bi-plus-circle me-2"></i>Nueva Solicitud
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Content Row -->
        <div class="row">
            <!-- Latest Trainings -->
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-mortarboard text-primary"></i> Últimas Capacitaciones
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Capacitación</th>
                                        <th>Tipo</th>
                                        <th>Estado</th>
                                        <th>Fecha</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td colspan="4" class="text-center text-muted">
                                            No hay capacitaciones recientes
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Requests -->
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-clipboard-data text-success"></i> Solicitudes Recientes
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Tipo</th>
                                        <th>Título</th>
                                        <th>Estado</th>
                                        <th>Fecha</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td colspan="4" class="text-center text-muted">
                                            No hay solicitudes recientes
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Academic Period Info -->
        <?php if (isset($periodo_activo) && $periodo_activo): ?>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center">
                        <h6 class="text-muted mb-2">Periodo Académico Activo</h6>
                        <h5 class="text-primary mb-0">
                            <?= $periodo_activo['nombre'] ?? 'Periodo 2025-1' ?> | 
                            Del <?= date('d/m/Y', strtotime($periodo_activo['fecha_inicio'] ?? '2025-01-15')) ?> 
                            al <?= date('d/m/Y', strtotime($periodo_activo['fecha_fin'] ?? '2025-06-30')) ?>
                        </h5>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Modal para cambiar contraseña -->
<div class="modal fade" id="modalCambiarPassword" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="ti ti-key me-2"></i> Cambiar Contraseña
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info">
                    <i class="ti ti-info-circle me-2"></i>
                    Por seguridad, debe cambiar su contraseña de acceso al sistema.
                </div>
                
                <form id="formCambiarPassword">
                    <div class="mb-3">
                        <label for="password_actual" class="form-label">Contraseña Actual *</label>
                        <input type="password" class="form-control" id="password_actual" name="password_actual" required>
                        <div class="form-text">Ingrese la contraseña actual: 123456</div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="password_nuevo" class="form-label">Nueva Contraseña *</label>
                        <input type="password" class="form-control" id="password_nuevo" name="password_nuevo" required minlength="6">
                        <div class="form-text">Mínimo 6 caracteres</div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="password_confirmar" class="form-label">Confirmar Nueva Contraseña *</label>
                        <input type="password" class="form-control" id="password_confirmar" name="password_confirmar" required minlength="6">
                        <div class="form-text">Debe coincidir con la nueva contraseña</div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-warning" onclick="cambiarPassword()">
                    <i class="ti ti-key me-1"></i> Cambiar Contraseña
                </button>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
function mostrarModalCambiarPassword() {
    const modal = new bootstrap.Modal(document.getElementById('modalCambiarPassword'));
    modal.show();
}

function recordarDespues() {
    // Ocultar la alerta por ahora
    const alerta = document.querySelector('.alert-warning');
    if (alerta) {
        alerta.style.display = 'none';
    }
}

function cambiarPassword() {
    const form = document.getElementById('formCambiarPassword');
    const formData = new FormData(form);
    
    // Validar que las contraseñas coincidan
    const passwordNuevo = formData.get('password_nuevo');
    const passwordConfirmar = formData.get('password_confirmar');
    
    if (passwordNuevo !== passwordConfirmar) {
        alert('❌ Las contraseñas nuevas no coinciden');
        return;
    }
    
    if (passwordNuevo.length < 6) {
        alert('❌ La contraseña debe tener al menos 6 caracteres');
        return;
    }
    
    // Mostrar indicador de carga
    const btnCambiar = document.querySelector('#modalCambiarPassword .btn-warning');
    const textoOriginal = btnCambiar.innerHTML;
    btnCambiar.innerHTML = '<i class="ti ti-loader ti-spin me-1"></i> Cambiando...';
    btnCambiar.disabled = true;
    
    // Enviar solicitud
    fetch('<?= site_url('empleado/cambiar-password') ?>', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('✅ ' + data.message);
            
            // Cerrar modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('modalCambiarPassword'));
            modal.hide();
            
            // Ocultar la alerta de advertencia
            const alerta = document.querySelector('.alert-warning');
            if (alerta) {
                alerta.style.display = 'none';
            }
            
            // Limpiar formulario
            form.reset();
            
            // Recargar la página para actualizar la sesión
            setTimeout(() => {
                window.location.reload();
            }, 1000);
            
        } else {
            alert('❌ Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('❌ Error de conexión. Por favor, intente nuevamente.');
    })
    .finally(() => {
        // Restaurar botón
        btnCambiar.innerHTML = textoOriginal;
        btnCambiar.disabled = false;
    });
}

// Auto-focus en el primer campo del modal
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('modalCambiarPassword');
    if (modal) {
        modal.addEventListener('shown.bs.modal', function() {
            document.getElementById('password_actual').focus();
        });
    }
});
</script>
<?= $this->endSection() ?>
