<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>
<div class="page-wrapper">
    <div class="page-content">
        <!-- Breadcrumb -->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Estadísticas</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="<?= site_url('admin-th/dashboard') ?>">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Estadísticas</li>
                    </ol>
                </nav>
            </div>
        </div>

        <!-- Resumen Ejecutivo -->
        <div class="row">
            <div class="col-md-3">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h3 class="text-white mb-0" id="totalEmpleados">127</h3>
                                <span class="text-white-50">Total Empleados</span>
                            </div>
                            <div class="align-self-center">
                                <i class="ti ti-users fs-2"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h3 class="text-white mb-0" id="capacitacionesActivas">23</h3>
                                <span class="text-white-50">Capacitaciones Activas</span>
                            </div>
                            <div class="align-self-center">
                                <i class="ti ti-school fs-2"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h3 class="text-white mb-0" id="evaluacionesPendientes">8</h3>
                                <span class="text-white-50">Evaluaciones Pendientes</span>
                            </div>
                            <div class="align-self-center">
                                <i class="ti ti-clipboard-check fs-2"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h3 class="text-white mb-0" id="solicitudesPendientes">15</h3>
                                <span class="text-white-50">Solicitudes Pendientes</span>
                            </div>
                            <div class="align-self-center">
                                <i class="ti ti-file-text fs-2"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Gráficos de Estadísticas -->
        <div class="row">
            <!-- Distribución de Empleados por Tipo -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Distribución de Empleados por Tipo</h5>
                    </div>
                    <div class="card-body">
                        <div id="chartEmpleadosTipo"></div>
                    </div>
                </div>
            </div>

            <!-- Capacitaciones por Mes -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Capacitaciones por Mes</h5>
                    </div>
                    <div class="card-body">
                        <div id="chartCapacitacionesMes"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Inasistencias por Departamento -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Inasistencias por Departamento</h5>
                    </div>
                    <div class="card-body">
                        <div id="chartInasistenciasDpto"></div>
                    </div>
                </div>
            </div>

            <!-- Estado de Evaluaciones -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Estado de Evaluaciones</h5>
                    </div>
                    <div class="card-body">
                        <div id="chartEstadoEvaluaciones"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tendencias Anuales -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Tendencias Anuales</h5>
                        <div class="card-actions">
                            <select class="form-select form-select-sm" id="filtroAnio">
                                <option value="2025">2025</option>
                                <option value="2024">2024</option>
                                <option value="2023">2023</option>
                            </select>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="chartTendenciasAnuales"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabla de Métricas Detalladas -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Métricas Detalladas por Departamento</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped" id="tablaMetricas">
                                <thead>
                                    <tr>
                                        <th>Departamento</th>
                                        <th>Empleados</th>
                                        <th>Capacitaciones</th>
                                        <th>Evaluaciones</th>
                                        <th>Inasistencias</th>
                                        <th>% Rendimiento</th>
                                    </tr>
                                </thead>
                                <tbody id="tbodyMetricas">
                                    <!-- Los datos se cargarán dinámicamente -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="<?= base_url('sistema/assets/libs/apexcharts/src/apexcharts.js') ?>"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    initCharts();
    cargarMetricasDetalladas();
});

function initCharts() {
    // Gráfico de Distribución de Empleados por Tipo
    const chartEmpleadosTipo = new ApexCharts(document.querySelector("#chartEmpleadosTipo"), {
        series: [65, 35, 15, 12],
        chart: {
            type: 'donut',
            height: 300
        },
        labels: ['Docentes', 'Administrativos', 'Directivos', 'Auxiliares'],
        colors: ['#3b82f6', '#10b981', '#f59e0b', '#ef4444'],
        legend: {
            position: 'bottom'
        }
    });
    chartEmpleadosTipo.render();

    // Gráfico de Capacitaciones por Mes
    const chartCapacitacionesMes = new ApexCharts(document.querySelector("#chartCapacitacionesMes"), {
        series: [{
            name: 'Capacitaciones',
            data: [8, 12, 15, 10, 18, 20, 22, 16, 14, 19, 15, 12]
        }],
        chart: {
            type: 'line',
            height: 300
        },
        xaxis: {
            categories: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic']
        },
        colors: ['#10b981'],
        stroke: {
            curve: 'smooth'
        }
    });
    chartCapacitacionesMes.render();

    // Gráfico de Inasistencias por Departamento
    const chartInasistenciasDpto = new ApexCharts(document.querySelector("#chartInasistenciasDpto"), {
        series: [{
            name: 'Inasistencias',
            data: [12, 8, 5, 15, 10]
        }],
        chart: {
            type: 'bar',
            height: 300
        },
        xaxis: {
            categories: ['TI', 'RRHH', 'Finanzas', 'Académico', 'Administración']
        },
        colors: ['#f59e0b']
    });
    chartInasistenciasDpto.render();

    // Gráfico de Estado de Evaluaciones
    const chartEstadoEvaluaciones = new ApexCharts(document.querySelector("#chartEstadoEvaluaciones"), {
        series: [45, 25, 15, 15],
        chart: {
            type: 'pie',
            height: 300
        },
        labels: ['Completadas', 'En Proceso', 'Pendientes', 'Vencidas'],
        colors: ['#10b981', '#3b82f6', '#f59e0b', '#ef4444']
    });
    chartEstadoEvaluaciones.render();

    // Gráfico de Tendencias Anuales
    const chartTendenciasAnuales = new ApexCharts(document.querySelector("#chartTendenciasAnuales"), {
        series: [
            {
                name: 'Contrataciones',
                data: [15, 8, 12, 20, 18, 25, 30, 22, 16, 19, 14, 12]
            },
            {
                name: 'Capacitaciones',
                data: [8, 12, 15, 10, 18, 20, 22, 16, 14, 19, 15, 12]
            },
            {
                name: 'Evaluaciones',
                data: [25, 20, 30, 28, 35, 40, 38, 32, 28, 30, 25, 22]
            }
        ],
        chart: {
            type: 'area',
            height: 350,
            stacked: false
        },
        xaxis: {
            categories: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic']
        },
        colors: ['#3b82f6', '#10b981', '#f59e0b'],
        fill: {
            type: 'gradient',
            opacity: 0.6
        }
    });
    chartTendenciasAnuales.render();
}

function cargarMetricasDetalladas() {
    // Datos simulados de métricas por departamento
    const metricas = [
        { departamento: 'Tecnología de la Información', empleados: 25, capacitaciones: 12, evaluaciones: 20, inasistencias: 8, rendimiento: 92 },
        { departamento: 'Recursos Humanos', empleados: 15, capacitaciones: 8, evaluaciones: 12, inasistencias: 5, rendimiento: 95 },
        { departamento: 'Finanzas', empleados: 18, capacitaciones: 6, evaluaciones: 15, inasistencias: 3, rendimiento: 98 },
        { departamento: 'Académico', empleados: 45, capacitaciones: 20, evaluaciones: 35, inasistencias: 15, rendimiento: 88 },
        { departamento: 'Administrativo', empleados: 24, capacitaciones: 10, evaluaciones: 18, inasistencias: 10, rendimiento: 85 }
    ];

    const tbody = document.getElementById('tbodyMetricas');
    tbody.innerHTML = '';

    metricas.forEach(metrica => {
        const row = document.createElement('tr');
        const rendimientoColor = metrica.rendimiento >= 90 ? 'success' : metrica.rendimiento >= 80 ? 'warning' : 'danger';
        
        row.innerHTML = `
            <td><strong>${metrica.departamento}</strong></td>
            <td>${metrica.empleados}</td>
            <td>${metrica.capacitaciones}</td>
            <td>${metrica.evaluaciones}</td>
            <td>${metrica.inasistencias}</td>
            <td><span class="badge bg-${rendimientoColor}">${metrica.rendimiento}%</span></td>
        `;
        tbody.appendChild(row);
    });
}

// Filtro por año para tendencias
document.getElementById('filtroAnio').addEventListener('change', function() {
    const anio = this.value;
    console.log('Cargando datos para el año:', anio);
    // Aquí implementarías la lógica para actualizar el gráfico con datos del año seleccionado
});
</script>
<?= $this->endSection() ?>
