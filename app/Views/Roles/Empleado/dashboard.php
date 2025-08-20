<?php 
// Detectar automáticamente el tipo de empleado para mostrar el sidebar correcto
$tipoEmpleado = $empleado['tipo_empleado'] ?? 'DOCENTE';
$sidebar = 'sidebar_empleado'; // Forzar sidebar de empleado

// Usar la información del controlador si está disponible
$tituloDashboard = $titulo_dashboard ?? 'Dashboard - Empleado';
$descripcionDashboard = $descripcion_dashboard ?? 'Panel de control para empleados';

// Si no hay información del controlador, determinar por tipo de empleado
if (!isset($titulo_dashboard)) {
    switch ($tipoEmpleado) {
        case 'DOCENTE':
            $tituloDashboard = 'Dashboard - Docente';
            $descripcionDashboard = 'Panel de control para docentes';
            break;
        case 'ADMINISTRATIVO':
            $tituloDashboard = 'Dashboard - Administrativo';
            $descripcionDashboard = 'Panel de control para personal administrativo';
            break;
        case 'DIRECTIVO':
            $tituloDashboard = 'Dashboard - Directivo';
            $descripcionDashboard = 'Panel de control para directivos';
            break;
        case 'AUXILIAR':
            $tituloDashboard = 'Dashboard - Auxiliar';
            $descripcionDashboard = 'Panel de control para auxiliares';
            break;
    }
}
?>

<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>

<div class="page-wrapper">
    <div class="page-content">
        <!-- Header -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <h4 class="mb-0"><i class="bi bi-speedometer2"></i> <?= $tituloDashboard ?></h4>
                    <div class="page-title-right">
                        <span class="text-muted">Última actualización: <?= date('d/m/Y H:i') ?></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Welcome Message -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center">
                        <h2 class="text-primary mb-3">¡Bienvenido, <?= $user['nombres'] ?> <?= $user['apellidos'] ?>!</h2>
                        <p class="lead mb-2"><?= $descripcionDashboard ?></p>
                        <?php if ($empleado && isset($empleado['tipo_empleado'])): ?>
                            <p class="text-muted">
                                Tipo: <?= $empleado['tipo_empleado'] ?> 
                                <?php if (isset($empleado['tipo_docente']) && $empleado['tipo_docente']): ?>
                                    - <?= $empleado['tipo_docente'] ?>
                                <?php endif; ?>
                                | Departamento: <?= $empleado['departamento'] ?? 'No asignado' ?>
                            </p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="row">
            <div class="col-xl-3 col-lg-6 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h4 class="mb-0"><?= $estadisticas['total_capacitaciones'] ?? 0 ?></h4>
                                <p class="text-muted mb-0">Mis Capacitaciones</p>
                            </div>
                            <div class="text-primary">
                                <i class="bi bi-mortarboard fs-1"></i>
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
                                <h4 class="mb-0"><?= $estadisticas['total_documentos'] ?? 0 ?></h4>
                                <p class="text-muted mb-0">Documentos</p>
                            </div>
                            <div class="text-success">
                                <i class="bi bi-file-earmark-text fs-1"></i>
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
                                <h4 class="mb-0"><?= $estadisticas['total_certificados'] ?? 0 ?></h4>
                                <p class="text-muted mb-0">Certificados</p>
                            </div>
                            <div class="text-warning">
                                <i class="bi bi-award fs-1"></i>
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
                                <h4 class="mb-0"><?= $estadisticas['total_solicitudes'] ?? 0 ?></h4>
                                <p class="text-muted mb-0">Solicitudes</p>
                            </div>
                            <div class="text-info">
                                <i class="bi bi-clipboard-data fs-1"></i>
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
                        <h5 class="card-title mb-0">
                            <i class="bi bi-lightning text-warning"></i> Acciones Rápidas
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3 col-sm-6 mb-3">
                                <a href="<?= base_url('empleado/capacitaciones') ?>" class="btn btn-primary w-100">
                                    <i class="bi bi-mortarboard me-2"></i>Mis Capacitaciones
                                </a>
                            </div>
                            <div class="col-md-3 col-sm-6 mb-3">
                                <a href="<?= base_url('empleado/documentos') ?>" class="btn btn-success w-100">
                                    <i class="bi bi-file-earmark-text me-2"></i>Documentos
                                </a>
                            </div>
                            <div class="col-md-3 col-sm-6 mb-3">
                                <a href="<?= base_url('empleado/certificados') ?>" class="btn btn-warning w-100">
                                    <i class="bi bi-award me-2"></i>Certificados
                                </a>
                            </div>
                            <div class="col-md-3 col-sm-6 mb-3">
                                <a href="<?= base_url('empleado/nueva-solicitud') ?>" class="btn btn-info w-100">
                                    <i class="bi bi-plus-circle me-2"></i>Nueva Solicitud
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Content Row -->
        <div class="row">
            <!-- Latest Trainings -->
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-mortarboard text-primary"></i> Últimas Capacitaciones
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Capacitación</th>
                                        <th>Tipo</th>
                                        <th>Estado</th>
                                        <th>Fecha</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td colspan="4" class="text-center text-muted">
                                            No hay capacitaciones recientes
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Requests -->
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-clipboard-data text-success"></i> Solicitudes Recientes
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Tipo</th>
                                        <th>Título</th>
                                        <th>Estado</th>
                                        <th>Fecha</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td colspan="4" class="text-center text-muted">
                                            No hay solicitudes recientes
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Academic Period Info -->
        <?php if (isset($periodo_activo) && $periodo_activo): ?>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center">
                        <h6 class="text-muted mb-2">Periodo Académico Activo</h6>
                        <h5 class="text-primary mb-0">
                            <?= $periodo_activo['nombre'] ?? 'Periodo 2025-1' ?> | 
                            Del <?= date('d/m/Y', strtotime($periodo_activo['fecha_inicio'] ?? '2025-01-15')) ?> 
                            al <?= date('d/m/Y', strtotime($periodo_activo['fecha_fin'] ?? '2025-06-30')) ?>
                        </h5>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<?= $this->endSection() ?>
