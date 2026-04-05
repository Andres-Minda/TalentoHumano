<?php $sidebar = 'sidebar_admin_th'; ?>
<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>

<div class="page-wrapper">
    <div class="page-content">

        <!-- Header -->
        <div class="row mb-3">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <h4 class="mb-0"><i class="bi bi-file-earmark-bar-graph me-2"></i>Reportes del Sistema</h4>
                    <small class="text-muted">Los reportes se descargan en formato CSV listo para Excel</small>
                </div>
            </div>
        </div>

        <!-- Tarjetas de resumen -->
        <div class="row mb-4">
            <div class="col-md-3 col-6">
                <div class="card border-0 shadow-sm text-center py-3">
                    <h3 class="mb-0 text-primary fw-bold"><?= $totalEmpleados ?></h3>
                    <small class="text-muted">Empleados Activos</small>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="card border-0 shadow-sm text-center py-3">
                    <h3 class="mb-0 text-success fw-bold"><?= $totalCapacitaciones ?></h3>
                    <small class="text-muted">Capacitaciones</small>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="card border-0 shadow-sm text-center py-3">
                    <h3 class="mb-0 text-warning fw-bold"><?= $totalInasistencias ?></h3>
                    <small class="text-muted">Inasistencias</small>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="card border-0 shadow-sm text-center py-3">
                    <h3 class="mb-0 text-info fw-bold"><?= $totalEvaluaciones ?></h3>
                    <small class="text-muted">Evaluaciones</small>
                </div>
            </div>
        </div>

        <!-- Generador de Reportes -->
        <div class="card shadow-sm border-0">
            <div class="card-header bg-dark text-white">
                <h6 class="mb-0"><i class="bi bi-tools me-2"></i>Generador de Reportes</h6>
            </div>
            <div class="card-body">
                <form id="formReporte">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label fw-bold">Tipo de Reporte <span class="text-danger">*</span></label>
                            <select class="form-select" id="tipoReporte" required>
                                <option value="">Seleccionar...</option>
                                <option value="empleados">👥 Empleados</option>
                                <option value="capacitaciones">🎓 Capacitaciones</option>
                                <option value="inasistencias">📅 Inasistencias</option>
                                <option value="evaluaciones">📋 Evaluaciones</option>
                                <option value="solicitudes">📝 Solicitudes</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold">Fecha Inicio <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="fechaInicio" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold">Fecha Fin <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="fechaFin" required>
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <button type="submit" class="btn btn-success w-100">
                                <i class="bi bi-download me-1"></i>Descargar CSV
                            </button>
                        </div>
                    </div>
                    <div id="alertaFechas" class="alert alert-warning mt-3 d-none py-2">
                        <i class="bi bi-exclamation-triangle me-1"></i>
                        La fecha de fin no puede ser anterior a la fecha de inicio.
                    </div>
                </form>
            </div>
        </div>

        <!-- Reportes rápidos por módulo -->
        <div class="row mt-4">
            <div class="col-12">
                <h6 class="text-muted mb-3">Reportes Rápidos — Mes Actual (<?= date('F Y') ?>)</h6>
            </div>

            <?php
                $hoy        = date('Y-m-d');
                $inicioMes  = date('Y-m-01');
                $modulos = [
                    ['tipo' => 'empleados',      'label' => 'Empleados',      'icon' => 'bi-people',          'color' => 'primary'],
                    ['tipo' => 'capacitaciones',  'label' => 'Capacitaciones', 'icon' => 'bi-mortarboard',     'color' => 'success'],
                    ['tipo' => 'inasistencias',   'label' => 'Inasistencias',  'icon' => 'bi-calendar-x',      'color' => 'warning'],
                    ['tipo' => 'evaluaciones',    'label' => 'Evaluaciones',   'icon' => 'bi-clipboard-check', 'color' => 'info'],
                    ['tipo' => 'solicitudes',     'label' => 'Solicitudes',    'icon' => 'bi-envelope-paper',  'color' => 'secondary'],
                ];
            ?>

            <?php foreach ($modulos as $mod): ?>
            <div class="col-md-4 col-6 mb-3">
                <div class="card border-<?= $mod['color'] ?> h-100">
                    <div class="card-body text-center py-4">
                        <i class="bi <?= $mod['icon'] ?> text-<?= $mod['color'] ?>" style="font-size:2.5rem;"></i>
                        <h6 class="mt-2 mb-1"><?= $mod['label'] ?></h6>
                        <small class="text-muted d-block mb-3"><?= date('d/m/Y', strtotime($inicioMes)) ?> — <?= date('d/m/Y') ?></small>
                        <a href="<?= site_url('admin-th/reportes/exportar/' . $mod['tipo'] . '/' . $inicioMes . '/' . $hoy) ?>"
                           class="btn btn-<?= $mod['color'] ?> btn-sm w-100">
                            <i class="bi bi-download me-1"></i>Descargar
                        </a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>

        </div>

        <!-- Reportes históricos rápidos -->
        <div class="card shadow-sm border-0 mt-2">
            <div class="card-header bg-white">
                <h6 class="mb-0 fw-semibold"><i class="bi bi-clock-history me-2"></i>Reportes Históricos Completos</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th>Módulo</th>
                                <th>Período</th>
                                <th class="text-center">Descargar</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $periodos = [
                                    ['label' => 'Último mes',      'inicio' => date('Y-m-d', strtotime('-1 month')), 'fin' => $hoy],
                                    ['label' => 'Último trimestre','inicio' => date('Y-m-d', strtotime('-3 months')),'fin' => $hoy],
                                    ['label' => 'Último semestre', 'inicio' => date('Y-m-d', strtotime('-6 months')),'fin' => $hoy],
                                    ['label' => 'Año actual',      'inicio' => date('Y-01-01'),                      'fin' => $hoy],
                                ];
                                $tipos = ['empleados','capacitaciones','inasistencias','evaluaciones','solicitudes'];
                            ?>
                            <?php foreach ($periodos as $per): ?>
                                <tr>
                                    <td class="fw-semibold"><?= $per['label'] ?></td>
                                    <td class="text-muted">
                                        <small><?= date('d/m/Y', strtotime($per['inicio'])) ?> — <?= date('d/m/Y', strtotime($per['fin'])) ?></small>
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex gap-1 justify-content-center flex-wrap">
                                            <?php foreach ($tipos as $t): ?>
                                                <a href="<?= site_url('admin-th/reportes/exportar/' . $t . '/' . $per['inicio'] . '/' . $per['fin']) ?>"
                                                   class="btn btn-outline-secondary btn-sm"
                                                   title="<?= ucfirst($t) ?>">
                                                    <?= ucfirst(substr($t, 0, 3)) ?>
                                                </a>
                                            <?php endforeach; ?>
                                        </div>
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

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {

    // Fechas por defecto: inicio del mes actual → hoy
    const hoy       = new Date().toISOString().split('T')[0];
    const inicioMes = new Date(new Date().getFullYear(), new Date().getMonth(), 1).toISOString().split('T')[0];
    document.getElementById('fechaInicio').value = inicioMes;
    document.getElementById('fechaFin').value    = hoy;

    // Validación de fechas en tiempo real
    document.getElementById('fechaFin').addEventListener('change', function () {
        const alerta = document.getElementById('alertaFechas');
        if (this.value < document.getElementById('fechaInicio').value) {
            alerta.classList.remove('d-none');
        } else {
            alerta.classList.add('d-none');
        }
    });

    // Envío del formulario → descarga directa
    document.getElementById('formReporte').addEventListener('submit', function (e) {
        e.preventDefault();

        const tipo   = document.getElementById('tipoReporte').value;
        const inicio = document.getElementById('fechaInicio').value;
        const fin    = document.getElementById('fechaFin').value;

        if (!tipo) {
            Swal.fire('Atención', 'Selecciona un tipo de reporte.', 'warning');
            return;
        }
        if (fin < inicio) {
            Swal.fire('Fechas inválidas', 'La fecha de fin no puede ser anterior a la de inicio.', 'error');
            return;
        }

        // Descarga directa — el servidor responde con el CSV
        const url = `<?= site_url('admin-th/reportes/exportar/') ?>${tipo}/${inicio}/${fin}`;
        window.location.href = url;

        Swal.fire({
            icon: 'success',
            title: '¡Descarga iniciada!',
            text: `Reporte de ${tipo} generado correctamente.`,
            timer: 2000,
            showConfirmButton: false
        });
    });
});
</script>
<?= $this->endSection() ?>
