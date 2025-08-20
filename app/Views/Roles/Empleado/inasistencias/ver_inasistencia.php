<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>

<div class="page-wrapper">
    <div class="page-content">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <h4 class="page-title mb-0">Detalle de Inasistencia</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="<?= base_url('empleado/dashboard') ?>">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="<?= base_url('empleado/inasistencias') ?>">Inasistencias</a></li>
                            <li class="breadcrumb-item"><a href="<?= base_url('empleado/inasistencias/mis-inasistencias') ?>">Mis Inasistencias</a></li>
                            <li class="breadcrumb-item active">Detalle</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <?php if (isset($inasistencia)): ?>
        <div class="row">
            <!-- Información Principal -->
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-calendar-x text-danger me-2"></i>
                            Inasistencia del <?= date('d/m/Y', strtotime($inasistencia['fecha'])) ?>
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Fecha de Inasistencia:</label>
                                    <p class="form-control-plaintext">
                                        <?= date('l, d \d\e F \d\e Y', strtotime($inasistencia['fecha'])) ?>
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Tipo de Inasistencia:</label>
                                    <p class="form-control-plaintext">
                                        <span class="badge bg-info fs-6">
                                            <?= $inasistencia['tipo_nombre'] ?? 'N/A' ?>
                                        </span>
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Estado:</label>
                                    <p class="form-control-plaintext">
                                        <?php
                                        $estadoClass = '';
                                        $estadoText = '';
                                        switch ($inasistencia['estado']) {
                                            case 'JUSTIFICADA':
                                                $estadoClass = 'bg-success';
                                                $estadoText = 'Justificada';
                                                break;
                                            case 'SIN_JUSTIFICAR':
                                                $estadoClass = 'bg-warning';
                                                $estadoText = 'Sin Justificar';
                                                break;
                                            case 'PENDIENTE':
                                                $estadoClass = 'bg-info';
                                                $estadoText = 'Pendiente';
                                                break;
                                            default:
                                                $estadoClass = 'bg-secondary';
                                                $estadoText = 'Desconocido';
                                        }
                                        ?>
                                        <span class="badge <?= $estadoClass ?> fs-6">
                                            <?= $estadoText ?>
                                        </span>
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Registrada por:</label>
                                    <p class="form-control-plaintext">
                                        <?= $inasistencia['registrado_por_nombre'] ?? 'Sistema' ?>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Motivo:</label>
                            <p class="form-control-plaintext">
                                <?= $inasistencia['motivo'] ?? 'No especificado' ?>
                            </p>
                        </div>

                        <?php if (!empty($inasistencia['observaciones'])): ?>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Observaciones:</label>
                            <p class="form-control-plaintext">
                                <?= $inasistencia['observaciones'] ?>
                            </p>
                        </div>
                        <?php endif; ?>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Fecha de Registro:</label>
                                    <p class="form-control-plaintext">
                                        <?= date('d/m/Y H:i', strtotime($inasistencia['fecha_registro'])) ?>
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Última Actualización:</label>
                                    <p class="form-control-plaintext">
                                        <?= date('d/m/Y H:i', strtotime($inasistencia['fecha_actualizacion'])) ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Información del Tipo de Inasistencia -->
                <?php if (isset($inasistencia['tipo_info'])): ?>
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title mb-0">
                            <i class="bi bi-info-circle text-info me-2"></i>
                            Información del Tipo de Inasistencia
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Descripción:</label>
                                    <p class="form-control-plaintext">
                                        <?= $inasistencia['tipo_info']['descripcion'] ?? 'No disponible' ?>
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Requiere Justificación:</label>
                                    <p class="form-control-plaintext">
                                        <?php if ($inasistencia['tipo_info']['requiere_justificacion']): ?>
                                            <span class="badge bg-warning">Sí</span>
                                        <?php else: ?>
                                            <span class="badge bg-success">No</span>
                                        <?php endif; ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <?php if (!empty($inasistencia['tipo_info']['categoria'])): ?>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Categoría:</label>
                            <p class="form-control-plaintext">
                                <span class="badge bg-secondary">
                                    <?= $inasistencia['tipo_info']['categoria'] ?>
                                </span>
                            </p>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>

            <!-- Panel Lateral -->
            <div class="col-lg-4">
                <!-- Acciones Rápidas -->
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title mb-0">
                            <i class="bi bi-lightning text-warning me-2"></i>
                            Acciones Rápidas
                        </h6>
                    </div>
                    <div class="card-body">
                        <?php if ($inasistencia['estado'] === 'SIN_JUSTIFICAR'): ?>
                            <button type="button" class="btn btn-primary w-100 mb-2" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#modalSubirJustificacion">
                                <i class="bi bi-upload me-2"></i>
                                Subir Justificación
                            </button>
                        <?php endif; ?>
                        
                        <a href="<?= base_url('empleado/inasistencias/mis-inasistencias') ?>" 
                           class="btn btn-outline-secondary w-100 mb-2">
                            <i class="bi bi-arrow-left me-2"></i>
                            Volver a la Lista
                        </a>
                        
                        <button type="button" class="btn btn-outline-info w-100" 
                                onclick="window.print()">
                            <i class="bi bi-printer me-2"></i>
                            Imprimir
                        </button>
                    </div>
                </div>

                <!-- Información de Política -->
                <?php if (isset($inasistencia['politica_info'])): ?>
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title mb-0">
                            <i class="bi bi-shield-check text-success me-2"></i>
                            Política Aplicable
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Límite Anual:</label>
                            <p class="form-control-plaintext">
                                <?= $inasistencia['politica_info']['limite_anual'] ?? 'No definido' ?> días
                            </p>
                        </div>
                        
                        <?php if (isset($inasistencia['politica_info']['consecutivas'])): ?>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Límite Consecutivas:</label>
                            <p class="form-control-plaintext">
                                <?= $inasistencia['politica_info']['consecutivas'] ?> días
                            </p>
                        </div>
                        <?php endif; ?>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Estado de la Política:</label>
                            <p class="form-control-plaintext">
                                <?php if ($inasistencia['politica_info']['activa']): ?>
                                    <span class="badge bg-success">Activa</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Inactiva</span>
                                <?php endif; ?>
                            </p>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Estadísticas Personales -->
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title mb-0">
                            <i class="bi bi-graph-up text-primary me-2"></i>
                            Mis Estadísticas
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Inasistencias este año:</label>
                            <p class="form-control-plaintext">
                                <?= $estadisticas['total_anio'] ?? 0 ?> días
                            </p>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Justificadas:</label>
                            <p class="form-control-plaintext">
                                <?= $estadisticas['justificadas_anio'] ?? 0 ?> días
                            </p>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Sin justificar:</label>
                            <p class="form-control-plaintext">
                                <?= $estadisticas['sin_justificar_anio'] ?? 0 ?> días
                            </p>
                        </div>
                        
                        <?php if (isset($estadisticas['limite_anual']) && $estadisticas['limite_anual'] > 0): ?>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Días restantes:</label>
                            <p class="form-control-plaintext">
                                <?php 
                                $restantes = $estadisticas['limite_anual'] - $estadisticas['total_anio'];
                                $clase = $restantes > 5 ? 'text-success' : ($restantes > 0 ? 'text-warning' : 'text-danger');
                                ?>
                                <span class="<?= $clase ?> fw-bold">
                                    <?= max(0, $restantes) ?> días
                                </span>
                            </p>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Historial de Cambios -->
        <?php if (isset($inasistencia['historial']) && !empty($inasistencia['historial'])): ?>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title mb-0">
                            <i class="bi bi-clock-history text-secondary me-2"></i>
                            Historial de Cambios
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="timeline">
                            <?php foreach ($inasistencia['historial'] as $cambio): ?>
                            <div class="timeline-item">
                                <div class="timeline-marker"></div>
                                <div class="timeline-content">
                                    <div class="d-flex justify-content-between">
                                        <h6 class="timeline-title">
                                            <?= $cambio['accion'] ?>
                                        </h6>
                                        <small class="text-muted">
                                            <?= date('d/m/Y H:i', strtotime($cambio['fecha'])) ?>
                                        </small>
                                    </div>
                                    <p class="timeline-text">
                                        <?= $cambio['descripcion'] ?>
                                    </p>
                                    <?php if (!empty($cambio['usuario'])): ?>
                                    <small class="text-muted">
                                        Por: <?= $cambio['usuario'] ?>
                                    </small>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <?php else: ?>
        <!-- Inasistencia no encontrada -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center">
                        <i class="bi bi-exclamation-triangle text-warning" style="font-size: 4rem;"></i>
                        <h4 class="mt-3">Inasistencia no encontrada</h4>
                        <p class="text-muted">
                            La inasistencia que buscas no existe o no tienes permisos para verla.
                        </p>
                        <a href="<?= base_url('empleado/inasistencias/mis-inasistencias') ?>" 
                           class="btn btn-primary">
                            <i class="bi bi-arrow-left me-2"></i>
                            Volver a la Lista
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Modal para Subir Justificación -->
<div class="modal fade" id="modalSubirJustificacion" tabindex="-1" aria-labelledby="modalSubirJustificacionLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalSubirJustificacionLabel">Subir Justificación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formJustificacion" enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="hidden" id="inasistencia_id" name="inasistencia_id" value="<?= $inasistencia['id'] ?? '' ?>">
                    
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle me-2"></i>
                        <strong>Importante:</strong> Esta inasistencia requiere justificación según la política establecida.
                    </div>
                    
                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripción de la Justificación</label>
                        <textarea class="form-control" id="descripcion" name="descripcion" rows="4" 
                                  placeholder="Describe detalladamente el motivo de tu inasistencia..." required></textarea>
                        <small class="form-text text-muted">
                            Sé específico y detallado en tu explicación para facilitar la revisión.
                        </small>
                    </div>
                    
                    <div class="mb-3">
                        <label for="documento" class="form-label">Documento de Justificación</label>
                        <input type="file" class="form-control" id="documento" name="documento" 
                               accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" required>
                        <small class="form-text text-muted">
                            Formatos permitidos: PDF, DOC, DOCX, JPG, JPEG, PNG. Tamaño máximo: 5MB
                        </small>
                    </div>
                    
                    <div class="mb-3">
                        <label for="fecha_justificacion" class="form-label">Fecha de la Justificación</label>
                        <input type="date" class="form-control" id="fecha_justificacion" name="fecha_justificacion" 
                               value="<?= date('Y-m-d') ?>" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-upload me-2"></i>
                        Subir Justificación
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline-item {
    position: relative;
    margin-bottom: 20px;
}

.timeline-marker {
    position: absolute;
    left: -35px;
    top: 0;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    background-color: #007bff;
    border: 3px solid #fff;
    box-shadow: 0 0 0 3px #007bff;
}

.timeline-item::before {
    content: '';
    position: absolute;
    left: -29px;
    top: 12px;
    width: 2px;
    height: calc(100% + 8px);
    background-color: #e9ecef;
}

.timeline-item:last-child::before {
    display: none;
}

.timeline-content {
    background-color: #f8f9fa;
    padding: 15px;
    border-radius: 8px;
    border-left: 3px solid #007bff;
}

.timeline-title {
    margin-bottom: 5px;
    color: #495057;
}

.timeline-text {
    margin-bottom: 5px;
    color: #6c757d;
}

@media print {
    .btn, .modal, .breadcrumb {
        display: none !important;
    }
    
    .card {
        border: 1px solid #000 !important;
        box-shadow: none !important;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Formulario de justificación
    document.getElementById('formJustificacion').addEventListener('submit', function(e) {
        e.preventDefault();
        subirJustificacion();
    });
});

function subirJustificacion() {
    const formData = new FormData(document.getElementById('formJustificacion'));
    
    // Mostrar indicador de carga
    const submitBtn = document.querySelector('#formJustificacion button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Enviando...';
    submitBtn.disabled = true;
    
    fetch('<?= base_url('empleado/inasistencias/subir-justificacion') ?>', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Justificación enviada exitosamente. Será revisada por el administrador.');
            location.reload();
        } else {
            alert('Error al enviar la justificación: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error al enviar la justificación. Inténtalo de nuevo.');
    })
    .finally(() => {
        // Restaurar botón
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    });
}
</script>

<?= $this->endSection() ?>
