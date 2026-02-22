<!-- Sidebar para Empleados -->
<aside class="left-sidebar">
    <div>
        <div class="brand-logo d-flex align-items-center justify-content-between">
            <a href="<?= base_url('index.php/empleado/dashboard') ?>" class="text-nowrap logo-img">
                                 <img src="<?= base_url('sistema/assets/images/logos/Talento_humano.jpg') ?>" alt="Logo" />
                <span class="ms-2 fw-bold" style="font-size: 1.3rem; color: #000;">Panel del Empleado</span>
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
                    <a class="sidebar-link" href="<?= base_url('index.php/empleado/dashboard') ?>" aria-expanded="false">
                        <span><i class="bi bi-house-door"></i></span>
                        <span class="hide-menu">Dashboard</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="<?= base_url('index.php/empleado/acceso-rapido') ?>" aria-expanded="false">
                        <span><i class="bi bi-lightning"></i></span>
                        <span class="hide-menu">Acceso Rápido</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="<?= base_url('index.php/empleado/mi-perfil') ?>" aria-expanded="false">
                        <span><i class="bi bi-person-circle"></i></span>
                        <span class="hide-menu">Mi Perfil</span>
                    </a>
                </li>
                <li class="nav-small-cap">
                    <i class="ti ti-dots nav-small-cap-icon fs-6"></i>
                    <span class="hide-menu">CAPACITACIONES</span>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="<?= base_url('index.php/empleado/capacitaciones') ?>" aria-expanded="false">
                        <span><i class="bi bi-mortarboard"></i></span>
                        <span class="hide-menu">Mis Capacitaciones</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="<?= base_url('index.php/empleado/titulos-academicos') ?>" aria-expanded="false">
                        <span><i class="bi bi-award"></i></span>
                        <span class="hide-menu">Títulos Académicos</span>
                    </a>
                </li>
                <li class="nav-small-cap">
                    <i class="ti ti-dots nav-small-cap-icon fs-6"></i>
                    <span class="hide-menu">EVALUACIONES</span>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="<?= base_url('index.php/empleado/evaluaciones') ?>" aria-expanded="false">
                        <span><i class="bi bi-clipboard-check"></i></span>
                        <span class="hide-menu">Mis Evaluaciones</span>
                    </a>
                </li>
                <li class="nav-small-cap">
                    <i class="ti ti-dots nav-small-cap-icon fs-6"></i>
                    <span class="hide-menu">INASISTENCIAS</span>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="<?= base_url('index.php/empleado/inasistencias') ?>" aria-expanded="false">
                        <span><i class="bi bi-calendar-x"></i></span>
                        <span class="hide-menu">Control de Inasistencias</span>
                    </a>
                </li>
                <li class="nav-small-cap">
                    <i class="ti ti-dots nav-small-cap-icon fs-6"></i>
                    <span class="hide-menu">SOLICITUDES</span>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="<?= base_url('index.php/empleado/solicitudes-capacitacion') ?>" aria-expanded="false">
                        <span><i class="bi bi-clipboard-data"></i></span>
                        <span class="hide-menu">Solicitudes de Capacitación</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="<?= base_url('index.php/empleado/permisos-vacaciones') ?>" aria-expanded="false">
                        <span><i class="bi bi-calendar-event"></i></span>
                        <span class="hide-menu">Permisos y Vacaciones</span>
                    </a>
                </li>
                <li class="nav-small-cap">
                    <i class="ti ti-dots nav-small-cap-icon fs-6"></i>
                    <span class="hide-menu">FUNCIONALIDADES DOCENTE</span>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="<?= base_url('index.php/empleado/competencias') ?>" aria-expanded="false">
                        <span><i class="bi bi-star"></i></span>
                        <span class="hide-menu">Mis Competencias</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="<?= base_url('index.php/empleado/asistencias') ?>" aria-expanded="false">
                        <span><i class="bi bi-calendar-check"></i></span>
                        <span class="hide-menu">Control de Asistencias</span>
                    </a>
                </li>
                <li class="nav-small-cap">
                    <i class="ti ti-dots nav-small-cap-icon fs-6"></i>
                    <span class="hide-menu">GESTIÓN PERSONAL</span>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="<?= base_url('index.php/empleado/documentos') ?>" aria-expanded="false">
                        <span><i class="bi bi-file-earmark-text"></i></span>
                        <span class="hide-menu">Mis Documentos</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="<?= base_url('index.php/empleado/solicitudes-generales') ?>" aria-expanded="false">
                        <span><i class="bi bi-envelope"></i></span>
                        <span class="hide-menu">Solicitudes Generales</span>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</aside>
