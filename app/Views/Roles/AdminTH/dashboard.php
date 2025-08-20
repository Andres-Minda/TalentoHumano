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
                        <li class="breadcrumb-item active" aria-current="page">Admin Talento Humano</li>
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
                                <i class="ti ti-user-circle fs-1 text-primary"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h4 class="mb-1">Bienvenido, <?= $usuario['nombres'] ?> <?= $usuario['apellidos'] ?></h4>
                                <p class="mb-0 text-muted">Panel de Administración de Talento Humano</p>
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
                                <h4 class="mb-0 text-white" id="total_empleados">0</h4>
                                <p class="mb-0 text-white-50">Total Empleados</p>
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
                                <i class="ti ti-calendar-check fs-1 text-white"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h4 class="mb-0 text-white" id="empleados_activos">0</h4>
                                <p class="mb-0 text-white-50">Empleados Activos</p>
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
                                <i class="ti ti-calendar-off fs-1 text-white"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h4 class="mb-0 text-white" id="inasistencias_pendientes">0</h4>
                                <p class="mb-0 text-white-50">Inasistencias Pendientes</p>
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
                                <i class="ti ti-graduation-cap fs-1 text-white"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h4 class="mb-0 text-white" id="capacitaciones_activas">0</h4>
                                <p class="mb-0 text-white-50">Capacitaciones Activas</p>
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
                                <a href="<?= site_url('admin-th/empleados') ?>" class="btn btn-outline-primary w-100">
                                    <i class="ti ti-users me-2"></i>
                                    Gestionar Empleados
                                </a>
                            </div>
                            <div class="col-md-3 mb-3">
                                <a href="<?= site_url('admin-th/inasistencias') ?>" class="btn btn-outline-warning w-100">
                                    <i class="ti ti-calendar-off me-2"></i>
                                    Gestionar Inasistencias
                                </a>
                            </div>
                            <div class="col-md-3 mb-3">
                                <a href="<?= site_url('admin-th/capacitaciones') ?>" class="btn btn-outline-info w-100">
                                    <i class="ti ti-graduation-cap me-2"></i>
                                    Gestionar Capacitaciones
                                </a>
                            </div>
                            <div class="col-md-3 mb-3">
                                <a href="<?= site_url('admin-th/reportes') ?>" class="btn btn-outline-success w-100">
                                    <i class="ti ti-chart-bar me-2"></i>
                                    Ver Reportes
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activities -->
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Últimas Inasistencias</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-borderless">
                                <thead>
                                    <tr>
                                        <th>Empleado</th>
                                        <th>Fecha</th>
                                        <th>Estado</th>
                                    </tr>
                                </thead>
                                <tbody id="ultimas_inasistencias">
                                    <tr>
                                        <td colspan="3" class="text-center text-muted">No hay inasistencias recientes</td>
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
                        <h5 class="card-title mb-0">Solicitudes de Capacitación</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-borderless">
                                <thead>
                                    <tr>
                                        <th>Empleado</th>
                                        <th>Capacitación</th>
                                        <th>Estado</th>
                                    </tr>
                                </thead>
                                <tbody id="solicitudes_capacitacion">
                                    <tr>
                                        <td colspan="3" class="text-center text-muted">No hay solicitudes recientes</td>
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
                        <h5 class="card-title mb-0">Empleados por Departamento</h5>
                    </div>
                    <div class="card-body">
                        <div id="chartEmpleadosDepartamento" style="height: 300px;"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Inasistencias por Mes</h5>
                    </div>
                    <div class="card-body">
                        <div id="chartInasistenciasMensuales" style="height: 300px;"></div>
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
    document.getElementById('total_empleados').textContent = '45';
    document.getElementById('empleados_activos').textContent = '42';
    document.getElementById('inasistencias_pendientes').textContent = '3';
    document.getElementById('capacitaciones_activas').textContent = '8';
}

function inicializarGraficos() {
    // Gráfico de empleados por departamento
    const optionsEmpleadosDepartamento = {
        series: [15, 12, 8, 6, 4],
        chart: {
            type: 'donut',
            height: 300
        },
        labels: ['Docentes', 'Administrativos', 'Directivos', 'Auxiliares', 'Otros'],
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

    const chartEmpleadosDepartamento = new ApexCharts(document.querySelector("#chartEmpleadosDepartamento"), optionsEmpleadosDepartamento);
    chartEmpleadosDepartamento.render();

    // Gráfico de inasistencias mensuales
    const optionsInasistenciasMensuales = {
        series: [{
            name: 'Inasistencias',
            data: [5, 3, 7, 2, 4, 6, 3, 5, 4, 2, 3, 4]
        }],
        chart: {
            type: 'bar',
            height: 300,
            toolbar: {
                show: false
            }
        },
        plotOptions: {
            bar: {
                horizontal: false,
                columnWidth: '55%',
                endingShape: 'rounded'
            },
        },
        dataLabels: {
            enabled: false
        },
        stroke: {
            show: true,
            width: 2,
            colors: ['transparent']
        },
        xaxis: {
            categories: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic']
        },
        yaxis: {
            title: {
                text: 'Cantidad de Inasistencias'
            }
        },
        fill: {
            opacity: 1
        },
        tooltip: {
            y: {
                formatter: function (val) {
                    return val + " inasistencias"
                }
            }
        }
    };

    const chartInasistenciasMensuales = new ApexCharts(document.querySelector("#chartInasistenciasMensuales"), optionsInasistenciasMensuales);
    chartInasistenciasMensuales.render();
}

function cargarDatosTablas() {
    // Simular datos de inasistencias
    const inasistencias = [
        { empleado: 'Juan Pérez', fecha: '2025-01-20', estado: 'Pendiente' },
        { empleado: 'María García', fecha: '2025-01-19', estado: 'Aprobada' },
        { empleado: 'Carlos López', fecha: '2025-01-18', estado: 'Rechazada' }
    ];

    const tbodyInasistencias = document.getElementById('ultimas_inasistencias');
    tbodyInasistencias.innerHTML = '';

    inasistencias.forEach(item => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${item.empleado}</td>
            <td>${formatearFecha(item.fecha)}</td>
            <td>
                <span class="badge bg-${getEstadoBadgeColor(item.estado)}">
                    ${item.estado}
                </span>
            </td>
        `;
        tbodyInasistencias.appendChild(row);
    });

    // Simular datos de solicitudes de capacitación
    const solicitudes = [
        { empleado: 'Ana Martínez', capacitacion: 'Gestión de Proyectos', estado: 'Pendiente' },
        { empleado: 'Luis Rodríguez', capacitacion: 'Liderazgo Efectivo', estado: 'Aprobada' },
        { empleado: 'Carmen Silva', capacitacion: 'Comunicación Asertiva', estado: 'En Revisión' }
    ];

    const tbodySolicitudes = document.getElementById('solicitudes_capacitacion');
    tbodySolicitudes.innerHTML = '';

    solicitudes.forEach(item => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${item.empleado}</td>
            <td>${item.capacitacion}</td>
            <td>
                <span class="badge bg-${getSolicitudBadgeColor(item.estado)}">
                    ${item.estado}
                </span>
            </td>
        `;
        tbodySolicitudes.appendChild(row);
    });
}

function getEstadoBadgeColor(estado) {
    switch(estado) {
        case 'Aprobada': return 'success';
        case 'Rechazada': return 'danger';
        case 'Pendiente': return 'warning';
        default: return 'secondary';
    }
}

function getSolicitudBadgeColor(estado) {
    switch(estado) {
        case 'Aprobada': return 'success';
        case 'Rechazada': return 'danger';
        case 'Pendiente': return 'warning';
        case 'En Revisión': return 'info';
        default: return 'secondary';
    }
}

function formatearFecha(fecha) {
    return new Date(fecha).toLocaleDateString('es-ES');
}
</script>
<?= $this->endSection() ?>
