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
                            <h4 class="mb-0">Asignación de Capacitaciones</h4>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalAsignacion">
                                <i class="bi bi-plus-circle"></i> Nueva Asignación
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Estadísticas -->
                        <div class="row mb-4">
                            <div class="col-md-3">
                                <div class="card bg-primary text-white">
                                    <div class="card-body">
                                        <h5 class="card-title">Total Asignaciones</h5>
                                        <h3 class="mb-0"><?= count($asignaciones ?? []) ?></h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-success text-white">
                                    <div class="card-body">
                                        <h5 class="card-title">Asistieron</h5>
                                        <h3 class="mb-0"><?= count(array_filter($asignaciones ?? [], function($a) { return $a['asistio'] == 1; })) ?></h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-warning text-white">
                                    <div class="card-body">
                                        <h5 class="card-title">Aprobaron</h5>
                                        <h3 class="mb-0"><?= count(array_filter($asignaciones ?? [], function($a) { return $a['aprobo'] == 1; })) ?></h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-info text-white">
                                    <div class="card-body">
                                        <h5 class="card-title">Con Certificado</h5>
                                        <h3 class="mb-0"><?= count(array_filter($asignaciones ?? [], function($a) { return !empty($a['certificado_url']); })) ?></h3>
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
                                <select class="form-select" id="filtroCapacitacion">
                                    <option value="">Todas las capacitaciones</option>
                                    <?php foreach ($capacitaciones ?? [] as $capacitacion): ?>
                                    <option value="<?= $capacitacion['nombre'] ?>"><?= $capacitacion['nombre'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select class="form-select" id="filtroEstado">
                                    <option value="">Todos los estados</option>
                                    <option value="1">Asistió</option>
                                    <option value="0">No asistió</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <button type="button" class="btn btn-secondary" onclick="limpiarFiltros()">
                                    <i class="bi bi-x-circle"></i> Limpiar Filtros
                                </button>
                            </div>
                        </div>

                        <!-- Tabla de Asignaciones -->
                        <div class="table-responsive">
                            <table class="table table-striped table-hover" id="tablaAsignaciones">
                                <thead class="table-dark">
                                    <tr>
                                        <th>ID</th>
                                        <th>Empleado</th>
                                        <th>Capacitación</th>
                                        <th>Asistió</th>
                                        <th>Aprobó</th>
                                        <th>Certificado</th>
                                        <th>Fecha Asignación</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($asignaciones ?? [] as $asignacion): ?>
                                    <tr>
                                        <td><?= $asignacion['id_empleado_capacitacion'] ?></td>
                                        <td>
                                            <strong><?= $asignacion['empleado_nombres'] . ' ' . $asignacion['empleado_apellidos'] ?></strong>
                                        </td>
                                        <td>
                                            <span class="badge bg-primary"><?= $asignacion['capacitacion_nombre'] ?></span>
                                        </td>
                                        <td>
                                            <?php if ($asignacion['asistio'] == 1): ?>
                                                <span class="badge bg-success">Sí</span>
                                            <?php else: ?>
                                                <span class="badge bg-danger">No</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if ($asignacion['aprobo'] == 1): ?>
                                                <span class="badge bg-success">Sí</span>
                                            <?php else: ?>
                                                <span class="badge bg-warning">No</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if (!empty($asignacion['certificado_url'])): ?>
                                                <span class="badge bg-info">Disponible</span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary">No disponible</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?= date('d/m/Y', strtotime($asignacion['created_at'])) ?></td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <button type="button" class="btn btn-sm btn-outline-primary" onclick="verAsignacion(<?= $asignacion['id_empleado_capacitacion'] ?>)">
                                                    <i class="bi bi-eye"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline-warning" onclick="editarAsignacion(<?= $asignacion['id_empleado_capacitacion'] ?>)">
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline-info" onclick="verCertificado(<?= $asignacion['id_empleado_capacitacion'] ?>)">
                                                    <i class="bi bi-file-earmark-text"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline-danger" onclick="eliminarAsignacion(<?= $asignacion['id_empleado_capacitacion'] ?>)">
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

<!-- Modal para Crear/Editar Asignación -->
<div class="modal fade" id="modalAsignacion" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Nueva Asignación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formAsignacion">
                <div class="modal-body">
                    <input type="hidden" id="id_empleado_capacitacion" name="id_empleado_capacitacion">
                    
                    <div class="mb-3">
                        <label for="id_empleado" class="form-label">Empleado *</label>
                        <select class="form-select" id="id_empleado" name="id_empleado" required>
                            <option value="">Seleccionar empleado...</option>
                            <?php foreach ($empleados ?? [] as $empleado): ?>
                            <option value="<?= $empleado['id_empleado'] ?>"><?= $empleado['nombres'] . ' ' . $empleado['apellidos'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="id_capacitacion" class="form-label">Capacitación *</label>
                        <select class="form-select" id="id_capacitacion" name="id_capacitacion" required>
                            <option value="">Seleccionar capacitación...</option>
                            <?php foreach ($capacitaciones ?? [] as $capacitacion): ?>
                            <option value="<?= $capacitacion['id_capacitacion'] ?>"><?= $capacitacion['nombre'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="asistio" class="form-label">Asistió</label>
                                <select class="form-select" id="asistio" name="asistio">
                                    <option value="0">No</option>
                                    <option value="1">Sí</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="aprobo" class="form-label">Aprobó</label>
                                <select class="form-select" id="aprobo" name="aprobo">
                                    <option value="0">No</option>
                                    <option value="1">Sí</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="certificado_url" class="form-label">Certificado (URL)</label>
                        <input type="url" class="form-control" id="certificado_url" name="certificado_url" placeholder="https://ejemplo.com/certificado.pdf">
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

<!-- Modal para Ver Asignación -->
<div class="modal fade" id="modalVerAsignacion" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detalles de la Asignación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="detallesAsignacion">
                <!-- Se llenará dinámicamente -->
            </div>
        </div>
    </div>
</div>

<!-- Modal para Ver Certificado -->
<div class="modal fade" id="modalCertificado" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Certificado de Capacitación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="contenidoCertificado">
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
    $('#tablaAsignaciones').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json'
        },
        order: [[6, 'desc']]
    });

    // Filtros
    $('#filtroEmpleado, #filtroCapacitacion, #filtroEstado').on('change', function() {
        aplicarFiltros();
    });

    // Manejar envío del formulario
    $('#formAsignacion').on('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const isEdit = $('#id_empleado_capacitacion').val() !== '';
        
        $.ajax({
            url: isEdit ? '/admin-th/empleados-capacitaciones/actualizar' : '/admin-th/empleados-capacitaciones/crear',
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
    const capacitacion = $('#filtroCapacitacion').val();
    const estado = $('#filtroEstado').val();
    
    $('#tablaAsignaciones').DataTable().columns().every(function() {
        const column = this;
        let searchTerm = '';
        
        if (column.index() === 1 && empleado) { // Columna empleado
            searchTerm = empleado;
        } else if (column.index() === 2 && capacitacion) { // Columna capacitación
            searchTerm = capacitacion;
        } else if (column.index() === 3 && estado !== '') { // Columna asistió
            searchTerm = estado === '1' ? 'Sí' : 'No';
        }
        
        column.search(searchTerm).draw();
    });
}

function limpiarFiltros() {
    $('#filtroEmpleado, #filtroCapacitacion, #filtroEstado').val('');
    $('#tablaAsignaciones').DataTable().search('').columns().search('').draw();
}

function verAsignacion(id) {
    $.ajax({
        url: `/admin-th/empleados-capacitaciones/${id}`,
        type: 'GET',
        success: function(response) {
            if (response.success) {
                $('#detallesAsignacion').html(response.html);
                $('#modalVerAsignacion').modal('show');
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

function editarAsignacion(id) {
    $.ajax({
        url: `/admin-th/empleados-capacitaciones/${id}/editar`,
        type: 'GET',
        success: function(response) {
            if (response.success) {
                $('#id_empleado_capacitacion').val(response.asignacion.id_empleado_capacitacion);
                $('#id_empleado').val(response.asignacion.id_empleado);
                $('#id_capacitacion').val(response.asignacion.id_capacitacion);
                $('#asistio').val(response.asignacion.asistio);
                $('#aprobo').val(response.asignacion.aprobo);
                $('#certificado_url').val(response.asignacion.certificado_url);
                
                $('#modalTitle').text('Editar Asignación');
                $('#modalAsignacion').modal('show');
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

function verCertificado(id) {
    $.ajax({
        url: `/admin-th/empleados-capacitaciones/${id}/certificado`,
        type: 'GET',
        success: function(response) {
            if (response.success) {
                $('#contenidoCertificado').html(response.html);
                $('#modalCertificado').modal('show');
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

function eliminarAsignacion(id) {
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
                url: `/admin-th/empleados-capacitaciones/${id}/eliminar`,
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