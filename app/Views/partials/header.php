<header class="header">
    <div class="d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center">
            <button class="btn btn-link d-md-none me-2" id="sidebarToggle">
                <i class="bi bi-list"></i>
            </button>
            <h4 class="mb-0">Sistema de Talento Humano</h4>
        </div>
        
        <div class="d-flex align-items-center">
            <div class="dropdown me-3">
                <button class="btn btn-link dropdown-toggle text-decoration-none" type="button" id="notificationsDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-bell"></i>
                    <span class="badge bg-danger rounded-pill ms-1">0</span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="notificationsDropdown">
                    <li><h6 class="dropdown-header">Notificaciones</h6></li>
                    <li><a class="dropdown-item" href="#">No hay notificaciones nuevas</a></li>
                </ul>
            </div>
            
            <div class="dropdown">
                <button class="btn btn-link dropdown-toggle text-decoration-none" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-person-circle me-1"></i>
                    <?= session('nombres') ?? 'Usuario' ?>
                </button>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                    <li><h6 class="dropdown-header">Mi Cuenta</h6></li>
                    <li><a class="dropdown-item" href="<?= base_url('empleado/mi-perfil') ?>"><i class="bi bi-person me-2"></i>Mi Perfil</a></li>
                    <li><a class="dropdown-item" href="<?= base_url('empleado/cuenta') ?>"><i class="bi bi-gear me-2"></i>Configuración</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="<?= base_url('logout') ?>"><i class="bi bi-box-arrow-right me-2"></i>Cerrar Sesión</a></li>
                </ul>
            </div>
        </div>
    </div>
</header>
