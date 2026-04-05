<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>

<div class="page-wrapper">
    <div class="page-content">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between mb-3">
                    <div class="d-flex align-items-center">
                        <a href="<?= base_url('empleado/inasistencias') ?>" class="btn btn-outline-secondary btn-sm me-3" title="Volver al Dashboard">
                            <i class="ti ti-arrow-left"></i> Volver
                        </a>
                        <h4 class="page-title mb-0">Mis Inasistencias</h4>
                    </div>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="<?= base_url('empleado/dashboard') ?>">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="<?= base_url('empleado/inasistencias') ?>">Inasistencias</a></li>
                            <li class="breadcrumb-item active">Mis Inasistencias</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <!-- Estadísticas Rápidas -->
        <div class="row">
            <div class="col-xl-3 col-md-6">
                <div class="card mini-stats-wid">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-grow-1">
                                <p class="text-muted fw-medium">Total Inasistencias</p>
                                <h4 class="mb-0" id="stat_total">0</h4>
                            </div>
                            <div class="flex-shrink-0 align-self-center">
                                <div class="mini-stat-icon avatar-sm rounded-circle bg-primary">
                                    <span class="avatar-title">
                                        <i class="ti ti-calendar-x fs-4"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-md-4">
                <div class="card mini-stats-wid">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-grow-1">
                                <p class="text-muted fw-medium">Justificadas</p>
                                <h4 class="mb-0 text-success" id="stat_justificadas">0</h4>
                            </div>
                            <div class="flex-shrink-0 align-self-center">
                                <div class="mini-stat-icon avatar-sm rounded-circle bg-success">
                                    <span class="avatar-title">
                                        <i class="ti ti-check fs-4"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-md-4">
                <div class="card mini-stats-wid">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-grow-1">
                                <p class="text-muted fw-medium">Pendientes (Sin justificar)</p>
                                <h4 class="mb-0 text-warning" id="stat_pendientes">0</h4>
                            </div>
                            <div class="flex-shrink-0 align-self-center">
                                <div class="mini-stat-icon avatar-sm rounded-circle bg-warning">
                                    <span class="avatar-title">
                                        <i class="ti ti-clock fs-4"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filtros -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <label for="fecha_desde" class="form-label">Fecha Desde</label>
                                <input type="date" class="form-control" id="fecha_desde" name="fecha_desde">
                            </div>
                            <div class="col-md-3">
                                <label for="fecha_hasta" class="form-label">Fecha Hasta</label>
                                <input type="date" class="form-control" id="fecha_hasta" name="fecha_hasta">
                            </div>
                            <div class="col-md-3">
                                <label for="tipo_inasistencia" class="form-label">Tipo</label>
                                <select class="form-select" id="tipo_inasistencia" name="tipo_inasistencia">
                                    <option value="">Todos los tipos</option>
                                    <?php if (isset($tiposInasistencia)): ?>
                                        <?php foreach ($tiposInasistencia as $tipo): ?>
                                            <option value="<?= $tipo['id'] ?>"><?= $tipo['nombre'] ?></option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="estado" class="form-label">Estado</label>
                                <select class="form-select" id="estado" name="estado">
                                    <option value="">Todos los estados</option>
                                    <option value="PENDIENTE">Pendiente</option>
                                    <option value="JUSTIFICADA">Justificada</option>
                                    <option value="SIN_JUSTIFICAR">Sin Justificar</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <button type="button" class="btn btn-primary" id="btnFiltrar">
                                    <i class="bi bi-search"></i> Filtrar
                                </button>
                                <button type="button" class="btn btn-secondary" id="btnLimpiar">
                                    <i class="bi bi-arrow-clockwise"></i> Limpiar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Lista de Inasistencias -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Historial de Inasistencias</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-centered table-nowrap mb-0" id="tablaInasistencias">
                                <thead>
                                    <tr>
                                        <th>Fecha</th>
                                        <th>Tipo</th>
                                        <th>Motivo</th>
                                        <th>Estado</th>
                                        <th>Justificación</th>
                                    </tr>
                                </thead>
                                <tbody id="tablaInasistenciasBody">
                                    <tr>
                                        <td colspan="5" class="text-center text-muted">
                                            <div class="spinner-border spinner-border-sm text-primary me-2" role="status"></div>
                                            Cargando inasistencias...
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Ver Justificación -->
<div class="modal fade" id="modalJustificacion" tabindex="-1" aria-labelledby="modalJustificacionLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalJustificacionLabel">Justificación de Inasistencia</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="contenidoJustificacion"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Subir Justificación -->
<div class="modal fade" id="modalSubirJustificacion" tabindex="-1" aria-labelledby="modalSubirJustificacionLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalSubirJustificacionLabel">Subir Justificación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formJustificacion" enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="hidden" id="inasistencia_id" name="inasistencia_id">
                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripción de la Justificación</label>
                        <textarea class="form-control" id="descripcion" name="descripcion" rows="4" 
                                  placeholder="Describe el motivo de tu inasistencia..." required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="documento" class="form-label">Documento de Justificación</label>
                        <input type="file" class="form-control" id="documento" name="documento" 
                               accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" required>
                        <small class="form-text text-muted">
                            Formatos permitidos: PDF, DOC, DOCX, JPG, JPEG, PNG. Tamaño máximo: 5MB
                        </small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-upload"></i> Subir Justificación
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    cargarInasistencias();

    // Filtros
    document.getElementById('btnFiltrar').addEventListener('click', function() {
        cargarInasistencias();
    });

    document.getElementById('btnLimpiar').addEventListener('click', function() {
        limpiarFiltros();
    });

    // Modal de justificación
    document.getElementById('modalJustificacion').addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        const justificacion = button.getAttribute('data-justificacion');
        document.getElementById('contenidoJustificacion').innerHTML = justificacion;
    });

    // Modal de subir justificación
    document.getElementById('modalSubirJustificacion').addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        const inasistenciaId = button.getAttribute('data-inasistencia-id');
        document.getElementById('inasistencia_id').value = inasistenciaId;
    });

    // Formulario de justificación
    document.getElementById('formJustificacion').addEventListener('submit', function(e) {
        e.preventDefault();
        subirJustificacion();
    });
});

function cargarInasistencias() {
    const tbody = document.getElementById('tablaInasistenciasBody');
    tbody.innerHTML = `<tr><td colspan="5" class="text-center py-4"><div class="spinner-border text-primary me-2"></div>Cargando...</td></tr>`;

    const fechaDesde = document.getElementById('fecha_desde').value;
    const fechaHasta = document.getElementById('fecha_hasta').value;
    const tipo = document.getElementById('tipo_inasistencia').value;
    const estado = document.getElementById('estado').value;

    const params = new URLSearchParams();
    if (fechaDesde) params.append('fecha_desde', fechaDesde);
    if (fechaHasta) params.append('fecha_hasta', fechaHasta);
    if (tipo) params.append('tipo', tipo);
    if (estado) params.append('estado', estado);

    fetch(`<?= base_url('empleado/inasistencias/obtener-mis-inasistencias') ?>?${params.toString()}`)
    .then(response => response.json())
    .then(data => {
        if (!data.success) {
            Swal.fire({icon: 'error', title: 'Error', text: data.message});
            tbody.innerHTML = `<tr><td colspan="5" class="text-center py-3 text-danger">Error al cargar datos</td></tr>`;
            return;
        }

        // Actualizar estadísticas
        document.getElementById('stat_total').textContent = data.total || 0;
        document.getElementById('stat_justificadas').textContent = data.justificadas || 0;
        document.getElementById('stat_pendientes').textContent = data.pendientes || 0;

        renderizarTabla(data.data);
    })
    .catch(error => {
        console.error('Error:', error);
        tbody.innerHTML = `<tr><td colspan="5" class="text-center py-3 text-danger">Error de conexión</td></tr>`;
    });
}

function renderizarTabla(inasistencias) {
    const tbody = document.getElementById('tablaInasistenciasBody');
    
    if (!inasistencias || inasistencias.length === 0) {
        tbody.innerHTML = `<tr><td colspan="5" class="text-center py-4 text-muted"><i class="ti ti-inbox fs-1 mb-2 d-block"></i>No tienes inasistencias registradas.</td></tr>`;
        return;
    }

    let html = '';
    inasistencias.forEach(i => {
        const isJustificada = parseInt(i.justificada) === 1;
        const estadoBadge = isJustificada 
            ? `<span class="badge bg-success"><i class="ti ti-check me-1"></i>Justificada</span>` 
            : `<span class="badge bg-warning"><i class="ti ti-clock me-1"></i>Pendiente</span>`;
            
        const fechaFormat = new Date(i.fecha_inasistencia).toLocaleDateString('es-EC', {day: '2-digit', month: 'short', year: 'numeric'});
        
        let justificacionContent = '';
        if (isJustificada && i.archivo_justificacion) {
             justificacionContent = `
                <button type="button" class="btn btn-sm btn-outline-info" 
                        data-bs-toggle="modal" 
                        data-bs-target="#modalJustificacion" 
                        data-justificacion="${htmlEscape(i.archivo_justificacion)}">
                    <i class="ti ti-eye"></i> Ver
                </button>`;
        } else if (!isJustificada) {
             justificacionContent = `<span class="text-muted small">Falta evidencia</span>`;
        } else {
             justificacionContent = `<span class="text-muted small">—</span>`;
        }

        html += `
            <tr>
                <td><strong>${fechaFormat}</strong></td>
                <td><span class="badge bg-secondary">${htmlEscape(i.tipo_inasistencia || '—')}</span></td>
                <td><small class="text-truncate d-inline-block" style="max-width:200px;" title="${htmlEscape(i.motivo)}">${htmlEscape(i.motivo)}</small></td>
                <td>${estadoBadge}</td>
                <td>${justificacionContent}</td>
            </tr>
        `;
    });

    tbody.innerHTML = html;
}

function limpiarFiltros() {
    document.getElementById('fecha_desde').value = '';
    document.getElementById('fecha_hasta').value = '';
    document.getElementById('tipo_inasistencia').value = '';
    document.getElementById('estado').value = '';
    cargarInasistencias();
}

function subirJustificacion() {
    const formData = new FormData(document.getElementById('formJustificacion'));
    
    fetch('<?= base_url('empleado/inasistencias/subir-justificacion') ?>', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            bootstrap.Modal.getInstance(document.getElementById('modalSubirJustificacion')).hide();
            Swal.fire({icon: 'success', title: 'Guardado', text: 'Justificación enviada exitosamente', timer: 2000, showConfirmButton: false});
            cargarInasistencias();
        } else {
            Swal.fire({icon: 'error', title: 'Error', text: data.message || 'Error al enviar'});
        }
    })
    .catch(error => {
        Swal.fire({icon: 'error', title: 'Error', text: 'Error de conexión'});
    });
}

function htmlEscape(str) {
    if (!str) return '';
    return String(str).replace(/[&<>'"]/g, match => {
        return {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            "'": '&#39;',
            '"': '&quot;'
        }[match];
    });
}
</script>

<?= $this->endSection() ?>
