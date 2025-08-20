<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>

<div class="page-wrapper">
    <div class="page-content">
        <!-- Header -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <h4 class="mb-0"><i class="bi bi-star"></i> Mis Competencias</h4>
                    <div class="page-title-right">
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCompetencyModal">
                            <i class="bi bi-plus-circle me-1"></i>Agregar Competencia
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Competency Statistics -->
        <div class="row">
            <div class="col-xl-3 col-lg-6 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h4 class="mb-0">12</h4>
                                <p class="text-muted mb-0">Total Competencias</p>
                            </div>
                            <div class="text-primary">
                                <i class="bi bi-star fs-1"></i>
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
                                <h4 class="mb-0">8</h4>
                                <p class="text-muted mb-0">Avanzadas</p>
                            </div>
                            <div class="text-success">
                                <i class="bi bi-star-fill fs-1"></i>
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
                                <p class="text-muted mb-0">Intermedias</p>
                            </div>
                            <div class="text-warning">
                                <i class="bi bi-star-half fs-1"></i>
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
                                <p class="text-muted mb-0">Básicas</p>
                            </div>
                            <div class="text-info">
                                <i class="bi bi-star fs-1"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Competency Categories -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Categorías de Competencias</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3 col-sm-6 mb-3">
                                <div class="text-center p-3 border rounded">
                                    <i class="bi bi-laptop text-primary fs-1"></i>
                                    <h6 class="mt-2">Tecnológicas</h6>
                                    <span class="badge bg-success">5 competencias</span>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6 mb-3">
                                <div class="text-center p-3 border rounded">
                                    <i class="bi bi-people text-success fs-1"></i>
                                    <h6 class="mt-2">Interpersonales</h6>
                                    <span class="badge bg-success">4 competencias</span>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6 mb-3">
                                <div class="text-center p-3 border rounded">
                                    <i class="bi bi-gear text-warning fs-1"></i>
                                    <h6 class="mt-2">Organizacionales</h6>
                                    <span class="badge bg-warning">2 competencias</span>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6 mb-3">
                                <div class="text-center p-3 border rounded">
                                    <i class="bi bi-lightbulb text-info fs-1"></i>
                                    <h6 class="mt-2">Creativas</h6>
                                    <span class="badge bg-info">1 competencia</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Competencies List -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Lista de Competencias</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Competencia</th>
                                        <th>Categoría</th>
                                        <th>Nivel</th>
                                        <th>Última Evaluación</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <div>
                                                <h6 class="mb-0">Microsoft Office Suite</h6>
                                                <small class="text-muted">Dominio completo de Word, Excel, PowerPoint</small>
                                            </div>
                                        </td>
                                        <td><span class="badge bg-primary">Tecnológica</span></td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <span class="badge bg-success me-2">Avanzado</span>
                                                <div class="progress flex-grow-1" style="height: 8px;">
                                                    <div class="progress-bar bg-success" style="width: 90%"></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>15/01/2025</td>
                                        <td><span class="badge bg-success">Activa</span></td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-primary" title="Ver Detalles">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-warning" title="Editar">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div>
                                                <h6 class="mb-0">Comunicación Efectiva</h6>
                                                <small class="text-muted">Habilidad para transmitir ideas claramente</small>
                                            </div>
                                        </td>
                                        <td><span class="badge bg-success">Interpersonal</span></td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <span class="badge bg-success me-2">Avanzado</span>
                                                <div class="progress flex-grow-1" style="height: 8px;">
                                                    <div class="progress-bar bg-success" style="width: 85%"></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>20/01/2025</td>
                                        <td><span class="badge bg-success">Activa</span></td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-primary" title="Ver Detalles">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-warning" title="Editar">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div>
                                                <h6 class="mb-0">Gestión de Proyectos</h6>
                                                <small class="text-muted">Planificación y ejecución de proyectos</small>
                                            </div>
                                        </td>
                                        <td><span class="badge bg-warning">Organizacional</span></td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <span class="badge bg-warning me-2">Intermedio</span>
                                                <div class="progress flex-grow-1" style="height: 8px;">
                                                    <div class="progress-bar bg-warning" style="width: 65%"></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>25/01/2025</td>
                                        <td><span class="badge bg-success">Activa</span></td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-primary" title="Ver Detalles">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-warning" title="Editar">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div>
                                                <h6 class="mb-0">Resolución de Problemas</h6>
                                                <small class="text-muted">Análisis y solución de situaciones complejas</small>
                                            </div>
                                        </td>
                                        <td><span class="badge bg-success">Interpersonal</span></td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <span class="badge bg-success me-2">Avanzado</span>
                                                <div class="progress flex-grow-1" style="height: 8px;">
                                                    <div class="progress-bar bg-success" style="width: 80%"></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>30/01/2025</td>
                                        <td><span class="badge bg-success">Activa</span></td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-primary" title="Ver Detalles">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-warning" title="Editar">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div>
                                                <h6 class="mb-0">Creatividad e Innovación</h6>
                                                <small class="text-muted">Generación de ideas originales</small>
                                            </div>
                                        </td>
                                        <td><span class="badge bg-info">Creativa</span></td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <span class="badge bg-info me-2">Básico</span>
                                                <div class="progress flex-grow-1" style="height: 8px;">
                                                    <div class="progress-bar bg-info" style="width: 40%"></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>05/02/2025</td>
                                        <td><span class="badge bg-warning">En Desarrollo</span></td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-primary" title="Ver Detalles">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-warning" title="Editar">
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

        <!-- Development Plan -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Plan de Desarrollo de Competencias</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6>Competencias a Desarrollar</h6>
                                <div class="list-group">
                                    <div class="list-group-item d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-1">Creatividad e Innovación</h6>
                                            <small class="text-muted">Nivel actual: Básico → Meta: Intermedio</small>
                                        </div>
                                        <span class="badge bg-warning">En Progreso</span>
                                    </div>
                                    <div class="list-group-item d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-1">Gestión de Proyectos</h6>
                                            <small class="text-muted">Nivel actual: Intermedio → Meta: Avanzado</small>
                                        </div>
                                        <span class="badge bg-info">Planificado</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h6>Recomendaciones</h6>
                                <div class="alert alert-info" role="alert">
                                    <h6 class="alert-heading">Para mejorar Creatividad e Innovación:</h6>
                                    <ul class="mb-0">
                                        <li>Participar en talleres de brainstorming</li>
                                        <li>Leer libros sobre innovación</li>
                                        <li>Colaborar en proyectos creativos</li>
                                    </ul>
                                </div>
                                <div class="alert alert-warning" role="alert">
                                    <h6 class="alert-heading">Para mejorar Gestión de Proyectos:</h6>
                                    <ul class="mb-0">
                                        <li>Tomar curso de metodologías ágiles</li>
                                        <li>Practicar con proyectos pequeños</li>
                                        <li>Mentoría de expertos</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Competency Modal -->
<div class="modal fade" id="addCompetencyModal" tabindex="-1" aria-labelledby="addCompetencyModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addCompetencyModalLabel">Agregar Nueva Competencia</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= base_url('empleado/competencias/agregar') ?>" method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nombre de la Competencia</label>
                        <input type="text" class="form-control" name="nombre_competencia" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Descripción</label>
                        <textarea class="form-control" name="descripcion" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Categoría</label>
                        <select class="form-select" name="categoria" required>
                            <option value="">Seleccionar categoría</option>
                            <option value="tecnologica">Tecnológica</option>
                            <option value="interpersonal">Interpersonal</option>
                            <option value="organizacional">Organizacional</option>
                            <option value="creativa">Creativa</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nivel Actual</label>
                        <select class="form-select" name="nivel_actual" required>
                            <option value="">Seleccionar nivel</option>
                            <option value="basico">Básico</option>
                            <option value="intermedio">Intermedio</option>
                            <option value="avanzado">Avanzado</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nivel Objetivo</label>
                        <select class="form-select" name="nivel_objetivo" required>
                            <option value="">Seleccionar nivel objetivo</option>
                            <option value="intermedio">Intermedio</option>
                            <option value="avanzado">Avanzado</option>
                            <option value="experto">Experto</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Fecha Objetivo</label>
                        <input type="date" class="form-control" name="fecha_objetivo" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Agregar Competencia</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
