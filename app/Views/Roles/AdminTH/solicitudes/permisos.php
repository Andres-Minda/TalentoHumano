<?= $this->extend('layouts/base') ?>

<?= $this->section('title') ?>
Gestión de Permisos
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="page-wrapper">
    <div class="page-content">
        <div class="container-fluid">
            <div class="row mb-4 align-items-center">
        <div class="col-8">
            <h2 class="mb-0"><i class="bi bi-clock-history text-primary me-2"></i>Gestión de Permisos</h2>
            <p class="text-muted">Revisión y aprobación de permisos (citas médicas, trámites) del personal.</p>
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
            <table class="table table-hover table-striped" id="tablaPermisosAdmin">
                <thead class="table-dark">
                    <tr>
                        <th width="5%">Nº</th>
                        <th width="10%">Fecha Ingreso</th>
                        <th width="20%">Empleado</th>
                        <th width="30%">Motivo / Justificación</th>
                        <th width="15%">Periodo</th>
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
                                <td><?= esc($sol['motivo_descripcion']) ?></td>
                                <td>
                                    <?php if ($sol['fecha_inicio'] && $sol['fecha_fin']): ?>
                                        <span class="badge bg-light text-dark border">
                                            <?= date('d/m', strtotime($sol['fecha_inicio'])) ?> - <?= date('d/m', strtotime($sol['fecha_fin'])) ?>
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
                                            onclick="gestionarSolicitud(<?= $sol['id_solicitud'] ?>, '<?= esc($sol['apellidos'] . ' ' . $sol['nombres']) ?>', '<?= date('d/m', strtotime($sol['fecha_inicio'])) ?> al <?= date('d/m', strtotime($sol['fecha_fin'])) ?>')"
                                            data-bs-toggle="tooltip" title="Gestionar (Aprobar/Rechazar)">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
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

<!-- Modal para Aprobar / Rechazar (Reutilizable para los 3 tipos) -->
<div class="modal fade" id="modalGestionarSolicitud" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form id="formGestionar" action="" method="POST" class="modal-content border-0 shadow">
            <?= csrf_field() ?>
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title"><i class="bi bi-gear-fill me-2"></i>Gestionar Permiso</h5>
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
                    <textarea class="form-control" id="comentarios" name="comentarios" rows="3" placeholder="Ej. Aprobado con goce de haberes..."></textarea>
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
        $('#tablaPermisosAdmin').DataTable({
            language: { url: "//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json" },
            order: [[1, 'desc']], 
            emptyTable: "No hay solicitudes de permisos pendientes."
        });

        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
          return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });

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
