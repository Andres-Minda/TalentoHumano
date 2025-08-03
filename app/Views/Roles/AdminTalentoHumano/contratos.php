<?php $sidebar = 'sidebar_admin_th'; ?>
<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>

<div class="page-wrapper">
    <div class="page-content">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="mb-0">Gestión de Contratos</h4>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalContrato">
                                <i class="bi bi-plus-circle"></i> Nuevo Contrato
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Estadísticas -->
                        <div class="row mb-4">
                            <div class="col-md-3">
                                <div class="card bg-primary text-white">
                                    <div class="card-body">
                                        <h5 class="card-title">Total Contratos</h5>
                                        <h3 class="mb-0"><?= count($contratos ?? []) ?></h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-success text-white">
                                    <div class="card-body">
                                        <h5 class="card-title">Indefinidos</h5>
                                        <h3 class="mb-0"><?= count(array_filter($contratos ?? [], function($c) { return $c['tipo_contrato'] == 'Indefinido'; })) ?></h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-warning text-white">
                                    <div class="card-body">
                                        <h5 class="card-title">Término Fijo</h5>
                                        <h3 class="mb-0"><?= count(array_filter($contratos ?? [], function($c) { return $c['tipo_contrato'] == 'Término fijo'; })) ?></h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-info text-white">
                                    <div class="card-body">
                                        <h5 class="card-title">Salario Promedio</h5>
                                        <h3 class="mb-0">$<?= number_format(array_sum(array_column($contratos ?? [], 'salario')) / max(count($contratos ?? []), 1), 0, ',', '.') ?></h3>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Filtros -->
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <select class="form-select" id="filtroTipo">
                                    <option value="">Todos los tipos</option>
                                    <option value="Indefinido">Indefinido</option>
                                    <option value="Término fijo">Término fijo</option>
                                    <option value="Obra o labor">Obra o labor</option>
                                    <option value="Aprendizaje">Aprendizaje</option>
                                    <option value="Ocasional">Ocasional</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select class="form-select" id="filtroEmpleado">
                                    <option value="">Todos los empleados</option>
                                    <?php foreach ($empleados ?? [] as $empleado): ?>
                                    <option value="<?= $empleado['nombres'] . ' ' . $empleado['apellidos'] ?>"><?= $empleado['nombres'] . ' ' . $empleado['apellidos'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <input type="date" class="form-control" id="filtroFecha" placeholder="Filtrar por fecha">
                            </div>
                            <div class="col-md-3">
                                <button type="button" class="btn btn-secondary" onclick="limpiarFiltros()">
                                    <i class="bi bi-x-circle"></i> Limpiar Filtros
                                </button>
                            </div>
                        </div>

                        <!-- Tabla de Contratos -->
                        <div class="table-responsive">
                            <table class="table table-striped table-hover" id="tablaContratos">
                                <thead class="table-dark">
                                    <tr>
                                        <th>ID</th>
                                        <th>Empleado</th>
                                        <th>Puesto</th>
                                        <th>Tipo Contrato</th>
                                        <th>Salario</th>
                                        <th>Fecha Inicio</th>
                                        <th>Fecha Fin</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($contratos ?? [] as $contrato): ?>
                                    <tr>
                                        <td><?= $contrato['id_contrato'] ?></td>
                                        <td>
                                            <strong><?= $contrato['empleado_nombres'] . ' ' . $contrato['empleado_apellidos'] ?></strong>
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary"><?= $contrato['puesto_nombre'] ?? 'Sin asignar' ?></span>
                                        </td>
                                        <td>
                                            <?php
                                            $tipoClass = '';
                                            switch($contrato['tipo_contrato']) {
                                                case 'Indefinido': $tipoClass = 'bg-success'; break;
                                                case 'Término fijo': $tipoClass = 'bg-warning'; break;
                                                case 'Obra o labor': $tipoClass = 'bg-info'; break;
                                                case 'Aprendizaje': $tipoClass = 'bg-primary'; break;
                                                case 'Ocasional': $tipoClass = 'bg-secondary'; break;
                                                default: $tipoClass = 'bg-secondary';
                                            }
                                            ?>
                                            <span class="badge <?= $tipoClass ?>"><?= $contrato['tipo_contrato'] ?></span>
                                        </td>
                                        <td>
                                            <span class="text-success fw-bold">$<?= number_format($contrato['salario'], 0, ',', '.') ?></span>
                                        </td>
                                        <td><?= date('d/m/Y', strtotime($contrato['fecha_inicio'])) ?></td>
                                        <td>
                                            <?php if ($contrato['fecha_fin']): ?>
                                                <?= date('d/m/Y', strtotime($contrato['fecha_fin'])) ?>
                                            <?php else: ?>
                                                <span class="text-muted">Sin fecha de fin</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <button type="button" class="btn btn-sm btn-outline-primary" onclick="verContrato(<?= $contrato['id_contrato'] ?>)">
                                                    <i class="bi bi-eye"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline-warning" onclick="editarContrato(<?= $contrato['id_contrato'] ?>)">
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline-info" onclick="verArchivo(<?= $contrato['id_contrato'] ?>)">
                                                    <i class="bi bi-file-earmark"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline-danger" onclick="eliminarContrato(<?= $contrato['id_contrato'] ?>)">
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

<!-- Modal para Crear/Editar Contrato -->
<div class="modal fade" id="modalContrato" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Nuevo Contrato</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formContrato">
                <div class="modal-body">
                    <input type="hidden" id="id_contrato" name="id_contrato">
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="id_empleado" class="form-label">Empleado *</label>
                                <select class="form-select" id="id_empleado" name="id_empleado" required>
                                    <option value="">Seleccionar empleado...</option>
                                    <?php foreach ($empleados ?? [] as $empleado): ?>
                                    <option value="<?= $empleado['id_empleado'] ?>"><?= $empleado['nombres'] . ' ' . $empleado['apellidos'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="id_puesto" class="form-label">Puesto *</label>
                                <select class="form-select" id="id_puesto" name="id_puesto" required>
                                    <option value="">Seleccionar puesto...</option>
                                    <!-- Se llenará dinámicamente -->
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="tipo_contrato" class="form-label">Tipo de Contrato *</label>
                                <select class="form-select" id="tipo_contrato" name="tipo_contrato" required>
                                    <option value="Indefinido">Indefinido</option>
                                    <option value="Término fijo">Término fijo</option>
                                    <option value="Obra o labor">Obra o labor</option>
                                    <option value="Aprendizaje">Aprendizaje</option>
                                    <option value="Ocasional">Ocasional</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="salario" class="form-label">Salario *</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" class="form-control" id="salario" name="salario" min="0" step="0.01" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="fecha_inicio" class="form-label">Fecha de Inicio *</label>
                                <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="fecha_fin" class="form-label">Fecha de Fin</label>
                                <input type="date" class="form-control" id="fecha_fin" name="fecha_fin">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="horas_semanales" class="form-label">Horas Semanales</label>
                                <input type="number" class="form-control" id="horas_semanales" name="horas_semanales" min="0" max="168">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="archivo_url" class="form-label">Archivo del Contrato</label>
                                <input type="file" class="form-control" id="archivo_url" name="archivo_url" accept=".pdf,.doc,.docx">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para Ver Contrato -->
<div class="modal fade" id="modalVerContrato" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detalles del Contrato</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="detallesContrato">
                <!-- Se llenará dinámicamente -->
            </div>
        </div>
    </div>
</div>

<!-- Modal para Ver Archivo -->
<div class="modal fade" id="modalArchivo" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Archivo del Contrato</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="contenidoArchivo">
                <!-- Se llenará dinámicamente -->
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(document).ready(function() {
    // Inicializar DataTable
    $('#tablaContratos').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json'
        },
        order: [[5, 'desc']]
    });

    // Filtros
    $('#filtroTipo, #filtroEmpleado, #filtroFecha').on('change', function() {
        aplicarFiltros();
    });

    // Manejar envío del formulario
    $('#formContrato').on('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const isEdit = $('#id_contrato').val() !== '';
        
        $.ajax({
            url: isEdit ? '/admin-th/contratos/actualizar' : '/admin-th/contratos/crear',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Éxito',
                        text: response.message
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message
                    });
                }
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error al procesar la solicitud'
                });
            }
        });
    });
});

function aplicarFiltros() {
    const tipo = $('#filtroTipo').val();
    const empleado = $('#filtroEmpleado').val();
    const fecha = $('#filtroFecha').val();
    
    $('#tablaContratos').DataTable().columns().every(function() {
        const column = this;
        let searchTerm = '';
        
        if (column.index() === 3 && tipo) { // Columna tipo contrato
            searchTerm = tipo;
        } else if (column.index() === 1 && empleado) { // Columna empleado
            searchTerm = empleado;
        } else if (column.index() === 5 && fecha) { // Columna fecha inicio
            searchTerm = fecha;
        }
        
        column.search(searchTerm).draw();
    });
}

function limpiarFiltros() {
    $('#filtroTipo, #filtroEmpleado').val('');
    $('#filtroFecha').val('');
    $('#tablaContratos').DataTable().search('').columns().search('').draw();
}

function verContrato(id) {
    $.ajax({
        url: `/admin-th/contratos/${id}`,
        type: 'GET',
        success: function(response) {
            if (response.success) {
                $('#detallesContrato').html(response.html);
                $('#modalVerContrato').modal('show');
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: response.message
                });
            }
        }
    });
}

function editarContrato(id) {
    $.ajax({
        url: `/admin-th/contratos/${id}/editar`,
        type: 'GET',
        success: function(response) {
            if (response.success) {
                $('#id_contrato').val(response.contrato.id_contrato);
                $('#id_empleado').val(response.contrato.id_empleado);
                $('#id_puesto').val(response.contrato.id_puesto);
                $('#tipo_contrato').val(response.contrato.tipo_contrato);
                $('#salario').val(response.contrato.salario);
                $('#fecha_inicio').val(response.contrato.fecha_inicio);
                $('#fecha_fin').val(response.contrato.fecha_fin);
                $('#horas_semanales').val(response.contrato.horas_semanales);
                
                $('#modalTitle').text('Editar Contrato');
                $('#modalContrato').modal('show');
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: response.message
                });
            }
        }
    });
}

function verArchivo(id) {
    $.ajax({
        url: `/admin-th/contratos/${id}/archivo`,
        type: 'GET',
        success: function(response) {
            if (response.success) {
                $('#contenidoArchivo').html(response.html);
                $('#modalArchivo').modal('show');
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: response.message
                });
            }
        }
    });
}

function eliminarContrato(id) {
    Swal.fire({
        title: '¿Estás seguro?',
        text: "Esta acción no se puede deshacer",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: `/admin-th/contratos/${id}/eliminar`,
                type: 'POST',
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Eliminado',
                            text: response.message
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: response.message
                        });
                    }
                }
            });
        }
    });
}
</script>
<?= $this->endSection() ?> 