<?php
$sidebar = 'sidebar_empleado';
?>

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
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
                        <li class="breadcrumb-item active">Mis Evaluaciones</li>
                    </ol>
                </nav>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="row">
            <div class="col-xl-4 col-md-4">
                <div class="card radius-10 bg-primary">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <i class="ti ti-clipboard-list fs-1 text-white"></i>
                            <div class="ms-3">
                                <h4 class="mb-0 text-white" id="stat_total"><?= isset($total) ? $total : 0 ?></h4>
                                <p class="mb-0 text-white-50">Total Evaluaciones</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-md-4">
                <div class="card radius-10 bg-warning">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <i class="ti ti-clock fs-1 text-white"></i>
                            <div class="ms-3">
                                <h4 class="mb-0 text-white" id="stat_pendientes"><?= isset($total_pendientes) ? $total_pendientes : 0 ?></h4>
                                <p class="mb-0 text-white-50">Pendientes</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-md-4">
                <div class="card radius-10 bg-success">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <i class="ti ti-check fs-1 text-white"></i>
                            <div class="ms-3">
                                <h4 class="mb-0 text-white" id="stat_completadas"><?= isset($total_completadas) ? $total_completadas : 0 ?></h4>
                                <p class="mb-0 text-white-50">Completadas</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Evaluaciones Pendientes -->
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-warning text-dark">
                        <h5 class="mb-0"><i class="ti ti-alert-circle me-2"></i>Evaluaciones Pendientes por Realizar</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Empleado a Evaluar</th>
                                        <th>Tipo</th>
                                        <th>Fecha</th>
                                        <th>Estado</th>
                                        <th class="text-center">Acción</th>
                                    </tr>
                                </thead>
                                <tbody id="tbodyPendientes">
                                    <?php if(empty($pendientes)): ?>
                                        <tr><td colspan="6" class="text-center py-3 text-muted"><i class="ti ti-check me-1"></i>Aún no tienes evaluaciones asignadas</td></tr>
                                    <?php else: ?>
                                        <?php foreach($pendientes as $i => $e): ?>
                                            <?php 
                                            $esAuto = ($e['tipo_evaluacion'] === 'Autoevaluación') || ($e['id_empleado'] == $e['id_evaluador']); 
                                            $nombreEv = esc(($e['nombres_evaluado'] ?? '') . ' ' . ($e['apellidos_evaluado'] ?? ''));
                                            ?>
                                            <tr>
                                                <td><?= $i+1 ?></td>
                                                <td><strong><?= $esAuto ? 'Yo mismo (Autoevaluación)' : $nombreEv ?></strong></td>
                                                <td><span class="badge bg-<?= $esAuto ? 'secondary' : 'info' ?>"><?= esc($e['tipo_evaluacion'] ?? '—') ?></span></td>
                                                <td><small><?= !empty($e['fecha_evaluacion']) ? date('d/m/Y', strtotime($e['fecha_evaluacion'])) : '—' ?></small></td>
                                                <td><span class="badge bg-warning text-dark"><i class="ti ti-clock me-1"></i>Pendiente</span></td>
                                                <td class="text-center">
                                                    <?php if ($esAuto): ?>
                                                        <button class="btn btn-sm btn-gradient" style="background:linear-gradient(135deg,#667eea,#764ba2);color:#fff;border:none;" onclick="abrirAutoevaluacion(<?= $e['id'] ?>)">
                                                            <i class="ti ti-user-check me-1"></i>Autoevaluarme
                                                        </button>
                                                    <?php else: ?>
                                                        <button class="btn btn-sm btn-primary" onclick="abrirRubrica(<?= $e['id'] ?>, '<?= addslashes($nombreEv) ?>')">
                                                            <i class="ti ti-clipboard-check me-1"></i>Evaluar
                                                        </button>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Evaluaciones Completadas -->
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0"><i class="ti ti-check me-2"></i>Evaluaciones Completadas</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Empleado Evaluado</th>
                                        <th>Tipo</th>
                                        <th>Fecha</th>
                                        <th>Puntaje Total</th>
                                        <th class="text-center">Detalles</th>
                                    </tr>
                                </thead>
                                <tbody id="tbodyCompletadas">
                                    <?php if(empty($completadas)): ?>
                                        <tr><td colspan="6" class="text-center py-3 text-muted"><i class="ti ti-inbox me-1"></i>No hay evaluaciones completadas</td></tr>
                                    <?php else: ?>
                                        <?php foreach($completadas as $i => $e): ?>
                                            <?php 
                                            $puntaje = $e['puntaje_total'] ? number_format((float)$e['puntaje_total'], 0) : '0';
                                            $pColor = $puntaje >= 20 ? 'success' : ($puntaje >= 15 ? 'primary' : ($puntaje >= 10 ? 'warning' : 'danger'));
                                            ?>
                                            <tr>
                                                <td><?= $i+1 ?></td>
                                                <td><strong><?= esc(($e['nombres_evaluado'] ?? '') . ' ' . ($e['apellidos_evaluado'] ?? '')) ?></strong></td>
                                                <td><span class="badge bg-info"><?= esc($e['tipo_evaluacion'] ?? '—') ?></span></td>
                                                <td><small><?= !empty($e['fecha_evaluacion']) ? date('d/m/Y', strtotime($e['fecha_evaluacion'])) : '—' ?></small></td>
                                                <td><span class="badge bg-<?= $pColor ?>"><?= $puntaje ?>/25</span></td>
                                                <td class="text-center">
                                                    <button class="btn btn-sm btn-outline-info" onclick="verDetalleRubrica(<?= $e['id'] ?>)" title="Ver detalle">
                                                        <i class="ti ti-eye"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ========= MODAL: Realizar Evaluación (Rúbrica) ========= -->
<div class="modal fade" id="modalRealizarEvaluacion" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="ti ti-clipboard-check me-2"></i>Realizar Evaluación</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info mb-3" id="infoEvaluado">
                    <strong>Evaluando a:</strong> <span id="nombreEvaluado">—</span>
                </div>

                <form id="formRubrica">
                    <input type="hidden" id="rubrica_id" name="id_evaluacion_empleado">

                    <p class="text-muted mb-3">Califique cada criterio del <strong>1 (Deficiente)</strong> al <strong>5 (Excelente)</strong>:</p>

                    <!-- Criterio 1: Responsabilidad -->
                    <div class="card mb-3 border-start border-4 border-primary">
                        <div class="card-body">
                            <h6 class="text-primary"><i class="ti ti-shield-check me-1"></i>1. Responsabilidad</h6>
                            <p class="text-muted small mb-2">Cumple con sus funciones asignadas. Respeta horarios y compromisos. Entrega trabajos en el tiempo establecido.</p>
                            <select class="form-select" name="puntaje_responsabilidad" required>
                                <option value="">Seleccionar...</option>
                                <option value="1">1 - Deficiente</option>
                                <option value="2">2 - Insuficiente</option>
                                <option value="3">3 - Aceptable</option>
                                <option value="4">4 - Bueno</option>
                                <option value="5">5 - Excelente</option>
                            </select>
                        </div>
                    </div>

                    <!-- Criterio 2: Trabajo en equipo -->
                    <div class="card mb-3 border-start border-4 border-success">
                        <div class="card-body">
                            <h6 class="text-success"><i class="ti ti-users me-1"></i>2. Trabajo en Equipo</h6>
                            <p class="text-muted small mb-2">Colabora con sus compañeros. Mantiene buena comunicación. Apoya cuando se le necesita.</p>
                            <select class="form-select" name="puntaje_equipo" required>
                                <option value="">Seleccionar...</option>
                                <option value="1">1 - Deficiente</option>
                                <option value="2">2 - Insuficiente</option>
                                <option value="3">3 - Aceptable</option>
                                <option value="4">4 - Bueno</option>
                                <option value="5">5 - Excelente</option>
                            </select>
                        </div>
                    </div>

                    <!-- Criterio 3: Ética y valores -->
                    <div class="card mb-3 border-start border-4 border-warning">
                        <div class="card-body">
                            <h6 class="text-warning"><i class="ti ti-heart me-1"></i>3. Ética y Valores</h6>
                            <p class="text-muted small mb-2">Actúa con honestidad. Respeta normas institucionales. Muestra respeto hacia los demás.</p>
                            <select class="form-select" name="puntaje_etica" required>
                                <option value="">Seleccionar...</option>
                                <option value="1">1 - Deficiente</option>
                                <option value="2">2 - Insuficiente</option>
                                <option value="3">3 - Aceptable</option>
                                <option value="4">4 - Bueno</option>
                                <option value="5">5 - Excelente</option>
                            </select>
                        </div>
                    </div>

                    <!-- Criterio 4: Comunicación -->
                    <div class="card mb-3 border-start border-4 border-info">
                        <div class="card-body">
                            <h6 class="text-info"><i class="ti ti-message-circle me-1"></i>4. Comunicación</h6>
                            <p class="text-muted small mb-2">Se expresa con claridad. Escucha activamente. Maneja conflictos adecuadamente.</p>
                            <select class="form-select" name="puntaje_comunicacion" required>
                                <option value="">Seleccionar...</option>
                                <option value="1">1 - Deficiente</option>
                                <option value="2">2 - Insuficiente</option>
                                <option value="3">3 - Aceptable</option>
                                <option value="4">4 - Bueno</option>
                                <option value="5">5 - Excelente</option>
                            </select>
                        </div>
                    </div>

                    <!-- Criterio 5: Compromiso institucional -->
                    <div class="card mb-3 border-start border-4 border-danger">
                        <div class="card-body">
                            <h6 class="text-danger"><i class="ti ti-building me-1"></i>5. Compromiso Institucional</h6>
                            <p class="text-muted small mb-2">Se identifica con la misión del instituto. Participa en actividades institucionales. Propone mejoras.</p>
                            <select class="form-select" name="puntaje_compromiso" required>
                                <option value="">Seleccionar...</option>
                                <option value="1">1 - Deficiente</option>
                                <option value="2">2 - Insuficiente</option>
                                <option value="3">3 - Aceptable</option>
                                <option value="4">4 - Bueno</option>
                                <option value="5">5 - Excelente</option>
                            </select>
                        </div>
                    </div>

                    <!-- Observaciones -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">Observaciones (opcional)</label>
                        <textarea class="form-control" name="observaciones" rows="3" placeholder="Comentarios adicionales sobre el desempeño del evaluado..."></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="ti ti-x me-1"></i>Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="enviarRubrica()"><i class="ti ti-send me-1"></i>Enviar Evaluación</button>
            </div>
        </div>
    </div>
</div>

<!-- ========= MODAL: Autoevaluación (Primera Persona) ========= -->
<div class="modal fade" id="modalAutoevaluacion" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-gradient" style="background: linear-gradient(135deg, #667eea, #764ba2); color:#fff;">
                <h5 class="modal-title"><i class="ti ti-user-check me-2"></i>Autoevaluación</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-secondary mb-3">
                    <i class="ti ti-info-circle me-1"></i>
                    <strong>Estás evaluándote a ti mismo.</strong> Responde con honestidad cada criterio.
                </div>

                <form id="formAutoevaluacion">
                    <input type="hidden" id="auto_rubrica_id" name="id_evaluacion_empleado">

                    <p class="text-muted mb-3">Califica tu desempeño del <strong>1 (Deficiente)</strong> al <strong>5 (Excelente)</strong>:</p>

                    <!-- Criterio 1 -->
                    <div class="card mb-3 border-start border-4 border-primary">
                        <div class="card-body">
                            <h6 class="text-primary"><i class="ti ti-shield-check me-1"></i>1. Responsabilidad</h6>
                            <p class="text-muted small mb-2">Cumplo puntualmente con mis funciones. Respeto horarios y compromisos. Entrego mis actividades en el tiempo establecido.</p>
                            <select class="form-select" name="puntaje_responsabilidad" required>
                                <option value="">Seleccionar...</option>
                                <option value="1">1 - Deficiente</option>
                                <option value="2">2 - Insuficiente</option>
                                <option value="3">3 - Aceptable</option>
                                <option value="4">4 - Bueno</option>
                                <option value="5">5 - Excelente</option>
                            </select>
                        </div>
                    </div>

                    <!-- Criterio 2 -->
                    <div class="card mb-3 border-start border-4 border-success">
                        <div class="card-body">
                            <h6 class="text-success"><i class="ti ti-users me-1"></i>2. Trabajo en Equipo</h6>
                            <p class="text-muted small mb-2">Colaboro activamente con mis compañeros. Mantengo una comunicación respetuosa. Apoyo cuando otros lo necesitan.</p>
                            <select class="form-select" name="puntaje_equipo" required>
                                <option value="">Seleccionar...</option>
                                <option value="1">1 - Deficiente</option>
                                <option value="2">2 - Insuficiente</option>
                                <option value="3">3 - Aceptable</option>
                                <option value="4">4 - Bueno</option>
                                <option value="5">5 - Excelente</option>
                            </select>
                        </div>
                    </div>

                    <!-- Criterio 3 -->
                    <div class="card mb-3 border-start border-4 border-warning">
                        <div class="card-body">
                            <h6 class="text-warning"><i class="ti ti-heart me-1"></i>3. Ética Profesional</h6>
                            <p class="text-muted small mb-2">Actúo con honestidad y transparencia. Respeto normas institucionales. Trato con respeto a estudiantes y compañeros.</p>
                            <select class="form-select" name="puntaje_etica" required>
                                <option value="">Seleccionar...</option>
                                <option value="1">1 - Deficiente</option>
                                <option value="2">2 - Insuficiente</option>
                                <option value="3">3 - Aceptable</option>
                                <option value="4">4 - Bueno</option>
                                <option value="5">5 - Excelente</option>
                            </select>
                        </div>
                    </div>

                    <!-- Criterio 4 -->
                    <div class="card mb-3 border-start border-4 border-info">
                        <div class="card-body">
                            <h6 class="text-info"><i class="ti ti-message-circle me-1"></i>4. Comunicación</h6>
                            <p class="text-muted small mb-2">Me expreso con claridad. Escucho activamente. Manejo adecuadamente los conflictos.</p>
                            <select class="form-select" name="puntaje_comunicacion" required>
                                <option value="">Seleccionar...</option>
                                <option value="1">1 - Deficiente</option>
                                <option value="2">2 - Insuficiente</option>
                                <option value="3">3 - Aceptable</option>
                                <option value="4">4 - Bueno</option>
                                <option value="5">5 - Excelente</option>
                            </select>
                        </div>
                    </div>

                    <!-- Criterio 5 -->
                    <div class="card mb-3 border-start border-4 border-danger">
                        <div class="card-body">
                            <h6 class="text-danger"><i class="ti ti-building me-1"></i>5. Compromiso Institucional</h6>
                            <p class="text-muted small mb-2">Me identifico con la misión del instituto. Participo en actividades institucionales. Propongo mejoras para la institución.</p>
                            <select class="form-select" name="puntaje_compromiso" required>
                                <option value="">Seleccionar...</option>
                                <option value="1">1 - Deficiente</option>
                                <option value="2">2 - Insuficiente</option>
                                <option value="3">3 - Aceptable</option>
                                <option value="4">4 - Bueno</option>
                                <option value="5">5 - Excelente</option>
                            </select>
                        </div>
                    </div>

                    <!-- Observaciones -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">Reflexión personal (opcional)</label>
                        <textarea class="form-control" name="observaciones" rows="3" placeholder="¿En qué áreas considero que puedo mejorar?"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="ti ti-x me-1"></i>Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="enviarAutoevaluacion()"><i class="ti ti-send me-1"></i>Enviar Autoevaluación</button>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    // Las funciones de render dinámico JS ya no son necesarias por primera carga usando PHP
    // Sin embargo, si 'cargarMisEvaluaciones' se llama luego de guardar rúbrica, aquí están las de update:


// ==================== RÚBRICA ====================
function abrirRubrica(id, nombre) {
    document.getElementById('rubrica_id').value = id;
    document.getElementById('nombreEvaluado').textContent = nombre;
    document.getElementById('formRubrica').reset();
    document.getElementById('rubrica_id').value = id;
    new bootstrap.Modal(document.getElementById('modalRealizarEvaluacion')).show();
}

function enviarRubrica() {
    const form = document.getElementById('formRubrica');
    const selects = form.querySelectorAll('select[required]');
    let todosLlenos = true;
    selects.forEach(sel => {
        if (!sel.value) { sel.classList.add('is-invalid'); todosLlenos = false; }
        else { sel.classList.remove('is-invalid'); }
    });

    if (!todosLlenos) {
        Swal.fire({icon:'warning', title:'Campos incompletos', text:'Debe calificar todos los 5 criterios'});
        return;
    }

    Swal.fire({ title:'Enviando evaluación...', allowOutsideClick:false, didOpen:() => Swal.showLoading() });

    const fd = new FormData(form);
    fetch('<?= base_url('index.php/empleado/evaluaciones/guardar-rubrica') ?>', { method:'POST', body: fd })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            bootstrap.Modal.getInstance(document.getElementById('modalRealizarEvaluacion')).hide();
            Swal.fire({icon:'success', title:'¡Evaluación enviada!', text: data.message, timer:2500, showConfirmButton:false}).then(() => {
                window.location.reload(); // Recargar página por seguridad para ver los resultados actualizados
            });
        } else {
            Swal.fire({icon:'error', title:'Error', text: data.message});
        }
    })
    .catch(() => Swal.fire({icon:'error', title:'Error', text:'Error de conexión'}));
}

// ==================== AUTOEVALUACIÓN ====================
function abrirAutoevaluacion(id) {
    document.getElementById('formAutoevaluacion').reset();
    document.getElementById('auto_rubrica_id').value = id;
    new bootstrap.Modal(document.getElementById('modalAutoevaluacion')).show();
}

function enviarAutoevaluacion() {
    const form = document.getElementById('formAutoevaluacion');
    const selects = form.querySelectorAll('select[required]');
    let todosLlenos = true;
    selects.forEach(sel => {
        if (!sel.value) { sel.classList.add('is-invalid'); todosLlenos = false; }
        else { sel.classList.remove('is-invalid'); }
    });

    if (!todosLlenos) {
        Swal.fire({icon:'warning', title:'Campos incompletos', text:'Debes calificar todos los 5 criterios'});
        return;
    }

    Swal.fire({ title:'Enviando autoevaluación...', allowOutsideClick:false, didOpen:() => Swal.showLoading() });

    const fd = new FormData(form);
    fetch('<?= base_url('index.php/empleado/evaluaciones/guardar-rubrica') ?>', { method:'POST', body: fd })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            bootstrap.Modal.getInstance(document.getElementById('modalAutoevaluacion')).hide();
            Swal.fire({icon:'success', title:'¡Autoevaluación enviada!', text: data.message, timer:2500, showConfirmButton:false}).then(() => {
                window.location.reload(); // Recargar página para reflejar el estado completado
            });
        } else {
            Swal.fire({icon:'error', title:'Error', text: data.message});
        }
    })
    .catch(() => Swal.fire({icon:'error', title:'Error', text:'Error de conexión'}));
}

function verDetalleRubrica(id) {
    fetch(`<?= base_url('index.php/empleado/evaluaciones/detalle') ?>/${id}`)
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            const e = data.evaluacion;
            const criterios = [
                { nombre:'Responsabilidad', valor: e.puntaje_responsabilidad, color:'primary', icono:'shield-check' },
                { nombre:'Trabajo en Equipo', valor: e.puntaje_equipo, color:'success', icono:'users' },
                { nombre:'Ética y Valores', valor: e.puntaje_etica, color:'warning', icono:'heart' },
                { nombre:'Comunicación', valor: e.puntaje_comunicacion, color:'info', icono:'message-circle' },
                { nombre:'Compromiso', valor: e.puntaje_compromiso, color:'danger', icono:'building' }
            ];
            const detHTML = criterios.map(c => `
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span><i class="ti ti-${c.icono} text-${c.color} me-1"></i>${c.nombre}</span>
                    <div>
                        <span class="badge bg-${c.color}">${c.valor || 0}/5</span>
                        <div class="progress d-inline-block ms-2" style="width:80px;height:8px;vertical-align:middle;">
                            <div class="progress-bar bg-${c.color}" style="width:${((c.valor||0)/5)*100}%"></div>
                        </div>
                    </div>
                </div>
            `).join('');

            Swal.fire({
                title: `Evaluación de ${esc(e.nombres_evaluado)} ${esc(e.apellidos_evaluado)}`,
                html: `
                    <div class="text-start">
                        ${detHTML}
                        <hr>
                        <div class="d-flex justify-content-between fw-bold">
                            <span>Puntaje Total</span>
                            <span class="badge bg-dark fs-6">${e.puntaje_total || 0}/25</span>
                        </div>
                        ${e.observaciones ? `<hr><p class="text-muted small"><strong>Observaciones:</strong> ${esc(e.observaciones)}</p>` : ''}
                    </div>
                `, width:'550px', confirmButtonText:'Cerrar'
            });
        }
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
