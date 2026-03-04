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
                            <li class="breadcrumb-item"><a href="<?= base_url('admin-th/dashboard') ?>">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="<?= base_url('admin-th/inasistencias') ?>">Inasistencias</a></li>
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
                        <div>
                            <a href="<?= site_url('admin-th/inasistencias') ?>" class="btn btn-outline-secondary btn-sm me-2">
                                <i class="ti ti-arrow-left me-1"></i>Regresar al Dashboard
                            </a>
                            <a href="<?= base_url('admin-th/inasistencias/registrar') ?>" class="btn btn-primary btn-sm">
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
                                                        'Licencia Médica' => 'warning'
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
                                                        <button type="button" class="btn btn-sm btn-outline-info" 
                                                                onclick="verDetalle(<?= $ina['id'] ?>)"
                                                                data-bs-toggle="tooltip" data-bs-placement="top" title="Ver Detalles">
                                                            <i class="bi bi-eye"></i>
                                                        </button>
                                                        <a href="<?= site_url('admin-th/inasistencias/editar/' . $ina['id']) ?>" class="btn btn-sm btn-outline-warning" 
                                                                data-bs-toggle="tooltip" data-bs-placement="top" title="Editar">
                                                            <i class="bi bi-pencil"></i>
                                                        </a>
                                                        <button type="button" class="btn btn-sm btn-outline-danger" 
                                                                onclick="eliminarInasistencia(<?= $ina['id'] ?>)"
                                                                data-bs-toggle="tooltip" data-bs-placement="top" title="Eliminar">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
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
<!-- Modal Ver Detalles -->
<div class="modal fade" id="modalVerDetalle" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title"><i class="bi bi-info-circle me-2"></i>Detalle de la Inasistencia</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="contenidoDetalle">
                <div class="text-center py-3"><div class="spinner-border text-info" role="status"></div></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<!-- DataTables (Opcional si se requiere búsqueda/paginación nativa) -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script>
$(document).ready(function() {
    $('#tablaInasistenciasCompletas').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json',
            emptyTable: "No hay inasistencias registradas en el sistema."
        },
        order: [[3, 'desc']] // Ordenar por fecha desc por defecto
    });

    // Inicializar Tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
      return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});

function verDetalle(id) {
    const modal = new bootstrap.Modal(document.getElementById('modalVerDetalle'));
    modal.show();
    document.getElementById('contenidoDetalle').innerHTML = '<div class="text-center py-3"><div class="spinner-border text-info" role="status"></div></div>';

    fetch(`<?= site_url('admin-th/inasistencias/detalles/') ?>${id}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const i = data.inasistencia;
                document.getElementById('contenidoDetalle').innerHTML = `
                    <div class="mb-3"><strong>Empleado:</strong> <br> ${i.apellidos} ${i.nombres}</div>
                    <div class="mb-3"><strong>Fecha y Hora:</strong> <br> ${i.fecha_inasistencia} ${i.hora_inasistencia ? '- ' + i.hora_inasistencia : ''}</div>
                    <div class="mb-3"><strong>Tipo:</strong> <br> <span class="badge bg-secondary">${i.tipo_inasistencia}</span></div>
                    <div class="mb-3"><strong>Estado:</strong> <br> <span class="badge ${i.justificada == 1 ? 'bg-success' : 'bg-warning'}">${i.justificada == 1 ? 'Justificada' : 'Sin Justificar'}</span></div>
                    <div class="mb-3"><strong>Motivo:</strong> <br> <p class="text-muted border p-2 rounded bg-light">${i.motivo}</p></div>
                `;
            } else {
                document.getElementById('contenidoDetalle').innerHTML = `<div class="alert alert-danger">${data.message}</div>`;
            }
        })
        .catch(error => {
            document.getElementById('contenidoDetalle').innerHTML = `<div class="alert alert-danger">Error de conexión al cargar datos.</div>`;
        });
}

function eliminarInasistencia(id) {
    Swal.fire({
        title: '¿Estás seguro?',
        text: "Esta inasistencia será eliminada permanentemente.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({ title: 'Eliminando...', allowOutsideClick: false, didOpen: () => { Swal.showLoading(); }});
            
            fetch(`<?= site_url('admin-th/inasistencias/eliminar/') ?>${id}`, {
                method: 'DELETE',
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(res => res.json())
            .then(data => {
                if(data.success) {
                    Swal.fire('¡Eliminado!', 'El registro ha sido eliminado.', 'success').then(() => {
                        window.location.reload();
                    });
                } else {
                    Swal.fire('Error', data.message || 'No se pudo eliminar el registro.', 'error');
                }
            })
            .catch(err => {
                Swal.fire('Error', 'Problema de conexión con el servidor.', 'error');
            });
        }
    });
}
</script>
<?= $this->endSection() ?>
