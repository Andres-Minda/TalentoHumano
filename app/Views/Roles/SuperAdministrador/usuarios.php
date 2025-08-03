<?php
$sidebar = 'sidebar_super_admin';
?>

<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <!-- Page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">Gestión de Usuarios</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="<?= base_url('super-admin/dashboard') ?>">Inicio</a></li>
                        <li class="breadcrumb-item active">Usuarios</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row">
        <div class="col-xl-3 col-md-6">
            <div class="card mini-stats-wid">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-grow-1">
                            <p class="text-muted fw-medium">Total Usuarios</p>
                            <h4 class="mb-0"><?= $totalUsuarios ?? 0 ?></h4>
                        </div>
                        <div class="flex-shrink-0 align-self-center">
                            <div class="mini-stat-icon avatar-sm rounded-circle bg-primary align-self-center">
                                <span class="avatar-title">
                                    <i class="ti ti-users font-size-24"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card mini-stats-wid">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-grow-1">
                            <p class="text-muted fw-medium">Usuarios Activos</p>
                            <h4 class="mb-0"><?= $usuariosActivos ?? 0 ?></h4>
                        </div>
                        <div class="flex-shrink-0 align-self-center">
                            <div class="mini-stat-icon avatar-sm rounded-circle bg-success align-self-center">
                                <span class="avatar-title">
                                    <i class="ti ti-user-check font-size-24"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card mini-stats-wid">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-grow-1">
                            <p class="text-muted fw-medium">Usuarios Inactivos</p>
                            <h4 class="mb-0"><?= $usuariosInactivos ?? 0 ?></h4>
                        </div>
                        <div class="flex-shrink-0 align-self-center">
                            <div class="mini-stat-icon avatar-sm rounded-circle bg-warning align-self-center">
                                <span class="avatar-title">
                                    <i class="ti ti-user-x font-size-24"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card mini-stats-wid">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-grow-1">
                            <p class="text-muted fw-medium">Con Último Acceso</p>
                            <h4 class="mb-0"><?= $usuariosConAcceso ?? 0 ?></h4>
                        </div>
                        <div class="flex-shrink-0 align-self-center">
                            <div class="mini-stat-icon avatar-sm rounded-circle bg-info align-self-center">
                                <span class="avatar-title">
                                    <i class="ti ti-login font-size-24"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Users Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="card-title">Lista de Usuarios</h4>
                        <div>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">
                                <i class="ti ti-plus me-1"></i>Nuevo Usuario
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Search and Filters -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="input-group">
                                <input type="text" class="form-control" id="searchUser" placeholder="Buscar usuarios...">
                                <button class="btn btn-outline-secondary" type="button" id="searchBtn">
                                    <i class="ti ti-search"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <select class="form-select" id="filterRol">
                                <option value="">Todos los roles</option>
                                <?php if (isset($roles) && !empty($roles)): ?>
                                    <?php foreach ($roles as $rol): ?>
                                        <option value="<?= $rol['id_rol'] ?>"><?= esc($rol['nombre_rol']) ?></option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select class="form-select" id="filterEstado">
                                <option value="">Todos los estados</option>
                                <option value="1">Activo</option>
                                <option value="0">Inactivo</option>
                            </select>
                        </div>
                    </div>

                    <!-- Users Table -->
                    <div class="table-responsive">
                        <table class="table table-centered table-nowrap mb-0" id="usersTable">
                            <thead>
                                <tr>
                                    <th>Usuario</th>
                                    <th>Rol</th>
                                    <th>Empleado</th>
                                    <th>Estado</th>
                                    <th>Último Acceso</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (isset($usuarios) && !empty($usuarios)): ?>
                                    <?php foreach ($usuarios as $usuario): ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm bg-light rounded-circle me-3">
                                                    <span class="avatar-title text-primary">
                                                        <i class="ti ti-user"></i>
                                                    </span>
                                                </div>
                                                <div>
                                                    <h6 class="mb-0"><?= esc($usuario['email']) ?></h6>
                                                    <small class="text-muted"><?= esc($usuario['cedula']) ?></small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-primary"><?= esc($usuario['nombre_rol']) ?></span>
                                        </td>
                                        <td>
                                            <?php if (!empty($usuario['nombres'])): ?>
                                                <span><?= esc($usuario['nombres'] . ' ' . $usuario['apellidos']) ?></span>
                                                <br><small class="text-muted"><?= esc($usuario['tipo_empleado']) ?></small>
                                            <?php else: ?>
                                                <span class="text-muted">No asignado</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if ($usuario['activo']): ?>
                                                <span class="badge bg-success">Activo</span>
                                            <?php else: ?>
                                                <span class="badge bg-danger">Inactivo</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?= $usuario['last_login'] ? date('d/m/Y H:i', strtotime($usuario['last_login'])) : 'Nunca' ?>
                                        </td>
                                        <td>
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                    <i class="ti ti-dots-vertical"></i>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li><a class="dropdown-item" href="#" onclick="editUser(<?= $usuario['id_usuario'] ?>)">
                                                        <i class="ti ti-edit me-2"></i>Editar
                                                    </a></li>
                                                    <li><a class="dropdown-item" href="#" onclick="viewUser(<?= $usuario['id_usuario'] ?>)">
                                                        <i class="ti ti-eye me-2"></i>Ver
                                                    </a></li>
                                                    <li><hr class="dropdown-divider"></li>
                                                    <?php if ($usuario['activo']): ?>
                                                        <li><a class="dropdown-item text-warning" href="#" onclick="toggleUserStatus(<?= $usuario['id_usuario'] ?>, 0)">
                                                            <i class="ti ti-user-x me-2"></i>Desactivar
                                                        </a></li>
                                                    <?php else: ?>
                                                        <li><a class="dropdown-item text-success" href="#" onclick="toggleUserStatus(<?= $usuario['id_usuario'] ?>, 1)">
                                                            <i class="ti ti-user-check me-2"></i>Activar
                                                        </a></li>
                                                    <?php endif; ?>
                                                    <li><a class="dropdown-item text-danger" href="#" onclick="deleteUser(<?= $usuario['id_usuario'] ?>)">
                                                        <i class="ti ti-trash me-2"></i>Eliminar
                                                    </a></li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="6" class="text-center">No hay usuarios registrados</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add User Modal -->
<div class="modal fade" id="addUserModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Nuevo Usuario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="addUserForm">
                <div class="modal-body">
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
                                <label for="password" class="form-label">Contraseña *</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="confirm_password" class="form-label">Confirmar Contraseña *</label>
                                <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="id_rol" class="form-label">Rol *</label>
                                <select class="form-select" id="id_rol" name="id_rol" required>
                                    <option value="">Seleccionar rol</option>
                                    <?php if (isset($roles) && !empty($roles)): ?>
                                        <?php foreach ($roles as $rol): ?>
                                            <option value="<?= $rol['id_rol'] ?>"><?= esc($rol['nombre_rol']) ?></option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="activo" class="form-label">Estado</label>
                                <select class="form-select" id="activo" name="activo">
                                    <option value="1">Activo</option>
                                    <option value="0">Inactivo</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar Usuario</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Search functionality
    const searchInput = document.getElementById('searchUser');
    const searchBtn = document.getElementById('searchBtn');
    const filterRol = document.getElementById('filterRol');
    const filterEstado = document.getElementById('filterEstado');

    function performSearch() {
        const searchTerm = searchInput.value.toLowerCase();
        const selectedRol = filterRol.value;
        const selectedEstado = filterEstado.value;
        
        const rows = document.querySelectorAll('#usersTable tbody tr');
        
        rows.forEach(row => {
            const email = row.querySelector('td:nth-child(1) h6').textContent.toLowerCase();
            const cedula = row.querySelector('td:nth-child(1) small').textContent.toLowerCase();
            const rol = row.querySelector('td:nth-child(2) .badge').textContent.toLowerCase();
            const estado = row.querySelector('td:nth-child(4) .badge').textContent.toLowerCase();
            
            let showRow = true;
            
            // Search filter
            if (searchTerm && !email.includes(searchTerm) && !cedula.includes(searchTerm)) {
                showRow = false;
            }
            
            // Role filter
            if (selectedRol && !rol.includes(selectedRol)) {
                showRow = false;
            }
            
            // Status filter
            if (selectedEstado) {
                const isActive = estado.includes('activo');
                if (selectedEstado === '1' && !isActive) showRow = false;
                if (selectedEstado === '0' && isActive) showRow = false;
            }
            
            row.style.display = showRow ? '' : 'none';
        });
    }

    searchBtn.addEventListener('click', performSearch);
    searchInput.addEventListener('keyup', performSearch);
    filterRol.addEventListener('change', performSearch);
    filterEstado.addEventListener('change', performSearch);

    // Add user form
    const addUserForm = document.getElementById('addUserForm');
    addUserForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        fetch('<?= base_url('super-admin/usuarios/crear') ?>', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Éxito',
                    text: 'Usuario creado correctamente'
                }).then(() => {
                    location.reload();
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: data.message || 'Error al crear usuario'
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
    });
});

function editUser(userId) {
    // Implementar edición de usuario
    Swal.fire({
        icon: 'info',
        title: 'Función en desarrollo',
        text: 'La edición de usuarios estará disponible próximamente'
    });
}

function viewUser(userId) {
    // Implementar vista de usuario
    Swal.fire({
        icon: 'info',
        title: 'Función en desarrollo',
        text: 'La vista de usuarios estará disponible próximamente'
    });
}

function toggleUserStatus(userId, status) {
    const action = status ? 'activar' : 'desactivar';
    
    Swal.fire({
        title: '¿Estás seguro?',
        text: `¿Deseas ${action} este usuario?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, continuar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch('<?= base_url('super-admin/usuarios/toggle-status') ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    user_id: userId,
                    status: status
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Éxito',
                        text: `Usuario ${action}do correctamente`
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message || 'Error al cambiar estado'
                    });
                }
            });
        }
    });
}

function deleteUser(userId) {
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
            fetch('<?= base_url('super-admin/usuarios/eliminar') ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    user_id: userId
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Eliminado',
                        text: 'Usuario eliminado correctamente'
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message || 'Error al eliminar usuario'
                    });
                }
            });
        }
    });
}
</script>
<?= $this->endSection() ?> 