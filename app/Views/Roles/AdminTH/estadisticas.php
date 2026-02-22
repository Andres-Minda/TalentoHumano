<?php $sidebar = 'sidebar_admin_th'; ?>
<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>

<div class="page-wrapper">
    <div class="page-content">
        <!-- Header -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <h4 class="mb-0"><i class="bi bi-bar-chart"></i> Estadísticas del Sistema</h4>
                    <div class="page-title-right">
                        <span class="text-muted">Métricas y análisis detallados</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Stats Cards -->
        <div class="row">
            <div class="col-xl-3 col-md-6">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4 class="mb-0" id="totalEmpleados">-</h4>
                                <p class="mb-0">Total Empleados</p>
                            </div>
                            <div class="align-self-center">
                                <i class="bi bi-people-fill" style="font-size: 2.5rem; opacity: 0.3;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4 class="mb-0" id="capacitacionesActivas">-</h4>
                                <p class="mb-0">Capacitaciones Activas</p>
                            </div>
                            <div class="align-self-center">
                                <i class="bi bi-mortarboard-fill" style="font-size: 2.5rem; opacity: 0.3;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card bg-warning text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4 class="mb-0" id="inasistenciasMes">-</h4>
                                <p class="mb-0">Inasistencias del Mes</p>
                            </div>
                            <div class="align-self-center">
                                <i class="bi bi-calendar-x-fill" style="font-size: 2.5rem; opacity: 0.3;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4 class="mb-0" id="evaluacionesPendientes">-</h4>
                                <p class="mb-0">Evaluaciones Pendientes</p>
                            </div>
                            <div class="align-self-center">
                                <i class="bi bi-clipboard-data-fill" style="font-size: 2.5rem; opacity: 0.3;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="row">
            <!-- Empleados por Departamento -->
            <div class="col-xl-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-building"></i> Empleados por Departamento
                        </h5>
                    </div>
                    <div class="card-body">
                        <canvas id="chartEmpleadosDepartamento" height="300"></canvas>
                    </div>
                </div>
            </div>
            
            <!-- Capacitaciones por Estado -->
            <div class="col-xl-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-mortarboard"></i> Capacitaciones por Estado
                        </h5>
                    </div>
                    <div class="card-body">
                        <canvas id="chartCapacitacionesEstado" height="300"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- More Charts Row -->
        <div class="row">
            <!-- Inasistencias por Mes -->
            <div class="col-xl-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-calendar-check"></i> Inasistencias por Mes (Último Año)
                        </h5>
                    </div>
                    <div class="card-body">
                        <canvas id="chartInasistenciasMes" height="200"></canvas>
                    </div>
                </div>
            </div>
            
            <!-- Evaluaciones por Calificación -->
            <div class="col-xl-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-clipboard-check"></i> Distribución de Calificaciones
                        </h5>
                    </div>
                    <div class="card-body">
                        <canvas id="chartCalificaciones" height="200"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detailed Statistics Tables -->
        <div class="row">
            <!-- Top Empleados por Capacitaciones -->
            <div class="col-xl-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-trophy"></i> Top 5 Empleados por Capacitaciones Completadas
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Posición</th>
                                        <th>Empleado</th>
                                        <th>Departamento</th>
                                        <th>Capacitaciones</th>
                                        <th>Puntuación</th>
                                    </tr>
                                </thead>
                                <tbody id="tablaTopEmpleados">
                                    <tr>
                                        <td colspan="5" class="text-center text-muted">
                                            <i class="bi bi-hourglass-split"></i> Cargando datos...
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Departamentos con Mayor Inasistencia -->
            <div class="col-xl-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-exclamation-triangle"></i> Departamentos con Mayor Inasistencia
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Departamento</th>
                                        <th>Total Empleados</th>
                                        <th>Inasistencias</th>
                                        <th>Porcentaje</th>
                                        <th>Tendencia</th>
                                    </tr>
                                </thead>
                                <tbody id="tablaDepartamentosInasistencia">
                                    <tr>
                                        <td colspan="5" class="text-center text-muted">
                                            <i class="bi bi-hourglass-split"></i> Cargando datos...
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Performance Metrics -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-speedometer2"></i> Métricas de Rendimiento
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3 text-center mb-3">
                                <div class="border rounded p-3">
                                    <i class="bi bi-graph-up text-success" style="font-size: 2rem;"></i>
                                    <h6 class="mt-2">Tasa de Asistencia</h6>
                                    <h4 class="text-success" id="tasaAsistencia">-</h4>
                                    <small class="text-muted">Promedio mensual</small>
                                </div>
                            </div>
                            <div class="col-md-3 text-center mb-3">
                                <div class="border rounded p-3">
                                    <i class="bi bi-mortarboard text-primary" style="font-size: 2rem;"></i>
                                    <h6 class="mt-2">Participación en Capacitaciones</h6>
                                    <h4 class="text-primary" id="participacionCapacitaciones">-</h4>
                                    <small class="text-muted">Último trimestre</small>
                                </div>
                            </div>
                            <div class="col-md-3 text-center mb-3">
                                <div class="border rounded p-3">
                                    <i class="bi bi-clipboard-check text-info" style="font-size: 2rem;"></i>
                                    <h6 class="mt-2">Promedio de Evaluaciones</h6>
                                    <h4 class="text-info" id="promedioEvaluaciones">-</h4>
                                    <small class="text-muted">Escala 1-10</small>
                                </div>
                            </div>
                            <div class="col-md-3 text-center mb-3">
                                <div class="border rounded p-3">
                                    <i class="bi bi-clock text-warning" style="font-size: 2rem;"></i>
                                    <h6 class="mt-2">Tiempo Promedio de Respuesta</h6>
                                    <h4 class="text-warning" id="tiempoRespuesta">-</h4>
                                    <small class="text-muted">En horas</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Export Options -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-download"></i> Exportar Estadísticas
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3 mb-2">
                                <button class="btn btn-outline-primary w-100" onclick="exportarEstadisticas('pdf')">
                                    <i class="bi bi-file-pdf"></i> Exportar a PDF
                                </button>
                            </div>
                            <div class="col-md-3 mb-2">
                                <button class="btn btn-outline-success w-100" onclick="exportarEstadisticas('excel')">
                                    <i class="bi bi-file-excel"></i> Exportar a Excel
                                </button>
                            </div>
                            <div class="col-md-3 mb-2">
                                <button class="btn btn-outline-info w-100" onclick="exportarEstadisticas('csv')">
                                    <i class="bi bi-file-text"></i> Exportar a CSV
                                </button>
                            </div>
                            <div class="col-md-3 mb-2">
                                <button class="btn btn-outline-secondary w-100" onclick="actualizarEstadisticas()">
                                    <i class="bi bi-arrow-clockwise"></i> Actualizar Datos
                                </button>
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
$(document).ready(function() {
    cargarEstadisticas();
    inicializarGraficos();
});

function cargarEstadisticas() {
    // Cargar estadísticas básicas
    Promise.all([
        fetch('<?= base_url('admin-th/empleados/estadisticas') ?>'),
        fetch('<?= base_url('admin-th/capacitaciones/estadisticas') ?>'),
        fetch('<?= base_url('admin-th/inasistencias/estadisticas') ?>'),
        fetch('<?= base_url('admin-th/evaluaciones/estadisticas') ?>')
    ])
    .then(responses => Promise.all(responses.map(r => r.json())))
    .then(data => {
        // Actualizar estadísticas básicas
        if (data[0].success) {
            document.getElementById('totalEmpleados').textContent = data[0].total_empleados || 0;
        }
        
        if (data[1].success) {
            document.getElementById('capacitacionesActivas').textContent = data[1].activas || 0;
        }
        
        if (data[2].success) {
            document.getElementById('inasistenciasMes').textContent = data[2].mes_actual || 0;
        }
        
        if (data[3].success) {
            document.getElementById('evaluacionesPendientes').textContent = data[3].pendientes || 0;
        }
        
        // Cargar tablas detalladas
        cargarTablaTopEmpleados();
        cargarTablaDepartamentosInasistencia();
        cargarMetricasRendimiento();
    })
    .catch(error => {
        console.error('Error cargando estadísticas:', error);
    });
}

function inicializarGraficos() {
    // Gráfico de Empleados por Departamento
    const ctxEmpleados = document.getElementById('chartEmpleadosDepartamento').getContext('2d');
    new Chart(ctxEmpleados, {
        type: 'doughnut',
        data: {
            labels: ['Administración', 'Docencia', 'Tecnología', 'Otros'],
            datasets: [{
                data: [25, 40, 20, 15],
                backgroundColor: ['#007bff', '#28a745', '#ffc107', '#6c757d'],
                borderWidth: 2
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

    // Gráfico de Capacitaciones por Estado
    const ctxCapacitaciones = document.getElementById('chartCapacitacionesEstado').getContext('2d');
    new Chart(ctxCapacitaciones, {
        type: 'bar',
        data: {
            labels: ['Activas', 'Completadas', 'Canceladas', 'Pendientes'],
            datasets: [{
                label: 'Cantidad',
                data: [12, 8, 3, 5],
                backgroundColor: ['#28a745', '#007bff', '#dc3545', '#ffc107'],
                borderWidth: 1
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

    // Gráfico de Inasistencias por Mes
    const ctxInasistencias = document.getElementById('chartInasistenciasMes').getContext('2d');
    new Chart(ctxInasistencias, {
        type: 'line',
        data: {
            labels: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
            datasets: [{
                label: 'Inasistencias',
                data: [15, 12, 18, 22, 19, 25, 28, 24, 20, 16, 14, 18],
                borderColor: '#ffc107',
                backgroundColor: 'rgba(255, 193, 7, 0.1)',
                tension: 0.4,
                fill: true
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

    // Gráfico de Calificaciones
    const ctxCalificaciones = document.getElementById('chartCalificaciones').getContext('2d');
    new Chart(ctxCalificaciones, {
        type: 'pie',
        data: {
            labels: ['Excelente (9-10)', 'Bueno (7-8)', 'Regular (5-6)', 'Bajo (1-4)'],
            datasets: [{
                data: [30, 45, 20, 5],
                backgroundColor: ['#28a745', '#007bff', '#ffc107', '#dc3545'],
                borderWidth: 2
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

function cargarTablaTopEmpleados() {
    // Simular carga de datos
    setTimeout(() => {
        const tbody = document.getElementById('tablaTopEmpleados');
        tbody.innerHTML = `
            <tr>
                <td><span class="badge bg-warning">1º</span></td>
                <td>María González</td>
                <td>Docencia</td>
                <td>15</td>
                <td><span class="badge bg-success">95%</span></td>
            </tr>
            <tr>
                <td><span class="badge bg-secondary">2º</span></td>
                <td>Carlos Rodríguez</td>
                <td>Tecnología</td>
                <td>14</td>
                <td><span class="badge bg-success">92%</span></td>
            </tr>
            <tr>
                <td><span class="badge bg-info">3º</span></td>
                <td>Ana Martínez</td>
                <td>Administración</td>
                <td>13</td>
                <td><span class="badge bg-success">88%</span></td>
            </tr>
            <tr>
                <td><span class="badge bg-primary">4º</span></td>
                <td>Luis Pérez</td>
                <td>Docencia</td>
                <td>12</td>
                <td><span class="badge bg-success">85%</span></td>
            </tr>
            <tr>
                <td><span class="badge bg-success">5º</span></td>
                <td>Carmen López</td>
                <td>Tecnología</td>
                <td>11</td>
                <td><span class="badge bg-success">82%</span></td>
            </tr>
        `;
    }, 1000);
}

function cargarTablaDepartamentosInasistencia() {
    // Simular carga de datos
    setTimeout(() => {
        const tbody = document.getElementById('tablaDepartamentosInasistencia');
        tbody.innerHTML = `
            <tr>
                <td>Docencia</td>
                <td>40</td>
                <td>28</td>
                <td><span class="badge bg-warning">70%</span></td>
                <td><i class="bi bi-arrow-up text-danger"></i></td>
            </tr>
            <tr>
                <td>Administración</td>
                <td>25</td>
                <td>15</td>
                <td><span class="badge bg-success">60%</span></td>
                <td><i class="bi bi-arrow-down text-success"></i></td>
            </tr>
            <tr>
                <td>Tecnología</td>
                <td>20</td>
                <td>12</td>
                <td><span class="badge bg-success">60%</span></td>
                <td><i class="bi bi-arrow-down text-success"></i></td>
            </tr>
        `;
    }, 1000);
}

function cargarMetricasRendimiento() {
    // Simular carga de métricas
    document.getElementById('tasaAsistencia').textContent = '92.5%';
    document.getElementById('participacionCapacitaciones').textContent = '78%';
    document.getElementById('promedioEvaluaciones').textContent = '7.8';
    document.getElementById('tiempoRespuesta').textContent = '4.2h';
}

function exportarEstadisticas(formato) {
    Swal.fire({
        title: 'Exportando Estadísticas',
        text: `Generando archivo en formato ${formato.toUpperCase()}...`,
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
    
    setTimeout(() => {
        Swal.fire({
            icon: 'success',
            title: 'Exportación Completada',
            text: `Las estadísticas se han exportado exitosamente en formato ${formato.toUpperCase()}`,
            confirmButtonText: 'Descargar',
            showCancelButton: true,
            cancelButtonText: 'Cerrar'
        }).then((result) => {
            if (result.isConfirmed) {
                // Simular descarga
                Swal.fire({
                    icon: 'success',
                    title: 'Descarga Iniciada',
                    text: 'El archivo se está descargando...',
                    timer: 2000,
                    showConfirmButton: false
                });
            }
        });
    }, 2000);
}

function actualizarEstadisticas() {
    Swal.fire({
        title: 'Actualizando Datos',
        text: 'Recopilando información más reciente...',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
    
    setTimeout(() => {
        cargarEstadisticas();
        Swal.fire({
            icon: 'success',
            title: 'Datos Actualizados',
            text: 'Las estadísticas se han actualizado exitosamente',
            timer: 1500,
            showConfirmButton: false
        });
    }, 1500);
}
</script>

<?= $this->endSection() ?>
