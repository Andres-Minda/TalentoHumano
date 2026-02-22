<?php $sidebar = 'sidebar_empleado'; ?>
<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>

<div class="page-wrapper">
    <div class="page-content">
        <!-- Header -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <h4 class="mb-0"><i class="bi bi-lightning"></i> Acceso Rápido</h4>
                    <div class="page-title-right">
                        <span class="text-muted">Panel de acceso directo a funcionalidades principales</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Welcome Section -->
        <div class="row">
            <div class="col-12">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h4 class="mb-2">¡Bienvenido, <?= session()->get('nombres') ?>!</h4>
                                <p class="mb-0">Accede rápidamente a las funciones más utilizadas del sistema de Talento Humano.</p>
                            </div>
                            <div class="col-md-4 text-end">
                                <i class="bi bi-person-badge-fill" style="font-size: 3rem; opacity: 0.3;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Access Cards -->
        <div class="row">
            <!-- Mi Perfil -->
            <div class="col-xl-4 col-lg-6 col-md-6">
                <div class="card">
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <i class="bi bi-person-circle text-primary" style="font-size: 3rem;"></i>
                        </div>
                        <h5 class="card-title">Mi Perfil</h5>
                        <p class="card-text">Gestiona tu información personal y configuración de cuenta.</p>
                        <div class="d-grid gap-2">
                            <a href="<?= base_url('empleado/mi-perfil') ?>" class="btn btn-primary">
                                <i class="bi bi-person"></i> Ver Perfil
                            </a>
                            <a href="<?= base_url('empleado/cuenta') ?>" class="btn btn-outline-primary">
                                <i class="bi bi-gear"></i> Configuración
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Capacitaciones -->
            <div class="col-xl-4 col-lg-6 col-md-6">
                <div class="card">
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <i class="bi bi-mortarboard text-success" style="font-size: 3rem;"></i>
                        </div>
                        <h5 class="card-title">Capacitaciones</h5>
                        <p class="card-text">Accede a capacitaciones disponibles y solicita nuevas.</p>
                        <div class="d-grid gap-2">
                            <a href="<?= base_url('empleado/capacitaciones') ?>" class="btn btn-success">
                                <i class="bi bi-mortarboard"></i> Ver Capacitaciones
                            </a>
                            <a href="<?= base_url('empleado/solicitudes-capacitacion') ?>" class="btn btn-outline-success">
                                <i class="bi bi-file-earmark-text"></i> Mis Solicitudes
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Evaluaciones -->
            <div class="col-xl-4 col-lg-6 col-md-6">
                <div class="card">
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <i class="bi bi-clipboard-data text-info" style="font-size: 3rem;"></i>
                        </div>
                        <h5 class="card-title">Evaluaciones</h5>
                        <p class="card-text">Revisa tu historial de evaluaciones y desempeño.</p>
                        <div class="d-grid gap-2">
                            <a href="<?= base_url('empleado/evaluaciones') ?>" class="btn btn-info">
                                <i class="bi bi-clipboard-data"></i> Ver Evaluaciones
                            </a>
                            <a href="<?= base_url('empleado/evaluaciones/estadisticas') ?>" class="btn btn-outline-info">
                                <i class="bi bi-graph-up"></i> Estadísticas
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Control de Inasistencias -->
            <div class="col-xl-4 col-lg-6 col-md-6">
                <div class="card">
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <i class="bi bi-calendar-check text-warning" style="font-size: 3rem;"></i>
                        </div>
                        <h5 class="card-title">Control de Inasistencias</h5>
                        <p class="card-text">Gestiona tus inasistencias y justificaciones.</p>
                        <div class="d-grid gap-2">
                            <a href="<?= base_url('empleado/inasistencias') ?>" class="btn btn-warning">
                                <i class="bi bi-calendar-check"></i> Ver Inasistencias
                            </a>
                            <a href="<?= base_url('empleado/inasistencias/estadisticas') ?>" class="btn btn-outline-warning">
                                <i class="bi bi-bar-chart"></i> Estadísticas
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Permisos y Vacaciones -->
            <div class="col-xl-4 col-lg-6 col-md-6">
                <div class="card">
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <i class="bi bi-calendar-event text-secondary" style="font-size: 3rem;"></i>
                        </div>
                        <h5 class="card-title">Permisos y Vacaciones</h5>
                        <p class="card-text">Solicita permisos y gestiona tus vacaciones.</p>
                        <div class="d-grid gap-2">
                            <a href="<?= base_url('empleado/permisos') ?>" class="btn btn-secondary">
                                <i class="bi bi-calendar-event"></i> Ver Permisos
                            </a>
                            <a href="<?= base_url('empleado/vacaciones') ?>" class="btn btn-outline-secondary">
                                <i class="bi bi-umbrella-beach"></i> Vacaciones
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Documentos -->
            <div class="col-xl-4 col-lg-6 col-md-6">
                <div class="card">
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <i class="bi bi-file-earmark-text text-dark" style="font-size: 3rem;"></i>
                        </div>
                        <h5 class="card-title">Documentos</h5>
                        <p class="card-text">Gestiona tus documentos y certificaciones.</p>
                        <div class="d-grid gap-2">
                            <a href="<?= base_url('empleado/documentos') ?>" class="btn btn-dark">
                                <i class="bi bi-file-earmark-text"></i> Ver Documentos
                            </a>
                            <a href="<?= base_url('empleado/documentos/subir') ?>" class="btn btn-outline-dark">
                                <i class="bi bi-upload"></i> Subir Documento
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity Section -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-activity"></i> Mi Actividad Reciente
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Acción</th>
                                        <th>Módulo</th>
                                        <th>Fecha</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody id="tablaActividadReciente">
                                    <tr>
                                        <td colspan="5" class="text-center text-muted">
                                            <i class="bi bi-hourglass-split"></i> Cargando actividad reciente...
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Stats Section -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-speedometer2"></i> Mis Estadísticas
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <i class="bi bi-mortarboard text-success" style="font-size: 2rem;"></i>
                                    <h6 class="mt-2">Capacitaciones Completadas</h6>
                                    <h4 class="text-success" id="capacitacionesCompletadas">-</h4>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <i class="bi bi-clipboard-check text-info" style="font-size: 2rem;"></i>
                                    <h6 class="mt-2">Evaluaciones Realizadas</h6>
                                    <h4 class="text-info" id="evaluacionesRealizadas">-</h4>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <i class="bi bi-calendar-check text-warning" style="font-size: 2rem;"></i>
                                    <h6 class="mt-2">Inasistencias del Mes</h6>
                                    <h4 class="text-warning" id="inasistenciasMes">-</h4>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <i class="bi bi-calendar-event text-secondary" style="font-size: 2rem;"></i>
                                    <h6 class="mt-2">Días de Vacaciones</h6>
                                    <h4 class="text-secondary" id="diasVacaciones">-</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Notifications Section -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-bell"></i> Notificaciones Recientes
                        </h5>
                    </div>
                    <div class="card-body">
                        <div id="notificacionesRecientes">
                            <div class="text-center text-muted">
                                <i class="bi bi-hourglass-split"></i> Cargando notificaciones...
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
    cargarActividadReciente();
    cargarEstadisticasPersonales();
    cargarNotificacionesRecientes();
});

function cargarActividadReciente() {
    // Simular carga de actividad reciente
    setTimeout(() => {
        const tbody = document.getElementById('tablaActividadReciente');
        tbody.innerHTML = `
            <tr>
                <td>
                    <span class="badge bg-success">Capacitación completada</span>
                </td>
                <td>Capacitaciones</td>
                <td>Hace 2 días</td>
                <td>
                    <span class="badge bg-success">Completado</span>
                </td>
                <td>
                    <a href="<?= base_url('empleado/capacitaciones') ?>" class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-eye"></i>
                    </a>
                </td>
            </tr>
            <tr>
                <td>
                    <span class="badge bg-info">Evaluación recibida</span>
                </td>
                <td>Evaluaciones</td>
                <td>Hace 1 semana</td>
                <td>
                    <span class="badge bg-info">Pendiente</span>
                </td>
                <td>
                    <a href="<?= base_url('empleado/evaluaciones') ?>" class="btn btn-sm btn-outline-info">
                        <i class="bi bi-eye"></i>
                    </a>
                </td>
            </tr>
            <tr>
                <td>
                    <span class="badge bg-warning">Inasistencia registrada</span>
                </td>
                <td>Inasistencias</td>
                <td>Hace 3 días</td>
                <td>
                    <span class="badge bg-warning">Pendiente justificación</span>
                </td>
                <td>
                    <a href="<?= base_url('empleado/inasistencias') ?>" class="btn btn-sm btn-outline-warning">
                        <i class="bi bi-eye"></i>
                    </a>
                </td>
            </tr>
        `;
    }, 1000);
}

function cargarEstadisticasPersonales() {
    // Cargar estadísticas personales
    Promise.all([
        fetch('<?= base_url('empleado/capacitaciones/estadisticas') ?>'),
        fetch('<?= base_url('empleado/evaluaciones/estadisticas') ?>'),
        fetch('<?= base_url('empleado/inasistencias/estadisticas') ?>'),
        fetch('<?= base_url('empleado/permisos/estadisticas') ?>')
    ])
    .then(responses => Promise.all(responses.map(r => r.json())))
    .then(data => {
        // Actualizar estadísticas
        if (data[0].success) {
            document.getElementById('capacitacionesCompletadas').textContent = data[0].completadas || 0;
        }
        
        if (data[1].success) {
            document.getElementById('evaluacionesRealizadas').textContent = data[1].total || 0;
        }
        
        if (data[2].success) {
            document.getElementById('inasistenciasMes').textContent = data[2].mes_actual || 0;
        }
        
        if (data[3].success) {
            document.getElementById('diasVacaciones').textContent = data[3].disponibles || 0;
        }
    })
    .catch(error => {
        console.error('Error cargando estadísticas:', error);
    });
}

function cargarNotificacionesRecientes() {
    // Cargar notificaciones recientes
    fetch('<?= base_url('empleado/notificaciones/obtener') ?>')
    .then(response => response.json())
    .then(data => {
        const container = document.getElementById('notificacionesRecientes');
        
        if (data.success && data.notificaciones && data.notificaciones.length > 0) {
            let html = '';
            data.notificaciones.slice(0, 5).forEach(notif => {
                const fecha = new Date(notif.fecha_creacion).toLocaleDateString('es-ES');
                const badgeClass = notif.leida ? 'bg-light text-dark' : 'bg-primary text-white';
                const iconClass = notif.leida ? 'bi-bell' : 'bi-bell-fill';
                
                html += `
                    <div class="d-flex align-items-center p-3 border-bottom">
                        <div class="flex-shrink-0">
                            <span class="badge ${badgeClass} rounded-circle p-2">
                                <i class="bi ${iconClass}"></i>
                            </span>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1">${notif.titulo}</h6>
                            <p class="mb-1 text-muted">${notif.mensaje}</p>
                            <small class="text-muted">${fecha}</small>
                        </div>
                        <div class="flex-shrink-0">
                            ${!notif.leida ? 
                                `<button class="btn btn-sm btn-outline-primary" onclick="marcarComoLeida(${notif.id_notificacion})">
                                    <i class="bi bi-check"></i>
                                </button>` : 
                                '<span class="badge bg-success">Leída</span>'
                            }
                        </div>
                    </div>
                `;
            });
            
            if (data.notificaciones.length > 5) {
                html += `
                    <div class="text-center p-3">
                        <a href="<?= base_url('empleado/notificaciones') ?>" class="btn btn-outline-primary">
                            Ver todas las notificaciones
                        </a>
                    </div>
                `;
            }
            
            container.innerHTML = html;
        } else {
            container.innerHTML = `
                <div class="text-center text-muted p-4">
                    <i class="bi bi-bell-slash" style="font-size: 3rem;"></i>
                    <p class="mt-2">No tienes notificaciones pendientes</p>
                </div>
            `;
        }
    })
    .catch(error => {
        console.error('Error cargando notificaciones:', error);
        document.getElementById('notificacionesRecientes').innerHTML = `
            <div class="text-center text-danger p-4">
                <i class="bi bi-exclamation-triangle" style="font-size: 3rem;"></i>
                <p class="mt-2">Error al cargar notificaciones</p>
            </div>
        `;
    });
}

function marcarComoLeida(idNotificacion) {
    fetch('<?= base_url('empleado/notificaciones/marcar-leida') ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ id_notificacion: idNotificacion })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Recargar notificaciones
            cargarNotificacionesRecientes();
            // Actualizar contador en sidebar si existe
            if (typeof actualizarContadorNotificaciones === 'function') {
                actualizarContadorNotificaciones();
            }
        }
    })
    .catch(error => {
        console.error('Error marcando notificación:', error);
    });
}
</script>

<?= $this->endSection() ?>
