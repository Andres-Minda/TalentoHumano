<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>
<div class="page-wrapper">
    <div class="page-content">
        <!-- Breadcrumb -->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Departamentos</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="<?= site_url('admin-th/dashboard') ?>">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Gestión de Departamentos</li>
                    </ol>
                </nav>
            </div>
        </div>

        <!-- Page Header -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Gestión de Departamentos</h4>
                        <div class="card-actions">
                            <button type="button" class="btn btn-primary" onclick="nuevoDepartamento()">
                                <i class="ti ti-plus"></i> Nuevo Departamento
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped" id="tablaDepartamentos">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nombre</th>
                                        <th>Descripción</th>
                                        <th>Responsable</th>
                                        <th>Contacto</th>
                                        <th>Ubicación</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody id="tbodyDepartamentos">
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

<!-- Sección de Gráficos -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">
                    <i class="ti ti-chart-pie me-2"></i>
                    Estadísticas de Departamentos
                </h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <!-- Gráfico: Departamentos por Estado -->
                    <div class="col-lg-6">
                        <div class="card border-warning">
                            <div class="card-header bg-warning text-white">
                                <h6 class="mb-0">
                                    <i class="ti ti-status-change me-2"></i>
                                    Departamentos por Estado
                                </h6>
                            </div>
                            <div class="card-body">
                                <canvas id="chartDepartamentosEstado" width="400" height="300"></canvas>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Gráfico: Empleados por Departamento -->
                    <div class="col-lg-6">
                        <div class="card border-primary">
                            <div class="card-header bg-primary text-white">
                                <h6 class="mb-0">
                                    <i class="ti ti-users me-2"></i>
                                    Empleados por Departamento
                                </h6>
                            </div>
                            <div class="card-body">
                                <canvas id="chartEmpleadosDepartamento" width="400" height="300"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para nuevo/editar departamento -->
<div class="modal fade" id="modalDepartamento" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-success text-white border-0">
                <h5 class="modal-title" id="modalTitle">
                    <i class="ti ti-building me-2"></i>
                    Nuevo Departamento
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body py-4">
                <form id="formDepartamento" action="<?= site_url('admin-th/departamentos/guardar') ?>" method="POST">
                    <input type="hidden" id="id_departamento" name="id_departamento">
                    
                    <!-- Información Básica -->
                    <div class="card border-success mb-3">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">
                                <i class="ti ti-info-circle text-success me-2"></i>
                                Información Básica
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="nombre" class="form-label fw-bold">Nombre del Departamento *</label>
                                        <input type="text" class="form-control form-control-lg" id="nombre" name="nombre" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="estado" class="form-label fw-bold">Estado</label>
                                        <select class="form-select form-select-lg" id="estado" name="estado" required>
                                            <option value="Activo">Activo</option>
                                            <option value="Inactivo">Inactivo</option>
                                            <option value="Suspendido">Suspendido</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label for="descripcion" class="form-label fw-bold">Descripción</label>
                                        <textarea class="form-control form-control-lg" id="descripcion" name="descripcion" rows="3" placeholder="Descripción del departamento, funciones principales, etc..."></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Información de Contacto -->
                    <div class="card border-info mb-3">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">
                                <i class="ti ti-phone text-info me-2"></i>
                                Información de Contacto
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="responsable" class="form-label fw-bold">Responsable</label>
                                        <input type="text" class="form-control form-control-lg" id="responsable" name="responsable" placeholder="Nombre del responsable del departamento">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="email_contacto" class="form-label fw-bold">Email de Contacto</label>
                                        <input type="email" class="form-control form-control-lg" id="email_contacto" name="email_contacto" placeholder="email@itsi.edu.ec">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="telefono" class="form-label fw-bold">Teléfono</label>
                                        <input type="text" class="form-control form-control-lg" id="telefono" name="telefono" placeholder="+593 XX XXX XXXX">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="ubicacion" class="form-label fw-bold">Ubicación</label>
                                        <input type="text" class="form-control form-control-lg" id="ubicacion" name="ubicacion" placeholder="Edificio, piso, oficina...">
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
                <button type="button" class="btn btn-success btn-lg" onclick="guardarDepartamento()">
                    <i class="ti ti-device-floppy me-1"></i> Guardar
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para ver detalles del departamento -->
<div class="modal fade" id="modalDetallesDepartamento" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-info text-white border-0">
                <h5 class="modal-title">
                    <i class="ti ti-eye me-2"></i>
                    Detalles del Departamento
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body py-4">
                <div id="detallesDepartamento">
                    <!-- Los detalles se cargarán dinámicamente -->
                </div>
            </div>
            <div class="modal-footer bg-light border-0 justify-content-center">
                <button type="button" class="btn btn-info btn-lg" data-bs-dismiss="modal">
                    <i class="ti ti-check me-1"></i> Cerrar
                </button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    cargarDepartamentos();
});

function cargarDepartamentos() {
    // Mostrar indicador de carga
    const tbody = document.getElementById('tbodyDepartamentos');
    tbody.innerHTML = '<tr><td colspan="8" class="text-center"><i class="ti ti-loader ti-spin"></i> Cargando departamentos...</td></tr>';
    
    // Cargar departamentos desde la base de datos
    fetch('<?= site_url('admin-th/departamentos/obtener') ?>')
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const departamentos = data.departamentos;
            
            if (departamentos.length === 0) {
                tbody.innerHTML = '<tr><td colspan="8" class="text-center text-muted">No hay departamentos registrados</td></tr>';
                return;
            }
            
            tbody.innerHTML = '';
            
            departamentos.forEach(departamento => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${departamento.id_departamento}</td>
                    <td><strong>${departamento.nombre}</strong></td>
                    <td>${departamento.descripcion || 'Sin descripción'}</td>
                    <td>${departamento.responsable || 'No asignado'}</td>
                    <td>
                        ${departamento.email_contacto ? `<div><i class="ti ti-mail text-primary"></i> ${departamento.email_contacto}</div>` : ''}
                        ${departamento.telefono ? `<div><i class="ti ti-phone text-success"></i> ${departamento.telefono}</div>` : ''}
                    </td>
                    <td>${departamento.ubicacion || 'No especificada'}</td>
                    <td>
                        <span class="badge bg-${departamento.estado === 'Activo' ? 'success' : departamento.estado === 'Inactivo' ? 'danger' : 'warning'}">
                            ${departamento.estado}
                        </span>
                    </td>
                    <td>
                        <button class="btn btn-sm btn-outline-info" onclick="verDetallesDepartamento(${departamento.id_departamento})" title="Ver Detalles">
                            <i class="ti ti-eye"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-primary" onclick="editarDepartamento(${departamento.id_departamento})" title="Editar">
                            <i class="ti ti-edit"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-danger" onclick="eliminarDepartamento(${departamento.id_departamento})" title="Eliminar">
                            <i class="ti ti-trash"></i>
                        </button>
                    </td>
                `;
                tbody.appendChild(row);
            });
        } else {
            tbody.innerHTML = '<tr><td colspan="8" class="text-center text-danger">Error: ' + (data.message || 'No se pudieron cargar los departamentos') + '</td></tr>';
        }
    })
    .catch(error => {
        console.error('Error al cargar departamentos:', error);
        tbody.innerHTML = '<tr><td colspan="8" class="text-center text-danger">Error de conexión al cargar departamentos</td></tr>';
    });
}

function nuevoDepartamento() {
    document.getElementById('modalTitle').textContent = 'Nuevo Departamento';
    document.getElementById('formDepartamento').reset();
    document.getElementById('id_departamento').value = '';
    const modal = new bootstrap.Modal(document.getElementById('modalDepartamento'));
    modal.show();
}

function editarDepartamento(id) {
    document.getElementById('modalTitle').textContent = 'Editar Departamento';
    
    // Mostrar indicador de carga
    const btnGuardar = document.querySelector('#modalDepartamento .btn-success');
    const textoOriginal = btnGuardar.innerHTML;
    btnGuardar.innerHTML = '<i class="ti ti-loader ti-spin"></i> Cargando...';
    btnGuardar.disabled = true;
    
    // Cargar datos del departamento
    fetch(`<?= site_url('admin-th/departamentos/obtener/') ?>${id}`)
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const departamento = data.departamento;
            
            // Llenar formulario con datos existentes
            document.getElementById('id_departamento').value = departamento.id_departamento;
            document.getElementById('nombre').value = departamento.nombre || '';
            document.getElementById('descripcion').value = departamento.descripcion || '';
            document.getElementById('responsable').value = departamento.responsable || '';
            document.getElementById('email_contacto').value = departamento.email_contacto || '';
            document.getElementById('telefono').value = departamento.telefono || '';
            document.getElementById('ubicacion').value = departamento.ubicacion || '';
            document.getElementById('estado').value = departamento.estado || 'Activo';
            
            // Mostrar modal
            const modal = new bootstrap.Modal(document.getElementById('modalDepartamento'));
            modal.show();
            
        } else {
            mostrarAlerta('error', data.message || 'No se pudo cargar el departamento');
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

function verDetallesDepartamento(id) {
    // Mostrar indicador de carga
    document.getElementById('detallesDepartamento').innerHTML = '<div class="text-center"><i class="ti ti-loader ti-spin fs-1"></i><p>Cargando detalles...</p></div>';
    
    // Cargar detalles del departamento
    fetch(`<?= site_url('admin-th/departamentos/obtener/') ?>${id}`)
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const departamento = data.departamento;
            
            // Crear HTML para los detalles
            const detallesHTML = `
                <div class="row">
                    <div class="col-12 text-center mb-4">
                        <i class="ti ti-building text-info" style="font-size: 3rem;"></i>
                        <h4 class="mt-2 text-info">${departamento.nombre}</h4>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="card border-info mb-3">
                            <div class="card-header bg-info text-white">
                                <h6 class="mb-0"><i class="ti ti-info-circle me-2"></i> Información General</h6>
                            </div>
                            <div class="card-body">
                                <p><strong>Descripción:</strong><br>${departamento.descripcion || 'Sin descripción'}</p>
                                <p><strong>Estado:</strong> 
                                    <span class="badge bg-${departamento.estado === 'Activo' ? 'success' : departamento.estado === 'Inactivo' ? 'danger' : 'warning'}">
                                        ${departamento.estado}
                                    </span>
                                </p>
                                <p><strong>Ubicación:</strong><br>${departamento.ubicacion || 'No especificada'}</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="card border-primary mb-3">
                            <div class="card-header bg-primary text-white">
                                <h6 class="mb-0"><i class="ti ti-phone me-2"></i> Información de Contacto</h6>
                            </div>
                            <div class="card-body">
                                <p><strong>Responsable:</strong><br>${departamento.responsable || 'No asignado'}</p>
                                <p><strong>Email:</strong><br>${departamento.email_contacto || 'No especificado'}</p>
                                <p><strong>Teléfono:</strong><br>${departamento.telefono || 'No especificado'}</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-12">
                        <div class="card border-success">
                            <div class="card-header bg-success text-white">
                                <h6 class="mb-0"><i class="ti ti-calendar me-2"></i> Información del Sistema</h6>
                            </div>
                            <div class="card-body">
                                <p><strong>ID del Departamento:</strong> ${departamento.id_departamento}</p>
                                <p><strong>Fecha de Creación:</strong> ${departamento.created_at || 'No disponible'}</p>
                                <p><strong>Última Actualización:</strong> ${departamento.updated_at || 'No disponible'}</p>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            
            document.getElementById('detallesDepartamento').innerHTML = detallesHTML;
            
            // Mostrar modal
            const modal = new bootstrap.Modal(document.getElementById('modalDetallesDepartamento'));
            modal.show();
            
        } else {
            mostrarAlerta('error', data.message || 'No se pudieron cargar los detalles');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        mostrarAlerta('error', 'Error de conexión. Por favor, intente nuevamente.');
    });
}

function guardarDepartamento() {
    // Validar campos requeridos
    const nombre = document.getElementById('nombre').value.trim();
    
    if (!nombre) {
        mostrarAlerta('warning', 'Por favor, complete el nombre del departamento.');
        document.getElementById('nombre').focus();
        return;
    }
    
    // Mostrar indicador de carga
    const btnGuardar = document.querySelector('#modalDepartamento .btn-success');
    const textoOriginal = btnGuardar.innerHTML;
    btnGuardar.innerHTML = '<i class="ti ti-loader ti-spin"></i> Guardando...';
    btnGuardar.disabled = true;
    
    // Crear FormData con los datos del formulario
    const formData = new FormData(document.getElementById('formDepartamento'));
    
    // Enviar datos via AJAX
    fetch('<?= site_url('admin-th/departamentos/guardar') ?>', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            mostrarAlerta('success', data.message);
            
            // Cerrar modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('modalDepartamento'));
            modal.hide();
            
            // Recargar lista de departamentos
            cargarDepartamentos();
            
            // Limpiar formulario
            document.getElementById('formDepartamento').reset();
            
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

function eliminarDepartamento(id) {
    mostrarConfirmacion(
        '¿Estás seguro de que quieres eliminar este departamento?',
        'Esta acción no se puede deshacer. Solo se pueden eliminar departamentos sin empleados asignados.',
        'Eliminar Departamento',
        'Eliminar',
        'btn-danger',
        () => {
            // Crear FormData para enviar el ID
            const formData = new FormData();
            formData.append('id_departamento', id);
            
            // Enviar solicitud de eliminación
            fetch('<?= site_url('admin-th/departamentos/eliminar') ?>', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    mostrarAlerta('success', data.message);
                    cargarDepartamentos(); // Recargar la lista
                } else {
                    mostrarAlerta('error', data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                mostrarAlerta('error', 'Error de conexión. Por favor, intente nuevamente.');
            });
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

// Variables globales para los gráficos
let chartDepartamentosEstado, chartEmpleadosDepartamento;

// Función para cargar y mostrar las estadísticas
function cargarEstadisticasDepartamentos() {
    fetch('<?= site_url('admin-th/departamentos/estadisticas') ?>')
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const estadisticas = data.estadisticas;
            
            // Crear gráfico de departamentos por estado
            crearGraficoDepartamentosEstado(estadisticas.porEstado);
            
            // Crear gráfico de empleados por departamento
            crearGraficoEmpleadosDepartamento(estadisticas.empleadosPorDepartamento);
        } else {
            console.error('Error al cargar estadísticas:', data.message);
        }
    })
    .catch(error => {
        console.error('Error de conexión al cargar estadísticas:', error);
    });
}

// Función para crear gráfico de departamentos por estado
function crearGraficoDepartamentosEstado(datos) {
    const ctx = document.getElementById('chartDepartamentosEstado').getContext('2d');
    
    // Destruir gráfico anterior si existe
    if (chartDepartamentosEstado) {
        chartDepartamentosEstado.destroy();
    }
    
    const labels = datos.map(item => item.estado);
    const values = datos.map(item => parseInt(item.total));
    
    chartDepartamentosEstado = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: labels,
            datasets: [{
                data: values,
                backgroundColor: [
                    '#28a745', '#dc3545', '#ffc107',
                    '#17a2b8', '#fd7e14', '#e83e8c'
                ],
                borderWidth: 2,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 20,
                        usePointStyle: true
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const label = context.label || '';
                            const value = context.parsed;
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = ((value / total) * 100).toFixed(1);
                            return `${label}: ${value} (${percentage}%)`;
                        }
                    }
                }
            }
        }
    });
}

// Función para crear gráfico de empleados por departamento
function crearGraficoEmpleadosDepartamento(datos) {
    const ctx = document.getElementById('chartEmpleadosDepartamento').getContext('2d');
    
    // Destruir gráfico anterior si existe
    if (chartEmpleadosDepartamento) {
        chartEmpleadosDepartamento.destroy();
    }
    
    const labels = datos.map(item => item.departamento || 'Sin departamento');
    const values = datos.map(item => parseInt(item.total));
    
    chartEmpleadosDepartamento = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Cantidad de Empleados',
                data: values,
                backgroundColor: [
                    '#4BC0C0', '#FF6384', '#36A2EB', '#FFCE56',
                    '#9966FF', '#FF9F40', '#FF6384', '#C9CBCF'
                ],
                borderColor: '#fff',
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return `${context.label}: ${context.parsed.y} empleados`;
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });
}

// Cargar estadísticas cuando se carga la página
document.addEventListener('DOMContentLoaded', function() {
    // Cargar Chart.js desde CDN si no está disponible
    if (typeof Chart === 'undefined') {
        const script = document.createElement('script');
        script.src = 'https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.js';
        script.onload = function() {
            cargarEstadisticasDepartamentos();
        };
        document.head.appendChild(script);
    } else {
        cargarEstadisticasDepartamentos();
    }
});
</script>
<?= $this->endSection() ?>
