<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>
<div class="page-wrapper">
    <div class="page-content">
        <!-- Breadcrumb -->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Empleados</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="<?= site_url('admin-th/dashboard') ?>">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Gestión de Empleados</li>
                    </ol>
                </nav>
            </div>
        </div>

        <!-- Page Header -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Gestión de Empleados</h4>
                        <div class="card-actions">
                            <button type="button" class="btn btn-primary" onclick="nuevoEmpleado()">
                                <i class="ti ti-plus"></i> Nuevo Empleado
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped" id="tablaEmpleados">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nombres</th>
                                        <th>Apellidos</th>
                                        <th>Cédula</th>
                                        <th>Tipo</th>
                                        <th>Departamento</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody id="tbodyEmpleados">
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

<!-- Modal para nuevo/editar empleado -->
<div class="modal fade" id="modalEmpleado" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Nuevo Empleado</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formEmpleado">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="nombres" class="form-label">Nombres *</label>
                                <input type="text" class="form-control" id="nombres" name="nombres" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="apellidos" class="form-label">Apellidos *</label>
                                <input type="text" class="form-control" id="apellidos" name="apellidos" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="cedula" class="form-label">Cédula *</label>
                                <input type="text" class="form-control" id="cedula" name="cedula" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="tipo_empleado" class="form-label">Tipo de Empleado *</label>
                                <select class="form-select" id="tipo_empleado" name="tipo_empleado" required>
                                    <option value="">Seleccionar...</option>
                                    <option value="DOCENTE">Docente</option>
                                    <option value="ADMINISTRATIVO">Administrativo</option>
                                    <option value="DIRECTIVO">Directivo</option>
                                    <option value="AUXILIAR">Auxiliar</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="departamento" class="form-label">Departamento *</label>
                                <input type="text" class="form-control" id="departamento" name="departamento" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="fecha_ingreso" class="form-label">Fecha de Ingreso *</label>
                                <input type="date" class="form-control" id="fecha_ingreso" name="fecha_ingreso" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="salario" class="form-label">Salario</label>
                                <input type="number" class="form-control" id="salario" name="salario" step="0.01">
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
                <button type="button" class="btn btn-primary" onclick="guardarEmpleado()">Guardar</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    cargarEmpleados();
});

function cargarEmpleados() {
    // Simular carga de empleados
    const empleados = [
        { id: 1, nombres: 'Juan', apellidos: 'Pérez', cedula: '1111111111', tipo: 'DOCENTE', departamento: 'Matemáticas', estado: 'Activo' },
        { id: 2, nombres: 'María', apellidos: 'García', cedula: '2222222222', tipo: 'ADMINISTRATIVO', departamento: 'Administración', estado: 'Activo' },
        { id: 3, nombres: 'Carlos', apellidos: 'López', cedula: '3333333333', tipo: 'DOCENTE', departamento: 'Física', estado: 'Activo' }
    ];

    const tbody = document.getElementById('tbodyEmpleados');
    tbody.innerHTML = '';

    empleados.forEach(empleado => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${empleado.id}</td>
            <td>${empleado.nombres}</td>
            <td>${empleado.apellidos}</td>
            <td>${empleado.cedula}</td>
            <td><span class="badge bg-primary">${empleado.tipo}</span></td>
            <td>${empleado.departamento}</td>
            <td><span class="badge bg-success">${empleado.estado}</span></td>
            <td>
                <button class="btn btn-sm btn-outline-primary" onclick="editarEmpleado(${empleado.id})">
                    <i class="ti ti-edit"></i>
                </button>
                <button class="btn btn-sm btn-outline-danger" onclick="eliminarEmpleado(${empleado.id})">
                    <i class="ti ti-trash"></i>
                </button>
            </td>
        `;
        tbody.appendChild(row);
    });
}

function nuevoEmpleado() {
    document.getElementById('modalTitle').textContent = 'Nuevo Empleado';
    document.getElementById('formEmpleado').reset();
    const modal = new bootstrap.Modal(document.getElementById('modalEmpleado'));
    modal.show();
}

function editarEmpleado(id) {
    document.getElementById('modalTitle').textContent = 'Editar Empleado';
    // Aquí cargarías los datos del empleado
    const modal = new bootstrap.Modal(document.getElementById('modalEmpleado'));
    modal.show();
}

function guardarEmpleado() {
    // Aquí implementarías la lógica para guardar
    alert('Funcionalidad de guardado en desarrollo');
}

function eliminarEmpleado(id) {
    if (confirm('¿Estás seguro de que quieres eliminar este empleado?')) {
        // Aquí implementarías la lógica para eliminar
        alert('Funcionalidad de eliminación en desarrollo');
    }
}
</script>
<?= $this->endSection() ?>
