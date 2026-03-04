<?php
$sidebar = 'sidebar_empleado';
?>
<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>
<div class="page-wrapper">
    <div class="page-content">
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Evaluaciones</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="<?= site_url('empleado/dashboard') ?>">Dashboard</a></li>
                        <li class="breadcrumb-item active">Evaluaciones entre Pares</li>
                    </ol>
                </nav>
            </div>
        </div>

        <!-- Tabs -->
        <ul class="nav nav-tabs mb-3" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" data-bs-toggle="tab" href="#tabPendientes">
                    <i class="ti ti-clipboard-check me-1"></i> Mis Evaluaciones a Pares
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#tabRetro">
                    <i class="ti ti-message-circle me-1"></i> Mi Retroalimentación Recibida
                </a>
            </li>
        </ul>

        <div class="tab-content">
            <!-- ========= TAB 1: Evaluaciones Pendientes ========= -->
            <div class="tab-pane fade show active" id="tabPendientes">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="ti ti-clipboard-check me-2"></i>Colegas Asignados para Evaluar</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-dark">
                                    <tr>
                                        <th>#</th>
                                        <th>Colega a Evaluar</th>
                                        <th>Departamento</th>
                                        <th>Estado</th>
                                        <th>Fecha Asignación</th>
                                        <th class="text-center">Acción</th>
                                    </tr>
                                </thead>
                                <tbody id="tbodyPendientes">
                                    <tr><td colspan="6" class="text-center py-4"><div class="spinner-border spinner-border-sm"></div> Cargando...</td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ========= TAB 2: Retroalimentación Recibida ========= -->
            <div class="tab-pane fade" id="tabRetro">
                <div class="card">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0"><i class="ti ti-message-circle me-2"></i>Retroalimentación Recibida de Mis Pares</h5>
                    </div>
                    <div class="card-body">
                        <div class="row" id="containerRetro">
                            <div class="col-12 text-center py-4"><div class="spinner-border spinner-border-sm"></div> Cargando...</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ========= MODAL: Realizar Evaluación ========= -->
<div class="modal fade" id="modalEvaluar" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="ti ti-edit me-2"></i>Evaluar a: <span id="nombreEvaluado"></span></h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formEvaluar">
                    <input type="hidden" id="eval_id" name="id">
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold"><i class="ti ti-school me-1"></i>Observación de Clase <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="observacion_clase" name="observacion_clase" rows="4" required 
                            placeholder="Describe tus observaciones sobre la metodología, interacción con estudiantes, dominio del tema, uso de recursos didácticos, etc."></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold"><i class="ti ti-book me-1"></i>Revisión de Materiales <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="revision_materiales" name="revision_materiales" rows="4" required
                            placeholder="Evalúa los materiales didácticos: calidad, pertinencia, actualización, claridad, organización, etc."></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold"><i class="ti ti-message-2 me-1"></i>Retroalimentación Constructiva <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="retroalimentacion" name="retroalimentacion" rows="4" required
                            placeholder="Proporciona sugerencias constructivas para mejorar la práctica docente. Incluye fortalezas observadas y áreas de oportunidad."></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="ti ti-x me-1"></i>Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="enviarEvaluacion()"><i class="ti ti-send me-1"></i>Enviar Evaluación</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    cargarPendientes();
    cargarRetroalimentacion();
});

// ==================== PENDIENTES ====================
function cargarPendientes() {
    fetch('<?= base_url('index.php/empleado/evaluaciones-pares/pendientes') ?>')
    .then(r => r.json())
    .then(data => {
        const tbody = document.getElementById('tbodyPendientes');
        if (data.success && data.evaluaciones.length > 0) {
            tbody.innerHTML = '';
            data.evaluaciones.forEach((e, i) => {
                const badge = e.estado === 'completada' ? 'success' : (e.estado === 'en curso' ? 'warning' : 'secondary');
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${i+1}</td>
                    <td><strong>${esc(e.evaluado_nombres)} ${esc(e.evaluado_apellidos)}</strong></td>
                    <td>${esc(e.evaluado_departamento || '—')}</td>
                    <td><span class="badge bg-${badge}">${e.estado}</span></td>
                    <td><small>${formatFecha(e.fecha_asignacion)}</small></td>
                    <td class="text-center">
                        ${e.estado === 'completada' 
                            ? '<span class="text-success"><i class="ti ti-check"></i> Completada</span>'
                            : `<button class="btn btn-sm btn-primary" onclick="abrirEvaluacion(${e.id}, '${esc(e.evaluado_nombres)} ${esc(e.evaluado_apellidos)}')">
                                <i class="ti ti-edit me-1"></i> Evaluar
                               </button>`
                        }
                    </td>
                `;
                tbody.appendChild(row);
            });
        } else {
            tbody.innerHTML = '<tr><td colspan="6" class="text-center text-muted py-4">No tienes evaluaciones asignadas</td></tr>';
        }
    })
    .catch(() => {
        document.getElementById('tbodyPendientes').innerHTML = '<tr><td colspan="6" class="text-center text-danger">Error de conexión</td></tr>';
    });
}

function abrirEvaluacion(id, nombre) {
    document.getElementById('eval_id').value = id;
    document.getElementById('nombreEvaluado').textContent = nombre;
    document.getElementById('formEvaluar').reset();
    document.getElementById('eval_id').value = id; // re-set after reset
    new bootstrap.Modal(document.getElementById('modalEvaluar')).show();
}

function enviarEvaluacion() {
    const obs = document.getElementById('observacion_clase').value.trim();
    const rev = document.getElementById('revision_materiales').value.trim();
    const ret = document.getElementById('retroalimentacion').value.trim();

    if (!obs || !rev || !ret) {
        Swal.fire({icon:'warning', title:'Campos requeridos', text:'Completa los 3 campos de evaluación'});
        return;
    }

    const fd = new FormData(document.getElementById('formEvaluar'));

    fetch('<?= base_url('index.php/empleado/evaluaciones-pares/guardar') ?>', { method:'POST', body: fd })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            bootstrap.Modal.getInstance(document.getElementById('modalEvaluar')).hide();
            Swal.fire({icon:'success', title:'¡Enviada!', text: data.message, timer:2000, showConfirmButton:false});
            cargarPendientes();
        } else {
            Swal.fire({icon:'error', title:'Error', text: data.message});
        }
    })
    .catch(() => Swal.fire({icon:'error', title:'Error', text:'Error de conexión'}));
}

// ==================== RETROALIMENTACIÓN RECIBIDA ====================
function cargarRetroalimentacion() {
    fetch('<?= base_url('index.php/empleado/evaluaciones-pares/obtener-retroalimentacion') ?>')
    .then(r => r.json())
    .then(data => {
        const container = document.getElementById('containerRetro');
        if (data.success && data.retroalimentacion.length > 0) {
            container.innerHTML = '';
            data.retroalimentacion.forEach(e => {
                const card = document.createElement('div');
                card.className = 'col-md-6 mb-3';
                card.innerHTML = `
                    <div class="card h-100 border-start border-4 border-success">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h6 class="mb-0"><i class="ti ti-user me-1 text-primary"></i>${esc(e.evaluador_nombres)} ${esc(e.evaluador_apellidos)}</h6>
                                <small class="text-muted">${formatFecha(e.fecha_evaluacion)}</small>
                            </div>
                            <hr>
                            <div class="mb-2">
                                <small class="text-muted fw-bold"><i class="ti ti-school me-1"></i>Observación de Clase:</small>
                                <p class="mb-2 bg-light p-2 rounded small">${esc(e.observacion_clase)}</p>
                            </div>
                            <div class="mb-2">
                                <small class="text-muted fw-bold"><i class="ti ti-book me-1"></i>Revisión de Materiales:</small>
                                <p class="mb-2 bg-light p-2 rounded small">${esc(e.revision_materiales)}</p>
                            </div>
                            <div>
                                <small class="text-muted fw-bold"><i class="ti ti-message-2 me-1"></i>Retroalimentación:</small>
                                <p class="mb-0 bg-light p-2 rounded small">${esc(e.retroalimentacion)}</p>
                            </div>
                        </div>
                    </div>
                `;
                container.appendChild(card);
            });
        } else {
            container.innerHTML = '<div class="col-12 text-center text-muted py-4">Aún no has recibido retroalimentación de tus pares</div>';
        }
    })
    .catch(() => {
        document.getElementById('containerRetro').innerHTML = '<div class="col-12 text-center text-danger">Error de conexión</div>';
    });
}

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
