<?php $sidebar = 'sidebar_super_admin'; ?>
<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>

<div class="page-wrapper">
    <div class="page-content">
        <!-- Header -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <h4 class="mb-0"><i class="bi bi-graph-up"></i> Estadísticas del Sistema</h4>
                    <div class="page-title-right">
                        <button type="button" class="btn btn-primary" onclick="actualizarEstadisticas()">
                            <i class="bi bi-arrow-clockwise"></i> Actualizar
                        </button>
                        <span class="text-muted ms-2">Última actualización: <span id="ultimaActualizacion"><?= date('d/m/Y H:i:s') ?></span></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row">
            <div class="col-xl-3 col-lg-6 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="me-3">
                                <span class="round-8 bg-primary rounded-circle me-2 d-inline-block"></span>
                                <div class="stat-digit d-flex">
                                    <h5 class="font-weight-semibold mb-0" id="totalUsuarios"><?= $estadisticas['usuarios']['total_usuarios'] ?? 0 ?></h5>
                                </div>
                                <div class="stat-digit d-flex">
                                    <h5 class="font-weight-semibold mb-0">Usuarios</h5>
                                </div>
                            </div>
                            <div class="ms-auto">
                                <i class="bi bi-people text-primary" style="font-size: 2rem;"></i>
                            </div>
                        </div>
                        <div class="mt-2">
                            <span class="badge bg-success"><?= $estadisticas['usuarios']['usuarios_activos'] ?? 0 ?> Activos</span>
                            <span class="badge bg-warning"><?= $estadisticas['usuarios']['usuarios_inactivos'] ?? 0 ?> Inactivos</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="me-3">
                                <span class="round-8 bg-success rounded-circle me-2 d-inline-block"></span>
                                <div class="stat-digit d-flex">
                                    <h5 class="font-weight-semibold mb-0" id="totalEmpleados"><?= $estadisticas['empleados']['total_empleados'] ?? 0 ?></h5>
                                </div>
                                <div class="stat-digit d-flex">
                                    <h5 class="font-weight-semibold mb-0">Empleados</h5>
                                </div>
                            </div>
                            <div class="ms-auto">
                                <i class="bi bi-briefcase text-success" style="font-size: 2rem;"></i>
                            </div>
                        </div>
                        <div class="mt-2">
                            <span class="badge bg-success"><?= $estadisticas['empleados']['empleados_activos'] ?? 0 ?> Activos</span>
                            <span class="badge bg-danger"><?= $estadisticas['empleados']['empleados_inactivos'] ?? 0 ?> Inactivos</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="me-3">
                                <span class="round-8 bg-info rounded-circle me-2 d-inline-block"></span>
                                <div class="stat-digit d-flex">
                                    <h5 class="font-weight-semibold mb-0" id="sesionesActivas"><?= $estadisticas['sesiones']['sesiones_activas'] ?? 0 ?></h5>
                                </div>
                                <div class="stat-digit d-flex">
                                    <h5 class="font-weight-semibold mb-0">Sesiones Activas</h5>
                                </div>
                            </div>
                            <div class="ms-auto">
                                <i class="bi bi-activity text-info" style="font-size: 2rem;"></i>
                            </div>
                        </div>
                        <div class="mt-2">
                            <span class="badge bg-info"><?= $estadisticas['sesiones']['usuarios_conectados'] ?? 0 ?> Usuarios</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="me-3">
                                <span class="round-8 bg-warning rounded-circle me-2 d-inline-block"></span>
                                <div class="stat-digit d-flex">
                                    <h5 class="font-weight-semibold mb-0" id="totalLogs"><?= $estadisticas['logs']['total_logs'] ?? 0 ?></h5>
                                </div>
                                <div class="stat-digit d-flex">
                                    <h5 class="font-weight-semibold mb-0">Logs (7 días)</h5>
                                </div>
                            </div>
                            <div class="ms-auto">
                                <i class="bi bi-file-text text-warning" style="font-size: 2rem;"></i>
                            </div>
                        </div>
                        <div class="mt-2">
                            <span class="badge bg-warning"><?= $estadisticas['logs']['logs_hoy'] ?? 0 ?> Hoy</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="row">
            <div class="col-xl-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Actividad del Sistema (Últimos 7 días)</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="chartActividad" height="100"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-xl-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Módulos Más Utilizados</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="chartModulos" height="200"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tables Row -->
        <div class="row">
            <div class="col-xl-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Usuarios Más Activos</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Usuario</th>
                                        <th>Acciones</th>
                                        <th>Última Actividad</th>
                                    </tr>
                                </thead>
                                <tbody id="tablaUsuariosActivos">
                                    <?php if (isset($estadisticas['usuarios_activos']) && !empty($estadisticas['usuarios_activos'])): ?>
                                        <?php foreach ($estadisticas['usuarios_activos'] as $usuario): ?>
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-sm me-3">
                                                        <span class="avatar-title bg-primary rounded-circle">
                                                            <?= strtoupper(substr($usuario['email'], 0, 1)) ?>
                                                        </span>
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0"><?= esc($usuario['cedula']) ?></h6>
                                                        <small class="text-muted"><?= esc($usuario['email']) ?></small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-primary"><?= $usuario['total_acciones'] ?> acciones</span>
                                            </td>
                                            <td>
                                                <small class="text-muted">Hoy</small>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="3" class="text-center text-muted">No hay datos disponibles</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Respaldo del Sistema</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="me-3">
                                <i class="bi bi-database text-success" style="font-size: 1.5rem;"></i>
                            </div>
                            <div>
                                <h6 class="mb-0">Estado del Respaldo</h6>
                                <small class="text-muted">
                                    <?php if (isset($estadisticas['respaldos']['total_respaldos'])): ?>
                                        <?= $estadisticas['respaldos']['total_respaldos'] ?> respaldos creados
                                    <?php else: ?>
                                        No hay respaldos disponibles
                                    <?php endif; ?>
                                </small>
                            </div>
                        </div>
                        <div class="row text-center">
                            <div class="col-4">
                                <h5 class="text-success"><?= $estadisticas['respaldos']['respaldos_exitosos'] ?? 0 ?></h5>
                                <small class="text-muted">Exitosos</small>
                            </div>
                            <div class="col-4">
                                <h5 class="text-danger"><?= $estadisticas['respaldos']['respaldos_fallidos'] ?? 0 ?></h5>
                                <small class="text-muted">Fallidos</small>
                            </div>
                            <div class="col-4">
                                <h5 class="text-info"><?= number_format(($estadisticas['respaldos']['tamano_total_bytes'] ?? 0) / 1024 / 1024, 2) ?> MB</h5>
                                <small class="text-muted">Tamaño Total</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- System Health Row -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Estado del Sistema</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="text-center">
                                    <div class="mb-2">
                                        <i class="bi bi-check-circle text-success" style="font-size: 2rem;"></i>
                                    </div>
                                    <h6>Base de Datos</h6>
                                    <span class="badge bg-success">Operativa</span>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="text-center">
                                    <div class="mb-2">
                                        <i class="bi bi-check-circle text-success" style="font-size: 2rem;"></i>
                                    </div>
                                    <h6>Autenticación</h6>
                                    <span class="badge bg-success">Activa</span>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="text-center">
                                    <div class="mb-2">
                                        <i class="bi bi-check-circle text-success" style="font-size: 2rem;"></i>
                                    </div>
                                    <h6>Logs</h6>
                                    <span class="badge bg-success">Grabando</span>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="text-center">
                                    <div class="mb-2">
                                        <i class="bi bi-check-circle text-success" style="font-size: 2rem;"></i>
                                    </div>
                                    <h6>Respaldos</h6>
                                    <span class="badge bg-success">Configurado</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
let chartActividad, chartModulos;

$(document).ready(function() {
    inicializarGraficos();
    configurarActualizacionAutomatica();
});

function inicializarGraficos() {
    // Gráfico de actividad del sistema
    const ctxActividad = document.getElementById('chartActividad').getContext('2d');
    chartActividad = new Chart(ctxActividad, {
        type: 'line',
        data: {
            labels: <?= json_encode(array_column($estadisticas['tendencias'] ?? [], 'fecha')) ?>,
            datasets: [{
                label: 'Total de Acciones',
                data: <?= json_encode(array_column($estadisticas['tendencias'] ?? [], 'total_acciones')) ?>,
                borderColor: 'rgb(75, 192, 192)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                tension: 0.1
            }, {
                label: 'Usuarios Únicos',
                data: <?= json_encode(array_column($estadisticas['tendencias'] ?? [], 'usuarios_unicos')) ?>,
                borderColor: 'rgb(255, 99, 132)',
                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Gráfico de módulos más utilizados
    const ctxModulos = document.getElementById('chartModulos').getContext('2d');
    chartModulos = new Chart(ctxModulos, {
        type: 'doughnut',
        data: {
            labels: <?= json_encode(array_column($estadisticas['modulos_activos'] ?? [], 'modulo')) ?>,
            datasets: [{
                data: <?= json_encode(array_column($estadisticas['modulos_activos'] ?? [], 'total_acciones')) ?>,
                backgroundColor: [
                    '#FF6384',
                    '#36A2EB',
                    '#FFCE56',
                    '#4BC0C0',
                    '#9966FF',
                    '#FF9F40'
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
}

function actualizarEstadisticas() {
    fetch('<?= base_url('super-admin/estadisticas-tiempo-real') ?>')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                actualizarDatosEstadisticas(data.estadisticas);
                document.getElementById('ultimaActualizacion').textContent = new Date().toLocaleString('es-ES');
                
                Swal.fire({
                    icon: 'success',
                    title: 'Estadísticas Actualizadas',
                    text: 'Los datos han sido actualizados correctamente',
                    timer: 1500,
                    showConfirmButton: false
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Error al actualizar las estadísticas'
            });
        });
}

function actualizarDatosEstadisticas(estadisticas) {
    // Actualizar contadores
    document.getElementById('totalUsuarios').textContent = estadisticas.usuarios.total_usuarios || 0;
    document.getElementById('totalEmpleados').textContent = estadisticas.empleados.total_empleados || 0;
    document.getElementById('sesionesActivas').textContent = estadisticas.sesiones.sesiones_activas || 0;
    document.getElementById('totalLogs').textContent = estadisticas.logs.total_logs || 0;
    
    // Actualizar gráficos
    actualizarGraficoActividad(estadisticas.tendencias);
    actualizarGraficoModulos(estadisticas.modulos_activos);
}

function actualizarGraficoActividad(tendencias) {
    if (chartActividad && tendencias) {
        chartActividad.data.labels = tendencias.map(t => t.fecha);
        chartActividad.data.datasets[0].data = tendencias.map(t => t.total_acciones);
        chartActividad.data.datasets[1].data = tendencias.map(t => t.usuarios_unicos);
        chartActividad.update();
    }
}

function actualizarGraficoModulos(modulos) {
    if (chartModulos && modulos) {
        chartModulos.data.labels = modulos.map(m => m.modulo);
        chartModulos.data.datasets[0].data = modulos.map(m => m.total_acciones);
        chartModulos.update();
    }
}

function configurarActualizacionAutomatica() {
    // Actualizar cada 5 minutos
    setInterval(actualizarEstadisticas, 5 * 60 * 1000);
}
</script>

<?= $this->endSection() ?>
