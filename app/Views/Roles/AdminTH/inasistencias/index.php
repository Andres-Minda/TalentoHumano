<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>

<div class="page-wrapper">
    <div class="page-content">
        <!-- Breadcrumb -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <h4 class="page-title mb-0">Historial de Inasistencias</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="<?= site_url('admin-th/dashboard') ?>">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="<?= site_url('admin-th/inasistencias') ?>">Inasistencias</a></li>
                            <li class="breadcrumb-item active">Listado</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <i class="ti ti-list text-primary me-2"></i>
                            Todas las Inasistencias
                        </h5>
                        <div class="d-flex align-items-center">
                            <a href="<?= site_url('admin-th/inasistencias') ?>" class="btn btn-outline-secondary btn-sm me-2">
                                <i class="ti ti-arrow-left me-1"></i>Regresar al Dashboard
                            </a>
                            <a href="<?= site_url('admin-th/inasistencias/registrar') ?>" class="btn btn-primary btn-sm">
                                <i class="ti ti-plus me-1"></i>Registrar Inasistencia
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover table-centered align-middle" id="tablaInasistenciasCompletas">
                                <thead class="table-dark">
                                    <tr>
                                        
                                        <th>#</th>
                                        <th>Empleado</th>
                                        <th>Departamento</th>
                                        <th>Fecha y Hora</th>
                                        <th>Tipo</th>
                                        <th>Motivo</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (isset($inasistencias) && !empty($inasistencias)): ?>
                                        <?php foreach ($inasistencias as $index => $ina): ?>
                                            <tr>
                                                <td><?= $index + 1 ?></td>
                                                <td>
                                                    <strong><?= esc($ina['apellidos'] ?? '') ?> <?= esc($ina['nombres'] ?? '') ?></strong><br>
                                                    <small class="text-muted"><?= esc($ina['tipo_empleado'] ?? '') ?></small>
                                                </td>
                                                <td>
                                                    <span class="badge bg-secondary"><?= esc($ina['departamento'] ?? 'Sin asignar') ?></span>
                                                </td>
                                                <td>
                                                    <?= date('d/m/Y', strtotime($ina['fecha_inasistencia'])) ?><br>
                                                    <small class="text-muted"><?= $ina['hora_inasistencia'] ? date('H:i', strtotime($ina['hora_inasistencia'])) : 'N/A' ?></small>
                                                </td>
                                                <td>
                                                    <?php
                                                    $tipoBadges = [
                                                        'Justificada' => 'success',
                                                        'Injustificada' => 'danger',
                                                        'Permiso' => 'info',
                                                        'Vacaciones' => 'primary',
                                                        'Licencia MÃ©dica' => 'warning'
                                                    ];
                                                    $tipoClass = $tipoBadges[$ina['tipo_inasistencia']] ?? 'secondary';
                                                    ?>
                                                    <span class="badge bg-<?= $tipoClass ?>">
                                                        <?= esc($ina['tipo_inasistencia'] ?? 'Desconocido') ?>
                                                    </span>
                                                </td>
                                                <td style="max-width: 250px; white-space: normal;">
                                                    <small><?= esc($ina['motivo'] ?? '') ?></small>
                                                </td>
                                                <td>
                                                    <?php if ($ina['justificada'] == 1): ?>
                                                        <span class="badge bg-success"><i class="ti ti-check me-1"></i>Justificada</span>
                                                    <?php else: ?>
                                                        <span class="badge bg-warning text-dark"><i class="ti ti-x me-1"></i>Sin Justificar</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                <a href="javascript:void(0)" class="btn btn-sm btn-outline-info btn-ver-detalles" data-id="<?= $ina['id'] ?? '' ?>" data-url="<?= site_url('admin-th/inasistencias/detalles/') ?>" 
                                                        data-bs-toggle="tooltip" data-bs-placement="top" title="Ver Detalles">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <a href="mailto:<?= $ina['correo'] ?? '' ?>?subject=Sobre tu inasistencia" 
                                                        class="btn btn-sm btn-outline-warning" 
                                                        data-bs-toggle="tooltip" data-bs-placement="top" title="Contactar">
                                                    <i class="bi bi-chat"></i>
                                                </a>
                                                <a href="<?= site_url('admin-th/inasistencias/reporte-empleado/' . ($ina['empleado_id'] ?? 0)) ?>" 
                                                   target="_blank"
                                                   class="btn btn-sm btn-outline-danger" 
                                                   data-bs-toggle="tooltip" data-bs-placement="top" title="Reporte Completo">
                                                   <i class="bi bi-file-earmark-pdf"></i>
                                                </a>
                                                        <a href="<?= site_url('admin-th/inasistencias/editar/' . $ina['id']) ?>" class="btn btn-sm btn-outline-warning" 
                                                                data-bs-toggle="tooltip" data-bs-placement="top" title="Editar">
                                                            <i class="bi bi-pencil"></i>
                                                        </a>
                                                        </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
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
<!-- Bootstap Icons for table Actions -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<!-- SweetAlert2 Plugin -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- Modal Detalles (Inyectado Automáticamente) -->
<div class="modal fade" id="modalDetallesInasistencia" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title"><i class="fas fa-info-circle"></i> Detalles de Inasistencia</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="row mb-2">
          <div class="col-sm-4 fw-bold">Empleado:</div>
          <div class="col-sm-8" id="detalle-empleado">Cargando...</div>
        </div>
        <div class="row mb-2">
          <div class="col-sm-4 fw-bold">Departamento:</div>
          <div class="col-sm-8" id="detalle-departamento">Cargando...</div>
        </div>
        <div class="row mb-2">
          <div class="col-sm-4 fw-bold">Fecha y Hora:</div>
          <div class="col-sm-8"><span id="detalle-fecha"></span> a las <span id="detalle-hora"></span></div>
        </div>
        <div class="row mb-2">
          <div class="col-sm-4 fw-bold">Tipo:</div>
          <div class="col-sm-8" id="detalle-tipo">Cargando...</div>
        </div>
        <div class="row mb-2">
          <div class="col-sm-4 fw-bold">Motivo:</div>
          <div class="col-sm-8 text-muted" id="detalle-motivo">Cargando...</div>
        </div>
      </div>
      <div class="modal-footer d-flex justify-content-center">
         <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<!-- DataTables (Opcional si se requiere bÃƒÂºsqueda/paginaciÃƒÂ³n nativa) -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script>
$(document).ready(function() {
    $("#tablaInasistenciasCompletas").DataTable({
        language: {
            url: "//cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json",
            emptyTable: "No hay inasistencias registradas en el sistema."
        },
        order: [[3, "desc"]] // Ordenar por fecha desc por defecto
    });

    // Inicializar Tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
      return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});


$(document).on('click', '.btn-ver-detalles', function(e) {
    e.preventDefault();
    
    // Obtenemos el ID desde el atributo del botón
    const id = $(this).data('id');
    const url = '<?= site_url("admin-th/inasistencias/detalles/") ?>' + id;
    
    // Mostramos que está cargando...
    $('#detalle-empleado').text('Cargando...');
    $('#detalle-departamento').text('Cargando...');
    $('#detalle-fecha').text('...');
    $('#detalle-hora').text('...');
    $('#detalle-tipo').text('Cargando...');
    $('#detalle-motivo').text('Cargando...');
    
    // Abrimos el modal preventivamente
    $('#modalDetallesInasistencia').modal('show');
    
    // Hacemos el Fetch AJAX
    fetch(url, { headers: {'X-Requested-With': 'XMLHttpRequest'} })
        .then(response => response.json())
        .then(data => {
            if (data.success && data.inasistencia) {
                const i = data.inasistencia;
                
                // Mapear el JSON a los campos HTML solicitados:
                $('#detalle-empleado').text((i.nombres || '') + ' ' + (i.apellidos || ''));
                $('#detalle-departamento').text(i.departamento || 'Sin asignar');
                $('#detalle-fecha').text(i.fecha_inasistencia || 'N/A');
                $('#detalle-hora').text(i.hora_inasistencia || 'N/A');
                $('#detalle-tipo').text(i.tipo_inasistencia || 'N/A');
                $('#detalle-motivo').text(i.motivo || 'Sin motivo reportado');
                
            } else {
                $('#detalle-empleado').text('Error: no se encontró registro.');
            }
        })
        .catch(error => {
            console.error(error);
            $('#detalle-empleado').text('Error de conexión.');
        });
});
</script>

<?= $this->endSection() ?>
