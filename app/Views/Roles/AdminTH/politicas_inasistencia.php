<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>
<div class="page-wrapper">
    <div class="page-content">
        <!-- Breadcrumb -->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Políticas de Inasistencia</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="<?= site_url('admin-th/dashboard') ?>">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Políticas de Inasistencia</li>
                    </ol>
                </nav>
            </div>
        </div>

        <!-- Page Header -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Gestión de Políticas de Inasistencia</h4>
                        <div class="card-actions">
                            <button type="button" class="btn btn-primary" onclick="nuevaPolitica()">
                                <i class="ti ti-plus"></i> Nueva Política
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped" id="tablaPoliticas">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nombre de la Política</th>
                                        <th>Tipo de Empleado</th>
                                        <th>Límite Mensual</th>
                                        <th>Límite Anual</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody id="tbodyPoliticas">
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

<!-- Modal para nueva/editar política -->
<div class="modal fade" id="modalPolitica" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Nueva Política de Inasistencia</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formPolitica">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="nombre_politica" class="form-label">Nombre de la Política *</label>
                                <input type="text" class="form-control" id="nombre_politica" name="nombre_politica" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="tipo_empleado" class="form-label">Tipo de Empleado *</label>
                                <select class="form-select" id="tipo_empleado" name="tipo_empleado" required>
                                    <option value="">Seleccionar tipo...</option>
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
                                <label for="limite_mensual" class="form-label">Límite Mensual *</label>
                                <input type="number" class="form-control" id="limite_mensual" name="limite_mensual" min="0" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="limite_anual" class="form-label">Límite Anual *</label>
                                <input type="number" class="form-control" id="limite_anual" name="limite_anual" min="0" required>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripción de la Política</label>
                        <textarea class="form-control" id="descripcion" name="descripcion" rows="3"></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="acciones_por_exceso" class="form-label">Acciones por Exceso</label>
                                <textarea class="form-control" id="acciones_por_exceso" name="acciones_por_exceso" rows="3" placeholder="Describir acciones a tomar cuando se exceda el límite"></textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="estado" class="form-label">Estado</label>
                                <select class="form-select" id="estado" name="estado">
                                    <option value="1">Activa</option>
                                    <option value="0">Inactiva</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="guardarPolitica()">Guardar</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    cargarPoliticas();
});

function cargarPoliticas() {
    // Simular carga de políticas
    const politicas = [
        { id: 1, nombre: 'Política Docentes Tiempo Completo', tipo: 'DOCENTE', limite_mensual: 2, limite_anual: 15, estado: 'Activa' },
        { id: 2, nombre: 'Política Administrativos', tipo: 'ADMINISTRATIVO', limite_mensual: 3, limite_anual: 20, estado: 'Activa' },
        { id: 3, nombre: 'Política Directivos', tipo: 'DIRECTIVO', limite_mensual: 1, limite_anual: 10, estado: 'Activa' },
        { id: 4, nombre: 'Política Auxiliares', tipo: 'AUXILIAR', limite_mensual: 4, limite_anual: 25, estado: 'Activa' }
    ];

    const tbody = document.getElementById('tbodyPoliticas');
    tbody.innerHTML = '';

    politicas.forEach(politica => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${politica.id}</td>
            <td><strong>${politica.nombre}</strong></td>
            <td><span class="badge bg-info">${politica.tipo}</span></td>
            <td><span class="badge bg-warning">${politica.limite_mensual} días</span></td>
            <td><span class="badge bg-warning">${politica.limite_anual} días</span></td>
            <td><span class="badge bg-success">${politica.estado}</span></td>
            <td>
                <button class="btn btn-sm btn-outline-primary" onclick="editarPolitica(${politica.id})">
                    <i class="ti ti-edit"></i>
                </button>
                <button class="btn btn-sm btn-outline-danger" onclick="eliminarPolitica(${politica.id})">
                    <i class="ti ti-trash"></i>
                </button>
            </td>
        `;
        tbody.appendChild(row);
    });
}

function nuevaPolitica() {
    document.getElementById('modalTitle').textContent = 'Nueva Política de Inasistencia';
    document.getElementById('formPolitica').reset();
    const modal = new bootstrap.Modal(document.getElementById('modalPolitica'));
    modal.show();
}

function editarPolitica(id) {
    document.getElementById('modalTitle').textContent = 'Editar Política de Inasistencia';
    // Aquí cargarías los datos de la política
    const modal = new bootstrap.Modal(document.getElementById('modalPolitica'));
    modal.show();
}

function guardarPolitica() {
    // Aquí implementarías la lógica para guardar
    alert('Funcionalidad de guardado en desarrollo');
}

function eliminarPolitica(id) {
    if (confirm('¿Estás seguro de que quieres eliminar esta política?')) {
        // Aquí implementarías la lógica para eliminar
        alert('Funcionalidad de eliminación en desarrollo');
    }
}
</script>
<?= $this->endSection() ?>
