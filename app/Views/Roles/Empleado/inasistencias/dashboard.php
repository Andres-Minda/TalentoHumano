<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>

<div class="page-wrapper">
    <div class="page-content">
        <!-- Breadcrumb -->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Inasistencias</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="<?= base_url('empleado/dashboard') ?>">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Inasistencias</li>
                    </ol>
                </nav>
            </div>
        </div>

        <!-- Contenido Principal -->
        <div class="row">
            <!-- Estadísticas Principales -->
            <div class="col-12">
                <div class="row">
                    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                        <div class="card radius-10">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="">
                                        <p class="mb-1 text-secondary">Total Inasistencias</p>
                                        <h4 class="mb-0 text-primary"><?= $estadisticas['total'] ?? 0 ?></h4>
                                    </div>
                                    <div class="ms-auto fs-1 text-primary">
                                        <i class="bi bi-calendar-x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                        <div class="card radius-10">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="">
                                        <p class="mb-1 text-secondary">Justificadas</p>
                                        <h4 class="mb-0 text-success"><?= $estadisticas['justificadas'] ?? 0 ?></h4>
                                    </div>
                                    <div class="ms-auto fs-1 text-success">
                                        <i class="bi bi-check-circle"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                        <div class="card radius-10">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="">
                                        <p class="mb-1 text-secondary">No Justificadas</p>
                                        <h4 class="mb-0 text-danger"><?= $estadisticas['no_justificadas'] ?? 0 ?></h4>
                                    </div>
                                    <div class="ms-auto fs-1 text-danger">
                                        <i class="bi bi-x-circle"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                        <div class="card radius-10">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="">
                                        <p class="mb-1 text-secondary">Pendientes</p>
                                        <h4 class="mb-0 text-warning"><?= $estadisticas['pendientes'] ?? 0 ?></h4>
                                    </div>
                                    <div class="ms-auto fs-1 text-warning">
                                        <i class="bi bi-clock"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Verificación de Límites -->
            <?php if (isset($verificacion_limites) && $verificacion_limites['estado'] !== 'DENTRO_LIMITE'): ?>
            <div class="col-12">
                <div class="alert alert-<?= $verificacion_limites['estado'] === 'EXCEDE_LIMITE_MENSUAL' ? 'danger' : 'warning' ?> alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    <strong>Alerta:</strong> <?= $verificacion_limites['alerta'] ?? 'Ha excedido los límites de inasistencias' ?>
                    <?php if (isset($verificacion_limites['requiere_accion_disciplinaria']) && $verificacion_limites['requiere_accion_disciplinaria']): ?>
                        <br><small class="text-muted">Se requiere acción disciplinaria según la política.</small>
                    <?php endif; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
            <?php endif; ?>

            <!-- Inasistencias Recientes -->
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <div>
                                <h6 class="mb-0">Inasistencias Recientes</h6>
                                <p class="mb-0 text-secondary">Últimas 5 inasistencias registradas</p>
                            </div>
                            <div class="ms-auto">
                                <a href="<?= base_url('empleado/inasistencias/mis-inasistencias') ?>" class="btn btn-primary btn-sm">
                                    <i class="bi bi-list me-1"></i>Ver Todas
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($inasistencias_recientes)): ?>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Fecha</th>
                                        <th>Tipo</th>
                                        <th>Motivo</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($inasistencias_recientes as $inasistencia): ?>
                                    <tr>
                                        <td>
                                            <span class="badge bg-light text-dark">
                                                <?= date('d/m/Y', strtotime($inasistencia['fecha_inasistencia'])) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php
                                            $badgeClass = 'bg-secondary';
                                            switch ($inasistencia['tipo_inasistencia']) {
                                                case 'JUSTIFICADA':
                                                    $badgeClass = 'bg-success';
                                                    break;
                                                case 'NO_JUSTIFICADA':
                                                    $badgeClass = 'bg-danger';
                                                    break;
                                                case 'PENDIENTE_JUSTIFICACION':
                                                    $badgeClass = 'bg-warning';
                                                    break;
                                            }
                                            ?>
                                            <span class="badge <?= $badgeClass ?>">
                                                <?= $inasistencia['tipo_inasistencia'] ?>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="text-truncate d-inline-block" style="max-width: 200px;" title="<?= $inasistencia['motivo_inasistencia'] ?>">
                                                <?= $inasistencia['motivo_inasistencia'] ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php if ($inasistencia['tipo_inasistencia'] === 'PENDIENTE_JUSTIFICACION'): ?>
                                                <span class="badge bg-warning">Pendiente de Revisión</span>
                                            <?php elseif ($inasistencia['tipo_inasistencia'] === 'JUSTIFICADA'): ?>
                                                <span class="badge bg-success">Aprobada</span>
                                            <?php else: ?>
                                                <span class="badge bg-danger">No Justificada</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="<?= base_url('empleado/inasistencias/ver/' . $inasistencia['id_inasistencia']) ?>" 
                                                   class="btn btn-outline-primary btn-sm" 
                                                   title="Ver Detalle">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <?php if ($inasistencia['tipo_inasistencia'] === 'NO_JUSTIFICADA'): ?>
                                                <a href="<?= base_url('empleado/inasistencias/justificar') ?>" 
                                                   class="btn btn-outline-warning btn-sm" 
                                                   title="Subir Justificación">
                                                    <i class="bi bi-upload"></i>
                                                </a>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <?php else: ?>
                        <div class="text-center py-4">
                            <i class="bi bi-calendar-check fs-1 text-muted"></i>
                            <p class="text-muted mt-2">No tienes inasistencias registradas</p>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Acciones Rápidas -->
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0">Acciones Rápidas</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <a href="<?= base_url('empleado/inasistencias/mis-inasistencias') ?>" class="btn btn-outline-primary w-100 mb-2">
                                    <i class="bi bi-list-ul me-2"></i>
                                    Ver Mis Inasistencias
                                </a>
                            </div>
                            <div class="col-md-4">
                                <a href="<?= base_url('empleado/inasistencias/reporte') ?>" class="btn btn-outline-info w-100 mb-2">
                                    <i class="bi bi-file-earmark-text me-2"></i>
                                    Generar Reporte
                                </a>
                            </div>
                            <div class="col-md-4">
                                <a href="<?= base_url('empleado/inasistencias/justificar') ?>" class="btn btn-outline-warning w-100 mb-2">
                                    <i class="bi bi-upload me-2"></i>
                                    Subir Justificación
                                </a>
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
$(document).ready(function() {
    // Auto-hide alerts after 5 seconds
    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 5000);

    // Tooltip initialization
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>
<?= $this->endSection() ?>
