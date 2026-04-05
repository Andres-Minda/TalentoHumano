<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>

<div class="page-wrapper">
    <div class="page-content">
        <!-- Breadcrumb -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <h4 class="page-title mb-0">Reporte de Inasistencias</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="<?= base_url('admin-th/dashboard') ?>">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="<?= base_url('admin-th/inasistencias') ?>">Inasistencias</a></li>
                            <li class="breadcrumb-item active">Reporte</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filtros -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-funnel me-2"></i>
                            Filtros de Búsqueda
                        </h5>
                    </div>
                    <div class="card-body">
                        <form id="formFiltros" method="GET" action="<?= site_url('admin-th/inasistencias/reporte') ?>">
                            <div class="row">
                                <div class="col-md-3">
                                    <label class="form-label">Fecha Inicio</label>
                                    <input type="date" class="form-control" name="fecha_inicio" id="fecha_inicio" 
                                           value="<?= $filtros['fecha_inicio'] ?? '' ?>">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Fecha Fin</label>
                                    <input type="date" class="form-control" name="fecha_fin" id="fecha_fin" 
                                           value="<?= $filtros['fecha_fin'] ?? '' ?>">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Departamento</label>
                                    <select class="form-select" name="departamento" id="departamento">
                                        <option value="todos">Todos los departamentos</option>
                                        <?php foreach ($departamentos as $dept): ?>
                                            <option value="<?= esc($dept['departamento']) ?>" 
                                                    <?= ($filtros['departamento'] ?? '') === $dept['departamento'] ? 'selected' : '' ?>>
                                                <?= esc($dept['departamento']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">&nbsp;</label>
                                    <button type="submit" class="btn btn-primary d-block w-100">
                                        <i class="bi bi-search me-1"></i>Generar Reporte
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Estadísticas del Reporte -->
        <div class="row">
            <div class="col-md-3">
                <div class="card mini-stats-wid">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-grow-1">
                                <p class="text-muted fw-medium">Total</p>
                                <h4 class="mb-0"><?= $estadisticas['total'] ?></h4>
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
            <div class="col-md-3">
                <div class="card mini-stats-wid">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-grow-1">
                                <p class="text-muted fw-medium">Justificadas</p>
                                <h4 class="mb-0 text-success"><?= $estadisticas['justificadas'] ?></h4>
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
            <div class="col-md-3">
                <div class="card mini-stats-wid">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-grow-1">
                                <p class="text-muted fw-medium">Sin Justificar</p>
                                <h4 class="mb-0 text-danger"><?= $estadisticas['sin_justificar'] ?></h4>
                            </div>
                            <div class="flex-shrink-0 align-self-center">
                                <div class="mini-stat-icon avatar-sm rounded-circle bg-danger">
                                    <span class="avatar-title">
                                        <i class="bi bi-x-circle font-size-24"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card mini-stats-wid">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-grow-1">
                                <p class="text-muted fw-medium">Tasa Justificación</p>
                                <h4 class="mb-0 text-info"><?= $estadisticas['tasa_justificacion'] ?>%</h4>
                            </div>
                            <div class="flex-shrink-0 align-self-center">
                                <div class="mini-stat-icon avatar-sm rounded-circle bg-info">
                                    <span class="avatar-title">
                                        <i class="bi bi-percent font-size-24"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabla de Resultados -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-table me-2"></i>
                            Resultados del Reporte
                        </h5>
                        <div>
                            <button type="button" class="btn btn-success btn-sm me-2" onclick="exportarExcel()">
                                <i class="bi bi-file-earmark-excel me-1"></i>Exportar Excel
                            </button>
                            <button type="button" class="btn btn-danger btn-sm" onclick="window.print()">
                                <i class="bi bi-printer me-1"></i>Imprimir
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover table-centered align-middle" id="tablaReporte">
                                <thead class="table-dark">
                                    <tr>
                                        <th>#</th>
                                        <th>Empleado</th>
                                        <th>Cédula</th>
                                        <th>Departamento</th>
                                        <th>Fecha</th>
                                        <th>Hora</th>
                                        <th>Tipo</th>
                                        <th>Motivo</th>
                                        <th>Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($inasistencias)): ?>
                                        <?php foreach ($inasistencias as $index => $ina): ?>
                                            <tr>
                                                <td><?= $index + 1 ?></td>
                                                <td>
                                                    <strong><?= esc($ina['apellidos']) ?> <?= esc($ina['nombres']) ?></strong><br>
                                                    <small class="text-muted"><?= esc($ina['tipo_empleado']) ?></small>
                                                </td>
                                                <td><?= esc($ina['cedula']) ?></td>
                                                <td>
                                                    <span class="badge bg-secondary"><?= esc($ina['departamento']) ?></span>
                                                </td>
                                                <td><?= date('d/m/Y', strtotime($ina['fecha_inasistencia'])) ?></td>
                                                <td><?= $ina['hora_inasistencia'] ? date('H:i', strtotime($ina['hora_inasistencia'])) : 'N/A' ?></td>
                                                <td>
                                                    <span class="badge bg-info"><?= esc($ina['tipo_inasistencia']) ?></span>
                                                </td>
                                                <td style="max-width: 250px;">
                                                    <small><?= esc($ina['motivo']) ?></small>
                                                </td>
                                                <td>
                                                    <?php if ($ina['justificada'] == 1): ?>
                                                        <span class="badge bg-success">Justificada</span>
                                                    <?php else: ?>
                                                        <span class="badge bg-warning text-dark">Sin Justificar</span>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="9" class="text-center text-muted">
                                                <i class="bi bi-inbox fs-1"></i>
                                                <p class="mt-2">No se encontraron resultados con los filtros aplicados</p>
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

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>

<script>
$(document).ready(function() {
    $('#tablaReporte').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json'
        },
        order: [[4, 'desc']]
    });
});

function exportarExcel() {
    const params = new URLSearchParams(window.location.search);
    params.set('formato', 'excel');
    window.location.href = '<?= site_url('admin-th/inasistencias/exportar') ?>?' + params.toString();
}
</script>

<style>
@media print {
    .card-header button,
    .breadcrumb,
    .page-title-box,
    #formFiltros {
        display: none !important;
    }
}
</style>

<?= $this->endSection() ?>
