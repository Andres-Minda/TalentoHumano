<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $titulo ?? 'Postulación Exitosa' ?></title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    
    <style>
        body {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .success-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 0;
        }
        
        .success-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            padding: 3rem;
            text-align: center;
            max-width: 600px;
            width: 90%;
        }
        
        .success-icon {
            font-size: 4rem;
            color: #28a745;
            margin-bottom: 1.5rem;
        }
        
        .success-title {
            color: #28a745;
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }
        
        .success-message {
            color: #6c757d;
            font-size: 1.1rem;
            line-height: 1.6;
            margin-bottom: 2rem;
        }
        
        .puesto-info {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .credenciales-card {
            background: #f8f9fa;
            border: 2px solid #28a745;
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .credencial-item {
            background: white;
            border-radius: 10px;
            padding: 1rem;
            margin-bottom: 1rem;
            border-left: 4px solid #28a745;
        }
        
        .btn-copy {
            background: #28a745;
            border: none;
            border-radius: 25px;
            padding: 8px 20px;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }
        
        .btn-copy:hover {
            background: #218838;
            transform: translateY(-1px);
        }
        
        .btn-dashboard {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            border: none;
            border-radius: 50px;
            padding: 15px 40px;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s ease;
        }
        
        .btn-dashboard:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        }
        
        .talento-logo {
            max-width: 120px;
            margin-bottom: 1rem;
        }
        
        .warning-alert {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 10px;
            padding: 1rem;
            margin-bottom: 1.5rem;
        }
    </style>
</head>
<body>
    <div class="success-container">
        <div class="success-card">
            <!-- Logo ITSI -->
                            <img src="<?= base_url('public/sistema/assets/images/logos/Talento_humano.jpg') ?>" alt="Talento Humano Logo" class="talento-logo">
            
            <!-- Icono de Éxito -->
            <div class="success-icon">
                <i class="bi bi-check-circle"></i>
            </div>
            
            <!-- Título de Éxito -->
            <h1 class="success-title">
                ¡Postulación Exitosa!
            </h1>
            
            <!-- Mensaje de Confirmación -->
            <p class="success-message">
                Su postulación ha sido recibida correctamente. Nuestro equipo de Recursos Humanos revisará su información y se pondrá en contacto con usted en los próximos días.
            </p>
            
            <!-- Información del Puesto -->
            <div class="puesto-info">
                <h4 class="mb-2"><?= $puesto['titulo'] ?? 'Puesto de Trabajo' ?></h4>
                <p class="mb-1"><i class="bi bi-building me-2"></i><?= $puesto['nombre_departamento'] ?? 'Departamento' ?></p>
                <p class="mb-0"><i class="bi bi-calendar me-2"></i>Fecha de postulación: <?= date('d/m/Y') ?></p>
            </div>
            
            <!-- Credenciales de Acceso (si es nuevo usuario) -->
            <?php if (isset($credenciales) && $credenciales): ?>
            <div class="credenciales-card">
                <h5 class="text-success mb-3">
                    <i class="bi bi-key me-2"></i>
                    Credenciales de Acceso
                </h5>
                <p class="text-muted mb-3">
                    Se han creado credenciales para que pueda acceder al sistema y dar seguimiento a su postulación:
                </p>
                
                <div class="credencial-item">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <strong>Email:</strong> <?= $credenciales['email'] ?>
                        </div>
                        <button class="btn btn-copy btn-sm" onclick="copiarTexto('<?= $credenciales['email'] ?>')">
                            <i class="bi bi-clipboard"></i> Copiar
                        </button>
                    </div>
                </div>
                
                <div class="credencial-item">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <strong>Contraseña:</strong> <?= $credenciales['password'] ?>
                        </div>
                        <button class="btn btn-copy btn-sm" onclick="copiarTexto('<?= $credenciales['password'] ?>')">
                            <i class="bi bi-clipboard"></i> Copiar
                        </button>
                    </div>
                </div>
                
                <div class="warning-alert">
                    <i class="bi bi-exclamation-triangle text-warning me-2"></i>
                    <strong>Importante:</strong> Guarde estas credenciales en un lugar seguro. Podrá cambiar su contraseña una vez que acceda al sistema.
                </div>
            </div>
            <?php endif; ?>
            
            <!-- Información de Seguimiento -->
            <div class="alert alert-info">
                <h6 class="alert-heading">
                    <i class="bi bi-info-circle me-2"></i>
                    ¿Qué sigue?
                </h6>
                <ul class="mb-0 text-start">
                    <li>Su postulación será revisada por nuestro equipo de RRHH</li>
                    <li>Recibirá notificaciones sobre el estado de su aplicación</li>
                    <li>Si es seleccionado, será contactado para una entrevista</li>
                    <li>Puede acceder al sistema para ver el estado de su postulación</li>
                </ul>
            </div>
            
            <!-- Botones de Acción -->
            <div class="d-grid gap-2 d-md-block">
                <?php if (isset($credenciales) && $credenciales): ?>
                <a href="<?= base_url('postulante/dashboard') ?>" class="btn btn-dashboard">
                    <i class="bi bi-speedometer2 me-2"></i>
                    Acceder al Dashboard
                </a>
                <?php endif; ?>
                
                <a href="<?= base_url() ?>" class="btn btn-outline-secondary">
                    <i class="bi bi-house-door me-2"></i>
                    Volver al Inicio
                </a>
            </div>
            
            <!-- Información de Contacto -->
            <div class="mt-4 pt-3 border-top">
                <small class="text-muted">
                    <strong>¿Tiene preguntas?</strong><br>
                    Contacte a Recursos Humanos<br>
                    <i class="bi bi-envelope me-1"></i>rh@itsi.edu.ec<br>
                    <i class="bi bi-telephone me-1"></i>+593 XX XXX XXXX
                </small>
            </div>
        </div>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        function copiarTexto(texto) {
            navigator.clipboard.writeText(texto).then(() => {
                // Mostrar notificación de copiado
                const btn = event.target.closest('.btn');
                const iconoOriginal = btn.innerHTML;
                
                btn.innerHTML = '<i class="bi bi-check"></i> Copiado';
                btn.classList.remove('btn-copy');
                btn.classList.add('btn-success');
                
                setTimeout(() => {
                    btn.innerHTML = iconoOriginal;
                    btn.classList.remove('btn-success');
                    btn.classList.add('btn-copy');
                }, 2000);
            }).catch(() => {
                alert('No se pudo copiar el texto. Por favor, cópielo manualmente.');
            });
        }
        
        // Mostrar mensaje de bienvenida si es nuevo usuario
        <?php if (isset($credenciales) && $credenciales): ?>
        setTimeout(() => {
            alert('¡Bienvenido! Se han creado sus credenciales de acceso. Por favor, guárdelas en un lugar seguro.');
        }, 1000);
        <?php endif; ?>
    </script>
</body>
</html>

