<!-- app/Views/admin/sidebarAdminBienestar.php -->
<aside class="left-sidebar">
    <div>
        <div class="brand-logo d-flex align-items-center justify-content-between">
            <a href="<?= base_url('dashboard') ?>" class="text-nowrap logo-img">
                <img src="<?= base_url('sistema/assets/images/logos/logo.jpg') ?>" alt="Logo" />
                <span class="ms-2 fw-bold" style="font-size: 1.3rem; color: #000;">Bienestar Admin</span>
            </a>
            <div class="close-btn d-xl-none d-block sidebartoggler cursor-pointer" id="sidebarCollapse">
                <i class="ti ti-x fs-8"></i>
            </div>
        </div>
        <nav class="sidebar-nav scroll-sidebar" data-simplebar="">
            <ul id="sidebarnav">
                <li class="nav-small-cap">
                    <i class="ti ti-dots nav-small-cap-icon fs-6"></i>
                    <span class="hide-menu">INICIO</span>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="<?= base_url('index.php/dashboard') ?>" aria-expanded="false">
                        <span><i class="bi bi-house-door"></i></span>
                        <span class="hide-menu">Dashboard</span>
                    </a>
                </li>
                <li class="nav-small-cap">
                    <i class="ti ti-dots nav-small-cap-icon fs-6"></i>
                    <span class="hide-menu">GESTIÓN ESTUDIANTIL</span>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="<?= base_url('index.php/fichas') ?>" aria-expanded="false">
                        <span><i class="bi bi-file-earmark-text"></i></span>
                        <span class="hide-menu">Formularios Socioeconómicos</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="<?= base_url('index.php/solicitudes-becas') ?>" aria-expanded="false">
                        <span><i class="bi bi-award"></i></span>
                        <span class="hide-menu">Gestión de Becas</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="<?= base_url('index.php/estudiantes') ?>" aria-expanded="false">
                        <span><i class="bi bi-people"></i></span>
                        <span class="hide-menu">Información Estudiantil</span>
                    </a>
                </li>
                <li class="nav-small-cap">
                    <i class="ti ti-dots nav-small-cap-icon fs-6"></i>
                    <span class="hide-menu">Solucitudes</span>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="<?= base_url('index.php/tickets') ?>" aria-expanded="false">
                        <span><i class="bi bi-ticket-detailed"></i></span>
                        <span class="hide-menu">Gestión de Solicitudes</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="<?= base_url('index.php/tickets/comunicacion') ?>" aria-expanded="false">
                        <span><i class="bi bi-chat-dots"></i></span>
                        <span class="hide-menu">Comunicación y Resolución</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="<?= base_url('index.php/tickets/integracion') ?>" aria-expanded="false">
                        <span><i class="bi bi-link-45deg"></i></span>
                        <span class="hide-menu">Integración de Información</span>
                    </a>
                </li>
                <li class="nav-small-cap">
                    <i class="ti ti-dots nav-small-cap-icon fs-6"></i>
                    <span class="hide-menu">REPORTES Y ANALÍTICA</span>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="<?= base_url('index.php/reportes') ?>" aria-expanded="false">
                        <span><i class="bi bi-bar-chart"></i></span>
                        <span class="hide-menu">Reportes y Analítica</span>
                    </a>
                </li>
                <li class="nav-small-cap">
                    <i class="ti ti-dots nav-small-cap-icon fs-6"></i>
                    <span class="hide-menu">USUARIOS</span>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="<?= base_url('index.php/usuarios/admin') ?>" aria-expanded="false">
                        <span><i class="bi bi-person-badge"></i></span>
                        <span class="hide-menu">Gestión de Administrativos</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="<?= base_url('index.php/usuarios/roles') ?>" aria-expanded="false">
                        <span><i class="bi bi-shield-lock"></i></span>
                        <span class="hide-menu">Gestión de Roles</span>
                    </a>
                </li>
                <li class="nav-small-cap">
                    <i class="ti ti-dots nav-small-cap-icon fs-6"></i>
                    <span class="hide-menu">CONFIGURACIÓN</span>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="<?= base_url('index.php/configuracion/periodos') ?>" aria-expanded="false">
                        <span><i class="bi bi-calendar-range"></i></span>
                        <span class="hide-menu">Definir Periodo Socioeconómico</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="<?= base_url('index.php/configuracion/becas') ?>" aria-expanded="false">
                        <span><i class="bi bi-award-fill"></i></span>
                        <span class="hide-menu">Configurar Programas de Becas</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="<?= base_url('index.php/configuracion/sistema') ?>" aria-expanded="false">
                        <span><i class="bi bi-gear"></i></span>
                        <span class="hide-menu">Personalización del Sistema</span>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</aside> 