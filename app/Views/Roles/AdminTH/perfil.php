<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>

<div class="page-wrapper">
    <div class="page-content">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <h4 class="mb-0">Mi Perfil - Administrador Talento Humano</h4>
                    <div class="page-title-right">
                        <button class="btn btn-primary" onclick="guardarPerfil()">
                            <i class="bi bi-save me-1"></i>Guardar Cambios
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-4">
                <!-- Información del Perfil -->
                <div class="card">
                    <div class="card-body text-center">
                        <div class="position-relative mb-3">
                            <img src="<?= base_url('public/sistema/assets/images/profile/default-avatar.jpg') ?>" 
                                 alt="Foto de Perfil" 
                                 class="rounded-circle" 
                                 width="120" 
                                 height="120"
                                 style="object-fit: cover;"
                                 onerror="this.src='<?= base_url('public/sistema/assets/images/profile/default-avatar.jpg') ?>'">
                            <button class="btn btn-sm btn-primary position-absolute bottom-0 end-0" 
                                    onclick="document.getElementById('fotoInput').click()">
                                <i class="bi bi-camera"></i>
                            </button>
                            <input type="file" id="fotoInput" accept="image/*" style="display: none;" onchange="cambiarFoto(this)">
                        </div>
                        <h5 class="card-title"><?= session()->get('nombres') ?> <?= session()->get('apellidos') ?></h5>
                        <p class="text-muted"><?= session()->get('nombre_rol') ?></p>
                        <div class="d-flex justify-content-center gap-2">
                            <span class="badge bg-success">Activo</span>
                            <span class="badge bg-info"><?= session()->get('email') ?></span>
                        </div>
                    </div>
                </div>

                <!-- Información de Contacto -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-person-lines-fill me-2"></i>Información de Contacto
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <i class="bi bi-envelope text-primary me-2"></i>
                            <span><?= session()->get('email') ?></span>
                        </div>
                        <div class="d-flex align-items-center mb-3">
                            <i class="bi bi-person-badge text-success me-2"></i>
                            <span><?= session()->get('cedula') ?></span>
                        </div>
                        <div class="d-flex align-items-center">
                            <i class="bi bi-building text-warning me-2"></i>
                            <span><?= session()->get('departamento') ?? 'Talento Humano' ?></span>
                        </div>
                    </div>
                </div>

                <!-- Estadísticas del Administrador -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-graph-up me-2"></i>Actividad Reciente
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="flex-shrink-0">
                                <div class="avatar-sm rounded-circle bg-primary">
                                    <span class="avatar-title text-white">
                                        <i class="bi bi-people"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-1">Empleados Gestionados</h6>
                                <small class="text-muted">Hoy: 8</small>
                            </div>
                        </div>
                        <div class="d-flex align-items-center mb-3">
                            <div class="flex-shrink-0">
                                <div class="avatar-sm rounded-circle bg-success">
                                    <span class="avatar-title text-white">
                                        <i class="bi bi-calendar-check"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-1">Inasistencias Revisadas</h6>
                                <small class="text-muted">Hoy: 12</small>
                            </div>
                        </div>
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="avatar-sm rounded-circle bg-info">
                                    <span class="avatar-title text-white">
                                        <i class="bi bi-award"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-1">Capacitaciones Programadas</h6>
                                <small class="text-muted">Esta semana: 3</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <!-- Información Personal -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-person me-2"></i>Información Personal
                        </h5>
                    </div>
                    <div class="card-body">
                        <form id="perfilForm">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Nombres</label>
                                        <input type="text" class="form-control" id="nombres" value="<?= session()->get('nombres') ?>" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Apellidos</label>
                                        <input type="text" class="form-control" id="apellidos" value="<?= session()->get('apellidos') ?>" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Cédula</label>
                                        <input type="text" class="form-control" id="cedula" value="<?= session()->get('cedula') ?>" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Email</label>
                                        <input type="email" class="form-control" id="email" value="<?= session()->get('email') ?>" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Tipo de Empleado</label>
                                        <input type="text" class="form-control" id="tipo_empleado" value="<?= session()->get('tipo_empleado') ?? 'ADMIN_TH' ?>" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Departamento</label>
                                        <input type="text" class="form-control" id="departamento" value="<?= session()->get('departamento') ?? 'Talento Humano' ?>" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="alert alert-info">
                                <i class="bi bi-info-circle me-2"></i>
                                Para modificar esta información, contacta al Super Administrador del sistema.
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Información de Seguridad -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-shield me-2"></i>Información de Seguridad
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Último Acceso</label>
                                    <input type="text" class="form-control" value="<?= date('d/m/Y H:i:s', session()->get('login_time') ?? time()) ?>" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">IP de Acceso</label>
                                    <input type="text" class="form-control" value="<?= $_SERVER['REMOTE_ADDR'] ?? 'No disponible' ?>" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Estado de la Cuenta</label>
                                    <input type="text" class="form-control" value="Activo" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Rol del Sistema</label>
                                    <input type="text" class="form-control" value="<?= session()->get('nombre_rol') ?>" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Acciones Rápidas -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-lightning me-2"></i>Acciones Rápidas
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <button class="btn btn-warning w-100 mb-2" data-bs-toggle="modal" data-bs-target="#changePasswordModal">
                                    <i class="bi bi-key me-2"></i>Cambiar Contraseña
                                </button>
                            </div>
                            <div class="col-md-6">
                                <button class="btn btn-outline-secondary w-100 mb-2" onclick="cerrarSesion()">
                                    <i class="bi bi-box-arrow-right me-2"></i>Cerrar Sesión
                                </button>
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
    function guardarPerfil() {
        // Aquí iría la lógica para guardar cambios del perfil
        alert('Funcionalidad de guardado en desarrollo.');
    }

    function cambiarFoto(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const img = input.parentElement.querySelector('img');
                img.src = e.target.result;
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

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
