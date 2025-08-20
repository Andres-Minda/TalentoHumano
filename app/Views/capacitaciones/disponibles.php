<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="<?= base_url('empleado/dashboard') ?>">Dashboard</a></li>
                        <li class="breadcrumb-item active">Capacitaciones Disponibles</li>
                    </ol>
                </div>
                <h4 class="page-title">Capacitaciones Disponibles</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="header-title">Plan de Capacitaciones del Talento Humano</h4>
                    <p class="text-muted mb-0">Estas son las capacitaciones disponibles. Puedes aplicar si estás interesado, pero no es obligatorio.</p>
                </div>
                <div class="card-body">
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
                            <select class="form-select" id="filtroInstitucion">
                                <option value="">Todas las instituciones</option>
                                <?php 
                                $instituciones = array_unique(array_column($capacitaciones ?? [], 'institucion'));
                                foreach ($instituciones as $institucion): 
                                ?>
                                <option value="<?= esc($institucion) ?>"><?= esc($institucion) ?></option>
                                <?php endforeach; ?>
                                ?>
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

                    <!-- Capacitaciones -->
                    <div class="row" id="capacitacionesContainer">
                        <?php if (!empty($capacitaciones)): ?>
                            <?php foreach ($capacitaciones as $capacitacion): ?>
                                <div class="col-md-6 col-lg-4 mb-4 capacitacion-item" 
                                     data-tipo="<?= esc($capacitacion['tipo']) ?>"
                                     data-institucion="<?= esc($capacitacion['institucion']) ?>"
                                     data-fecha="<?= $capacitacion['fecha_inicio'] ?>">
                                    <div class="card h-100">
                                        <div class="card-header bg-primary text-white">
                                            <h6 class="card-title mb-0"><?= esc($capacitacion['nombre']) ?></h6>
                                        </div>
                                        <div class="card-body">
                                            <p class="card-text"><?= esc($capacitacion['descripcion']) ?></p>
                                            
                                            <div class="row mb-2">
                                                <div class="col-6">
                                                    <small class="text-muted">Tipo:</small><br>
                                                    <span class="badge bg-info"><?= $capacitacion['tipo'] ?></span>
                                                </div>
                                                <div class="col-6">
                                                    <small class="text-muted">Duración:</small><br>
                                                    <strong><?= $capacitacion['duracion_horas'] ?> horas</strong>
                                                </div>
                                            </div>
                                            
                                            <div class="row mb-2">
                                                <div class="col-12">
                                                    <small class="text-muted">Institución:</small><br>
                                                    <strong><?= esc($capacitacion['institucion']) ?></strong>
                                                </div>
                                            </div>
                                            
                                            <div class="row mb-3">
                                                <div class="col-6">
                                                    <small class="text-muted">Inicio:</small><br>
                                                    <strong><?= date('d/m/Y', strtotime($capacitacion['fecha_inicio'])) ?></strong>
                                                </div>
                                                <div class="col-6">
                                                    <small class="text-muted">Fin:</small><br>
                                                    <strong><?= date('d/m/Y', strtotime($capacitacion['fecha_fin'])) ?></strong>
                                                </div>
                                            </div>
                                            
                                            <div class="alert alert-info">
                                                <small>
                                                    <i class="bi bi-info-circle"></i>
                                                    <strong>Nota:</strong> Esta capacitación es informativa. 
                                                    Si obtienes un certificado, puedes registrarlo en tu perfil.
                                                </small>
                                            </div>
                                        </div>
                                        <div class="card-footer">
                                            <div class="d-grid">
                                                <button type="button" class="btn btn-outline-primary btn-sm" 
                                                        onclick="verDetalles(<?= $capacitacion['id_capacitacion'] ?>)">
                                                    <i class="bi bi-eye"></i> Ver Detalles
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="col-12">
                                <div class="alert alert-info text-center">
                                    <h5>No hay capacitaciones disponibles</h5>
                                    <p>El departamento de Talento Humano aún no ha publicado capacitaciones para este período.</p>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para ver detalles -->
<div class="modal fade" id="modalDetalles" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detalles de la Capacitación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="detallesCapacitacion">
                <!-- Se llenará dinámicamente -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(document).ready(function() {
    // Aplicar filtros en tiempo real
    $('#filtroTipo, #filtroInstitucion, #filtroFecha').on('change keyup', function() {
        aplicarFiltros();
    });
});

function aplicarFiltros() {
    const tipo = $('#filtroTipo').val();
    const institucion = $('#filtroInstitucion').val();
    const fecha = $('#filtroFecha').val();
    
    $('.capacitacion-item').each(function() {
        const item = $(this);
        const itemTipo = item.data('tipo');
        const itemInstitucion = item.data('institucion');
        const itemFecha = item.data('fecha');
        
        let mostrar = true;
        
        if (tipo && itemTipo !== tipo) mostrar = false;
        if (institucion && itemInstitucion !== institucion) mostrar = false;
        if (fecha && itemFecha !== fecha) mostrar = false;
        
        if (mostrar) {
            item.show();
        } else {
            item.hide();
        }
    });
}

function limpiarFiltros() {
    $('#filtroTipo, #filtroInstitucion').val('');
    $('#filtroFecha').val('');
    $('.capacitacion-item').show();
}

function verDetalles(id) {
    // Aquí se implementaría la lógica para mostrar detalles completos
    // Por ahora solo mostramos un mensaje
    $('#detallesCapacitacion').html(`
        <div class="text-center">
            <h6>Detalles de la Capacitación</h6>
            <p>Esta funcionalidad mostrará información detallada de la capacitación seleccionada.</p>
        </div>
    `);
    $('#modalDetalles').modal('show');
}
</script>
<?= $this->endSection() ?>
