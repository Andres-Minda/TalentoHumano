<?php $sidebar = 'sidebar_admin_th'; ?>
<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>

<div class="page-wrapper">
    <div class="page-content">
        <!-- Header -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <h4 class="mb-0"><i class="bi bi-graph-up"></i> Reportes y Analítica</h4>
                    <div class="page-title-right">
                        <button type="button" class="btn btn-primary" onclick="exportarReporte()">
                            <i class="bi bi-download"></i> Exportar Reporte
                        </button>
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
                                    <h5 class="font-weight-semibold mb-0"><?= $estadisticas['total_empleados'] ?? 0 ?></h5>
                                </div>
                                <div class="stat-digit d-flex">
                                    <h5 class="font-weight-semibold mb-0">Empleados</h5>
                                </div>
                            </div>
                            <div class="ms-auto">
                                <i class="bi bi-people text-primary" style="font-size: 2rem;"></i>
                            </div>
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
                                    <h5 class="font-weight-semibold mb-0"><?= $estadisticas['total_departamentos'] ?? 0 ?></h5>
                                </div>
                                <div class="stat-digit d-flex">
                                    <h5 class="font-weight-semibold mb-0">Departamentos</h5>
                                </div>
                            </div>
                            <div class="ms-auto">
                                <i class="bi bi-building text-success" style="font-size: 2rem;"></i>
                            </div>
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
                                    <h5 class="font-weight-semibold mb-0"><?= $estadisticas['total_capacitaciones'] ?? 0 ?></h5>
                                </div>
                                <div class="stat-digit d-flex">
                                    <h5 class="font-weight-semibold mb-0">Capacitaciones</h5>
                                </div>
                            </div>
                            <div class="ms-auto">
                                <i class="bi bi-mortarboard text-warning" style="font-size: 2rem;"></i>
                            </div>
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
                                    <h5 class="font-weight-semibold mb-0"><?= $estadisticas['total_vacantes'] ?? 0 ?></h5>
                                </div>
                                <div class="stat-digit d-flex">
                                    <h5 class="font-weight-semibold mb-0">Vacantes</h5>
                                </div>
                            </div>
                            <div class="ms-auto">
                                <i class="bi bi-briefcase text-info" style="font-size: 2rem;"></i>
                            </div>
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
                        <h5 class="card-title mb-0">Distribución de Empleados por Departamento</h5>
                    </div>
                    <div class="card-body">
                        <div id="chartEmpleadosDepartamento"></div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Estado de Vacantes</h5>
                    </div>
                    <div class="card-body">
                        <div id="chartVacantes"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detailed Reports -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Reportes Detallados</h5>
                    </div>
                    <div class="card-body">
                        <ul class="nav nav-tabs" id="reportTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="empleados-tab" data-bs-toggle="tab" data-bs-target="#empleados" type="button" role="tab">
                                    <i class="bi bi-people"></i> Empleados
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="capacitaciones-tab" data-bs-toggle="tab" data-bs-target="#capacitaciones" type="button" role="tab">
                                    <i class="bi bi-mortarboard"></i> Capacitaciones
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="vacantes-tab" data-bs-toggle="tab" data-bs-target="#vacantes" type="button" role="tab">
                                    <i class="bi bi-briefcase"></i> Vacantes
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="asistencias-tab" data-bs-toggle="tab" data-bs-target="#asistencias" type="button" role="tab">
                                    <i class="bi bi-calendar-check"></i> Asistencias
                                </button>
                            </li>
                        </ul>
                        <div class="tab-content" id="reportTabsContent">
                            <!-- Empleados Tab -->
                            <div class="tab-pane fade show active" id="empleados" role="tabpanel">
                                <div class="table-responsive mt-3">
                                    <table class="table table-striped" id="tablaEmpleados">
                                        <thead>
                                            <tr>
                                                <th>Empleado</th>
                                                <th>Departamento</th>
                                                <th>Puesto</th>
                                                <th>Fecha Ingreso</th>
                                                <th>Salario</th>
                                                <th>Estado</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($empleados as $empleado): ?>
                                            <tr>
                                                <td><?= $empleado['nombres'] . ' ' . $empleado['apellidos'] ?></td>
                                                <td><?= $empleado['departamento_nombre'] ?? 'N/A' ?></td>
                                                <td><?= $empleado['puesto_nombre'] ?? 'N/A' ?></td>
                                                <td><?= date('d/m/Y', strtotime($empleado['fecha_ingreso'])) ?></td>
                                                <td>$<?= number_format($empleado['salario'], 0, ',', '.') ?></td>
                                                <td>
                                                    <span class="badge bg-<?= $empleado['estado'] == 'Activo' ? 'success' : 'danger' ?>">
                                                        <?= $empleado['estado'] ?>
                                                    </span>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Capacitaciones Tab -->
                            <div class="tab-pane fade" id="capacitaciones" role="tabpanel">
                                <div class="table-responsive mt-3">
                                    <table class="table table-striped" id="tablaCapacitaciones">
                                        <thead>
                                            <tr>
                                                <th>Capacitación</th>
                                                <th>Tipo</th>
                                                <th>Fecha Inicio</th>
                                                <th>Fecha Fin</th>
                                                <th>Costo</th>
                                                <th>Inscritos</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($capacitaciones as $capacitacion): ?>
                                            <tr>
                                                <td><?= $capacitacion['nombre'] ?></td>
                                                <td><?= $capacitacion['tipo'] ?></td>
                                                <td><?= date('d/m/Y', strtotime($capacitacion['fecha_inicio'])) ?></td>
                                                <td><?= date('d/m/Y', strtotime($capacitacion['fecha_fin'])) ?></td>
                                                <td>$<?= number_format($capacitacion['costo'], 0, ',', '.') ?></td>
                                                <td><?= $capacitacion['total_inscritos'] ?? 0 ?></td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Vacantes Tab -->
                            <div class="tab-pane fade" id="vacantes" role="tabpanel">
                                <div class="table-responsive mt-3">
                                    <table class="table table-striped" id="tablaVacantes">
                                        <thead>
                                            <tr>
                                                <th>Vacante</th>
                                                <th>Departamento</th>
                                                <th>Fecha Publicación</th>
                                                <th>Estado</th>
                                                <th>Candidatos</th>
                                                <th>Salario</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($vacantes as $vacante): ?>
                                            <tr>
                                                <td><?= $vacante['nombre'] ?></td>
                                                <td><?= $vacante['departamento_nombre'] ?? 'N/A' ?></td>
                                                <td><?= date('d/m/Y', strtotime($vacante['fecha_publicacion'])) ?></td>
                                                <td>
                                                    <span class="badge bg-<?= $vacante['estado'] == 'Abierta' ? 'success' : 'secondary' ?>">
                                                        <?= $vacante['estado'] ?>
                                                    </span>
                                                </td>
                                                <td><?= $vacante['total_candidatos'] ?? 0 ?></td>
                                                <td>$<?= number_format($vacante['salario_min'], 0, ',', '.') ?> - $<?= number_format($vacante['salario_max'], 0, ',', '.') ?></td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Asistencias Tab -->
                            <div class="tab-pane fade" id="asistencias" role="tabpanel">
                                <div class="table-responsive mt-3">
                                    <table class="table table-striped" id="tablaAsistencias">
                                        <thead>
                                            <tr>
                                                <th>Empleado</th>
                                                <th>Fecha</th>
                                                <th>Hora Entrada</th>
                                                <th>Hora Salida</th>
                                                <th>Horas Trabajadas</th>
                                                <th>Estado</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($asistencias as $asistencia): ?>
                                            <tr>
                                                <td><?= $asistencia['empleado_nombre'] ?></td>
                                                <td><?= date('d/m/Y', strtotime($asistencia['fecha'])) ?></td>
                                                <td><?= $asistencia['hora_entrada'] ?></td>
                                                <td><?= $asistencia['hora_salida'] ?? 'N/A' ?></td>
                                                <td><?= $asistencia['horas_trabajadas'] ?? 'N/A' ?></td>
                                                <td>
                                                    <span class="badge bg-<?= $asistencia['estado'] == 'Presente' ? 'success' : 'danger' ?>">
                                                        <?= $asistencia['estado'] ?>
                                                    </span>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
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
    // Initialize DataTables
    $('#tablaEmpleados').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json'
        },
        pageLength: 10,
        order: [[0, 'asc']]
    });

    $('#tablaCapacitaciones').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json'
        },
        pageLength: 10,
        order: [[2, 'desc']]
    });

    $('#tablaVacantes').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json'
        },
        pageLength: 10,
        order: [[2, 'desc']]
    });

    $('#tablaAsistencias').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json'
        },
        pageLength: 10,
        order: [[1, 'desc']]
    });

    // Initialize Charts
    initializeCharts();
});

function initializeCharts() {
    // Chart 1: Distribución de Empleados por Departamento
    const empleadosData = <?= json_encode($chartData['empleados_por_departamento'] ?? []) ?>;
    
    const chartEmpleados = new ApexCharts(document.querySelector("#chartEmpleadosDepartamento"), {
        series: empleadosData.map(item => item.cantidad),
        chart: {
            type: 'bar',
            height: 350,
            toolbar: {
                show: true
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
            categories: empleadosData.map(item => item.departamento),
        },
        yaxis: {
            title: {
                text: 'Cantidad de Empleados'
            }
        },
        fill: {
            opacity: 1
        },
        tooltip: {
            y: {
                formatter: function (val) {
                    return val + " empleados"
                }
            }
        }
    });
    chartEmpleados.render();

    // Chart 2: Estado de Vacantes
    const vacantesData = <?= json_encode($chartData['estado_vacantes'] ?? []) ?>;
    
    const chartVacantes = new ApexCharts(document.querySelector("#chartVacantes"), {
        series: vacantesData.map(item => item.cantidad),
        chart: {
            type: 'donut',
            height: 300
        },
        labels: vacantesData.map(item => item.estado),
        colors: ['#28a745', '#6c757d', '#ffc107'],
        legend: {
            position: 'bottom'
        },
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
    });
    chartVacantes.render();
}

function exportarReporte() {
    // Implementar exportación de reporte
    Swal.fire({
        title: 'Exportando Reporte',
        text: 'Generando archivo de reporte...',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    // Simular proceso de exportación
    setTimeout(() => {
        Swal.fire({
            icon: 'success',
            title: 'Reporte Exportado',
            text: 'El reporte se ha descargado correctamente',
            confirmButtonText: 'Aceptar'
        });
    }, 2000);
}
</script>

<?= $this->endSection() ?> 