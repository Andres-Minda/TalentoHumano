<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>
<div class="page-wrapper">
    <div class="page-content">
        <!-- Breadcrumb -->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Puestos</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="<?= site_url('admin-th/dashboard') ?>">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Gestión de Puestos</li>
                    </ol>
                </nav>
            </div>
        </div>

        <!-- Page Header -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Gestión de Puestos de Trabajo</h4>
                        <div class="card-actions">
                            <button type="button" class="btn btn-primary" onclick="nuevoPuesto()">
                                <i class="ti ti-plus"></i> Nuevo Puesto
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped" id="tablaPuestos">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nombre del Puesto</th>
                                        <th>Departamento</th>
                                        <th>Descripción</th>
                                        <th>Salario Base</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody id="tbodyPuestos">
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

<!-- Modal para nuevo/editar puesto -->
<div class="modal fade" id="modalPuesto" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Nuevo Puesto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formPuesto">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="nombre_puesto" class="form-label">Nombre del Puesto *</label>
                                <input type="text" class="form-control" id="nombre_puesto" name="nombre_puesto" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="departamento" class="form-label">Departamento *</label>
                                <select class="form-select" id="departamento" name="departamento" required>
                                    <option value="">Seleccionar departamento...</option>
                                    <option value="Recursos Humanos">Recursos Humanos</option>
                                    <option value="Tecnología de la Información">Tecnología de la Información</option>
                                    <option value="Finanzas">Finanzas</option>
                                    <option value="Académico">Académico</option>
                                    <option value="Administrativo">Administrativo</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="salario_base" class="form-label">Salario Base</label>
                                <input type="number" class="form-control" id="salario_base" name="salario_base" min="0" step="0.01">
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
                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripción del Puesto</label>
                        <textarea class="form-control" id="descripcion" name="descripcion" rows="4"></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="requisitos" class="form-label">Requisitos Mínimos</label>
                                <textarea class="form-control" id="requisitos" name="requisitos" rows="3"></textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="responsabilidades" class="form-label">Responsabilidades</label>
                                <textarea class="form-control" id="responsabilidades" name="responsabilidades" rows="3"></textarea>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="guardarPuesto()">Guardar</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    cargarPuestos();
});

function cargarPuestos() {
    // Simular carga de puestos
    const puestos = [
        { id: 1, nombre: 'Docente Tiempo Completo', departamento: 'Académico', descripcion: 'Impartir clases y realizar investigación', salario: '$2500.00', estado: 'Activo' },
        { id: 2, nombre: 'Analista de Sistemas', departamento: 'Tecnología de la Información', descripcion: 'Desarrollo y mantenimiento de sistemas', salario: '$3000.00', estado: 'Activo' },
        { id: 3, nombre: 'Contador Senior', departamento: 'Finanzas', descripcion: 'Contabilidad y reportes financieros', salario: '$2800.00', estado: 'Activo' },
        { id: 4, nombre: 'Coordinador Académico', departamento: 'Académico', descripcion: 'Coordinación de programas académicos', salario: '$3200.00', estado: 'Activo' }
    ];

    const tbody = document.getElementById('tbodyPuestos');
    tbody.innerHTML = '';

    puestos.forEach(puesto => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${puesto.id}</td>
            <td><strong>${puesto.nombre}</strong></td>
            <td><span class="badge bg-info">${puesto.departamento}</span></td>
            <td>${puesto.descripcion}</td>
            <td><span class="badge bg-success">${puesto.salario}</span></td>
            <td><span class="badge bg-success">${puesto.estado}</span></td>
            <td>
                <button class="btn btn-sm btn-outline-primary" onclick="editarPuesto(${puesto.id})">
                    <i class="ti ti-edit"></i>
                </button>
                <button class="btn btn-sm btn-outline-danger" onclick="eliminarPuesto(${puesto.id})">
                    <i class="ti ti-trash"></i>
                </button>
            </td>
        `;
        tbody.appendChild(row);
    });
}

function nuevoPuesto() {
    document.getElementById('modalTitle').textContent = 'Nuevo Puesto';
    document.getElementById('formPuesto').reset();
    const modal = new bootstrap.Modal(document.getElementById('modalPuesto'));
    modal.show();
}

function editarPuesto(id) {
    document.getElementById('modalTitle').textContent = 'Editar Puesto';
    // Aquí cargarías los datos del puesto
    const modal = new bootstrap.Modal(document.getElementById('modalPuesto'));
    modal.show();
}

function guardarPuesto() {
    // Aquí implementarías la lógica para guardar
    alert('Funcionalidad de guardado en desarrollo');
}

function eliminarPuesto(id) {
    if (confirm('¿Estás seguro de que quieres eliminar este puesto?')) {
        // Aquí implementarías la lógica para eliminar
        alert('Funcionalidad de eliminación en desarrollo');
    }
}
</script>
<?= $this->endSection() ?>
