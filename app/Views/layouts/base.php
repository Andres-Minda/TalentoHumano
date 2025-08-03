<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Sistema Talento Humano' ?></title>
    <link rel="shortcut icon" type="image/png" href="<?= base_url('sistema/assets/images/logos/faviconV2.png') ?>" />
    
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="<?= base_url('sistema/assets/libs/bootstrap/dist/css/bootstrap.min.css') ?>">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Tabler Icons -->
    <link href="https://cdn.jsdelivr.net/npm/@tabler/icons@latest/iconfont/tabler-icons.min.css" rel="stylesheet">
    <!-- Main styles -->
    <link href="<?= base_url('sistema/assets/css/styles.min.css') ?>" rel="stylesheet">
    <!-- Custom styles -->
    <link href="<?= base_url('sistema/assets/css/custom.css') ?>" rel="stylesheet">
    
    <style>
        /* Debug: Verificar rutas CSS */
        console.log('CSS Styles URL:', '<?= base_url('sistema/assets/css/styles.min.css') ?>');
        console.log('CSS Custom URL:', '<?= base_url('sistema/assets/css/custom.css') ?>');
    </style>
</head>
<body>
    <!--  Body Wrapper -->
    <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
        data-sidebar-position="fixed" data-header-position="fixed">

        <!-- Sidebar -->
        <?php 
        // Determinar el sidebar basándose en la URL actual
        $currentUrl = current_url();
        $sidebarFile = 'sidebar_super_admin'; // Por defecto
        
        if (isset($sidebar)) {
            $sidebarFile = $sidebar;
        } else {
            // Determinar sidebar por URL
            if (strpos($currentUrl, 'admin-th') !== false) {
                $sidebarFile = 'sidebar_admin_th';
            } elseif (strpos($currentUrl, 'docente') !== false) {
                $sidebarFile = 'sidebar_docente';
            } elseif (strpos($currentUrl, 'super-admin') !== false) {
                $sidebarFile = 'sidebar_super_admin';
            }
        }
        ?>
        <?= $this->include('partials/' . $sidebarFile); ?>
        
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
    <script src="<?= base_url('sistema/assets/js/dashboard.js') ?>"></script>
    
    <!-- Scripts específicos de la página -->
    <?= $this->renderSection('scripts') ?>
    
    <script>
        // Debug information
        console.log('jQuery version:', $.fn.jquery);
        console.log('Bootstrap version:', typeof bootstrap !== 'undefined' ? bootstrap.VERSION : 'undefined');
    </script>
</body>
</html> 