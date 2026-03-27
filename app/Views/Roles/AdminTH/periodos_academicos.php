<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>
<div class="page-wrapper">
    <div class="page-content">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center justify-content-between">
                            <h4 class="card-title mb-0">Gestión de Periodos Académicos</h4>
                            <button type="button" class="btn btn-primary" onclick="nuevoPeriodo()">
                                <i class="ti ti-plus"></i> Nuevo Periodo
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover" id="tablaPeriodos">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nombre del Periodo</th>
                                        <th>Fecha Inicio</th>
                                        <th>Fecha Fin</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Puesto / Periodo -->
<div class="modal fade" id="modalPeriodo" tabindex="-1" aria-labelledby="modalPeriodoLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalPeriodoLabel">Nuevo Periodo Académico</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formPeriodo">
                <div class="modal-body">
                    <input type="hidden" id="id_periodo" name="id_periodo">
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre del Periodo * (Ej: Noviembre 2025 - Abril 2026)</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="fecha_inicio" class="form-label">Fecha de Inicio *</label>
                            <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="fecha_fin" class="form-label">Fecha de Fin *</label>
                            <input type="date" class="form-control" id="fecha_fin" name="fecha_fin" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="estado" class="form-label">Estado Inicial *</label>
                        <select class="form-select" id="estado" name="estado" required>
                            <option value="Inactivo">Inactivo (Planificado)</option>
                            <option value="Activo">Activo (Periodo Actual)</option>
                            <option value="Cerrado">Cerrado (Periodo Finalizado)</option>
                        </select>
                        <small class="text-muted">Si selecciona "Activo", el periodo que actualmente es activo cambiará a Inactivo Automáticamente.</small>
                    </div>
                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripción</label>
                        <textarea class="form-control" id="descripcion" name="descripcion" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar Periodo</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
let periodosData = [];

document.addEventListener('DOMContentLoaded', function() {
    cargarPeriodos();
});

function cargarPeriodos() {
    fetch('<?= base_url('admin-th/periodos-academicos/obtener') ?>')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                periodosData = data.data;
                renderizarTabla();
            } else {
                Swal.fire('Error', 'Error al cargar periodos: ' + data.message, 'error');
            }
        })
        .catch(error => {
            console.error(error);
            Swal.fire('Error', 'Error de red al cargar periodos', 'error');
        });
}

function renderizarTabla() {
    const tbody = document.querySelector('#tablaPeriodos tbody');
    tbody.innerHTML = '';

    periodosData.forEach(p => {
        let badgeClass = 'bg-secondary';
        if (p.estado === 'Activo') badgeClass = 'bg-success';
        if (p.estado === 'Cerrado') badgeClass = 'bg-danger';

        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${p.id_periodo}</td>
            <td><strong>${p.nombre}</strong></td>
            <td>${p.fecha_inicio}</td>
            <td>${p.fecha_fin}</td>
            <td><span class="badge ${badgeClass}">${p.estado}</span></td>
            <td>
                <div class="btn-group">
                    ${(p.estado === 'Activo' || p.estado === 'Inactivo') ? `
                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="editarPeriodo(${p.id_periodo})" title="Editar"><i class="ti ti-edit"></i></button>
                        <button type="button" class="btn btn-sm btn-outline-warning" onclick="cambiarEstadoPrompt(${p.id_periodo}, '${p.estado}')" title="Cambiar Estado"><i class="ti ti-refresh"></i></button>
                    ` : `
                        <button type="button" class="btn btn-sm btn-outline-secondary disabled" title="Modo Histórico: Solo Lectura"><i class="ti ti-lock"></i></button>
                    `}
                    ${p.estado !== 'Activo' ? `<button type="button" class="btn btn-sm btn-outline-danger" onclick="eliminarPeriodo(${p.id_periodo})" title="Eliminar"><i class="ti ti-trash"></i></button>` : ''}
                </div>
            </td>
        `;
        tbody.appendChild(row);
    });
}

function nuevoPeriodo() {
    document.getElementById('modalPeriodoLabel').textContent = 'Nuevo Periodo Académico';
    document.getElementById('formPeriodo').reset();
    document.getElementById('id_periodo').value = '';
    new bootstrap.Modal(document.getElementById('modalPeriodo')).show();
}

function editarPeriodo(id) {
    const p = periodosData.find(x => x.id_periodo == id);
    if (!p) return;

    document.getElementById('modalPeriodoLabel').textContent = 'Editar Periodo Académico';
    document.getElementById('id_periodo').value = p.id_periodo;
    document.getElementById('nombre').value = p.nombre;
    document.getElementById('fecha_inicio').value = p.fecha_inicio;
    document.getElementById('fecha_fin').value = p.fecha_fin;
    document.getElementById('estado').value = p.estado;
    document.getElementById('descripcion').value = p.descripcion;

    new bootstrap.Modal(document.getElementById('modalPeriodo')).show();
}

document.getElementById('formPeriodo').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);

    fetch('<?= base_url('admin-th/periodos-academicos/guardar') ?>', {
        method: 'POST',
        body: formData
    })
    .then(r => r.json())
    .then(res => {
        if(res.success) {
            Swal.fire('Éxito', res.message, 'success');
            bootstrap.Modal.getInstance(document.getElementById('modalPeriodo')).hide();
            cargarPeriodos();
        } else {
            Swal.fire('Error', res.message, 'error');
        }
    });
});

function eliminarPeriodo(id) {
    Swal.fire({
        title: '¿Eliminar Periodo?',
        text: 'Esta acción no se puede deshacer',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then(result => {
        if(result.isConfirmed) {
            const fd = new FormData();
            fd.append('id_periodo', id);
            fetch('<?= base_url('admin-th/periodos-academicos/eliminar') ?>', {
                method: 'POST',
                body: fd
            }).then(r => r.json()).then(res => {
                if(res.success) {
                    Swal.fire('Eliminado', res.message, 'success');
                    cargarPeriodos();
                } else {
                    Swal.fire('Error', res.message, 'error');
                }
            });
        }
    });
}

function cambiarEstadoPrompt(id, estadoActual) {
    Swal.fire({
        title: 'Cambiar Estado',
        input: 'select',
        inputOptions: {
            'Inactivo': 'Inactivo',
            'Activo': 'Activo',
            'Cerrado': 'Cerrado'
        },
        inputValue: estadoActual,
        showCancelButton: true,
        confirmButtonText: 'Guardar',
        cancelButtonText: 'Cancelar'
    }).then(result => {
        if(result.isConfirmed) {
            const nuevoEstado = result.value;
            if(nuevoEstado === estadoActual) return;
            
            const fd = new FormData();
            fd.append('id_periodo', id);
            fd.append('estado', nuevoEstado);
            
            fetch('<?= base_url('admin-th/periodos-academicos/cambiar-estado') ?>', {
                method: 'POST',
                body: fd
            }).then(r => r.json()).then(res => {
                if(res.success) {
                    Swal.fire('Actualizado', res.message, 'success');
                    cargarPeriodos();
                } else {
                    Swal.fire('Error', res.message, 'error');
                }
            });
        }
    });
}
</script>
<?= $this->endSection() ?>
