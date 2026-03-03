<?php $sidebar = 'sidebar_admin_th'; ?>
<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>

<div class="page-wrapper">
    <div class="page-content">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="mb-0">Gestión de Vacantes</h4>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalVacante">
                                <i class="bi bi-plus-circle"></i> Nueva Vacante
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Estadísticas -->
                        <div class="row mb-4">
                            <div class="col-md-3">
                                <div class="card bg-primary text-white">
                                    <div class="card-body">
                                        <h5 class="card-title">Total Vacantes</h5>
                                        <h3 class="mb-0"><?= count($vacantes ?? []) ?></h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-success text-white">
                                    <div class="card-body">
                                        <h5 class="card-title">Abiertas</h5>
                                        <h3 class="mb-0"><?= count(array_filter($vacantes ?? [], function($v) { return $v['estado'] == 'Abierta'; })) ?></h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-warning text-white">
                                    <div class="card-body">
                                        <h5 class="card-title">Cerradas</h5>
                                        <h3 class="mb-0"><?= count(array_filter($vacantes ?? [], function($v) { return $v['estado'] == 'Cerrada'; })) ?></h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-danger text-white">
                                    <div class="card-body">
                                        <h5 class="card-title">Canceladas</h5>
                                        <h3 class="mb-0"><?= count(array_filter($vacantes ?? [], function($v) { return $v['estado'] == 'Cancelada'; })) ?></h3>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Filtros -->
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <select class="form-select" id="filtroEstado">
                                    <option value="">Todos los estados</option>
                                    <option value="Abierta">Abierta</option>
                                    <option value="Cerrada">Cerrada</option>
                                    <option value="Cancelada">Cancelada</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select class="form-select" id="filtroPuesto">
                                    <option value="">Todos los puestos</option>
                                    <?php foreach ($puestos ?? [] as $puesto): ?>
                                    <option value="<?= $puesto['nombre'] ?>"><?= $puesto['nombre'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <input type="date" class="form-control" id="filtroFecha" placeholder="Filtrar por fecha">
                            </div>
                            <div class="col-md-3">
                                <button type="button" class="btn btn-secondary" onclick="limpiarFiltros()">
                                    <i class="bi bi-x-circle"></i> Limpiar Filtros
                                </button>
                            </div>
                        </div>

                        <!-- Tabla de Vacantes -->
                        <div class="table-responsive">
                            <table class="table table-striped table-hover" id="tablaVacantes">
                                <thead class="table-dark">
                                    <tr>
                                        <th>ID</th>
                                        <th>Puesto</th>
                                        <th>Departamento</th>
                                        <th>Estado</th>
                                        <th>Fecha Publicación</th>
                                        <th>Fecha Cierre</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($vacantes ?? [] as $vacante): ?>
                                    <tr>
                                        <td><?= $vacante['id_vacante'] ?></td>
                                        <td>
                                            <strong><?= $vacante['puesto_nombre'] ?? 'Sin asignar' ?></strong>
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary"><?= $vacante['departamento_nombre'] ?? 'Sin asignar' ?></span>
                                        </td>
                                        <td>
                                            <?php
                                            $estadoClass = '';
                                            switch($vacante['estado']) {
                                                case 'Abierta': $estadoClass = 'bg-success'; break;
                                                case 'Cerrada': $estadoClass = 'bg-warning'; break;
                                                case 'Cancelada': $estadoClass = 'bg-danger'; break;
                                                default: $estadoClass = 'bg-secondary';
                                            }
                                            ?>
                                            <span class="badge <?= $estadoClass ?>"><?= $vacante['estado'] ?></span>
                                        </td>
                                        <td><?= date('d/m/Y', strtotime($vacante['fecha_publicacion'])) ?></td>
                                        <td>
                                            <?php if ($vacante['fecha_cierre']): ?>
                                                <?= date('d/m/Y', strtotime($vacante['fecha_cierre'])) ?>
                                            <?php else: ?>
                                                <span class="text-muted">Sin fecha de cierre</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <button type="button" class="btn btn-sm btn-outline-primary" onclick="verVacante(<?= $vacante['id_vacante'] ?>)">
                                                    <i class="bi bi-eye"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline-warning" onclick="editarVacante(<?= $vacante['id_vacante'] ?>)">
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline-info" onclick="verCandidatos(<?= $vacante['id_vacante'] ?>)">
                                                    <i class="bi bi-people"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline-success" onclick="generarAnuncio(<?= $vacante['id_vacante'] ?>, '<?= addslashes($vacante['puesto_nombre'] ?? 'Puesto') ?>', '<?= addslashes($vacante['departamento_nombre'] ?? 'Sin asignar') ?>', '<?= addslashes($vacante['descripcion'] ?? '') ?>')" title="Generar Anuncio">
                                                    <i class="bi bi-megaphone"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline-danger" onclick="eliminarVacante(<?= $vacante['id_vacante'] ?>)">
                                                    <i class="bi bi-trash"></i>
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

<!-- Modal para Crear/Editar Vacante -->
<div class="modal fade" id="modalVacante" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Nueva Vacante</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formVacante">
                <div class="modal-body">
                    <input type="hidden" id="id_vacante" name="id_vacante">
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="id_puesto" class="form-label">Puesto *</label>
                                <select class="form-select" id="id_puesto" name="id_puesto" required>
                                    <option value="">Seleccionar puesto...</option>
                                    <?php foreach ($puestos ?? [] as $puesto): ?>
                                    <option value="<?= $puesto['id_puesto'] ?>"><?= $puesto['nombre'] ?> - <?= $puesto['departamento_nombre'] ?? 'Sin departamento' ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="estado" class="form-label">Estado *</label>
                                <select class="form-select" id="estado" name="estado" required>
                                    <option value="Abierta">Abierta</option>
                                    <option value="Cerrada">Cerrada</option>
                                    <option value="Cancelada">Cancelada</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="fecha_publicacion" class="form-label">Fecha de Publicación *</label>
                                <input type="date" class="form-control" id="fecha_publicacion" name="fecha_publicacion" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="fecha_cierre" class="form-label">Fecha de Cierre</label>
                                <input type="date" class="form-control" id="fecha_cierre" name="fecha_cierre">
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripción</label>
                        <textarea class="form-control" id="descripcion" name="descripcion" rows="3"></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label for="requisitos" class="form-label">Requisitos</label>
                        <textarea class="form-control" id="requisitos" name="requisitos" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para Ver Vacante -->
<div class="modal fade" id="modalVerVacante" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detalles de la Vacante</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="detallesVacante">
                <!-- Se llenará dinámicamente -->
            </div>
        </div>
    </div>
</div>

<!-- Modal para Ver Candidatos -->
<div class="modal fade" id="modalCandidatos" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Candidatos de la Vacante</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="listaCandidatos">
                <!-- Se llenará dinámicamente -->
            </div>
        </div>
    </div>
</div>

<!-- Modal para Generar Anuncio / Flyer -->
<div class="modal fade" id="modalFlyer" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="bi bi-megaphone me-2"></i>Generar Anuncio de Vacante</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0">
                <!-- Contenido del Flyer (se captura con html2canvas) -->
                <div id="flyerContainer" style="background: linear-gradient(135deg, #1e3a5f 0%, #2c5282 50%, #1a365d 100%); padding: 40px; color: white; position: relative; overflow: hidden;">
                    <!-- Patrón decorativo -->
                    <div style="position: absolute; top: -50px; right: -50px; width: 200px; height: 200px; border-radius: 50%; background: rgba(255,255,255,0.05);"></div>
                    <div style="position: absolute; bottom: -80px; left: -80px; width: 300px; height: 300px; border-radius: 50%; background: rgba(255,255,255,0.03);"></div>
                    
                    <!-- Header con Logo -->
                    <div class="text-center mb-4">
                        <img src="<?= base_url('assets/images/logos/logo.png') ?>" alt="Logo Instituto" style="max-height: 80px; margin-bottom: 15px;" onerror="this.style.display='none'">
                        <div style="font-size: 12px; text-transform: uppercase; letter-spacing: 3px; opacity: 0.8;">Instituto Tecnológico Superior ITSI</div>
                    </div>
                    
                    <!-- Badge -->
                    <div class="text-center mb-3">
                        <span style="background: #e53e3e; color: white; padding: 6px 20px; border-radius: 20px; font-size: 13px; font-weight: bold; text-transform: uppercase; letter-spacing: 2px;">¡Estamos Contratando!</span>
                    </div>
                    
                    <!-- Título del Puesto -->
                    <div class="text-center mb-3">
                        <h2 id="flyerTitulo" style="font-size: 28px; font-weight: 800; margin: 0; text-shadow: 0 2px 4px rgba(0,0,0,0.3);"></h2>
                    </div>
                    
                    <!-- Departamento -->
                    <div class="text-center mb-4">
                        <span id="flyerDepartamento" style="background: rgba(255,255,255,0.15); padding: 6px 18px; border-radius: 15px; font-size: 14px;"></span>
                    </div>
                    
                    <!-- Descripción -->
                    <div id="flyerDescripcion" style="background: rgba(255,255,255,0.1); border-radius: 12px; padding: 20px; margin-bottom: 25px; font-size: 14px; line-height: 1.6; text-align: center; backdrop-filter: blur(10px);"></div>
                    
                    <!-- QR y CTA -->
                    <div class="row align-items-center">
                        <div class="col-7 text-center">
                            <p style="font-size: 16px; font-weight: 600; margin-bottom: 5px;"><i class="bi bi-arrow-right-circle me-2"></i>Escanea el código QR para postularte</p>
                            <p style="font-size: 12px; opacity: 0.7;">O visita nuestra página web</p>
                        </div>
                        <div class="col-5 text-center">
                            <div style="background: white; padding: 12px; border-radius: 12px; display: inline-block; box-shadow: 0 4px 15px rgba(0,0,0,0.3);">
                                <div id="flyerQRCode"></div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Footer -->
                    <div class="text-center mt-4" style="border-top: 1px solid rgba(255,255,255,0.2); padding-top: 15px;">
                        <p style="font-size: 11px; opacity: 0.6; margin: 0;">Síguenos en nuestras redes sociales · www.itsi.edu.ec</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-success" id="btnDescargarFlyer" onclick="descargarFlyer()">
                    <i class="bi bi-download me-1"></i> Descargar Imagen
                </button>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<!-- CDN: QRCode.js para generar códigos QR -->
<script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
<!-- CDN: html2canvas para capturar el DOM como imagen -->
<script src="https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js"></script>
<script>
$(document).ready(function() {
    // Inicializar DataTable
    $('#tablaVacantes').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json'
        },
        order: [[4, 'desc']]
    });

    // Filtros
    $('#filtroEstado, #filtroPuesto, #filtroFecha').on('change', function() {
        aplicarFiltros();
    });

    // Manejar envío del formulario
    $('#formVacante').on('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const isEdit = $('#id_vacante').val() !== '';
        
        $.ajax({
            url: isEdit ? '/admin-th/vacantes/actualizar' : '/admin-th/vacantes/crear',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Éxito',
                        text: response.message
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message
                    });
                }
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error al procesar la solicitud'
                });
            }
        });
    });
});

function aplicarFiltros() {
    const estado = $('#filtroEstado').val();
    const puesto = $('#filtroPuesto').val();
    const fecha = $('#filtroFecha').val();
    
    $('#tablaVacantes').DataTable().columns().every(function() {
        const column = this;
        let searchTerm = '';
        
        if (column.index() === 3 && estado) { // Columna estado
            searchTerm = estado;
        } else if (column.index() === 1 && puesto) { // Columna puesto
            searchTerm = puesto;
        } else if (column.index() === 4 && fecha) { // Columna fecha
            searchTerm = fecha;
        }
        
        column.search(searchTerm).draw();
    });
}

function limpiarFiltros() {
    $('#filtroEstado, #filtroPuesto').val('');
    $('#filtroFecha').val('');
    $('#tablaVacantes').DataTable().search('').columns().search('').draw();
}

function verVacante(id) {
    $.ajax({
        url: `/admin-th/vacantes/${id}`,
        type: 'GET',
        success: function(response) {
            if (response.success) {
                $('#detallesVacante').html(response.html);
                $('#modalVerVacante').modal('show');
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: response.message
                });
            }
        }
    });
}

function editarVacante(id) {
    $.ajax({
        url: `/admin-th/vacantes/${id}/editar`,
        type: 'GET',
        success: function(response) {
            if (response.success) {
                $('#id_vacante').val(response.vacante.id_vacante);
                $('#id_puesto').val(response.vacante.id_puesto);
                $('#estado').val(response.vacante.estado);
                $('#fecha_publicacion').val(response.vacante.fecha_publicacion);
                $('#fecha_cierre').val(response.vacante.fecha_cierre);
                $('#descripcion').val(response.vacante.descripcion);
                $('#requisitos').val(response.vacante.requisitos);
                
                $('#modalTitle').text('Editar Vacante');
                $('#modalVacante').modal('show');
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: response.message
                });
            }
        }
    });
}

function verCandidatos(id) {
    $.ajax({
        url: `/admin-th/vacantes/${id}/candidatos`,
        type: 'GET',
        success: function(response) {
            if (response.success) {
                $('#listaCandidatos').html(response.html);
                $('#modalCandidatos').modal('show');
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: response.message
                });
            }
        }
    });
}

function eliminarVacante(id) {
    Swal.fire({
        title: '¿Estás seguro?',
        text: "Esta acción no se puede deshacer",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: `/admin-th/vacantes/${id}/eliminar`,
                type: 'POST',
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Eliminado',
                            text: response.message
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: response.message
                        });
                    }
                }
            });
        }
    });
}

// ===== Generador de Anuncio / Flyer =====
function generarAnuncio(id, titulo, departamento, descripcion) {
    // Limpiar QR anterior
    const qrContainer = document.getElementById('flyerQRCode');
    qrContainer.innerHTML = '';
    
    // Llenar datos del flyer
    document.getElementById('flyerTitulo').textContent = titulo;
    document.getElementById('flyerDepartamento').textContent = '📍 Departamento: ' + departamento;
    document.getElementById('flyerDescripcion').innerHTML = descripcion 
        ? descripcion.replace(/\n/g, '<br>') 
        : 'Buscamos un profesional comprometido para unirse a nuestro equipo. ¡Envía tu postulación escaneando el código QR!';
    
    // Generar QR con la URL pública de postulación
    const urlPostulacion = '<?= base_url('postularse/') ?>' + id;
    new QRCode(qrContainer, {
        text: urlPostulacion,
        width: 150,
        height: 150,
        colorDark: '#1e3a5f',
        colorLight: '#ffffff',
        correctLevel: QRCode.CorrectLevel.H
    });
    
    // Mostrar modal
    const modal = new bootstrap.Modal(document.getElementById('modalFlyer'));
    modal.show();
}

function descargarFlyer() {
    const btn = document.getElementById('btnDescargarFlyer');
    const textoOriginal = btn.innerHTML;
    btn.innerHTML = '<i class="bi bi-hourglass-split me-1"></i> Generando...';
    btn.disabled = true;
    
    const flyerElement = document.getElementById('flyerContainer');
    
    html2canvas(flyerElement, {
        scale: 2,
        useCORS: true,
        allowTaint: true,
        backgroundColor: null
    }).then(canvas => {
        const link = document.createElement('a');
        const titulo = document.getElementById('flyerTitulo').textContent || 'vacante';
        link.download = 'Anuncio_' + titulo.replace(/\s+/g, '_') + '.png';
        link.href = canvas.toDataURL('image/png');
        link.click();
        
        Swal.fire({
            icon: 'success',
            title: '¡Imagen descargada!',
            text: 'El anuncio se ha descargado como imagen PNG.',
            timer: 2000,
            showConfirmButton: false
        });
    }).catch(error => {
        console.error('Error al generar imagen:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'No se pudo generar la imagen del anuncio.'
        });
    }).finally(() => {
        btn.innerHTML = textoOriginal;
        btn.disabled = false;
    });
}
</script>
<?= $this->endSection() ?> 