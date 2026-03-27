<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>
<div class="page-wrapper">
    <div class="page-content">
        <!-- Breadcrumb -->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Títulos Académicos</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="<?= site_url('admin-th/dashboard') ?>">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Gestión de Títulos Académicos</li>
                    </ol>
                </nav>
            </div>
        </div>

        <!-- Page Header -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title mb-0">Gestión de Títulos Académicos</h4>
                        <div class="card-actions d-flex align-items-center">
                            <button type="button" class="btn btn-danger me-2 d-none" id="btnEliminarSeleccion" onclick="eliminarSeleccionados()">
                                <i class="ti ti-trash"></i> Eliminar Seleccionados (<span id="contadorSeleccion">0</span>)
                            </button>
                            <button type="button" class="btn btn-primary" onclick="nuevoTitulo()">
                                <i class="ti ti-plus"></i> Nuevo Título
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Buscador Interno -->
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <div class="input-group">
                                    <span class="input-group-text"><i class="ti ti-search"></i></span>
                                    <input type="text" class="form-control" id="filtroTitulo" placeholder="Buscar por Empleado, Título, Inst..." oninput="filtrarTitulos()">
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped" id="tablaTitulos">
                                <thead>
                                    <tr>
                                        <!-- Estándar Modular: Checkbox Maestro -->
                                        <th style="width:40px;">
                                            <input type="checkbox" class="form-check-input" id="checkAll" onchange="toggleAll(this)">
                                        </th>
                                        <th>ID</th>
                                        <th>Empleado</th>
                                        <th>Título</th>
                                        <th>Institución</th>
                                        <th>Año</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody id="tbodyTitulos">
                                    <!-- Los datos se cargarán dinámicamente -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para nuevo/editar título -->
<div class="modal fade" id="modalTitulo" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Nuevo Título Académico</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formTitulo">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="empleado_id" class="form-label">Empleado *</label>
                                <select class="form-select" id="empleado_id" name="empleado_id" required>
                                    <option value="">Seleccionar empleado...</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="titulo" class="form-label">Título *</label>
                                <input type="text" class="form-control" id="titulo" name="titulo" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="institucion" class="form-label">Institución *</label>
                                <input type="text" class="form-control" id="institucion" name="institucion" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="anio" class="form-label">Año *</label>
                                <input type="number" class="form-control" id="anio" name="anio" min="1950" max="2030" required>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripción</label>
                        <textarea class="form-control" id="descripcion" name="descripcion" rows="3"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="guardarTitulo()">Guardar</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    cargarTitulos();
});

function cargarTitulos() {
    // Simular carga de títulos académicos
    const titulos = [
        { id: 1, empleado: 'Juan Pérez', titulo: 'Ingeniero en Sistemas', institucion: 'Universidad Técnica', anio: 2020, estado: 'Verificado' },
        { id: 2, empleado: 'María García', titulo: 'Licenciada en Administración', institucion: 'Universidad Nacional', anio: 2019, estado: 'Verificado' },
        { id: 3, empleado: 'Carlos López', titulo: 'Magíster en Educación', institucion: 'Universidad Católica', anio: 2021, estado: 'Pendiente' }
    ];

    const tbody = document.getElementById('tbodyTitulos');
    tbody.innerHTML = '';

    titulos.forEach(titulo => {
        const row = document.createElement('tr');
        const idCol = titulo.id_titulo || titulo.id;
        row.innerHTML = `
            <td>
                <input type="checkbox" class="form-check-input chk-item" value="${idCol}" onchange="actualizarBotonEliminar()">
            </td>
            <td>${idCol}</td>
            <td>${titulo.empleado}</td>
            <td>${titulo.titulo}</td>
            <td>${titulo.institucion}</td>
            <td>${titulo.anio}</td>
            <td><span class="badge bg-${getEstadoBadgeColor(titulo.estado)}">${titulo.estado}</span></td>
            <td>
                <button class="btn btn-sm btn-outline-primary" onclick="editarTitulo(${titulo.id})">
                    <i class="ti ti-edit"></i>
                </button>
                <button class="btn btn-sm btn-outline-danger" onclick="eliminarTitulo(${titulo.id})">
                    <i class="ti ti-trash"></i>
                </button>
            </td>
        `;
        tbody.appendChild(row);
    });
}

function nuevoTitulo() {
    document.getElementById('modalTitle').textContent = 'Nuevo Título Académico';
    document.getElementById('formTitulo').reset();
    const modal = new bootstrap.Modal(document.getElementById('modalTitulo'));
    modal.show();
}

function editarTitulo(id) {
    document.getElementById('modalTitle').textContent = 'Editar Título Académico';
    // Aquí cargarías los datos del título
    const modal = new bootstrap.Modal(document.getElementById('modalTitulo'));
    modal.show();
}

function guardarTitulo() {
    // Aquí implementarías la lógica para guardar
    alert('Funcionalidad de guardado en desarrollo');
}

function eliminarTitulo(id) {
    if (confirm('¿Estás seguro de que quieres eliminar este título académico?')) {
        // Aquí implementarías la lógica para eliminar
        alert('Funcionalidad de eliminación en desarrollo');
    }
}

function getEstadoBadgeColor(estado) {
    switch(estado) {
        case 'Verificado': return 'success';
        case 'Pendiente': return 'warning';
        case 'Rechazado': return 'danger';
        default: return 'secondary';
    }
}

// ==================== [ESTANDARIZACIÓN] LÓGICA DE BÚSQUEDA Y BORRADO MASIVO ====================
// 1. Buscador Interno del Frontend
function filtrarTitulos() {
    const filtro = document.getElementById('filtroTitulo').value.toLowerCase().trim();
    const filas = document.querySelectorAll('#tbodyTitulos tr');

    filas.forEach(fila => {
        const celdas = fila.querySelectorAll('td');
        if (celdas.length < 7) return;

        let textoFila = '';
        celdas.forEach(celda => textoFila += celda.textContent.toLowerCase() + ' ');

        if (!filtro || textoFila.includes(filtro)) {
            fila.style.display = '';
        } else {
            fila.style.display = 'none';
        }
    });
}

// 2. Controladores de Selección Múltiple (Checkboxes)
function toggleAll(master) {
    const checkboxes = document.querySelectorAll('.chk-item');
    checkboxes.forEach(chk => {
        if (chk.closest('tr').style.display !== 'none') {
            chk.checked = master.checked;
        }
    });
    actualizarBotonEliminar();
}

function actualizarBotonEliminar() {
    const seleccionados = document.querySelectorAll('.chk-item:checked');
    const btn = document.getElementById('btnEliminarSeleccion');
    const contador = document.getElementById('contadorSeleccion');

    if (seleccionados.length > 0) {
        btn.classList.remove('d-none');
        if (contador) contador.textContent = seleccionados.length;
    } else {
        btn.classList.add('d-none');
        if (contador) contador.textContent = '0';
    }

    const todosVisibles = Array.from(document.querySelectorAll('.chk-item')).filter(chk => chk.closest('tr').style.display !== 'none');
    const checkAll = document.getElementById('checkAll');
    
    if (todosVisibles.length > 0) {
        const completados = document.querySelectorAll('.chk-item:checked').length;
        if (checkAll) {
            checkAll.checked = completados === todosVisibles.length;
            checkAll.indeterminate = completados > 0 && completados < todosVisibles.length;
        }
    }
}

// 3. Acción AJAX: Eliminación Masiva
function eliminarSeleccionados() {
    const ids = Array.from(document.querySelectorAll('.chk-item:checked')).map(chk => chk.value);

    if (ids.length === 0) {
        // En caso de que no haya SweetAlert, usamos alert nativo como fallback temporal if needed
        if (typeof Swal !== 'undefined') {
            Swal.fire({icon: 'warning', title: 'Aviso', text: 'Seleccione al menos un título para eliminar.'});
        } else {
            alert('Seleccione al menos un título para eliminar.');
        }
        return;
    }

    const msg = '¿Eliminar ' + ids.length + ' título(s)? Esta acción no se puede deshacer.';
    
    // Uso de Confirm estándar si no hay SweetAlert implementado en esta vista, sino Swal.
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: '¿Confirmar eliminación masiva?',
            text: msg,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="ti ti-trash me-1"></i> Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then(result => {
            if (result.isConfirmed) procesarEliminacionAjax(ids);
        });
    } else {
        if (confirm(msg)) procesarEliminacionAjax(ids);
    }
}

function procesarEliminacionAjax(ids) {
    const btnDelete = document.getElementById('btnEliminarSeleccion');
    const htmlAnterior = btnDelete.innerHTML;
    btnDelete.innerHTML = '<i class="ti ti-loader ti-spin"></i> Procesando...';
    btnDelete.disabled = true;

    const fnData = new FormData();
    ids.forEach(id => fnData.append('ids[]', id));

    fetch('<?= site_url('admin-th/titulos-academicos/eliminar-masivo') ?>', { 
        method: 'POST', 
        body: fnData,
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(r => r.json())
    .then(data => {
        btnDelete.innerHTML = htmlAnterior;
        btnDelete.disabled = false;
        
        if (data.success) {
            if (typeof Swal !== 'undefined') Swal.fire({icon: 'success', title: '¡Éxito!', text: data.message, timer: 3000, showConfirmButton: false});
            else alert(data.message);
            
            document.getElementById('checkAll').checked = false;
            document.getElementById('checkAll').indeterminate = false;
            actualizarBotonEliminar();
            cargarTitulos();
        } else {
            if (typeof Swal !== 'undefined') Swal.fire({icon: 'error', title: 'Error', text: data.message});
            else alert(data.message);
        }
    })
    .catch(error => {
        console.error(error);
        btnDelete.innerHTML = htmlAnterior;
        btnDelete.disabled = false;
        if (typeof Swal !== 'undefined') Swal.fire({icon: 'error', title: 'Error', text: 'Fallo de red al intentar eliminar.'});
        else alert('Error de red al eliminar.');
    });
}
// ==================== FIN [ESTANDARIZACIÓN] ====================
</script>
<?= $this->endSection() ?>
