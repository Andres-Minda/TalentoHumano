<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>

<div class="page-wrapper">
    <div class="page-content">
        <!-- Breadcrumb -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <h4 class="page-title mb-0">Registrar Nueva Inasistencia</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="<?= base_url('admin-th/dashboard') ?>">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="<?= base_url('admin-th/inasistencias') ?>">Inasistencias</a></li>
                            <li class="breadcrumb-item active">Registrar</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Formulario Principal -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="card-title mb-0">
                            <i class="ti ti-calendar-plus me-2"></i>
                            Información de la Inasistencia
                        </h5>
                    </div>
                    <div class="card-body">
                        <form id="formInasistencia">

                            <!-- Empleado -->
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <label for="empleado_id" class="form-label fw-bold">Empleado <span class="text-danger">*</span></label>
                                    <select class="form-select" id="empleado_id" name="empleado_id" required>
                                        <option value="">-- Seleccionar empleado --</option>
                                        <?php if (isset($empleados) && !empty($empleados)): ?>
                                            <?php foreach ($empleados as $empleado): ?>
                                                <option value="<?= $empleado['id_empleado'] ?>"
                                                        data-tipo="<?= $empleado['tipo_empleado'] ?? '' ?>"
                                                        data-departamento="<?= $empleado['departamento'] ?? '' ?>">
                                                    <?= $empleado['apellidos'] . ' ' . $empleado['nombres'] ?>
                                                    (<?= $empleado['tipo_empleado'] ?? '' ?> - <?= $empleado['departamento'] ?? '' ?>)
                                                </option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                </div>
                            </div>

                            <!-- Fecha y Hora -->
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="fecha_inasistencia" class="form-label fw-bold">Fecha <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="fecha_inasistencia" name="fecha_inasistencia"
                                           value="<?= date('Y-m-d') ?>" max="<?= date('Y-m-d') ?>" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="hora_inasistencia" class="form-label fw-bold">Hora (opcional)</label>
                                    <input type="time" class="form-control" id="hora_inasistencia" name="hora_inasistencia">
                                    <small class="text-muted">Hora en que se detectó la ausencia</small>
                                </div>
                            </div>

                            <!-- Tipo de Inasistencia -->
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <label for="tipo_inasistencia" class="form-label fw-bold">Tipo de Inasistencia <span class="text-danger">*</span></label>
                                    <select class="form-select" id="tipo_inasistencia" name="tipo_inasistencia" required>
                                        <option value="Injustificada">Injustificada</option>
                                        <option value="Justificada">Justificada</option>
                                        <option value="Permiso">Permiso</option>
                                        <option value="Vacaciones">Vacaciones</option>
                                        <option value="Licencia Médica">Licencia Médica</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Motivo / Comentario -->
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <label for="motivo" class="form-label fw-bold">Comentario / Motivo <span class="text-danger">*</span></label>
                                    <textarea class="form-control" id="motivo" name="motivo" rows="4"
                                              placeholder="Describa el motivo de la inasistencia (mínimo 5 caracteres)..." required minlength="5"></textarea>
                                </div>
                            </div>

                            <!-- Botones -->
                            <div class="d-flex justify-content-between">
                                <a href="<?= base_url('admin-th/inasistencias') ?>" class="btn btn-secondary">
                                    <i class="ti ti-arrow-left me-1"></i>Cancelar
                                </a>
                                <button type="button" class="btn btn-primary" onclick="guardarInasistencia()">
                                    <i class="ti ti-check me-1"></i>Registrar Inasistencia
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Panel Lateral: Info del Empleado + Historial -->
            <div class="col-lg-4">
                <!-- Info del empleado seleccionado -->
                <div class="card border-0 shadow-sm" id="panelInfoEmpleado" style="display:none;">
                    <div class="card-header bg-info text-white">
                        <h6 class="mb-0"><i class="ti ti-user me-1"></i>Empleado Seleccionado</h6>
                    </div>
                    <div class="card-body" id="contenidoInfoEmpleado"></div>
                </div>

                <!-- Total Acumulado -->
                <div class="card border-0 shadow-sm" id="panelAcumulado" style="display:none;">
                    <div class="card-header bg-warning text-dark">
                        <h6 class="mb-0"><i class="ti ti-chart-bar me-1"></i>Total Inasistencias Históricas</h6>
                    </div>
                    <div class="card-body text-center" id="contenidoAcumulado">
                        <div class="spinner-border spinner-border-sm text-warning"></div>
                    </div>
                </div>

                <!-- Guía rápida -->
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-light">
                        <h6 class="mb-0"><i class="ti ti-info-circle me-1"></i>Guía</h6>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled small text-muted mb-0">
                            <li class="mb-1"><i class="ti ti-check text-success me-1"></i><strong>Justificada:</strong> Con documentación</li>
                            <li class="mb-1"><i class="ti ti-x text-danger me-1"></i><strong>Injustificada:</strong> Sin documentación</li>
                            <li class="mb-1"><i class="ti ti-clock text-info me-1"></i><strong>Permiso:</strong> Autorizado previamente</li>
                            <li class="mb-1"><i class="ti ti-beach text-warning me-1"></i><strong>Vacaciones:</strong> Período autorizado</li>
                            <li><i class="ti ti-first-aid-kit text-primary me-1"></i><strong>Licencia Médica:</strong> Certificado médico</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabla: Listado de Inasistencias (con Acumulado) -->
        <div class="row mt-2">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="ti ti-list me-1"></i>Inasistencias Registradas</h5>
                        <div>
                            <input type="text" id="filtroNombreIna" class="form-control form-control-sm" placeholder="Filtrar por nombre..." onkeyup="filtrarTablaInasistencias()" style="width:220px;display:inline-block;">
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle" id="tablaInasistencias">
                                <thead class="table-dark">
                                    <tr>
                                        <th>#</th>
                                        <th>Empleado</th>
                                        <th>Departamento</th>
                                        <th>Fecha</th>
                                        <th>Hora</th>
                                        <th>Tipo</th>
                                        <th>Motivo</th>
                                        <th class="text-center">Total Histórico</th>
                                    </tr>
                                </thead>
                                <tbody id="tbodyInasistencias">
                                    <tr><td colspan="8" class="text-center py-3"><div class="spinner-border spinner-border-sm text-primary me-2"></div>Cargando...</td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    cargarListaInasistencias();

    // Al cambiar empleado, mostrar info y total acumulado
    document.getElementById('empleado_id').addEventListener('change', function() {
        const sel = this;
        const id = sel.value;
        if (id) {
            const opt = sel.options[sel.selectedIndex];
            const tipo = opt.getAttribute('data-tipo') || '';
            const depto = opt.getAttribute('data-departamento') || '';
            const nombre = opt.textContent.trim();

            document.getElementById('contenidoInfoEmpleado').innerHTML = `
                <p class="mb-1"><strong>${nombre.split('(')[0].trim()}</strong></p>
                <span class="badge bg-info me-1">${tipo}</span>
                <span class="badge bg-secondary">${depto}</span>
            `;
            document.getElementById('panelInfoEmpleado').style.display = 'block';

            // Cargar total acumulado (Req 4)
            document.getElementById('panelAcumulado').style.display = 'block';
            document.getElementById('contenidoAcumulado').innerHTML = '<div class="spinner-border spinner-border-sm text-warning"></div>';

            fetch(`<?= base_url('index.php/admin-th/inasistencias/total') ?>/${id}`)
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    const total = data.total_inasistencias;
                    const color = total >= 10 ? 'danger' : (total >= 5 ? 'warning' : 'success');
                    document.getElementById('contenidoAcumulado').innerHTML = `
                        <h1 class="text-${color} mb-1">${total}</h1>
                        <p class="text-muted mb-0">inasistencias acumuladas</p>
                        ${total >= 10 ? '<span class="badge bg-danger mt-1"><i class="ti ti-alert-triangle me-1"></i>Nivel Crítico</span>' : ''}
                        ${total >= 5 && total < 10 ? '<span class="badge bg-warning text-dark mt-1"><i class="ti ti-alert-circle me-1"></i>Atención</span>' : ''}
                        ${total < 5 ? '<span class="badge bg-success mt-1"><i class="ti ti-check me-1"></i>Normal</span>' : ''}
                    `;
                }
            });
        } else {
            document.getElementById('panelInfoEmpleado').style.display = 'none';
            document.getElementById('panelAcumulado').style.display = 'none';
        }
    });
});

// ==================== GUARDAR INASISTENCIA (Req 3) ====================
function guardarInasistencia() {
    const form = document.getElementById('formInasistencia');
    const empleado = document.getElementById('empleado_id').value;
    const fecha = document.getElementById('fecha_inasistencia').value;
    const motivo = document.getElementById('motivo').value.trim();

    if (!empleado) { Swal.fire({icon:'warning', title:'Campo requerido', text:'Seleccione un empleado'}); return; }
    if (!fecha) { Swal.fire({icon:'warning', title:'Campo requerido', text:'Ingrese la fecha'}); return; }
    if (!motivo || motivo.length < 5) { Swal.fire({icon:'warning', title:'Campo requerido', text:'El motivo debe tener al menos 5 caracteres'}); return; }

    Swal.fire({ title:'Registrando inasistencia...', allowOutsideClick:false, didOpen:() => Swal.showLoading() });

    const fd = new FormData(form);
    fetch('<?= base_url("index.php/admin-th/inasistencias/guardar") ?>', { method:'POST', body: fd })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            Swal.fire({icon:'success', title:'¡Registrada!', text: data.message, timer:2500, showConfirmButton:false});
            form.reset();
            document.getElementById('fecha_inasistencia').value = new Date().toISOString().split('T')[0];
            document.getElementById('panelInfoEmpleado').style.display = 'none';
            document.getElementById('panelAcumulado').style.display = 'none';
            cargarListaInasistencias();
        } else {
            Swal.fire({icon:'error', title:'Error', text: data.message});
        }
    })
    .catch(() => Swal.fire({icon:'error', title:'Error', text:'Error de conexión'}));
}

// ==================== LISTAR INASISTENCIAS CON ACUMULADO (Req 4) ====================
function cargarListaInasistencias() {
    fetch('<?= base_url("index.php/admin-th/inasistencias/listar-json") ?>')
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            renderTablaInasistencias(data.data);
        } else {
            document.getElementById('tbodyInasistencias').innerHTML = `<tr><td colspan="8" class="text-center text-danger">${data.message}</td></tr>`;
        }
    })
    .catch(err => {
        document.getElementById('tbodyInasistencias').innerHTML = `<tr><td colspan="8" class="text-center text-danger">Error: ${err.message}</td></tr>`;
    });
}

function renderTablaInasistencias(rows) {
    const tbody = document.getElementById('tbodyInasistencias');
    tbody.innerHTML = '';
    if (!rows || rows.length === 0) {
        tbody.innerHTML = '<tr><td colspan="8" class="text-center text-muted py-3"><i class="ti ti-inbox me-1"></i>No hay inasistencias registradas</td></tr>';
        return;
    }

    rows.forEach((r, i) => {
        const tipoBadges = {
            'Justificada':'success', 'Injustificada':'danger', 'Permiso':'info',
            'Vacaciones':'primary', 'Licencia Médica':'warning'
        };
        const total = parseInt(r.total_acumulado) || 0;
        const totalColor = total >= 10 ? 'danger' : (total >= 5 ? 'warning' : 'success');

        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td>${i+1}</td>
            <td><strong>${esc(r.apellidos || '')} ${esc(r.nombres || '')}</strong><br><small class="text-muted">${esc(r.tipo_empleado || '')}</small></td>
            <td><span class="badge bg-secondary">${esc(r.departamento || '—')}</span></td>
            <td>${formatFecha(r.fecha_inasistencia)}</td>
            <td>${r.hora_inasistencia || '—'}</td>
            <td><span class="badge bg-${tipoBadges[r.tipo_inasistencia] || 'secondary'}">${esc(r.tipo_inasistencia || '—')}</span></td>
            <td><small>${esc((r.motivo || '').substring(0, 60))}${(r.motivo||'').length > 60 ? '...' : ''}</small></td>
            <td class="text-center">
                <span class="badge bg-${totalColor} fs-6" title="Total Inasistencias Históricas">${total}</span>
            </td>
        `;
        tbody.appendChild(tr);
    });
}

// ==================== FILTRO POR NOMBRE ====================
function filtrarTablaInasistencias() {
    const filtro = document.getElementById('filtroNombreIna').value.toLowerCase().trim();
    const filas = document.querySelectorAll('#tbodyInasistencias tr');
    filas.forEach(fila => {
        const celdas = fila.querySelectorAll('td');
        if (celdas.length < 2) return;
        const nombre = (celdas[1]?.textContent || '').toLowerCase();
        fila.style.display = (!filtro || nombre.includes(filtro)) ? '' : 'none';
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
