<?php
$sidebar = 'sidebar_docente';
?>

<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <!-- Page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">Mi Perfil - Docente</h4>
                <div class="page-title-right">
                    <button class="btn btn-primary" onclick="guardarPerfil()">
                        <i class="ti ti-device-floppy me-1"></i>Guardar Cambios
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-4">
            <!-- Información del Perfil -->
            <div class="card">
                <div class="card-body text-center">
                    <div class="position-relative mb-3">
                        <?php 
                        $foto_perfil = session('foto_perfil');
                        if ($foto_perfil && file_exists(FCPATH . 'sistema/assets/images/profile/' . $foto_perfil)) {
                            $foto_url = base_url('sistema/assets/images/profile/' . $foto_perfil);
                        } else {
                            $foto_url = base_url('sistema/assets/images/profile/user-1.jpg');
                        }
                        ?>
                        <img src="<?= $foto_url ?>" 
                             alt="Foto de Perfil" 
                             class="rounded-circle" 
                             width="120" 
                             height="120"
                             style="object-fit: cover;">
                        <button class="btn btn-sm btn-primary position-absolute bottom-0 end-0" 
                                onclick="document.getElementById('fotoInput').click()">
                            <i class="ti ti-camera"></i>
                        </button>
                        <input type="file" id="fotoInput" accept="image/*" style="display: none;" onchange="cambiarFoto(this)">
                    </div>
                    <h5 class="card-title"><?= session('nombres') ?> <?= session('apellidos') ?></h5>
                    <p class="text-muted">Docente</p>
                    <div class="d-flex justify-content-center gap-2">
                        <span class="badge bg-info">Docente</span>
                        <span class="badge bg-info"><?= session('email') ?></span>
                    </div>
                </div>
            </div>

            <!-- Información de Contacto -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="ti ti-user me-2"></i>Información de Contacto
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <i class="ti ti-mail text-primary me-2"></i>
                        <span><?= session('email') ?></span>
                    </div>
                    <div class="d-flex align-items-center mb-3">
                        <i class="ti ti-phone text-success me-2"></i>
                        <span><?= session('telefono') ?? 'No registrado' ?></span>
                    </div>
                    <div class="d-flex align-items-center">
                        <i class="ti ti-map-pin text-warning me-2"></i>
                        <span><?= session('direccion') ?? 'No registrada' ?></span>
                    </div>
                </div>
            </div>

            <!-- Estadísticas del Docente -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="ti ti-chart-line me-2"></i>Actividad Reciente
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="flex-shrink-0">
                            <div class="avatar-sm rounded-circle bg-primary">
                                <span class="avatar-title text-white">
                                    <i class="ti ti-calendar-check"></i>
                                </span>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1">Asistencias</h6>
                            <small class="text-muted">Este mes: <?= $asistenciasMes ?? 0 ?></small>
                        </div>
                    </div>
                    <div class="d-flex align-items-center mb-3">
                        <div class="flex-shrink-0">
                            <div class="avatar-sm rounded-circle bg-success">
                                <span class="avatar-title text-white">
                                    <i class="ti ti-mortarboard"></i>
                                </span>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1">Capacitaciones</h6>
                            <small class="text-muted">Completadas: <?= $capacitacionesCompletadas ?? 0 ?></small>
                        </div>
                    </div>
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar-sm rounded-circle bg-warning">
                                <span class="avatar-title text-white">
                                    <i class="ti ti-clipboard-data"></i>
                                </span>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1">Evaluaciones</h6>
                            <small class="text-muted">Pendientes: <?= $evaluacionesPendientes ?? 0 ?></small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <!-- Formulario de Información Personal -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="ti ti-user-edit me-2"></i>Información Personal
                    </h5>
                </div>
                <div class="card-body">
                    <form id="perfilForm">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Nombres</label>
                                    <input type="text" class="form-control" id="nombres" name="nombres" value="<?= session('nombres') ?>" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Apellidos</label>
                                    <input type="text" class="form-control" id="apellidos" name="apellidos" value="<?= session('apellidos') ?>" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" value="<?= session('email') ?>" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Teléfono</label>
                                    <input type="tel" class="form-control" id="telefono" name="telefono" value="<?= session('telefono') ?? '' ?>">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Fecha de Nacimiento</label>
                                    <input type="date" class="form-control" id="fecha_nacimiento" name="fecha_nacimiento" value="<?= session('fecha_nacimiento') ?? '' ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Género</label>
                                    <select class="form-select" id="genero" name="genero">
                                        <option value="">Seleccionar</option>
                                        <option value="Masculino" <?= (session('genero') == 'Masculino') ? 'selected' : '' ?>>Masculino</option>
                                        <option value="Femenino" <?= (session('genero') == 'Femenino') ? 'selected' : '' ?>>Femenino</option>
                                        <option value="Otro" <?= (session('genero') == 'Otro') ? 'selected' : '' ?>>Otro</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Estado Civil</label>
                                    <select class="form-select" id="estado_civil" name="estado_civil">
                                        <option value="">Seleccionar</option>
                                        <option value="Soltero" <?= (session('estado_civil') == 'Soltero') ? 'selected' : '' ?>>Soltero</option>
                                        <option value="Casado" <?= (session('estado_civil') == 'Casado') ? 'selected' : '' ?>>Casado</option>
                                        <option value="Divorciado" <?= (session('estado_civil') == 'Divorciado') ? 'selected' : '' ?>>Divorciado</option>
                                        <option value="Viudo" <?= (session('estado_civil') == 'Viudo') ? 'selected' : '' ?>>Viudo</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Fecha de Ingreso</label>
                                    <input type="date" class="form-control" id="fecha_ingreso" name="fecha_ingreso" value="<?= session('fecha_ingreso') ?? '' ?>" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Dirección</label>
                            <textarea class="form-control" id="direccion" name="direccion" rows="3"><?= session('direccion') ?? '' ?></textarea>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Información del Sistema -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="ti ti-settings me-2"></i>Información del Sistema
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Rol del Sistema</label>
                                <input type="text" class="form-control" value="<?= session('nombre_rol') ?>" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Tipo de Empleado</label>
                                <input type="text" class="form-control" value="<?= session('tipo_empleado') ?>" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Último Acceso</label>
                                <input type="text" class="form-control" value="<?= session('last_login') ? date('d/m/Y H:i', strtotime(session('last_login'))) : 'Nunca' ?>" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Estado de la Cuenta</label>
                                <input type="text" class="form-control" value="<?= session('activo') ? 'Activa' : 'Inactiva' ?>" readonly>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
function cambiarFoto(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const img = input.parentElement.querySelector('img');
            img.src = e.target.result;
        };
        reader.readAsDataURL(input.files[0]);
        
        Swal.fire({
            icon: 'success',
            title: 'Foto actualizada',
            text: 'La foto se actualizará al guardar los cambios'
        });
    }
}

function guardarPerfil() {
    const formData = new FormData(document.getElementById('perfilForm'));
    
    // Agregar la foto si se seleccionó una
    const fotoInput = document.getElementById('fotoInput');
    if (fotoInput.files && fotoInput.files[0]) {
        formData.append('foto', fotoInput.files[0]);
    }
    
    fetch('<?= base_url('docente/perfil/actualizar') ?>', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Éxito',
                text: 'Perfil actualizado correctamente'
            }).then(() => {
                location.reload();
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: data.message || 'Error al actualizar perfil'
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
</script>
<?= $this->endSection() ?> 