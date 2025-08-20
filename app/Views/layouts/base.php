<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $title ?? 'Sistema Talento Humano' ?></title>
    
    <!-- Favicons -->
    <link rel="icon" type="image/x-icon" href="<?= base_url('sistema/assets/images/favicon.ico') ?>">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/ti-icons@0.1.2/css/themify-icons.min.css">
    
    <!-- Core CSS -->
    <link rel="stylesheet" href="<?= base_url('sistema/assets/css/styles.min.css') ?>">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?= base_url('sistema/assets/css/custom.css') ?>">
    
    <style>
        .page-wrapper {
            min-height: 100vh;
        }
        .body-wrapper {
            min-height: calc(100vh - 60px);
        }
    </style>
</head>
<body>
    <!--  Body Wrapper -->
    <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
        data-sidebar-position="fixed" data-header-position="fixed">

        <!-- Sidebar -->
        <?php 
        // Determinar el sidebar basándose en el rol del usuario y la URL actual
        $currentUrl = current_url();
        $userRoleId = session()->get('id_rol');
        $userRoleName = session()->get('nombre_rol');
        $sidebarFile = 'sidebar_super_admin'; // Por defecto
        
        // Si se especifica un sidebar específico en la vista, usarlo
        if (isset($sidebar)) {
            $sidebarFile = $sidebar;
        } else {
            // Determinar sidebar por rol del usuario (prioridad alta)
            switch ($userRoleId) {
                case 1: // Super Administrador
                    $sidebarFile = 'sidebar_super_admin';
                    break;
                case 2: // Administrador Talento Humano
                    $sidebarFile = 'sidebar_admin_th';
                    break;
                case 3: // Docente
                case 6: // Administrativo
                case 7: // Directivo
                case 8: // Auxiliar
                    $sidebarFile = 'sidebar_empleado';
                    break;
                default:
                    // Fallback por URL si no hay rol definido
                    if (strpos($currentUrl, 'admin-th') !== false) {
                        $sidebarFile = 'sidebar_admin_th';
                    } elseif (strpos($currentUrl, 'empleado') !== false) {
                        $sidebarFile = 'sidebar_empleado';
                    } elseif (strpos($currentUrl, 'super-admin') !== false) {
                        $sidebarFile = 'sidebar_super_admin';
                    }
                    break;
            }
        }
        
        // Debug: mostrar qué sidebar se está cargando
        log_message('info', 'Layout Base - Rol ID: ' . $userRoleId . ', Rol Nombre: ' . $userRoleName . ', Sidebar: ' . $sidebarFile);
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
    <script src="<?= base_url('sistema/assets/libs/perfect-scrollbar/dist/perfect-scrollbar.min.js') ?>"></script>
    <script src="<?= base_url('sistema/assets/libs/feather-icons/dist/feather.min.js') ?>"></script>
    <script src="<?= base_url('sistema/assets/js/app.js') ?>"></script>
    
    <!-- Custom Scripts -->
    <script>
        // Script para activar el menú actual en el sidebar
        document.addEventListener('DOMContentLoaded', function() {
            const currentPath = window.location.pathname;
            const sidebarLinks = document.querySelectorAll('.sidebar-link');
            
            sidebarLinks.forEach(link => {
                if (link.getAttribute('href') && currentPath.includes(link.getAttribute('href'))) {
                    link.classList.add('active');
                    link.closest('.sidebar-item').classList.add('active');
                }
            });
        });
    </script>
</body>
</html> 