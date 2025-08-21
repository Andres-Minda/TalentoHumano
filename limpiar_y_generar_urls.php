<?php
// Script para limpiar puestos vacíos y generar URLs para los válidos

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
    
    // 1. Eliminar puestos con títulos vacíos
    echo "🗑️ Eliminando puestos con títulos vacíos...\n";
    $stmt = $pdo->prepare("DELETE FROM puestos WHERE titulo IS NULL OR titulo = ''");
    $stmt->execute();
    $puestosEliminados = $stmt->rowCount();
    echo "✅ Se eliminaron {$puestosEliminados} puestos vacíos\n\n";
    
    // 2. Generar URLs para los puestos válidos
    echo "🔧 Generando URLs para puestos válidos...\n";
    $stmt = $pdo->query("SELECT id_puesto, titulo FROM puestos WHERE titulo IS NOT NULL AND titulo != '' ORDER BY id_puesto ASC");
    $puestos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($puestos as $puesto) {
        $idPuesto = $puesto['id_puesto'];
        $titulo = $puesto['titulo'];
        
        // Crear URL única basada en ID y título
        $url = 'postulacion-' . $idPuesto . '-' . strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $titulo)));
        $url = trim($url, '-');
        
        // Actualizar el puesto con la URL generada
        $updateStmt = $pdo->prepare("UPDATE puestos SET url_postulacion = ? WHERE id_puesto = ?");
        $updateStmt->execute([$url, $idPuesto]);
        
        echo "✅ Puesto '{$titulo}' (ID: {$idPuesto}) - URL: {$url}\n";
    }
    
    echo "\n";
    
    // 3. Verificar resultado final
    echo "📋 Estado final de la tabla puestos:\n";
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM puestos");
    $count = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "- Total de puestos: {$count['total']}\n\n";
    
    if ($count['total'] > 0) {
        echo "📊 Puestos con URLs generadas:\n";
        $stmt = $pdo->query("SELECT id_puesto, titulo, url_postulacion FROM puestos ORDER BY id_puesto ASC");
        $puestosFinales = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        foreach ($puestosFinales as $puesto) {
            echo "- ID: {$puesto['id_puesto']} | Título: {$puesto['titulo']}\n";
            echo "  URL: {$puesto['url_postulacion']}\n";
            echo "\n";
        }
    }
    
    echo "✅ Proceso completado exitosamente\n";
    
} catch (PDOException $e) {
    echo "❌ Error de base de datos: " . $e->getMessage() . "\n";
} catch (Exception $e) {
    echo "❌ Error general: " . $e->getMessage() . "\n";
}
?>
