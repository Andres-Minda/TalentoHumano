<?php
// Simulate a logged-in user session for testing
session()->set([
    'id_usuario' => 1,
    'cedula' => '9999999999',
    'email' => 'superadmin@universidad.edu',
    'id_rol' => 1,
    'nombre_rol' => 'SuperAdministrador',
    'nombres' => 'Super',
    'apellidos' => 'Admin',
    'tipo_empleado' => 'Administrativo',
    'isLoggedIn' => true,
    'login_time' => time()
]);

$sidebar = 'sidebar_super_admin';
?>

<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Test de Dropdown</h4>
                </div>
                <div class="card-body">
                    <h5>Instrucciones:</h5>
                    <ol>
                        <li>Haz clic en la foto de perfil en la barra de navegación</li>
                        <li>Deberías ver un menú desplegable con las opciones:</li>
                        <ul>
                            <li>Mi Perfil</li>
                            <li>Mi Cuenta</li>
                            <li>Cerrar sesión</li>
                        </ul>
                        <li>Si el dropdown funciona, el sistema está funcionando correctamente</li>
                    </ol>
                    
                    <div class="alert alert-info">
                        <strong>Estado del sistema:</strong>
                        <ul class="mb-0 mt-2">
                            <li>✅ Login funcionando</li>
                            <li>✅ Navegación funcionando</li>
                            <li>✅ Sidebar funcionando</li>
                            <li>❓ Dropdown - <strong>Pruébalo ahora</strong></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?> 