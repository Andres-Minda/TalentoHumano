<?php $sidebar = 'sidebar_super_admin'; ?>
<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4><i class="bi bi-shield-check"></i> Gestión de Roles</h4>
                    <a href="<?= base_url('super-admin/dashboard') ?>" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Volver al Dashboard
                    </a>
                </div>
                <div class="card-body">
                    <h1 class="text-center text-primary">¡Hola Mundo!</h1>
                    <p class="text-center">Página de gestión de roles - Super Administrador</p>
                    
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i>
                        <strong>Funcionalidad:</strong> Aquí se mostrará la lista de roles del sistema con opciones para crear, editar y eliminar roles.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?> 