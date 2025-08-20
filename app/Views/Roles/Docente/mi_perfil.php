<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="<?= base_url('index.php/empleado/dashboard') ?>">Dashboard</a></li>
                        <li class="breadcrumb-item active">Mi Perfil</li>
                    </ol>
                </div>
                <h4 class="page-title">Mi Perfil</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <img src="<?= base_url('sistema/assets/images/users/avatar-default.jpg') ?>" 
                             alt="Avatar" class="rounded-circle" width="100">
                    </div>
                    <h5 class="card-title mb-0"><?= $user['nombres'] . ' ' . $user['apellidos'] ?></h5>
                    <p class="text-muted"><?= $user['rol'] ?></p>
                    <div class="mt-3">
                        <span class="badge bg-primary"><?= session('tipo_empleado') ?? 'DOCENTE' ?></span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="header-title">Información Personal</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Nombres:</label>
                                <p class="form-control-plaintext"><?= $user['nombres'] ?></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Apellidos:</label>
                                <p class="form-control-plaintext"><?= $user['apellidos'] ?></p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Cédula:</label>
                                <p class="form-control-plaintext"><?= session('cedula') ?? 'N/A' ?></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Email:</label>
                                <p class="form-control-plaintext"><?= session('email') ?? 'N/A' ?></p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Tipo de Empleado:</label>
                                <p class="form-control-plaintext"><?= session('tipo_empleado') ?? 'DOCENTE' ?></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Departamento:</label>
                                <p class="form-control-plaintext"><?= session('departamento') ?? 'N/A' ?></p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Rol en el Sistema:</label>
                                <p class="form-control-plaintext"><?= $user['rol'] ?></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Estado:</label>
                                <span class="badge bg-success">Activo</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="header-title">Acciones Rápidas</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <a href="<?= base_url('index.php/empleado/documentos') ?>" class="btn btn-outline-primary w-100 mb-2">
                                <i class="bi bi-file-earmark-text me-2"></i>
                                Mis Documentos
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="<?= base_url('index.php/empleado/capacitaciones') ?>" class="btn btn-outline-success w-100 mb-2">
                                <i class="bi bi-mortarboard me-2"></i>
                                Mis Capacitaciones
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="<?= base_url('index.php/empleado/evaluaciones') ?>" class="btn btn-outline-info w-100 mb-2">
                                <i class="bi bi-clipboard-check me-2"></i>
                                Mis Evaluaciones
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="<?= base_url('index.php/empleado/permisos-vacaciones') ?>" class="btn btn-outline-warning w-100 mb-2">
                                <i class="bi bi-calendar-event me-2"></i>
                                Permisos y Vacaciones
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
