<?php $sidebar = 'sidebar_docente'; ?>
<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>

<div class="page-wrapper">
    <div class="page-content">
        <!-- Header -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <h4 class="mb-0"><i class="bi bi-calendar-check"></i> Control de Asistencias</h4>
                    <div class="page-title-right">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalAsistencia">
                            <i class="bi bi-plus-circle"></i> Registrar Asistencia
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
                            <div class="flex-grow-1">
                                <h4 class="mb-0"><?= $estadisticas['total_dias'] ?? 0 ?></h4>
                                <p class="text-muted mb-0">Días Trabajados</p>
                            </div>
                            <div class="text-primary">
                                <i class="bi bi-calendar-event fs-1"></i>
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
                                <h4 class="mb-0"><?= $estadisticas['puntuales'] ?? 0 ?></h4>
                                <p class="text-muted mb-0">Puntual</p>
                            </div>
                            <div class="text-success">
                                <i class="bi bi-check-circle fs-1"></i>
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
                                <h4 class="mb-0"><?= $estadisticas['tardanzas'] ?? 0 ?></h4>
                                <p class="text-muted mb-0">Tardanzas</p>
                            </div>
                            <div class="text-warning">
                                <i class="bi bi-clock fs-1"></i>
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
                                <h4 class="mb-0"><?= $estadisticas['ausencias'] ?? 0 ?></h4>
                                <p class="text-muted mb-0">Ausencias</p>
                            </div>
                            <div class="text-danger">
                                <i class="bi bi-x-circle fs-1"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <label class="form-label">Fecha Desde</label>
                                <input type="date" class="form-control" id="fechaDesde">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Fecha Hasta</label>
                                <input type="date" class="form-control" id="fechaHasta">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Estado</label>
                                <select class="form-select" id="filtroEstado">
                                    <option value="">Todos</option>
                                    <option value="Puntual">Puntual</option>
                                    <option value="Tardanza">Tardanza</option>
                                    <option value="Ausente">Ausente</option>
                                    <option value="Justificado">Justificado</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">&nbsp;</label>
                                <button type="button" class="btn btn-primary w-100" onclick="filtrarAsistencias()">
                                    <i class="bi bi-search"></i> Filtrar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Asistencias Table -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Registro de Asistencias</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover" id="tablaAsistencias">
                                <thead>
                                    <tr>
                                        <th>Fecha</th>
                                        <th>Hora Entrada</th>
                                        <th>Hora Salida</th>
                                        <th>Horas Trabajadas</th>
                                        <th>Estado</th>
                                        <th>Observaciones</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($asistencias ?? [] as $asistencia): ?>
                                    <tr>
                                        <td><?= date('d/m/Y', strtotime($asistencia['fecha'])) ?></td>
                                        <td>
                                            <?php if ($asistencia['hora_entrada']): ?>
                                                <span class="text-success"><?= date('H:i', strtotime($asistencia['hora_entrada'])) ?></span>
                                            <?php else: ?>
                                                <span class="text-muted">No registrada</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if ($asistencia['hora_salida']): ?>
                                                <span class="text-danger"><?= date('H:i', strtotime($asistencia['hora_salida'])) ?></span>
                                            <?php else: ?>
                                                <span class="text-muted">No registrada</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?= $asistencia['horas_trabajadas'] ?? 'N/A' ?></td>
                                        <td>
                                            <?php
                                            $estadoClass = '';
                                            switch($asistencia['estado']) {
                                                case 'Puntual': $estadoClass = 'bg-success'; break;
                                                case 'Tardanza': $estadoClass = 'bg-warning'; break;
                                                case 'Ausente': $estadoClass = 'bg-danger'; break;
                                                default: $estadoClass = 'bg-secondary';
                                            }
                                            ?>
                                            <span class="badge <?= $estadoClass ?>"><?= $asistencia['estado'] ?></span>
                                        </td>
                                        <td><?= $asistencia['observaciones'] ?? 'Sin observaciones' ?></td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <button type="button" class="btn btn-sm btn-outline-primary" onclick="verAsistencia(<?= $asistencia['id_asistencia'] ?>)">
                                                    <i class="bi bi-eye"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline-warning" onclick="editarAsistencia(<?= $asistencia['id_asistencia'] ?>)">
                                                    <i class="bi bi-pencil"></i>
                                                </button>
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

<!-- Modal para Ver/Editar Asistencia -->
<div class="modal fade" id="modalAsistencia" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Registrar Asistencia</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formAsistencia">
                <div class="modal-body">
                    <input type="hidden" id="id_asistencia" name="id_asistencia">
                    
                    <div class="mb-3">
                        <label for="fecha" class="form-label">Fecha *</label>
                        <input type="date" class="form-control" id="fecha" name="fecha" required>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="hora_entrada" class="form-label">Hora de Entrada</label>
                                <input type="time" class="form-control" id="hora_entrada" name="hora_entrada">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="hora_salida" class="form-label">Hora de Salida</label>
                                <input type="time" class="form-control" id="hora_salida" name="hora_salida">
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="estado" class="form-label">Estado *</label>
                        <select class="form-select" id="estado" name="estado" required>
                            <option value="">Seleccionar</option>
                            <option value="Puntual">Puntual</option>
                            <option value="Tardanza">Tardanza</option>
                            <option value="Ausente">Ausente</option>
                            <option value="Justificado">Justificado</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="observaciones" class="form-label">Observaciones</label>
                        <textarea class="form-control" id="observaciones" name="observaciones" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(document).ready(function() {
    // Inicializar DataTable
    $('#tablaAsistencias').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json'
        },
        pageLength: 10,
        order: [[0, 'desc']]
    });

    // Formulario de asistencia
    $('#formAsistencia').on('submit', function(e) {
        e.preventDefault();
        guardarAsistencia();
    });
});

function filtrarAsistencias() {
    var fechaDesde = $('#fechaDesde').val();
    var fechaHasta = $('#fechaHasta').val();
    var estado = $('#filtroEstado').val();
    
    // Aquí implementarías la lógica de filtrado
    console.log('Filtrando:', { fechaDesde, fechaHasta, estado });
}

function guardarAsistencia() {
    var formData = new FormData($('#formAsistencia')[0]);
    
    $.ajax({
        url: '<?= base_url('docente/asistencias/guardar') ?>',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            if (response.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Éxito',
                    text: 'Asistencia registrada correctamente'
                }).then(() => {
                    location.reload();
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: response.message || 'Error al registrar asistencia'
                });
            }
        },
        error: function() {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Error de conexión'
            });
        }
    });
}

function verAsistencia(id) {
    // Implementar vista detallada
    console.log('Ver asistencia:', id);
}

function editarAsistencia(id) {
    // Implementar edición
    console.log('Editar asistencia:', id);
}
</script>
<?= $this->endSection() ?> 