<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>

<div class="page-wrapper">
    <div class="page-content">

        <!-- Header -->
        <div class="row mb-3">
            <div class="col-8">
                <h4 class="mb-0"><i class="bi bi-file-earmark-text text-primary me-2"></i>Mis Documentos</h4>
                <p class="text-muted mb-0">Gestiona y consulta tus documentos personales.</p>
            </div>
            <div class="col-4 text-end">
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalSubirDocumento">
                    <i class="bi bi-upload me-1"></i>Subir Documento
                </button>
            </div>
        </div>

        <!-- Flash messages -->
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <i class="bi bi-check-circle me-2"></i><?= session()->getFlashdata('success') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="bi bi-exclamation-triangle me-2"></i><?= session()->getFlashdata('error') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- Stats -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm text-center py-3">
                    <h2 class="mb-0 text-primary fw-bold"><?= $stats['total'] ?></h2>
                    <small class="text-muted">Total documentos</small>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm text-center py-3">
                    <h2 class="mb-0 text-success fw-bold"><?= $stats['vigentes'] ?></h2>
                    <small class="text-muted">Vigentes</small>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm text-center py-3">
                    <h2 class="mb-0 text-danger fw-bold"><?= $stats['vencidos'] ?></h2>
                    <small class="text-muted">Vencidos</small>
                </div>
            </div>
        </div>

        <!-- Tabla de documentos -->
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-semibold">Lista de Documentos</h6>
                <input type="text" id="buscadorDocs" class="form-control form-control-sm w-auto"
                       placeholder="🔍 Buscar..." style="min-width:200px;">
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="tablaDocumentos">
                        <thead class="table-dark">
                            <tr>
                                <th>Nombre</th>
                                <th>Tipo</th>
                                <th>Descripción</th>
                                <th>Emisión</th>
                                <th>Vencimiento</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="tbodyDocumentos">
                            <?php if (!empty($documentos)): ?>
                                <?php foreach ($documentos as $doc): ?>
                                    <?php
                                        $hoy      = date('Y-m-d');
                                        $vencido  = $doc['fecha_vencimiento'] && $doc['fecha_vencimiento'] < $hoy;
                                        $ext      = strtolower(pathinfo($doc['archivo_url'], PATHINFO_EXTENSION));
                                        $iconos   = [
                                            'pdf'  => 'bi-file-pdf text-danger',
                                            'doc'  => 'bi-file-word text-primary',
                                            'docx' => 'bi-file-word text-primary',
                                            'jpg'  => 'bi-file-image text-warning',
                                            'jpeg' => 'bi-file-image text-warning',
                                            'png'  => 'bi-file-image text-warning',
                                        ];
                                        $icono = $iconos[$ext] ?? 'bi-file-earmark text-secondary';
                                    ?>
                                    <tr id="fila-doc-<?= $doc['id_documento'] ?>">
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <i class="bi <?= $icono ?> fs-4 me-2"></i>
                                                <div>
                                                    <span class="fw-semibold"><?= esc($doc['nombre']) ?></span><br>
                                                    <small class="text-muted"><?= esc(basename($doc['archivo_url'])) ?></small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary"><?= esc($doc['tipo_documento']) ?></span>
                                        </td>
                                        <td>
                                            <small><?= esc($doc['descripcion'] ?? '—') ?></small>
                                        </td>
                                        <td>
                                            <?= $doc['fecha_emision'] ? date('d/m/Y', strtotime($doc['fecha_emision'])) : '—' ?>
                                        </td>
                                        <td>
                                            <?php if ($doc['fecha_vencimiento']): ?>
                                                <span class="badge <?= $vencido ? 'bg-danger' : 'bg-success' ?>">
                                                    <?= date('d/m/Y', strtotime($doc['fecha_vencimiento'])) ?>
                                                </span>
                                            <?php else: ?>
                                                <span class="text-muted">Sin vencimiento</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group" role="group">
                                                <!-- Ver -->
                                                <a href="<?= esc($doc['archivo_url']) ?>"
                                                   target="_blank"
                                                   class="btn btn-sm btn-outline-primary"
                                                   data-bs-toggle="tooltip" title="Ver documento">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <!-- Descargar -->
                                                <a href="<?= site_url('empleado/documentos/descargar/' . $doc['id_documento']) ?>"
                                                   class="btn btn-sm btn-outline-success"
                                                   data-bs-toggle="tooltip" title="Descargar">
                                                    <i class="bi bi-download"></i>
                                                </a>
                                                <!-- Eliminar -->
                                                <button type="button"
                                                        class="btn btn-sm btn-outline-danger"
                                                        onclick="eliminarDocumento(<?= $doc['id_documento'] ?>, '<?= esc($doc['nombre'], 'js') ?>')"
                                                        data-bs-toggle="tooltip" title="Eliminar">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-muted">
                                        <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                        No tienes documentos registrados aún.
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

<!-- Modal Subir Documento -->
<div class="modal fade" id="modalSubirDocumento" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form id="formSubirDocumento" action="<?= site_url('empleado/documentos/subir') ?>" method="POST" enctype="multipart/form-data" class="modal-content border-0 shadow">
            <?= csrf_field() ?>
            <input type="hidden" name="empleado_id" value="<?= esc($empleado_id) ?>">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="bi bi-upload me-2"></i>Subir Nuevo Documento</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="mb-3">
                    <label class="form-label fw-bold">Nombre <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="nombre" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Tipo de documento <span class="text-danger">*</span></label>
                    <select class="form-select" name="tipo_documento" required>
                        <option value="">Seleccionar...</option>
                        <option value="Personal">Personal</option>
                        <option value="Académico">Académico</option>
                        <option value="Certificado">Certificado</option>
                        <option value="Contrato">Contrato</option>
                        <option value="Otro">Otro</option>
                    </select>
                </div>
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Fecha de emisión</label>
                        <input type="date" class="form-control" name="fecha_emision">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Fecha de vencimiento</label>
                        <input type="date" class="form-control" name="fecha_vencimiento">
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Descripción</label>
                    <textarea class="form-control" name="descripcion" rows="2"></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Archivo <span class="text-danger">*</span></label>
                    <input type="file" class="form-control" name="archivo" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" required>
                    <small class="text-muted">PDF, DOC, DOCX, JPG, PNG — máx. 5 MB</small>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary"><i class="bi bi-upload me-1"></i>Subir</button>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {

    // ── Buscador nativo (sin DataTables) ─────────────────────────────────────
    document.getElementById('buscadorDocs').addEventListener('keyup', function () {
        const termino = this.value.toLowerCase();
        document.querySelectorAll('#tbodyDocumentos tr').forEach(function (fila) {
            fila.style.display = fila.textContent.toLowerCase().includes(termino) ? '' : 'none';
        });
    });

    // ── Simulación de subida (sin backend ni Drive configurado) ───────────────
    document.getElementById('formSubirDocumento').addEventListener('submit', function (e) {
        e.preventDefault();

        const modal = bootstrap.Modal.getInstance(document.getElementById('modalSubirDocumento'));
        if (modal) modal.hide();

        this.reset();

        Swal.fire({
            icon: 'info',
            title: 'Proceso Simulado',
            text: 'Documento guardado localmente por funciones prácticas del sistema, al no tener aún el Drive asignado.',
            confirmButtonColor: '#0d6efd',
            confirmButtonText: 'Entendido'
        });
    });

    // ── Tooltips Bootstrap ────────────────────────────────────────────────────
    document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(function (el) {
        new bootstrap.Tooltip(el);
    });
});

function eliminarDocumento(id, nombre) {
    Swal.fire({
        title: '¿Eliminar documento?',
        html: `El documento <strong>${nombre}</strong> será eliminado permanentemente.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: '<i class="bi bi-trash me-1"></i>Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then(result => {
        if (!result.isConfirmed) return;

        Swal.fire({ title: 'Eliminando...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });

        fetch(`<?= site_url('empleado/documentos/eliminar/') ?>${id}`, {
            method: 'DELETE',
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                const fila = document.getElementById(`fila-doc-${id}`);
                if (fila) fila.remove();

                Swal.fire({
                    icon: 'success',
                    title: '¡Eliminado!',
                    text: data.message,
                    timer: 2000,
                    showConfirmButton: false
                });
            } else {
                Swal.fire('Error', data.message, 'error');
            }
        })
        .catch(() => Swal.fire('Error', 'Problema de conexión con el servidor.', 'error'));
    });
}
</script>
<?= $this->endSection() ?>
