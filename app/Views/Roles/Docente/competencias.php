<?php $sidebar = 'sidebar_docente'; ?>
<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>

<div class="page-wrapper">
    <div class="page-content">
        <!-- Header -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <h4 class="mb-0"><i class="bi bi-star"></i> Mis Competencias</h4>
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
                                    <h5 class="font-weight-semibold mb-0"><?= $estadisticas['total_competencias'] ?? 0 ?></h5>
                                </div>
                                <div class="stat-digit d-flex">
                                    <h5 class="font-weight-semibold mb-0">Competencias</h5>
                                </div>
                            </div>
                            <div class="ms-auto">
                                <i class="bi bi-star text-primary" style="font-size: 2rem;"></i>
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
                                    <h5 class="font-weight-semibold mb-0"><?= $estadisticas['nivel_experto'] ?? 0 ?></h5>
                                </div>
                                <div class="stat-digit d-flex">
                                    <h5 class="font-weight-semibold mb-0">Experto</h5>
                                </div>
                            </div>
                            <div class="ms-auto">
                                <i class="bi bi-award text-success" style="font-size: 2rem;"></i>
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
                                    <h5 class="font-weight-semibold mb-0"><?= $estadisticas['nivel_avanzado'] ?? 0 ?></h5>
                                </div>
                                <div class="stat-digit d-flex">
                                    <h5 class="font-weight-semibold mb-0">Avanzado</h5>
                                </div>
                            </div>
                            <div class="ms-auto">
                                <i class="bi bi-graph-up text-warning" style="font-size: 2rem;"></i>
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
                                    <h5 class="font-weight-semibold mb-0"><?= $estadisticas['promedio_nivel'] ?? 0 ?></h5>
                                </div>
                                <div class="stat-digit d-flex">
                                    <h5 class="font-weight-semibold mb-0">Promedio</h5>
                                </div>
                            </div>
                            <div class="ms-auto">
                                <i class="bi bi-bar-chart text-info" style="font-size: 2rem;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Competencias Grid -->
        <div class="row">
            <?php foreach ($competencias as $competencia): ?>
            <div class="col-xl-4 col-lg-6 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="me-3">
                                <i class="bi bi-star-fill text-warning" style="font-size: 1.5rem;"></i>
                            </div>
                            <div>
                                <h6 class="mb-1"><?= $competencia['nombre_competencia'] ?></h6>
                                <p class="text-muted mb-0"><?= $competencia['descripcion_competencia'] ?></p>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-1">
                                <span>Nivel Actual</span>
                                <span class="badge bg-<?= $competencia['nivel_actual'] == 'Experto' ? 'success' : ($competencia['nivel_actual'] == 'Avanzado' ? 'warning' : ($competencia['nivel_actual'] == 'Intermedio' ? 'info' : 'secondary')) ?>">
                                    <?= $competencia['nivel_actual'] ?>
                                </span>
                            </div>
                            <div class="progress" style="height: 8px;">
                                <?php 
                                $progress = 0;
                                switch($competencia['nivel_actual']) {
                                    case 'Básico': $progress = 25; break;
                                    case 'Intermedio': $progress = 50; break;
                                    case 'Avanzado': $progress = 75; break;
                                    case 'Experto': $progress = 100; break;
                                }
                                ?>
                                <div class="progress-bar bg-<?= $competencia['nivel_actual'] == 'Experto' ? 'success' : ($competencia['nivel_actual'] == 'Avanzado' ? 'warning' : ($competencia['nivel_actual'] == 'Intermedio' ? 'info' : 'secondary')) ?>" 
                                     style="width: <?= $progress ?>%"></div>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">
                                Evaluado: <?= date('d/m/Y', strtotime($competencia['fecha_evaluacion'])) ?>
                            </small>
                            <button type="button" class="btn btn-sm btn-outline-primary" onclick="verDetallesCompetencia(<?= $competencia['id_empleado_competencia'] ?>)">
                                <i class="bi bi-eye"></i> Ver
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<!-- Modal Detalles Competencia -->
<div class="modal fade" id="modalDetallesCompetencia" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detalles de Competencia</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="detallesCompetenciaContent">
                <!-- Contenido dinámico -->
            </div>
        </div>
    </div>
</div>

<script>
function verDetallesCompetencia(idCompetencia) {
    // Aquí se cargarían los detalles de la competencia
    $('#modalDetallesCompetencia').modal('show');
}
</script>

<?= $this->endSection() ?> 