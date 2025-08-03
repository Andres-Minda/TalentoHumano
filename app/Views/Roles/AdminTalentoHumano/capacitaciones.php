<?php $sidebar = 'sidebar_admin_th'; ?>
<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4><i class="bi bi-mortarboard"></i> Gestión de Capacitaciones</h4>
                    <div>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalNuevaCapacitacion">
                            <i class="bi bi-plus-circle"></i> Nueva Capacitación
                        </button>
                        <a href="<?= base_url('admin-th/dashboard') ?>" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Volver al Dashboard
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Estadísticas -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card bg-primary text-white">
                                <div class="card-body">
                                    <h5 class="card-title">Planificadas</h5>
                                    <h3><?= $estadisticas->planificadas ?? 0 ?></h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-warning text-dark">
                                <div class="card-body">
                                    <h5 class="card-title">En Curso</h5>
                                    <h3><?= $estadisticas->en_curso ?? 0 ?></h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-success text-white">
                                <div class="card-body">
                                    <h5 class="card-title">Completadas</h5>
                                    <h3><?= $estadisticas->completadas ?? 0 ?></h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-danger text-white">
                                <div class="card-body">
                                    <h5 class="card-title">Canceladas</h5>
                                    <h3><?= $estadisticas->canceladas ?? 0 ?></h3>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Filtros -->
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <input type="text" id="buscarCapacitacion" class="form-control" placeholder="Buscar capacitación...">
                        </div>
                        <div class="col-md-2">
                            <select id="filtroEstado" class="form-select">
                                <option value="">Todos los estados</option>
                                <option value="Planificada">Planificada</option>
                                <option value="En curso">En curso</option>
                                <option value="Completada">Completada</option>
                                <option value="Cancelada">Cancelada</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select id="filtroTipo" class="form-select">
                                <option value="">Todos los tipos</option>
                                <option value="Interna">Interna</option>
                                <option value="Externa">Externa</option>
                                <option value="Online">Online</option>
                                <option value="Presencial">Presencial</option>
                                <option value="Híbrida">Híbrida</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <button type="button" class="btn btn-outline-secondary" onclick="limpiarFiltros()">
                                <i class="bi bi-arrow-clockwise"></i> Limpiar Filtros
                            </button>
                        </div>
                    </div>

                    <!-- Tabla de capacitaciones -->
                    <div class="table-responsive">
                        <table class="table table-striped table-hover" id="tablaCapacitaciones">
                            <thead class="table-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>Título</th>
                                    <th>Tipo</th>
                                    <th>Instructor</th>
                                    <th>Fecha Inicio</th>
                                    <th>Fecha Fin</th>
                                    <th>Duración</th>
                                    <th>Inscritos</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($capacitaciones ?? [] as $capacitacion): ?>
                                <tr>
                                    <td><?= $capacitacion['id_capacitacion'] ?></td>
                                    <td><?= $capacitacion['nombre'] ?></td>
                                    <td>
                                        <span class="badge bg-<?= $capacitacion['tipo'] == 'Online' ? 'info' : ($capacitacion['tipo'] == 'Presencial' ? 'primary' : 'secondary') ?>">
                                            <?= $capacitacion['tipo'] ?>
                                        </span>
                                    </td>
                                    <td><?= $capacitacion['instructor'] ?></td>
                                    <td><?= date('d/m/Y', strtotime($capacitacion['fecha_inicio'])) ?></td>
                                    <td><?= date('d/m/Y', strtotime($capacitacion['fecha_fin'])) ?></td>
                                    <td><?= $capacitacion['duracion_horas'] ?> horas</td>
                                    <td>
                                        <span class="badge bg-info">
                                            <?= $capacitacion['total_inscritos'] ?? 0 ?> / <?= $capacitacion['cupo_maximo'] ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-<?= $capacitacion['estado'] == 'Planificada' ? 'warning' : ($capacitacion['estado'] == 'En curso' ? 'info' : ($capacitacion['estado'] == 'Completada' ? 'success' : 'danger')) ?>">
                                            <?= $capacitacion['estado'] ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button type="button" class="btn btn-sm btn-outline-primary" onclick="verCapacitacion(<?= $capacitacion['id_capacitacion'] ?>)">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-warning" onclick="editarCapacitacion(<?= $capacitacion['id_capacitacion'] ?>)">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-success" onclick="gestionarInscritos(<?= $capacitacion['id_capacitacion'] ?>)">
                                                <i class="bi bi-people"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-danger" onclick="eliminarCapacitacion(<?= $capacitacion['id_capacitacion'] ?>)">
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

<!-- Modal Nueva Capacitación -->
<div class="modal fade" id="modalNuevaCapacitacion" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Nueva Capacitación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formNuevaCapacitacion">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label class="form-label">Título *</label>
                                <input type="text" class="form-control" name="nombre" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Tipo *</label>
                                <select class="form-select" name="tipo" required>
                                    <option value="">Seleccionar</option>
                                    <option value="Interna">Interna</option>
                                    <option value="Externa">Externa</option>
                                    <option value="Online">Online</option>
                                    <option value="Presencial">Presencial</option>
                                    <option value="Híbrida">Híbrida</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Descripción</label>
                        <textarea class="form-control" name="descripcion" rows="3"></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Fecha de Inicio *</label>
                                <input type="date" class="form-control" name="fecha_inicio" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Fecha de Fin *</label>
                                <input type="date" class="form-control" name="fecha_fin" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Duración (horas) *</label>
                                <input type="number" class="form-control" name="duracion_horas" min="1" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Cupo Máximo *</label>
                                <input type="number" class="form-control" name="cupo_maximo" min="1" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Lugar</label>
                                <input type="text" class="form-control" name="lugar">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Instructor</label>
                                <input type="text" class="form-control" name="instructor">
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Costo</label>
                        <input type="number" class="form-control" name="costo" min="0" step="0.01">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar Capacitación</button>
                </div>
            </form>
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
        pageLength: 10,
        order: [[4, 'asc']]
    });

    // Búsqueda en tiempo real
    $('#buscarCapacitacion').on('keyup', function() {
        $('#tablaCapacitaciones').DataTable().search(this.value).draw();
    });

    // Filtros
    $('#filtroEstado, #filtroTipo').on('change', function() {
        aplicarFiltros();
    });

    // Formulario nueva capacitación
    $('#formNuevaCapacitacion').on('submit', function(e) {
        e.preventDefault();
        guardarCapacitacion();
    });
});

function aplicarFiltros() {
    var estado = $('#filtroEstado').val();
    var tipo = $('#filtroTipo').val();
    
    var table = $('#tablaCapacitaciones').DataTable();
    
    // Limpiar filtros anteriores
    table.draw();
    
    // Aplicar filtros personalizados
    $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
        var estadoMatch = !estado || data[8] === estado;
        var tipoMatch = !tipo || data[2] === tipo;
        
        return estadoMatch && tipoMatch;
    });
    
    table.draw();
}

function limpiarFiltros() {
    $('#filtroEstado, #filtroTipo').val('');
    $('#buscarCapacitacion').val('');
    $('#tablaCapacitaciones').DataTable().search('').draw();
    $.fn.dataTable.ext.search.pop();
    $('#tablaCapacitaciones').DataTable().draw();
}

function guardarCapacitacion() {
    var formData = new FormData($('#formNuevaCapacitacion')[0]);
    
    $.ajax({
        url: '<?= base_url('admin-th/capacitaciones/crear') ?>',
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
                text: 'Error al guardar la capacitación'
            });
        }
    });
}

function verCapacitacion(id) {
    window.location.href = '<?= base_url('admin-th/capacitaciones/ver/') ?>' + id;
}

function editarCapacitacion(id) {
    window.location.href = '<?= base_url('admin-th/capacitaciones/editar/') ?>' + id;
}

function gestionarInscritos(id) {
    window.location.href = '<?= base_url('admin-th/empleados-capacitaciones/') ?>' + id;
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
                url: '<?= base_url('admin-th/capacitaciones/eliminar/') ?>' + id,
                type: 'POST',
                success: function(response) {
                    if (response.success) {
                        Swal.fire('Eliminado', response.message, 'success').then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire('Error', response.message, 'error');
                    }
                }
            });
        }
    });
}
</script>
<?= $this->endSection() ?> 