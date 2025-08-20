<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="<?= base_url('empleado/dashboard') ?>">Dashboard</a></li>
                        <li class="breadcrumb-item active">Capacitaciones Individuales</li>
                    </ol>
                </div>
                <h4 class="page-title">Mis Capacitaciones Individuales</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="header-title">Capacitaciones y Desarrollo Profesional</h4>
                    <p class="text-muted mb-0">Gestiona tus capacitaciones individuales y desarrollo profesional</p>
                </div>
                <div class="card-body">
                    <?php if (isset($capacitaciones) && !empty($capacitaciones)): ?>
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Capacitación</th>
                                        <th>Institución</th>
                                        <th>Fecha</th>
                                        <th>Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($capacitaciones as $capacitacion): ?>
                                        <tr>
                                            <td><?= $capacitacion['nombre_capacitacion'] ?? 'N/A' ?></td>
                                            <td><?= $capacitacion['institucion'] ?? 'N/A' ?></td>
                                            <td><?= $capacitacion['fecha'] ?? 'N/A' ?></td>
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
                            <h5>No hay capacitaciones registradas</h5>
                            <p>No se encontraron capacitaciones individuales en tu perfil. Contacta al administrador para agregar esta información.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
