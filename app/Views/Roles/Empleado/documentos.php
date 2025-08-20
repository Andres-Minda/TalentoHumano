<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>

<div class="page-wrapper">
    <div class="page-content">
        <!-- Header -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <h4 class="mb-0"><i class="bi bi-file-earmark-text"></i> Mis Documentos</h4>
                    <div class="page-title-right">
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#uploadDocumentModal">
                            <i class="bi bi-upload me-1"></i>Subir Documento
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Document Categories -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Categorías de Documentos</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3 col-sm-6 mb-3">
                                <div class="text-center p-3 border rounded">
                                    <i class="bi bi-file-earmark-text text-primary fs-1"></i>
                                    <h6 class="mt-2">Documentos Personales</h6>
                                    <span class="badge bg-primary">5</span>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6 mb-3">
                                <div class="text-center p-3 border rounded">
                                    <i class="bi bi-mortarboard text-success fs-1"></i>
                                    <h6 class="mt-2">Títulos Académicos</h6>
                                    <span class="badge bg-success">3</span>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6 mb-3">
                                <div class="text-center p-3 border rounded">
                                    <i class="bi bi-award text-warning fs-1"></i>
                                    <h6 class="mt-2">Certificados</h6>
                                    <span class="badge bg-warning">2</span>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6 mb-3">
                                <div class="text-center p-3 border rounded">
                                    <i class="bi bi-briefcase text-info fs-1"></i>
                                    <h6 class="mt-2">Contratos</h6>
                                    <span class="badge bg-info">1</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Documents List -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Lista de Documentos</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Documento</th>
                                        <th>Categoría</th>
                                        <th>Fecha de Subida</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <i class="bi bi-file-pdf text-danger me-2"></i>
                                                <div>
                                                    <h6 class="mb-0">Cédula de Identidad</h6>
                                                    <small class="text-muted">cedula.pdf</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td><span class="badge bg-primary">Personal</span></td>
                                        <td>15/01/2025</td>
                                        <td><span class="badge bg-success">Aprobado</span></td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-primary" title="Ver">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-success" title="Descargar">
                                                <i class="bi bi-download"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-danger" title="Eliminar">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <i class="bi bi-file-earmark-text text-primary me-2"></i>
                                                <div>
                                                    <h6 class="mb-0">Título Universitario</h6>
                                                    <small class="text-muted">titulo_ingenieria.pdf</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td><span class="badge bg-success">Académico</span></td>
                                        <td>20/01/2025</td>
                                        <td><span class="badge bg-warning">Pendiente</span></td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-primary" title="Ver">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-success" title="Descargar">
                                                <i class="bi bi-download"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-danger" title="Eliminar">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <i class="bi bi-file-earmark-text text-primary me-2"></i>
                                                <div>
                                                    <h6 class="mb-0">Certificado de Capacitación</h6>
                                                    <small class="text-muted">certificado_excel.pdf</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td><span class="badge bg-warning">Certificado</span></td>
                                        <td>25/01/2025</td>
                                        <td><span class="badge bg-success">Aprobado</span></td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-primary" title="Ver">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-success" title="Descargar">
                                                <i class="bi bi-download"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-danger" title="Eliminar">
                                                <i class="bi bi-trash"></i>
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

<!-- Upload Document Modal -->
<div class="modal fade" id="uploadDocumentModal" tabindex="-1" aria-labelledby="uploadDocumentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="uploadDocumentModalLabel">Subir Nuevo Documento</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= base_url('empleado/documentos/subir') ?>" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nombre del Documento</label>
                        <input type="text" class="form-control" name="nombre_documento" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Categoría</label>
                        <select class="form-select" name="categoria" required>
                            <option value="">Seleccionar categoría</option>
                            <option value="personal">Documentos Personales</option>
                            <option value="academico">Títulos Académicos</option>
                            <option value="certificado">Certificados</option>
                            <option value="contrato">Contratos</option>
                            <option value="otro">Otros</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Descripción</label>
                        <textarea class="form-control" name="descripcion" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Archivo</label>
                        <input type="file" class="form-control" name="archivo" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" required>
                        <small class="text-muted">Formatos permitidos: PDF, DOC, DOCX, JPG, JPEG, PNG. Máximo 5MB.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Subir Documento</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
