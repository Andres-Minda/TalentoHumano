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
                            <h4 class="mb-0">Gestión de Candidatos</h4>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalCandidato">
                                <i class="bi bi-plus-circle"></i> Nuevo Candidato
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Estadísticas -->
                        <div class="row mb-4">
                            <div class="col-md-3">
                                <div class="card bg-primary text-white">
                                    <div class="card-body">
                                        <h5 class="card-title">Total Candidatos</h5>
                                        <h3 class="mb-0"><?= count($candidatos ?? []) ?></h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-info text-white">
                                    <div class="card-body">
                                        <h5 class="card-title">En Revisión</h5>
                                        <h3 class="mb-0"><?= count(array_filter($candidatos ?? [], function($c) { return $c['estado'] == 'En revisión'; })) ?></h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-warning text-white">
                                    <div class="card-body">
                                        <h5 class="card-title">Entrevista</h5>
                                        <h3 class="mb-0"><?= count(array_filter($candidatos ?? [], function($c) { return $c['estado'] == 'Entrevista'; })) ?></h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-success text-white">
                                    <div class="card-body">
                                        <h5 class="card-title">Contratados</h5>
                                        <h3 class="mb-0"><?= count(array_filter($candidatos ?? [], function($c) { return $c['estado'] == 'Contratado'; })) ?></h3>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Filtros -->
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <select class="form-select" id="filtroEstado">
                                    <option value="">Todos los estados</option>
                                    <option value="En revisión">En revisión</option>
                                    <option value="Entrevista">Entrevista</option>
                                    <option value="Contratado">Contratado</option>
                                    <option value="Rechazado">Rechazado</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select class="form-select" id="filtroVacante">
                                    <option value="">Todas las vacantes</option>
                                    <?php foreach ($vacantes ?? [] as $vacante): ?>
                                    <option value="<?= $vacante['id_vacante'] ?>"><?= $vacante['puesto_nombre'] ?? 'Vacante ' . $vacante['id_vacante'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <input type="text" class="form-control" id="filtroBusqueda" placeholder="Buscar por nombre, email...">
                            </div>
                            <div class="col-md-3">
                                <button type="button" class="btn btn-secondary" onclick="limpiarFiltros()">
                                    <i class="bi bi-x-circle"></i> Limpiar Filtros
                                </button>
                            </div>
                        </div>

                        <!-- Tabla de Candidatos -->
                        <div class="table-responsive">
                            <table class="table table-striped table-hover" id="tablaCandidatos">
                                <thead class="table-dark">
                                    <tr>
                                        <th>ID</th>
                                        <th>Nombre</th>
                                        <th>Email</th>
                                        <th>Teléfono</th>
                                        <th>Vacante</th>
                                        <th>Estado</th>
                                        <th>Fecha Postulación</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($candidatos ?? [] as $candidato): ?>
                                    <tr>
                                        <td><?= $candidato['id_candidato'] ?></td>
                                        <td>
                                            <strong><?= $candidato['nombres'] . ' ' . $candidato['apellidos'] ?></strong>
                                        </td>
                                        <td><?= $candidato['email'] ?></td>
                                        <td><?= $candidato['telefono'] ?? 'No especificado' ?></td>
                                        <td>
                                            <span class="badge bg-secondary"><?= $candidato['id_vacante'] ?? 'Sin asignar' ?></span>
                                        </td>
                                        <td>
                                            <?php
                                            $estadoClass = '';
                                            switch($candidato['estado']) {
                                                case 'En revisión': $estadoClass = 'bg-info'; break;
                                                case 'Entrevista': $estadoClass = 'bg-warning'; break;
                                                case 'Contratado': $estadoClass = 'bg-success'; break;
                                                case 'Rechazado': $estadoClass = 'bg-danger'; break;
                                                default: $estadoClass = 'bg-secondary';
                                            }
                                            ?>
                                            <span class="badge <?= $estadoClass ?>"><?= $candidato['estado'] ?></span>
                                        </td>
                                        <td><?= date('d/m/Y', strtotime($candidato['created_at'])) ?></td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <button type="button" class="btn btn-sm btn-outline-primary" onclick="verCandidato(<?= $candidato['id_candidato'] ?>)">
                                                    <i class="bi bi-eye"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline-warning" onclick="editarCandidato(<?= $candidato['id_candidato'] ?>)">
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline-info" onclick="verCV(<?= $candidato['id_candidato'] ?>)">
                                                    <i class="bi bi-file-earmark-text"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline-danger" onclick="eliminarCandidato(<?= $candidato['id_candidato'] ?>)">
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

<!-- Modal para Crear/Editar Candidato -->
<div class="modal fade" id="modalCandidato" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Nuevo Candidato</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formCandidato">
                <div class="modal-body">
                    <input type="hidden" id="id_candidato" name="id_candidato">
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="nombres" class="form-label">Nombres *</label>
                                <input type="text" class="form-control" id="nombres" name="nombres" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="apellidos" class="form-label">Apellidos *</label>
                                <input type="text" class="form-control" id="apellidos" name="apellidos" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="cedula" class="form-label">Cédula *</label>
                                <input type="text" class="form-control" id="cedula" name="cedula" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email *</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="telefono" class="form-label">Teléfono</label>
                                <input type="text" class="form-control" id="telefono" name="telefono">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="estado" class="form-label">Estado *</label>
                                <select class="form-select" id="estado" name="estado" required>
                                    <option value="En revisión">En revisión</option>
                                    <option value="Entrevista">Entrevista</option>
                                    <option value="Contratado">Contratado</option>
                                    <option value="Rechazado">Rechazado</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="cv_url" class="form-label">CV (URL)</label>
                        <input type="url" class="form-control" id="cv_url" name="cv_url" placeholder="https://ejemplo.com/cv.pdf">
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

<!-- Modal para Ver Candidato -->
<div class="modal fade" id="modalVerCandidato" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detalles del Candidato</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="detallesCandidato">
                <!-- Se llenará dinámicamente -->
            </div>
        </div>
    </div>
</div>

<!-- Modal para Ver CV -->
<div class="modal fade" id="modalCV" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Curriculum Vitae</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="contenidoCV">
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
    $('#tablaCandidatos').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json'
        },
        order: [[6, 'desc']]
    });

    // Filtros
    $('#filtroEstado, #filtroVacante').on('change', function() {
        aplicarFiltros();
    });

    $('#filtroBusqueda').on('keyup', function() {
        $('#tablaCandidatos').DataTable().search(this.value).draw();
    });

    // Manejar envío del formulario
    $('#formCandidato').on('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const isEdit = $('#id_candidato').val() !== '';
        
        $.ajax({
            url: isEdit ? '/admin-th/candidatos/actualizar' : '/admin-th/candidatos/crear',
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
    const vacante = $('#filtroVacante').val();
    
    $('#tablaCandidatos').DataTable().columns().every(function() {
        const column = this;
        let searchTerm = '';
        
        if (column.index() === 5 && estado) { // Columna estado
            searchTerm = estado;
        } else if (column.index() === 4 && vacante) { // Columna vacante
            searchTerm = vacante;
        }
        
        column.search(searchTerm).draw();
    });
}

function limpiarFiltros() {
    $('#filtroEstado, #filtroVacante').val('');
    $('#filtroBusqueda').val('');
    $('#tablaCandidatos').DataTable().search('').columns().search('').draw();
}

function verCandidato(id) {
    $.ajax({
        url: `/admin-th/candidatos/${id}`,
        type: 'GET',
        success: function(response) {
            if (response.success) {
                $('#detallesCandidato').html(response.html);
                $('#modalVerCandidato').modal('show');
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

function editarCandidato(id) {
    $.ajax({
        url: `/admin-th/candidatos/${id}/editar`,
        type: 'GET',
        success: function(response) {
            if (response.success) {
                $('#id_candidato').val(response.candidato.id_candidato);
                $('#nombres').val(response.candidato.nombres);
                $('#apellidos').val(response.candidato.apellidos);
                $('#cedula').val(response.candidato.cedula);
                $('#email').val(response.candidato.email);
                $('#telefono').val(response.candidato.telefono);
                $('#estado').val(response.candidato.estado);
                $('#cv_url').val(response.candidato.cv_url);
                
                $('#modalTitle').text('Editar Candidato');
                $('#modalCandidato').modal('show');
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

function verCV(id) {
    $.ajax({
        url: `/admin-th/candidatos/${id}/cv`,
        type: 'GET',
        success: function(response) {
            if (response.success) {
                $('#contenidoCV').html(response.html);
                $('#modalCV').modal('show');
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

function eliminarCandidato(id) {
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
                url: `/admin-th/candidatos/${id}/eliminar`,
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