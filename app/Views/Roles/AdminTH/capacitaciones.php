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

        <!-- Tabla de capacitaciones -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="ti ti-certificate me-2"></i>Gestión de Capacitaciones</h5>
                        <button type="button" class="btn btn-primary" onclick="nuevaCapacitacion()">
                            <i class="ti ti-plus me-1"></i> Nueva Capacitación
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover align-middle" id="tablaCapacitaciones">
                                <thead class="table-dark">
                                    <tr>
                                        <th>#</th>
                                        <th>Nombre</th>
                                        <th>Modalidad</th>
                                        <th>Duración</th>
                                        <th>Fechas</th>
                                        <th>Cupos</th>
                                        <th>Estado</th>
                                        <th class="text-center">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody id="tbodyCapacitaciones">
                                    <tr><td colspan="8" class="text-center py-4"><div class="spinner-border spinner-border-sm text-primary me-2"></div>Cargando capacitaciones...</td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ============ MODAL: CREAR / EDITAR ============ -->
<div class="modal fade" id="modalCapacitacion" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modalTitle"><i class="ti ti-plus me-2"></i>Nueva Capacitación</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formCapacitacion">
                    <input type="hidden" id="id_capacitacion" name="id_capacitacion">
                    <div class="row">
                        <div class="col-md-8 mb-3">
                            <label for="nombre" class="form-label fw-bold">Nombre <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="nombre" name="nombre" required placeholder="Nombre de la capacitación">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="duracion" class="form-label fw-bold">Duración (horas) <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="duracion" name="duracion" required min="1" placeholder="Ej: 40">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="modalidad" class="form-label fw-bold">Modalidad <span class="text-danger">*</span></label>
                            <select class="form-select" id="modalidad" name="modalidad" required>
                                <option value="">Seleccionar...</option>
                                <option value="PRESENCIAL">Presencial</option>
                                <option value="VIRTUAL">Virtual</option>
                                <option value="HIBRIDA">Híbrida</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="estado" class="form-label fw-bold">Estado</label>
                            <select class="form-select" id="estado" name="estado">
                                <option value="ACTIVA">Activa</option>
                                <option value="INACTIVA">Inactiva</option>
                                <option value="EN_CURSO">En Curso</option>
                                <option value="COMPLETADA">Completada</option>
                                <option value="CANCELADA">Cancelada</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="cupo_maximo" class="form-label fw-bold">Cupo máximo</label>
                            <input type="number" class="form-control" id="cupo_maximo" name="cupo_maximo" min="1" placeholder="Ej: 30">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="descripcion" class="form-label fw-bold">Descripción</label>
                        <textarea class="form-control" id="descripcion" name="descripcion" rows="3" placeholder="Descripción de la capacitación"></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="fecha_inicio" class="form-label fw-bold">Fecha de Inicio <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="fecha_fin" class="form-label fw-bold">Fecha de Fin</label>
                            <input type="date" class="form-control" id="fecha_fin" name="fecha_fin">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="ti ti-x me-1"></i>Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="guardarCapacitacion()"><i class="ti ti-device-floppy me-1"></i>Guardar</button>
            </div>
        </div>
    </div>
</div>

<!-- ============ MODAL: VER DETALLES ============ -->
<div class="modal fade" id="modalDetalles" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title"><i class="ti ti-eye me-2"></i>Detalles de Capacitación</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="detallesContenido">
                <div class="text-center py-4"><div class="spinner-border text-primary"></div></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<script>
// ==================== VARIABLES GLOBALES ====================
let capacitacionesCache = [];

document.addEventListener('DOMContentLoaded', function() {
    cargarCapacitaciones();
});

// ==================== CARGAR TABLA ====================
function cargarCapacitaciones() {
    const tbody = document.getElementById('tbodyCapacitaciones');
    tbody.innerHTML = '<tr><td colspan="8" class="text-center py-4"><div class="spinner-border spinner-border-sm text-primary me-2"></div>Cargando...</td></tr>';
    
    fetch('<?= base_url('index.php/admin-th/capacitaciones/obtener') ?>')
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                capacitacionesCache = data.capacitaciones;
                renderTabla(data.capacitaciones);
            } else {
                tbody.innerHTML = `<tr><td colspan="8" class="text-center text-danger py-4"><i class="ti ti-alert-circle me-2"></i>${data.message || 'Error al cargar'}</td></tr>`;
            }
        })
        .catch(err => {
            console.error('Error:', err);
            tbody.innerHTML = '<tr><td colspan="8" class="text-center text-danger py-4"><i class="ti ti-wifi-off me-2"></i>Error de conexión al servidor</td></tr>';
        });
}

function renderTabla(caps) {
    const tbody = document.getElementById('tbodyCapacitaciones');
    tbody.innerHTML = '';

    if (!caps || caps.length === 0) {
        tbody.innerHTML = '<tr><td colspan="8" class="text-center text-muted py-4">No hay capacitaciones registradas</td></tr>';
        return;
    }

    caps.forEach((c, i) => {
        const esInactiva = c.estado === 'INACTIVA';
        const fechas = formatFecha(c.fecha_inicio) + (c.fecha_fin ? ' — ' + formatFecha(c.fecha_fin) : '');
        const inscritos = c.total_inscritos || 0;
        const cupo = c.cupo_maximo || '∞';

        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${i + 1}</td>
            <td><strong>${esc(c.nombre)}</strong><br><small class="text-muted">${esc(c.descripcion || '').substring(0, 60)}</small></td>
            <td><span class="badge bg-light text-dark border">${c.modalidad || '—'}</span></td>
            <td>${c.duracion_horas || '—'} hrs</td>
            <td><small>${fechas}</small></td>
            <td>${inscritos}/${cupo}</td>
            <td>${badgeEstado(c.estado)}</td>
            <td class="text-center">
                <div class="btn-group btn-group-sm" role="group">
                    <button class="btn btn-outline-info" onclick="verDetalles(${c.id_capacitacion})" title="Ver Detalles">
                        <i class="ti ti-eye"></i>
                    </button>
                    <button class="btn btn-outline-primary" onclick="editarCapacitacion(${c.id_capacitacion})" title="Editar">
                        <i class="ti ti-pencil"></i>
                    </button>
                    <button class="btn btn-outline-warning" onclick="cambiarEstado(${c.id_capacitacion}, '${c.estado}')" title="Cambiar Estado">
                        <i class="ti ti-toggle-left"></i>
                    </button>
                    <button class="btn btn-outline-danger" onclick="eliminarCapacitacion(${c.id_capacitacion})" 
                            title="${esInactiva ? 'Eliminar' : 'Solo se puede eliminar si está INACTIVA'}" 
                            ${esInactiva ? '' : 'disabled'}>
                        <i class="ti ti-trash"></i>
                    </button>
                </div>
            </td>
        `;
        tbody.appendChild(row);
    });
}

// ==================== VER DETALLES (Modal) ====================
function verDetalles(id) {
    const modal = new bootstrap.Modal(document.getElementById('modalDetalles'));
    document.getElementById('detallesContenido').innerHTML = '<div class="text-center py-4"><div class="spinner-border text-primary"></div></div>';
    modal.show();

    fetch(`<?= base_url('index.php/admin-th/capacitaciones/obtener') ?>/${id}`)
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                const c = data.capacitacion;
                document.getElementById('detallesContenido').innerHTML = `
                    <div class="row">
                        <div class="col-md-8">
                            <h5 class="mb-3">${esc(c.nombre)}</h5>
                            <p class="text-muted">${esc(c.descripcion || 'Sin descripción')}</p>
                        </div>
                        <div class="col-md-4 text-end">
                            ${badgeEstado(c.estado)}
                        </div>
                    </div>
                    <hr>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="card bg-light border-0">
                                <div class="card-body text-center py-3">
                                    <i class="ti ti-clock fs-3 text-primary"></i>
                                    <h6 class="mt-2 mb-0">${c.duracion_horas || '—'} horas</h6>
                                    <small class="text-muted">Duración</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-light border-0">
                                <div class="card-body text-center py-3">
                                    <i class="ti ti-device-laptop fs-3 text-info"></i>
                                    <h6 class="mt-2 mb-0">${c.modalidad || '—'}</h6>
                                    <small class="text-muted">Modalidad</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-light border-0">
                                <div class="card-body text-center py-3">
                                    <i class="ti ti-users fs-3 text-success"></i>
                                    <h6 class="mt-2 mb-0">${c.total_inscritos || 0} / ${c.cupo_maximo || '∞'}</h6>
                                    <small class="text-muted">Inscritos / Cupo</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <p class="mb-1"><strong><i class="ti ti-calendar me-1"></i>Fecha Inicio:</strong> ${formatFecha(c.fecha_inicio)}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-1"><strong><i class="ti ti-calendar-off me-1"></i>Fecha Fin:</strong> ${formatFecha(c.fecha_fin)}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-1"><strong><i class="ti ti-building me-1"></i>Tipo:</strong> ${c.tipo || '—'}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-1"><strong><i class="ti ti-id me-1"></i>ID:</strong> ${c.id_capacitacion}</p>
                        </div>
                    </div>
                `;
            } else {
                document.getElementById('detallesContenido').innerHTML = `<div class="alert alert-danger">${data.message}</div>`;
            }
        })
        .catch(() => {
            document.getElementById('detallesContenido').innerHTML = '<div class="alert alert-danger">Error de conexión</div>';
        });
}

// ==================== CREAR / EDITAR ====================
function nuevaCapacitacion() {
    document.getElementById('modalTitle').innerHTML = '<i class="ti ti-plus me-2"></i>Nueva Capacitación';
    document.getElementById('formCapacitacion').reset();
    document.getElementById('id_capacitacion').value = '';
    document.getElementById('formCapacitacion').setAttribute('data-accion', 'crear');
    document.getElementById('fecha_inicio').value = new Date().toISOString().split('T')[0];
    
    new bootstrap.Modal(document.getElementById('modalCapacitacion')).show();
}

function editarCapacitacion(id) {
    fetch(`<?= base_url('index.php/admin-th/capacitaciones/obtener') ?>/${id}`)
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                const c = data.capacitacion;
                document.getElementById('modalTitle').innerHTML = '<i class="ti ti-pencil me-2"></i>Editar Capacitación';
                document.getElementById('formCapacitacion').setAttribute('data-accion', 'editar');
                document.getElementById('id_capacitacion').value = id;
                document.getElementById('nombre').value = c.nombre || '';
                document.getElementById('descripcion').value = c.descripcion || '';
                document.getElementById('duracion').value = c.duracion_horas || '';
                document.getElementById('modalidad').value = c.modalidad || '';
                document.getElementById('estado').value = c.estado || 'ACTIVA';
                document.getElementById('fecha_inicio').value = c.fecha_inicio || '';
                document.getElementById('fecha_fin').value = c.fecha_fin || '';
                document.getElementById('cupo_maximo').value = c.cupo_maximo || '';

                new bootstrap.Modal(document.getElementById('modalCapacitacion')).show();
            } else {
                Swal.fire({icon:'error', title:'Error', text: data.message || 'No se pudo obtener los datos'});
            }
        })
        .catch(() => Swal.fire({icon:'error', title:'Error', text:'Error de conexión'}));
}

function guardarCapacitacion() {
    const form = document.getElementById('formCapacitacion');
    const accion = form.getAttribute('data-accion');
    const fd = new FormData(form);
    
    if (!fd.get('nombre') || !fd.get('duracion') || !fd.get('modalidad') || !fd.get('fecha_inicio')) {
        Swal.fire({icon:'warning', title:'Campos Requeridos', text:'Complete Nombre, Duración, Modalidad y Fecha de Inicio'});
        return;
    }
    
    const url = accion === 'crear' 
        ? '<?= base_url('index.php/admin-th/capacitaciones/crear') ?>' 
        : '<?= base_url('index.php/admin-th/capacitaciones/actualizar') ?>';
    
    fetch(url, { method: 'POST', body: fd })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            bootstrap.Modal.getInstance(document.getElementById('modalCapacitacion')).hide();
            Swal.fire({icon:'success', title:'¡Éxito!', text: data.message, timer: 2000, showConfirmButton: false});
            cargarCapacitaciones();
        } else {
            Swal.fire({icon:'error', title:'Error', text: data.message || 'Error al guardar'});
        }
    })
    .catch(() => Swal.fire({icon:'error', title:'Error', text:'Error de conexión'}));
}

// ==================== CAMBIAR ESTADO (SweetAlert2) ====================
function cambiarEstado(id, estadoActual) {
    const nuevoEstado = estadoActual === 'ACTIVA' ? 'INACTIVA' : 'ACTIVA';
    const colorBtn = nuevoEstado === 'ACTIVA' ? '#198754' : '#dc3545';
    
    Swal.fire({
        title: '¿Cambiar Estado?',
        html: `El estado cambiará de <strong>${estadoActual}</strong> a <strong>${nuevoEstado}</strong>`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: colorBtn,
        cancelButtonColor: '#6c757d',
        confirmButtonText: `Sí, cambiar a ${nuevoEstado}`,
        cancelButtonText: 'Cancelar'
    }).then(result => {
        if (result.isConfirmed) {
            const fd = new FormData();
            fd.append('id_capacitacion', id);
            fd.append('estado', nuevoEstado);
            
            fetch('<?= base_url('index.php/admin-th/capacitaciones/cambiar-estado') ?>', { method: 'POST', body: fd })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({icon:'success', title:'Estado Actualizado', text: data.message, timer: 2000, showConfirmButton: false});
                    cargarCapacitaciones();
                } else {
                    Swal.fire({icon:'error', title:'Error', text: data.message});
                }
            })
            .catch(() => Swal.fire({icon:'error', title:'Error', text:'Error de conexión'}));
        }
    });
}

// ==================== ELIMINAR (solo INACTIVA) ====================
function eliminarCapacitacion(id) {
    Swal.fire({
        title: '¿Eliminar Capacitación?',
        text: 'Esta acción no se puede deshacer. Solo se eliminan capacitaciones INACTIVAS.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: '<i class="ti ti-trash me-1"></i> Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then(result => {
        if (result.isConfirmed) {
            const fd = new FormData();
            fd.append('id_capacitacion', id);
            
            fetch('<?= base_url('index.php/admin-th/capacitaciones/eliminar') ?>', { method: 'POST', body: fd })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({icon:'success', title:'Eliminada', text: data.message, timer: 2000, showConfirmButton: false});
                    cargarCapacitaciones();
                } else {
                    Swal.fire({icon:'error', title:'No se pudo eliminar', text: data.message});
                }
            })
            .catch(() => Swal.fire({icon:'error', title:'Error', text:'Error de conexión'}));
        }
    });
}

// ==================== HELPERS ====================
function badgeEstado(estado) {
    const map = {
        'ACTIVA':     '<span class="badge bg-success"><i class="ti ti-check me-1"></i>Activa</span>',
        'INACTIVA':   '<span class="badge bg-danger"><i class="ti ti-x me-1"></i>Inactiva</span>',
        'EN_CURSO':   '<span class="badge bg-warning text-dark"><i class="ti ti-player-play me-1"></i>En Curso</span>',
        'COMPLETADA': '<span class="badge bg-info"><i class="ti ti-trophy me-1"></i>Completada</span>',
        'CANCELADA':  '<span class="badge bg-secondary"><i class="ti ti-ban me-1"></i>Cancelada</span>'
    };
    return map[estado] || `<span class="badge bg-secondary">${estado}</span>`;
}

function formatFecha(f) {
    if (!f) return '—';
    const d = new Date(f + 'T00:00:00');
    return d.toLocaleDateString('es-EC', { day: '2-digit', month: 'short', year: 'numeric' });
}

function esc(str) {
    if (!str) return '';
    const el = document.createElement('span');
    el.textContent = str;
    return el.innerHTML;
}
</script>
<?= $this->endSection() ?>
