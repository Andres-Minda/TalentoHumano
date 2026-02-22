<?php $sidebar = 'sidebar_admin_th'; ?>
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
                                <p class="mb-0">Accede rápidamente a las funciones más utilizadas del sistema de Talento Humano.</p>
                            </div>
                            <div class="col-md-4 text-end">
                                <i class="bi bi-briefcase-fill" style="font-size: 3rem; opacity: 0.3;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Access Cards -->
        <div class="row">
            <!-- Gestión de Empleados -->
            <div class="col-xl-4 col-lg-6 col-md-6">
                <div class="card">
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <i class="bi bi-people-fill text-primary" style="font-size: 3rem;"></i>
                        </div>
                        <h5 class="card-title">Gestión de Empleados</h5>
                        <p class="card-text">Administra empleados, departamentos y puestos de trabajo.</p>
                        <div class="d-grid gap-2">
                            <a href="<?= base_url('admin-th/empleados') ?>" class="btn btn-primary">
                                <i class="bi bi-people"></i> Ver Empleados
                            </a>
                            <a href="<?= base_url('admin-th/departamentos') ?>" class="btn btn-outline-primary">
                                <i class="bi bi-building"></i> Departamentos
                            </a>
                            <a href="<?= base_url('admin-th/puestos') ?>" class="btn btn-outline-primary">
                                <i class="bi bi-briefcase"></i> Puestos
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Capacitaciones -->
            <div class="col-xl-4 col-lg-6 col-md-6">
                <div class="card">
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <i class="bi bi-mortarboard text-success" style="font-size: 3rem;"></i>
                        </div>
                        <h5 class="card-title">Capacitaciones</h5>
                        <p class="card-text">Gestiona capacitaciones, cursos y programas de desarrollo.</p>
                        <div class="d-grid gap-2">
                            <a href="<?= base_url('admin-th/capacitaciones') ?>" class="btn btn-success">
                                <i class="bi bi-mortarboard"></i> Ver Capacitaciones
                            </a>
                            <a href="<?= base_url('admin-th/solicitudes-capacitacion') ?>" class="btn btn-outline-success">
                                <i class="bi bi-file-earmark-text"></i> Solicitudes
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Evaluaciones -->
            <div class="col-xl-4 col-lg-6 col-md-6">
                <div class="card">
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <i class="bi bi-clipboard-data text-info" style="font-size: 3rem;"></i>
                        </div>
                        <h5 class="card-title">Evaluaciones</h5>
                        <p class="card-text">Sistema de evaluación de desempeño y competencias.</p>
                        <div class="d-grid gap-2">
                            <a href="<?= base_url('admin-th/evaluaciones') ?>" class="btn btn-info">
                                <i class="bi bi-clipboard-data"></i> Ver Evaluaciones
                            </a>
                            <button class="btn btn-outline-info" onclick="configurarEvaluacionPares()">
                                <i class="bi bi-people"></i> Evaluación Entre Pares
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Control de Inasistencias -->
            <div class="col-xl-4 col-lg-6 col-md-6">
                <div class="card">
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <i class="bi bi-calendar-check text-warning" style="font-size: 3rem;"></i>
                        </div>
                        <h5 class="card-title">Control de Inasistencias</h5>
                        <p class="card-text">Gestiona inasistencias, justificaciones y reportes.</p>
                        <div class="d-grid gap-2">
                            <a href="<?= base_url('admin-th/inasistencias') ?>" class="btn btn-warning">
                                <i class="bi bi-calendar-check"></i> Ver Inasistencias
                            </a>
                            <a href="<?= base_url('admin-th/inasistencias/reporte') ?>" class="btn btn-outline-warning">
                                <i class="bi bi-file-earmark-bar-graph"></i> Reportes
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Postulantes -->
            <div class="col-xl-4 col-lg-6 col-md-6">
                <div class="card">
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <i class="bi bi-person-plus text-secondary" style="font-size: 3rem;"></i>
                        </div>
                        <h5 class="card-title">Postulantes</h5>
                        <p class="card-text">Gestiona postulantes y procesos de selección.</p>
                        <div class="d-grid gap-2">
                            <a href="<?= base_url('admin-th/postulantes') ?>" class="btn btn-secondary">
                                <i class="bi bi-person-plus"></i> Ver Postulantes
                            </a>
                            <a href="<?= base_url('admin-th/puestos') ?>" class="btn btn-outline-secondary">
                                <i class="bi bi-briefcase"></i> Puestos Abiertos
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Reportes y Estadísticas -->
            <div class="col-xl-4 col-lg-6 col-md-6">
                <div class="card">
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <i class="bi bi-graph-up text-dark" style="font-size: 3rem;"></i>
                        </div>
                        <h5 class="card-title">Reportes y Estadísticas</h5>
                        <p class="card-text">Genera reportes y visualiza estadísticas del sistema.</p>
                        <div class="d-grid gap-2">
                            <a href="<?= base_url('admin-th/reportes') ?>" class="btn btn-dark">
                                <i class="bi bi-graph-up"></i> Ver Reportes
                            </a>
                            <a href="<?= base_url('admin-th/estadisticas') ?>" class="btn btn-outline-dark">
                                <i class="bi bi-bar-chart"></i> Estadísticas
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
                                        <th>Acción</th>
                                        <th>Usuario</th>
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

        <!-- Quick Stats Section -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-speedometer2"></i> Estadísticas Rápidas
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <i class="bi bi-people text-primary" style="font-size: 2rem;"></i>
                                    <h6 class="mt-2">Total Empleados</h6>
                                    <h4 class="text-primary" id="totalEmpleados">-</h4>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <i class="bi bi-mortarboard text-success" style="font-size: 2rem;"></i>
                                    <h6 class="mt-2">Capacitaciones Activas</h6>
                                    <h4 class="text-success" id="capacitacionesActivas">-</h4>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <i class="bi bi-calendar-check text-warning" style="font-size: 2rem;"></i>
                                    <h6 class="mt-2">Inasistencias Hoy</h6>
                                    <h4 class="text-warning" id="inasistenciasHoy">-</h4>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <i class="bi bi-person-plus text-info" style="font-size: 2rem;"></i>
                                    <h6 class="mt-2">Postulantes Nuevos</h6>
                                    <h4 class="text-info" id="postulantesNuevos">-</h4>
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
    cargarEstadisticasRapidas();
});

function cargarActividadReciente() {
    // Simular carga de actividad reciente
    setTimeout(() => {
        const tbody = document.getElementById('tablaActividadReciente');
        tbody.innerHTML = `
            <tr>
                <td>
                    <span class="badge bg-primary">Nuevo empleado registrado</span>
                </td>
                <td>
                    <div class="d-flex align-items-center">
                        <div class="avatar-sm me-3">
                            <span class="avatar-title bg-primary rounded-circle">
                                <?= strtoupper(substr(session()->get('email'), 0, 1)) ?>
                            </span>
                        </div>
                        <div>
                            <h6 class="mb-0"><?= session()->get('nombres') ?> <?= session()->get('apellidos') ?></h6>
                            <small class="text-muted">AdminTH</small>
                        </div>
                    </div>
                </td>
                <td>Empleados</td>
                <td>Hace 10 minutos</td>
                <td>
                    <span class="badge bg-success">Completado</span>
                </td>
            </tr>
            <tr>
                <td>
                    <span class="badge bg-success">Capacitación creada</span>
                </td>
                <td>
                    <div class="d-flex align-items-center">
                        <div class="avatar-sm me-3">
                            <span class="avatar-title bg-success rounded-circle">
                                <?= strtoupper(substr(session()->get('email'), 0, 1)) ?>
                            </span>
                        </div>
                        <div>
                            <h6 class="mb-0"><?= session()->get('nombres') ?> <?= session()->get('apellidos') ?></h6>
                            <small class="text-muted">AdminTH</small>
                        </div>
                    </div>
                </td>
                <td>Capacitaciones</td>
                <td>Hace 30 minutos</td>
                <td>
                    <span class="badge bg-success">Completado</span>
                </td>
            </tr>
            <tr>
                <td>
                    <span class="badge bg-warning">Inasistencia registrada</span>
                </td>
                <td>
                    <div class="d-flex align-items-center">
                        <div class="avatar-sm me-3">
                            <span class="avatar-title bg-warning rounded-circle">
                                <?= strtoupper(substr(session()->get('email'), 0, 1)) ?>
                            </span>
                        </div>
                        <div>
                            <h6 class="mb-0"><?= session()->get('nombres') ?> <?= session()->get('apellidos') ?></h6>
                            <small class="text-muted">AdminTH</small>
                        </div>
                    </div>
                </td>
                <td>Inasistencias</td>
                <td>Hace 1 hora</td>
                <td>
                    <span class="badge bg-success">Completado</span>
                </td>
            </tr>
        `;
    }, 1000);
}

function cargarEstadisticasRapidas() {
    // Cargar estadísticas básicas
    Promise.all([
        fetch('<?= base_url('admin-th/empleados/estadisticas') ?>'),
        fetch('<?= base_url('admin-th/capacitaciones/obtener') ?>'),
        fetch('<?= base_url('admin-th/inasistencias/obtener') ?>'),
        fetch('<?= base_url('admin-th/postulantes/obtener') ?>')
    ])
    .then(responses => Promise.all(responses.map(r => r.json())))
    .then(data => {
        // Actualizar estadísticas
        if (data[0].success) {
            document.getElementById('totalEmpleados').textContent = data[0].total_empleados || 0;
        }
        
        if (data[1].success) {
            const capacitacionesActivas = data[1].capacitaciones?.filter(c => c.estado === 'ACTIVA').length || 0;
            document.getElementById('capacitacionesActivas').textContent = capacitacionesActivas;
        }
        
        if (data[2].success) {
            const hoy = new Date().toISOString().split('T')[0];
            const inasistenciasHoy = data[2].inasistencias?.filter(i => i.fecha === hoy).length || 0;
            document.getElementById('inasistenciasHoy').textContent = inasistenciasHoy;
        }
        
        if (data[3].success) {
            const postulantesNuevos = data[3].postulantes?.filter(p => p.estado === 'NUEVO').length || 0;
            document.getElementById('postulantesNuevos').textContent = postulantesNuevos;
        }
    })
    .catch(error => {
        console.error('Error cargando estadísticas:', error);
    });
}

function configurarEvaluacionPares() {
    // Redirigir a la vista de evaluaciones
    window.location.href = '<?= base_url('admin-th/evaluaciones') ?>';
}
</script>

<?= $this->endSection() ?>
