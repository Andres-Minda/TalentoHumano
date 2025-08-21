<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $titulo ?? 'Formulario de Postulación' ?></title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .form-container {
            padding: 2rem 0;
        }
        
        .form-header {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            padding: 2rem;
            margin-bottom: 2rem;
            text-align: center;
        }
        
        .form-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            padding: 2rem;
            margin-bottom: 2rem;
        }
        
        .form-section {
            border-left: 4px solid #667eea;
            padding-left: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .form-section h5 {
            color: #667eea;
            font-weight: 700;
            margin-bottom: 1.5rem;
        }
        
        .form-label {
            font-weight: 600;
            color: #495057;
        }
        
        .form-control, .form-select {
            border-radius: 10px;
            border: 2px solid #e9ecef;
            transition: all 0.3s ease;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        
        .btn-submit {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 50px;
            padding: 15px 40px;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s ease;
        }
        
        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        }
        
        .talento-logo {
            max-width: 120px;
            margin-bottom: 1rem;
        }
        
        .puesto-info {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .progress-bar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .file-upload-area {
            border: 2px dashed #dee2e6;
            border-radius: 10px;
            padding: 2rem;
            text-align: center;
            transition: all 0.3s ease;
            cursor: pointer;
        }
        
        .file-upload-area:hover {
            border-color: #667eea;
            background-color: #f8f9fa;
        }
        
        .file-upload-area.dragover {
            border-color: #667eea;
            background-color: #e3f2fd;
        }
    </style>
</head>
<body>
    <div class="container form-container">
        <!-- Header del Formulario -->
        <div class="form-header">
                            <img src="<?= base_url('public/sistema/assets/images/logos/Talento_humano.jpg') ?>" alt="Talento Humano Logo" class="talento-logo">
            <h1 class="mb-3">Formulario de Postulación</h1>
            <p class="lead mb-0">Complete todos los campos para postularse a la oferta de trabajo</p>
        </div>
        
        <!-- Información del Puesto -->
        <div class="puesto-info">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h3 class="mb-2"><?= $puesto['titulo'] ?? 'Puesto de Trabajo' ?></h3>
                    <p class="mb-1"><i class="bi bi-building me-2"></i><?= $puesto['nombre_departamento'] ?? 'Departamento' ?></p>
                    <p class="mb-1"><i class="bi bi-geo-alt me-2"></i><?= $puesto['ubicacion_trabajo'] ?? 'Ubicación' ?></p>
                    <p class="mb-0"><i class="bi bi-calendar me-2"></i>Fecha límite: <?= date('d/m/Y', strtotime($puesto['fecha_limite'] ?? 'now')) ?></p>
                </div>
                <div class="col-md-4 text-end">
                    <div class="badge bg-success fs-6 p-2">
                        <i class="bi bi-check-circle me-1"></i>
                        Vacantes: <?= $puesto['vacantes_disponibles'] ?? 0 ?>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Barra de Progreso -->
        <div class="form-card">
            <div class="progress mb-3" style="height: 10px;">
                <div class="progress-bar" role="progressbar" style="width: 0%" id="progressBar"></div>
            </div>
            <small class="text-muted">Progreso del formulario: <span id="progressText">0%</span></small>
        </div>
        
        <!-- Formulario de Postulación -->
        <form id="formPostulacion" action="<?= base_url('postulacion/procesar') ?>" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="url_postulacion" value="<?= $url_postulacion ?>">
            
            <!-- Información Personal -->
            <div class="form-card">
                <div class="form-section">
                    <h5><i class="bi bi-person-circle me-2"></i>Información Personal</h5>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="nombres" class="form-label">Nombres *</label>
                            <input type="text" class="form-control" id="nombres" name="nombres" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="apellidos" class="form-label">Apellidos *</label>
                            <input type="text" class="form-control" id="apellidos" name="apellidos" required>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="cedula" class="form-label">Cédula de Identidad *</label>
                            <input type="text" class="form-control" id="cedula" name="cedula" maxlength="10" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Correo Electrónico *</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="telefono" class="form-label">Teléfono *</label>
                            <input type="tel" class="form-control" id="telefono" name="telefono" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="fecha_nacimiento" class="form-label">Fecha de Nacimiento *</label>
                            <input type="date" class="form-control" id="fecha_nacimiento" name="fecha_nacimiento" required>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="genero" class="form-label">Género *</label>
                            <select class="form-select" id="genero" name="genero" required>
                                <option value="">Seleccionar...</option>
                                <option value="Masculino">Masculino</option>
                                <option value="Femenino">Femenino</option>
                                <option value="No especificado">No especificado</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="estado_civil" class="form-label">Estado Civil *</label>
                            <select class="form-select" id="estado_civil" name="estado_civil" required>
                                <option value="">Seleccionar...</option>
                                <option value="Soltero">Soltero</option>
                                <option value="Casado">Casado</option>
                                <option value="Divorciado">Divorciado</option>
                                <option value="Viudo">Viudo</option>
                                <option value="Unión libre">Unión libre</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Información de Residencia -->
            <div class="form-card">
                <div class="form-section">
                    <h5><i class="bi bi-geo-alt me-2"></i>Información de Residencia</h5>
                    
                    <div class="row">
                        <div class="col-12 mb-3">
                            <label for="direccion" class="form-label">Dirección Completa *</label>
                            <input type="text" class="form-control" id="direccion" name="direccion" required>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="ciudad" class="form-label">Ciudad *</label>
                            <input type="text" class="form-control" id="ciudad" name="ciudad" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="provincia" class="form-label">Provincia *</label>
                            <input type="text" class="form-control" id="provincia" name="provincia" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="codigo_postal" class="form-label">Código Postal</label>
                            <input type="text" class="form-control" id="codigo_postal" name="codigo_postal">
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="nacionalidad" class="form-label">Nacionalidad *</label>
                            <input type="text" class="form-control" id="nacionalidad" name="nacionalidad" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="disponibilidad_inmediata" class="form-label">Disponibilidad Inmediata *</label>
                            <select class="form-select" id="disponibilidad_inmediata" name="disponibilidad_inmediata" required>
                                <option value="">Seleccionar...</option>
                                <option value="Sí">Sí</option>
                                <option value="No">No</option>
                                <option value="En 2 semanas">En 2 semanas</option>
                                <option value="En 1 mes">En 1 mes</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Información Profesional -->
            <div class="form-card">
                <div class="form-section">
                    <h5><i class="bi bi-briefcase me-2"></i>Información Profesional</h5>
                    
                    <div class="row">
                        <div class="col-12 mb-3">
                            <label for="experiencia_laboral" class="form-label">Experiencia Laboral</label>
                            <textarea class="form-control" id="experiencia_laboral" name="experiencia_laboral" rows="4" placeholder="Describa su experiencia laboral relevante..."></textarea>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-12 mb-3">
                            <label for="educacion" class="form-label">Educación y Formación</label>
                            <textarea class="form-control" id="educacion" name="educacion" rows="4" placeholder="Describa su formación académica, títulos obtenidos..."></textarea>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-12 mb-3">
                            <label for="habilidades" class="form-label">Habilidades y Competencias</label>
                            <textarea class="form-control" id="habilidades" name="habilidades" rows="3" placeholder="Liste sus habilidades técnicas y blandas..."></textarea>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="idiomas" class="form-label">Idiomas</label>
                            <textarea class="form-control" id="idiomas" name="idiomas" rows="3" placeholder="Idiomas que domina y nivel..."></textarea>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="certificaciones" class="form-label">Certificaciones</label>
                            <textarea class="form-control" id="certificaciones" name="certificaciones" rows="3" placeholder="Certificaciones profesionales..."></textarea>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-12 mb-3">
                            <label for="referencias" class="form-label">Referencias Laborales</label>
                            <textarea class="form-control" id="referencias" name="referencias" rows="3" placeholder="Referencias de trabajos anteriores..."></textarea>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="expectativa_salarial" class="form-label">Expectativa Salarial</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" class="form-control" id="expectativa_salarial" name="expectativa_salarial" min="0" step="0.01" placeholder="0.00">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Documentos -->
            <div class="form-card">
                <div class="form-section">
                    <h5><i class="bi bi-file-earmark-text me-2"></i>Documentos</h5>
                    
                    <div class="row">
                        <div class="col-12 mb-3">
                            <label for="cv" class="form-label">Curriculum Vitae (PDF, DOC, DOCX)</label>
                            <div class="file-upload-area" id="fileUploadArea">
                                <i class="bi bi-cloud-upload fs-1 text-muted mb-3"></i>
                                <p class="mb-2">Arrastre su archivo aquí o haga clic para seleccionar</p>
                                <input type="file" class="form-control d-none" id="cv" name="cv" accept=".pdf,.doc,.docx">
                                <small class="text-muted">Máximo 5MB</small>
                            </div>
                            <div id="fileInfo" class="mt-2 d-none">
                                <div class="alert alert-success d-flex align-items-center">
                                    <i class="bi bi-check-circle me-2"></i>
                                    <span id="fileName"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-12 mb-3">
                            <label for="carta_motivacion" class="form-label">Carta de Motivación</label>
                            <textarea class="form-control" id="carta_motivacion" name="carta_motivacion" rows="5" placeholder="Explique por qué desea trabajar en esta posición y qué puede aportar a la empresa..."></textarea>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Botón de Envío -->
            <div class="form-card text-center">
                <button type="submit" class="btn btn-primary btn-submit" id="btnSubmit">
                    <i class="bi bi-send me-2"></i>
                    Enviar Postulación
                </button>
                <p class="text-muted mt-3">
                    <i class="bi bi-info-circle me-1"></i>
                    Al enviar este formulario, usted confirma que toda la información proporcionada es veraz y completa.
                </p>
            </div>
        </form>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('formPostulacion');
            const progressBar = document.getElementById('progressBar');
            const progressText = document.getElementById('progressText');
            const fileUploadArea = document.getElementById('fileUploadArea');
            const fileInput = document.getElementById('cv');
            const fileInfo = document.getElementById('fileInfo');
            const fileName = document.getElementById('fileName');
            
            // Calcular progreso del formulario
            function updateProgress() {
                const requiredFields = form.querySelectorAll('[required]');
                const filledFields = Array.from(requiredFields).filter(field => field.value.trim() !== '');
                const progress = Math.round((filledFields.length / requiredFields.length) * 100);
                
                progressBar.style.width = progress + '%';
                progressText.textContent = progress + '%';
            }
            
            // Actualizar progreso en tiempo real
            form.addEventListener('input', updateProgress);
            form.addEventListener('change', updateProgress);
            
            // Manejo de archivos
            fileUploadArea.addEventListener('click', () => fileInput.click());
            
            fileUploadArea.addEventListener('dragover', (e) => {
                e.preventDefault();
                fileUploadArea.classList.add('dragover');
            });
            
            fileUploadArea.addEventListener('dragleave', () => {
                fileUploadArea.classList.remove('dragover');
            });
            
            fileUploadArea.addEventListener('drop', (e) => {
                e.preventDefault();
                fileUploadArea.classList.remove('dragover');
                const files = e.dataTransfer.files;
                if (files.length > 0) {
                    fileInput.files = files;
                    handleFileSelect(files[0]);
                }
            });
            
            fileInput.addEventListener('change', (e) => {
                if (e.target.files.length > 0) {
                    handleFileSelect(e.target.files[0]);
                }
            });
            
            function handleFileSelect(file) {
                // Validar tipo de archivo
                const allowedTypes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
                if (!allowedTypes.includes(file.type)) {
                    alert('Solo se permiten archivos PDF, DOC o DOCX');
                    return;
                }
                
                // Validar tamaño (5MB)
                if (file.size > 5 * 1024 * 1024) {
                    alert('El archivo no puede exceder 5MB');
                    return;
                }
                
                fileName.textContent = file.name;
                fileInfo.classList.remove('d-none');
            }
            
            // Validación del formulario
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const requiredFields = form.querySelectorAll('[required]');
                let isValid = true;
                
                requiredFields.forEach(field => {
                    if (!field.value.trim()) {
                        field.classList.add('is-invalid');
                        isValid = false;
                    } else {
                        field.classList.remove('is-invalid');
                    }
                });
                
                if (isValid) {
                    // Mostrar indicador de envío
                    const btnSubmit = document.getElementById('btnSubmit');
                    btnSubmit.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Enviando...';
                    btnSubmit.disabled = true;
                    
                    // Enviar formulario
                    form.submit();
                } else {
                    alert('Por favor, complete todos los campos obligatorios.');
                }
            });
            
            // Inicializar progreso
            updateProgress();
        });
    </script>
</body>
</html>

