<?php
/**
 * Script para implementar registros completos en la base de datos
 * Sistema de Talento Humano - ITSI
 */

// Configuración de la base de datos
$host = 'localhost';
$dbname = 'talent_human_db';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "✅ Conexión a la base de datos establecida\n\n";
    
    // 1. ACTUALIZAR ROLES EXISTENTES
    echo "🔄 Actualizando roles existentes...\n";
    
    $roles = [
        ['id' => 1, 'nombre' => 'SuperAdministrador', 'descripcion' => 'Acceso total al sistema'],
        ['id' => 2, 'nombre' => 'AdministradorTalentoHumano', 'descripcion' => 'Gestión de talento humano'],
        ['id' => 3, 'nombre' => 'Docente', 'descripcion' => 'Personal docente'],
        ['id' => 6, 'nombre' => 'ADMINISTRATIVO', 'descripcion' => 'Personal administrativo'],
        ['id' => 7, 'nombre' => 'DIRECTIVO', 'descripcion' => 'Directivos de la institución'],
        ['id' => 8, 'nombre' => 'AUXILIAR', 'descripcion' => 'Personal auxiliar']
    ];
    
    foreach ($roles as $rol) {
        $stmt = $pdo->prepare("UPDATE roles SET nombre_rol = ?, descripcion = ? WHERE id_rol = ?");
        $stmt->execute([$rol['nombre'], $rol['descripcion'], $rol['id']]);
    }
    echo "✅ Roles actualizados\n\n";
    
    // 2. CREAR NUEVOS USUARIOS PARA TODOS LOS ROLES
    echo "🔄 Creando usuarios para todos los roles...\n";
    
    $usuarios = [
        // Super Administrador
        [
            'cedula' => '9999999999',
            'email' => 'superadmin@itsi.edu.ec',
            'password' => 'superadmin123',
            'id_rol' => 1,
            'nombres' => 'Super',
            'apellidos' => 'Administrador'
        ],
        // Administrador Talento Humano
        [
            'cedula' => '8888888888',
            'email' => 'admin.th@itsi.edu.ec',
            'password' => 'adminth123',
            'id_rol' => 2,
            'nombres' => 'Ana',
            'apellidos' => 'García'
        ],
        // Docente
        [
            'cedula' => '7777777777',
            'email' => 'docente@itsi.edu.ec',
            'password' => 'docente123',
            'id_rol' => 3,
            'nombres' => 'Carlos',
            'apellidos' => 'Pérez'
        ],
        // Administrativo
        [
            'cedula' => '6666666666',
            'email' => 'administrativo@itsi.edu.ec',
            'password' => 'admin123',
            'id_rol' => 6,
            'nombres' => 'María',
            'apellidos' => 'López'
        ],
        // Directivo
        [
            'cedula' => '5555555555',
            'email' => 'directivo@itsi.edu.ec',
            'password' => 'directivo123',
            'id_rol' => 7,
            'nombres' => 'Roberto',
            'apellidos' => 'Martínez'
        ],
        // Auxiliar
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
            // Actualizar usuario existente
            $passwordHash = password_hash($usuario['password'], PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("UPDATE usuarios SET email = ?, password_hash = ?, id_rol = ?, updated_at = NOW() WHERE cedula = ?");
            $stmt->execute([$usuario['email'], $passwordHash, $usuario['id_rol'], $usuario['cedula']]);
            
            echo "🔄 Usuario actualizado: {$usuario['nombres']} {$usuario['apellidos']} ({$usuario['email']})\n";
        }
    }
    echo "✅ Usuarios procesados\n\n";
    
    // 3. CREAR DEPARTAMENTOS
    echo "🔄 Creando departamentos...\n";
    
    $departamentos = [
        'Departamento ITSI',
        'Recursos Humanos',
        'Contabilidad',
        'Tecnología',
        'Académico',
        'Administrativo',
        'Vinculación',
        'Departamento General'
    ];
    
    foreach ($departamentos as $departamento) {
        $stmt = $pdo->prepare("INSERT IGNORE INTO departamentos (nombre, descripcion, estado, created_at, updated_at) VALUES (?, ?, 'Activo', NOW(), NOW())");
        $stmt->execute([$departamento, "Descripción del departamento {$departamento}"]);
    }
    echo "✅ Departamentos creados\n\n";
    
    // 4. CREAR PUESTOS DE TRABAJO
    echo "🔄 Creando puestos de trabajo...\n";
    
    $puestos = [
        ['nombre' => 'Docente Tiempo Completo', 'descripcion' => 'Docente con dedicación completa'],
        ['nombre' => 'Docente Medio Tiempo', 'descripcion' => 'Docente con dedicación parcial'],
        ['nombre' => 'Docente Tiempo Parcial', 'descripcion' => 'Docente con dedicación mínima'],
        ['nombre' => 'Administrador', 'descripcion' => 'Personal administrativo'],
        ['nombre' => 'Directivo', 'descripcion' => 'Personal directivo'],
        ['nombre' => 'Auxiliar', 'descripcion' => 'Personal auxiliar'],
        ['nombre' => 'Analista', 'descripcion' => 'Analista de sistemas'],
        ['nombre' => 'Contador', 'descripcion' => 'Contador público']
    ];
    
    foreach ($puestos as $puesto) {
        $stmt = $pdo->prepare("INSERT IGNORE INTO puestos (nombre, descripcion, estado, created_at, updated_at) VALUES (?, ?, 'Activo', NOW(), NOW())");
        $stmt->execute([$puesto['nombre'], $puesto['descripcion']]);
    }
    echo "✅ Puestos de trabajo creados\n\n";
    
    // 5. CREAR PERIODO ACADÉMICO
    echo "🔄 Creando período académico...\n";
    
    $stmt = $pdo->prepare("INSERT IGNORE INTO periodos_academicos (nombre, fecha_inicio, fecha_fin, estado, created_at, updated_at) VALUES (?, ?, ?, 'Activo', NOW(), NOW())");
    $stmt->execute(['Periodo 2025-2026', '2025-09-01', '2026-07-31']);
    echo "✅ Período académico creado\n\n";
    
    // 6. CREAR TABLA DE TÍTULOS ACADÉMICOS
    echo "🔄 Creando tabla de títulos académicos...\n";
    
    $sql = "CREATE TABLE IF NOT EXISTS titulos_academicos (
        id_titulo INT AUTO_INCREMENT PRIMARY KEY,
        id_empleado INT NOT NULL,
        universidad VARCHAR(200) NOT NULL,
        tipo_titulo ENUM('Tercer nivel', 'Cuarto nivel', 'Doctorado/PhD') NOT NULL,
        nombre_titulo VARCHAR(300) NOT NULL,
        fecha_obtencion DATE NOT NULL,
        pais VARCHAR(100),
        archivo_certificado VARCHAR(500),
        estado ENUM('Activo', 'Inactivo', 'Pendiente') DEFAULT 'Activo',
        observaciones TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (id_empleado) REFERENCES empleados(id_empleado) ON DELETE CASCADE
    )";
    
    $pdo->exec($sql);
    echo "✅ Tabla de títulos académicos creada\n\n";
    
    // 7. CREAR TABLA DE CAPACITACIONES EMPLEADOS
    echo "🔄 Creando tabla de capacitaciones empleados...\n";
    
    $sql = "CREATE TABLE IF NOT EXISTS capacitaciones_empleados (
        id_capacitacion_empleado INT AUTO_INCREMENT PRIMARY KEY,
        id_empleado INT NOT NULL,
        nombre_capacitacion VARCHAR(300) NOT NULL,
        institucion VARCHAR(200) NOT NULL,
        fecha_inicio DATE NOT NULL,
        fecha_fin DATE NOT NULL,
        numero_horas INT NOT NULL,
        tipo_capacitacion ENUM('Obligatoria', 'Voluntaria', 'Especialización', 'Actualización') NOT NULL,
        archivo_certificado VARCHAR(500),
        estado ENUM('En curso', 'Completada', 'Pendiente', 'Cancelada') DEFAULT 'Pendiente',
        observaciones TEXT,
        fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (id_empleado) REFERENCES empleados(id_empleado) ON DELETE CASCADE
    )";
    
    $pdo->exec($sql);
    echo "✅ Tabla de capacitaciones empleados creada\n\n";
    
    // 8. CREAR TABLA DE POSTULANTES
    echo "🔄 Creando tabla de postulantes...\n";
    
    $sql = "CREATE TABLE IF NOT EXISTS postulantes (
        id_postulante INT AUTO_INCREMENT PRIMARY KEY,
        cedula VARCHAR(10) UNIQUE NOT NULL,
        nombres VARCHAR(100) NOT NULL,
        apellidos VARCHAR(100) NOT NULL,
        email VARCHAR(255) UNIQUE NOT NULL,
        telefono VARCHAR(15) NOT NULL,
        direccion VARCHAR(300) NOT NULL,
        fecha_nacimiento DATE NOT NULL,
        estado_civil ENUM('Soltero', 'Casado', 'Divorciado', 'Viudo', 'Unión Libre') NOT NULL,
        genero ENUM('Masculino', 'Femenino', 'Otro') NOT NULL,
        nivel_educativo ENUM('Primaria', 'Secundaria', 'Técnico', 'Universitario', 'Postgrado') NOT NULL,
        titulo_academico VARCHAR(200),
        universidad VARCHAR(200),
        experiencia_laboral TEXT NOT NULL,
        disponibilidad ENUM('Inmediata', '15 días', '30 días', '60 días', '90 días') NOT NULL,
        salario_esperado DECIMAL(10,2) NOT NULL,
        archivo_cv VARCHAR(500),
        archivo_cedula VARCHAR(500),
        archivo_titulo VARCHAR(500),
        archivo_referencias VARCHAR(500),
        estado ENUM('Activo', 'Inactivo', 'Seleccionado', 'Rechazado') DEFAULT 'Activo',
        fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        observaciones TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )";
    
    $pdo->exec($sql);
    echo "✅ Tabla de postulantes creada\n\n";
    
    // 9. CREAR TABLA DE POSTULACIONES
    echo "🔄 Creando tabla de postulaciones...\n";
    
    $sql = "CREATE TABLE IF NOT EXISTS postulaciones (
        id_postulacion INT AUTO_INCREMENT PRIMARY KEY,
        id_postulante INT NOT NULL,
        id_vacante INT NOT NULL,
        fecha_aplicacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        estado ENUM('Pendiente', 'Revisada', 'Aprobada', 'Rechazada') DEFAULT 'Pendiente',
        observaciones TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (id_postulante) REFERENCES postulantes(id_postulante) ON DELETE CASCADE,
        FOREIGN KEY (id_vacante) REFERENCES vacantes(id_vacante) ON DELETE CASCADE
    )";
    
    $pdo->exec($sql);
    echo "✅ Tabla de postulaciones creada\n\n";
    
    // 10. INSERTAR DATOS DE PRUEBA
    echo "🔄 Insertando datos de prueba...\n";
    
    // Títulos académicos de prueba
    $titulos = [
        ['id_empleado' => 3, 'universidad' => 'Universidad de Guayaquil', 'tipo_titulo' => 'Tercer nivel', 'nombre_titulo' => 'Ingeniero en Sistemas', 'fecha_obtencion' => '2015-07-15'],
        ['id_empleado' => 3, 'universidad' => 'Universidad Católica', 'tipo_titulo' => 'Cuarto nivel', 'nombre_titulo' => 'Maestría en Tecnologías de la Información', 'fecha_obtencion' => '2018-12-10']
    ];
    
    foreach ($titulos as $titulo) {
        $stmt = $pdo->prepare("INSERT IGNORE INTO titulos_academicos (id_empleado, universidad, tipo_titulo, nombre_titulo, fecha_obtencion, estado, created_at, updated_at) VALUES (?, ?, ?, ?, ?, 'Activo', NOW(), NOW())");
        $stmt->execute([$titulo['id_empleado'], $titulo['universidad'], $titulo['tipo_titulo'], $titulo['nombre_titulo'], $titulo['fecha_obtencion']]);
    }
    
    // Capacitaciones de prueba
    $capacitaciones = [
        ['id_empleado' => 3, 'nombre_capacitacion' => 'Metodologías Ágiles', 'institucion' => 'Coursera', 'fecha_inicio' => '2024-01-15', 'fecha_fin' => '2024-03-15', 'numero_horas' => 40, 'tipo_capacitacion' => 'Voluntaria'],
        ['id_empleado' => 3, 'nombre_capacitacion' => 'Diseño de Software', 'institucion' => 'edX', 'fecha_inicio' => '2024-06-01', 'fecha_fin' => '2024-08-01', 'numero_horas' => 60, 'tipo_capacitacion' => 'Especialización']
    ];
    
    foreach ($capacitaciones as $capacitacion) {
        $stmt = $pdo->prepare("INSERT IGNORE INTO capacitaciones_empleados (id_empleado, nombre_capacitacion, institucion, fecha_inicio, fecha_fin, numero_horas, tipo_capacitacion, estado, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, 'Completada', NOW(), NOW())");
        $stmt->execute([$capacitacion['id_empleado'], $capacitacion['nombre_capacitacion'], $capacitacion['institucion'], $capacitacion['fecha_inicio'], $capacitacion['fecha_fin'], $capacitacion['numero_horas'], $capacitacion['tipo_capacitacion']]);
    }
    
    echo "✅ Datos de prueba insertados\n\n";
    
    // 11. MOSTRAR CREDENCIALES FINALES
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
