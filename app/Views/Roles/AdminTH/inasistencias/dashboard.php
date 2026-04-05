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
                            <div class="col-md-4 mb-3">
                                <a href="<?= base_url('admin-th/inasistencias/registrar') ?>" 
                                   class="btn btn-primary w-100 h-100 d-flex flex-column align-items-center justify-content-center">
                                    <i class="bi bi-plus-circle fs-1 mb-2"></i>
                                    <span>Registrar Nueva Inasistencia</span>
                                </a>
                            </div>
                            <div class="col-md-4 mb-3">
                                <a href="<?= base_url('admin-th/inasistencias/listar') ?>" 
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
                            <div class="col-md-4 mb-3">
                                <a href="<?= base_url('admin-th/inasistencias/reporte') ?>" 
                                   class="btn btn-info w-100 h-100 d-flex flex-column align-items-center justify-content-center">
                                    <i class="bi bi-bar-chart fs-1 mb-2"></i>
                                    <span>Generar Reportes</span>
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
                        <div class="d-flex align-items-center">
                            <button type="button" class="btn btn-danger btn-sm me-2 d-none" id="btnEliminarSeleccion" onclick="eliminarSeleccionados()">
                                <i class="ti ti-trash"></i> Eliminar ( <span id="contadorSeleccion">0</span> )
                            </button>
                            <a href="<?= site_url('admin-th/inasistencias/listar') ?>" class="btn btn-outline-primary btn-sm">
                                Ver Todas
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-centered table-nowrap mb-0">
                                <thead>
                                    <tr>
                                        <!-- Estándar Modular: Checkbox Maestro -->
                                        <th style="width:40px;">
                                            <input type="checkbox" class="form-check-input" id="checkAll" onchange="toggleAll(this)">
                                        </th>
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
                                                    <input type="checkbox" class="form-check-input chk-item" value="<?= $inasistencia['id'] ?>" onchange="actualizarBotonEliminar()">
                                                </td>
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
                                                                onclick="verDetalle(<?= $inasistencia['id'] ?>)"
                                                                data-bs-toggle="tooltip" data-bs-placement="top" title="Ver Detalles">
                                                            <i class="bi bi-eye"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-sm btn-outline-warning" 
                                                                onclick="editarInasistencia(<?= $inasistencia['id'] ?>)"
                                                                data-bs-toggle="tooltip" data-bs-placement="top" title="Editar">
                                                            <i class="bi bi-pencil"></i>
                                                        </button>
                                                        <?php if ($inasistencia['estado'] === 'PENDIENTE'): ?>
                                                            <button type="button" class="btn btn-sm btn-outline-success" 
                                                                    onclick="revisarJustificacion(<?= $inasistencia['id'] ?>)"
                                                                    data-bs-toggle="tooltip" data-bs-placement="top" title="Revisar Justificación">
                                                                <i class="bi bi-check-circle"></i>
                                                            </button>
                                                        <?php endif; ?>
                                                        <button type="button" class="btn btn-sm btn-outline-danger" 
                                                                onclick="eliminarInasistencia(<?= $inasistencia['id'] ?>)"
                                                                data-bs-toggle="tooltip" data-bs-placement="top" title="Eliminar">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
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
                        <div id="graficoDepartamentos" style="min-height:280px;"></div>
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
                        <div id="graficoTendencia" style="min-height:280px;"></div>
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
                                                        <button type="button" class="btn btn-sm btn-outline-info btn-ver-perfil" 
                                                                data-id="<?= $empleado['empleado_id'] ?? 0 ?>"
                                                                data-bs-toggle="tooltip" data-bs-placement="top" title="Ver Perfil">
                                                            <i class="bi bi-eye"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-sm btn-outline-success"
                                                                onclick="verDetallesEmpleadoMes(<?= (int)($empleado['empleado_id'] ?? 0) ?>, '<?= esc($empleado['nombre'] ?? 'Empleado') ?>')"
                                                                data-bs-toggle="tooltip" data-bs-placement="top" title="Ver Inasistencias del Mes">
                                                            <i class="bi bi-calendar2-x"></i>
                                                        </button>
                                                        <a href="mailto:<?= $empleado['correo'] ?? '' ?>?subject=Sobre tu inasistencia" 
                                                                class="btn btn-sm btn-outline-warning" 
                                                                data-bs-toggle="tooltip" data-bs-placement="top" title="Contactar">
                                                            <i class="bi bi-chat"></i>
                                                        </a>
                                                        <a href="<?= site_url('admin-th/inasistencias/reporte-empleado/' . ($empleado['empleado_id'] ?? 0)) ?>" 
                                                           target="_blank"
                                                           class="btn btn-sm btn-outline-danger" 
                                                           data-bs-toggle="tooltip" data-bs-placement="top" title="Generar Reporte">
                                                            <i class="bi bi-file-earmark-text"></i>
                                                        </a>
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

<!-- Modal Ver Detalles (Inasistencia) -->
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

<!-- Modal Ver Perfil de Empleado -->
<div class="modal fade" id="modalPerfilEmpleado" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="bi bi-person-badge me-2"></i>Perfil del Empleado</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="contenidoPerfil">
                <!-- Fallback/spinner -->
                <div class="text-center py-4 text-primary">
                    <div class="spinner-border" role="status"></div>
                    <p class="mt-2">Cargando perfil...</p>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<!-- SweetAlert2 Plugin -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- ApexCharts -->
<script src="https://cdn.jsdelivr.net/npm/apexcharts@3.49.0/dist/apexcharts.min.js"></script>
<script>
// ── Datos inyectados desde PHP ────────────────────────────────────────────────
const graficosData = {
    departamentos: {
        labels:  <?= json_encode(
            empty($graficos['departamentos']['labels'])
                ? ['Sin datos']
                : $graficos['departamentos']['labels']
        ) ?>,
        valores: <?= json_encode(
            empty($graficos['departamentos']['valores'])
                ? [0]
                : $graficos['departamentos']['valores']
        ) ?>
    },
    tendencia: {
        labels:  <?= json_encode($graficos['tendencia']['labels']) ?>,
        valores: <?= json_encode($graficos['tendencia']['valores']) ?>
    }
};

document.addEventListener('DOMContentLoaded', function() {

    // ── Gráfico de Dona: Inasistencias por Departamento ───────────────────────
    const elDonut = document.getElementById('graficoDepartamentos');
    if (elDonut) {
        new ApexCharts(elDonut, {
            series: graficosData.departamentos.valores,
            labels: graficosData.departamentos.labels,
            chart: {
                type: 'donut',
                height: 280,
                toolbar: { show: false },
                animations: { enabled: true, speed: 600 }
            },
            colors: ['#4361ee','#3bc9db','#f72585','#4cc9f0','#7209b7','#f3722c','#43aa8b','#90be6d'],
            dataLabels: {
                enabled: true,
                formatter: (val) => val.toFixed(1) + '%'
            },
            legend: { position: 'bottom', fontSize: '13px' },
            plotOptions: {
                pie: {
                    donut: {
                        size: '60%',
                        labels: {
                            show: true,
                            total: {
                                show: true,
                                label: 'Total',
                                fontSize: '14px',
                                fontWeight: 600,
                                formatter: (w) => w.globals.seriesTotals.reduce((a, b) => a + b, 0)
                            }
                        }
                    }
                }
            },
            tooltip: { y: { formatter: (val) => val + ' inasistencia(s)' } },
            noData: { text: 'Sin datos disponibles' }
        }).render();
    }

    // ── Gráfico de Área: Tendencia Semanal ────────────────────────────────────
    const elArea = document.getElementById('graficoTendencia');
    if (elArea) {
        new ApexCharts(elArea, {
            series: [{ name: 'Inasistencias', data: graficosData.tendencia.valores }],
            chart: {
                type: 'area',
                height: 280,
                toolbar: { show: false },
                zoom: { enabled: false },
                animations: { enabled: true, speed: 600 }
            },
            colors: ['#4361ee'],
            fill: {
                type: 'gradient',
                gradient: { shadeIntensity: 1, opacityFrom: 0.45, opacityTo: 0.05, stops: [0, 100] }
            },
            stroke: { curve: 'smooth', width: 2 },
            markers: { size: 5, hover: { size: 7 } },
            xaxis: {
                categories: graficosData.tendencia.labels,
                labels: { style: { fontSize: '12px' } }
            },
            yaxis: {
                min: 0,
                tickAmount: 4,
                labels: { formatter: (val) => Math.floor(val) }
            },
            dataLabels: { enabled: false },
            grid: { borderColor: '#f1f1f1' },
            tooltip: {
                x: { show: true },
                y: { formatter: (val) => val + ' inasistencia(s)' }
            },
            noData: { text: 'Sin datos disponibles' }
        }).render();
    }

    // ── Tooltips Bootstrap ────────────────────────────────────────────────────
    document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => {
        new bootstrap.Tooltip(el);
    });

    // ── Perfil de Empleado (modal) ────────────────────────────────────────────
    const botonesVerPerfil = document.querySelectorAll('.btn-ver-perfil');

    botonesVerPerfil.forEach(btn => {
        btn.addEventListener('click', function() {
            const empleadoId = this.getAttribute('data-id');
            const modal      = new bootstrap.Modal(document.getElementById('modalPerfilEmpleado'));
            const contenido  = document.getElementById('contenidoPerfil');

            contenido.innerHTML = `
                <div class="text-center py-4 text-primary">
                    <div class="spinner-border" role="status"></div>
                    <p class="mt-2">Cargando perfil...</p>
                </div>`;
            modal.show();

            // Llamada Fetch
            fetch(`<?= site_url('admin-th/inasistencias/perfil-empleado/') ?>${empleadoId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        contenido.innerHTML = `
                            <div class="text-center mb-4">
                                <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px; font-size: 2rem;">
                                    ${data.nombre_completo.charAt(0)}
                                </div>
                                <h5 class="mb-0 text-dark font-weight-bold">${data.nombre_completo}</h5>
                                <span class="badge bg-secondary mt-1">${data.tipo_empleado}</span>
                            </div>
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    <span class="text-muted"><i class="bi bi-card-text me-2"></i>Documento</span>
                                    <span class="font-weight-medium">${data.cedula}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    <span class="text-muted"><i class="bi bi-building me-2"></i>Departamento</span>
                                    <span class="font-weight-medium">${data.departamento}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    <span class="text-muted"><i class="bi bi-envelope me-2"></i>Correo</span>
                                    <span class="font-weight-medium">${data.correo}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    <span class="text-muted"><i class="bi bi-telephone me-2"></i>Teléfono</span>
                                    <span class="font-weight-medium">${data.telefono}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    <span class="text-muted"><i class="bi bi-calendar-check me-2"></i>Contratación</span>
                                    <span class="font-weight-medium">${data.fecha_contratacion}</span>
                                </li>
                            </ul>
                        `;
                    } else {
                        contenido.innerHTML = `<div class="alert alert-danger text-center"><i class="bi bi-exclamation-triangle me-2"></i>${data.message || 'Error al cargar perfil.'}</div>`;
                    }
                })
                .catch(error => {
                    console.error('Error fetching profile:', error);
                    contenido.innerHTML = `<div class="alert alert-danger text-center"><i class="bi bi-wifi-off me-2"></i>Error de conexión al servidor.</div>`;
                });
        });
    });
});

function verDetallesEmpleadoMes(idEmpleado, nombreEmpleado) {
    Swal.fire({
        title: `<i class="bi bi-calendar2-x me-2"></i>Inasistencias del Mes`,
        html: `<p class="text-muted mb-3">Cargando registros de <strong>${nombreEmpleado}</strong>...</p>
               <div class="d-flex justify-content-center">
                   <div class="spinner-border text-primary" role="status"></div>
               </div>`,
        showConfirmButton: false,
        allowOutsideClick: false,
        width: '700px',
        didOpen: () => {
            fetch(`<?= site_url('admin-th/inasistencias/detalles-mes/') ?>${idEmpleado}`)
                .then(res => res.json())
                .then(response => {
                    if (!response.success) {
                        Swal.update({
                            html: `<div class="alert alert-danger"><i class="bi bi-exclamation-triangle me-2"></i>${response.message || 'Error al cargar los datos.'}</div>`,
                            showConfirmButton: true,
                            confirmButtonText: 'Cerrar'
                        });
                        return;
                    }

                    const registros = response.data;

                    if (registros.length === 0) {
                        Swal.update({
                            html: `<div class="text-center py-3">
                                       <i class="bi bi-check-circle text-success" style="font-size:3rem;"></i>
                                       <p class="mt-3 text-muted">
                                           <strong>${nombreEmpleado}</strong> no tiene inasistencias registradas este mes.
                                       </p>
                                   </div>`,
                            showConfirmButton: true,
                            confirmButtonText: 'Cerrar',
                            confirmButtonColor: '#6c757d'
                        });
                        return;
                    }

                    const filas = registros.map(r => {
                        const estadoBadge = r.justificada == 1
                            ? `<span class="badge bg-success">Justificada</span>`
                            : `<span class="badge bg-warning text-dark">Sin Justificar</span>`;
                        const tipoBadge = `<span class="badge bg-info">${r.tipo_inasistencia ?? 'N/A'}</span>`;
                        const fecha = r.fecha_inasistencia
                            ? new Date(r.fecha_inasistencia + 'T00:00:00').toLocaleDateString('es-EC', { day:'2-digit', month:'short', year:'numeric' })
                            : 'N/A';
                        const motivo = r.motivo ? r.motivo.substring(0, 60) + (r.motivo.length > 60 ? '…' : '') : '<em class="text-muted">Sin motivo</em>';

                        return `<tr>
                                    <td>${fecha}</td>
                                    <td>${tipoBadge}</td>
                                    <td style="max-width:220px;">${motivo}</td>
                                    <td>${estadoBadge}</td>
                                </tr>`;
                    }).join('');

                    Swal.update({
                        title: `<i class="bi bi-calendar2-x me-2"></i>Inasistencias del Mes`,
                        html: `<p class="text-muted mb-3">Registros de <strong>${nombreEmpleado}</strong> — 
                               <span class="badge bg-primary">${registros.length} inasistencia(s)</span></p>
                               <div class="table-responsive">
                                   <table class="table table-sm table-hover table-bordered align-middle mb-0">
                                       <thead class="table-dark">
                                           <tr>
                                               <th>Fecha</th>
                                               <th>Tipo</th>
                                               <th>Motivo</th>
                                               <th>Estado</th>
                                           </tr>
                                       </thead>
                                       <tbody>${filas}</tbody>
                                   </table>
                               </div>`,
                        showConfirmButton: true,
                        confirmButtonText: 'Cerrar',
                        confirmButtonColor: '#4361ee'
                    });
                })
                .catch(() => {
                    Swal.update({
                        html: `<div class="alert alert-danger"><i class="bi bi-wifi-off me-2"></i>Error de conexión con el servidor.</div>`,
                        showConfirmButton: true,
                        confirmButtonText: 'Cerrar'
                    });
                });
        }
    });
}

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

function revisarJustificacion(id) {
    // Obtener datos de la justificación
    fetch(`<?= site_url('admin-th/inasistencias/obtener-justificacion') ?>?id=${id}`)
        .then(res => res.json())
        .then(response => {
            if (!response.success) {
                Swal.fire('Error', response.message || 'No se pudo cargar la justificación', 'error');
                return;
            }
            
            const data = response.data;
            const archivoLink = data.archivo_justificacion ? 
                `<p class="mb-3"><strong>Documento adjunto:</strong><br>
                 <a href="${data.archivo_justificacion}" target="_blank" class="btn btn-sm btn-outline-primary">
                    <i class="bi bi-file-earmark-pdf me-1"></i>Ver documento en Google Drive
                 </a></p>` : 
                '<p class="text-muted">Sin documento adjunto</p>';
            
            Swal.fire({
                title: 'Revisar Justificación',
                html: `
                    <div class="text-start">
                        <p class="mb-2"><strong>Empleado:</strong> ${data.apellidos} ${data.nombres}</p>
                        <p class="mb-2"><strong>Fecha:</strong> ${data.fecha_inasistencia}</p>
                        <p class="mb-2"><strong>Tipo:</strong> <span class="badge bg-info">${data.tipo_inasistencia}</span></p>
                        <hr>
                        <p class="mb-2"><strong>Motivo:</strong></p>
                        <div class="alert alert-light">${data.motivo || 'Sin motivo especificado'}</div>
                        ${archivoLink}
                        <hr>
                        <label for="observaciones" class="form-label"><strong>Observaciones (opcional):</strong></label>
                        <textarea id="observaciones" class="form-control" rows="3" placeholder="Agregar comentarios sobre la revisión..."></textarea>
                    </div>
                `,
                width: '600px',
                showCancelButton: true,
                showDenyButton: true,
                confirmButtonText: '<i class="bi bi-check-circle me-1"></i>Aprobar',
                denyButtonText: '<i class="bi bi-x-circle me-1"></i>Rechazar',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#28a745',
                denyButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                preConfirm: () => {
                    return document.getElementById('observaciones').value;
                },
                preDeny: () => {
                    return document.getElementById('observaciones').value;
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    procesarRevision(id, 'aprobar', result.value);
                } else if (result.isDenied) {
                    procesarRevision(id, 'rechazar', result.value);
                }
            });
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire('Error', 'Error de conexión al cargar la justificación', 'error');
        });
}

function procesarRevision(inasistenciaId, accion, observaciones) {
    Swal.fire({
        title: 'Procesando...',
        text: 'Guardando revisión',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
    
    const formData = new FormData();
    formData.append('inasistencia_id', inasistenciaId);
    formData.append('accion', accion);
    formData.append('observaciones', observaciones);
    
    fetch('<?= site_url('admin-th/inasistencias/revisar-justificacion') ?>', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: '¡Éxito!',
                text: data.message,
                timer: 2000,
                showConfirmButton: false
            }).then(() => {
                window.location.reload();
            });
        } else {
            Swal.fire('Error', data.message || 'No se pudo procesar la revisión', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire('Error', 'Error de conexión al procesar la revisión', 'error');
    });
}


    Swal.fire({
        title: 'En desarrollo',
        text: 'Esta funcionalidad estará disponible próximamente.',
        icon: 'info',
        confirmButtonColor: '#007bff'
    });
}

// ==================== [ESTANDARIZACIÓN] LÓGICA DE BORRADO MASIVO ====================
function toggleAll(master) {
    const checkboxes = document.querySelectorAll('.chk-item');
    checkboxes.forEach(chk => {
        if (chk.closest('tr').style.display !== 'none') {
            chk.checked = master.checked;
        }
    });
    actualizarBotonEliminar();
}

function actualizarBotonEliminar() {
    const seleccionados = document.querySelectorAll('.chk-item:checked');
    const btn = document.getElementById('btnEliminarSeleccion');
    const contador = document.getElementById('contadorSeleccion');

    if (seleccionados.length > 0) {
        btn.classList.remove('d-none');
        if (contador) contador.textContent = seleccionados.length;
    } else {
        btn.classList.add('d-none');
        if (contador) contador.textContent = '0';
    }

    const todosVisibles = Array.from(document.querySelectorAll('.chk-item')).filter(chk => chk.closest('tr').style.display !== 'none');
    const checkAll = document.getElementById('checkAll');
    
    if (todosVisibles.length > 0) {
        const completados = document.querySelectorAll('.chk-item:checked').length;
        if (checkAll) {
            checkAll.checked = completados === todosVisibles.length;
            checkAll.indeterminate = completados > 0 && completados < todosVisibles.length;
        }
    }
}

function eliminarSeleccionados() {
    const ids = Array.from(document.querySelectorAll('.chk-item:checked')).map(chk => chk.value);

    if (ids.length === 0) return;

    Swal.fire({
        title: '¿Confirmar eliminación masiva?',
        text: `¿Eliminar ${ids.length} inasistencia(s)? Esta acción no se puede deshacer.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: '<i class="ti ti-trash me-1"></i> Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then(result => {
        if (result.isConfirmed) procesarEliminacionAjaxMasiva(ids);
    });
}

function procesarEliminacionAjaxMasiva(ids) {
    const btnDelete = document.getElementById('btnEliminarSeleccion');
    const htmlAnterior = btnDelete.innerHTML;
    btnDelete.innerHTML = '<i class="ti ti-loader ti-spin"></i> Procesando...';
    btnDelete.disabled = true;

    const fnData = new FormData();
    ids.forEach(id => fnData.append('ids[]', id));

    fetch('<?= site_url('admin-th/inasistencias/eliminar-masivo') ?>', { 
        method: 'POST', 
        body: fnData,
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            Swal.fire({icon: 'success', title: '¡Éxito!', text: data.message, timer: 3000, showConfirmButton: false})
            .then(() => window.location.reload());
        } else {
            Swal.fire({icon: 'error', title: 'Error', text: data.message});
            btnDelete.innerHTML = htmlAnterior;
            btnDelete.disabled = false;
        }
    })
    .catch(error => {
        console.error(error);
        btnDelete.innerHTML = htmlAnterior;
        btnDelete.disabled = false;
        Swal.fire({icon: 'error', title: 'Error', text: 'Fallo de red al intentar eliminar.'});
    });
}
// ==================== FIN [ESTANDARIZACIÓN] ====================
</script>

<style>
.avatar-sm { width: 32px; height: 32px; font-size: 14px; font-weight: 600; }
.btn-group .btn { margin-right: 2px; }
.btn-group .btn:last-child { margin-right: 0; }
.card-header .btn { font-size: 0.875rem; }
.table th { background-color: #f8f9fa; border-top: none; }
.badge { font-size: 0.875em; }
.mini-stats-wid .avatar-title { width: 48px; height: 48px; font-size: 24px; }
</style>

<?= $this->endSection() ?>
