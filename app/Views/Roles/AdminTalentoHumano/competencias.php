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
                            <h4 class="mb-0">Gestión de Competencias</h4>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalCompetencia">
                                <i class="bi bi-plus-circle"></i> Nueva Competencia
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Estadísticas -->
                        <div class="row mb-4">
                            <div class="col-md-3">
                                <div class="card bg-primary text-white">
                                    <div class="card-body">
                                        <h5 class="card-title">Total Competencias</h5>
                                        <h3 class="mb-0"><?= count($competencias ?? []) ?></h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-success text-white">
                                    <div class="card-body">
                                        <h5 class="card-title">Evaluaciones Realizadas</h5>
                                        <h3 class="mb-0"><?= count($evaluaciones ?? []) ?></h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-warning text-white">
                                    <div class="card-body">
                                        <h5 class="card-title">Promedio Nivel</h5>
                                        <h3 class="mb-0"><?= count($evaluaciones ?? []) > 0 ? round(count($evaluaciones ?? []) / count($competencias ?? []), 1) : 0 ?></h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-info text-white">
                                    <div class="card-body">
                                        <h5 class="card-title">Empleados Evaluados</h5>
                                        <h3 class="mb-0"><?= count(array_unique(array_column($evaluaciones ?? [], 'empleado_nombres'))) ?></h3>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Filtros -->
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <select class="form-select" id="filtroCompetencia">
                                    <option value="">Todas las competencias</option>
                                    <?php foreach ($competencias ?? [] as $competencia): ?>
                                    <option value="<?= $competencia['nombre'] ?>"><?= $competencia['nombre'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select class="form-select" id="filtroNivel">
                                    <option value="">Todos los niveles</option>
                                    <option value="Básico">Básico</option>
                                    <option value="Intermedio">Intermedio</option>
                                    <option value="Avanzado">Avanzado</option>
                                    <option value="Experto">Experto</option>
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

                        <!-- Tabla de Competencias -->
                        <div class="table-responsive">
                            <table class="table table-striped table-hover" id="tablaCompetencias">
                                <thead class="table-dark">
                                    <tr>
                                        <th>ID</th>
                                        <th>Competencia</th>
                                        <th>Descripción</th>
                                        <th>Total Evaluaciones</th>
                                        <th>Fecha Creación</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($competencias ?? [] as $competencia): ?>
                                    <tr>
                                        <td><?= $competencia['id_competencia'] ?></td>
                                        <td>
                                            <strong><?= $competencia['nombre'] ?></strong>
                                        </td>
                                        <td><?= $competencia['descripcion'] ?? 'Sin descripción' ?></td>
                                        <td>
                                            <span class="badge bg-primary"><?= $competencia['total_evaluaciones'] ?? 0 ?></span>
                                        </td>
                                        <td><?= date('d/m/Y', strtotime($competencia['created_at'])) ?></td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <button type="button" class="btn btn-sm btn-outline-primary" onclick="verCompetencia(<?= $competencia['id_competencia'] ?>)">
                                                    <i class="bi bi-eye"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline-warning" onclick="editarCompetencia(<?= $competencia['id_competencia'] ?>)">
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline-info" onclick="verEvaluaciones(<?= $competencia['id_competencia'] ?>)">
                                                    <i class="bi bi-list-ul"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline-danger" onclick="eliminarCompetencia(<?= $competencia['id_competencia'] ?>)">
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

<!-- Modal para Crear/Editar Competencia -->
<div class="modal fade" id="modalCompetencia" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Nueva Competencia</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formCompetencia">
                <div class="modal-body">
                    <input type="hidden" id="id_competencia" name="id_competencia">
                    
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre de la Competencia *</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripción</label>
                        <textarea class="form-control" id="descripcion" name="descripcion" rows="3"></textarea>
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

<!-- Modal para Ver Competencia -->
<div class="modal fade" id="modalVerCompetencia" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detalles de la Competencia</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="detallesCompetencia">
                <!-- Se llenará dinámicamente -->
            </div>
        </div>
    </div>
</div>

<!-- Modal para Ver Evaluaciones -->
<div class="modal fade" id="modalEvaluaciones" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Evaluaciones de Competencias</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="contenidoEvaluaciones">
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
    $('#tablaCompetencias').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json'
        },
        order: [[1, 'asc']]
    });

    // Filtros
    $('#filtroCompetencia, #filtroNivel, #filtroFecha').on('change', function() {
        aplicarFiltros();
    });

    // Manejar envío del formulario
    $('#formCompetencia').on('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const isEdit = $('#id_competencia').val() !== '';
        
        $.ajax({
            url: isEdit ? '/admin-th/competencias/actualizar' : '/admin-th/competencias/crear',
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
    const competencia = $('#filtroCompetencia').val();
    const nivel = $('#filtroNivel').val();
    const fecha = $('#filtroFecha').val();
    
    $('#tablaCompetencias').DataTable().columns().every(function() {
        const column = this;
        let searchTerm = '';
        
        if (column.index() === 1 && competencia) { // Columna competencia
            searchTerm = competencia;
        } else if (column.index() === 2 && nivel) { // Columna nivel
            searchTerm = nivel;
        } else if (column.index() === 4 && fecha) { // Columna fecha
            searchTerm = fecha;
        }
        
        column.search(searchTerm).draw();
    });
}

function limpiarFiltros() {
    $('#filtroCompetencia, #filtroNivel').val('');
    $('#filtroFecha').val('');
    $('#tablaCompetencias').DataTable().search('').columns().search('').draw();
}

function verCompetencia(id) {
    $.ajax({
        url: `/admin-th/competencias/${id}`,
        type: 'GET',
        success: function(response) {
            if (response.success) {
                $('#detallesCompetencia').html(response.html);
                $('#modalVerCompetencia').modal('show');
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

function editarCompetencia(id) {
    $.ajax({
        url: `/admin-th/competencias/${id}/editar`,
        type: 'GET',
        success: function(response) {
            if (response.success) {
                $('#id_competencia').val(response.competencia.id_competencia);
                $('#nombre').val(response.competencia.nombre);
                $('#descripcion').val(response.competencia.descripcion);
                
                $('#modalTitle').text('Editar Competencia');
                $('#modalCompetencia').modal('show');
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

function verEvaluaciones(id) {
    $.ajax({
        url: `/admin-th/competencias/${id}/evaluaciones`,
        type: 'GET',
        success: function(response) {
            if (response.success) {
                $('#contenidoEvaluaciones').html(response.html);
                $('#modalEvaluaciones').modal('show');
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

function eliminarCompetencia(id) {
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
                url: `/admin-th/competencias/${id}/eliminar`,
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