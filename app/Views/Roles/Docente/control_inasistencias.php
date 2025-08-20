<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="<?= base_url('empleado/dashboard') ?>">Dashboard</a></li>
                        <li class="breadcrumb-item active">Control de Inasistencias</li>
                    </ol>
                </div>
                <h4 class="page-title">Control de Inasistencias</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="header-title">Registro de Asistencias e Inasistencias</h4>
                    <p class="text-muted mb-0">Controla tu asistencia y registra inasistencias</p>
                </div>
                <div class="card-body">
                    <?php if (isset($asistencias) && !empty($asistencias)): ?>
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Fecha</th>
                                        <th>Hora Entrada</th>
                                        <th>Hora Salida</th>
                                        <th>Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($asistencias as $asistencia): ?>
                                        <tr>
                                            <td><?= $asistencia['fecha'] ?? 'N/A' ?></td>
                                            <td><?= $asistencia['hora_entrada'] ?? 'N/A' ?></td>
                                            <td><?= $asistencia['hora_salida'] ?? 'N/A' ?></td>
                                            <td>
                                                <span class="badge bg-success">Presente</span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-info">
                            <h5>No hay registros de asistencia</h5>
                            <p>No se encontraron registros de asistencia en tu perfil. Contacta al administrador para agregar esta información.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
