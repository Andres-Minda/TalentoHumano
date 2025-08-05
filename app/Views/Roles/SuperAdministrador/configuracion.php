<?php $sidebar = 'sidebar_super_admin'; ?>
<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>

<div class="page-wrapper">
    <div class="page-content">
        <!-- Header -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <h4 class="mb-0"><i class="bi bi-gear"></i> Configuración del Sistema</h4>
                </div>
            </div>
        </div>

        <!-- Configuración General -->
        <div class="row">
            <div class="col-xl-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0"><i class="bi bi-building"></i> Información de la Institución</h5>
                    </div>
                    <div class="card-body">
                        <form id="formInstitucion">
                            <div class="mb-3">
                                <label class="form-label">Nombre de la Institución</label>
                                <input type="text" class="form-control" name="nombre_institucion" value="<?= $configuracion['nombre_institucion'] ?? 'Instituto Tecnológico Superior' ?>">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Dirección</label>
                                <textarea class="form-control" name="direccion" rows="2"><?= $configuracion['direccion'] ?? '' ?></textarea>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Teléfono</label>
                                        <input type="text" class="form-control" name="telefono" value="<?= $configuracion['telefono'] ?? '' ?>">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Email</label>
                                        <input type="email" class="form-control" name="email" value="<?= $configuracion['email'] ?? '' ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Sitio Web</label>
                                <input type="url" class="form-control" name="sitio_web" value="<?= $configuracion['sitio_web'] ?? '' ?>">
                            </div>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Guardar Cambios
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-xl-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0"><i class="bi bi-shield-check"></i> Configuración de Seguridad</h5>
                    </div>
                    <div class="card-body">
                        <form id="formSeguridad">
                            <div class="mb-3">
                                <label class="form-label">Duración de Sesión (minutos)</label>
                                <input type="number" class="form-control" name="duracion_sesion" value="<?= $configuracion['duracion_sesion'] ?? 30 ?>" min="15" max="480">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Intentos Máximos de Login</label>
                                <input type="number" class="form-control" name="intentos_login" value="<?= $configuracion['intentos_login'] ?? 3 ?>" min="1" max="10">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Tiempo de Bloqueo (minutos)</label>
                                <input type="number" class="form-control" name="tiempo_bloqueo" value="<?= $configuracion['tiempo_bloqueo'] ?? 15 ?>" min="5" max="60">
                            </div>
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="requerir_cambio_password" id="requerir_cambio_password" <?= ($configuracion['requerir_cambio_password'] ?? false) ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="requerir_cambio_password">
                                        Requerir cambio de contraseña en primer login
                                    </label>
                                </div>
                            </div>
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="registrar_actividad" id="registrar_actividad" <?= ($configuracion['registrar_actividad'] ?? true) ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="registrar_actividad">
                                        Registrar actividad de usuarios
                                    </label>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Guardar Cambios
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Configuración de Notificaciones -->
        <div class="row">
            <div class="col-xl-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0"><i class="bi bi-bell"></i> Configuración de Notificaciones</h5>
                    </div>
                    <div class="card-body">
                        <form id="formNotificaciones">
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="notificar_nuevas_solicitudes" id="notificar_nuevas_solicitudes" <?= ($configuracion['notificar_nuevas_solicitudes'] ?? true) ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="notificar_nuevas_solicitudes">
                                        Notificar nuevas solicitudes
                                    </label>
                                </div>
                            </div>
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="notificar_capacitaciones" id="notificar_capacitaciones" <?= ($configuracion['notificar_capacitaciones'] ?? true) ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="notificar_capacitaciones">
                                        Notificar nuevas capacitaciones
                                    </label>
                                </div>
                            </div>
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="notificar_evaluaciones" id="notificar_evaluaciones" <?= ($configuracion['notificar_evaluaciones'] ?? true) ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="notificar_evaluaciones">
                                        Notificar evaluaciones pendientes
                                    </label>
                                </div>
                            </div>
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="notificar_permisos" id="notificar_permisos" <?= ($configuracion['notificar_permisos'] ?? true) ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="notificar_permisos">
                                        Notificar solicitudes de permisos
                                    </label>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Guardar Cambios
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-xl-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0"><i class="bi bi-database"></i> Configuración de Base de Datos</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Tamaño de Base de Datos</label>
                            <input type="text" class="form-control" value="<?= $configuracion['tamano_bd'] ?? '2.5 MB' ?>" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Último Respaldo</label>
                            <input type="text" class="form-control" value="<?= $configuracion['ultimo_respaldo'] ?? 'No disponible' ?>" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Estado de la Base de Datos</label>
                            <span class="badge bg-success">Conectado</span>
                        </div>
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-warning" onclick="optimizarBaseDatos()">
                                <i class="bi bi-tools"></i> Optimizar BD
                            </button>
                            <button type="button" class="btn btn-info" onclick="crearRespaldo()">
                                <i class="bi bi-download"></i> Crear Respaldo
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Formulario de Institución
    $('#formInstitucion').on('submit', function(e) {
        e.preventDefault();
        alert('Configuración de institución guardada correctamente');
    });
    
    // Formulario de Seguridad
    $('#formSeguridad').on('submit', function(e) {
        e.preventDefault();
        alert('Configuración de seguridad guardada correctamente');
    });
    
    // Formulario de Notificaciones
    $('#formNotificaciones').on('submit', function(e) {
        e.preventDefault();
        alert('Configuración de notificaciones guardada correctamente');
    });
});

function optimizarBaseDatos() {
    if (confirm('¿Está seguro de optimizar la base de datos?')) {
        alert('Base de datos optimizada correctamente');
    }
}

function crearRespaldo() {
    if (confirm('¿Desea crear un respaldo de la base de datos?')) {
        alert('Respaldo creado correctamente');
    }
}
</script>

<?= $this->endSection() ?> 