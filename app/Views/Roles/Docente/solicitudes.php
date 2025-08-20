<?php $sidebar = 'sidebar_docente'; ?>
<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>

<div class="page-wrapper">
    <div class="page-content">
        <!-- Header -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <h4 class="mb-0"><i class="bi bi-envelope"></i> Mis Solicitudes</h4>
                    <div class="page-title-right">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalNuevaSolicitud">
                            <i class="bi bi-plus"></i> Nueva Solicitud
                        </button>
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
                            <div class="me-3">
                                <span class="round-8 bg-primary rounded-circle me-2 d-inline-block"></span>
                                <div class="stat-digit d-flex">
                                    <h5 class="font-weight-semibold mb-0"><?= $estadisticas['total_solicitudes'] ?? 0 ?></h5>
                                </div>
                                <div class="stat-digit d-flex">
                                    <h5 class="font-weight-semibold mb-0">Solicitudes</h5>
                                </div>
                            </div>
                            <div class="ms-auto">
                                <i class="bi bi-envelope text-primary" style="font-size: 2rem;"></i>
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
                                    <h5 class="font-weight-semibold mb-0"><?= $estadisticas['solicitudes_aprobadas'] ?? 0 ?></h5>
                                </div>
                                <div class="stat-digit d-flex">
                                    <h5 class="font-weight-semibold mb-0">Aprobadas</h5>
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
                                    <h5 class="font-weight-semibold mb-0"><?= $estadisticas['solicitudes_pendientes'] ?? 0 ?></h5>
                                </div>
                                <div class="stat-digit d-flex">
                                    <h5 class="font-weight-semibold mb-0">Pendientes</h5>
                                </div>
                            </div>
                            <div class="ms-auto">
                                <i class="bi bi-clock text-warning" style="font-size: 2rem;"></i>
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
                                <span class="round-8 bg-danger rounded-circle me-2 d-inline-block"></span>
                                <div class="stat-digit d-flex">
                                    <h5 class="font-weight-semibold mb-0"><?= $estadisticas['solicitudes_rechazadas'] ?? 0 ?></h5>
                                </div>
                                <div class="stat-digit d-flex">
                                    <h5 class="font-weight-semibold mb-0">Rechazadas</h5>
                                </div>
                            </div>
                            <div class="ms-auto">
                                <i class="bi bi-x-circle text-danger" style="font-size: 2rem;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Solicitudes Table -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Historial de Solicitudes</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover" id="tablaSolicitudes">
                                <thead>
                                    <tr>
                                        <th>Tipo</th>
                                        <th>Título</th>
                                        <th>Fecha Solicitud</th>
                                        <th>Fecha Resolución</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($solicitudes as $solicitud): ?>
                                    <tr>
                                        <td><?= $solicitud['tipo_solicitud'] ?></td>
                                        <td><?= $solicitud['titulo'] ?></td>
                                        <td><?= date('d/m/Y', strtotime($solicitud['fecha_solicitud'])) ?></td>
                                        <td><?= $solicitud['fecha_resolucion'] ? date('d/m/Y', strtotime($solicitud['fecha_resolucion'])) : 'Pendiente' ?></td>
                                        <td>
                                            <span class="badge bg-<?= $solicitud['estado'] == 'Aprobada' ? 'success' : ($solicitud['estado'] == 'Pendiente' ? 'warning' : 'danger') ?>">
                                                <?= $solicitud['estado'] ?>
                                            </span>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-outline-primary" onclick="verDetallesSolicitud(<?= $solicitud['id_solicitud'] ?>)">
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

<!-- Modal Nueva Solicitud -->
<div class="modal fade" id="modalNuevaSolicitud" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Nueva Solicitud</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formNuevaSolicitud">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Tipo de Solicitud *</label>
                        <select class="form-select" name="tipo_solicitud" required>
                            <option value="">Seleccionar</option>
                            <option value="Permiso">Permiso</option>
                            <option value="Capacitación">Capacitación</option>
    
                            <option value="Cambio de horario">Cambio de horario</option>
                            <option value="Otro">Otro</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Título *</label>
                        <input type="text" class="form-control" name="titulo" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Descripción *</label>
                        <textarea class="form-control" name="descripcion" rows="4" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Enviar Solicitud</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#tablaSolicitudes').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json'
        }
    });
    
    $('#formNuevaSolicitud').on('submit', function(e) {
        e.preventDefault();
        // Aquí se enviaría la solicitud
        alert('Solicitud enviada correctamente');
        $('#modalNuevaSolicitud').modal('hide');
    });
});

function verDetallesSolicitud(idSolicitud) {
    // Aquí se cargarían los detalles de la solicitud
    alert('Detalles de la solicitud ' + idSolicitud);
}
</script>

<?= $this->endSection() ?> 