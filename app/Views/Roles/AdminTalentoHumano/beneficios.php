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
                            <h4 class="mb-0">Gestión de Beneficios</h4>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalBeneficio">
                                <i class="bi bi-plus-circle"></i> Nuevo Beneficio
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Estadísticas -->
                        <div class="row mb-4">
                            <div class="col-md-3">
                                <div class="card bg-primary text-white">
                                    <div class="card-body">
                                        <h5 class="card-title">Total Beneficios</h5>
                                        <h3 class="mb-0"><?= count($beneficios ?? []) ?></h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-success text-white">
                                    <div class="card-body">
                                        <h5 class="card-title">Asignaciones</h5>
                                        <h3 class="mb-0"><?= count($asignaciones ?? []) ?></h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-warning text-white">
                                    <div class="card-body">
                                        <h5 class="card-title">Empleados Beneficiados</h5>
                                        <h3 class="mb-0"><?= count(array_unique(array_column($asignaciones ?? [], 'empleado_nombres'))) ?></h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-info text-white">
                                    <div class="card-body">
                                        <h5 class="card-title">Tipos Diferentes</h5>
                                        <h3 class="mb-0"><?= count(array_unique(array_column($beneficios ?? [], 'tipo'))) ?></h3>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Filtros -->
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <select class="form-select" id="filtroTipo">
                                    <option value="">Todos los tipos</option>
                                    <option value="Salud">Salud</option>
                                    <option value="Educación">Educación</option>
                                    <option value="Transporte">Transporte</option>
                                    <option value="Alimentación">Alimentación</option>
                                    <option value="Otro">Otro</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select class="form-select" id="filtroEmpleado">
                                    <option value="">Todos los empleados</option>
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

                        <!-- Tabla de Beneficios -->
                        <div class="table-responsive">
                            <table class="table table-striped table-hover" id="tablaBeneficios">
                                <thead class="table-dark">
                                    <tr>
                                        <th>ID</th>
                                        <th>Beneficio</th>
                                        <th>Tipo</th>
                                        <th>Descripción</th>
                                        <th>Total Asignaciones</th>
                                        <th>Fecha Creación</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($beneficios ?? [] as $beneficio): ?>
                                    <tr>
                                        <td><?= $beneficio['id_beneficio'] ?></td>
                                        <td>
                                            <strong><?= $beneficio['nombre'] ?></strong>
                                        </td>
                                        <td>
                                            <span class="badge bg-info"><?= $beneficio['tipo'] ?></span>
                                        </td>
                                        <td><?= $beneficio['descripcion'] ?? 'Sin descripción' ?></td>
                                        <td>
                                            <span class="badge bg-primary"><?= $beneficio['total_asignaciones'] ?? 0 ?></span>
                                        </td>
                                        <td><?= date('d/m/Y', strtotime($beneficio['created_at'])) ?></td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <button type="button" class="btn btn-sm btn-outline-primary" onclick="verBeneficio(<?= $beneficio['id_beneficio'] ?>)">
                                                    <i class="bi bi-eye"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline-warning" onclick="editarBeneficio(<?= $beneficio['id_beneficio'] ?>)">
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline-info" onclick="verAsignaciones(<?= $beneficio['id_beneficio'] ?>)">
                                                    <i class="bi bi-list-ul"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline-danger" onclick="eliminarBeneficio(<?= $beneficio['id_beneficio'] ?>)">
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

<!-- Modal para Crear/Editar Beneficio -->
<div class="modal fade" id="modalBeneficio" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Nuevo Beneficio</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formBeneficio">
                <div class="modal-body">
                    <input type="hidden" id="id_beneficio" name="id_beneficio">
                    
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre del Beneficio *</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="tipo" class="form-label">Tipo de Beneficio *</label>
                        <select class="form-select" id="tipo" name="tipo" required>
                            <option value="Salud">Salud</option>
                            <option value="Educación">Educación</option>
                            <option value="Transporte">Transporte</option>
                            <option value="Alimentación">Alimentación</option>
                            <option value="Otro">Otro</option>
                        </select>
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

<!-- Modal para Ver Beneficio -->
<div class="modal fade" id="modalVerBeneficio" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detalles del Beneficio</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="detallesBeneficio">
                <!-- Se llenará dinámicamente -->
            </div>
        </div>
    </div>
</div>

<!-- Modal para Ver Asignaciones -->
<div class="modal fade" id="modalAsignaciones" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Asignaciones del Beneficio</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="contenidoAsignaciones">
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
    $('#tablaBeneficios').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json'
        },
        order: [[1, 'asc']]
    });

    // Filtros
    $('#filtroTipo, #filtroEmpleado, #filtroFecha').on('change', function() {
        aplicarFiltros();
    });

    // Manejar envío del formulario
    $('#formBeneficio').on('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const isEdit = $('#id_beneficio').val() !== '';
        
        $.ajax({
            url: isEdit ? '/admin-th/beneficios/actualizar' : '/admin-th/beneficios/crear',
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
    const tipo = $('#filtroTipo').val();
    const empleado = $('#filtroEmpleado').val();
    const fecha = $('#filtroFecha').val();
    
    $('#tablaBeneficios').DataTable().columns().every(function() {
        const column = this;
        let searchTerm = '';
        
        if (column.index() === 2 && tipo) { // Columna tipo
            searchTerm = tipo;
        } else if (column.index() === 1 && empleado) { // Columna empleado
            searchTerm = empleado;
        } else if (column.index() === 5 && fecha) { // Columna fecha
            searchTerm = fecha;
        }
        
        column.search(searchTerm).draw();
    });
}

function limpiarFiltros() {
    $('#filtroTipo, #filtroEmpleado').val('');
    $('#filtroFecha').val('');
    $('#tablaBeneficios').DataTable().search('').columns().search('').draw();
}

function verBeneficio(id) {
    $.ajax({
        url: `/admin-th/beneficios/${id}`,
        type: 'GET',
        success: function(response) {
            if (response.success) {
                $('#detallesBeneficio').html(response.html);
                $('#modalVerBeneficio').modal('show');
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

function editarBeneficio(id) {
    $.ajax({
        url: `/admin-th/beneficios/${id}/editar`,
        type: 'GET',
        success: function(response) {
            if (response.success) {
                $('#id_beneficio').val(response.beneficio.id_beneficio);
                $('#nombre').val(response.beneficio.nombre);
                $('#tipo').val(response.beneficio.tipo);
                $('#descripcion').val(response.beneficio.descripcion);
                
                $('#modalTitle').text('Editar Beneficio');
                $('#modalBeneficio').modal('show');
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

function verAsignaciones(id) {
    $.ajax({
        url: `/admin-th/beneficios/${id}/asignaciones`,
        type: 'GET',
        success: function(response) {
            if (response.success) {
                $('#contenidoAsignaciones').html(response.html);
                $('#modalAsignaciones').modal('show');
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

function eliminarBeneficio(id) {
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
                url: `/admin-th/beneficios/${id}/eliminar`,
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