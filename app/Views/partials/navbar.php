<header class="app-header">
    <nav class="navbar navbar-expand-lg navbar-light" style="background-color: #00367c;">
        <a class="navbar-brand d-flex align-items-center" href="<?= base_url() ?>">
        </a>
        <ul class="navbar-nav">
            <li class="nav-item d-block d-xl-none">
                <a class="nav-link sidebartoggler nav-icon-hover" id="headerCollapse" href="javascript:void(0)">
                    <i class="ti ti-menu-2" style="color: #ffffff;"></i> </a>
            </li>
        </ul>
        <div class="navbar-collapse justify-content-end px-0" id="navbarNav">
            <ul class="navbar-nav flex-row ms-auto align-items-center justify-content-end">
                
                <?php
                // Obtener periodos disponibles para el dropdown
                $db_periodos = \Config\Database::connect();
                $listaPeriodos = $db_periodos->table('periodos_academicos')
                                             ->orderBy('fecha_inicio', 'DESC')
                                             ->limit(10)
                                             ->get()
                                             ->getResultArray();
                                             
                $idPeriodoActual = session('id_periodo');
                $isReadOnly = session('periodo_readonly') === true;
                ?>

                <!-- Selector de Periodo Académico -->
                <li class="nav-item dropdown d-none d-md-flex me-3">
                    <a href="#" class="nav-link px-0 d-flex text-white" data-bs-toggle="dropdown" tabindex="-1" aria-label="Seleccionar periodo">
                        <!-- Ícono SVG nativo de Tabler: calendar-event -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-calendar-event me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                            <path d="M4 5m0 2a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2z"></path>
                            <path d="M16 3l0 4"></path>
                            <path d="M8 3l0 4"></path>
                            <path d="M4 11l16 0"></path>
                            <path d="M8 15h2v2h-2z"></path>
                        </svg>
                        <span>Periodo: <?= esc(session('periodo_nombre') ?? 'Seleccionar') ?></span>
                    </a>
                    
                    <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow shadow-sm">
                        <span class="dropdown-header">Contexto Académico</span>
                        <?php if(empty($listaPeriodos)): ?>
                            <span class="dropdown-item text-muted">No hay periodos configurados</span>
                        <?php else: ?>
                            <?php foreach($listaPeriodos as $p): ?>
                                <a class="dropdown-item <?= ($idPeriodoActual == $p['id_periodo']) ? 'active fw-bold' : '' ?>" href="<?= base_url('index.php/periodo/switch/' . $p['id_periodo']) ?>">
                                    <?= esc($p['nombre']) ?>
                                    
                                    <?php if($p['estado'] === 'Activo'): ?>
                                        <span class="badge bg-success text-white ms-auto">ACTUAL</span>
                                    <?php elseif($p['estado'] === 'Cerrado'): ?>
                                        <span class="badge bg-danger text-white ms-auto">CERRADO</span>
                                    <?php else: ?>
                                        <span class="badge bg-warning text-dark ms-auto">PRÓXIMO</span>
                                    <?php endif; ?>
                                </a>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        
                        <?php if(session('id_rol') == 2): // Solo Admin ?>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item text-primary" href="<?= base_url('index.php/admin-th/periodos-academicos') ?>">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-settings me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                    <path d="M10.325 4.317c.426 -1.756 2.924 -1.756 3.35 0a1.724 1.724 0 0 0 2.573 1.066c1.543 -.94 3.31 .826 2.37 2.37a1.724 1.724 0 0 0 1.065 2.572c1.756 .426 1.756 2.924 0 3.35a1.724 1.724 0 0 0 -1.066 2.573c.94 1.543 -.826 3.31 -2.37 2.37a1.724 1.724 0 0 0 -2.572 1.065c-.426 1.756 -2.924 1.756 -3.35 0a1.724 1.724 0 0 0 -2.573 -1.066c-1.543 .94 -3.31 -.826 -2.37 -2.37a1.724 1.724 0 0 0 -1.065 -2.572c-1.756 -.426 -1.756 -2.924 0 -3.35a1.724 1.724 0 0 0 1.066 -2.573c-.94 -1.543 .826 -3.31 2.37 -2.37c1 .608 2.296 .07 2.572 -1.065z"></path>
                                    <path d="M9 12a3 3 0 1 0 6 0a3 3 0 0 0 -6 0"></path>
                                </svg> 
                                Gestionar Periodos
                            </a>
                        <?php endif; ?>
                    </div>
                </li>

                <!-- Mensaje de bienvenida -->
                <li class="nav-item me-3">
                    <span class="text-white fw-medium">
                        <i class="ti ti-user-circle me-1"></i>
                        Bienvenido al sistema, <?= session('nombres') ?? 'Usuario' ?>
                    </span>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link nav-icon-hover" href="javascript:void(0)" id="drop2" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        <?php 
                        $foto_perfil = session('foto_perfil');
                        if ($foto_perfil && file_exists(FCPATH . 'sistema/assets/images/profile/' . $foto_perfil)) {
                            $foto_url = base_url('sistema/assets/images/profile/' . $foto_perfil);
                        } else {
                            $foto_url = base_url('sistema/assets/images/profile/user-1.jpg');
                        }
                        ?>
                        <img src="<?= $foto_url ?>" alt="Foto de perfil" width="35" height="35" class="rounded-circle" style="object-fit: cover; background-color: white; border: 2px solid #ffffff;">
                    </a>
                    <div class="dropdown-menu dropdown-menu-end dropdown-menu-animate-up" aria-labelledby="drop2">
                        <div class="message-body">
                            <?php if (session('id_rol') == 2): ?>
                                <a href="<?= base_url('index.php/admin-th/mi-perfil') ?>" class="d-flex align-items-center gap-2 dropdown-item">
                                    <i class="ti ti-user fs-6"></i>
                                    <p class="mb-0 fs-3">Mi Perfil</p>
                                </a>
                            <?php else: ?>
                                <a href="<?= base_url('index.php/empleado/mi-perfil') ?>" class="d-flex align-items-center gap-2 dropdown-item">
                                    <i class="ti ti-user fs-6"></i>
                                    <p class="mb-0 fs-3">Mi Perfil</p>
                                </a>
                            <?php endif; ?>
                            <hr class="dropdown-divider">
                            <a href="<?= base_url('index.php/auth/logout') ?>" class="btn btn-outline-danger mx-3 mt-2 d-block">
                                <i class="ti ti-logout me-1"></i>Cerrar sesión
                            </a>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </nav>
</header>

<?php if (isset($isReadOnly) && $isReadOnly): ?>
<div class="alert alert-warning border-0 rounded-0 m-0 d-flex align-items-center justify-content-center py-2 shadow-sm" style="background-color: #fff3cd; color: #856404; font-size: 0.9rem;">
    <i class="ti ti-lock fs-5 me-2"></i>
    <span><strong>Modo Histórico Activo:</strong> Estás visualizando un periodo académico cerrado. El sistema se encuentra en <strong>Solo Lectura</strong>.</span>
</div>
<?php endif; ?>
