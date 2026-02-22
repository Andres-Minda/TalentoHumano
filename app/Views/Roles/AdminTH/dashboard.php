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
                                <h4 class="mb-0 text-white"><?= $totalEmpleados ?></h4>
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
                                <h4 class="mb-0 text-white"><?= $empleadosActivos ?></h4>
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
                                <h4 class="mb-0 text-white"><?= $inasistenciasPendientes ?></h4>
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
                                <h4 class="mb-0 text-white"><?= $capacitacionesActivas ?></h4>
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
                            <div class="col-md-3 mb-3">
                                <a href="<?= site_url('admin-th/postulantes') ?>" class="btn btn-outline-secondary w-100">
                                    <i class="ti ti-person-lines-fill me-2"></i>
                                    Gestionar Postulantes
                                </a>
                            </div>
                            <div class="col-md-3 mb-3">
                                <a href="<?= site_url('admin-th/departamentos') ?>" class="btn btn-outline-dark w-100">
                                    <i class="ti ti-building me-2"></i>
                                    Departamentos
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
                                <tbody>
                                    <?php if (!empty($ultimasInasistencias)): ?>
                                        <?php foreach ($ultimasInasistencias as $ina): ?>
                                            <tr>
                                                <td><?= esc($ina['nombres']) ?> <?= esc($ina['apellidos']) ?></td>
                                                <td><?= date('d/m/Y', strtotime($ina['fecha_inasistencia'])) ?></td>
                                                <td>
                                                    <?php
                                                        $tipo = $ina['tipo_inasistencia'] ?? ($ina['justificada'] ? 'Justificada' : 'Injustificada');
                                                        $color = match($tipo) {
                                                            'Justificada' => 'success',
                                                            'Injustificada' => 'danger',
                                                            'Permiso' => 'info',
                                                            'Vacaciones' => 'primary',
                                                            'Licencia Médica' => 'warning',
                                                            default => 'secondary'
                                                        };
                                                    ?>
                                                    <span class="badge bg-<?= $color ?>"><?= esc($tipo) ?></span>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="3" class="text-center text-muted">No hay inasistencias registradas</td>
                                        </tr>
                                    <?php endif; ?>
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
                                <tbody>
                                    <?php if (!empty($solicitudesCapacitacion)): ?>
                                        <?php foreach ($solicitudesCapacitacion as $sol): ?>
                                            <tr>
                                                <td><?= esc($sol['nombres']) ?> <?= esc($sol['apellidos']) ?></td>
                                                <td><?= esc($sol['capacitacion']) ?></td>
                                                <td>
                                                    <?php
                                                        $estado = $sol['estado'] ?? 'Pendiente';
                                                        $color = match($estado) {
                                                            'Aprobada', 'Completada' => 'success',
                                                            'Rechazada' => 'danger',
                                                            'Pendiente' => 'warning',
                                                            'En curso' => 'info',
                                                            default => 'secondary'
                                                        };
                                                    ?>
                                                    <span class="badge bg-<?= $color ?>"><?= esc($estado) ?></span>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="3" class="text-center text-muted">No hay solicitudes de capacitación</td>
                                        </tr>
                                    <?php endif; ?>
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
                        <h5 class="card-title mb-0">Inasistencias por Mes (<?= date('Y') ?>)</h5>
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
    // --- Gráfico Empleados por Departamento ---
    const deptLabels = <?= $chartDeptLabels ?>;
    const deptData   = <?= $chartDeptData ?>;

    if (deptLabels.length > 0) {
        new ApexCharts(document.querySelector("#chartEmpleadosDepartamento"), {
            series: deptData,
            chart: { type: 'donut', height: 300 },
            labels: deptLabels,
            colors: ['#007bff','#28a745','#ffc107','#dc3545','#6c757d','#17a2b8','#6610f2','#e83e8c'],
            responsive: [{
                breakpoint: 480,
                options: { chart: { width: 200 }, legend: { position: 'bottom' } }
            }]
        }).render();
    } else {
        document.querySelector("#chartEmpleadosDepartamento").innerHTML =
            '<div class="d-flex align-items-center justify-content-center h-100 text-muted">No hay datos de departamentos</div>';
    }

    // --- Gráfico Inasistencias por Mes ---
    const inasistenciasMes = <?= $chartInasistencias ?>;

    new ApexCharts(document.querySelector("#chartInasistenciasMensuales"), {
        series: [{ name: 'Inasistencias', data: inasistenciasMes }],
        chart: { type: 'bar', height: 300, toolbar: { show: false } },
        plotOptions: { bar: { horizontal: false, columnWidth: '55%', borderRadius: 4 } },
        dataLabels: { enabled: false },
        stroke: { show: true, width: 2, colors: ['transparent'] },
        xaxis: { categories: ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'] },
        yaxis: { title: { text: 'Cantidad' } },
        fill: { opacity: 1 },
        colors: ['#ffc107'],
        tooltip: { y: { formatter: val => val + " inasistencia(s)" } }
    }).render();
});
</script>
<?= $this->endSection() ?>
