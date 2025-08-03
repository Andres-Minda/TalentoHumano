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
                            <h4 class="mb-0">Gestión de Nóminas</h4>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalNomina">
                                <i class="bi bi-plus-circle"></i> Nueva Nómina
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Estadísticas -->
                        <div class="row mb-4">
                            <div class="col-md-3">
                                <div class="card bg-primary text-white">
                                    <div class="card-body">
                                        <h5 class="card-title">Total Nóminas</h5>
                                        <h3 class="mb-0"><?= count($nominas ?? []) ?></h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-success text-white">
                                    <div class="card-body">
                                        <h5 class="card-title">Generadas</h5>
                                        <h3 class="mb-0"><?= count(array_filter($nominas ?? [], function($n) { return $n['estado'] == 'Generada'; })) ?></h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-warning text-white">
                                    <div class="card-body">
                                        <h5 class="card-title">Pagadas</h5>
                                        <h3 class="mb-0"><?= count(array_filter($nominas ?? [], function($n) { return $n['estado'] == 'Pagada'; })) ?></h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-info text-white">
                                    <div class="card-body">
                                        <h5 class="card-title">Total Pagado</h5>
                                        <h3 class="mb-0">$<?= number_format(array_sum(array_column($nominas ?? [], 'total_nomina')), 0, ',', '.') ?></h3>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Filtros -->
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <select class="form-select" id="filtroEstado">
                                    <option value="">Todos los estados</option>
                                    <option value="Generada">Generada</option>
                                    <option value="Pagada">Pagada</option>
                                    <option value="Anulada">Anulada</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select class="form-select" id="filtroPeriodo">
                                    <option value="">Todos los períodos</option>
                                    <?php foreach ($periodos ?? [] as $periodo): ?>
                                    <option value="<?= $periodo ?>"><?= $periodo ?></option>
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

                        <!-- Tabla de Nóminas -->
                        <div class="table-responsive">
                            <table class="table table-striped table-hover" id="tablaNominas">
                                <thead class="table-dark">
                                    <tr>
                                        <th>ID</th>
                                        <th>Período</th>
                                        <th>Fecha Generación</th>
                                        <th>Fecha Pago</th>
                                        <th>Total Empleados</th>
                                        <th>Total Nómina</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($nominas ?? [] as $nomina): ?>
                                    <tr>
                                        <td><?= $nomina['id_nomina'] ?></td>
                                        <td>
                                            <strong><?= $nomina['periodo'] ?></strong>
                                        </td>
                                        <td><?= date('d/m/Y', strtotime($nomina['fecha_generacion'])) ?></td>
                                        <td>
                                            <?php if ($nomina['fecha_pago']): ?>
                                                <?= date('d/m/Y', strtotime($nomina['fecha_pago'])) ?>
                                            <?php else: ?>
                                                <span class="text-muted">Sin fecha de pago</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <span class="badge bg-primary"><?= $nomina['total_empleados'] ?? 0 ?></span>
                                        </td>
                                        <td>
                                            <span class="text-success fw-bold">$<?= number_format($nomina['total_nomina'] ?? 0, 0, ',', '.') ?></span>
                                        </td>
                                        <td>
                                            <?php
                                            $estadoClass = '';
                                            switch($nomina['estado']) {
                                                case 'Generada': $estadoClass = 'bg-info'; break;
                                                case 'Pagada': $estadoClass = 'bg-success'; break;
                                                case 'Anulada': $estadoClass = 'bg-danger'; break;
                                                default: $estadoClass = 'bg-secondary';
                                            }
                                            ?>
                                            <span class="badge <?= $estadoClass ?>"><?= $nomina['estado'] ?></span>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <button type="button" class="btn btn-sm btn-outline-primary" onclick="verNomina(<?= $nomina['id_nomina'] ?>)">
                                                    <i class="bi bi-eye"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline-warning" onclick="editarNomina(<?= $nomina['id_nomina'] ?>)">
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline-info" onclick="verDetalles(<?= $nomina['id_nomina'] ?>)">
                                                    <i class="bi bi-list-ul"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline-success" onclick="generarPDF(<?= $nomina['id_nomina'] ?>)">
                                                    <i class="bi bi-file-pdf"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline-danger" onclick="eliminarNomina(<?= $nomina['id_nomina'] ?>)">
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

<!-- Modal para Crear/Editar Nómina -->
<div class="modal fade" id="modalNomina" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Nueva Nómina</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formNomina">
                <div class="modal-body">
                    <input type="hidden" id="id_nomina" name="id_nomina">
                    
                    <div class="mb-3">
                        <label for="periodo" class="form-label">Período *</label>
                        <input type="text" class="form-control" id="periodo" name="periodo" placeholder="Ej: Enero 2024" required>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="fecha_generacion" class="form-label">Fecha de Generación *</label>
                                <input type="date" class="form-control" id="fecha_generacion" name="fecha_generacion" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="fecha_pago" class="form-label">Fecha de Pago</label>
                                <input type="date" class="form-control" id="fecha_pago" name="fecha_pago">
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="estado" class="form-label">Estado *</label>
                        <select class="form-select" id="estado" name="estado" required>
                            <option value="Generada">Generada</option>
                            <option value="Pagada">Pagada</option>
                            <option value="Anulada">Anulada</option>
                        </select>
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

<!-- Modal para Ver Nómina -->
<div class="modal fade" id="modalVerNomina" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detalles de la Nómina</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="detallesNomina">
                <!-- Se llenará dinámicamente -->
            </div>
        </div>
    </div>
</div>

<!-- Modal para Ver Detalles -->
<div class="modal fade" id="modalDetalles" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detalles de la Nómina</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="contenidoDetalles">
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
    $('#tablaNominas').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json'
        },
        order: [[2, 'desc']]
    });

    // Filtros
    $('#filtroEstado, #filtroPeriodo, #filtroFecha').on('change', function() {
        aplicarFiltros();
    });

    // Manejar envío del formulario
    $('#formNomina').on('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const isEdit = $('#id_nomina').val() !== '';
        
        $.ajax({
            url: isEdit ? '/admin-th/nominas/actualizar' : '/admin-th/nominas/crear',
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
    const estado = $('#filtroEstado').val();
    const periodo = $('#filtroPeriodo').val();
    const fecha = $('#filtroFecha').val();
    
    $('#tablaNominas').DataTable().columns().every(function() {
        const column = this;
        let searchTerm = '';
        
        if (column.index() === 6 && estado) { // Columna estado
            searchTerm = estado;
        } else if (column.index() === 1 && periodo) { // Columna período
            searchTerm = periodo;
        } else if (column.index() === 2 && fecha) { // Columna fecha generación
            searchTerm = fecha;
        }
        
        column.search(searchTerm).draw();
    });
}

function limpiarFiltros() {
    $('#filtroEstado, #filtroPeriodo').val('');
    $('#filtroFecha').val('');
    $('#tablaNominas').DataTable().search('').columns().search('').draw();
}

function verNomina(id) {
    $.ajax({
        url: `/admin-th/nominas/${id}`,
        type: 'GET',
        success: function(response) {
            if (response.success) {
                $('#detallesNomina').html(response.html);
                $('#modalVerNomina').modal('show');
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

function editarNomina(id) {
    $.ajax({
        url: `/admin-th/nominas/${id}/editar`,
        type: 'GET',
        success: function(response) {
            if (response.success) {
                $('#id_nomina').val(response.nomina.id_nomina);
                $('#periodo').val(response.nomina.periodo);
                $('#fecha_generacion').val(response.nomina.fecha_generacion);
                $('#fecha_pago').val(response.nomina.fecha_pago);
                $('#estado').val(response.nomina.estado);
                
                $('#modalTitle').text('Editar Nómina');
                $('#modalNomina').modal('show');
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

function verDetalles(id) {
    $.ajax({
        url: `/admin-th/nominas/${id}/detalles`,
        type: 'GET',
        success: function(response) {
            if (response.success) {
                $('#contenidoDetalles').html(response.html);
                $('#modalDetalles').modal('show');
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

function generarPDF(id) {
    Swal.fire({
        title: 'Generando PDF',
        text: 'Por favor espere...',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
    
    $.ajax({
        url: `/admin-th/nominas/${id}/pdf`,
        type: 'GET',
        success: function(response) {
            if (response.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'PDF Generado',
                    text: 'El archivo se ha descargado correctamente'
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
                text: 'Error al generar el PDF'
            });
        }
    });
}

function eliminarNomina(id) {
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
                url: `/admin-th/nominas/${id}/eliminar`,
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