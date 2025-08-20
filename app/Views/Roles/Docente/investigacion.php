<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="<?= base_url('empleado/dashboard') ?>">Dashboard</a></li>
                        <li class="breadcrumb-item active">Investigación</li>
                    </ol>
                </div>
                <h4 class="page-title">Investigación</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="header-title">Actividades de Investigación</h4>
                    <p class="text-muted mb-0">Gestiona tus proyectos y actividades de investigación</p>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <h5>Funcionalidad en Desarrollo</h5>
                        <p>Esta funcionalidad está siendo implementada. Pronto podrás gestionar tus actividades de investigación.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
