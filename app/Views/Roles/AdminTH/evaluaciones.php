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
                        <li class="breadcrumb-item"><a href="<?= site_url('admin-th/dashboard') ?>">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Gestión de Evaluaciones</li>
                    </ol>
                </nav>
            </div>
        </div>

        <!-- Page Header -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Gestión de Evaluaciones</h4>
                        <div class="card-actions">
                            <button type="button" class="btn btn-primary" onclick="nuevaEvaluacion()">
                                <i class="ti ti-plus"></i> Nueva Evaluación
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped" id="tablaEvaluaciones">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Empleado</th>
                                        <th>Tipo</th>
                                        <th>Fecha</th>
                                        <th>Puntuación</th>
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
    </div>
</div>

<!-- Modal para nueva/editar evaluación -->
<div class="modal fade" id="modalEvaluacion" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Nueva Evaluación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formEvaluacion">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="empleado_id" class="form-label">Empleado *</label>
                                <select class="form-select" id="empleado_id" name="empleado_id" required>
                                    <option value="">Seleccionar empleado...</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="tipo_evaluacion" class="form-label">Tipo de Evaluación *</label>
                                <select class="form-select" id="tipo_evaluacion" name="tipo_evaluacion" required>
                                    <option value="">Seleccionar tipo...</option>
                                    <option value="DESEMPEÑO">Desempeño</option>
                                    <option value="COMPETENCIAS">Competencias</option>
                                    <option value="PERIÓDICA">Periódica</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="fecha_evaluacion" class="form-label">Fecha de Evaluación *</label>
                                <input type="date" class="form-control" id="fecha_evaluacion" name="fecha_evaluacion" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="puntuacion" class="form-label">Puntuación (1-10) *</label>
                                <input type="number" class="form-control" id="puntuacion" name="puntuacion" min="1" max="10" required>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="observaciones" class="form-label">Observaciones</label>
                        <textarea class="form-control" id="observaciones" name="observaciones" rows="3"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="guardarEvaluacion()">Guardar</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    cargarEvaluaciones();
});

function cargarEvaluaciones() {
    // Simular carga de evaluaciones
    const evaluaciones = [
        { id: 1, empleado: 'Juan Pérez', tipo: 'DESEMPEÑO', fecha: '2025-01-15', puntuacion: 8, estado: 'Completada' },
        { id: 2, empleado: 'María García', tipo: 'COMPETENCIAS', fecha: '2025-01-20', puntuacion: 9, estado: 'Completada' },
        { id: 3, empleado: 'Carlos López', tipo: 'PERIÓDICA', fecha: '2025-01-25', puntuacion: 7, estado: 'Pendiente' }
    ];

    const tbody = document.getElementById('tbodyEvaluaciones');
    tbody.innerHTML = '';

    evaluaciones.forEach(evaluacion => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${evaluacion.id}</td>
            <td>${evaluacion.empleado}</td>
            <td><span class="badge bg-info">${evaluacion.tipo}</span></td>
            <td>${formatearFecha(evaluacion.fecha)}</td>
            <td><span class="badge bg-${getPuntuacionBadgeColor(evaluacion.puntuacion)}">${evaluacion.puntuacion}/10</span></td>
            <td><span class="badge bg-${getEstadoBadgeColor(evaluacion.estado)}">${evaluacion.estado}</span></td>
            <td>
                <button class="btn btn-sm btn-outline-primary" onclick="editarEvaluacion(${evaluacion.id})">
                    <i class="ti ti-edit"></i>
                </button>
                <button class="btn btn-sm btn-outline-danger" onclick="eliminarEvaluacion(${evaluacion.id})">
                    <i class="ti ti-trash"></i>
                </button>
            </td>
        `;
        tbody.appendChild(row);
    });
}

function nuevaEvaluacion() {
    document.getElementById('modalTitle').textContent = 'Nueva Evaluación';
    document.getElementById('formEvaluacion').reset();
    const modal = new bootstrap.Modal(document.getElementById('modalEvaluacion'));
    modal.show();
}

function editarEvaluacion(id) {
    document.getElementById('modalTitle').textContent = 'Editar Evaluación';
    // Aquí cargarías los datos de la evaluación
    const modal = new bootstrap.Modal(document.getElementById('modalEvaluacion'));
    modal.show();
}

function guardarEvaluacion() {
    // Aquí implementarías la lógica para guardar
    alert('Funcionalidad de guardado en desarrollo');
}

function eliminarEvaluacion(id) {
    if (confirm('¿Estás seguro de que quieres eliminar esta evaluación?')) {
        // Aquí implementarías la lógica para eliminar
        alert('Funcionalidad de eliminación en desarrollo');
    }
}

function getEstadoBadgeColor(estado) {
    switch(estado) {
        case 'Completada': return 'success';
        case 'Pendiente': return 'warning';
        case 'Cancelada': return 'danger';
        default: return 'secondary';
    }
}

function getPuntuacionBadgeColor(puntuacion) {
    if (puntuacion >= 9) return 'success';
    if (puntuacion >= 7) return 'warning';
    return 'danger';
}

function formatearFecha(fecha) {
    return new Date(fecha).toLocaleDateString('es-ES');
}
</script>
<?= $this->endSection() ?>
