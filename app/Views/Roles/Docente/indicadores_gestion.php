<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="<?= base_url('empleado/dashboard') ?>">Dashboard</a></li>
                        <li class="breadcrumb-item active">Indicadores de Gestión</li>
                    </ol>
                </div>
                <h4 class="page-title">Indicadores de Gestión</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="header-title">Indicadores de Gestión y Rendimiento</h4>
                    <p class="text-muted mb-0">Monitorea tus indicadores de gestión y rendimiento</p>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <h5>Funcionalidad en Desarrollo</h5>
                        <p>Esta funcionalidad está siendo implementada. Pronto podrás monitorear tus indicadores de gestión.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
