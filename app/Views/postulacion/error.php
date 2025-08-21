<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $titulo ?? 'Error en Postulación' ?></title>
    
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
        
        .error-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .error-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            padding: 3rem;
            text-align: center;
            max-width: 500px;
            width: 90%;
        }
        
        .error-icon {
            font-size: 4rem;
            color: #dc3545;
            margin-bottom: 1.5rem;
        }
        
        .error-title {
            color: #dc3545;
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }
        
        .error-message {
            color: #6c757d;
            font-size: 1.1rem;
            line-height: 1.6;
            margin-bottom: 2rem;
        }
        
        .btn-home {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 50px;
            padding: 12px 30px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-home:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        }
        
        .talento-logo {
            max-width: 150px;
            margin-bottom: 2rem;
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="error-card">
            <!-- Logo ITSI -->
                            <img src="<?= base_url('public/sistema/assets/images/logos/Talento_humano.jpg') ?>" alt="Talento Humano Logo" class="talento-logo">
            
            <!-- Icono de Error -->
            <div class="error-icon">
                <i class="bi bi-exclamation-triangle"></i>
            </div>
            
            <!-- Título del Error -->
            <h1 class="error-title">
                <?= $titulo ?? 'Error en Postulación' ?>
            </h1>
            
            <!-- Mensaje del Error -->
            <p class="error-message">
                <?= $mensaje ?? 'Ha ocurrido un error al procesar su postulación. Por favor, intente nuevamente.' ?>
            </p>
            
            <!-- Botón para Volver -->
            <a href="<?= base_url() ?>" class="btn btn-primary btn-home">
                <i class="bi bi-house-door me-2"></i>
                Volver al Inicio
            </a>
            
            <!-- Información Adicional -->
            <div class="mt-4 pt-3 border-top">
                <small class="text-muted">
                    Si tiene alguna pregunta, contacte a Recursos Humanos<br>
                    <i class="bi bi-envelope me-1"></i>rh@itsi.edu.ec
                </small>
            </div>
        </div>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

