<!-- app/Views/partials/sidebar_docente.php -->
<aside class="left-sidebar">
    <div>
        <div class="brand-logo d-flex align-items-center justify-content-between">
            <a href="<?= base_url('docente/dashboard') ?>" class="text-nowrap logo-img">
                                 <img src="<?= base_url('sistema/assets/images/logos/Talento_humano.jpg') ?>" alt="Logo" />
                <span class="ms-2 fw-bold" style="font-size: 1.3rem; color: #000;">Panel del Docente</span>
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
                    <a class="sidebar-link" href="<?= base_url('docente/dashboard') ?>" aria-expanded="false">
                        <span><i class="bi bi-house-door"></i></span>
                        <span class="hide-menu">Dashboard</span>
                    </a>
                </li>
                <li class="nav-small-cap">
                    <i class="ti ti-dots nav-small-cap-icon fs-6"></i>
                    <span class="hide-menu">MI PERFIL</span>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="<?= base_url('docente/perfil') ?>" aria-expanded="false">
                        <span><i class="bi bi-person"></i></span>
                        <span class="hide-menu">Mi Perfil</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="<?= base_url('docente/documentos') ?>" aria-expanded="false">
                        <span><i class="bi bi-file-earmark-text"></i></span>
                        <span class="hide-menu">Mis Documentos</span>
                    </a>
                </li>
                <li class="nav-small-cap">
                    <i class="ti ti-dots nav-small-cap-icon fs-6"></i>
                    <span class="hide-menu">CAPACITACIÓN</span>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="<?= base_url('docente/capacitaciones') ?>" aria-expanded="false">
                        <span><i class="bi bi-mortarboard"></i></span>
                        <span class="hide-menu">Mis Capacitaciones</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="<?= base_url('docente/certificados') ?>" aria-expanded="false">
                        <span><i class="bi bi-award"></i></span>
                        <span class="hide-menu">Certificados</span>
                    </a>
                </li>
                <li class="nav-small-cap">
                    <i class="ti ti-dots nav-small-cap-icon fs-6"></i>
                    <span class="hide-menu">EVALUACIONES</span>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="<?= base_url('docente/evaluaciones') ?>" aria-expanded="false">
                        <span><i class="bi bi-clipboard-data"></i></span>
                        <span class="hide-menu">Mis Evaluaciones</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="<?= base_url('docente/competencias') ?>" aria-expanded="false">
                        <span><i class="bi bi-star"></i></span>
                        <span class="hide-menu">Mis Competencias</span>
                    </a>
                </li>
                <li class="nav-small-cap">
                    <i class="ti ti-dots nav-small-cap-icon fs-6"></i>
                    <span class="hide-menu">ASISTENCIAS</span>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="<?= base_url('docente/asistencias') ?>" aria-expanded="false">
                        <span><i class="bi bi-calendar-check"></i></span>
                        <span class="hide-menu">Mis Asistencias</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="<?= base_url('docente/permisos') ?>" aria-expanded="false">
                        <span><i class="bi bi-calendar-event"></i></span>
                        <span class="hide-menu">Mis Permisos</span>
                    </a>
                </li>
                <li class="nav-small-cap">
                    <i class="ti ti-dots nav-small-cap-icon fs-6"></i>
                    <span class="hide-menu">NÓMINA</span>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="<?= base_url('docente/nomina') ?>" aria-expanded="false">
                        <span><i class="bi bi-cash-stack"></i></span>
                        <span class="hide-menu">Mi Nómina</span>
                    </a>
                </li>

                <li class="nav-small-cap">
                    <i class="ti ti-dots nav-small-cap-icon fs-6"></i>
                    <span class="hide-menu">SOLICITUDES</span>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="<?= base_url('docente/solicitudes') ?>" aria-expanded="false">
                        <span><i class="bi bi-ticket-detailed"></i></span>
                        <span class="hide-menu">Mis Solicitudes</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="<?= base_url('docente/nueva-solicitud') ?>" aria-expanded="false">
                        <span><i class="bi bi-plus-circle"></i></span>
                        <span class="hide-menu">Nueva Solicitud</span>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</aside> 