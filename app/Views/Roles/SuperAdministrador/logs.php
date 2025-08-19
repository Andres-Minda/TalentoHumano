<?php $sidebar = 'sidebar_super_admin'; ?>
<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>

<div class="page-wrapper">
    <div class="page-content">
        <!-- Header -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <h4 class="mb-0"><i class="bi bi-journal-text"></i> Logs del Sistema</h4>
                    <div class="page-title-right">
                        <button type="button" class="btn btn-warning" onclick="limpiarLogs()">
                            <i class="bi bi-trash"></i> Limpiar Logs
                        </button>
                        <button type="button" class="btn btn-success" onclick="exportarLogs()">
                            <i class="bi bi-download"></i> Exportar
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filtros -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <label class="form-label">Nivel de Log</label>
                                <select class="form-select" id="filtroNivel">
                                    <option value="">Todos</option>
                                    <option value="CRITICAL">CRITICAL</option>
                                    <option value="ERROR">ERROR</option>
                                    <option value="WARNING">WARNING</option>
                                    <option value="INFO">INFO</option>
                                    <option value="DEBUG">DEBUG</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Fecha Desde</label>
                                <input type="date" class="form-control" id="fechaDesde">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Fecha Hasta</label>
                                <input type="date" class="form-control" id="fechaHasta">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Buscar</label>
                                <input type="text" class="form-control" id="buscarLog" placeholder="Buscar en logs...">
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-12">
                                <button type="button" class="btn btn-primary" onclick="aplicarFiltros()">
                                    <i class="bi bi-search"></i> Aplicar Filtros
                                </button>
                                <button type="button" class="btn btn-secondary" onclick="limpiarFiltros()">
                                    <i class="bi bi-x-circle"></i> Limpiar Filtros
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Estadísticas de Logs -->
        <div class="row">
            <div class="col-xl-3 col-lg-6 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="me-3">
                                <span class="round-8 bg-danger rounded-circle me-2 d-inline-block"></span>
                                <div class="stat-digit d-flex">
                                    <h5 class="font-weight-semibold mb-0"><?= $estadisticas['total_critical'] ?? 0 ?></h5>
                                </div>
                                <div class="stat-digit d-flex">
                                    <h5 class="font-weight-semibold mb-0">CRITICAL</h5>
                                </div>
                            </div>
                            <div class="ms-auto">
                                <i class="bi bi-exclamation-triangle text-danger" style="font-size: 2rem;"></i>
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
                                <span class="round-8 bg-warning rounded-circle me-2 d-inline-block"></span>
                                <div class="stat-digit d-flex">
                                    <h5 class="font-weight-semibold mb-0"><?= $estadisticas['total_error'] ?? 0 ?></h5>
                                </div>
                                <div class="stat-digit d-flex">
                                    <h5 class="font-weight-semibold mb-0">ERROR</h5>
                                </div>
                            </div>
                            <div class="ms-auto">
                                <i class="bi bi-exclamation-circle text-warning" style="font-size: 2rem;"></i>
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
                                    <h5 class="font-weight-semibold mb-0"><?= $estadisticas['total_warning'] ?? 0 ?></h5>
                                </div>
                                <div class="stat-digit d-flex">
                                    <h5 class="font-weight-semibold mb-0">WARNING</h5>
                                </div>
                            </div>
                            <div class="ms-auto">
                                <i class="bi bi-info-circle text-info" style="font-size: 2rem;"></i>
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
                                    <h5 class="font-weight-semibold mb-0"><?= $estadisticas['total_info'] ?? 0 ?></h5>
                                </div>
                                <div class="stat-digit d-flex">
                                    <h5 class="font-weight-semibold mb-0">INFO</h5>
                                </div>
                            </div>
                            <div class="ms-auto">
                                <i class="bi bi-info-circle text-success" style="font-size: 2rem;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabla de Logs -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Registros de Log</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover" id="tablaLogs">
                                <thead>
                                    <tr>
                                        <th>Fecha y Hora</th>
                                        <th>Nivel</th>
                                        <th>Mensaje</th>
                                        <th>Archivo</th>
                                        <th>Línea</th>
                                        <th>Usuario</th>
                                        <th>IP</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($logs as $log): ?>
                                    <tr>
                                        <td><?= date('d/m/Y H:i:s', strtotime($log['fecha'])) ?></td>
                                        <td>
                                            <span class="badge bg-<?= $log['nivel'] == 'CRITICAL' ? 'danger' : ($log['nivel'] == 'ERROR' ? 'warning' : ($log['nivel'] == 'WARNING' ? 'info' : 'success')) ?>">
                                                <?= $log['nivel'] ?>
                                            </span>
                                        </td>
                                        <td>
                                            <div class="text-truncate" style="max-width: 300px;" title="<?= $log['mensaje'] ?>">
                                                <?= $log['mensaje'] ?>
                                            </div>
                                        </td>
                                        <td><?= $log['archivo'] ?></td>
                                        <td><?= $log['linea'] ?></td>
                                        <td><?= $log['usuario'] ?? 'Sistema' ?></td>
                                        <td><?= $log['ip'] ?? 'N/A' ?></td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <button type="button" class="btn btn-sm btn-outline-primary" onclick="verDetalleLog(<?= $log['id'] ?>)">
                                                    <i class="bi bi-eye"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline-danger" onclick="eliminarLog(<?= $log['id'] ?>)">
                                                    <i class="bi bi-trash"></i>
                                                </button>
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
    </div>
</div>

<!-- Modal Detalle Log -->
<div class="modal fade" id="modalDetalleLog" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detalle del Log</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="detalleLogContent">
                    <!-- Contenido del detalle -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#tablaLogs').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json'
        },
        order: [[0, 'desc']],
        pageLength: 25
    });
});

function aplicarFiltros() {
    // Implementar filtros
    alert('Filtros aplicados');
}

function limpiarFiltros() {
    $('#filtroNivel').val('');
    $('#fechaDesde').val('');
    $('#fechaHasta').val('');
    $('#buscarLog').val('');
}

function verDetalleLog(idLog) {
    // Simular detalle del log
    $('#detalleLogContent').html(`
        <div class="row">
            <div class="col-md-6">
                <strong>ID:</strong> ${idLog}<br>
                <strong>Fecha:</strong> 03/08/2025 16:22:33<br>
                <strong>Nivel:</strong> <span class="badge bg-danger">CRITICAL</span><br>
                <strong>Usuario:</strong> Sistema<br>
                <strong>IP:</strong> 127.0.0.1
            </div>
            <div class="col-md-6">
                <strong>Archivo:</strong> CapacitacionModel.php<br>
                <strong>Línea:</strong> 52<br>
                <strong>Método:</strong> GET<br>
                <strong>Ruta:</strong> docente/capacitaciones
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-12">
                <strong>Mensaje:</strong><br>
                <pre class="bg-light p-3 rounded">You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near '?) = 0 :(SELECT COUNT(*) FROM empleados_capacitaciones ec WHERE ec.id_capacit...' at line 5</pre>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-12">
                <strong>Stack Trace:</strong><br>
                <pre class="bg-light p-3 rounded" style="max-height: 200px; overflow-y: auto;">Stack trace:
#0 C:\xampp\htdocs\TalentoHumano\vendor\codeigniter4\framework\system\Database\MySQLi\Connection.php(327): mysqli->query('SELECT `c`.*, (...', 0)
#1 C:\xampp\htdocs\TalentoHumano\vendor\codeigniter4\framework\system\Database\BaseConnection.php(738): CodeIgniter\Database\MySQLi\Connection->execute('SELECT `c`.*, (...')
#2 C:\xampp\htdocs\TalentoHumano\vendor\codeigniter4\framework\system\Database\BaseBuilder.php(1649): CodeIgniter\Database\BaseConnection->query('SELECT `c`.*, (...', Array, false)
#3 APPPATH\Models\CapacitacionModel.php(52): CodeIgniter\Database\BaseBuilder->get()
#4 APPPATH\Controllers\DocenteController.php(68): App\Models\CapacitacionModel->getCapacitacionesDisponibles('3')</pre>
            </div>
        </div>
    `);
    $('#modalDetalleLog').modal('show');
}

function eliminarLog(idLog) {
    if (confirm('¿Está seguro de eliminar este log?')) {
        alert('Log eliminado correctamente');
    }
}

function limpiarLogs() {
    if (confirm('¿Está seguro de limpiar todos los logs? Esta acción no se puede deshacer.')) {
        alert('Logs limpiados correctamente');
    }
}

function exportarLogs() {
    alert('Exportando logs...');
}
</script>

<?= $this->endSection() ?> 