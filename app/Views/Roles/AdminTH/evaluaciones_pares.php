<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>
<div class="page-wrapper">
    <div class="page-content">
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Evaluaciones</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="<?= site_url('admin-th/dashboard') ?>">Dashboard</a></li>
                        <li class="breadcrumb-item active">Evaluaciones entre Pares</li>
                    </ol>
                </nav>
            </div>
        </div>

        <!-- Formulario de Asignación -->
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="ti ti-clipboard-plus me-2"></i>Nueva Evaluación entre Pares</h5>
                    </div>
                    <div class="card-body">
                        <form id="formAsignar">
                            <div class="row">
                                <!-- Paso 1: Seleccionar Evaluado -->
                                <div class="col-md-5 mb-3">
                                    <label class="form-label fw-bold">
                                        <span class="badge bg-danger me-1">1</span> Profesor a Evaluar (Evaluado) <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select" id="evaluado_id" name="evaluado_id" required onchange="actualizarEvaluadores()">
                                        <option value="">Cargando docentes...</option>
                                    </select>
                                </div>

                                <!-- Flecha -->
                                <div class="col-md-2 mb-3 d-flex align-items-end justify-content-center pb-2">
                                    <div class="text-center">
                                        <i class="ti ti-arrow-left fs-2 text-primary"></i>
                                        <br><small class="text-muted">será evaluado por</small>
                                    </div>
                                </div>

                                <!-- Paso 2: Seleccionar Evaluador -->
                                <div class="col-md-5 mb-3">
                                    <label class="form-label fw-bold">
                                        <span class="badge bg-success me-1">2</span> Profesor Evaluador <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select" id="evaluador_id" name="evaluador_id" required disabled>
                                        <option value="">Primero seleccione al evaluado...</option>
                                    </select>
                                </div>
                            </div>

                            <div class="text-end">
                                <button type="button" class="btn btn-primary px-4" onclick="asignarEvaluacion()" id="btnAsignar" disabled>
                                    <i class="ti ti-send me-1"></i> Asignar Evaluación
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabla de evaluaciones asignadas -->
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="ti ti-list-check me-2"></i>Evaluaciones Asignadas</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover align-middle">
                                <thead class="table-dark">
                                    <tr>
                                        <th>#</th>
                                        <th>Evaluado (Profesor)</th>
                                        <th></th>
                                        <th>Evaluador (Par)</th>
                                        <th>Estado</th>
                                        <th>Fecha Asignación</th>
                                        <th>Fecha Evaluación</th>
                                        <th class="text-center">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody id="tbodyEvaluaciones">
                                    <tr><td colspan="8" class="text-center py-4"><div class="spinner-border spinner-border-sm text-primary me-2"></div>Cargando evaluaciones...</td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let docentesData = [];

document.addEventListener('DOMContentLoaded', function() {
    cargarDocentes();
    cargarEvaluaciones();
});

// ==================== CARGAR DOCENTES ====================
function cargarDocentes() {
    fetch('<?= base_url('index.php/admin-th/evaluaciones-pares/docentes') ?>')
    .then(r => r.json())
    .then(data => {
        if (data.success && data.docentes) {
            docentesData = data.docentes;
            const placeholder = '<option value="">-- Seleccionar profesor --</option>';
            const opts = docentesData.map(d =>
                `<option value="${d.id_empleado}">${d.apellidos} ${d.nombres} ${d.departamento ? '(' + d.departamento + ')' : ''}</option>`
            ).join('');
            document.getElementById('evaluado_id').innerHTML = placeholder + opts;
        } else {
            document.getElementById('evaluado_id').innerHTML = '<option value="">No se encontraron docentes</option>';
        }
    })
    .catch(err => {
        console.error('Error cargando docentes:', err);
        document.getElementById('evaluado_id').innerHTML = '<option value="">Error al cargar docentes</option>';
    });
}

// Cuando se selecciona el evaluado, llenar el evaluador excluyendo al seleccionado
function actualizarEvaluadores() {
    const evaluadoId = document.getElementById('evaluado_id').value;
    const selEvaluador = document.getElementById('evaluador_id');
    const btnAsignar = document.getElementById('btnAsignar');

    if (!evaluadoId) {
        selEvaluador.innerHTML = '<option value="">Primero seleccione al evaluado...</option>';
        selEvaluador.disabled = true;
        btnAsignar.disabled = true;
        return;
    }

    // Filtrar: excluir al evaluado de la lista de evaluadores
    const evaluadores = docentesData.filter(d => d.id_empleado != evaluadoId);

    if (evaluadores.length === 0) {
        selEvaluador.innerHTML = '<option value="">No hay otros docentes disponibles</option>';
        selEvaluador.disabled = true;
        btnAsignar.disabled = true;
        return;
    }

    const placeholder = '<option value="">-- Seleccionar evaluador --</option>';
    const opts = evaluadores.map(d =>
        `<option value="${d.id_empleado}">${d.apellidos} ${d.nombres} ${d.departamento ? '(' + d.departamento + ')' : ''}</option>`
    ).join('');

    selEvaluador.innerHTML = placeholder + opts;
    selEvaluador.disabled = false;

    // Habilitar botón cuando evaluador se seleccione
    selEvaluador.onchange = function() {
        btnAsignar.disabled = !this.value;
    };
}

// ==================== CARGAR EVALUACIONES ====================
function cargarEvaluaciones() {
    const tbody = document.getElementById('tbodyEvaluaciones');
    tbody.innerHTML = '<tr><td colspan="8" class="text-center py-4"><div class="spinner-border spinner-border-sm text-primary me-2"></div>Cargando...</td></tr>';

    fetch('<?= base_url('index.php/admin-th/evaluaciones-pares/obtener') ?>')
    .then(r => {
        if (!r.ok) throw new Error('HTTP ' + r.status);
        return r.json();
    })
    .then(data => {
        if (data.success) {
            renderTabla(data.evaluaciones);
        } else {
            tbody.innerHTML = `<tr><td colspan="8" class="text-center text-danger py-3"><i class="ti ti-alert-circle me-1"></i>${data.message || 'Error al cargar'}</td></tr>`;
        }
    })
    .catch(err => {
        console.error('Error:', err);
        tbody.innerHTML = `<tr><td colspan="8" class="text-center text-danger py-3"><i class="ti ti-wifi-off me-1"></i>Error de conexión: ${err.message}</td></tr>`;
    });
}

function renderTabla(evals) {
    const tbody = document.getElementById('tbodyEvaluaciones');
    tbody.innerHTML = '';

    if (!evals || evals.length === 0) {
        tbody.innerHTML = '<tr><td colspan="8" class="text-center text-muted py-4"><i class="ti ti-inbox me-1"></i>No hay evaluaciones asignadas aún</td></tr>';
        return;
    }

    evals.forEach((e, i) => {
        const badges = {
            'pendiente': '<span class="badge bg-secondary"><i class="ti ti-clock me-1"></i>Pendiente</span>',
            'en curso': '<span class="badge bg-warning text-dark"><i class="ti ti-player-play me-1"></i>En Curso</span>',
            'completada': '<span class="badge bg-success"><i class="ti ti-check me-1"></i>Completada</span>'
        };
        const esPendiente = e.estado === 'pendiente';
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${i + 1}</td>
            <td><strong>${esc(e.evaluado_apellidos)} ${esc(e.evaluado_nombres)}</strong></td>
            <td class="text-center"><i class="ti ti-arrow-left text-primary"></i></td>
            <td>${esc(e.evaluador_apellidos)} ${esc(e.evaluador_nombres)}</td>
            <td>${badges[e.estado] || e.estado}</td>
            <td><small>${formatFecha(e.fecha_asignacion)}</small></td>
            <td><small>${e.fecha_evaluacion ? formatFecha(e.fecha_evaluacion) : '—'}</small></td>
            <td class="text-center">
                <div class="btn-group btn-group-sm">
                    ${e.estado === 'completada' ? `
                        <button class="btn btn-outline-info" onclick="verResultado('${esc(e.evaluador_nombres)} ${esc(e.evaluador_apellidos)}', '${esc(e.evaluado_nombres)} ${esc(e.evaluado_apellidos)}', \`${esc(e.observacion_clase)}\`, \`${esc(e.revision_materiales)}\`, \`${esc(e.retroalimentacion)}\`)" title="Ver Resultado">
                            <i class="ti ti-eye"></i>
                        </button>
                    ` : ''}
                    <button class="btn btn-outline-danger" onclick="eliminarEvaluacion(${e.id})" 
                            title="${esPendiente ? 'Eliminar' : 'Solo se eliminan pendientes'}" ${esPendiente ? '' : 'disabled'}>
                        <i class="ti ti-trash"></i>
                    </button>
                </div>
            </td>
        `;
        tbody.appendChild(row);
    });
}

// ==================== ASIGNAR ====================
function asignarEvaluacion() {
    const evaluadoId = document.getElementById('evaluado_id').value;
    const evaluadorId = document.getElementById('evaluador_id').value;

    if (!evaluadoId || !evaluadorId) {
        Swal.fire({icon:'warning', title:'Campos requeridos', text:'Seleccione al profesor a evaluar y al evaluador'});
        return;
    }

    if (evaluadoId === evaluadorId) {
        Swal.fire({icon:'error', title:'Error', text:'Un profesor no puede evaluarse a sí mismo'});
        return;
    }

    const fd = new FormData();
    fd.append('evaluador_id', evaluadorId);
    fd.append('evaluado_id', evaluadoId);

    fetch('<?= base_url('index.php/admin-th/evaluaciones-pares/asignar') ?>', { method:'POST', body: fd })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            Swal.fire({icon:'success', title:'¡Asignada!', text: data.message, timer:2000, showConfirmButton:false});
            document.getElementById('formAsignar').reset();
            document.getElementById('evaluador_id').innerHTML = '<option value="">Primero seleccione al evaluado...</option>';
            document.getElementById('evaluador_id').disabled = true;
            document.getElementById('btnAsignar').disabled = true;
            cargarEvaluaciones();
        } else {
            Swal.fire({icon:'error', title:'Error', text: data.message});
        }
    })
    .catch(() => Swal.fire({icon:'error', title:'Error', text:'Error de conexión'}));
}

// ==================== ELIMINAR ====================
function eliminarEvaluacion(id) {
    Swal.fire({
        title: '¿Eliminar asignación?',
        text: 'Solo se pueden eliminar evaluaciones pendientes.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then(r => {
        if (r.isConfirmed) {
            const fd = new FormData();
            fd.append('id', id);
            fetch('<?= base_url('index.php/admin-th/evaluaciones-pares/eliminar') ?>', { method:'POST', body: fd })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({icon:'success', title:'Eliminada', text: data.message, timer:1500, showConfirmButton:false});
                    cargarEvaluaciones();
                } else {
                    Swal.fire({icon:'error', title:'Error', text: data.message});
                }
            });
        }
    });
}

// ==================== VER RESULTADO ====================
function verResultado(evaluador, evaluado, obs, rev, retro) {
    Swal.fire({
        title: 'Resultado de Evaluación',
        html: `
            <div class="text-start">
                <div class="row mb-3">
                    <div class="col-6"><small class="text-muted">Evaluado:</small><br><strong>${evaluado}</strong></div>
                    <div class="col-6"><small class="text-muted">Evaluador:</small><br><strong>${evaluador}</strong></div>
                </div>
                <hr>
                <p class="fw-bold mb-1"><i class="ti ti-school me-1"></i>Observación de Clase:</p>
                <p class="bg-light p-2 rounded small">${obs || 'N/A'}</p>
                <p class="fw-bold mb-1"><i class="ti ti-book me-1"></i>Revisión de Materiales:</p>
                <p class="bg-light p-2 rounded small">${rev || 'N/A'}</p>
                <p class="fw-bold mb-1"><i class="ti ti-message-2 me-1"></i>Retroalimentación:</p>
                <p class="bg-light p-2 rounded small">${retro || 'N/A'}</p>
            </div>
        `,
        width: '600px',
        confirmButtonText: 'Cerrar'
    });
}

// ==================== HELPERS ====================
function formatFecha(f) {
    if (!f) return '—';
    return new Date(f).toLocaleDateString('es-EC', {day:'2-digit', month:'short', year:'numeric'});
}
function esc(s) {
    if (!s) return '';
    const el = document.createElement('span');
    el.textContent = s;
    return el.innerHTML;
}
</script>
<?= $this->endSection() ?>
