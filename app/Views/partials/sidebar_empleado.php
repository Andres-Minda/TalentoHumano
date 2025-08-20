<?php
// Obtener información del empleado desde la sesión o controlador
$tipoEmpleado = session()->get('tipo_empleado') ?? 'DOCENTE';
$userNombres = session()->get('nombres') ?? 'Usuario';
$userApellidos = session()->get('apellidos') ?? '';
$userRol = session()->get('nombre_rol') ?? 'Empleado';
?>

<aside class="left-sidebar">
    <div>
        <div class="brand-logo d-flex align-items-center justify-content-between">
            <a href="<?= base_url('empleado/dashboard') ?>" class="text-nowrap logo-img">
                <img src="<?= base_url('sistema/assets/images/logos/Talento_humano.jpg') ?>" alt="Logo Talento Humano" />
                <span class="ms-2 fw-bold" style="font-size: 1.3rem; color: #000;">
                    <?php
                    $tipoEmpleado = session('tipo_empleado') ?? 'EMPLEADO';
                    switch ($tipoEmpleado) {
                        case 'DOCENTE':
                            echo 'Panel del <br>Docente<br>';
                            break;
                        case 'ADMINISTRATIVO':
                            echo 'Panel del <br>Administrativo<br>';
                            break;
                        case 'DIRECTIVO':
                            echo 'Panel del <br>Directivo<br>';
                            break;
                        case 'AUXILIAR':
                            echo 'Panel del <br>Auxiliar<br>';
                            break;
                        default:
                            echo 'Panel del <br>Empleado<br>';
                    }
                    ?>
                </span>
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
                    <a class="sidebar-link" href="<?= base_url('empleado/dashboard') ?>" aria-expanded="false">
                        <span><i class="bi bi-house-door"></i></span>
                        <span class="hide-menu">Dashboard</span>
                    </a>
                </li>
                <li class="nav-small-cap">
                    <i class="ti ti-dots nav-small-cap-icon fs-6"></i>
                    <span class="hide-menu">MI PERFIL</span>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="<?= base_url('empleado/mi-perfil') ?>" aria-expanded="false">
                        <span><i class="bi bi-person-circle"></i></span>
                        <span class="hide-menu">Mi Perfil</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="<?= base_url('empleado/documentos') ?>" aria-expanded="false">
                        <span><i class="bi bi-file-earmark-text"></i></span>
                        <span class="hide-menu">Mis Documentos</span>
                    </a>
                </li>
                <li class="nav-small-cap">
                    <i class="ti ti-dots nav-small-cap-icon fs-6"></i>
                    <span class="hide-menu">CAPACITACIONES</span>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="<?= base_url('empleado/capacitaciones') ?>" aria-expanded="false">
                        <span><i class="bi bi-mortarboard"></i></span>
                        <span class="hide-menu">Mis Capacitaciones</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="<?= base_url('empleado/certificados') ?>" aria-expanded="false">
                        <span><i class="bi bi-award"></i></span>
                        <span class="hide-menu">Mis Certificados</span>
                    </a>
                </li>
                <li class="nav-small-cap">
                    <i class="ti ti-dots nav-small-cap-icon fs-6"></i>
                    <span class="hide-menu">EVALUACIONES</span>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="<?= base_url('empleado/evaluaciones') ?>" aria-expanded="false">
                        <span><i class="bi bi-clipboard-check"></i></span>
                        <span class="hide-menu">Mis Evaluaciones</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="<?= base_url('empleado/competencias') ?>" aria-expanded="false">
                        <span><i class="bi bi-star"></i></span>
                        <span class="hide-menu">Mis Competencias</span>
                    </a>
                </li>
                <li class="nav-small-cap">
                    <i class="ti ti-dots nav-small-cap-icon fs-6"></i>
                    <span class="hide-menu">INASISTENCIAS</span>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="<?= base_url('empleado/inasistencias') ?>" aria-expanded="false">
                        <span><i class="bi bi-calendar-x"></i></span>
                        <span class="hide-menu">Mis Inasistencias</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="<?= base_url('empleado/permisos') ?>" aria-expanded="false">
                        <span><i class="bi bi-calendar-check"></i></span>
                        <span class="hide-menu">Mis Permisos</span>
                    </a>
                </li>
                <li class="nav-small-cap">
                    <i class="ti ti-dots nav-small-cap-icon fs-6"></i>
                    <span class="hide-menu">SOLICITUDES</span>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="<?= base_url('empleado/solicitudes') ?>" aria-expanded="false">
                        <span><i class="bi bi-clipboard-data"></i></span>
                        <span class="hide-menu">Mis Solicitudes</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="<?= base_url('empleado/nueva-solicitud') ?>" aria-expanded="false">
                        <span><i class="bi bi-plus-circle"></i></span>
                        <span class="hide-menu">Nueva Solicitud</span>
                    </a>
                </li>
                <?php if (session('tipo_empleado') === 'DOCENTE'): ?>
                <li class="nav-small-cap">
                    <i class="ti ti-dots nav-small-cap-icon fs-6"></i>
                    <span class="hide-menu">FUNCIONALIDADES DOCENTE</span>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="<?= base_url('empleado/evaluaciones-estudiantiles') ?>" aria-expanded="false">
                        <span><i class="bi bi-people"></i></span>
                        <span class="hide-menu">Evaluaciones Estudiantiles</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="<?= base_url('empleado/metodologia-ensenanza') ?>" aria-expanded="false">
                        <span><i class="bi bi-book"></i></span>
                        <span class="hide-menu">Metodología de Enseñanza</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="<?= base_url('empleado/investigacion') ?>" aria-expanded="false">
                        <span><i class="bi bi-search"></i></span>
                        <span class="hide-menu">Investigación</span>
                    </a>
                </li>
                <?php endif; ?>
                <li class="nav-small-cap">
                    <i class="ti ti-dots nav-small-cap-icon fs-6"></i>
                    <span class="hide-menu">GESTIÓN PERSONAL</span>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="<?= base_url('empleado/titulos-academicos') ?>" aria-expanded="false">
                        <span><i class="bi bi-mortarboard-fill"></i></span>
                        <span class="hide-menu">Títulos Académicos</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="<?= base_url('empleado/experiencia-laboral') ?>" aria-expanded="false">
                        <span><i class="bi bi-briefcase"></i></span>
                        <span class="hide-menu">Experiencia Laboral</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="<?= base_url('empleado/solicitudes-generales') ?>" aria-expanded="false">
                        <span><i class="bi bi-file-earmark-text"></i></span>
                        <span class="hide-menu">Solicitudes Generales</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="<?= base_url('empleado/permisos-vacaciones') ?>" aria-expanded="false">
                        <span><i class="bi bi-calendar-check"></i></span>
                        <span class="hide-menu">Permisos y Vacaciones</span>
                    </a>
                </li>
                <?php if (session('tipo_empleado') === 'ADMINISTRATIVO'): ?>
                <li class="nav-small-cap">
                    <i class="ti ti-dots nav-small-cap-icon fs-6"></i>
                    <span class="hide-menu">FUNCIONALIDADES ADMINISTRATIVAS</span>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="<?= base_url('empleado/gestion-procesos') ?>" aria-expanded="false">
                        <span><i class="bi bi-gear"></i></span>
                        <span class="hide-menu">Gestión de Procesos</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="<?= base_url('empleado/reportes-administrativos') ?>" aria-expanded="false">
                        <span><i class="bi bi-file-earmark-bar-graph"></i></span>
                        <span class="hide-menu">Reportes Administrativos</span>
                    </a>
                </li>
                <?php endif; ?>
                <?php if (session('tipo_empleado') === 'DIRECTIVO'): ?>
                <li class="nav-small-cap">
                    <i class="ti ti-dots nav-small-cap-icon fs-6"></i>
                    <span class="hide-menu">FUNCIONALIDADES DIRECTIVAS</span>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="<?= base_url('empleado/gestion-equipo') ?>" aria-expanded="false">
                        <span><i class="bi bi-people-fill"></i></span>
                        <span class="hide-menu">Gestión de Equipo</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="<?= base_url('empleado/indicadores-gestion') ?>" aria-expanded="false">
                        <span><i class="bi bi-graph-up"></i></span>
                        <span class="hide-menu">Indicadores de Gestión</span>
                    </a>
                </li>
                <?php endif; ?>
                <li class="nav-small-cap">
                    <i class="ti ti-dots nav-small-cap-icon fs-6"></i>
                    <span class="hide-menu">CUENTA</span>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="<?= base_url('empleado/cuenta') ?>" aria-expanded="false">
                        <span><i class="bi bi-person-gear"></i></span>
                        <span class="hide-menu">Configuración de Cuenta</span>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</aside>

<style>
.sidebar-empleado .sidebar-item .sidebar-link {
    color: #6c757d;
    transition: all 0.3s ease;
}

.sidebar-empleado .sidebar-item .sidebar-link:hover {
    color: #007bff;
    background-color: rgba(0, 123, 255, 0.1);
}

.sidebar-empleado .nav-small-cap {
    color: #495057;
    font-weight: 600;
    margin-top: 20px;
}

.sidebar-empleado .sidebar-item.active .sidebar-link {
    color: #007bff;
    background-color: rgba(0, 123, 255, 0.1);
}
</style>
