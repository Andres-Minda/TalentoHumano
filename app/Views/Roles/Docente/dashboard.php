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
    <nav class="navbar navbar-expand-lg navbar-dark bg-info">
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
                        <h4><i class="bi bi-speedometer2"></i> Dashboard - Docente</h4>
                    </div>
                    <div class="card-body">
                        <h1 class="text-center text-info">¡Hola Mundo!</h1>
                        <p class="text-center">Bienvenido al panel de Docente</p>
                        
                        <div class="row mt-4">
                            <div class="col-md-4">
                                <div class="card bg-primary text-white">
                                    <div class="card-body">
                                        <h5 class="card-title"><i class="bi bi-person"></i> Mi Perfil</h5>
                                        <p class="card-text">Ver y editar mi información personal</p>
                                        <a href="<?= site_url('docente/perfil') ?>" class="btn btn-light">Ver Perfil</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card bg-success text-white">
                                    <div class="card-body">
                                        <h5 class="card-title"><i class="bi bi-mortarboard"></i> Capacitaciones</h5>
                                        <p class="card-text">Ver mis capacitaciones disponibles</p>
                                        <a href="<?= site_url('docente/capacitaciones') ?>" class="btn btn-light">Ver Capacitaciones</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card bg-warning text-dark">
                                    <div class="card-body">
                                        <h5 class="card-title"><i class="bi bi-calendar-check"></i> Asistencias</h5>
                                        <p class="card-text">Registrar y ver mis asistencias</p>
                                        <a href="#" class="btn btn-light">Gestionar</a>
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