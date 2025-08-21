<?php
// Script para verificar qué puestos tienen títulos válidos

// Configuración de la base de datos
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'talent_human_db';

try {
    // Conectar a la base de datos
    $pdo = new PDO("mysql:host=$host;dbname=$database;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "✅ Conectado exitosamente a la base de datos: $database\n\n";
    
    // Verificar puestos con títulos válidos
    echo "📋 Puestos con títulos válidos:\n";
    $stmt = $pdo->query("SELECT id_puesto, titulo, url_postulacion FROM puestos WHERE titulo IS NOT NULL AND titulo != '' ORDER BY id_puesto ASC");
    $puestos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (count($puestos) > 0) {
        foreach ($puestos as $puesto) {
            echo "- ID: {$puesto['id_puesto']} | Título: {$puesto['titulo']}\n";
            echo "  URL actual: " . ($puesto['url_postulacion'] ?: 'No generada') . "\n";
            echo "\n";
        }
    } else {
        echo "❌ No hay puestos con títulos válidos\n";
    }
    
    // Verificar puestos con títulos vacíos
    echo "📋 Puestos con títulos vacíos:\n";
    $stmt = $pdo->query("SELECT id_puesto, titulo FROM puestos WHERE titulo IS NULL OR titulo = '' ORDER BY id_puesto ASC");
    $puestosVacios = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (count($puestosVacios) > 0) {
        foreach ($puestosVacios as $puesto) {
            echo "- ID: {$puesto['id_puesto']} | Título: [VACÍO]\n";
        }
        echo "\n";
    } else {
        echo "✅ No hay puestos con títulos vacíos\n";
    }
    
    echo "✅ Verificación completada exitosamente\n";
    
} catch (PDOException $e) {
    echo "❌ Error de base de datos: " . $e->getMessage() . "\n";
} catch (Exception $e) {
    echo "❌ Error general: " . $e->getMessage() . "\n";
}
?>
