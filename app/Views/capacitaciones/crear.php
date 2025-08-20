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
                            <h4 class="mb-0">Nueva Capacitación</h4>
                            <a href="<?= base_url('capacitaciones') ?>" class="btn btn-secondary">
                                <i class="ti ti-arrow-left"></i> Volver
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <?php if (session()->getFlashdata('errors')): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <ul class="mb-0">
                                    <?php foreach (session()->getFlashdata('errors') as $error): ?>
                                        <li><?= $error ?></li>
                                    <?php endforeach; ?>
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <form action="<?= base_url('capacitaciones/guardar') ?>" method="POST" enctype="multipart/form-data" id="formCapacitacion">
                            <div class="row">
                                <!-- Nombre de la Capacitación -->
                                <div class="col-md-12 mb-3">
                                    <label for="nombre" class="form-label">Nombre de la Capacitación <span class="text-danger">*</span></label>
                                    <input type="text" name="nombre" id="nombre" class="form-control" 
                                           value="<?= old('nombre') ?>" required minlength="5" maxlength="255">
                                </div>

                                <!-- Descripción -->
                                <div class="col-md-12 mb-3">
                                    <label for="descripcion" class="form-label">Descripción <span class="text-danger">*</span></label>
                                    <textarea name="descripcion" id="descripcion" class="form-control" rows="3" 
                                              required minlength="10"><?= old('descripcion') ?></textarea>
                                </div>

                                <!-- Tipo y Duración -->
                                <div class="col-md-6 mb-3">
                                    <label for="tipo" class="form-label">Tipo de Capacitación <span class="text-danger">*</span></label>
                                    <select name="tipo" id="tipo" class="form-select" required>
                                        <option value="">Seleccione el tipo</option>
                                        <?php foreach ($tipos as $tipo): ?>
                                            <option value="<?= $tipo ?>" <?= old('tipo') == $tipo ? 'selected' : '' ?>>
                                                <?= $tipo ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="duracion_horas" class="form-label">Duración (horas) <span class="text-danger">*</span></label>
                                    <input type="number" name="duracion_horas" id="duracion_horas" class="form-control" 
                                           value="<?= old('duracion_horas') ?>" required min="1" max="1000">
                                </div>

                                <!-- Institución -->
                                <div class="col-md-12 mb-3">
                                    <label for="institucion" class="form-label">Institución o Centro de Estudios <span class="text-danger">*</span></label>
                                    <input type="text" name="institucion" id="institucion" class="form-control" 
                                           value="<?= old('institucion') ?>" required minlength="2" maxlength="255">
                                </div>

                                <!-- Fechas -->
                                <div class="col-md-6 mb-3">
                                    <label for="fecha_inicio" class="form-label">Fecha de Inicio <span class="text-danger">*</span></label>
                                    <input type="date" name="fecha_inicio" id="fecha_inicio" class="form-control" 
                                           value="<?= old('fecha_inicio') ?>" required>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="fecha_fin" class="form-label">Fecha de Fin <span class="text-danger">*</span></label>
                                    <input type="date" name="fecha_fin" id="fecha_fin" class="form-control" 
                                           value="<?= old('fecha_fin') ?>" required>
                                </div>

                                <!-- Archivo de Certificado -->
                                <div class="col-md-12 mb-3">
                                    <label for="archivo_certificado" class="form-label">Archivo de Certificado (opcional)</label>
                                    <input type="file" name="archivo_certificado" id="archivo_certificado" class="form-control" 
                                           accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
                                    <small class="text-muted">Formatos permitidos: PDF, JPG, PNG, DOC, DOCX. Máximo 5MB.</small>
                                </div>

                                <!-- Empleados Seleccionados -->
                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Empleados a Asignar <span class="text-danger">*</span></label>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="selectAll">
                                                <label class="form-check-label" for="selectAll">
                                                    <strong>Seleccionar Todos</strong>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <button type="button" class="btn btn-outline-info btn-sm" onclick="filtrarPorTipo()">
                                                <i class="bi bi-funnel"></i> Filtrar por Tipo
                                            </button>
                                            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="limpiarFiltros()">
                                                <i class="bi bi-x-circle"></i> Limpiar Filtros
                                            </button>
                                        </div>
                                    </div>
                                    
                                    <div class="mt-3">
                                        <div class="row" id="empleadosContainer">
                                            <?php foreach ($empleados as $empleado): ?>
                                                <div class="col-md-6 col-lg-4 mb-2 empleado-item" 
                                                     data-tipo="<?= $empleado['tipo_empleado'] ?>"
                                                     data-departamento="<?= esc($empleado['departamento'] ?? '') ?>">
                                                    <div class="form-check">
                                                        <input class="form-check-input empleado-checkbox" type="checkbox" 
                                                               name="empleados_seleccionados[]" 
                                                               value="<?= $empleado['id_empleado'] ?>" 
                                                               id="empleado_<?= $empleado['id_empleado'] ?>">
                                                        <label class="form-check-label" for="empleado_<?= $empleado['id_empleado'] ?>">
                                                            <strong><?= esc($empleado['nombres']) ?> <?= esc($empleado['apellidos']) ?></strong>
                                                            <br><small class="text-muted">
                                                                <?= $empleado['tipo_empleado'] ?> - <?= esc($empleado['departamento'] ?? 'No asignado') ?>
                                                            </small>
                                                        </label>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Botones -->
                            <div class="row mt-4">
                                <div class="col-12">
                                    <div class="d-flex justify-content-end gap-2">
                                        <a href="<?= base_url('capacitaciones') ?>" class="btn btn-secondary">
                                            <i class="ti ti-x"></i> Cancelar
                                        </a>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="ti ti-check"></i> Crear Capacitación
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const fechaInicio = document.getElementById('fecha_inicio');
    const fechaFin = document.getElementById('fecha_fin');
    const selectAll = document.getElementById('selectAll');
    const empleadoCheckboxes = document.querySelectorAll('.empleado-checkbox');

    // Configurar fecha mínima como hoy
    const today = new Date().toISOString().split('T')[0];
    fechaInicio.min = today;
    fechaFin.min = today;

    // Validar que fecha fin no sea menor que fecha inicio
    fechaInicio.addEventListener('change', function() {
        fechaFin.min = this.value;
        if (fechaFin.value && fechaFin.value < this.value) {
            fechaFin.value = this.value;
        }
    });

    // Seleccionar/deseleccionar todos los empleados
    selectAll.addEventListener('change', function() {
        empleadoCheckboxes.forEach(checkbox => {
            if (checkbox.closest('.empleado-item').style.display !== 'none') {
                checkbox.checked = this.checked;
            }
        });
    });

    // Actualizar estado de "seleccionar todos"
    empleadoCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const visibleCheckboxes = Array.from(empleadoCheckboxes).filter(cb => 
                cb.closest('.empleado-item').style.display !== 'none'
            );
            const checkedCheckboxes = visibleCheckboxes.filter(cb => cb.checked);
            
            selectAll.checked = checkedCheckboxes.length === visibleCheckboxes.length;
            selectAll.indeterminate = checkedCheckboxes.length > 0 && checkedCheckboxes.length < visibleCheckboxes.length;
        });
    });

    // Validación del formulario
    document.getElementById('formCapacitacion').addEventListener('submit', function(e) {
        const empleadosSeleccionados = document.querySelectorAll('input[name="empleados_seleccionados[]"]:checked');
        
        if (empleadosSeleccionados.length === 0) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Debe seleccionar al menos un empleado para la capacitación.'
            });
            return false;
        }

        // Validar fechas
        if (fechaInicio.value >= fechaFin.value) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'La fecha de fin debe ser posterior a la fecha de inicio.'
            });
            return false;
        }
    });
});

function filtrarPorTipo() {
    const tipos = ['DOCENTE', 'ADMINISTRATIVO', 'DIRECTIVO', 'AUXILIAR'];
    const tipoSeleccionado = prompt('Seleccione el tipo de empleado:\n\n' + tipos.map((t, i) => `${i + 1}. ${t}`).join('\n'));
    
    if (tipoSeleccionado && tipos[tipoSeleccionado - 1]) {
        const tipo = tipos[tipoSeleccionado - 1];
        
        document.querySelectorAll('.empleado-item').forEach(item => {
            if (item.dataset.tipo === tipo) {
                item.style.display = 'block';
            } else {
                item.style.display = 'none';
            }
        });
        
        // Actualizar estado de checkboxes
        actualizarEstadoCheckboxes();
    }
}

function limpiarFiltros() {
    document.querySelectorAll('.empleado-item').forEach(item => {
        item.style.display = 'block';
    });
    
    // Desmarcar todos los checkboxes
    document.querySelectorAll('.empleado-checkbox').forEach(checkbox => {
        checkbox.checked = false;
    });
    
    document.getElementById('selectAll').checked = false;
    document.getElementById('selectAll').indeterminate = false;
}

function actualizarEstadoCheckboxes() {
    const visibleCheckboxes = Array.from(document.querySelectorAll('.empleado-checkbox')).filter(cb => 
        cb.closest('.empleado-item').style.display !== 'none'
    );
    const checkedCheckboxes = visibleCheckboxes.filter(cb => cb.checked);
    
    const selectAll = document.getElementById('selectAll');
    selectAll.checked = checkedCheckboxes.length === visibleCheckboxes.length;
    selectAll.indeterminate = checkedCheckboxes.length > 0 && checkedCheckboxes.length < visibleCheckboxes.length;
}
</script>
<?= $this->endSection() ?>
