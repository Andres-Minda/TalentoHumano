<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?> - Sistema de Talento Humano</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- SweetAlert2 -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    
    <style>
        .status-badge {
            font-size: 0.8rem;
            padding: 0.25rem 0.5rem;
        }
        .priority-badge {
            font-size: 0.75rem;
            padding: 0.2rem 0.4rem;
        }
        .stats-card {
            transition: transform 0.2s;
        }
        .stats-card:hover {
            transform: translateY(-2px);
        }
        .solicitud-card {
            transition: all 0.3s ease;
            border-left: 4px solid #dee2e6;
        }
        .solicitud-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .solicitud-card.pendiente { border-left-color: #ffc107; }
        .solicitud-card.aprobada { border-left-color: #198754; }
        .solicitud-card.rechazada { border-left-color: #dc3545; }
        .solicitud-card.cancelada { border-left-color: #6c757d; }
        .solicitud-card.convertida { border-left-color: #0dcaf0; }
    </style>
</head>
<body>
    <div class="container-fluid">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <h1 class="h3 mb-0">
                        <i class="fas fa-clipboard-list text-primary me-2"></i>
                        <?= $title ?>
                    </h1>
                    <div>
                        <a href="/empleado/solicitudes-capacitacion/crear" class="btn btn-primary">
                            <i class="fas fa-plus me-1"></i> Nueva Solicitud
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Estadísticas Personales -->
        <div class="row mb-4">
            <div class="col-md-2 col-sm-6 mb-3">
                <div class="card stats-card bg-primary text-white">
                    <div class="card-body text-center">
                        <i class="fas fa-clipboard-list fa-2x mb-2"></i>
                        <h4 class="mb-0"><?= $estadisticas['total'] ?? 0 ?></h4>
                        <small>Total Solicitudes</small>
                    </div>
                </div>
            </div>
            <div class="col-md-2 col-sm-6 mb-3">
                <div class="card stats-card bg-warning text-white">
                    <div class="card-body text-center">
                        <i class="fas fa-clock fa-2x mb-2"></i>
                        <h4 class="mb-0"><?= $estadisticas['pendientes'] ?? 0 ?></h4>
                        <small>Pendientes</small>
                    </div>
                </div>
            </div>
            <div class="col-md-2 col-sm-6 mb-3">
                <div class="card stats-card bg-success text-white">
                    <div class="card-body text-center">
                        <i class="fas fa-check-circle fa-2x mb-2"></i>
                        <h4 class="mb-0"><?= $estadisticas['aprobadas'] ?? 0 ?></h4>
                        <small>Aprobadas</small>
                    </div>
                </div>
            </div>
            <div class="col-md-2 col-sm-6 mb-3">
                <div class="card stats-card bg-danger text-white">
                    <div class="card-body text-center">
                        <i class="fas fa-times-circle fa-2x mb-2"></i>
                        <h4 class="mb-0"><?= $estadisticas['rechazadas'] ?? 0 ?></h4>
                        <small>Rechazadas</small>
                    </div>
                </div>
            </div>
            <div class="col-md-2 col-sm-6 mb-3">
                <div class="card stats-card bg-info text-white">
                    <div class="card-body text-center">
                        <i class="fas fa-exchange-alt fa-2x mb-2"></i>
                        <h4 class="mb-0"><?= $estadisticas['convertidas'] ?? 0 ?></h4>
                        <small>Convertidas</small>
                    </div>
                </div>
            </div>
            <div class="col-md-2 col-sm-6 mb-3">
                <div class="card stats-card bg-secondary text-white">
                    <div class="card-body text-center">
                        <i class="fas fa-ban fa-2x mb-2"></i>
                        <h4 class="mb-0"><?= $estadisticas['canceladas'] ?? 0 ?></h4>
                        <small>Canceladas</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filtros -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Estado</label>
                                <select class="form-select" id="filtroEstado">
                                    <option value="">Todos</option>
                                    <option value="Pendiente">Pendiente</option>
                                    <option value="Aprobada">Aprobada</option>
                                    <option value="Rechazada">Rechazada</option>
                                    <option value="Cancelada">Cancelada</option>
                                    <option value="Convertida en Capacitación">Convertida</option>
                                </select>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Prioridad</label>
                                <select class="form-select" id="filtroPrioridad">
                                    <option value="">Todas</option>
                                    <option value="Baja">Baja</option>
                                    <option value="Media">Media</option>
                                    <option value="Alta">Alta</option>
                                    <option value="Crítica">Crítica</option>
                                </select>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Tipo</label>
                                <select class="form-select" id="filtroTipo">
                                    <option value="">Todos</option>
                                    <option value="Técnica">Técnica</option>
                                    <option value="Pedagógica">Pedagógica</option>
                                    <option value="Administrativa">Administrativa</option>
                                    <option value="Soft Skills">Soft Skills</option>
                                    <option value="Otra">Otra</option>
                                </select>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Buscar</label>
                                <input type="text" class="form-control" id="filtroBuscar" placeholder="Nombre capacitación...">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Fecha Desde</label>
                                <input type="date" class="form-control" id="filtroFechaDesde">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Fecha Hasta</label>
                                <input type="date" class="form-control" id="filtroFechaHasta">
                            </div>
                            <div class="col-md-6 mb-3 d-flex align-items-end">
                                <button class="btn btn-primary me-2" onclick="aplicarFiltros()">
                                    <i class="fas fa-search me-1"></i> Aplicar
                                </button>
                                <button class="btn btn-outline-secondary" onclick="limpiarFiltros()">
                                    <i class="fas fa-times me-1"></i> Limpiar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Lista de Solicitudes -->
        <div class="row" id="solicitudesContainer">
            <?php if (empty($solicitudes)): ?>
            <div class="col-12">
                <div class="text-center py-5">
                    <i class="fas fa-clipboard-list fa-3x text-muted mb-3"></i>
                    <h4 class="text-muted">No tienes solicitudes de capacitación</h4>
                    <p class="text-muted">Comienza creando tu primera solicitud para una capacitación</p>
                    <a href="/empleado/solicitudes-capacitacion/crear" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i> Crear Solicitud
                    </a>
                </div>
            </div>
            <?php else: ?>
                <?php foreach ($solicitudes as $solicitud): ?>
                <div class="col-lg-6 col-md-12 mb-4 solicitud-item" 
                     data-estado="<?= strtolower($solicitud['estado']) ?>"
                     data-prioridad="<?= $solicitud['prioridad'] ?>"
                     data-tipo="<?= $solicitud['tipo_capacitacion'] ?>"
                     data-nombre="<?= strtolower($solicitud['nombre_capacitacion']) ?>"
                     data-fecha="<?= $solicitud['fecha_creacion'] ?>">
                    <div class="card solicitud-card h-100 <?= strtolower(str_replace(' ', '-', $solicitud['estado'])) ?>">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h6 class="mb-0">
                                <i class="fas fa-graduation-cap text-primary me-2"></i>
                                <?= $solicitud['nombre_capacitacion'] ?>
                            </h6>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="/empleado/solicitudes-capacitacion/ver/<?= $solicitud['id_solicitud'] ?>">
                                        <i class="fas fa-eye me-2"></i>Ver Detalles
                                    </a></li>
                                    <?php if ($solicitud['estado'] === 'Pendiente'): ?>
                                    <li><a class="dropdown-item" href="/empleado/solicitudes-capacitacion/editar/<?= $solicitud['id_solicitud'] ?>">
                                        <i class="fas fa-edit me-2"></i>Editar
                                    </a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item text-danger" href="#" onclick="cancelarSolicitud(<?= $solicitud['id_solicitud'] ?>)">
                                        <i class="fas fa-times me-2"></i>Cancelar
                                    </a></li>
                                    <?php endif; ?>
                                </ul>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-6">
                                    <small class="text-muted">Tipo:</small>
                                    <br>
                                    <span class="badge bg-secondary"><?= $solicitud['tipo_capacitacion'] ?></span>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted">Prioridad:</small>
                                    <br>
                                    <?php
                                    $prioridadClass = [
                                        'Baja' => 'bg-success',
                                        'Media' => 'bg-warning',
                                        'Alta' => 'bg-danger',
                                        'Crítica' => 'bg-dark'
                                    ];
                                    $prioridadClass = $prioridadClass[$solicitud['prioridad']] ?? 'bg-secondary';
                                    ?>
                                    <span class="badge <?= $prioridadClass ?> priority-badge">
                                        <?= $solicitud['prioridad'] ?>
                                    </span>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <small class="text-muted">Descripción:</small>
                                <p class="mb-0"><?= substr($solicitud['descripcion'], 0, 100) ?><?= strlen($solicitud['descripcion']) > 100 ? '...' : '' ?></p>
                            </div>

                            <div class="row mb-3">
                                <div class="col-6">
                                    <small class="text-muted">Fecha Deseada:</small>
                                    <br>
                                    <strong><?= date('d/m/Y', strtotime($solicitud['fecha_deseada'])) ?></strong>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted">Duración:</small>
                                    <br>
                                    <strong><?= $solicitud['duracion_estimada'] ?> horas</strong>
                                </div>
                            </div>

                            <?php if ($solicitud['institucion_preferida']): ?>
                            <div class="mb-3">
                                <small class="text-muted">Institución Preferida:</small>
                                <br>
                                <strong><?= $solicitud['institucion_preferida'] ?></strong>
                            </div>
                            <?php endif; ?>

                            <?php if ($solicitud['costo_estimado']): ?>
                            <div class="mb-3">
                                <small class="text-muted">Costo Estimado:</small>
                                <br>
                                <strong>$<?= number_format($solicitud['costo_estimado'], 2) ?></strong>
                            </div>
                            <?php endif; ?>

                            <div class="mb-3">
                                <small class="text-muted">Estado:</small>
                                <br>
                                <?php
                                $estadoClass = [
                                    'Pendiente' => 'bg-warning',
                                    'Aprobada' => 'bg-success',
                                    'Rechazada' => 'bg-danger',
                                    'Cancelada' => 'bg-secondary',
                                    'Convertida en Capacitación' => 'bg-info'
                                ];
                                $estadoClass = $estadoClass[$solicitud['estado']] ?? 'bg-secondary';
                                ?>
                                <span class="badge <?= $estadoClass ?> status-badge">
                                    <?= $solicitud['estado'] ?>
                                </span>
                            </div>

                            <?php if ($solicitud['estado'] === 'Aprobada' && $solicitud['observaciones_admin']): ?>
                            <div class="mb-3">
                                <small class="text-muted">Observaciones del Administrador:</small>
                                <div class="alert alert-success py-2">
                                    <small><?= $solicitud['observaciones_admin'] ?></small>
                                </div>
                            </div>
                            <?php endif; ?>

                            <?php if ($solicitud['estado'] === 'Rechazada' && $solicitud['motivo_rechazo']): ?>
                            <div class="mb-3">
                                <small class="text-muted">Motivo del Rechazo:</small>
                                <div class="alert alert-danger py-2">
                                    <small><?= $solicitud['motivo_rechazo'] ?></small>
                                </div>
                            </div>
                            <?php endif; ?>

                            <?php if ($solicitud['estado'] === 'Convertida en Capacitación' && $solicitud['id_capacitacion_creada']): ?>
                            <div class="mb-3">
                                <div class="alert alert-info py-2">
                                    <small>
                                        <i class="fas fa-info-circle me-1"></i>
                                        Esta solicitud ha sido convertida en capacitación
                                    </small>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                        <div class="card-footer text-muted">
                            <small>
                                <i class="fas fa-calendar me-1"></i>
                                Creada: <?= date('d/m/Y H:i', strtotime($solicitud['fecha_creacion'])) ?>
                            </small>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <!-- Modal para Cancelar Solicitud -->
    <div class="modal fade" id="modalCancelar" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Cancelar Solicitud</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="formCancelar">
                        <div class="mb-3">
                            <label class="form-label">Motivo de la Cancelación <span class="text-danger">*</span></label>
                            <textarea class="form-control" name="motivo_cancelacion" rows="3" placeholder="Motivo de la cancelación..." required></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-danger" onclick="confirmarCancelar()">Confirmar Cancelación</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        let solicitudActual = null;

        $(document).ready(function() {
            // Aplicar filtros en tiempo real
            $('#filtroEstado, #filtroPrioridad, #filtroTipo').change(function() {
                aplicarFiltros();
            });

            $('#filtroBuscar').on('keyup', function() {
                aplicarFiltros();
            });
        });

        function aplicarFiltros() {
            const estado = $('#filtroEstado').val().toLowerCase();
            const prioridad = $('#filtroPrioridad').val();
            const tipo = $('#filtroTipo').val();
            const buscar = $('#filtroBuscar').val().toLowerCase();
            const fechaDesde = $('#filtroFechaDesde').val();
            const fechaHasta = $('#filtroFechaHasta').val();

            $('.solicitud-item').each(function() {
                const $item = $(this);
                const itemEstado = $item.data('estado');
                const itemPrioridad = $item.data('prioridad');
                const itemTipo = $item.data('tipo');
                const itemNombre = $item.data('nombre');
                const itemFecha = $item.data('fecha');

                let mostrar = true;

                // Filtro por estado
                if (estado && itemEstado !== estado) mostrar = false;

                // Filtro por prioridad
                if (prioridad && itemPrioridad !== prioridad) mostrar = false;

                // Filtro por tipo
                if (tipo && itemTipo !== tipo) mostrar = false;

                // Filtro por búsqueda
                if (buscar && !itemNombre.includes(buscar)) mostrar = false;

                // Filtro por fechas
                if (fechaDesde || fechaHasta) {
                    const fechaItem = new Date(itemFecha);
                    if (fechaDesde && fechaItem < new Date(fechaDesde)) mostrar = false;
                    if (fechaHasta && fechaItem > new Date(fechaHasta)) mostrar = false;
                }

                $item.toggle(mostrar);
            });

            // Verificar si hay elementos visibles
            const elementosVisibles = $('.solicitud-item:visible').length;
            if (elementosVisibles === 0) {
                $('#solicitudesContainer').html(`
                    <div class="col-12">
                        <div class="text-center py-5">
                            <i class="fas fa-search fa-3x text-muted mb-3"></i>
                            <h4 class="text-muted">No se encontraron solicitudes</h4>
                            <p class="text-muted">Intenta ajustar los filtros de búsqueda</p>
                            <button class="btn btn-outline-secondary" onclick="limpiarFiltros()">
                                <i class="fas fa-times me-1"></i> Limpiar Filtros
                            </button>
                        </div>
                    </div>
                `);
            }
        }

        function limpiarFiltros() {
            $('#filtroEstado, #filtroPrioridad, #filtroTipo').val('');
            $('#filtroBuscar, #filtroFechaDesde, #filtroFechaHasta').val('');
            $('.solicitud-item').show();
            
            // Restaurar vista original si no hay solicitudes
            if ($('.solicitud-item').length === 0) {
                location.reload();
            }
        }

        function cancelarSolicitud(idSolicitud) {
            solicitudActual = idSolicitud;
            $('#modalCancelar').modal('show');
        }

        function confirmarCancelar() {
            const motivo = $('#formCancelar textarea[name="motivo_cancelacion"]').val();
            
            if (!motivo.trim()) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Campo Requerido',
                    text: 'Debe especificar el motivo de la cancelación'
                });
                return;
            }

            $.ajax({
                url: `/empleado/solicitudes-capacitacion/cancelar/${solicitudActual}`,
                type: 'POST',
                data: { motivo_cancelacion: motivo },
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: '¡Éxito!',
                            text: response.message
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: response.message
                        });
                    }
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Error al cancelar la solicitud'
                    });
                }
            });
        }
    </script>
</body>
</html>
