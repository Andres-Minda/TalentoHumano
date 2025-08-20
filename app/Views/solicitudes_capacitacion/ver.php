<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?> - Sistema de Talento Humano</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <style>
        .info-card {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            border-left: 4px solid #007bff;
        }
        .info-card h6 {
            color: #007bff;
            margin-bottom: 15px;
        }
        .status-badge {
            font-size: 1rem;
            padding: 0.5rem 1rem;
        }
        .priority-badge {
            font-size: 0.9rem;
            padding: 0.4rem 0.8rem;
        }
        .timeline {
            position: relative;
            padding-left: 30px;
        }
        .timeline::before {
            content: '';
            position: absolute;
            left: 15px;
            top: 0;
            bottom: 0;
            width: 2px;
            background: #dee2e6;
        }
        .timeline-item {
            position: relative;
            margin-bottom: 20px;
        }
        .timeline-item::before {
            content: '';
            position: absolute;
            left: -22px;
            top: 5px;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: #007bff;
            border: 3px solid #fff;
            box-shadow: 0 0 0 3px #dee2e6;
        }
        .timeline-item.completed::before {
            background: #28a745;
        }
        .timeline-item.pending::before {
            background: #ffc107;
        }
        .timeline-item.rejected::before {
            background: #dc3545;
        }
        .detail-row {
            background: #fff;
            border-radius: 6px;
            padding: 15px;
            margin-bottom: 10px;
            border: 1px solid #e9ecef;
        }
        .detail-label {
            font-weight: 600;
            color: #495057;
            margin-bottom: 5px;
        }
        .detail-value {
            color: #212529;
        }
        .action-buttons {
            position: sticky;
            top: 20px;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <h1 class="h3 mb-0">
                        <i class="fas fa-clipboard-list text-primary me-2"></i>
                        <?= $title ?>
                    </h1>
                    <div>
                        <?php if ($es_admin): ?>
                        <a href="/solicitudes-capacitacion" class="btn btn-outline-secondary me-2">
                            <i class="fas fa-arrow-left me-1"></i> Volver al Listado
                        </a>
                        <?php else: ?>
                        <a href="/empleado/solicitudes-capacitacion" class="btn btn-outline-secondary me-2">
                            <i class="fas fa-arrow-left me-1"></i> Mis Solicitudes
                        </a>
                        <?php endif; ?>
                        
                        <?php if ($solicitud['estado'] === 'Pendiente' && !$es_admin): ?>
                        <a href="/empleado/solicitudes-capacitacion/editar/<?= $solicitud['id_solicitud'] ?>" class="btn btn-warning me-2">
                            <i class="fas fa-edit me-1"></i> Editar
                        </a>
                        <?php endif; ?>
                        
                        <?php if ($es_admin && $solicitud['estado'] === 'Pendiente'): ?>
                        <button class="btn btn-success me-2" onclick="aprobarSolicitud()">
                            <i class="fas fa-check me-1"></i> Aprobar
                        </button>
                        <button class="btn btn-danger me-2" onclick="rechazarSolicitud()">
                            <i class="fas fa-times me-1"></i> Rechazar
                        </button>
                        <?php endif; ?>
                        
                        <?php if ($es_admin && $solicitud['estado'] === 'Aprobada'): ?>
                        <button class="btn btn-info" onclick="convertirEnCapacitacion()">
                            <i class="fas fa-exchange-alt me-1"></i> Convertir en Capacitación
                        </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <!-- Estado y Prioridad -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="info-card text-center">
                            <h6><i class="fas fa-info-circle me-2"></i>Estado Actual</h6>
                            <?php
                            $estadoClass = [
                                'Pendiente' => 'bg-warning',
                                'Aprobada' => 'bg-success',
                                'Rechazada' => 'bg-danger',
                                'Cancelada' => 'bg-secondary',
                                'Convertida en Capacitación' => 'bg-info'
                            ];
                            $estadoClass = $estadoClass[$solicitud['estado']] ?? 'bg-secondary';
                            ?>
                            <span class="badge <?= $estadoClass ?> status-badge">
                                <?= $solicitud['estado'] ?>
                            </span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-card text-center">
                            <h6><i class="fas fa-exclamation-triangle me-2"></i>Prioridad</h6>
                            <?php
                            $prioridadClass = [
                                'Baja' => 'bg-success',
                                'Media' => 'bg-warning',
                                'Alta' => 'bg-danger',
                                'Crítica' => 'bg-dark'
                            ];
                            $prioridadClass = $prioridadClass[$solicitud['prioridad']] ?? 'bg-secondary';
                            ?>
                            <span class="badge <?= $prioridadClass ?> priority-badge">
                                <?= $solicitud['prioridad'] ?>
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Información de la Capacitación -->
                <div class="info-card">
                    <h6><i class="fas fa-graduation-cap me-2"></i>Información de la Capacitación</h6>
                    
                    <div class="detail-row">
                        <div class="detail-label">Nombre de la Capacitación</div>
                        <div class="detail-value"><?= $solicitud['nombre_capacitacion'] ?></div>
                    </div>
                    
                    <div class="detail-row">
                        <div class="detail-label">Tipo de Capacitación</div>
                        <div class="detail-value">
                            <span class="badge bg-secondary"><?= $solicitud['tipo_capacitacion'] ?></span>
                        </div>
                    </div>
                    
                    <div class="detail-row">
                        <div class="detail-label">Descripción Detallada</div>
                        <div class="detail-value"><?= nl2br($solicitud['descripcion']) ?></div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="detail-row">
                                <div class="detail-label">Fecha Deseada</div>
                                <div class="detail-value">
                                    <i class="fas fa-calendar me-2"></i>
                                    <?= date('d/m/Y', strtotime($solicitud['fecha_deseada'])) ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="detail-row">
                                <div class="detail-label">Duración Estimada</div>
                                <div class="detail-value">
                                    <i class="fas fa-clock me-2"></i>
                                    <?= $solicitud['duracion_estimada'] ?> horas
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <?php if ($solicitud['institucion_preferida']): ?>
                    <div class="detail-row">
                        <div class="detail-label">Institución Preferida</div>
                        <div class="detail-value">
                            <i class="fas fa-university me-2"></i>
                            <?= $solicitud['institucion_preferida'] ?>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <?php if ($solicitud['costo_estimado']): ?>
                    <div class="detail-row">
                        <div class="detail-label">Costo Estimado</div>
                        <div class="detail-value">
                            <i class="fas fa-dollar-sign me-2"></i>
                            $<?= number_format($solicitud['costo_estimado'], 2) ?>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>

                <!-- Justificación y Beneficios -->
                <div class="info-card">
                    <h6><i class="fas fa-lightbulb me-2"></i>Justificación y Beneficios</h6>
                    
                    <div class="detail-row">
                        <div class="detail-label">Justificación</div>
                        <div class="detail-value"><?= $solicitud['justificacion'] ?></div>
                    </div>
                    
                    <div class="detail-row">
                        <div class="detail-label">Beneficios Esperados</div>
                        <div class="detail-value"><?= nl2br($solicitud['beneficios_esperados']) ?></div>
                    </div>
                </div>

                <!-- Información del Empleado -->
                <div class="info-card">
                    <h6><i class="fas fa-user me-2"></i>Información del Solicitante</h6>
                    
                    <div class="detail-row">
                        <div class="detail-label">Nombre Completo</div>
                        <div class="detail-value">
                            <?= $solicitud['nombre_empleado'] . ' ' . $solicitud['apellido_empleado'] ?>
                        </div>
                    </div>
                    
                    <div class="detail-row">
                        <div class="detail-label">Cédula</div>
                        <div class="detail-value"><?= $solicitud['cedula'] ?></div>
                    </div>
                    
                    <?php if ($solicitud['email']): ?>
                    <div class="detail-row">
                        <div class="detail-label">Email</div>
                        <div class="detail-value">
                            <i class="fas fa-envelope me-2"></i>
                            <?= $solicitud['email'] ?>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <?php if ($solicitud['nombre_departamento']): ?>
                    <div class="detail-row">
                        <div class="detail-label">Departamento</div>
                        <div class="detail-value">
                            <i class="fas fa-building me-2"></i>
                            <?= $solicitud['nombre_departamento'] ?>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <?php if ($solicitud['nombre_puesto']): ?>
                    <div class="detail-row">
                        <div class="detail-label">Puesto de Trabajo</div>
                        <div class="detail-value">
                            <i class="fas fa-briefcase me-2"></i>
                            <?= $solicitud['nombre_puesto'] ?>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>

                <!-- Observaciones y Comentarios -->
                <?php if ($solicitud['observaciones_admin'] || $solicitud['motivo_rechazo'] || $solicitud['motivo_cancelacion']): ?>
                <div class="info-card">
                    <h6><i class="fas fa-comments me-2"></i>Observaciones y Comentarios</h6>
                    
                    <?php if ($solicitud['observaciones_admin']): ?>
                    <div class="detail-row">
                        <div class="detail-label">Observaciones del Administrador</div>
                        <div class="detail-value">
                            <div class="alert alert-success mb-0">
                                <i class="fas fa-check-circle me-2"></i>
                                <?= nl2br($solicitud['observaciones_admin']) ?>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <?php if ($solicitud['motivo_rechazo']): ?>
                    <div class="detail-row">
                        <div class="detail-label">Motivo del Rechazo</div>
                        <div class="detail-value">
                            <div class="alert alert-danger mb-0">
                                <i class="fas fa-times-circle me-2"></i>
                                <?= nl2br($solicitud['motivo_rechazo']) ?>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <?php if ($solicitud['motivo_cancelacion']): ?>
                    <div class="detail-row">
                        <div class="detail-label">Motivo de la Cancelación</div>
                        <div class="detail-value">
                            <div class="alert alert-secondary mb-0">
                                <i class="fas fa-ban me-2"></i>
                                <?= nl2br($solicitud['motivo_cancelacion']) ?>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
                <?php endif; ?>

                <!-- Información de Conversión -->
                <?php if ($solicitud['estado'] === 'Convertida en Capacitación' && $solicitud['id_capacitacion_creada']): ?>
                <div class="info-card">
                    <h6><i class="fas fa-exchange-alt me-2"></i>Información de Conversión</h6>
                    
                    <div class="detail-row">
                        <div class="detail-label">ID de Capacitación Creada</div>
                        <div class="detail-value">
                            <i class="fas fa-hashtag me-2"></i>
                            <?= $solicitud['id_capacitacion_creada'] ?>
                        </div>
                    </div>
                    
                    <?php if ($solicitud['fecha_conversion']): ?>
                    <div class="detail-row">
                        <div class="detail-label">Fecha de Conversión</div>
                        <div class="detail-value">
                            <i class="fas fa-calendar-check me-2"></i>
                            <?= date('d/m/Y H:i', strtotime($solicitud['fecha_conversion'])) ?>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Acciones Rápidas -->
                <div class="action-buttons">
                    <div class="card mb-3">
                        <div class="card-header bg-primary text-white">
                            <h6 class="mb-0">
                                <i class="fas fa-bolt me-2"></i>Acciones Rápidas
                            </h6>
                        </div>
                        <div class="card-body">
                            <?php if ($es_admin && $solicitud['estado'] === 'Pendiente'): ?>
                            <button class="btn btn-success w-100 mb-2" onclick="aprobarSolicitud()">
                                <i class="fas fa-check me-2"></i>Aprobar Solicitud
                            </button>
                            <button class="btn btn-danger w-100 mb-2" onclick="rechazarSolicitud()">
                                <i class="fas fa-times me-2"></i>Rechazar Solicitud
                            </button>
                            <?php endif; ?>
                            
                            <?php if ($es_admin && $solicitud['estado'] === 'Aprobada'): ?>
                            <button class="btn btn-info w-100 mb-2" onclick="convertirEnCapacitacion()">
                                <i class="fas fa-exchange-alt me-2"></i>Convertir en Capacitación
                            </button>
                            <?php endif; ?>
                            
                            <?php if (!$es_admin && $solicitud['estado'] === 'Pendiente'): ?>
                            <a href="/empleado/solicitudes-capacitacion/editar/<?= $solicitud['id_solicitud'] ?>" class="btn btn-warning w-100 mb-2">
                                <i class="fas fa-edit me-2"></i>Editar Solicitud
                            </a>
                            <button class="btn btn-outline-danger w-100" onclick="cancelarSolicitud()">
                                <i class="fas fa-ban me-2"></i>Cancelar Solicitud
                            </button>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Timeline del Proceso -->
                    <div class="card">
                        <div class="card-header bg-info text-white">
                            <h6 class="mb-0">
                                <i class="fas fa-tasks me-2"></i>Progreso de la Solicitud
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="timeline">
                                <div class="timeline-item completed">
                                    <div class="small text-muted">Solicitud Creada</div>
                                    <div class="small"><?= date('d/m/Y H:i', strtotime($solicitud['fecha_creacion'])) ?></div>
                                </div>
                                
                                <?php if ($solicitud['estado'] !== 'Pendiente'): ?>
                                <div class="timeline-item completed">
                                    <div class="small text-muted">En Revisión</div>
                                    <div class="small">Procesada por administración</div>
                                </div>
                                
                                <?php if ($solicitud['estado'] === 'Aprobada'): ?>
                                <div class="timeline-item completed">
                                    <div class="small text-muted">Aprobada</div>
                                    <div class="small"><?= $solicitud['fecha_aprobacion'] ? date('d/m/Y H:i', strtotime($solicitud['fecha_aprobacion'])) : 'Fecha no disponible' ?></div>
                                </div>
                                <?php endif; ?>
                                
                                <?php if ($solicitud['estado'] === 'Rechazada'): ?>
                                <div class="timeline-item rejected">
                                    <div class="small text-muted">Rechazada</div>
                                    <div class="small"><?= $solicitud['fecha_rechazo'] ? date('d/m/Y H:i', strtotime($solicitud['fecha_rechazo'])) : 'Fecha no disponible' ?></div>
                                </div>
                                <?php endif; ?>
                                
                                <?php if ($solicitud['estado'] === 'Convertida en Capacitación'): ?>
                                <div class="timeline-item completed">
                                    <div class="small text-muted">Convertida en Capacitación</div>
                                    <div class="small"><?= $solicitud['fecha_conversion'] ? date('d/m/Y H:i', strtotime($solicitud['fecha_conversion'])) : 'Fecha no disponible' ?></div>
                                </div>
                                <?php endif; ?>
                                
                                <?php else: ?>
                                <div class="timeline-item pending">
                                    <div class="small text-muted">En Revisión</div>
                                    <div class="small">Pendiente de aprobación</div>
                                </div>
                                <div class="timeline-item">
                                    <div class="small text-muted">Implementación</div>
                                    <div class="small">Pendiente</div>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Información Adicional -->
                    <div class="card mt-3">
                        <div class="card-header bg-secondary text-white">
                            <h6 class="mb-0">
                                <i class="fas fa-info-circle me-2"></i>Información Adicional
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="small">
                                <div class="mb-2">
                                    <strong>ID de Solicitud:</strong><br>
                                    <code><?= $solicitud['id_solicitud'] ?></code>
                                </div>
                                <div class="mb-2">
                                    <strong>Creada por:</strong><br>
                                    <?= $solicitud['nombre_creador'] ?? 'Usuario del sistema' ?>
                                </div>
                                <div class="mb-2">
                                    <strong>Fecha de Creación:</strong><br>
                                    <?= date('d/m/Y H:i', strtotime($solicitud['fecha_creacion'])) ?>
                                </div>
                                <?php if ($solicitud['fecha_modificacion']): ?>
                                <div class="mb-2">
                                    <strong>Última Modificación:</strong><br>
                                    <?= date('d/m/Y H:i', strtotime($solicitud['fecha_modificacion'])) ?>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para Aprobar Solicitud -->
    <div class="modal fade" id="modalAprobar" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Aprobar Solicitud</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="formAprobar">
                        <div class="mb-3">
                            <label class="form-label">Observaciones (opcional)</label>
                            <textarea class="form-control" name="observaciones" rows="3" placeholder="Observaciones adicionales..."></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-success" onclick="confirmarAprobar()">Aprobar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para Rechazar Solicitud -->
    <div class="modal fade" id="modalRechazar" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Rechazar Solicitud</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="formRechazar">
                        <div class="mb-3">
                            <label class="form-label">Motivo del Rechazo <span class="text-danger">*</span></label>
                            <textarea class="form-control" name="motivo_rechazo" rows="3" placeholder="Motivo del rechazo..." required></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-danger" onclick="confirmarRechazar()">Rechazar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para Cancelar Solicitud -->
    <div class="modal fade" id="modalCancelar" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Cancelar Solicitud</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="formCancelar">
                        <div class="mb-3">
                            <label class="form-label">Motivo de la Cancelación <span class="text-danger">*</span></label>
                            <textarea class="form-control" name="motivo_cancelacion" rows="3" placeholder="Motivo de la cancelación..." required></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-danger" onclick="confirmarCancelar()">Confirmar Cancelación</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        function aprobarSolicitud() {
            $('#modalAprobar').modal('show');
        }

        function confirmarAprobar() {
            const observaciones = $('#formAprobar textarea[name="observaciones"]').val();
            
            $.ajax({
                url: `/solicitudes-capacitacion/aprobar/<?= $solicitud['id_solicitud'] ?>`,
                type: 'POST',
                data: { observaciones: observaciones },
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: '¡Éxito!',
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
                        text: 'Error al procesar la solicitud'
                    });
                }
            });
        }

        function rechazarSolicitud() {
            $('#modalRechazar').modal('show');
        }

        function confirmarRechazar() {
            const motivo = $('#formRechazar textarea[name="motivo_rechazo"]').val();
            
            if (!motivo.trim()) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Campo Requerido',
                    text: 'Debe especificar el motivo del rechazo'
                });
                return;
            }

            $.ajax({
                url: `/solicitudes-capacitacion/rechazar/<?= $solicitud['id_solicitud'] ?>`,
                type: 'POST',
                data: { motivo_rechazo: motivo },
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: '¡Éxito!',
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
                        text: 'Error al procesar la solicitud'
                    });
                }
            });
        }

        function convertirEnCapacitacion() {
            Swal.fire({
                title: '¿Convertir en Capacitación?',
                text: 'Esta acción creará una nueva capacitación basada en la solicitud aprobada',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, convertir',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/solicitudes-capacitacion/convertir/<?= $solicitud['id_solicitud'] ?>`,
                        type: 'POST',
                        success: function(response) {
                            if (response.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: '¡Éxito!',
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
                                text: 'Error al convertir la solicitud'
                            });
                        }
                    });
                }
            });
        }

        function cancelarSolicitud() {
            $('#modalCancelar').modal('show');
        }

        function confirmarCancelar() {
            const motivo = $('#formCancelar textarea[name="motivo_cancelacion"]').val();
            
            if (!motivo.trim()) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Campo Requerido',
                    text: 'Debe especificar el motivo de la cancelación'
                });
                return;
            }

            $.ajax({
                url: `/empleado/solicitudes-capacitacion/cancelar/<?= $solicitud['id_solicitud'] ?>`,
                type: 'POST',
                data: { motivo_cancelacion: motivo },
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: '¡Éxito!',
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
                        text: 'Error al cancelar la solicitud'
                    });
                }
            });
        }
    </script>
</body>
</html>
