<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>
<div class="page-wrapper">
    <div class="page-content">
        <!-- Breadcrumb -->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Evaluaciones</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="<?= site_url('admin-th/dashboard') ?>">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Gestión de Evaluaciones</li>
                    </ol>
                </nav>
            </div>
        </div>

        <!-- Page Header -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Gestión de Evaluaciones</h4>
                        <div class="card-actions">
                                                    <button type="button" class="btn btn-primary" onclick="nuevaEvaluacion()">
                            <i class="bi bi-plus"></i> Nueva Evaluación
                        </button>
                        <button type="button" class="btn btn-info ms-2" onclick="configurarEvaluacionPares()">
                            <i class="bi bi-people"></i> Evaluación Entre Pares
                        </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped" id="tablaEvaluaciones">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Empleado</th>
                                        <th>Tipo</th>
                                        <th>Fecha</th>
                                        <th>Puntuación</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody id="tbodyEvaluaciones">
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

<!-- Modal para nueva/editar evaluación -->
<div class="modal fade" id="modalEvaluacion" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Nueva Evaluación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formEvaluacion">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="empleado_id" class="form-label">Empleado *</label>
                                <select class="form-select" id="empleado_id" name="empleado_id" required>
                                    <option value="">Seleccionar empleado...</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="tipo_evaluacion" class="form-label">Tipo de Evaluación *</label>
                                <select class="form-select" id="tipo_evaluacion" name="tipo_evaluacion" required>
                                    <option value="">Seleccionar tipo...</option>
                                    <option value="DESEMPEÑO">Desempeño</option>
                                    <option value="COMPETENCIAS">Competencias</option>
                                    <option value="PERIÓDICA">Periódica</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="fecha_evaluacion" class="form-label">Fecha de Evaluación *</label>
                                <input type="date" class="form-control" id="fecha_evaluacion" name="fecha_evaluacion" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="puntuacion" class="form-label">Puntuación (1-10) *</label>
                                <input type="number" class="form-control" id="puntuacion" name="puntuacion" min="1" max="10" required>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="observaciones" class="form-label">Observaciones</label>
                        <textarea class="form-control" id="observaciones" name="observaciones" rows="3"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="guardarEvaluacion()">Guardar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para evaluación entre pares -->
<div class="modal fade" id="modalEvaluacionPares" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Configurar Evaluación Entre Pares</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formEvaluacionPares">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="periodo_evaluacion" class="form-label">Período de Evaluación *</label>
                                <select class="form-select" id="periodo_evaluacion" name="periodo_evaluacion" required>
                                    <option value="">Seleccionar período...</option>
                                    <option value="2025-1">Primer Semestre 2025</option>
                                    <option value="2025-2">Segundo Semestre 2025</option>
                                    <option value="2026-1">Primer Semestre 2026</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="fecha_limite" class="form-label">Fecha Límite *</label>
                                <input type="date" class="form-control" id="fecha_limite" name="fecha_limite" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-12">
                            <h6>Asignación de Evaluadores</h6>
                            <div class="table-responsive">
                                <table class="table table-sm" id="tablaAsignacionEvaluadores">
                                    <thead>
                                        <tr>
                                            <th>Docente</th>
                                            <th>Evaluador 1</th>
                                            <th>Evaluador 2</th>
                                            <th>Evaluador 3</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tbodyAsignacionEvaluadores">
                                        <!-- Se llenará dinámicamente -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="guardarEvaluacionPares()">Guardar Configuración</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    cargarEvaluaciones();
    cargarEmpleados();
});

function cargarEvaluaciones() {
    // Mostrar loading
    const tbody = document.getElementById('tbodyEvaluaciones');
    tbody.innerHTML = '<tr><td colspan="7" class="text-center"><i class="spinner-border spinner-border-sm"></i> Cargando evaluaciones...</td></tr>';
    
    // Obtener evaluaciones del servidor
    fetch('<?= base_url('admin-th/evaluaciones/obtener') ?>')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                renderizarEvaluaciones(data.evaluaciones);
            } else {
                tbody.innerHTML = '<tr><td colspan="7" class="text-center text-danger">Error al cargar evaluaciones</td></tr>';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            tbody.innerHTML = '<tr><td colspan="7" class="text-center text-danger">Error de conexión</td></tr>';
        });
}

function renderizarEvaluaciones(evaluaciones) {
    const tbody = document.getElementById('tbodyEvaluaciones');
    tbody.innerHTML = '';

    if (evaluaciones.length === 0) {
        tbody.innerHTML = '<tr><td colspan="7" class="text-center text-muted">No hay evaluaciones disponibles</td></tr>';
        return;
    }

    evaluaciones.forEach(evaluacion => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${evaluacion.id_evaluacion}</td>
            <td>${evaluacion.nombres_empleado} ${evaluacion.apellidos_empleado}</td>
            <td><span class="badge bg-info">${evaluacion.tipo_evaluacion}</span></td>
            <td>${formatearFecha(evaluacion.fecha_evaluacion)}</td>
            <td><span class="badge bg-${getPuntuacionBadgeColor(evaluacion.puntuacion)}">${evaluacion.puntuacion}/10</span></td>
            <td><span class="badge bg-${getEstadoBadgeColor(evaluacion.estado)}">${evaluacion.estado}</span></td>
            <td>
                <div class="btn-group" role="group">
                    <button class="btn btn-sm btn-outline-primary" onclick="editarEvaluacion(${evaluacion.id_evaluacion})" title="Editar">
                        <i class="bi bi-pencil"></i>
                    </button>
                    <button class="btn btn-sm btn-outline-info" onclick="verDetallesEvaluacion(${evaluacion.id_evaluacion})" title="Ver Detalles">
                        <i class="bi bi-eye"></i>
                    </button>
                    <button class="btn btn-sm btn-outline-warning" onclick="cambiarEstadoEvaluacion(${evaluacion.id_evaluacion}, '${evaluacion.estado}')" title="Cambiar Estado">
                        <i class="bi bi-toggle-on"></i>
                    </button>
                    <button class="btn btn-sm btn-outline-danger" onclick="eliminarEvaluacion(${evaluacion.id_evaluacion})" title="Eliminar">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            </td>
        `;
        tbody.appendChild(row);
    });
}

function cargarEmpleados() {
    // Cargar empleados para el select
    fetch('<?= base_url('admin-th/empleados/obtener') ?>')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const select = document.getElementById('empleado_id');
                select.innerHTML = '<option value="">Seleccionar empleado...</option>';
                
                data.empleados.forEach(empleado => {
                    const option = document.createElement('option');
                    option.value = empleado.id_empleado;
                    option.textContent = `${empleado.nombres} ${empleado.apellidos} - ${empleado.tipo_empleado}`;
                    select.appendChild(option);
                });
            }
        })
        .catch(error => {
            console.error('Error cargando empleados:', error);
        });
}

function nuevaEvaluacion() {
    document.getElementById('modalTitle').textContent = 'Nueva Evaluación';
    document.getElementById('formEvaluacion').reset();
    document.getElementById('formEvaluacion').setAttribute('data-accion', 'crear');
    
    // Establecer fecha por defecto
    const hoy = new Date().toISOString().split('T')[0];
    document.getElementById('fecha_evaluacion').value = hoy;
    
    const modal = new bootstrap.Modal(document.getElementById('modalEvaluacion'));
    modal.show();
}

function editarEvaluacion(id) {
    // Obtener datos de la evaluación
    fetch(`<?= base_url('admin-th/evaluaciones/obtener') ?>/${id}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const evaluacion = data.evaluacion;
                
                // Llenar el formulario
                document.getElementById('modalTitle').textContent = 'Editar Evaluación';
                document.getElementById('formEvaluacion').setAttribute('data-accion', 'editar');
                document.getElementById('formEvaluacion').setAttribute('data-id', id);
                
                document.getElementById('empleado_id').value = evaluacion.id_empleado;
                document.getElementById('tipo_evaluacion').value = evaluacion.tipo_evaluacion;
                document.getElementById('fecha_evaluacion').value = evaluacion.fecha_evaluacion;
                document.getElementById('puntuacion').value = evaluacion.puntuacion;
                document.getElementById('observaciones').value = evaluacion.observaciones || '';
                
                const modal = new bootstrap.Modal(document.getElementById('modalEvaluacion'));
                modal.show();
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: data.message || 'Error al obtener datos de la evaluación'
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Error de conexión'
            });
        });
}

function guardarEvaluacion() {
    const form = document.getElementById('formEvaluacion');
    const accion = form.getAttribute('data-accion');
    const formData = new FormData(form);
    
    // Validaciones básicas
    if (!formData.get('empleado_id') || !formData.get('tipo_evaluacion') || !formData.get('fecha_evaluacion') || !formData.get('puntuacion')) {
        Swal.fire({
            icon: 'warning',
            title: 'Campos Requeridos',
            text: 'Por favor complete todos los campos obligatorios'
        });
        return;
    }
    
    const url = accion === 'crear' ? 
        '<?= base_url('admin-th/evaluaciones/crear') ?>' : 
        '<?= base_url('admin-th/evaluaciones/actualizar') ?>';
    
    if (accion === 'editar') {
        formData.append('id_evaluacion', form.getAttribute('data-id'));
    }
    
    fetch(url, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Éxito',
                text: data.message
            }).then(() => {
                const modal = bootstrap.Modal.getInstance(document.getElementById('modalEvaluacion'));
                modal.hide();
                cargarEvaluaciones();
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: data.message || 'Error al guardar la evaluación'
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Error de conexión'
        });
    });
}

function verDetallesEvaluacion(id) {
    // Obtener detalles completos de la evaluación
    fetch(`<?= base_url('admin-th/evaluaciones/obtener') ?>/${id}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const evaluacion = data.evaluacion;
                
                Swal.fire({
                    title: `Evaluación de ${evaluacion.nombres_empleado} ${evaluacion.apellidos_empleado}`,
                    html: `
                        <div class="text-start">
                            <p><strong>Tipo:</strong> ${evaluacion.tipo_evaluacion}</p>
                            <p><strong>Fecha:</strong> ${formatearFecha(evaluacion.fecha_evaluacion)}</p>
                            <p><strong>Puntuación:</strong> ${evaluacion.puntuacion}/10</p>
                            <p><strong>Estado:</strong> ${evaluacion.estado}</p>
                            <p><strong>Observaciones:</strong> ${evaluacion.observaciones || 'Sin observaciones'}</p>
                            <p><strong>Evaluador:</strong> ${evaluacion.nombres_evaluador || 'N/A'}</p>
                            <p><strong>Fecha Creación:</strong> ${formatearFecha(evaluacion.fecha_creacion)}</p>
                        </div>
                    `,
                    icon: 'info',
                    confirmButtonText: 'Cerrar'
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: data.message || 'Error al obtener detalles'
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Error de conexión'
            });
        });
}

function cambiarEstadoEvaluacion(id, estadoActual) {
    const nuevoEstado = estadoActual === 'COMPLETADA' ? 'PENDIENTE' : 'COMPLETADA';
    
    Swal.fire({
        title: '¿Cambiar Estado?',
        text: `¿Deseas cambiar el estado de la evaluación a ${nuevoEstado}?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, cambiar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            const formData = new FormData();
            formData.append('id_evaluacion', id);
            formData.append('estado', nuevoEstado);
            
            fetch('<?= base_url('admin-th/evaluaciones/cambiar-estado') ?>', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Estado Cambiado',
                        text: data.message
                    }).then(() => {
                        cargarEvaluaciones();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message || 'Error al cambiar el estado'
                    });
                }
            });
        }
    });
}

function eliminarEvaluacion(id) {
    Swal.fire({
        title: '¿Eliminar Evaluación?',
        text: "Esta acción no se puede deshacer",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            const formData = new FormData();
            formData.append('id_evaluacion', id);
            
            fetch('<?= base_url('admin-th/evaluaciones/eliminar') ?>', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Eliminada',
                        text: data.message
                    }).then(() => {
                        cargarEvaluaciones();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message || 'Error al eliminar la evaluación'
                    });
                }
            });
        }
    });
}

// Funciones para evaluación entre pares
function configurarEvaluacionPares() {
    // Cargar docentes para la asignación
    cargarDocentesParaEvaluacionPares();
    
    const modal = new bootstrap.Modal(document.getElementById('modalEvaluacionPares'));
    modal.show();
}

function cargarDocentesParaEvaluacionPares() {
    // Obtener solo docentes
    fetch('<?= base_url('admin-th/empleados/obtener') ?>?tipo=DOCENTE')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const tbody = document.getElementById('tbodyAsignacionEvaluadores');
                tbody.innerHTML = '';
                
                data.empleados.forEach(docente => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${docente.nombres} ${docente.apellidos}</td>
                        <td>
                            <select class="form-select form-select-sm" name="evaluador1_${docente.id_empleado}">
                                <option value="">Seleccionar...</option>
                                ${data.empleados.map(e => `<option value="${e.id_empleado}">${e.nombres} ${e.apellidos}</option>`).join('')}
                            </select>
                        </td>
                        <td>
                            <select class="form-select form-select-sm" name="evaluador2_${docente.id_empleado}">
                                <option value="">Seleccionar...</option>
                                ${data.empleados.map(e => `<option value="${e.id_empleado}">${e.nombres} ${e.apellidos}</option>`).join('')}
                            </select>
                        </td>
                        <td>
                            <select class="form-select form-select-sm" name="evaluador3_${docente.id_empleado}">
                                <option value="">Seleccionar...</option>
                                ${data.empleados.map(e => `<option value="${e.id_empleado}">${e.nombres} ${e.apellidos}</option>`).join('')}
                            </select>
                        </td>
                    `;
                    tbody.appendChild(row);
                });
            }
        })
        .catch(error => {
            console.error('Error cargando docentes:', error);
        });
}

function guardarEvaluacionPares() {
    const formData = new FormData(document.getElementById('formEvaluacionPares'));
    
    // Validar campos requeridos
    if (!formData.get('periodo_evaluacion') || !formData.get('fecha_limite')) {
        Swal.fire({
            icon: 'warning',
            title: 'Campos Requeridos',
            text: 'Por favor complete todos los campos obligatorios'
        });
        return;
    }
    
    // Recopilar asignaciones
    const asignaciones = [];
    const tbody = document.getElementById('tbodyAsignacionEvaluadores');
    const filas = tbody.querySelectorAll('tr');
    
    filas.forEach(fila => {
        const celdas = fila.querySelectorAll('td');
        const docente = celdas[0].textContent;
        const evaluadores = [];
        
        for (let i = 1; i < celdas.length; i++) {
            const select = celdas[i].querySelector('select');
            if (select && select.value) {
                evaluadores.push(select.value);
            }
        }
        
        if (evaluadores.length > 0) {
            asignaciones.push({
                docente: docente,
                evaluadores: evaluadores
            });
        }
    });
    
    if (asignaciones.length === 0) {
        Swal.fire({
            icon: 'warning',
            title: 'Sin Asignaciones',
            text: 'Debe asignar al menos un evaluador por docente'
        });
        return;
    }
    
    // Enviar configuración
    formData.append('asignaciones', JSON.stringify(asignaciones));
    
    fetch('<?= base_url('admin-th/evaluaciones/configurar-pares') ?>', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Configuración Guardada',
                text: data.message
            }).then(() => {
                const modal = bootstrap.Modal.getInstance(document.getElementById('modalEvaluacionPares'));
                modal.hide();
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: data.message || 'Error al guardar la configuración'
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Error de conexión'
        });
    });
}

function getEstadoBadgeColor(estado) {
    switch(estado) {
        case 'COMPLETADA': return 'success';
        case 'PENDIENTE': return 'warning';
        case 'CANCELADA': return 'danger';
        case 'EN_PROCESO': return 'info';
        default: return 'secondary';
    }
}

function getPuntuacionBadgeColor(puntuacion) {
    if (puntuacion >= 9) return 'success';
    if (puntuacion >= 7) return 'warning';
    return 'danger';
}

function formatearFecha(fecha) {
    return new Date(fecha).toLocaleDateString('es-ES');
}
</script>
<?= $this->endSection() ?>
