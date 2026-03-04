<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>

<div class="page-wrapper">
    <div class="page-content">
        <!-- Breadcrumb -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <h4 class="page-title mb-0">Editar Inasistencia</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="<?= base_url('admin-th/dashboard') ?>">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="<?= base_url('admin-th/inasistencias') ?>">Inasistencias</a></li>
                            <li class="breadcrumb-item active">Editar</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-8 mx-auto">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white pb-0">
                        <h5 class="card-title mb-0">
                            <i class="ti ti-pencil text-warning me-2"></i>Modificar Registro
                        </h5>
                    </div>
                    <div class="card-body">
                        
                        <form id="formEditarInasistencia" class="needs-validation" novalidate>
                            <?= csrf_field() ?>
                            <input type="hidden" id="inasistencia_id" name="id" value="<?= esc($inasistencia['id']) ?>">
                            
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <label for="empleado_id" class="form-label">Empleado Registrado <span class="text-danger">*</span></label>
                                    <select class="form-select" id="empleado_id" name="empleado_id" required>
                                        <option value="">Seleccione un empleado...</option>
                                        <?php foreach ($empleados as $emp): ?>
                                            <option value="<?= $emp['id_empleado'] ?>" <?= ($emp['id_empleado'] == $inasistencia['empleado_id']) ? 'selected' : '' ?>>
                                                <?= esc($emp['apellidos'] . ' ' . $emp['nombres']) ?> - <?= esc($emp['departamento']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <div class="invalid-feedback">Debe seleccionar un empleado.</div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="fecha_inasistencia" class="form-label">Fecha de Inasistencia <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="fecha_inasistencia" name="fecha_inasistencia" value="<?= esc($inasistencia['fecha_inasistencia']) ?>" required>
                                    <div class="invalid-feedback">La fecha es obligatoria.</div>
                                </div>
                                <div class="col-md-6">
                                    <label for="hora_inasistencia" class="form-label">Hora exacto (Opcional)</label>
                                    <input type="time" class="form-control" id="hora_inasistencia" name="hora_inasistencia" value="<?= esc($inasistencia['hora_inasistencia']) ?>">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <label for="tipo_inasistencia" class="form-label">Tipo de Inasistencia / Estado <span class="text-danger">*</span></label>
                                    <select class="form-select" id="tipo_inasistencia" name="tipo_inasistencia" required>
                                        <?php 
                                        $tipos = ['Injustificada', 'Justificada', 'Permiso', 'Vacaciones', 'Licencia Médica'];
                                        foreach ($tipos as $tipo): 
                                        ?>
                                            <option value="<?= $tipo ?>" <?= ($tipo == $inasistencia['tipo_inasistencia']) ? 'selected' : '' ?>>
                                                <?= $tipo ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-12">
                                    <label for="motivo" class="form-label">Motivo o Descripción de la Falta <span class="text-danger">*</span></label>
                                    <textarea class="form-control" id="motivo" name="motivo" rows="4" required minlength="5" placeholder="Describa brevemente la razón de la inasistencia..."><?= esc($inasistencia['motivo']) ?></textarea>
                                    <div class="invalid-feedback">Debe proporcionar un motivo válido (mínimo 5 caracteres).</div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end border-top pt-3">
                                <a href="javascript:history.back()" class="btn btn-light me-2">Cancelar</a>
                                <button type="submit" class="btn btn-warning" id="btnGuardar">
                                    <i class="ti ti-device-floppy me-1"></i> Guardar Cambios
                                </button>
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
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('formEditarInasistencia');
    
    form.addEventListener('submit', function (event) {
        event.preventDefault();
        
        if (!form.checkValidity()) {
            event.stopPropagation();
            form.classList.add('was-validated');
            return;
        }

        const btnSubmit = document.getElementById('btnGuardar');
        const id = document.getElementById('inasistencia_id').value;
        const formData = new FormData(form);

        btnSubmit.disabled = true;
        btnSubmit.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Guardando...';

        fetch(`<?= site_url('admin-th/inasistencias/actualizar/') ?>${id}`, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // El backend ya estableció un flashdata, pero por si acaso damos un fallback
                window.location.href = '<?= site_url('admin-th/inasistencias') ?>';
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error de validación',
                    text: data.message || 'Ocurrió un error al guardar los cambios.'
                });
                btnSubmit.disabled = false;
                btnSubmit.innerHTML = '<i class="ti ti-device-floppy me-1"></i> Guardar Cambios';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error del servidor',
                text: 'Hubo un problema de conexión. Intente nuevamente.'
            });
            btnSubmit.disabled = false;
            btnSubmit.innerHTML = '<i class="ti ti-device-floppy me-1"></i> Guardar Cambios';
        });
    });
});
</script>
<?= $this->endSection() ?>
