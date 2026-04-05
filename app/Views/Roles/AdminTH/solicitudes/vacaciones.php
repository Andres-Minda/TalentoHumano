<?= $this->extend('layouts/base') ?>

<?= $this->section('title') ?>
Gestión de Vacaciones
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="page-wrapper">
    <div class="page-content">
        <div class="container-fluid">
            <div class="row mb-4 align-items-center">
        <div class="col-8">
            <h2 class="mb-0"><i class="bi bi-airplane text-primary me-2"></i>Gestión de Vacaciones</h2>
            <p class="text-muted">Revisión y aprobación de solicitudes de vacaciones del personal.</p>
        </div>
    </div>

    <!-- Mensajes de sesión -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i><?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle me-2"></i><?= session()->getFlashdata('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- Tabla -->
    <div class="card shadow-sm border-0">
        <div class="card-body">
            <table class="table table-hover table-striped" id="tablaVacacionesAdmin">
                <thead class="table-dark">
                    <tr>
                        <th width="5%">Nº</th>
                        <th width="10%">Fecha Ingreso</th>
                        <th width="20%">Empleado</th>
                        <th width="25%">Motivo Adicional</th>
                        <th width="20%">Periodo Solicitado</th>
                        <th width="10%">Estado</th>
                        <th width="10%" class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($solicitudes)): ?>
                        <?php foreach ($solicitudes as $index => $sol): ?>
                            <tr>
                                <td><?= ltrim($index + 1, '0') ?></td>
                                <td><?= date('d/m/Y H:i', strtotime($sol['fecha_solicitud'])) ?></td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <span class="font-weight-bold"><?= esc($sol['apellidos'] . ' ' . $sol['nombres']) ?></span>
                                        <small class="text-muted"><?= esc($sol['departamento']) ?></small>
                                    </div>
                                </td>
                                <td><?= esc($sol['motivo_descripcion'] ?? 'N/A') ?></td>
                                <td>
                                    <?php if ($sol['fecha_inicio'] && $sol['fecha_fin']): ?>
                                        <span class="badge bg-light text-dark border">
                                            <?= date('d/m/Y', strtotime($sol['fecha_inicio'])) ?> - <?= date('d/m/Y', strtotime($sol['fecha_fin'])) ?>
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php
                                        $badges = [
                                            'Pendiente' => 'bg-warning text-dark',
                                            'Aprobada'  => 'bg-success',
                                            'Rechazada' => 'bg-danger'
                                        ];
                                        $clase = $badges[$sol['estado']] ?? 'bg-secondary';
                                    ?>
                                    <span class="badge <?= $clase ?>"><?= $sol['estado'] ?></span>
                                </td>
                                <td class="text-center">
                                    <?php if ($sol['estado'] === 'Pendiente'): ?>
                                    <button type="button" class="btn btn-sm btn-outline-primary" 
                                            onclick="gestionarSolicitud(<?= $sol['id_solicitud'] ?>, '<?= esc($sol['apellidos'] . ' ' . $sol['nombres']) ?>', '<?= date('d/m/Y', strtotime($sol['fecha_inicio'])) ?> al <?= date('d/m/Y', strtotime($sol['fecha_fin'])) ?>')"
                                            data-bs-toggle="tooltip" title="Gestionar (Aprobar/Rechazar)">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                    <?php elseif ($sol['estado'] === 'Aprobada'): ?>
                                    <div class="d-flex gap-1 justify-content-center">
                                        <button type="button" class="btn btn-sm btn-outline-secondary" disabled
                                                data-bs-toggle="tooltip" title="Ya aprobada">
                                            <i class="bi bi-lock"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-danger"
                                                onclick="interrumpirVacaciones(
                                                    <?= $sol['id_solicitud'] ?>,
                                                    '<?= esc($sol['apellidos'] . ' ' . $sol['nombres']) ?>',
                                                    '<?= $sol['fecha_inicio'] ?>',
                                                    '<?= $sol['fecha_fin'] ?>',
                                                    <?= (int) $sol['dias_solicitados'] ?>
                                                )"
                                                data-bs-toggle="tooltip" title="Interrumpir / Cancelar vacaciones">
                                            <i class="bi bi-stop-circle"></i>
                                        </button>
                                    </div>
                                    <?php else: ?>
                                    <button type="button" class="btn btn-sm btn-outline-secondary" disabled>
                                        <i class="bi bi-lock"></i>
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

<!-- Modal para Interrumpir Vacaciones -->
<div class="modal fade" id="modalInterrumpir" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title"><i class="bi bi-stop-circle me-2"></i>Interrumpir Vacaciones</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <p class="mb-3">
                    Empleado: <strong id="intEmpleado"></strong><br>
                    Periodo original: <strong id="intPeriodo"></strong>
                </p>
                <div class="mb-3">
                    <label class="form-label fw-bold">Fecha de cancelación <span class="text-danger">*</span></label>
                    <input type="date" class="form-control" id="intFechaCancelacion">
                    <div class="form-text">Por defecto: hoy. Puedes elegir otra fecha dentro del periodo.</div>
                </div>
                <div id="intPreview" class="alert alert-info d-none py-2"></div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger" id="btnConfirmarInterrupcion">
                    <i class="bi bi-stop-circle me-1"></i>Confirmar Interrupción
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Aprobar / Rechazar (Reutilizable para los 3 tipos) -->
<div class="modal fade" id="modalGestionarSolicitud" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form id="formGestionar" action="" method="POST" class="modal-content border-0 shadow">
            <?= csrf_field() ?>
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title"><i class="bi bi-gear-fill me-2"></i>Gestionar Solicitud de Vacaciones</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <div class="modal-body p-4">
                <div class="mb-3 border-bottom pb-2">
                    <strong>Empleado:</strong> <span id="spanEmpleadoNombre" class="text-primary"></span><br>
                    <strong>Periodo:</strong> <span id="spanPeriodo" class="text-primary"></span>
                </div>
                
                <div class="mb-3">
                    <label class="form-label font-weight-bold">Resolución <span class="text-danger">*</span></label>
                    <div class="d-flex gap-3">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="estado" id="estadoAprobar" value="Aprobada" required>
                            <label class="form-check-label text-success font-weight-bold" for="estadoAprobar">Aprobar</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="estado" id="estadoRechazar" value="Rechazada" required>
                            <label class="form-check-label text-danger font-weight-bold" for="estadoRechazar">Rechazar</label>
                        </div>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label for="comentarios" class="form-label font-weight-bold">Comentarios de Resolución (Opcional)</label>
                    <textarea class="form-control" id="comentarios" name="comentarios" rows="3" placeholder="Ej. Aprobado según cronograma."></textarea>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-dark"><i class="bi bi-save me-1"></i> Guardar Cambios</button>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<!-- DataTables -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script>
    $(document).ready(function() {
        $('#tablaVacacionesAdmin').DataTable({
            language: { url: "//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json" },
            order: [[1, 'desc']], 
            emptyTable: "No hay solicitudes de vacaciones para mostrar."
        });

        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
          return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });

    // ── Interrumpir / Cancelar Vacaciones ────────────────────────────────────
    let _intSolicitudId  = null;
    let _intFechaInicio  = null;
    let _intFechaFin     = null;
    let _intDiasTotales  = 0;

    function interrumpirVacaciones(id, empleado, fechaInicio, fechaFin, diasSolicitados) {
        _intSolicitudId = id;
        _intFechaInicio = fechaInicio;
        _intFechaFin    = fechaFin;
        _intDiasTotales = diasSolicitados;

        const hoy = new Date().toISOString().split('T')[0];

        document.getElementById('intEmpleado').textContent = empleado;
        document.getElementById('intPeriodo').textContent  =
            formatFecha(fechaInicio) + ' al ' + formatFecha(fechaFin) +
            ' (' + diasSolicitados + ' días)';

        const inputFecha = document.getElementById('intFechaCancelacion');
        inputFecha.value = hoy;
        inputFecha.min   = fechaInicio;
        inputFecha.max   = fechaFin;

        actualizarPreview(hoy);
        inputFecha.addEventListener('input', () => actualizarPreview(inputFecha.value));

        new bootstrap.Modal(document.getElementById('modalInterrumpir')).show();
    }

    function actualizarPreview(fechaCancelStr) {
        const preview   = document.getElementById('intPreview');
        const cancelacion = new Date(fechaCancelStr + 'T00:00:00');
        const inicio      = new Date(_intFechaInicio  + 'T00:00:00');
        const fin         = new Date(_intFechaFin     + 'T00:00:00');

        preview.classList.remove('d-none', 'alert-info', 'alert-warning', 'alert-danger');

        if (cancelacion > fin) {
            preview.classList.add('alert-danger');
            preview.innerHTML = '<i class="bi bi-x-circle me-1"></i>Las vacaciones ya terminaron en esa fecha. No hay días que devolver.';
            document.getElementById('btnConfirmarInterrupcion').disabled = true;
            return;
        }

        document.getElementById('btnConfirmarInterrupcion').disabled = false;

        let diasDevolver;
        if (cancelacion <= inicio) {
            diasDevolver = _intDiasTotales;
            preview.classList.add('alert-warning');
            preview.innerHTML = `<i class="bi bi-arrow-counterclockwise me-1"></i>Las vacaciones aún no empezaron. Se devolverán <strong>todos los ${diasDevolver} días</strong>.`;
        } else {
            diasDevolver = Math.round((fin - cancelacion) / (1000 * 60 * 60 * 24));
            const diasUsados = _intDiasTotales - diasDevolver;
            preview.classList.add('alert-info');
            preview.innerHTML = `<i class="bi bi-info-circle me-1"></i>Días ya disfrutados: <strong>${diasUsados}</strong>. Se devolverán <strong>${diasDevolver} día(s)</strong> al empleado.`;
        }
    }

    document.getElementById('btnConfirmarInterrupcion').addEventListener('click', function () {
        const fechaCancelacion = document.getElementById('intFechaCancelacion').value;
        if (!fechaCancelacion) {
            Swal.fire('Atención', 'Debes seleccionar una fecha de cancelación.', 'warning');
            return;
        }

        bootstrap.Modal.getInstance(document.getElementById('modalInterrumpir')).hide();

        Swal.fire({
            title: '¿Confirmar interrupción?',
            html: 'Esta acción cambiará el estado a <strong>Cancelada</strong> y devolverá los días no utilizados al empleado.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="bi bi-stop-circle me-1"></i>Sí, interrumpir',
            cancelButtonText: 'No, volver'
        }).then(result => {
            if (!result.isConfirmed) return;

            Swal.fire({ title: 'Procesando...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });

            const formData = new FormData();
            formData.append('fecha_cancelacion', fechaCancelacion);
            formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');

            fetch(`<?= site_url('admin-th/solicitudes/cancelar-vacaciones/') ?>${_intSolicitudId}`, {
                method: 'POST',
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Interrupción registrada!',
                        html: `Se devolvieron <strong>${data.dias_devueltos} día(s)</strong> al saldo del empleado.`,
                        confirmButtonColor: '#198754'
                    }).then(() => window.location.reload());
                } else {
                    Swal.fire('Error', data.message, 'error');
                }
            })
            .catch(() => Swal.fire('Error', 'Problema de conexión con el servidor.', 'error'));
        });
    });

    function formatFecha(ymd) {
        if (!ymd) return '';
        const [y, m, d] = ymd.split('-');
        return `${d}/${m}/${y}`;
    }

    // ── Gestionar (Aprobar / Rechazar) ────────────────────────────────────────
    function gestionarSolicitud(id, empleado, periodo) {
        // Llenar Modal
        $('#spanEmpleadoNombre').text(empleado);
        $('#spanPeriodo').text(periodo);
        
        // Reset inputs
        $('input[name="estado"]').prop('checked', false);
        $('#comentarios').val('');
        
        // Set Action Dynamically
        const baseUrl = '<?= site_url('admin-th/solicitudes/cambiar-estado/') ?>';
        $('#formGestionar').attr('action', baseUrl + id);
        
        // Mostrar
        var myModal = new bootstrap.Modal(document.getElementById('modalGestionarSolicitud'));
        myModal.show();
    }
</script>
    </div>
</div>
<?= $this->endSection() ?>
