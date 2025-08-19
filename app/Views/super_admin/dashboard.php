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
                        <div class="alert alert-warning">
                            <h5>¡Bienvenido Super Administrador!</h5>
                            <p>Usuario: <strong><?= $usuario['nombres'] ?> <?= $usuario['apellidos'] ?></strong></p>
                            <p>Rol: <strong><?= $usuario['rol'] ?></strong></p>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-3">
                                <div class="card bg-primary text-white">
                                    <div class="card-body text-center">
                                        <h5 class="card-title">Usuarios</h5>
                                        <p class="card-text">Gestión de usuarios del sistema</p>
                                        <a href="<?= base_url('super-admin/usuarios') ?>" class="btn btn-light">Acceder</a>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-3">
                                <div class="card bg-success text-white">
                                    <div class="card-body text-center">
                                        <h5 class="card-title">Backup</h5>
                                        <p class="card-text">Respaldo del sistema</p>
                                        <a href="<?= base_url('super-admin/backup') ?>" class="btn btn-light">Acceder</a>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-3">
                                <div class="card bg-info text-white">
                                    <div class="card-body text-center">
                                        <h5 class="card-title">Configuración</h5>
                                        <p class="card-text">Configuración del sistema</p>
                                        <a href="<?= base_url('super-admin/configuracion-sistema') ?>" class="btn btn-light">Acceder</a>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-3">
                                <div class="card bg-warning text-white">
                                    <div class="card-body text-center">
                                        <h5 class="card-title">Dashboard General</h5>
                                        <p class="card-text">Volver al dashboard principal</p>
                                        <a href="<?= base_url('dashboard') ?>" class="btn btn-light">Acceder</a>
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
