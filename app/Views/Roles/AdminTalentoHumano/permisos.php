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
                            <h4 class="mb-0">Gestión de Permisos</h4>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalPermiso">
                                <i class="bi bi-plus-circle"></i> Nuevo Permiso
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Estadísticas -->
                        <div class="row mb-4">
                            <div class="col-md-3">
                                <div class="card bg-primary text-white">
                                    <div class="card-body">
                                        <h5 class="card-title">Total Permisos</h5>
                                        <h3 class="mb-0"><?= count($permisos ?? []) ?></h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-success text-white">
                                    <div class="card-body">
                                        <h5 class="card-title">Aprobados</h5>
                                        <h3 class="mb-0"><?= count(array_filter($permisos ?? [], function($p) { return $p['estado'] == 'Aprobado'; })) ?></h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-warning text-white">
                                    <div class="card-body">
                                        <h5 class="card-title">Pendientes</h5>
                                        <h3 class="mb-0"><?= count(array_filter($permisos ?? [], function($p) { return $p['estado'] == 'Pendiente'; })) ?></h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-danger text-white">
                                    <div class="card-body">
                                        <h5 class="card-title">Rechazados</h5>
                                        <h3 class="mb-0"><?= count(array_filter($permisos ?? [], function($p) { return $p['estado'] == 'Rechazado'; })) ?></h3>
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
                                    <option value="Aprobado">Aprobado</option>
                                    <option value="Pendiente">Pendiente</option>
                                    <option value="Rechazado">Rechazado</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select class="form-select" id="filtroTipo">
                                    <option value="">Todos los tipos</option>
                                    <option value="Personal">Personal</option>
                                    <option value="Médico">Médico</option>
                                    <option value="Académico">Académico</option>
                                    <option value="Otro">Otro</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <button type="button" class="btn btn-secondary" onclick="limpiarFiltros()">
                                    <i class="bi bi-x-circle"></i> Limpiar Filtros
                                </button>
                            </div>
                        </div>

                        <!-- Tabla de Permisos -->
                        <div class="table-responsive">
                            <table class="table table-striped table-hover" id="tablaPermisos">
                                <thead class="table-dark">
                                    <tr>
                                        <th>ID</th>
                                        <th>Empleado</th>
                                        <th>Tipo Permiso</th>
                                        <th>Fecha Inicio</th>
                                        <th>Fecha Fin</th>
                                        <th>Días</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($permisos ?? [] as $permiso): ?>
                                    <tr>
                                        <td><?= $permiso['id_permiso'] ?></td>
                                        <td>
                                            <strong><?= $permiso['empleado_nombres'] . ' ' . $permiso['empleado_apellidos'] ?></strong>
                                        </td>
                                        <td>
                                            <span class="badge bg-info"><?= $permiso['tipo_permiso'] ?></span>
                                        </td>
                                        <td><?= date('d/m/Y', strtotime($permiso['fecha_inicio'])) ?></td>
                                        <td><?= date('d/m/Y', strtotime($permiso['fecha_fin'])) ?></td>
                                        <td>
                                            <span class="badge bg-secondary"><?= $permiso['dias'] ?> días</span>
                                        </td>
                                        <td>
                                            <?php
                                            $estadoClass = '';
                                            switch($permiso['estado']) {
                                                case 'Aprobado': $estadoClass = 'bg-success'; break;
                                                case 'Pendiente': $estadoClass = 'bg-warning'; break;
                                                case 'Rechazado': $estadoClass = 'bg-danger'; break;
                                                default: $estadoClass = 'bg-secondary';
                                            }
                                            ?>
                                            <span class="badge <?= $estadoClass ?>"><?= $permiso['estado'] ?></span>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <button type="button" class="btn btn-sm btn-outline-primary" onclick="verPermiso(<?= $permiso['id_permiso'] ?>)">
                                                    <i class="bi bi-eye"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline-warning" onclick="editarPermiso(<?= $permiso['id_permiso'] ?>)">
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline-info" onclick="verArchivo(<?= $permiso['id_permiso'] ?>)">
                                                    <i class="bi bi-file-earmark"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline-danger" onclick="eliminarPermiso(<?= $permiso['id_permiso'] ?>)">
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

<!-- Modal para Crear/Editar Permiso -->
<div class="modal fade" id="modalPermiso" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Nuevo Permiso</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formPermiso">
                <div class="modal-body">
                    <input type="hidden" id="id_permiso" name="id_permiso">
                    
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
                                <label for="tipo_permiso" class="form-label">Tipo de Permiso *</label>
                                <select class="form-select" id="tipo_permiso" name="tipo_permiso" required>
                                    <option value="Personal">Personal</option>
                                    <option value="Médico">Médico</option>
                                    <option value="Académico">Académico</option>
                                    <option value="Otro">Otro</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="fecha_inicio" class="form-label">Fecha de Inicio *</label>
                                <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="fecha_fin" class="form-label">Fecha de Fin *</label>
                                <input type="date" class="form-control" id="fecha_fin" name="fecha_fin" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="dias" class="form-label">Días *</label>
                                <input type="number" class="form-control" id="dias" name="dias" min="1" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="estado" class="form-label">Estado *</label>
                                <select class="form-select" id="estado" name="estado" required>
                                    <option value="Pendiente">Pendiente</option>
                                    <option value="Aprobado">Aprobado</option>
                                    <option value="Rechazado">Rechazado</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="motivo" class="form-label">Motivo *</label>
                        <textarea class="form-control" id="motivo" name="motivo" rows="3" required></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label for="archivo_url" class="form-label">Archivo de Soporte</label>
                        <input type="file" class="form-control" id="archivo_url" name="archivo_url" accept=".pdf,.doc,.docx,.jpg,.png">
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

<!-- Modal para Ver Permiso -->
<div class="modal fade" id="modalVerPermiso" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detalles del Permiso</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="detallesPermiso">
                <!-- Se llenará dinámicamente -->
            </div>
        </div>
    </div>
</div>

<!-- Modal para Ver Archivo -->
<div class="modal fade" id="modalArchivo" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Archivo de Soporte</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="contenidoArchivo">
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
    $('#tablaPermisos').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json'
        },
        order: [[3, 'desc']]
    });

    // Filtros
    $('#filtroEmpleado, #filtroEstado, #filtroTipo').on('change', function() {
        aplicarFiltros();
    });

    // Calcular días automáticamente
    $('#fecha_inicio, #fecha_fin').on('change', function() {
        calcularDias();
    });

    // Manejar envío del formulario
    $('#formPermiso').on('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const isEdit = $('#id_permiso').val() !== '';
        
        $.ajax({
            url: isEdit ? '/admin-th/permisos/actualizar' : '/admin-th/permisos/crear',
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

function calcularDias() {
    const fechaInicio = $('#fecha_inicio').val();
    const fechaFin = $('#fecha_fin').val();
    
    if (fechaInicio && fechaFin) {
        const inicio = new Date(fechaInicio);
        const fin = new Date(fechaFin);
        const diffTime = Math.abs(fin - inicio);
        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;
        $('#dias').val(diffDays);
    }
}

function aplicarFiltros() {
    const empleado = $('#filtroEmpleado').val();
    const estado = $('#filtroEstado').val();
    const tipo = $('#filtroTipo').val();
    
    $('#tablaPermisos').DataTable().columns().every(function() {
        const column = this;
        let searchTerm = '';
        
        if (column.index() === 1 && empleado) { // Columna empleado
            searchTerm = empleado;
        } else if (column.index() === 6 && estado) { // Columna estado
            searchTerm = estado;
        } else if (column.index() === 2 && tipo) { // Columna tipo
            searchTerm = tipo;
        }
        
        column.search(searchTerm).draw();
    });
}

function limpiarFiltros() {
    $('#filtroEmpleado, #filtroEstado, #filtroTipo').val('');
    $('#tablaPermisos').DataTable().search('').columns().search('').draw();
}

function verPermiso(id) {
    $.ajax({
        url: `/admin-th/permisos/${id}`,
        type: 'GET',
        success: function(response) {
            if (response.success) {
                $('#detallesPermiso').html(response.html);
                $('#modalVerPermiso').modal('show');
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

function editarPermiso(id) {
    $.ajax({
        url: `/admin-th/permisos/${id}/editar`,
        type: 'GET',
        success: function(response) {
            if (response.success) {
                $('#id_permiso').val(response.permiso.id_permiso);
                $('#id_empleado').val(response.permiso.id_empleado);
                $('#tipo_permiso').val(response.permiso.tipo_permiso);
                $('#fecha_inicio').val(response.permiso.fecha_inicio);
                $('#fecha_fin').val(response.permiso.fecha_fin);
                $('#dias').val(response.permiso.dias);
                $('#estado').val(response.permiso.estado);
                $('#motivo').val(response.permiso.motivo);
                
                $('#modalTitle').text('Editar Permiso');
                $('#modalPermiso').modal('show');
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

function verArchivo(id) {
    $.ajax({
        url: `/admin-th/permisos/${id}/archivo`,
        type: 'GET',
        success: function(response) {
            if (response.success) {
                $('#contenidoArchivo').html(response.html);
                $('#modalArchivo').modal('show');
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

function eliminarPermiso(id) {
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
                url: `/admin-th/permisos/${id}/eliminar`,
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