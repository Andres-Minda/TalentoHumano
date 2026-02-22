<?php
/**
 * Reset completo: elimina todos los usuarios y crea 2 nuevos
 */
$host = 'localhost';
$dbname = 'talent_human_db';
$dbuser = 'root';
$dbpass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $dbuser, $dbpass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $password = password_hash('Admin2026!', PASSWORD_DEFAULT);
    
    echo "=== RESET COMPLETO DE USUARIOS ===\n\n";
    
    // Desactivar foreign keys temporalmente
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 0");
    
    // 1. Limpiar tablas dependientes
    $pdo->exec("DELETE FROM sesiones_activas");
    echo "✓ Tabla sesiones_activas limpiada\n";
    
    $pdo->exec("DELETE FROM logs_sistema");
    echo "✓ Tabla logs_sistema limpiada\n";
    
    // 2. Eliminar empleados
    $pdo->exec("DELETE FROM empleados");
    echo "✓ Todos los empleados eliminados\n";
    
    // 3. Eliminar usuarios
    $pdo->exec("DELETE FROM usuarios");
    echo "✓ Todos los usuarios eliminados\n";
    
    // Reset auto_increment
    $pdo->exec("ALTER TABLE usuarios AUTO_INCREMENT = 1");
    $pdo->exec("ALTER TABLE empleados AUTO_INCREMENT = 1");
    
    // 4. Crear usuario Admin TH
    $pdo->exec("INSERT INTO usuarios (cedula, email, password_hash, id_rol, activo, password_changed) 
                VALUES ('0802829192', 'admin@mail.com', '$password', 2, 1, 1)");
    $adminId = $pdo->lastInsertId();
    echo "\n✓ Usuario Admin TH creado (ID: $adminId)\n";
    echo "  Cédula: 0802829192\n";
    echo "  Email: admin@mail.com\n";
    echo "  Contraseña: Admin2026!\n";
    
    // 5. Crear empleado para Admin TH
    $pdo->exec("INSERT INTO empleados (id_usuario, nombres, apellidos, tipo_empleado, departamento, fecha_ingreso, estado)
                VALUES ($adminId, 'Leonardo', 'Minda', 'ADMINISTRATIVO', 'Talento Humano', CURDATE(), 'ACTIVO')");
    echo "  Empleado: Leonardo Minda\n";
    
    // 6. Crear usuario Empleado de ejemplo
    $pdo->exec("INSERT INTO usuarios (cedula, email, password_hash, id_rol, activo, password_changed) 
                VALUES ('0900000001', 'empleado@mail.com', '$password', 3, 1, 1)");
    $empId = $pdo->lastInsertId();
    echo "\n✓ Usuario Empleado creado (ID: $empId)\n";
    echo "  Cédula: 0900000001\n";
    echo "  Email: empleado@mail.com\n";
    echo "  Contraseña: Admin2026!\n";
    
    // 7. Crear empleado para el usuario Empleado
    $pdo->exec("INSERT INTO empleados (id_usuario, nombres, apellidos, tipo_empleado, departamento, fecha_ingreso, estado)
                VALUES ($empId, 'Empleado', 'Prueba', 'DOCENTE', 'Departamento General', CURDATE(), 'ACTIVO')");
    echo "  Empleado: Empleado Prueba\n";
    
    // Reactivar foreign keys
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 1");
    
    // Verificación final
    echo "\n=== VERIFICACIÓN FINAL ===\n";
    $stmt = $pdo->query("
        SELECT u.id_usuario, u.cedula, u.email, r.nombre_rol, e.nombres, e.apellidos
        FROM usuarios u
        JOIN roles r ON r.id_rol = u.id_rol
        JOIN empleados e ON e.id_usuario = u.id_usuario
    ");
    foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
        echo "  [{$row['nombre_rol']}] {$row['nombres']} {$row['apellidos']} | Cédula: {$row['cedula']} | Email: {$row['email']}\n";
    }
    
    $total = $pdo->query("SELECT COUNT(*) FROM usuarios")->fetchColumn();
    echo "\nTotal usuarios: $total\n";
    echo "\n=== RESET COMPLETADO ===\n";
    
} catch (PDOException $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
?>
