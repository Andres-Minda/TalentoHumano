<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-success">
        <div class="container">
            <a class="navbar-brand" href="#">Sistema Talento Humano</a>
            <div class="navbar-nav ms-auto">
                <span class="navbar-text me-3">
                    <i class="bi bi-person-circle"></i>
                    <?= $user['nombres'] ?> <?= $user['apellidos'] ?> (<?= $user['rol'] ?>)
                </span>
                <a class="nav-link" href="<?= site_url('logout') ?>">
                    <i class="bi bi-box-arrow-right"></i> Cerrar Sesión
                </a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4><i class="bi bi-speedometer2"></i> Dashboard - Administrador Talento Humano</h4>
                    </div>
                    <div class="card-body">
                        <h1 class="text-center text-success">¡Hola Mundo!</h1>
                        <p class="text-center">Bienvenido al panel de Administrador de Talento Humano</p>
                        
                        <div class="row mt-4">
                            <div class="col-md-3">
                                <div class="card bg-primary text-white">
                                    <div class="card-body">
                                        <h5 class="card-title"><i class="bi bi-people"></i> Empleados</h5>
                                        <p class="card-text">Gestión de empleados</p>
                                        <a href="<?= site_url('admin-th/empleados') ?>" class="btn btn-light">Gestionar</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-warning text-dark">
                                    <div class="card-body">
                                        <h5 class="card-title"><i class="bi bi-cash-stack"></i> Nóminas</h5>
                                        <p class="card-text">Gestión de nóminas</p>
                                        <a href="<?= site_url('admin-th/nominas') ?>" class="btn btn-light">Gestionar</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-info text-white">
                                    <div class="card-body">
                                        <h5 class="card-title"><i class="bi bi-clipboard-data"></i> Evaluaciones</h5>
                                        <p class="card-text">Gestión de evaluaciones</p>
                                        <a href="<?= site_url('admin-th/evaluaciones') ?>" class="btn btn-light">Gestionar</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-secondary text-white">
                                    <div class="card-body">
                                        <h5 class="card-title"><i class="bi bi-graph-up"></i> Reportes</h5>
                                        <p class="card-text">Generar reportes</p>
                                        <a href="#" class="btn btn-light">Generar</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 