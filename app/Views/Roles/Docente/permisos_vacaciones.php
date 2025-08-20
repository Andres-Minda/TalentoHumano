<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="<?= base_url('index.php/empleado/dashboard') ?>">Dashboard</a></li>
                        <li class="breadcrumb-item active">Permisos y Vacaciones</li>
                    </ol>
                </div>
                <h4 class="page-title">Permisos y Vacaciones</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="header-title">Mis Permisos y Vacaciones</h4>
                    <p class="text-muted mb-0">Gestiona y visualiza tus permisos y vacaciones</p>
                </div>
                <div class="card-body">
                    <?php if (isset($permisos) && !empty($permisos)): ?>
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Tipo</th>
                                        <th>Fecha Inicio</th>
                                        <th>Fecha Fin</th>
                                        <th>Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($permisos as $permiso): ?>
                                        <tr>
                                            <td><?= $permiso['tipo_permiso'] ?? 'N/A' ?></td>
                                            <td><?= $permiso['fecha_inicio'] ?? 'N/A' ?></td>
                                            <td><?= $permiso['fecha_fin'] ?? 'N/A' ?></td>
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
                            <h5>No hay permisos registrados</h5>
                            <p>No se encontraron permisos o vacaciones en tu perfil. Contacta al administrador para agregar esta información.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
