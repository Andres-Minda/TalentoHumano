<?php
/**
 * Script para resetear credenciales de usuarios del sistema
 * Genera un nuevo hash bcrypt y actualiza TODOS los usuarios activos
 */

$host = 'localhost';
$dbname = 'talent_human_db';
$dbuser = 'root';
$dbpass = '';

// Nueva contraseña para todos los usuarios
$newPassword = 'Admin2026!';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $dbuser, $dbpass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Generar hash bcrypt
    $hash = password_hash($newPassword, PASSWORD_BCRYPT);
    
    echo "=== RESET DE CREDENCIALES ===\n\n";
    echo "Nueva contraseña: $newPassword\n";
    echo "Nuevo hash: $hash\n\n";
    
    // Verificar que el hash funciona ANTES de actualizar
    if (!password_verify($newPassword, $hash)) {
        die("ERROR: El hash generado no verifica correctamente.\n");
    }
    echo "Verificación del hash: OK\n\n";
    
    // Actualizar todos los usuarios activos
    $stmt = $pdo->prepare("UPDATE usuarios SET password_hash = ? WHERE activo = 1");
    $stmt->execute([$hash]);
    $count = $stmt->rowCount();
    
    echo "Usuarios actualizados: $count\n\n";
    
    // Listar usuarios actualizados
    $stmt2 = $pdo->query("
        SELECT u.id_usuario, u.cedula, u.email, u.id_rol, r.nombre_rol, u.password_hash
        FROM usuarios u 
        LEFT JOIN roles r ON u.id_rol = r.id_rol 
        WHERE u.activo = 1 
        ORDER BY u.id_rol
    ");
    
    echo "USUARIOS ACTUALIZADOS:\n";
    echo str_repeat("-", 90) . "\n";
    echo sprintf("%-5s %-15s %-30s %-25s\n", "ID", "CEDULA", "EMAIL", "ROL");
    echo str_repeat("-", 90) . "\n";
    
    while ($row = $stmt2->fetch(PDO::FETCH_ASSOC)) {
        echo sprintf("%-5s %-15s %-30s %-25s\n", 
            $row['id_usuario'],
            $row['cedula'], 
            $row['email'],
            $row['nombre_rol']
        );
        
        // Verificar cada usuario
        $ok = password_verify($newPassword, $row['password_hash']) ? 'OK' : 'FALLO';
        echo "  -> Verificación: $ok\n";
    }
    
    echo "\n=== LISTO ===\n";
    echo "Contraseña para TODOS los usuarios: $newPassword\n";
    
} catch (PDOException $e) {
    echo "Error DB: " . $e->getMessage() . "\n";
}
?>
