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
                        <li class="breadcrumb-item"><a href="<?= site_url('admin-th/dashboard') ?>">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Gestión de Capacitaciones</li>
                    </ol>
                </nav>
            </div>
        </div>

        <!-- Page Header -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Gestión de Capacitaciones</h4>
                        <div class="card-actions">
                            <button type="button" class="btn btn-primary" onclick="nuevaCapacitacion()">
                                <i class="ti ti-plus"></i> Nueva Capacitación
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped" id="tablaCapacitaciones">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nombre</th>
                                        <th>Descripción</th>
                                        <th>Duración</th>
                                        <th>Modalidad</th>
                                        <th>Estado</th>
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
    </div>
</div>

<!-- Modal para nueva/editar capacitación -->
<div class="modal fade" id="modalCapacitacion" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Nueva Capacitación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formCapacitacion">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="nombre" class="form-label">Nombre *</label>
                                <input type="text" class="form-control" id="nombre" name="nombre" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="duracion" class="form-label">Duración (horas) *</label>
                                <input type="number" class="form-control" id="duracion" name="duracion" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="modalidad" class="form-label">Modalidad *</label>
                                <select class="form-select" id="modalidad" name="modalidad" required>
                                    <option value="">Seleccionar...</option>
                                    <option value="PRESENCIAL">Presencial</option>
                                    <option value="VIRTUAL">Virtual</option>
                                    <option value="HIBRIDA">Híbrida</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="estado" class="form-label">Estado</label>
                                <select class="form-select" id="estado" name="estado">
                                    <option value="ACTIVA">Activa</option>
                                    <option value="INACTIVA">Inactiva</option>
                                    <option value="EN_CURSO">En Curso</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripción</label>
                        <textarea class="form-control" id="descripcion" name="descripcion" rows="3"></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="fecha_inicio" class="form-label">Fecha de Inicio</label>
                                <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="fecha_fin" class="form-label">Fecha de Fin</label>
                                <input type="date" class="form-control" id="fecha_fin" name="fecha_fin">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="guardarCapacitacion()">Guardar</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    cargarCapacitaciones();
});

function cargarCapacitaciones() {
    // Simular carga de capacitaciones
    const capacitaciones = [
        { id: 1, nombre: 'Gestión de Proyectos', descripcion: 'Capacitación en metodologías ágiles', duracion: 40, modalidad: 'PRESENCIAL', estado: 'ACTIVA' },
        { id: 2, nombre: 'Liderazgo Efectivo', descripcion: 'Desarrollo de habilidades de liderazgo', duracion: 24, modalidad: 'VIRTUAL', estado: 'ACTIVA' },
        { id: 3, nombre: 'Comunicación Asertiva', descripcion: 'Mejora de habilidades comunicativas', duracion: 16, modalidad: 'HIBRIDA', estado: 'EN_CURSO' }
    ];

    const tbody = document.getElementById('tbodyCapacitaciones');
    tbody.innerHTML = '';

    capacitaciones.forEach(capacitacion => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${capacitacion.id}</td>
            <td>${capacitacion.nombre}</td>
            <td>${capacitacion.descripcion}</td>
            <td>${capacitacion.duracion} hrs</td>
            <td><span class="badge bg-info">${capacitacion.modalidad}</span></td>
            <td><span class="badge bg-${getEstadoBadgeColor(capacitacion.estado)}">${capacitacion.estado}</span></td>
            <td>
                <button class="btn btn-sm btn-outline-primary" onclick="editarCapacitacion(${capacitacion.id})">
                    <i class="ti ti-edit"></i>
                </button>
                <button class="btn btn-sm btn-outline-danger" onclick="eliminarCapacitacion(${capacitacion.id})">
                    <i class="ti ti-trash"></i>
                </button>
            </td>
        `;
        tbody.appendChild(row);
    });
}

function nuevaCapacitacion() {
    document.getElementById('modalTitle').textContent = 'Nueva Capacitación';
    document.getElementById('formCapacitacion').reset();
    const modal = new bootstrap.Modal(document.getElementById('modalCapacitacion'));
    modal.show();
}

function editarCapacitacion(id) {
    document.getElementById('modalTitle').textContent = 'Editar Capacitación';
    // Aquí cargarías los datos de la capacitación
    const modal = new bootstrap.Modal(document.getElementById('modalCapacitacion'));
    modal.show();
}

function guardarCapacitacion() {
    // Aquí implementarías la lógica para guardar
    alert('Funcionalidad de guardado en desarrollo');
}

function eliminarCapacitacion(id) {
    if (confirm('¿Estás seguro de que quieres eliminar esta capacitación?')) {
        // Aquí implementarías la lógica para eliminar
        alert('Funcionalidad de eliminación en desarrollo');
    }
}

function getEstadoBadgeColor(estado) {
    switch(estado) {
        case 'ACTIVA': return 'success';
        case 'INACTIVA': return 'danger';
        case 'EN_CURSO': return 'warning';
        default: return 'secondary';
    }
}
</script>
<?= $this->endSection() ?>
