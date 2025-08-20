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
                            <h4 class="mb-0">Capacitaciones por Empleado</h4>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalRegistrarCapacitacion">
                                <i class="bi bi-plus-circle"></i> Registrar Capacitación Completada
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Estadísticas -->
                        <div class="row mb-4">
                            <div class="col-md-3">
                                <div class="card bg-primary text-white">
                                    <div class="card-body">
                                        <h5 class="card-title">Total Empleados</h5>
                                        <h3 class="mb-0"><?= count($empleados ?? []) ?></h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-success text-white">
                                    <div class="card-body">
                                        <h5 class="card-title">Con Capacitaciones</h5>
                                        <h3 class="mb-0"><?= $estadisticas['empleados_con_capacitaciones'] ?? 0 ?></h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-warning text-white">
                                    <div class="card-body">
                                        <h5 class="card-title">Total Capacitaciones</h5>
                                        <h3 class="mb-0"><?= $estadisticas['total_capacitaciones'] ?? 0 ?></h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-info text-white">
                                    <div class="card-body">
                                        <h5 class="card-title">Promedio por Empleado</h5>
                                        <h3 class="mb-0"><?= $estadisticas['promedio_por_empleado'] ?? 0 ?></h3>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Filtros -->
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <select class="form-select" id="filtroDepartamento">
                                    <option value="">Todos los departamentos</option>
                                    <?php 
                                    $departamentos = array_unique(array_column($empleados ?? [], 'departamento'));
                                    foreach ($departamentos as $departamento): 
                                    ?>
                                    <option value="<?= esc($departamento) ?>"><?= esc($departamento) ?></option>
                                    <?php endforeach; ?>
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select class="form-select" id="filtroTipoEmpleado">
                                    <option value="">Todos los tipos</option>
                                    <option value="DOCENTE">Docente</option>
                                    <option value="ADMINISTRATIVO">Administrativo</option>
                                    <option value="DIRECTIVO">Directivo</option>
                                    <option value="AUXILIAR">Auxiliar</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <input type="text" class="form-control" id="filtroNombre" placeholder="Buscar por nombre">
                            </div>
                            <div class="col-md-3">
                                <button type="button" class="btn btn-secondary" onclick="limpiarFiltros()">
                                    <i class="bi bi-x-circle"></i> Limpiar Filtros
                                </button>
                            </div>
                        </div>

                        <!-- Tabla de Empleados con Capacitaciones -->
                        <div class="table-responsive">
                            <table class="table table-striped table-hover" id="tablaEmpleados">
                                <thead class="table-dark">
                                    <tr>
                                        <th>ID</th>
                                        <th>Empleado</th>
                                        <th>Tipo</th>
                                        <th>Departamento</th>
                                        <th>Capacitaciones</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($empleados ?? [] as $empleado): ?>
                                    <tr>
                                        <td><?= $empleado['id_empleado'] ?></td>
                                        <td>
                                            <strong><?= esc($empleado['nombres']) ?> <?= esc($empleado['apellidos']) ?></strong>
                                            <br><small class="text-muted"><?= esc($empleado['cedula']) ?></small>
                                        </td>
                                        <td>
                                            <span class="badge bg-info"><?= $empleado['tipo_empleado'] ?></span>
                                            <?php if (isset($empleado['tipo_docente']) && $empleado['tipo_docente']): ?>
                                                <br><small class="text-muted"><?= $empleado['tipo_docente'] ?></small>
                                            <?php endif; ?>
                                        </td>
                                        <td><?= esc($empleado['departamento'] ?? 'No asignado') ?></td>
                                        <td>
                                            <span class="badge bg-primary"><?= $empleado['total_capacitaciones'] ?? 0 ?></span>
                                            <?php if (isset($empleado['capacitaciones_recientes']) && !empty($empleado['capacitaciones_recientes'])): ?>
                                                <br><small class="text-muted">
                                                    Última: <?= $empleado['capacitaciones_recientes'][0]['nombre'] ?? 'N/A' ?>
                                                </small>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php
                                            $totalCap = $empleado['total_capacitaciones'] ?? 0;
                                            if ($totalCap == 0) {
                                                echo '<span class="badge bg-warning">Sin capacitaciones</span>';
                                            } elseif ($totalCap < 3) {
                                                echo '<span class="badge bg-info">Básico</span>';
                                            } elseif ($totalCap < 6) {
                                                echo '<span class="badge bg-success">Intermedio</span>';
                                            } else {
                                                echo '<span class="badge bg-primary">Avanzado</span>';
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <button type="button" class="btn btn-sm btn-outline-primary" 
                                                        onclick="verCapacitaciones(<?= $empleado['id_empleado'] ?>)" title="Ver Capacitaciones">
                                                    <i class="bi bi-eye"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline-success" 
                                                        onclick="registrarCapacitacion(<?= $empleado['id_empleado'] ?>)" title="Registrar Capacitación">
                                                    <i class="bi bi-plus-circle"></i>
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

<!-- Modal para Registrar Capacitación -->
<div class="modal fade" id="modalRegistrarCapacitacion" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Registrar Capacitación Completada</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formRegistrarCapacitacion">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="empleado_id" class="form-label">Empleado *</label>
                                <select class="form-select" id="empleado_id" name="empleado_id" required>
                                    <option value="">Seleccionar empleado...</option>
                                    <?php foreach ($empleados ?? [] as $empleado): ?>
                                    <option value="<?= $empleado['id_empleado'] ?>"><?= $empleado['nombres'] . ' ' . $empleado['apellidos'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="nombre_capacitacion" class="form-label">Nombre de la Capacitación *</label>
                                <input type="text" class="form-control" id="nombre_capacitacion" name="nombre_capacitacion" required>
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
                                <label for="fecha_completado" class="form-label">Fecha de Completado *</label>
                                <input type="date" class="form-control" id="fecha_completado" name="fecha_completado" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="duracion_horas" class="form-label">Duración (horas)</label>
                                <input type="number" class="form-control" id="duracion_horas" name="duracion_horas" min="1">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="puntaje" class="form-label">Puntaje (opcional)</label>
                                <input type="number" class="form-control" id="puntaje" name="puntaje" min="0" max="100">
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="observaciones" class="form-label">Observaciones</label>
                        <textarea class="form-control" id="observaciones" name="observaciones" rows="3"></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label for="archivo_certificado" class="form-label">Certificado (opcional)</label>
                        <input type="file" class="form-control" id="archivo_certificado" name="archivo_certificado" accept=".pdf,.jpg,.jpeg,.png">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Registrar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para Ver Capacitaciones del Empleado -->
<div class="modal fade" id="modalVerCapacitaciones" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Capacitaciones del Empleado</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="capacitacionesEmpleado">
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
    $('#tablaEmpleados').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json'
        },
        order: [[0, 'asc']]
    });

    // Configurar fecha por defecto
    $('#fecha_completado').val(new Date().toISOString().split('T')[0]);
});

function aplicarFiltros() {
    const departamento = $('#filtroDepartamento').val();
    const tipoEmpleado = $('#filtroTipoEmpleado').val();
    const nombre = $('#filtroNombre').val().toLowerCase();
    
    $('#tablaEmpleados').DataTable().columns().every(function() {
        const column = this;
        let searchTerm = '';
        
        if (column.index() === 2 && tipoEmpleado) { // Columna tipo
            searchTerm = tipoEmpleado;
        } else if (column.index() === 3 && departamento) { // Columna departamento
            searchTerm = departamento;
        } else if (column.index() === 1 && nombre) { // Columna empleado
            searchTerm = nombre;
        }
        
        column.search(searchTerm).draw();
    });
}

function limpiarFiltros() {
    $('#filtroDepartamento, #filtroTipoEmpleado').val('');
    $('#filtroNombre').val('');
    $('#tablaEmpleados').DataTable().search('').columns().search('').draw();
}

function verCapacitaciones(idEmpleado) {
    // Aquí se implementaría la lógica para mostrar las capacitaciones del empleado
    $('#capacitacionesEmpleado').html(`
        <div class="text-center">
            <h6>Capacitaciones del Empleado</h6>
            <p>Esta funcionalidad mostrará todas las capacitaciones registradas para este empleado.</p>
        </div>
    `);
    $('#modalVerCapacitaciones').modal('show');
}

function registrarCapacitacion(idEmpleado) {
    $('#empleado_id').val(idEmpleado);
    $('#modalRegistrarCapacitacion').modal('show');
}

// Aplicar filtros en tiempo real
$('#filtroDepartamento, #filtroTipoEmpleado, #filtroNombre').on('change keyup', function() {
    aplicarFiltros();
});

// Manejar envío del formulario
$('#formRegistrarCapacitacion').on('submit', function(e) {
    e.preventDefault();
    
    // Aquí se implementaría la lógica para guardar la capacitación
    Swal.fire({
        icon: 'success',
        title: 'Capacitación Registrada',
        text: 'La capacitación se ha registrado exitosamente para el empleado.'
    }).then(() => {
        $('#modalRegistrarCapacitacion').modal('hide');
        location.reload();
    });
});
</script>
<?= $this->endSection() ?>
