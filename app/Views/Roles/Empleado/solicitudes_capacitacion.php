<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>

<div class="page-wrapper">
    <div class="page-content">
        <!-- Header -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <h4 class="mb-0"><i class="bi bi-clipboard-data"></i> Mis Solicitudes de Capacitación</h4>
                    <div class="page-title-right">
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#nuevaSolicitudModal">
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
                                <h4 class="mb-0">5</h4>
                                <p class="text-muted mb-0">Total Solicitudes</p>
                            </div>
                            <div class="text-primary">
                                <i class="bi bi-clipboard-data fs-1"></i>
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
                                <h4 class="mb-0">2</h4>
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
                                    <label class="form-label">Estado</label>
                                    <select class="form-select" id="filtroEstado">
                                        <option value="">Todos los estados</option>
                                        <option value="PENDIENTE">Pendiente</option>
                                        <option value="APROBADA">Aprobada</option>
                                        <option value="RECHAZADA">Rechazada</option>
                                        <option value="EN_PROCESO">En Proceso</option>
                                        <option value="COMPLETADA">Completada</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label">Tipo de Capacitación</label>
                                    <select class="form-select" id="filtroTipo">
                                        <option value="">Todos los tipos</option>
                                        <option value="TECNICA">Técnica</option>
                                        <option value="SOFT_SKILLS">Soft Skills</option>
                                        <option value="GESTION">Gestión</option>
                                        <option value="LIDERAZGO">Liderazgo</option>
                                        <option value="ESPECIALIZACION">Especialización</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Buscar</label>
                                    <input type="text" class="form-control" id="buscarSolicitud" placeholder="Título, institución, área...">
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

        <!-- Lista de Solicitudes -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Mis Solicitudes de Capacitación</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover" id="solicitudesTable">
                                <thead>
                                    <tr>
                                        <th>Título de la Capacitación</th>
                                        <th>Tipo</th>
                                        <th>Institución</th>
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
                                                <i class="bi bi-mortarboard text-primary me-2"></i>
                                                <div>
                                                    <h6 class="mb-0">Desarrollo Web con React</h6>
                                                    <small class="text-muted">Plataforma: Udemy</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td><span class="badge bg-info">TÉCNICA</span></td>
                                        <td>Udemy</td>
                                        <td>15/08/2025</td>
                                        <td><span class="badge bg-warning">PENDIENTE</span></td>
                                        <td><span class="badge bg-danger">ALTA</span></td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <button class="btn btn-sm btn-outline-primary" onclick="verSolicitud(1)" title="Ver detalles">
                                                    <i class="bi bi-eye"></i>
                                                </button>
                                                <button class="btn btn-sm btn-outline-warning" onclick="editarSolicitud(1)" title="Editar">
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                                <button class="btn btn-sm btn-outline-danger" onclick="cancelarSolicitud(1)" title="Cancelar">
                                                    <i class="bi bi-x-circle"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <!-- Solicitud 2 -->
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <i class="bi bi-people text-success me-2"></i>
                                                <div>
                                                    <h6 class="mb-0">Liderazgo y Gestión de Equipos</h6>
                                                    <small class="text-muted">Plataforma: Coursera</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td><span class="badge bg-success">LIDERAZGO</span></td>
                                        <td>Coursera</td>
                                        <td>10/08/2025</td>
                                        <td><span class="badge bg-success">APROBADA</span></td>
                                        <td><span class="badge bg-warning">MEDIA</span></td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <button class="btn btn-sm btn-outline-primary" onclick="verSolicitud(2)" title="Ver detalles">
                                                    <i class="bi bi-eye"></i>
                                                </button>
                                                <button class="btn btn-sm btn-outline-info" onclick="seguirProgreso(2)" title="Seguir progreso">
                                                    <i class="bi bi-graph-up"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <!-- Solicitud 3 -->
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <i class="bi bi-gear text-warning me-2"></i>
                                                <div>
                                                    <h6 class="mb-0">Gestión de Proyectos Ágiles</h6>
                                                    <small class="text-muted">Plataforma: LinkedIn Learning</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td><span class="badge bg-warning">GESTIÓN</span></td>
                                        <td>LinkedIn Learning</td>
                                        <td>05/08/2025</td>
                                        <td><span class="badge bg-success">APROBADA</span></td>
                                        <td><span class="badge bg-info">BAJA</span></td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <button class="btn btn-sm btn-outline-primary" onclick="verSolicitud(3)" title="Ver detalles">
                                                    <i class="bi bi-eye"></i>
                                                </button>
                                                <button class="btn btn-sm btn-outline-info" onclick="seguirProgreso(3)" title="Seguir progreso">
                                                    <i class="bi bi-graph-up"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <!-- Solicitud 4 -->
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <i class="bi bi-chat-dots text-info me-2"></i>
                                                <div>
                                                    <h6 class="mb-0">Comunicación Efectiva en el Trabajo</h6>
                                                    <small class="text-muted">Plataforma: Skillshare</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td><span class="badge bg-primary">SOFT_SKILLS</span></td>
                                        <td>Skillshare</td>
                                        <td>01/08/2025</td>
                                        <td><span class="badge bg-danger">RECHAZADA</span></td>
                                        <td><span class="badge bg-warning">MEDIA</span></td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <button class="btn btn-sm btn-outline-primary" onclick="verSolicitud(4)" title="Ver detalles">
                                                    <i class="bi bi-eye"></i>
                                                </button>
                                                <button class="btn btn-sm btn-outline-warning" onclick="editarSolicitud(4)" title="Editar y reenviar">
                                                    <i class="bi bi-arrow-clockwise"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <!-- Solicitud 5 -->
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <i class="bi bi-code-slash text-dark me-2"></i>
                                                <div>
                                                    <h6 class="mb-0">Python para Análisis de Datos</h6>
                                                    <small class="text-muted">Plataforma: DataCamp</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td><span class="badge bg-info">TÉCNICA</span></td>
                                        <td>DataCamp</td>
                                        <td>28/07/2025</td>
                                        <td><span class="badge bg-warning">PENDIENTE</span></td>
                                        <td><span class="badge bg-danger">ALTA</span></td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <button class="btn btn-sm btn-outline-primary" onclick="verSolicitud(5)" title="Ver detalles">
                                                    <i class="bi bi-eye"></i>
                                                </button>
                                                <button class="btn btn-sm btn-outline-warning" onclick="editarSolicitud(5)" title="Editar">
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                                <button class="btn btn-sm btn-outline-danger" onclick="cancelarSolicitud(5)" title="Cancelar">
                                                    <i class="bi bi-x-circle"></i>
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
<div class="modal fade" id="nuevaSolicitudModal" tabindex="-1" aria-labelledby="nuevaSolicitudModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="nuevaSolicitudModalLabel">Nueva Solicitud de Capacitación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= base_url('empleado/guardar-solicitud-capacitacion') ?>" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-8 mb-3">
                            <label class="form-label">Título de la Capacitación <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="titulo" required placeholder="Ej: Desarrollo Web con React">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Tipo de Capacitación <span class="text-danger">*</span></label>
                            <select class="form-select" name="tipo_capacitacion" required>
                                <option value="">Seleccionar tipo</option>
                                <option value="TECNICA">Técnica</option>
                                <option value="SOFT_SKILLS">Soft Skills</option>
                                <option value="GESTION">Gestión</option>
                                <option value="LIDERAZGO">Liderazgo</option>
                                <option value="ESPECIALIZACION">Especialización</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Institución/Plataforma <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="institucion" required placeholder="Ej: Udemy, Coursera, Universidad...">
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
                            <label class="form-label">Fecha de Inicio Deseada</label>
                            <input type="date" class="form-control" name="fecha_inicio_deseada">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Duración Estimada</label>
                            <input type="text" class="form-control" name="duracion_estimada" placeholder="Ej: 40 horas, 8 semanas...">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Costo Estimado</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" class="form-control" name="costo_estimado" step="0.01" placeholder="0.00">
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Modalidad</label>
                            <select class="form-select" name="modalidad">
                                <option value="">Seleccionar modalidad</option>
                                <option value="ONLINE">Online</option>
                                <option value="PRESENCIAL">Presencial</option>
                                <option value="HIBRIDA">Híbrida</option>
                                <option value="SELF_PACED">Autodidacta</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Justificación <span class="text-danger">*</span></label>
                            <textarea class="form-control" name="justificacion" rows="3" required placeholder="Explica por qué necesitas esta capacitación y cómo beneficiará tu desarrollo profesional..."></textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Objetivos de Aprendizaje</label>
                            <textarea class="form-control" name="objetivos_aprendizaje" rows="2" placeholder="¿Qué esperas aprender de esta capacitación?"></textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Documentos de Soporte</label>
                            <input type="file" class="form-control" name="documentos_soporte" multiple accept=".pdf,.doc,.docx,.jpg,.png">
                            <small class="text-muted">Puedes adjuntar catálogos, programas, o cualquier documento que respalde tu solicitud</small>
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
<div class="modal fade" id="verSolicitudModal" tabindex="-1" aria-labelledby="verSolicitudModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="verSolicitudModalLabel">Detalles de la Solicitud</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="detallesSolicitud">
                    <!-- Los detalles se cargarán aquí dinámicamente -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-info" onclick="descargarComprobante()">
                    <i class="bi bi-download me-1"></i>Descargar Comprobante
                </button>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    function filtrarSolicitudes() {
        const estado = document.getElementById('filtroEstado').value;
        const tipo = document.getElementById('filtroTipo').value;
        const busqueda = document.getElementById('buscarSolicitud').value;
        
        // Aquí iría la lógica de filtrado
        console.log('Filtros aplicados:', { estado, tipo, busqueda });
        
        // Mostrar mensaje temporal
        alert('Filtros aplicados. Funcionalidad de filtrado en desarrollo.');
    }
    
    function verSolicitud(id) {
        // Simulación de datos de la solicitud
        const solicitudes = {
            1: {
                titulo: 'Desarrollo Web con React',
                tipo: 'TÉCNICA',
                institucion: 'Udemy',
                fechaSolicitud: '15/08/2025',
                estado: 'PENDIENTE',
                prioridad: 'ALTA',
                justificacion: 'Necesito actualizar mis conocimientos en desarrollo frontend para mejorar la calidad de los proyectos que desarrollo en la empresa.',
                objetivos: 'Aprender React Hooks, Context API, y mejores prácticas de desarrollo moderno',
                costo: '$29.99',
                duracion: '40 horas',
                modalidad: 'ONLINE',
                documentos: ['catalogo_curso.pdf', 'programa_react.pdf']
            },
            2: {
                titulo: 'Liderazgo y Gestión de Equipos',
                tipo: 'LIDERAZGO',
                institucion: 'Coursera',
                fechaSolicitud: '10/08/2025',
                estado: 'APROBADA',
                prioridad: 'MEDIA',
                justificacion: 'Como líder de proyecto, necesito mejorar mis habilidades de gestión de equipos para optimizar la productividad del grupo.',
                objetivos: 'Desarrollar habilidades de comunicación efectiva, resolución de conflictos y motivación de equipos',
                costo: '$49.00',
                duracion: '8 semanas',
                modalidad: 'ONLINE',
                documentos: ['certificado_aprobacion.pdf', 'programa_liderazgo.pdf']
            }
        };
        
        const solicitud = solicitudes[id];
        if (solicitud) {
            document.getElementById('detallesSolicitud').innerHTML = `
                <div class="row">
                    <div class="col-md-6">
                        <h6><i class="bi bi-mortarboard text-primary me-2"></i>Título</h6>
                        <p>${solicitud.titulo}</p>
                    </div>
                    <div class="col-md-6">
                        <h6><i class="bi bi-tag text-info me-2"></i>Tipo</h6>
                        <span class="badge bg-info">${solicitud.tipo}</span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <h6><i class="bi bi-building text-success me-2"></i>Institución</h6>
                        <p>${solicitud.institucion}</p>
                    </div>
                    <div class="col-md-6">
                        <h6><i class="bi bi-calendar text-warning me-2"></i>Fecha de Solicitud</h6>
                        <p>${solicitud.fechaSolicitud}</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <h6><i class="bi bi-check-circle text-success me-2"></i>Estado</h6>
                        <span class="badge bg-success">${solicitud.estado}</span>
                    </div>
                    <div class="col-md-6">
                        <h6><i class="bi bi-exclamation-triangle text-danger me-2"></i>Prioridad</h6>
                        <span class="badge bg-danger">${solicitud.prioridad}</span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <h6><i class="bi bi-currency-dollar text-success me-2"></i>Costo</h6>
                        <p>${solicitud.costo}</p>
                    </div>
                    <div class="col-md-6">
                        <h6><i class="bi bi-clock text-warning me-2"></i>Duración</h6>
                        <p>${solicitud.duracion}</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <h6><i class="bi bi-laptop text-info me-2"></i>Modalidad</h6>
                        <p>${solicitud.modalidad}</p>
                    </div>
                    <div class="col-md-6">
                        <h6><i class="bi bi-calendar-check text-primary me-2"></i>Fecha de Inicio Deseada</h6>
                        <p>Por definir</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <h6><i class="bi bi-chat-text text-primary me-2"></i>Justificación</h6>
                        <p>${solicitud.justificacion}</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <h6><i class="bi bi-target text-warning me-2"></i>Objetivos de Aprendizaje</h6>
                        <p>${solicitud.objetivos}</p>
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
            `;
            
            $('#verSolicitudModal').modal('show');
        }
    }
    
    function editarSolicitud(id) {
        // Aquí iría la lógica para editar la solicitud
        alert(`Editar solicitud ID: ${id}. Funcionalidad en desarrollo.`);
    }
    
    function cancelarSolicitud(id) {
        if (confirm('¿Estás seguro de que deseas cancelar esta solicitud?')) {
            // Aquí iría la lógica para cancelar la solicitud
            alert(`Solicitud ID: ${id} cancelada. Funcionalidad en desarrollo.`);
        }
    }
    
    function seguirProgreso(id) {
        // Aquí iría la lógica para seguir el progreso
        alert(`Siguiendo progreso de solicitud ID: ${id}. Funcionalidad en desarrollo.`);
    }
    
    function descargarComprobante() {
        // Aquí iría la lógica para descargar el comprobante
        alert('Descargando comprobante. Funcionalidad en desarrollo.');
    }
</script>
<?= $this->endSection() ?>
