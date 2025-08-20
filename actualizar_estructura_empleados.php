<?php
/**
 * Script para actualizar la estructura de la tabla empleados
 */

$host = 'localhost';
$dbname = 'talent_human_db';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "✅ Conexión a la base de datos establecida\n\n";
    
    // Agregar columna tipo_docente
    echo "🔄 Agregando columna tipo_docente...\n";
    try {
        $pdo->exec("ALTER TABLE empleados ADD COLUMN tipo_docente ENUM('Tiempo completo', 'Medio tiempo', 'Tiempo parcial') NULL AFTER tipo_empleado");
        echo "✅ Columna tipo_docente agregada\n";
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'Duplicate column name') !== false) {
            echo "ℹ️ Columna tipo_docente ya existe\n";
        } else {
            echo "❌ Error: " . $e->getMessage() . "\n";
        }
    }
    
    // Agregar columna departamento
    echo "🔄 Agregando columna departamento...\n";
    try {
        $pdo->exec("ALTER TABLE empleados ADD COLUMN departamento VARCHAR(255) NULL AFTER tipo_docente");
        echo "✅ Columna departamento agregada\n";
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'Duplicate column name') !== false) {
            echo "ℹ️ Columna departamento ya existe\n";
        } else {
            echo "❌ Error: " . $e->getMessage() . "\n";
        }
    }
    
    // Actualizar empleados existentes con departamentos
    echo "🔄 Actualizando empleados existentes...\n";
    
    $empleados = [
        ['id' => 1, 'tipo_empleado' => 'ADMINISTRATIVO', 'departamento' => 'Departamento ITSI', 'tipo_docente' => null],
        ['id' => 2, 'tipo_empleado' => 'ADMINISTRATIVO', 'departamento' => 'Recursos Humanos', 'tipo_docente' => null],
        ['id' => 3, 'tipo_empleado' => 'DOCENTE', 'departamento' => 'Departamento General', 'tipo_docente' => 'Tiempo completo']
    ];
    
    foreach ($empleados as $empleado) {
        $stmt = $pdo->prepare("UPDATE empleados SET tipo_empleado = ?, departamento = ?, tipo_docente = ? WHERE id_empleado = ?");
        $stmt->execute([$empleado['tipo_empleado'], $empleado['departamento'], $empleado['tipo_docente'], $empleado['id']]);
        echo "✅ Empleado ID {$empleado['id']} actualizado\n";
    }
    
    echo "\n✅ Estructura de tabla empleados actualizada exitosamente\n";
    
} catch (PDOException $e) {
    echo "❌ Error en la base de datos: " . $e->getMessage() . "\n";
} catch (Exception $e) {
    echo "❌ Error general: " . $e->getMessage() . "\n";
}
?>
