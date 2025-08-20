<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>

<div class="page-wrapper">
    <div class="page-content">
        <!-- Header -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <h4 class="mb-0"><i class="bi bi-mortarboard"></i> Mis Títulos Académicos</h4>
                    <div class="page-title-right">
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#nuevoTituloModal">
                            <i class="bi bi-plus-circle me-1"></i>Agregar Título
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="row">
            <div class="col-xl-3 col-lg-6 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h4 class="mb-0">4</h4>
                                <p class="text-muted mb-0">Total Títulos</p>
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
                                <h4 class="mb-0">2</h4>
                                <p class="text-muted mb-0">Universitarios</p>
                            </div>
                            <div class="text-success">
                                <i class="bi bi-building fs-1"></i>
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
                                <p class="text-muted mb-0">Técnicos</p>
                            </div>
                            <div class="text-info">
                                <i class="bi bi-tools fs-1"></i>
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
                                <p class="text-muted mb-0">Certificaciones</p>
                            </div>
                            <div class="text-warning">
                                <i class="bi bi-award fs-1"></i>
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
                        <div class="row">
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label">Nivel Académico</label>
                                    <select class="form-select" id="filtroNivel">
                                        <option value="">Todos los niveles</option>
                                        <option value="PRIMARIA">Primaria</option>
                                        <option value="SECUNDARIA">Secundaria</option>
                                        <option value="TECNICO">Técnico</option>
                                        <option value="TECNOLOGO">Tecnólogo</option>
                                        <option value="LICENCIATURA">Licenciatura</option>
                                        <option value="INGENIERIA">Ingeniería</option>
                                        <option value="MAESTRIA">Maestría</option>
                                        <option value="DOCTORADO">Doctorado</option>
                                        <option value="CERTIFICACION">Certificación</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label">Estado</label>
                                    <select class="form-select" id="filtroEstado">
                                        <option value="">Todos los estados</option>
                                        <option value="COMPLETADO">Completado</option>
                                        <option value="EN_CURSO">En Curso</option>
                                        <option value="SUSPENDIDO">Suspendido</option>
                                        <option value="CANCELADO">Cancelado</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Buscar</label>
                                    <input type="text" class="form-control" id="buscarTitulo" placeholder="Título, institución, área...">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="mb-3">
                                    <label class="form-label">&nbsp;</label>
                                    <button class="btn btn-outline-primary w-100" onclick="filtrarTitulos()">
                                        <i class="bi bi-search me-1"></i>Filtrar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Lista de Títulos Académicos -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Historial Académico</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover" id="titulosTable">
                                <thead>
                                    <tr>
                                        <th>Título</th>
                                        <th>Nivel</th>
                                        <th>Institución</th>
                                        <th>Fecha Obtención</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Título 1 -->
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <i class="bi bi-mortarboard text-primary me-2"></i>
                                                <div>
                                                    <h6 class="mb-0">Ingeniería en Sistemas</h6>
                                                    <small class="text-muted">Área: Tecnología</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td><span class="badge bg-success">INGENIERIA</span></td>
                                        <td>Universidad Técnica del Norte</td>
                                        <td>15/06/2020</td>
                                        <td><span class="badge bg-success">COMPLETADO</span></td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <button class="btn btn-sm btn-outline-primary" onclick="verTitulo(1)" title="Ver detalles">
                                                    <i class="bi bi-eye"></i>
                                                </button>
                                                <button class="btn btn-sm btn-outline-warning" onclick="editarTitulo(1)" title="Editar">
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                                <button class="btn btn-sm btn-outline-info" onclick="descargarTitulo(1)" title="Descargar">
                                                    <i class="bi bi-download"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <!-- Título 2 -->
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <i class="bi bi-award text-warning me-2"></i>
                                                <div>
                                                    <h6 class="mb-0">Técnico en Desarrollo Web</h6>
                                                    <small class="text-muted">Área: Programación</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td><span class="badge bg-info">TECNICO</span></td>
                                        <td>Instituto Tecnológico Superior</td>
                                        <td>20/12/2018</td>
                                        <td><span class="badge bg-success">COMPLETADO</span></td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <button class="btn btn-sm btn-outline-primary" onclick="verTitulo(2)" title="Ver detalles">
                                                    <i class="bi bi-eye"></i>
                                                </button>
                                                <button class="btn btn-sm btn-outline-warning" onclick="editarTitulo(2)" title="Editar">
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                                <button class="btn btn-sm btn-outline-info" onclick="descargarTitulo(2)" title="Descargar">
                                                    <i class="bi bi-download"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <!-- Título 3 -->
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <i class="bi bi-building text-success me-2"></i>
                                                <div>
                                                    <h6 class="mb-0">Licenciatura en Administración</h6>
                                                    <small class="text-muted">Área: Administración</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td><span class="badge bg-primary">LICENCIATURA</span></td>
                                        <td>Universidad Central del Ecuador</td>
                                        <td>10/08/2022</td>
                                        <td><span class="badge bg-success">COMPLETADO</span></td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <button class="btn btn-sm btn-outline-primary" onclick="verTitulo(3)" title="Ver detalles">
                                                    <i class="bi bi-eye"></i>
                                                </button>
                                                <button class="btn btn-sm btn-outline-warning" onclick="editarTitulo(3)" title="Editar">
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                                <button class="btn btn-sm btn-outline-info" onclick="descargarTitulo(3)" title="Descargar">
                                                    <i class="bi bi-download"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <!-- Título 4 -->
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <i class="bi bi-award text-info me-2"></i>
                                                <div>
                                                    <h6 class="mb-0">Certificación AWS Solutions Architect</h6>
                                                    <small class="text-muted">Área: Cloud Computing</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td><span class="badge bg-warning">CERTIFICACION</span></td>
                                        <td>Amazon Web Services</td>
                                        <td>05/03/2024</td>
                                        <td><span class="badge bg-success">COMPLETADO</span></td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <button class="btn btn-sm btn-outline-primary" onclick="verTitulo(4)" title="Ver detalles">
                                                    <i class="bi bi-eye"></i>
                                                </button>
                                                <button class="btn btn-sm btn-outline-warning" onclick="editarTitulo(4)" title="Editar">
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                                <button class="btn btn-sm btn-outline-info" onclick="descargarTitulo(4)" title="Descargar">
                                                    <i class="bi bi-download"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Paginación -->
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <div>
                                <small class="text-muted">Mostrando 1-4 de 4 títulos</small>
                            </div>
                            <nav>
                                <ul class="pagination pagination-sm mb-0">
                                    <li class="page-item disabled">
                                        <span class="page-link">Anterior</span>
                                    </li>
                                    <li class="page-item active">
                                        <span class="page-link">1</span>
                                    </li>
                                    <li class="page-item disabled">
                                        <span class="page-link">Siguiente</span>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Nuevo Título -->
<div class="modal fade" id="nuevoTituloModal" tabindex="-1" aria-labelledby="nuevoTituloModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="nuevoTituloModalLabel">Agregar Nuevo Título Académico</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= base_url('empleado/guardar-titulo-academico') ?>" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-8 mb-3">
                            <label class="form-label">Título Obtenido <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="titulo" required placeholder="Ej: Ingeniería en Sistemas">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Nivel Académico <span class="text-danger">*</span></label>
                            <select class="form-select" name="nivel_academico" required>
                                <option value="">Seleccionar nivel</option>
                                <option value="PRIMARIA">Primaria</option>
                                <option value="SECUNDARIA">Secundaria</option>
                                <option value="TECNICO">Técnico</option>
                                <option value="TECNOLOGO">Tecnólogo</option>
                                <option value="LICENCIATURA">Licenciatura</option>
                                <option value="INGENIERIA">Ingeniería</option>
                                <option value="MAESTRIA">Maestría</option>
                                <option value="DOCTORADO">Doctorado</option>
                                <option value="CERTIFICACION">Certificación</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Institución <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="institucion" required placeholder="Ej: Universidad Técnica del Norte">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Área de Estudio <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="area_estudio" required placeholder="Ej: Tecnología, Administración">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Fecha de Inicio</label>
                            <input type="date" class="form-control" name="fecha_inicio">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Fecha de Obtención <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" name="fecha_obtencion" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Estado <span class="text-danger">*</span></label>
                            <select class="form-select" name="estado" required>
                                <option value="">Seleccionar estado</option>
                                <option value="COMPLETADO">Completado</option>
                                <option value="EN_CURSO">En Curso</option>
                                <option value="SUSPENDIDO">Suspendido</option>
                                <option value="CANCELADO">Cancelado</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Promedio (si aplica)</label>
                            <input type="number" class="form-control" name="promedio" step="0.01" min="0" max="10" placeholder="Ej: 8.5">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Descripción</label>
                            <textarea class="form-control" name="descripcion" rows="3" placeholder="Descripción adicional del título, especializaciones, tesis, etc..."></textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Documentos de Soporte</label>
                            <input type="file" class="form-control" name="documentos_soporte" multiple accept=".pdf,.doc,.docx,.jpg,.png">
                            <small class="text-muted">Adjunta título, certificado, cédula profesional, o cualquier documento que acredite el título</small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-1"></i>Guardar Título
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Ver Detalles -->
<div class="modal fade" id="verTituloModal" tabindex="-1" aria-labelledby="verTituloModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="verTituloModalLabel">Detalles del Título Académico</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="detallesTitulo">
                    <!-- Los detalles se cargarán aquí dinámicamente -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-info" onclick="descargarTitulo()">
                    <i class="bi bi-download me-1"></i>Descargar Documentos
                </button>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    function filtrarTitulos() {
        const nivel = document.getElementById('filtroNivel').value;
        const estado = document.getElementById('filtroEstado').value;
        const busqueda = document.getElementById('buscarTitulo').value;
        
        // Aquí iría la lógica de filtrado
        console.log('Filtros aplicados:', { nivel, estado, busqueda });
        
        // Mostrar mensaje temporal
        alert('Filtros aplicados. Funcionalidad de filtrado en desarrollo.');
    }
    
    function verTitulo(id) {
        // Simulación de datos del título
        const titulos = {
            1: {
                titulo: 'Ingeniería en Sistemas',
                nivel: 'INGENIERIA',
                institucion: 'Universidad Técnica del Norte',
                areaEstudio: 'Tecnología',
                fechaInicio: '01/09/2016',
                fechaObtencion: '15/06/2020',
                estado: 'COMPLETADO',
                promedio: '8.7',
                descripcion: 'Especialización en desarrollo de software y sistemas de información. Tesis: "Sistema de Gestión de Inventarios para PYMES"',
                documentos: ['titulo_ingenieria.pdf', 'cedula_profesional.pdf', 'tesis.pdf']
            },
            2: {
                titulo: 'Técnico en Desarrollo Web',
                nivel: 'TECNICO',
                institucion: 'Instituto Tecnológico Superior',
                areaEstudio: 'Programación',
                fechaInicio: '01/03/2017',
                fechaObtencion: '20/12/2018',
                estado: 'COMPLETADO',
                promedio: '9.2',
                descripcion: 'Formación técnica en desarrollo web frontend y backend. Proyecto final: "E-commerce para tienda local"',
                documentos: ['certificado_tecnico.pdf', 'proyecto_final.pdf']
            }
        };
        
        const titulo = titulos[id];
        if (titulo) {
            document.getElementById('detallesTitulo').innerHTML = `
                <div class="row">
                    <div class="col-md-6">
                        <h6><i class="bi bi-mortarboard text-primary me-2"></i>Título</h6>
                        <p>${titulo.titulo}</p>
                    </div>
                    <div class="col-md-6">
                        <h6><i class="bi bi-tag text-info me-2"></i>Nivel</h6>
                        <span class="badge bg-info">${titulo.nivel}</span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <h6><i class="bi bi-building text-success me-2"></i>Institución</h6>
                        <p>${titulo.institucion}</p>
                    </div>
                    <div class="col-md-6">
                        <h6><i class="bi bi-book text-warning me-2"></i>Área de Estudio</h6>
                        <p>${titulo.areaEstudio}</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <h6><i class="bi bi-calendar-plus text-primary me-2"></i>Fecha de Inicio</h6>
                        <p>${titulo.fechaInicio}</p>
                    </div>
                    <div class="col-md-6">
                        <h6><i class="bi bi-calendar-check text-success me-2"></i>Fecha de Obtención</h6>
                        <p>${titulo.fechaObtencion}</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <h6><i class="bi bi-check-circle text-success me-2"></i>Estado</h6>
                        <span class="badge bg-success">${titulo.estado}</span>
                    </div>
                    <div class="col-md-6">
                        <h6><i class="bi bi-star text-warning me-2"></i>Promedio</h6>
                        <p>${titulo.promedio}/10</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <h6><i class="bi bi-chat-text text-primary me-2"></i>Descripción</h6>
                        <p>${titulo.descripcion}</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <h6><i class="bi bi-paperclip text-secondary me-2"></i>Documentos Adjuntos</h6>
                        <ul class="list-unstyled">
                            ${titulo.documentos.map(doc => `<li><i class="bi bi-file-earmark-text me-2"></i>${doc}</li>`).join('')}
                        </ul>
                    </div>
                </div>
            `;
            
            $('#verTituloModal').modal('show');
        }
    }
    
    function editarTitulo(id) {
        // Aquí iría la lógica para editar el título
        alert(`Editar título ID: ${id}. Funcionalidad en desarrollo.`);
    }
    
    function descargarTitulo(id) {
        // Aquí iría la lógica para descargar el título
        alert(`Descargando documentos del título ID: ${id}. Funcionalidad en desarrollo.`);
    }
</script>
<?= $this->endSection() ?>
