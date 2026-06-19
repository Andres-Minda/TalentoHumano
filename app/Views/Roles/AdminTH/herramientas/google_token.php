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
                        <li class="breadcrumb-item"><a href="<?= site_url('admin-th/herramientas/credenciales') ?>">Herramientas</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Google Drive OAuth2</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-8">

                <!-- Tarjeta principal -->
                <div class="card shadow-sm">
                    <div class="card-header d-flex align-items-center gap-2">
                        <i class="ti ti-brand-google fs-4 text-danger"></i>
                        <h5 class="mb-0">Autorización OAuth2 — Google Drive</h5>
                    </div>
                    <div class="card-body">

                        <?php if (! empty($error)): ?>
                            <!-- Error de configuración -->
                            <div class="alert alert-danger d-flex align-items-start gap-2">
                                <i class="ti ti-circle-x fs-4 flex-shrink-0 mt-1"></i>
                                <div>
                                    <strong>Error de Configuración</strong>
                                    <p class="mb-0 mt-1"><?= $error /* intencional: puede contener HTML controlado */ ?></p>
                                </div>
                            </div>

                        <?php elseif (! empty($tokenError)): ?>
                            <!-- Error devuelto por Google -->
                            <div class="alert alert-danger">
                                <h6><i class="ti ti-circle-x me-1"></i> Error al obtener el token</h6>
                                <ul class="mb-0 mt-2">
                                    <li><strong>Código:</strong> <code><?= esc($tokenError['code']) ?></code></li>
                                    <li><strong>Descripción:</strong> <?= esc($tokenError['description']) ?></li>
                                </ul>
                                <hr>
                                <p class="mb-1 small"><strong>Causas comunes:</strong></p>
                                <ul class="small mb-0">
                                    <li>El código de autorización ya fue usado (solo es válido una vez).</li>
                                    <li>El código expiró (tiene una validez muy corta).</li>
                                    <li>Las credenciales en <code>client_secrets.json</code> no coinciden con la app de Google Cloud.</li>
                                </ul>
                            </div>
                            <a href="<?= site_url('admin-th/herramientas/google-token') ?>" class="btn btn-outline-danger">
                                <i class="ti ti-refresh me-1"></i> Intentar de nuevo
                            </a>

                        <?php elseif (! empty($accessToken)): ?>
                            <!-- Éxito -->
                            <div class="alert alert-success d-flex align-items-start gap-2">
                                <i class="ti ti-circle-check fs-3 flex-shrink-0 mt-1"></i>
                                <div>
                                    <strong>¡Token generado correctamente!</strong>
                                    <p class="mb-0 mt-1">
                                        El archivo <code>token.json</code> se ha guardado en:<br>
                                        <code><?= esc($tokenPath) ?></code>
                                    </p>
                                </div>
                            </div>

                            <?php if ($hasRefreshToken): ?>
                                <div class="alert alert-success">
                                    <i class="ti ti-refresh me-1"></i>
                                    <strong>refresh_token obtenido.</strong> La renovación automática funcionará sin intervención manual.
                                </div>
                            <?php else: ?>
                                <div class="alert alert-warning">
                                    <i class="ti ti-alert-triangle me-1"></i>
                                    <strong>No se obtuvo refresh_token.</strong>
                                    Esto ocurre cuando ya autorizaste la app antes. Para forzarlo, revoca el acceso en
                                    <a href="https://myaccount.google.com/permissions" target="_blank" rel="noopener">myaccount.google.com/permissions</a>
                                    y vuelve a ejecutar este proceso.
                                </div>
                            <?php endif; ?>

                            <div class="alert alert-info mt-3">
                                <i class="ti ti-info-circle me-1"></i>
                                El sistema ya puede subir archivos a Google Drive.
                                No necesitas repetir este proceso a menos que el token expire o revoques el acceso.
                            </div>

                        <?php else: ?>
                            <!-- Paso 1: Mostrar enlace de autorización -->
                            <div class="alert alert-info d-flex align-items-start gap-2">
                                <i class="ti ti-info-circle fs-4 flex-shrink-0 mt-1"></i>
                                <div>
                                    <strong>Este proceso es de una sola vez por instalación.</strong>
                                    <p class="mb-0 mt-1">
                                        Al autorizar, Google generará un <em>refresh_token</em> que permite al sistema
                                        subir archivos a Drive de forma automática sin volver a pedir permiso.
                                    </p>
                                </div>
                            </div>

                            <h6 class="mt-3 mb-3">Pasos para autorizar:</h6>
                            <ol class="list-group list-group-numbered mb-4">
                                <li class="list-group-item">
                                    Haz clic en el botón <strong>"Autorizar mi cuenta de Google"</strong>.
                                </li>
                                <li class="list-group-item">
                                    Inicia sesión con la cuenta de Gmail que tiene el espacio de Drive.
                                </li>
                                <li class="list-group-item">
                                    Si Google muestra una advertencia de seguridad, haz clic en
                                    <strong>"Configuración avanzada"</strong> → <strong>"Ir a TalentoHumano (no seguro)"</strong>.
                                </li>
                                <li class="list-group-item">
                                    Google te redirigirá de vuelta aquí automáticamente con el token generado.
                                </li>
                            </ol>

                            <div class="d-grid">
                                <a href="<?= esc($authUrl) ?>" class="btn btn-danger btn-lg">
                                    <i class="ti ti-brand-google me-2"></i> Autorizar mi cuenta de Google
                                </a>
                            </div>

                            <hr class="my-4">
                            <p class="text-muted small mb-0">
                                <i class="ti ti-lock me-1"></i>
                                El archivo de credenciales se guarda en <code><?= esc($tokenPath) ?></code> (fuera del directorio público).
                                El archivo <code>client_secrets.json</code> utilizado está en <code><?= esc($secretsPath) ?></code>.
                            </p>
                        <?php endif; ?>

                    </div><!-- /.card-body -->
                </div><!-- /.card -->

            </div>
        </div>

    </div>
</div>
<?= $this->endSection() ?>
