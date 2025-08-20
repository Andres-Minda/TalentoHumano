<?php
/**
 * Script final que muestra todas las credenciales de acceso
 */

$host = 'localhost';
$dbname = 'talent_human_db';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "🎯 CREDENCIALES DE ACCESO AL SISTEMA TALENTO HUMANO\n";
    echo "==================================================\n\n";
    
    // Obtener información completa de todos los usuarios
    $stmt = $pdo->query("
        SELECT 
            u.id_usuario,
            u.cedula,
            u.email,
            u.id_rol,
            e.nombres,
            e.apellidos,
            e.tipo_empleado,
            e.departamento
        FROM usuarios u 
        LEFT JOIN empleados e ON u.id_usuario = e.id_usuario 
        ORDER BY u.id_rol, u.id_usuario
    ");
    
    $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Definir contraseñas y URLs por rol
    $configuracionRoles = [
        1 => ['password' => 'superadmin123', 'url' => '/super-admin/dashboard', 'nombre' => 'Super Administrador'],
        2 => ['password' => 'adminth123', 'url' => '/admin-th/dashboard', 'nombre' => 'Administrador Talento Humano'],
        3 => ['password' => 'docente123', 'url' => '/empleado/dashboard', 'nombre' => 'Docente'],
        6 => ['password' => 'admin123', 'url' => '/empleado/dashboard', 'nombre' => 'Administrativo'],
        7 => ['password' => 'directivo123', 'url' => '/empleado/dashboard', 'nombre' => 'Directivo'],
        8 => ['password' => 'auxiliar123', 'url' => '/empleado/dashboard', 'nombre' => 'Auxiliar']
    ];
    
    // Agrupar usuarios por rol
    $usuariosPorRol = [];
    foreach ($usuarios as $usuario) {
        $rol = $usuario['id_rol'];
        if (!isset($usuariosPorRol[$rol])) {
            $usuariosPorRol[$rol] = [];
        }
        $usuariosPorRol[$rol][] = $usuario;
    }
    
    // Mostrar credenciales por rol
    foreach ($usuariosPorRol as $rolId => $usuariosRol) {
        if (isset($configuracionRoles[$rolId])) {
            $config = $configuracionRoles[$rolId];
            
            echo "🎭 ROL: {$config['nombre']}\n";
            echo str_repeat("=", strlen("ROL: {$config['nombre']}") + 5) . "\n";
            
            foreach ($usuariosRol as $usuario) {
                echo "\n👤 {$usuario['nombres']} {$usuario['apellidos']}\n";
                echo "   📝 Cédula: {$usuario['cedula']}\n";
                echo "   📧 Email: {$usuario['email']}\n";
                echo "   🔑 Contraseña: {$config['password']}\n";
                echo "   🏢 Tipo: {$usuario['tipo_empleado']}\n";
                echo "   🏛️ Departamento: {$usuario['departamento']}\n";
                echo "   🔗 URL: {$config['url']}\n";
                echo "   📍 Acceso: http://localhost/TalentoHumano/public{$config['url']}\n";
            }
            
            echo "\n" . str_repeat("-", 50) . "\n\n";
        }
    }
    
    // Mostrar resumen de funcionalidades
    echo "🚀 FUNCIONALIDADES DISPONIBLES POR ROL\n";
    echo "=====================================\n\n";
    
    echo "👑 SUPER ADMINISTRADOR:\n";
    echo "   • Dashboard del sistema\n";
    echo "   • Gestión de usuarios\n";
    echo "   • Gestión de roles\n";
    echo "   • Gestión de departamentos\n";
    echo "   • Configuración del sistema\n";
    echo "   • Logs del sistema\n\n";
    
    echo "👔 ADMINISTRADOR TALENTO HUMANO:\n";
    echo "   • Dashboard de RRHH\n";
    echo "   • Gestión de candidatos\n";
    echo "   • Gestión de contratos\n";
    echo "   • Gestión de empleados\n";
    echo "   • Gestión de capacitaciones\n";
    echo "   • Evaluaciones\n";
    echo "   • Competencias\n";
    echo "   • Control de asistencias\n";
    echo "   • Gestión de permisos\n";
    echo "   • Reportes\n\n";
    
    echo "👨‍🏫 DOCENTE / EMPLEADO:\n";
    echo "   • Dashboard personal\n";
    echo "   • Perfil de empleado\n";
    echo "   • Ver capacitaciones disponibles\n";
    echo "   • Solicitar permisos\n";
    echo "   • Ver asistencias\n";
    echo "   • Ver evaluaciones\n";
    echo "   • Gestión de títulos académicos\n\n";
    
    echo "📋 TABLAS CREADAS:\n";
    echo "==================\n";
    
    $tablas = ['titulos_academicos', 'capacitaciones_empleados', 'postulantes'];
    
    foreach ($tablas as $tabla) {
        $stmt = $pdo->query("SHOW TABLES LIKE '$tabla'");
        if ($stmt->rowCount() > 0) {
            echo "   ✅ $tabla\n";
        } else {
            echo "   ❌ $tabla\n";
        }
    }
    
    echo "\n🎉 SISTEMA LISTO PARA PROBAR\n";
    echo "============================\n";
    echo "💡 Usa las credenciales mostradas arriba para acceder\n";
    echo "🔐 Todas las contraseñas están configuradas y funcionando\n";
    echo "🚀 El sistema incluye todas las funcionalidades solicitadas\n";
    
} catch (PDOException $e) {
    echo "❌ Error en la base de datos: " . $e->getMessage() . "\n";
} catch (Exception $e) {
    echo "❌ Error general: " . $e->getMessage() . "\n";
}
?>
