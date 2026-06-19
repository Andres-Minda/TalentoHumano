<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>
<div class="page-wrapper">
    <div class="page-content">

        <!-- Breadcrumb -->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Herramientas</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="<?= site_url('admin-th/dashboard') ?>"><i class="bx bx-home-alt"></i></a></li>
                        <li class="breadcrumb-item active" aria-current="page">Credenciales del Sistema</li>
                    </ol>
                </nav>
            </div>
            <div class="ms-auto">
                <a href="<?= site_url('admin-th/herramientas/exportar-schema') ?>" class="btn btn-sm btn-outline-secondary">
                    <i class="ti ti-database-export me-1"></i> Exportar Esquema BD
                </a>
                <a href="<?= site_url('admin-th/herramientas/google-token') ?>" class="btn btn-sm btn-outline-info ms-2">
                    <i class="ti ti-brand-google me-1"></i> Token Google Drive
                </a>
            </div>
        </div>

        <!-- Alerta de seguridad -->
        <div class="alert alert-warning alert-dismissible fade show d-flex align-items-center" role="alert">
            <i class="ti ti-shield-lock fs-4 me-2"></i>
            <div>
                <strong>Acceso Restringido.</strong> Esta página es exclusiva del Administrador de Talento Humano.
                La información mostrada aquí es <strong>sensible</strong>. No la comparta ni acceda desde redes públicas.
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>

        <!-- Resumen -->
        <div class="row mb-3">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body py-3">
                        <div class="d-flex align-items-center">
                            <i class="ti ti-users fs-1 text-primary me-3"></i>
                            <div>
                                <h5 class="mb-0">Credenciales de Acceso al Sistema</h5>
                                <small class="text-muted">
                                    Total de usuarios registrados: <strong><?= (int) $totalUsuarios ?></strong>
                                    &nbsp;·&nbsp; Generado el <?= date('d/m/Y H:i:s') ?>
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabla de credenciales por rol -->
        <?php if (empty($usuariosPorRol)): ?>
            <div class="alert alert-info">
                <i class="ti ti-info-circle me-1"></i> No hay usuarios registrados en el sistema.
            </div>
        <?php else: ?>
            <?php foreach ($usuariosPorRol as $rolNombre => $usuarios): ?>
            <div class="card mb-4 shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center bg-light">
                    <h6 class="card-title mb-0">
                        <i class="ti ti-shield me-2 text-primary"></i>
                        <?= esc($rolNombre) ?>
                        <span class="badge bg-primary ms-2"><?= count($usuarios) ?></span>
                    </h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped align-middle mb-0">
                            <thead class="table-dark">
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Nombre Completo</th>
                                    <th scope="col">Cédula</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Tipo Empleado</th>
                                    <th scope="col">Departamento</th>
                                    <th scope="col">Estado</th>
                                    <th scope="col">Contraseña</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($usuarios as $u): ?>
                                <tr>
                                    <td><small class="text-muted"><?= (int) $u['id_usuario'] ?></small></td>
                                    <td>
                                        <strong><?= esc(trim(($u['nombres'] ?? '') . ' ' . ($u['apellidos'] ?? ''))) ?: '<em class="text-muted">Sin empleado</em>' ?></strong>
                                    </td>
                                    <td><code><?= esc($u['cedula']) ?></code></td>
                                    <td>
                                        <a href="mailto:<?= esc($u['email']) ?>"><?= esc($u['email']) ?></a>
                                    </td>
                                    <td><?= esc($u['tipo_empleado'] ?? '—') ?></td>
                                    <td><?= esc($u['departamento'] ?? '—') ?></td>
                                    <td>
                                        <?php if ($u['activo']): ?>
                                            <span class="badge bg-success">Activo</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger">Inactivo</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($u['password_changed']): ?>
                                            <span class="badge bg-success">
                                                <i class="ti ti-lock me-1"></i>Personalizada
                                            </span>
                                        <?php else: ?>
                                            <span class="badge bg-warning text-dark"
                                                  data-bs-toggle="tooltip"
                                                  title="Contraseña por defecto: la cédula del usuario">
                                                <i class="ti ti-lock-open me-1"></i>Por defecto (cédula)
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>

        <!-- Nota informativa -->
        <div class="card border-warning">
            <div class="card-body d-flex align-items-start gap-3">
                <i class="ti ti-info-circle fs-3 text-warning mt-1 flex-shrink-0"></i>
                <div>
                    <h6 class="mb-1">Nota sobre contraseñas</h6>
                    <p class="mb-0 text-muted small">
                        Los usuarios con contraseña <em>"Por defecto (cédula)"</em> aún no han cambiado su contraseña inicial.
                        La contraseña predeterminada es su número de cédula. Se recomienda notificarles para que la cambien
                        al ingresar al sistema por primera vez.
                    </p>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
// Activar tooltips de Bootstrap
document.addEventListener('DOMContentLoaded', function () {
    const tooltipEls = document.querySelectorAll('[data-bs-toggle="tooltip"]');
    tooltipEls.forEach(el => new bootstrap.Tooltip(el));
});
</script>
<?= $this->endSection() ?>
