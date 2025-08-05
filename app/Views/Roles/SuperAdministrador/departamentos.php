<?php $sidebar = 'sidebar_super_admin'; ?>
<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>

<div class="page-wrapper">
    <div class="page-content">
        <!-- Header -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <h4 class="mb-0"><i class="bi bi-building"></i> Gestión de Departamentos</h4>
                    <div class="page-title-right">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalNuevoDepartamento">
                            <i class="bi bi-plus"></i> Nuevo Departamento
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row">
            <div class="col-xl-3 col-lg-6 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="me-3">
                                <span class="round-8 bg-primary rounded-circle me-2 d-inline-block"></span>
                                <div class="stat-digit d-flex">
                                    <h5 class="font-weight-semibold mb-0"><?= $estadisticas['total_departamentos'] ?? 0 ?></h5>
                                </div>
                                <div class="stat-digit d-flex">
                                    <h5 class="font-weight-semibold mb-0">Departamentos</h5>
                                </div>
                            </div>
                            <div class="ms-auto">
                                <i class="bi bi-building text-primary" style="font-size: 2rem;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="me-3">
                                <span class="round-8 bg-success rounded-circle me-2 d-inline-block"></span>
                                <div class="stat-digit d-flex">
                                    <h5 class="font-weight-semibold mb-0"><?= $estadisticas['total_empleados'] ?? 0 ?></h5>
                                </div>
                                <div class="stat-digit d-flex">
                                    <h5 class="font-weight-semibold mb-0">Empleados</h5>
                                </div>
                            </div>
                            <div class="ms-auto">
                                <i class="bi bi-people text-success" style="font-size: 2rem;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="me-3">
                                <span class="round-8 bg-info rounded-circle me-2 d-inline-block"></span>
                                <div class="stat-digit d-flex">
                                    <h5 class="font-weight-semibold mb-0"><?= $estadisticas['departamentos_con_jefe'] ?? 0 ?></h5>
                                </div>
                                <div class="stat-digit d-flex">
                                    <h5 class="font-weight-semibold mb-0">Con Jefe</h5>
                                </div>
                            </div>
                            <div class="ms-auto">
                                <i class="bi bi-person-badge text-info" style="font-size: 2rem;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="me-3">
                                <span class="round-8 bg-warning rounded-circle me-2 d-inline-block"></span>
                                <div class="stat-digit d-flex">
                                    <h5 class="font-weight-semibold mb-0"><?= $estadisticas['departamentos_sin_empleados'] ?? 0 ?></h5>
                                </div>
                                <div class="stat-digit d-flex">
                                    <h5 class="font-weight-semibold mb-0">Sin Empleados</h5>
                                </div>
                            </div>
                            <div class="ms-auto">
                                <i class="bi bi-exclamation-triangle text-warning" style="font-size: 2rem;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Departamentos Table -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Lista de Departamentos</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover" id="tablaDepartamentos">
                                <thead>
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
                                    <?php foreach ($departamentos as $departamento): ?>
                                    <tr>
                                        <td><?= $departamento['id_departamento'] ?></td>
                                        <td>
                                            <span class="badge bg-primary"><?= $departamento['nombre'] ?></span>
                                        </td>
                                        <td><?= $departamento['descripcion'] ?></td>
                                        <td>
                                            <?php if ($departamento['jefe_nombre']): ?>
                                                <span class="badge bg-info"><?= $departamento['jefe_nombre'] ?></span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary">Sin asignar</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <span class="badge bg-success"><?= $departamento['total_empleados'] ?> empleados</span>
                                        </td>
                                        <td><?= date('d/m/Y', strtotime($departamento['created_at'])) ?></td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <button type="button" class="btn btn-sm btn-outline-primary" onclick="editarDepartamento(<?= $departamento['id_departamento'] ?>)">
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline-info" onclick="verEmpleadosDepartamento(<?= $departamento['id_departamento'] ?>)">
                                                    <i class="bi bi-people"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline-danger" onclick="eliminarDepartamento(<?= $departamento['id_departamento'] ?>)" <?= $departamento['total_empleados'] > 0 ? 'disabled' : '' ?>>
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

<!-- Modal Nuevo Departamento -->
<div class="modal fade" id="modalNuevoDepartamento" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Nuevo Departamento</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formNuevoDepartamento">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nombre del Departamento *</label>
                        <input type="text" class="form-control" name="nombre" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Descripción</label>
                        <textarea class="form-control" name="descripcion" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Jefe del Departamento</label>
                        <select class="form-select" name="id_jefe">
                            <option value="">Seleccionar empleado</option>
                            <?php foreach ($empleados as $empleado): ?>
                            <option value="<?= $empleado['id_empleado'] ?>">
                                <?= $empleado['nombres'] ?> <?= $empleado['apellidos'] ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Crear Departamento</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#tablaDepartamentos').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json'
        }
    });
    
    $('#formNuevoDepartamento').on('submit', function(e) {
        e.preventDefault();
        // Aquí se enviaría la creación del departamento
        alert('Departamento creado correctamente');
        $('#modalNuevoDepartamento').modal('hide');
    });
});

function editarDepartamento(idDepartamento) {
    // Aquí se cargaría el formulario de edición
    alert('Editando departamento ' + idDepartamento);
}

function verEmpleadosDepartamento(idDepartamento) {
    // Aquí se mostrarían los empleados del departamento
    alert('Empleados del departamento ' + idDepartamento);
}

function eliminarDepartamento(idDepartamento) {
    if (confirm('¿Está seguro de eliminar este departamento?')) {
        // Aquí se eliminaría el departamento
        alert('Departamento eliminado correctamente');
    }
}
</script>

<?= $this->endSection() ?> 