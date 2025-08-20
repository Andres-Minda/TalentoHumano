<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>

<div class="page-wrapper">
    <div class="page-content">
        <!-- Header -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <h4 class="mb-0"><i class="bi bi-file-earmark-text"></i> Mis Solicitudes Generales</h4>
                    <div class="page-title-right">
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#nuevaSolicitudGeneralModal">
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
                                <h4 class="mb-0">8</h4>
                                <p class="text-muted mb-0">Total Solicitudes</p>
                            </div>
                            <div class="text-primary">
                                <i class="bi bi-file-earmark-text fs-1"></i>
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
                                <p class="text-muted mb-0">Pendientes</p>
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
                                <h4 class="mb-0">4</h4>
                                <p class="text-muted mb-0">Aprobadas</p>
                            </div>
                            <div class="text-success">
                                <i class="bi bi-check-circle fs-1"></i>
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
                                <h4 class="mb-0">1</h4>
                                <p class="text-muted mb-0">Rechazadas</p>
                            </div>
                            <div class="text-danger">
                                <i class="bi bi-x-circle fs-1"></i>
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
                                    <label class="form-label">Tipo de Solicitud</label>
                                    <select class="form-select" id="filtroTipo">
                                        <option value="">Todos los tipos</option>
                                        <option value="CERTIFICACION_LABORAL">Certificación Laboral</option>
                                        <option value="CONSTANCIA_TRABAJO">Constancia de Trabajo</option>
                                        <option value="CARTA_RECOMENDACION">Carta de Recomendación</option>
                                        <option value="CAMBIO_HORARIO">Cambio de Horario</option>
                                        <option value="CAMBIO_SEDE">Cambio de Sede</option>
                                        <option value="REVISION_SALARIAL">Revisión Salarial</option>
                                        <option value="RECONOCIMIENTO">Reconocimiento</option>
                                        <option value="OTROS">Otros</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label">Estado</label>
                                    <select class="form-select" id="filtroEstado">
                                        <option value="">Todos los estados</option>
                                        <option value="PENDIENTE">Pendiente</option>
                                        <option value="EN_REVISION">En Revisión</option>
                                        <option value="APROBADA">Aprobada</option>
                                        <option value="RECHAZADA">Rechazada</option>
                                        <option value="COMPLETADA">Completada</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Buscar</label>
                                    <input type="text" class="form-control" id="buscarSolicitud" placeholder="Buscar por asunto, descripción...">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="mb-3">
                                    <label class="form-label">&nbsp;</label>
                                    <button class="btn btn-outline-primary w-100" onclick="filtrarSolicitudes()">
                                        <i class="bi bi-search me-1"></i>Filtrar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Lista de Solicitudes Generales -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Historial de Solicitudes</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover" id="solicitudesGeneralesTable">
                                <thead>
                                    <tr>
                                        <th>Tipo de Solicitud</th>
                                        <th>Asunto</th>
                                        <th>Fecha Solicitud</th>
                                        <th>Estado</th>
                                        <th>Prioridad</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Solicitud 1 -->
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <i class="bi bi-file-earmark-check text-success me-2"></i>
                                                <div>
                                                    <h6 class="mb-0">Certificación Laboral</h6>
                                                    <small class="text-muted">Documento oficial</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>Certificación laboral para trámite bancario</td>
                                        <td>18/08/2025</td>
                                        <td><span class="badge bg-warning">PENDIENTE</span></td>
                                        <td><span class="badge bg-danger">ALTA</span></td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <button class="btn btn-sm btn-outline-primary" onclick="verSolicitudGeneral(1)" title="Ver detalles">
                                                    <i class="bi bi-eye"></i>
                                                </button>
                                                <button class="btn btn-sm btn-outline-warning" onclick="editarSolicitudGeneral(1)" title="Editar">
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                                <button class="btn btn-sm btn-outline-danger" onclick="cancelarSolicitudGeneral(1)" title="Cancelar">
                                                    <i class="bi bi-x-circle"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <!-- Solicitud 2 -->
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <i class="bi bi-file-earmark-text text-info me-2"></i>
                                                <div>
                                                    <h6 class="mb-0">Constancia de Trabajo</h6>
                                                    <small class="text-muted">Documento laboral</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>Constancia de trabajo para visa de viaje</td>
                                        <td>15/08/2025</td>
                                        <td><span class="badge bg-success">APROBADA</span></td>
                                        <td><span class="badge bg-warning">MEDIA</span></td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <button class="btn btn-sm btn-outline-primary" onclick="verSolicitudGeneral(2)" title="Ver detalles">
                                                    <i class="bi bi-eye"></i>
                                                </button>
                                                <button class="btn btn-sm btn-outline-info" onclick="descargarDocumento(2)" title="Descargar">
                                                    <i class="bi bi-download"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <!-- Solicitud 3 -->
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <i class="bi bi-envelope-heart text-primary me-2"></i>
                                                <div>
                                                    <h6 class="mb-0">Carta de Recomendación</h6>
                                                    <small class="text-muted">Referencias</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>Carta de recomendación para estudios de postgrado</td>
                                        <td>12/08/2025</td>
                                        <td><span class="badge bg-success">APROBADA</span></td>
                                        <td><span class="badge bg-info">BAJA</span></td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <button class="btn btn-sm btn-outline-primary" onclick="verSolicitudGeneral(3)" title="Ver detalles">
                                                    <i class="bi bi-eye"></i>
                                                </button>
                                                <button class="btn btn-sm btn-outline-info" onclick="descargarDocumento(3)" title="Descargar">
                                                    <i class="bi bi-download"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <!-- Solicitud 4 -->
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <i class="bi bi-clock-history text-warning me-2"></i>
                                                <div>
                                                    <h6 class="mb-0">Cambio de Horario</h6>
                                                    <small class="text-muted">Modificación laboral</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>Solicitud de cambio de horario por estudios</td>
                                        <td>10/08/2025</td>
                                        <td><span class="badge bg-info">EN_REVISION</span></td>
                                        <td><span class="badge bg-danger">ALTA</span></td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <button class="btn btn-sm btn-outline-primary" onclick="verSolicitudGeneral(4)" title="Ver detalles">
                                                    <i class="bi bi-eye"></i>
                                                </button>
                                                <button class="btn btn-sm btn-outline-warning" onclick="editarSolicitudGeneral(4)" title="Editar">
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <!-- Solicitud 5 -->
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <i class="bi bi-currency-dollar text-success me-2"></i>
                                                <div>
                                                    <h6 class="mb-0">Revisión Salarial</h6>
                                                    <small class="text-muted">Solicitud económica</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>Solicitud de revisión salarial anual</td>
                                        <td>08/08/2025</td>
                                        <td><span class="badge bg-warning">PENDIENTE</span></td>
                                        <td><span class="badge bg-danger">ALTA</span></td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <button class="btn btn-sm btn-outline-primary" onclick="verSolicitudGeneral(5)" title="Ver detalles">
                                                    <i class="bi bi-eye"></i>
                                                </button>
                                                <button class="btn btn-sm btn-outline-warning" onclick="editarSolicitudGeneral(5)" title="Editar">
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                                <button class="btn btn-sm btn-outline-danger" onclick="cancelarSolicitudGeneral(5)" title="Cancelar">
                                                    <i class="bi bi-x-circle"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <!-- Solicitud 6 -->
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <i class="bi bi-geo-alt text-danger me-2"></i>
                                                <div>
                                                    <h6 class="mb-0">Cambio de Sede</h6>
                                                    <small class="text-muted">Traslado laboral</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>Solicitud de traslado a sede Quito</td>
                                        <td>05/08/2025</td>
                                        <td><span class="badge bg-danger">RECHAZADA</span></td>
                                        <td><span class="badge bg-warning">MEDIA</span></td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <button class="btn btn-sm btn-outline-primary" onclick="verSolicitudGeneral(6)" title="Ver detalles">
                                                    <i class="bi bi-eye"></i>
                                                </button>
                                                <button class="btn btn-sm btn-outline-warning" onclick="apelarSolicitud(6)" title="Apelar decisión">
                                                    <i class="bi bi-arrow-repeat"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <!-- Solicitud 7 -->
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <i class="bi bi-award text-warning me-2"></i>
                                                <div>
                                                    <h6 class="mb-0">Reconocimiento</h6>
                                                    <small class="text-muted">Solicitud especial</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>Solicitud de reconocimiento por proyecto exitoso</td>
                                        <td>02/08/2025</td>
                                        <td><span class="badge bg-success">APROBADA</span></td>
                                        <td><span class="badge bg-info">BAJA</span></td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <button class="btn btn-sm btn-outline-primary" onclick="verSolicitudGeneral(7)" title="Ver detalles">
                                                    <i class="bi bi-eye"></i>
                                                </button>
                                                <button class="btn btn-sm btn-outline-info" onclick="descargarDocumento(7)" title="Descargar">
                                                    <i class="bi bi-download"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <!-- Solicitud 8 -->
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <i class="bi bi-question-circle text-secondary me-2"></i>
                                                <div>
                                                    <h6 class="mb-0">Otros</h6>
                                                    <small class="text-muted">Solicitud general</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>Consulta sobre beneficios adicionales</td>
                                        <td>30/07/2025</td>
                                        <td><span class="badge bg-success">COMPLETADA</span></td>
                                        <td><span class="badge bg-info">BAJA</span></td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <button class="btn btn-sm btn-outline-primary" onclick="verSolicitudGeneral(8)" title="Ver detalles">
                                                    <i class="bi bi-eye"></i>
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
                                <small class="text-muted">Mostrando 1-8 de 8 solicitudes</small>
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

<!-- Modal Nueva Solicitud General -->
<div class="modal fade" id="nuevaSolicitudGeneralModal" tabindex="-1" aria-labelledby="nuevaSolicitudGeneralModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="nuevaSolicitudGeneralModalLabel">Nueva Solicitud General</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= base_url('empleado/guardar-solicitud-general') ?>" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tipo de Solicitud <span class="text-danger">*</span></label>
                            <select class="form-select" name="tipo_solicitud" required>
                                <option value="">Seleccionar tipo</option>
                                <option value="CERTIFICACION_LABORAL">Certificación Laboral</option>
                                <option value="CONSTANCIA_TRABAJO">Constancia de Trabajo</option>
                                <option value="CARTA_RECOMENDACION">Carta de Recomendación</option>
                                <option value="CAMBIO_HORARIO">Cambio de Horario</option>
                                <option value="CAMBIO_SEDE">Cambio de Sede</option>
                                <option value="REVISION_SALARIAL">Revisión Salarial</option>
                                <option value="RECONOCIMIENTO">Reconocimiento</option>
                                <option value="OTROS">Otros</option>
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
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Asunto <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="asunto" required placeholder="Ej: Certificación laboral para trámite bancario">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Descripción Detallada <span class="text-danger">*</span></label>
                            <textarea class="form-control" name="descripcion" rows="4" required placeholder="Describe detalladamente tu solicitud, incluye motivos, fechas específicas y cualquier información relevante..."></textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Fecha Deseada de Respuesta</label>
                            <input type="date" class="form-control" name="fecha_respuesta_deseada">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Dirigido a</label>
                            <select class="form-select" name="dirigido_a">
                                <option value="">Automático (según tipo)</option>
                                <option value="RECURSOS_HUMANOS">Recursos Humanos</option>
                                <option value="DIRECCION_GENERAL">Dirección General</option>
                                <option value="JEFE_DEPARTAMENTO">Jefe de Departamento</option>
                                <option value="COORDINACION_ACADEMICA">Coordinación Académica</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Justificación</label>
                            <textarea class="form-control" name="justificacion" rows="3" placeholder="Explica por qué es necesaria esta solicitud y cómo beneficiará tu trabajo o situación laboral..."></textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Documentos de Soporte</label>
                            <input type="file" class="form-control" name="documentos_soporte" multiple accept=".pdf,.doc,.docx,.jpg,.png">
                            <small class="text-muted">Adjunta cualquier documento que respalde tu solicitud (opcional)</small>
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
<div class="modal fade" id="verSolicitudGeneralModal" tabindex="-1" aria-labelledby="verSolicitudGeneralModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="verSolicitudGeneralModalLabel">Detalles de la Solicitud</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="detallesSolicitudGeneral">
                    <!-- Los detalles se cargarán aquí dinámicamente -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-info" onclick="descargarDocumento()">
                    <i class="bi bi-download me-1"></i>Descargar Documento
                </button>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    function filtrarSolicitudes() {
        const tipo = document.getElementById('filtroTipo').value;
        const estado = document.getElementById('filtroEstado').value;
        const busqueda = document.getElementById('buscarSolicitud').value;
        
        // Aquí iría la lógica de filtrado
        console.log('Filtros aplicados:', { tipo, estado, busqueda });
        
        // Mostrar mensaje temporal
        alert('Filtros aplicados. Funcionalidad de filtrado en desarrollo.');
    }
    
    function verSolicitudGeneral(id) {
        // Simulación de datos de la solicitud
        const solicitudes = {
            1: {
                tipo: 'CERTIFICACION_LABORAL',
                asunto: 'Certificación laboral para trámite bancario',
                fechaSolicitud: '18/08/2025',
                estado: 'PENDIENTE',
                prioridad: 'ALTA',
                descripcion: 'Necesito una certificación laboral oficial para presentar en el banco como parte del proceso de solicitud de crédito hipotecario. El documento debe incluir mis datos personales, cargo actual, salario, y tiempo de servicio en la institución.',
                justificacion: 'El banco requiere este documento como parte de los requisitos obligatorios para el otorgamiento del crédito hipotecario que necesito para la compra de vivienda.',
                dirigidoA: 'RECURSOS_HUMANOS',
                fechaRespuestaDeseada: '25/08/2025',
                documentos: ['solicitud_credito.pdf', 'cotizacion_vivienda.pdf'],
                observaciones: 'Es urgente ya que el banco tiene un plazo límite para la entrega de documentos.'
            },
            2: {
                tipo: 'CONSTANCIA_TRABAJO',
                asunto: 'Constancia de trabajo para visa de viaje',
                fechaSolicitud: '15/08/2025',
                estado: 'APROBADA',
                prioridad: 'MEDIA',
                descripcion: 'Solicito una constancia de trabajo para presentar en el consulado como parte del proceso de solicitud de visa de turismo. El viaje está programado para octubre 2025.',
                justificacion: 'El consulado requiere documentación que demuestre estabilidad laboral y vínculos con el país de origen.',
                dirigidoA: 'RECURSOS_HUMANOS',
                fechaRespuestaDeseada: '22/08/2025',
                documentos: ['itinerario_viaje.pdf', 'reserva_hotel.pdf'],
                observaciones: 'El documento fue aprobado y estará listo para retiro el 20/08/2025.'
            }
        };
        
        const solicitud = solicitudes[id];
        if (solicitud) {
            document.getElementById('detallesSolicitudGeneral').innerHTML = `
                <div class="row">
                    <div class="col-md-6">
                        <h6><i class="bi bi-tag text-info me-2"></i>Tipo de Solicitud</h6>
                        <span class="badge bg-info">${solicitud.tipo}</span>
                    </div>
                    <div class="col-md-6">
                        <h6><i class="bi bi-exclamation-triangle text-danger me-2"></i>Prioridad</h6>
                        <span class="badge bg-danger">${solicitud.prioridad}</span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <h6><i class="bi bi-calendar text-warning me-2"></i>Fecha de Solicitud</h6>
                        <p>${solicitud.fechaSolicitud}</p>
                    </div>
                    <div class="col-md-6">
                        <h6><i class="bi bi-check-circle text-success me-2"></i>Estado</h6>
                        <span class="badge bg-success">${solicitud.estado}</span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <h6><i class="bi bi-chat-text text-primary me-2"></i>Asunto</h6>
                        <p>${solicitud.asunto}</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <h6><i class="bi bi-file-text text-primary me-2"></i>Descripción</h6>
                        <p>${solicitud.descripcion}</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <h6><i class="bi bi-info-circle text-warning me-2"></i>Justificación</h6>
                        <p>${solicitud.justificacion}</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <h6><i class="bi bi-person text-info me-2"></i>Dirigido a</h6>
                        <p>${solicitud.dirigidoA}</p>
                    </div>
                    <div class="col-md-6">
                        <h6><i class="bi bi-calendar-check text-primary me-2"></i>Fecha Respuesta Deseada</h6>
                        <p>${solicitud.fechaRespuestaDeseada}</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <h6><i class="bi bi-paperclip text-secondary me-2"></i>Documentos Adjuntos</h6>
                        <ul class="list-unstyled">
                            ${solicitud.documentos.map(doc => `<li><i class="bi bi-file-earmark-text me-2"></i>${doc}</li>`).join('')}
                        </ul>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <h6><i class="bi bi-chat-dots text-warning me-2"></i>Observaciones</h6>
                        <p>${solicitud.observaciones}</p>
                    </div>
                </div>
            `;
            
            $('#verSolicitudGeneralModal').modal('show');
        }
    }
    
    function editarSolicitudGeneral(id) {
        // Aquí iría la lógica para editar la solicitud
        alert(`Editar solicitud general ID: ${id}. Funcionalidad en desarrollo.`);
    }
    
    function cancelarSolicitudGeneral(id) {
        if (confirm('¿Estás seguro de que deseas cancelar esta solicitud?')) {
            // Aquí iría la lógica para cancelar la solicitud
            alert(`Solicitud general ID: ${id} cancelada. Funcionalidad en desarrollo.`);
        }
    }
    
    function apelarSolicitud(id) {
        if (confirm('¿Deseas apelar la decisión de esta solicitud?')) {
            // Aquí iría la lógica para apelar la decisión
            alert(`Apelación de solicitud ID: ${id} enviada. Funcionalidad en desarrollo.`);
        }
    }
    
    function descargarDocumento(id) {
        // Aquí iría la lógica para descargar el documento
        alert(`Descargando documento de solicitud ID: ${id}. Funcionalidad en desarrollo.`);
    }
</script>
<?= $this->endSection() ?>
