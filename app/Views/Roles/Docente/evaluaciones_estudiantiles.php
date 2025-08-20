<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="<?= base_url('empleado/dashboard') ?>">Dashboard</a></li>
                        <li class="breadcrumb-item active">Evaluaciones Estudiantiles</li>
                    </ol>
                </div>
                <h4 class="page-title">Evaluaciones Estudiantiles</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="header-title">Panel de Evaluaciones Estudiantiles</h4>
                    <p class="text-muted mb-0">Gestiona las evaluaciones de tus estudiantes</p>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card bg-primary text-white">
                                <div class="card-body">
                                    <h5 class="card-title">Evaluaciones Pendientes</h5>
                                    <h2 class="mb-0">0</h2>
                                    <p class="mb-0">Evaluaciones por revisar</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card bg-success text-white">
                                <div class="card-body">
                                    <h5 class="card-title">Evaluaciones Completadas</h5>
                                    <h2 class="mb-0">0</h2>
                                    <p class="mb-0">Evaluaciones revisadas</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <h5>Funcionalidad en Desarrollo</h5>
                        <p>Esta funcionalidad está siendo implementada. Pronto podrás:</p>
                        <ul>
                            <li>Crear evaluaciones para tus estudiantes</li>
                            <li>Revisar y calificar evaluaciones</li>
                            <li>Generar reportes de rendimiento</li>
                            <li>Gestionar rúbricas de evaluación</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
