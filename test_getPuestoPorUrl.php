<?php
// Script para probar exactamente qué está devolviendo el método getPuestoPorUrl

// Simular el entorno de CodeIgniter
require_once 'app/Config/Database.php';
require_once 'app/Models/PuestoModel.php';

echo "🧪 PRUEBA DEL MÉTODO getPuestoPorUrl\n";
echo "=====================================\n\n";

try {
    // Crear instancia del modelo
    $puestoModel = new App\Models\PuestoModel();
    
    echo "✅ Modelo PuestoModel creado exitosamente\n\n";
    
    // URL a probar
    $urlPostulacion = 'postulacion-12-desarrollador-web-full-stack';
    echo "🔍 Probando URL: $urlPostulacion\n\n";
    
    // Llamar al método
    echo "📞 Llamando a getPuestoPorUrl()...\n";
    $puesto = $puestoModel->getPuestoPorUrl($urlPostulacion);
    
    if ($puesto) {
        echo "✅ getPuestoPorUrl() devolvió resultado:\n";
        foreach ($puesto as $key => $value) {
            echo "   $key: $value\n";
        }
        
        echo "\n🔍 Verificando criterios de validación:\n";
        
        // Verificar activo = 1
        $activo = $puesto['activo'] ?? null;
        echo "   - activo = $activo: " . ($activo == 1 ? "✅ VÁLIDO" : "❌ INVÁLIDO") . "\n";
        
        // Verificar estado = 'Abierto'
        $estado = $puesto['estado'] ?? null;
        echo "   - estado = '$estado': " . ($estado === 'Abierto' ? "✅ VÁLIDO" : "❌ INVÁLIDO") . "\n";
        
        // Verificar fecha límite
        $fechaLimite = $puesto['fecha_limite'] ?? null;
        $fechaActual = date('Y-m-d');
        $fechaLimiteTimestamp = strtotime($fechaLimite);
        $fechaActualTimestamp = strtotime($fechaActual);
        
        echo "   - fecha_limite = '$fechaLimite': ";
        if ($fechaLimiteTimestamp >= $fechaActualTimestamp) {
            echo "✅ VÁLIDO (futura)\n";
        } else {
            echo "❌ INVÁLIDO (expirada)\n";
        }
        
        // Verificar vacantes disponibles
        $vacantes = $puesto['vacantes_disponibles'] ?? null;
        echo "   - vacantes_disponibles = $vacantes: " . ($vacantes > 0 ? "✅ VÁLIDO" : "❌ INVÁLIDO") . "\n";
        
        echo "\n🎯 RESULTADO: El puesto debería ser válido para postulaciones\n";
        
    } else {
        echo "❌ getPuestoPorUrl() devolvió NULL\n";
        echo "🔍 Esto significa que no se encontró el puesto o falló algún filtro\n\n";
        
        // Verificar paso a paso
        echo "🔍 VERIFICACIÓN PASO A PASO:\n";
        
        // 1. Buscar solo por URL
        echo "1️⃣ Buscando solo por URL...\n";
        $puestoSoloUrl = $puestoModel->where('url_postulacion', $urlPostulacion)->first();
        if ($puestoSoloUrl) {
            echo "   ✅ Encontrado por URL\n";
        } else {
            echo "   ❌ NO encontrado por URL\n";
        }
        
        // 2. Buscar por URL + activo
        echo "2️⃣ Buscando por URL + activo = 1...\n";
        $puestoUrlActivo = $puestoModel->where('url_postulacion', $urlPostulacion)
                                      ->where('activo', 1)
                                      ->first();
        if ($puestoUrlActivo) {
            echo "   ✅ Encontrado por URL + activo\n";
        } else {
            echo "   ❌ NO encontrado por URL + activo\n";
        }
        
        // 3. Buscar por URL + activo + estado
        echo "3️⃣ Buscando por URL + activo + estado = 'Abierto'...\n";
        $puestoUrlActivoEstado = $puestoModel->where('url_postulacion', $urlPostulacion)
                                            ->where('activo', 1)
                                            ->where('estado', 'Abierto')
                                            ->first();
        if ($puestoUrlActivoEstado) {
            echo "   ✅ Encontrado por URL + activo + estado\n";
        } else {
            echo "   ❌ NO encontrado por URL + activo + estado\n";
        }
        
        // 4. Buscar por URL + activo + estado + fecha
        echo "4️⃣ Buscando por URL + activo + estado + fecha >= hoy...\n";
        $puestoUrlActivoEstadoFecha = $puestoModel->where('url_postulacion', $urlPostulacion)
                                                 ->where('activo', 1)
                                                 ->where('estado', 'Abierto')
                                                 ->where('fecha_limite >=', date('Y-m-d'))
                                                 ->first();
        if ($puestoUrlActivoEstadoFecha) {
            echo "   ✅ Encontrado por URL + activo + estado + fecha\n";
        } else {
            echo "   ❌ NO encontrado por URL + activo + estado + fecha\n";
        }
        
        // 5. Buscar por URL + activo + estado + fecha + vacantes
        echo "5️⃣ Buscando por URL + activo + estado + fecha + vacantes > 0...\n";
        $puestoCompleto = $puestoModel->where('url_postulacion', $urlPostulacion)
                                     ->where('activo', 1)
                                     ->where('estado', 'Abierto')
                                     ->where('fecha_limite >=', date('Y-m-d'))
                                     ->where('vacantes_disponibles >', 0)
                                     ->first();
        if ($puestoCompleto) {
            echo "   ✅ Encontrado por todos los filtros\n";
        } else {
            echo "   ❌ NO encontrado por todos los filtros\n";
        }
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "📍 Archivo: " . $e->getFile() . "\n";
    echo "📍 Línea: " . $e->getLine() . "\n";
}
?>
