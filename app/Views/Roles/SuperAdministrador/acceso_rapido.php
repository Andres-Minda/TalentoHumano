<?php $sidebar = 'sidebar_super_admin'; ?>
<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>

<div class="page-wrapper">
    <div class="page-content">
        <!-- Header -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <h4 class="mb-0"><i class="bi bi-lightning"></i> Acceso Rápido</h4>
                    <div class="page-title-right">
                        <span class="text-muted">Panel de acceso directo a funcionalidades principales</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Welcome Section -->
        <div class="row">
            <div class="col-12">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h4 class="mb-2">¡Bienvenido, <?= session()->get('nombres') ?>!</h4>
                                <p class="mb-0">Accede rápidamente a las funciones más utilizadas del sistema de administración.</p>
                            </div>
                            <div class="col-md-4 text-end">
                                <i class="bi bi-gear-fill" style="font-size: 3rem; opacity: 0.3;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Access Cards -->
        <div class="row">
            <!-- Gestión de Usuarios -->
            <div class="col-xl-4 col-lg-6 col-md-6">
                <div class="card">
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <i class="bi bi-people-fill text-primary" style="font-size: 3rem;"></i>
                        </div>
                        <h5 class="card-title">Gestión de Usuarios</h5>
                        <p class="card-text">Administra usuarios del sistema, roles y permisos de acceso.</p>
                        <div class="d-grid gap-2">
                            <a href="<?= base_url('super-admin/usuarios') ?>" class="btn btn-primary">
                                <i class="bi bi-people"></i> Ver Usuarios
                            </a>
                            <a href="<?= base_url('super-admin/roles') ?>" class="btn btn-outline-primary">
                                <i class="bi bi-shield-check"></i> Gestionar Roles
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Monitoreo del Sistema -->
            <div class="col-xl-4 col-lg-6 col-md-6">
                <div class="card">
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <i class="bi bi-graph-up text-success" style="font-size: 3rem;"></i>
                        </div>
                        <h5 class="card-title">Monitoreo del Sistema</h5>
                        <p class="card-text">Supervisa el rendimiento y actividad del sistema en tiempo real.</p>
                        <div class="d-grid gap-2">
                            <a href="<?= base_url('super-admin/estadisticas') ?>" class="btn btn-success">
                                <i class="bi bi-graph-up"></i> Ver Estadísticas
                            </a>
                            <a href="<?= base_url('super-admin/logs') ?>" class="btn btn-outline-success">
                                <i class="bi bi-file-text"></i> Ver Logs
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Respaldos y Seguridad -->
            <div class="col-xl-4 col-lg-6 col-md-6">
                <div class="card">
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <i class="bi bi-shield-lock text-warning" style="font-size: 3rem;"></i>
                        </div>
                        <h5 class="card-title">Respaldos y Seguridad</h5>
                        <p class="card-text">Gestiona respaldos de la base de datos y configuración de seguridad.</p>
                        <div class="d-grid gap-2">
                            <a href="<?= base_url('super-admin/respaldos') ?>" class="btn btn-warning">
                                <i class="bi bi-database"></i> Gestionar Respaldos
                            </a>
                            <a href="<?= base_url('super-admin/configuracion') ?>" class="btn btn-outline-warning">
                                <i class="bi bi-gear"></i> Configuración
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Acceso Rápido a AdminTH -->
            <div class="col-xl-4 col-lg-6 col-md-6">
                <div class="card">
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <i class="bi bi-briefcase text-info" style="font-size: 3rem;"></i>
                        </div>
                        <h5 class="card-title">Admin Talento Humano</h5>
                        <p class="card-text">Acceso directo al panel de administración de recursos humanos.</p>
                        <div class="d-grid gap-2">
                            <a href="<?= base_url('admin-th/dashboard') ?>" class="btn btn-info">
                                <i class="bi bi-briefcase"></i> Ir a AdminTH
                            </a>
                            <a href="<?= base_url('admin-th/empleados') ?>" class="btn btn-outline-info">
                                <i class="bi bi-people"></i> Gestionar Empleados
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Vista de Empleados -->
            <div class="col-xl-4 col-lg-6 col-md-6">
                <div class="card">
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <i class="bi bi-person-badge text-secondary" style="font-size: 3rem;"></i>
                        </div>
                        <h5 class="card-title">Vista de Empleados</h5>
                        <p class="card-text">Accede a la vista de empleados para verificar funcionalidades.</p>
                        <div class="d-grid gap-2">
                            <a href="<?= base_url('empleado/dashboard') ?>" class="btn btn-secondary">
                                <i class="bi bi-person-badge"></i> Vista Empleado
                            </a>
                            <a href="<?= base_url('empleado/capacitaciones') ?>" class="btn btn-outline-secondary">
                                <i class="bi bi-mortarboard"></i> Capacitaciones
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Configuración Personal -->
            <div class="col-xl-4 col-lg-6 col-md-6">
                <div class="card">
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <i class="bi bi-person-circle text-dark" style="font-size: 3rem;"></i>
                        </div>
                        <h5 class="card-title">Configuración Personal</h5>
                        <p class="card-text">Gestiona tu perfil, cuenta y configuraciones personales.</p>
                        <div class="d-grid gap-2">
                            <a href="<?= base_url('super-admin/perfil') ?>" class="btn btn-dark">
                                <i class="bi bi-person-circle"></i> Mi Perfil
                            </a>
                            <a href="<?= base_url('super-admin/cuenta') ?>" class="btn btn-outline-dark">
                                <i class="bi bi-gear"></i> Configuración
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity Section -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-activity"></i> Actividad Reciente del Sistema
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Usuario</th>
                                        <th>Acción</th>
                                        <th>Módulo</th>
                                        <th>Fecha</th>
                                        <th>Estado</th>
                                    </tr>
                                </thead>
                                <tbody id="tablaActividadReciente">
                                    <tr>
                                        <td colspan="5" class="text-center text-muted">
                                            <i class="bi bi-hourglass-split"></i> Cargando actividad reciente...
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- System Status Section -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-heart-pulse"></i> Estado del Sistema
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <i class="bi bi-database-check text-success" style="font-size: 2rem;"></i>
                                    <h6 class="mt-2">Base de Datos</h6>
                                    <span class="badge bg-success">Operativa</span>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <i class="bi bi-server text-success" style="font-size: 2rem;"></i>
                                    <h6 class="mt-2">Servidor Web</h6>
                                    <span class="badge bg-success">Activo</span>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <i class="bi bi-shield-check text-success" style="font-size: 2rem;"></i>
                                    <h6 class="mt-2">Seguridad</h6>
                                    <span class="badge bg-success">Protegido</span>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <i class="bi bi-clock text-success" style="font-size: 2rem;"></i>
                                    <h6 class="mt-2">Tiempo Activo</h6>
                                    <span class="badge bg-success">24/7</span>
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
$(document).ready(function() {
    cargarActividadReciente();
});

function cargarActividadReciente() {
    // Simular carga de actividad reciente
    setTimeout(() => {
        const tbody = document.getElementById('tablaActividadReciente');
        tbody.innerHTML = `
            <tr>
                <td>
                    <div class="d-flex align-items-center">
                        <div class="avatar-sm me-3">
                            <span class="avatar-title bg-primary rounded-circle">
                                <?= strtoupper(substr(session()->get('email'), 0, 1)) ?>
                            </span>
                        </div>
                        <div>
                            <h6 class="mb-0"><?= session()->get('nombres') ?> <?= session()->get('apellidos') ?></h6>
                            <small class="text-muted"><?= session()->get('email') ?></small>
                        </div>
                    </div>
                </td>
                <td>
                    <span class="badge bg-primary">Acceso al sistema</span>
                </td>
                <td>Sistema</td>
                <td>Hace 5 minutos</td>
                <td>
                    <span class="badge bg-success">Completado</span>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="d-flex align-items-center">
                        <div class="avatar-sm me-3">
                            <span class="avatar-title bg-success rounded-circle">
                                S
                            </span>
                        </div>
                        <div>
                            <h6 class="mb-0">Sistema</h6>
                            <small class="text-muted">Respaldo automático</small>
                        </div>
                    </div>
                </td>
                <td>
                    <span class="badge bg-info">Respaldo creado</span>
                </td>
                <td>Respaldos</td>
                <td>Hace 1 hora</td>
                <td>
                    <span class="badge bg-success">Completado</span>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="d-flex align-items-center">
                        <div class="avatar-sm me-3">
                            <span class="avatar-title bg-warning rounded-circle">
                                L
                            </span>
                        </div>
                        <div>
                            <h6 class="mb-0">Logs del sistema</h6>
                            <small class="text-muted">Limpieza automática</small>
                        </div>
                    </div>
                </td>
                <td>
                    <span class="badge bg-warning">Limpieza de logs</span>
                </td>
                <td>Logs</td>
                <td>Hace 2 horas</td>
                <td>
                    <span class="badge bg-success">Completado</span>
                </td>
            </tr>
        `;
    }, 1000);
}
</script>

<?= $this->endSection() ?>
