<?php $sidebar = 'sidebar_super_admin'; ?>
<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>

<div class="page-wrapper">
    <div class="page-content">
        <!-- Header -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <h4 class="mb-0"><i class="bi bi-shield-check"></i> Gestión de Roles</h4>
                    <div class="page-title-right">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalNuevoRol">
                            <i class="bi bi-plus"></i> Nuevo Rol
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
                                    <h5 class="font-weight-semibold mb-0"><?= $estadisticas['total_roles'] ?? 0 ?></h5>
                                </div>
                                <div class="stat-digit d-flex">
                                    <h5 class="font-weight-semibold mb-0">Roles</h5>
                                </div>
                            </div>
                            <div class="ms-auto">
                                <i class="bi bi-shield-check text-primary" style="font-size: 2rem;"></i>
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
                                    <h5 class="font-weight-semibold mb-0"><?= $estadisticas['usuarios_activos'] ?? 0 ?></h5>
                                </div>
                                <div class="stat-digit d-flex">
                                    <h5 class="font-weight-semibold mb-0">Usuarios Activos</h5>
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
                                    <h5 class="font-weight-semibold mb-0"><?= $estadisticas['rol_mas_usado'] ?? 'N/A' ?></h5>
                                </div>
                                <div class="stat-digit d-flex">
                                    <h5 class="font-weight-semibold mb-0">Rol Más Usado</h5>
                                </div>
                            </div>
                            <div class="ms-auto">
                                <i class="bi bi-star text-info" style="font-size: 2rem;"></i>
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
                                    <h5 class="font-weight-semibold mb-0"><?= $estadisticas['roles_sin_usuarios'] ?? 0 ?></h5>
                                </div>
                                <div class="stat-digit d-flex">
                                    <h5 class="font-weight-semibold mb-0">Sin Usuarios</h5>
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

        <!-- Roles Table -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Lista de Roles</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover" id="tablaRoles">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nombre</th>
                                        <th>Descripción</th>
                                        <th>Usuarios</th>
                                        <th>Fecha Creación</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($roles as $rol): ?>
                                    <tr>
                                        <td><?= $rol['id_rol'] ?></td>
                                        <td>
                                            <span class="badge bg-primary"><?= $rol['nombre_rol'] ?></span>
                                        </td>
                                        <td><?= $rol['descripcion'] ?></td>
                                        <td>
                                            <span class="badge bg-info"><?= $rol['total_usuarios'] ?> usuarios</span>
                                        </td>
                                        <td><?= date('d/m/Y', strtotime($rol['created_at'])) ?></td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <button type="button" class="btn btn-sm btn-outline-primary" onclick="editarRol(<?= $rol['id_rol'] ?>)">
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline-info" onclick="verUsuariosRol(<?= $rol['id_rol'] ?>)">
                                                    <i class="bi bi-people"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline-danger" onclick="eliminarRol(<?= $rol['id_rol'] ?>)" <?= $rol['total_usuarios'] > 0 ? 'disabled' : '' ?>>
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

<!-- Modal Nuevo Rol -->
<div class="modal fade" id="modalNuevoRol" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Nuevo Rol</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formNuevoRol">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nombre del Rol *</label>
                        <input type="text" class="form-control" name="nombre_rol" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Descripción</label>
                        <textarea class="form-control" name="descripcion" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Crear Rol</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#tablaRoles').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json'
        }
    });
    
    $('#formNuevoRol').on('submit', function(e) {
        e.preventDefault();
        // Aquí se enviaría la creación del rol
        alert('Rol creado correctamente');
        $('#modalNuevoRol').modal('hide');
    });
});

function editarRol(idRol) {
    // Aquí se cargaría el formulario de edición
    alert('Editando rol ' + idRol);
}

function verUsuariosRol(idRol) {
    // Aquí se mostrarían los usuarios del rol
    alert('Usuarios del rol ' + idRol);
}

function eliminarRol(idRol) {
    if (confirm('¿Está seguro de eliminar este rol?')) {
        // Aquí se eliminaría el rol
        alert('Rol eliminado correctamente');
    }
}
</script>

<?= $this->endSection() ?> 