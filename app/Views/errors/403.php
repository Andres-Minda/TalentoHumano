<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $titulo ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body text-center">
                        <h1 class="display-1 text-danger">403</h1>
                        <h2 class="card-title"><?= $titulo ?></h2>
                        <p class="card-text">No tiene permisos para acceder a esta página.</p>
                        <a href="<?= base_url('dashboard') ?>" class="btn btn-primary">Volver al Dashboard</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
