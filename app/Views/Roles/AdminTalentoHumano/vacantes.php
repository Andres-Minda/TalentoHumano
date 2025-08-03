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
                            <h4 class="mb-0">Gestión de Vacantes</h4>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalVacante">
                                <i class="bi bi-plus-circle"></i> Nueva Vacante
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Estadísticas -->
                        <div class="row mb-4">
                            <div class="col-md-3">
                                <div class="card bg-primary text-white">
                                    <div class="card-body">
                                        <h5 class="card-title">Total Vacantes</h5>
                                        <h3 class="mb-0"><?= count($vacantes ?? []) ?></h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-success text-white">
                                    <div class="card-body">
                                        <h5 class="card-title">Abiertas</h5>
                                        <h3 class="mb-0"><?= count(array_filter($vacantes ?? [], function($v) { return $v['estado'] == 'Abierta'; })) ?></h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-warning text-white">
                                    <div class="card-body">
                                        <h5 class="card-title">Cerradas</h5>
                                        <h3 class="mb-0"><?= count(array_filter($vacantes ?? [], function($v) { return $v['estado'] == 'Cerrada'; })) ?></h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-danger text-white">
                                    <div class="card-body">
                                        <h5 class="card-title">Canceladas</h5>
                                        <h3 class="mb-0"><?= count(array_filter($vacantes ?? [], function($v) { return $v['estado'] == 'Cancelada'; })) ?></h3>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Filtros -->
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <select class="form-select" id="filtroEstado">
                                    <option value="">Todos los estados</option>
                                    <option value="Abierta">Abierta</option>
                                    <option value="Cerrada">Cerrada</option>
                                    <option value="Cancelada">Cancelada</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select class="form-select" id="filtroPuesto">
                                    <option value="">Todos los puestos</option>
                                    <?php foreach ($puestos ?? [] as $puesto): ?>
                                    <option value="<?= $puesto['nombre'] ?>"><?= $puesto['nombre'] ?></option>
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

                        <!-- Tabla de Vacantes -->
                        <div class="table-responsive">
                            <table class="table table-striped table-hover" id="tablaVacantes">
                                <thead class="table-dark">
                                    <tr>
                                        <th>ID</th>
                                        <th>Puesto</th>
                                        <th>Departamento</th>
                                        <th>Estado</th>
                                        <th>Fecha Publicación</th>
                                        <th>Fecha Cierre</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($vacantes ?? [] as $vacante): ?>
                                    <tr>
                                        <td><?= $vacante['id_vacante'] ?></td>
                                        <td>
                                            <strong><?= $vacante['puesto_nombre'] ?? 'Sin asignar' ?></strong>
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary"><?= $vacante['departamento_nombre'] ?? 'Sin asignar' ?></span>
                                        </td>
                                        <td>
                                            <?php
                                            $estadoClass = '';
                                            switch($vacante['estado']) {
                                                case 'Abierta': $estadoClass = 'bg-success'; break;
                                                case 'Cerrada': $estadoClass = 'bg-warning'; break;
                                                case 'Cancelada': $estadoClass = 'bg-danger'; break;
                                                default: $estadoClass = 'bg-secondary';
                                            }
                                            ?>
                                            <span class="badge <?= $estadoClass ?>"><?= $vacante['estado'] ?></span>
                                        </td>
                                        <td><?= date('d/m/Y', strtotime($vacante['fecha_publicacion'])) ?></td>
                                        <td>
                                            <?php if ($vacante['fecha_cierre']): ?>
                                                <?= date('d/m/Y', strtotime($vacante['fecha_cierre'])) ?>
                                            <?php else: ?>
                                                <span class="text-muted">Sin fecha de cierre</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <button type="button" class="btn btn-sm btn-outline-primary" onclick="verVacante(<?= $vacante['id_vacante'] ?>)">
                                                    <i class="bi bi-eye"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline-warning" onclick="editarVacante(<?= $vacante['id_vacante'] ?>)">
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline-info" onclick="verCandidatos(<?= $vacante['id_vacante'] ?>)">
                                                    <i class="bi bi-people"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline-danger" onclick="eliminarVacante(<?= $vacante['id_vacante'] ?>)">
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

<!-- Modal para Crear/Editar Vacante -->
<div class="modal fade" id="modalVacante" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Nueva Vacante</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formVacante">
                <div class="modal-body">
                    <input type="hidden" id="id_vacante" name="id_vacante">
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="id_puesto" class="form-label">Puesto *</label>
                                <select class="form-select" id="id_puesto" name="id_puesto" required>
                                    <option value="">Seleccionar puesto...</option>
                                    <?php foreach ($puestos ?? [] as $puesto): ?>
                                    <option value="<?= $puesto['id_puesto'] ?>"><?= $puesto['nombre'] ?> - <?= $puesto['departamento_nombre'] ?? 'Sin departamento' ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="estado" class="form-label">Estado *</label>
                                <select class="form-select" id="estado" name="estado" required>
                                    <option value="Abierta">Abierta</option>
                                    <option value="Cerrada">Cerrada</option>
                                    <option value="Cancelada">Cancelada</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="fecha_publicacion" class="form-label">Fecha de Publicación *</label>
                                <input type="date" class="form-control" id="fecha_publicacion" name="fecha_publicacion" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="fecha_cierre" class="form-label">Fecha de Cierre</label>
                                <input type="date" class="form-control" id="fecha_cierre" name="fecha_cierre">
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripción</label>
                        <textarea class="form-control" id="descripcion" name="descripcion" rows="3"></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label for="requisitos" class="form-label">Requisitos</label>
                        <textarea class="form-control" id="requisitos" name="requisitos" rows="3"></textarea>
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

<!-- Modal para Ver Vacante -->
<div class="modal fade" id="modalVerVacante" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detalles de la Vacante</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="detallesVacante">
                <!-- Se llenará dinámicamente -->
            </div>
        </div>
    </div>
</div>

<!-- Modal para Ver Candidatos -->
<div class="modal fade" id="modalCandidatos" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Candidatos de la Vacante</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="listaCandidatos">
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
    $('#tablaVacantes').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json'
        },
        order: [[4, 'desc']]
    });

    // Filtros
    $('#filtroEstado, #filtroPuesto, #filtroFecha').on('change', function() {
        aplicarFiltros();
    });

    // Manejar envío del formulario
    $('#formVacante').on('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const isEdit = $('#id_vacante').val() !== '';
        
        $.ajax({
            url: isEdit ? '/admin-th/vacantes/actualizar' : '/admin-th/vacantes/crear',
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
    const estado = $('#filtroEstado').val();
    const puesto = $('#filtroPuesto').val();
    const fecha = $('#filtroFecha').val();
    
    $('#tablaVacantes').DataTable().columns().every(function() {
        const column = this;
        let searchTerm = '';
        
        if (column.index() === 3 && estado) { // Columna estado
            searchTerm = estado;
        } else if (column.index() === 1 && puesto) { // Columna puesto
            searchTerm = puesto;
        } else if (column.index() === 4 && fecha) { // Columna fecha
            searchTerm = fecha;
        }
        
        column.search(searchTerm).draw();
    });
}

function limpiarFiltros() {
    $('#filtroEstado, #filtroPuesto').val('');
    $('#filtroFecha').val('');
    $('#tablaVacantes').DataTable().search('').columns().search('').draw();
}

function verVacante(id) {
    $.ajax({
        url: `/admin-th/vacantes/${id}`,
        type: 'GET',
        success: function(response) {
            if (response.success) {
                $('#detallesVacante').html(response.html);
                $('#modalVerVacante').modal('show');
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

function editarVacante(id) {
    $.ajax({
        url: `/admin-th/vacantes/${id}/editar`,
        type: 'GET',
        success: function(response) {
            if (response.success) {
                $('#id_vacante').val(response.vacante.id_vacante);
                $('#id_puesto').val(response.vacante.id_puesto);
                $('#estado').val(response.vacante.estado);
                $('#fecha_publicacion').val(response.vacante.fecha_publicacion);
                $('#fecha_cierre').val(response.vacante.fecha_cierre);
                $('#descripcion').val(response.vacante.descripcion);
                $('#requisitos').val(response.vacante.requisitos);
                
                $('#modalTitle').text('Editar Vacante');
                $('#modalVacante').modal('show');
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

function verCandidatos(id) {
    $.ajax({
        url: `/admin-th/vacantes/${id}/candidatos`,
        type: 'GET',
        success: function(response) {
            if (response.success) {
                $('#listaCandidatos').html(response.html);
                $('#modalCandidatos').modal('show');
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

function eliminarVacante(id) {
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
                url: `/admin-th/vacantes/${id}/eliminar`,
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