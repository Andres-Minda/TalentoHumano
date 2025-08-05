<?php $sidebar = 'sidebar_admin_th'; ?>
<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>

<div class="page-wrapper">
    <div class="page-content">
        <!-- Header -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <h4 class="mb-0"><i class="bi bi-speedometer2"></i> Dashboard - Administrador Talento Humano</h4>
                    <div class="page-title-right">
                        <span class="text-muted">Última actualización: <?= date('d/m/Y H:i') ?></span>
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
                            <div class="flex-grow-1">
                                <h4 class="mb-0"><?= $estadisticas['total_empleados'] ?? 0 ?></h4>
                                <p class="text-muted mb-0">Total Empleados</p>
                            </div>
                            <div class="text-primary">
                                <i class="bi bi-people-fill fs-1"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h4 class="mb-0"><?= $estadisticas['total_departamentos'] ?? 0 ?></h4>
                                <p class="text-muted mb-0">Departamentos</p>
                            </div>
                            <div class="text-success">
                                <i class="bi bi-building fs-1"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h4 class="mb-0"><?= $estadisticas['total_capacitaciones'] ?? 0 ?></h4>
                                <p class="text-muted mb-0">Capacitaciones</p>
                            </div>
                            <div class="text-warning">
                                <i class="bi bi-mortarboard-fill fs-1"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h4 class="mb-0"><?= $estadisticas['total_vacantes'] ?? 0 ?></h4>
                                <p class="text-muted mb-0">Vacantes Activas</p>
                            </div>
                            <div class="text-info">
                                <i class="bi bi-briefcase-fill fs-1"></i>
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
                        <h5 class="card-title mb-0">Empleados por Departamento</h5>
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

        <!-- Recent Activity -->
        <div class="row">
            <div class="col-xl-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Últimas Capacitaciones</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Capacitación</th>
                                        <th>Tipo</th>
                                        <th>Estado</th>
                                        <th>Inscritos</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach (array_slice($capacitaciones ?? [], 0, 5) as $capacitacion): ?>
                                    <tr>
                                        <td><?= $capacitacion['nombre'] ?></td>
                                        <td><span class="badge bg-info"><?= $capacitacion['tipo'] ?></span></td>
                                        <td><span class="badge bg-warning"><?= $capacitacion['estado'] ?></span></td>
                                        <td><?= $capacitacion['total_inscritos'] ?? 0 ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Vacantes Recientes</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Vacante</th>
                                        <th>Departamento</th>
                                        <th>Estado</th>
                                        <th>Candidatos</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach (array_slice($vacantes ?? [], 0, 5) as $vacante): ?>
                                    <tr>
                                        <td><?= $vacante['nombre'] ?? $vacante['puesto_nombre'] ?></td>
                                        <td><?= $vacante['departamento_nombre'] ?></td>
                                        <td><span class="badge bg-success"><?= $vacante['estado'] ?></span></td>
                                        <td><?= $vacante['total_candidatos'] ?? 0 ?></td>
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

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(document).ready(function() {
    // Chart 1: Empleados por Departamento
    var optionsEmpleados = {
        series: [<?= implode(',', array_column($chartData['empleados_por_departamento'] ?? [], 'cantidad')) ?>],
        chart: {
            type: 'donut',
            height: 300
        },
        labels: [<?= implode(',', array_map(function($item) { return '"' . $item['departamento'] . '"'; }, $chartData['empleados_por_departamento'] ?? [])) ?>],
        colors: ['#3B82F6', '#10B981', '#F59E0B', '#EF4444', '#8B5CF6'],
        legend: {
            position: 'bottom'
        }
    };
    
    var chartEmpleados = new ApexCharts(document.querySelector("#chartEmpleadosDepartamento"), optionsEmpleados);
    chartEmpleados.render();
    
    // Chart 2: Estado de Vacantes
    var optionsVacantes = {
        series: [<?= implode(',', array_column($chartData['estado_vacantes'] ?? [], 'cantidad')) ?>],
        chart: {
            type: 'pie',
            height: 300
        },
        labels: [<?= implode(',', array_map(function($item) { return '"' . $item['estado'] . '"'; }, $chartData['estado_vacantes'] ?? [])) ?>],
        colors: ['#10B981', '#F59E0B', '#EF4444'],
        legend: {
            position: 'bottom'
        }
    };
    
    var chartVacantes = new ApexCharts(document.querySelector("#chartVacantes"), optionsVacantes);
    chartVacantes.render();
});
</script>
<?= $this->endSection() ?> 