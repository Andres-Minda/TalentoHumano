<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="<?= base_url('index.php/empleado/dashboard') ?>">Dashboard</a></li>
                        <li class="breadcrumb-item active">Títulos Académicos</li>
                    </ol>
                </div>
                <h4 class="page-title">Mis Títulos Académicos</h4>
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
                                <h5 class="font-weight-semibold mb-0"><?= $estadisticas['total_titulos'] ?? 0 ?></h5>
                            </div>
                            <div class="stat-digit d-flex">
                                <h5 class="font-weight-semibold mb-0">Títulos</h5>
                            </div>
                        </div>
                        <div class="ms-auto">
                            <i class="bi bi-mortarboard text-primary" style="font-size: 2rem;"></i>
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
                                <h5 class="font-weight-semibold mb-0"><?= $estadisticas['titulos_tercer_nivel'] ?? 0 ?></h5>
                            </div>
                            <div class="stat-digit d-flex">
                                <h5 class="font-weight-semibold mb-0">Tercer Nivel</h5>
                            </div>
                        </div>
                        <div class="ms-auto">
                            <i class="bi bi-award text-success" style="font-size: 2rem;"></i>
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
                                <h5 class="font-weight-semibold mb-0"><?= $estadisticas['titulos_cuarto_nivel'] ?? 0 ?></h5>
                            </div>
                            <div class="stat-digit d-flex">
                                <h5 class="font-weight-semibold mb-0">Cuarto Nivel</h5>
                            </div>
                        </div>
                        <div class="ms-auto">
                            <i class="bi bi-star text-warning" style="font-size: 2rem;"></i>
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
                                <h5 class="font-weight-semibold mb-0"><?= $estadisticas['universidades'] ?? 0 ?></h5>
                            </div>
                            <div class="stat-digit d-flex">
                                <h5 class="font-weight-semibold mb-0">Universidades</h5>
                            </div>
                        </div>
                        <div class="ms-auto">
                            <i class="bi bi-building text-info" style="font-size: 2rem;"></i>
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
                            <label for="filtroTipo" class="form-label">Tipo de Título</label>
                            <select class="form-select" id="filtroTipo">
                                <option value="">Todos los tipos</option>
                                <option value="Tercer Nivel">Tercer Nivel</option>
                                <option value="Cuarto Nivel">Cuarto Nivel</option>
                                <option value="Ph.D.">Ph.D.</option>
                                <option value="Doctorado">Doctorado</option>
                                <option value="Maestría">Maestría</option>
                                <option value="Especialización">Especialización</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="filtroUniversidad" class="form-label">Universidad</label>
                            <select class="form-select" id="filtroUniversidad">
                                <option value="">Todas las universidades</option>
                                <?php if (isset($universidades) && !empty($universidades)): ?>
                                    <?php foreach ($universidades as $universidad): ?>
                                        <option value="<?= $universidad ?>"><?= $universidad ?></option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="filtroFecha" class="form-label">Fecha Obtención</label>
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

    <!-- Títulos Académicos Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <div>
                        <h4 class="header-title">Títulos Académicos Obtenidos</h4>
                        <p class="text-muted mb-0">Gestiona y visualiza tus títulos académicos</p>
                    </div>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalAgregarTitulo">
                        <i class="bi bi-plus-circle"></i> Agregar Título
                    </button>
                </div>
                <div class="card-body">
                    <?php if (isset($titulos) && !empty($titulos)): ?>
                        <div class="table-responsive">
                            <table class="table table-striped" id="tablaTitulos">
                                <thead>
                                    <tr>
                                        <th>Título</th>
                                        <th>Universidad</th>
                                        <th>Tipo</th>
                                        <th>Fecha Obtención</th>
                                        <th>País</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($titulos as $titulo): ?>
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <i class="bi bi-mortarboard me-2 text-primary"></i>
                                                    <div>
                                                        <h6 class="mb-0"><?= $titulo['nombre_titulo'] ?? 'N/A' ?></h6>
                                                        <small class="text-muted"><?= $titulo['descripcion'] ?? '' ?></small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td><?= $titulo['universidad'] ?? 'N/A' ?></td>
                                            <td>
                                                <span class="badge bg-info"><?= $titulo['tipo_titulo'] ?? 'N/A' ?></span>
                                            </td>
                                            <td><?= isset($titulo['fecha_obtencion']) ? date('d/m/Y', strtotime($titulo['fecha_obtencion'])) : 'N/A' ?></td>
                                            <td><?= $titulo['pais'] ?? 'N/A' ?></td>
                                            <td>
                                                <span class="badge bg-success">Vigente</span>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="verTitulo(<?= $titulo['id_titulo'] ?? 0 ?>)">
                                                        <i class="bi bi-eye"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-outline-success" onclick="descargarTitulo(<?= $titulo['id_titulo'] ?? 0 ?>)">
                                                        <i class="bi bi-download"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-outline-warning" onclick="editarTitulo(<?= $titulo['id_titulo'] ?? 0 ?>)">
                                                        <i class="bi bi-pencil"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="eliminarTitulo(<?= $titulo['id_titulo'] ?? 0 ?>)">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-info text-center">
                            <div class="mb-3">
                                <i class="bi bi-mortarboard" style="font-size: 3rem; color: #0dcaf0;"></i>
                            </div>
                            <h5 class="alert-heading">No hay títulos académicos registrados</h5>
                            <p class="mb-0">No se encontraron títulos académicos en tu perfil. Puedes agregar un nuevo título académico usando el botón "Agregar Título" o contacta al administrador para agregar esta información.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Agregar Título -->
<div class="modal fade" id="modalAgregarTitulo" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Agregar Nuevo Título Académico</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formAgregarTitulo" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="nombre_titulo" class="form-label">Nombre del Título</label>
                                <input type="text" class="form-control" id="nombre_titulo" name="nombre_titulo" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="tipo_titulo" class="form-label">Tipo de Título</label>
                                <select class="form-select" id="tipo_titulo" name="tipo_titulo" required>
                                    <option value="">Seleccionar tipo</option>
                                    <option value="Tercer Nivel">Tercer Nivel</option>
                                    <option value="Cuarto Nivel">Cuarto Nivel</option>
                                    <option value="Ph.D.">Ph.D.</option>
                                    <option value="Doctorado">Doctorado</option>
                                    <option value="Maestría">Maestría</option>
                                    <option value="Especialización">Especialización</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="universidad" class="form-label">Universidad</label>
                                <input type="text" class="form-control" id="universidad" name="universidad" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="pais" class="form-label">País</label>
                                <input type="text" class="form-control" id="pais" name="pais">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="fecha_obtencion" class="form-label">Fecha de Obtención</label>
                                <input type="date" class="form-control" id="fecha_obtencion" name="fecha_obtencion" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="archivo_titulo" class="form-label">Archivo del Título</label>
                                <input type="file" class="form-control" id="archivo_titulo" name="archivo_titulo" accept=".pdf,.jpg,.jpeg,.png">
                                <div class="form-text">Formatos permitidos: PDF, JPG, JPEG, PNG</div>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripción</label>
                        <textarea class="form-control" id="descripcion" name="descripcion" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Agregar Título</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize DataTable if there are titles
    <?php if (isset($titulos) && !empty($titulos)): ?>
    $('#tablaTitulos').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json'
        },
        pageLength: 10,
        order: [[3, 'desc']]
    });
    <?php endif; ?>

    // Form submission
    $('#formAgregarTitulo').on('submit', function(e) {
        e.preventDefault();
        agregarTitulo();
    });
});

function aplicarFiltros() {
    const tipo = $('#filtroTipo').val();
    const universidad = $('#filtroUniversidad').val();
    const fecha = $('#filtroFecha').val();
    
    // Aplicar filtros a la tabla
    if (typeof $.fn.DataTable !== 'undefined') {
        const tabla = $('#tablaTitulos').DataTable();
        tabla.draw();
    }
}

function agregarTitulo() {
    const formData = new FormData(document.getElementById('formAgregarTitulo'));
    
    Swal.fire({
        title: 'Agregando título...',
        text: 'Por favor espere',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    fetch('<?= base_url('index.php/empleado/titulos-academicos/agregar') ?>', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Título agregado',
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
            text: 'Error al agregar el título académico',
            confirmButtonText: 'Aceptar'
        });
    });
}

function verTitulo(idTitulo) {
    Swal.fire({
        icon: 'info',
        title: 'Ver Título',
        text: 'Función para ver detalles del título académico',
        confirmButtonText: 'Aceptar'
    });
}

function descargarTitulo(idTitulo) {
    Swal.fire({
        icon: 'info',
        title: 'Descargar Título',
        text: 'Función para descargar el archivo del título',
        confirmButtonText: 'Aceptar'
    });
}

function editarTitulo(idTitulo) {
    Swal.fire({
        icon: 'info',
        title: 'Editar Título',
        text: 'Función para editar el título académico',
        confirmButtonText: 'Aceptar'
    });
}

function eliminarTitulo(idTitulo) {
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
            Swal.fire({
                icon: 'success',
                title: 'Título eliminado',
                text: 'El título académico ha sido eliminado',
                confirmButtonText: 'Aceptar'
            });
        }
    });
}
</script>

<?= $this->endSection() ?>
