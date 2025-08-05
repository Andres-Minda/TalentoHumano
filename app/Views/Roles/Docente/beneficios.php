<?php $sidebar = 'sidebar_docente'; ?>
<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>

<div class="page-wrapper">
    <div class="page-content">
        <!-- Header -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <h4 class="mb-0"><i class="bi bi-gift"></i> Mis Beneficios</h4>
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
                                    <h5 class="font-weight-semibold mb-0"><?= $estadisticas['total_beneficios'] ?? 0 ?></h5>
                                </div>
                                <div class="stat-digit d-flex">
                                    <h5 class="font-weight-semibold mb-0">Beneficios</h5>
                                </div>
                            </div>
                            <div class="ms-auto">
                                <i class="bi bi-gift text-primary" style="font-size: 2rem;"></i>
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
                                    <h5 class="font-weight-semibold mb-0"><?= $estadisticas['beneficios_activos'] ?? 0 ?></h5>
                                </div>
                                <div class="stat-digit d-flex">
                                    <h5 class="font-weight-semibold mb-0">Activos</h5>
                                </div>
                            </div>
                            <div class="ms-auto">
                                <i class="bi bi-check-circle text-success" style="font-size: 2rem;"></i>
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
                                    <h5 class="font-weight-semibold mb-0"><?= $estadisticas['beneficios_vencidos'] ?? 0 ?></h5>
                                </div>
                                <div class="stat-digit d-flex">
                                    <h5 class="font-weight-semibold mb-0">Vencidos</h5>
                                </div>
                            </div>
                            <div class="ms-auto">
                                <i class="bi bi-exclamation-triangle text-warning" style="font-size: 2rem;"></i>
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
                                    <h5 class="font-weight-semibold mb-0"><?= $estadisticas['tipos_beneficios'] ?? 0 ?></h5>
                                </div>
                                <div class="stat-digit d-flex">
                                    <h5 class="font-weight-semibold mb-0">Tipos</h5>
                                </div>
                            </div>
                            <div class="ms-auto">
                                <i class="bi bi-tags text-info" style="font-size: 2rem;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Beneficios Grid -->
        <div class="row">
            <?php foreach ($beneficios as $beneficio): ?>
            <div class="col-xl-4 col-lg-6 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="me-3">
                                <i class="bi bi-gift-fill text-primary" style="font-size: 1.5rem;"></i>
                            </div>
                            <div>
                                <h6 class="mb-1"><?= $beneficio['nombre_beneficio'] ?></h6>
                                <p class="text-muted mb-0"><?= $beneficio['descripcion_beneficio'] ?></p>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-1">
                                <span>Tipo</span>
                                <span class="badge bg-info"><?= $beneficio['tipo_beneficio'] ?></span>
                            </div>
                            <div class="d-flex justify-content-between mb-1">
                                <span>Estado</span>
                                <span class="badge bg-<?= $beneficio['estado'] == 'Activo' ? 'success' : 'danger' ?>">
                                    <?= $beneficio['estado'] ?>
                                </span>
                            </div>
                            <div class="d-flex justify-content-between mb-1">
                                <span>Asignado</span>
                                <span><?= date('d/m/Y', strtotime($beneficio['fecha_asignacion'])) ?></span>
                            </div>
                            <?php if ($beneficio['fecha_vencimiento']): ?>
                            <div class="d-flex justify-content-between mb-1">
                                <span>Vence</span>
                                <span><?= date('d/m/Y', strtotime($beneficio['fecha_vencimiento'])) ?></span>
                            </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">
                                <?= $beneficio['observaciones'] ?? 'Sin observaciones' ?>
                            </small>
                            <button type="button" class="btn btn-sm btn-outline-primary" onclick="verDetallesBeneficio(<?= $beneficio['id_empleado_beneficio'] ?>)">
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

<!-- Modal Detalles Beneficio -->
<div class="modal fade" id="modalDetallesBeneficio" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detalles de Beneficio</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="detallesBeneficioContent">
                <!-- Contenido dinámico -->
            </div>
        </div>
    </div>
</div>

<script>
function verDetallesBeneficio(idBeneficio) {
    // Aquí se cargarían los detalles del beneficio
    $('#modalDetallesBeneficio').modal('show');
}
</script>

<?= $this->endSection() ?> 