<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>

<div class="page-wrapper">
    <div class="page-content">

        <!-- Header -->
        <div class="row mb-3">
            <div class="col-8">
                <h4 class="mb-0"><i class="bi bi-file-earmark-text text-primary me-2"></i>Mis Solicitudes Generales</h4>
                <p class="text-muted mb-0">Gestiona tus solicitudes administrativas.</p>
            </div>
            <div class="col-4 text-end">
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalNuevaSolicitud">
                    <i class="bi bi-plus-circle me-1"></i>Nueva Solicitud
                </button>
            </div>
        </div>

        <!-- Stats reales -->
        <div class="row mb-4">
            <div class="col-md-3 col-6">
                <div class="card border-0 shadow-sm text-center py-3">
                    <h2 class="mb-0 fw-bold"><?= $stats['total'] ?></h2>
                    <small class="text-muted">Total</small>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="card border-0 shadow-sm text-center py-3">
                    <h2 class="mb-0 fw-bold text-warning"><?= $stats['pendientes'] ?></h2>
                    <small class="text-muted">Pendientes</small>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="card border-0 shadow-sm text-center py-3">
                    <h2 class="mb-0 fw-bold text-success"><?= $stats['aprobadas'] ?></h2>
                    <small class="text-muted">Aprobadas</small>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="card border-0 shadow-sm text-center py-3">
                    <h2 class="mb-0 fw-bold text-danger"><?= $stats['rechazadas'] ?></h2>
                    <small class="text-muted">Rechazadas</small>
                </div>
            </div>
        </div>

        <!-- Tabla -->
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-semibold">Historial de Solicitudes</h6>
                <input type="text" id="buscador" class="form-control form-control-sm w-auto"
                       placeholder="🔍 Buscar..." style="min-width:200px;">
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th>Tipo</th>
                                <th>Asunto</th>
                                <th>Fecha</th>
                                <th>Estado</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="tbodySolicitudes">
                            <?php if (!empty($solicitudes)): ?>
                                <?php foreach ($solicitudes as $sol): ?>
                                    <?php
                                        $badgeEstado = [
                                            'Pendiente'   => 'bg-warning text-dark',
                                            'En revisión' => 'bg-info',
                                            'Aprobada'    => 'bg-success',
                                            'Rechazada'   => 'bg-danger',
                                            'Cancelada'   => 'bg-secondary',
                                        ][$sol['estado']] ?? 'bg-secondary';
                                        $esPendiente = $sol['estado'] === 'Pendiente';
                                    ?>
                                    <tr id="fila-sol-<?= $sol['id_solicitud'] ?>">
                                        <td>
                                            <span class="badge bg-light text-dark border">
                                                <?= esc($sol['tipo_solicitud']) ?>
                                            </span>
                                        </td>
                                        <td><?= esc($sol['titulo']) ?></td>
                                        <td><?= date('d/m/Y', strtotime($sol['fecha_solicitud'])) ?></td>
                                        <td>
                                            <span class="badge <?= $badgeEstado ?>">
                                                <?= $sol['estado'] ?>
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group" role="group">
                                                <button type="button"
                                                        class="btn btn-sm btn-outline-primary"
                                                        onclick="verDetalle(<?= $sol['id_solicitud'] ?>)"
                                                        title="Ver detalles">
                                                    <i class="bi bi-eye"></i>
                                                </button>
                                                <?php if ($esPendiente): ?>
                                                <button type="button"
                                                        class="btn btn-sm btn-outline-danger"
                                                        onclick="eliminarSolicitud(<?= $sol['id_solicitud'] ?>, '<?= esc($sol['titulo'], 'js') ?>')"
                                                        title="Eliminar">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="text-center py-5 text-muted">
                                        <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                        No tienes solicitudes registradas aún.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- Modal Nueva Solicitud -->
<div class="modal fade" id="modalNuevaSolicitud" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form id="formNuevaSolicitud" class="modal-content border-0 shadow">
            <?= csrf_field() ?>
            <input type="hidden" name="empleado_id" value="<?= esc($empleado_id) ?>">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="bi bi-file-earmark-plus me-2"></i>Nueva Solicitud General</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="mb-3">
                    <label class="form-label fw-bold">Tipo de Solicitud <span class="text-danger">*</span></label>
                    <select class="form-select" name="tipo_solicitud" required>
                        <option value="">Seleccionar tipo...</option>
                        <option value="Certificación Laboral">Certificación Laboral</option>
                        <option value="Constancia de Trabajo">Constancia de Trabajo</option>
                        <option value="Carta de Recomendación">Carta de Recomendación</option>
                        <option value="Cambio de Horario">Cambio de Horario</option>
                        <option value="Cambio de Sede">Cambio de Sede</option>
                        <option value="Revisión Salarial">Revisión Salarial</option>
                        <option value="Reconocimiento">Reconocimiento</option>
                        <option value="Otros">Otros</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Asunto <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="asunto" required
                           placeholder="Ej: Certificación laboral para trámite bancario">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Descripción <span class="text-danger">*</span></label>
                    <textarea class="form-control" name="descripcion" rows="4" required
                              placeholder="Describe detalladamente tu solicitud..."></textarea>
                </div>
                <div class="alert alert-info py-2 mb-0">
                    <i class="bi bi-info-circle me-1"></i>
                    Tu solicitud será revisada por el departamento de Talento Humano.
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary" id="btnEnviar">
                    <i class="bi bi-send me-1"></i>Enviar Solicitud
                </button>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {

    // ── Buscador nativo ───────────────────────────────────────────────────────
    document.getElementById('buscador').addEventListener('keyup', function () {
        const t = this.value.toLowerCase();
        document.querySelectorAll('#tbodySolicitudes tr').forEach(function (fila) {
            fila.style.display = fila.textContent.toLowerCase().includes(t) ? '' : 'none';
        });
    });

    // ── Envío del formulario vía AJAX ─────────────────────────────────────────
    document.getElementById('formNuevaSolicitud').addEventListener('submit', function (e) {
        e.preventDefault();

        const btn  = document.getElementById('btnEnviar');
        const html = btn.innerHTML;
        btn.disabled  = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Enviando...';

        fetch('<?= site_url('empleado/solicitudes-generales/guardar') ?>', {
            method: 'POST',
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            body: new FormData(this)
        })
        .then(r => r.json())
        .then(data => {
            btn.disabled  = false;
            btn.innerHTML = html;

            if (data.success) {
                bootstrap.Modal.getInstance(
                    document.getElementById('modalNuevaSolicitud')
                ).hide();
                this.reset();

                Swal.fire({
                    icon: 'success',
                    title: '¡Solicitud enviada!',
                    text: data.message,
                    timer: 2500,
                    showConfirmButton: false
                }).then(() => window.location.reload());
            } else {
                Swal.fire('Error', data.message, 'error');
            }
        })
        .catch(() => {
            btn.disabled  = false;
            btn.innerHTML = html;
            Swal.fire('Error', 'Problema de conexión con el servidor.', 'error');
        });
    });
});

// ── Ver detalle ───────────────────────────────────────────────────────────────
function verDetalle(id) {
    Swal.fire({
        title: 'Cargando...',
        allowOutsideClick: false,
        didOpen: () => Swal.showLoading()
    });

    fetch(`<?= site_url('empleado/solicitudes-generales/detalle/') ?>${id}`, {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(r => r.json())
    .then(res => {
        if (!res.success) { Swal.fire('Error', res.message, 'error'); return; }

        const s = res.data;
        const badgeMap = {
            'Pendiente':   'warning',
            'En revisión': 'info',
            'Aprobada':    'success',
            'Rechazada':   'danger',
            'Cancelada':   'secondary'
        };
        const color = badgeMap[s.estado] ?? 'secondary';

        Swal.fire({
            title: `<i class="bi bi-file-earmark-text me-2"></i>${s.titulo}`,
            html: `
                <div class="text-start">
                    <p class="mb-2"><strong>Tipo:</strong>
                        <span class="badge bg-light text-dark border ms-1">${s.tipo_solicitud}</span>
                    </p>
                    <p class="mb-2"><strong>Estado:</strong>
                        <span class="badge bg-${color} ms-1">${s.estado}</span>
                    </p>
                    <p class="mb-2"><strong>Fecha:</strong>
                        ${new Date(s.fecha_solicitud).toLocaleDateString('es-EC')}
                    </p>
                    <hr>
                    <p class="mb-1"><strong>Descripción:</strong></p>
                    <div class="alert alert-light py-2">${s.motivo_descripcion ?? '—'}</div>
                    ${s.comentarios_resolucion
                        ? `<p class="mb-1"><strong>Respuesta de TH:</strong></p>
                           <div class="alert alert-info py-2">${s.comentarios_resolucion}</div>`
                        : ''}
                </div>`,
            width: '550px',
            confirmButtonText: 'Cerrar',
            confirmButtonColor: '#6c757d'
        });
    })
    .catch(() => Swal.fire('Error', 'Problema de conexión.', 'error'));
}

// ── Eliminar ──────────────────────────────────────────────────────────────────
function eliminarSolicitud(id, asunto) {
    Swal.fire({
        title: '¿Eliminar solicitud?',
        html: `Se eliminará: <strong>${asunto}</strong>`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: '<i class="bi bi-trash me-1"></i>Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then(result => {
        if (!result.isConfirmed) return;

        Swal.fire({ title: 'Eliminando...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });

        fetch(`<?= site_url('empleado/solicitudes-generales/eliminar/') ?>${id}`, {
            method: 'DELETE',
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                const fila = document.getElementById(`fila-sol-${id}`);
                if (fila) fila.remove();
                Swal.fire({ icon: 'success', title: '¡Eliminada!', text: data.message, timer: 2000, showConfirmButton: false });
            } else {
                Swal.fire('Error', data.message, 'error');
            }
        })
        .catch(() => Swal.fire('Error', 'Problema de conexión.', 'error'));
    });
}
</script>
<?= $this->endSection() ?>
