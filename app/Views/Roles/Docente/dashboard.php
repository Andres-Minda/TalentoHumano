<?php $sidebar = 'sidebar_docente'; ?>
<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>

<div class="page-wrapper">
    <div class="page-content">
        <!-- Header -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <h4 class="mb-0"><i class="bi bi-speedometer2"></i> Dashboard - Docente</h4>
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
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h4 class="mb-1">¡Bienvenido, <?= $user['nombres'] . ' ' . $user['apellidos'] ?>!</h4>
                                <p class="text-muted mb-0">Panel de control para docentes</p>
                            </div>
                            <div class="text-primary">
                                <i class="bi bi-person-badge fs-1"></i>
                            </div>
                        </div>
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
                                <h4 class="mb-0"><?= $estadisticas['total_capacitaciones'] ?? 0 ?></h4>
                                <p class="text-muted mb-0">Mis Capacitaciones</p>
                            </div>
                            <div class="text-primary">
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
                                <i class="bi bi-award-fill fs-1"></i>
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
                        <h5 class="card-title mb-0">Acciones Rápidas</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <a href="<?= base_url('docente/capacitaciones') ?>" class="btn btn-primary w-100 mb-3">
                                    <i class="bi bi-mortarboard me-2"></i>Mis Capacitaciones
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a href="<?= base_url('docente/documentos') ?>" class="btn btn-success w-100 mb-3">
                                    <i class="bi bi-file-earmark-text me-2"></i>Documentos
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a href="<?= base_url('docente/certificados') ?>" class="btn btn-warning w-100 mb-3">
                                    <i class="bi bi-award me-2"></i>Certificados
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a href="<?= base_url('docente/nueva-solicitud') ?>" class="btn btn-info w-100 mb-3">
                                    <i class="bi bi-plus-circle me-2"></i>Nueva Solicitud
                                </a>
                            </div>
                        </div>
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
                                        <th>Fecha</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach (array_slice($capacitaciones ?? [], 0, 5) as $capacitacion): ?>
                                    <tr>
                                        <td><?= $capacitacion['nombre'] ?></td>
                                        <td><span class="badge bg-info"><?= $capacitacion['tipo'] ?></span></td>
                                        <td><span class="badge bg-warning"><?= $capacitacion['estado'] ?></span></td>
                                        <td><?= date('d/m/Y', strtotime($capacitacion['fecha_inicio'])) ?></td>
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
                        <h5 class="card-title mb-0">Solicitudes Recientes</h5>
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
                                    <?php foreach (array_slice($solicitudes ?? [], 0, 5) as $solicitud): ?>
                                    <tr>
                                        <td><span class="badge bg-secondary"><?= $solicitud['tipo_solicitud'] ?></span></td>
                                        <td><?= $solicitud['titulo'] ?></td>
                                        <td><span class="badge bg-warning"><?= $solicitud['estado'] ?></span></td>
                                        <td><?= date('d/m/Y', strtotime($solicitud['fecha_solicitud'])) ?></td>
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