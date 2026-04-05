<?php $sidebar = 'sidebar_admin_th'; ?>
<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>

<div class="page-wrapper">
    <div class="page-content">

        <!-- Header -->
        <div class="row mb-3">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <h4 class="mb-0"><i class="bi bi-bar-chart me-2"></i>Estadísticas del Sistema</h4>
                    <span class="text-muted">Actualizado: <?= date('d/m/Y H:i') ?></span>
                </div>
            </div>
        </div>

        <!-- Tarjetas de resumen -->
        <div class="row">
            <div class="col-xl-3 col-md-6">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h3 class="mb-0"><?= $totalEmpleados ?></h3>
                                <p class="mb-0">Total Empleados Activos</p>
                            </div>
                            <i class="bi bi-people-fill" style="font-size:2.5rem;opacity:.3;"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h3 class="mb-0"><?= $capActivas ?></h3>
                                <p class="mb-0">Capacitaciones Activas</p>
                            </div>
                            <i class="bi bi-mortarboard-fill" style="font-size:2.5rem;opacity:.3;"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card bg-warning text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h3 class="mb-0"><?= $inasistenciasMes ?></h3>
                                <p class="mb-0">Inasistencias del Mes</p>
                            </div>
                            <i class="bi bi-calendar-x-fill" style="font-size:2.5rem;opacity:.3;"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h3 class="mb-0"><?= $evaluacionesPendientes ?></h3>
                                <p class="mb-0">Evaluaciones Pendientes</p>
                            </div>
                            <i class="bi bi-clipboard-data-fill" style="font-size:2.5rem;opacity:.3;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Gráficos fila 1 -->
        <div class="row">
            <div class="col-xl-6">
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title mb-0"><i class="bi bi-building me-1"></i>Empleados por Departamento</h6>
                    </div>
                    <div class="card-body">
                        <div id="chartEmpleadosDepartamento" style="min-height:300px;"></div>
                    </div>
                </div>
            </div>
            <div class="col-xl-6">
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title mb-0"><i class="bi bi-mortarboard me-1"></i>Capacitaciones por Estado</h6>
                    </div>
                    <div class="card-body">
                        <div id="chartCapacitacionesEstado" style="min-height:300px;"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Gráficos fila 2 -->
        <div class="row">
            <div class="col-xl-8">
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title mb-0"><i class="bi bi-calendar-check me-1"></i>Inasistencias por Mes (<?= date('Y') ?>)</h6>
                    </div>
                    <div class="card-body">
                        <div id="chartInasistenciasMes" style="min-height:260px;"></div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4">
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title mb-0"><i class="bi bi-clipboard-check me-1"></i>Distribución de Calificaciones</h6>
                    </div>
                    <div class="card-body">
                        <div id="chartCalificaciones" style="min-height:260px;"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tablas Top -->
        <div class="row">
            <!-- Top 5 empleados por capacitaciones -->
            <div class="col-xl-6">
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title mb-0"><i class="bi bi-trophy me-1"></i>Top 5 Empleados por Capacitaciones</h6>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-dark">
                                    <tr>
                                        <th>#</th>
                                        <th>Empleado</th>
                                        <th>Departamento</th>
                                        <th class="text-center">Total</th>
                                        <th class="text-center">Completadas</th>
                                        <th class="text-center">% Completado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($topEmpleadosCap)): ?>
                                        <?php
                                            $medallas = ['bg-warning', 'bg-secondary', 'bg-info', 'bg-primary', 'bg-success'];
                                        ?>
                                        <?php foreach ($topEmpleadosCap as $i => $emp): ?>
                                            <tr>
                                                <td>
                                                    <span class="badge <?= $medallas[$i] ?? 'bg-secondary' ?>">
                                                        <?= $i + 1 ?>º
                                                    </span>
                                                </td>
                                                <td><?= esc($emp['apellidos'] . ' ' . $emp['nombres']) ?></td>
                                                <td><small class="text-muted"><?= esc($emp['departamento']) ?></small></td>
                                                <td class="text-center">
                                                    <span class="badge bg-primary"><?= $emp['total_cap'] ?></span>
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge bg-success"><?= (int)($emp['completadas'] ?? 0) ?></span>
                                                </td>
                                                <td class="text-center">
                                                    <?php $pct = (int)($emp['porcentaje'] ?? 0); ?>
                                                    <span class="badge <?= $pct >= 80 ? 'bg-success' : ($pct >= 50 ? 'bg-warning text-dark' : 'bg-danger') ?>">
                                                        <?= $pct ?>%
                                                    </span>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="6" class="text-center text-muted py-4">
                                                <i class="bi bi-inbox d-block fs-2 mb-1"></i>
                                                Sin registros de capacitaciones aún
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Top departamentos con mayor inasistencia -->
            <div class="col-xl-6">
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title mb-0"><i class="bi bi-exclamation-triangle me-1"></i>Departamentos con Mayor Inasistencia (Mes Actual)</h6>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Departamento</th>
                                        <th class="text-center">Empleados</th>
                                        <th class="text-center">Inasistencias</th>
                                        <th class="text-center">% Afectación</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($topDeptosInasistencia)): ?>
                                        <?php foreach ($topDeptosInasistencia as $dept): ?>
                                            <?php $pct = (float)($dept['porcentaje'] ?? 0); ?>
                                            <tr>
                                                <td><?= esc($dept['departamento']) ?></td>
                                                <td class="text-center"><?= $dept['total_empleados'] ?></td>
                                                <td class="text-center">
                                                    <span class="badge bg-danger"><?= $dept['total_inasistencias'] ?></span>
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge <?= $pct >= 30 ? 'bg-danger' : ($pct >= 15 ? 'bg-warning text-dark' : 'bg-success') ?>">
                                                        <?= $pct ?>%
                                                    </span>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="4" class="text-center text-muted py-4">
                                                <i class="bi bi-check-circle text-success d-block fs-2 mb-1"></i>
                                                Sin inasistencias registradas este mes
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Métricas de rendimiento -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title mb-0"><i class="bi bi-speedometer2 me-1"></i>Métricas de Rendimiento</h6>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-md-3 mb-3">
                                <div class="border rounded p-3">
                                    <i class="bi bi-graph-up text-success fs-2"></i>
                                    <h6 class="mt-2">Tasa de Asistencia</h6>
                                    <h4 class="text-success mb-0"><?= esc($tasaAsistencia) ?></h4>
                                    <small class="text-muted">Promedio mensual</small>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="border rounded p-3">
                                    <i class="bi bi-mortarboard text-primary fs-2"></i>
                                    <h6 class="mt-2">Participación Capacitaciones</h6>
                                    <h4 class="text-primary mb-0"><?= esc($partCapacitaciones) ?></h4>
                                    <small class="text-muted">Último trimestre</small>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="border rounded p-3">
                                    <i class="bi bi-clipboard-check text-info fs-2"></i>
                                    <h6 class="mt-2">Promedio Evaluaciones</h6>
                                    <h4 class="text-info mb-0"><?= esc($promedioEvaluaciones) ?></h4>
                                    <small class="text-muted">Escala 1-10</small>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="border rounded p-3">
                                    <i class="bi bi-clock text-warning fs-2"></i>
                                    <h6 class="mt-2">Tiempo Respuesta Solicitudes</h6>
                                    <h4 class="text-warning mb-0"><?= esc($tiempoRespuesta) ?></h4>
                                    <small class="text-muted">Promedio histórico</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
document.addEventListener('DOMContentLoaded', function () {

    // ── Datos inyectados desde PHP ────────────────────────────────────────────
    const deptos   = <?= $graficoDeptos ?>;
    const capEst   = <?= $graficoCapEstado ?>;
    const inaMeses = <?= $graficoInasistenciasAnio ?>;
    const califs   = <?= $graficoCalificaciones ?>;

    const COLORES = ['#4361ee','#3bc9db','#f72585','#4cc9f0','#7209b7','#f3722c','#43aa8b','#90be6d','#e63946','#457b9d'];

    // ── Gráfico 1: Empleados por Departamento (donut) ─────────────────────────
    new ApexCharts(document.getElementById('chartEmpleadosDepartamento'), {
        series: deptos.data,
        labels: deptos.labels,
        chart: { type: 'donut', height: 300, toolbar: { show: false } },
        colors: COLORES,
        legend: { position: 'bottom', fontSize: '12px' },
        plotOptions: {
            pie: {
                donut: {
                    size: '58%',
                    labels: {
                        show: true,
                        total: {
                            show: true,
                            label: 'Total',
                            formatter: w => w.globals.seriesTotals.reduce((a, b) => a + b, 0)
                        }
                    }
                }
            }
        },
        dataLabels: { enabled: false },
        tooltip: { y: { formatter: v => v + ' empleado(s)' } },
        noData: { text: 'Sin datos disponibles' }
    }).render();

    // ── Gráfico 2: Capacitaciones por Estado (barras horizontales) ────────────
    new ApexCharts(document.getElementById('chartCapacitacionesEstado'), {
        series: [{ name: 'Capacitaciones', data: capEst.data }],
        chart: { type: 'bar', height: 300, toolbar: { show: false } },
        colors: ['#4361ee'],
        plotOptions: { bar: { horizontal: true, borderRadius: 4, barHeight: '55%' } },
        dataLabels: { enabled: true, formatter: v => v },
        xaxis: { categories: capEst.labels, labels: { style: { fontSize: '12px' } } },
        yaxis: { labels: { style: { fontSize: '12px' } } },
        tooltip: { y: { formatter: v => v + ' capacitación(es)' } },
        noData: { text: 'Sin datos disponibles' }
    }).render();

    // ── Gráfico 3: Inasistencias por Mes (área) ───────────────────────────────
    new ApexCharts(document.getElementById('chartInasistenciasMes'), {
        series: [{ name: 'Inasistencias', data: inaMeses }],
        chart: { type: 'area', height: 260, toolbar: { show: false }, zoom: { enabled: false } },
        colors: ['#f72585'],
        fill: {
            type: 'gradient',
            gradient: { shadeIntensity: 1, opacityFrom: 0.4, opacityTo: 0.05, stops: [0, 100] }
        },
        stroke: { curve: 'smooth', width: 2 },
        markers: { size: 4 },
        xaxis: {
            categories: ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'],
            labels: { style: { fontSize: '11px' } }
        },
        yaxis: { min: 0, tickAmount: 4, labels: { formatter: v => Math.floor(v) } },
        dataLabels: { enabled: false },
        tooltip: { y: { formatter: v => v + ' inasistencia(s)' } },
        noData: { text: 'Sin datos disponibles' }
    }).render();

    // ── Gráfico 4: Distribución de Calificaciones (pie) ───────────────────────
    new ApexCharts(document.getElementById('chartCalificaciones'), {
        series: califs.data.length ? califs.data : [1],
        labels: califs.labels.length ? califs.labels : ['Sin datos'],
        chart: { type: 'pie', height: 260, toolbar: { show: false } },
        colors: ['#43aa8b','#4361ee','#ffc107','#dc3545'],
        legend: { position: 'bottom', fontSize: '11px' },
        dataLabels: { enabled: true, formatter: (v, opts) => opts.w.globals.series[opts.seriesIndex] },
        tooltip: { y: { formatter: v => v + ' evaluación(es)' } },
        noData: { text: 'Sin evaluaciones calificadas' }
    }).render();
});
</script>
<?= $this->endSection() ?>
