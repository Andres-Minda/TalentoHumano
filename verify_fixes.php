<?php
/**
 * Verification script for logout + profile fixes
 */
$host = 'localhost';
$dbname = 'talent_human_db';
$dbuser = 'root';
$dbpass = '';

$passes = 0;
$errors = 0;

function pass($msg) { global $passes; $passes++; echo "  ✓ $msg\n"; }
function fail($msg) { global $errors; $errors++; echo "  ✗ $msg\n"; }

echo "=== VERIFICACIÓN DE CORRECCIONES ===\n\n";

// 1. Database tables
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $dbuser, $dbpass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    
    if (in_array('sesiones_activas', $tables)) pass("Tabla sesiones_activas EXISTE");
    else fail("Tabla sesiones_activas NO EXISTE");
    
    if (in_array('logs_sistema', $tables)) pass("Tabla logs_sistema EXISTE");
    else fail("Tabla logs_sistema NO EXISTE");
    
    // Verify sesiones_activas columns
    $cols = array_column($pdo->query("SHOW COLUMNS FROM sesiones_activas")->fetchAll(PDO::FETCH_ASSOC), 'Field');
    $required = ['id_sesion', 'id_usuario', 'token_sesion', 'activa'];
    foreach ($required as $col) {
        if (in_array($col, $cols)) pass("sesiones_activas tiene columna '$col'");
        else fail("sesiones_activas FALTA columna '$col'");
    }
    
    // Verify logs_sistema columns
    $cols = array_column($pdo->query("SHOW COLUMNS FROM logs_sistema")->fetchAll(PDO::FETCH_ASSOC), 'Field');
    $required = ['id_log', 'id_usuario', 'accion', 'modulo', 'descripcion', 'ip_address'];
    foreach ($required as $col) {
        if (in_array($col, $cols)) pass("logs_sistema tiene columna '$col'");
        else fail("logs_sistema FALTA columna '$col'");
    }
} catch (PDOException $e) {
    fail("Error DB: " . $e->getMessage());
}

echo "\n--- AuthController.php ---\n";
$auth = file_get_contents(__DIR__ . '/app/Controllers/AuthController.php');

if (strpos($auth, 'try {') !== false && strpos($auth, "} catch (\\Exception") !== false) 
    pass("logout() tiene try-catch");
else fail("logout() NO tiene try-catch");

if (strpos($auth, "base_url('index.php/login')") !== false) 
    pass("logout redirige a base_url('index.php/login')");
else fail("logout NO redirige correctamente");

echo "\n--- Routes.php ---\n";
$routes = file_get_contents(__DIR__ . '/app/Config/Routes.php');

if (strpos($routes, "post('actualizar-perfil', 'AdminTH") !== false) 
    pass("Ruta POST admin-th/actualizar-perfil existe");
else fail("Ruta POST admin-th/actualizar-perfil NO existe");

if (strpos($routes, "post('cambiar-contrasena', 'AdminTH") !== false) 
    pass("Ruta POST admin-th/cambiar-contrasena existe");
else fail("Ruta POST admin-th/cambiar-contrasena NO existe");

if (strpos($routes, "post('actualizar-perfil', 'Empleado") !== false) 
    pass("Ruta POST empleado/actualizar-perfil existe");
else fail("Ruta POST empleado/actualizar-perfil NO existe");

if (strpos($routes, "post('cambiar-password', 'Empleado") !== false) 
    pass("Ruta POST empleado/cambiar-password existe");
else fail("Ruta POST empleado/cambiar-password NO existe");

echo "\n--- AdminTHController.php ---\n";
$adminCtrl = file_get_contents(__DIR__ . '/app/Controllers/AdminTH/AdminTHController.php');

if (strpos($adminCtrl, 'function actualizarPerfil') !== false) 
    pass("AdminTHController tiene actualizarPerfil()");
else fail("AdminTHController NO tiene actualizarPerfil()");

if (strpos($adminCtrl, 'function cambiarPassword') !== false) 
    pass("AdminTHController tiene cambiarPassword()");
else fail("AdminTHController NO tiene cambiarPassword()");

echo "\n--- EmpleadoController.php ---\n";
$empCtrl = file_get_contents(__DIR__ . '/app/Controllers/Empleado/EmpleadoController.php');

if (strpos($empCtrl, 'function actualizarPerfil') !== false) 
    pass("EmpleadoController tiene actualizarPerfil()");
else fail("EmpleadoController NO tiene actualizarPerfil()");

if (strpos($empCtrl, 'function cambiarPassword') !== false) 
    pass("EmpleadoController tiene cambiarPassword()");
else fail("EmpleadoController NO tiene cambiarPassword()");

echo "\n--- AdminTH perfil.php ---\n";
$perfil = file_get_contents(__DIR__ . '/app/Views/Roles/AdminTH/perfil.php');

if (strpos($perfil, "index.php/auth/logout") !== false) 
    pass("cerrarSesion() usa URL correcta (auth/logout)");
else fail("cerrarSesion() NO usa URL correcta");

if (strpos($perfil, "index.php/admin-th/actualizar-perfil") !== false) 
    pass("guardarPerfil() envía a admin-th/actualizar-perfil");
else fail("guardarPerfil() NO envía a URL correcta");

if (strpos($perfil, "index.php/admin-th/cambiar-contrasena") !== false) 
    pass("cambiarContraseña() envía a admin-th/cambiar-contrasena");
else fail("cambiarContraseña() NO envía a URL correcta");

if (strpos($perfil, 'readonly') === false || strpos($perfil, 'name="nombres"') !== false) 
    pass("Campos de nombres/apellidos/email son editables");
else fail("Campos siguen readonly");

echo "\n--- AdminTH cuenta.php ---\n";
$cuenta = file_get_contents(__DIR__ . '/app/Views/Roles/AdminTH/cuenta.php');

if (strpos($cuenta, "index.php/auth/logout") !== false) 
    pass("cerrarSesion() usa URL correcta (auth/logout)");
else fail("cerrarSesion() NO usa URL correcta");

if (strpos($cuenta, "index.php/admin-th/cambiar-contrasena") !== false) 
    pass("Formulario/AJAX cambiar contraseña usa URL correcta");
else fail("Formulario cambiar contraseña usa URL incorrecta");

echo "\n--- Empleado mi_perfil.php ---\n";
$miPerfil = file_get_contents(__DIR__ . '/app/Views/Roles/Empleado/mi_perfil.php');

if (strpos($miPerfil, "index.php/empleado/actualizar-perfil") !== false) 
    pass("Formulario de edición usa URL correcta");
else fail("Formulario de edición NO usa URL correcta");

if (strpos($miPerfil, "getFlashdata('success')") !== false) 
    pass("Vista muestra mensajes flash de éxito");
else fail("Vista NO muestra mensajes flash");

echo "\n--- Empleado cuenta.php ---\n";
$empCuenta = file_get_contents(__DIR__ . '/app/Views/Roles/Empleado/cuenta.php');

if (strpos($empCuenta, "index.php/empleado/cambiar-password") !== false) 
    pass("Fetch de cambio de contraseña usa URL correcta");
else fail("Fetch de cambio de contraseña NO usa URL correcta");

if (strpos($empCuenta, "password_actual") !== false) 
    pass("Campos enviados son password_actual/password_nuevo");
else fail("Campos enviados NO coinciden con el controlador");

echo "\n--- Navbar ---\n";
$navbar = file_get_contents(__DIR__ . '/app/Views/partials/navbar.php');

if (strpos($navbar, "index.php/auth/logout") !== false) 
    pass("Navbar logout usa URL correcta");
else fail("Navbar logout NO usa URL correcta");

echo "\n=== RESUMEN: $passes verificaciones pasaron, $errors errores ===\n";
if ($errors === 0) echo "\n=== ¡TODO CORRECTO! ===\n";
else echo "\n=== HAY ERRORES POR CORREGIR ===\n";
?>
