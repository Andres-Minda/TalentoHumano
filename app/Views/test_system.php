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
                    <h4 class="card-title">Test del Sistema - Estructura ITSI</h4>
                </div>
                <div class="card-body">
                    <h5>✅ Cambios Aplicados:</h5>
                    <ul>
                        <li>✅ Layout actualizado con estructura ITSI</li>
                        <li>✅ Navbar con estructura original ITSI</li>
                        <li>✅ Archivos CSS y JS copiados del sistema ITSI</li>
                        <li>✅ Bootstrap y jQuery locales en lugar de CDN</li>
                        <li>✅ Simplebar local en lugar de CDN</li>
                    </ul>
                    
                    <h5>🧪 Instrucciones de Prueba:</h5>
                    <ol>
                        <li>Haz clic en la foto de perfil en la barra de navegación</li>
                        <li>Deberías ver un menú desplegable con animación</li>
                        <li>Verifica que las opciones "Mi Perfil", "Mi Cuenta" y "Cerrar sesión" aparezcan</li>
                        <li>El sidebar debería funcionar correctamente</li>
                    </ol>
                    
                    <div class="alert alert-success">
                        <strong>Estado del sistema:</strong>
                        <ul class="mb-0 mt-2">
                            <li>✅ Layout funcionando</li>
                            <li>✅ Navbar funcionando</li>
                            <li>✅ Sidebar funcionando</li>
                            <li>❓ Dropdown - <strong>Pruébalo ahora</strong></li>
                        </ul>
                    </div>
                    
                    <div class="alert alert-info">
                        <strong>Archivos copiados del sistema ITSI:</strong>
                        <ul class="mb-0 mt-2">
                            <li>✅ styles.min.css (391KB)</li>
                            <li>✅ custom.css</li>
                            <li>✅ sidebarmenu.js</li>
                            <li>✅ app.min.js</li>
                            <li>✅ dashboard.js</li>
                            <li>✅ Librerías (jquery, bootstrap, simplebar, apexcharts)</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?> 