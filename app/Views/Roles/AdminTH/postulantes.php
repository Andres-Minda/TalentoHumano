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
                            <?php if (!isset($soloLectura) || !$soloLectura): ?>
                            <div class="d-flex gap-2 align-items-center">
                                <span class="badge bg-primary fs-6 px-3 py-2">
                                    <i class="bi bi-people me-1"></i>Total: <?= $totalPostulantes ?? count($postulantes) ?>
                                </span>
                                <a href="<?= base_url('index.php/admin-th/postulantes/exportar-drive') ?>" class="btn btn-outline-primary" target="_blank">
                                    <i class="bi bi-cloud me-2"></i>CVs en Drive
                                </a>
                            </div>
                            <?php else: ?>
                            <span class="badge bg-secondary fs-6 px-3 py-2">
                                <i class="bi bi-eye me-1"></i>Solo lectura
                            </span>
                            <?php endif; ?>
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

        <!-- Tarjetas de Postulantes -->
        <div class="row">
            <?php if (empty($postulantes)): ?>
                <div class="col-12">
                    <div class="card">
                        <div class="card-body text-center py-5">
                            <i class="bi bi-inbox fs-1 d-block mb-3 text-muted"></i>
                            <h5 class="text-muted">No se encontraron postulantes</h5>
                            <p class="text-muted">Ajusta los filtros o espera nuevas postulaciones.</p>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <?php foreach ($postulantes as $postulante): ?>
                    <?php
                    $badgeClass = match($postulante['estado_postulacion']) {
                        'Pendiente' => 'bg-warning',
                        'Aprobada' => 'bg-success',
                        'Rechazada' => 'bg-danger',
                        'Contratado' => 'bg-dark',
                        default => 'bg-secondary'
                    };
                    ?>
                    <div class="col-xl-4 col-md-6 col-12 mb-3">
                        <div class="card h-100 border-start border-4 border-primary shadow-sm">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div>
                                        <h6 class="card-title mb-0">
                                            <?= $postulante['nombres'] ?> <?= $postulante['apellidos'] ?>
                                        </h6>
                                        <small class="text-muted"><?= $postulante['cedula'] ?></small>
                                    </div>
                                    <span class="badge <?= $badgeClass ?>" data-badge-id="<?= $postulante['id_postulante'] ?>"><?= $postulante['estado_postulacion'] ?></span>
                                </div>

                                <div class="mb-2">
                                    <small class="d-block"><i class="bi bi-envelope me-1"></i><?= $postulante['email'] ?></small>
                                    <small class="d-block"><i class="bi bi-telephone me-1"></i><?= $postulante['telefono'] ?? 'N/A' ?></small>
                                </div>

                                <div class="mb-2">
                                    <span class="badge bg-light text-dark me-1">
                                        <i class="bi bi-briefcase me-1"></i><?= $postulante['titulo_puesto'] ?? 'N/A' ?>
                                    </span>
                                    <span class="badge bg-light text-dark">
                                        <i class="bi bi-building me-1"></i><?= $postulante['nombre_departamento'] ?? 'N/A' ?>
                                    </span>
                                </div>

                                <small class="text-muted d-block mb-3">
                                    <i class="bi bi-calendar me-1"></i>Postulación: <?= date('d/m/Y', strtotime($postulante['fecha_postulacion'])) ?>
                                </small>

                                <div class="d-flex gap-2">
                                    <?php if (!empty($postulante['cv_path']) && str_starts_with($postulante['cv_path'], 'http')): ?>
                                        <a href="<?= $postulante['cv_path'] ?>" target="_blank" class="btn btn-primary btn-sm flex-grow-1">
                                            <i class="bi bi-file-earmark-pdf me-1"></i>Ver CV
                                        </a>
                                    <?php elseif (!empty($postulante['cv_path'])): ?>
                                        <a href="<?= base_url('index.php/admin-th/postulantes/' . $postulante['id_postulante'] . '/cv') ?>" class="btn btn-primary btn-sm flex-grow-1">
                                            <i class="bi bi-download me-1"></i>Descargar CV
                                        </a>
                                    <?php else: ?>
                                        <button class="btn btn-outline-secondary btn-sm flex-grow-1" disabled>
                                            <i class="bi bi-file-earmark-x me-1"></i>Sin CV
                                        </button>
                                    <?php endif; ?>

                                    <?php if (!isset($soloLectura) || !$soloLectura): ?>
                                    <button type="button" class="btn btn-outline-warning btn-sm"
                                            onclick="cambiarEstado(<?= $postulante['id_postulante'] ?>, '<?= $postulante['estado_postulacion'] ?>')"
                                            title="Cambiar estado">
                                        <i class="bi bi-pencil"></i>
                                    </button>

                                    <button type="button" class="btn btn-outline-danger btn-sm"
                                            onclick="eliminarPostulante(<?= $postulante['id_postulante'] ?>, '<?= esc($postulante['nombres'] . ' ' . $postulante['apellidos']) ?>')"
                                            title="Eliminar postulante">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
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
function eliminarPostulante(id, nombre) {
    Swal.fire({
        title: '¿Eliminar postulante?',
        text: `¿Estás seguro de eliminar a ${nombre}? Esta acción no se puede deshacer.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            const formData = new FormData();
            formData.append('id_postulante', id);

            fetch('<?= base_url('index.php/admin-th/postulantes/eliminar') ?>', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire('¡Eliminado!', data.message, 'success').then(() => {
                        window.location.reload();
                    });
                } else {
                    Swal.fire('Error', data.message, 'error');
                }
            })
            .catch(error => {
                Swal.fire('Error', 'Error al procesar la solicitud', 'error');
            });
        }
    });
}

function cambiarEstado(idPostulante, estadoActual) {
    document.getElementById('idPostulante').value = idPostulante;
    document.getElementById('nuevoEstado').value = estadoActual;
    
    const modal = new bootstrap.Modal(document.getElementById('modalCambiarEstado'));
    modal.show();
}

document.getElementById('formCambiarEstado').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const nuevoEstado = formData.get('nuevo_estado');
    const idPostulante = formData.get('id_postulante');
    
    fetch('<?= base_url('index.php/admin-th/postulaciones/cambiar-estado') ?>', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Cerrar modal
            bootstrap.Modal.getInstance(document.getElementById('modalCambiarEstado')).hide();
            
            // Actualizar badge dinámicamente sin recargar
            const badgeMap = {
                'Pendiente': 'bg-warning',
                'Aprobada': 'bg-success',
                'Rechazada': 'bg-danger',
                'Contratado': 'bg-dark'
            };
            const badge = document.querySelector(`[data-badge-id="${idPostulante}"]`);
            if (badge) {
                badge.className = 'badge ' + (badgeMap[nuevoEstado] || 'bg-secondary');
                badge.textContent = nuevoEstado;
            }
            
            // Lógica según el nuevo estado
            if (data.is_contratado) {
                // Estado: Contratado → Ofrecer crear credenciales
                Swal.fire({
                    icon: 'success',
                    title: '\u00a1Postulante Contratado!',
                    text: '\u00bfDesea crear las credenciales de acceso al sistema para este nuevo empleado ahora?',
                    showCancelButton: true,
                    confirmButtonColor: '#198754',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: '<i class="bi bi-person-plus me-1"></i> S\u00ed, crear credenciales',
                    cancelButtonText: 'Ahora no'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '<?= base_url('index.php/admin-th/empleados') ?>?postulante_id=' + idPostulante;
                    }
                });

            } else if (nuevoEstado === 'Aprobada') {
                // Estado: Aprobada → Mostrar teléfono de contacto
                const telefono = data.telefono || 'No disponible';
                Swal.fire({
                    icon: 'info',
                    title: 'Postulación Aprobada',
                    html: `<p>Se espera al postulante para la entrevista.</p>
                           <p><strong>Por favor contactarse al número:</strong></p>
                           <h3 class="text-primary"><i class="bi bi-telephone-fill me-2"></i>${telefono}</h3>`,
                    confirmButtonColor: '#0d6efd',
                    confirmButtonText: 'Entendido'
                });

            } else if (nuevoEstado === 'Rechazada') {
                // Estado: Rechazada → Preguntar si desea eliminar permanentemente
                Swal.fire({
                    icon: 'warning',
                    title: 'Postulación Rechazada',
                    text: '\u00bfDeseas borrar permanentemente a este postulante?',
                    showCancelButton: true,
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'S\u00ed, borrar',
                    cancelButtonText: 'No'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Eliminar postulante permanentemente
                        const deleteFormData = new FormData();
                        deleteFormData.append('id_postulante', idPostulante);

                        fetch('<?= base_url('index.php/admin-th/postulantes/eliminar') ?>', {
                            method: 'POST',
                            body: deleteFormData
                        })
                        .then(response => response.json())
                        .then(deleteData => {
                            if (deleteData.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: '\u00a1Eliminado!',
                                    text: 'El postulante ha sido eliminado permanentemente.',
                                    timer: 2000,
                                    showConfirmButton: false
                                }).then(() => {
                                    window.location.reload();
                                });
                            } else {
                                Swal.fire('Error', deleteData.message || 'No se pudo eliminar al postulante.', 'error');
                            }
                        })
                        .catch(error => {
                            Swal.fire('Error', 'Error al procesar la eliminación.', 'error');
                        });
                    } else {
                        // Solo rechazado, sin eliminar
                        Swal.fire({
                            icon: 'info',
                            title: 'Estado actualizado',
                            text: 'El postulante fue marcado como rechazado pero no se eliminó.',
                            timer: 2500,
                            showConfirmButton: false
                        });
                    }
                });

            } else {
                // Otros estados (Pendiente, etc.)
                Swal.fire({
                    icon: 'success',
                    title: '\u00a1\u00c9xito!',
                    text: data.message,
                    timer: 2000,
                    showConfirmButton: false
                });
            }
        } else {
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
            text: 'Ocurri\u00f3 un error al procesar la solicitud'
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
    const labels = ['Pendientes', 'Aprobadas', 'Rechazadas', 'Contratados'];
    const data = [
        estadisticas.pendientes || 0,
        estadisticas.aprobadas || 0,
        estadisticas.rechazadas || 0,
        estadisticas.contratados || 0
    ];
    
    // Colores para cada estado
    const colors = [
        '#ffc107', // Pendientes - Amarillo
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
