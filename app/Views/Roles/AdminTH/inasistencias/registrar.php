<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>

<div class="page-wrapper">
    <div class="page-content">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <h4 class="page-title mb-0">Registrar Nueva Inasistencia</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="<?= base_url('admin-th/dashboard') ?>">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="<?= base_url('admin-th/inasistencias') ?>">Inasistencias</a></li>
                            <li class="breadcrumb-item active">Registrar</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-plus-circle text-primary me-2"></i>
                            Información de la Inasistencia
                        </h5>
                    </div>
                    <div class="card-body">
                        <form id="formInasistencia" method="POST" action="<?= base_url('admin-th/inasistencias/registrar') ?>">
                            <?= csrf_field() ?>
                            
                            <!-- Selección de Empleado -->
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="empleado_id" class="form-label">Empleado <span class="text-danger">*</span></label>
                                    <select class="form-select" id="empleado_id" name="empleado_id" required>
                                        <option value="">Seleccionar empleado</option>
                                        <?php if (isset($empleados) && !empty($empleados)): ?>
                                            <?php foreach ($empleados as $empleado): ?>
                                                <option value="<?= $empleado['id_empleado'] ?>" 
                                                        data-tipo="<?= $empleado['tipo_empleado'] ?>"
                                                        data-departamento="<?= $empleado['departamento'] ?>">
                                                    <?= $empleado['nombres'] . ' ' . $empleado['apellidos'] ?> 
                                                    (<?= $empleado['tipo_empleado'] ?> - <?= $empleado['departamento'] ?>)
                                                </option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="tipo_inasistencia_id" class="form-label">Tipo de Inasistencia <span class="text-danger">*</span></label>
                                    <select class="form-select" id="tipo_inasistencia_id" name="tipo_inasistencia_id" required>
                                        <option value="">Seleccionar tipo</option>
                                        <?php if (isset($tipos_inasistencia) && !empty($tipos_inasistencia)): ?>
                                            <?php foreach ($tipos_inasistencia as $tipo): ?>
                                                <option value="<?= $tipo['id'] ?>" 
                                                        data-requiere-justificacion="<?= $tipo['requiere_justificacion'] ?>"
                                                        data-descripcion="<?= htmlspecialchars($tipo['descripcion']) ?>">
                                                    <?= $tipo['nombre'] ?>
                                                </option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                </div>
                            </div>

                            <!-- Fecha y Duración -->
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="fecha" class="form-label">Fecha de Inasistencia <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="fecha" name="fecha" 
                                           value="<?= date('Y-m-d') ?>" max="<?= date('Y-m-d') ?>" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="duracion" class="form-label">Duración <span class="text-danger">*</span></label>
                                    <select class="form-select" id="duracion" name="duracion" required>
                                        <option value="1">1 día</option>
                                        <option value="2">2 días</option>
                                        <option value="3">3 días</option>
                                        <option value="4">4 días</option>
                                        <option value="5">5 días</option>
                                        <option value="6">6 días</option>
                                        <option value="7">7 días</option>
                                        <option value="8">8 días</option>
                                        <option value="9">9 días</option>
                                        <option value="10">10 días</option>
                                        <option value="11">11 días</option>
                                        <option value="12">12 días</option>
                                        <option value="13">13 días</option>
                                        <option value="14">14 días</option>
                                        <option value="15">15 días</option>
                                        <option value="16">16 días</option>
                                        <option value="17">17 días</option>
                                        <option value="18">18 días</option>
                                        <option value="19">19 días</option>
                                        <option value="20">20 días</option>
                                        <option value="21">21 días</option>
                                        <option value="22">22 días</option>
                                        <option value="23">23 días</option>
                                        <option value="24">24 días</option>
                                        <option value="25">25 días</option>
                                        <option value="26">26 días</option>
                                        <option value="27">27 días</option>
                                        <option value="28">28 días</option>
                                        <option value="29">29 días</option>
                                        <option value="30">30 días</option>
                                        <option value="31">31 días</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Motivo y Observaciones -->
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <label for="motivo" class="form-label">Motivo de la Inasistencia <span class="text-danger">*</span></label>
                                    <textarea class="form-control" id="motivo" name="motivo" rows="3" 
                                              placeholder="Describa el motivo de la inasistencia..." required></textarea>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <label for="observaciones" class="form-label">Observaciones Adicionales</label>
                                    <textarea class="form-control" id="observaciones" name="observaciones" rows="2" 
                                              placeholder="Observaciones adicionales (opcional)..."></textarea>
                                </div>
                            </div>

                            <!-- Estado Inicial -->
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="estado" class="form-label">Estado Inicial <span class="text-danger">*</span></label>
                                    <select class="form-select" id="estado" name="estado" required>
                                        <option value="PENDIENTE">Pendiente de Justificación</option>
                                        <option value="SIN_JUSTIFICAR">Sin Justificar</option>
                                        <option value="JUSTIFICADA">Justificada</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="prioridad" class="form-label">Prioridad</label>
                                    <select class="form-select" id="prioridad" name="prioridad">
                                        <option value="BAJA">Baja</option>
                                        <option value="MEDIA" selected>Media</option>
                                        <option value="ALTA">Alta</option>
                                        <option value="CRITICA">Crítica</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Campos Condicionales -->
                            <div id="camposJustificacion" style="display: none;">
                                <div class="alert alert-info">
                                    <i class="bi bi-info-circle me-2"></i>
                                    <strong>Información:</strong> Este tipo de inasistencia requiere justificación según la política establecida.
                                </div>
                                
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="fecha_limite_justificacion" class="form-label">Fecha Límite para Justificación</label>
                                        <input type="date" class="form-control" id="fecha_limite_justificacion" 
                                               name="fecha_limite_justificacion" 
                                               value="<?= date('Y-m-d', strtotime('+7 days')) ?>">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="recordatorio_justificacion" class="form-label">Recordatorio Automático</label>
                                        <select class="form-select" id="recordatorio_justificacion" name="recordatorio_justificacion">
                                            <option value="1">Sí, enviar recordatorio</option>
                                            <option value="0">No enviar recordatorio</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Botones de Acción -->
                            <div class="row">
                                <div class="col-12">
                                    <div class="d-flex justify-content-between">
                                        <a href="<?= base_url('admin-th/inasistencias') ?>" class="btn btn-secondary">
                                            <i class="bi bi-arrow-left me-2"></i>Cancelar
                                        </a>
                                        <div>
                                            <button type="button" class="btn btn-outline-info me-2" onclick="guardarBorrador()">
                                                <i class="bi bi-save me-2"></i>Guardar Borrador
                                            </button>
                                            <button type="submit" class="btn btn-primary">
                                                <i class="bi bi-check-circle me-2"></i>Registrar Inasistencia
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Panel Lateral -->
            <div class="col-lg-4">
                <!-- Información del Empleado Seleccionado -->
                <div class="card" id="infoEmpleado" style="display: none;">
                    <div class="card-header">
                        <h6 class="card-title mb-0">
                            <i class="bi bi-person text-info me-2"></i>
                            Información del Empleado
                        </h6>
                    </div>
                    <div class="card-body">
                        <div id="contenidoInfoEmpleado">
                            <!-- Se llena dinámicamente -->
                        </div>
                    </div>
                </div>

                <!-- Información del Tipo de Inasistencia -->
                <div class="card" id="infoTipo" style="display: none;">
                    <div class="card-header">
                        <h6 class="card-title mb-0">
                            <i class="bi bi-info-circle text-success me-2"></i>
                            Información del Tipo
                        </h6>
                    </div>
                    <div class="card-body">
                        <div id="contenidoInfoTipo">
                            <!-- Se llena dinámicamente -->
                        </div>
                    </div>
                </div>

                <!-- Historial de Inasistencias del Empleado -->
                <div class="card" id="historialEmpleado" style="display: none;">
                    <div class="card-header">
                        <h6 class="card-title mb-0">
                            <i class="bi bi-clock-history text-warning me-2"></i>
                            Historial del Empleado
                        </h6>
                    </div>
                    <div class="card-body">
                        <div id="contenidoHistorial">
                            <!-- Se llena dinámicamente -->
                        </div>
                    </div>
                </div>

                <!-- Políticas Aplicables -->
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title mb-0">
                            <i class="bi bi-shield-check text-primary me-2"></i>
                            Políticas Aplicables
                        </h6>
                    </div>
                    <div class="card-body">
                        <div id="contenidoPoliticas">
                            <!-- Se llena dinámicamente -->
                        </div>
                    </div>
                </div>

                <!-- Validaciones y Alertas -->
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title mb-0">
                            <i class="bi bi-exclamation-triangle text-warning me-2"></i>
                            Validaciones
                        </h6>
                    </div>
                    <div class="card-body">
                        <div id="contenidoValidaciones">
                            <!-- Se llena dinámicamente -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Cambio de empleado
    document.getElementById('empleado_id').addEventListener('change', function() {
        const empleadoId = this.value;
        if (empleadoId) {
            cargarInfoEmpleado(empleadoId);
            cargarHistorialEmpleado(empleadoId);
            cargarPoliticasEmpleado(empleadoId);
        } else {
            ocultarInfoEmpleado();
        }
    });

    // Cambio de tipo de inasistencia
    document.getElementById('tipo_inasistencia_id').addEventListener('change', function() {
        const tipoId = this.value;
        if (tipoId) {
            cargarInfoTipo(tipoId);
            mostrarCamposJustificacion();
        } else {
            ocultarInfoTipo();
        }
    });

    // Cambio de fecha
    document.getElementById('fecha').addEventListener('change', function() {
        validarFecha();
    });

    // Cambio de duración
    document.getElementById('duracion').addEventListener('change', function() {
        validarDuracion();
    });

    // Formulario submit
    document.getElementById('formInasistencia').addEventListener('submit', function(e) {
        if (!validarFormulario()) {
            e.preventDefault();
        }
    });

    // Cargar políticas iniciales
    cargarPoliticasGenerales();
});

function cargarInfoEmpleado(empleadoId) {
    const select = document.getElementById('empleado_id');
    const option = select.options[select.selectedIndex];
    const tipo = option.getAttribute('data-tipo');
    const departamento = option.getAttribute('data-departamento');
    
    document.getElementById('contenidoInfoEmpleado').innerHTML = `
        <div class="mb-3">
            <label class="form-label fw-bold">Tipo de Empleado:</label>
            <p class="form-control-plaintext">
                <span class="badge bg-info">${tipo}</span>
            </p>
        </div>
        <div class="mb-3">
            <label class="form-label fw-bold">Departamento:</label>
            <p class="form-control-plaintext">
                <span class="badge bg-secondary">${departamento}</span>
            </p>
        </div>
        <div class="mb-3">
            <label class="form-label fw-bold">Estado:</label>
            <p class="form-control-plaintext">
                <span class="badge bg-success">Activo</span>
            </p>
        </div>
    `;
    
    document.getElementById('infoEmpleado').style.display = 'block';
}

function ocultarInfoEmpleado() {
    document.getElementById('infoEmpleado').style.display = 'none';
    document.getElementById('historialEmpleado').style.display = 'none';
}

function cargarInfoTipo(tipoId) {
    const select = document.getElementById('tipo_inasistencia_id');
    const option = select.options[select.selectedIndex];
    const requiereJustificacion = option.getAttribute('data-requiere-justificacion');
    const descripcion = option.getAttribute('data-descripcion');
    
    document.getElementById('contenidoInfoTipo').innerHTML = `
        <div class="mb-3">
            <label class="form-label fw-bold">Requiere Justificación:</label>
            <p class="form-control-plaintext">
                <span class="badge bg-${requiereJustificacion === '1' ? 'warning' : 'success'}">
                    ${requiereJustificacion === '1' ? 'Sí' : 'No'}
                </span>
            </p>
        </div>
        <div class="mb-3">
            <label class="form-label fw-bold">Descripción:</label>
            <p class="form-control-plaintext small">
                ${descripcion || 'No disponible'}
            </p>
        </div>
    `;
    
    document.getElementById('infoTipo').style.display = 'block';
}

function ocultarInfoTipo() {
    document.getElementById('infoTipo').style.display = 'none';
}

function mostrarCamposJustificacion() {
    const select = document.getElementById('tipo_inasistencia_id');
    const option = select.options[select.selectedIndex];
    const requiereJustificacion = option.getAttribute('data-requiere-justificacion');
    
    if (requiereJustificacion === '1') {
        document.getElementById('camposJustificacion').style.display = 'block';
    } else {
        document.getElementById('camposJustificacion').style.display = 'none';
    }
}

function cargarHistorialEmpleado(empleadoId) {
    // Simular carga de historial
    document.getElementById('contenidoHistorial').innerHTML = `
        <div class="text-center">
            <div class="spinner-border spinner-border-sm text-primary" role="status">
                <span class="visually-hidden">Cargando...</span>
            </div>
            <p class="mt-2 small text-muted">Cargando historial...</p>
        </div>
    `;
    
    document.getElementById('historialEmpleado').style.display = 'block';
    
    // Aquí se haría la llamada AJAX real
    setTimeout(() => {
        document.getElementById('contenidoHistorial').innerHTML = `
            <div class="mb-3">
                <label class="form-label fw-bold">Este Mes:</label>
                <p class="form-control-plaintext">0 inasistencias</p>
            </div>
            <div class="mb-3">
                <label class="form-label fw-bold">Este Año:</label>
                <p class="form-control-plaintext">2 inasistencias</p>
            </div>
            <div class="mb-3">
                <label class="form-label fw-bold">Última:</label>
                <p class="form-control-plaintext">Hace 15 días</p>
            </div>
        `;
    }, 1000);
}

function cargarPoliticasEmpleado(empleadoId) {
    // Aquí se cargarían las políticas específicas del empleado
    document.getElementById('contenidoPoliticas').innerHTML = `
        <div class="mb-3">
            <label class="form-label fw-bold">Límite Anual:</label>
            <p class="form-control-plaintext">15 días</p>
        </div>
        <div class="mb-3">
            <label class="form-label fw-bold">Límite Consecutivas:</label>
            <p class="form-control-plaintext">3 días</p>
        </div>
        <div class="mb-3">
            <label class="form-label fw-bold">Días Restantes:</label>
            <p class="form-control-plaintext text-success">13 días</p>
        </div>
    `;
}

function cargarPoliticasGenerales() {
    document.getElementById('contenidoPoliticas').innerHTML = `
        <div class="mb-3">
            <label class="form-label fw-bold">Política General:</label>
            <p class="form-control-plaintext small">
                Seleccione un empleado para ver las políticas específicas aplicables.
            </p>
        </div>
    `;
}

function validarFecha() {
    const fecha = document.getElementById('fecha').value;
    const hoy = new Date().toISOString().split('T')[0];
    
    if (fecha > hoy) {
        alert('La fecha de inasistencia no puede ser futura');
        document.getElementById('fecha').value = hoy;
    }
}

function validarDuracion() {
    const duracion = parseInt(document.getElementById('duracion').value);
    const empleadoId = document.getElementById('empleado_id').value;
    
    if (empleadoId && duracion > 30) {
        alert('La duración máxima permitida es de 30 días');
        document.getElementById('duracion').value = '30';
    }
}

function validarFormulario() {
    const empleadoId = document.getElementById('empleado_id').value;
    const tipoId = document.getElementById('tipo_inasistencia_id').value;
    const fecha = document.getElementById('fecha').value;
    const motivo = document.getElementById('motivo').value.trim();
    
    if (!empleadoId) {
        alert('Debe seleccionar un empleado');
        return false;
    }
    
    if (!tipoId) {
        alert('Debe seleccionar un tipo de inasistencia');
        return false;
    }
    
    if (!fecha) {
        alert('Debe especificar la fecha de inasistencia');
        return false;
    }
    
    if (!motivo) {
        alert('Debe especificar el motivo de la inasistencia');
        return false;
    }
    
    return true;
}

function guardarBorrador() {
    if (validarFormulario()) {
        // Cambiar el formulario para guardar como borrador
        const form = document.getElementById('formInasistencia');
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'es_borrador';
        input.value = '1';
        form.appendChild(input);
        
        // Cambiar el botón de submit
        const submitBtn = form.querySelector('button[type="submit"]');
        submitBtn.innerHTML = '<i class="bi bi-save me-2"></i>Guardar Borrador';
        submitBtn.className = 'btn btn-outline-info';
        
        // Enviar formulario
        form.submit();
    }
}
</script>

<style>
.card {
    margin-bottom: 1.5rem;
}

.form-label {
    font-weight: 600;
    color: #495057;
}

.form-control, .form-select {
    border-radius: 0.5rem;
}

.form-control:focus, .form-select:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

.btn {
    border-radius: 0.5rem;
    padding: 0.5rem 1rem;
}

.alert {
    border-radius: 0.5rem;
    border: none;
}

.badge {
    font-size: 0.875em;
}

.card-header {
    background-color: #f8f9fa;
    border-bottom: 1px solid #dee2e6;
}

.form-control-plaintext {
    padding: 0.375rem 0;
    margin-bottom: 0;
    color: #495057;
    background-color: transparent;
    border: solid transparent;
    border-width: 1px 0;
}
</style>

<?= $this->endSection() ?>
