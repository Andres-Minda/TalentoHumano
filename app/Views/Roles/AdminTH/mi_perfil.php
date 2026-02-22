<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>
<div class="page-wrapper">
    <div class="page-content">
        <!-- Breadcrumb -->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Mi Perfil</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="<?= base_url('index.php/admin-th/dashboard') ?>"><i class="bx bx-home-alt"></i></a></li>
                        <li class="breadcrumb-item active" aria-current="page">Mi Perfil</li>
                    </ol>
                </nav>
            </div>
        </div>

        <!-- Alert Messages -->
        <div id="alertContainer"></div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <!-- Tabs Navigation -->
                        <ul class="nav nav-tabs nav-primary" id="perfilTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="datos-tab" data-bs-toggle="tab" data-bs-target="#datos" type="button" role="tab">
                                    <i class="ti ti-user me-1"></i> Datos Personales
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="seguridad-tab" data-bs-toggle="tab" data-bs-target="#seguridad" type="button" role="tab">
                                    <i class="ti ti-shield-lock me-1"></i> Seguridad
                                </button>
                            </li>
                        </ul>

                        <!-- Tabs Content -->
                        <div class="tab-content pt-4" id="perfilTabContent">

                            <!-- Tab 1: Datos Personales -->
                            <div class="tab-pane fade show active" id="datos" role="tabpanel">
                                <div class="row">
                                    <!-- Foto y resumen -->
                                    <div class="col-md-4 text-center mb-4">
                                        <div class="card border shadow-none">
                                            <div class="card-body">
                                                <?php
                                                $foto = session('foto_perfil');
                                                $fotoUrl = ($foto && file_exists(FCPATH . 'sistema/assets/images/profile/' . $foto))
                                                    ? base_url('sistema/assets/images/profile/' . $foto)
                                                    : base_url('sistema/assets/images/profile/user-1.jpg');
                                                ?>
                                                <img src="<?= $fotoUrl ?>" alt="Foto de perfil" class="rounded-circle mb-3" width="120" height="120" style="object-fit: cover; border: 3px solid #007bff;">
                                                <h5 class="mb-1"><?= esc(session('nombres')) ?> <?= esc(session('apellidos')) ?></h5>
                                                <p class="text-muted mb-1">Administrador de Talento Humano</p>
                                                <p class="text-muted mb-0"><i class="ti ti-mail me-1"></i><?= esc(session('email')) ?></p>
                                                <p class="text-muted mb-0"><i class="ti ti-id me-1"></i><?= esc(session('cedula')) ?></p>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Formulario editable -->
                                    <div class="col-md-8">
                                        <form id="formPerfil">
                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label for="nombres" class="form-label fw-bold">Nombres</label>
                                                    <input type="text" class="form-control" id="nombres" name="nombres" value="<?= esc(session('nombres')) ?>" required>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label for="apellidos" class="form-label fw-bold">Apellidos</label>
                                                    <input type="text" class="form-control" id="apellidos" name="apellidos" value="<?= esc(session('apellidos')) ?>" required>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label for="email" class="form-label fw-bold">Correo Electrónico</label>
                                                    <input type="email" class="form-control" id="email" name="email" value="<?= esc(session('email')) ?>" required>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label fw-bold">Cédula</label>
                                                    <input type="text" class="form-control" value="<?= esc(session('cedula')) ?>" readonly disabled>
                                                </div>
                                            </div>
                                            <div class="text-end">
                                                <button type="submit" class="btn btn-primary">
                                                    <i class="ti ti-device-floppy me-1"></i> Guardar Cambios
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- Tab 2: Seguridad -->
                            <div class="tab-pane fade" id="seguridad" role="tabpanel">
                                <div class="row justify-content-center">
                                    <div class="col-md-8">
                                        <div class="card border shadow-none">
                                            <div class="card-header bg-light">
                                                <h6 class="mb-0"><i class="ti ti-key me-2"></i>Cambiar Contraseña</h6>
                                            </div>
                                            <div class="card-body">
                                                <form id="formPassword">
                                                    <div class="mb-3">
                                                        <label for="password_actual" class="form-label fw-bold">Contraseña Actual</label>
                                                        <div class="input-group">
                                                            <input type="password" class="form-control" id="password_actual" name="password_actual" required>
                                                            <button class="btn btn-outline-secondary toggle-password" type="button" data-target="password_actual">
                                                                <i class="ti ti-eye"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="password_nuevo" class="form-label fw-bold">Nueva Contraseña</label>
                                                        <div class="input-group">
                                                            <input type="password" class="form-control" id="password_nuevo" name="password_nuevo" required minlength="6">
                                                            <button class="btn btn-outline-secondary toggle-password" type="button" data-target="password_nuevo">
                                                                <i class="ti ti-eye"></i>
                                                            </button>
                                                        </div>
                                                        <small class="text-muted">Mínimo 6 caracteres</small>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="password_confirmar" class="form-label fw-bold">Confirmar Nueva Contraseña</label>
                                                        <div class="input-group">
                                                            <input type="password" class="form-control" id="password_confirmar" name="password_confirmar" required minlength="6">
                                                            <button class="btn btn-outline-secondary toggle-password" type="button" data-target="password_confirmar">
                                                                <i class="ti ti-eye"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <div class="text-end">
                                                        <button type="submit" class="btn btn-warning">
                                                            <i class="ti ti-lock me-1"></i> Cambiar Contraseña
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>

                                        <!-- Cerrar Sesión -->
                                        <div class="card border border-danger shadow-none mt-3">
                                            <div class="card-body text-center">
                                                <h6 class="text-danger mb-2"><i class="ti ti-alert-triangle me-1"></i>Zona de Sesión</h6>
                                                <p class="text-muted mb-3">Cerrar tu sesión actual o todas las sesiones activas.</p>
                                                <div class="d-flex justify-content-center gap-2">
                                                    <a href="<?= base_url('index.php/auth/logout') ?>" class="btn btn-outline-danger">
                                                        <i class="ti ti-logout me-1"></i> Cerrar Sesión
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // --- Guardar Perfil ---
    document.getElementById('formPerfil').addEventListener('submit', function(e) {
        e.preventDefault();
        const data = {
            nombres: document.getElementById('nombres').value.trim(),
            apellidos: document.getElementById('apellidos').value.trim(),
            email: document.getElementById('email').value.trim()
        };

        fetch('<?= base_url("index.php/admin-th/actualizar-perfil") ?>', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
            body: JSON.stringify(data)
        })
        .then(r => r.json())
        .then(res => {
            mostrarAlerta(res.success ? 'success' : 'danger', res.message);
            if (res.success) setTimeout(() => location.reload(), 1500);
        })
        .catch(() => mostrarAlerta('danger', 'Error de conexión al servidor'));
    });

    // --- Cambiar Contraseña ---
    document.getElementById('formPassword').addEventListener('submit', function(e) {
        e.preventDefault();
        const nuevo = document.getElementById('password_nuevo').value;
        const confirmar = document.getElementById('password_confirmar').value;

        if (nuevo !== confirmar) {
            mostrarAlerta('danger', 'Las contraseñas nuevas no coinciden');
            return;
        }

        const data = {
            password_actual: document.getElementById('password_actual').value,
            password_nuevo: nuevo,
            password_confirmar: confirmar
        };

        fetch('<?= base_url("index.php/admin-th/cambiar-contrasena") ?>', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
            body: JSON.stringify(data)
        })
        .then(r => r.json())
        .then(res => {
            mostrarAlerta(res.success ? 'success' : 'danger', res.message);
            if (res.success) document.getElementById('formPassword').reset();
        })
        .catch(() => mostrarAlerta('danger', 'Error de conexión al servidor'));
    });

    // --- Toggle Password Visibility ---
    document.querySelectorAll('.toggle-password').forEach(btn => {
        btn.addEventListener('click', function() {
            const input = document.getElementById(this.dataset.target);
            const icon = this.querySelector('i');
            if (input.type === 'password') {
                input.type = 'text';
                icon.className = 'ti ti-eye-off';
            } else {
                input.type = 'password';
                icon.className = 'ti ti-eye';
            }
        });
    });
});

function mostrarAlerta(tipo, mensaje) {
    const container = document.getElementById('alertContainer');
    container.innerHTML = `
        <div class="alert alert-${tipo} alert-dismissible fade show" role="alert">
            <i class="ti ti-${tipo === 'success' ? 'check' : 'alert-circle'} me-2"></i>${mensaje}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>`;
    window.scrollTo({ top: 0, behavior: 'smooth' });
}
</script>
<?= $this->endSection() ?>
