<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>

<div class="page-wrapper">
    <div class="page-content">
        <!-- Header -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <h4 class="mb-0"><i class="bi bi-calendar-check"></i> Mis Inasistencias</h4>
                    <div class="page-title-right">
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#nuevaInasistenciaModal">
                            <i class="bi bi-plus-circle me-1"></i>Registrar Inasistencia
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="row">
            <div class="col-xl-3 col-lg-6 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h4 class="mb-0">12</h4>
                                <p class="text-muted mb-0">Total Inasistencias</p>
                            </div>
                            <div class="text-primary">
                                <i class="bi bi-calendar-check fs-1"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h4 class="mb-0">8</h4>
                                <p class="text-muted mb-0">Justificadas</p>
                            </div>
                            <div class="text-success">
                                <i class="bi bi-check-circle fs-1"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h4 class="mb-0">3</h4>
                                <p class="text-muted mb-0">Pendientes</p>
                            </div>
                            <div class="text-warning">
                                <i class="bi bi-clock fs-1"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h4 class="mb-0">1</h4>
                                <p class="text-muted mb-0">Sin Justificar</p>
                            </div>
                            <div class="text-danger">
                                <i class="bi bi-x-circle fs-1"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filtros -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Filtros de Búsqueda</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label">Estado</label>
                                    <select class="form-select" id="filtroEstado">
                                        <option value="">Todos los estados</option>
                                        <option value="JUSTIFICADA">Justificada</option>
                                        <option value="PENDIENTE">Pendiente</option>
                                        <option value="SIN_JUSTIFICAR">Sin Justificar</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label">Tipo de Inasistencia</label>
                                    <select class="form-select" id="filtroTipo">
                                        <option value="">Todos los tipos</option>
                                        <option value="ENFERMEDAD">Enfermedad</option>
                                        <option value="PERSONAL">Personal</option>
                                        <option value="LABORAL">Laboral</option>
                                        <option value="OTRO">Otro</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Rango de Fechas</label>
                                    <div class="input-group">
                                        <input type="date" class="form-control" id="fechaInicio" placeholder="Desde">
                                        <span class="input-group-text">hasta</span>
                                        <input type="date" class="form-control" id="fechaFin" placeholder="Hasta">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="mb-3">
                                    <label class="form-label">&nbsp;</label>
                                    <button class="btn btn-outline-primary w-100" onclick="filtrarInasistencias()">
                                        <i class="bi bi-search me-1"></i>Filtrar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Lista de Inasistencias -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Historial de Inasistencias</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover" id="inasistenciasTable">
                                <thead>
                                    <tr>
                                        <th>Fecha</th>
                                        <th>Tipo</th>
                                        <th>Motivo</th>
                                        <th>Estado</th>
                                        <th>Días</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Inasistencia 1 -->
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <i class="bi bi-calendar-event text-primary me-2"></i>
                                                <div>
                                                    <h6 class="mb-0">15/08/2025</h6>
                                                    <small class="text-muted">Lunes</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td><span class="badge bg-info">ENFERMEDAD</span></td>
                                        <td>Gripe estacional con fiebre</td>
                                        <td><span class="badge bg-success">JUSTIFICADA</span></td>
                                        <td><span class="badge bg-secondary">1 día</span></td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <button class="btn btn-sm btn-outline-primary" onclick="verInasistencia(1)" title="Ver detalles">
                                                    <i class="bi bi-eye"></i>
                                                </button>
                                                <button class="btn btn-sm btn-outline-info" onclick="descargarJustificacion(1)" title="Descargar justificación">
                                                    <i class="bi bi-download"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <!-- Inasistencia 2 -->
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <i class="bi bi-calendar-event text-primary me-2"></i>
                                                <div>
                                                    <h6 class="mb-0">12/08/2025</h6>
                                                    <small class="text-muted">Viernes</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td><span class="badge bg-warning">PERSONAL</span></td>
                                        <td>Trámite bancario urgente</td>
                                        <td><span class="badge bg-warning">PENDIENTE</span></td>
                                        <td><span class="badge bg-secondary">0.5 días</span></td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <button class="btn btn-sm btn-outline-primary" onclick="verInasistencia(2)" title="Ver detalles">
                                                    <i class="bi bi-eye"></i>
                                                </button>
                                                <button class="btn btn-sm btn-outline-warning" onclick="editarInasistencia(2)" title="Editar">
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <!-- Inasistencia 3 -->
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <i class="bi bi-calendar-event text-primary me-2"></i>
                                                <div>
                                                    <h6 class="mb-0">08/08/2025</h6>
                                                    <small class="text-muted">Lunes</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td><span class="badge bg-success">LABORAL</span></td>
                                        <td>Capacitación externa autorizada</td>
                                        <td><span class="badge bg-success">JUSTIFICADA</span></td>
                                        <td><span class="badge bg-secondary">1 día</span></td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <button class="btn btn-sm btn-outline-primary" onclick="verInasistencia(3)" title="Ver detalles">
                                                    <i class="bi bi-eye"></i>
                                                </button>
                                                <button class="btn btn-sm btn-outline-info" onclick="descargarJustificacion(3)" title="Descargar justificación">
                                                    <i class="bi bi-download"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <!-- Inasistencia 4 -->
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <i class="bi bi-calendar-event text-primary me-2"></i>
                                                <div>
                                                    <h6 class="mb-0">05/08/2025</h6>
                                                    <small class="text-muted">Viernes</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td><span class="badge bg-danger">OTRO</span></td>
                                        <td>Problema de transporte</td>
                                        <td><span class="badge bg-danger">SIN JUSTIFICAR</span></td>
                                        <td><span class="badge bg-secondary">0.5 días</span></td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <button class="btn btn-sm btn-outline-primary" onclick="verInasistencia(4)" title="Ver detalles">
                                                    <i class="bi bi-eye"></i>
                                                </button>
                                                <button class="btn btn-sm btn-outline-warning" onclick="editarInasistencia(4)" title="Editar y justificar">
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Paginación -->
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <div>
                                <small class="text-muted">Mostrando 1-4 de 12 inasistencias</small>
                            </div>
                            <nav>
                                <ul class="pagination pagination-sm mb-0">
                                    <li class="page-item disabled">
                                        <span class="page-link">Anterior</span>
                                    </li>
                                    <li class="page-item active">
                                        <span class="page-link">1</span>
                                    </li>
                                    <li class="page-item">
                                        <a class="page-link" href="#">2</a>
                                    </li>
                                    <li class="page-item">
                                        <a class="page-link" href="#">3</a>
                                    </li>
                                    <li class="page-item">
                                        <a class="page-link" href="#">Siguiente</a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Nueva Inasistencia -->
<div class="modal fade" id="nuevaInasistenciaModal" tabindex="-1" aria-labelledby="nuevaInasistenciaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="nuevaInasistenciaModalLabel">Registrar Nueva Inasistencia</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= base_url('empleado/guardar-inasistencia') ?>" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Fecha de Inasistencia <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" name="fecha_inasistencia" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tipo de Inasistencia <span class="text-danger">*</span></label>
                            <select class="form-select" name="tipo_inasistencia" required>
                                <option value="">Seleccionar tipo</option>
                                <option value="ENFERMEDAD">Enfermedad</option>
                                <option value="PERSONAL">Personal</option>
                                <option value="LABORAL">Laboral</option>
                                <option value="OTRO">Otro</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Duración <span class="text-danger">*</span></label>
                            <select class="form-select" name="duracion" required>
                                <option value="">Seleccionar duración</option>
                                <option value="0.5">Media jornada (0.5 días)</option>
                                <option value="1">Jornada completa (1 día)</option>
                                <option value="1.5">1.5 días</option>
                                <option value="2">2 días</option>
                                <option value="3">3 días</option>
                                <option value="4">4 días</option>
                                <option value="5">5 días</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Hora de Inicio (si es media jornada)</label>
                            <input type="time" class="form-control" name="hora_inicio">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Motivo Detallado <span class="text-danger">*</span></label>
                            <textarea class="form-control" name="motivo" rows="3" required placeholder="Describe detalladamente el motivo de la inasistencia..."></textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Documentos de Justificación</label>
                            <input type="file" class="form-control" name="documentos_justificacion" multiple accept=".pdf,.doc,.docx,.jpg,.png">
                            <small class="text-muted">Adjunta certificados médicos, documentos oficiales, o cualquier evidencia que justifique la inasistencia</small>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Observaciones Adicionales</label>
                            <textarea class="form-control" name="observaciones" rows="2" placeholder="Cualquier información adicional que consideres relevante..."></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-send me-1"></i>Registrar Inasistencia
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Ver Detalles -->
<div class="modal fade" id="verInasistenciaModal" tabindex="-1" aria-labelledby="verInasistenciaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="verInasistenciaModalLabel">Detalles de la Inasistencia</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="detallesInasistencia">
                    <!-- Los detalles se cargarán aquí dinámicamente -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-info" onclick="descargarJustificacion()">
                    <i class="bi bi-download me-1"></i>Descargar Justificación
                </button>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    function filtrarInasistencias() {
        const estado = document.getElementById('filtroEstado').value;
        const tipo = document.getElementById('filtroTipo').value;
        const fechaInicio = document.getElementById('fechaInicio').value;
        const fechaFin = document.getElementById('fechaFin').value;
        
        // Aquí iría la lógica de filtrado
        console.log('Filtros aplicados:', { estado, tipo, fechaInicio, fechaFin });
        
        // Mostrar mensaje temporal
        alert('Filtros aplicados. Funcionalidad de filtrado en desarrollo.');
    }
    
    function verInasistencia(id) {
        // Simulación de datos de la inasistencia
        const inasistencias = {
            1: {
                fecha: '15/08/2025',
                tipo: 'ENFERMEDAD',
                motivo: 'Gripe estacional con fiebre alta. Presenté certificado médico del Dr. Juan Pérez del Centro Médico San José.',
                duracion: '1 día',
                estado: 'JUSTIFICADA',
                justificacion: 'Certificado médico válido hasta el 16/08/2025',
                observaciones: 'El empleado presentó síntomas desde el domingo anterior',
                documentos: ['certificado_medico.pdf', 'receta_medica.pdf']
            },
            2: {
                fecha: '12/08/2025',
                tipo: 'PERSONAL',
                motivo: 'Trámite bancario urgente relacionado con hipoteca. El banco solo atendía en horario laboral.',
                duracion: '0.5 días',
                estado: 'PENDIENTE',
                justificacion: 'Comprobante de trámite bancario',
                observaciones: 'Se solicitó autorización previa pero no se pudo confirmar',
                documentos: ['comprobante_bancario.pdf']
            }
        };
        
        const inasistencia = inasistencias[id];
        if (inasistencia) {
            document.getElementById('detallesInasistencia').innerHTML = `
                <div class="row">
                    <div class="col-md-6">
                        <h6><i class="bi bi-calendar-event text-primary me-2"></i>Fecha</h6>
                        <p>${inasistencia.fecha}</p>
                    </div>
                    <div class="col-md-6">
                        <h6><i class="bi bi-tag text-info me-2"></i>Tipo</h6>
                        <span class="badge bg-info">${inasistencia.tipo}</span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <h6><i class="bi bi-clock text-warning me-2"></i>Duración</h6>
                        <p>${inasistencia.duracion}</p>
                    </div>
                    <div class="col-md-6">
                        <h6><i class="bi bi-check-circle text-success me-2"></i>Estado</h6>
                        <span class="badge bg-success">${inasistencia.estado}</span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <h6><i class="bi bi-chat-text text-primary me-2"></i>Motivo</h6>
                        <p>${inasistencia.motivo}</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <h6><i class="bi bi-file-text text-info me-2"></i>Justificación</h6>
                        <p>${inasistencia.justificacion}</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <h6><i class="bi bi-info-circle text-warning me-2"></i>Observaciones</h6>
                        <p>${inasistencia.observaciones}</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <h6><i class="bi bi-paperclip text-secondary me-2"></i>Documentos Adjuntos</h6>
                        <ul class="list-unstyled">
                            ${inasistencia.documentos.map(doc => `<li><i class="bi bi-file-earmark-text me-2"></i>${doc}</li>`).join('')}
                        </ul>
                    </div>
                </div>
            `;
            
            $('#verInasistenciaModal').modal('show');
        }
    }
    
    function editarInasistencia(id) {
        // Aquí iría la lógica para editar la inasistencia
        alert(`Editar inasistencia ID: ${id}. Funcionalidad en desarrollo.`);
    }
    
    function descargarJustificacion(id) {
        // Aquí iría la lógica para descargar la justificación
        alert(`Descargando justificación de inasistencia ID: ${id}. Funcionalidad en desarrollo.`);
    }
</script>
<?= $this->endSection() ?>
