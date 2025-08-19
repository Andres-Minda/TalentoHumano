<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>

<div class="page-wrapper">
    <div class="page-content">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="mb-0"><?= $titulo ?></h4>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-success">
                            <h5>¡Bienvenido al Sistema de Talento Humano!</h5>
                            <p>Usuario: <strong><?= $usuario['nombres'] ?> <?= $usuario['apellidos'] ?></strong></p>
                            <p>Rol: <strong><?= $usuario['rol'] ?></strong></p>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-4">
                                <div class="card bg-primary text-white">
                                    <div class="card-body text-center">
                                        <h5 class="card-title">Empleados</h5>
                                        <p class="card-text">Gestión de empleados del sistema</p>
                                        <a href="<?= base_url('empleados') ?>" class="btn btn-light">Acceder</a>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="card bg-success text-white">
                                    <div class="card-body text-center">
                                        <h5 class="card-title">Títulos Académicos</h5>
                                        <p class="card-text">Gestión de títulos académicos</p>
                                        <a href="<?= base_url('titulos-academicos') ?>" class="btn btn-light">Acceder</a>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="card bg-info text-white">
                                    <div class="card-body text-center">
                                        <h5 class="card-title">Capacitaciones</h5>
                                        <p class="card-text">Gestión de capacitaciones</p>
                                        <a href="<?= base_url('capacitaciones') ?>" class="btn btn-light">Acceder</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
