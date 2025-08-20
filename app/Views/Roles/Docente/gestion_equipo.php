<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="<?= base_url('empleado/dashboard') ?>">Dashboard</a></li>
                        <li class="breadcrumb-item active">Gestión de Equipo</li>
                    </ol>
                </div>
                <h4 class="page-title">Gestión de Equipo</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="header-title">Gestión de Equipo de Trabajo</h4>
                    <p class="text-muted mb-0">Gestiona tu equipo de trabajo y colaboradores</p>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <h5>Funcionalidad en Desarrollo</h5>
                        <p>Esta funcionalidad está siendo implementada. Pronto podrás gestionar tu equipo de trabajo.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
