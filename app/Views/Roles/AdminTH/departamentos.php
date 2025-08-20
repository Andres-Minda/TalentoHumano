<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>
<div class="page-wrapper">
    <div class="page-content">
        <!-- Breadcrumb -->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Departamentos</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="<?= site_url('admin-th/dashboard') ?>">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Gestión de Departamentos</li>
                    </ol>
                </nav>
            </div>
        </div>

        <!-- Page Header -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Gestión de Departamentos</h4>
                        <div class="card-actions">
                            <button type="button" class="btn btn-primary" onclick="nuevoDepartamento()">
                                <i class="ti ti-plus"></i> Nuevo Departamento
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped" id="tablaDepartamentos">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nombre</th>
                                        <th>Descripción</th>
                                        <th>Jefe de Departamento</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody id="tbodyDepartamentos">
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

<!-- Modal para nuevo/editar departamento -->
<div class="modal fade" id="modalDepartamento" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Nuevo Departamento</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formDepartamento">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="nombre" class="form-label">Nombre *</label>
                                <input type="text" class="form-control" id="nombre" name="nombre" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="codigo" class="form-label">Código</label>
                                <input type="text" class="form-control" id="codigo" name="codigo">
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
                                <label for="jefe_departamento" class="form-label">Jefe de Departamento</label>
                                <input type="text" class="form-control" id="jefe_departamento" name="jefe_departamento">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="estado" class="form-label">Estado</label>
                                <select class="form-select" id="estado" name="estado">
                                    <option value="1">Activo</option>
                                    <option value="0">Inactivo</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="guardarDepartamento()">Guardar</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    cargarDepartamentos();
});

function cargarDepartamentos() {
    // Simular carga de departamentos
    const departamentos = [
        { id: 1, nombre: 'Recursos Humanos', descripcion: 'Gestión del talento humano', jefe: 'María García', estado: 'Activo' },
        { id: 2, nombre: 'Tecnología de la Información', descripcion: 'Sistemas y desarrollo', jefe: 'Juan Pérez', estado: 'Activo' },
        { id: 3, nombre: 'Finanzas', descripcion: 'Contabilidad y presupuestos', jefe: 'Carlos López', estado: 'Activo' }
    ];

    const tbody = document.getElementById('tbodyDepartamentos');
    tbody.innerHTML = '';

    departamentos.forEach(departamento => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${departamento.id}</td>
            <td>${departamento.nombre}</td>
            <td>${departamento.descripcion}</td>
            <td>${departamento.jefe}</td>
            <td><span class="badge bg-success">${departamento.estado}</span></td>
            <td>
                <button class="btn btn-sm btn-outline-primary" onclick="editarDepartamento(${departamento.id})">
                    <i class="ti ti-edit"></i>
                </button>
                <button class="btn btn-sm btn-outline-danger" onclick="eliminarDepartamento(${departamento.id})">
                    <i class="ti ti-trash"></i>
                </button>
            </td>
        `;
        tbody.appendChild(row);
    });
}

function nuevoDepartamento() {
    document.getElementById('modalTitle').textContent = 'Nuevo Departamento';
    document.getElementById('formDepartamento').reset();
    const modal = new bootstrap.Modal(document.getElementById('modalDepartamento'));
    modal.show();
}

function editarDepartamento(id) {
    document.getElementById('modalTitle').textContent = 'Editar Departamento';
    // Aquí cargarías los datos del departamento
    const modal = new bootstrap.Modal(document.getElementById('modalDepartamento'));
    modal.show();
}

function guardarDepartamento() {
    // Aquí implementarías la lógica para guardar
    alert('Funcionalidad de guardado en desarrollo');
}

function eliminarDepartamento(id) {
    if (confirm('¿Estás seguro de que quieres eliminar este departamento?')) {
        // Aquí implementarías la lógica para eliminar
        alert('Funcionalidad de eliminación en desarrollo');
    }
}
</script>
<?= $this->endSection() ?>
