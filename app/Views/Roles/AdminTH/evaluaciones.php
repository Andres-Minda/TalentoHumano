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
                        <li class="breadcrumb-item"><a href="<?= site_url('admin-th/dashboard') ?>">Dashboard</a></li>
                        <li class="breadcrumb-item active">Gestión de Evaluaciones</li>
                    </ol>
                </nav>
            </div>
        </div>

        <!-- Tabla -->
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="ti ti-clipboard-check me-2"></i>Gestión de Evaluaciones</h5>
                        <div>
                            <button type="button" class="btn btn-danger me-2 d-none" id="btnEliminarSeleccion" onclick="eliminarSeleccionados()">
                                <i class="ti ti-trash me-1"></i> Eliminar Seleccionados (<span id="contadorSeleccion">0</span>)
                            </button>
                            <button type="button" class="btn btn-info me-2" onclick="mostrarSelectorGlobal()">
                                <i class="ti ti-chart-bar me-1"></i> Resultados Globales
                            </button>
                            <button type="button" class="btn btn-primary" onclick="nuevaEvaluacion()">
                                <i class="ti ti-plus me-1"></i> Nueva Evaluación
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <div class="input-group">
                                    <span class="input-group-text"><i class="ti ti-search"></i></span>
                                    <input type="text" class="form-control" id="filtroNombre" placeholder="Buscar por nombre de empleado..." oninput="filtrarPorNombre()">
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover align-middle">
                                <thead class="table-dark">
                                    <tr>
                                        <th style="width:40px;"><input type="checkbox" class="form-check-input" id="checkAll" onchange="toggleAll(this)"></th>
                                        <th>#</th>
                                        <th>Empleado Evaluado</th>
                                        <th>Evaluador</th>
                                        <th>Tipo</th>
                                        <th>Fecha</th>
                                        <th>Puntuación</th>
                                        <th>Estado</th>
                                        <th class="text-center">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody id="tbodyEvaluaciones">
                                    <tr><td colspan="9" class="text-center py-4">
                                        <div class="spinner-border spinner-border-sm text-primary me-2"></div>Cargando evaluaciones...
                                    </td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ========= MODAL: Nueva Evaluación ========= -->
<div class="modal fade" id="modalEvaluacion" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="ti ti-clipboard-plus me-2"></i>Nueva Evaluación</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formEvaluacion">
                    <!-- Tipo de Evaluación -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Tipo de Evaluación <span class="text-danger">*</span></label>
                            <select class="form-select" id="tipo_evaluacion" name="tipo_evaluacion" required onchange="onTipoChange()">
                                <option value="">Seleccionar tipo...</option>
                                <option value="Autoevaluación">Autoevaluación</option>
                                <option value="Evaluación 360">Evaluación 360 (Todos evalúan)</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Fecha de Evaluación <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="fecha_evaluacion" name="fecha_evaluacion" required>
                        </div>
                    </div>

                    <!-- Info dinámica del tipo -->
                    <div class="alert alert-info d-none" id="info360">
                        <i class="ti ti-info-circle me-1"></i>
                        <strong>Evaluación 360°:</strong> El sistema asignará automáticamente a <strong>todos los empleados activos</strong> como evaluadores del empleado seleccionado.
                    </div>
                    <div class="alert alert-secondary d-none" id="infoAuto">
                        <i class="ti ti-user me-1"></i>
                        <strong>Autoevaluación:</strong> El empleado seleccionado se evaluará a <strong>sí mismo</strong>.
                    </div>

                    <!-- Empleado a Evaluar -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Empleado a Evaluar <span class="text-danger">*</span></label>
                            <select class="form-select" id="empleado_id" name="empleado_id" required>
                                <option value="">Cargando empleados...</option>
                            </select>
                        </div>
                    </div>

                    <!-- Observaciones -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">Observaciones</label>
                        <textarea class="form-control" id="observaciones" name="observaciones" rows="3" placeholder="Observaciones opcionales..."></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="ti ti-x me-1"></i>Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="guardarEvaluacion()">
                    <i class="ti ti-device-floppy me-1"></i>Guardar
                </button>
            </div>
        </div>
    </div>
</div>

<script>
let todosEmpleados = [];

document.addEventListener('DOMContentLoaded', function() {
    cargarEvaluaciones();
    cargarEmpleadosData();
    document.getElementById('fecha_evaluacion').value = new Date().toISOString().split('T')[0];
});

// ==================== CARGAR EMPLEADOS ====================
function cargarEmpleadosData() {
    fetch('<?= base_url('index.php/admin-th/empleados/obtener') ?>')
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            todosEmpleados = data.empleados;
            const sel = document.getElementById('empleado_id');
            sel.innerHTML = '<option value="">-- Seleccionar empleado --</option>';
            todosEmpleados.forEach(e => {
                sel.innerHTML += `<option value="${e.id_empleado}">${e.apellidos} ${e.nombres} (${e.tipo_empleado})</option>`;
            });
        }
    })
    .catch(err => console.error('Error cargando empleados:', err));
}

// ==================== TIPO CHANGE ====================
function onTipoChange() {
    const tipo = document.getElementById('tipo_evaluacion').value;
    document.getElementById('info360').classList.toggle('d-none', tipo !== 'Evaluación 360');
    document.getElementById('infoAuto').classList.toggle('d-none', tipo !== 'Autoevaluación');
}

// ==================== CARGAR EVALUACIONES ====================
function cargarEvaluaciones() {
    const tbody = document.getElementById('tbodyEvaluaciones');
    tbody.innerHTML = '<tr><td colspan="9" class="text-center py-4"><div class="spinner-border spinner-border-sm text-primary me-2"></div>Cargando...</td></tr>';
    document.getElementById('checkAll').checked = false;
    actualizarBotonEliminar();

    fetch('<?= base_url('index.php/admin-th/evaluaciones/obtener') ?>')
    .then(r => {
        if (!r.ok) throw new Error('HTTP ' + r.status);
        return r.json();
    })
    .then(data => {
        if (data.success) {
            renderizar(data.evaluaciones);
        } else {
            tbody.innerHTML = `<tr><td colspan="9" class="text-center text-danger py-3"><i class="ti ti-alert-circle me-1"></i>${data.message || 'Error al cargar'}</td></tr>`;
        }
    })
    .catch(err => {
        console.error('Error:', err);
        tbody.innerHTML = `<tr><td colspan="9" class="text-center text-danger py-3"><i class="ti ti-wifi-off me-1"></i>Error: ${err.message}</td></tr>`;
    });
}

function renderizar(evals) {
    const tbody = document.getElementById('tbodyEvaluaciones');
    tbody.innerHTML = '';

    if (!evals || evals.length === 0) {
        tbody.innerHTML = '<tr><td colspan="9" class="text-center text-muted py-4"><i class="ti ti-inbox me-1"></i>No hay evaluaciones registradas</td></tr>';
        return;
    }

    evals.forEach((e, i) => {
        const estadoBadges = { 'Planificada':'warning', 'En curso':'info', 'Finalizada':'success' };

        // Puntuación dinámica por estado
        let puntuacionHTML;
        const estado = e.estado || '';
        if (estado === 'Finalizada') {
            const puntaje = e.puntaje_total ? parseFloat(e.puntaje_total).toFixed(1) : '0.0';
            const pColor = parseFloat(puntaje) >= 80 ? 'success' : (parseFloat(puntaje) >= 60 ? 'primary' : 'danger');
            puntuacionHTML = `<span class="badge bg-${pColor}">${puntaje}/100</span>`;
        } else {
            puntuacionHTML = '<span class="badge bg-warning text-dark"><i class="ti ti-clock me-1"></i>Pendiente</span>';
        }

        const row = document.createElement('tr');
        row.innerHTML = `
            <td><input type="checkbox" class="form-check-input chk-eval" value="${e.id}" onchange="actualizarBotonEliminar()"></td>
            <td>${i+1}</td>
            <td><strong>${esc(e.nombres_empleado || '')} ${esc(e.apellidos_empleado || '')}</strong></td>
            <td>${esc(e.nombre_evaluador || 'N/A')}</td>
            <td><span class="badge bg-info">${esc(e.tipo_evaluacion || '—')}</span></td>
            <td><small>${formatFecha(e.fecha_evaluacion)}</small></td>
            <td>${puntuacionHTML}</td>
            <td><span class="badge bg-${estadoBadges[estado] || 'secondary'}">${estado || '—'}</span></td>
            <td class="text-center">
                <div class="btn-group btn-group-sm">
                    <button class="btn btn-outline-primary" onclick="verFormularioAdmin(${e.id})" title="Ver Formulario"><i class="ti ti-clipboard-list"></i></button>
                    <button class="btn btn-outline-info" onclick="verDetalle(${e.id})" title="Ver Detalles"><i class="ti ti-eye"></i></button>
                    <button class="btn btn-outline-warning" onclick="cambiarEstado(${e.id}, '${estado}')" title="Cambiar Estado"><i class="ti ti-toggle-left"></i></button>
                    <button class="btn btn-outline-danger" onclick="eliminarEval(${e.id})" title="Eliminar"><i class="ti ti-trash"></i></button>
                </div>
            </td>
        `;
        tbody.appendChild(row);
    });
}

// ==================== NUEVA EVALUACIÓN ====================
function nuevaEvaluacion() {
    document.getElementById('formEvaluacion').reset();
    document.getElementById('fecha_evaluacion').value = new Date().toISOString().split('T')[0];
    document.getElementById('info360').classList.add('d-none');
    document.getElementById('infoAuto').classList.add('d-none');
    new bootstrap.Modal(document.getElementById('modalEvaluacion')).show();
}

// ==================== GUARDAR ====================
function guardarEvaluacion() {
    const tipo = document.getElementById('tipo_evaluacion').value;
    const empleadoId = document.getElementById('empleado_id').value;

    if (!tipo || !empleadoId) {
        Swal.fire({icon:'warning', title:'Campos requeridos', text:'Seleccione tipo de evaluación y empleado a evaluar'});
        return;
    }

    // Loading para 360
    if (tipo === 'Evaluación 360') {
        Swal.fire({
            title: 'Generando Evaluaciones 360°',
            html: 'Creando evaluaciones para todos los empleados activos...',
            allowOutsideClick: false, didOpen: () => Swal.showLoading()
        });
    }

    const fd = new FormData(document.getElementById('formEvaluacion'));

    fetch('<?= base_url('index.php/admin-th/evaluaciones/crear') ?>', { method:'POST', body: fd })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            bootstrap.Modal.getInstance(document.getElementById('modalEvaluacion')).hide();
            Swal.fire({icon:'success', title:'¡Éxito!', text: data.message, timer:2500, showConfirmButton:false});
            cargarEvaluaciones();
        } else {
            Swal.fire({icon:'error', title:'Error', text: data.message});
        }
    })
    .catch(() => Swal.fire({icon:'error', title:'Error', text:'Error de conexión'}));
}

// ==================== VER DETALLE ====================
function verDetalle(id) {
    fetch(`<?= base_url('index.php/admin-th/evaluaciones/obtener') ?>/${id}`)
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            const e = data.evaluacion;
            const estado = e.evaluacion_estado || e.estado || '';
            let puntajeHTML = estado === 'Finalizada'
                ? (e.puntaje_total ? `<strong>${parseFloat(e.puntaje_total).toFixed(1)}/100</strong>` : 'Sin calificar')
                : '<span class="badge bg-warning text-dark">Pendiente</span>';

            Swal.fire({
                title: 'Detalle de Evaluación',
                html: `
                    <div class="text-start">
                        <p><strong>Evaluado:</strong> ${esc(e.nombres_empleado)} ${esc(e.apellidos_empleado)}</p>
                        <p><strong>Evaluador:</strong> ${esc(e.nombre_evaluador || 'N/A')}</p>
                        <p><strong>Tipo:</strong> ${e.tipo_evaluacion || '—'}</p>
                        <p><strong>Fecha:</strong> ${formatFecha(e.fecha_evaluacion)}</p>
                        <p><strong>Puntuación:</strong> ${puntajeHTML}</p>
                        <p><strong>Estado:</strong> ${estado}</p>
                        <p><strong>Observaciones:</strong> ${esc(e.observaciones) || 'Sin observaciones'}</p>
                    </div>
                `, width:'500px', confirmButtonText:'Cerrar'
            });
        }
    });
}

// ==================== CAMBIAR ESTADO ====================
function cambiarEstado(id, estadoActual) {
    const estados = ['Planificada', 'En curso', 'Finalizada'];
    const idx = estados.indexOf(estadoActual);
    const nuevo = estados[(idx + 1) % estados.length];

    Swal.fire({
        title: '¿Cambiar estado?', text: `Cambiar de "${estadoActual}" a "${nuevo}"`, icon:'question',
        showCancelButton:true, confirmButtonText:'Sí, cambiar', cancelButtonText:'Cancelar'
    }).then(r => {
        if (r.isConfirmed) {
            const fd = new FormData();
            fd.append('id_evaluacion', id); fd.append('estado', nuevo);
            fetch('<?= base_url('index.php/admin-th/evaluaciones/cambiar-estado') ?>', {method:'POST', body:fd})
            .then(r => r.json())
            .then(data => {
                Swal.fire({icon: data.success?'success':'error', title: data.success?'Cambiado':'Error', text: data.message, timer:1500, showConfirmButton:false});
                if (data.success) cargarEvaluaciones();
            });
        }
    });
}

// ==================== ELIMINAR ====================
function eliminarEval(id) {
    Swal.fire({
        title: '¿Eliminar evaluación?', text:'No se puede deshacer', icon:'warning',
        showCancelButton:true, confirmButtonColor:'#dc3545', confirmButtonText:'Eliminar', cancelButtonText:'Cancelar'
    }).then(r => {
        if (r.isConfirmed) {
            const fd = new FormData();
            fd.append('id_evaluacion', id);
            fetch('<?= base_url('index.php/admin-th/evaluaciones/eliminar') ?>', {method:'POST', body:fd})
            .then(r => r.json())
            .then(data => {
                Swal.fire({icon: data.success?'success':'error', title: data.success?'Eliminada':'Error', text: data.message, timer:1500, showConfirmButton:false});
                if (data.success) cargarEvaluaciones();
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

// ==================== FILTRO POR NOMBRE ====================
function filtrarPorNombre() {
    const filtro = document.getElementById('filtroNombre').value.toLowerCase().trim();
    const filas = document.querySelectorAll('#tbodyEvaluaciones tr');

    filas.forEach(fila => {
        const celdas = fila.querySelectorAll('td');
        if (celdas.length < 4) return; // skip empty/loading rows

        // Buscar en columna evaluado (index 2) y evaluador (index 3)
        const evaluado = (celdas[2]?.textContent || '').toLowerCase();
        const evaluador = (celdas[3]?.textContent || '').toLowerCase();

        if (!filtro || evaluado.includes(filtro) || evaluador.includes(filtro)) {
            fila.style.display = '';
        } else {
            fila.style.display = 'none';
        }
    });
}

// ==================== SELECCIÓN MASIVA ====================
function toggleAll(master) {
    document.querySelectorAll('.chk-eval').forEach(chk => chk.checked = master.checked);
    actualizarBotonEliminar();
}

function actualizarBotonEliminar() {
    const seleccionados = document.querySelectorAll('.chk-eval:checked');
    const btn = document.getElementById('btnEliminarSeleccion');
    const contador = document.getElementById('contadorSeleccion');

    if (seleccionados.length > 0) {
        btn.classList.remove('d-none');
        contador.textContent = seleccionados.length;
    } else {
        btn.classList.add('d-none');
        contador.textContent = '0';
    }

    // Sincronizar checkAll
    const todos = document.querySelectorAll('.chk-eval');
    const checkAll = document.getElementById('checkAll');
    if (todos.length > 0) {
        checkAll.checked = seleccionados.length === todos.length;
        checkAll.indeterminate = seleccionados.length > 0 && seleccionados.length < todos.length;
    }
}

function eliminarSeleccionados() {
    const ids = Array.from(document.querySelectorAll('.chk-eval:checked')).map(chk => chk.value);

    if (ids.length === 0) {
        Swal.fire({icon:'warning', title:'Sin selección', text:'Seleccione al menos una evaluación'});
        return;
    }

    Swal.fire({
        title: `¿Eliminar ${ids.length} evaluación(es)?`,
        text: 'Esta acción no se puede deshacer.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        confirmButtonText: `Sí, eliminar ${ids.length}`,
        cancelButtonText: 'Cancelar'
    }).then(r => {
        if (r.isConfirmed) {
            Swal.fire({ title: 'Eliminando...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });

            const fd = new FormData();
            ids.forEach(id => fd.append('ids[]', id));

            fetch('<?= base_url('index.php/admin-th/evaluaciones/eliminar-masivo') ?>', { method:'POST', body: fd })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({icon:'success', title:'Eliminadas', text: data.message, timer:2000, showConfirmButton:false});
                    cargarEvaluaciones();
                } else {
                    Swal.fire({icon:'error', title:'Error', text: data.message});
                }
            })
            .catch(() => Swal.fire({icon:'error', title:'Error', text:'Error de conexión'}));
        }
    });
}

// ==================== VER FORMULARIO (LECTURA - ADMIN) ====================
function verFormularioAdmin(id) {
    fetch(`<?= base_url('index.php/admin-th/evaluaciones/obtener') ?>/${id}`)
    .then(r => r.json())
    .then(data => {
        if (!data.success) return Swal.fire({icon:'error', title:'Error', text: data.message});

        const e = data.evaluacion;
        const completada = e.puntaje_total && parseFloat(e.puntaje_total) > 0;

        const criterios = [
            { key:'puntaje_responsabilidad', nombre:'Responsabilidad', desc:'Cumple con sus funciones asignadas. Respeta horarios y compromisos. Entrega trabajos en el tiempo establecido.', color:'primary', icono:'shield-check' },
            { key:'puntaje_equipo', nombre:'Trabajo en Equipo', desc:'Colabora con sus compañeros. Mantiene buena comunicación. Apoya cuando se le necesita.', color:'success', icono:'users' },
            { key:'puntaje_etica', nombre:'Ética y Valores', desc:'Actúa con honestidad. Respeta normas institucionales. Muestra respeto hacia los demás.', color:'warning', icono:'heart' },
            { key:'puntaje_comunicacion', nombre:'Comunicación', desc:'Se expresa con claridad. Escucha activamente. Maneja conflictos adecuadamente.', color:'info', icono:'message-circle' },
            { key:'puntaje_compromiso', nombre:'Compromiso Institucional', desc:'Se identifica con la misión del instituto. Participa en actividades institucionales. Propone mejoras.', color:'danger', icono:'building' }
        ];

        const formHTML = criterios.map((c, i) => {
            const val = e[c.key] || '';
            let selectHTML = `<select class="form-select" disabled>`;
            selectHTML += `<option value="">${completada ? '' : 'Sin calificar'}</option>`;
            for (let v=1; v<=5; v++) {
                const labels = {1:'Deficiente',2:'Insuficiente',3:'Aceptable',4:'Bueno',5:'Excelente'};
                selectHTML += `<option value="${v}" ${val==v?'selected':''}>${v} - ${labels[v]}</option>`;
            }
            selectHTML += `</select>`;
            return `
                <div style="border-left:4px solid var(--bs-${c.color});padding:10px 15px;margin-bottom:10px;background:#f8f9fa;border-radius:4px;">
                    <h6 style="color:var(--bs-${c.color});margin-bottom:4px;"><i class="ti ti-${c.icono} me-1"></i>${i+1}. ${c.nombre}</h6>
                    <p style="font-size:0.8em;color:#666;margin-bottom:6px;">${c.desc}</p>
                    ${selectHTML}
                </div>
            `;
        }).join('');

        const estadoLabel = completada
            ? `<span class="badge bg-success">Completada - ${e.puntaje_total}/25</span>`
            : `<span class="badge bg-warning text-dark">Pendiente</span>`;

        Swal.fire({
            title: `Formulario de Evaluación`,
            html: `
                <div class="text-start">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <strong>Evaluado:</strong> ${esc(e.nombres_empleado || e.empleado_nombres || '')} ${esc(e.apellidos_empleado || e.empleado_apellidos || '')}<br>
                            <strong>Evaluador:</strong> ${esc(e.nombre_evaluador || e.evaluador_nombre || 'N/A')}
                        </div>
                        <div>${estadoLabel}</div>
                    </div>
                    ${formHTML}
                    ${e.observaciones ? `<div class="alert alert-light mt-2"><strong>Observaciones:</strong> ${esc(e.observaciones)}</div>` : ''}
                </div>
            `, width:'650px', confirmButtonText:'Cerrar'
        });
    });
}

// ==================== RESULTADOS GLOBALES ====================
function mostrarSelectorGlobal() {
    // Show selector with all employees
    let opcionesHTML = '<option value="">-- Seleccionar empleado --</option>';
    if (todosEmpleados && todosEmpleados.length > 0) {
        todosEmpleados.forEach(e => {
            opcionesHTML += `<option value="${e.id_empleado}">${e.apellidos} ${e.nombres}</option>`;
        });
    }

    Swal.fire({
        title: 'Resultados Globales de Desempeño',
        html: `
            <div class="text-start">
                <label class="form-label fw-bold">Seleccione un empleado:</label>
                <select class="form-select" id="swalSelectEmpleado">
                    ${opcionesHTML}
                </select>
            </div>
        `, width:'500px', showCancelButton:true, confirmButtonText:'Ver Resultados', cancelButtonText:'Cancelar',
        preConfirm: () => {
            const val = document.getElementById('swalSelectEmpleado').value;
            if (!val) { Swal.showValidationMessage('Seleccione un empleado'); return false; }
            return val;
        }
    }).then(result => {
        if (result.isConfirmed) {
            verResultadosGlobales(result.value);
        }
    });
}

function verResultadosGlobales(empleadoId) {
    Swal.fire({ title:'Calculando resultados...', allowOutsideClick:false, didOpen:() => Swal.showLoading() });

    fetch(`<?= base_url('index.php/admin-th/evaluaciones/resultados-globales') ?>/${empleadoId}`)
    .then(r => r.json())
    .then(data => {
        if (!data.success) {
            Swal.fire({icon:'info', title:'Sin Resultados', text: data.message});
            return;
        }

        const d = data.resultados;
        const porcentaje = d.porcentaje;
        const colorPct = porcentaje >= 80 ? 'success' : (porcentaje >= 60 ? 'primary' : (porcentaje >= 40 ? 'warning' : 'danger'));

        let html = `<div class="text-start">`;
        html += `<h5 class="text-center mb-3">${esc(d.nombre_empleado)}</h5>`;

        // Autoevaluación
        html += `<div class="card mb-2"><div class="card-body py-2">`;
        html += `<div class="d-flex justify-content-between"><span><i class="ti ti-user-check text-primary me-1"></i>Autoevaluación</span>`;
        if (d.autoevaluacion !== null) {
            html += `<span class="badge bg-primary">${d.autoevaluacion}/25</span>`;
        } else {
            html += `<span class="badge bg-secondary">No realizada</span>`;
        }
        html += `</div></div></div>`;

        // 360
        html += `<div class="card mb-2"><div class="card-body py-2">`;
        html += `<div class="d-flex justify-content-between"><span><i class="ti ti-refresh text-info me-1"></i>Promedio 360° (${d.total_360} eval.)</span>`;
        if (d.promedio_360 !== null) {
            html += `<span class="badge bg-info">${d.promedio_360}/25</span>`;
        } else {
            html += `<span class="badge bg-secondary">Sin evaluaciones</span>`;
        }
        html += `</div></div></div>`;

        // Calificación final
        html += `<hr>`;
        html += `<div class="text-center">`;
        html += `<h6>Calificación Final de Desempeño</h6>`;
        html += `<h2 class="text-${colorPct}">${d.calificacion_final}/50</h2>`;
        html += `<div class="progress mb-2" style="height:12px;"><div class="progress-bar bg-${colorPct}" style="width:${porcentaje}%"></div></div>`;
        html += `<span class="badge bg-${colorPct} fs-6">${porcentaje}%</span>`;
        html += `</div>`;
        html += `</div>`;

        Swal.fire({ title:'Resultados Globales', html: html, width:'550px', confirmButtonText:'Cerrar' });
    })
    .catch(() => Swal.fire({icon:'error', title:'Error', text:'Error de conexión'}));
}
</script>
<?= $this->endSection() ?>
