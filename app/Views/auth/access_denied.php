<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso Denegado</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-danger text-white">
                        <h4><i class="bi bi-exclamation-triangle"></i> Acceso Denegado</h4>
                    </div>
                    <div class="card-body text-center">
                        <i class="bi bi-shield-x text-danger" style="font-size: 4rem;"></i>
                        <h2 class="mt-3 text-danger">Acceso Denegado</h2>
                        <p class="lead">No tienes permisos para acceder a esta página.</p>
                        <p>Si crees que esto es un error, contacta al administrador del sistema.</p>
                        
                        <div class="mt-4">
                            <a href="<?= site_url('login') ?>" class="btn btn-primary">
                                <i class="bi bi-arrow-left"></i> Volver al Login
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 