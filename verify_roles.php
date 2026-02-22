<?php
/**
 * Verificación de la migración de roles
 */
$host = 'localhost';
$dbname = 'talent_human_db';
$dbuser = 'root';
$dbpass = '';
$newPassword = 'Admin2026!';

$errors = [];
$passes = [];

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $dbuser, $dbpass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "=== VERIFICACIÓN DE MIGRACIÓN ===\n\n";
    
    // 1. Verificar que solo existan roles 2 y 3
    $stmt = $pdo->query("SELECT id_rol, nombre_rol FROM roles ORDER BY id_rol");
    $roles = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $roleIds = array_column($roles, 'id_rol');
    
    if ($roleIds == [2, 3]) {
        $passes[] = "✓ Solo existen roles 2 (AdminTH) y 3 (Empleado)";
    } else {
        $errors[] = "✗ Roles incorrectos: " . implode(', ', $roleIds);
    }
    
    // 2. Verificar que no hay usuarios con roles eliminados
    $stmt = $pdo->query("SELECT COUNT(*) FROM usuarios WHERE id_rol NOT IN (2, 3) AND activo = 1");
    $count = $stmt->fetchColumn();
    
    if ($count == 0) {
        $passes[] = "✓ No hay usuarios activos con roles eliminados";
    } else {
        $errors[] = "✗ Hay $count usuarios con roles eliminados";
    }
    
    // 3. Verificar contraseñas
    $stmt = $pdo->query("SELECT id_usuario, email, password_hash FROM usuarios WHERE activo = 1");
    $allPasswordsOk = true;
    foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $user) {
        if (!password_verify($newPassword, $user['password_hash'])) {
            $errors[] = "✗ Password incorrecto para usuario: " . $user['email'];
            $allPasswordsOk = false;
        }
    }
    if ($allPasswordsOk) {
        $passes[] = "✓ Todas las contraseñas verificadas correctamente";
    }
    
    // 4. Verificar que los archivos de sidebar eliminados ya no existen
    $deletedFiles = [
        'app/Views/partials/sidebar_super_admin.php',
        'app/Views/partials/sidebar_docente.php'
    ];
    foreach ($deletedFiles as $file) {
        if (!file_exists(__DIR__ . '/' . $file)) {
            $passes[] = "✓ Archivo eliminado: $file";
        } else {
            $errors[] = "✗ Archivo NO eliminado: $file";
        }
    }
    
    // 5. Verificar que los archivos de sidebar existentes están presentes
    $existingFiles = [
        'app/Views/partials/sidebar_admin_th.php',
        'app/Views/partials/sidebar_empleado.php'
    ];
    foreach ($existingFiles as $file) {
        if (file_exists(__DIR__ . '/' . $file)) {
            $passes[] = "✓ Archivo existe: $file";
        } else {
            $errors[] = "✗ Archivo NO existe: $file";
        }
    }
    
    // 6. Verificar que Routes.php no contiene rutas de super-admin
    $routesContent = file_get_contents(__DIR__ . '/app/Config/Routes.php');
    if (strpos($routesContent, "super-admin") === false) {
        $passes[] = "✓ Routes.php no contiene rutas de super-admin";
    } else {
        $errors[] = "✗ Routes.php aún contiene rutas de super-admin";
    }
    
    // 7. Verificar que AuthController no redirige a super-admin
    $authContent = file_get_contents(__DIR__ . '/app/Controllers/AuthController.php');
    if (strpos($authContent, "super-admin/dashboard") === false) {
        $passes[] = "✓ AuthController no redirige a super-admin/dashboard";
    } else {
        $errors[] = "✗ AuthController aún redirige a super-admin/dashboard";
    }
    
    // 8. Verificar logout redirige a /login
    if (strpos($authContent, "redirect()->to('/login')") !== false) {
        $passes[] = "✓ Logout redirige a /login";
    } else {
        $errors[] = "✗ Logout NO redirige a /login";
    }
    
    // Imprimir resultados
    echo "PASSES:\n";
    foreach ($passes as $pass) {
        echo "  $pass\n";
    }
    echo "\nERRORS:\n";
    if (empty($errors)) {
        echo "  Ninguno - ¡Todo correcto!\n";
    } else {
        foreach ($errors as $error) {
            echo "  $error\n";
        }
    }
    
    echo "\nRESUMEN: " . count($passes) . " verificaciones pasaron, " . count($errors) . " errores\n";
    echo "\n=== " . (empty($errors) ? "VERIFICACIÓN EXITOSA" : "VERIFICACIÓN FALLIDA") . " ===\n";
    
} catch (PDOException $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
?>
