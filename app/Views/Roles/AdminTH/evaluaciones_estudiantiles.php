<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>
<div class="page-wrapper">
    <div class="page-content">
        <!-- Breadcrumb -->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Evaluación Estudiantil</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="<?= site_url('admin-th/dashboard') ?>">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Links de Evaluación Estudiantil</li>
                    </ol>
                </nav>
            </div>
        </div>

        <!-- Tarjetas de resumen -->
        <div class="row mb-3">
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center py-3">
                        <i class="bi bi-link-45deg fs-3 text-primary"></i>
                        <h5 class="mb-0 mt-1" id="statTotal">0</h5>
                        <small class="text-muted">Total Links</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center py-3">
                        <i class="bi bi-hourglass-split fs-3 text-warning"></i>
                        <h5 class="mb-0 mt-1" id="statPendientes">0</h5>
                        <small class="text-muted">Pendientes</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center py-3">
                        <i class="bi bi-check-circle fs-3 text-success"></i>
                        <h5 class="mb-0 mt-1" id="statUsados">0</h5>
                        <small class="text-muted">Completados</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center py-3">
                        <i class="bi bi-people fs-3 text-info"></i>
                        <h5 class="mb-0 mt-1" id="statDocentes">0</h5>
                        <small class="text-muted">Docentes Evaluados</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Formulario de Generación -->
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="bi bi-plus-circle me-2"></i>Generar Links de Evaluación</h5>
                    </div>
                    <div class="card-body">
                        <form id="formGenerar">
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <label for="id_evaluacion" class="form-label fw-bold">Evaluación (Periodo) <span class="text-danger">*</span></label>
                                    <select class="form-select" id="id_evaluacion" name="id_evaluacion" required>
                                        <option value="">Seleccionar...</option>
                                        <?php foreach ($evaluaciones as $ev): ?>
                                            <option value="<?= $ev['id_evaluacion'] ?>"><?= esc($ev['nombre']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="id_docente" class="form-label fw-bold">Docente a Evaluar <span class="text-danger">*</span></label>
                                    <select class="form-select" id="id_docente" name="id_docente" required>
                                        <option value="">Seleccionar...</option>
                                        <?php foreach ($docentes as $doc): ?>
                                            <option value="<?= $doc['id_empleado'] ?>"><?= esc($doc['nombres'] . ' ' . $doc['apellidos']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label for="grupo_curso" class="form-label fw-bold">Curso/Grupo <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="grupo_curso" name="grupo_curso" placeholder="Ej: 3ro Sistemas A" required>
                                </div>
                                <div class="col-md-2">
                                    <label for="cantidad" class="form-label fw-bold">N° de Links <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="cantidad" name="cantidad" min="1" max="100" value="30" required>
                                </div>
                                <div class="col-md-2">
                                    <label for="dias_vigencia" class="form-label fw-bold">Vigencia (días)</label>
                                    <input type="number" class="form-control" id="dias_vigencia" name="dias_vigencia" min="1" max="90" value="7">
                                </div>
                            </div>
                            <div class="mt-3">
                                <button type="button" class="btn btn-primary" onclick="generarLinks()">
                                    <i class="bi bi-lightning me-1"></i>Generar Links
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabla de Links Generados -->
        <div class="row mt-3">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="bi bi-table me-2"></i>Links Generados</h5>
                        <button class="btn btn-outline-primary btn-sm" onclick="cargarTokens()">
                            <i class="bi bi-arrow-clockwise me-1"></i>Actualizar
                        </button>
                    </div>
                    <div class="card-body">
                        <div id="contenedorTokens">
                            <div class="text-center py-4">
                                <div class="spinner-border spinner-border-sm text-primary me-2"></div>Cargando...
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
const BASE_URL = '<?= base_url('index.php/') ?>';
const SITE_URL = '<?= site_url() ?>';

document.addEventListener('DOMContentLoaded', function() {
    cargarTokens();
});

// ==================== GENERAR LINKS ====================
function generarLinks() {
    const form = document.getElementById('formGenerar');
    const fd = new FormData(form);

    if (!fd.get('id_evaluacion') || !fd.get('id_docente') || !fd.get('grupo_curso') || !fd.get('cantidad')) {
        Swal.fire({icon:'warning', title:'Campos Requeridos', text:'Completa todos los campos obligatorios.'});
        return;
    }

    Swal.fire({
        title: '¿Generar links?',
        html: `Se generarán <strong>${fd.get('cantidad')}</strong> links anónimos para el curso <strong>${fd.get('grupo_curso')}</strong>`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#206bc4',
        confirmButtonText: '<i class="bi bi-lightning"></i> Generar',
        cancelButtonText: 'Cancelar'
    }).then(result => {
        if (result.isConfirmed) {
            Swal.fire({title:'Generando...', allowOutsideClick:false, didOpen:()=>Swal.showLoading()});
            
            fetch(BASE_URL + 'admin-th/evaluaciones-estudiantiles/generar', { method: 'POST', body: fd })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({icon:'success', title:'¡Links Generados!', text: data.message, timer:3000, showConfirmButton:false});
                    cargarTokens();
                    form.reset();
                    document.getElementById('cantidad').value = '30';
                    document.getElementById('dias_vigencia').value = '7';
                } else {
                    Swal.fire({icon:'error', title:'Error', text: data.message});
                }
            })
            .catch(() => Swal.fire({icon:'error', title:'Error', text:'Error de conexión'}));
        }
    });
}

// ==================== CARGAR TOKENS (AGRUPADOS) ====================
function cargarTokens() {
    const contenedor = document.getElementById('contenedorTokens');
    contenedor.innerHTML = '<div class="text-center py-4"><div class="spinner-border spinner-border-sm text-primary me-2"></div>Cargando...</div>';

    fetch(BASE_URL + 'admin-th/evaluaciones-estudiantiles/obtener')
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            renderTokens(data.grupos);
            actualizarStats(data.grupos);
        } else {
            contenedor.innerHTML = `<div class="alert alert-danger">${data.message || 'Error al cargar'}</div>`;
        }
    })
    .catch(() => {
        contenedor.innerHTML = '<div class="alert alert-danger"><i class="bi bi-wifi-off me-2"></i>Error de conexión</div>';
    });
}

function renderTokens(grupos) {
    const contenedor = document.getElementById('contenedorTokens');

    if (!grupos || grupos.length === 0) {
        contenedor.innerHTML = '<div class="text-center text-muted py-4"><i class="bi bi-inbox fs-1 d-block mb-2"></i>No hay links generados aún. Usa el formulario de arriba para crear.</div>';
        return;
    }

    let html = '';

    grupos.forEach((grupo, gi) => {
        const porcUsado = grupo.total > 0 ? Math.round((grupo.usados / grupo.total) * 100) : 0;
        const progressColor = porcUsado >= 75 ? 'bg-success' : porcUsado >= 40 ? 'bg-warning' : 'bg-primary';

        html += `
        <div class="card border mb-3">
            <div class="card-header bg-light d-flex justify-content-between align-items-center py-2">
                <div>
                    <strong><i class="bi bi-person-badge me-1"></i>${esc(grupo.docente)}</strong>
                    <span class="badge bg-info ms-2">${esc(grupo.grupo_curso)}</span>
                    <span class="badge bg-secondary ms-1">${esc(grupo.evaluacion)}</span>
                </div>
                <div>
                    <span class="badge bg-success">${grupo.usados} usados</span>
                    <span class="badge bg-warning text-dark">${grupo.pendientes} pendientes</span>
                    <button class="btn btn-sm btn-outline-primary ms-2" onclick="toggleGrupo(${gi})">
                        <i class="bi bi-chevron-down" id="iconGrupo${gi}"></i>
                    </button>
                </div>
            </div>
            <div class="px-3 py-2">
                <div class="progress" style="height:6px">
                    <div class="progress-bar ${progressColor}" style="width:${porcUsado}%"></div>
                </div>
                <small class="text-muted">${porcUsado}% completado (${grupo.usados}/${grupo.total})</small>
            </div>
            <div class="collapse" id="grupoCollapse${gi}">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-sm table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Link</th>
                                    <th>Estado</th>
                                    <th>Expiración</th>
                                    <th class="text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>`;

        grupo.tokens.forEach((t, ti) => {
            const linkUrl = SITE_URL + 'evaluacion-estudiantil/' + t.token;
            const esUsado = t.usado == 1;
            const expirado = new Date(t.fecha_expiracion) < new Date();
            const estadoBadge = esUsado
                ? '<span class="badge bg-success"><i class="bi bi-check me-1"></i>Usado</span>'
                : expirado
                    ? '<span class="badge bg-danger"><i class="bi bi-clock me-1"></i>Expirado</span>'
                    : '<span class="badge bg-warning text-dark"><i class="bi bi-hourglass me-1"></i>Pendiente</span>';

            const docenteNombre = esc(grupo.docente);
            const whatsappMsg = encodeURIComponent(`Por favor, ayúdanos evaluando al docente ${grupo.docente} en el siguiente enlace:\n${linkUrl}`);

            html += `
                <tr>
                    <td>${ti + 1}</td>
                    <td><code class="small text-truncate d-inline-block" style="max-width:280px" title="${linkUrl}">${linkUrl}</code></td>
                    <td>${estadoBadge}</td>
                    <td><small>${formatFecha(t.fecha_expiracion)}</small></td>
                    <td class="text-center">
                        <div class="btn-group btn-group-sm">
                            <button class="btn btn-outline-primary" onclick="copiarLink('${linkUrl}')" title="Copiar Link" ${esUsado ? 'disabled' : ''}>
                                <i class="bi bi-clipboard"></i>
                            </button>
                            <a class="btn btn-outline-success" href="https://wa.me/?text=${whatsappMsg}" target="_blank" title="Compartir por WhatsApp" ${esUsado ? 'disabled' : ''}>
                                <i class="bi bi-whatsapp"></i>
                            </a>
                        </div>
                    </td>
                </tr>`;
        });

        html += `</tbody></table></div></div></div></div>`;
    });

    contenedor.innerHTML = html;
}

// ==================== ACCIONES ====================
function copiarLink(url) {
    navigator.clipboard.writeText(url).then(() => {
        Swal.fire({
            icon: 'success',
            title: 'Link copiado',
            text: 'El enlace ha sido copiado al portapapeles.',
            timer: 1500,
            showConfirmButton: false,
            toast: true,
            position: 'top-end'
        });
    }).catch(() => {
        // Fallback
        const input = document.createElement('input');
        input.value = url;
        document.body.appendChild(input);
        input.select();
        document.execCommand('copy');
        document.body.removeChild(input);
        Swal.fire({icon:'success', title:'Copiado', timer:1500, showConfirmButton:false, toast:true, position:'top-end'});
    });
}

function toggleGrupo(idx) {
    const col = document.getElementById('grupoCollapse' + idx);
    const icon = document.getElementById('iconGrupo' + idx);
    if (col.classList.contains('show')) {
        col.classList.remove('show');
        icon.className = 'bi bi-chevron-down';
    } else {
        col.classList.add('show');
        icon.className = 'bi bi-chevron-up';
    }
}

function actualizarStats(grupos) {
    let total = 0, usados = 0, pendientes = 0;
    const docentesSet = new Set();

    grupos.forEach(g => {
        total += g.total;
        usados += g.usados;
        pendientes += g.pendientes;
        docentesSet.add(g.id_docente);
    });

    document.getElementById('statTotal').textContent = total;
    document.getElementById('statPendientes').textContent = pendientes;
    document.getElementById('statUsados').textContent = usados;
    document.getElementById('statDocentes').textContent = docentesSet.size;
}

// ==================== HELPERS ====================
function formatFecha(f) {
    if (!f) return '—';
    const d = new Date(f);
    return d.toLocaleDateString('es-EC', { day: '2-digit', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit' });
}

function esc(str) {
    if (!str) return '';
    const el = document.createElement('span');
    el.textContent = str;
    return el.innerHTML;
}
</script>
<?= $this->endSection() ?>
