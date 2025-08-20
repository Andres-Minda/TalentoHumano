<?php
// Script simple para verificar credenciales del sistema TalentoHumano
echo "=== CREDENCIALES DEL SISTEMA TALENTOHUMANO ===\n\n";

try {
    $db = new mysqli('localhost', 'root', '', 'talent_human_db');
    
    if ($db->connect_error) {
        die("Error de conexión: " . $db->connect_error);
    }
    
    echo "✅ Conexión exitosa a la base de datos talent_human_db\n\n";
    
    // Verificar usuarios activos
    $query = "SELECT u.cedula, u.email, u.estado, r.nombre_rol 
              FROM usuarios u 
              LEFT JOIN roles r ON u.rol_id = r.id_rol 
              WHERE u.estado = 1 
              ORDER BY r.nombre_rol, u.cedula";
    
    $result = $db->query($query);
    
    if ($result && $result->num_rows > 0) {
        echo "👥 USUARIOS ACTIVOS EN EL SISTEMA:\n";
        echo str_repeat("-", 80) . "\n";
        echo sprintf("%-15s %-30s %-25s %-15s\n", "CÉDULA", "EMAIL", "ROL", "ESTADO");
        echo str_repeat("-", 80) . "\n";
        
        while ($row = $result->fetch_assoc()) {
            echo sprintf("%-15s %-30s %-25s %-15s\n", 
                $row['cedula'], 
                $row['email'], 
                $row['nombre_rol'], 
                $row['estado'] ? '✅ Activo' : '❌ Inactivo'
            );
        }
        
        echo str_repeat("-", 80) . "\n\n";
    } else {
        echo "❌ No se encontraron usuarios activos\n\n";
    }
    
    // Verificar roles disponibles
    echo "🎭 ROLES DISPONIBLES EN EL SISTEMA:\n";
    echo str_repeat("-", 50) . "\n";
    $roles = $db->query("SELECT id_rol, nombre_rol FROM roles ORDER BY id_rol");
    
    if ($roles && $roles->num_rows > 0) {
        while ($rol = $roles->fetch_assoc()) {
            echo "ID: {$rol['id_rol']} - {$rol['nombre_rol']}\n";
        }
    } else {
        echo "❌ No se encontraron roles\n";
    }
    
    echo str_repeat("-", 50) . "\n\n";
    
    // Mostrar credenciales de prueba
    echo "🔐 CREDENCIALES DE PRUEBA:\n";
    echo str_repeat("=", 60) . "\n";
    echo "⚠️  IMPORTANTE: TODOS LOS USUARIOS USAN LA CONTRASEÑA: 123456\n\n";
    
    // Buscar usuarios por rol específico
    $roles_usuarios = [
        'Super Administrador' => 'super-admin',
        'Administrador Talento Humano' => 'admin-th',
        'Docente' => 'empleado',
        'Empleado' => 'empleado'
    ];
    
    foreach ($roles_usuarios as $rol_nombre => $rol_clave) {
        $query = "SELECT u.cedula, u.email 
                  FROM usuarios u 
                  JOIN roles r ON u.rol_id = r.id_rol 
                  WHERE r.nombre_rol = ? AND u.estado = 1 
                  LIMIT 1";
        
        $stmt = $db->prepare($query);
        $stmt->bind_param('s', $rol_nombre);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($user = $result->fetch_assoc()) {
            echo "👤 {$rol_nombre}:\n";
            echo "   📧 Email: {$user['email']}\n";
            echo "   🆔 Cédula: {$user['cedula']}\n";
            echo "   🔑 Contraseña: 123456\n";
            echo "   🌐 URL: http://localhost/TalentoHumano/public/index.php/{$rol_clave}/dashboard\n\n";
        } else {
            echo "❌ {$rol_nombre}: No se encontró usuario activo\n\n";
        }
    }
    
    echo "📝 INSTRUCCIONES DE ACCESO:\n";
    echo str_repeat("-", 50) . "\n";
    echo "1️⃣  Asegúrate de que XAMPP esté corriendo (Apache + MySQL)\n";
    echo "2️⃣  Usa siempre la URL: http://localhost/TalentoHumano/public/index.php/...\n";
    echo "3️⃣  La contraseña por defecto es: 123456\n";
    echo "4️⃣  Si no funciona, verifica que la BD talent_human_db esté activa\n\n";
    
    echo "🚀 URLs DIRECTAS PARA ACCESO:\n";
    echo str_repeat("-", 50) . "\n";
    echo "• Super Admin: http://localhost/TalentoHumano/public/index.php/super-admin/dashboard\n";
    echo "• Admin TH: http://localhost/TalentoHumano/public/index.php/admin-th/dashboard\n";
    echo "• Empleado: http://localhost/TalentoHumano/public/index.php/empleado/dashboard\n";
    echo "• Login: http://localhost/TalentoHumano/public/index.php/login\n\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
} finally {
    if (isset($db)) {
        $db->close();
    }
}

// Limpiar archivo temporal
unlink(__FILE__);
?>
