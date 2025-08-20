<!-- app/Views/partials/sidebar_admin_th.php -->
<aside class="left-sidebar">
    <div>
        <div class="brand-logo d-flex align-items-center justify-content-between">
            <a href="<?= base_url('admin-th/dashboard') ?>" class="text-nowrap logo-img">
                <img src="<?= base_url('sistema/assets/images/logos/Talento_humano.jpg') ?>" alt="Logo" />
                <span class="ms-2 fw-bold" style="font-size: 1.3rem; color: #000;">Panel del Administrador<br> Talento Humano</span>
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
                    <a class="sidebar-link" href="<?= base_url('admin-th/dashboard') ?>" aria-expanded="false">
                        <span><i class="bi bi-house-door"></i></span>
                        <span class="hide-menu">Dashboard</span>
                    </a>
                </li>
                <li class="nav-small-cap">
                    <i class="ti ti-dots nav-small-cap-icon fs-6"></i>
                    <span class="hide-menu">GESTIÓN DE EMPLEADOS</span>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="<?= base_url('admin-th/empleados') ?>" aria-expanded="false">
                        <span><i class="bi bi-people"></i></span>
                        <span class="hide-menu">Gestión de Empleados</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="<?= base_url('admin-th/departamentos') ?>" aria-expanded="false">
                        <span><i class="bi bi-building"></i></span>
                        <span class="hide-menu">Departamentos</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="<?= base_url('admin-th/puestos') ?>" aria-expanded="false">
                        <span><i class="bi bi-briefcase"></i></span>
                        <span class="hide-menu">Puestos de Trabajo</span>
                    </a>
                </li>
                <li class="nav-small-cap">
                    <i class="ti ti-dots nav-small-cap-icon fs-6"></i>
                    <span class="hide-menu">RECLUTAMIENTO</span>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="<?= base_url('admin-th/vacantes') ?>" aria-expanded="false">
                        <span><i class="bi bi-file-earmark-text"></i></span>
                        <span class="hide-menu">Gestión de Vacantes</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="<?= base_url('admin-th/candidatos') ?>" aria-expanded="false">
                        <span><i class="bi bi-person-plus"></i></span>
                        <span class="hide-menu">Candidatos</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="<?= base_url('admin-th/contratos') ?>" aria-expanded="false">
                        <span><i class="bi bi-file-earmark-check"></i></span>
                        <span class="hide-menu">Contratos</span>
                    </a>
                </li>
                <li class="nav-small-cap">
                    <i class="ti ti-dots nav-small-cap-icon fs-6"></i>
                    <span class="hide-menu">CAPACITACIÓN</span>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="<?= base_url('admin-th/capacitaciones') ?>" aria-expanded="false">
                        <span><i class="bi bi-mortarboard"></i></span>
                        <span class="hide-menu">Gestión de Capacitaciones</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="<?= base_url('admin-th/empleados-capacitaciones') ?>" aria-expanded="false">
                        <span><i class="bi bi-people-fill"></i></span>
                        <span class="hide-menu">Asignación de Capacitaciones</span>
                    </a>
                </li>
                <li class="nav-small-cap">
                    <i class="ti ti-dots nav-small-cap-icon fs-6"></i>
                    <span class="hide-menu">EVALUACIONES</span>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="<?= base_url('admin-th/evaluaciones') ?>" aria-expanded="false">
                        <span><i class="bi bi-clipboard-data"></i></span>
                        <span class="hide-menu">Gestión de Evaluaciones</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="<?= base_url('admin-th/competencias') ?>" aria-expanded="false">
                        <span><i class="bi bi-star"></i></span>
                        <span class="hide-menu">Competencias</span>
                    </a>
                </li>
                <li class="nav-small-cap">
                    <i class="ti ti-dots nav-small-cap-icon fs-6"></i>
                    <span class="hide-menu">INASISTENCIAS</span>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="<?= base_url('admin-th/inasistencias') ?>" aria-expanded="false">
                        <span><i class="bi bi-calendar-x"></i></span>
                        <span class="hide-menu">Gestión de Inasistencias</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="<?= base_url('admin-th/permisos') ?>" aria-expanded="false">
                        <span><i class="bi bi-calendar-event"></i></span>
                        <span class="hide-menu">Gestión de Permisos</span>
                    </a>
                </li>
                <li class="nav-small-cap">
                    <i class="ti ti-dots nav-small-cap-icon fs-6"></i>
                    <span class="hide-menu">NÓMINAS</span>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="<?= base_url('admin-th/nominas') ?>" aria-expanded="false">
                        <span><i class="bi bi-cash-stack"></i></span>
                        <span class="hide-menu">Gestión de Nóminas</span>
                    </a>
                </li>

                <li class="nav-small-cap">
                    <i class="ti ti-dots nav-small-cap-icon fs-6"></i>
                    <span class="hide-menu">REPORTES</span>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="<?= base_url('admin-th/reportes') ?>" aria-expanded="false">
                        <span><i class="bi bi-bar-chart"></i></span>
                        <span class="hide-menu">Reportes y Analítica</span>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</aside> 