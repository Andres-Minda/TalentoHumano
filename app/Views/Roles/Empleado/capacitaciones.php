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
                            <div class="flex-shrink-0"><i class="ti ti-book fs-1 text-white"></i></div>
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
                            <div class="flex-shrink-0"><i class="ti ti-check fs-1 text-white"></i></div>
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
                            <div class="flex-shrink-0"><i class="ti ti-clock fs-1 text-white"></i></div>
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
                            <div class="flex-shrink-0"><i class="ti ti-award fs-1 text-white"></i></div>
                            <div class="flex-grow-1 ms-3">
                                <h4 class="mb-0 text-white" id="horas_acumuladas">0</h4>
                                <p class="mb-0 text-white-50">Horas Acumuladas</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mis Capacitaciones Inscritas -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center justify-content-between">
                            <h5 class="mb-0">Mis Capacitaciones Inscritas</h5>
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-outline-primary btn-sm" onclick="filtrarCapacitaciones('todas')">
                                    <i class="ti ti-filter"></i> Todas
                                </button>
                                <button type="button" class="btn btn-outline-success btn-sm" onclick="filtrarCapacitaciones('COMPLETADA')">
                                    <i class="ti ti-check"></i> Completadas
                                </button>
                                <button type="button" class="btn btn-outline-warning btn-sm" onclick="filtrarCapacitaciones('EN_CURSO')">
                                    <i class="ti ti-clock"></i> En Curso
                                </button>
                                <button type="button" class="btn btn-outline-info btn-sm" onclick="filtrarCapacitaciones('ACTIVA')">
                                    <i class="ti ti-calendar"></i> Activas
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
                                        <th>Modalidad</th>
                                        <th>Fecha Inicio</th>
                                        <th>Fecha Fin</th>
                                        <th>Estado</th>
                                        <th>Duración</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody id="tbodyCapacitaciones">
                                    <tr><td colspan="7" class="text-center"><i class="spinner-border spinner-border-sm"></i> Cargando...</td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Capacitaciones Disponibles para Inscribirse -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Capacitaciones Disponibles</h5>
                    </div>
                    <div class="card-body">
                        <div class="row" id="capacitacionesDisponibles">
                            <div class="col-12 text-center text-muted">
                                <i class="spinner-border spinner-border-sm"></i> Cargando capacitaciones disponibles...
                            </div>
                        </div>
                    </div>
                </div>
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

document.addEventListener('DOMContentLoaded', function() {
    cargarMisCapacitaciones();
    cargarCapacitacionesDisponibles();
});

// Cargar mis capacitaciones inscritas desde el backend
function cargarMisCapacitaciones() {
    fetch('<?= base_url('index.php/empleado/capacitaciones/obtener') ?>')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                capacitacionesData = data.capacitaciones;
                actualizarEstadisticas(capacitacionesData);
                mostrarCapacitaciones(capacitacionesData);
            } else {
                document.getElementById('tbodyCapacitaciones').innerHTML = 
                    '<tr><td colspan="7" class="text-center text-danger">Error al cargar capacitaciones</td></tr>';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('tbodyCapacitaciones').innerHTML = 
                '<tr><td colspan="7" class="text-center text-danger">Error de conexión al servidor</td></tr>';
        });
}

// Actualizar tarjetas de estadísticas
function actualizarEstadisticas(capacitaciones) {
    document.getElementById('total_capacitaciones').textContent = capacitaciones.length;
    
    const completadas = capacitaciones.filter(c => c.estado === 'COMPLETADA').length;
    const enCurso = capacitaciones.filter(c => c.estado === 'EN_CURSO').length;
    const horasTotal = capacitaciones.reduce((sum, c) => sum + (parseInt(c.duracion_horas) || 0), 0);
    
    document.getElementById('capacitaciones_completadas').textContent = completadas;
    document.getElementById('capacitaciones_en_curso').textContent = enCurso;
    document.getElementById('horas_acumuladas').textContent = horasTotal;
}

// Mostrar capacitaciones en la tabla
function mostrarCapacitaciones(capacitaciones) {
    const tbody = document.getElementById('tbodyCapacitaciones');
    tbody.innerHTML = '';
    
    if (!capacitaciones || capacitaciones.length === 0) {
        tbody.innerHTML = '<tr><td colspan="7" class="text-center text-muted">No tienes capacitaciones inscritas aún</td></tr>';
        return;
    }
    
    capacitaciones.forEach(c => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0"><i class="ti ti-book text-primary fs-4"></i></div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="mb-0">${c.nombre}</h6>
                        <small class="text-muted">${c.descripcion || ''}</small>
                    </div>
                </div>
            </td>
            <td><span class="badge bg-light text-dark">${c.modalidad || '-'}</span></td>
            <td>${formatearFecha(c.fecha_inicio)}</td>
            <td>${formatearFecha(c.fecha_fin)}</td>
            <td>${obtenerBadgeEstado(c.estado)}</td>
            <td>${c.duracion_horas || '-'} hrs</td>
            <td>
                <button type="button" class="btn btn-sm btn-outline-primary" onclick="verDetalleCapacitacion(${c.id_capacitacion})">
                    <i class="ti ti-eye"></i>
                </button>
            </td>
        `;
        tbody.appendChild(row);
    });
}

// Cargar capacitaciones disponibles para inscribirse
function cargarCapacitacionesDisponibles() {
    const container = document.getElementById('capacitacionesDisponibles');
    
    fetch('<?= base_url('index.php/empleado/capacitaciones/disponibles') ?>')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                renderizarDisponibles(data.capacitaciones);
            } else {
                container.innerHTML = '<div class="col-12 text-center text-danger">Error al cargar</div>';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            container.innerHTML = '<div class="col-12 text-center text-danger">Error de conexión</div>';
        });
}

function renderizarDisponibles(capacitaciones) {
    const container = document.getElementById('capacitacionesDisponibles');
    container.innerHTML = '';
    
    if (!capacitaciones || capacitaciones.length === 0) {
        container.innerHTML = '<div class="col-12 text-center text-muted">No hay capacitaciones disponibles en este momento</div>';
        return;
    }
    
    capacitaciones.forEach(c => {
        const esEnCurso = c.estado === 'EN_CURSO';
        const esActiva = c.estado === 'ACTIVA';
        
        // Botón de inscripción: deshabilitado si EN_CURSO, habilitado solo si ACTIVA
        let botonHtml = '';
        if (esEnCurso) {
            botonHtml = `<button type="button" class="btn btn-secondary btn-sm w-100" disabled>
                            <i class="ti ti-lock"></i> Capacitación en curso (Cerrada)
                         </button>`;
        } else if (esActiva) {
            botonHtml = `<button type="button" class="btn btn-primary btn-sm w-100" onclick="inscribirseCapacitacion(${c.id_capacitacion})">
                            <i class="ti ti-plus"></i> Inscribirse
                         </button>`;
        } else {
            botonHtml = `<button type="button" class="btn btn-secondary btn-sm w-100" disabled>
                            No disponible
                         </button>`;
        }
        
        const card = document.createElement('div');
        card.className = 'col-md-6 col-lg-4 mb-3';
        card.innerHTML = `
            <div class="card h-100 ${esEnCurso ? 'border-warning' : ''}">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <h6 class="card-title mb-0">${c.nombre}</h6>
                        ${obtenerBadgeEstado(c.estado)}
                    </div>
                    <p class="card-text text-muted small">${c.descripcion || 'Sin descripción'}</p>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <small class="text-muted"><i class="ti ti-clock"></i> ${c.duracion_horas || '-'} hrs</small>
                        <small class="text-muted"><i class="ti ti-device-laptop"></i> ${c.modalidad || '-'}</small>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <small class="text-muted">${formatearFecha(c.fecha_inicio)} - ${formatearFecha(c.fecha_fin)}</small>
                        <span class="badge bg-light text-dark">${c.total_inscritos || 0} inscritos</span>
                    </div>
                    ${botonHtml}
                </div>
            </div>
        `;
        container.appendChild(card);
    });
}

// Inscribirse en una capacitación
function inscribirseCapacitacion(idCapacitacion) {
    Swal.fire({
        title: '¿Inscribirse?',
        text: '¿Deseas inscribirte en esta capacitación?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#198754',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sí, inscribirme',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            const formData = new FormData();
            formData.append('id_capacitacion', idCapacitacion);
            
            fetch('<?= base_url('index.php/empleado/capacitaciones/inscribir') ?>', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({ icon: 'success', title: '¡Inscrito!', text: data.message, timer: 2000, showConfirmButton: false })
                    .then(() => {
                        cargarMisCapacitaciones();
                        cargarCapacitacionesDisponibles();
                    });
                } else {
                    Swal.fire({ icon: 'error', title: 'No se pudo inscribir', text: data.message });
                }
            })
            .catch(error => {
                Swal.fire({ icon: 'error', title: 'Error', text: 'Error de conexión al servidor' });
            });
        }
    });
}

// Filtrar capacitaciones
function filtrarCapacitaciones(filtro) {
    filtroActual = filtro;
    
    if (filtro === 'todas') {
        mostrarCapacitaciones(capacitacionesData);
    } else {
        const filtradas = capacitacionesData.filter(c => c.estado === filtro);
        mostrarCapacitaciones(filtradas);
    }
}

// Ver detalle de capacitación
function verDetalleCapacitacion(id) {
    const c = capacitacionesData.find(cap => cap.id_capacitacion == id);
    if (!c) return;
    
    Swal.fire({
        title: c.nombre,
        html: `
            <div class="text-start">
                <p><strong>Descripción:</strong> ${c.descripcion || 'Sin descripción'}</p>
                <p><strong>Modalidad:</strong> ${c.modalidad || '-'}</p>
                <p><strong>Duración:</strong> ${c.duracion_horas || '-'} horas</p>
                <p><strong>Estado:</strong> ${c.estado}</p>
                <p><strong>Inicio:</strong> ${formatearFecha(c.fecha_inicio)}</p>
                <p><strong>Fin:</strong> ${formatearFecha(c.fecha_fin)}</p>
            </div>
        `,
        icon: 'info',
        confirmButtonText: 'Cerrar'
    });
}

// Funciones auxiliares
function formatearFecha(fecha) {
    if (!fecha) return 'N/A';
    return new Date(fecha).toLocaleDateString('es-ES');
}

function obtenerBadgeEstado(estado) {
    const badges = {
        'ACTIVA': 'bg-success',
        'EN_CURSO': 'bg-warning',
        'COMPLETADA': 'bg-info',
        'INACTIVA': 'bg-danger',
        'CANCELADA': 'bg-secondary'
    };
    return `<span class="badge ${badges[estado] || 'bg-secondary'}">${estado}</span>`;
}
</script>
<?= $this->endSection() ?>
