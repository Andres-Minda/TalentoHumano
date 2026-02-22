<?php
$sidebar = 'sidebar_empleado';
?>

<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>
<div class="page-wrapper">
    <div class="page-content">
        <!-- Breadcrumb -->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Solicitudes</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
                        <li class="breadcrumb-item active" aria-current="page">Solicitudes de Capacitación</li>
                    </ol>
                </nav>
            </div>
        </div>

        <!-- Welcome Section -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <i class="ti ti-clipboard-data fs-1 text-primary"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h4 class="mb-1">Solicitudes de Capacitación</h4>
                                <p class="mb-0 text-muted">Gestiona tus solicitudes de capacitación profesional</p>
                            </div>
                            <div class="flex-shrink-0">
                                <button type="button" class="btn btn-primary" onclick="abrirModalNuevaSolicitud()">
                                    <i class="ti ti-plus"></i> Nueva Solicitud
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row">
            <div class="col-xl-3 col-md-6">
                <div class="card radius-10 bg-primary">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <i class="ti ti-clipboard-list fs-1 text-white"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h4 class="mb-0 text-white" id="total_solicitudes">0</h4>
                                <p class="mb-0 text-white-50">Total Solicitudes</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card radius-10 bg-success">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <i class="ti ti-check fs-1 text-white"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h4 class="mb-0 text-white" id="solicitudes_aprobadas">0</h4>
                                <p class="mb-0 text-white-50">Aprobadas</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card radius-10 bg-warning">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <i class="ti ti-clock fs-1 text-white"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h4 class="mb-0 text-white" id="solicitudes_pendientes">0</h4>
                                <p class="mb-0 text-white-50">Pendientes</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card radius-10 bg-danger">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <i class="ti ti-x fs-1 text-white"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h4 class="mb-0 text-white" id="solicitudes_rechazadas">0</h4>
                                <p class="mb-0 text-white-50">Rechazadas</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Solicitudes List -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center justify-content-between">
                            <h5 class="mb-0">Historial de Solicitudes</h5>
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-outline-primary btn-sm" onclick="filtrarSolicitudes('todas')">
                                    <i class="ti ti-filter"></i> Todas
                                </button>
                                <button type="button" class="btn btn-outline-success btn-sm" onclick="filtrarSolicitudes('aprobadas')">
                                    <i class="ti ti-check"></i> Aprobadas
                                </button>
                                <button type="button" class="btn btn-outline-warning btn-sm" onclick="filtrarSolicitudes('pendientes')">
                                    <i class="ti ti-clock"></i> Pendientes
                                </button>
                                <button type="button" class="btn btn-outline-danger btn-sm" onclick="filtrarSolicitudes('rechazadas')">
                                    <i class="ti ti-x"></i> Rechazadas
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover" id="tablaSolicitudes">
                                <thead>
                                    <tr>
                                        <th>Capacitación</th>
                                        <th>Tipo</th>
                                        <th>Fecha Solicitud</th>
                                        <th>Estado</th>
                                        <th>Justificación</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody id="tbodySolicitudes">
                                    <!-- Los datos se cargarán dinámicamente -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Capacitaciones Recomendadas -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Capacitaciones Recomendadas</h5>
                    </div>
                    <div class="card-body">
                        <div class="row" id="capacitacionesRecomendadas">
                            <!-- Las capacitaciones recomendadas se cargarán dinámicamente -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Nueva Solicitud -->
<div class="modal fade" id="modalNuevaSolicitud" tabindex="-1" aria-labelledby="modalNuevaSolicitudLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalNuevaSolicitudLabel">Nueva Solicitud de Capacitación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formNuevaSolicitud">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="nombre_capacitacion" class="form-label">Nombre de la Capacitación</label>
                                <input type="text" class="form-control" id="nombre_capacitacion" name="nombre_capacitacion" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="tipo_capacitacion" class="form-label">Tipo de Capacitación</label>
                                <select class="form-select" id="tipo_capacitacion" name="tipo_capacitacion" required>
                                    <option value="">Seleccionar tipo</option>
                                    <option value="Técnica">Técnica</option>
                                    <option value="Soft Skills">Soft Skills</option>
                                    <option value="Liderazgo">Liderazgo</option>
                                    <option value="Gestión">Gestión</option>
                                    <option value="Especialización">Especialización</option>
                                    <option value="Certificación">Certificación</option>
                                    <option value="Otros">Otros</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="descripcion_capacitacion" class="form-label">Descripción Detallada</label>
                        <textarea class="form-control" id="descripcion_capacitacion" name="descripcion_capacitacion" rows="3" required 
                                  placeholder="Describe la capacitación que necesitas, incluyendo objetivos y beneficios esperados"></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="fecha_propuesta" class="form-label">Fecha Propuesta</label>
                                <input type="date" class="form-control" id="fecha_propuesta" name="fecha_propuesta" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="duracion_estimada" class="form-label">Duración Estimada (horas)</label>
                                <input type="number" class="form-control" id="duracion_estimada" name="duracion_estimada" min="1" max="200" required>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="justificacion_solicitud" class="form-label">Justificación de la Solicitud</label>
                        <textarea class="form-control" id="justificacion_solicitud" name="justificacion_solicitud" rows="3" required
                                  placeholder="Explica por qué necesitas esta capacitación y cómo beneficiará a tu desarrollo profesional y a la organización"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="institucion_preferida" class="form-label">Institución Preferida (opcional)</label>
                        <input type="text" class="form-control" id="institucion_preferida" name="institucion_preferida" 
                               placeholder="Si tienes una institución específica en mente">
                    </div>
                    <div class="mb-3">
                        <label for="documentos_soporte" class="form-label">Documentos de Soporte</label>
                        <input type="file" class="form-control" id="documentos_soporte" name="documentos_soporte" multiple 
                               accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
                        <small class="text-muted">Formatos permitidos: PDF, JPG, PNG, DOC, DOCX (máx. 10MB total)</small>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="enviarSolicitud()">Enviar Solicitud</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Detalles de Solicitud -->
<div class="modal fade" id="modalDetalleSolicitud" tabindex="-1" aria-labelledby="modalDetalleSolicitudLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalDetalleSolicitudLabel">Detalles de la Solicitud</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="modalDetalleSolicitudBody">
                <!-- Contenido del modal se cargará dinámicamente -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" onclick="editarSolicitud()">Editar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Comentarios y Respuesta -->
<div class="modal fade" id="modalComentariosSolicitud" tabindex="-1" aria-labelledby="modalComentariosSolicitudLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalComentariosSolicitudLabel">Comentarios y Respuesta</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="modalComentariosSolicitudBody">
                <!-- Contenido del modal se cargará dinámicamente -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
// Variables globales
let solicitudesData = [];
let filtroActual = 'todas';

// Cargar datos al iniciar la página
document.addEventListener('DOMContentLoaded', function() {
    cargarEstadisticas();
    cargarSolicitudes();
    cargarCapacitacionesRecomendadas();
});

// Cargar estadísticas
function cargarEstadisticas() {
    // Simular carga de estadísticas (en producción esto vendría de una API)
    document.getElementById('total_solicitudes').textContent = '8';
    document.getElementById('solicitudes_aprobadas').textContent = '5';
    document.getElementById('solicitudes_pendientes').textContent = '2';
    document.getElementById('solicitudes_rechazadas').textContent = '1';
}

// Cargar solicitudes del empleado
function cargarSolicitudes() {
    // Simular datos (en producción esto vendría de una API)
    solicitudesData = [
        {
            id: 1,
            nombre: 'Gestión de Proyectos con Metodologías Ágiles',
            tipo: 'Gestión',
            fecha_solicitud: '2025-01-15',
            estado: 'Aprobada',
            justificacion: 'Necesito fortalecer mis habilidades en gestión de proyectos para liderar equipos de desarrollo',
            categoria: 'aprobada',
            comentarios: 'Solicitud aprobada. Se asignará presupuesto para capacitación en Q2 2025.',
            fecha_respuesta: '2025-01-20',
            evaluador: 'Lic. María González'
        },
        {
            id: 2,
            nombre: 'Liderazgo Efectivo y Comunicación',
            tipo: 'Liderazgo',
            fecha_solicitud: '2025-02-01',
            estado: 'Pendiente',
            justificacion: 'Como supervisor de equipo, necesito mejorar mis habilidades de liderazgo y comunicación',
            categoria: 'pendiente',
            comentarios: 'En revisión por el departamento de RRHH',
            fecha_respuesta: null,
            evaluador: null
        },
        {
            id: 3,
            nombre: 'Certificación en Excel Avanzado',
            tipo: 'Técnica',
            fecha_solicitud: '2025-01-20',
            estado: 'Rechazada',
            justificacion: 'Necesito certificación en Excel para mejorar la eficiencia en reportes',
            categoria: 'rechazada',
            comentarios: 'Se recomienda utilizar las capacitaciones internas disponibles en la plataforma corporativa.',
            fecha_respuesta: '2025-01-25',
            evaluador: 'Dr. Carlos Rodríguez'
        },
        {
            id: 4,
            nombre: 'Innovación y Design Thinking',
            tipo: 'Soft Skills',
            fecha_solicitud: '2025-02-10',
            estado: 'Aprobada',
            justificacion: 'Para fomentar la creatividad e innovación en el equipo de desarrollo',
            categoria: 'aprobada',
            comentarios: 'Excelente iniciativa. Capacitación programada para marzo 2025.',
            fecha_respuesta: '2025-02-15',
            evaluador: 'Lic. María González'
        }
    ];
    
    mostrarSolicitudes(solicitudesData);
}

// Mostrar solicitudes en la tabla
function mostrarSolicitudes(solicitudes) {
    const tbody = document.getElementById('tbodySolicitudes');
    tbody.innerHTML = '';
    
    solicitudes.forEach(solicitud => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <i class="ti ti-clipboard-data text-primary fs-4"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="mb-0">${solicitud.nombre}</h6>
                        <small class="text-muted">${solicitud.justificacion.substring(0, 60)}...</small>
                    </div>
                </div>
            </td>
            <td><span class="badge bg-light text-dark">${solicitud.tipo}</span></td>
            <td>${formatearFecha(solicitud.fecha_solicitud)}</td>
            <td>${obtenerBadgeEstado(solicitud.estado)}</td>
            <td>${solicitud.justificacion.substring(0, 40)}...</td>
            <td>
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="verDetalleSolicitud(${solicitud.id})">
                        <i class="ti ti-eye"></i>
                    </button>
                    ${solicitud.estado === 'Pendiente' ? 
                        `<button type="button" class="btn btn-sm btn-outline-warning" onclick="editarSolicitud(${solicitud.id})">
                            <i class="ti ti-edit"></i>
                        </button>` : ''
                    }
                    <button type="button" class="btn btn-sm btn-outline-info" onclick="verComentariosSolicitud(${solicitud.id})">
                        <i class="ti ti-message-circle"></i>
                    </button>
                </div>
            </td>
        `;
        tbody.appendChild(row);
    });
}

// Cargar capacitaciones recomendadas
function cargarCapacitacionesRecomendadas() {
    const container = document.getElementById('capacitacionesRecomendadas');
    
    // Simular datos (en producción esto vendría de una API)
    const capacitacionesRecomendadas = [
        {
            id: 1,
            nombre: 'Gestión del Tiempo y Productividad',
            descripcion: 'Aprende técnicas efectivas para optimizar tu productividad diaria',
            tipo: 'Soft Skills',
            duracion: '16 horas',
            modalidad: 'Virtual',
            prioridad: 'Alta'
        },
        {
            id: 2,
            nombre: 'Comunicación Asertiva en el Entorno Laboral',
            descripcion: 'Mejora tus habilidades de comunicación interpersonal y resolución de conflictos',
            tipo: 'Soft Skills',
            duracion: '20 horas',
            modalidad: 'Presencial',
            prioridad: 'Media'
        },
        {
            id: 3,
            nombre: 'Análisis de Datos con Power BI',
            descripcion: 'Capacitación en herramientas de business intelligence para análisis avanzado',
            tipo: 'Técnica',
            duracion: '32 horas',
            modalidad: 'Híbrida',
            prioridad: 'Alta'
        },
        {
            id: 4,
            nombre: 'Gestión de Cambio Organizacional',
            descripcion: 'Desarrolla habilidades para liderar procesos de transformación',
            tipo: 'Liderazgo',
            duracion: '24 horas',
            modalidad: 'Presencial',
            prioridad: 'Media'
        }
    ];
    
    container.innerHTML = '';
    capacitacionesRecomendadas.forEach(capacitacion => {
        const col = document.createElement('div');
        col.className = 'col-md-6 col-lg-3 mb-3';
        col.innerHTML = `
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <span class="badge bg-${obtenerColorPrioridad(capacitacion.prioridad)}">${capacitacion.prioridad}</span>
                        <span class="badge bg-light text-dark">${capacitacion.tipo}</span>
                    </div>
                    <h6 class="card-title">${capacitacion.nombre}</h6>
                    <p class="card-text text-muted small">${capacitacion.descripcion}</p>
                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-muted">
                            <i class="ti ti-clock"></i> ${capacitacion.duracion}
                        </small>
                        <small class="text-muted">
                            <i class="ti ti-device-laptop"></i> ${capacitacion.modalidad}
                        </small>
                    </div>
                    <div class="mt-3">
                        <button type="button" class="btn btn-primary btn-sm w-100" onclick="solicitarCapacitacionRecomendada(${capacitacion.id})">
                            Solicitar
                        </button>
                    </div>
                </div>
            </div>
        `;
        container.appendChild(col);
    });
}

// Filtrar solicitudes
function filtrarSolicitudes(filtro) {
    filtroActual = filtro;
    
    let solicitudesFiltradas = [];
    
    switch(filtro) {
        case 'aprobadas':
            solicitudesFiltradas = solicitudesData.filter(s => s.categoria === 'aprobada');
            break;
        case 'pendientes':
            solicitudesFiltradas = solicitudesData.filter(s => s.categoria === 'pendiente');
            break;
        case 'rechazadas':
            solicitudesFiltradas = solicitudesData.filter(s => s.categoria === 'rechazada');
            break;
        default:
            solicitudesFiltradas = solicitudesData;
    }
    
    mostrarSolicitudes(solicitudesFiltradas);
}

// Abrir modal para nueva solicitud
function abrirModalNuevaSolicitud() {
    const modal = new bootstrap.Modal(document.getElementById('modalNuevaSolicitud'));
    modal.show();
}

// Enviar solicitud
function enviarSolicitud() {
    const form = document.getElementById('formNuevaSolicitud');
    const formData = new FormData(form);
    
    // Validar formulario
    if (!formData.get('nombre_capacitacion') || !formData.get('tipo_capacitacion') || 
        !formData.get('descripcion_capacitacion') || !formData.get('fecha_propuesta') || 
        !formData.get('duracion_estimada') || !formData.get('justificacion_solicitud')) {
        alert('Por favor, complete todos los campos obligatorios');
        return;
    }
    
    // Aquí se enviaría la solicitud al servidor
    alert('Solicitud enviada exitosamente');
    
    // Cerrar modal y limpiar formulario
    const modal = bootstrap.Modal.getInstance(document.getElementById('modalNuevaSolicitud'));
    modal.hide();
    form.reset();
    
    // Recargar datos
    cargarSolicitudes();
}

// Ver detalle de solicitud
function verDetalleSolicitud(id) {
    const solicitud = solicitudesData.find(s => s.id === id);
    if (!solicitud) return;
    
    const modalBody = document.getElementById('modalDetalleSolicitudBody');
    modalBody.innerHTML = `
        <div class="row">
            <div class="col-md-6">
                <h6>Información de la Capacitación</h6>
                <p><strong>Nombre:</strong> ${solicitud.nombre}</p>
                <p><strong>Tipo:</strong> ${solicitud.tipo}</p>
                <p><strong>Estado:</strong> ${solicitud.estado}</p>
                <p><strong>Fecha de Solicitud:</strong> ${formatearFecha(solicitud.fecha_solicitud)}</p>
            </div>
            <div class="col-md-6">
                <h6>Evaluación</h6>
                ${solicitud.evaluador ? 
                    `<p><strong>Evaluador:</strong> ${solicitud.evaluador}</p>` : 
                    '<p><strong>Evaluador:</strong> Pendiente</p>'
                }
                ${solicitud.fecha_respuesta ? 
                    `<p><strong>Fecha de Respuesta:</strong> ${formatearFecha(solicitud.fecha_respuesta)}</p>` : 
                    '<p><strong>Fecha de Respuesta:</strong> Pendiente</p>'
                }
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-12">
                <h6>Justificación</h6>
                <p class="text-muted">${solicitud.justificacion}</p>
            </div>
        </div>
        ${solicitud.comentarios ? `
        <div class="row mt-3">
            <div class="col-12">
                <h6>Comentarios del Evaluador</h6>
                <div class="alert alert-info">
                    <p class="mb-0">${solicitud.comentarios}</p>
                </div>
            </div>
        </div>
        ` : ''}
    `;
    
    const modal = new bootstrap.Modal(document.getElementById('modalDetalleSolicitud'));
    modal.show();
}

// Ver comentarios de solicitud
function verComentariosSolicitud(id) {
    const solicitud = solicitudesData.find(s => s.id === id);
    if (!solicitud) return;
    
    const modalBody = document.getElementById('modalComentariosSolicitudBody');
    modalBody.innerHTML = `
        <div class="alert alert-info">
            <h6><i class="ti ti-info-circle"></i> Solicitud: ${solicitud.nombre}</h6>
            <p class="mb-0"><strong>Estado:</strong> ${solicitud.estado}</p>
        </div>
        ${solicitud.comentarios ? `
        <div class="mt-3">
            <h6>Comentarios del Evaluador:</h6>
            <div class="border rounded p-3 bg-light">
                <p class="mb-0">${solicitud.comentarios}</p>
                ${solicitud.evaluador ? `<small class="text-muted">- ${solicitud.evaluador}</small>` : ''}
            </div>
        </div>
        ` : '<p class="text-muted">No hay comentarios disponibles.</p>'}
    `;
    
    const modal = new bootstrap.Modal(document.getElementById('modalComentariosSolicitud'));
    modal.show();
}

// Editar solicitud
function editarSolicitud(id) {
    alert('Funcionalidad de edición en desarrollo');
}

// Solicitar capacitación recomendada
function solicitarCapacitacionRecomendada(id) {
    alert('Redirigiendo al formulario de solicitud...');
    // Aquí se abriría el modal de nueva solicitud con los datos prellenados
}

// Funciones auxiliares
function formatearFecha(fecha) {
    if (!fecha) return 'N/A';
    return new Date(fecha).toLocaleDateString('es-ES');
}

function obtenerBadgeEstado(estado) {
    const badges = {
        'Aprobada': 'bg-success',
        'Pendiente': 'bg-warning',
        'Rechazada': 'bg-danger',
        'En Revisión': 'bg-info'
    };
    
    return `<span class="badge ${badges[estado] || 'bg-secondary'}">${estado}</span>`;
}

function obtenerColorPrioridad(prioridad) {
    const colores = {
        'Alta': 'danger',
        'Media': 'warning',
        'Baja': 'success'
    };
    
    return colores[prioridad] || 'secondary';
}
</script>
<?= $this->endSection() ?>
