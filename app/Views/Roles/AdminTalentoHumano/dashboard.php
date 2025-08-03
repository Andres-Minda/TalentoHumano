<?php $sidebar = 'sidebar_admin_th'; ?>
<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>

<div class="container-fluid">
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
                                    <a href="<?= base_url('admin-th/empleados') ?>" class="btn btn-light">Gestionar</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-warning text-dark">
                                <div class="card-body">
                                    <h5 class="card-title"><i class="bi bi-cash-stack"></i> Nóminas</h5>
                                    <p class="card-text">Gestión de nóminas</p>
                                    <a href="<?= base_url('admin-th/nominas') ?>" class="btn btn-light">Gestionar</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-info text-white">
                                <div class="card-body">
                                    <h5 class="card-title"><i class="bi bi-clipboard-data"></i> Evaluaciones</h5>
                                    <p class="card-text">Gestión de evaluaciones</p>
                                    <a href="<?= base_url('admin-th/evaluaciones') ?>" class="btn btn-light">Gestionar</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-secondary text-white">
                                <div class="card-body">
                                    <h5 class="card-title"><i class="bi bi-graph-up"></i> Reportes</h5>
                                    <p class="card-text">Generar reportes</p>
                                    <a href="<?= base_url('admin-th/reportes') ?>" class="btn btn-light">Generar</a>
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