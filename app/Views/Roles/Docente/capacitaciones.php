<?php $sidebar = 'sidebar_docente'; ?>
<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4><i class="bi bi-mortarboard"></i> Mis Capacitaciones</h4>
                    <a href="<?= base_url('docente/dashboard') ?>" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Volver al Dashboard
                    </a>
                </div>
                <div class="card-body">
                    <!-- Estadísticas -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card bg-primary text-white">
                                <div class="card-body">
                                    <h5 class="card-title">Inscrito</h5>
                                    <h3><?= $estadisticas->inscrito ?? 0 ?></h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-warning text-dark">
                                <div class="card-body">
                                    <h5 class="card-title">En Curso</h5>
                                    <h3><?= $estadisticas->en_curso ?? 0 ?></h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-success text-white">
                                <div class="card-body">
                                    <h5 class="card-title">Completado</h5>
                                    <h3><?= $estadisticas->completado ?? 0 ?></h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-info text-white">
                                <div class="card-body">
                                    <h5 class="card-title">Aprobado</h5>
                                    <h3><?= $estadisticas->aprobado ?? 0 ?></h3>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Capacitaciones Disponibles -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h5><i class="bi bi-calendar-plus"></i> Capacitaciones Disponibles</h5>
                            <div class="table-responsive">
                                <table class="table table-striped table-hover" id="tablaCapacitacionesDisponibles">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>Título</th>
                                            <th>Tipo</th>
                                            <th>Instructor</th>
                                            <th>Fecha Inicio</th>
                                            <th>Fecha Fin</th>
                                            <th>Duración</th>
                                            <th>Cupos</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($capacitacionesDisponibles ?? [] as $capacitacion): ?>
                                        <tr>
                                            <td><?= $capacitacion['nombre'] ?></td>
                                            <td>
                                                <span class="badge bg-<?= $capacitacion['tipo'] == 'Online' ? 'info' : ($capacitacion['tipo'] == 'Presencial' ? 'primary' : 'secondary') ?>">
                                                    <?= $capacitacion['tipo'] ?>
                                                </span>
                                            </td>
                                            <td><?= $capacitacion['instructor'] ?></td>
                                            <td><?= date('d/m/Y', strtotime($capacitacion['fecha_inicio'])) ?></td>
                                            <td><?= date('d/m/Y', strtotime($capacitacion['fecha_fin'])) ?></td>
                                            <td><?= $capacitacion['duracion_horas'] ?> horas</td>
                                            <td>
                                                <span class="badge bg-info">
                                                    <?= $capacitacion['total_inscritos'] ?? 0 ?> / <?= $capacitacion['cupo_maximo'] ?>
                                                </span>
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-sm btn-success" onclick="inscribirseCapacitacion(<?= $capacitacion['id_capacitacion'] ?>)">
                                                    <i class="bi bi-plus-circle"></i> Inscribirse
                                                </button>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Mis Capacitaciones -->
                    <div class="row">
                        <div class="col-12">
                            <h5><i class="bi bi-bookmark-check"></i> Mis Capacitaciones</h5>
                            <div class="table-responsive">
                                <table class="table table-striped table-hover" id="tablaMisCapacitaciones">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>Título</th>
                                            <th>Tipo</th>
                                            <th>Instructor</th>
                                            <th>Fecha Inicio</th>
                                            <th>Fecha Fin</th>
                                            <th>Estado</th>
                                            <th>Calificación</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($misCapacitaciones ?? [] as $capacitacion): ?>
                                        <tr>
                                            <td><?= $capacitacion['nombre'] ?></td>
                                            <td>
                                                <span class="badge bg-<?= $capacitacion['tipo'] == 'Online' ? 'info' : ($capacitacion['tipo'] == 'Presencial' ? 'primary' : 'secondary') ?>">
                                                    <?= $capacitacion['tipo'] ?>
                                                </span>
                                            </td>
                                            <td><?= $capacitacion['instructor'] ?></td>
                                            <td><?= date('d/m/Y', strtotime($capacitacion['fecha_inicio'])) ?></td>
                                            <td><?= date('d/m/Y', strtotime($capacitacion['fecha_fin'])) ?></td>
                                            <td>
                                                <span class="badge bg-<?= $capacitacion['estado'] == 'Inscrito' ? 'primary' : ($capacitacion['estado'] == 'En curso' ? 'warning' : ($capacitacion['estado'] == 'Completado' ? 'success' : ($capacitacion['estado'] == 'Aprobado' ? 'info' : 'danger'))) ?>">
                                                    <?= $capacitacion['estado'] ?>
                                                </span>
                                            </td>
                                            <td>
                                                <?php if ($capacitacion['calificacion']): ?>
                                                    <span class="badge bg-success"><?= $capacitacion['calificacion'] ?>/10</span>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary">Sin calificar</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="verDetallesCapacitacion(<?= $capacitacion['id_capacitacion'] ?>)">
                                                        <i class="bi bi-eye"></i>
                                                    </button>
                                                    <?php if ($capacitacion['estado'] == 'Inscrito'): ?>
                                                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="cancelarInscripcion(<?= $capacitacion['id_capacitacion'] ?>)">
                                                        <i class="bi bi-x-circle"></i>
                                                    </button>
                                                    <?php endif; ?>
                                                    <?php if ($capacitacion['certificado_url']): ?>
                                                    <a href="<?= base_url($capacitacion['certificado_url']) ?>" class="btn btn-sm btn-outline-success" target="_blank">
                                                        <i class="bi bi-download"></i>
                                                    </a>
                                                    <?php endif; ?>
                                                </div>
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
</div>

<!-- Modal Detalles Capacitación -->
<div class="modal fade" id="modalDetallesCapacitacion" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detalles de la Capacitación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="detallesCapacitacion">
                <!-- Contenido dinámico -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(document).ready(function() {
    // Inicializar DataTables
    $('#tablaCapacitacionesDisponibles').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json'
        },
        pageLength: 5,
        order: [[3, 'asc']]
    });

    $('#tablaMisCapacitaciones').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json'
        },
        pageLength: 10,
        order: [[3, 'desc']]
    });
});

function inscribirseCapacitacion(idCapacitacion) {
    Swal.fire({
        title: '¿Confirmar inscripción?',
        text: "¿Deseas inscribirte en esta capacitación?",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sí, inscribirme',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '<?= base_url('docente/capacitaciones/inscribirse') ?>',
                type: 'POST',
                data: {
                    id_capacitacion: idCapacitacion
                },
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: '¡Inscripción exitosa!',
                            text: response.message
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: response.message
                        });
                    }
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Error al procesar la inscripción'
                    });
                }
            });
        }
    });
}

function cancelarInscripcion(idCapacitacion) {
    Swal.fire({
        title: '¿Cancelar inscripción?',
        text: "¿Estás seguro de que deseas cancelar tu inscripción?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sí, cancelar',
        cancelButtonText: 'No, mantener'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '<?= base_url('docente/capacitaciones/cancelar-inscripcion') ?>',
                type: 'POST',
                data: {
                    id_capacitacion: idCapacitacion
                },
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Inscripción cancelada',
                            text: response.message
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: response.message
                        });
                    }
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Error al cancelar la inscripción'
                    });
                }
            });
        }
    });
}

function verDetallesCapacitacion(idCapacitacion) {
    $.ajax({
        url: '<?= base_url('docente/capacitaciones/detalles/') ?>' + idCapacitacion,
        type: 'GET',
        success: function(response) {
            if (response.success) {
                $('#detallesCapacitacion').html(response.html);
                $('#modalDetallesCapacitacion').modal('show');
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: response.message
                });
            }
        },
        error: function() {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Error al cargar los detalles'
            });
        }
    });
}
</script>
<?= $this->endSection() ?> 