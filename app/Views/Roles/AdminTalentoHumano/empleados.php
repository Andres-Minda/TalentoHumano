<?php $sidebar = 'sidebar_admin_th'; ?>
<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4><i class="bi bi-people"></i> Gestión de Empleados</h4>
                    <div>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalNuevoEmpleado">
                            <i class="bi bi-plus-circle"></i> Nuevo Empleado
                        </button>
                        <a href="<?= base_url('admin-th/dashboard') ?>" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Volver al Dashboard
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Filtros -->
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <input type="text" id="buscarEmpleado" class="form-control" placeholder="Buscar empleado...">
                        </div>
                        <div class="col-md-2">
                            <select id="filtroDepartamento" class="form-select">
                                <option value="">Todos los departamentos</option>
                                <?php foreach ($departamentos ?? [] as $depto): ?>
                                    <option value="<?= $depto['id_departamento'] ?>"><?= $depto['nombre'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select id="filtroTipo" class="form-select">
                                <option value="">Todos los tipos</option>
                                <option value="Docente">Docente</option>
                                <option value="Administrativo">Administrativo</option>
                                <option value="Directivo">Directivo</option>
                                <option value="Auxiliar">Auxiliar</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select id="filtroEstado" class="form-select">
                                <option value="">Todos los estados</option>
                                <option value="Activo">Activo</option>
                                <option value="Inactivo">Inactivo</option>
                                <option value="Vacaciones">Vacaciones</option>
                                <option value="Licencia">Licencia</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <button type="button" class="btn btn-outline-secondary" onclick="limpiarFiltros()">
                                <i class="bi bi-arrow-clockwise"></i> Limpiar Filtros
                            </button>
                        </div>
                    </div>

                    <!-- Estadísticas -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card bg-primary text-white">
                                <div class="card-body">
                                    <h5 class="card-title">Total Empleados</h5>
                                    <h3><?= $estadisticas->total_empleados ?? 0 ?></h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-success text-white">
                                <div class="card-body">
                                    <h5 class="card-title">Activos</h5>
                                    <h3><?= $estadisticas->empleados_activos ?? 0 ?></h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-info text-white">
                                <div class="card-body">
                                    <h5 class="card-title">Docentes</h5>
                                    <h3><?= $estadisticas->total_docentes ?? 0 ?></h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-warning text-dark">
                                <div class="card-body">
                                    <h5 class="card-title">Administrativos</h5>
                                    <h3><?= $estadisticas->total_administrativos ?? 0 ?></h3>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tabla de empleados -->
                    <div class="table-responsive">
                        <table class="table table-striped table-hover" id="tablaEmpleados">
                            <thead class="table-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>Foto</th>
                                    <th>Nombres</th>
                                    <th>Email</th>
                                    <th>Departamento</th>
                                    <th>Puesto</th>
                                    <th>Tipo</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($empleados ?? [] as $empleado): ?>
                                <tr>
                                    <td><?= $empleado['id_empleado'] ?></td>
                                    <td>
                                        <img src="<?= $empleado['foto_url'] ? base_url($empleado['foto_url']) : base_url('sistema/assets/images/profile/user-1.jpg') ?>" 
                                             class="rounded-circle" width="40" height="40" alt="Foto">
                                    </td>
                                    <td><?= $empleado['nombres'] . ' ' . $empleado['apellidos'] ?></td>
                                    <td><?= $empleado['email'] ?></td>
                                    <td><?= $empleado['departamento_nombre'] ?? 'Sin asignar' ?></td>
                                    <td><?= $empleado['puesto_nombre'] ?? 'Sin asignar' ?></td>
                                    <td>
                                        <span class="badge bg-<?= $empleado['tipo_empleado'] == 'Docente' ? 'primary' : ($empleado['tipo_empleado'] == 'Administrativo' ? 'success' : 'warning') ?>">
                                            <?= $empleado['tipo_empleado'] ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-<?= $empleado['estado'] == 'Activo' ? 'success' : 'danger' ?>">
                                            <?= $empleado['estado'] ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button type="button" class="btn btn-sm btn-outline-primary" onclick="verEmpleado(<?= $empleado['id_empleado'] ?>)">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-warning" onclick="editarEmpleado(<?= $empleado['id_empleado'] ?>)">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-danger" onclick="eliminarEmpleado(<?= $empleado['id_empleado'] ?>)">
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

<!-- Modal Nuevo Empleado -->
<div class="modal fade" id="modalNuevoEmpleado" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Nuevo Empleado</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formNuevoEmpleado">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Nombres *</label>
                                <input type="text" class="form-control" name="nombres" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Apellidos *</label>
                                <input type="text" class="form-control" name="apellidos" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Email *</label>
                                <input type="email" class="form-control" name="email" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Cédula *</label>
                                <input type="text" class="form-control" name="cedula" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Teléfono</label>
                                <input type="text" class="form-control" name="telefono">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Fecha de Nacimiento</label>
                                <input type="date" class="form-control" name="fecha_nacimiento">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Género</label>
                                <select class="form-select" name="genero">
                                    <option value="">Seleccionar</option>
                                    <option value="Masculino">Masculino</option>
                                    <option value="Femenino">Femenino</option>
                                    <option value="Otro">Otro</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Estado Civil</label>
                                <select class="form-select" name="estado_civil">
                                    <option value="">Seleccionar</option>
                                    <option value="Soltero">Soltero</option>
                                    <option value="Casado">Casado</option>
                                    <option value="Divorciado">Divorciado</option>
                                    <option value="Viudo">Viudo</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Tipo de Empleado *</label>
                                <select class="form-select" name="tipo_empleado" required>
                                    <option value="">Seleccionar</option>
                                    <option value="Docente">Docente</option>
                                    <option value="Administrativo">Administrativo</option>
                                    <option value="Directivo">Directivo</option>
                                    <option value="Auxiliar">Auxiliar</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Departamento</label>
                                <select class="form-select" name="id_departamento">
                                    <option value="">Seleccionar</option>
                                    <?php foreach ($departamentos ?? [] as $depto): ?>
                                        <option value="<?= $depto['id_departamento'] ?>"><?= $depto['nombre'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Puesto</label>
                                <select class="form-select" name="id_puesto">
                                    <option value="">Seleccionar</option>
                                    <?php foreach ($puestos ?? [] as $puesto): ?>
                                        <option value="<?= $puesto['id_puesto'] ?>"><?= $puesto['nombre'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Dirección</label>
                        <textarea class="form-control" name="direccion" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar Empleado</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(document).ready(function() {
    // Inicializar DataTable
    $('#tablaEmpleados').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json'
        },
        pageLength: 10,
        order: [[2, 'asc']]
    });

    // Búsqueda en tiempo real
    $('#buscarEmpleado').on('keyup', function() {
        $('#tablaEmpleados').DataTable().search(this.value).draw();
    });

    // Filtros
    $('#filtroDepartamento, #filtroTipo, #filtroEstado').on('change', function() {
        aplicarFiltros();
    });

    // Formulario nuevo empleado
    $('#formNuevoEmpleado').on('submit', function(e) {
        e.preventDefault();
        guardarEmpleado();
    });
});

function aplicarFiltros() {
    var departamento = $('#filtroDepartamento').val();
    var tipo = $('#filtroTipo').val();
    var estado = $('#filtroEstado').val();
    
    var table = $('#tablaEmpleados').DataTable();
    
    // Limpiar filtros anteriores
    table.draw();
    
    // Aplicar filtros personalizados
    $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
        var deptoMatch = !departamento || data[4] === departamento;
        var tipoMatch = !tipo || data[6] === tipo;
        var estadoMatch = !estado || data[7] === estado;
        
        return deptoMatch && tipoMatch && estadoMatch;
    });
    
    table.draw();
}

function limpiarFiltros() {
    $('#filtroDepartamento, #filtroTipo, #filtroEstado').val('');
    $('#buscarEmpleado').val('');
    $('#tablaEmpleados').DataTable().search('').draw();
    $.fn.dataTable.ext.search.pop();
    $('#tablaEmpleados').DataTable().draw();
}

function guardarEmpleado() {
    var formData = new FormData($('#formNuevoEmpleado')[0]);
    
    $.ajax({
        url: '<?= base_url('admin-th/empleados/crear') ?>',
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
                text: 'Error al guardar el empleado'
            });
        }
    });
}

function verEmpleado(id) {
    window.location.href = '<?= base_url('admin-th/empleados/ver/') ?>' + id;
}

function editarEmpleado(id) {
    window.location.href = '<?= base_url('admin-th/empleados/editar/') ?>' + id;
}

function eliminarEmpleado(id) {
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
                url: '<?= base_url('admin-th/empleados/eliminar/') ?>' + id,
                type: 'POST',
                success: function(response) {
                    if (response.success) {
                        Swal.fire('Eliminado', response.message, 'success').then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire('Error', response.message, 'error');
                    }
                }
            });
        }
    });
}
</script>
<?= $this->endSection() ?> 