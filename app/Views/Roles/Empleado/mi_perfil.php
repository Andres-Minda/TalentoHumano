<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>

<div class="page-wrapper">
    <div class="page-content">
        <!-- Header -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <h4 class="mb-0"><i class="bi bi-person-circle"></i> Mi Perfil</h4>
                    <div class="page-title-right">
                        <span class="text-muted">Última actualización: <?= date('d/m/Y H:i') ?></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Profile Information -->
        <div class="row">
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <?php 
                            $foto_perfil = session()->get('foto_perfil');
                            if ($foto_perfil && file_exists(FCPATH . 'sistema/assets/images/profile/' . $foto_perfil)) {
                                $foto_url = base_url('sistema/assets/images/profile/' . $foto_perfil);
                            } else {
                                $foto_url = base_url('sistema/assets/images/profile/user-1.jpg');
                            }
                            ?>
                            <img src="<?= $foto_url ?>" alt="Foto de perfil" class="rounded-circle" width="120" height="120" style="object-fit: cover;">
                        </div>
                        <h5 class="mb-1"><?= $empleado['nombres'] ?? session()->get('nombres') ?> <?= $empleado['apellidos'] ?? session()->get('apellidos') ?></h5>
                        <p class="text-muted mb-2"><?= $empleado['tipo_empleado'] ?? 'Empleado' ?></p>
                        <p class="text-muted mb-0"><?= $empleado['departamento'] ?? 'Departamento no asignado' ?></p>
                        
                        <div class="mt-3">
                            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editProfileModal">
                                <i class="bi bi-pencil me-1"></i>Editar Perfil
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Información Personal</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Nombres:</label>
                                <p class="mb-0"><?= $empleado['nombres'] ?? 'No especificado' ?></p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Apellidos:</label>
                                <p class="mb-0"><?= $empleado['apellidos'] ?? 'No especificado' ?></p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Tipo de Empleado:</label>
                                <p class="mb-0"><?= $empleado['tipo_empleado'] ?? 'No especificado' ?></p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Departamento:</label>
                                <p class="mb-0"><?= $empleado['departamento'] ?? 'No asignado' ?></p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Fecha de Ingreso:</label>
                                <p class="mb-0"><?= isset($empleado['fecha_ingreso']) && $empleado['fecha_ingreso'] ? date('d/m/Y', strtotime($empleado['fecha_ingreso'])) : 'No especificado' ?></p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Fecha de Contratación:</label>
                                <p class="mb-0"><?= isset($empleado['fecha_contratacion']) && $empleado['fecha_contratacion'] ? date('d/m/Y', strtotime($empleado['fecha_contratacion'])) : 'No especificado' ?></p>
                            </div>
                        </div>
                        
                        <hr>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Cédula:</label>
                                <p class="mb-0"><?= session()->get('cedula') ?? 'No especificado' ?></p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Email:</label>
                                <p class="mb-0"><?= session()->get('email') ?? 'No especificado' ?></p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Estado:</label>
                                <span class="badge bg-<?= ($empleado['estado'] === 'Activo') ? 'success' : 'danger' ?>">
                                    <?= $empleado['estado'] ?? 'No especificado' ?>
                                </span>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Observaciones:</label>
                                <p class="mb-0"><?= $empleado['observaciones'] ?? 'Sin observaciones' ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Professional Information -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Información Profesional</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Tipo de Empleado:</label>
                                <p class="mb-0"><?= $empleado['tipo_empleado'] ?? 'No especificado' ?></p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Departamento:</label>
                                <p class="mb-0"><?= $empleado['departamento'] ?? 'No especificado' ?></p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Fecha de Ingreso:</label>
                                <p class="mb-0"><?= isset($empleado['fecha_ingreso']) && $empleado['fecha_ingreso'] ? date('d/m/Y', strtotime($empleado['fecha_ingreso'])) : 'No especificado' ?></p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Estado:</label>
                                <span class="badge bg-<?= ($empleado['estado'] === 'Activo') ? 'success' : 'danger' ?>">
                                    <?= $empleado['estado'] ?? 'No especificado' ?>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Profile Modal -->
<div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editProfileModalLabel">Editar Perfil</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= base_url('empleado/actualizar-perfil') ?>" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nombres</label>
                            <input type="text" class="form-control" name="nombres" value="<?= $empleado['nombres'] ?? '' ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Apellidos</label>
                            <input type="text" class="form-control" name="apellidos" value="<?= $empleado['apellidos'] ?? '' ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Departamento</label>
                            <input type="text" class="form-control" name="departamento" value="<?= $empleado['departamento'] ?? '' ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Observaciones</label>
                            <textarea class="form-control" name="observaciones" rows="2"><?= $empleado['observaciones'] ?? '' ?></textarea>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Foto de Perfil</label>
                            <input type="file" class="form-control" name="foto_perfil" accept="image/*">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
