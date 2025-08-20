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
                            <h4 class="mb-0">Editar Capacitación</h4>
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

                        <form action="<?= base_url('capacitaciones/actualizar/' . $capacitacion['id_capacitacion']) ?>" method="POST" enctype="multipart/form-data" id="formEditarCapacitacion">
                            <div class="row">
                                <!-- Nombre de la Capacitación -->
                                <div class="col-md-12 mb-3">
                                    <label for="nombre" class="form-label">Nombre de la Capacitación <span class="text-danger">*</span></label>
                                    <input type="text" name="nombre" id="nombre" class="form-control" 
                                           value="<?= old('nombre', $capacitacion['nombre']) ?>" required minlength="5" maxlength="255">
                                </div>

                                <!-- Descripción -->
                                <div class="col-md-12 mb-3">
                                    <label for="descripcion" class="form-label">Descripción <span class="text-danger">*</span></label>
                                    <textarea name="descripcion" id="descripcion" class="form-control" rows="3" 
                                              required minlength="10"><?= old('descripcion', $capacitacion['descripcion']) ?></textarea>
                                </div>

                                <!-- Tipo y Duración -->
                                <div class="col-md-6 mb-3">
                                    <label for="tipo" class="form-label">Tipo de Capacitación <span class="text-danger">*</span></label>
                                    <select name="tipo" id="tipo" class="form-select" required>
                                        <option value="">Seleccione el tipo</option>
                                        <?php foreach ($tipos as $tipo): ?>
                                            <option value="<?= $tipo ?>" <?= (old('tipo', $capacitacion['tipo']) == $tipo) ? 'selected' : '' ?>>
                                                <?= $tipo ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="duracion_horas" class="form-label">Duración (horas) <span class="text-danger">*</span></label>
                                    <input type="number" name="duracion_horas" id="duracion_horas" class="form-control" 
                                           value="<?= old('duracion_horas', $capacitacion['duracion_horas']) ?>" required min="1" max="1000">
                                </div>

                                <!-- Institución -->
                                <div class="col-md-12 mb-3">
                                    <label for="institucion" class="form-label">Institución o Centro de Estudios <span class="text-danger">*</span></label>
                                    <input type="text" name="institucion" id="institucion" class="form-control" 
                                           value="<?= old('institucion', $capacitacion['institucion']) ?>" required minlength="2" maxlength="255">
                                </div>

                                <!-- Fechas -->
                                <div class="col-md-6 mb-3">
                                    <label for="fecha_inicio" class="form-label">Fecha de Inicio <span class="text-danger">*</span></label>
                                    <input type="date" name="fecha_inicio" id="fecha_inicio" class="form-control" 
                                           value="<?= old('fecha_inicio', $capacitacion['fecha_inicio']) ?>" required>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="fecha_fin" class="form-label">Fecha de Fin <span class="text-danger">*</span></label>
                                    <input type="date" name="fecha_fin" id="fecha_fin" class="form-control" 
                                           value="<?= old('fecha_fin', $capacitacion['fecha_fin']) ?>" required>
                                </div>

                                <!-- Estado -->
                                <div class="col-md-6 mb-3">
                                    <label for="estado" class="form-label">Estado <span class="text-danger">*</span></label>
                                    <select name="estado" id="estado" class="form-select" required>
                                        <option value="Activa" <?= (old('estado', $capacitacion['estado']) == 'Activa') ? 'selected' : '' ?>>Activa</option>
                                        <option value="Finalizada" <?= (old('estado', $capacitacion['estado']) == 'Finalizada') ? 'selected' : '' ?>>Finalizada</option>
                                        <option value="Cancelada" <?= (old('estado', $capacitacion['estado']) == 'Cancelada') ? 'selected' : '' ?>>Cancelada</option>
                                    </select>
                                </div>

                                <!-- Archivo de Certificado -->
                                <div class="col-md-6 mb-3">
                                    <label for="archivo_certificado" class="form-label">Archivo de Certificado (opcional)</label>
                                    <input type="file" name="archivo_certificado" id="archivo_certificado" class="form-control" 
                                           accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
                                    <small class="text-muted">Formatos permitidos: PDF, JPG, PNG, DOC, DOCX. Máximo 5MB.</small>
                                    <?php if (!empty($capacitacion['archivo_certificado'])): ?>
                                        <br><small class="text-info">Archivo actual: <?= $capacitacion['archivo_certificado'] ?></small>
                                    <?php endif; ?>
                                </div>

                                <!-- Empleados Asignados -->
                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Empleados Asignados</label>
                                    <div class="alert alert-info">
                                        <i class="bi bi-info-circle"></i>
                                        <strong>Nota:</strong> Los empleados ya asignados a esta capacitación no se pueden modificar desde aquí. 
                                        Para cambiar la asignación, vaya a la vista de empleados por capacitación.
                                    </div>
                                    
                                    <div class="table-responsive">
                                        <table class="table table-sm table-bordered">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Empleado</th>
                                                    <th>Tipo</th>
                                                    <th>Departamento</th>
                                                    <th>Estado</th>
                                                    <th>Fecha Inscripción</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php if (!empty($empleadosAsignados)): ?>
                                                    <?php foreach ($empleadosAsignados as $empleado): ?>
                                                        <tr>
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
                                                                <?php
                                                                $estadoClass = 'bg-warning';
                                                                if ($empleado['estado'] == 'Completada') $estadoClass = 'bg-success';
                                                                if ($empleado['estado'] == 'Cancelada') $estadoClass = 'bg-danger';
                                                                ?>
                                                                <span class="badge <?= $estadoClass ?>"><?= $empleado['estado'] ?></span>
                                                            </td>
                                                            <td>
                                                                <?= $empleado['fecha_inscripcion'] ? date('d/m/Y', strtotime($empleado['fecha_inscripcion'])) : 'N/A' ?>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                <?php else: ?>
                                                    <tr>
                                                        <td colspan="5" class="text-center text-muted">
                                                            No hay empleados asignados a esta capacitación
                                                        </td>
                                                    </tr>
                                                <?php endif; ?>
                                            </tbody>
                                        </table>
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
                                            <i class="ti ti-check"></i> Actualizar Capacitación
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

    // Validar que fecha fin no sea menor que fecha inicio
    fechaInicio.addEventListener('change', function() {
        fechaFin.min = this.value;
        if (fechaFin.value && fechaFin.value < this.value) {
            fechaFin.value = this.value;
        }
    });

    // Validación del formulario
    document.getElementById('formEditarCapacitacion').addEventListener('submit', function(e) {
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
</script>
<?= $this->endSection() ?>
