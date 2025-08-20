<?php
/**
 * Script para verificar las restricciones de la tabla empleados
 */

$host = 'localhost';
$dbname = 'talent_human_db';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "✅ Conexión a la base de datos establecida\n\n";
    
    // Verificar estructura de la tabla empleados
    echo "🔍 Verificando estructura de la tabla empleados...\n";
    
    $stmt = $pdo->query("DESCRIBE empleados");
    $columnas = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($columnas as $columna) {
        if ($columna['Field'] === 'tipo_empleado') {
            echo "📋 Columna: {$columna['Field']}\n";
            echo "   Tipo: {$columna['Type']}\n";
            echo "   Null: {$columna['Null']}\n";
            echo "   Key: {$columna['Key']}\n";
            echo "   Default: {$columna['Default']}\n";
            echo "   Extra: {$columna['Extra']}\n\n";
        }
    }
    
    // Verificar valores actuales en tipo_empleado
    echo "🔍 Verificando valores actuales en tipo_empleado...\n";
    
    $stmt = $pdo->query("SELECT DISTINCT tipo_empleado FROM empleados");
    $valores = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    echo "Valores actuales:\n";
    foreach ($valores as $valor) {
        echo "- {$valor}\n";
    }
    echo "\n";
    
    // Verificar si hay restricciones ENUM
    echo "🔍 Verificando restricciones ENUM...\n";
    
    $stmt = $pdo->query("SHOW COLUMNS FROM empleados LIKE 'tipo_empleado'");
    $columna = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (strpos($columna['Type'], 'enum') !== false) {
        echo "La columna tipo_empleado es ENUM con valores:\n";
        preg_match_all("/'([^']+)'/", $columna['Type'], $matches);
        foreach ($matches[1] as $valor) {
            echo "- {$valor}\n";
        }
    } else {
        echo "La columna tipo_empleado NO es ENUM\n";
    }
    
} catch (PDOException $e) {
    echo "❌ Error en la base de datos: " . $e->getMessage() . "\n";
} catch (Exception $e) {
    echo "❌ Error general: " . $e->getMessage() . "\n";
}
?>
