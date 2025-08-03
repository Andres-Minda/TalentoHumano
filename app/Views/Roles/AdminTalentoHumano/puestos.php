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
                            <h4 class="mb-0">Gestión de Puestos de Trabajo</h4>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalPuesto">
                                <i class="bi bi-plus-circle"></i> Nuevo Puesto
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Estadísticas -->
                        <div class="row mb-4">
                            <div class="col-md-3">
                                <div class="card bg-primary text-white">
                                    <div class="card-body">
                                        <h5 class="card-title">Total Puestos</h5>
                                        <h3 class="mb-0"><?= count($puestos ?? []) ?></h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-success text-white">
                                    <div class="card-body">
                                        <h5 class="card-title">Con Empleados</h5>
                                        <h3 class="mb-0"><?= count(array_filter($puestos ?? [], function($p) { return ($p['total_empleados'] ?? 0) > 0; })) ?></h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-info text-white">
                                    <div class="card-body">
                                        <h5 class="card-title">Total Empleados</h5>
                                        <h3 class="mb-0"><?= array_sum(array_column($puestos ?? [], 'total_empleados')) ?></h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-warning text-white">
                                    <div class="card-body">
                                        <h5 class="card-title">Salario Promedio</h5>
                                        <h3 class="mb-0">$<?= number_format(array_sum(array_column($puestos ?? [], 'salario_base')) / max(count($puestos ?? []), 1), 0, ',', '.') ?></h3>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tabla de Puestos -->
                        <div class="table-responsive">
                            <table class="table table-striped table-hover" id="tablaPuestos">
                                <thead class="table-dark">
                                    <tr>
                                        <th>ID</th>
                                        <th>Puesto</th>
                                        <th>Departamento</th>
                                        <th>Descripción</th>
                                        <th>Salario Base</th>
                                        <th>Empleados</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($puestos ?? [] as $puesto): ?>
                                    <tr>
                                        <td><?= $puesto['id_puesto'] ?></td>
                                        <td>
                                            <strong><?= $puesto['nombre'] ?></strong>
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary"><?= $puesto['departamento_nombre'] ?? 'Sin asignar' ?></span>
                                        </td>
                                        <td><?= $puesto['descripcion'] ?? 'Sin descripción' ?></td>
                                        <td>
                                            <span class="text-success fw-bold">$<?= number_format($puesto['salario_base'], 0, ',', '.') ?></span>
                                        </td>
                                        <td>
                                            <span class="badge bg-primary"><?= $puesto['total_empleados'] ?? 0 ?></span>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <button type="button" class="btn btn-sm btn-outline-primary" onclick="verPuesto(<?= $puesto['id_puesto'] ?>)">
                                                    <i class="bi bi-eye"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline-warning" onclick="editarPuesto(<?= $puesto['id_puesto'] ?>)">
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline-danger" onclick="eliminarPuesto(<?= $puesto['id_puesto'] ?>)">
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

<!-- Modal para Crear/Editar Puesto -->
<div class="modal fade" id="modalPuesto" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Nuevo Puesto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formPuesto">
                <div class="modal-body">
                    <input type="hidden" id="id_puesto" name="id_puesto">
                    
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre del Puesto *</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripción</label>
                        <textarea class="form-control" id="descripcion" name="descripcion" rows="3"></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label for="id_departamento" class="form-label">Departamento *</label>
                        <select class="form-select" id="id_departamento" name="id_departamento" required>
                            <option value="">Seleccionar departamento...</option>
                            <?php foreach ($departamentos ?? [] as $departamento): ?>
                            <option value="<?= $departamento['id_departamento'] ?>"><?= $departamento['nombre'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="salario_base" class="form-label">Salario Base *</label>
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input type="number" class="form-control" id="salario_base" name="salario_base" min="0" step="0.01" required>
                        </div>
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

<!-- Modal para Ver Puesto -->
<div class="modal fade" id="modalVerPuesto" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detalles del Puesto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="detallesPuesto">
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
    $('#tablaPuestos').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json'
        },
        order: [[1, 'asc']]
    });

    // Manejar envío del formulario
    $('#formPuesto').on('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const isEdit = $('#id_puesto').val() !== '';
        
        $.ajax({
            url: isEdit ? '/admin-th/puestos/actualizar' : '/admin-th/puestos/crear',
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

function verPuesto(id) {
    $.ajax({
        url: `/admin-th/puestos/${id}`,
        type: 'GET',
        success: function(response) {
            if (response.success) {
                $('#detallesPuesto').html(response.html);
                $('#modalVerPuesto').modal('show');
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

function editarPuesto(id) {
    $.ajax({
        url: `/admin-th/puestos/${id}/editar`,
        type: 'GET',
        success: function(response) {
            if (response.success) {
                $('#id_puesto').val(response.puesto.id_puesto);
                $('#nombre').val(response.puesto.nombre);
                $('#descripcion').val(response.puesto.descripcion);
                $('#id_departamento').val(response.puesto.id_departamento);
                $('#salario_base').val(response.puesto.salario_base);
                
                $('#modalTitle').text('Editar Puesto');
                $('#modalPuesto').modal('show');
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

function eliminarPuesto(id) {
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
                url: `/admin-th/puestos/${id}/eliminar`,
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