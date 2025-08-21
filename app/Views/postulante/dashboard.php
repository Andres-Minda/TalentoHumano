<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard del Postulante - Sistema de Talento Humano</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Tabler Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons@latest/iconfont/tabler-icons.min.css">
    <!-- Custom CSS -->
    <style>
        :root {
            --primary-color: #206bc4;
            --secondary-color: #6c757d;
            --success-color: #2fb344;
            --warning-color: #f59f00;
            --danger-color: #d63939;
            --info-color: #39c5d8;
            --light-color: #f8f9fa;
            --dark-color: #212529;
        }

        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .dashboard-container {
            padding: 2rem;
            max-width: 1400px;
            margin: 0 auto;
        }

        .welcome-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .welcome-header {
            text-align: center;
            margin-bottom: 1.5rem;
        }

        .welcome-title {
            color: var(--primary-color);
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .welcome-subtitle {
            color: var(--secondary-color);
            font-size: 1.1rem;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: rgba(255, 255, 255, 0.9);
            border-radius: 15px;
            padding: 1.5rem;
            text-align: center;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.15);
        }

        .stat-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
        }

        .stat-number {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .stat-label {
            color: var(--secondary-color);
            font-size: 1rem;
        }

        .postulaciones-section {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .section-header {
            display: flex;
            justify-content: between;
            align-items: center;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid var(--light-color);
        }

        .section-title {
            color: var(--primary-color);
            font-size: 1.8rem;
            font-weight: 600;
            margin: 0;
        }

        .postulacion-card {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            border: 1px solid rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }

        .postulacion-card:hover {
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
            transform: translateY(-2px);
        }

        .postulacion-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 1rem;
        }

        .postulacion-title {
            color: var(--primary-color);
            font-size: 1.3rem;
            font-weight: 600;
            margin: 0;
        }

        .postulacion-status {
            padding: 0.5rem 1rem;
            border-radius: 25px;
            font-size: 0.9rem;
            font-weight: 500;
        }

        .status-pendiente { background: var(--light-color); color: var(--secondary-color); }
        .status-en-revision { background: #fff3cd; color: #856404; }
        .status-aprobado { background: #d1edff; color: #0c5460; }
        .status-rechazado { background: #f8d7da; color: #721c24; }
        .status-entrevista { background: #d1ecf1; color: #0c5460; }
        .status-contratado { background: #d4edda; color: #155724; }

        .postulacion-details {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .detail-item {
            display: flex;
            flex-direction: column;
        }

        .detail-label {
            font-size: 0.9rem;
            color: var(--secondary-color);
            margin-bottom: 0.25rem;
        }

        .detail-value {
            font-weight: 500;
            color: var(--dark-color);
        }

        .postulacion-actions {
            display: flex;
            gap: 0.75rem;
            flex-wrap: wrap;
        }

        .btn-custom {
            border-radius: 25px;
            padding: 0.5rem 1.5rem;
            font-weight: 500;
            transition: all 0.3s ease;
            border: none;
        }

        .btn-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .btn-primary-custom {
            background: var(--primary-color);
            color: white;
        }

        .btn-primary-custom:hover {
            background: #1a5aa0;
            color: white;
        }

        .btn-warning-custom {
            background: var(--warning-color);
            color: white;
        }

        .btn-warning-custom:hover {
            background: #d68900;
            color: white;
        }

        .btn-info-custom {
            background: var(--info-color);
            color: white;
        }

        .btn-info-custom:hover {
            background: #2da8bb;
            color: white;
        }

        .empty-state {
            text-align: center;
            padding: 3rem 2rem;
            color: var(--secondary-color);
        }

        .empty-icon {
            font-size: 4rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }

        .empty-title {
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
            color: var(--secondary-color);
        }

        .empty-description {
            font-size: 1rem;
            opacity: 0.8;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .user-avatar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: var(--primary-color);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
            font-weight: 600;
        }

        .user-details h5 {
            margin: 0;
            color: var(--primary-color);
            font-weight: 600;
        }

        .user-details p {
            margin: 0;
            color: var(--secondary-color);
            font-size: 0.9rem;
        }

        .logout-btn {
            position: fixed;
            top: 2rem;
            right: 2rem;
            background: rgba(255, 255, 255, 0.9);
            border: none;
            border-radius: 50px;
            padding: 0.75rem 1.5rem;
            color: var(--danger-color);
            font-weight: 500;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .logout-btn:hover {
            background: var(--danger-color);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
        }

        @media (max-width: 768px) {
            .dashboard-container {
                padding: 1rem;
            }
            
            .welcome-title {
                font-size: 2rem;
            }
            
            .stats-grid {
                grid-template-columns: 1fr;
            }
            
            .postulacion-header {
                flex-direction: column;
                gap: 1rem;
            }
            
            .postulacion-actions {
                justify-content: center;
            }
            
            .logout-btn {
                position: static;
                margin-bottom: 1rem;
                align-self: flex-end;
            }
        }
    </style>
</head>
<body>
    <!-- Botón de Logout -->
    <button class="logout-btn" onclick="logout()">
        <i class="ti ti-logout me-2"></i>Cerrar Sesión
    </button>

    <div class="dashboard-container">
        <!-- Tarjeta de Bienvenida -->
        <div class="welcome-card">
            <div class="welcome-header">
                <h1 class="welcome-title">¡Bienvenido a tu Dashboard!</h1>
                <p class="welcome-subtitle">Gestiona tus postulaciones y mantén tu información actualizada</p>
            </div>
            
            <div class="user-info">
                <div class="user-avatar">
                    <?= strtoupper(substr($user['nombres'] ?? 'U', 0, 1)) ?>
                </div>
                <div class="user-details">
                    <h5><?= $user['nombres'] ?? 'Usuario' ?> <?= $user['apellidos'] ?? '' ?></h5>
                    <p><?= $user['email'] ?? 'usuario@email.com' ?></p>
                </div>
            </div>
        </div>

        <!-- Estadísticas -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon text-primary">
                    <i class="ti ti-file-text"></i>
                </div>
                <div class="stat-number text-primary"><?= $estadisticas['total_postulaciones'] ?? 0 ?></div>
                <div class="stat-label">Total de Postulaciones</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon text-warning">
                    <i class="ti ti-clock"></i>
                </div>
                <div class="stat-number text-warning"><?= $estadisticas['en_revision'] ?? 0 ?></div>
                <div class="stat-label">En Revisión</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon text-success">
                    <i class="ti ti-check"></i>
                </div>
                <div class="stat-number text-success"><?= $estadisticas['aprobadas'] ?? 0 ?></div>
                <div class="stat-label">Aprobadas</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon text-info">
                    <i class="ti ti-users"></i>
                </div>
                <div class="stat-number text-info"><?= $estadisticas['entrevistas'] ?? 0 ?></div>
                <div class="stat-label">Entrevistas</div>
            </div>
        </div>

        <!-- Sección de Postulaciones -->
        <div class="postulaciones-section">
            <div class="section-header">
                <h2 class="section-title">Mis Postulaciones</h2>
            </div>

            <div id="postulacionesContainer">
                <?php if (empty($postulaciones)): ?>
                    <div class="empty-state">
                        <div class="empty-icon">
                            <i class="ti ti-file-text"></i>
                        </div>
                        <h3 class="empty-title">No tienes postulaciones aún</h3>
                        <p class="empty-description">Cuando te postules a un puesto de trabajo, aparecerá aquí para que puedas darle seguimiento.</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($postulaciones as $postulacion): ?>
                        <div class="postulacion-card">
                            <div class="postulacion-header">
                                <h3 class="postulacion-title"><?= htmlspecialchars($postulacion['titulo_puesto']) ?></h3>
                                <span class="postulacion-status status-<?= strtolower(str_replace(' ', '-', $postulacion['estado_postulacion'])) ?>">
                                    <?= htmlspecialchars($postulacion['estado_postulacion']) ?>
                                </span>
                            </div>
                            
                            <div class="postulacion-details">
                                <div class="detail-item">
                                    <span class="detail-label">Empresa/Institución</span>
                                    <span class="detail-value"><?= htmlspecialchars($postulacion['nombre_departamento'] ?? 'N/A') ?></span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Tipo de Contrato</span>
                                    <span class="detail-value"><?= htmlspecialchars($postulacion['tipo_contrato'] ?? 'N/A') ?></span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Salario</span>
                                    <span class="detail-value">
                                        $<?= number_format($postulacion['salario_min'] ?? 0, 2) ?> - 
                                        $<?= number_format($postulacion['salario_max'] ?? 0, 2) ?>
                                    </span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Fecha de Postulación</span>
                                    <span class="detail-value"><?= date('d/m/Y', strtotime($postulacion['fecha_postulacion'])) ?></span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Modalidad</span>
                                    <span class="detail-value"><?= htmlspecialchars($postulacion['modalidad_trabajo'] ?? 'N/A') ?></span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Ubicación</span>
                                    <span class="detail-value"><?= htmlspecialchars($postulacion['ubicacion_trabajo'] ?? 'N/A') ?></span>
                                </div>
                            </div>
                            
                            <div class="postulacion-actions">
                                <button class="btn btn-custom btn-info-custom" onclick="verPostulacion(<?= $postulacion['id_postulante'] ?>)">
                                    <i class="ti ti-eye me-2"></i>Ver Detalles
                                </button>
                                
                                <?php if (in_array($postulacion['estado_postulacion'], ['Pendiente', 'En Revisión'])): ?>
                                    <button class="btn btn-custom btn-warning-custom" onclick="editarPostulacion(<?= $postulacion['id_postulante'] ?>)">
                                        <i class="ti ti-edit me-2"></i>Editar
                                    </button>
                                <?php endif; ?>
                                
                                <button class="btn btn-custom btn-primary-custom" onclick="subirCV(<?= $postulacion['id_postulante'] ?>)">
                                    <i class="ti ti-upload me-2"></i>Actualizar CV
                                </button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Modal para ver detalles de postulación -->
    <div class="modal fade" id="modalDetallesPostulacion" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detalles de la Postulación</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="detallesPostulacionContent">
                    <!-- Los detalles se cargan dinámicamente -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para editar postulación -->
    <div class="modal fade" id="modalEditarPostulacion" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Editar Postulación</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="formEditarPostulacion">
                    <div class="modal-body" id="formularioEditarContent">
                        <!-- El formulario se carga dinámicamente -->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal para subir CV -->
    <div class="modal fade" id="modalSubirCV" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Actualizar CV</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="formSubirCV">
                    <div class="modal-body">
                        <input type="hidden" id="id_postulante_cv" name="id_postulante">
                        
                        <div class="mb-3">
                            <label for="cv_file" class="form-label">Seleccionar nuevo CV *</label>
                            <input type="file" class="form-control" id="cv_file" name="cv_file" accept=".pdf,.doc,.docx" required>
                            <div class="form-text">Formatos permitidos: PDF, DOC, DOCX. Tamaño máximo: 5MB</div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="carta_motivacion" class="form-label">Carta de Motivación</label>
                            <textarea class="form-control" id="carta_motivacion" name="carta_motivacion" rows="4" placeholder="Actualiza tu carta de motivación..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Subir CV</button>
                    </div>
                </form>
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

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Función para cerrar sesión
        function logout() {
            if (confirm('¿Estás seguro de que quieres cerrar sesión?')) {
                window.location.href = '<?= base_url('postulante/logout') ?>';
            }
        }

        // Función para ver detalles de postulación
        function verPostulacion(idPostulante) {
            fetch(`<?= base_url('postulante/ver/') ?>${idPostulante}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const postulacion = data.data;
                        const content = document.getElementById('detallesPostulacionContent');
                        
                        content.innerHTML = `
                            <div class="row">
                                <div class="col-md-6">
                                    <h6 class="text-primary">Información Personal</h6>
                                    <p><strong>Nombre:</strong> ${postulacion.nombres} ${postulacion.apellidos}</p>
                                    <p><strong>Cédula:</strong> ${postulacion.cedula}</p>
                                    <p><strong>Email:</strong> ${postulacion.email}</p>
                                    <p><strong>Teléfono:</strong> ${postulacion.telefono || 'N/A'}</p>
                                    <p><strong>Fecha de Nacimiento:</strong> ${postulacion.fecha_nacimiento || 'N/A'}</p>
                                    <p><strong>Género:</strong> ${postulacion.genero || 'N/A'}</p>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="text-primary">Información de Residencia</h6>
                                    <p><strong>Dirección:</strong> ${postulacion.direccion || 'N/A'}</p>
                                    <p><strong>Ciudad:</strong> ${postulacion.ciudad || 'N/A'}</p>
                                    <p><strong>Provincia:</strong> ${postulacion.provincia || 'N/A'}</p>
                                    <p><strong>Nacionalidad:</strong> ${postulacion.nacionalidad || 'N/A'}</p>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-6">
                                    <h6 class="text-primary">Información Profesional</h6>
                                    <p><strong>Experiencia Laboral:</strong> ${postulacion.experiencia_laboral || 'N/A'}</p>
                                    <p><strong>Educación:</strong> ${postulacion.educacion || 'N/A'}</p>
                                    <p><strong>Habilidades:</strong> ${postulacion.habilidades || 'N/A'}</p>
                                    <p><strong>Idiomas:</strong> ${postulacion.idiomas || 'N/A'}</p>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="text-primary">Información de Postulación</h6>
                                    <p><strong>Estado:</strong> <span class="badge bg-${getBadgeClass(postulacion.estado_postulacion)}">${postulacion.estado_postulacion}</span></p>
                                    <p><strong>Fecha de Postulación:</strong> ${new Date(postulacion.fecha_postulacion).toLocaleDateString('es-ES')}</p>
                                    <p><strong>Disponibilidad Inmediata:</strong> ${postulacion.disponibilidad_inmediata ? 'Sí' : 'No'}</p>
                                    <p><strong>Expectativa Salarial:</strong> $${postulacion.expectativa_salarial || 'N/A'}</p>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-12">
                                    <h6 class="text-primary">Documentos</h6>
                                    <p><strong>CV:</strong> ${postulacion.cv_path ? `<a href="${postulacion.cv_path}" target="_blank" class="btn btn-sm btn-outline-primary">Ver CV</a>` : 'No subido'}</p>
                                    <p><strong>Carta de Motivación:</strong> ${postulacion.carta_motivacion || 'No proporcionada'}</p>
                                </div>
                            </div>
                        `;
                        
                        const modal = new bootstrap.Modal(document.getElementById('modalDetallesPostulacion'));
                        modal.show();
                    } else {
                        mostrarAlerta('Error', 'Error al cargar los detalles: ' + data.message, 'danger');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    mostrarAlerta('Error', 'Error al cargar los detalles', 'danger');
                });
        }

        // Función para editar postulación
        function editarPostulacion(idPostulante) {
            fetch(`<?= base_url('postulante/editar/') ?>${idPostulante}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const postulacion = data.data;
                        const content = document.getElementById('formularioEditarContent');
                        
                        content.innerHTML = `
                            <input type="hidden" name="id_postulante" value="${postulacion.id_postulante}">
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="nombres" class="form-label">Nombres *</label>
                                        <input type="text" class="form-control" id="nombres" name="nombres" value="${postulacion.nombres}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="apellidos" class="form-label">Apellidos *</label>
                                        <input type="text" class="form-control" id="apellidos" name="apellidos" value="${postulacion.apellidos}" required>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="telefono" class="form-label">Teléfono</label>
                                        <input type="tel" class="form-control" id="telefono" name="telefono" value="${postulacion.telefono || ''}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="fecha_nacimiento" class="form-label">Fecha de Nacimiento</label>
                                        <input type="date" class="form-control" id="fecha_nacimiento" name="fecha_nacimiento" value="${postulacion.fecha_nacimiento || ''}">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="genero" class="form-label">Género</label>
                                        <select class="form-select" id="genero" name="genero">
                                            <option value="">Seleccionar</option>
                                            <option value="Masculino" ${postulacion.genero === 'Masculino' ? 'selected' : ''}>Masculino</option>
                                            <option value="Femenino" ${postulacion.genero === 'Femenino' ? 'selected' : ''}>Femenino</option>
                                            <option value="Otro" ${postulacion.genero === 'Otro' ? 'selected' : ''}>Otro</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="estado_civil" class="form-label">Estado Civil</label>
                                        <select class="form-select" id="estado_civil" name="estado_civil">
                                            <option value="">Seleccionar</option>
                                            <option value="Soltero" ${postulacion.estado_civil === 'Soltero' ? 'selected' : ''}>Soltero</option>
                                            <option value="Casado" ${postulacion.estado_civil === 'Casado' ? 'selected' : ''}>Casado</option>
                                            <option value="Divorciado" ${postulacion.estado_civil === 'Divorciado' ? 'selected' : ''}>Divorciado</option>
                                            <option value="Viudo" ${postulacion.estado_civil === 'Viudo' ? 'selected' : ''}>Viudo</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="direccion" class="form-label">Dirección</label>
                                <textarea class="form-control" id="direccion" name="direccion" rows="2">${postulacion.direccion || ''}</textarea>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="ciudad" class="form-label">Ciudad</label>
                                        <input type="text" class="form-control" id="ciudad" name="ciudad" value="${postulacion.ciudad || ''}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="provincia" class="form-label">Provincia</label>
                                        <input type="text" class="form-control" id="provincia" name="provincia" value="${postulacion.provincia || ''}">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="experiencia_laboral" class="form-label">Experiencia Laboral</label>
                                <textarea class="form-control" id="experiencia_laboral" name="experiencia_laboral" rows="3">${postulacion.experiencia_laboral || ''}</textarea>
                            </div>
                            
                            <div class="mb-3">
                                <label for="educacion" class="form-label">Educación</label>
                                <textarea class="form-control" id="educacion" name="educacion" rows="3">${postulacion.educacion || ''}</textarea>
                            </div>
                            
                            <div class="mb-3">
                                <label for="habilidades" class="form-label">Habilidades</label>
                                <textarea class="form-control" id="habilidades" name="habilidades" rows="3">${postulacion.habilidades || ''}</textarea>
                            </div>
                            
                            <div class="mb-3">
                                <label for="idiomas" class="form-label">Idiomas</label>
                                <textarea class="form-control" id="idiomas" name="idiomas" rows="2">${postulacion.idiomas || ''}</textarea>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="disponibilidad_inmediata" class="form-label">Disponibilidad Inmediata</label>
                                        <select class="form-select" id="disponibilidad_inmediata" name="disponibilidad_inmediata">
                                            <option value="1" ${postulacion.disponibilidad_inmediata ? 'selected' : ''}>Sí</option>
                                            <option value="0" ${!postulacion.disponibilidad_inmediata ? 'selected' : ''}>No</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="expectativa_salarial" class="form-label">Expectativa Salarial</label>
                                        <input type="number" class="form-control" id="expectativa_salarial" name="expectativa_salarial" value="${postulacion.expectativa_salarial || ''}" min="0" step="0.01">
                                    </div>
                                </div>
                            </div>
                        `;
                        
                        const modal = new bootstrap.Modal(document.getElementById('modalEditarPostulacion'));
                        modal.show();
                    } else {
                        mostrarAlerta('Error', 'Error al cargar la postulación: ' + data.message, 'danger');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    mostrarAlerta('Error', 'Error al cargar la postulación', 'danger');
                });
        }

        // Función para subir CV
        function subirCV(idPostulante) {
            document.getElementById('id_postulante_cv').value = idPostulante;
            const modal = new bootstrap.Modal(document.getElementById('modalSubirCV'));
            modal.show();
        }

        // Función para obtener clase de badge según estado
        function getBadgeClass(estado) {
            switch(estado) {
                case 'Pendiente': return 'secondary';
                case 'En Revisión': return 'warning';
                case 'Aprobado': return 'success';
                case 'Rechazado': return 'danger';
                case 'Entrevista': return 'info';
                case 'Contratado': return 'primary';
                default: return 'secondary';
            }
        }

        // Función para mostrar alerta
        function mostrarAlerta(titulo, mensaje, tipo = 'info') {
            const header = document.getElementById('headerAlerta');
            const tituloElement = document.getElementById('tituloAlerta');
            const mensajeElement = document.getElementById('mensajeAlerta');
            
            header.className = `modal-header bg-${tipo} text-white`;
            tituloElement.textContent = titulo;
            mensajeElement.textContent = mensaje;
            
            const modal = new bootstrap.Modal(document.getElementById('modalAlerta'));
            modal.show();
        }

        // Función para mostrar modal de éxito
        function mostrarModalExito(mensaje) {
            const mensajeElement = document.getElementById('mensajeExito');
            mensajeElement.textContent = mensaje;
            
            const modal = new bootstrap.Modal(document.getElementById('modalExito'));
            modal.show();
        }

        // Manejar envío del formulario de edición
        document.getElementById('formEditarPostulacion').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const data = Object.fromEntries(formData.entries());
            
            fetch('<?= base_url('postulante/actualizar') ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    mostrarModalExito('Postulación actualizada exitosamente');
                    bootstrap.Modal.getInstance(document.getElementById('modalEditarPostulacion')).hide();
                    // Recargar la página para mostrar los cambios
                    setTimeout(() => {
                        location.reload();
                    }, 1500);
                } else {
                    mostrarAlerta('Error', 'Error al actualizar la postulación: ' + result.message, 'danger');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                mostrarAlerta('Error', 'Error al actualizar la postulación', 'danger');
            });
        });

        // Manejar envío del formulario de CV
        document.getElementById('formSubirCV').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            fetch('<?= base_url('postulante/subir-cv') ?>', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    mostrarModalExito('CV actualizado exitosamente');
                    bootstrap.Modal.getInstance(document.getElementById('modalSubirCV')).hide();
                    // Recargar la página para mostrar los cambios
                    setTimeout(() => {
                        location.reload();
                    }, 1500);
                } else {
                    mostrarAlerta('Error', 'Error al subir el CV: ' + result.message, 'danger');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                mostrarAlerta('Error', 'Error al subir el CV', 'danger');
            });
        });
    </script>
</body>
</html>
