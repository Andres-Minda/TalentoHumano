<?php $sidebar = 'sidebar_docente'; ?>
<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>

<div class="page-wrapper">
    <div class="page-content">
        <!-- Header -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <h4 class="mb-0"><i class="bi bi-clipboard-check"></i> Mis Evaluaciones</h4>
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
                                    <h5 class="font-weight-semibold mb-0"><?= $estadisticas['total_evaluaciones'] ?? 0 ?></h5>
                                </div>
                                <div class="stat-digit d-flex">
                                    <h5 class="font-weight-semibold mb-0">Evaluaciones</h5>
                                </div>
                            </div>
                            <div class="ms-auto">
                                <i class="bi bi-clipboard-check text-primary" style="font-size: 2rem;"></i>
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
                                    <h5 class="font-weight-semibold mb-0"><?= $estadisticas['promedio_puntaje'] ?? 0 ?></h5>
                                </div>
                                <div class="stat-digit d-flex">
                                    <h5 class="font-weight-semibold mb-0">Promedio</h5>
                                </div>
                            </div>
                            <div class="ms-auto">
                                <i class="bi bi-graph-up text-success" style="font-size: 2rem;"></i>
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
                                    <h5 class="font-weight-semibold mb-0"><?= $estadisticas['ultima_evaluacion'] ?? 'N/A' ?></h5>
                                </div>
                                <div class="stat-digit d-flex">
                                    <h5 class="font-weight-semibold mb-0">Última</h5>
                                </div>
                            </div>
                            <div class="ms-auto">
                                <i class="bi bi-calendar-event text-info" style="font-size: 2rem;"></i>
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
                                    <h5 class="font-weight-semibold mb-0"><?= $estadisticas['competencias_evaluadas'] ?? 0 ?></h5>
                                </div>
                                <div class="stat-digit d-flex">
                                    <h5 class="font-weight-semibold mb-0">Competencias</h5>
                                </div>
                            </div>
                            <div class="ms-auto">
                                <i class="bi bi-star text-warning" style="font-size: 2rem;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Evaluaciones Table -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Historial de Evaluaciones</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover" id="tablaEvaluaciones">
                                <thead>
                                    <tr>
                                        <th>Evaluación</th>
                                        <th>Evaluador</th>
                                        <th>Fecha</th>
                                        <th>Puntaje</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($evaluaciones as $evaluacion): ?>
                                    <tr>
                                        <td><?= $evaluacion['nombre_evaluacion'] ?></td>
                                        <td><?= $evaluacion['evaluador_nombre'] ?></td>
                                        <td><?= date('d/m/Y', strtotime($evaluacion['fecha_evaluacion'])) ?></td>
                                        <td>
                                            <span class="badge bg-<?= $evaluacion['puntaje_total'] >= 80 ? 'success' : ($evaluacion['puntaje_total'] >= 60 ? 'warning' : 'danger') ?>">
                                                <?= $evaluacion['puntaje_total'] ?>%
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-<?= $evaluacion['puntaje_total'] >= 80 ? 'success' : ($evaluacion['puntaje_total'] >= 60 ? 'warning' : 'danger') ?>">
                                                <?= $evaluacion['puntaje_total'] >= 80 ? 'Excelente' : ($evaluacion['puntaje_total'] >= 60 ? 'Bueno' : 'Necesita Mejora') ?>
                                            </span>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-outline-primary" onclick="verDetallesEvaluacion(<?= $evaluacion['id_evaluacion_empleado'] ?>)">
                                                <i class="bi bi-eye"></i> Ver
                                            </button>
                                        </td>
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

<!-- Modal Detalles Evaluación -->
<div class="modal fade" id="modalDetallesEvaluacion" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detalles de Evaluación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="detallesEvaluacionContent">
                <!-- Contenido dinámico -->
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#tablaEvaluaciones').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json'
        }
    });
});

function verDetallesEvaluacion(idEvaluacion) {
    // Aquí se cargarían los detalles de la evaluación
    $('#modalDetallesEvaluacion').modal('show');
}
</script>

<?= $this->endSection() ?> 