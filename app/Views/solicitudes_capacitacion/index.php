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
        .table-responsive {
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
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
                        <button class="btn btn-outline-secondary me-2" onclick="exportarDatos()">
                            <i class="fas fa-download me-1"></i> Exportar
                        </button>
                        <button class="btn btn-primary" onclick="mostrarFiltros()">
                            <i class="fas fa-filter me-1"></i> Filtros
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Estadísticas -->
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
                        <i class="fas fa-calendar-alt fa-2x mb-2"></i>
                        <h4 class="mb-0"><?= $estadisticas['mes_actual'] ?? 0 ?></h4>
                        <small>Este Mes</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filtros -->
        <div class="row mb-4" id="filtrosSection" style="display: none;">
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
                                <input type="text" class="form-control" id="filtroBuscar" placeholder="Nombre, empleado...">
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

        <!-- Tabla de Solicitudes -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="tablaSolicitudes" class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>ID</th>
                                        <th>Empleado</th>
                                        <th>Capacitación</th>
                                        <th>Tipo</th>
                                        <th>Prioridad</th>
                                        <th>Estado</th>
                                        <th>Fecha Deseada</th>
                                        <th>Fecha Creación</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($solicitudes as $solicitud): ?>
                                    <tr>
                                        <td><?= $solicitud['id_solicitud'] ?></td>
                                        <td>
                                            <div>
                                                <strong><?= $solicitud['nombre_empleado'] . ' ' . $solicitud['apellido_empleado'] ?></strong>
                                                <br>
                                                <small class="text-muted"><?= $solicitud['cedula'] ?></small>
                                                <?php if ($solicitud['nombre_departamento']): ?>
                                                <br>
                                                <small class="text-info"><?= $solicitud['nombre_departamento'] ?></small>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                        <td>
                                            <div>
                                                <strong><?= $solicitud['nombre_capacitacion'] ?></strong>
                                                <br>
                                                <small class="text-muted"><?= substr($solicitud['descripcion'], 0, 50) ?>...</small>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary"><?= $solicitud['tipo_capacitacion'] ?></span>
                                        </td>
                                        <td>
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
                                        </td>
                                        <td>
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
                                        </td>
                                        <td>
                                            <small><?= date('d/m/Y', strtotime($solicitud['fecha_deseada'])) ?></small>
                                        </td>
                                        <td>
                                            <small><?= date('d/m/Y H:i', strtotime($solicitud['fecha_creacion'])) ?></small>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <button class="btn btn-sm btn-outline-primary" onclick="verSolicitud(<?= $solicitud['id_solicitud'] ?>)">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <?php if ($solicitud['estado'] === 'Pendiente'): ?>
                                                <button class="btn btn-sm btn-outline-success" onclick="aprobarSolicitud(<?= $solicitud['id_solicitud'] ?>)">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                                <button class="btn btn-sm btn-outline-danger" onclick="rechazarSolicitud(<?= $solicitud['id_solicitud'] ?>)">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                                <?php endif; ?>
                                                <?php if ($solicitud['estado'] === 'Aprobada'): ?>
                                                <button class="btn btn-sm btn-outline-info" onclick="convertirEnCapacitacion(<?= $solicitud['id_solicitud'] ?>)">
                                                    <i class="fas fa-exchange-alt"></i>
                                                </button>
                                                <?php endif; ?>
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

    <!-- Modal para Aprobar Solicitud -->
    <div class="modal fade" id="modalAprobar" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Aprobar Solicitud</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="formAprobar">
                        <div class="mb-3">
                            <label class="form-label">Observaciones (opcional)</label>
                            <textarea class="form-control" name="observaciones" rows="3" placeholder="Observaciones adicionales..."></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-success" onclick="confirmarAprobar()">Aprobar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para Rechazar Solicitud -->
    <div class="modal fade" id="modalRechazar" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Rechazar Solicitud</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="formRechazar">
                        <div class="mb-3">
                            <label class="form-label">Motivo del Rechazo <span class="text-danger">*</span></label>
                            <textarea class="form-control" name="motivo_rechazo" rows="3" placeholder="Motivo del rechazo..." required></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-danger" onclick="confirmarRechazar()">Rechazar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        let solicitudActual = null;
        let tablaSolicitudes;

        $(document).ready(function() {
            // Inicializar DataTable
            tablaSolicitudes = $('#tablaSolicitudes').DataTable({
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
                },
                pageLength: 25,
                order: [[7, 'desc']], // Ordenar por fecha de creación descendente
                columnDefs: [
                    { orderable: false, targets: 8 } // Columna de acciones no ordenable
                ]
            });

            // Aplicar filtros en tiempo real
            $('#filtroEstado, #filtroPrioridad, #filtroTipo').change(function() {
                aplicarFiltros();
            });

            $('#filtroBuscar').on('keyup', function() {
                aplicarFiltros();
            });
        });

        function mostrarFiltros() {
            $('#filtrosSection').toggle();
        }

        function aplicarFiltros() {
            const estado = $('#filtroEstado').val();
            const prioridad = $('#filtroPrioridad').val();
            const tipo = $('#filtroTipo').val();
            const buscar = $('#filtroBuscar').val();
            const fechaDesde = $('#filtroFechaDesde').val();
            const fechaHasta = $('#filtroFechaHasta').val();

            // Aplicar filtros a DataTable
            tablaSolicitudes.draw();

            // Filtro personalizado
            $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
                const rowEstado = data[5]; // Columna estado
                const rowPrioridad = data[4]; // Columna prioridad
                const rowTipo = data[3]; // Columna tipo
                const rowEmpleado = data[1]; // Columna empleado
                const rowCapacitacion = data[2]; // Columna capacitación
                const rowFecha = data[7]; // Columna fecha creación

                // Filtro por estado
                if (estado && rowEstado !== estado) return false;

                // Filtro por prioridad
                if (prioridad && rowPrioridad !== prioridad) return false;

                // Filtro por tipo
                if (tipo && rowTipo !== tipo) return false;

                // Filtro por búsqueda
                if (buscar) {
                    const textoBusqueda = buscar.toLowerCase();
                    if (!rowEmpleado.toLowerCase().includes(textoBusqueda) && 
                        !rowCapacitacion.toLowerCase().includes(textoBusqueda)) {
                        return false;
                    }
                }

                // Filtro por fechas
                if (fechaDesde || fechaHasta) {
                    const fechaRow = new Date(rowFecha.split('/').reverse().join('-'));
                    if (fechaDesde && fechaRow < new Date(fechaDesde)) return false;
                    if (fechaHasta && fechaRow > new Date(fechaHasta)) return false;
                }

                return true;
            });

            tablaSolicitudes.draw();
        }

        function limpiarFiltros() {
            $('#filtroEstado, #filtroPrioridad, #filtroTipo').val('');
            $('#filtroBuscar, #filtroFechaDesde, #filtroFechaHasta').val('');
            $.fn.dataTable.ext.search.pop();
            tablaSolicitudes.draw();
        }

        function verSolicitud(idSolicitud) {
            window.location.href = `/solicitudes-capacitacion/ver/${idSolicitud}`;
        }

        function aprobarSolicitud(idSolicitud) {
            solicitudActual = idSolicitud;
            $('#modalAprobar').modal('show');
        }

        function confirmarAprobar() {
            const observaciones = $('#formAprobar textarea[name="observaciones"]').val();
            
            $.ajax({
                url: `/solicitudes-capacitacion/aprobar/${solicitudActual}`,
                type: 'POST',
                data: { observaciones: observaciones },
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
                        text: 'Error al procesar la solicitud'
                    });
                }
            });
        }

        function rechazarSolicitud(idSolicitud) {
            solicitudActual = idSolicitud;
            $('#modalRechazar').modal('show');
        }

        function confirmarRechazar() {
            const motivo = $('#formRechazar textarea[name="motivo_rechazo"]').val();
            
            if (!motivo.trim()) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Campo Requerido',
                    text: 'Debe especificar el motivo del rechazo'
                });
                return;
            }

            $.ajax({
                url: `/solicitudes-capacitacion/rechazar/${solicitudActual}`,
                type: 'POST',
                data: { motivo_rechazo: motivo },
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
                        text: 'Error al procesar la solicitud'
                    });
                }
            });
        }

        function convertirEnCapacitacion(idSolicitud) {
            Swal.fire({
                title: '¿Convertir en Capacitación?',
                text: 'Esta acción creará una nueva capacitación basada en la solicitud aprobada',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, convertir',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/solicitudes-capacitacion/convertir/${idSolicitud}`,
                        type: 'POST',
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
                                text: 'Error al convertir la solicitud'
                            });
                        }
                    });
                }
            });
        }

        function exportarDatos() {
            // Implementar exportación a Excel/CSV
            Swal.fire({
                icon: 'info',
                title: 'Exportar Datos',
                text: 'Función de exportación en desarrollo'
            });
        }
    </script>
</body>
</html>
