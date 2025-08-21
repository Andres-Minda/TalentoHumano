<!-- Sidebar para Administrador de Talento Humano -->
<aside class="left-sidebar">
    <div>
        <div class="brand-logo d-flex align-items-center justify-content-between">
            <a href="<?= base_url('index.php/admin-th/dashboard') ?>" class="text-nowrap logo-img">
                                 <img src="<?= base_url('sistema/assets/images/logos/Talento_humano.jpg') ?>" alt="Logo" />
                <span class="ms-2 fw-bold" style="font-size: 1.3rem; color: #000;">Administrador del <br>departamento de <br>Talento Humano</span>
            </a>
            <div class="close-btn d-xl-none d-block sidebartoggler cursor-pointer" id="sidebarCollapse">
                <i class="ti ti-x fs-8"></i>
            </div>
        </div>
        <nav class="sidebar-nav scroll-sidebar" data-simplebar="">
            <ul id="sidebarnav">
                <li class="nav-small-cap">
                    <i class="ti ti-dots nav-small-cap-icon fs-6"></i>
                    <span class="hide-menu">PANEL PRINCIPAL</span>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="<?= base_url('index.php/admin-th/dashboard') ?>" aria-expanded="false">
                        <span><i class="bi bi-house-door"></i></span>
                        <span class="hide-menu">Dashboard</span>
                    </a>
                </li>
                <li class="nav-small-cap">
                    <i class="ti ti-dots nav-small-cap-icon fs-6"></i>
                    <span class="hide-menu">GESTIÓN DE EMPLEADOS</span>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="<?= base_url('index.php/admin-th/empleados') ?>" aria-expanded="false">
                        <span><i class="bi bi-people"></i></span>
                        <span class="hide-menu">Gestión de Empleados</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="<?= base_url('index.php/admin-th/departamentos') ?>" aria-expanded="false">
                        <span><i class="bi bi-building"></i></span>
                        <span class="hide-menu">Departamentos</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="<?= base_url('index.php/admin-th/puestos') ?>" aria-expanded="false">
                        <span><i class="bi bi-briefcase"></i></span>
                        <span class="hide-menu">Puestos de Trabajo</span>
                    </a>
                </li>
                <li class="nav-small-cap">
                    <i class="ti ti-dots nav-small-cap-icon fs-6"></i>
                    <span class="hide-menu">CAPACITACIONES</span>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="<?= base_url('index.php/admin-th/capacitaciones') ?>" aria-expanded="false">
                        <span><i class="bi bi-mortarboard"></i></span>
                        <span class="hide-menu">Gestión de Capacitaciones</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="<?= base_url('index.php/admin-th/titulos-academicos') ?>" aria-expanded="false">
                        <span><i class="bi bi-award"></i></span>
                        <span class="hide-menu">Títulos Académicos</span>
                    </a>
                </li>
                <li class="nav-small-cap">
                    <i class="ti ti-dots nav-small-cap-icon fs-6"></i>
                    <span class="hide-menu">EVALUACIONES</span>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="<?= base_url('index.php/admin-th/evaluaciones') ?>" aria-expanded="false">
                        <span><i class="bi bi-clipboard-check"></i></span>
                        <span class="hide-menu">Gestión de Evaluaciones</span>
                    </a>
                </li>
                <li class="nav-small-cap">
                    <i class="ti ti-dots nav-small-cap-icon fs-6"></i>
                    <span class="hide-menu">INASISTENCIAS</span>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="<?= base_url('index.php/admin-th/inasistencias') ?>" aria-expanded="false">
                        <span><i class="bi bi-calendar-x"></i></span>
                        <span class="hide-menu">Control de Inasistencias</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="<?= base_url('index.php/admin-th/politicas-inasistencia') ?>" aria-expanded="false">
                        <span><i class="bi bi-gear"></i></span>
                        <span class="hide-menu">Políticas de Inasistencia</span>
                    </a>
                </li>
                <li class="nav-small-cap">
                    <i class="ti ti-dots nav-small-cap-icon fs-6"></i>
                    <span class="hide-menu">SOLICITUDES</span>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="<?= base_url('index.php/admin-th/solicitudes-capacitacion') ?>" aria-expanded="false">
                        <span><i class="bi bi-clipboard-data"></i></span>
                        <span class="hide-menu">Solicitudes de Capacitación</span>
                    </a>
                </li>
                <li class="nav-small-cap">
                    <i class="ti ti-dots nav-small-cap-icon fs-6"></i>
                    <span class="hide-menu">REPORTES</span>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="<?= base_url('index.php/admin-th/reportes') ?>" aria-expanded="false">
                        <span><i class="bi bi-file-earmark-bar-graph"></i></span>
                        <span class="hide-menu">Reportes del Sistema</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="<?= base_url('index.php/admin-th/estadisticas') ?>" aria-expanded="false">
                        <span><i class="bi bi-bar-chart"></i></span>
                        <span class="hide-menu">Estadísticas</span>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</aside> 