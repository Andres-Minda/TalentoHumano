<?php $sidebar = 'sidebar_docente'; ?>
<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>

<div class="page-wrapper">
    <div class="page-content">
        <!-- Header -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <h4 class="mb-0"><i class="bi bi-cash-stack"></i> Mi Nómina</h4>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row">
            <div class="col-xl-3 col-lg-6 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="me-3">
                                <span class="round-8 bg-primary rounded-circle me-2 d-inline-block"></span>
                                <div class="stat-digit d-flex">
                                    <h5 class="font-weight-semibold mb-0">$<?= number_format($estadisticas['salario_base'] ?? 0, 0, ',', '.') ?></h5>
                                </div>
                                <div class="stat-digit d-flex">
                                    <h5 class="font-weight-semibold mb-0">Salario Base</h5>
                                </div>
                            </div>
                            <div class="ms-auto">
                                <i class="bi bi-cash-stack text-primary" style="font-size: 2rem;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="me-3">
                                <span class="round-8 bg-success rounded-circle me-2 d-inline-block"></span>
                                <div class="stat-digit d-flex">
                                    <h5 class="font-weight-semibold mb-0">$<?= number_format($estadisticas['total_ingresos'] ?? 0, 0, ',', '.') ?></h5>
                                </div>
                                <div class="stat-digit d-flex">
                                    <h5 class="font-weight-semibold mb-0">Total Ingresos</h5>
                                </div>
                            </div>
                            <div class="ms-auto">
                                <i class="bi bi-plus-circle text-success" style="font-size: 2rem;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="me-3">
                                <span class="round-8 bg-danger rounded-circle me-2 d-inline-block"></span>
                                <div class="stat-digit d-flex">
                                    <h5 class="font-weight-semibold mb-0">$<?= number_format($estadisticas['total_descuentos'] ?? 0, 0, ',', '.') ?></h5>
                                </div>
                                <div class="stat-digit d-flex">
                                    <h5 class="font-weight-semibold mb-0">Total Descuentos</h5>
                                </div>
                            </div>
                            <div class="ms-auto">
                                <i class="bi bi-dash-circle text-danger" style="font-size: 2rem;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="me-3">
                                <span class="round-8 bg-info rounded-circle me-2 d-inline-block"></span>
                                <div class="stat-digit d-flex">
                                    <h5 class="font-weight-semibold mb-0">$<?= number_format($estadisticas['neto_pagar'] ?? 0, 0, ',', '.') ?></h5>
                                </div>
                                <div class="stat-digit d-flex">
                                    <h5 class="font-weight-semibold mb-0">Neto a Pagar</h5>
                                </div>
                            </div>
                            <div class="ms-auto">
                                <i class="bi bi-wallet2 text-info" style="font-size: 2rem;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Nómina Table -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Historial de Nóminas</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover" id="tablaNominas">
                                <thead>
                                    <tr>
                                        <th>Período</th>
                                        <th>Fecha Generación</th>
                                        <th>Fecha Pago</th>
                                        <th>Salario Base</th>
                                        <th>Ingresos</th>
                                        <th>Descuentos</th>
                                        <th>Neto</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($nominas as $nomina): ?>
                                    <tr>
                                        <td><?= $nomina['periodo'] ?></td>
                                        <td><?= date('d/m/Y', strtotime($nomina['fecha_generacion'])) ?></td>
                                        <td><?= date('d/m/Y', strtotime($nomina['fecha_pago'])) ?></td>
                                        <td>$<?= number_format($nomina['salario_base'], 0, ',', '.') ?></td>
                                        <td>$<?= number_format($nomina['total_ingresos'], 0, ',', '.') ?></td>
                                        <td>$<?= number_format($nomina['total_descuentos'], 0, ',', '.') ?></td>
                                        <td>$<?= number_format($nomina['neto_pagar'], 0, ',', '.') ?></td>
                                        <td>
                                            <span class="badge bg-<?= $nomina['estado'] == 'Pagada' ? 'success' : 'warning' ?>">
                                                <?= $nomina['estado'] ?>
                                            </span>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-outline-primary" onclick="verDetallesNomina(<?= $nomina['id_detalle'] ?>)">
                                                <i class="bi bi-eye"></i> Ver
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-success" onclick="descargarNomina(<?= $nomina['id_detalle'] ?>)">
                                                <i class="bi bi-download"></i> PDF
                                            </button>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Detalles Nómina -->
<div class="modal fade" id="modalDetallesNomina" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detalles de Nómina</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="detallesNominaContent">
                <!-- Contenido dinámico -->
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#tablaNominas').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json'
        }
    });
});

function verDetallesNomina(idNomina) {
    // Aquí se cargarían los detalles de la nómina
    $('#modalDetallesNomina').modal('show');
}

function descargarNomina(idNomina) {
    // Aquí se descargaría el PDF de la nómina
    alert('Descargando nómina ' + idNomina);
}
</script>

<?= $this->endSection() ?> 