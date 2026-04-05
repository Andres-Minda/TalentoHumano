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

        <!-- Welcome -->
        <div class="row mb-3">
            <div class="col-12">
                <div class="card">
                    <div class="card-body py-3">
                        <div class="d-flex align-items-center">
                            <i class="ti ti-user-circle fs-1 text-primary me-3"></i>
                            <div>
                                <h5 class="mb-0">Bienvenido, <?= esc($usuario['nombres']) ?> <?= esc($usuario['apellidos']) ?></h5>
                                <small class="text-muted">Panel de Administración de Talento Humano — <?= date('d/m/Y') ?></small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tarjetas de resumen -->
        <div class="row">
            <div class="col-xl-2 col-md-4 col-6">
                <div class="card radius-10 bg-primary text-white">
                    <div class="card-body text-center py-3">
                        <i class="ti ti-users fs-2"></i>
                        <h3 class="mb-0 mt-1"><?= $totalEmpleados ?></h3>
                        <small class="text-white-50">Total Empleados</small>
                    </div>
                </div>
            </div>
            <div class="col-xl-2 col-md-4 col-6">
                <div class="card radius-10 bg-success text-white">
                    <div class="card-body text-center py-3">
                        <i class="ti ti-user-check fs-2"></i>
                        <h3 class="mb-0 mt-1"><?= $empleadosActivos ?></h3>
                        <small class="text-white-50">Activos</small>
                    </div>
                </div>
            </div>
            <div class="col-xl-2 col-md-4 col-6">
                <div class="card radius-10 bg-warning text-white">
                    <div class="card-body text-center py-3">
                        <i class="ti ti-calendar-off fs-2"></i>
                        <h3 class="mb-0 mt-1"><?= $inasistenciasMesActual ?></h3>
                        <small class="text-white-50">Inasistencias (mes)</small>
                    </div>
                </div>
            </div>
            <div class="col-xl-2 col-md-4 col-6">
                <div class="card radius-10 bg-danger text-white">
                    <div class="card-body text-center py-3">
                        <i class="ti ti-clipboard-list fs-2"></i>
                        <h3 class="mb-0 mt-1"><?= $solicitudesPendientes ?></h3>
                        <small class="text-white-50">Solicitudes Pendientes</small>
                    </div>
                </div>
            </div>
            <div class="col-xl-2 col-md-4 col-6">
                <div class="card radius-10 bg-info text-white">
                    <div class="card-body text-center py-3">
                        <i class="ti ti-briefcase fs-2"></i>
                        <h3 class="mb-0 mt-1"><?= $puestosAbiertos ?></h3>
                        <small class="text-white-50">Puestos Abiertos</small>
                    </div>
                </div>
            </div>
            <div class="col-xl-2 col-md-4 col-6">
                <div class="card radius-10 bg-secondary text-white">
                    <div class="card-body text-center py-3">
                        <i class="ti ti-graduation-cap fs-2"></i>
                        <h3 class="mb-0 mt-1"><?= $capacitacionesActivas ?></h3>
                        <small class="text-white-50">Capacitaciones</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Gráficos -->
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title mb-0">Empleados por Departamento</h6>
                    </div>
                    <div class="card-body">
                        <div id="chartDepartamentos" style="min-height:280px;"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title mb-0">Estado de Solicitudes</h6>
                    </div>
                    <div class="card-body">
                        <div id="chartSolicitudes" style="min-height:280px;"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title mb-0">Inasistencias <?= date('Y') ?></h6>
                    </div>
                    <div class="card-body">
                        <div id="chartInasistencias" style="min-height:280px;"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tablas recientes -->
        <div class="row">
            <!-- Últimas inasistencias -->
            <div class="col-md-7">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h6 class="card-title mb-0">Últimas Inasistencias</h6>
                        <a href="<?= site_url('admin-th/inasistencias') ?>" class="btn btn-sm btn-outline-warning">Ver todas</a>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Empleado</th>
                                        <th>Fecha</th>
                                        <th>Tipo</th>
                                        <th>Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($ultimasInasistencias)): ?>
                                        <?php foreach ($ultimasInasistencias as $ina): ?>
                                            <?php
                                                $tipo  = $ina['tipo_inasistencia'] ?? ($ina['justificada'] ? 'Justificada' : 'Injustificada');
                                                $color = match($tipo) {
                                                    'Justificada'    => 'success',
                                                    'Injustificada'  => 'danger',
                                                    'Permiso'        => 'info',
                                                    'Vacaciones'     => 'primary',
                                                    'Licencia Médica' => 'warning',
                                                    default          => 'secondary'
                                                };
                                            ?>
                                            <tr>
                                                <td><?= esc($ina['apellidos'] . ' ' . $ina['nombres']) ?></td>
                                                <td><?= date('d/m/Y', strtotime($ina['fecha_inasistencia'])) ?></td>
                                                <td><span class="badge bg-<?= $color ?>"><?= esc($tipo) ?></span></td>
                                                <td>
                                                    <?php if ($ina['justificada']): ?>
                                                        <span class="badge bg-success">Justificada</span>
                                                    <?php else: ?>
                                                        <span class="badge bg-warning text-dark">Sin justificar</span>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="4" class="text-center text-muted py-3">
                                                <i class="bi bi-check-circle text-success me-1"></i>Sin inasistencias recientes
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Últimas solicitudes -->
            <div class="col-md-5">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h6 class="card-title mb-0">Solicitudes Recientes</h6>
                        <a href="<?= site_url('admin-th/solicitudes/vacaciones') ?>" class="btn btn-sm btn-outline-primary">Ver todas</a>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Empleado</th>
                                        <th>Tipo</th>
                                        <th>Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($ultimasSolicitudes)): ?>
                                        <?php foreach ($ultimasSolicitudes as $sol): ?>
                                            <?php
                                                $badgeMap = [
                                                    'Pendiente'   => 'warning text-dark',
                                                    'Aprobada'    => 'success',
                                                    'Rechazada'   => 'danger',
                                                    'En revisión' => 'info',
                                                    'Cancelada'   => 'secondary',
                                                ];
                                                $badge = $badgeMap[$sol['estado']] ?? 'secondary';
                                            ?>
                                            <tr>
                                                <td>
                                                    <small><?= esc($sol['apellidos'] . ' ' . $sol['nombres']) ?></small>
                                                </td>
                                                <td>
                                                    <small class="text-muted"><?= esc($sol['tipo_solicitud']) ?></small>
                                                </td>
                                                <td>
                                                    <span class="badge bg-<?= $badge ?>"><?= $sol['estado'] ?></span>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="3" class="text-center text-muted py-3">Sin solicitudes recientes</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Acciones rápidas -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title mb-0">Acciones Rápidas</h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-2">
                            <div class="col-md-2 col-4">
                                <a href="<?= site_url('admin-th/empleados') ?>" class="btn btn-outline-primary w-100 btn-sm">
                                    <i class="ti ti-users d-block fs-4 mb-1"></i>Empleados
                                </a>
                            </div>
                            <div class="col-md-2 col-4">
                                <a href="<?= site_url('admin-th/inasistencias') ?>" class="btn btn-outline-warning w-100 btn-sm">
                                    <i class="ti ti-calendar-off d-block fs-4 mb-1"></i>Inasistencias
                                </a>
                            </div>
                            <div class="col-md-2 col-4">
                                <a href="<?= site_url('admin-th/solicitudes/vacaciones') ?>" class="btn btn-outline-danger w-100 btn-sm">
                                    <i class="ti ti-clipboard-list d-block fs-4 mb-1"></i>Solicitudes
                                </a>
                            </div>
                            <div class="col-md-2 col-4">
                                <a href="<?= site_url('admin-th/capacitaciones') ?>" class="btn btn-outline-info w-100 btn-sm">
                                    <i class="ti ti-graduation-cap d-block fs-4 mb-1"></i>Capacitaciones
                                </a>
                            </div>
                            <div class="col-md-2 col-4">
                                <a href="<?= site_url('admin-th/puestos') ?>" class="btn btn-outline-secondary w-100 btn-sm">
                                    <i class="ti ti-briefcase d-block fs-4 mb-1"></i>Puestos
                                </a>
                            </div>
                            <div class="col-md-2 col-4">
                                <a href="<?= site_url('admin-th/reportes') ?>" class="btn btn-outline-success w-100 btn-sm">
                                    <i class="ti ti-chart-bar d-block fs-4 mb-1"></i>Reportes
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {

    // ── Datos inyectados desde PHP ────────────────────────────────────────────
    const deptLabels = <?= $chartDeptLabels ?>;
    const deptData   = <?= $chartDeptData ?>;
    const solLabels  = <?= $chartSolLabels ?>;
    const solData    = <?= $chartSolData ?>;
    const inaData    = <?= $chartInasistencias ?>;

    const COLORES = ['#4361ee','#3bc9db','#f72585','#4cc9f0','#7209b7','#f3722c','#43aa8b','#90be6d'];

    // ── Gráfico 1: Empleados por Departamento (donut) ─────────────────────────
    new ApexCharts(document.getElementById('chartDepartamentos'), {
        series: deptData,
        labels: deptLabels,
        chart: { type: 'donut', height: 280, toolbar: { show: false } },
        colors: COLORES,
        legend: { position: 'bottom', fontSize: '12px' },
        plotOptions: {
            pie: {
                donut: {
                    size: '60%',
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
        tooltip: { y: { formatter: val => val + ' empleado(s)' } },
        noData: { text: 'Sin datos' }
    }).render();

    // ── Gráfico 2: Estado de Solicitudes (donut) ──────────────────────────────
    new ApexCharts(document.getElementById('chartSolicitudes'), {
        series: solData,
        labels: solLabels,
        chart: { type: 'donut', height: 280, toolbar: { show: false } },
        colors: ['#ffc107','#28a745','#dc3545','#17a2b8','#6c757d'],
        legend: { position: 'bottom', fontSize: '12px' },
        plotOptions: {
            pie: {
                donut: {
                    size: '60%',
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
        tooltip: { y: { formatter: val => val + ' solicitud(es)' } },
        noData: { text: 'Sin datos' }
    }).render();

    // ── Gráfico 3: Inasistencias por Mes (barras) ─────────────────────────────
    new ApexCharts(document.getElementById('chartInasistencias'), {
        series: [{ name: 'Inasistencias', data: inaData }],
        chart: { type: 'bar', height: 280, toolbar: { show: false } },
        colors: ['#f72585'],
        plotOptions: { bar: { columnWidth: '55%', borderRadius: 4 } },
        dataLabels: { enabled: false },
        xaxis: {
            categories: ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'],
            labels: { style: { fontSize: '11px' } }
        },
        yaxis: { min: 0, tickAmount: 4, labels: { formatter: val => Math.floor(val) } },
        tooltip: { y: { formatter: val => val + ' inasistencia(s)' } },
        noData: { text: 'Sin datos' }
    }).render();
});
</script>
<?= $this->endSection() ?>
