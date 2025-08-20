<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>

<div class="page-wrapper">
    <div class="page-content">
        <!-- Header -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <h4 class="mb-0"><i class="bi bi-mortarboard"></i> Mis Capacitaciones</h4>
                    <div class="page-title-right">
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#requestTrainingModal">
                            <i class="bi bi-plus-circle me-1"></i>Solicitar Capacitación
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Training Statistics -->
        <div class="row">
            <div class="col-xl-3 col-lg-6 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h4 class="mb-0">5</h4>
                                <p class="text-muted mb-0">Total Capacitaciones</p>
                            </div>
                            <div class="text-primary">
                                <i class="bi bi-mortarboard fs-1"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h4 class="mb-0">3</h4>
                                <p class="text-muted mb-0">Completadas</p>
                            </div>
                            <div class="text-success">
                                <i class="bi bi-check-circle fs-1"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h4 class="mb-0">1</h4>
                                <p class="text-muted mb-0">En Curso</p>
                            </div>
                            <div class="text-warning">
                                <i class="bi bi-clock fs-1"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h4 class="mb-0">120</h4>
                                <p class="text-muted mb-0">Horas Totales</p>
                            </div>
                            <div class="text-info">
                                <i class="bi bi-clock-history fs-1"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Training List -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Lista de Capacitaciones</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Capacitación</th>
                                        <th>Institución</th>
                                        <th>Fecha</th>
                                        <th>Horas</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <div>
                                                <h6 class="mb-0">Excel Avanzado para Administrativos</h6>
                                                <small class="text-muted">Capacitación en herramientas de Office</small>
                                            </div>
                                        </td>
                                        <td>Microsoft Learning Center</td>
                                        <td>15/01/2025 - 20/01/2025</td>
                                        <td>40 horas</td>
                                        <td><span class="badge bg-success">Completada</span></td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-primary" title="Ver Detalles">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-success" title="Descargar Certificado">
                                                <i class="bi bi-download"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div>
                                                <h6 class="mb-0">Gestión de Proyectos con Metodologías Ágiles</h6>
                                                <small class="text-muted">Capacitación en metodologías modernas</small>
                                            </div>
                                        </td>
                                        <td>Instituto de Gestión Empresarial</td>
                                        <td>25/01/2025 - 30/01/2025</td>
                                        <td>30 horas</td>
                                        <td><span class="badge bg-warning">En Curso</span></td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-primary" title="Ver Detalles">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-info" title="Continuar">
                                                <i class="bi bi-play-circle"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div>
                                                <h6 class="mb-0">Liderazgo y Trabajo en Equipo</h6>
                                                <small class="text-muted">Desarrollo de habilidades blandas</small>
                                            </div>
                                        </td>
                                        <td>Centro de Desarrollo Humano</td>
                                        <td>10/02/2025 - 15/02/2025</td>
                                        <td>25 horas</td>
                                        <td><span class="badge bg-secondary">Pendiente</span></td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-primary" title="Ver Detalles">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-warning" title="Iniciar">
                                                <i class="bi bi-play"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Available Trainings -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Capacitaciones Disponibles</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 col-lg-4 mb-3">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <h6 class="card-title">Comunicación Efectiva</h6>
                                        <p class="card-text text-muted">Mejora tus habilidades de comunicación en el entorno laboral.</p>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="badge bg-primary">20 horas</span>
                                            <button class="btn btn-sm btn-primary">Solicitar</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-4 mb-3">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <h6 class="card-title">Gestión del Tiempo</h6>
                                        <p class="card-text text-muted">Aprende técnicas efectivas para optimizar tu productividad.</p>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="badge bg-primary">15 horas</span>
                                            <button class="btn btn-sm btn-primary">Solicitar</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-4 mb-3">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <h6 class="card-title">Resolución de Conflictos</h6>
                                        <p class="card-text text-muted">Desarrolla habilidades para manejar situaciones difíciles.</p>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="badge bg-primary">18 horas</span>
                                            <button class="btn btn-sm btn-primary">Solicitar</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Request Training Modal -->
<div class="modal fade" id="requestTrainingModal" tabindex="-1" aria-labelledby="requestTrainingModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="requestTrainingModalLabel">Solicitar Capacitación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= base_url('empleado/capacitaciones/solicitar') ?>" method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nombre de la Capacitación</label>
                        <input type="text" class="form-control" name="nombre_capacitacion" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Institución</label>
                        <input type="text" class="form-control" name="institucion" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Fecha de Inicio</label>
                            <input type="date" class="form-control" name="fecha_inicio" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Fecha de Fin</label>
                            <input type="date" class="form-control" name="fecha_fin" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Número de Horas</label>
                        <input type="number" class="form-control" name="numero_horas" min="1" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tipo de Capacitación</label>
                        <select class="form-select" name="tipo_capacitacion" required>
                            <option value="">Seleccionar tipo</option>
                            <option value="obligatoria">Obligatoria</option>
                            <option value="voluntaria">Voluntaria</option>
                            <option value="especializacion">Especialización</option>
                            <option value="actualizacion">Actualización</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Justificación</label>
                        <textarea class="form-control" name="justificacion" rows="3" placeholder="Explica por qué necesitas esta capacitación"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Enviar Solicitud</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
