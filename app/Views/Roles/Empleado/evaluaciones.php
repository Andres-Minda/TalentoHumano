<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>

<div class="page-wrapper">
    <div class="page-content">
        <!-- Header -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <h4 class="mb-0"><i class="bi bi-clipboard-check"></i> Mis Evaluaciones</h4>
                    <div class="page-title-right">
                        <span class="text-muted">Periodo: <?= $periodo_activo['nombre'] ?? '2025-1' ?></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Evaluation Statistics -->
        <div class="row">
            <div class="col-xl-3 col-lg-6 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h4 class="mb-0">4</h4>
                                <p class="text-muted mb-0">Total Evaluaciones</p>
                            </div>
                            <div class="text-primary">
                                <i class="bi bi-clipboard-check fs-1"></i>
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
                                <p class="text-muted mb-0">Pendiente</p>
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
                                <h4 class="mb-0">8.5</h4>
                                <p class="text-muted mb-0">Promedio General</p>
                            </div>
                            <div class="text-info">
                                <i class="bi bi-star fs-1"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Evaluation Categories -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Categorías de Evaluación</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3 col-sm-6 mb-3">
                                <div class="text-center p-3 border rounded">
                                    <i class="bi bi-people text-primary fs-1"></i>
                                    <h6 class="mt-2">Evaluación Estudiantil</h6>
                                    <span class="badge bg-success">9.2</span>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6 mb-3">
                                <div class="text-center p-3 border rounded">
                                    <i class="bi bi-book text-success fs-1"></i>
                                    <h6 class="mt-2">Metodología de Enseñanza</h6>
                                    <span class="badge bg-success">8.8</span>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6 mb-3">
                                <div class="text-center p-3 border rounded">
                                    <i class="bi bi-search text-warning fs-1"></i>
                                    <h6 class="mt-2">Investigación</h6>
                                    <span class="badge bg-warning">7.5</span>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6 mb-3">
                                <div class="text-center p-3 border rounded">
                                    <i class="bi bi-gear text-info fs-1"></i>
                                    <h6 class="mt-2">Gestión Administrativa</h6>
                                    <span class="badge bg-success">8.9</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Evaluation List -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Lista de Evaluaciones</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Evaluación</th>
                                        <th>Categoría</th>
                                        <th>Periodo</th>
                                        <th>Fecha</th>
                                        <th>Calificación</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <div>
                                                <h6 class="mb-0">Evaluación Estudiantil Q1</h6>
                                                <small class="text-muted">Evaluación del primer cuatrimestre</small>
                                            </div>
                                        </td>
                                        <td><span class="badge bg-primary">Estudiantil</span></td>
                                        <td>2025-1</td>
                                        <td>15/01/2025</td>
                                        <td>
                                            <span class="badge bg-success">9.2/10</span>
                                        </td>
                                        <td><span class="badge bg-success">Completada</span></td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-primary" title="Ver Detalles">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-success" title="Descargar Reporte">
                                                <i class="bi bi-download"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div>
                                                <h6 class="mb-0">Metodología de Enseñanza</h6>
                                                <small class="text-muted">Evaluación de métodos pedagógicos</small>
                                            </div>
                                        </td>
                                        <td><span class="badge bg-success">Metodología</span></td>
                                        <td>2025-1</td>
                                        <td>20/01/2025</td>
                                        <td>
                                            <span class="badge bg-success">8.8/10</span>
                                        </td>
                                        <td><span class="badge bg-success">Completada</span></td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-primary" title="Ver Detalles">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-success" title="Descargar Reporte">
                                                <i class="bi bi-download"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div>
                                                <h6 class="mb-0">Investigación y Publicaciones</h6>
                                                <small class="text-muted">Evaluación de producción científica</small>
                                            </div>
                                        </td>
                                        <td><span class="badge bg-warning">Investigación</span></td>
                                        <td>2025-1</td>
                                        <td>25/01/2025</td>
                                        <td>
                                            <span class="badge bg-warning">7.5/10</span>
                                        </td>
                                        <td><span class="badge bg-success">Completada</span></td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-primary" title="Ver Detalles">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-success" title="Descargar Reporte">
                                                <i class="bi bi-download"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div>
                                                <h6 class="mb-0">Gestión Administrativa</h6>
                                                <small class="text-muted">Evaluación de funciones administrativas</small>
                                            </div>
                                        </td>
                                        <td><span class="badge bg-info">Administrativa</span></td>
                                        <td>2025-1</td>
                                        <td>30/01/2025</td>
                                        <td>
                                            <span class="badge bg-success">8.9/10</span>
                                        </td>
                                        <td><span class="badge bg-success">Completada</span></td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-primary" title="Ver Detalles">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-success" title="Descargar Reporte">
                                                <i class="bi bi-download"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div>
                                                <h6 class="mb-0">Evaluación Estudiantil Q2</h6>
                                                <small class="text-muted">Evaluación del segundo cuatrimestre</small>
                                            </div>
                                        </td>
                                        <td><span class="badge bg-primary">Estudiantil</span></td>
                                        <td>2025-1</td>
                                        <td>15/02/2025</td>
                                        <td>
                                            <span class="badge bg-secondary">Pendiente</span>
                                        </td>
                                        <td><span class="badge bg-warning">Pendiente</span></td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-warning" title="Realizar Evaluación">
                                                <i class="bi bi-pencil"></i>
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

        <!-- Performance Chart -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Rendimiento por Categoría</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6>Evaluación Estudiantil</h6>
                                <div class="progress mb-3" style="height: 25px;">
                                    <div class="progress-bar bg-success" role="progressbar" style="width: 92%">9.2/10</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h6>Metodología de Enseñanza</h6>
                                <div class="progress mb-3" style="height: 25px;">
                                    <div class="progress-bar bg-success" role="progressbar" style="width: 88%">8.8/10</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h6>Investigación</h6>
                                <div class="progress mb-3" style="height: 25px;">
                                    <div class="progress-bar bg-warning" role="progressbar" style="width: 75%">7.5/10</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h6>Gestión Administrativa</h6>
                                <div class="progress mb-3" style="height: 25px;">
                                    <div class="progress-bar bg-success" role="progressbar" style="width: 89%">8.9/10</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recommendations -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Recomendaciones de Mejora</h5>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-warning" role="alert">
                            <h6 class="alert-heading"><i class="bi bi-exclamation-triangle me-2"></i>Área de Mejora: Investigación</h6>
                            <p class="mb-0">Tu calificación en investigación es de 7.5/10. Se recomienda:</p>
                            <ul class="mb-0 mt-2">
                                <li>Participar en más proyectos de investigación</li>
                                <li>Publicar artículos en revistas indexadas</li>
                                <li>Asistir a conferencias y seminarios</li>
                                <li>Colaborar con otros investigadores</li>
                            </ul>
                        </div>
                        <div class="alert alert-info" role="alert">
                            <h6 class="alert-heading"><i class="bi bi-info-circle me-2"></i>Mantener Excelencia</h6>
                            <p class="mb-0">Tus áreas de fortaleza son:</p>
                            <ul class="mb-0 mt-2">
                                <li>Evaluación Estudiantil (9.2/10)</li>
                                <li>Metodología de Enseñanza (8.8/10)</li>
                                <li>Gestión Administrativa (8.9/10)</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
