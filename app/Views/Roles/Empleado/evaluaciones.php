<?php
$sidebar = 'sidebar_empleado';
?>

<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>
<div class="page-wrapper">
    <div class="page-content">
        <!-- Breadcrumb -->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Evaluaciones</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
                        <li class="breadcrumb-item active" aria-current="page">Mis Evaluaciones</li>
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
                                <i class="ti ti-clipboard-check fs-1 text-primary"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h4 class="mb-1">Mis Evaluaciones</h4>
                                <p class="mb-0 text-muted">Revisa tu desempeño y desarrollo profesional</p>
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
                                <h4 class="mb-0 text-white" id="total_evaluaciones">0</h4>
                                <p class="mb-0 text-white-50">Total Evaluaciones</p>
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
                                <i class="ti ti-trending-up fs-1 text-white"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h4 class="mb-0 text-white" id="promedio_general">0</h4>
                                <p class="mb-0 text-white-50">Promedio General</p>
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
                                <h4 class="mb-0 text-white" id="evaluaciones_pendientes">0</h4>
                                <p class="mb-0 text-white-50">Pendientes</p>
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
                                <i class="ti ti-star fs-1 text-white"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h4 class="mb-0 text-white" id="mejor_categoria">0</h4>
                                <p class="mb-0 text-white-50">Mejor Categoría</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Performance Chart -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Evolución del Desempeño</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="chartDesempeno" height="100"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Evaluaciones List -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center justify-content-between">
                            <h5 class="mb-0">Historial de Evaluaciones</h5>
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-outline-primary btn-sm" onclick="filtrarEvaluaciones('todas')">
                                    <i class="ti ti-filter"></i> Todas
                                </button>
                                <button type="button" class="btn btn-outline-success btn-sm" onclick="filtrarEvaluaciones('excelente')">
                                    <i class="ti ti-star"></i> Excelente
                                </button>
                                <button type="button" class="btn btn-outline-warning btn-sm" onclick="filtrarEvaluaciones('bueno')">
                                    <i class="ti ti-thumbs-up"></i> Bueno
                                </button>
                                <button type="button" class="btn btn-outline-info btn-sm" onclick="filtrarEvaluaciones('regular')">
                                    <i class="ti ti-minus"></i> Regular
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover" id="tablaEvaluaciones">
                                <thead>
                                    <tr>
                                        <th>Evaluación</th>
                                        <th>Evaluador</th>
                                        <th>Fecha</th>
                                        <th>Calificación</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody id="tbodyEvaluaciones">
                                    <!-- Los datos se cargarán dinámicamente -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Categorías de Evaluación -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Desempeño por Categorías</h5>
                    </div>
                    <div class="card-body">
                        <div class="row" id="categoriasEvaluacion">
                            <!-- Las categorías se cargarán dinámicamente -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Detalles de Evaluación -->
<div class="modal fade" id="modalDetalleEvaluacion" tabindex="-1" aria-labelledby="modalDetalleEvaluacionLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalDetalleEvaluacionLabel">Detalles de Evaluación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="modalDetalleEvaluacionBody">
                <!-- Contenido del modal se cargará dinámicamente -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" onclick="descargarReporte()">Descargar Reporte</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Comentarios -->
<div class="modal fade" id="modalComentarios" tabindex="-1" aria-labelledby="modalComentariosLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalComentariosLabel">Comentarios y Observaciones</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="modalComentariosBody">
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
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Variables globales
let evaluacionesData = [];
let filtroActual = 'todas';
let chartDesempeno;

// Cargar datos al iniciar la página
document.addEventListener('DOMContentLoaded', function() {
    cargarEstadisticas();
    cargarEvaluaciones();
    cargarCategorias();
    inicializarGrafico();
});

// Cargar estadísticas
function cargarEstadisticas() {
    // Simular carga de estadísticas (en producción esto vendría de una API)
    document.getElementById('total_evaluaciones').textContent = '8';
    document.getElementById('promedio_general').textContent = '4.2';
    document.getElementById('evaluaciones_pendientes').textContent = '1';
    document.getElementById('mejor_categoria').textContent = 'Liderazgo';
}

// Cargar evaluaciones del empleado
function cargarEvaluaciones() {
    // Simular datos (en producción esto vendría de una API)
    evaluacionesData = [
        {
            id: 1,
            nombre: 'Evaluación Trimestral Q1 2025',
            evaluador: 'Dr. Juan Pérez',
            fecha: '2025-03-31',
            calificacion: 4.5,
            estado: 'Completada',
            categoria: 'excelente',
            comentarios: 'Excelente desempeño en todas las áreas evaluadas. Destaca en liderazgo y comunicación.',
            detalles: {
                'Liderazgo': 5.0,
                'Comunicación': 4.5,
                'Trabajo en Equipo': 4.0,
                'Innovación': 4.5,
                'Resultados': 4.0
            }
        },
        {
            id: 2,
            nombre: 'Evaluación de Competencias',
            evaluador: 'Lic. María González',
            fecha: '2025-02-15',
            calificacion: 4.0,
            estado: 'Completada',
            categoria: 'bueno',
            comentarios: 'Buen desempeño general. Áreas de mejora en innovación y gestión del tiempo.',
            detalles: {
                'Liderazgo': 4.0,
                'Comunicación': 4.5,
                'Trabajo en Equipo': 4.0,
                'Innovación': 3.5,
                'Resultados': 4.0
            }
        },
        {
            id: 3,
            nombre: 'Evaluación Anual 2024',
            evaluador: 'Dr. Carlos Rodríguez',
            fecha: '2024-12-31',
            calificacion: 3.8,
            estado: 'Completada',
            categoria: 'bueno',
            comentarios: 'Desempeño satisfactorio. Se recomienda fortalecer habilidades de innovación.',
            detalles: {
                'Liderazgo': 4.0,
                'Comunicación': 4.0,
                'Trabajo en Equipo': 3.5,
                'Innovación': 3.0,
                'Resultados': 4.0
            }
        }
    ];
    
    mostrarEvaluaciones(evaluacionesData);
}

// Mostrar evaluaciones en la tabla
function mostrarEvaluaciones(evaluaciones) {
    const tbody = document.getElementById('tbodyEvaluaciones');
    tbody.innerHTML = '';
    
    evaluaciones.forEach(evaluacion => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <i class="ti ti-clipboard-check text-primary fs-4"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="mb-0">${evaluacion.nombre}</h6>
                        <small class="text-muted">${evaluacion.comentarios.substring(0, 50)}...</small>
                    </div>
                </div>
            </td>
            <td>${evaluacion.evaluador}</td>
            <td>${formatearFecha(evaluacion.fecha)}</td>
            <td>
                <div class="d-flex align-items-center">
                    <span class="badge bg-primary me-2">${evaluacion.calificacion}/5.0</span>
                    ${obtenerEstrellas(evaluacion.calificacion)}
                </div>
            </td>
            <td>${obtenerBadgeEstado(evaluacion.estado)}</td>
            <td>
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="verDetalleEvaluacion(${evaluacion.id})">
                        <i class="ti ti-eye"></i>
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-info" onclick="verComentarios(${evaluacion.id})">
                        <i class="ti ti-message-circle"></i>
                    </button>
                </div>
            </td>
        `;
        tbody.appendChild(row);
    });
}

// Cargar categorías de evaluación
function cargarCategorias() {
    const container = document.getElementById('categoriasEvaluacion');
    
    // Simular datos (en producción esto vendría de una API)
    const categorias = [
        { nombre: 'Liderazgo', puntaje: 4.3, color: 'primary' },
        { nombre: 'Comunicación', puntaje: 4.2, color: 'success' },
        { nombre: 'Trabajo en Equipo', puntaje: 3.8, color: 'warning' },
        { nombre: 'Innovación', puntaje: 3.5, color: 'info' },
        { nombre: 'Resultados', puntaje: 4.0, color: 'danger' }
    ];
    
    container.innerHTML = '';
    categorias.forEach(categoria => {
        const col = document.createElement('div');
        col.className = 'col-md-6 col-lg-4 mb-3';
        col.innerHTML = `
            <div class="card h-100">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <i class="ti ti-star fs-1 text-${categoria.color}"></i>
                    </div>
                    <h6 class="card-title">${categoria.nombre}</h6>
                    <h4 class="text-${categoria.color} mb-2">${categoria.puntaje}/5.0</h4>
                    <div class="progress mb-2" style="height: 8px;">
                        <div class="progress-bar bg-${categoria.color}" role="progressbar" 
                             style="width: ${(categoria.puntaje / 5) * 100}%" 
                             aria-valuenow="${categoria.puntaje}" aria-valuemin="0" aria-valuemax="5"></div>
                    </div>
                    <small class="text-muted">${obtenerDescripcionPuntaje(categoria.puntaje)}</small>
                </div>
            </div>
        `;
        container.appendChild(col);
    });
}

// Inicializar gráfico de desempeño
function inicializarGrafico() {
    const ctx = document.getElementById('chartDesempeno').getContext('2d');
    
    // Simular datos históricos (en producción esto vendría de una API)
    const datos = {
        labels: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun'],
        datasets: [{
            label: 'Promedio General',
            data: [3.8, 4.0, 4.2, 4.1, 4.3, 4.2],
            borderColor: 'rgb(75, 192, 192)',
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            tension: 0.1
        }]
    };
    
    chartDesempeno = new Chart(ctx, {
        type: 'line',
        data: datos,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    max: 5,
                    ticks: {
                        stepSize: 1
                    }
                }
            },
            plugins: {
                legend: {
                    display: true,
                    position: 'top'
                }
            }
        }
    });
}

// Filtrar evaluaciones
function filtrarEvaluaciones(filtro) {
    filtroActual = filtro;
    
    let evaluacionesFiltradas = [];
    
    switch(filtro) {
        case 'excelente':
            evaluacionesFiltradas = evaluacionesData.filter(e => e.calificacion >= 4.5);
            break;
        case 'bueno':
            evaluacionesFiltradas = evaluacionesData.filter(e => e.calificacion >= 4.0 && e.calificacion < 4.5);
            break;
        case 'regular':
            evaluacionesFiltradas = evaluacionesData.filter(e => e.calificacion < 4.0);
            break;
        default:
            evaluacionesFiltradas = evaluacionesData;
    }
    
    mostrarEvaluaciones(evaluacionesFiltradas);
}

// Ver detalle de evaluación
function verDetalleEvaluacion(id) {
    const evaluacion = evaluacionesData.find(e => e.id === id);
    if (!evaluacion) return;
    
    const modalBody = document.getElementById('modalDetalleEvaluacionBody');
    modalBody.innerHTML = `
        <div class="row">
            <div class="col-md-6">
                <h6>Información General</h6>
                <p><strong>Evaluación:</strong> ${evaluacion.nombre}</p>
                <p><strong>Evaluador:</strong> ${evaluacion.evaluador}</p>
                <p><strong>Fecha:</strong> ${formatearFecha(evaluacion.fecha)}</p>
                <p><strong>Calificación:</strong> ${evaluacion.calificacion}/5.0</p>
                <p><strong>Estado:</strong> ${evaluacion.estado}</p>
            </div>
            <div class="col-md-6">
                <h6>Calificación por Categorías</h6>
                ${Object.entries(evaluacion.detalles).map(([categoria, puntaje]) => `
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span>${categoria}</span>
                        <div class="d-flex align-items-center">
                            <span class="badge bg-primary me-2">${puntaje}/5.0</span>
                            ${obtenerEstrellas(puntaje)}
                        </div>
                    </div>
                `).join('')}
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-12">
                <h6>Comentarios y Observaciones</h6>
                <p class="text-muted">${evaluacion.comentarios}</p>
            </div>
        </div>
    `;
    
    const modal = new bootstrap.Modal(document.getElementById('modalDetalleEvaluacion'));
    modal.show();
}

// Ver comentarios
function verComentarios(id) {
    const evaluacion = evaluacionesData.find(e => e.id === id);
    if (!evaluacion) return;
    
    const modalBody = document.getElementById('modalComentariosBody');
    modalBody.innerHTML = `
        <div class="alert alert-info">
            <h6><i class="ti ti-info-circle"></i> Comentarios del Evaluador</h6>
            <p class="mb-0">${evaluacion.comentarios}</p>
        </div>
        <div class="mt-3">
            <h6>Recomendaciones:</h6>
            <ul class="list-unstyled">
                <li><i class="ti ti-check text-success"></i> Continuar con el excelente trabajo en liderazgo</li>
                <li><i class="ti ti-arrow-up text-warning"></i> Fortalecer habilidades de innovación</li>
                <li><i class="ti ti-arrow-up text-warning"></i> Mejorar gestión del tiempo</li>
            </ul>
        </div>
    `;
    
    const modal = new bootstrap.Modal(document.getElementById('modalComentarios'));
    modal.show();
}

// Descargar reporte
function descargarReporte() {
    alert('Descargando reporte de evaluación...');
    // Aquí se implementaría la descarga del reporte
}

// Funciones auxiliares
function formatearFecha(fecha) {
    if (!fecha) return 'N/A';
    return new Date(fecha).toLocaleDateString('es-ES');
}

function obtenerBadgeEstado(estado) {
    const badges = {
        'Completada': 'bg-success',
        'En Proceso': 'bg-warning',
        'Pendiente': 'bg-info',
        'Cancelada': 'bg-danger'
    };
    
    return `<span class="badge ${badges[estado] || 'bg-secondary'}">${estado}</span>`;
}

function obtenerEstrellas(puntaje) {
    const estrellas = Math.round(puntaje);
    let html = '';
    
    for (let i = 1; i <= 5; i++) {
        if (i <= estrellas) {
            html += '<i class="ti ti-star-filled text-warning"></i>';
        } else {
            html += '<i class="ti ti-star text-muted"></i>';
        }
    }
    
    return html;
}

function obtenerDescripcionPuntaje(puntaje) {
    if (puntaje >= 4.5) return 'Excelente';
    if (puntaje >= 4.0) return 'Muy Bueno';
    if (puntaje >= 3.5) return 'Bueno';
    if (puntaje >= 3.0) return 'Satisfactorio';
    return 'Necesita Mejora';
}
</script>
<?= $this->endSection() ?>
