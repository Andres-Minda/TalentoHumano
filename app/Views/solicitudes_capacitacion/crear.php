<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?> - Sistema de Talento Humano</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet">
    
    <style>
        .form-section {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            border-left: 4px solid #007bff;
        }
        .form-section h5 {
            color: #007bff;
            margin-bottom: 15px;
        }
        .priority-indicator {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            display: inline-block;
            margin-right: 8px;
        }
        .priority-baja { background-color: #28a745; }
        .priority-media { background-color: #ffc107; }
        .priority-alta { background-color: #fd7e14; }
        .priority-critica { background-color: #dc3545; }
        .help-text {
            font-size: 0.875rem;
            color: #6c757d;
            margin-top: 5px;
        }
        .character-count {
            font-size: 0.75rem;
            color: #6c757d;
            text-align: right;
            margin-top: 5px;
        }
        .character-count.warning {
            color: #fd7e14;
        }
        .character-count.danger {
            color: #dc3545;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <h1 class="h3 mb-0">
                        <i class="fas fa-plus-circle text-primary me-2"></i>
                        <?= $title ?>
                    </h1>
                    <div>
                        <a href="/empleado/solicitudes-capacitacion" class="btn btn-outline-secondary me-2">
                            <i class="fas fa-arrow-left me-1"></i> Volver
                        </a>
                        <button type="submit" form="formSolicitud" class="btn btn-primary">
                            <i class="fas fa-paper-plane me-1"></i> Enviar Solicitud
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Formulario -->
        <form id="formSolicitud" action="/empleado/solicitudes-capacitacion/guardar" method="POST" class="needs-validation" novalidate>
            <div class="row">
                <div class="col-lg-8">
                    <!-- Información Principal -->
                    <div class="form-section">
                        <h5><i class="fas fa-info-circle me-2"></i>Información de la Capacitación</h5>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="tipo_capacitacion" class="form-label">
                                    Tipo de Capacitación <span class="text-danger">*</span>
                                </label>
                                <select class="form-select" id="tipo_capacitacion" name="tipo_capacitacion" required>
                                    <option value="">Seleccione un tipo</option>
                                    <?php foreach ($tipos_capacitacion as $tipo): ?>
                                    <option value="<?= $tipo ?>"><?= $tipo ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <div class="invalid-feedback">
                                    Por favor seleccione el tipo de capacitación
                                </div>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="prioridad" class="form-label">
                                    Prioridad <span class="text-danger">*</span>
                                </label>
                                <select class="form-select" id="prioridad" name="prioridad" required>
                                    <option value="">Seleccione prioridad</option>
                                    <?php foreach ($prioridades as $prioridad): ?>
                                    <option value="<?= $prioridad ?>" data-class="priority-<?= strtolower($prioridad) ?>">
                                        <span class="priority-indicator priority-<?= strtolower($prioridad) ?>"></span>
                                        <?= $prioridad ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                                <div class="invalid-feedback">
                                    Por favor seleccione la prioridad
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="nombre_capacitacion" class="form-label">
                                Nombre de la Capacitación <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control" id="nombre_capacitacion" name="nombre_capacitacion" 
                                   placeholder="Ej: Curso de Pedagogía Digital" required maxlength="255">
                            <div class="invalid-feedback">
                                Por favor ingrese el nombre de la capacitación
                            </div>
                            <div class="character-count">
                                <span id="nombreCount">0</span>/255 caracteres
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="descripcion" class="form-label">
                                Descripción Detallada <span class="text-danger">*</span>
                            </label>
                            <textarea class="form-control" id="descripcion" name="descripcion" rows="4" 
                                      placeholder="Describa detalladamente la capacitación que desea..." required maxlength="1000"></textarea>
                            <div class="invalid-feedback">
                                Por favor ingrese una descripción detallada
                            </div>
                            <div class="character-count">
                                <span id="descripcionCount">0</span>/1000 caracteres
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="fecha_deseada" class="form-label">
                                    Fecha Deseada <span class="text-danger">*</span>
                                </label>
                                <input type="date" class="form-control" id="fecha_deseada" name="fecha_deseada" required>
                                <div class="invalid-feedback">
                                    Por favor seleccione la fecha deseada
                                </div>
                                <div class="help-text">
                                    Seleccione la fecha en la que desearía realizar la capacitación
                                </div>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="duracion_estimada" class="form-label">
                                    Duración Estimada (horas) <span class="text-danger">*</span>
                                </label>
                                <input type="number" class="form-control" id="duracion_estimada" name="duracion_estimada" 
                                       min="1" max="200" placeholder="Ej: 40" required>
                                <div class="invalid-feedback">
                                    Por favor ingrese la duración estimada
                                </div>
                                <div class="help-text">
                                    Duración estimada en horas (máximo 200 horas)
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Justificación y Beneficios -->
                    <div class="form-section">
                        <h5><i class="fas fa-lightbulb me-2"></i>Justificación y Beneficios</h5>
                        
                        <div class="mb-3">
                            <label for="justificacion" class="form-label">
                                Justificación <span class="text-danger">*</span>
                            </label>
                            <select class="form-select" id="justificacion" name="justificacion" required>
                                <option value="">Seleccione una justificación</option>
                                <?php foreach ($justificaciones as $justificacion): ?>
                                <option value="<?= $justificacion ?>"><?= $justificacion ?></option>
                                <?php endforeach; ?>
                            </select>
                            <div class="invalid-feedback">
                                Por favor seleccione la justificación
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="beneficios_esperados" class="form-label">
                                Beneficios Esperados <span class="text-danger">*</span>
                            </label>
                            <textarea class="form-control" id="beneficios_esperados" name="beneficios_esperados" rows="3" 
                                      placeholder="Describa los beneficios que espera obtener de esta capacitación..." required maxlength="500"></textarea>
                            <div class="invalid-feedback">
                                Por favor describa los beneficios esperados
                            </div>
                            <div class="character-count">
                                <span id="beneficiosCount">0</span>/500 caracteres
                            </div>
                        </div>
                    </div>

                    <!-- Información Adicional -->
                    <div class="form-section">
                        <h5><i class="fas fa-plus-circle me-2"></i>Información Adicional (Opcional)</h5>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="institucion_preferida" class="form-label">
                                    Institución Preferida
                                </label>
                                <input type="text" class="form-control" id="institucion_preferida" name="institucion_preferida" 
                                       placeholder="Ej: Universidad XYZ, Instituto ABC">
                                <div class="help-text">
                                    Si tiene alguna institución específica en mente
                                </div>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="costo_estimado" class="form-label">
                                    Costo Estimado
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" class="form-control" id="costo_estimado" name="costo_estimado" 
                                           min="0" step="0.01" placeholder="0.00">
                                </div>
                                <div class="help-text">
                                    Costo estimado en dólares (si lo conoce)
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar con Información -->
                <div class="col-lg-4">
                    <!-- Resumen de la Solicitud -->
                    <div class="card sticky-top" style="top: 20px;">
                        <div class="card-header bg-primary text-white">
                            <h6 class="mb-0">
                                <i class="fas fa-clipboard-check me-2"></i>Resumen de la Solicitud
                            </h6>
                        </div>
                        <div class="card-body">
                            <div id="resumenSolicitud">
                                <p class="text-muted text-center">
                                    Complete el formulario para ver el resumen
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Consejos -->
                    <div class="card mt-3">
                        <div class="card-header bg-info text-white">
                            <h6 class="mb-0">
                                <i class="fas fa-lightbulb me-2"></i>Consejos para una Buena Solicitud
                            </h6>
                        </div>
                        <div class="card-body">
                            <ul class="list-unstyled mb-0">
                                <li class="mb-2">
                                    <i class="fas fa-check text-success me-2"></i>
                                    Sea específico en la descripción
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-check text-success me-2"></i>
                                    Justifique bien la necesidad
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-check text-success me-2"></i>
                                    Considere el impacto en su trabajo
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-check text-success me-2"></i>
                                    Incluya beneficios para la institución
                                </li>
                                <li>
                                    <i class="fas fa-check text-success me-2"></i>
                                    Planifique fechas realistas
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- Estados del Proceso -->
                    <div class="card mt-3">
                        <div class="card-header bg-secondary text-white">
                            <h6 class="mb-0">
                                <i class="fas fa-tasks me-2"></i>Estados del Proceso
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-2">
                                <div class="badge bg-warning me-2">1</div>
                                <small>Pendiente de Revisión</small>
                            </div>
                            <div class="d-flex align-items-center mb-2">
                                <div class="badge bg-info me-2">2</div>
                                <small>En Revisión</small>
                            </div>
                            <div class="d-flex align-items-center mb-2">
                                <div class="badge bg-success me-2">3</div>
                                <small>Aprobada/Rechazada</small>
                            </div>
                            <div class="d-flex align-items-center">
                                <div class="badge bg-primary me-2">4</div>
                                <small>Implementación</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
            // Inicializar Select2
            $('#tipo_capacitacion, #prioridad, #justificacion').select2({
                theme: 'bootstrap-5',
                width: '100%'
            });

            // Configurar fecha mínima (hoy)
            const today = new Date().toISOString().split('T')[0];
            $('#fecha_deseada').attr('min', today);

            // Contadores de caracteres
            $('#nombre_capacitacion').on('input', function() {
                const count = $(this).val().length;
                const max = 255;
                $('#nombreCount').text(count);
                
                const $counter = $('#nombreCount').parent();
                $counter.removeClass('warning danger');
                if (count > max * 0.8) $counter.addClass('warning');
                if (count > max * 0.95) $counter.addClass('danger');
            });

            $('#descripcion').on('input', function() {
                const count = $(this).val().length;
                const max = 1000;
                $('#descripcionCount').text(count);
                
                const $counter = $('#descripcionCount').parent();
                $counter.removeClass('warning danger');
                if (count > max * 0.8) $counter.addClass('warning');
                if (count > max * 0.95) $counter.addClass('danger');
            });

            $('#beneficios_esperados').on('input', function() {
                const count = $(this).val().length;
                const max = 500;
                $('#beneficiosCount').text(count);
                
                const $counter = $('#beneficiosCount').parent();
                $counter.removeClass('warning danger');
                if (count > max * 0.8) $counter.addClass('warning');
                if (count > max * 0.95) $counter.addClass('danger');
            });

            // Actualizar resumen en tiempo real
            $('input, select, textarea').on('input change', function() {
                actualizarResumen();
            });

            // Validación del formulario
            $('#formSolicitud').on('submit', function(e) {
                if (!this.checkValidity()) {
                    e.preventDefault();
                    e.stopPropagation();
                }
                $(this).addClass('was-validated');
            });
        });

        function actualizarResumen() {
            const tipo = $('#tipo_capacitacion').val();
            const nombre = $('#nombre_capacitacion').val();
            const prioridad = $('#prioridad').val();
            const fecha = $('#fecha_deseada').val();
            const duracion = $('#duracion_estimada').val();
            const justificacion = $('#justificacion').val();
            const institucion = $('#institucion_preferida').val();
            const costo = $('#costo_estimado').val();

            let resumenHTML = '';

            if (tipo && nombre && prioridad && fecha && duracion && justificacion) {
                resumenHTML = `
                    <div class="mb-3">
                        <strong>Tipo:</strong> ${tipo}
                    </div>
                    <div class="mb-3">
                        <strong>Nombre:</strong> ${nombre}
                    </div>
                    <div class="mb-3">
                        <strong>Prioridad:</strong> 
                        <span class="badge bg-${getPrioridadColor(prioridad)}">${prioridad}</span>
                    </div>
                    <div class="mb-3">
                        <strong>Fecha Deseada:</strong> ${formatearFecha(fecha)}
                    </div>
                    <div class="mb-3">
                        <strong>Duración:</strong> ${duracion} horas
                    </div>
                    <div class="mb-3">
                        <strong>Justificación:</strong> ${justificacion}
                    </div>
                `;

                if (institucion) {
                    resumenHTML += `<div class="mb-3"><strong>Institución:</strong> ${institucion}</div>`;
                }

                if (costo) {
                    resumenHTML += `<div class="mb-3"><strong>Costo Estimado:</strong> $${parseFloat(costo).toFixed(2)}</div>`;
                }

                resumenHTML += `
                    <div class="alert alert-success mt-3">
                        <i class="fas fa-check-circle me-2"></i>
                        Solicitud completa y lista para enviar
                    </div>
                `;
            } else {
                resumenHTML = `
                    <p class="text-muted text-center">
                        Complete todos los campos obligatorios para ver el resumen
                    </p>
                `;
            }

            $('#resumenSolicitud').html(resumenHTML);
        }

        function getPrioridadColor(prioridad) {
            const colores = {
                'Baja': 'success',
                'Media': 'warning',
                'Alta': 'danger',
                'Crítica': 'dark'
            };
            return colores[prioridad] || 'secondary';
        }

        function formatearFecha(fecha) {
            if (!fecha) return '';
            const opciones = { year: 'numeric', month: 'long', day: 'numeric' };
            return new Date(fecha).toLocaleDateString('es-ES', opciones);
        }

        // Validación personalizada
        function validarFormulario() {
            let esValido = true;
            const campos = ['tipo_capacitacion', 'nombre_capacitacion', 'prioridad', 'fecha_deseada', 'duracion_estimada', 'justificacion', 'beneficios_esperados'];

            campos.forEach(campo => {
                const valor = $(`#${campo}`).val();
                if (!valor || valor.trim() === '') {
                    esValido = false;
                    $(`#${campo}`).addClass('is-invalid');
                } else {
                    $(`#${campo}`).removeClass('is-invalid');
                }
            });

            // Validación específica para duración
            const duracion = parseInt($('#duracion_estimada').val());
            if (duracion < 1 || duracion > 200) {
                esValido = false;
                $('#duracion_estimada').addClass('is-invalid');
            }

            // Validación para fecha
            const fecha = new Date($('#fecha_deseada').val());
            const hoy = new Date();
            if (fecha < hoy) {
                esValido = false;
                $('#fecha_deseada').addClass('is-invalid');
            }

            return esValido;
        }

        // Interceptar envío del formulario
        $('#formSolicitud').on('submit', function(e) {
            if (!validarFormulario()) {
                e.preventDefault();
                alert('Por favor, complete correctamente todos los campos obligatorios.');
                return false;
            }
        });
    </script>
</body>
</html>
