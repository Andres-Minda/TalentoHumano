<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $title ?? 'Sistema de Talento Humano' ?></title>
    <link rel="shortcut icon" type="image/png" href="<?= base_url('sistema/assets/images/logos/faviconV2.png') ?>" />

    <!-- ESTILOS -->
    <link rel="stylesheet" href="<?= base_url('sistema/assets/css/styles.min.css') ?>" />
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Estilos personalizados -->
    <link rel="stylesheet" href="<?= base_url('sistema/assets/css/custom.css') ?>" />
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <?= $this->renderSection('css') ?>
</head>

<body>
    <!--  Body Wrapper -->
    <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
        data-sidebar-position="fixed" data-header-position="fixed">

        <!-- Sidebar -->
        <?php if(isset($sidebar)): ?>
            <?= $this->include($sidebar); ?>
        <?php else: ?>
            <?= $this->include('partials/sidebar_' . (session('sidebar_type') ?? 'empleado')); ?>
        <?php endif; ?>
        
        <!-- Main Content -->
        <div class="body-wrapper">
            <!-- Navbar -->
            <?= $this->include('partials/navbar'); ?>
            
            <!-- Content -->
            <div class="container-fluid">
                <?= $this->renderSection('content') ?>
            </div>
            
            <!-- Modal Section -->
            <div class="container-fluid">
                <?= $this->renderSection('modal') ?>
            </div>
            
            <!-- Footer -->
            <?= $this->include('partials/footer') ?>
        </div>
    </div>

    <!-- Scripts -->
    <script src="<?= base_url('sistema/assets/libs/jquery/dist/jquery.min.js') ?>"></script>
    <script src="<?= base_url('sistema/assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js') ?>"></script>
    <script src="<?= base_url('sistema/assets/libs/apexcharts/dist/apexcharts.min.js') ?>"></script>
    <script src="<?= base_url('sistema/assets/libs/simplebar/dist/simplebar.js') ?>"></script>
    <script src="<?= base_url('sistema/assets/js/sidebarmenu.js') ?>"></script>
    <script src="<?= base_url('sistema/assets/js/app.min.js') ?>"></script>
    <script src="https://cdn.jsdelivr.net/npm/iconify-icon@1.0.8/dist/iconify-icon.min.js"></script>
    
    <!-- Scripts específicos de la página -->
    <?= $this->renderSection('scripts') ?>
</body>

</html> 