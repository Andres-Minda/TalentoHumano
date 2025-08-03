<?php $sidebar = 'sidebar_docente'; ?>
<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>

<div class="page-wrapper">
    <div class="page-content">
        <!-- Header -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <h4 class="mb-0"><i class="bi bi-file-earmark-text"></i> Gestión de Documentos</h4>
                    <div class="page-title-right">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalSubirDocumento">
                            <i class="bi bi-upload"></i> Subir Documento
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row">
            <div class="col-xl-3 col-lg-6 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="me-3">
                                <span class="round-8 bg-primary rounded-circle me-2 d-inline-block"></span>
                                <div class="stat-digit d-flex">
                                    <h5 class="font-weight-semibold mb-0"><?= $estadisticas['total_documentos'] ?? 0 ?></h5>
                                </div>
                                <div class="stat-digit d-flex">
                                    <h5 class="font-weight-semibold mb-0">Documentos</h5>
                                </div>
                            </div>
                            <div class="ms-auto">
                                <i class="bi bi-file-earmark-text text-primary" style="font-size: 2rem;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="me-3">
                                <span class="round-8 bg-success rounded-circle me-2 d-inline-block"></span>
                                <div class="stat-digit d-flex">
                                    <h5 class="font-weight-semibold mb-0"><?= $estadisticas['documentos_aprobados'] ?? 0 ?></h5>
                                </div>
                                <div class="stat-digit d-flex">
                                    <h5 class="font-weight-semibold mb-0">Aprobados</h5>
                                </div>
                            </div>
                            <div class="ms-auto">
                                <i class="bi bi-check-circle text-success" style="font-size: 2rem;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="me-3">
                                <span class="round-8 bg-warning rounded-circle me-2 d-inline-block"></span>
                                <div class="stat-digit d-flex">
                                    <h5 class="font-weight-semibold mb-0"><?= $estadisticas['documentos_pendientes'] ?? 0 ?></h5>
                                </div>
                                <div class="stat-digit d-flex">
                                    <h5 class="font-weight-semibold mb-0">Pendientes</h5>
                                </div>
                            </div>
                            <div class="ms-auto">
                                <i class="bi bi-clock text-warning" style="font-size: 2rem;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="me-3">
                                <span class="round-8 bg-info rounded-circle me-2 d-inline-block"></span>
                                <div class="stat-digit d-flex">
                                    <h5 class="font-weight-semibold mb-0"><?= $estadisticas['categorias'] ?? 0 ?></h5>
                                </div>
                                <div class="stat-digit d-flex">
                                    <h5 class="font-weight-semibold mb-0">Categorías</h5>
                                </div>
                            </div>
                            <div class="ms-auto">
                                <i class="bi bi-folder text-info" style="font-size: 2rem;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <label for="filtroCategoria" class="form-label">Categoría</label>
                                <select class="form-select" id="filtroCategoria">
                                    <option value="">Todas las categorías</option>
                                    <?php foreach ($categorias as $categoria): ?>
                                    <option value="<?= $categoria['id_categoria'] ?>"><?= $categoria['nombre'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="filtroEstado" class="form-label">Estado</label>
                                <select class="form-select" id="filtroEstado">
                                    <option value="">Todos los estados</option>
                                    <option value="Aprobado">Aprobado</option>
                                    <option value="Pendiente">Pendiente</option>
                                    <option value="Rechazado">Rechazado</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="filtroFecha" class="form-label">Fecha</label>
                                <input type="date" class="form-control" id="filtroFecha">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">&nbsp;</label>
                                <button type="button" class="btn btn-secondary d-block w-100" onclick="aplicarFiltros()">
                                    <i class="bi bi-funnel"></i> Aplicar Filtros
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Documents Table -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Mis Documentos</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped" id="tablaDocumentos">
                                <thead>
                                    <tr>
                                        <th>Documento</th>
                                        <th>Categoría</th>
                                        <th>Fecha Subida</th>
                                        <th>Estado</th>
                                        <th>Tamaño</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($documentos as $documento): ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <i class="bi bi-file-earmark-text me-2 text-primary"></i>
                                                <div>
                                                    <h6 class="mb-0"><?= $documento['nombre'] ?></h6>
                                                    <small class="text-muted"><?= $documento['descripcion'] ?></small>
                                                </div>
                                            </div>
                                        </td>
                                        <td><?= $documento['categoria_nombre'] ?></td>
                                        <td><?= date('d/m/Y', strtotime($documento['fecha_subida'])) ?></td>
                                        <td>
                                            <span class="badge bg-<?= $documento['estado'] == 'Aprobado' ? 'success' : ($documento['estado'] == 'Pendiente' ? 'warning' : 'danger') ?>">
                                                <?= $documento['estado'] ?>
                                            </span>
                                        </td>
                                        <td><?= $documento['tamaño'] ?></td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <button type="button" class="btn btn-sm btn-outline-primary" onclick="verDocumento(<?= $documento['id_documento'] ?>)">
                                                    <i class="bi bi-eye"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline-success" onclick="descargarDocumento(<?= $documento['id_documento'] ?>)">
                                                    <i class="bi bi-download"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline-warning" onclick="editarDocumento(<?= $documento['id_documento'] ?>)">
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline-danger" onclick="eliminarDocumento(<?= $documento['id_documento'] ?>)">
                                                    <i class="bi bi-trash"></i>
                                                </button>
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
</div>

<!-- Modal Subir Documento -->
<div class="modal fade" id="modalSubirDocumento" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Subir Nuevo Documento</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formSubirDocumento" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="nombre" class="form-label">Nombre del Documento</label>
                                <input type="text" class="form-control" id="nombre" name="nombre" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="categoria" class="form-label">Categoría</label>
                                <select class="form-select" id="categoria" name="categoria" required>
                                    <option value="">Seleccionar categoría</option>
                                    <?php foreach ($categorias as $categoria): ?>
                                    <option value="<?= $categoria['id_categoria'] ?>"><?= $categoria['nombre'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripción</label>
                        <textarea class="form-control" id="descripcion" name="descripcion" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="archivo" class="form-label">Archivo</label>
                        <input type="file" class="form-control" id="archivo" name="archivo" required>
                        <div class="form-text">Formatos permitidos: PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX</div>
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

<!-- Modal Ver Documento -->
<div class="modal fade" id="modalVerDocumento" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detalles del Documento</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="detallesDocumento">
                <!-- Contenido dinámico -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" onclick="descargarDocumentoActual()">Descargar</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize DataTable
    $('#tablaDocumentos').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json'
        },
        pageLength: 10,
        order: [[2, 'desc']]
    });

    // Form submission
    $('#formSubirDocumento').on('submit', function(e) {
        e.preventDefault();
        subirDocumento();
    });
});

function aplicarFiltros() {
    const categoria = $('#filtroCategoria').val();
    const estado = $('#filtroEstado').val();
    const fecha = $('#filtroFecha').val();
    
    // Aplicar filtros a la tabla
    const tabla = $('#tablaDocumentos').DataTable();
    tabla.draw();
}

function subirDocumento() {
    const formData = new FormData(document.getElementById('formSubirDocumento'));
    
    Swal.fire({
        title: 'Subiendo documento...',
        text: 'Por favor espere',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    fetch('<?= base_url('docente/documentos/subir') ?>', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Documento subido',
                text: data.message,
                confirmButtonText: 'Aceptar'
            }).then(() => {
                location.reload();
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: data.message,
                confirmButtonText: 'Aceptar'
            });
        }
    })
    .catch(error => {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Error al subir el documento',
            confirmButtonText: 'Aceptar'
        });
    });
}

function verDocumento(idDocumento) {
    fetch(`<?= base_url('docente/documentos/ver/') ?>${idDocumento}`)
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            $('#detallesDocumento').html(data.html);
            $('#modalVerDocumento').modal('show');
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: data.message,
                confirmButtonText: 'Aceptar'
            });
        }
    });
}

function descargarDocumento(idDocumento) {
    window.open(`<?= base_url('docente/documentos/descargar/') ?>${idDocumento}`, '_blank');
}

function editarDocumento(idDocumento) {
    // Implementar edición de documento
    Swal.fire({
        icon: 'info',
        title: 'Función en desarrollo',
        text: 'La edición de documentos estará disponible próximamente',
        confirmButtonText: 'Aceptar'
    });
}

function eliminarDocumento(idDocumento) {
    Swal.fire({
        title: '¿Está seguro?',
        text: "Esta acción no se puede deshacer",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`<?= base_url('docente/documentos/eliminar/') ?>${idDocumento}`, {
                method: 'POST'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Documento eliminado',
                        text: data.message,
                        confirmButtonText: 'Aceptar'
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message,
                        confirmButtonText: 'Aceptar'
                    });
                }
            });
        }
    });
}

function descargarDocumentoActual() {
    // Implementar descarga del documento actual
    Swal.fire({
        icon: 'info',
        title: 'Descarga iniciada',
        text: 'El documento se está descargando',
        confirmButtonText: 'Aceptar'
    });
}
</script>

<?= $this->endSection() ?> 