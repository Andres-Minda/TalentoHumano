<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>

<div class="page-wrapper">
    <div class="page-content">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <h4 class="page-title mb-0">Gestión de Inasistencias</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="<?= base_url('admin-th/dashboard') ?>">Dashboard</a></li>
                            <li class="breadcrumb-item active">Inasistencias</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <!-- Estadísticas Generales -->
        <div class="row">
            <div class="col-xl-3 col-md-6">
                <div class="card mini-stats-wid">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-grow-1">
                                <p class="text-muted fw-medium">Total Inasistencias</p>
                                <h4 class="mb-0"><?= $estadisticas['total'] ?? 0 ?></h4>
                                <p class="text-muted mt-1 mb-0">
                                    <span class="text-<?= ($estadisticas['tendencia_total'] ?? 0) >= 0 ? 'success' : 'danger' ?>">
                                        <i class="bi bi-arrow-<?= ($estadisticas['tendencia_total'] ?? 0) >= 0 ? 'up' : 'down' ?>"></i>
                                        <?= abs($estadisticas['tendencia_total'] ?? 0) ?>%
                                    </span>
                                    vs mes anterior
                                </p>
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
                                <p class="text-muted fw-medium">Pendientes de Revisión</p>
                                <h4 class="mb-0 text-warning"><?= $estadisticas['pendientes'] ?? 0 ?></h4>
                                <p class="text-muted mt-1 mb-0">
                                    Requieren atención
                                </p>
                            </div>
                            <div class="flex-shrink-0 align-self-center">
                                <div class="mini-stat-icon avatar-sm rounded-circle bg-warning">
                                    <span class="avatar-title">
                                        <i class="bi bi-clock font-size-24"></i>
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
                                <h4 class="mb-0 text-danger"><?= $estadisticas['sin_justificar'] ?? 0 ?></h4>
                                <p class="text-muted mt-1 mb-0">
                                    Críticas
                                </p>
                            </div>
                            <div class="flex-shrink-0 align-self-center">
                                <div class="mini-stat-icon avatar-sm rounded-circle bg-danger">
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
                                <p class="text-muted fw-medium">Tasa de Justificación</p>
                                <h4 class="mb-0 text-success"><?= $estadisticas['tasa_justificacion'] ?? 0 ?>%</h4>
                                <p class="text-muted mt-1 mb-0">
                                    Promedio general
                                </p>
                            </div>
                            <div class="flex-shrink-0 align-self-center">
                                <div class="mini-stat-icon avatar-sm rounded-circle bg-success">
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

        <!-- Acciones Rápidas -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-lightning text-warning me-2"></i>
                            Acciones Rápidas
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <a href="<?= base_url('admin-th/inasistencias/registrar') ?>" 
                                   class="btn btn-primary w-100 h-100 d-flex flex-column align-items-center justify-content-center">
                                    <i class="bi bi-plus-circle fs-1 mb-2"></i>
                                    <span>Registrar Nueva Inasistencia</span>
                                </a>
                            </div>
                            <div class="col-md-3 mb-3">
                                <a href="<?= base_url('admin-th/inasistencias/revisar-justificaciones') ?>" 
                                   class="btn btn-warning w-100 h-100 d-flex flex-column align-items-center justify-content-center">
                                    <i class="bi bi-clipboard-check fs-1 mb-2"></i>
                                    <span>Revisar Justificaciones</span>
                                    <?php if (($estadisticas['pendientes'] ?? 0) > 0): ?>
                                        <span class="badge bg-danger position-absolute top-0 start-100 translate-middle">
                                            <?= $estadisticas['pendientes'] ?>
                                        </span>
                                    <?php endif; ?>
                                </a>
                            </div>
                            <div class="col-md-3 mb-3">
                                <a href="<?= base_url('admin-th/inasistencias/reportes') ?>" 
                                   class="btn btn-info w-100 h-100 d-flex flex-column align-items-center justify-content-center">
                                    <i class="bi bi-bar-chart fs-1 mb-2"></i>
                                    <span>Generar Reportes</span>
                                </a>
                            </div>
                            <div class="col-md-3 mb-3">
                                <a href="<?= base_url('admin-th/inasistencias/politicas') ?>" 
                                   class="btn btn-success w-100 h-100 d-flex flex-column align-items-center justify-content-center">
                                    <i class="bi bi-shield-check fs-1 mb-2"></i>
                                    <span>Gestionar Políticas</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Alertas y Notificaciones -->
        <?php if (isset($alertas) && !empty($alertas)): ?>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-exclamation-triangle text-danger me-2"></i>
                            Alertas y Notificaciones
                        </h5>
                    </div>
                    <div class="card-body">
                        <?php foreach ($alertas as $alerta): ?>
                            <div class="alert alert-<?= $alerta['tipo'] ?> alert-dismissible fade show" role="alert">
                                <i class="bi bi-<?= $alerta['icono'] ?> me-2"></i>
                                <strong><?= $alerta['titulo'] ?></strong><br>
                                <?= $alerta['mensaje'] ?>
                                <?php if (isset($alerta['accion'])): ?>
                                    <div class="mt-2">
                                        <a href="<?= $alerta['accion']['url'] ?>" class="btn btn-sm btn-<?= $alerta['accion']['clase'] ?>">
                                            <?= $alerta['accion']['texto'] ?>
                                        </a>
                                    </div>
                                <?php endif; ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Inasistencias Recientes -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-clock-history text-info me-2"></i>
                            Inasistencias Recientes
                        </h5>
                        <a href="<?= base_url('admin-th/inasistencias/listar') ?>" class="btn btn-outline-primary btn-sm">
                            Ver Todas
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-centered table-nowrap mb-0">
                                <thead>
                                    <tr>
                                        <th>Empleado</th>
                                        <th>Fecha</th>
                                        <th>Tipo</th>
                                        <th>Estado</th>
                                        <th>Departamento</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (isset($inasistencias_recientes) && !empty($inasistencias_recientes)): ?>
                                        <?php foreach ($inasistencias_recientes as $inasistencia): ?>
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar-sm rounded-circle bg-light text-dark d-flex align-items-center justify-content-center me-2">
                                                            <?= strtoupper(substr($inasistencia['empleado_nombre'] ?? 'N', 0, 1)) ?>
                                                        </div>
                                                        <div>
                                                            <h6 class="mb-0"><?= $inasistencia['empleado_nombre'] ?? 'N/A' ?></h6>
                                                            <small class="text-muted"><?= $inasistencia['empleado_tipo'] ?? 'N/A' ?></small>
                                                        </div>
                                                    </div>
                                                </td>
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
                                                    <span class="badge bg-secondary">
                                                        <?= $inasistencia['departamento'] ?? 'N/A' ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <button type="button" class="btn btn-sm btn-outline-info" 
                                                                onclick="verDetalle(<?= $inasistencia['id'] ?>)">
                                                            <i class="bi bi-eye"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-sm btn-outline-warning" 
                                                                onclick="editarInasistencia(<?= $inasistencia['id'] ?>)">
                                                            <i class="bi bi-pencil"></i>
                                                        </button>
                                                        <?php if ($inasistencia['estado'] === 'PENDIENTE'): ?>
                                                            <button type="button" class="btn btn-sm btn-outline-success" 
                                                                    onclick="revisarJustificacion(<?= $inasistencia['id'] ?>)">
                                                                <i class="bi bi-check-circle"></i>
                                                            </button>
                                                        <?php endif; ?>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="6" class="text-center text-muted">
                                                <i class="bi bi-inbox fs-1"></i>
                                                <p class="mt-2">No hay inasistencias recientes</p>
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

        <!-- Resumen por Departamento -->
        <div class="row">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-building text-primary me-2"></i>
                            Inasistencias por Departamento
                        </h5>
                    </div>
                    <div class="card-body">
                        <canvas id="graficoDepartamentos" width="400" height="200"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-calendar-week text-success me-2"></i>
                            Tendencia Semanal
                        </h5>
                    </div>
                    <div class="card-body">
                        <canvas id="graficoTendencia" width="400" height="200"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Empleados con Más Inasistencias -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-exclamation-triangle text-warning me-2"></i>
                            Empleados con Más Inasistencias (Este Mes)
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-centered table-nowrap mb-0">
                                <thead>
                                    <tr>
                                        <th>Empleado</th>
                                        <th>Departamento</th>
                                        <th>Total Inasistencias</th>
                                        <th>Sin Justificar</th>
                                        <th>Porcentaje Justificadas</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (isset($empleados_criticos) && !empty($empleados_criticos)): ?>
                                        <?php foreach ($empleados_criticos as $empleado): ?>
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar-sm rounded-circle bg-light text-dark d-flex align-items-center justify-content-center me-2">
                                                            <?= strtoupper(substr($empleado['nombre'] ?? 'N', 0, 1)) ?>
                                                        </div>
                                                        <div>
                                                            <h6 class="mb-0"><?= $empleado['nombre'] ?? 'N/A' ?></h6>
                                                            <small class="text-muted"><?= $empleado['tipo'] ?? 'N/A' ?></small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="badge bg-secondary">
                                                        <?= $empleado['departamento'] ?? 'N/A' ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="badge bg-primary fs-6">
                                                        <?= $empleado['total_inasistencias'] ?? 0 ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="badge bg-warning fs-6">
                                                        <?= $empleado['sin_justificar'] ?? 0 ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <?php 
                                                    $porcentaje = $empleado['total_inasistencias'] > 0 ? 
                                                        round(($empleado['justificadas'] / $empleado['total_inasistencias']) * 100) : 0;
                                                    $clase = $porcentaje >= 80 ? 'bg-success' : ($porcentaje >= 60 ? 'bg-warning' : 'bg-danger');
                                                    ?>
                                                    <span class="badge <?= $clase ?> fs-6">
                                                        <?= $porcentaje ?>%
                                                    </span>
                                                </td>
                                                <td>
                                                    <?php
                                                    $estadoClase = '';
                                                    $estadoTexto = '';
                                                    if ($empleado['sin_justificar'] > 3) {
                                                        $estadoClase = 'bg-danger';
                                                        $estadoTexto = 'Crítico';
                                                    } elseif ($empleado['sin_justificar'] > 1) {
                                                        $estadoClase = 'bg-warning';
                                                        $estadoTexto = 'Atención';
                                                    } else {
                                                        $estadoClase = 'bg-success';
                                                        $estadoTexto = 'Normal';
                                                    }
                                                    ?>
                                                    <span class="badge <?= $estadoClase ?>">
                                                        <?= $estadoTexto ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <button type="button" class="btn btn-sm btn-outline-info" 
                                                                onclick="verEmpleado(<?= $empleado['id_empleado'] ?? 0 ?>)">
                                                            <i class="bi bi-eye"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-sm btn-outline-warning" 
                                                                onclick="contactarEmpleado(<?= $empleado['id_empleado'] ?? 0 ?>)">
                                                            <i class="bi bi-chat"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-sm btn-outline-danger" 
                                                                onclick="generarReporte(<?= $empleado['id_empleado'] ?? 0 ?>)">
                                                            <i class="bi bi-file-earmark-text"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="7" class="text-center text-muted">
                                                <i class="bi bi-check-circle text-success fs-1"></i>
                                                <p class="mt-2">Todos los empleados están dentro de los parámetros normales</p>
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

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Gráfico por departamentos
    const ctxDepartamentos = document.getElementById('graficoDepartamentos').getContext('2d');
    new Chart(ctxDepartamentos, {
        type: 'doughnut',
        data: {
            labels: <?= json_encode($graficos['departamentos']['labels'] ?? []) ?>,
            datasets: [{
                data: <?= json_encode($graficos['departamentos']['valores'] ?? []) ?>,
                backgroundColor: ['#007bff', '#28a745', '#ffc107', '#dc3545', '#6c757d', '#17a2b8'],
                borderWidth: 2,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });

    // Gráfico de tendencia
    const ctxTendencia = document.getElementById('graficoTendencia').getContext('2d');
    new Chart(ctxTendencia, {
        type: 'line',
        data: {
            labels: <?= json_encode($graficos['tendencia']['labels'] ?? []) ?>,
            datasets: [{
                label: 'Inasistencias',
                data: <?= json_encode($graficos['tendencia']['valores'] ?? []) ?>,
                borderColor: '#007bff',
                backgroundColor: 'rgba(0, 123, 255, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });
});

function verDetalle(id) {
    window.location.href = '<?= base_url('admin-th/inasistencias/ver/') ?>' + id;
}

function editarInasistencia(id) {
    window.location.href = '<?= base_url('admin-th/inasistencias/editar/') ?>' + id;
}

function revisarJustificacion(id) {
    window.location.href = '<?= base_url('admin-th/inasistencias/revisar-justificacion/') ?>' + id;
}

function verEmpleado(id) {
    window.location.href = '<?= base_url('admin-th/empleados/ver/') ?>' + id;
}

function contactarEmpleado(id) {
    // Implementar modal de contacto
    alert('Función de contacto en desarrollo');
}

function generarReporte(id) {
    window.location.href = '<?= base_url('admin-th/inasistencias/reporte-empleado/') ?>' + id;
}
</script>

<style>
.avatar-sm {
    width: 32px;
    height: 32px;
    font-size: 14px;
    font-weight: 600;
}

.btn-group .btn {
    margin-right: 2px;
}

.btn-group .btn:last-child {
    margin-right: 0;
}

.card-header .btn {
    font-size: 0.875rem;
}

.table th {
    background-color: #f8f9fa;
    border-top: none;
}

.badge {
    font-size: 0.875em;
}

.mini-stats-wid .avatar-title {
    width: 48px;
    height: 48px;
    font-size: 24px;
}
</style>

<?= $this->endSection() ?>
