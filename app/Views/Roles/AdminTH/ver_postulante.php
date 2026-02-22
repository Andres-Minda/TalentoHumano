<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>
<div class="page-wrapper">
    <div class="page-content">
        <!-- Breadcrumb -->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Postulantes</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
                        <li class="breadcrumb-item"><a href="<?= base_url('index.php/admin-th/dashboard') ?>">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="<?= base_url('index.php/admin-th/postulantes') ?>">Postulantes</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Detalles del Postulante</li>
                    </ol>
                </nav>
            </div>
        </div>

        <!-- Header -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h4 class="card-title mb-1">Detalles del Postulante</h4>
                                <p class="card-subtitle mb-0">Información completa de la postulación</p>
                            </div>
                            <div>
                                <a href="<?= base_url('index.php/admin-th/postulantes') ?>" class="btn btn-secondary">
                                    <i class="bi bi-arrow-left me-2"></i>Volver
                                </a>
                                <?php if ($postulante['cv_path']): ?>
                                    <a href="<?= base_url('index.php/admin-th/postulantes/' . $postulante['id_postulante'] . '/cv') ?>" 
                                       class="btn btn-primary ms-2">
                                        <i class="bi bi-download me-2"></i>Descargar CV
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Información del Puesto -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Información del Puesto</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="text-primary"><?= $postulante['titulo_puesto'] ?></h6>
                                <p class="text-muted mb-2"><?= $postulante['nombre_departamento'] ?></p>
                                <p class="mb-0"><?= $postulante['descripcion_puesto'] ?? 'Sin descripción disponible' ?></p>
                            </div>
                            <div class="col-md-6 text-end">
                                <span class="badge fs-6 p-2 <?= 
                                    match($postulante['estado_postulacion']) {
                                        'Pendiente' => 'bg-warning',
                                        'En revisión' => 'bg-info',
                                        'Aprobada' => 'bg-success',
                                        'Rechazada' => 'bg-danger',
                                        'Contratado' => 'bg-dark',
                                        default => 'bg-secondary'
                                    }
                                ?>">
                                    <?= $postulante['estado_postulacion'] ?>
                                </span>
                                <p class="text-muted mt-2 mb-0">
                                    Postulado el: <?= date('d/m/Y', strtotime($postulante['fecha_postulacion'])) ?>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Información Personal -->
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Información Personal</h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-sm-4">
                                <strong>Nombres:</strong>
                            </div>
                            <div class="col-sm-8">
                                <?= $postulante['nombres'] ?>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-4">
                                <strong>Apellidos:</strong>
                            </div>
                            <div class="col-sm-8">
                                <?= $postulante['apellidos'] ?>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-4">
                                <strong>Cédula:</strong>
                            </div>
                            <div class="col-sm-8">
                                <?= $postulante['cedula'] ?>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-4">
                                <strong>Email:</strong>
                            </div>
                            <div class="col-sm-8">
                                <a href="mailto:<?= $postulante['email'] ?>"><?= $postulante['email'] ?></a>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-4">
                                <strong>Teléfono:</strong>
                            </div>
                            <div class="col-sm-8">
                                <a href="tel:<?= $postulante['telefono'] ?>"><?= $postulante['telefono'] ?></a>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-4">
                                <strong>Fecha Nacimiento:</strong>
                            </div>
                            <div class="col-sm-8">
                                <?= date('d/m/Y', strtotime($postulante['fecha_nacimiento'])) ?>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-4">
                                <strong>Género:</strong>
                            </div>
                            <div class="col-sm-8">
                                <?= $postulante['genero'] ?>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-4">
                                <strong>Estado Civil:</strong>
                            </div>
                            <div class="col-sm-8">
                                <?= $postulante['estado_civil'] ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Información de Residencia</h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-sm-4">
                                <strong>Dirección:</strong>
                            </div>
                            <div class="col-sm-8">
                                <?= $postulante['direccion'] ?>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-4">
                                <strong>Ciudad:</strong>
                            </div>
                            <div class="col-sm-8">
                                <?= $postulante['ciudad'] ?>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-4">
                                <strong>Provincia:</strong>
                            </div>
                            <div class="col-sm-8">
                                <?= $postulante['provincia'] ?>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-4">
                                <strong>Código Postal:</strong>
                            </div>
                            <div class="col-sm-8">
                                <?= $postulante['codigo_postal'] ?: 'No especificado' ?>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-4">
                                <strong>Nacionalidad:</strong>
                            </div>
                            <div class="col-sm-8">
                                <?= $postulante['nacionalidad'] ?>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-4">
                                <strong>Disponibilidad:</strong>
                            </div>
                            <div class="col-sm-8">
                                <?= $postulante['disponibilidad_inmediata'] ?>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-4">
                                <strong>Expectativa Salarial:</strong>
                            </div>
                            <div class="col-sm-8">
                                <?php if ($postulante['expectativa_salarial']): ?>
                                    $<?= number_format($postulante['expectativa_salarial'], 2) ?>
                                <?php else: ?>
                                    No especificada
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Información Profesional -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Información Profesional</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <h6 class="text-primary">Experiencia Laboral</h6>
                                <div class="border rounded p-3 bg-light">
                                    <?php if ($postulante['experiencia_laboral']): ?>
                                        <?= nl2br(htmlspecialchars($postulante['experiencia_laboral'])) ?>
                                    <?php else: ?>
                                        <em class="text-muted">No especificada</em>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="col-md-6 mb-4">
                                <h6 class="text-primary">Educación y Formación</h6>
                                <div class="border rounded p-3 bg-light">
                                    <?php if ($postulante['educacion']): ?>
                                        <?= nl2br(htmlspecialchars($postulante['educacion'])) ?>
                                    <?php else: ?>
                                        <em class="text-muted">No especificada</em>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <h6 class="text-primary">Habilidades y Competencias</h6>
                                <div class="border rounded p-3 bg-light">
                                    <?php if ($postulante['habilidades']): ?>
                                        <?= nl2br(htmlspecialchars($postulante['habilidades'])) ?>
                                    <?php else: ?>
                                        <em class="text-muted">No especificadas</em>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="col-md-6 mb-4">
                                <h6 class="text-primary">Idiomas</h6>
                                <div class="border rounded p-3 bg-light">
                                    <?php if ($postulante['idiomas']): ?>
                                        <?= nl2br(htmlspecialchars($postulante['idiomas'])) ?>
                                    <?php else: ?>
                                        <em class="text-muted">No especificados</em>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <h6 class="text-primary">Certificaciones</h6>
                                <div class="border rounded p-3 bg-light">
                                    <?php if ($postulante['certificaciones']): ?>
                                        <?= nl2br(htmlspecialchars($postulante['certificaciones'])) ?>
                                    <?php else: ?>
                                        <em class="text-muted">No especificadas</em>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="col-md-6 mb-4">
                                <h6 class="text-primary">Referencias Laborales</h6>
                                <div class="border rounded p-3 bg-light">
                                    <?php if ($postulante['referencias']): ?>
                                        <?= nl2br(htmlspecialchars($postulante['referencias'])) ?>
                                    <?php else: ?>
                                        <em class="text-muted">No especificadas</em>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Carta de Motivación -->
        <?php if ($postulante['carta_motivacion']): ?>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Carta de Motivación</h5>
                    </div>
                    <div class="card-body">
                        <div class="border rounded p-3 bg-light">
                            <?= nl2br(htmlspecialchars($postulante['carta_motivacion'])) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Notas Administrativas -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Notas Administrativas</h5>
                    </div>
                    <div class="card-body">
                        <div class="border rounded p-3 bg-light">
                            <?php if ($postulante['notas_admin']): ?>
                                <?= nl2br(htmlspecialchars($postulante['notas_admin'])) ?>
                            <?php else: ?>
                                <em class="text-muted">No hay notas administrativas</em>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Acciones -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center">
                        <button type="button" class="btn btn-warning me-2" 
                                onclick="cambiarEstado(<?= $postulante['id_postulante'] ?>, '<?= $postulante['estado_postulacion'] ?>')">
                            <i class="bi bi-pencil me-2"></i>Cambiar Estado
                        </button>
                        <a href="<?= base_url('index.php/admin-th/postulantes') ?>" class="btn btn-secondary">
                            <i class="bi bi-arrow-left me-2"></i>Volver a la Lista
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para cambiar estado -->
<div class="modal fade" id="modalCambiarEstado" tabindex="-1" aria-labelledby="modalCambiarEstadoLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalCambiarEstadoLabel">Cambiar Estado de Postulación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formCambiarEstado">
                <div class="modal-body">
                    <input type="hidden" id="idPostulante" name="id_postulante" value="<?= $postulante['id_postulante'] ?>">
                    <div class="mb-3">
                        <label for="nuevoEstado" class="form-label">Nuevo Estado</label>
                        <select class="form-select" id="nuevoEstado" name="nuevo_estado" required>
                            <option value="">Seleccionar estado...</option>
                            <option value="Pendiente" <?= $postulante['estado_postulacion'] === 'Pendiente' ? 'selected' : '' ?>>Pendiente</option>
                            <option value="En revisión" <?= $postulante['estado_postulacion'] === 'En revisión' ? 'selected' : '' ?>>En revisión</option>
                            <option value="Aprobada" <?= $postulante['estado_postulacion'] === 'Aprobada' ? 'selected' : '' ?>>Aprobada</option>
                            <option value="Rechazada" <?= $postulante['estado_postulacion'] === 'Rechazada' ? 'selected' : '' ?>>Rechazada</option>
                            <option value="Contratado" <?= $postulante['estado_postulacion'] === 'Contratado' ? 'selected' : '' ?>>Contratado</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="notas" class="form-label">Notas Administrativas</label>
                        <textarea class="form-control" id="notas" name="notas" rows="3" 
                                  placeholder="Agregue notas sobre la decisión..."><?= $postulante['notas_admin'] ?? '' ?></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Actualizar Estado</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function cambiarEstado(idPostulante, estadoActual) {
    document.getElementById('idPostulante').value = idPostulante;
    document.getElementById('nuevoEstado').value = estadoActual;
    
    const modal = new bootstrap.Modal(document.getElementById('modalCambiarEstado'));
    modal.show();
}

document.getElementById('formCambiarEstado').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    fetch('<?= base_url('index.php/admin-th/postulaciones/cambiar-estado') ?>', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Mostrar mensaje de éxito
            Swal.fire({
                icon: 'success',
                title: '¡Éxito!',
                text: data.message,
                timer: 2000,
                showConfirmButton: false
            }).then(() => {
                // Recargar la página
                window.location.reload();
            });
        } else {
            // Mostrar mensaje de error
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: data.message
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Ocurrió un error al procesar la solicitud'
        });
    });
});
</script>

<?= $this->endSection() ?>
