<?= $this->extend('layouts/base') ?>

<?= $this->section('title') ?>Solicitudes Generales<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="page-wrapper">
    <div class="page-content">

        <!-- Header -->
        <div class="row mb-3">
            <div class="col-8">
                <h4 class="mb-0">
                    <i class="bi bi-file-earmark-text text-primary me-2"></i>Solicitudes Generales
                </h4>
                <p class="text-muted mb-0">Gestiona las solicitudes administrativas del personal.</p>
            </div>
        </div>

        <!-- Flash messages -->
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <i class="bi bi-check-circle me-2"></i><?= session()->getFlashdata('success') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- Stats -->
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
                <h6 class="mb-0 fw-semibold">Listado de Solicitudes</h6>
                <input type="text" id="buscador" class="form-control form-control-sm w-auto"
                       placeholder="🔍 Buscar..." style="min-width:220px;">
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th>Empleado</th>
                                <th>Departamento</th>
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
                                        $badgeMap = [
                                            'Pendiente'   => 'bg-warning text-dark',
                                            'En revisión' => 'bg-info',
                                            'Aprobada'    => 'bg-success',
                                            'Rechazada'   => 'bg-danger',
                                            'Cancelada'   => 'bg-secondary',
                                        ];
                                        $badge = $badgeMap[$sol['estado']] ?? 'bg-secondary';
                                        $gestionable = in_array($sol['estado'], ['Pendiente', 'En revisión']);
                                    ?>
                                    <tr id="fila-<?= $sol['id_solicitud'] ?>">
                                        <td>
                                            <span class="fw-semibold">
                                                <?= esc($sol['apellidos'] . ' ' . $sol['nombres']) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark border">
                                                <?= esc($sol['departamento'] ?? 'N/A') ?>
                                            </span>
                                        </td>
                                        <td>
                                            <small class="text-muted"><?= esc($sol['tipo_solicitud']) ?></small>
                                        </td>
                                        <td><?= esc($sol['titulo']) ?></td>
                                        <td>
                                            <?= date('d/m/Y', strtotime($sol['fecha_solicitud'])) ?>
                                        </td>
                                        <td>
                                            <span class="badge <?= $badge ?>">
                                                <?= $sol['estado'] ?>
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group" role="group">
                                                <!-- Ver detalle -->
                                                <button type="button"
                                                        class="btn btn-sm btn-outline-primary"
                                                        onclick="verDetalle(<?= $sol['id_solicitud'] ?>)"
                                                        title="Ver detalle">
                                                    <i class="bi bi-eye"></i>
                                                </button>
                                                <!-- Gestionar (solo si está pendiente o en revisión) -->
                                                <?php if ($gestionable): ?>
                                                <button type="button"
                                                        class="btn btn-sm btn-outline-dark"
                                                        onclick="gestionarSolicitud(
                                                            <?= $sol['id_solicitud'] ?>,
                                                            '<?= esc($sol['apellidos'] . ' ' . $sol['nombres'], 'js') ?>',
                                                            '<?= esc($sol['titulo'], 'js') ?>'
                                                        )"
                                                        title="Resolver solicitud">
                                                    <i class="bi bi-pencil-square"></i>
                                                </button>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7" class="text-center py-5 text-muted">
                                        <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                        No hay solicitudes generales registradas.
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

<!-- Modal Gestionar -->
<div class="modal fade" id="modalGestionar" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form id="formGestionar" class="modal-content border-0 shadow">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title">
                    <i class="bi bi-gear-fill me-2"></i>Resolver Solicitud
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <p class="mb-3">
                    <strong>Empleado:</strong> <span id="gEmpleado" class="text-primary"></span><br>
                    <strong>Asunto:</strong> <span id="gAsunto" class="text-muted"></span>
                </p>
                <div class="mb-3">
                    <label class="form-label fw-bold">Resolución <span class="text-danger">*</span></label>
                    <div class="d-flex gap-3 flex-wrap">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="estado"
                                   id="rAprobada" value="Aprobada" required>
                            <label class="form-check-label text-success fw-bold" for="rAprobada">
                                Aprobar
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="estado"
                                   id="rRevision" value="En revisión">
                            <label class="form-check-label text-info fw-bold" for="rRevision">
                                En revisión
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="estado"
                                   id="rRechazada" value="Rechazada">
                            <label class="form-check-label text-danger fw-bold" for="rRechazada">
                                Rechazar
                            </label>
                        </div>
                    </div>
                </div>
                <div class="mb-0">
                    <label class="form-label fw-bold">Comentarios / Respuesta</label>
                    <textarea class="form-control" name="comentarios" rows="3"
                              placeholder="Escribe una respuesta o comentario para el empleado..."></textarea>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-dark" id="btnResolver">
                    <i class="bi bi-save me-1"></i>Guardar Resolución
                </button>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
let _solicitudId = null;

// ── Buscador ──────────────────────────────────────────────────────────────────
document.getElementById('buscador').addEventListener('keyup', function () {
    const t = this.value.toLowerCase();
    document.querySelectorAll('#tbodySolicitudes tr').forEach(function (fila) {
        fila.style.display = fila.textContent.toLowerCase().includes(t) ? '' : 'none';
    });
});

// ── Ver detalle ───────────────────────────────────────────────────────────────
function verDetalle(id) {
    Swal.fire({
        title: 'Cargando...',
        allowOutsideClick: false,
        didOpen: () => Swal.showLoading()
    });

    fetch(`<?= site_url('admin-th/solicitudes/generales/detalle/') ?>${id}`, {
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
                    <p class="mb-2"><strong>Empleado:</strong>
                        ${s.apellidos ?? ''} ${s.nombres ?? ''}
                        <span class="badge bg-light text-dark border ms-1">${s.departamento ?? 'N/A'}</span>
                    </p>
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
                    <p class="mb-1"><strong>Descripción del empleado:</strong></p>
                    <div class="alert alert-light py-2">${s.motivo_descripcion ?? '—'}</div>
                    ${s.comentarios_resolucion
                        ? `<p class="mb-1"><strong>Respuesta de TH:</strong></p>
                           <div class="alert alert-info py-2">${s.comentarios_resolucion}</div>`
                        : ''}
                </div>`,
            width: '560px',
            confirmButtonText: 'Cerrar',
            confirmButtonColor: '#6c757d'
        });
    })
    .catch(() => Swal.fire('Error', 'Problema de conexión.', 'error'));
}

// ── Abrir modal gestionar ─────────────────────────────────────────────────────
function gestionarSolicitud(id, empleado, asunto) {
    _solicitudId = id;
    document.getElementById('gEmpleado').textContent = empleado;
    document.getElementById('gAsunto').textContent   = asunto;
    document.querySelectorAll('input[name="estado"]').forEach(r => r.checked = false);
    document.querySelector('textarea[name="comentarios"]').value = '';
    new bootstrap.Modal(document.getElementById('modalGestionar')).show();
}

// ── Enviar resolución ─────────────────────────────────────────────────────────
document.getElementById('formGestionar').addEventListener('submit', function (e) {
    e.preventDefault();

    const estado = document.querySelector('input[name="estado"]:checked')?.value;
    if (!estado) {
        Swal.fire('Atención', 'Debes seleccionar una resolución.', 'warning');
        return;
    }

    const btn  = document.getElementById('btnResolver');
    const html = btn.innerHTML;
    btn.disabled  = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Guardando...';

    bootstrap.Modal.getInstance(document.getElementById('modalGestionar')).hide();

    fetch(`<?= site_url('admin-th/solicitudes/resolver-general/') ?>${_solicitudId}`, {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        body: new FormData(this)
    })
    .then(r => r.json())
    .then(data => {
        btn.disabled  = false;
        btn.innerHTML = html;

        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: '¡Resuelto!',
                text: data.message,
                timer: 2000,
                showConfirmButton: false
            }).then(() => window.location.reload());
        } else {
            Swal.fire('Error', data.message, 'error');
        }
    })
    .catch(() => {
        btn.disabled  = false;
        btn.innerHTML = html;
        Swal.fire('Error', 'Problema de conexión.', 'error');
    });
});
</script>
<?= $this->endSection() ?>
