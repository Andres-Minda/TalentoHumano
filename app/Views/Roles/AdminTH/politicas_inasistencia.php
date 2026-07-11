<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>
<div class="page-wrapper">
    <div class="page-content">
        <!-- Breadcrumb -->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Políticas de Inasistencia</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="<?= site_url('admin-th/dashboard') ?>">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Políticas de Inasistencia</li>
                    </ol>
                </nav>
            </div>
        </div>

        <!-- Page Header -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title mb-0">Gestión de Políticas de Inasistencia</h4>
                        <div class="card-actions d-flex align-items-center">
                            <button type="button" class="btn btn-danger me-2 d-none" id="btnEliminarSeleccion" onclick="eliminarSeleccionados()">
                                <i class="ti ti-trash"></i> Eliminar ( <span id="contadorSeleccion">0</span> )
                            </button>
                            <button type="button" class="btn btn-primary" onclick="nuevaPolitica()">
                                <i class="ti ti-plus"></i> Nueva Política
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Buscador Interno -->
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <div class="input-group">
                                    <span class="input-group-text"><i class="ti ti-search"></i></span>
                                    <input type="text" class="form-control" id="filtroPolitica" placeholder="Buscar por Nombre, Tipo, Estado..." oninput="filtrarPoliticas()">
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped" id="tablaPoliticas">
                                <thead>
                                    <tr>
                                        <th style="width:40px;">
                                            <input type="checkbox" class="form-check-input" id="checkAll" onchange="toggleAll(this)">
                                        </th>
                                        <th>ID</th>
                                        <th>Nombre de la Política</th>
                                        <th>Límite Mensual</th>
                                        <th>Límite Trimestral</th>
                                        <th>Límite Anual</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody id="tbodyPoliticas">
                                    <?php if (!empty($politicas)): ?>
                                        <?php foreach ($politicas as $politica): ?>
                                            <tr>
                                                <td>
                                                    <input type="checkbox" class="form-check-input chk-item" value="<?= $politica['id_politica'] ?>" onchange="actualizarBotonEliminar()">
                                                </td>
                                                <td><?= $politica['id_politica'] ?></td>
                                                <td><strong><?= esc($politica['nombre_politica']) ?></strong></td>
                                                <td><span class="badge bg-warning"><?= $politica['max_inasistencias_mes'] ?> días</span></td>
                                                <td><span class="badge bg-warning"><?= isset($politica['max_inasistencias_trimestre']) ? $politica['max_inasistencias_trimestre'] : '-' ?> días</span></td>
                                                <td><span class="badge bg-warning"><?= $politica['max_inasistencias_anio'] ?> días</span></td>
                                                <td><span class="badge <?= $politica['activo'] ? 'bg-success' : 'bg-danger' ?>"><?= $politica['activo'] ? 'Activa' : 'Inactiva' ?></span></td>
                                                <td>
                                                    <button class="btn btn-sm btn-outline-info" data-id="<?= $politica['id_politica'] ?>" onclick="verPolitica(<?= $politica['id_politica'] ?>)" title="Ver">
                                                        <i class="ti ti-eye"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-outline-primary" data-id="<?= $politica['id_politica'] ?>" onclick="editarPolitica(<?= $politica['id_politica'] ?>)" title="Editar">
                                                        <i class="ti ti-edit"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-outline-danger" data-id="<?= $politica['id_politica'] ?>" onclick="eliminarPolitica(<?= $politica['id_politica'] ?>)" title="Borrar">
                                                        <i class="ti ti-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr><td colspan="7" class="text-center">No hay políticas registradas.</td></tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para nueva/editar política -->
<div class="modal fade" id="modalPolitica" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Nueva Política de Inasistencia</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formPolitica">
                    <input type="hidden" id="politica_id" name="politica_id">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="nombre_politica" class="form-label">Nombre de la Política *</label>
                                <input type="text" class="form-control" id="nombre_politica" name="nombre_politica" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="limite_mensual" class="form-label">Límite Mensual *</label>
                                <input type="number" class="form-control" id="limite_mensual" name="limite_mensual" min="0" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="limite_trimestral" class="form-label">Límite Trimestral *</label>
                                <input type="number" class="form-control" id="limite_trimestral" name="limite_trimestral" min="0" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="limite_anual" class="form-label">Límite Anual *</label>
                                <input type="number" class="form-control" id="limite_anual" name="limite_anual" min="0" required>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripción de la Política</label>
                        <textarea class="form-control" id="descripcion" name="descripcion" rows="3"></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="acciones_por_exceso" class="form-label">Acciones por Exceso</label>
                                <textarea class="form-control" id="acciones_por_exceso" name="acciones_por_exceso" rows="3" placeholder="Describir acciones a tomar cuando se exceda el límite"></textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="estado" class="form-label">Estado</label>
                                <select class="form-select" id="estado" name="estado">
                                    <option value="1">Activa</option>
                                    <option value="0">Inactiva</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" id="btnCancelarModal" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="btnGuardarModal" onclick="guardarPolitica()">Guardar</button>
            </div>
        </div>
    </div>
</div>

<script>
function nuevaPolitica() {
    document.getElementById('modalTitle').textContent = 'Nueva Política de Inasistencia';
    document.getElementById('formPolitica').reset();
    document.getElementById('politica_id').value = '';
    
    // Habilitar campos por si estaban en modo vista
    document.querySelectorAll('#formPolitica input, #formPolitica textarea, #formPolitica select').forEach(el => el.disabled = false);
    document.getElementById('btnGuardarModal').classList.remove('d-none');
    
    const modal = new bootstrap.Modal(document.getElementById('modalPolitica'));
    modal.show();
}

function verPolitica(id) {
    fetch(`<?= site_url('admin-th/politicas-inasistencia/ver/') ?>${id}`, {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(r => r.json())
    .then(data => {
        if (!data.success) {
            Swal.fire('Error', data.message, 'error');
            return;
        }
        const pol = data.data;
        document.getElementById('modalTitle').textContent = 'Ver Política de Inasistencia';
        document.getElementById('politica_id').value = pol.id_politica;
        document.getElementById('nombre_politica').value = pol.nombre_politica;
        document.getElementById('limite_mensual').value = pol.max_inasistencias_mes;
        document.getElementById('limite_trimestral').value = pol.max_inasistencias_trimestre;
        document.getElementById('limite_anual').value = pol.max_inasistencias_anio;
        document.getElementById('estado').value = pol.activo;
        
        // Separar descripcion y acciones si estaban combinadas
        let desc = pol.descripcion || '';
        let acc = '';
        if (desc.includes('\nAcciones por exceso: ')) {
            let partes = desc.split('\nAcciones por exceso: ');
            desc = partes[0];
            acc = partes[1];
        }
        document.getElementById('descripcion').value = desc;
        document.getElementById('acciones_por_exceso').value = acc;
        
        // Deshabilitar campos
        document.querySelectorAll('#formPolitica input, #formPolitica textarea, #formPolitica select').forEach(el => el.disabled = true);
        document.getElementById('btnGuardarModal').classList.add('d-none');
        
        new bootstrap.Modal(document.getElementById('modalPolitica')).show();
    })
    .catch(err => {
        console.error(err);
        Swal.fire('Error', 'Fallo de red', 'error');
    });
}

function editarPolitica(id) {
    fetch(`<?= site_url('admin-th/politicas-inasistencia/editar/') ?>${id}`, {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(r => r.json())
    .then(data => {
        if (!data.success) {
            Swal.fire('Error', data.message, 'error');
            return;
        }
        const pol = data.data;
        document.getElementById('modalTitle').textContent = 'Editar Política de Inasistencia';
        document.getElementById('politica_id').value = pol.id_politica;
        document.getElementById('nombre_politica').value = pol.nombre_politica;
        document.getElementById('limite_mensual').value = pol.max_inasistencias_mes;
        document.getElementById('limite_trimestral').value = pol.max_inasistencias_trimestre;
        document.getElementById('limite_anual').value = pol.max_inasistencias_anio;
        document.getElementById('estado').value = pol.activo;
        
        let desc = pol.descripcion || '';
        let acc = '';
        if (desc.includes('\nAcciones por exceso: ')) {
            let partes = desc.split('\nAcciones por exceso: ');
            desc = partes[0];
            acc = partes[1];
        }
        document.getElementById('descripcion').value = desc;
        document.getElementById('acciones_por_exceso').value = acc;
        
        // Habilitar campos
        document.querySelectorAll('#formPolitica input, #formPolitica textarea, #formPolitica select').forEach(el => el.disabled = false);
        document.getElementById('btnGuardarModal').classList.remove('d-none');
        
        new bootstrap.Modal(document.getElementById('modalPolitica')).show();
    })
    .catch(err => {
        console.error(err);
        Swal.fire('Error', 'Fallo de red', 'error');
    });
}

function guardarPolitica() {
    // Validar HTML5 nativo
    const frm = document.getElementById('formPolitica');
    if (!frm.checkValidity()) {
        frm.reportValidity();
        return;
    }

    const btnGuardar = document.getElementById('btnGuardarModal');
    btnGuardar.innerHTML = '<i class="ti ti-loader ti-spin"></i> Guardando...';
    btnGuardar.disabled = true;

    const id = document.getElementById('politica_id').value;
    const url = id ? `<?= site_url('admin-th/politicas-inasistencia/actualizar/') ?>${id}` : `<?= site_url('admin-th/politicas-inasistencia/guardar') ?>`;
    
    const formData = new FormData(frm);

    fetch(url, {
        method: 'POST',
        body: formData,
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(r => r.json())
    .then(data => {
        btnGuardar.innerHTML = 'Guardar';
        btnGuardar.disabled = false;
        if (data.success) {
            Swal.fire({icon: 'success', title: '¡Éxito!', text: data.message, timer: 2000, showConfirmButton: false})
            .then(() => window.location.reload());
        } else {
            Swal.fire('Error', data.message, 'error');
        }
    })
    .catch(err => {
        console.error(err);
        btnGuardar.innerHTML = 'Guardar';
        btnGuardar.disabled = false;
        Swal.fire('Error', 'Fallo de red', 'error');
    });
}

function eliminarPolitica(id) {
    Swal.fire({
        title: '¿Estás seguro?',
        text: '¿Deseas eliminar permanentemente esta política?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: '<i class="ti ti-trash"></i> Sí, eliminar'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`<?= site_url('admin-th/politicas-inasistencia/eliminar/') ?>${id}`, {
                method: 'DELETE',
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(r => r.json())
            .then(data => {
                if(data.success) {
                    Swal.fire({icon: 'success', title: 'Eliminado', text: data.message, timer: 2000, showConfirmButton: false})
                    .then(() => window.location.reload());
                } else {
                    Swal.fire('Error', data.message, 'error');
                }
            })
            .catch(err => Swal.fire('Error', 'Fallo de conexión', 'error'));
        }
    });
}

// ==================== [ESTANDARIZACIÓN] LÓGICA DE BÚSQUEDA Y BORRADO MASIVO ====================
// 1. Buscador Interno del Frontend
function filtrarPoliticas() {
    const filtro = document.getElementById('filtroPolitica').value.toLowerCase().trim();
    const filas = document.querySelectorAll('#tbodyPoliticas tr');

    filas.forEach(fila => {
        const celdas = fila.querySelectorAll('td');
        if (celdas.length < 5) return;

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

    if (ids.length === 0) return;

    const msg = '¿Eliminar ' + ids.length + ' política(s)? Esta acción no se puede deshacer.';
    
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

    fetch('<?= site_url('admin-th/politicas-inasistencia/eliminar-masivo') ?>', { 
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
            window.location.reload();
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

