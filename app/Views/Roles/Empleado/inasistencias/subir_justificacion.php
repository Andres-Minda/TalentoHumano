<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>

<div class="page-wrapper">
    <div class="page-content">
        <!-- Breadcrumb -->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Subir Justificación</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="<?= base_url('empleado/dashboard') ?>">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="<?= base_url('empleado/inasistencias') ?>">Mis Inasistencias</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Subir Justificación</li>
                    </ol>
                </nav>
            </div>
        </div>

        <!-- Mensajes de Sistema -->
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger border-0 bg-danger alert-dismissible fade show py-2">
                <div class="d-flex align-items-center">
                    <div class="font-35 text-white"><i class="bx bxs-message-square-x"></i></div>
                    <div class="ms-3">
                        <h6 class="mb-0 text-white">Error</h6>
                        <div class="text-white"><?= session()->getFlashdata('error') ?></div>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <!-- Formulario -->
        <div class="row">
            <div class="col-xl-9 mx-auto">
                <div class="card border-top border-0 border-4 border-primary">
                    <div class="card-body p-5">
                        <div class="card-title d-flex align-items-center">
                            <div><i class="bx bxs-file-pdf me-1 font-22 text-primary"></i></div>
                            <h5 class="mb-0 text-primary">Detalle de tu Justificación</h5>
                        </div>
                        <hr>
                        
                        <form action="<?= base_url('empleado/inasistencias/subir-justificacion') ?>" method="POST" enctype="multipart/form-data">
                            
                            <!-- Selección de Falta -->
                            <div class="row mb-3">
                                <label for="id_inasistencia" class="col-sm-3 col-form-label">Inasistencia a justificar <span class="text-danger">*</span></label>
                                <div class="col-sm-9">
                                    <select name="id_inasistencia" id="id_inasistencia" class="form-select" required>
                                        <?php if (empty($inasistencias_pendientes)): ?>
                                            <option value="">No tienes faltas pendientes de justificar</option>
                                        <?php else: ?>
                                            <option value="">Selecciona la inasistencia...</option>
                                            <?php foreach ($inasistencias_pendientes as $falta): ?>
                                                <option value="<?= $falta['id'] ?>">
                                                    Día: <?= date('d/m/Y', strtotime($falta['fecha_inasistencia'])) ?> - 
                                                    Motivo Registrado: <?= esc($falta['motivo'] ?? 'Falta') ?>
                                                </option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                    <?php if(empty($inasistencias_pendientes)): ?>
                                        <small class="text-success"><i class="bx bx-check-circle"></i> Tienes todo al día, no hay inasistencias reportadas sin justificar.</small>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- Documento Probatorio -->
                            <div class="row mb-3">
                                <label for="documento" class="col-sm-3 col-form-label">Archivo PDF/IMG <span class="text-danger">*</span></label>
                                <div class="col-sm-9">
                                    <input type="file" name="documento" class="form-control" id="documento" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" required <?= empty($inasistencias_pendientes) ? 'disabled' : '' ?>>
                                    <small class="form-text text-muted">Acepta PDF o Imágenes claras de tu certificado médico u otro soporte.</small>
                                </div>
                            </div>

                            <!-- Contexto o justificación escrita -->
                            <div class="row mb-3">
                                <label for="justificacion" class="col-sm-3 col-form-label">Descripción Atribucional</label>
                                <div class="col-sm-9">
                                    <textarea name="justificacion" class="form-control" id="justificacion" rows="4" placeholder="Escribe detalles adicionales relevantes que aclaren la situación de esta falta..." <?= empty($inasistencias_pendientes) ? 'disabled' : '' ?>></textarea>
                                </div>
                            </div>

                            <div class="row">
                                <label class="col-sm-3 col-form-label"></label>
                                <div class="col-sm-9 d-flex gap-2">
                                    <button type="submit" class="btn btn-primary px-5" <?= empty($inasistencias_pendientes) ? 'disabled' : '' ?>>
                                        <i class="bx bx-upload me-1"></i> Enviar a Revisión
                                    </button>
                                    <a href="<?= base_url('empleado/inasistencias') ?>" class="btn btn-outline-secondary px-5">
                                        <i class="bx bx-arrow-back me-1"></i> Cancelar
                                    </a>
                                </div>
                            </div>
                        </form>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
