<?php $sidebar = 'sidebar_docente'; ?>
<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>

<div class="page-wrapper">
    <div class="page-content">
        <!-- Header -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <h4 class="mb-0"><i class="bi bi-award"></i> Mis Certificados</h4>
                    <div class="page-title-right">
                        <button type="button" class="btn btn-primary" onclick="solicitarCertificado()">
                            <i class="bi bi-plus-circle"></i> Solicitar Certificado
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row">
            <div class="col-xl-3 col-lg-6 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="me-3">
                                <span class="round-8 bg-primary rounded-circle me-2 d-inline-block"></span>
                                <div class="stat-digit d-flex">
                                    <h5 class="font-weight-semibold mb-0"><?= $estadisticas['total_certificados'] ?? 0 ?></h5>
                                </div>
                                <div class="stat-digit d-flex">
                                    <h5 class="font-weight-semibold mb-0">Certificados</h5>
                                </div>
                            </div>
                            <div class="ms-auto">
                                <i class="bi bi-award text-primary" style="font-size: 2rem;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="me-3">
                                <span class="round-8 bg-success rounded-circle me-2 d-inline-block"></span>
                                <div class="stat-digit d-flex">
                                    <h5 class="font-weight-semibold mb-0"><?= $estadisticas['certificados_activos'] ?? 0 ?></h5>
                                </div>
                                <div class="stat-digit d-flex">
                                    <h5 class="font-weight-semibold mb-0">Activos</h5>
                                </div>
                            </div>
                            <div class="ms-auto">
                                <i class="bi bi-check-circle text-success" style="font-size: 2rem;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="me-3">
                                <span class="round-8 bg-warning rounded-circle me-2 d-inline-block"></span>
                                <div class="stat-digit d-flex">
                                    <h5 class="font-weight-semibold mb-0"><?= $estadisticas['certificados_vencidos'] ?? 0 ?></h5>
                                </div>
                                <div class="stat-digit d-flex">
                                    <h5 class="font-weight-semibold mb-0">Por Vencer</h5>
                                </div>
                            </div>
                            <div class="ms-auto">
                                <i class="bi bi-exclamation-triangle text-warning" style="font-size: 2rem;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="me-3">
                                <span class="round-8 bg-info rounded-circle me-2 d-inline-block"></span>
                                <div class="stat-digit d-flex">
                                    <h5 class="font-weight-semibold mb-0"><?= $estadisticas['solicitudes_pendientes'] ?? 0 ?></h5>
                                </div>
                                <div class="stat-digit d-flex">
                                    <h5 class="font-weight-semibold mb-0">Pendientes</h5>
                                </div>
                            </div>
                            <div class="ms-auto">
                                <i class="bi bi-clock text-info" style="font-size: 2rem;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <label for="filtroTipo" class="form-label">Tipo de Certificado</label>
                                <select class="form-select" id="filtroTipo">
                                    <option value="">Todos los tipos</option>
                                    <option value="Capacitación">Capacitación</option>
                                    <option value="Competencia">Competencia</option>
                                    <option value="Evaluación">Evaluación</option>
                                    <option value="Servicio">Servicio</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="filtroEstado" class="form-label">Estado</label>
                                <select class="form-select" id="filtroEstado">
                                    <option value="">Todos los estados</option>
                                    <option value="Activo">Activo</option>
                                    <option value="Vencido">Vencido</option>
                                    <option value="Por Vencer">Por Vencer</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="filtroFecha" class="form-label">Fecha Emisión</label>
                                <input type="date" class="form-control" id="filtroFecha">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">&nbsp;</label>
                                <button type="button" class="btn btn-secondary d-block w-100" onclick="aplicarFiltros()">
                                    <i class="bi bi-funnel"></i> Aplicar Filtros
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Certificates Table -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Mis Certificados</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped" id="tablaCertificados">
                                <thead>
                                    <tr>
                                        <th>Certificado</th>
                                        <th>Tipo</th>
                                        <th>Fecha Emisión</th>
                                        <th>Fecha Vencimiento</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($certificados as $certificado): ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <i class="bi bi-award me-2 text-warning"></i>
                                                <div>
                                                    <h6 class="mb-0"><?= $certificado['nombre'] ?></h6>
                                                    <small class="text-muted"><?= $certificado['descripcion'] ?></small>
                                                </div>
                                            </div>
                                        </td>
                                        <td><?= $certificado['tipo'] ?></td>
                                        <td><?= date('d/m/Y', strtotime($certificado['fecha_emision'])) ?></td>
                                        <td><?= date('d/m/Y', strtotime($certificado['fecha_vencimiento'])) ?></td>
                                        <td>
                                            <span class="badge bg-<?= $certificado['estado'] == 'Activo' ? 'success' : ($certificado['estado'] == 'Por Vencer' ? 'warning' : 'danger') ?>">
                                                <?= $certificado['estado'] ?>
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <button type="button" class="btn btn-sm btn-outline-primary" onclick="verCertificado(<?= $certificado['id_certificado'] ?>)">
                                                    <i class="bi bi-eye"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline-success" onclick="descargarCertificado(<?= $certificado['id_certificado'] ?>)">
                                                    <i class="bi bi-download"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline-info" onclick="validarCertificado(<?= $certificado['id_certificado'] ?>)">
                                                    <i class="bi bi-shield-check"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Solicitar Certificado -->
<div class="modal fade" id="modalSolicitarCertificado" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Solicitar Nuevo Certificado</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formSolicitarCertificado">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="tipo_certificado" class="form-label">Tipo de Certificado</label>
                                <select class="form-select" id="tipo_certificado" name="tipo_certificado" required>
                                    <option value="">Seleccionar tipo</option>
                                    <option value="Capacitación">Certificado de Capacitación</option>
                                    <option value="Competencia">Certificado de Competencia</option>
                                    <option value="Evaluación">Certificado de Evaluación</option>
                                    <option value="Servicio">Certificado de Servicio</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="motivo" class="form-label">Motivo de Solicitud</label>
                                <select class="form-select" id="motivo" name="motivo" required>
                                    <option value="">Seleccionar motivo</option>
                                    <option value="Requisito Laboral">Requisito Laboral</option>
                                    <option value="Actualización">Actualización</option>
                                    <option value="Reemplazo">Reemplazo</option>
                                    <option value="Otro">Otro</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="descripcion_solicitud" class="form-label">Descripción Adicional</label>
                        <textarea class="form-control" id="descripcion_solicitud" name="descripcion_solicitud" rows="3" placeholder="Proporcione detalles adicionales sobre su solicitud..."></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="urgencia" class="form-label">Nivel de Urgencia</label>
                        <select class="form-select" id="urgencia" name="urgencia" required>
                            <option value="Normal">Normal (5-7 días)</option>
                            <option value="Urgente">Urgente (2-3 días)</option>
                            <option value="Muy Urgente">Muy Urgente (24 horas)</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Solicitar Certificado</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Ver Certificado -->
<div class="modal fade" id="modalVerCertificado" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detalles del Certificado</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="detallesCertificado">
                <!-- Contenido dinámico -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" onclick="descargarCertificadoActual()">Descargar</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize DataTable
    $('#tablaCertificados').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json'
        },
        pageLength: 10,
        order: [[2, 'desc']]
    });

    // Form submission
    $('#formSolicitarCertificado').on('submit', function(e) {
        e.preventDefault();
        solicitarCertificado();
    });
});

function aplicarFiltros() {
    const tipo = $('#filtroTipo').val();
    const estado = $('#filtroEstado').val();
    const fecha = $('#filtroFecha').val();
    
    // Aplicar filtros a la tabla
    const tabla = $('#tablaCertificados').DataTable();
    tabla.draw();
}

function solicitarCertificado() {
    const formData = new FormData(document.getElementById('formSolicitarCertificado'));
    
    Swal.fire({
        title: 'Enviando solicitud...',
        text: 'Por favor espere',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    fetch('<?= base_url('docente/certificados/solicitar') ?>', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Solicitud enviada',
                text: data.message,
                confirmButtonText: 'Aceptar'
            }).then(() => {
                $('#modalSolicitarCertificado').modal('hide');
                location.reload();
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: data.message,
                confirmButtonText: 'Aceptar'
            });
        }
    })
    .catch(error => {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Error al enviar la solicitud',
            confirmButtonText: 'Aceptar'
        });
    });
}

function verCertificado(idCertificado) {
    fetch(`<?= base_url('docente/certificados/ver/') ?>${idCertificado}`)
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            $('#detallesCertificado').html(data.html);
            $('#modalVerCertificado').modal('show');
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: data.message,
                confirmButtonText: 'Aceptar'
            });
        }
    });
}

function descargarCertificado(idCertificado) {
    window.open(`<?= base_url('docente/certificados/descargar/') ?>${idCertificado}`, '_blank');
}

function validarCertificado(idCertificado) {
    fetch(`<?= base_url('docente/certificados/validar/') ?>${idCertificado}`)
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Certificado Válido',
                text: data.message,
                confirmButtonText: 'Aceptar'
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Certificado Inválido',
                text: data.message,
                confirmButtonText: 'Aceptar'
            });
        }
    });
}

function descargarCertificadoActual() {
    // Implementar descarga del certificado actual
    Swal.fire({
        icon: 'info',
        title: 'Descarga iniciada',
        text: 'El certificado se está descargando',
        confirmButtonText: 'Aceptar'
    });
}
</script>

<?= $this->endSection() ?> 