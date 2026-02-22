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

<!-- Modal Editar Rol -->
<div class="modal fade" id="modalEditarRol" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Editar Rol</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formEditarRol">
                <div class="modal-body">
                    <input type="hidden" id="editRolId" name="id_rol">
                    <div class="mb-3">
                        <label class="form-label">Nombre del Rol *</label>
                        <input type="text" class="form-control" id="editNombreRol" name="nombre_rol" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Descripción</label>
                        <textarea class="form-control" id="editDescripcionRol" name="descripcion" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nivel de Acceso *</label>
                        <input type="number" class="form-control" id="editNivelAcceso" name="nivel_acceso" min="1" max="99" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" onclick="guardarEdicionRol()">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Ver Usuarios del Rol -->
<div class="modal fade" id="modalUsuariosRol" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Usuarios del Rol: <span id="nombreRolUsuarios"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-striped" id="tablaUsuariosRol">
                        <thead>
                            <tr>
                                <th>Cédula</th>
                                <th>Email</th>
                                <th>Nombres</th>
                                <th>Apellidos</th>
                                <th>Estado</th>
                                <th>Fecha Registro</th>
                            </tr>
                        </thead>
                        <tbody id="tbodyUsuariosRol">
                            <!-- Los usuarios se cargarán dinámicamente -->
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
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
        crearNuevoRol();
    });
});

function crearNuevoRol() {
    const formData = new FormData(document.getElementById('formNuevoRol'));
    
    // Agregar nivel de acceso por defecto
    formData.append('nivel_acceso', '50');
    
    fetch('<?= base_url('super-admin/crear-rol') ?>', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Éxito',
                text: data.message
            }).then(() => {
                $('#modalNuevoRol').modal('hide');
                document.getElementById('formNuevoRol').reset();
                location.reload();
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: data.message || 'Error al crear el rol'
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Error de conexión'
        });
    });
}

function editarRol(idRol) {
    // Obtener datos del rol
    fetch(`<?= base_url('super-admin/obtener-rol') ?>/${idRol}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const rol = data.rol;
                
                // Llenar el modal de edición
                document.getElementById('editRolId').value = rol.id_rol;
                document.getElementById('editNombreRol').value = rol.nombre_rol;
                document.getElementById('editDescripcionRol').value = rol.descripcion || '';
                document.getElementById('editNivelAcceso').value = rol.nivel_acceso || 50;
                
                // Mostrar modal
                $('#modalEditarRol').modal('show');
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: data.message || 'Error al obtener datos del rol'
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Error de conexión'
            });
        });
}

function guardarEdicionRol() {
    const formData = new FormData(document.getElementById('formEditarRol'));
    
    fetch('<?= base_url('super-admin/editar-rol') ?>', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Éxito',
                text: data.message
            }).then(() => {
                $('#modalEditarRol').modal('hide');
                location.reload();
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: data.message || 'Error al actualizar el rol'
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Error de conexión'
        });
    });
}

function verUsuariosRol(idRol) {
    // Obtener usuarios del rol
    fetch(`<?= base_url('super-admin/usuarios/obtener') ?>`, {
        method: 'GET'
    })
    .then(response => response.json())
    .then(data => {
        if (data.data) {
            const usuarios = data.data.filter(usuario => usuario.id_rol == idRol);
            const rol = data.data.find(usuario => usuario.id_rol == idRol);
            
            // Llenar la tabla de usuarios
            const tbody = document.getElementById('tbodyUsuariosRol');
            tbody.innerHTML = '';
            
            if (usuarios.length > 0) {
                usuarios.forEach(usuario => {
                    const row = `
                        <tr>
                            <td>${usuario.cedula}</td>
                            <td>${usuario.email}</td>
                            <td>${usuario.nombres || 'N/A'}</td>
                            <td>${usuario.apellidos || 'N/A'}</td>
                            <td>
                                <span class="badge ${usuario.activo === 'Activo' ? 'bg-success' : 'bg-danger'}">
                                    ${usuario.activo}
                                </span>
                            </td>
                            <td>${usuario.fecha_registro}</td>
                        </tr>
                    `;
                    tbody.innerHTML += row;
                });
                
                document.getElementById('nombreRolUsuarios').textContent = rol ? rol.nombre_rol : 'N/A';
                $('#modalUsuariosRol').modal('show');
            } else {
                Swal.fire({
                    icon: 'info',
                    title: 'Información',
                    text: 'Este rol no tiene usuarios asignados'
                });
            }
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Error al obtener usuarios del rol'
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Error de conexión'
        });
    });
}

function eliminarRol(idRol) {
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
            // En lugar de eliminar, deshabilitar
            const formData = new FormData();
            formData.append('id_rol', idRol);
            
            fetch('<?= base_url('super-admin/deshabilitar-rol') ?>', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Éxito',
                        text: data.message
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message || 'Error al deshabilitar el rol'
                    });
                }
            });
        }
    });
}
</script>

<?= $this->endSection() ?> 