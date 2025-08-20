<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>

<div class="page-wrapper">
    <div class="page-content">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <h4 class="page-title mb-0">Mis Inasistencias</h4>
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
                                <h4 class="mb-0"><?= $totalInasistencias ?? 0 ?></h4>
                            </div>
                            <div class="flex-shrink-0 align-self-center">
                                <div class="mini-stat-icon avatar-sm rounded-circle bg-primary">
                                    <span class="avatar-title">
                                        <i class="bi bi-calendar-x font-size-24"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card mini-stats-wid">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-grow-1">
                                <p class="text-muted fw-medium">Justificadas</p>
                                <h4 class="mb-0 text-success"><?= $inasistenciasJustificadas ?? 0 ?></h4>
                            </div>
                            <div class="flex-shrink-0 align-self-center">
                                <div class="mini-stat-icon avatar-sm rounded-circle bg-success">
                                    <span class="avatar-title">
                                        <i class="bi bi-check-circle font-size-24"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card mini-stats-wid">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-grow-1">
                                <p class="text-muted fw-medium">Sin Justificar</p>
                                <h4 class="mb-0 text-warning"><?= $inasistenciasSinJustificar ?? 0 ?></h4>
                            </div>
                            <div class="flex-shrink-0 align-self-center">
                                <div class="mini-stat-icon avatar-sm rounded-circle bg-warning">
                                    <span class="avatar-title">
                                        <i class="bi bi-exclamation-triangle font-size-24"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card mini-stats-wid">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-grow-1">
                                <p class="text-muted fw-medium">Pendientes</p>
                                <h4 class="mb-0 text-info"><?= $inasistenciasPendientes ?? 0 ?></h4>
                            </div>
                            <div class="flex-shrink-0 align-self-center">
                                <div class="mini-stat-icon avatar-sm rounded-circle bg-info">
                                    <span class="avatar-title">
                                        <i class="bi bi-clock font-size-24"></i>
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
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (isset($inasistencias) && !empty($inasistencias)): ?>
                                        <?php foreach ($inasistencias as $inasistencia): ?>
                                            <tr>
                                                <td>
                                                    <span class="badge bg-light text-dark">
                                                        <?= date('d/m/Y', strtotime($inasistencia['fecha'])) ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="badge bg-info">
                                                        <?= $inasistencia['tipo_nombre'] ?? 'N/A' ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <?= $inasistencia['motivo'] ?? 'No especificado' ?>
                                                </td>
                                                <td>
                                                    <?php
                                                    $estadoClass = '';
                                                    $estadoText = '';
                                                    switch ($inasistencia['estado']) {
                                                        case 'JUSTIFICADA':
                                                            $estadoClass = 'bg-success';
                                                            $estadoText = 'Justificada';
                                                            break;
                                                        case 'SIN_JUSTIFICAR':
                                                            $estadoClass = 'bg-warning';
                                                            $estadoText = 'Sin Justificar';
                                                            break;
                                                        case 'PENDIENTE':
                                                            $estadoClass = 'bg-info';
                                                            $estadoText = 'Pendiente';
                                                            break;
                                                        default:
                                                            $estadoClass = 'bg-secondary';
                                                            $estadoText = 'Desconocido';
                                                    }
                                                    ?>
                                                    <span class="badge <?= $estadoClass ?>">
                                                        <?= $estadoText ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <?php if ($inasistencia['estado'] === 'JUSTIFICADA' && !empty($inasistencia['justificacion'])): ?>
                                                        <button type="button" class="btn btn-sm btn-outline-info" 
                                                                data-bs-toggle="modal" 
                                                                data-bs-target="#modalJustificacion" 
                                                                data-justificacion="<?= htmlspecialchars($inasistencia['justificacion']) ?>">
                                                            <i class="bi bi-eye"></i> Ver
                                                        </button>
                                                    <?php elseif ($inasistencia['estado'] === 'SIN_JUSTIFICAR'): ?>
                                                        <span class="text-muted">Sin justificación</span>
                                                    <?php else: ?>
                                                        <span class="text-muted">Pendiente</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?php if ($inasistencia['estado'] === 'SIN_JUSTIFICAR'): ?>
                                                        <button type="button" class="btn btn-sm btn-primary" 
                                                                data-bs-toggle="modal" 
                                                                data-bs-target="#modalSubirJustificacion" 
                                                                data-inasistencia-id="<?= $inasistencia['id'] ?>">
                                                            <i class="bi bi-upload"></i> Justificar
                                                        </button>
                                                    <?php endif; ?>
                                                    <button type="button" class="btn btn-sm btn-outline-secondary" 
                                                            onclick="verDetalle(<?= $inasistencia['id'] ?>)">
                                                        <i class="bi bi-eye"></i> Ver
                                                    </button>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="6" class="text-center text-muted">
                                                <i class="bi bi-inbox fs-1"></i>
                                                <p class="mt-2">No hay inasistencias registradas</p>
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
    // Filtros
    document.getElementById('btnFiltrar').addEventListener('click', function() {
        aplicarFiltros();
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

function aplicarFiltros() {
    const fechaDesde = document.getElementById('fecha_desde').value;
    const fechaHasta = document.getElementById('fecha_hasta').value;
    const tipo = document.getElementById('tipo_inasistencia').value;
    const estado = document.getElementById('estado').value;

    // Aquí implementar la lógica de filtrado
    console.log('Aplicando filtros:', { fechaDesde, fechaHasta, tipo, estado });
    
    // Recargar la página con los filtros aplicados
    const params = new URLSearchParams();
    if (fechaDesde) params.append('fecha_desde', fechaDesde);
    if (fechaHasta) params.append('fecha_hasta', fechaHasta);
    if (tipo) params.append('tipo', tipo);
    if (estado) params.append('estado', estado);
    
    window.location.href = '<?= base_url('empleado/inasistencias/mis-inasistencias') ?>?' + params.toString();
}

function limpiarFiltros() {
    document.getElementById('fecha_desde').value = '';
    document.getElementById('fecha_hasta').value = '';
    document.getElementById('tipo_inasistencia').value = '';
    document.getElementById('estado').value = '';
    
    // Recargar sin filtros
    window.location.href = '<?= base_url('empleado/inasistencias/mis-inasistencias') ?>';
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
            alert('Justificación enviada exitosamente');
            location.reload();
        } else {
            alert('Error al enviar la justificación: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error al enviar la justificación');
    });
}

function verDetalle(id) {
    window.location.href = '<?= base_url('empleado/inasistencias/ver/') ?>' + id;
}
</script>

<?= $this->endSection() ?>
