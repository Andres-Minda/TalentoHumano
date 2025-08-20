<?php
/**
 * Script para crear usuarios faltantes y actualizar contraseñas
 */

$host = 'localhost';
$dbname = 'talent_human_db';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "✅ Conexión a la base de datos establecida\n\n";
    
    // Usuarios a crear/actualizar
    $usuarios = [
        // Super Administrador (ya existe, solo actualizar contraseña)
        [
            'cedula' => '9999999999',
            'email' => 'superadmin@itsi.edu.ec',
            'password' => 'superadmin123',
            'id_rol' => 1,
            'nombres' => 'Super',
            'apellidos' => 'Administrador'
        ],
        // Administrador Talento Humano (ya existe, solo actualizar contraseña)
        [
            'cedula' => '8888888888',
            'email' => 'admin.th@itsi.edu.ec',
            'password' => 'adminth123',
            'id_rol' => 2,
            'nombres' => 'Ana',
            'apellidos' => 'García'
        ],
        // Docente (ya existe, solo actualizar contraseña)
        [
            'cedula' => '7777777777',
            'email' => 'docente@itsi.edu.ec',
            'password' => 'docente123',
            'id_rol' => 3,
            'nombres' => 'Carlos',
            'apellidos' => 'Pérez'
        ],
        // Administrativo (crear nuevo)
        [
            'cedula' => '6666666666',
            'email' => 'administrativo@itsi.edu.ec',
            'password' => 'admin123',
            'id_rol' => 6,
            'nombres' => 'María',
            'apellidos' => 'López'
        ],
        // Directivo (crear nuevo)
        [
            'cedula' => '5555555555',
            'email' => 'directivo@itsi.edu.ec',
            'password' => 'directivo123',
            'id_rol' => 7,
            'nombres' => 'Roberto',
            'apellidos' => 'Martínez'
        ],
        // Auxiliar (crear nuevo)
        [
            'cedula' => '4444444444',
            'email' => 'auxiliar@itsi.edu.ec',
            'password' => 'auxiliar123',
            'id_rol' => 8,
            'nombres' => 'Patricia',
            'apellidos' => 'Rodríguez'
        ]
    ];
    
    foreach ($usuarios as $usuario) {
        // Verificar si el usuario ya existe
        $stmt = $pdo->prepare("SELECT id_usuario FROM usuarios WHERE cedula = ?");
        $stmt->execute([$usuario['cedula']]);
        
        if ($stmt->rowCount() == 0) {
            // Crear nuevo usuario
            $passwordHash = password_hash($usuario['password'], PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO usuarios (cedula, email, password_hash, id_rol, activo, created_at, updated_at) VALUES (?, ?, ?, ?, 1, NOW(), NOW())");
            $stmt->execute([$usuario['cedula'], $usuario['email'], $passwordHash, $usuario['id_rol']]);
            
            $idUsuario = $pdo->lastInsertId();
            
            // Crear empleado correspondiente
            $tipoEmpleado = '';
            $departamento = '';
            $tipoDocente = null;
            
            switch ($usuario['id_rol']) {
                case 1: // SuperAdministrador
                    $tipoEmpleado = 'ADMINISTRATIVO';
                    $departamento = 'Departamento ITSI';
                    break;
                case 2: // AdministradorTalentoHumano
                    $tipoEmpleado = 'ADMINISTRATIVO';
                    $departamento = 'Recursos Humanos';
                    break;
                case 3: // Docente
                    $tipoEmpleado = 'DOCENTE';
                    $departamento = 'Departamento General';
                    $tipoDocente = 'Tiempo completo';
                    break;
                case 6: // ADMINISTRATIVO
                    $tipoEmpleado = 'ADMINISTRATIVO';
                    $departamento = 'Administrativo';
                    break;
                case 7: // DIRECTIVO
                    $tipoEmpleado = 'DIRECTIVO';
                    $departamento = 'Departamento ITSI';
                    break;
                case 8: // AUXILIAR
                    $tipoEmpleado = 'AUXILIAR';
                    $departamento = 'Departamento ITSI';
                    break;
            }
            
            $stmt = $pdo->prepare("INSERT INTO empleados (id_usuario, tipo_empleado, nombres, apellidos, fecha_nacimiento, genero, estado_civil, direccion, telefono, fecha_ingreso, activo, estado, periodo_academico_id, created_at, updated_at, tipo_docente, departamento) VALUES (?, ?, ?, ?, '1980-01-01', 'Masculino', 'Soltero', 'Dirección de prueba', '0987654321', '2020-01-01', 1, 'Activo', 1, NOW(), NOW(), ?, ?)");
            $stmt->execute([$idUsuario, $tipoEmpleado, $usuario['nombres'], $usuario['apellidos'], $tipoDocente, $departamento]);
            
            echo "✅ Usuario creado: {$usuario['nombres']} {$usuario['apellidos']} ({$usuario['email']})\n";
        } else {
            // Actualizar contraseña del usuario existente
            $passwordHash = password_hash($usuario['password'], PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("UPDATE usuarios SET password_hash = ?, updated_at = NOW() WHERE cedula = ?");
            $stmt->execute([$passwordHash, $usuario['cedula']]);
            
            echo "🔄 Contraseña actualizada: {$usuario['nombres']} {$usuario['apellidos']} ({$usuario['email']})\n";
        }
    }
    
    echo "\n✅ Usuarios procesados exitosamente\n\n";
    
    // Mostrar credenciales finales
    echo "🎯 CREDENCIALES DE ACCESO AL SISTEMA\n";
    echo "=====================================\n\n";
    
    foreach ($usuarios as $usuario) {
        echo "👤 {$usuario['nombres']} {$usuario['apellidos']}\n";
        echo "   📧 Email: {$usuario['email']}\n";
        echo "   🔑 Contraseña: {$usuario['password']}\n";
        echo "   🎭 Rol: ";
        
        switch ($usuario['id_rol']) {
            case 1: echo "Super Administrador"; break;
            case 2: echo "Administrador Talento Humano"; break;
            case 3: echo "Docente"; break;
            case 6: echo "Administrativo"; break;
            case 7: echo "Directivo"; break;
            case 8: echo "Auxiliar"; break;
        }
        
        echo "\n   🔗 URL: " . ($usuario['id_rol'] == 1 ? '/super-admin/dashboard' : ($usuario['id_rol'] == 2 ? '/admin-th/dashboard' : '/empleado/dashboard'));
        echo "\n\n";
    }
    
    echo "✅ IMPLEMENTACIÓN COMPLETADA EXITOSAMENTE\n";
    echo "========================================\n";
    echo "🎉 El sistema está listo para ser probado con todas las funcionalidades\n";
    echo "🚀 Puedes acceder con cualquiera de las credenciales mostradas arriba\n";
    
} catch (PDOException $e) {
    echo "❌ Error en la base de datos: " . $e->getMessage() . "\n";
} catch (Exception $e) {
    echo "❌ Error general: " . $e->getMessage() . "\n";
}
?>
