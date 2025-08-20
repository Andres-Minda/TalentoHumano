<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="<?= base_url('empleado/dashboard') ?>">Dashboard</a></li>
                        <li class="breadcrumb-item active">Solicitudes Generales</li>
                    </ol>
                </div>
                <h4 class="page-title">Solicitudes Generales</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="header-title">Mis Solicitudes Generales</h4>
                    <p class="text-muted mb-0">Gestiona y visualiza tus solicitudes generales</p>
                </div>
                <div class="card-body">
                    <?php if (isset($solicitudes) && !empty($solicitudes)): ?>
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Tipo</th>
                                        <th>Descripción</th>
                                        <th>Fecha</th>
                                        <th>Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($solicitudes as $solicitud): ?>
                                        <tr>
                                            <td><?= $solicitud['tipo_solicitud'] ?? 'N/A' ?></td>
                                            <td><?= $solicitud['descripcion'] ?? 'N/A' ?></td>
                                            <td><?= $solicitud['fecha_solicitud'] ?? 'N/A' ?></td>
                                            <td>
                                                <span class="badge bg-warning">Pendiente</span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-info">
                            <h5>No hay solicitudes registradas</h5>
                            <p>No se encontraron solicitudes generales en tu perfil. Contacta al administrador para agregar esta información.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
