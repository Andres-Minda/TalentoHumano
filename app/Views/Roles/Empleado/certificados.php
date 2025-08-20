<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>

<div class="page-wrapper">
    <div class="page-content">
        <!-- Header -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <h4 class="mb-0"><i class="bi bi-award"></i> Mis Certificados</h4>
                    <div class="page-title-right">
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#uploadCertificateModal">
                            <i class="bi bi-upload me-1"></i>Subir Certificado
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Certificate Statistics -->
        <div class="row">
            <div class="col-xl-3 col-lg-6 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h4 class="mb-0">8</h4>
                                <p class="text-muted mb-0">Total Certificados</p>
                            </div>
                            <div class="text-primary">
                                <i class="bi bi-award fs-1"></i>
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
                                <h4 class="mb-0">6</h4>
                                <p class="text-muted mb-0">Vigentes</p>
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
                                <h4 class="mb-0">2</h4>
                                <p class="text-muted mb-0">Por Vencer</p>
                            </div>
                            <div class="text-warning">
                                <i class="bi bi-exclamation-triangle fs-1"></i>
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
                                <h4 class="mb-0">150</h4>
                                <p class="text-muted mb-0">Horas Certificadas</p>
                            </div>
                            <div class="text-info">
                                <i class="bi bi-clock-history fs-1"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Certificates Grid -->
        <div class="row">
            <div class="col-md-6 col-lg-4 mb-3">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <i class="bi bi-award text-warning" style="font-size: 3rem;"></i>
                        </div>
                        <h6 class="card-title">Excel Avanzado</h6>
                        <p class="card-text text-muted">Microsoft Learning Center</p>
                        <div class="mb-2">
                            <span class="badge bg-success">Vigente</span>
                            <span class="badge bg-primary ms-1">40 horas</span>
                        </div>
                        <p class="text-muted small">Emitido: 20/01/2025</p>
                        <p class="text-muted small">Válido hasta: 20/01/2027</p>
                        <div class="mt-3">
                            <button class="btn btn-sm btn-outline-primary me-1" title="Ver">
                                <i class="bi bi-eye"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-success me-1" title="Descargar">
                                <i class="bi bi-download"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-info" title="Compartir">
                                <i class="bi bi-share"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6 col-lg-4 mb-3">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <i class="bi bi-award text-warning" style="font-size: 3rem;"></i>
                        </div>
                        <h6 class="card-title">Gestión de Proyectos</h6>
                        <p class="card-text text-muted">Instituto de Gestión Empresarial</p>
                        <div class="mb-2">
                            <span class="badge bg-success">Vigente</span>
                            <span class="badge bg-primary ms-1">30 horas</span>
                        </div>
                        <p class="text-muted small">Emitido: 30/01/2025</p>
                        <p class="text-muted small">Válido hasta: 30/01/2027</p>
                        <div class="mt-3">
                            <button class="btn btn-sm btn-outline-primary me-1" title="Ver">
                                <i class="bi bi-eye"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-success me-1" title="Descargar">
                                <i class="bi bi-download"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-info" title="Compartir">
                                <i class="bi bi-share"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6 col-lg-4 mb-3">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <i class="bi bi-award text-warning" style="font-size: 3rem;"></i>
                        </div>
                        <h6 class="card-title">Liderazgo y Trabajo en Equipo</h6>
                        <p class="card-text text-muted">Centro de Desarrollo Humano</p>
                        <div class="mb-2">
                            <span class="badge bg-warning">Por Vencer</span>
                            <span class="badge bg-primary ms-1">25 horas</span>
                        </div>
                        <p class="text-muted small">Emitido: 15/02/2025</p>
                        <p class="text-muted small">Válido hasta: 15/02/2026</p>
                        <div class="mt-3">
                            <button class="btn btn-sm btn-outline-primary me-1" title="Ver">
                                <i class="bi bi-eye"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-success me-1" title="Descargar">
                                <i class="bi bi-download"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-info" title="Compartir">
                                <i class="bi bi-share"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6 col-lg-4 mb-3">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <i class="bi bi-award text-warning" style="font-size: 3rem;"></i>
                        </div>
                        <h6 class="card-title">Comunicación Efectiva</h6>
                        <p class="card-text text-muted">Instituto de Comunicación</p>
                        <div class="mb-2">
                            <span class="badge bg-success">Vigente</span>
                            <span class="badge bg-primary ms-1">20 horas</span>
                        </div>
                        <p class="text-muted small">Emitido: 10/03/2025</p>
                        <p class="text-muted small">Válido hasta: 10/03/2027</p>
                        <div class="mt-3">
                            <button class="btn btn-sm btn-outline-primary me-1" title="Ver">
                                <i class="bi bi-eye"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-success me-1" title="Descargar">
                                <i class="bi bi-download"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-info" title="Compartir">
                                <i class="bi bi-share"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6 col-lg-4 mb-3">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <i class="bi bi-award text-warning" style="font-size: 3rem;"></i>
                        </div>
                        <h6 class="card-title">Gestión del Tiempo</h6>
                        <p class="card-text text-muted">Centro de Productividad</p>
                        <div class="mb-2">
                            <span class="badge bg-success">Vigente</span>
                            <span class="badge bg-primary ms-1">15 horas</span>
                        </div>
                        <p class="text-muted small">Emitido: 25/03/2025</p>
                        <p class="text-muted small">Válido hasta: 25/03/2027</p>
                        <div class="mt-3">
                            <button class="btn btn-sm btn-outline-primary me-1" title="Ver">
                                <i class="bi bi-eye"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-success me-1" title="Descargar">
                                <i class="bi bi-download"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-info" title="Compartir">
                                <i class="bi bi-share"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6 col-lg-4 mb-3">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <i class="bi bi-award text-warning" style="font-size: 3rem;"></i>
                        </div>
                        <h6 class="card-title">Resolución de Conflictos</h6>
                        <p class="card-text text-muted">Instituto de Mediación</p>
                        <div class="mb-2">
                            <span class="badge bg-warning">Por Vencer</span>
                            <span class="badge bg-primary ms-1">18 horas</span>
                        </div>
                        <p class="text-muted small">Emitido: 05/04/2025</p>
                        <p class="text-muted small">Válido hasta: 05/04/2026</p>
                        <div class="mt-3">
                            <button class="btn btn-sm btn-outline-primary me-1" title="Ver">
                                <i class="bi bi-eye"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-success me-1" title="Descargar">
                                <i class="bi bi-download"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-info" title="Compartir">
                                <i class="bi bi-share"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Expired Certificates -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Certificados Expirados</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Certificado</th>
                                        <th>Institución</th>
                                        <th>Fecha de Emisión</th>
                                        <th>Fecha de Expiración</th>
                                        <th>Horas</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Office Básico</td>
                                        <td>Microsoft Learning Center</td>
                                        <td>15/01/2023</td>
                                        <td>15/01/2025</td>
                                        <td>20 horas</td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-primary" title="Ver">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-success" title="Descargar">
                                                <i class="bi bi-download"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-warning" title="Renovar">
                                                <i class="bi bi-arrow-clockwise"></i>
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
    </div>
</div>

<!-- Upload Certificate Modal -->
<div class="modal fade" id="uploadCertificateModal" tabindex="-1" aria-labelledby="uploadCertificateModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="uploadCertificateModalLabel">Subir Nuevo Certificado</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= base_url('empleado/certificados/subir') ?>" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nombre del Certificado</label>
                        <input type="text" class="form-control" name="nombre_certificado" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Institución Emisora</label>
                        <input type="text" class="form-control" name="institucion" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Fecha de Emisión</label>
                            <input type="date" class="form-control" name="fecha_emision" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Fecha de Expiración</label>
                            <input type="date" class="form-control" name="fecha_expiracion">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Número de Horas</label>
                        <input type="number" class="form-control" name="numero_horas" min="1" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Descripción</label>
                        <textarea class="form-control" name="descripcion" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Archivo del Certificado</label>
                        <input type="file" class="form-control" name="archivo" accept=".pdf,.jpg,.jpeg,.png" required>
                        <small class="text-muted">Formatos permitidos: PDF, JPG, JPEG, PNG. Máximo 5MB.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Subir Certificado</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
