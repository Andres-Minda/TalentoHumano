<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>

<div class="page-wrapper">
    <div class="page-content">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="mb-0"><?= $titulo ?></h4>
                            <a href="<?= base_url('empleados/crear') ?>" class="btn btn-primary">
                                <i class="ti ti-plus"></i> Nuevo Empleado
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <?php if (session()->getFlashdata('mensaje')): ?>
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <?= session()->getFlashdata('mensaje') ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <?php if (session()->getFlashdata('error')): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <?= session()->getFlashdata('error') ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <!-- Filtros -->
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <label for="filtro_tipo" class="form-label">Filtrar por Tipo:</label>
                                <select id="filtro_tipo" class="form-select">
                                    <option value="">Todos los tipos</option>
                                    <option value="DOCENTE">Docente</option>
                                    <option value="ADMINISTRATIVO">Administrativo</option>
                                    <option value="DIRECTIVO">Directivo</option>
                                    <option value="AUXILIAR">Auxiliar</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="filtro_departamento" class="form-label">Filtrar por Departamento:</label>
                                <select id="filtro_departamento" class="form-select">
                                    <option value="">Todos los departamentos</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="buscar" class="form-label">Buscar:</label>
                                <input type="text" id="buscar" class="form-control" placeholder="Nombre, apellido o cédula">
                            </div>
                            <div class="col-md-3 d-flex align-items-end">
                                <button type="button" id="limpiar_filtros" class="btn btn-secondary">
                                    <i class="ti ti-refresh"></i> Limpiar Filtros
                                </button>
                            </div>
                        </div>

                        <!-- Tabla de empleados -->
                        <div class="table-responsive">
                            <table class="table table-striped table-hover" id="tabla_empleados">
                                <thead class="table-dark">
                                    <tr>
                                        <th>ID</th>
                                        <th>Nombres</th>
                                        <th>Apellidos</th>
                                        <th>Cédula</th>
                                        <th>Tipo</th>
                                        <th>Tipo Docente</th>
                                        <th>Departamento</th>
                                        <th>Fecha Ingreso</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($empleados as $empleado): ?>
                                        <tr data-tipo="<?= $empleado['tipo_empleado'] ?>" data-departamento="<?= $empleado['departamento'] ?>">
                                            <td><?= $empleado['id'] ?></td>
                                            <td><?= esc($empleado['nombres']) ?></td>
                                            <td><?= esc($empleado['apellidos']) ?></td>
                                            <td><?= esc($empleado['cedula']) ?></td>
                                            <td>
                                                <span class="badge bg-<?= getBadgeColor($empleado['tipo_empleado']) ?>">
                                                    <?= $empleado['tipo_empleado'] ?>
                                                </span>
                                            </td>
                                            <td>
                                                <?php if ($empleado['tipo_docente']): ?>
                                                    <span class="badge bg-info"><?= $empleado['tipo_docente'] ?></span>
                                                <?php else: ?>
                                                    <span class="text-muted">-</span>
                                                <?php endif; ?>
                                            </td>
                                            <td><?= esc($empleado['departamento']) ?></td>
                                            <td><?= date('d/m/Y', strtotime($empleado['fecha_ingreso'])) ?></td>
                                            <td>
                                                <?php if ($empleado['activo']): ?>
                                                    <span class="badge bg-success">Activo</span>
                                                <?php else: ?>
                                                    <span class="badge bg-danger">Inactivo</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="<?= base_url('empleados/perfil/' . $empleado['id']) ?>" 
                                                       class="btn btn-sm btn-info" title="Ver Perfil">
                                                        <i class="ti ti-eye"></i>
                                                    </a>
                                                    <a href="<?= base_url('empleados/editar/' . $empleado['id']) ?>" 
                                                       class="btn btn-sm btn-warning" title="Editar">
                                                        <i class="ti ti-edit"></i>
                                                    </a>
                                                    <?php if ($empleado['activo']): ?>
                                                        <button type="button" class="btn btn-sm btn-danger" 
                                                                onclick="confirmarEliminacion(<?= $empleado['id'] ?>)" title="Eliminar">
                                                            <i class="ti ti-trash"></i>
                                                        </button>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- Estadísticas -->
                        <div class="row mt-4">
                            <div class="col-md-3">
                                <div class="card bg-primary text-white">
                                    <div class="card-body text-center">
                                        <h5 class="card-title">Total Empleados</h5>
                                        <h3 class="mb-0" id="total_empleados"><?= count($empleados) ?></h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-success text-white">
                                    <div class="card-body text-center">
                                        <h5 class="card-title">Docentes</h5>
                                        <h3 class="mb-0" id="total_docentes">0</h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-info text-white">
                                    <div class="card-body text-center">
                                        <h5 class="card-title">Administrativos</h5>
                                        <h3 class="mb-0" id="total_administrativos">0</h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-warning text-white">
                                    <div class="card-body text-center">
                                        <h5 class="card-title">Directivos/Auxiliares</h5>
                                        <h3 class="mb-0" id="total_otros">0</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de confirmación para eliminar -->
<div class="modal fade" id="modalConfirmacion" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmar Eliminación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>¿Está seguro de que desea eliminar este empleado?</p>
                <p class="text-muted">El empleado será marcado como inactivo.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <a href="#" id="btn_confirmar_eliminar" class="btn btn-danger">Eliminar</a>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Inicializar estadísticas
    actualizarEstadisticas();
    
    // Event listeners para filtros
    document.getElementById('filtro_tipo').addEventListener('change', filtrarEmpleados);
    document.getElementById('filtro_departamento').addEventListener('change', filtrarEmpleados);
    document.getElementById('buscar').addEventListener('input', filtrarEmpleados);
    document.getElementById('limpiar_filtros').addEventListener('click', limpiarFiltros);
    
    // Cargar departamentos según tipo seleccionado
    document.getElementById('filtro_tipo').addEventListener('change', function() {
        cargarDepartamentos(this.value);
    });
});

function filtrarEmpleados() {
    const tipo = document.getElementById('filtro_tipo').value;
    const departamento = document.getElementById('filtro_departamento').value;
    const busqueda = document.getElementById('buscar').value.toLowerCase();
    
    const filas = document.querySelectorAll('#tabla_empleados tbody tr');
    
    filas.forEach(fila => {
        let mostrar = true;
        
        // Filtro por tipo
        if (tipo && fila.dataset.tipo !== tipo) {
            mostrar = false;
        }
        
        // Filtro por departamento
        if (departamento && fila.dataset.departamento !== departamento) {
            mostrar = false;
        }
        
        // Filtro por búsqueda
        if (busqueda) {
            const texto = fila.textContent.toLowerCase();
            if (!texto.includes(busqueda)) {
                mostrar = false;
            }
        }
        
        fila.style.display = mostrar ? '' : 'none';
    });
    
    actualizarEstadisticas();
}

function limpiarFiltros() {
    document.getElementById('filtro_tipo').value = '';
    document.getElementById('filtro_departamento').value = '';
    document.getElementById('buscar').value = '';
    
    // Mostrar todas las filas
    const filas = document.querySelectorAll('#tabla_empleados tbody tr');
    filas.forEach(fila => fila.style.display = '');
    
    actualizarEstadisticas();
}

function cargarDepartamentos(tipo) {
    const selectDepartamento = document.getElementById('filtro_departamento');
    selectDepartamento.innerHTML = '<option value="">Todos los departamentos</option>';
    
    if (tipo === 'DOCENTE') {
        selectDepartamento.innerHTML += '<option value="Departamento General">Departamento General</option>';
    } else if (tipo === 'ADMINISTRATIVO') {
        const departamentos = [
            'Recursos Humanos', 'Contabilidad', 'Tecnología', 
            'Académico', 'Administrativo', 'Vinculación'
        ];
        departamentos.forEach(dept => {
            selectDepartamento.innerHTML += `<option value="${dept}">${dept}</option>`;
        });
    } else if (tipo === 'DIRECTIVO' || tipo === 'AUXILIAR') {
        selectDepartamento.innerHTML += '<option value="Departamento ITSI">Departamento ITSI</option>';
    }
}

function actualizarEstadisticas() {
    const filasVisibles = document.querySelectorAll('#tabla_empleados tbody tr:not([style*="display: none"])');
    
    let totalDocentes = 0;
    let totalAdministrativos = 0;
    let totalOtros = 0;
    
    filasVisibles.forEach(fila => {
        const tipo = fila.dataset.tipo;
        if (tipo === 'DOCENTE') {
            totalDocentes++;
        } else if (tipo === 'ADMINISTRATIVO') {
            totalAdministrativos++;
        } else {
            totalOtros++;
        }
    });
    
    document.getElementById('total_empleados').textContent = filasVisibles.length;
    document.getElementById('total_docentes').textContent = totalDocentes;
    document.getElementById('total_administrativos').textContent = totalAdministrativos;
    document.getElementById('total_otros').textContent = totalOtros;
}

function confirmarEliminacion(id) {
    document.getElementById('btn_confirmar_eliminar').href = `<?= base_url('empleados/eliminar/') ?>/${id}`;
    new bootstrap.Modal(document.getElementById('modalConfirmacion')).show();
}
</script>
<?= $this->endSection() ?>

<?php
function getBadgeColor($tipo) {
    switch ($tipo) {
        case 'DOCENTE':
            return 'success';
        case 'ADMINISTRATIVO':
            return 'info';
        case 'DIRECTIVO':
            return 'warning';
        case 'AUXILIAR':
            return 'secondary';
        default:
            return 'primary';
    }
}
?>
