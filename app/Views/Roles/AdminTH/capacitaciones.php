<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>
<div class="page-wrapper">
    <div class="page-content">
        <!-- Breadcrumb -->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Capacitaciones</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="<?= site_url('admin-th/dashboard') ?>">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Gestión de Capacitaciones</li>
                    </ol>
                </nav>
            </div>
        </div>

        <!-- Page Header -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Gestión de Capacitaciones</h4>
                        <div class="card-actions">
                            <button type="button" class="btn btn-primary" onclick="nuevaCapacitacion()">
                                <i class="ti ti-plus"></i> Nueva Capacitación
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped" id="tablaCapacitaciones">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nombre</th>
                                        <th>Descripción</th>
                                        <th>Duración</th>
                                        <th>Modalidad</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody id="tbodyCapacitaciones">
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

<!-- Modal para nueva/editar capacitación -->
<div class="modal fade" id="modalCapacitacion" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Nueva Capacitación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formCapacitacion">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="nombre" class="form-label">Nombre *</label>
                                <input type="text" class="form-control" id="nombre" name="nombre" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="duracion" class="form-label">Duración (horas) *</label>
                                <input type="number" class="form-control" id="duracion" name="duracion" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="modalidad" class="form-label">Modalidad *</label>
                                <select class="form-select" id="modalidad" name="modalidad" required>
                                    <option value="">Seleccionar...</option>
                                    <option value="PRESENCIAL">Presencial</option>
                                    <option value="VIRTUAL">Virtual</option>
                                    <option value="HIBRIDA">Híbrida</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="estado" class="form-label">Estado</label>
                                <select class="form-select" id="estado" name="estado">
                                    <option value="ACTIVA">Activa</option>
                                    <option value="INACTIVA">Inactiva</option>
                                    <option value="EN_CURSO">En Curso</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripción</label>
                        <textarea class="form-control" id="descripcion" name="descripcion" rows="3"></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="fecha_inicio" class="form-label">Fecha de Inicio</label>
                                <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="fecha_fin" class="form-label">Fecha de Fin</label>
                                <input type="date" class="form-control" id="fecha_fin" name="fecha_fin">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="guardarCapacitacion()">Guardar</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    cargarCapacitaciones();
});

function cargarCapacitaciones() {
    // Mostrar loading
    const tbody = document.getElementById('tbodyCapacitaciones');
    tbody.innerHTML = '<tr><td colspan="7" class="text-center"><i class="spinner-border spinner-border-sm"></i> Cargando capacitaciones...</td></tr>';
    
    // Obtener capacitaciones del servidor
    fetch('<?= base_url('admin-th/capacitaciones/obtener') ?>')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                renderizarCapacitaciones(data.capacitaciones);
            } else {
                tbody.innerHTML = '<tr><td colspan="7" class="text-center text-danger">Error al cargar capacitaciones</td></tr>';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            tbody.innerHTML = '<tr><td colspan="7" class="text-center text-danger">Error de conexión</td></tr>';
        });
}

function renderizarCapacitaciones(capacitaciones) {
    const tbody = document.getElementById('tbodyCapacitaciones');
    tbody.innerHTML = '';

    if (capacitaciones.length === 0) {
        tbody.innerHTML = '<tr><td colspan="7" class="text-center text-muted">No hay capacitaciones disponibles</td></tr>';
        return;
    }

    capacitaciones.forEach(capacitacion => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${capacitacion.id_capacitacion}</td>
            <td>${capacitacion.nombre}</td>
            <td>${capacitacion.descripcion || 'Sin descripción'}</td>
            <td>${capacitacion.duracion_horas} hrs</td>
            <td><span class="badge bg-info">${capacitacion.modalidad}</span></td>
            <td><span class="badge bg-${getEstadoBadgeColor(capacitacion.estado)}">${capacitacion.estado}</span></td>
            <td>
                <div class="btn-group" role="group">
                    <button class="btn btn-sm btn-outline-primary" onclick="editarCapacitacion(${capacitacion.id_capacitacion})" title="Editar">
                        <i class="bi bi-pencil"></i>
                    </button>
                    <button class="btn btn-sm btn-outline-info" onclick="verDetallesCapacitacion(${capacitacion.id_capacitacion})" title="Ver Detalles">
                        <i class="bi bi-eye"></i>
                    </button>
                    <button class="btn btn-sm btn-outline-warning" onclick="cambiarEstadoCapacitacion(${capacitacion.id_capacitacion}, '${capacitacion.estado}')" title="Cambiar Estado">
                        <i class="bi bi-toggle-on"></i>
                    </button>
                    <button class="btn btn-sm btn-outline-danger" onclick="eliminarCapacitacion(${capacitacion.id_capacitacion})" title="Eliminar">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            </td>
        `;
        tbody.appendChild(row);
    });
}

function nuevaCapacitacion() {
    document.getElementById('modalTitle').textContent = 'Nueva Capacitación';
    document.getElementById('formCapacitacion').reset();
    document.getElementById('formCapacitacion').setAttribute('data-accion', 'crear');
    
    // Establecer fechas por defecto
    const hoy = new Date().toISOString().split('T')[0];
    document.getElementById('fecha_inicio').value = hoy;
    
    const modal = new bootstrap.Modal(document.getElementById('modalCapacitacion'));
    modal.show();
}

function editarCapacitacion(id) {
    // Obtener datos de la capacitación
    fetch(`<?= base_url('admin-th/capacitaciones/obtener') ?>/${id}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const capacitacion = data.capacitacion;
                
                // Llenar el formulario
                document.getElementById('modalTitle').textContent = 'Editar Capacitación';
                document.getElementById('formCapacitacion').setAttribute('data-accion', 'editar');
                document.getElementById('formCapacitacion').setAttribute('data-id', id);
                
                document.getElementById('nombre').value = capacitacion.nombre;
                document.getElementById('descripcion').value = capacitacion.descripcion || '';
                document.getElementById('duracion').value = capacitacion.duracion_horas;
                document.getElementById('modalidad').value = capacitacion.modalidad;
                document.getElementById('estado').value = capacitacion.estado;
                document.getElementById('fecha_inicio').value = capacitacion.fecha_inicio || '';
                document.getElementById('fecha_fin').value = capacitacion.fecha_fin || '';
                
                const modal = new bootstrap.Modal(document.getElementById('modalCapacitacion'));
                modal.show();
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: data.message || 'Error al obtener datos de la capacitación'
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

function guardarCapacitacion() {
    const form = document.getElementById('formCapacitacion');
    const accion = form.getAttribute('data-accion');
    const formData = new FormData(form);
    
    // Validaciones básicas
    if (!formData.get('nombre') || !formData.get('duracion') || !formData.get('modalidad')) {
        Swal.fire({
            icon: 'warning',
            title: 'Campos Requeridos',
            text: 'Por favor complete todos los campos obligatorios'
        });
        return;
    }
    
    const url = accion === 'crear' ? 
        '<?= base_url('admin-th/capacitaciones/crear') ?>' : 
        '<?= base_url('admin-th/capacitaciones/actualizar') ?>';
    
    if (accion === 'editar') {
        formData.append('id_capacitacion', form.getAttribute('data-id'));
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
                const modal = bootstrap.Modal.getInstance(document.getElementById('modalCapacitacion'));
                modal.hide();
                cargarCapacitaciones();
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: data.message || 'Error al guardar la capacitación'
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

function verDetallesCapacitacion(id) {
    // Obtener detalles completos de la capacitación
    fetch(`<?= base_url('admin-th/capacitaciones/obtener') ?>/${id}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const capacitacion = data.capacitacion;
                
                Swal.fire({
                    title: capacitacion.nombre,
                    html: `
                        <div class="text-start">
                            <p><strong>Descripción:</strong> ${capacitacion.descripcion || 'Sin descripción'}</p>
                            <p><strong>Duración:</strong> ${capacitacion.duracion_horas} horas</p>
                            <p><strong>Modalidad:</strong> ${capacitacion.modalidad}</p>
                            <p><strong>Estado:</strong> ${capacitacion.estado}</p>
                            <p><strong>Fecha Inicio:</strong> ${capacitacion.fecha_inicio || 'No definida'}</p>
                            <p><strong>Fecha Fin:</strong> ${capacitacion.fecha_fin || 'No definida'}</p>
                            <p><strong>Fecha Creación:</strong> ${new Date(capacitacion.fecha_creacion).toLocaleDateString('es-ES')}</p>
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

function cambiarEstadoCapacitacion(id, estadoActual) {
    const nuevoEstado = estadoActual === 'ACTIVA' ? 'INACTIVA' : 'ACTIVA';
    
    Swal.fire({
        title: '¿Cambiar Estado?',
        text: `¿Deseas cambiar el estado de la capacitación a ${nuevoEstado}?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, cambiar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            const formData = new FormData();
            formData.append('id_capacitacion', id);
            formData.append('estado', nuevoEstado);
            
            fetch('<?= base_url('admin-th/capacitaciones/cambiar-estado') ?>', {
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
                        cargarCapacitaciones();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message || 'Error al cambiar el estado'
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
    });
}

function eliminarCapacitacion(id) {
    Swal.fire({
        title: '¿Eliminar Capacitación?',
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
            formData.append('id_capacitacion', id);
            
            fetch('<?= base_url('admin-th/capacitaciones/eliminar') ?>', {
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
                        cargarCapacitaciones();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message || 'Error al eliminar la capacitación'
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
    });
}

function getEstadoBadgeColor(estado) {
    switch(estado) {
        case 'ACTIVA': return 'success';
        case 'INACTIVA': return 'danger';
        case 'EN_CURSO': return 'warning';
        case 'COMPLETADA': return 'info';
        case 'CANCELADA': return 'secondary';
        default: return 'secondary';
    }
}
</script>
<?= $this->endSection() ?>
