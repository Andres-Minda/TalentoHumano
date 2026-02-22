<?php
$sidebar = 'sidebar_empleado';
?>

<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>
<div class="page-wrapper">
    <div class="page-content">
        <!-- Breadcrumb -->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Inasistencias</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
                        <li class="breadcrumb-item active" aria-current="page">Control de Inasistencias</li>
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
                                <i class="ti ti-calendar-x fs-1 text-primary"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h4 class="mb-1">Control de Inasistencias</h4>
                                <p class="mb-0 text-muted">Gestiona tu asistencia y justificaciones</p>
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
                                <i class="ti ti-calendar-check fs-1 text-white"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h4 class="mb-0 text-white" id="dias_asistidos">0</h4>
                                <p class="mb-0 text-white-50">Días Asistidos</p>
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
                                <h4 class="mb-0 text-white" id="inasistencias_justificadas">0</h4>
                                <p class="mb-0 text-white-50">Justificadas</p>
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
                                <h4 class="mb-0 text-white" id="inasistencias_pendientes">0</h4>
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
                                <h4 class="mb-0 text-white" id="inasistencias_injustificadas">0</h4>
                                <p class="mb-0 text-white-50">Injustificadas</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Attendance Chart -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Asistencia del Mes</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="chartAsistencia" height="100"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Inasistencias List -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center justify-content-between">
                            <h5 class="mb-0">Historial de Inasistencias</h5>
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-outline-primary btn-sm" onclick="filtrarInasistencias('todas')">
                                    <i class="ti ti-filter"></i> Todas
                                </button>
                                <button type="button" class="btn btn-outline-success btn-sm" onclick="filtrarInasistencias('justificadas')">
                                    <i class="ti ti-check"></i> Justificadas
                                </button>
                                <button type="button" class="btn btn-outline-warning btn-sm" onclick="filtrarInasistencias('pendientes')">
                                    <i class="ti ti-clock"></i> Pendientes
                                </button>
                                <button type="button" class="btn btn-outline-danger btn-sm" onclick="filtrarInasistencias('injustificadas')">
                                    <i class="ti ti-x"></i> Injustificadas
                                </button>
                                <button type="button" class="btn btn-primary btn-sm" onclick="abrirModalNuevaInasistencia()">
                                    <i class="ti ti-plus"></i> Nueva Inasistencia
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover" id="tablaInasistencias">
                                <thead>
                                    <tr>
                                        <th>Fecha</th>
                                        <th>Tipo</th>
                                        <th>Motivo</th>
                                        <th>Estado</th>
                                        <th>Justificación</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody id="tbodyInasistencias">
                                    <!-- Los datos se cargarán dinámicamente -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Políticas de Inasistencia -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Políticas de Inasistencia</h5>
                    </div>
                    <div class="card-body">
                        <div class="row" id="politicasInasistencia">
                            <!-- Las políticas se cargarán dinámicamente -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Nueva Inasistencia -->
<div class="modal fade" id="modalNuevaInasistencia" tabindex="-1" aria-labelledby="modalNuevaInasistenciaLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalNuevaInasistenciaLabel">Registrar Nueva Inasistencia</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formNuevaInasistencia">
                    <div class="mb-3">
                        <label for="fecha_inasistencia" class="form-label">Fecha de Inasistencia</label>
                        <input type="date" class="form-control" id="fecha_inasistencia" name="fecha_inasistencia" required>
                    </div>
                    <div class="mb-3">
                        <label for="tipo_inasistencia" class="form-label">Tipo de Inasistencia</label>
                        <select class="form-select" id="tipo_inasistencia" name="tipo_inasistencia" required>
                            <option value="">Seleccionar tipo</option>
                            <option value="Enfermedad">Enfermedad</option>
                            <option value="Emergencia Familiar">Emergencia Familiar</option>
                            <option value="Cita Médica">Cita Médica</option>
                            <option value="Asuntos Personales">Asuntos Personales</option>
                            <option value="Capacitación">Capacitación</option>
                            <option value="Otros">Otros</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="motivo_inasistencia" class="form-label">Motivo Detallado</label>
                        <textarea class="form-control" id="motivo_inasistencia" name="motivo_inasistencia" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="documento_soporte" class="form-label">Documento de Soporte</label>
                        <input type="file" class="form-control" id="documento_soporte" name="documento_soporte" accept=".pdf,.jpg,.jpeg,.png">
                        <small class="text-muted">Formatos permitidos: PDF, JPG, PNG (máx. 5MB)</small>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="enviarInasistencia()">Enviar Solicitud</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Detalles de Inasistencia -->
<div class="modal fade" id="modalDetalleInasistencia" tabindex="-1" aria-labelledby="modalDetalleInasistenciaLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalDetalleInasistenciaLabel">Detalles de Inasistencia</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="modalDetalleInasistenciaBody">
                <!-- Contenido del modal se cargará dinámicamente -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" onclick="editarInasistencia()">Editar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Justificación -->
<div class="modal fade" id="modalJustificacion" tabindex="-1" aria-labelledby="modalJustificacionLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalJustificacionLabel">Justificar Inasistencia</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="modalJustificacionBody">
                <!-- Contenido del modal se cargará dinámicamente -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="enviarJustificacion()">Enviar Justificación</button>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Variables globales
let inasistenciasData = [];
let filtroActual = 'todas';
let chartAsistencia;

// Cargar datos al iniciar la página
document.addEventListener('DOMContentLoaded', function() {
    cargarEstadisticas();
    cargarInasistencias();
    cargarPoliticas();
    inicializarGrafico();
});

// Cargar estadísticas
function cargarEstadisticas() {
    // Simular carga de estadísticas (en producción esto vendría de una API)
    document.getElementById('dias_asistidos').textContent = '22';
    document.getElementById('inasistencias_justificadas').textContent = '3';
    document.getElementById('inasistencias_pendientes').textContent = '1';
    document.getElementById('inasistencias_injustificadas').textContent = '0';
}

// Cargar inasistencias del empleado
function cargarInasistencias() {
    // Simular datos (en producción esto vendría de una API)
    inasistenciasData = [
        {
            id: 1,
            fecha: '2025-01-15',
            tipo: 'Enfermedad',
            motivo: 'Gripe con fiebre alta',
            estado: 'Justificada',
            justificacion: 'Certificado médico presentado',
            documento: 'certificado_medico_001.pdf',
            categoria: 'justificada'
        },
        {
            id: 2,
            fecha: '2025-01-20',
            tipo: 'Cita Médica',
            motivo: 'Control médico programado',
            estado: 'Justificada',
            justificacion: 'Cita médica programada con anticipación',
            documento: 'cita_medica_001.pdf',
            categoria: 'justificada'
        },
        {
            id: 3,
            fecha: '2025-02-05',
            tipo: 'Emergencia Familiar',
            motivo: 'Accidente de familiar cercano',
            estado: 'Pendiente',
            justificacion: 'En proceso de documentación',
            documento: null,
            categoria: 'pendiente'
        },
        {
            id: 4,
            fecha: '2025-02-10',
            tipo: 'Capacitación',
            motivo: 'Curso de actualización profesional',
            estado: 'Justificada',
            justificacion: 'Capacitación autorizada por RRHH',
            documento: 'autorizacion_capacitacion_001.pdf',
            categoria: 'justificada'
        }
    ];
    
    mostrarInasistencias(inasistenciasData);
}

// Mostrar inasistencias en la tabla
function mostrarInasistencias(inasistencias) {
    const tbody = document.getElementById('tbodyInasistencias');
    tbody.innerHTML = '';
    
    inasistencias.forEach(inasistencia => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${formatearFecha(inasistencia.fecha)}</td>
            <td><span class="badge bg-light text-dark">${inasistencia.tipo}</span></td>
            <td>${inasistencia.motivo}</td>
            <td>${obtenerBadgeEstado(inasistencia.estado)}</td>
            <td>${inasistencia.justificacion || 'Sin justificación'}</td>
            <td>
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="verDetalleInasistencia(${inasistencia.id})">
                        <i class="ti ti-eye"></i>
                    </button>
                    ${inasistencia.estado === 'Pendiente' ? 
                        `<button type="button" class="btn btn-sm btn-outline-warning" onclick="justificarInasistencia(${inasistencia.id})">
                            <i class="ti ti-edit"></i>
                        </button>` : ''
                    }
                    ${inasistencia.documento ? 
                        `<button type="button" class="btn btn-sm btn-outline-success" onclick="descargarDocumento('${inasistencia.documento}')">
                            <i class="ti ti-download"></i>
                        </button>` : ''
                    }
                </div>
            </td>
        `;
        tbody.appendChild(row);
    });
}

// Cargar políticas de inasistencia
function cargarPoliticas() {
    const container = document.getElementById('politicasInasistencia');
    
    // Simular datos (en producción esto vendría de una API)
    const politicas = [
        {
            titulo: 'Enfermedad',
            descripcion: 'Máximo 5 días por año con certificado médico',
            color: 'success',
            icono: 'ti ti-heart'
        },
        {
            titulo: 'Emergencia Familiar',
            descripcion: 'Hasta 3 días por año con justificación',
            color: 'warning',
            icono: 'ti ti-users'
        },
        {
            titulo: 'Cita Médica',
            descripcion: 'Sin límite con cita programada',
            color: 'info',
            icono: 'ti ti-stethoscope'
        },
        {
            titulo: 'Capacitación',
            descripcion: 'Sin límite si está autorizada',
            color: 'primary',
            icono: 'ti ti-school'
        }
    ];
    
    container.innerHTML = '';
    politicas.forEach(politica => {
        const col = document.createElement('div');
        col.className = 'col-md-6 col-lg-3 mb-3';
        col.innerHTML = `
            <div class="card h-100">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <i class="${politica.icono} fs-1 text-${politica.color}"></i>
                    </div>
                    <h6 class="card-title">${politica.titulo}</h6>
                    <p class="card-text text-muted small">${politica.descripcion}</p>
                </div>
            </div>
        `;
        container.appendChild(col);
    });
}

// Inicializar gráfico de asistencia
function inicializarGrafico() {
    const ctx = document.getElementById('chartAsistencia').getContext('2d');
    
    // Simular datos del mes (en producción esto vendría de una API)
    const datos = {
        labels: ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20', '21', '22', '23', '24', '25', '26', '27', '28', '29', '30', '31'],
        datasets: [{
            label: 'Asistencia',
            data: [1, 1, 1, 1, 1, 0, 0, 1, 1, 1, 1, 1, 1, 0, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 0, 0, 1, 1, 1, 1],
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            borderColor: 'rgb(75, 192, 192)',
            borderWidth: 1
        }]
    };
    
    chartAsistencia = new Chart(ctx, {
        type: 'bar',
        data: datos,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    max: 1,
                    ticks: {
                        stepSize: 1,
                        callback: function(value) {
                            return value === 1 ? 'Asistió' : 'No Asistió';
                        }
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });
}

// Filtrar inasistencias
function filtrarInasistencias(filtro) {
    filtroActual = filtro;
    
    let inasistenciasFiltradas = [];
    
    switch(filtro) {
        case 'justificadas':
            inasistenciasFiltradas = inasistenciasData.filter(i => i.categoria === 'justificada');
            break;
        case 'pendientes':
            inasistenciasFiltradas = inasistenciasData.filter(i => i.categoria === 'pendiente');
            break;
        case 'injustificadas':
            inasistenciasFiltradas = inasistenciasData.filter(i => i.categoria === 'injustificada');
            break;
        default:
            inasistenciasFiltradas = inasistenciasData;
    }
    
    mostrarInasistencias(inasistenciasFiltradas);
}

// Abrir modal para nueva inasistencia
function abrirModalNuevaInasistencia() {
    const modal = new bootstrap.Modal(document.getElementById('modalNuevaInasistencia'));
    modal.show();
}

// Enviar inasistencia
function enviarInasistencia() {
    const form = document.getElementById('formNuevaInasistencia');
    const formData = new FormData(form);
    
    // Validar formulario
    if (!formData.get('fecha_inasistencia') || !formData.get('tipo_inasistencia') || !formData.get('motivo_inasistencia')) {
        alert('Por favor, complete todos los campos obligatorios');
        return;
    }
    
    // Aquí se enviaría la solicitud al servidor
    alert('Inasistencia registrada exitosamente');
    
    // Cerrar modal y limpiar formulario
    const modal = bootstrap.Modal.getInstance(document.getElementById('modalNuevaInasistencia'));
    modal.hide();
    form.reset();
    
    // Recargar datos
    cargarInasistencias();
}

// Ver detalle de inasistencia
function verDetalleInasistencia(id) {
    const inasistencia = inasistenciasData.find(i => i.id === id);
    if (!inasistencia) return;
    
    const modalBody = document.getElementById('modalDetalleInasistenciaBody');
    modalBody.innerHTML = `
        <div class="row">
            <div class="col-md-6">
                <h6>Información General</h6>
                <p><strong>Fecha:</strong> ${formatearFecha(inasistencia.fecha)}</p>
                <p><strong>Tipo:</strong> ${inasistencia.tipo}</p>
                <p><strong>Estado:</strong> ${inasistencia.estado}</p>
                <p><strong>Motivo:</strong> ${inasistencia.motivo}</p>
            </div>
            <div class="col-md-6">
                <h6>Justificación</h6>
                <p><strong>Estado:</strong> ${inasistencia.justificacion || 'Sin justificación'}</p>
                ${inasistencia.documento ? 
                    `<p><strong>Documento:</strong> <a href="#" onclick="descargarDocumento('${inasistencia.documento}')">${inasistencia.documento}</a></p>` : 
                    '<p><strong>Documento:</strong> No presentado</p>'
                }
            </div>
        </div>
    `;
    
    const modal = new bootstrap.Modal(document.getElementById('modalDetalleInasistencia'));
    modal.show();
}

// Justificar inasistencia
function justificarInasistencia(id) {
    const inasistencia = inasistenciasData.find(i => i.id === id);
    if (!inasistencia) return;
    
    const modalBody = document.getElementById('modalJustificacionBody');
    modalBody.innerHTML = `
        <div class="alert alert-info">
            <h6><i class="ti ti-info-circle"></i> Inasistencia del ${formatearFecha(inasistencia.fecha)}</h6>
            <p class="mb-0"><strong>Motivo:</strong> ${inasistencia.motivo}</p>
        </div>
        <form id="formJustificacion">
            <div class="mb-3">
                <label for="justificacion_detallada" class="form-label">Justificación Detallada</label>
                <textarea class="form-control" id="justificacion_detallada" name="justificacion_detallada" rows="3" required></textarea>
            </div>
            <div class="mb-3">
                <label for="documento_justificacion" class="form-label">Documento de Soporte</label>
                <input type="file" class="form-control" id="documento_justificacion" name="documento_justificacion" accept=".pdf,.jpg,.jpeg,.png" required>
                <small class="text-muted">Formatos permitidos: PDF, JPG, PNG (máx. 5MB)</small>
            </div>
        </form>
    `;
    
    const modal = new bootstrap.Modal(document.getElementById('modalJustificacion'));
    modal.show();
}

// Enviar justificación
function enviarJustificacion() {
    const form = document.getElementById('formJustificacion');
    const formData = new FormData(form);
    
    // Validar formulario
    if (!formData.get('justificacion_detallada') || !formData.get('documento_justificacion')) {
        alert('Por favor, complete todos los campos obligatorios');
        return;
    }
    
    // Aquí se enviaría la solicitud al servidor
    alert('Justificación enviada exitosamente');
    
    // Cerrar modal
    const modal = bootstrap.Modal.getInstance(document.getElementById('modalJustificacion'));
    modal.hide();
    
    // Recargar datos
    cargarInasistencias();
}

// Editar inasistencia
function editarInasistencia() {
    alert('Funcionalidad de edición en desarrollo');
}

// Descargar documento
function descargarDocumento(nombreDocumento) {
    alert(`Descargando documento: ${nombreDocumento}`);
    // Aquí se implementaría la descarga del documento
}

// Funciones auxiliares
function formatearFecha(fecha) {
    if (!fecha) return 'N/A';
    return new Date(fecha).toLocaleDateString('es-ES');
}

function obtenerBadgeEstado(estado) {
    const badges = {
        'Justificada': 'bg-success',
        'Pendiente': 'bg-warning',
        'Injustificada': 'bg-danger',
        'En Revisión': 'bg-info'
    };
    
    return `<span class="badge ${badges[estado] || 'bg-secondary'}">${estado}</span>`;
}
</script>
<?= $this->endSection() ?>
