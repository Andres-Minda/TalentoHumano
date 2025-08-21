<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>
<div class="page-wrapper">
    <div class="page-content">
        <!-- Breadcrumb -->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Empleados</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="<?= site_url('admin-th/dashboard') ?>">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Gestión de Empleados</li>
                    </ol>
                </nav>
            </div>
        </div>

        <!-- Page Header -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Gestión de Empleados</h4>
                        <div class="card-actions">
                            <button type="button" class="btn btn-primary" onclick="nuevoEmpleado()">
                                <i class="ti ti-plus"></i> Nuevo Empleado
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped" id="tablaEmpleados">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nombres</th>
                                        <th>Apellidos</th>
                                        <th>Cédula</th>
                                        <th>Tipo</th>
                                        <th>Departamento</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody id="tbodyEmpleados">
                                    <!-- Los datos se cargarán dinámicamente -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para nuevo/editar empleado -->
<div class="modal fade" id="modalEmpleado" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary text-white border-0">
                <h5 class="modal-title" id="modalTitle">
                    <i class="ti ti-user-plus me-2"></i>
                    Nuevo Empleado
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body py-4">
                <form id="formEmpleado" action="<?= site_url('admin-th/empleados/guardar') ?>" method="POST">
                    <input type="hidden" id="id_empleado" name="id_empleado">
                    
                    <!-- Información Personal -->
                    <div class="card border-primary mb-3">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">
                                <i class="ti ti-user text-primary me-2"></i>
                                Información Personal
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="nombres" class="form-label fw-bold">Nombres *</label>
                                        <input type="text" class="form-control form-control-lg" id="nombres" name="nombres" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="apellidos" class="form-label fw-bold">Apellidos *</label>
                                        <input type="text" class="form-control form-control-lg" id="apellidos" name="apellidos" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="cedula" class="form-label fw-bold">Cédula *</label>
                                        <input type="text" class="form-control form-control-lg" id="cedula" name="cedula" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="tipo_empleado" class="form-label fw-bold">Tipo de Empleado *</label>
                                        <select class="form-select form-select-lg" id="tipo_empleado" name="tipo_empleado" required onchange="toggleTipoPersonalizado()">
                                            <option value="">Seleccionar...</option>
                                            <option value="DOCENTE">Docente</option>
                                            <option value="ADMINISTRATIVO">Administrativo</option>
                                            <option value="DIRECTIVO">Directivo</option>
                                            <option value="AUXILIAR">Auxiliar</option>
                                            <option value="OTRO">Otro</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row" id="rowTipoPersonalizado" style="display: none;">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="tipo_empleado_personalizado" class="form-label fw-bold">Especificar Tipo Personalizado *</label>
                                        <input type="text" class="form-control form-control-lg" id="tipo_empleado_personalizado" name="tipo_empleado_personalizado" placeholder="Ej: Coordinador, Supervisor, Especialista...">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Información Laboral -->
                    <div class="card border-info mb-3">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">
                                <i class="ti ti-briefcase text-info me-2"></i>
                                Información Laboral
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="departamento" class="form-label fw-bold">Departamento *</label>
                                <select class="form-select form-select-lg" id="departamento" name="departamento" required>
                                    <option value="">Seleccionar departamento...</option>
                                    <!-- Los departamentos se cargarán dinámicamente -->
                                </select>
                            </div>
                        </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="fecha_ingreso" class="form-label fw-bold">Fecha de Ingreso *</label>
                                        <input type="date" class="form-control form-control-lg" id="fecha_ingreso" name="fecha_ingreso" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="salario" class="form-label fw-bold">Salario</label>
                                        <div class="input-group input-group-lg">
                                            <span class="input-group-text">$</span>
                                            <input type="number" class="form-control" id="salario" name="salario" step="0.01" placeholder="0.00">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Estado</label>
                                        <div class="btn-group w-100" role="group">
                                            <input type="radio" class="btn-check" name="estado" id="estado_activo" value="ACTIVO" checked>
                                            <label class="btn btn-outline-success btn-lg" for="estado_activo">
                                                <i class="ti ti-check me-1"></i> Activo
                                            </label>
                                            
                                            <input type="radio" class="btn-check" name="estado" id="estado_inactivo" value="INACTIVO">
                                            <label class="btn btn-outline-danger btn-lg" for="estado_inactivo">
                                                <i class="ti ti-x me-1"></i> Inactivo
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer bg-light border-0">
                <button type="button" class="btn btn-secondary btn-lg" data-bs-dismiss="modal">
                    <i class="ti ti-x me-1"></i> Cancelar
                </button>
                <button type="button" class="btn btn-primary btn-lg" onclick="guardarEmpleado()">
                    <i class="ti ti-device-floppy me-1"></i> Guardar
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para ver credenciales -->
<div class="modal fade" id="modalCredenciales" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-info text-white border-0">
                <h5 class="modal-title">
                    <i class="ti ti-key me-2"></i>
                    Credenciales de Acceso
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body py-4">
                <div class="text-center mb-4">
                    <i class="ti ti-user-check text-info" style="font-size: 3rem;"></i>
                </div>
                
                <div class="alert alert-info border-0">
                    <i class="ti ti-info-circle me-2"></i>
                    <span id="mensajeCredenciales"></span>
                </div>
                
                <div class="card border-info">
                    <div class="card-header bg-info text-white">
                        <h6 class="mb-0">
                            <i class="ti ti-credentials me-2"></i>
                            Datos de Acceso
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold text-info">Email:</label>
                                    <div class="input-group input-group-lg">
                                        <input type="text" class="form-control" id="emailCredenciales" readonly>
                                        <button class="btn btn-outline-info" type="button" onclick="copiarAlPortapapeles('emailCredenciales')">
                                            <i class="ti ti-copy"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold text-info">Contraseña:</label>
                                    <div class="input-group input-group-lg">
                                        <input type="text" class="form-control" id="passwordCredenciales" readonly>
                                        <button class="btn btn-outline-info" type="button" onclick="copiarAlPortapapeles('passwordCredenciales')">
                                            <i class="ti ti-copy"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light border-0 justify-content-center">
                <button type="button" class="btn btn-info btn-lg" data-bs-dismiss="modal">
                    <i class="ti ti-check me-1"></i> Entendido
                </button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    cargarEmpleados();
});

function cargarEmpleados() {
    // Mostrar indicador de carga
    const tbody = document.getElementById('tbodyEmpleados');
    tbody.innerHTML = '<tr><td colspan="8" class="text-center"><i class="ti ti-loader ti-spin"></i> Cargando empleados...</td></tr>';
    
    // Cargar empleados desde la base de datos
    fetch('<?= site_url('admin-th/empleados/obtener') ?>')
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const empleados = data.empleados;
            
            if (empleados.length === 0) {
                tbody.innerHTML = '<tr><td colspan="8" class="text-center text-muted">No hay empleados registrados</td></tr>';
                return;
            }
            
            tbody.innerHTML = '';
            
            empleados.forEach(empleado => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${empleado.id_empleado || empleado.id}</td>
                    <td>${empleado.nombres || ''}</td>
                    <td>${empleado.apellidos || ''}</td>
                    <td>${empleado.cedula || ''}</td>
                    <td><span class="badge bg-primary">${empleado.tipo_empleado || ''}</span></td>
                    <td>${empleado.departamento || ''}</td>
                    <td><span class="badge bg-success">${empleado.estado || 'Activo'}</span></td>
                    <td>
                        <button class="btn btn-sm btn-outline-primary" onclick="editarEmpleado(${empleado.id_empleado || empleado.id})" title="Editar">
                            <i class="ti ti-edit"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-info" onclick="verCredenciales(${empleado.id_empleado || empleado.id})" title="Ver Credenciales">
                            <i class="ti ti-key"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-danger" onclick="eliminarEmpleado(${empleado.id_empleado || empleado.id})" title="Eliminar">
                            <i class="ti ti-trash"></i>
                        </button>
                    </td>
                `;
                tbody.appendChild(row);
            });
        } else {
            tbody.innerHTML = '<tr><td colspan="8" class="text-center text-danger">Error: ' + (data.message || 'No se pudieron cargar los empleados') + '</td></tr>';
        }
    })
    .catch(error => {
        console.error('Error al cargar empleados:', error);
        // En caso de error, mostrar mensaje
        tbody.innerHTML = '<tr><td colspan="8" class="text-center text-danger">Error de conexión al cargar empleados</td></tr>';
    });
}

function nuevoEmpleado() {
    document.getElementById('modalTitle').textContent = 'Nuevo Empleado';
    document.getElementById('formEmpleado').reset();
    document.getElementById('id_empleado').value = '';
    document.getElementById('rowTipoPersonalizado').style.display = 'none';
    
    // Cargar departamentos en el dropdown
    cargarDepartamentosDropdown();
    
    const modal = new bootstrap.Modal(document.getElementById('modalEmpleado'));
    modal.show();
}

function toggleTipoPersonalizado() {
    const tipoEmpleado = document.getElementById('tipo_empleado').value;
    const rowTipoPersonalizado = document.getElementById('rowTipoPersonalizado');
    const tipoPersonalizadoInput = document.getElementById('tipo_empleado_personalizado');
    
    if (tipoEmpleado === 'OTRO') {
        rowTipoPersonalizado.style.display = 'flex';
        tipoPersonalizadoInput.required = true;
        tipoPersonalizadoInput.focus();
    } else {
        rowTipoPersonalizado.style.display = 'none';
        tipoPersonalizadoInput.required = false;
        tipoPersonalizadoInput.value = '';
    }
}

function editarEmpleado(id) {
    document.getElementById('modalTitle').textContent = 'Editar Empleado';
    
    // Mostrar indicador de carga
    const btnGuardar = document.querySelector('#modalEmpleado .btn-primary');
    const textoOriginal = btnGuardar.innerHTML;
    btnGuardar.innerHTML = '<i class="ti ti-loader ti-spin"></i> Cargando...';
    btnGuardar.disabled = true;
    
    // Cargar departamentos en el dropdown primero
    cargarDepartamentosDropdown().then(() => {
        // Cargar datos del empleado
        fetch(`<?= site_url('admin-th/empleados/obtener/') ?>${id}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const empleado = data.empleado;
                
                // Llenar formulario con datos existentes
                document.getElementById('id_empleado').value = empleado.id_empleado;
                document.getElementById('nombres').value = empleado.nombres || '';
                document.getElementById('apellidos').value = empleado.apellidos || '';
                document.getElementById('cedula').value = empleado.cedula || '';
                document.getElementById('departamento').value = empleado.departamento || '';
                document.getElementById('fecha_ingreso').value = empleado.fecha_ingreso || '';
                document.getElementById('salario').value = empleado.salario || '';
                
                // Configurar tipo de empleado
                if (empleado.tipo_empleado && empleado.tipo_empleado !== 'DOCENTE' && 
                    empleado.tipo_empleado !== 'ADMINISTRATIVO' && 
                    empleado.tipo_empleado !== 'DIRECTIVO' && 
                    empleado.tipo_empleado !== 'AUXILIAR') {
                    // Es un tipo personalizado
                    document.getElementById('tipo_empleado').value = 'OTRO';
                    document.getElementById('tipo_empleado_personalizado').value = empleado.tipo_empleado;
                    document.getElementById('rowTipoPersonalizado').style.display = 'flex';
                } else {
                    document.getElementById('tipo_empleado').value = empleado.tipo_empleado || '';
                    document.getElementById('rowTipoPersonalizado').style.display = 'none';
                }
                
                // Configurar estado
                if (empleado.estado === 'ACTIVO') {
                    document.getElementById('estado_activo').checked = true;
                } else {
                    document.getElementById('estado_inactivo').checked = true;
                }
                
                // Mostrar modal
                const modal = new bootstrap.Modal(document.getElementById('modalEmpleado'));
                modal.show();
                
            } else {
                mostrarAlerta('error', data.message || 'No se pudo cargar el empleado');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            mostrarAlerta('error', 'Error de conexión. Por favor, intente nuevamente.');
        })
        .finally(() => {
            // Restaurar botón
            btnGuardar.innerHTML = textoOriginal;
            btnGuardar.disabled = false;
        });
    });
}

function verCredenciales(id) {
    // Mostrar indicador de carga
    document.getElementById('mensajeCredenciales').innerHTML = '<i class="ti ti-loader ti-spin"></i> Cargando credenciales...';
    
    // Cargar credenciales del empleado
    fetch(`<?= site_url('admin-th/empleados/credenciales/') ?>${id}`)
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const credenciales = data.credenciales;
            
            // Llenar modal con credenciales
            document.getElementById('emailCredenciales').value = credenciales.email;
            document.getElementById('passwordCredenciales').value = credenciales.password;
            document.getElementById('mensajeCredenciales').innerHTML = credenciales.mensaje;
            
            // Mostrar modal
            const modal = new bootstrap.Modal(document.getElementById('modalCredenciales'));
            modal.show();
            
        } else {
            alert('❌ Error: ' + (data.message || 'No se pudieron cargar las credenciales'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('❌ Error de conexión. Por favor, intente nuevamente.');
    });
}

function copiarAlPortapapeles(elementId) {
    const elemento = document.getElementById(elementId);
    elemento.select();
    elemento.setSelectionRange(0, 99999); // Para dispositivos móviles
    
    try {
        document.execCommand('copy');
        // Mostrar notificación temporal
        const btn = elemento.nextElementSibling;
        const textoOriginal = btn.innerHTML;
        btn.innerHTML = '<i class="ti ti-check"></i>';
        btn.classList.remove('btn-outline-secondary');
        btn.classList.add('btn-success');
        
        setTimeout(() => {
            btn.innerHTML = textoOriginal;
            btn.classList.remove('btn-success');
            btn.classList.add('btn-outline-secondary');
        }, 2000);
        
    } catch (err) {
        console.error('Error al copiar:', err);
        alert('Error al copiar al portapapeles');
    }
}

function guardarEmpleado() {
    const tipoEmpleado = document.getElementById('tipo_empleado').value;
    const tipoPersonalizado = document.getElementById('tipo_empleado_personalizado').value;
    
    // Validar que si se selecciona "Otro", se especifique el tipo personalizado
    if (tipoEmpleado === 'OTRO' && !tipoPersonalizado.trim()) {
        mostrarAlerta('warning', 'Por favor, especifique el tipo de empleado personalizado.');
        document.getElementById('tipo_empleado_personalizado').focus();
        return;
    }
    
    // Validar campos requeridos
    const nombres = document.getElementById('nombres').value.trim();
    const apellidos = document.getElementById('apellidos').value.trim();
    const cedula = document.getElementById('cedula').value.trim();
    const departamento = document.getElementById('departamento').value.trim();
    const fechaIngreso = document.getElementById('fecha_ingreso').value;
    
    if (!nombres || !apellidos || !cedula || !departamento || !fechaIngreso) {
        mostrarAlerta('warning', 'Por favor, complete todos los campos obligatorios.');
        return;
    }
    
    // Mostrar indicador de carga
    const btnGuardar = document.querySelector('#modalEmpleado .btn-primary');
    const textoOriginal = btnGuardar.innerHTML;
    btnGuardar.innerHTML = '<i class="ti ti-loader ti-spin"></i> Guardando...';
    btnGuardar.disabled = true;
    
    // Crear FormData con los datos del formulario
    const formData = new FormData(document.getElementById('formEmpleado'));
    
    // Enviar datos via AJAX
    fetch('<?= site_url('admin-th/empleados/guardar') ?>', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            if (data.tipo === 'creacion') {
                // Mostrar modal de éxito con credenciales
                mostrarModalExito(data.message, data.credenciales);
            } else {
                // Mostrar alerta de éxito para actualización
                mostrarAlerta('success', data.message);
            }
            
            // Cerrar modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('modalEmpleado'));
            modal.hide();
            
            // Recargar lista de empleados
            cargarEmpleados();
            
            // Limpiar formulario
            document.getElementById('formEmpleado').reset();
            document.getElementById('rowTipoPersonalizado').style.display = 'none';
            
        } else {
            mostrarAlerta('error', data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        mostrarAlerta('error', 'Error de conexión. Por favor, intente nuevamente.');
    })
    .finally(() => {
        // Restaurar botón
        btnGuardar.innerHTML = textoOriginal;
        btnGuardar.disabled = false;
    });
}

function eliminarEmpleado(id) {
    mostrarConfirmacion(
        '¿Estás seguro de que quieres eliminar este empleado?',
        'Esta acción no se puede deshacer.',
        'Eliminar Empleado',
        'Eliminar',
        'btn-danger',
        () => {
            // Aquí implementarías la lógica para eliminar
            mostrarAlerta('info', 'Funcionalidad de eliminación en desarrollo');
        }
    );
}

// Función para mostrar alertas personalizadas
function mostrarAlerta(tipo, mensaje) {
    const alertaDiv = document.createElement('div');
    alertaDiv.className = `alert alert-${tipo === 'error' ? 'danger' : tipo} alert-dismissible fade show position-fixed`;
    alertaDiv.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px; box-shadow: 0 4px 12px rgba(0,0,0,0.15);';
    
    const iconos = {
        'success': 'ti ti-check-circle',
        'warning': 'ti ti-alert-triangle',
        'error': 'ti ti-x-circle',
        'info': 'ti ti-info-circle'
    };
    
    alertaDiv.innerHTML = `
        <div class="d-flex align-items-center">
            <i class="${iconos[tipo] || 'ti ti-info-circle'} me-2 fs-4"></i>
            <div class="flex-grow-1">
                <strong>${tipo === 'success' ? 'Éxito' : tipo === 'warning' ? 'Advertencia' : tipo === 'error' ? 'Error' : 'Información'}</strong>
                <br>${mensaje}
            </div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    document.body.appendChild(alertaDiv);
    
    // Auto-ocultar después de 5 segundos
    setTimeout(() => {
        if (alertaDiv.parentNode) {
            alertaDiv.remove();
        }
    }, 5000);
}

// Función para mostrar confirmaciones personalizadas
function mostrarConfirmacion(titulo, mensaje, tituloModal, textoBoton, claseBoton, callback) {
    const modalHtml = `
        <div class="modal fade" id="modalConfirmacion" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow">
                    <div class="modal-header bg-light border-0">
                        <h5 class="modal-title">
                            <i class="ti ti-alert-triangle text-warning me-2"></i>
                            ${tituloModal}
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body text-center py-4">
                        <div class="mb-3">
                            <i class="ti ti-help-circle text-primary" style="font-size: 3rem;"></i>
                        </div>
                        <h6 class="mb-2">${titulo}</h6>
                        <p class="text-muted mb-0">${mensaje}</p>
                    </div>
                    <div class="modal-footer border-0 justify-content-center">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="ti ti-x me-1"></i> Cancelar
                        </button>
                        <button type="button" class="btn ${claseBoton}" onclick="ejecutarConfirmacion()">
                            <i class="ti ti-check me-1"></i> ${textoBoton}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    // Remover modal anterior si existe
    const modalAnterior = document.getElementById('modalConfirmacion');
    if (modalAnterior) {
        modalAnterior.remove();
    }
    
    // Agregar nuevo modal
    document.body.insertAdjacentHTML('beforeend', modalHtml);
    
    // Guardar callback en variable global
    window.confirmacionCallback = callback;
    
    // Mostrar modal
    const modal = new bootstrap.Modal(document.getElementById('modalConfirmacion'));
    modal.show();
}

// Función para ejecutar confirmación
function ejecutarConfirmacion() {
    if (window.confirmacionCallback) {
        window.confirmacionCallback();
        // Cerrar modal
        const modal = bootstrap.Modal.getInstance(document.getElementById('modalConfirmacion'));
        modal.hide();
        // Limpiar callback
        window.confirmacionCallback = null;
    }
}

// Función para mostrar modal de éxito con credenciales
function mostrarModalExito(mensaje, credenciales) {
    const modalHtml = `
        <div class="modal fade" id="modalExito" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow">
                    <div class="modal-header bg-success text-white border-0">
                        <h5 class="modal-title">
                            <i class="ti ti-check-circle me-2"></i>
                            Empleado Creado Exitosamente
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body py-4">
                        <div class="text-center mb-4">
                            <i class="ti ti-user-check text-success" style="font-size: 3rem;"></i>
                        </div>
                        <div class="alert alert-success">
                            <i class="ti ti-check me-2"></i>
                            ${mensaje}
                        </div>
                        
                        <div class="card border-success">
                            <div class="card-header bg-success text-white">
                                <h6 class="mb-0">
                                    <i class="ti ti-key me-2"></i>
                                    Credenciales de Acceso
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold text-success">Email:</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" id="emailExito" value="${credenciales.email}" readonly>
                                            <button class="btn btn-outline-success" type="button" onclick="copiarAlPortapapeles('emailExito')">
                                                <i class="ti ti-copy"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold text-success">Contraseña:</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" id="passwordExito" value="${credenciales.password}" readonly>
                                            <button class="btn btn-outline-success" type="button" onclick="copiarAlPortapapeles('passwordExito')">
                                                <i class="ti ti-copy"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="alert alert-warning mt-3">
                                    <i class="ti ti-alert-triangle me-2"></i>
                                    <strong>IMPORTANTE:</strong> El empleado debe cambiar su contraseña en el primer inicio de sesión.
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 justify-content-center">
                        <button type="button" class="btn btn-success" data-bs-dismiss="modal">
                            <i class="ti ti-check me-1"></i> Entendido
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    // Remover modal anterior si existe
    const modalAnterior = document.getElementById('modalExito');
    if (modalAnterior) {
        modalAnterior.remove();
    }
    
    // Agregar nuevo modal
    document.body.insertAdjacentHTML('beforeend', modalHtml);
    
    // Mostrar modal
    const modal = new bootstrap.Modal(document.getElementById('modalExito'));
    modal.show();
}

// Función para cargar departamentos en el dropdown
function cargarDepartamentosDropdown() {
    return new Promise((resolve, reject) => {
        const selectDepartamento = document.getElementById('departamento');
        
        // Limpiar opciones existentes
        selectDepartamento.innerHTML = '<option value="">Seleccionar departamento...</option>';
        
        // Cargar departamentos activos
        fetch('<?= site_url('admin-th/departamentos/activos') ?>')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const departamentos = data.departamentos;
                
                // Agregar opciones al select
                Object.entries(departamentos).forEach(([id, nombre]) => {
                    const option = document.createElement('option');
                    option.value = nombre; // Usar el nombre como valor para mantener compatibilidad
                    option.textContent = nombre;
                    selectDepartamento.appendChild(option);
                });
                
                resolve();
            } else {
                console.error('Error al cargar departamentos:', data.message);
                reject(new Error(data.message));
            }
        })
        .catch(error => {
            console.error('Error de conexión al cargar departamentos:', error);
            reject(error);
        });
    });
}
</script>
<?= $this->endSection() ?>
