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
                            <h4 class="mb-0">Gestión de Evaluaciones</h4>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalEvaluacion">
                                <i class="bi bi-plus-circle"></i> Nueva Evaluación
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Estadísticas -->
                        <div class="row mb-4">
                            <div class="col-md-3">
                                <div class="card bg-primary text-white">
                                    <div class="card-body">
                                        <h5 class="card-title">Total Evaluaciones</h5>
                                        <h3 class="mb-0"><?= count($evaluaciones ?? []) ?></h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-success text-white">
                                    <div class="card-body">
                                        <h5 class="card-title">Promedio Puntaje</h5>
                                        <h3 class="mb-0"><?= number_format(array_sum(array_column($evaluaciones ?? [], 'puntaje_total')) / max(count($evaluaciones ?? []), 1), 1) ?></h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-warning text-white">
                                    <div class="card-body">
                                        <h5 class="card-title">Evaluaciones Recientes</h5>
                                        <h3 class="mb-0"><?= count(array_filter($evaluaciones ?? [], function($e) { return strtotime($e['fecha_evaluacion']) >= strtotime('-30 days'); })) ?></h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-info text-white">
                                    <div class="card-body">
                                        <h5 class="card-title">Evaluadores Activos</h5>
                                        <h3 class="mb-0"><?= count(array_unique(array_column($evaluaciones ?? [], 'evaluador_nombres'))) ?></h3>
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
                                <select class="form-select" id="filtroEvaluador">
                                    <option value="">Todos los evaluadores</option>
                                    <?php foreach ($empleados ?? [] as $empleado): ?>
                                    <option value="<?= $empleado['nombres'] . ' ' . $empleado['apellidos'] ?>"><?= $empleado['nombres'] . ' ' . $empleado['apellidos'] ?></option>
                                    <?php endforeach; ?>
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

                        <!-- Tabla de Evaluaciones -->
                        <div class="table-responsive">
                            <table class="table table-striped table-hover" id="tablaEvaluaciones">
                                <thead class="table-dark">
                                    <tr>
                                        <th>ID</th>
                                        <th>Empleado</th>
                                        <th>Evaluación</th>
                                        <th>Evaluador</th>
                                        <th>Puntaje</th>
                                        <th>Fecha Evaluación</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($evaluaciones ?? [] as $evaluacion): ?>
                                    <tr>
                                        <td><?= $evaluacion['id_evaluacion_empleado'] ?></td>
                                        <td>
                                            <strong><?= $evaluacion['empleado_nombres'] . ' ' . $evaluacion['empleado_apellidos'] ?></strong>
                                        </td>
                                        <td>
                                            <span class="badge bg-primary"><?= $evaluacion['evaluacion_nombre'] ?></span>
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary"><?= $evaluacion['evaluador_nombres'] . ' ' . $evaluacion['evaluador_apellidos'] ?></span>
                                        </td>
                                        <td>
                                            <?php
                                            $puntaje = $evaluacion['puntaje_total'] ?? 0;
                                            $colorClass = '';
                                            if ($puntaje >= 90) $colorClass = 'bg-success';
                                            elseif ($puntaje >= 80) $colorClass = 'bg-info';
                                            elseif ($puntaje >= 70) $colorClass = 'bg-warning';
                                            else $colorClass = 'bg-danger';
                                            ?>
                                            <span class="badge <?= $colorClass ?>"><?= number_format($puntaje, 1) ?></span>
                                        </td>
                                        <td><?= date('d/m/Y', strtotime($evaluacion['fecha_evaluacion'])) ?></td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <button type="button" class="btn btn-sm btn-outline-primary" onclick="verEvaluacion(<?= $evaluacion['id_evaluacion_empleado'] ?>)">
                                                    <i class="bi bi-eye"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline-warning" onclick="editarEvaluacion(<?= $evaluacion['id_evaluacion_empleado'] ?>)">
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline-info" onclick="verDetalles(<?= $evaluacion['id_evaluacion_empleado'] ?>)">
                                                    <i class="bi bi-list-ul"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline-danger" onclick="eliminarEvaluacion(<?= $evaluacion['id_evaluacion_empleado'] ?>)">
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

<!-- Modal para Crear/Editar Evaluación -->
<div class="modal fade" id="modalEvaluacion" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Nueva Evaluación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formEvaluacion">
                <div class="modal-body">
                    <input type="hidden" id="id_evaluacion_empleado" name="id_evaluacion_empleado">
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="id_evaluacion" class="form-label">Tipo de Evaluación *</label>
                                <select class="form-select" id="id_evaluacion" name="id_evaluacion" required>
                                    <option value="">Seleccionar evaluación...</option>
                                    <!-- Se llenará dinámicamente -->
                                </select>
                            </div>
                        </div>
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
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="id_evaluador" class="form-label">Evaluador *</label>
                                <select class="form-select" id="id_evaluador" name="id_evaluador" required>
                                    <option value="">Seleccionar evaluador...</option>
                                    <?php foreach ($empleados ?? [] as $empleado): ?>
                                    <option value="<?= $empleado['id_empleado'] ?>"><?= $empleado['nombres'] . ' ' . $empleado['apellidos'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="fecha_evaluacion" class="form-label">Fecha de Evaluación *</label>
                                <input type="date" class="form-control" id="fecha_evaluacion" name="fecha_evaluacion" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="puntaje_total" class="form-label">Puntaje Total</label>
                        <input type="number" class="form-control" id="puntaje_total" name="puntaje_total" min="0" max="100" step="0.1">
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

<!-- Modal para Ver Evaluación -->
<div class="modal fade" id="modalVerEvaluacion" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detalles de la Evaluación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="detallesEvaluacion">
                <!-- Se llenará dinámicamente -->
            </div>
        </div>
    </div>
</div>

<!-- Modal para Ver Detalles -->
<div class="modal fade" id="modalDetalles" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detalles de Competencias</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="contenidoDetalles">
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
    $('#tablaEvaluaciones').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json'
        },
        order: [[5, 'desc']]
    });

    // Filtros
    $('#filtroEmpleado, #filtroEvaluador, #filtroFecha').on('change', function() {
        aplicarFiltros();
    });

    // Manejar envío del formulario
    $('#formEvaluacion').on('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const isEdit = $('#id_evaluacion_empleado').val() !== '';
        
        $.ajax({
            url: isEdit ? '/admin-th/evaluaciones/actualizar' : '/admin-th/evaluaciones/crear',
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
    const evaluador = $('#filtroEvaluador').val();
    const fecha = $('#filtroFecha').val();
    
    $('#tablaEvaluaciones').DataTable().columns().every(function() {
        const column = this;
        let searchTerm = '';
        
        if (column.index() === 1 && empleado) { // Columna empleado
            searchTerm = empleado;
        } else if (column.index() === 3 && evaluador) { // Columna evaluador
            searchTerm = evaluador;
        } else if (column.index() === 5 && fecha) { // Columna fecha
            searchTerm = fecha;
        }
        
        column.search(searchTerm).draw();
    });
}

function limpiarFiltros() {
    $('#filtroEmpleado, #filtroEvaluador').val('');
    $('#filtroFecha').val('');
    $('#tablaEvaluaciones').DataTable().search('').columns().search('').draw();
}

function verEvaluacion(id) {
    $.ajax({
        url: `/admin-th/evaluaciones/${id}`,
        type: 'GET',
        success: function(response) {
            if (response.success) {
                $('#detallesEvaluacion').html(response.html);
                $('#modalVerEvaluacion').modal('show');
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

function editarEvaluacion(id) {
    $.ajax({
        url: `/admin-th/evaluaciones/${id}/editar`,
        type: 'GET',
        success: function(response) {
            if (response.success) {
                $('#id_evaluacion_empleado').val(response.evaluacion.id_evaluacion_empleado);
                $('#id_evaluacion').val(response.evaluacion.id_evaluacion);
                $('#id_empleado').val(response.evaluacion.id_empleado);
                $('#id_evaluador').val(response.evaluacion.id_evaluador);
                $('#fecha_evaluacion').val(response.evaluacion.fecha_evaluacion);
                $('#puntaje_total').val(response.evaluacion.puntaje_total);
                $('#observaciones').val(response.evaluacion.observaciones);
                
                $('#modalTitle').text('Editar Evaluación');
                $('#modalEvaluacion').modal('show');
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

function verDetalles(id) {
    $.ajax({
        url: `/admin-th/evaluaciones/${id}/detalles`,
        type: 'GET',
        success: function(response) {
            if (response.success) {
                $('#contenidoDetalles').html(response.html);
                $('#modalDetalles').modal('show');
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

function eliminarEvaluacion(id) {
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
                url: `/admin-th/evaluaciones/${id}/eliminar`,
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