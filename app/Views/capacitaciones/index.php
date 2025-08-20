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
                            <h4 class="mb-0">Gestión de Capacitaciones</h4>
                            <a href="<?= base_url('capacitaciones/crear') ?>" class="btn btn-primary">
                                <i class="bi bi-plus-circle"></i> Nueva Capacitación
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Estadísticas -->
                        <div class="row mb-4">
                            <div class="col-md-3">
                                <div class="card bg-primary text-white">
                                    <div class="card-body">
                                        <h5 class="card-title">Total Capacitaciones</h5>
                                        <h3 class="mb-0" id="totalCapacitaciones">0</h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-success text-white">
                                    <div class="card-body">
                                        <h5 class="card-title">Activas</h5>
                                        <h3 class="mb-0" id="capacitacionesActivas">0</h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-warning text-white">
                                    <div class="card-body">
                                        <h5 class="card-title">Total Inscripciones</h5>
                                        <h3 class="mb-0" id="totalInscripciones">0</h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-info text-white">
                                    <div class="card-body">
                                        <h5 class="card-title">Completadas</h5>
                                        <h3 class="mb-0" id="completadas">0</h3>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Filtros -->
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <select class="form-select" id="filtroTipo">
                                    <option value="">Todos los tipos</option>
                                    <option value="Técnica">Técnica</option>
                                    <option value="Pedagógica">Pedagógica</option>
                                    <option value="Administrativa">Administrativa</option>
                                    <option value="Soft Skills">Soft Skills</option>
                                    <option value="Otra">Otra</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select class="form-select" id="filtroEstado">
                                    <option value="">Todos los estados</option>
                                    <option value="Activa">Activa</option>
                                    <option value="Finalizada">Finalizada</option>
                                    <option value="Cancelada">Cancelada</option>
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

                        <!-- Tabla de Capacitaciones -->
                        <div class="table-responsive">
                            <table class="table table-striped table-hover" id="tablaCapacitaciones">
                                <thead class="table-dark">
                                    <tr>
                                        <th>ID</th>
                                        <th>Nombre</th>
                                        <th>Tipo</th>
                                        <th>Institución</th>
                                        <th>Fechas</th>
                                        <th>Duración</th>
                                        <th>Estado</th>
                                        <th>Inscritos</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($capacitaciones ?? [] as $capacitacion): ?>
                                    <tr>
                                        <td><?= $capacitacion['id_capacitacion'] ?></td>
                                        <td>
                                            <strong><?= esc($capacitacion['nombre']) ?></strong>
                                            <br><small class="text-muted"><?= esc($capacitacion['descripcion']) ?></small>
                                        </td>
                                        <td>
                                            <span class="badge bg-info"><?= $capacitacion['tipo'] ?></span>
                                        </td>
                                        <td><?= esc($capacitacion['institucion']) ?></td>
                                        <td>
                                            <small>
                                                <strong>Inicio:</strong> <?= date('d/m/Y', strtotime($capacitacion['fecha_inicio'])) ?><br>
                                                <strong>Fin:</strong> <?= date('d/m/Y', strtotime($capacitacion['fecha_fin'])) ?>
                                            </small>
                                        </td>
                                        <td><?= $capacitacion['duracion_horas'] ?> horas</td>
                                        <td>
                                            <?php
                                            $estadoClass = 'bg-success';
                                            if ($capacitacion['estado'] == 'Finalizada') $estadoClass = 'bg-warning';
                                            if ($capacitacion['estado'] == 'Cancelada') $estadoClass = 'bg-danger';
                                            ?>
                                            <span class="badge <?= $estadoClass ?>"><?= $capacitacion['estado'] ?></span>
                                        </td>
                                        <td>
                                            <span class="badge bg-primary"><?= $capacitacion['total_inscritos'] ?? 0 ?></span>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="<?= base_url('capacitaciones/editar/' . $capacitacion['id_capacitacion']) ?>" 
                                                   class="btn btn-sm btn-outline-primary" title="Editar">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <a href="<?= base_url('capacitaciones/empleados/' . $capacitacion['id_capacitacion']) ?>" 
                                                   class="btn btn-sm btn-outline-info" title="Ver Empleados">
                                                    <i class="bi bi-people"></i>
                                                </a>
                                                <button type="button" class="btn btn-sm btn-outline-danger" 
                                                        onclick="eliminarCapacitacion(<?= $capacitacion['id_capacitacion'] ?>)" title="Eliminar">
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

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(document).ready(function() {
    // Inicializar DataTable
    $('#tablaCapacitaciones').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json'
        },
        order: [[0, 'desc']]
    });

    // Cargar estadísticas
    cargarEstadisticas();
});

function cargarEstadisticas() {
    $.ajax({
        url: '<?= base_url('capacitaciones/getEstadisticas') ?>',
        type: 'GET',
        success: function(response) {
            $('#totalCapacitaciones').text(response.total_capacitaciones);
            $('#capacitacionesActivas').text(response.capacitaciones_activas);
            $('#totalInscripciones').text(response.total_inscripciones);
            $('#completadas').text(response.completadas);
        }
    });
}

function aplicarFiltros() {
    const tipo = $('#filtroTipo').val();
    const estado = $('#filtroEstado').val();
    const fecha = $('#filtroFecha').val();
    
    $('#tablaCapacitaciones').DataTable().columns().every(function() {
        const column = this;
        let searchTerm = '';
        
        if (column.index() === 2 && tipo) { // Columna tipo
            searchTerm = tipo;
        } else if (column.index() === 6 && estado) { // Columna estado
            searchTerm = estado;
        } else if (column.index() === 4 && fecha) { // Columna fechas
            searchTerm = fecha;
        }
        
        column.search(searchTerm).draw();
    });
}

function limpiarFiltros() {
    $('#filtroTipo, #filtroEstado').val('');
    $('#filtroFecha').val('');
    $('#tablaCapacitaciones').DataTable().search('').columns().search('').draw();
}

function eliminarCapacitacion(id) {
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
                url: `<?= base_url('capacitaciones/eliminar') ?>/${id}`,
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

// Aplicar filtros en tiempo real
$('#filtroTipo, #filtroEstado, #filtroFecha').on('change keyup', function() {
    aplicarFiltros();
});
</script>
<?= $this->endSection() ?>
