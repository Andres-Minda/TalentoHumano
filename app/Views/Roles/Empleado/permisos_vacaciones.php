<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>

<div class="page-wrapper">
    <div class="page-content">
        <!-- Header -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <h4 class="mb-0"><i class="bi bi-calendar-heart"></i> Mis Permisos y Vacaciones</h4>
                    <div class="page-title-right">
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#nuevoPermisoModal">
                            <i class="bi bi-plus-circle me-1"></i>Nueva Solicitud
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="row">
            <div class="col-xl-3 col-lg-6 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h4 class="mb-0">15</h4>
                                <p class="text-muted mb-0">Días Disponibles</p>
                            </div>
                            <div class="text-primary">
                                <i class="bi bi-calendar-heart fs-1"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h4 class="mb-0">5</h4>
                                <p class="text-muted mb-0">Días Tomados</p>
                            </div>
                            <div class="text-info">
                                <i class="bi bi-calendar-check fs-1"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h4 class="mb-0">3</h4>
                                <p class="text-muted mb-0">Solicitudes Pendientes</p>
                            </div>
                            <div class="text-warning">
                                <i class="bi bi-clock fs-1"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h4 class="mb-0">2</h4>
                                <p class="text-muted mb-0">Permisos Especiales</p>
                            </div>
                            <div class="text-success">
                                <i class="bi bi-star fs-1"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filtros -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Filtros de Búsqueda</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label">Tipo</label>
                                    <select class="form-select" id="filtroTipo">
                                        <option value="">Todos los tipos</option>
                                        <option value="VACACIONES">Vacaciones</option>
                                        <option value="PERMISO_PERSONAL">Permiso Personal</option>
                                        <option value="PERMISO_MEDICO">Permiso Médico</option>
                                        <option value="PERMISO_ESTUDIOS">Permiso de Estudios</option>
                                        <option value="PERMISO_MATERNAL">Permiso Maternal</option>
                                        <option value="PERMISO_PATERNAL">Permiso Paternal</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label">Estado</label>
                                    <select class="form-select" id="filtroEstado">
                                        <option value="">Todos los estados</option>
                                        <option value="PENDIENTE">Pendiente</option>
                                        <option value="APROBADO">Aprobado</option>
                                        <option value="RECHAZADO">Rechazado</option>
                                        <option value="EN_PROCESO">En Proceso</option>
                                        <option value="COMPLETADO">Completado</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Rango de Fechas</label>
                                    <div class="input-group">
                                        <input type="date" class="form-control" id="fechaInicio" placeholder="Desde">
                                        <span class="input-group-text">hasta</span>
                                        <input type="date" class="form-control" id="fechaFin" placeholder="Hasta">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="mb-3">
                                    <label class="form-label">&nbsp;</label>
                                    <button class="btn btn-outline-primary w-100" onclick="filtrarPermisos()">
                                        <i class="bi bi-search me-1"></i>Filtrar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Lista de Permisos y Vacaciones -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Historial de Solicitudes</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover" id="permisosTable">
                                <thead>
                                    <tr>
                                        <th>Tipo</th>
                                        <th>Fecha Inicio</th>
                                        <th>Fecha Fin</th>
                                        <th>Días</th>
                                        <th>Estado</th>
                                        <th>Motivo</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Solicitud 1 -->
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <i class="bi bi-calendar-heart text-primary me-2"></i>
                                                <div>
                                                    <h6 class="mb-0">Vacaciones</h6>
                                                    <small class="text-muted">Recreativas</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>20/12/2025</td>
                                        <td>27/12/2025</td>
                                        <td><span class="badge bg-info">8 días</span></td>
                                        <td><span class="badge bg-warning">PENDIENTE</span></td>
                                        <td>Vacaciones de fin de año con la familia</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <button class="btn btn-sm btn-outline-primary" onclick="verPermiso(1)" title="Ver detalles">
                                                    <i class="bi bi-eye"></i>
                                                </button>
                                                <button class="btn btn-sm btn-outline-warning" onclick="editarPermiso(1)" title="Editar">
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                                <button class="btn btn-sm btn-outline-danger" onclick="cancelarPermiso(1)" title="Cancelar">
                                                    <i class="bi bi-x-circle"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <!-- Solicitud 2 -->
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <i class="bi bi-heart-pulse text-success me-2"></i>
                                                <div>
                                                    <h6 class="mb-0">Permiso Médico</h6>
                                                    <small class="text-muted">Consulta especialista</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>15/08/2025</td>
                                        <td>15/08/2025</td>
                                        <td><span class="badge bg-secondary">1 día</span></td>
                                        <td><span class="badge bg-success">APROBADO</span></td>
                                        <td>Consulta con cardiólogo</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <button class="btn btn-sm btn-outline-primary" onclick="verPermiso(2)" title="Ver detalles">
                                                    <i class="bi bi-eye"></i>
                                                </button>
                                                <button class="btn btn-sm btn-outline-info" onclick="descargarPermiso(2)" title="Descargar">
                                                    <i class="bi bi-download"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <!-- Solicitud 3 -->
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <i class="bi bi-mortarboard text-warning me-2"></i>
                                                <div>
                                                    <h6 class="mb-0">Permiso de Estudios</h6>
                                                    <small class="text-muted">Examen final</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>10/09/2025</td>
                                        <td>10/09/2025</td>
                                        <td><span class="badge bg-secondary">1 día</span></td>
                                        <td><span class="badge bg-success">APROBADO</span></td>
                                        <td>Examen final de maestría</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <button class="btn btn-sm btn-outline-primary" onclick="verPermiso(3)" title="Ver detalles">
                                                    <i class="bi bi-eye"></i>
                                                </button>
                                                <button class="btn btn-sm btn-outline-info" onclick="descargarPermiso(3)" title="Descargar">
                                                    <i class="bi bi-download"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <!-- Solicitud 4 -->
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <i class="bi bi-person-heart text-info me-2"></i>
                                                <div>
                                                    <h6 class="mb-0">Permiso Personal</h6>
                                                    <small class="text-muted">Trámite familiar</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>25/08/2025</td>
                                        <td>25/08/2025</td>
                                        <td><span class="badge bg-secondary">0.5 días</span></td>
                                        <td><span class="badge bg-warning">PENDIENTE</span></td>
                                        <td>Trámite de pasaporte</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <button class="btn btn-sm btn-outline-primary" onclick="verPermiso(4)" title="Ver detalles">
                                                    <i class="bi bi-eye"></i>
                                                </button>
                                                <button class="btn btn-sm btn-outline-warning" onclick="editarPermiso(4)" title="Editar">
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                                <button class="btn btn-sm btn-outline-danger" onclick="cancelarPermiso(4)" title="Cancelar">
                                                    <i class="bi bi-x-circle"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <!-- Solicitud 5 -->
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <i class="bi bi-calendar-heart text-primary me-2"></i>
                                                <div>
                                                    <h6 class="mb-0">Vacaciones</h6>
                                                    <small class="text-muted">Semana Santa</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>14/04/2025</td>
                                        <td>18/04/2025</td>
                                        <td><span class="badge bg-info">5 días</span></td>
                                        <td><span class="badge bg-success">COMPLETADO</span></td>
                                        <td>Viaje familiar a la costa</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <button class="btn btn-sm btn-outline-primary" onclick="verPermiso(5)" title="Ver detalles">
                                                    <i class="bi bi-eye"></i>
                                                </button>
                                                <button class="btn btn-sm btn-outline-info" onclick="descargarPermiso(5)" title="Descargar">
                                                    <i class="bi bi-download"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Paginación -->
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <div>
                                <small class="text-muted">Mostrando 1-5 de 5 solicitudes</small>
                            </div>
                            <nav>
                                <ul class="pagination pagination-sm mb-0">
                                    <li class="page-item disabled">
                                        <span class="page-link">Anterior</span>
                                    </li>
                                    <li class="page-item active">
                                        <span class="page-link">1</span>
                                    </li>
                                    <li class="page-item disabled">
                                        <span class="page-link">Siguiente</span>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Nueva Solicitud -->
<div class="modal fade" id="nuevoPermisoModal" tabindex="-1" aria-labelledby="nuevoPermisoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="nuevoPermisoModalLabel">Nueva Solicitud de Permiso/Vacaciones</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= base_url('empleado/guardar-permiso-vacaciones') ?>" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tipo de Solicitud <span class="text-danger">*</span></label>
                            <select class="form-select" name="tipo_solicitud" required>
                                <option value="">Seleccionar tipo</option>
                                <option value="VACACIONES">Vacaciones</option>
                                <option value="PERMISO_PERSONAL">Permiso Personal</option>
                                <option value="PERMISO_MEDICO">Permiso Médico</option>
                                <option value="PERMISO_ESTUDIOS">Permiso de Estudios</option>
                                <option value="PERMISO_MATERNAL">Permiso Maternal</option>
                                <option value="PERMISO_PATERNAL">Permiso Paternal</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Prioridad <span class="text-danger">*</span></label>
                            <select class="form-select" name="prioridad" required>
                                <option value="">Seleccionar prioridad</option>
                                <option value="BAJA">Baja</option>
                                <option value="MEDIA">Media</option>
                                <option value="ALTA">Alta</option>
                                <option value="URGENTE">Urgente</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Fecha de Inicio <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" name="fecha_inicio" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Fecha de Fin <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" name="fecha_fin" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Duración en Días</label>
                            <input type="number" class="form-control" name="duracion_dias" min="0.5" step="0.5" placeholder="Ej: 5, 0.5, 1.5">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Hora de Inicio (si es medio día)</label>
                            <input type="time" class="form-control" name="hora_inicio">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Motivo Detallado <span class="text-danger">*</span></label>
                            <textarea class="form-control" name="motivo" rows="3" required placeholder="Describe detalladamente el motivo de tu solicitud..."></textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Plan de Trabajo Durante Ausencia</label>
                            <textarea class="form-control" name="plan_trabajo" rows="2" placeholder="¿Cómo planeas organizar tu trabajo durante tu ausencia? ¿Quién te reemplazará?"></textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Documentos de Soporte</label>
                            <input type="file" class="form-control" name="documentos_soporte" multiple accept=".pdf,.doc,.docx,.jpg,.png">
                            <small class="text-muted">Adjunta certificados médicos, documentos oficiales, o cualquier evidencia que respalde tu solicitud</small>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Observaciones Adicionales</label>
                            <textarea class="form-control" name="observaciones" rows="2" placeholder="Cualquier información adicional que consideres relevante..."></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-send me-1"></i>Enviar Solicitud
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Ver Detalles -->
<div class="modal fade" id="verPermisoModal" tabindex="-1" aria-labelledby="verPermisoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="verPermisoModalLabel">Detalles de la Solicitud</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="detallesPermiso">
                    <!-- Los detalles se cargarán aquí dinámicamente -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-info" onclick="descargarPermiso()">
                    <i class="bi bi-download me-1"></i>Descargar Permiso
                </button>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    function filtrarPermisos() {
        const tipo = document.getElementById('filtroTipo').value;
        const estado = document.getElementById('filtroEstado').value;
        const fechaInicio = document.getElementById('fechaInicio').value;
        const fechaFin = document.getElementById('fechaFin').value;
        
        // Aquí iría la lógica de filtrado
        console.log('Filtros aplicados:', { tipo, estado, fechaInicio, fechaFin });
        
        // Mostrar mensaje temporal
        alert('Filtros aplicados. Funcionalidad de filtrado en desarrollo.');
    }
    
    function verPermiso(id) {
        // Simulación de datos del permiso
        const permisos = {
            1: {
                tipo: 'VACACIONES',
                fechaInicio: '20/12/2025',
                fechaFin: '27/12/2025',
                duracion: '8 días',
                estado: 'PENDIENTE',
                prioridad: 'MEDIA',
                motivo: 'Vacaciones de fin de año con la familia. Planeamos viajar a la costa para celebrar las fiestas.',
                planTrabajo: 'Delegaré mis responsabilidades a María González. Los proyectos en curso estarán documentados y actualizados.',
                documentos: ['solicitud_vacaciones.pdf'],
                observaciones: 'Solicito aprobación anticipada para poder planificar el viaje familiar'
            },
            2: {
                tipo: 'PERMISO_MEDICO',
                fechaInicio: '15/08/2025',
                fechaFin: '15/08/2025',
                duracion: '1 día',
                estado: 'APROBADO',
                prioridad: 'ALTA',
                motivo: 'Consulta con cardiólogo para revisión de rutina y ajuste de medicación.',
                planTrabajo: 'Coordinaré con Juan Pérez para que cubra mis clases. Los materiales estarán preparados con anticipación.',
                documentos: ['cita_medica.pdf', 'certificado_medico.pdf'],
                observaciones: 'Consulta programada para las 10:00 AM. Estaré disponible por teléfono en caso de emergencia.'
            }
        };
        
        const permiso = permisos[id];
        if (permiso) {
            document.getElementById('detallesPermiso').innerHTML = `
                <div class="row">
                    <div class="col-md-6">
                        <h6><i class="bi bi-tag text-info me-2"></i>Tipo de Solicitud</h6>
                        <span class="badge bg-info">${permiso.tipo}</span>
                    </div>
                    <div class="col-md-6">
                        <h6><i class="bi bi-exclamation-triangle text-danger me-2"></i>Prioridad</h6>
                        <span class="badge bg-danger">${permiso.prioridad}</span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <h6><i class="bi bi-calendar-plus text-primary me-2"></i>Fecha de Inicio</h6>
                        <p>${permiso.fechaInicio}</p>
                    </div>
                    <div class="col-md-6">
                        <h6><i class="bi bi-calendar-check text-success me-2"></i>Fecha de Fin</h6>
                        <p>${permiso.fechaFin}</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <h6><i class="bi bi-clock text-warning me-2"></i>Duración</h6>
                        <span class="badge bg-info">${permiso.duracion}</span>
                    </div>
                    <div class="col-md-6">
                        <h6><i class="bi bi-check-circle text-success me-2"></i>Estado</h6>
                        <span class="badge bg-success">${permiso.estado}</span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <h6><i class="bi bi-chat-text text-primary me-2"></i>Motivo</h6>
                        <p>${permiso.motivo}</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <h6><i class="bi bi-briefcase text-warning me-2"></i>Plan de Trabajo Durante Ausencia</h6>
                        <p>${permiso.planTrabajo}</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <h6><i class="bi bi-paperclip text-secondary me-2"></i>Documentos Adjuntos</h6>
                        <ul class="list-unstyled">
                            ${permiso.documentos.map(doc => `<li><i class="bi bi-file-earmark-text me-2"></i>${doc}</li>`).join('')}
                        </ul>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <h6><i class="bi bi-info-circle text-warning me-2"></i>Observaciones</h6>
                        <p>${permiso.observaciones}</p>
                    </div>
                </div>
            `;
            
            $('#verPermisoModal').modal('show');
        }
    }
    
    function editarPermiso(id) {
        // Aquí iría la lógica para editar el permiso
        alert(`Editar permiso ID: ${id}. Funcionalidad en desarrollo.`);
    }
    
    function cancelarPermiso(id) {
        if (confirm('¿Estás seguro de que deseas cancelar esta solicitud?')) {
            // Aquí iría la lógica para cancelar el permiso
            alert(`Permiso ID: ${id} cancelado. Funcionalidad en desarrollo.`);
        }
    }
    
    function descargarPermiso(id) {
        // Aquí iría la lógica para descargar el permiso
        alert(`Descargando permiso ID: ${id}. Funcionalidad en desarrollo.`);
    }
</script>
<?= $this->endSection() ?>
