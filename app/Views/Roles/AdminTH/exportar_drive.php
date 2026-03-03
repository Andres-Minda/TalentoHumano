<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>
<div class="page-wrapper">
    <div class="page-content">
        <!-- Breadcrumb -->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Drive</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
                        <li class="breadcrumb-item"><a href="<?= base_url('index.php/admin-th/postulantes') ?>">Postulantes</a></li>
                        <li class="breadcrumb-item active" aria-current="page">CVs en Google Drive</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-cloud me-2"></i>Archivos en Google Drive
                            <span class="badge bg-primary ms-2"><?= count($archivos) ?> archivos</span>
                        </h5>
                        <a href="<?= base_url('index.php/admin-th/postulantes') ?>" class="btn btn-secondary btn-sm">
                            <i class="bi bi-arrow-left me-1"></i>Volver
                        </a>
                    </div>
                    <div class="card-body">
                        <?php if (empty($archivos)): ?>
                            <div class="text-center py-5">
                                <i class="bi bi-folder2-open fs-1 d-block mb-3 text-muted"></i>
                                <h5 class="text-muted">La carpeta de CVs está vacía</h5>
                            </div>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Nombre del Archivo</th>
                                            <th>Fecha de Subida</th>
                                            <th>Acción</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($archivos as $i => $archivo): ?>
                                            <tr>
                                                <td><?= $i + 1 ?></td>
                                                <td>
                                                    <i class="bi bi-file-earmark-pdf text-danger me-1"></i>
                                                    <?= esc($archivo['nombre']) ?>
                                                </td>
                                                <td>
                                                    <?php if ($archivo['fecha']): ?>
                                                        <?= date('d/m/Y H:i', strtotime($archivo['fecha'])) ?>
                                                    <?php else: ?>
                                                        N/A
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <a href="<?= $archivo['enlace'] ?>" target="_blank" class="btn btn-primary btn-sm">
                                                        <i class="bi bi-box-arrow-up-right me-1"></i>Abrir en Drive
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
