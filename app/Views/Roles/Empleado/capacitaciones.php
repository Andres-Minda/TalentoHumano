<?php
$sidebar = 'sidebar_empleado';
?>

<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>
<div class="page-wrapper">
    <div class="page-content">
        <!-- Breadcrumb -->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Capacitaciones</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
                        <li class="breadcrumb-item active" aria-current="page">Mis Capacitaciones</li>
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
                                <i class="ti ti-mortarboard fs-1 text-primary"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h4 class="mb-1">Mis Capacitaciones</h4>
                                <p class="mb-0 text-muted">Gestiona tu desarrollo profesional y capacitaciones</p>
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
                                <i class="ti ti-book fs-1 text-white"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h4 class="mb-0 text-white" id="total_capacitaciones">0</h4>
                                <p class="mb-0 text-white-50">Total Capacitaciones</p>
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
                                <h4 class="mb-0 text-white" id="capacitaciones_completadas">0</h4>
                                <p class="mb-0 text-white-50">Completadas</p>
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
                                <h4 class="mb-0 text-white" id="capacitaciones_en_curso">0</h4>
                                <p class="mb-0 text-white-50">En Curso</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card radius-10 bg-info">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <i class="ti ti-award fs-1 text-white"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h4 class="mb-0 text-white" id="horas_acumuladas">0</h4>
                                <p class="mb-0 text-white-50">Horas Acumuladas</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Capacitaciones List -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center justify-content-between">
                            <h5 class="mb-0">Lista de Capacitaciones</h5>
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-outline-primary btn-sm" onclick="filtrarCapacitaciones('todas')">
                                    <i class="ti ti-filter"></i> Todas
                                </button>
                                <button type="button" class="btn btn-outline-success btn-sm" onclick="filtrarCapacitaciones('completadas')">
                                    <i class="ti ti-check"></i> Completadas
                                </button>
                                <button type="button" class="btn btn-outline-warning btn-sm" onclick="filtrarCapacitaciones('en_curso')">
                                    <i class="ti ti-clock"></i> En Curso
                                </button>
                                <button type="button" class="btn btn-outline-info btn-sm" onclick="filtrarCapacitaciones('pendientes')">
                                    <i class="ti ti-calendar"></i> Pendientes
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover" id="tablaCapacitaciones">
                                <thead>
                                    <tr>
                                        <th>Capacitación</th>
                                        <th>Tipo</th>
                                        <th>Fecha Inicio</th>
                                        <th>Fecha Fin</th>
                                        <th>Estado</th>
                                        <th>Progreso</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody id="tbodyCapacitaciones">
                                    <!-- Los datos se cargarán dinámicamente -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Capacitaciones Disponibles -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Capacitaciones Disponibles</h5>
                    </div>
                    <div class="card-body">
                        <div class="row" id="capacitacionesDisponibles">
                            <!-- Las capacitaciones disponibles se cargarán dinámicamente -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Detalles de Capacitación -->
<div class="modal fade" id="modalDetalleCapacitacion" tabindex="-1" aria-labelledby="modalDetalleCapacitacionLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalDetalleCapacitacionLabel">Detalles de Capacitación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="modalDetalleCapacitacionBody">
                <!-- Contenido del modal se cargará dinámicamente -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" id="btnInscribirCapacitacion">Inscribirse</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Solicitar Capacitación -->
<div class="modal fade" id="modalSolicitarCapacitacion" tabindex="-1" aria-labelledby="modalSolicitarCapacitacionLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalSolicitarCapacitacionLabel">Solicitar Nueva Capacitación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formSolicitarCapacitacion">
                    <div class="mb-3">
                        <label for="nombre_capacitacion" class="form-label">Nombre de la Capacitación</label>
                        <input type="text" class="form-control" id="nombre_capacitacion" name="nombre_capacitacion" required>
                    </div>
                    <div class="mb-3">
                        <label for="descripcion_capacitacion" class="form-label">Descripción</label>
                        <textarea class="form-control" id="descripcion_capacitacion" name="descripcion_capacitacion" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="justificacion_capacitacion" class="form-label">Justificación</label>
                        <textarea class="form-control" id="justificacion_capacitacion" name="justificacion_capacitacion" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="fecha_propuesta" class="form-label">Fecha Propuesta</label>
                        <input type="date" class="form-control" id="fecha_propuesta" name="fecha_propuesta" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="enviarSolicitudCapacitacion()">Enviar Solicitud</button>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
// Variables globales
let capacitacionesData = [];
let filtroActual = 'todas';

// Cargar datos al iniciar la página
document.addEventListener('DOMContentLoaded', function() {
    cargarEstadisticas();
    cargarCapacitaciones();
    cargarCapacitacionesDisponibles();
});

// Cargar estadísticas
function cargarEstadisticas() {
    // Simular carga de estadísticas (en producción esto vendría de una API)
    document.getElementById('total_capacitaciones').textContent = '5';
    document.getElementById('capacitaciones_completadas').textContent = '3';
    document.getElementById('capacitaciones_en_curso').textContent = '1';
    document.getElementById('horas_acumuladas').textContent = '45';
}

// Cargar capacitaciones del empleado
function cargarCapacitaciones() {
    // Simular datos (en producción esto vendría de una API)
    capacitacionesData = [
        {
            id: 1,
            nombre: 'Gestión de Proyectos',
            tipo: 'Presencial',
            fecha_inicio: '2025-01-15',
            fecha_fin: '2025-01-20',
            estado: 'Completada',
            progreso: 100,
            descripcion: 'Capacitación en metodologías ágiles y gestión de proyectos'
        },
        {
            id: 2,
            nombre: 'Liderazgo Efectivo',
            tipo: 'Virtual',
            fecha_inicio: '2025-02-01',
            fecha_fin: '2025-02-28',
            estado: 'En Curso',
            progreso: 65,
            descripcion: 'Desarrollo de habilidades de liderazgo y comunicación'
        },
        {
            id: 3,
            nombre: 'Excel Avanzado',
            tipo: 'Híbrida',
            fecha_inicio: '2025-03-10',
            fecha_fin: '2025-03-15',
            estado: 'Pendiente',
            progreso: 0,
            descripcion: 'Funciones avanzadas de Excel para análisis de datos'
        }
    ];
    
    mostrarCapacitaciones(capacitacionesData);
}

// Mostrar capacitaciones en la tabla
function mostrarCapacitaciones(capacitaciones) {
    const tbody = document.getElementById('tbodyCapacitaciones');
    tbody.innerHTML = '';
    
    capacitaciones.forEach(capacitacion => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <i class="ti ti-book text-primary fs-4"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="mb-0">${capacitacion.nombre}</h6>
                        <small class="text-muted">${capacitacion.descripcion}</small>
                    </div>
                </div>
            </td>
            <td><span class="badge bg-light text-dark">${capacitacion.tipo}</span></td>
            <td>${formatearFecha(capacitacion.fecha_inicio)}</td>
            <td>${formatearFecha(capacitacion.fecha_fin)}</td>
            <td>${obtenerBadgeEstado(capacitacion.estado)}</td>
            <td>
                <div class="progress" style="height: 6px;">
                    <div class="progress-bar" role="progressbar" style="width: ${capacitacion.progreso}%" 
                         aria-valuenow="${capacitacion.progreso}" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <small class="text-muted">${capacitacion.progreso}%</small>
            </td>
            <td>
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="verDetalleCapacitacion(${capacitacion.id})">
                        <i class="ti ti-eye"></i>
                    </button>
                    ${capacitacion.estado === 'Pendiente' ? 
                        `<button type="button" class="btn btn-sm btn-outline-success" onclick="iniciarCapacitacion(${capacitacion.id})">
                            <i class="ti ti-play"></i>
                        </button>` : ''
                    }
                </div>
            </td>
        `;
        tbody.appendChild(row);
    });
}

// Cargar capacitaciones disponibles
function cargarCapacitacionesDisponibles() {
    const container = document.getElementById('capacitacionesDisponibles');
    
    // Simular datos (en producción esto vendría de una API)
    const capacitacionesDisponibles = [
        {
            id: 4,
            nombre: 'Comunicación Efectiva',
            descripcion: 'Mejora tus habilidades de comunicación interpersonal',
            duracion: '20 horas',
            modalidad: 'Virtual',
            costo: 'Gratuito'
        },
        {
            id: 5,
            nombre: 'Gestión del Tiempo',
            descripcion: 'Aprende a organizar tu tiempo de manera eficiente',
            duracion: '15 horas',
            modalidad: 'Presencial',
            costo: '$50'
        }
    ];
    
    container.innerHTML = '';
    capacitacionesDisponibles.forEach(capacitacion => {
        const card = document.createElement('div');
        card.className = 'col-md-6 col-lg-4 mb-3';
        card.innerHTML = `
            <div class="card h-100">
                <div class="card-body">
                    <h6 class="card-title">${capacitacion.nombre}</h6>
                    <p class="card-text text-muted">${capacitacion.descripcion}</p>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <small class="text-muted">
                            <i class="ti ti-clock"></i> ${capacitacion.duracion}
                        </small>
                        <small class="text-muted">
                            <i class="ti ti-device-laptop"></i> ${capacitacion.modalidad}
                        </small>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="badge bg-success">${capacitacion.costo}</span>
                        <button type="button" class="btn btn-primary btn-sm" onclick="solicitarCapacitacion(${capacitacion.id})">
                            Solicitar
                        </button>
                    </div>
                </div>
            </div>
        `;
        container.appendChild(card);
    });
}

// Filtrar capacitaciones
function filtrarCapacitaciones(filtro) {
    filtroActual = filtro;
    
    let capacitacionesFiltradas = [];
    
    switch(filtro) {
        case 'completadas':
            capacitacionesFiltradas = capacitacionesData.filter(c => c.estado === 'Completada');
            break;
        case 'en_curso':
            capacitacionesFiltradas = capacitacionesData.filter(c => c.estado === 'En Curso');
            break;
        case 'pendientes':
            capacitacionesFiltradas = capacitacionesData.filter(c => c.estado === 'Pendiente');
            break;
        default:
            capacitacionesFiltradas = capacitacionesData;
    }
    
    mostrarCapacitaciones(capacitacionesFiltradas);
}

// Ver detalle de capacitación
function verDetalleCapacitacion(id) {
    const capacitacion = capacitacionesData.find(c => c.id === id);
    if (!capacitacion) return;
    
    const modalBody = document.getElementById('modalDetalleCapacitacionBody');
    modalBody.innerHTML = `
        <div class="row">
            <div class="col-md-6">
                <h6>Información General</h6>
                <p><strong>Nombre:</strong> ${capacitacion.nombre}</p>
                <p><strong>Tipo:</strong> ${capacitacion.tipo}</p>
                <p><strong>Estado:</strong> ${capacitacion.estado}</p>
                <p><strong>Progreso:</strong> ${capacitacion.progreso}%</p>
            </div>
            <div class="col-md-6">
                <h6>Fechas</h6>
                <p><strong>Inicio:</strong> ${formatearFecha(capacitacion.fecha_inicio)}</p>
                <p><strong>Fin:</strong> ${formatearFecha(capacitacion.fecha_fin)}</p>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-12">
                <h6>Descripción</h6>
                <p>${capacitacion.descripcion}</p>
            </div>
        </div>
    `;
    
    const modal = new bootstrap.Modal(document.getElementById('modalDetalleCapacitacion'));
    modal.show();
}

// Iniciar capacitación
function iniciarCapacitacion(id) {
    if (confirm('¿Estás seguro de que quieres iniciar esta capacitación?')) {
        // Aquí se enviaría la solicitud al servidor
        alert('Capacitación iniciada exitosamente');
        // Recargar datos
        cargarCapacitaciones();
    }
}

// Solicitar nueva capacitación
function solicitarCapacitacion(id) {
    const modal = new bootstrap.Modal(document.getElementById('modalSolicitarCapacitacion'));
    modal.show();
}

// Enviar solicitud de capacitación
function enviarSolicitudCapacitacion() {
    const form = document.getElementById('formSolicitarCapacitacion');
    const formData = new FormData(form);
    
    // Validar formulario
    if (!formData.get('nombre_capacitacion') || !formData.get('descripcion_capacitacion')) {
        alert('Por favor, complete todos los campos obligatorios');
        return;
    }
    
    // Aquí se enviaría la solicitud al servidor
    alert('Solicitud enviada exitosamente');
    
    // Cerrar modal y limpiar formulario
    const modal = bootstrap.Modal.getInstance(document.getElementById('modalSolicitarCapacitacion'));
    modal.hide();
    form.reset();
}

// Funciones auxiliares
function formatearFecha(fecha) {
    if (!fecha) return 'N/A';
    return new Date(fecha).toLocaleDateString('es-ES');
}

function obtenerBadgeEstado(estado) {
    const badges = {
        'Completada': 'bg-success',
        'En Curso': 'bg-warning',
        'Pendiente': 'bg-info',
        'Cancelada': 'bg-danger'
    };
    
    return `<span class="badge ${badges[estado] || 'bg-secondary'}">${estado}</span>`;
}
</script>
<?= $this->endSection() ?>
