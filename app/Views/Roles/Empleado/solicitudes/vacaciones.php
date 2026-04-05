<?= $this->extend('layouts/base') ?>

<?= $this->section('title') ?>
Mis Vacaciones
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="page-wrapper">
    <div class="page-content">
        <div class="container-fluid">
            <div class="row mb-4 align-items-center">
        <div class="col-8">
            <h2 class="mb-0 "><i class="bi bi-airplane text-primary me-2"></i>Mis Solicitudes de Vacaciones</h2>
            <p class="text-muted">Gestiona tus periodos vacacionales.</p>
        </div>
        <div class="col-4 text-end">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalNuevaSolicitud">
                <i class="bi bi-plus-circle me-1"></i> Nueva Solicitud
            </button>
        </div>
    </div>

    <!-- Tarjeta de Saldo de Vacaciones -->
    <div class="row mb-4">
        <div class="col-md-4">
            <?php
                $diasDisp  = (int) ($empleado['dias_vacaciones_disponibles'] ?? 0);
                $cardColor = $diasDisp >= 10 ? 'bg-success' : ($diasDisp >= 5 ? 'bg-warning' : 'bg-danger');
                $pct       = round(($diasDisp / 15) * 100);
            ?>
            <div class="card border-0 shadow-sm <?= $cardColor ?> text-white h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2">
                        <i class="bi bi-calendar-check display-5 me-3"></i>
                        <div>
                            <h6 class="mb-0 fw-normal opacity-75">Días de Vacaciones Disponibles</h6>
                            <h2 class="mb-0 fw-bold"><?= $diasDisp ?> <small class="fs-5 fw-normal">de 15</small></h2>
                        </div>
                    </div>
                    <div class="progress bg-white bg-opacity-25" style="height:6px;">
                        <div class="progress-bar bg-white" style="width:<?= $pct ?>%;"></div>
                    </div>
                    <small class="opacity-75 mt-1 d-block"><?= 15 - $diasDisp ?> día(s) ya utilizados este período</small>
                </div>
            </div>
        </div>
        <?php if (!empty($saldo_reseteado)): ?>
        <div class="col-md-8 d-flex align-items-center">
            <div class="alert alert-success border-0 shadow-sm w-100 mb-0">
                <i class="bi bi-arrow-clockwise me-2 fs-5"></i>
                <strong>¡Saldo renovado!</strong> Tu período vacacional se ha reiniciado. Tienes <strong>15 días</strong> disponibles.
            </div>
        </div>
        <?php endif; ?>
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

    <!-- Tabla de Solicitudes -->
    <div class="card shadow-sm border-0">
        <div class="card-body">
            <table class="table table-hover table-striped" id="tablaVacaciones">
                <thead class="table-dark">
                    <tr>
                        <th width="5%">Nº</th>
                        <th width="15%">Fecha Ingreso</th>
                        <th width="30%">Detalle / Motivo</th>
                        <th width="20%">Periodo Seleccionado</th>
                        <th width="15%">Estado</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($solicitudes)): ?>
                        <?php foreach ($solicitudes as $index => $sol): ?>
                            <tr>
                                <td><?= ltrim($index + 1, '0') ?></td>
                                <td><?= date('d/m/Y H:i', strtotime($sol['fecha_solicitud'])) ?></td>
                                <td><?= esc($sol['motivo_descripcion']) ?></td>
                                <td>
                                    <?php if ($sol['fecha_inicio'] && $sol['fecha_fin']): ?>
                                        Del <?= date('d/m', strtotime($sol['fecha_inicio'])) ?> al <?= date('d/m', strtotime($sol['fecha_fin'])) ?>
                                    <?php else: ?>
                                        <span class="text-muted">No especificado</span>
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
                                    <span class="badge <?= $clase ?> fs-6"><?= $sol['estado'] ?></span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Nueva Solicitud -->
<div class="modal fade" id="modalNuevaSolicitud" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="<?= site_url('empleado/mis-solicitudes/guardar') ?>" method="POST" class="modal-content border-0 shadow">
            <?= csrf_field() ?>
            <input type="hidden" name="tipo_solicitud" value="Vacaciones">
            <!-- Traemos el id_empleado enviado desde el controlador -->
            <input type="hidden" name="empleado_id" value="<?= esc($empleado_id) ?>">

            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="bi bi-airplane me-2"></i>Solicitar Vacaciones</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <div class="modal-body p-4">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label for="fecha_inicio" class="form-label font-weight-bold">Fecha Inicio <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" required>
                    </div>
                    <div class="col-md-4">
                        <label for="fecha_fin" class="form-label font-weight-bold">Fecha Fin <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" id="fecha_fin" name="fecha_fin" disabled required>
                    </div>
                    <div class="col-md-4">
                        <label for="dias_solicitados" class="form-label font-weight-bold">Días Solicitados</label>
                        <input type="number" class="form-control bg-light" id="dias_solicitados" name="dias_solicitados" readonly value="0">
                    </div>
                    <div class="col-12">
                        <label for="motivo_descripcion" class="form-label font-weight-bold">Comentarios Adicionales (Opcional)</label>
                        <textarea class="form-control" id="motivo_descripcion" name="motivo_descripcion" rows="3" placeholder="Ej. Según el cronograma anual establecido..."></textarea>
                    </div>
                </div>
                <div class="alert alert-info mt-3 mb-0 fs-7">
                    <i class="bi bi-info-circle me-1"></i> Su solicitud será revisada por el departamento de Talento Humano.
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary"><i class="bi bi-send me-1"></i> Enviar Solicitud</button>
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
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {
        const TOTAL_DIAS    = 15;
        const saldoDisponible = <?= (int) ($empleado['dias_vacaciones_disponibles'] ?? 0) ?>;
        const btnGuardar    = $('button[type="submit"]');
        const $alerta       = $('<div class="alert mt-2 mb-0 py-2 px-3 d-none" id="alertaDias"></div>');
        $('#dias_solicitados').closest('.col-md-4').append($alerta);

        $('#tablaVacaciones').DataTable({
            language: { url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json' },
            order: [[1, 'desc']],
            columnDefs: [{ orderable: false, targets: -1 }]
        });

        // Habilitar fecha_fin solo cuando hay fecha_inicio
        $('#fecha_inicio').on('change', function() {
            const inicio = $(this).val();
            if (inicio) {
                $('#fecha_fin').prop('disabled', false).attr('min', inicio);
                if ($('#fecha_fin').val() && $('#fecha_fin').val() < inicio) {
                    $('#fecha_fin').val('');
                }
            } else {
                $('#fecha_fin').prop('disabled', true).val('');
            }
            calcularDias();
        });

        $('#fecha_fin').on('change', calcularDias);

        function calcularDias() {
            const inicioVal = $('#fecha_inicio').val();
            const finVal    = $('#fecha_fin').val();

            if (!inicioVal || !finVal) {
                $('#dias_solicitados').val(0);
                $alerta.addClass('d-none');
                btnGuardar.prop('disabled', false);
                return;
            }

            const start    = new Date(inicioVal + 'T00:00:00');
            const end      = new Date(finVal    + 'T00:00:00');

            if (end < start) {
                Swal.fire('Fechas inválidas', 'La fecha de fin no puede ser anterior a la de inicio.', 'error');
                $('#fecha_fin').val('');
                $('#dias_solicitados').val(0);
                return;
            }

            // Convención: del 04/04 al 11/04 = 7 días (sin +1, igual que PHP diff->days)
            const diffDays = Math.round((end - start) / (1000 * 60 * 60 * 24));
            $('#dias_solicitados').val(diffDays);

            // Feedback visual inmediato
            $alerta.removeClass('d-none alert-success alert-warning alert-danger');

            if (diffDays > saldoDisponible) {
                $alerta.addClass('alert-danger')
                       .html(`<i class="bi bi-x-circle me-1"></i><strong>Saldo insuficiente.</strong> Solicitas <strong>${diffDays}</strong> días pero solo tienes <strong>${saldoDisponible}</strong> disponibles.`);
                btnGuardar.prop('disabled', true);
            } else if (diffDays === saldoDisponible) {
                $alerta.addClass('alert-warning')
                       .html(`<i class="bi bi-exclamation-triangle me-1"></i>Usarás <strong>todos</strong> tus días disponibles (${diffDays} de ${TOTAL_DIAS}).`);
                btnGuardar.prop('disabled', false);
            } else {
                const restante = saldoDisponible - diffDays;
                $alerta.addClass('alert-success')
                       .html(`<i class="bi bi-check-circle me-1"></i>Solicitarás <strong>${diffDays}</strong> día(s). Te quedarán <strong>${restante}</strong> día(s) disponibles.`);
                btnGuardar.prop('disabled', false);
            }
        }
    });
</script>
    </div>
</div>
<?= $this->endSection() ?>
