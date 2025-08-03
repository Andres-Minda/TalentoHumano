<?php
$sidebar = 'sidebar_super_admin';
?>

<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <!-- Page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">Dashboard - Super Administrador</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="<?= base_url('super-admin/dashboard') ?>">Inicio</a></li>
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row">
        <div class="col-xl-3 col-md-6">
            <div class="card mini-stats-wid">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-grow-1">
                            <p class="text-muted fw-medium">Total Usuarios</p>
                            <h4 class="mb-0"><?= $totalUsuarios ?? 0 ?></h4>
                        </div>
                        <div class="flex-shrink-0 align-self-center">
                            <div class="mini-stat-icon avatar-sm rounded-circle bg-primary align-self-center">
                                <span class="avatar-title">
                                    <i class="ti ti-users font-size-24"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card mini-stats-wid">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-grow-1">
                            <p class="text-muted fw-medium">Total Empleados</p>
                            <h4 class="mb-0"><?= $totalEmpleados ?? 0 ?></h4>
                        </div>
                        <div class="flex-shrink-0 align-self-center">
                            <div class="mini-stat-icon avatar-sm rounded-circle bg-success align-self-center">
                                <span class="avatar-title">
                                    <i class="ti ti-user-check font-size-24"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card mini-stats-wid">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-grow-1">
                            <p class="text-muted fw-medium">Departamentos</p>
                            <h4 class="mb-0"><?= $totalDepartamentos ?? 0 ?></h4>
                        </div>
                        <div class="flex-shrink-0 align-self-center">
                            <div class="mini-stat-icon avatar-sm rounded-circle bg-warning align-self-center">
                                <span class="avatar-title">
                                    <i class="ti ti-building font-size-24"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card mini-stats-wid">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-grow-1">
                            <p class="text-muted fw-medium">Roles Activos</p>
                            <h4 class="mb-0"><?= $totalRoles ?? 0 ?></h4>
                        </div>
                        <div class="flex-shrink-0 align-self-center">
                            <div class="mini-stat-icon avatar-sm rounded-circle bg-info align-self-center">
                                <span class="avatar-title">
                                    <i class="ti ti-shield-check font-size-24"></i>
                                </span>
                            </div>
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
                    <h4 class="card-title">Actividad del Sistema</h4>
                    <p class="card-title-desc">Estadísticas de usuarios y empleados</p>
                </div>
                <div class="card-body">
                    <div id="system-activity-chart" style="height: 300px;"></div>
                </div>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Distribución por Roles</h4>
                </div>
                <div class="card-body">
                    <div id="roles-distribution-chart" style="height: 300px;"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="row">
        <div class="col-xl-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Últimos Usuarios Registrados</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-centered table-nowrap mb-0">
                            <thead>
                                <tr>
                                    <th>Usuario</th>
                                    <th>Rol</th>
                                    <th>Estado</th>
                                    <th>Último Acceso</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (isset($ultimosUsuarios) && !empty($ultimosUsuarios)): ?>
                                    <?php foreach ($ultimosUsuarios as $usuario): ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm bg-light rounded-circle me-3">
                                                    <span class="avatar-title text-primary">
                                                        <i class="ti ti-user"></i>
                                                    </span>
                                                </div>
                                                <div>
                                                    <h6 class="mb-0"><?= esc($usuario['email']) ?></h6>
                                                    <small class="text-muted"><?= esc($usuario['cedula']) ?></small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-primary"><?= esc($usuario['nombre_rol']) ?></span>
                                        </td>
                                        <td>
                                            <?php if ($usuario['activo']): ?>
                                                <span class="badge bg-success">Activo</span>
                                            <?php else: ?>
                                                <span class="badge bg-danger">Inactivo</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?= $usuario['last_login'] ? date('d/m/Y H:i', strtotime($usuario['last_login'])) : 'Nunca' ?>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="4" class="text-center">No hay usuarios registrados</td>
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
                    <h4 class="card-title">Acciones Rápidas</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6 mb-3">
                            <a href="<?= base_url('super-admin/usuarios') ?>" class="btn btn-primary w-100">
                                <i class="ti ti-users me-2"></i>Gestionar Usuarios
                            </a>
                        </div>
                        <div class="col-6 mb-3">
                            <a href="<?= base_url('super-admin/roles') ?>" class="btn btn-success w-100">
                                <i class="ti ti-shield me-2"></i>Gestionar Roles
                            </a>
                        </div>
                        <div class="col-6 mb-3">
                            <a href="<?= base_url('super-admin/departamentos') ?>" class="btn btn-warning w-100">
                                <i class="ti ti-building me-2"></i>Gestionar Departamentos
                            </a>
                        </div>
                        <div class="col-6 mb-3">
                            <a href="<?= base_url('super-admin/configuracion') ?>" class="btn btn-info w-100">
                                <i class="ti ti-settings me-2"></i>Configuración
                            </a>
                        </div>
                        <div class="col-6 mb-3">
                            <a href="<?= base_url('super-admin/backup') ?>" class="btn btn-secondary w-100">
                                <i class="ti ti-database me-2"></i>Backup
                            </a>
                        </div>
                        <div class="col-6 mb-3">
                            <a href="<?= base_url('super-admin/reportes') ?>" class="btn btn-dark w-100">
                                <i class="ti ti-chart-bar me-2"></i>Reportes
                            </a>
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
document.addEventListener('DOMContentLoaded', function() {
    // System Activity Chart
    var systemActivityOptions = {
        series: [{
            name: 'Usuarios',
            data: [<?= isset($chartData['usuarios']) ? implode(',', $chartData['usuarios']) : '0,0,0,0,0,0,0' ?>]
        }, {
            name: 'Empleados',
            data: [<?= isset($chartData['empleados']) ? implode(',', $chartData['empleados']) : '0,0,0,0,0,0,0' ?>]
        }],
        chart: {
            height: 300,
            type: 'area',
            toolbar: {
                show: false
            }
        },
        dataLabels: {
            enabled: false
        },
        stroke: {
            curve: 'smooth',
            width: 2
        },
        colors: ['#5D87FF', '#13DEB9'],
        fill: {
            type: 'gradient',
            gradient: {
                opacityFrom: 0.6,
                opacityTo: 0.1,
            }
        },
        xaxis: {
            categories: ['Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb', 'Dom']
        },
        tooltip: {
            theme: 'dark'
        }
    };

    var systemActivityChart = new ApexCharts(document.querySelector("#system-activity-chart"), systemActivityOptions);
    systemActivityChart.render();

    // Roles Distribution Chart
    var rolesDistributionOptions = {
        series: [<?= isset($chartData['roles']) ? implode(',', $chartData['roles']) : '0,0,0' ?>],
        chart: {
            height: 300,
            type: 'donut',
        },
        labels: ['Super Admin', 'Admin TH', 'Docente'],
        colors: ['#5D87FF', '#13DEB9', '#FFAE1F'],
        plotOptions: {
            pie: {
                donut: {
                    size: '70%'
                }
            }
        },
        legend: {
            position: 'bottom'
        }
    };

    var rolesDistributionChart = new ApexCharts(document.querySelector("#roles-distribution-chart"), rolesDistributionOptions);
    rolesDistributionChart.render();
});
</script>
<?= $this->endSection() ?> 