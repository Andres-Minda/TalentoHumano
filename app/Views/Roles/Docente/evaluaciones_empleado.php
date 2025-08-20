<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="<?= base_url('empleado/dashboard') ?>">Dashboard</a></li>
                        <li class="breadcrumb-item active">Mis Evaluaciones</li>
                    </ol>
                </div>
                <h4 class="page-title">Mis Evaluaciones</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="header-title">Evaluaciones de Desempeño</h4>
                    <p class="text-muted mb-0">Visualiza tus evaluaciones de desempeño y retroalimentación</p>
                </div>
                <div class="card-body">
                    <?php if (isset($evaluaciones) && !empty($evaluaciones)): ?>
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Fecha Evaluación</th>
                                        <th>Evaluador</th>
                                        <th>Puntaje</th>
                                        <th>Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($evaluaciones as $evaluacion): ?>
                                        <tr>
                                            <td><?= $evaluacion['fecha_evaluacion'] ?? 'N/A' ?></td>
                                            <td><?= $evaluacion['evaluador'] ?? 'N/A' ?></td>
                                            <td><?= $evaluacion['puntaje_total'] ?? 'N/A' ?></td>
                                            <td>
                                                <span class="badge bg-success">Completada</span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-info">
                            <h5>No hay evaluaciones registradas</h5>
                            <p>No se encontraron evaluaciones de desempeño en tu perfil. Contacta al administrador para agregar esta información.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
