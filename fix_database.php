<?php
/**
 * Fix: Create missing database tables and verify existing structure
 */
$host = 'localhost';
$dbname = 'talent_human_db';
$dbuser = 'root';
$dbpass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $dbuser, $dbpass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "=== DIAGNÓSTICO DE BASE DE DATOS ===\n\n";
    
    // 1. Listar tablas existentes
    $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    echo "Tablas existentes (" . count($tables) . "):\n";
    foreach ($tables as $t) {
        echo "  - $t\n";
    }
    echo "\n";
    
    // 2. Verificar tablas necesarias
    $required = ['sesiones_activas', 'logs_sistema', 'usuarios', 'empleados', 'roles'];
    foreach ($required as $table) {
        $exists = in_array($table, $tables);
        echo ($exists ? "✓" : "✗") . " $table: " . ($exists ? "EXISTE" : "NO EXISTE") . "\n";
    }
    echo "\n";
    
    // 3. Crear tabla sesiones_activas si no existe
    if (!in_array('sesiones_activas', $tables)) {
        echo "Creando tabla sesiones_activas...\n";
        $pdo->exec("
            CREATE TABLE sesiones_activas (
                id_sesion INT AUTO_INCREMENT PRIMARY KEY,
                id_usuario INT NOT NULL,
                token_sesion VARCHAR(255) NOT NULL,
                fecha_inicio DATETIME DEFAULT CURRENT_TIMESTAMP,
                fecha_ultima_actividad DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                ip_address VARCHAR(45) DEFAULT NULL,
                user_agent TEXT DEFAULT NULL,
                activa TINYINT(1) DEFAULT 1,
                INDEX idx_usuario (id_usuario),
                INDEX idx_token (token_sesion),
                INDEX idx_activa (activa),
                FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario) ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
        ");
        echo "  ✓ Tabla sesiones_activas creada\n";
    }
    
    // 4. Crear tabla logs_sistema si no existe
    if (!in_array('logs_sistema', $tables)) {
        echo "Creando tabla logs_sistema...\n";
        $pdo->exec("
            CREATE TABLE logs_sistema (
                id_log INT AUTO_INCREMENT PRIMARY KEY,
                id_usuario INT DEFAULT NULL,
                accion VARCHAR(100) NOT NULL,
                modulo VARCHAR(50) NOT NULL,
                descripcion TEXT DEFAULT NULL,
                fecha_accion DATETIME DEFAULT CURRENT_TIMESTAMP,
                ip_address VARCHAR(45) DEFAULT NULL,
                INDEX idx_usuario (id_usuario),
                INDEX idx_fecha (fecha_accion),
                INDEX idx_modulo (modulo),
                FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario) ON DELETE SET NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
        ");
        echo "  ✓ Tabla logs_sistema creada\n";
    }
    
    // 5. Verificar estructura de tabla empleados
    echo "\nEstructura de tabla empleados:\n";
    $cols = $pdo->query("SHOW COLUMNS FROM empleados")->fetchAll(PDO::FETCH_ASSOC);
    foreach ($cols as $col) {
        echo "  {$col['Field']} ({$col['Type']}) " . ($col['Null'] === 'YES' ? 'NULL' : 'NOT NULL') . "\n";
    }
    
    // 6. Verificar estructura de tabla usuarios
    echo "\nEstructura de tabla usuarios:\n";
    $cols = $pdo->query("SHOW COLUMNS FROM usuarios")->fetchAll(PDO::FETCH_ASSOC);
    foreach ($cols as $col) {
        echo "  {$col['Field']} ({$col['Type']}) " . ($col['Null'] === 'YES' ? 'NULL' : 'NOT NULL') . "\n";
    }
    
    // 7. Verificar datos actuales de usuarios + empleados
    echo "\nUsuarios con sus datos de empleado:\n";
    $stmt = $pdo->query("
        SELECT u.id_usuario, u.cedula, u.email, u.id_rol, r.nombre_rol,
               e.id_empleado, e.nombres, e.apellidos, e.tipo_empleado
        FROM usuarios u
        LEFT JOIN roles r ON r.id_rol = u.id_rol
        LEFT JOIN empleados e ON e.id_usuario = u.id_usuario
        WHERE u.activo = 1
        ORDER BY u.id_rol, u.id_usuario
    ");
    foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
        $empInfo = $row['id_empleado'] ? "Empleado #{$row['id_empleado']}: {$row['nombres']} {$row['apellidos']} ({$row['tipo_empleado']})" : "SIN REGISTRO EN EMPLEADOS";
        echo "  User {$row['id_usuario']} ({$row['email']}) - Rol: {$row['nombre_rol']} - $empInfo\n";
    }
    
    echo "\n=== DIAGNÓSTICO COMPLETADO ===\n";
    
} catch (PDOException $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
?>
