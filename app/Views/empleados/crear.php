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
                            <a href="<?= base_url('empleados') ?>" class="btn btn-secondary">
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

                        <form action="<?= base_url('empleados/guardar') ?>" method="POST" id="formEmpleado">
                            <div class="row">
                                <!-- Usuario -->
                                <div class="col-md-6 mb-3">
                                    <label for="usuario_id" class="form-label">Usuario <span class="text-danger">*</span></label>
                                    <select name="usuario_id" id="usuario_id" class="form-select" required>
                                        <option value="">Seleccione un usuario</option>
                                        <?php foreach ($usuarios as $usuario): ?>
                                            <option value="<?= $usuario['id'] ?>" <?= old('usuario_id') == $usuario['id'] ? 'selected' : '' ?>>
                                                <?= esc($usuario['username']) ?> - <?= esc($usuario['email']) ?> (<?= $usuario['rol'] ?>)
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <!-- Cédula -->
                                <div class="col-md-6 mb-3">
                                    <label for="cedula" class="form-label">Cédula <span class="text-danger">*</span></label>
                                    <input type="text" name="cedula" id="cedula" class="form-control" 
                                           value="<?= old('cedula') ?>" required minlength="10" maxlength="20">
                                </div>

                                <!-- Nombres -->
                                <div class="col-md-6 mb-3">
                                    <label for="nombres" class="form-label">Nombres <span class="text-danger">*</span></label>
                                    <input type="text" name="nombres" id="nombres" class="form-control" 
                                           value="<?= old('nombres') ?>" required minlength="2" maxlength="255">
                                </div>

                                <!-- Apellidos -->
                                <div class="col-md-6 mb-3">
                                    <label for="apellidos" class="form-label">Apellidos <span class="text-danger">*</span></label>
                                    <input type="text" name="apellidos" id="apellidos" class="form-control" 
                                           value="<?= old('apellidos') ?>" required minlength="2" maxlength="255">
                                </div>

                                <!-- Tipo de Empleado -->
                                <div class="col-md-6 mb-3">
                                    <label for="tipo_empleado" class="form-label">Tipo de Empleado <span class="text-danger">*</span></label>
                                    <select name="tipo_empleado" id="tipo_empleado" class="form-select" required>
                                        <option value="">Seleccione el tipo</option>
                                        <option value="DOCENTE" <?= old('tipo_empleado') == 'DOCENTE' ? 'selected' : '' ?>>Docente</option>
                                        <option value="ADMINISTRATIVO" <?= old('tipo_empleado') == 'ADMINISTRATIVO' ? 'selected' : '' ?>>Administrativo</option>
                                        <option value="DIRECTIVO" <?= old('tipo_empleado') == 'DIRECTIVO' ? 'selected' : '' ?>>Directivo</option>
                                        <option value="AUXILIAR" <?= old('tipo_empleado') == 'AUXILIAR' ? 'selected' : '' ?>>Auxiliar</option>
                                    </select>
                                </div>

                                <!-- Tipo de Docente (condicional) -->
                                <div class="col-md-6 mb-3" id="campo_tipo_docente" style="display: none;">
                                    <label for="tipo_docente" class="form-label">Tipo de Docente <span class="text-danger">*</span></label>
                                    <select name="tipo_docente" id="tipo_docente" class="form-select">
                                        <option value="">Seleccione el tipo</option>
                                        <option value="Tiempo completo" <?= old('tipo_docente') == 'Tiempo completo' ? 'selected' : '' ?>>Tiempo completo</option>
                                        <option value="Medio tiempo" <?= old('tipo_docente') == 'Medio tiempo' ? 'selected' : '' ?>>Medio tiempo</option>
                                        <option value="Tiempo parcial" <?= old('tipo_docente') == 'Tiempo parcial' ? 'selected' : '' ?>>Tiempo parcial</option>
                                    </select>
                                </div>

                                <!-- Departamento -->
                                <div class="col-md-6 mb-3" id="campo_departamento">
                                    <label for="departamento" class="form-label">Departamento <span class="text-danger">*</span></label>
                                    <select name="departamento" id="departamento" class="form-select" required>
                                        <option value="">Seleccione el departamento</option>
                                    </select>
                                </div>

                                <!-- Fecha de Ingreso -->
                                <div class="col-md-6 mb-3">
                                    <label for="fecha_ingreso" class="form-label">Fecha de Ingreso <span class="text-danger">*</span></label>
                                    <input type="date" name="fecha_ingreso" id="fecha_ingreso" class="form-control" 
                                           value="<?= old('fecha_ingreso') ?>" required>
                                </div>

                                <!-- Salario -->
                                <div class="col-md-6 mb-3">
                                    <label for="salario" class="form-label">Salario</label>
                                    <input type="number" name="salario" id="salario" class="form-control" 
                                           value="<?= old('salario') ?>" step="0.01" min="0">
                                </div>
                            </div>

                            <!-- Botones -->
                            <div class="row mt-4">
                                <div class="col-12">
                                    <div class="d-flex justify-content-end gap-2">
                                        <a href="<?= base_url('empleados') ?>" class="btn btn-secondary">
                                            <i class="ti ti-x"></i> Cancelar
                                        </a>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="ti ti-check"></i> Guardar Empleado
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
    const tipoEmpleadoSelect = document.getElementById('tipo_empleado');
    const campoTipoDocente = document.getElementById('campo_tipo_docente');
    const campoDepartamento = document.getElementById('campo_departamento');
    const departamentoSelect = document.getElementById('departamento');
    const tipoDocenteSelect = document.getElementById('tipo_docente');

    // Event listener para cambios en tipo de empleado
    tipoEmpleadoSelect.addEventListener('change', function() {
        const tipoSeleccionado = this.value;
        
        // Mostrar/ocultar campo tipo de docente
        if (tipoSeleccionado === 'DOCENTE') {
            campoTipoDocente.style.display = 'block';
            tipoDocenteSelect.required = true;
        } else {
            campoTipoDocente.style.display = 'none';
            tipoDocenteSelect.required = false;
            tipoDocenteSelect.value = '';
        }

        // Cargar departamentos según tipo
        cargarDepartamentos(tipoSeleccionado);
        
        // Asignar departamento automático para DIRECTIVO y AUXILIAR
        if (tipoSeleccionado === 'DIRECTIVO' || tipoSeleccionado === 'AUXILIAR') {
            departamentoSelect.value = 'Departamento ITSI';
            departamentoSelect.disabled = true;
        } else {
            departamentoSelect.disabled = false;
        }
    });

    // Función para cargar departamentos según tipo de empleado
    function cargarDepartamentos(tipo) {
        departamentoSelect.innerHTML = '<option value="">Seleccione el departamento</option>';
        
        if (tipo === 'DOCENTE') {
            departamentoSelect.innerHTML += '<option value="Departamento General">Departamento General</option>';
        } else if (tipo === 'ADMINISTRATIVO') {
            const departamentos = [
                'Recursos Humanos',
                'Contabilidad', 
                'Tecnología',
                'Académico',
                'Administrativo',
                'Vinculación'
            ];
            
            departamentos.forEach(dept => {
                departamentoSelect.innerHTML += `<option value="${dept}">${dept}</option>`;
            });
        } else if (tipo === 'DIRECTIVO' || tipo === 'AUXILIAR') {
            departamentoSelect.innerHTML += '<option value="Departamento ITSI">Departamento ITSI</option>';
        }
    }

    // Validación del formulario antes de enviar
    document.getElementById('formEmpleado').addEventListener('submit', function(e) {
        const tipoEmpleado = tipoEmpleadoSelect.value;
        const tipoDocente = tipoDocenteSelect.value;
        const departamento = departamentoSelect.value;

        // Validar tipo de docente para DOCENTE
        if (tipoEmpleado === 'DOCENTE' && !tipoDocente) {
            e.preventDefault();
            alert('Para empleados tipo DOCENTE, el campo "Tipo de Docente" es obligatorio.');
            tipoDocenteSelect.focus();
            return false;
        }

        // Validar departamento
        if (!departamento) {
            e.preventDefault();
            alert('El campo "Departamento" es obligatorio.');
            departamentoSelect.focus();
            return false;
        }

        // Validar que no haya tipo de docente para no-docentes
        if (tipoEmpleado !== 'DOCENTE' && tipoDocente) {
            e.preventDefault();
            alert('El campo "Tipo de Docente" solo es válido para empleados tipo DOCENTE.');
            return false;
        }
    });

    // Cargar departamentos iniciales si hay valor en old()
    const tipoInicial = tipoEmpleadoSelect.value;
    if (tipoInicial) {
        cargarDepartamentos(tipoInicial);
        
        if (tipoInicial === 'DOCENTE') {
            campoTipoDocente.style.display = 'block';
            tipoDocenteSelect.required = true;
        }
        
        if (tipoInicial === 'DIRECTIVO' || tipoInicial === 'AUXILIAR') {
            departamentoSelect.value = 'Departamento ITSI';
            departamentoSelect.disabled = true;
        }
    }
});
</script>
<?= $this->endSection() ?>
