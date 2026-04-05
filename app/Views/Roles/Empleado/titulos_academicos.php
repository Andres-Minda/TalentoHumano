<?php $sidebar = 'sidebar_empleado'; ?>

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

        <!-- Summary Cards (dinámicas) -->
        <div class="row">
            <div class="col-xl-3 col-lg-6 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h4 class="mb-0" id="statTotal">–</h4>
                                <p class="text-muted mb-0">Total Títulos</p>
                            </div>
                            <div class="text-primary"><i class="bi bi-mortarboard fs-1"></i></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h4 class="mb-0" id="statUniv">–</h4>
                                <p class="text-muted mb-0">Universitarios</p>
                            </div>
                            <div class="text-success"><i class="bi bi-building fs-1"></i></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h4 class="mb-0" id="statSuperiores">–</h4>
                                <p class="text-muted mb-0">Posgrado</p>
                            </div>
                            <div class="text-info"><i class="bi bi-tools fs-1"></i></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h4 class="mb-0" id="statBachiller">–</h4>
                                <p class="text-muted mb-0">Bachiller</p>
                            </div>
                            <div class="text-warning"><i class="bi bi-award fs-1"></i></div>
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
                                    <label class="form-label">Tipo de Título</label>
                                    <select class="form-select" id="filtroNivel" onchange="aplicarFiltros()">
                                        <option value="">Todos los tipos</option>
                                        <option value="BACHILLER">Bachiller</option>
                                        <option value="LICENCIADO">Licenciado</option>
                                        <option value="INGENIERO">Ingeniero</option>
                                        <option value="MASTER">Master</option>
                                        <option value="DOCTOR">Doctor</option>
                                        <option value="POSTDOCTOR">Postdoctor</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label">País</label>
                                    <input type="text" class="form-control" id="filtroPais" placeholder="Ej: Ecuador" oninput="aplicarFiltros()">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Buscar</label>
                                    <input type="text" class="form-control" id="buscarTitulo" placeholder="Nombre del título, institución..." oninput="aplicarFiltros()">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="mb-3">
                                    <label class="form-label">&nbsp;</label>
                                    <button class="btn btn-outline-secondary w-100" onclick="limpiarFiltros()">
                                        <i class="bi bi-x-circle me-1"></i>Limpiar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Lista de Títulos -->
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
                                        <th>Tipo</th>
                                        <th>Institución</th>
                                        <th>Fecha Obtención</th>
                                        <th>Archivo</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody id="tbodyTitulos">
                                    <tr><td colspan="6" class="text-center py-4"><span class="spinner-border spinner-border-sm me-2"></span>Cargando...</td></tr>
                                </tbody>
                            </table>
                        </div>
                        <small class="text-muted" id="contadorTitulos"></small>
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
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formNuevoTitulo">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-8 mb-3">
                            <label class="form-label">Nombre del Título <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="new_nombre" required placeholder="Ej: Ingeniería en Sistemas">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Tipo <span class="text-danger">*</span></label>
                            <select class="form-select" id="new_tipo" required>
                                <option value="">Seleccionar tipo</option>
                                <option value="BACHILLER">Bachiller</option>
                                <option value="LICENCIADO">Licenciado</option>
                                <option value="INGENIERO">Ingeniero</option>
                                <option value="MASTER">Master</option>
                                <option value="DOCTOR">Doctor</option>
                                <option value="POSTDOCTOR">Postdoctor</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-8 mb-3">
                            <label class="form-label">Institución <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="new_univ" required placeholder="Ej: Universidad Técnica del Norte">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">País <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="new_pais" required placeholder="Ej: Ecuador">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Fecha de Obtención <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="new_fecha" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" onclick="guardarNuevoTitulo()">
                        <i class="bi bi-save me-1"></i>Guardar Título
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Editar Título -->
<div class="modal fade" id="editarTituloModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Editar Título Académico</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="edit_id">
                <div class="row">
                    <div class="col-md-8 mb-3">
                        <label class="form-label">Nombre del Título <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="edit_nombre" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Tipo <span class="text-danger">*</span></label>
                        <select class="form-select" id="edit_tipo" required>
                            <option value="BACHILLER">Bachiller</option>
                            <option value="LICENCIADO">Licenciado</option>
                            <option value="INGENIERO">Ingeniero</option>
                            <option value="MASTER">Master</option>
                            <option value="DOCTOR">Doctor</option>
                            <option value="POSTDOCTOR">Postdoctor</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-8 mb-3">
                        <label class="form-label">Institución <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="edit_univ" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">País <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="edit_pais" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Fecha de Obtención <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" id="edit_fecha" required>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-warning" onclick="guardarEdicion()">
                    <i class="bi bi-save me-1"></i>Actualizar
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Ver Detalles -->
<div class="modal fade" id="verTituloModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detalles del Título Académico</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="detallesTitulo"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-outline-info" onclick="descargarTitulo()">
                    <i class="bi bi-download me-1"></i>Descargar Documento
                </button>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
// Cache global
let titulosData = [];

document.addEventListener('DOMContentLoaded', () => cargarTitulos());

// ------- CARGA -------
function cargarTitulos() {
    fetch('<?= base_url('index.php/empleado/titulos-academicos/mis-titulos') ?>')
        .then(r => r.json())
        .then(data => {
            titulosData = data.titulos || [];
            actualizarStats();
            renderizarTabla(titulosData);
        })
        .catch(() => renderizarTabla([]));
}

// ------- ESTADÍSTICAS -------
function actualizarStats() {
    document.getElementById('statTotal').textContent      = titulosData.length;
    document.getElementById('statSuperiores').textContent = titulosData.filter(t => ['MASTER','DOCTOR','POSTDOCTOR'].includes(t.tipo_titulo)).length;
    document.getElementById('statUniv').textContent       = titulosData.filter(t => ['LICENCIADO','INGENIERO'].includes(t.tipo_titulo)).length;
    document.getElementById('statBachiller').textContent  = titulosData.filter(t => t.tipo_titulo === 'BACHILLER').length;
}

// ------- RENDER -------
function renderizarTabla(lista) {
    const tbody = document.getElementById('tbodyTitulos');
    const cnt   = document.getElementById('contadorTitulos');
    tbody.innerHTML = '';
    if (!lista || lista.length === 0) {
        tbody.innerHTML = '<tr><td colspan="6" class="text-center text-muted py-4"><i class="bi bi-inbox fs-3 d-block mb-2"></i>No hay títulos registrados</td></tr>';
        cnt.textContent = '';
        return;
    }
    cnt.textContent = `${lista.length} título(s) encontrado(s)`;
    lista.forEach(t => {
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td>
                <div class="d-flex align-items-center">
                    <i class="bi bi-mortarboard text-primary me-2"></i>
                    <div>
                        <h6 class="mb-0">${t.nombre_titulo}</h6>
                        <small class="text-muted">${t.pais || ''}</small>
                    </div>
                </div>
            </td>
            <td><span class="badge bg-primary">${t.tipo_titulo}</span></td>
            <td>${t.universidad}</td>
            <td>${formatFecha(t.fecha_obtencion)}</td>
            <td>${t.archivo_certificado ? '<span class="badge bg-success">Con archivo</span>' : '<span class="badge bg-secondary">Sin archivo</span>'}</td>
            <td>
                <div class="btn-group" role="group">
                    <button class="btn btn-sm btn-outline-primary" onclick="verTitulo(${t.id})" title="Ver"><i class="bi bi-eye"></i></button>
                    <button class="btn btn-sm btn-outline-warning" onclick="editarTitulo(${t.id})" title="Editar"><i class="bi bi-pencil"></i></button>
                    <button class="btn btn-sm btn-outline-info" onclick="descargarTitulo(${t.id})" title="Descargar"><i class="bi bi-download"></i></button>
                    <button class="btn btn-sm btn-outline-danger" onclick="eliminarTitulo(${t.id})" title="Eliminar"><i class="bi bi-trash"></i></button>
                </div>
            </td>`;
        tbody.appendChild(tr);
    });
}

// ------- FILTROS EN TIEMPO REAL -------
function aplicarFiltros() {
    const tipo   = document.getElementById('filtroNivel').value;
    const pais   = (document.getElementById('filtroPais').value || '').toLowerCase();
    const buscar = (document.getElementById('buscarTitulo').value || '').toLowerCase();
    renderizarTabla(titulosData.filter(t =>
        (!tipo   || t.tipo_titulo === tipo) &&
        (!pais   || (t.pais || '').toLowerCase().includes(pais)) &&
        (!buscar || t.nombre_titulo.toLowerCase().includes(buscar) || t.universidad.toLowerCase().includes(buscar))
    ));
}

function limpiarFiltros() {
    ['filtroNivel','filtroPais','buscarTitulo'].forEach(id => document.getElementById(id).value = '');
    renderizarTabla(titulosData);
}

// ------- VER DETALLE -------
function verTitulo(id) {
    const t = titulosData.find(x => x.id == id);
    if (!t) return;
    document.getElementById('detallesTitulo').innerHTML = `
        <div class="row">
            <div class="col-md-6 mb-3"><strong><i class="bi bi-mortarboard me-1 text-primary"></i>Título</strong><p class="mt-1">${t.nombre_titulo}</p></div>
            <div class="col-md-6 mb-3"><strong><i class="bi bi-tag me-1 text-info"></i>Tipo</strong><p class="mt-1"><span class="badge bg-primary">${t.tipo_titulo}</span></p></div>
            <div class="col-md-6 mb-3"><strong><i class="bi bi-building me-1 text-success"></i>Institución</strong><p class="mt-1">${t.universidad}</p></div>
            <div class="col-md-6 mb-3"><strong><i class="bi bi-globe me-1 text-warning"></i>País</strong><p class="mt-1">${t.pais || '-'}</p></div>
            <div class="col-md-6 mb-3"><strong><i class="bi bi-calendar-check me-1 text-success"></i>Fecha Obtención</strong><p class="mt-1">${formatFecha(t.fecha_obtencion)}</p></div>
            <div class="col-md-6 mb-3"><strong><i class="bi bi-paperclip me-1"></i>Archivo</strong><p class="mt-1">${t.archivo_certificado || 'No adjunto'}</p></div>
        </div>`;
    new bootstrap.Modal(document.getElementById('verTituloModal')).show();
}

// ------- EDITAR -------
function editarTitulo(id) {
    const t = titulosData.find(x => x.id == id);
    if (!t) return;
    document.getElementById('edit_id').value     = t.id;
    document.getElementById('edit_nombre').value = t.nombre_titulo;
    document.getElementById('edit_tipo').value   = t.tipo_titulo;
    document.getElementById('edit_univ').value   = t.universidad;
    document.getElementById('edit_pais').value   = t.pais || '';
    document.getElementById('edit_fecha').value  = t.fecha_obtencion;
    new bootstrap.Modal(document.getElementById('editarTituloModal')).show();
}

function guardarEdicion() {
    const fd = new FormData();
    fd.append('id',              document.getElementById('edit_id').value);
    fd.append('nombre_titulo',   document.getElementById('edit_nombre').value.trim());
    fd.append('tipo_titulo',     document.getElementById('edit_tipo').value);
    fd.append('universidad',     document.getElementById('edit_univ').value.trim());
    fd.append('pais',            document.getElementById('edit_pais').value.trim());
    fd.append('fecha_obtencion', document.getElementById('edit_fecha').value);

    fetch('<?= base_url('index.php/empleado/titulos-academicos/actualizar') ?>', { method: 'POST', body: fd })
        .then(r => r.json())
        .then(data => {
            bootstrap.Modal.getInstance(document.getElementById('editarTituloModal')).hide();
            if (data.success) {
                Swal.fire({ icon:'success', title:'Actualizado', text: data.message, timer:1800, showConfirmButton:false }).then(() => cargarTitulos());
            } else {
                Swal.fire({ icon:'error', title:'Error', text: data.message });
            }
        })
        .catch(() => Swal.fire({ icon:'error', title:'Error', text:'Error de conexión' }));
}

// ------- NUEVO TÍTULO -------
function guardarNuevoTitulo() {
    const fd = new FormData();
    fd.append('nombre_titulo',   document.getElementById('new_nombre').value.trim());
    fd.append('tipo_titulo',     document.getElementById('new_tipo').value);
    fd.append('universidad',     document.getElementById('new_univ').value.trim());
    fd.append('pais',            document.getElementById('new_pais').value.trim());
    fd.append('fecha_obtencion', document.getElementById('new_fecha').value);

    fetch('<?= base_url('index.php/empleado/titulos-academicos/guardar') ?>', { method: 'POST', body: fd })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                bootstrap.Modal.getInstance(document.getElementById('nuevoTituloModal')).hide();
                document.getElementById('formNuevoTitulo').reset();
                Swal.fire({ icon:'success', title:'¡Guardado!', text: data.message, timer:1800, showConfirmButton:false }).then(() => cargarTitulos());
            } else {
                Swal.fire({ icon:'error', title:'Error', text: data.message });
            }
        })
        .catch(() => Swal.fire({ icon:'error', title:'Error', text:'Error de conexión' }));
}

// ------- ELIMINAR -------
function eliminarTitulo(id) {
    Swal.fire({
        title: '¿Eliminar este título?', text: 'Esta acción no se puede deshacer.',
        icon: 'warning', showCancelButton: true,
        confirmButtonColor: '#dc3545', cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sí, eliminar', cancelButtonText: 'Cancelar'
    }).then(result => {
        if (!result.isConfirmed) return;
        const fd = new FormData();
        fd.append('id', id);
        fetch('<?= base_url('index.php/empleado/titulos-academicos/eliminar') ?>', { method: 'POST', body: fd })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({ icon:'success', title:'Eliminado', text: data.message, timer:1500, showConfirmButton:false }).then(() => cargarTitulos());
                } else {
                    Swal.fire({ icon:'error', title:'Error', text: data.message });
                }
            })
            .catch(() => Swal.fire({ icon:'error', title:'Error', text:'Error de conexión' }));
    });
}

// ------- DESCARGA (EN DESARROLLO) -------
function descargarTitulo(id) {
    Swal.fire({ icon:'info', title:'Función en desarrollo', text:'La descarga de documentos estará disponible próximamente.', confirmButtonText:'Entendido' });
}

// ------- AUXILIAR -------
function formatFecha(fecha) {
    if (!fecha) return '-';
    const [y, m, d] = fecha.split('-');
    return d ? `${d}/${m}/${y}` : fecha;
}
</script>
<?= $this->endSection() ?>