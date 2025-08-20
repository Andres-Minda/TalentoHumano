<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>
<div class="page-wrapper">
    <div class="page-content">
        <!-- Breadcrumb -->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Solicitudes de Capacitación</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="<?= site_url('admin-th/dashboard') ?>">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Solicitudes de Capacitación</li>
                    </ol>
                </nav>
            </div>
        </div>

        <!-- Page Header -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Gestión de Solicitudes de Capacitación</h4>
                        <div class="card-actions">
                            <button type="button" class="btn btn-success" onclick="aprobarSeleccionadas()">
                                <i class="ti ti-check"></i> Aprobar Seleccionadas
                            </button>
                            <button type="button" class="btn btn-danger" onclick="rechazarSeleccionadas()">
                                <i class="ti ti-x"></i> Rechazar Seleccionadas
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Filtros -->
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <select class="form-select" id="filtroEstado">
                                    <option value="">Todos los Estados</option>
                                    <option value="Pendiente">Pendiente</option>
                                    <option value="Aprobada">Aprobada</option>
                                    <option value="Rechazada">Rechazada</option>
                                    <option value="Cancelada">Cancelada</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select class="form-select" id="filtroPrioridad">
                                    <option value="">Todas las Prioridades</option>
                                    <option value="Alta">Alta</option>
                                    <option value="Media">Media</option>
                                    <option value="Baja">Baja</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <input type="text" class="form-control" id="filtroBusqueda" placeholder="Buscar empleado...">
                            </div>
                            <div class="col-md-3">
                                <button class="btn btn-primary" onclick="aplicarFiltros()">
                                    <i class="ti ti-search"></i> Filtrar
                                </button>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-striped" id="tablaSolicitudes">
                                <thead>
                                    <tr>
                                        <th>
                                            <input type="checkbox" id="selectAll" onchange="toggleSelectAll()">
                                        </th>
                                        <th>ID</th>
                                        <th>Empleado</th>
                                        <th>Tipo de Capacitación</th>
                                        <th>Prioridad</th>
                                        <th>Fecha Solicitud</th>
                                        <th>Estado</th>
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
    </div>
</div>

<!-- Modal para revisar solicitud -->
<div class="modal fade" id="modalRevisarSolicitud" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Revisar Solicitud de Capacitación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6>Información del Empleado</h6>
                        <table class="table table-sm">
                            <tr><td><strong>Nombre:</strong></td><td id="modalEmpleadoNombre">-</td></tr>
                            <tr><td><strong>Departamento:</strong></td><td id="modalEmpleadoDepartamento">-</td></tr>
                            <tr><td><strong>Cargo:</strong></td><td id="modalEmpleadoCargo">-</td></tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6>Detalles de la Solicitud</h6>
                        <table class="table table-sm">
                            <tr><td><strong>Fecha:</strong></td><td id="modalFechaSolicitud">-</td></tr>
                            <tr><td><strong>Prioridad:</strong></td><td id="modalPrioridad">-</td></tr>
                            <tr><td><strong>Estado:</strong></td><td id="modalEstado">-</td></tr>
                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <h6>Descripción de la Capacitación</h6>
                        <p id="modalDescripcion">-</p>
                        
                        <h6>Justificación</h6>
                        <p id="modalJustificacion">-</p>
                        
                        <h6>Beneficios Esperados</h6>
                        <p id="modalBeneficios">-</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <label for="modalComentarios" class="form-label">Comentarios del Revisor</label>
                        <textarea class="form-control" id="modalComentarios" rows="3" placeholder="Ingrese sus comentarios sobre la solicitud"></textarea>
                    </div>
                    <div class="col-md-6">
                        <label for="modalDecision" class="form-label">Decisión</label>
                        <select class="form-select" id="modalDecision">
                            <option value="">Seleccionar...</option>
                            <option value="Aprobada">Aprobar</option>
                            <option value="Rechazada">Rechazar</option>
                            <option value="Pendiente">Mantener Pendiente</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" onclick="guardarDecision()">Guardar Decisión</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    cargarSolicitudes();
});

function cargarSolicitudes() {
    // Simular carga de solicitudes
    const solicitudes = [
        { id: 1, empleado: 'Juan Pérez', tipo: 'Desarrollo Web', prioridad: 'Alta', fecha: '2025-01-15', estado: 'Pendiente' },
        { id: 2, empleado: 'María García', tipo: 'Gestión de Proyectos', prioridad: 'Media', fecha: '2025-01-14', estado: 'Pendiente' },
        { id: 3, empleado: 'Carlos López', tipo: 'Liderazgo', prioridad: 'Baja', fecha: '2025-01-13', estado: 'Aprobada' },
        { id: 4, empleado: 'Ana Rodríguez', tipo: 'Excel Avanzado', prioridad: 'Media', fecha: '2025-01-12', estado: 'Rechazada' }
    ];

    const tbody = document.getElementById('tbodySolicitudes');
    tbody.innerHTML = '';

    solicitudes.forEach(solicitud => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td><input type="checkbox" class="solicitud-checkbox" value="${solicitud.id}"></td>
            <td>${solicitud.id}</td>
            <td><strong>${solicitud.empleado}</strong></td>
            <td>${solicitud.tipo}</td>
            <td><span class="badge bg-${getPrioridadBadgeColor(solicitud.prioridad)}">${solicitud.prioridad}</span></td>
            <td>${formatearFecha(solicitud.fecha)}</td>
            <td><span class="badge bg-${getEstadoBadgeColor(solicitud.estado)}">${solicitud.estado}</span></td>
            <td>
                <button class="btn btn-sm btn-outline-primary" onclick="revisarSolicitud(${solicitud.id})">
                    <i class="ti ti-eye"></i>
                </button>
                <button class="btn btn-sm btn-outline-success" onclick="aprobarSolicitud(${solicitud.id})" ${solicitud.estado !== 'Pendiente' ? 'disabled' : ''}>
                    <i class="ti ti-check"></i>
                </button>
                <button class="btn btn-sm btn-outline-danger" onclick="rechazarSolicitud(${solicitud.id})" ${solicitud.estado !== 'Pendiente' ? 'disabled' : ''}>
                    <i class="ti ti-x"></i>
                </button>
            </td>
        `;
        tbody.appendChild(row);
    });
}

function getPrioridadBadgeColor(prioridad) {
    switch(prioridad) {
        case 'Alta': return 'danger';
        case 'Media': return 'warning';
        case 'Baja': return 'success';
        default: return 'secondary';
    }
}

function getEstadoBadgeColor(estado) {
    switch(estado) {
        case 'Pendiente': return 'warning';
        case 'Aprobada': return 'success';
        case 'Rechazada': return 'danger';
        case 'Cancelada': return 'secondary';
        default: return 'info';
    }
}

function formatearFecha(fecha) {
    return new Date(fecha).toLocaleDateString('es-ES');
}

function toggleSelectAll() {
    const selectAll = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.solicitud-checkbox');
    
    checkboxes.forEach(checkbox => {
        checkbox.checked = selectAll.checked;
    });
}

function aprobarSeleccionadas() {
    const checkboxes = document.querySelectorAll('.solicitud-checkbox:checked');
    if (checkboxes.length === 0) {
        alert('Seleccione al menos una solicitud para aprobar');
        return;
    }
    
    if (confirm(`¿Está seguro de que desea aprobar ${checkboxes.length} solicitud(es)?`)) {
        // Aquí implementarías la lógica para aprobar múltiples solicitudes
        alert('Funcionalidad de aprobación múltiple en desarrollo');
    }
}

function rechazarSeleccionadas() {
    const checkboxes = document.querySelectorAll('.solicitud-checkbox:checked');
    if (checkboxes.length === 0) {
        alert('Seleccione al menos una solicitud para rechazar');
        return;
    }
    
    if (confirm(`¿Está seguro de que desea rechazar ${checkboxes.length} solicitud(es)?`)) {
        // Aquí implementarías la lógica para rechazar múltiples solicitudes
        alert('Funcionalidad de rechazo múltiple en desarrollo');
    }
}

function revisarSolicitud(id) {
    // Aquí cargarías los datos de la solicitud en el modal
    document.getElementById('modalEmpleadoNombre').textContent = 'Juan Pérez';
    document.getElementById('modalEmpleadoDepartamento').textContent = 'Tecnología';
    document.getElementById('modalEmpleadoCargo').textContent = 'Desarrollador';
    document.getElementById('modalFechaSolicitud').textContent = '15/01/2025';
    document.getElementById('modalPrioridad').textContent = 'Alta';
    document.getElementById('modalEstado').textContent = 'Pendiente';
    document.getElementById('modalDescripcion').textContent = 'Capacitación en desarrollo web moderno con React y Node.js';
    document.getElementById('modalJustificacion').textContent = 'Necesario para proyectos actuales y futuros del departamento';
    document.getElementById('modalBeneficios').textContent = 'Mejorará la calidad del código y reducirá tiempo de desarrollo';
    
    const modal = new bootstrap.Modal(document.getElementById('modalRevisarSolicitud'));
    modal.show();
}

function aprobarSolicitud(id) {
    if (confirm('¿Está seguro de que desea aprobar esta solicitud?')) {
        // Aquí implementarías la lógica para aprobar
        alert('Funcionalidad de aprobación en desarrollo');
    }
}

function rechazarSolicitud(id) {
    if (confirm('¿Está seguro de que desea rechazar esta solicitud?')) {
        // Aquí implementarías la lógica para rechazar
        alert('Funcionalidad de rechazo en desarrollo');
    }
}

function aplicarFiltros() {
    // Aquí implementarías la lógica para aplicar filtros
    alert('Funcionalidad de filtros en desarrollo');
}

function guardarDecision() {
    const decision = document.getElementById('modalDecision').value;
    const comentarios = document.getElementById('modalComentarios').value;
    
    if (!decision) {
        alert('Debe seleccionar una decisión');
        return;
    }
    
    // Aquí implementarías la lógica para guardar la decisión
    alert('Funcionalidad de guardado de decisión en desarrollo');
    
    // Cerrar modal
    const modal = bootstrap.Modal.getInstance(document.getElementById('modalRevisarSolicitud'));
    modal.hide();
}
</script>
<?= $this->endSection() ?>
