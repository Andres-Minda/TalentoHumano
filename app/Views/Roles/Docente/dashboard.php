<?php $sidebar = 'sidebar_docente'; ?>
<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>

<div class="container-fluid">
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
                                    <a href="<?= base_url('docente/perfil') ?>" class="btn btn-light">Ver Perfil</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-success text-white">
                                <div class="card-body">
                                    <h5 class="card-title"><i class="bi bi-mortarboard"></i> Capacitaciones</h5>
                                    <p class="card-text">Ver mis capacitaciones disponibles</p>
                                    <a href="<?= base_url('docente/capacitaciones') ?>" class="btn btn-light">Ver Capacitaciones</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-warning text-dark">
                                <div class="card-body">
                                    <h5 class="card-title"><i class="bi bi-calendar-check"></i> Asistencias</h5>
                                    <p class="card-text">Registrar y ver mis asistencias</p>
                                    <a href="<?= base_url('docente/asistencias') ?>" class="btn btn-light">Gestionar</a>
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