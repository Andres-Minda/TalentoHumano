<?php
/**
 * Script de migración: Simplificar roles del sistema a 2 perfiles
 * - Admin TH (id_rol = 2)
 * - Empleado (id_rol = 3)
 */

$host = 'localhost';
$dbname = 'talent_human_db';
$dbuser = 'root';
$dbpass = '';

$newPassword = 'Admin2026!';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $dbuser, $dbpass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "=== MIGRACIÓN DE ROLES ===\n\n";
    
    // 1. Mostrar estado actual
    echo "--- ESTADO ACTUAL ---\n";
    $stmt = $pdo->query("
        SELECT r.id_rol, r.nombre_rol, COUNT(u.id_usuario) as total_usuarios
        FROM roles r
        LEFT JOIN usuarios u ON u.id_rol = r.id_rol AND u.activo = 1
        GROUP BY r.id_rol, r.nombre_rol
        ORDER BY r.id_rol
    ");
    foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
        echo sprintf("  Rol %d: %-30s (%d usuarios)\n", $row['id_rol'], $row['nombre_rol'], $row['total_usuarios']);
    }
    echo "\n";
    
    // 2. Reasignar usuarios de SuperAdmin (1) -> AdminTH (2)
    $stmt = $pdo->prepare("UPDATE usuarios SET id_rol = 2 WHERE id_rol = 1");
    $stmt->execute();
    echo "SuperAdmin (1) -> AdminTH (2): " . $stmt->rowCount() . " usuarios reasignados\n";
    
    // 3. Reasignar ADMINISTRATIVO (6) -> Empleado (3)
    $stmt = $pdo->prepare("UPDATE usuarios SET id_rol = 3 WHERE id_rol = 6");
    $stmt->execute();
    echo "ADMINISTRATIVO (6) -> Empleado (3): " . $stmt->rowCount() . " usuarios reasignados\n";
    
    // 4. Reasignar DIRECTIVO (7) -> Empleado (3)
    $stmt = $pdo->prepare("UPDATE usuarios SET id_rol = 3 WHERE id_rol = 7");
    $stmt->execute();
    echo "DIRECTIVO (7) -> Empleado (3): " . $stmt->rowCount() . " usuarios reasignados\n";
    
    // 5. Reasignar AUXILIAR (8) -> Empleado (3)
    $stmt = $pdo->prepare("UPDATE usuarios SET id_rol = 3 WHERE id_rol = 8");
    $stmt->execute();
    echo "AUXILIAR (8) -> Empleado (3): " . $stmt->rowCount() . " usuarios reasignados\n\n";
    
    // 6. Eliminar roles innecesarios
    $pdo->exec("DELETE FROM roles WHERE id_rol IN (1, 6, 7, 8)");
    echo "Roles eliminados: SuperAdministrador (1), ADMINISTRATIVO (6), DIRECTIVO (7), AUXILIAR (8)\n\n";
    
    // 7. Renombrar rol 3 a "Empleado" para que sea más claro
    $pdo->exec("UPDATE roles SET nombre_rol = 'Empleado', descripcion = 'Docentes, administrativos y todo el personal' WHERE id_rol = 3");
    echo "Rol 3 renombrado a 'Empleado'\n\n";
    
    // 8. Actualizar contraseñas
    $hash = password_hash($newPassword, PASSWORD_BCRYPT);
    $stmt = $pdo->prepare("UPDATE usuarios SET password_hash = ? WHERE activo = 1");
    $stmt->execute([$hash]);
    echo "Contraseñas actualizadas: " . $stmt->rowCount() . " usuarios\n\n";
    
    // 9. Mostrar estado final
    echo "--- ESTADO FINAL ---\n";
    $stmt = $pdo->query("
        SELECT u.id_usuario, u.cedula, u.email, r.nombre_rol, u.id_rol
        FROM usuarios u
        JOIN roles r ON u.id_rol = r.id_rol
        WHERE u.activo = 1
        ORDER BY u.id_rol, u.id_usuario
    ");
    echo sprintf("  %-5s %-15s %-30s %-25s\n", "ID", "CÉDULA", "EMAIL", "ROL");
    echo "  " . str_repeat("-", 80) . "\n";
    foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
        echo sprintf("  %-5s %-15s %-30s %-25s\n", $row['id_usuario'], $row['cedula'], $row['email'], $row['nombre_rol']);
        
        // Verificar contraseña
        $verify = $pdo->prepare("SELECT password_hash FROM usuarios WHERE id_usuario = ?");
        $verify->execute([$row['id_usuario']]);
        $h = $verify->fetchColumn();
        $ok = password_verify($newPassword, $h) ? 'OK' : 'FALLO';
        echo "    Password verificación: $ok\n";
    }
    
    echo "\n--- ROLES DISPONIBLES ---\n";
    $stmt = $pdo->query("SELECT id_rol, nombre_rol, descripcion FROM roles ORDER BY id_rol");
    foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
        echo sprintf("  Rol %d: %s - %s\n", $row['id_rol'], $row['nombre_rol'], $row['descripcion']);
    }
    
    echo "\n=== MIGRACIÓN COMPLETADA ===\n";
    echo "Contraseña para TODOS los usuarios: $newPassword\n";
    
} catch (PDOException $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
?>
