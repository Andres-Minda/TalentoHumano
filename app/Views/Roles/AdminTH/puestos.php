<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>

<div class="page-wrapper">
    <div class="page-content">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center justify-content-between">
                            <h4 class="card-title mb-0">Gestión de Puestos de Trabajo</h4>
                            <button type="button" class="btn btn-primary" onclick="nuevoPuesto()">
                                <i class="ti ti-plus"></i> Nuevo Puesto
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover" id="tablaPuestos">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Título</th>
                                        <th>Departamento</th>
                                        <th>Tipo Contrato</th>
                                        <th>Vacantes</th>
                                        <th>Estado</th>
                                        <th>Fecha Límite</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Los datos se cargan dinámicamente -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Nuevo/Editar Puesto -->
<div class="modal fade" id="modalPuesto" tabindex="-1" aria-labelledby="modalPuestoLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalPuestoLabel">Nuevo Puesto de Trabajo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formPuesto">
                <div class="modal-body">
                    <input type="hidden" id="id_puesto" name="id_puesto">
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="titulo" class="form-label">Título del Puesto *</label>
                                <input type="text" class="form-control" id="titulo" name="titulo" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="id_departamento" class="form-label">Departamento *</label>
                                <select class="form-select" id="id_departamento" name="id_departamento" required>
                                    <option value="">Seleccionar departamento</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="tipo_contrato" class="form-label">Tipo de Contrato *</label>
                                <select class="form-select" id="tipo_contrato" name="tipo_contrato" required>
                                    <option value="">Seleccionar tipo</option>
                                    <option value="Tiempo Completo">Tiempo Completo</option>
                                    <option value="Tiempo Parcial">Tiempo Parcial</option>
                                    <option value="Contrato Fijo">Contrato Fijo</option>
                                    <option value="Contrato Indefinido">Contrato Indefinido</option>
                                    <option value="Por Proyecto">Por Proyecto</option>
                                    <option value="Prácticas">Prácticas</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="modalidad_trabajo" class="form-label">Modalidad de Trabajo *</label>
                                <select class="form-select" id="modalidad_trabajo" name="modalidad_trabajo" required>
                                    <option value="">Seleccionar modalidad</option>
                                    <option value="Presencial">Presencial</option>
                                    <option value="Remoto">Remoto</option>
                                    <option value="Híbrido">Híbrido</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="salario_min" class="form-label">Salario Mínimo *</label>
                                <input type="number" class="form-control" id="salario_min" name="salario_min" min="0" step="0.01" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="salario_max" class="form-label">Salario Máximo *</label>
                                <input type="number" class="form-control" id="salario_max" name="salario_max" min="0" step="0.01" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="vacantes_disponibles" class="form-label">Vacantes Disponibles *</label>
                                <input type="number" class="form-control" id="vacantes_disponibles" name="vacantes_disponibles" min="1" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="fecha_limite" class="form-label">Fecha Límite de Postulación *</label>
                                <input type="date" class="form-control" id="fecha_limite" name="fecha_limite" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="nivel_experiencia" class="form-label">Nivel de Experiencia *</label>
                                <select class="form-select" id="nivel_experiencia" name="nivel_experiencia" required>
                                    <option value="">Seleccionar nivel</option>
                                    <option value="Sin Experiencia">Sin Experiencia</option>
                                    <option value="Junior (1-2 años)">Junior (1-2 años)</option>
                                    <option value="Semi-Senior (3-5 años)">Semi-Senior (3-5 años)</option>
                                    <option value="Senior (5+ años)">Senior (5+ años)</option>
                                    <option value="Experto (8+ años)">Experto (8+ años)</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="ubicacion_trabajo" class="form-label">Ubicación de Trabajo</label>
                                <input type="text" class="form-control" id="ubicacion_trabajo" name="ubicacion_trabajo" placeholder="Ciudad, País">
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripción del Puesto *</label>
                        <textarea class="form-control" id="descripcion" name="descripcion" rows="4" required placeholder="Describe las responsabilidades principales del puesto..."></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="experiencia_requerida" class="form-label">Experiencia Requerida</label>
                                <textarea class="form-control" id="experiencia_requerida" name="experiencia_requerida" rows="3" placeholder="Describe la experiencia específica requerida..."></textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="educacion_requerida" class="form-label">Educación Requerida</label>
                                <textarea class="form-control" id="educacion_requerida" name="educacion_requerida" rows="3" placeholder="Describe los requisitos educativos..."></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="habilidades_requeridas" class="form-label">Habilidades Requeridas</label>
                                <textarea class="form-control" id="habilidades_requeridas" name="habilidades_requeridas" rows="3" placeholder="Lista las habilidades técnicas y blandas requeridas..."></textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="responsabilidades" class="form-label">Responsabilidades Principales</label>
                                <textarea class="form-control" id="responsabilidades" name="responsabilidades" rows="3" placeholder="Enumera las responsabilidades principales..."></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="beneficios" class="form-label">Beneficios Ofrecidos</label>
                        <textarea class="form-control" id="beneficios" name="beneficios" rows="3" placeholder="Describe los beneficios del puesto (seguro médico, vacaciones, etc.)..."></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="estado" class="form-label">Estado *</label>
                                <select class="form-select" id="estado" name="estado" required>
                                    <option value="Abierto">Abierto</option>
                                    <option value="Cerrado">Cerrado</option>
                                    <option value="En Revisión">En Revisión</option>
                                    <option value="Pausado">Pausado</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="activo" class="form-label">Activo *</label>
                                <select class="form-select" id="activo" name="activo" required>
                                    <option value="1">Sí</option>
                                    <option value="0">No</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar Puesto</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Ver Detalles del Puesto -->
<div class="modal fade" id="modalDetallesPuesto" tabindex="-1" aria-labelledby="modalDetallesPuestoLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalDetallesPuestoLabel">Detalles del Puesto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="detallesPuestoContent">
                <!-- Los detalles se cargan dinámicamente -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Ver Postulantes -->
<div class="modal fade" id="modalPostulantes" tabindex="-1" aria-labelledby="modalPostulantesLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalPostulantesLabel">Postulantes al Puesto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-striped" id="tablaPostulantes">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Cédula</th>
                                <th>Email</th>
                                <th>Teléfono</th>
                                <th>Estado</th>
                                <th>Fecha Postulación</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Los postulantes se cargan dinámicamente -->
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Cambiar Estado Postulación -->
<div class="modal fade" id="modalCambiarEstado" tabindex="-1" aria-labelledby="modalCambiarEstadoLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalCambiarEstadoLabel">Cambiar Estado de Postulación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formCambiarEstado">
                <div class="modal-body">
                    <input type="hidden" id="id_postulante_estado" name="id_postulante">
                    <input type="hidden" id="id_puesto_estado" name="id_puesto">
                    
                    <div class="mb-3">
                        <label for="nuevo_estado" class="form-label">Nuevo Estado *</label>
                        <select class="form-select" id="nuevo_estado" name="nuevo_estado" required>
                            <option value="">Seleccionar estado</option>
                            <option value="Pendiente">Pendiente</option>
                            <option value="En Revisión">En Revisión</option>
                            <option value="Aprobado">Aprobado</option>
                            <option value="Rechazado">Rechazado</option>
                            <option value="Entrevista">Entrevista</option>
                            <option value="Contratado">Contratado</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="notas_admin" class="form-label">Notas Administrativas</label>
                        <textarea class="form-control" id="notas_admin" name="notas_admin" rows="3" placeholder="Agregar notas sobre la postulación..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Actualizar Estado</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal de Confirmación -->
<div class="modal fade" id="modalConfirmacion" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title">
                    <i class="ti ti-alert-triangle me-2"></i>Confirmar Acción
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <p class="mb-0" id="mensajeConfirmacion"></p>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-warning" id="btnConfirmarAccion">Confirmar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Alerta -->
<div class="modal fade" id="modalAlerta" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header" id="headerAlerta">
                <h5 class="modal-title" id="tituloAlerta"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <p class="mb-0" id="mensajeAlerta"></p>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Aceptar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Éxito -->
<div class="modal fade" id="modalExito" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">
                    <i class="ti ti-check me-2"></i>¡Éxito!
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <p class="mb-0" id="mensajeExito"></p>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-success" data-bs-dismiss="modal">Aceptar</button>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
let puestosData = [];
let postulantesData = [];

// Cargar puestos al iniciar la página
document.addEventListener('DOMContentLoaded', function() {
    cargarPuestos();
    cargarDepartamentos();
});

// Función para cargar puestos
function cargarPuestos() {
    fetch('<?= base_url('admin-th/puestos/obtener') ?>')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                puestosData = data.data;
                renderizarTablaPuestos();
            } else {
                mostrarAlerta('Error', 'Error al cargar los puestos: ' + data.message, 'danger');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            mostrarAlerta('Error', 'Error al cargar los puestos', 'danger');
        });
}

// Función para cargar departamentos
function cargarDepartamentos() {
    fetch('<?= base_url('admin-th/departamentos/activos') ?>')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const select = document.getElementById('id_departamento');
                select.innerHTML = '<option value="">Seleccionar departamento</option>';
                
                data.data.forEach(departamento => {
                    const option = document.createElement('option');
                    option.value = departamento.id_departamento;
                    option.textContent = departamento.titulo;
                    select.appendChild(option);
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
}

// Función para renderizar tabla de puestos
function renderizarTablaPuestos() {
    const tbody = document.querySelector('#tablaPuestos tbody');
    if (!tbody) {
        console.error('No se encontró el tbody de la tabla de puestos');
        return;
    }
    
    tbody.innerHTML = '';

    // Verificar que puestosData sea un array válido
    if (!Array.isArray(puestosData)) {
        console.error('puestosData no es un array válido:', puestosData);
        puestosData = [];
        return;
    }

    puestosData.forEach(puesto => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${puesto.id_puesto}</td>
            <td><strong>${puesto.titulo}</strong></td>
            <td>${puesto.nombre_departamento || 'N/A'}</td>
            <td><span class="badge bg-info">${puesto.tipo_contrato}</span></td>
            <td>
                <span class="badge ${puesto.vacantes_disponibles > 0 ? 'bg-success' : 'bg-danger'}">
                    ${puesto.vacantes_disponibles} disponible${puesto.vacantes_disponibles !== 1 ? 's' : ''}
                </span>
            </td>
            <td>
                <span class="badge ${getBadgeClass(puesto.estado)}">${puesto.estado}</span>
            </td>
            <td>${formatearFecha(puesto.fecha_limite)}</td>
            <td>
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="editarPuesto(${puesto.id_puesto})" title="Editar">
                        <i class="ti ti-edit"></i>
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-info" onclick="verDetallesPuesto(${puesto.id_puesto})" title="Ver Detalles">
                        <i class="ti ti-eye"></i>
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-success" onclick="verPostulantes(${puesto.id_puesto})" title="Ver Postulantes">
                        <i class="ti ti-users"></i>
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-warning" onclick="generarUrl(${puesto.id_puesto})" title="Generar URL">
                        <i class="ti ti-link"></i>
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="eliminarPuesto(${puesto.id_puesto})" title="Eliminar">
                        <i class="ti ti-trash"></i>
                    </button>
                </div>
            </td>
        `;
        tbody.appendChild(row);
    });
}

// Función para obtener clase de badge según estado
function getBadgeClass(estado) {
    switch(estado) {
        case 'Abierto': return 'bg-success';
        case 'Cerrado': return 'bg-danger';
        case 'En Revisión': return 'bg-warning';
        case 'Pausado': return 'bg-secondary';
        default: return 'bg-primary';
    }
}

// Función para formatear fecha
function formatearFecha(fecha) {
    if (!fecha) return 'N/A';
    return new Date(fecha).toLocaleDateString('es-ES');
}

// Función para nuevo puesto
function nuevoPuesto() {
    document.getElementById('modalPuestoLabel').textContent = 'Nuevo Puesto de Trabajo';
    document.getElementById('formPuesto').reset();
    document.getElementById('id_puesto').value = '';
    
    // Establecer fecha mínima como hoy
    const fechaLimite = document.getElementById('fecha_limite');
    const hoy = new Date().toISOString().split('T')[0];
    fechaLimite.min = hoy;
    
    const modal = new bootstrap.Modal(document.getElementById('modalPuesto'));
    modal.show();
}

// Función para editar puesto
function editarPuesto(idPuesto) {
    const puesto = puestosData.find(p => p.id_puesto == idPuesto);
    if (!puesto) {
        mostrarAlerta('Error', 'Puesto no encontrado', 'danger');
        return;
    }

    document.getElementById('modalPuestoLabel').textContent = 'Editar Puesto de Trabajo';
    document.getElementById('id_puesto').value = puesto.id_puesto;
    document.getElementById('titulo').value = puesto.titulo;
    document.getElementById('id_departamento').value = puesto.id_departamento;
    document.getElementById('tipo_contrato').value = puesto.tipo_contrato;
    document.getElementById('modalidad_trabajo').value = puesto.modalidad_trabajo;
    document.getElementById('salario_min').value = puesto.salario_min;
    document.getElementById('salario_max').value = puesto.salario_max;
    document.getElementById('vacantes_disponibles').value = puesto.vacantes_disponibles;
    document.getElementById('fecha_limite').value = puesto.fecha_limite;
    document.getElementById('nivel_experiencia').value = puesto.nivel_experiencia;
    document.getElementById('ubicacion_trabajo').value = puesto.ubicacion_trabajo;
    document.getElementById('descripcion').value = puesto.descripcion;
    document.getElementById('experiencia_requerida').value = puesto.experiencia_requerida;
    document.getElementById('educacion_requerida').value = puesto.educacion_requerida;
    document.getElementById('habilidades_requeridas').value = puesto.habilidades_requeridas;
    document.getElementById('responsabilidades').value = puesto.responsabilidades;
    document.getElementById('beneficios').value = puesto.beneficios;
    document.getElementById('estado').value = puesto.estado;
    document.getElementById('activo').value = puesto.activo;

    const modal = new bootstrap.Modal(document.getElementById('modalPuesto'));
    modal.show();
}

// Función para guardar puesto
document.getElementById('formPuesto').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const data = Object.fromEntries(formData.entries());
    
    fetch('<?= base_url('admin-th/puestos/guardar') ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(result => {
        if (result.success) {
            mostrarModalExito(result.message || 'Puesto guardado exitosamente');
            bootstrap.Modal.getInstance(document.getElementById('modalPuesto')).hide();
            cargarPuestos();
        } else {
            mostrarAlerta('Error', 'Error al guardar el puesto: ' + result.message, 'danger');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        mostrarAlerta('Error', 'Error al guardar el puesto', 'danger');
    });
});

// Función para eliminar puesto
function eliminarPuesto(idPuesto) {
    const puesto = puestosData.find(p => p.id_puesto == idPuesto);
    if (!puesto) return;

    mostrarConfirmacion(
        '¿Estás seguro?',
        `¿Deseas eliminar el puesto "${puesto.titulo}"? Esta acción no se puede deshacer.`,
        () => {
            fetch('<?= base_url('admin-th/puestos/eliminar') ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ id_puesto: idPuesto })
            })
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    mostrarModalExito('Puesto eliminado exitosamente');
                    cargarPuestos();
                } else {
                    mostrarAlerta('Error', 'Error al eliminar el puesto: ' + result.message, 'danger');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                mostrarAlerta('Error', 'Error al eliminar el puesto', 'danger');
            });
        }
    );
}

// Función para generar URL
function generarUrl(idPuesto) {
    const puesto = puestosData.find(p => p.id_puesto == idPuesto);
    if (!puesto) return;

    if (puesto.url_postulacion) {
        mostrarConfirmacion(
            'URL ya existe',
            `El puesto "${puesto.titulo}" ya tiene una URL de postulación. ¿Deseas generar una nueva?`,
            () => generarNuevaUrl(idPuesto)
        );
    } else {
        generarNuevaUrl(idPuesto);
    }
}

// Función para generar nueva URL
function generarNuevaUrl(idPuesto) {
    fetch('<?= base_url('admin-th/puestos/generar-url') ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ id_puesto: idPuesto })
    })
    .then(response => response.json())
    .then(result => {
        if (result.success) {
            mostrarModalExito('URL generada exitosamente');
            cargarPuestos();
        } else {
            mostrarAlerta('Error', 'Error al generar URL: ' + result.message, 'danger');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        mostrarAlerta('Error', 'Error al generar URL', 'danger');
    });
}

// Función para ver detalles del puesto
function verDetallesPuesto(idPuesto) {
    const puesto = puestosData.find(p => p.id_puesto == idPuesto);
    if (!puesto) return;

    const content = document.getElementById('detallesPuestoContent');
    content.innerHTML = `
        <div class="row">
            <div class="col-md-6">
                <h6 class="text-primary">Información General</h6>
                <p><strong>Título:</strong> ${puesto.titulo}</p>
                <p><strong>Departamento:</strong> ${puesto.nombre_departamento || 'N/A'}</p>
                <p><strong>Tipo de Contrato:</strong> ${puesto.tipo_contrato}</p>
                <p><strong>Modalidad:</strong> ${puesto.modalidad_trabajo}</p>
                <p><strong>Ubicación:</strong> ${puesto.ubicacion_trabajo || 'N/A'}</p>
                <p><strong>Estado:</strong> <span class="badge ${getBadgeClass(puesto.estado)}">${puesto.estado}</span></p>
            </div>
            <div class="col-md-6">
                <h6 class="text-primary">Detalles Laborales</h6>
                <p><strong>Salario:</strong> $${puesto.salario_min} - $${puesto.salario_max}</p>
                <p><strong>Vacantes:</strong> ${puesto.vacantes_disponibles}</p>
                <p><strong>Nivel de Experiencia:</strong> ${puesto.nivel_experiencia}</p>
                <p><strong>Fecha Límite:</strong> ${formatearFecha(puesto.fecha_limite)}</p>
                <p><strong>URL de Postulación:</strong> ${puesto.url_postulacion ? `<a href="${puesto.url_postulacion}" target="_blank">${puesto.url_postulacion}</a>` : 'No generada'}</p>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-12">
                <h6 class="text-primary">Descripción del Puesto</h6>
                <p>${puesto.descripcion || 'No especificada'}</p>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <h6 class="text-primary">Experiencia Requerida</h6>
                <p>${puesto.experiencia_requerida || 'No especificada'}</p>
            </div>
            <div class="col-md-6">
                <h6 class="text-primary">Educación Requerida</h6>
                <p>${puesto.educacion_requerida || 'No especificada'}</p>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <h6 class="text-primary">Habilidades Requeridas</h6>
                <p>${puesto.habilidades_requeridas || 'No especificadas'}</p>
            </div>
            <div class="col-md-6">
                <h6 class="text-primary">Responsabilidades</h6>
                <p>${puesto.responsabilidades || 'No especificadas'}</p>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <h6 class="text-primary">Beneficios Ofrecidos</h6>
                <p>${puesto.beneficios || 'No especificados'}</p>
            </div>
        </div>
    `;

    const modal = new bootstrap.Modal(document.getElementById('modalDetallesPuesto'));
    modal.show();
}

// Función para ver postulantes
function verPostulantes(idPuesto) {
    const puesto = puestosData.find(p => p.id_puesto == idPuesto);
    if (!puesto) return;

    document.getElementById('modalPostulantesLabel').textContent = `Postulantes al Puesto: ${puesto.titulo}`;
    
    fetch(`<?= base_url('admin-th/puestos') ?>/${idPuesto}/postulantes`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                postulantesData = data.data;
                renderizarTablaPostulantes();
                const modal = new bootstrap.Modal(document.getElementById('modalPostulantes'));
                modal.show();
            } else {
                mostrarAlerta('Error', 'Error al cargar los postulantes: ' + data.message, 'danger');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            mostrarAlerta('Error', 'Error al cargar los postulantes', 'danger');
        });
}

// Función para renderizar tabla de postulantes
function renderizarTablaPostulantes() {
    const tbody = document.querySelector('#tablaPostulantes tbody');
    if (!tbody) {
        console.error('No se encontró el tbody de la tabla de postulantes');
        return;
    }
    
    tbody.innerHTML = '';

    // Verificar que postulantesData sea un array válido
    if (!Array.isArray(postulantesData)) {
        console.error('postulantesData no es un array válido:', postulantesData);
        postulantesData = [];
        tbody.innerHTML = '<tr><td colspan="8" class="text-center text-muted">Error al cargar los datos</td></tr>';
        return;
    }

    if (postulantesData.length === 0) {
        tbody.innerHTML = '<tr><td colspan="8" class="text-center text-muted">No hay postulantes para este puesto</td></tr>';
        return;
    }

    postulantesData.forEach(postulante => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${postulante.id_postulante}</td>
            <td><strong>${postulante.nombres} ${postulante.apellidos}</strong></td>
            <td>${postulante.cedula}</td>
            <td>${postulante.email}</td>
            <td>${postulante.telefono || 'N/A'}</td>
            <td><span class="badge ${getBadgeClassPostulacion(postulante.estado_postulacion)}">${postulante.estado_postulacion}</span></td>
            <td>${formatearFecha(postulante.fecha_postulacion)}</td>
            <td>
                <button type="button" class="btn btn-sm btn-outline-warning" onclick="cambiarEstadoPostulacion(${postulante.id_postulante}, ${postulante.id_puesto})" title="Cambiar Estado">
                    <i class="ti ti-edit"></i>
                </button>
            </td>
        `;
        tbody.appendChild(row);
    });
}

// Función para obtener clase de badge según estado de postulación
function getBadgeClassPostulacion(estado) {
    switch(estado) {
        case 'Pendiente': return 'bg-secondary';
        case 'En Revisión': return 'bg-warning';
        case 'Aprobado': return 'bg-success';
        case 'Rechazado': return 'bg-danger';
        case 'Entrevista': return 'bg-info';
        case 'Contratado': return 'bg-primary';
        default: return 'bg-secondary';
    }
}

// Función para cambiar estado de postulación
function cambiarEstadoPostulacion(idPostulante, idPuesto) {
    document.getElementById('id_postulante_estado').value = idPostulante;
    document.getElementById('id_puesto_estado').value = idPuesto;
    
    const modal = new bootstrap.Modal(document.getElementById('modalCambiarEstado'));
    modal.show();
}

// Función para procesar cambio de estado
document.getElementById('formCambiarEstado').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const data = Object.fromEntries(formData.entries());
    
    fetch('<?= base_url('postulaciones/cambiar-estado') ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(result => {
        if (result.success) {
            mostrarModalExito('Estado de postulación actualizado exitosamente');
            bootstrap.Modal.getInstance(document.getElementById('modalCambiarEstado')).hide();
            
            // Actualizar la tabla de postulantes
            const idPuesto = document.getElementById('id_puesto_estado').value;
            verPostulantes(idPuesto);
        } else {
            mostrarAlerta('Error', 'Error al actualizar el estado: ' + result.message, 'danger');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        mostrarAlerta('Error', 'Error al actualizar el estado', 'danger');
    });
});

// Funciones de utilidad para modales
function mostrarAlerta(titulo, mensaje, tipo = 'info') {
    const header = document.getElementById('headerAlerta');
    const tituloElement = document.getElementById('tituloAlerta');
    const mensajeElement = document.getElementById('mensajeAlerta');
    
    // Configurar colores según tipo
    header.className = `modal-header bg-${tipo} text-white`;
    tituloElement.textContent = titulo;
    mensajeElement.textContent = mensaje;
    
    const modal = new bootstrap.Modal(document.getElementById('modalAlerta'));
    modal.show();
}

function mostrarConfirmacion(titulo, mensaje, callback) {
    const tituloElement = document.getElementById('mensajeConfirmacion');
    tituloElement.textContent = mensaje;
    
    const btnConfirmar = document.getElementById('btnConfirmarAccion');
    btnConfirmar.onclick = callback;
    
    const modal = new bootstrap.Modal(document.getElementById('modalConfirmacion'));
    modal.show();
}

function mostrarModalExito(mensaje) {
    const mensajeElement = document.getElementById('mensajeExito');
    mensajeElement.textContent = mensaje;
    
    const modal = new bootstrap.Modal(document.getElementById('modalExito'));
    modal.show();
}
</script>
<?= $this->endSection() ?>
