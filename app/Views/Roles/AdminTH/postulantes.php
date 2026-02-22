<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>
<div class="page-wrapper">
    <div class="page-content">
        <!-- Breadcrumb -->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Postulantes</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
                        <li class="breadcrumb-item"><a href="<?= base_url('index.php/admin-th/dashboard') ?>">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Gestión de Postulantes</li>
                    </ol>
                </nav>
            </div>
        </div>

        <!-- Header -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h4 class="card-title mb-1">Gestión de Postulantes</h4>
                                <p class="card-subtitle mb-0">Administra todas las postulaciones recibidas</p>
                            </div>
                            <div>
                                <a href="<?= base_url('index.php/admin-th/postulantes/exportar') ?>" class="btn btn-success">
                                    <i class="bi bi-download me-2"></i>Exportar CSV
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Estadísticas -->
        <div class="row">
            <div class="col-xl-2 col-md-4 col-sm-6">
                <div class="card radius-10 bg-primary">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <i class="bi bi-people fs-1 text-white"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h4 class="mb-0 text-white"><?= $estadisticas['total'] ?? 0 ?></h4>
                                <p class="mb-0 text-white-50">Total</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-2 col-md-4 col-sm-6">
                <div class="card radius-10 bg-warning">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <i class="bi bi-clock fs-1 text-white"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h4 class="mb-0 text-white"><?= $estadisticas['pendientes'] ?? 0 ?></h4>
                                <p class="mb-0 text-white-50">Pendientes</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-2 col-md-4 col-sm-6">
                <div class="card radius-10 bg-info">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <i class="bi bi-search fs-1 text-white"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h4 class="mb-0 text-white"><?= $estadisticas['en_revision'] ?? 0 ?></h4>
                                <p class="mb-0 text-white-50">En Revisión</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-2 col-md-4 col-sm-6">
                <div class="card radius-10 bg-success">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <i class="bi bi-check-circle fs-1 text-white"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h4 class="mb-0 text-white"><?= $estadisticas['aprobadas'] ?? 0 ?></h4>
                                <p class="mb-0 text-white-50">Aprobadas</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-2 col-md-4 col-sm-6">
                <div class="card radius-10 bg-danger">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <i class="bi bi-x-circle fs-1 text-white"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h4 class="mb-0 text-white"><?= $estadisticas['rechazadas'] ?? 0 ?></h4>
                                <p class="mb-0 text-white-50">Rechazadas</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-2 col-md-4 col-sm-6">
                <div class="card radius-10 bg-dark">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <i class="bi bi-person-check fs-1 text-white"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h4 class="mb-0 text-white"><?= $estadisticas['contratados'] ?? 0 ?></h4>
                                <p class="mb-0 text-white-50">Contratados</p>
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
                    <div class="card-header">
                        <h5 class="card-title mb-0">Filtros de Búsqueda</h5>
                    </div>
                    <div class="card-body">
                        <form method="GET" action="<?= base_url('index.php/admin-th/postulantes') ?>" id="filtrosForm">
                            <div class="row">
                                <div class="col-md-3 mb-3">
                                    <label for="busqueda" class="form-label">Buscar</label>
                                    <input type="text" class="form-control" id="busqueda" name="busqueda" 
                                           value="<?= $filtros['busqueda'] ?>" placeholder="Nombre, cédula, email...">
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="estado" class="form-label">Estado</label>
                                    <select class="form-select" id="estado" name="estado">
                                        <option value="">Todos los estados</option>
                                        <?php foreach ($estados as $est): ?>
                                            <option value="<?= $est ?>" <?= $filtros['estado'] === $est ? 'selected' : '' ?>>
                                                <?= $est ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="puesto" class="form-label">Puesto</label>
                                    <select class="form-select" id="puesto" name="puesto">
                                        <option value="">Todos los puestos</option>
                                        <?php foreach ($puestos as $p): ?>
                                            <option value="<?= $p['id_puesto'] ?>" <?= $filtros['puesto'] == $p['id_puesto'] ? 'selected' : '' ?>>
                                                <?= $p['titulo'] ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="departamento" class="form-label">Departamento</label>
                                    <select class="form-select" id="departamento" name="departamento">
                                        <option value="">Todos los departamentos</option>
                                        <?php foreach ($departamentos as $d): ?>
                                            <option value="<?= $d['id_departamento'] ?>" <?= $filtros['departamento'] == $d['id_departamento'] ? 'selected' : '' ?>>
                                                <?= $d['nombre'] ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary me-2">
                                        <i class="bi bi-search me-2"></i>Filtrar
                                    </button>
                                    <a href="<?= base_url('index.php/admin-th/postulantes') ?>" class="btn btn-secondary">
                                        <i class="bi bi-arrow-clockwise me-2"></i>Limpiar
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabla de Postulantes -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Lista de Postulantes</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Postulante</th>
                                        <th>Puesto</th>
                                        <th>Departamento</th>
                                        <th>Estado</th>
                                        <th>Fecha</th>
                                        <th>CV</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($postulantes)): ?>
                                        <tr>
                                            <td colspan="8" class="text-center text-muted py-4">
                                                <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                                No se encontraron postulantes
                                            </td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($postulantes as $postulante): ?>
                                            <tr>
                                                <td><?= $postulante['id_postulante'] ?></td>
                                                <td>
                                                    <div>
                                                        <strong><?= $postulante['nombres'] ?> <?= $postulante['apellidos'] ?></strong>
                                                        <br>
                                                        <small class="text-muted">
                                                            <?= $postulante['email'] ?><br>
                                                            <?= $postulante['cedula'] ?>
                                                        </small>
                                                    </div>
                                                </td>
                                                <td><?= $postulante['titulo_puesto'] ?? 'N/A' ?></td>
                                                <td><?= $postulante['nombre_departamento'] ?? 'N/A' ?></td>
                                                <td>
                                                    <?php
                                                    $badgeClass = match($postulante['estado_postulacion']) {
                                                        'Pendiente' => 'bg-warning',
                                                        'En revisión' => 'bg-info',
                                                        'Aprobada' => 'bg-success',
                                                        'Rechazada' => 'bg-danger',
                                                        'Contratado' => 'bg-dark',
                                                        default => 'bg-secondary'
                                                    };
                                                    ?>
                                                    <span class="badge <?= $badgeClass ?>">
                                                        <?= $postulante['estado_postulacion'] ?>
                                                    </span>
                                                </td>
                                                <td><?= date('d/m/Y', strtotime($postulante['fecha_postulacion'])) ?></td>
                                                <td>
                                                    <?php if ($postulante['cv_path']): ?>
                                                        <a href="<?= base_url('index.php/admin-th/postulantes/' . $postulante['id_postulante'] . '/cv') ?>" 
                                                           class="btn btn-sm btn-outline-primary" title="Descargar CV">
                                                            <i class="bi bi-download"></i>
                                                        </a>
                                                    <?php else: ?>
                                                        <span class="text-muted">Sin CV</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <a href="<?= base_url('index.php/admin-th/postulantes/' . $postulante['id_postulante']) ?>" 
                                                           class="btn btn-sm btn-outline-info" title="Ver detalles">
                                                            <i class="bi bi-eye"></i>
                                                        </a>
                                                        <button type="button" class="btn btn-sm btn-outline-warning" 
                                                                onclick="cambiarEstado(<?= $postulante['id_postulante'] ?>, '<?= $postulante['estado_postulacion'] ?>')"
                                                                title="Cambiar estado">
                                                            <i class="bi bi-pencil"></i>
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

<!-- Gráfico de Postulantes por Estado -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Distribución de Postulantes por Estado</h5>
            </div>
            <div class="card-body">
                <div class="chart-container" style="position: relative; height:400px;">
                    <canvas id="chartPostulantesEstado"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para cambiar estado -->
<div class="modal fade" id="modalCambiarEstado" tabindex="-1" aria-labelledby="modalCambiarEstadoLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalCambiarEstadoLabel">Cambiar Estado de Postulación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formCambiarEstado">
                <div class="modal-body">
                    <input type="hidden" id="idPostulante" name="id_postulante">
                    <div class="mb-3">
                        <label for="nuevoEstado" class="form-label">Nuevo Estado</label>
                        <select class="form-select" id="nuevoEstado" name="nuevo_estado" required>
                            <option value="">Seleccionar estado...</option>
                            <option value="Pendiente">Pendiente</option>
                            <option value="En revisión">En revisión</option>
                            <option value="Aprobada">Aprobada</option>
                            <option value="Rechazada">Rechazada</option>
                            <option value="Contratado">Contratado</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="notas" class="form-label">Notas Administrativas</label>
                        <textarea class="form-control" id="notas" name="notas" rows="3" 
                                  placeholder="Agregue notas sobre la decisión..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Actualizar Estado</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function cambiarEstado(idPostulante, estadoActual) {
    document.getElementById('idPostulante').value = idPostulante;
    document.getElementById('nuevoEstado').value = estadoActual;
    
    const modal = new bootstrap.Modal(document.getElementById('modalCambiarEstado'));
    modal.show();
}

document.getElementById('formCambiarEstado').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    fetch('<?= base_url('index.php/admin-th/postulaciones/cambiar-estado') ?>', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Mostrar mensaje de éxito
            Swal.fire({
                icon: 'success',
                title: '¡Éxito!',
                text: data.message,
                timer: 2000,
                showConfirmButton: false
            }).then(() => {
                // Recargar la página
                window.location.reload();
            });
        } else {
            // Mostrar mensaje de error
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: data.message
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Ocurrió un error al procesar la solicitud'
        });
    });
});

// Auto-submit del formulario de filtros cuando cambien los selects
document.getElementById('estado').addEventListener('change', function() {
    document.getElementById('filtrosForm').submit();
});

document.getElementById('puesto').addEventListener('change', function() {
    document.getElementById('filtrosForm').submit();
});

document.getElementById('departamento').addEventListener('change', function() {
    document.getElementById('filtrosForm').submit();
});

// Inicializar gráfico de postulantes por estado
document.addEventListener('DOMContentLoaded', function() {
    inicializarGraficoPostulantes();
});

function inicializarGraficoPostulantes() {
    const ctx = document.getElementById('chartPostulantesEstado').getContext('2d');
    
    // Obtener datos de las estadísticas
    const estadisticas = <?= json_encode($estadisticas) ?>;
    
    // Preparar datos para el gráfico
    const labels = ['Pendientes', 'En Revisión', 'Aprobadas', 'Rechazadas', 'Contratados'];
    const data = [
        estadisticas.pendientes || 0,
        estadisticas.en_revision || 0,
        estadisticas.aprobadas || 0,
        estadisticas.rechazadas || 0,
        estadisticas.contratados || 0
    ];
    
    // Colores para cada estado
    const colors = [
        '#ffc107', // Pendientes - Amarillo
        '#17a2b8', // En Revisión - Azul
        '#28a745', // Aprobadas - Verde
        '#dc3545', // Rechazadas - Rojo
        '#343a40'  // Contratados - Negro
    ];
    
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: labels,
            datasets: [{
                data: data,
                backgroundColor: colors,
                borderColor: '#ffffff',
                borderWidth: 2,
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 20,
                        usePointStyle: true,
                        font: {
                            size: 12
                        }
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const label = context.label || '';
                            const value = context.parsed || 0;
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = ((value / total) * 100).toFixed(1);
                            return `${label}: ${value} (${percentage}%)`;
                        }
                    }
                }
            },
            animation: {
                animateRotate: true,
                animateScale: true
            }
        }
    });
}
</script>

<?= $this->endSection() ?>
