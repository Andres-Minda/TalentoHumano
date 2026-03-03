<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>

<div class="page-wrapper">
    <div class="page-content">
        <?php if (!file_exists(WRITEPATH . 'token.json')): ?>
        <div class="row mb-3">
            <div class="col-12">
                <div class="alert alert-warning d-flex align-items-center justify-content-between" role="alert">
                    <div>
                        <i class="ti ti-cloud-off me-2"></i>
                        <strong>Google Drive no conectado.</strong> Los CVs no se pueden recibir hasta que conectes tu cuenta.
                    </div>
                    <a href="<?= base_url('admin-th/conectar-google') ?>" class="btn btn-warning btn-sm">
                        <i class="ti ti-brand-google-drive me-1"></i> Conectar Google Drive
                    </a>
                </div>
            </div>
        </div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('success')): ?>
        <div class="row mb-3">
            <div class="col-12">
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="ti ti-check me-2"></i><?= session()->getFlashdata('success') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
        </div>
        <?php endif; ?>
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
                                    <option value="" disabled selected>Seleccionar nivel...</option>
                                    <option value="Sin experiencia">Sin experiencia</option>
                                    <option value="Menos de 1 año">Menos de 1 año</option>
                                    <option value="1 a 3 años">1 a 3 años</option>
                                    <option value="3 a 5 años">3 a 5 años</option>
                                    <option value="Más de 5 años">Más de 5 años</option>
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

<!-- Modal para Generar Anuncio / Flyer -->
<div class="modal fade" id="modalFlyer" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="ti ti-speakerphone me-2"></i>Generar Anuncio del Puesto</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0">
                <div id="flyerContainer" style="background: linear-gradient(135deg, #1e3a5f 0%, #2c5282 50%, #1a365d 100%); padding: 40px; color: white; position: relative; overflow: hidden;">
                    <div style="position: absolute; top: -50px; right: -50px; width: 200px; height: 200px; border-radius: 50%; background: rgba(255,255,255,0.05);"></div>
                    <div style="position: absolute; bottom: -80px; left: -80px; width: 300px; height: 300px; border-radius: 50%; background: rgba(255,255,255,0.03);"></div>
                    <div class="text-center mb-4">
                        <img src="<?= base_url('sistema/assets/images/logos/logo_instituto.png') ?>" alt="Logo Instituto" style="max-height: 80px; margin-bottom: 15px;" onerror="this.style.display='none'">
                        <div style="font-size: 12px; text-transform: uppercase; letter-spacing: 3px; opacity: 0.8;">Instituto Tecnológico Superior ITSI</div>
                    </div>
                    <div class="text-center mb-3">
                        <span style="background: #e53e3e; color: white; padding: 6px 20px; border-radius: 20px; font-size: 13px; font-weight: bold; text-transform: uppercase; letter-spacing: 2px;">¡Estamos Contratando!</span>
                    </div>
                    <div class="text-center mb-3">
                        <h2 id="flyerTitulo" style="font-size: 28px; font-weight: 800; margin: 0; color: white !important; text-shadow: 0 2px 4px rgba(0,0,0,0.3);"></h2>
                    </div>
                    <div class="text-center mb-4">
                        <span id="flyerDepartamento" style="background: rgba(255,255,255,0.15); padding: 6px 18px; border-radius: 15px; font-size: 14px;"></span>
                    </div>
                    <div id="flyerDescripcion" style="background: rgba(255,255,255,0.1); border-radius: 12px; padding: 20px; margin-bottom: 25px; font-size: 14px; line-height: 1.6; text-align: center;"></div>
                    <div class="row align-items-center">
                        <div class="col-7 text-center">
                            <p style="font-size: 16px; font-weight: 600; margin-bottom: 5px;">Escanea el código QR para postularte</p>
                            <p style="font-size: 12px; opacity: 0.7;">O visita nuestra página web</p>
                        </div>
                        <div class="col-5 text-center">
                            <div style="background: white; padding: 12px; border-radius: 12px; display: inline-block; box-shadow: 0 4px 15px rgba(0,0,0,0.3);">
                                <div id="flyerQRCode"></div>
                            </div>
                        </div>
                    </div>
                    <div class="text-center mt-3">
                        <p style="font-size: 13px; font-weight: 500; margin-bottom: 3px;">Postúlate en:</p>
                        <p id="flyerUrlTexto" style="font-size: 12px; opacity: 0.85; word-break: break-all; background: rgba(255,255,255,0.1); padding: 6px 12px; border-radius: 8px; display: inline-block; margin: 0;"></p>
                    </div>
                    <div class="text-center mt-4" style="border-top: 1px solid rgba(255,255,255,0.2); padding-top: 15px;">
                        <p style="font-size: 11px; opacity: 0.6; margin: 0;">Síguenos en nuestras redes sociales · www.itsi.edu.ec</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-success" id="btnDescargarFlyer" onclick="descargarFlyer()">
                    <i class="ti ti-download me-1"></i> Descargar Imagen
                </button>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js"></script>
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
                    option.textContent = departamento.nombre;
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
                    <button type="button" class="btn btn-sm btn-outline-warning" onclick="generarAnuncio(${puesto.id_puesto}, '${(puesto.titulo || '').replace(/'/g, "\\'")  }', '${(puesto.nombre_departamento || 'Sin asignar').replace(/'/g, "\\'")}', '${(puesto.descripcion || '').replace(/'/g, "\\'")}')" title="Generar Anuncio">
                        <i class="ti ti-speakerphone"></i>
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

// ===== Generador de Anuncio / Flyer =====
function generarAnuncio(id, titulo, departamento, descripcion) {
    const qrContainer = document.getElementById('flyerQRCode');
    qrContainer.innerHTML = '';
    
    document.getElementById('flyerTitulo').textContent = titulo;
    document.getElementById('flyerDepartamento').textContent = '📍 Departamento: ' + departamento;
    document.getElementById('flyerDescripcion').innerHTML = descripcion 
        ? descripcion.replace(/\n/g, '<br>') 
        : 'Buscamos un profesional comprometido para unirse a nuestro equipo. ¡Envía tu postulación escaneando el código QR!';
    
    const urlPostulacion = '<?= base_url('postularse/') ?>' + id;
    document.getElementById('flyerUrlTexto').textContent = urlPostulacion;
    new QRCode(qrContainer, {
        text: urlPostulacion,
        width: 150,
        height: 150,
        colorDark: '#1e3a5f',
        colorLight: '#ffffff',
        correctLevel: QRCode.CorrectLevel.H
    });
    
    const modal = new bootstrap.Modal(document.getElementById('modalFlyer'));
    modal.show();
}

function descargarFlyer() {
    const btn = document.getElementById('btnDescargarFlyer');
    const textoOriginal = btn.innerHTML;
    btn.innerHTML = '<i class="ti ti-loader ti-spin me-1"></i> Generando...';
    btn.disabled = true;
    
    // Esperar a que el QR se renderice completamente
    setTimeout(() => {
        const container = document.getElementById('flyerContainer');
        html2canvas(container, {
            scale: 2,
            useCORS: true,
            allowTaint: true,
            backgroundColor: null,
            logging: false,
            width: container.scrollWidth,
            height: container.scrollHeight,
            scrollX: 0,
            scrollY: 0
        }).then(canvas => {
            const link = document.createElement('a');
            const titulo = document.getElementById('flyerTitulo').textContent || 'puesto';
            link.download = 'Anuncio_' + titulo.replace(/\s+/g, '_') + '.png';
            link.href = canvas.toDataURL('image/png');
            link.click();
            mostrarModalExito('Anuncio descargado como imagen PNG.');
        }).catch(error => {
            console.error('Error:', error);
            mostrarAlerta('Error', 'No se pudo generar la imagen del anuncio.', 'danger');
        }).finally(() => {
            btn.innerHTML = textoOriginal;
            btn.disabled = false;
        });
    }, 2000);
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
