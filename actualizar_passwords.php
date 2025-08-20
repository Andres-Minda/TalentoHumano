<?php
// Script para actualizar las contraseñas de todos los usuarios
echo "=== ACTUALIZANDO CONTRASEÑAS DE USUARIOS ===\n\n";

try {
    $db = new mysqli('localhost', 'root', '', 'talent_human_db');
    
    if ($db->connect_error) {
        die("Error de conexión: " . $db->connect_error);
    }
    
    echo "✅ Conexión exitosa a la base de datos talent_human_db\n\n";
    
    // Generar nuevo hash para la contraseña 123456
    $password = '123456';
    $newHash = password_hash($password, PASSWORD_DEFAULT);
    
    echo "🔑 Nueva contraseña: $password\n";
    echo "🔐 Nuevo hash: $newHash\n\n";
    
    // Actualizar todos los usuarios activos
    $query = "UPDATE usuarios SET password_hash = ? WHERE activo = 1";
    $stmt = $db->prepare($query);
    $stmt->bind_param('s', $newHash);
    
    if ($stmt->execute()) {
        $affectedRows = $stmt->affected_rows;
        echo "✅ Se actualizaron $affectedRows usuarios exitosamente\n\n";
        
        // Verificar que se actualizó correctamente
        $verifyQuery = "SELECT cedula, email, password_hash FROM usuarios WHERE activo = 1 LIMIT 3";
        $result = $db->query($verifyQuery);
        
        echo "🔍 VERIFICACIÓN DE ACTUALIZACIÓN:\n";
        echo str_repeat("-", 80) . "\n";
        echo sprintf("%-15s %-30s %-50s\n", "CÉDULA", "EMAIL", "HASH");
        echo str_repeat("-", 80) . "\n";
        
        while ($row = $result->fetch_assoc()) {
            echo sprintf("%-15s %-30s %-50s\n", 
                $row['cedula'], 
                $row['email'], 
                substr($row['password_hash'], 0, 50) . "..."
            );
        }
        
        echo str_repeat("-", 80) . "\n\n";
        
        // Verificar que la contraseña funciona
        echo "🧪 PRUEBA DE VERIFICACIÓN:\n";
        $testQuery = "SELECT cedula, email, password_hash FROM usuarios WHERE email = 'admin.th@itsi.edu.ec'";
        $testResult = $db->query($testQuery);
        $testUser = $testResult->fetch_assoc();
        
        if ($testUser && password_verify($password, $testUser['password_hash'])) {
            echo "✅ La contraseña '123456' ahora funciona para admin.th@itsi.edu.ec\n";
        } else {
            echo "❌ Error: La contraseña aún no funciona\n";
        }
        
    } else {
        echo "❌ Error al actualizar usuarios: " . $stmt->error . "\n";
    }
    
    echo "\n📝 RESUMEN:\n";
    echo "• Todos los usuarios activos ahora usan la contraseña: 123456\n";
    echo "• El hash ha sido actualizado en la base de datos\n";
    echo "• Puedes probar el login con cualquier usuario activo\n\n";
    
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
