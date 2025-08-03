<?php $sidebar = 'sidebar_docente'; ?>
<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4><i class="bi bi-plus-circle"></i> Nueva Solicitud</h4>
                    <a href="<?= base_url('docente/dashboard') ?>" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Volver al Dashboard
                    </a>
                </div>
                <div class="card-body">
                    <h1 class="text-center text-info">¡Hola Mundo!</h1>
                    <p class="text-center">Página de nueva solicitud - Docente</p>
                    
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i>
                        <strong>Funcionalidad:</strong> Aquí se podrán crear nuevas solicitudes.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?> 