<?php
$sidebar = 'sidebar_super_admin';
?>

<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>
<div class="page-wrapper">
    <div class="page-content">
        <!-- Breadcrumb -->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Dashboard</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
                        <li class="breadcrumb-item active" aria-current="page">Super Administrador</li>
                    </ol>
                </nav>
            </div>
        </div>

        <!-- Welcome Section -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <i class="ti ti-shield-check fs-1 text-primary"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h4 class="mb-1">Bienvenido, <?= $usuario['nombres'] ?> <?= $usuario['apellidos'] ?></h4>
                                <p class="mb-0 text-muted">Panel de Super Administración del Sistema</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row">
            <div class="col-xl-3 col-md-6">
                <div class="card radius-10 bg-primary">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <i class="ti ti-users fs-1 text-white"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h4 class="mb-0 text-white" id="total_usuarios">0</h4>
                                <p class="mb-0 text-white-50">Total Usuarios</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card radius-10 bg-success">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <i class="ti ti-user-check fs-1 text-white"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h4 class="mb-0 text-white" id="usuarios_activos">0</h4>
                                <p class="mb-0 text-white-50">Usuarios Activos</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card radius-10 bg-warning">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <i class="ti ti-role fs-1 text-white"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h4 class="mb-0 text-white" id="total_roles">0</h4>
                                <p class="mb-0 text-white-50">Roles del Sistema</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card radius-10 bg-info">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <i class="ti ti-database fs-1 text-white"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h4 class="mb-0 text-white" id="tamano_bd">0</h4>
                                <p class="mb-0 text-white-50">Tamaño BD (MB)</p>
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
                        <h5 class="card-title mb-0">Acciones Rápidas</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <a href="<?= site_url('super-admin/usuarios') ?>" class="btn btn-outline-primary w-100">
                                    <i class="ti ti-users me-2"></i>
                                    Gestionar Usuarios
                                </a>
                            </div>
                            <div class="col-md-3 mb-3">
                                <a href="<?= site_url('super-admin/roles') ?>" class="btn btn-outline-warning w-100">
                                    <i class="ti ti-role me-2"></i>
                                    Gestionar Roles
                                </a>
                            </div>
                            <div class="col-md-3 mb-3">
                                <a href="<?= site_url('super-admin/respaldos') ?>" class="btn btn-outline-info w-100">
                                    <i class="ti ti-database me-2"></i>
                                    Respaldos
                                </a>
                            </div>
                            <div class="col-md-3 mb-3">
                                <a href="<?= site_url('super-admin/configuracion') ?>" class="btn btn-outline-success w-100">
                                    <i class="ti ti-settings me-2"></i>
                                    Configuración
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- System Status -->
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Estado del Sistema</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-borderless">
                                <tbody>
                                    <tr>
                                        <td><strong>Servidor Web:</strong></td>
                                        <td><span class="badge bg-success">Activo</span></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Base de Datos:</strong></td>
                                        <td><span class="badge bg-success">Conectado</span></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Cache:</strong></td>
                                        <td><span class="badge bg-info">Habilitado</span></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Último Backup:</strong></td>
                                        <td>Hace 2 días</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Actividad Reciente</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-borderless">
                                <thead>
                                    <tr>
                                        <th>Usuario</th>
                                        <th>Acción</th>
                                        <th>Hora</th>
                                    </tr>
                                </thead>
                                <tbody id="actividad_reciente">
                                    <tr>
                                        <td colspan="3" class="text-center text-muted">No hay actividad reciente</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Usuarios por Rol</h5>
                    </div>
                    <div class="card-body">
                        <div id="chartUsuariosRol" style="height: 300px;"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Accesos por Día</h5>
                    </div>
                    <div class="card-body">
                        <div id="chartAccesosDiarios" style="height: 300px;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Cargar estadísticas
    cargarEstadisticas();
    
    // Inicializar gráficos
    inicializarGraficos();
    
    // Cargar datos de las tablas
    cargarDatosTablas();
});

function cargarEstadisticas() {
    // Simular carga de estadísticas
    document.getElementById('total_usuarios').textContent = '67';
    document.getElementById('usuarios_activos').textContent = '64';
    document.getElementById('total_roles').textContent = '6';
    document.getElementById('tamano_bd').textContent = '45.2';
}

function inicializarGraficos() {
    // Gráfico de usuarios por rol
    const optionsUsuariosRol = {
        series: [25, 20, 15, 5, 2],
        chart: {
            type: 'donut',
            height: 300
        },
        labels: ['Docentes', 'Administrativos', 'Directivos', 'Admin TH', 'Super Admin'],
        colors: ['#007bff', '#28a745', '#ffc107', '#dc3545', '#6c757d'],
        responsive: [{
            breakpoint: 480,
            options: {
                chart: {
                    width: 200
                },
                legend: {
                    position: 'bottom'
                }
            }
        }]
    };

    const chartUsuariosRol = new ApexCharts(document.querySelector("#chartUsuariosRol"), optionsUsuariosRol);
    chartUsuariosRol.render();

    // Gráfico de accesos diarios
    const optionsAccesosDiarios = {
        series: [{
            name: 'Accesos',
            data: [45, 52, 38, 24, 33, 26, 21, 20, 6, 8, 15, 10]
        }],
        chart: {
            type: 'line',
            height: 300,
            toolbar: {
                show: false
            }
        },
        stroke: {
            curve: 'smooth',
            width: 3
        },
        xaxis: {
            categories: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic']
        },
        yaxis: {
            title: {
                text: 'Cantidad de Accesos'
            }
        },
        colors: ['#007bff'],
        markers: {
            size: 5,
            colors: ['#007bff'],
            strokeColors: '#fff',
            strokeWidth: 2
        }
    };

    const chartAccesosDiarios = new ApexCharts(document.querySelector("#chartAccesosDiarios"), optionsAccesosDiarios);
    chartAccesosDiarios.render();
}

function cargarDatosTablas() {
    // Simular datos de actividad reciente
    const actividades = [
        { usuario: 'Admin TH', accion: 'Registró inasistencia', hora: '10:30' },
        { usuario: 'Docente', accion: 'Solicitó capacitación', hora: '09:15' },
        { usuario: 'Super Admin', accion: 'Creó nuevo usuario', hora: '08:45' }
    ];

    const tbodyActividad = document.getElementById('actividad_reciente');
    tbodyActividad.innerHTML = '';

    actividades.forEach(item => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${item.usuario}</td>
            <td>${item.accion}</td>
            <td>${item.hora}</td>
        `;
        tbodyActividad.appendChild(row);
    });
}
</script>
<?= $this->endSection() ?> 