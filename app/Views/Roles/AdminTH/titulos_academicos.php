<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>
<div class="page-wrapper">
    <div class="page-content">
        <!-- Breadcrumb -->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Títulos Académicos</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="<?= site_url('admin-th/dashboard') ?>">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Gestión de Títulos Académicos</li>
                    </ol>
                </nav>
            </div>
        </div>

        <!-- Page Header -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Gestión de Títulos Académicos</h4>
                        <div class="card-actions">
                            <button type="button" class="btn btn-primary" onclick="nuevoTitulo()">
                                <i class="ti ti-plus"></i> Nuevo Título
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped" id="tablaTitulos">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Empleado</th>
                                        <th>Título</th>
                                        <th>Institución</th>
                                        <th>Año</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody id="tbodyTitulos">
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

<!-- Modal para nuevo/editar título -->
<div class="modal fade" id="modalTitulo" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Nuevo Título Académico</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formTitulo">
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
                                <label for="titulo" class="form-label">Título *</label>
                                <input type="text" class="form-control" id="titulo" name="titulo" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="institucion" class="form-label">Institución *</label>
                                <input type="text" class="form-control" id="institucion" name="institucion" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="anio" class="form-label">Año *</label>
                                <input type="number" class="form-control" id="anio" name="anio" min="1950" max="2030" required>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripción</label>
                        <textarea class="form-control" id="descripcion" name="descripcion" rows="3"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="guardarTitulo()">Guardar</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    cargarTitulos();
});

function cargarTitulos() {
    // Simular carga de títulos académicos
    const titulos = [
        { id: 1, empleado: 'Juan Pérez', titulo: 'Ingeniero en Sistemas', institucion: 'Universidad Técnica', anio: 2020, estado: 'Verificado' },
        { id: 2, empleado: 'María García', titulo: 'Licenciada en Administración', institucion: 'Universidad Nacional', anio: 2019, estado: 'Verificado' },
        { id: 3, empleado: 'Carlos López', titulo: 'Magíster en Educación', institucion: 'Universidad Católica', anio: 2021, estado: 'Pendiente' }
    ];

    const tbody = document.getElementById('tbodyTitulos');
    tbody.innerHTML = '';

    titulos.forEach(titulo => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${titulo.id}</td>
            <td>${titulo.empleado}</td>
            <td>${titulo.titulo}</td>
            <td>${titulo.institucion}</td>
            <td>${titulo.anio}</td>
            <td><span class="badge bg-${getEstadoBadgeColor(titulo.estado)}">${titulo.estado}</span></td>
            <td>
                <button class="btn btn-sm btn-outline-primary" onclick="editarTitulo(${titulo.id})">
                    <i class="ti ti-edit"></i>
                </button>
                <button class="btn btn-sm btn-outline-danger" onclick="eliminarTitulo(${titulo.id})">
                    <i class="ti ti-trash"></i>
                </button>
            </td>
        `;
        tbody.appendChild(row);
    });
}

function nuevoTitulo() {
    document.getElementById('modalTitle').textContent = 'Nuevo Título Académico';
    document.getElementById('formTitulo').reset();
    const modal = new bootstrap.Modal(document.getElementById('modalTitulo'));
    modal.show();
}

function editarTitulo(id) {
    document.getElementById('modalTitle').textContent = 'Editar Título Académico';
    // Aquí cargarías los datos del título
    const modal = new bootstrap.Modal(document.getElementById('modalTitulo'));
    modal.show();
}

function guardarTitulo() {
    // Aquí implementarías la lógica para guardar
    alert('Funcionalidad de guardado en desarrollo');
}

function eliminarTitulo(id) {
    if (confirm('¿Estás seguro de que quieres eliminar este título académico?')) {
        // Aquí implementarías la lógica para eliminar
        alert('Funcionalidad de eliminación en desarrollo');
    }
}

function getEstadoBadgeColor(estado) {
    switch(estado) {
        case 'Verificado': return 'success';
        case 'Pendiente': return 'warning';
        case 'Rechazado': return 'danger';
        default: return 'secondary';
    }
}
</script>
<?= $this->endSection() ?>
