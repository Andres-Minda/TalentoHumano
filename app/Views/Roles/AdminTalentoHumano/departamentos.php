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
                            <h4 class="mb-0">Gestión de Departamentos</h4>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalDepartamento">
                                <i class="bi bi-plus-circle"></i> Nuevo Departamento
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Estadísticas -->
                        <div class="row mb-4">
                            <div class="col-md-3">
                                <div class="card bg-primary text-white">
                                    <div class="card-body">
                                        <h5 class="card-title">Total Departamentos</h5>
                                        <h3 class="mb-0"><?= count($departamentos ?? []) ?></h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-success text-white">
                                    <div class="card-body">
                                        <h5 class="card-title">Con Jefe Asignado</h5>
                                        <h3 class="mb-0"><?= count(array_filter($departamentos ?? [], function($d) { return !empty($d['id_jefe']); })) ?></h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-info text-white">
                                    <div class="card-body">
                                        <h5 class="card-title">Total Empleados</h5>
                                        <h3 class="mb-0"><?= array_sum(array_column($departamentos ?? [], 'total_empleados')) ?></h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-warning text-white">
                                    <div class="card-body">
                                        <h5 class="card-title">Promedio Empleados</h5>
                                        <h3 class="mb-0"><?= count($departamentos ?? []) > 0 ? round(array_sum(array_column($departamentos ?? [], 'total_empleados')) / count($departamentos ?? []), 1) : 0 ?></h3>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tabla de Departamentos -->
                        <div class="table-responsive">
                            <table class="table table-striped table-hover" id="tablaDepartamentos">
                                <thead class="table-dark">
                                    <tr>
                                        <th>ID</th>
                                        <th>Nombre</th>
                                        <th>Descripción</th>
                                        <th>Jefe</th>
                                        <th>Empleados</th>
                                        <th>Fecha Creación</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($departamentos ?? [] as $departamento): ?>
                                    <tr>
                                        <td><?= $departamento['id_departamento'] ?></td>
                                        <td>
                                            <strong><?= $departamento['nombre'] ?></strong>
                                        </td>
                                        <td><?= $departamento['descripcion'] ?? 'Sin descripción' ?></td>
                                        <td>
                                            <?php if (!empty($departamento['id_jefe'])): ?>
                                                <span class="badge bg-success">Asignado</span>
                                            <?php else: ?>
                                                <span class="badge bg-warning">Sin asignar</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <span class="badge bg-primary"><?= $departamento['total_empleados'] ?? 0 ?></span>
                                        </td>
                                        <td><?= date('d/m/Y', strtotime($departamento['created_at'])) ?></td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <button type="button" class="btn btn-sm btn-outline-primary" onclick="verDepartamento(<?= $departamento['id_departamento'] ?>)">
                                                    <i class="bi bi-eye"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline-warning" onclick="editarDepartamento(<?= $departamento['id_departamento'] ?>)">
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline-danger" onclick="eliminarDepartamento(<?= $departamento['id_departamento'] ?>)">
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

<!-- Modal para Crear/Editar Departamento -->
<div class="modal fade" id="modalDepartamento" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Nuevo Departamento</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formDepartamento">
                <div class="modal-body">
                    <input type="hidden" id="id_departamento" name="id_departamento">
                    
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre del Departamento *</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripción</label>
                        <textarea class="form-control" id="descripcion" name="descripcion" rows="3"></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label for="id_jefe" class="form-label">Jefe del Departamento</label>
                        <select class="form-select" id="id_jefe" name="id_jefe">
                            <option value="">Seleccionar jefe...</option>
                            <!-- Se llenará dinámicamente -->
                        </select>
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

<!-- Modal para Ver Departamento -->
<div class="modal fade" id="modalVerDepartamento" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detalles del Departamento</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="detallesDepartamento">
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
    $('#tablaDepartamentos').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json'
        },
        order: [[1, 'asc']]
    });

    // Manejar envío del formulario
    $('#formDepartamento').on('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const isEdit = $('#id_departamento').val() !== '';
        
        $.ajax({
            url: isEdit ? '/admin-th/departamentos/actualizar' : '/admin-th/departamentos/crear',
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

function verDepartamento(id) {
    $.ajax({
        url: `/admin-th/departamentos/${id}`,
        type: 'GET',
        success: function(response) {
            if (response.success) {
                $('#detallesDepartamento').html(response.html);
                $('#modalVerDepartamento').modal('show');
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

function editarDepartamento(id) {
    $.ajax({
        url: `/admin-th/departamentos/${id}/editar`,
        type: 'GET',
        success: function(response) {
            if (response.success) {
                $('#id_departamento').val(response.departamento.id_departamento);
                $('#nombre').val(response.departamento.nombre);
                $('#descripcion').val(response.departamento.descripcion);
                $('#id_jefe').val(response.departamento.id_jefe);
                
                $('#modalTitle').text('Editar Departamento');
                $('#modalDepartamento').modal('show');
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

function eliminarDepartamento(id) {
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
                url: `/admin-th/departamentos/${id}/eliminar`,
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