<?php $sidebar = 'sidebar_admin_th'; ?>
<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>

<div class="page-wrapper">
    <div class="page-content">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="mb-0">Control de Asistencias</h4>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalAsistencia">
                                <i class="bi bi-plus-circle"></i> Nueva Asistencia
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Estadísticas -->
                        <div class="row mb-4">
                            <div class="col-md-3">
                                <div class="card bg-primary text-white">
                                    <div class="card-body">
                                        <h5 class="card-title">Total Registros</h5>
                                        <h3 class="mb-0"><?= count($asistencias ?? []) ?></h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-success text-white">
                                    <div class="card-body">
                                        <h5 class="card-title">Puntuales</h5>
                                        <h3 class="mb-0"><?= count(array_filter($asistencias ?? [], function($a) { return $a['estado'] == 'Puntual'; })) ?></h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-warning text-white">
                                    <div class="card-body">
                                        <h5 class="card-title">Tardanzas</h5>
                                        <h3 class="mb-0"><?= count(array_filter($asistencias ?? [], function($a) { return $a['estado'] == 'Tardanza'; })) ?></h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-danger text-white">
                                    <div class="card-body">
                                        <h5 class="card-title">Ausencias</h5>
                                        <h3 class="mb-0"><?= count(array_filter($asistencias ?? [], function($a) { return $a['estado'] == 'Ausente'; })) ?></h3>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Filtros -->
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <select class="form-select" id="filtroEmpleado">
                                    <option value="">Todos los empleados</option>
                                    <?php foreach ($empleados ?? [] as $empleado): ?>
                                    <option value="<?= $empleado['nombres'] . ' ' . $empleado['apellidos'] ?>"><?= $empleado['nombres'] . ' ' . $empleado['apellidos'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select class="form-select" id="filtroEstado">
                                    <option value="">Todos los estados</option>
                                    <option value="Puntual">Puntual</option>
                                    <option value="Tardanza">Tardanza</option>
                                    <option value="Ausente">Ausente</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <input type="date" class="form-control" id="filtroFecha" placeholder="Filtrar por fecha">
                            </div>
                            <div class="col-md-3">
                                <button type="button" class="btn btn-secondary" onclick="limpiarFiltros()">
                                    <i class="bi bi-x-circle"></i> Limpiar Filtros
                                </button>
                            </div>
                        </div>

                        <!-- Tabla de Asistencias -->
                        <div class="table-responsive">
                            <table class="table table-striped table-hover" id="tablaAsistencias">
                                <thead class="table-dark">
                                    <tr>
                                        <th>ID</th>
                                        <th>Empleado</th>
                                        <th>Fecha</th>
                                        <th>Hora Entrada</th>
                                        <th>Hora Salida</th>
                                        <th>Estado</th>
                                        <th>Observaciones</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($asistencias ?? [] as $asistencia): ?>
                                    <tr>
                                        <td><?= $asistencia['id_asistencia'] ?></td>
                                        <td>
                                            <strong><?= $asistencia['empleado_nombre'] ?></strong>
                                        </td>
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
                                                <button type="button" class="btn btn-sm btn-outline-danger" onclick="eliminarAsistencia(<?= $asistencia['id_asistencia'] ?>)">
                                                    <i class="bi bi-trash"></i>
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

<!-- Modal para Crear/Editar Asistencia -->
<div class="modal fade" id="modalAsistencia" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Nueva Asistencia</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formAsistencia">
                <div class="modal-body">
                    <input type="hidden" id="id_asistencia" name="id_asistencia">
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="id_empleado" class="form-label">Empleado *</label>
                                <select class="form-select" id="id_empleado" name="id_empleado" required>
                                    <option value="">Seleccionar empleado...</option>
                                    <?php foreach ($empleados ?? [] as $empleado): ?>
                                    <option value="<?= $empleado['id_empleado'] ?>"><?= $empleado['nombres'] . ' ' . $empleado['apellidos'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="fecha" class="form-label">Fecha *</label>
                                <input type="date" class="form-control" id="fecha" name="fecha" required>
                            </div>
                        </div>
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
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="tipo" class="form-label">Tipo</label>
                                <select class="form-select" id="tipo" name="tipo">
                                    <option value="Normal">Normal</option>
                                    <option value="Extra">Extra</option>
                                    <option value="Vacaciones">Vacaciones</option>
                                    <option value="Permiso">Permiso</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="estado" class="form-label">Estado *</label>
                                <select class="form-select" id="estado" name="estado" required>
                                    <option value="Puntual">Puntual</option>
                                    <option value="Tardanza">Tardanza</option>
                                    <option value="Ausente">Ausente</option>
                                </select>
                            </div>
                        </div>
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

<!-- Modal para Ver Asistencia -->
<div class="modal fade" id="modalVerAsistencia" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detalles de la Asistencia</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="detallesAsistencia">
                <!-- Se llenará dinámicamente -->
            </div>
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
        order: [[2, 'desc']]
    });

    // Filtros
    $('#filtroEmpleado, #filtroEstado, #filtroFecha').on('change', function() {
        aplicarFiltros();
    });

    // Manejar envío del formulario
    $('#formAsistencia').on('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const isEdit = $('#id_asistencia').val() !== '';
        
        $.ajax({
            url: isEdit ? '/admin-th/asistencias/actualizar' : '/admin-th/asistencias/crear',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Éxito',
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
                    text: 'Error al procesar la solicitud'
                });
            }
        });
    });
});

function aplicarFiltros() {
    const empleado = $('#filtroEmpleado').val();
    const estado = $('#filtroEstado').val();
    const fecha = $('#filtroFecha').val();
    
    $('#tablaAsistencias').DataTable().columns().every(function() {
        const column = this;
        let searchTerm = '';
        
        if (column.index() === 1 && empleado) { // Columna empleado
            searchTerm = empleado;
        } else if (column.index() === 5 && estado) { // Columna estado
            searchTerm = estado;
        } else if (column.index() === 2 && fecha) { // Columna fecha
            searchTerm = fecha;
        }
        
        column.search(searchTerm).draw();
    });
}

function limpiarFiltros() {
    $('#filtroEmpleado, #filtroEstado').val('');
    $('#filtroFecha').val('');
    $('#tablaAsistencias').DataTable().search('').columns().search('').draw();
}

function verAsistencia(id) {
    $.ajax({
        url: `/admin-th/asistencias/${id}`,
        type: 'GET',
        success: function(response) {
            if (response.success) {
                $('#detallesAsistencia').html(response.html);
                $('#modalVerAsistencia').modal('show');
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: response.message
                });
            }
        }
    });
}

function editarAsistencia(id) {
    $.ajax({
        url: `/admin-th/asistencias/${id}/editar`,
        type: 'GET',
        success: function(response) {
            if (response.success) {
                $('#id_asistencia').val(response.asistencia.id_asistencia);
                $('#id_empleado').val(response.asistencia.id_empleado);
                $('#fecha').val(response.asistencia.fecha);
                $('#hora_entrada').val(response.asistencia.hora_entrada);
                $('#hora_salida').val(response.asistencia.hora_salida);
                $('#tipo').val(response.asistencia.tipo);
                $('#estado').val(response.asistencia.estado);
                $('#observaciones').val(response.asistencia.observaciones);
                
                $('#modalTitle').text('Editar Asistencia');
                $('#modalAsistencia').modal('show');
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: response.message
                });
            }
        }
    });
}

function eliminarAsistencia(id) {
    Swal.fire({
        title: '¿Estás seguro?',
        text: "Esta acción no se puede deshacer",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: `/admin-th/asistencias/${id}/eliminar`,
                type: 'POST',
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Eliminado',
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
                }
            });
        }
    });
}
</script>
<?= $this->endSection() ?> 