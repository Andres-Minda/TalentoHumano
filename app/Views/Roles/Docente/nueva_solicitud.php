<?php $sidebar = 'sidebar_docente'; ?>
<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>

<div class="page-wrapper">
    <div class="page-content">
        <!-- Header -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <h4 class="mb-0"><i class="bi bi-plus-circle"></i> Nueva Solicitud</h4>
                    <div class="page-title-right">
                        <a href="<?= base_url('docente/solicitudes') ?>" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Volver a Mis Solicitudes
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Formulario de Nueva Solicitud -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Crear Nueva Solicitud</h5>
                    </div>
                    <div class="card-body">
                        <form id="formNuevaSolicitud" method="POST" action="<?= base_url('docente/crear-solicitud') ?>">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Tipo de Solicitud *</label>
                                        <select class="form-select" name="tipo_solicitud" required>
                                            <option value="">Seleccionar tipo</option>
                                            <option value="Permiso">Permiso</option>
                                            <option value="Capacitación">Capacitación</option>
                    
                                            <option value="Cambio de horario">Cambio de horario</option>
                                            <option value="Otro">Otro</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Prioridad</label>
                                        <select class="form-select" name="prioridad">
                                            <option value="Baja">Baja</option>
                                            <option value="Media" selected>Media</option>
                                            <option value="Alta">Alta</option>
                                            <option value="Urgente">Urgente</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Título de la Solicitud *</label>
                                <input type="text" class="form-control" name="titulo" placeholder="Ej: Solicitud de permiso médico" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Descripción Detallada *</label>
                                <textarea class="form-control" name="descripcion" rows="5" placeholder="Describa su solicitud con el mayor detalle posible..." required></textarea>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Fecha de Inicio (si aplica)</label>
                                        <input type="date" class="form-control" name="fecha_inicio">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Fecha de Fin (si aplica)</label>
                                        <input type="date" class="form-control" name="fecha_fin">
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Adjuntar Documento (opcional)</label>
                                <input type="file" class="form-control" name="documento" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                                <small class="text-muted">Formatos permitidos: PDF, DOC, DOCX, JPG, PNG. Máximo 5MB.</small>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Comentarios Adicionales</label>
                                <textarea class="form-control" name="comentarios" rows="3" placeholder="Información adicional que considere relevante..."></textarea>
                            </div>

                            <div class="d-flex justify-content-between">
                                <a href="<?= base_url('docente/solicitudes') ?>" class="btn btn-secondary">
                                    <i class="bi bi-x-circle"></i> Cancelar
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-send"></i> Enviar Solicitud
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Información de Ayuda -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title mb-0"><i class="bi bi-info-circle"></i> Información de Ayuda</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6>Tipos de Solicitudes:</h6>
                                <ul>
                                    <li><strong>Permiso:</strong> Para solicitar días libres, permisos personales, etc.</li>
                                    <li><strong>Capacitación:</strong> Para solicitar participación en cursos o talleres.</li>
                
                                    <li><strong>Cambio de horario:</strong> Para solicitar modificaciones en el horario laboral.</li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <h6>Proceso de Solicitud:</h6>
                                <ol>
                                    <li>Complete todos los campos obligatorios (*)</li>
                                    <li>Adjunte documentos de respaldo si es necesario</li>
                                    <li>Revise la información antes de enviar</li>
                                    <li>Recibirá una notificación cuando su solicitud sea procesada</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#formNuevaSolicitud').on('submit', function(e) {
        e.preventDefault();
        
        // Validación básica
        if (!$('select[name="tipo_solicitud"]').val()) {
            alert('Por favor seleccione un tipo de solicitud');
            return;
        }
        
        if (!$('input[name="titulo"]').val()) {
            alert('Por favor ingrese un título para la solicitud');
            return;
        }
        
        if (!$('textarea[name="descripcion"]').val()) {
            alert('Por favor ingrese una descripción');
            return;
        }
        
        // Aquí se enviaría la solicitud
        alert('Solicitud enviada correctamente. Será revisada por el administrador.');
        window.location.href = '<?= base_url('docente/solicitudes') ?>';
    });
});
</script>

<?= $this->endSection() ?> 