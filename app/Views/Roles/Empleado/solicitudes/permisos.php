<?= $this->extend('layouts/base') ?>

<?= $this->section('title') ?>
Mis Permisos
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="page-wrapper">
    <div class="page-content">
        <div class="container-fluid">
            <div class="row mb-4 align-items-center">
        <div class="col-8">
            <h2 class="mb-0 "><i class="bi bi-clock-history text-primary me-2"></i>Mis Solicitudes de Permisos</h2>
            <p class="text-muted">Gestiona tus permisos para citas médicas, trámites personales, etc.</p>
        </div>
        <div class="col-4 text-end">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalNuevaSolicitud">
                <i class="bi bi-plus-circle me-1"></i> Nuevo Permiso
            </button>
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

    <!-- Tabla de Solicitudes -->
    <div class="card shadow-sm border-0">
        <div class="card-body">
            <table class="table table-hover table-striped" id="tablaPermisos">
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
            <input type="hidden" name="tipo_solicitud" value="Permisos">
            <input type="hidden" name="empleado_id" value="<?= esc($empleado_id) ?>">

            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="bi bi-clock me-2"></i>Solicitar Permiso</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <div class="modal-body p-4">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="fecha_inicio" class="form-label font-weight-bold">Día de Inicio <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" required>
                    </div>
                    <div class="col-md-6">
                        <label for="fecha_fin" class="form-label font-weight-bold">Día de Fin <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" id="fecha_fin" name="fecha_fin" required>
                    </div>
                    <div class="col-12">
                        <label for="motivo_descripcion" class="form-label font-weight-bold">Motivo del Permiso <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="motivo_descripcion" name="motivo_descripcion" rows="3" placeholder="Ej. Cita médica en el IESS..." required></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary"><i class="bi bi-send me-1"></i> Enviar a Aprobación</button>
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
        $('#tablaPermisos').DataTable({
            language: { url: "//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json" },
            order: [[1, 'desc']], 
            emptyTable: "No tienes solicitudes de permisos registradas."
        });

        $('#fecha_fin').on('change', function() {
            var start = $('#fecha_inicio').val();
            if(start && $(this).val() < start) {
                alert('La fecha de fin no puede ser anterior a la fecha de inicio.');
                $(this).val('');
            }
        });
    });
</script>
    </div>
</div>
<?= $this->endSection() ?>
