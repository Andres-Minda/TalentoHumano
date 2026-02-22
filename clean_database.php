<?php
/**
 * Limpieza de datos basura en la base de datos
 * NO toca la tabla empleados ni usuarios (ya están limpias con solo 2 registros)
 */
$host = 'localhost';
$dbname = 'talent_human_db';
$dbuser = 'root';
$dbpass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $dbuser, $dbpass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "=== LIMPIEZA DE DATOS BASURA ===\n\n";

    $pdo->exec("SET FOREIGN_KEY_CHECKS = 0");

    // Tablas a vaciar completamente
    $truncate = [
        'inasistencias',
        'capacitaciones',
        'capacitaciones_empleados',
        'empleados_capacitaciones',
        'solicitudes',
        'solicitudes_generales',
        'evaluaciones',
        'evaluaciones_empleados',
        'detalles_evaluacion',
        'preguntas_evaluacion',
        'asistencias',
        'certificados',
        'contratos',
        'documentos',
        'historial_laboral',
        'detalles_nomina',
        'nominas',
        'postulaciones',
        'postulantes',
        'titulos_academicos',
        'empleados_competencias',
        'competencias',
        'categorias_evaluacion',
        'permisos',
        'vacantes',
        'candidatos',
    ];

    foreach ($truncate as $table) {
        $tables = $pdo->query("SHOW TABLES LIKE '$table'")->fetchAll();
        if (!empty($tables)) {
            $count = $pdo->query("SELECT COUNT(*) FROM $table")->fetchColumn();
            $pdo->exec("TRUNCATE TABLE $table");
            echo "✓ $table vaciada ($count registros eliminados)\n";
        }
    }

    // Departamentos: vaciar pero conservar estructura
    $countD = $pdo->query("SELECT COUNT(*) FROM departamentos")->fetchColumn();
    $pdo->exec("TRUNCATE TABLE departamentos");
    echo "✓ departamentos vaciada ($countD registros eliminados)\n";

    // Puestos: vaciar
    $tables = $pdo->query("SHOW TABLES LIKE 'puestos'")->fetchAll();
    if (!empty($tables)) {
        $countP = $pdo->query("SELECT COUNT(*) FROM puestos")->fetchColumn();
        $pdo->exec("TRUNCATE TABLE puestos");
        echo "✓ puestos vaciada ($countP registros eliminados)\n";
    }

    // Tipos de inasistencia: vaciar
    $tables = $pdo->query("SHOW TABLES LIKE 'tipos_inasistencia'")->fetchAll();
    if (!empty($tables)) {
        $countT = $pdo->query("SELECT COUNT(*) FROM tipos_inasistencia")->fetchColumn();
        $pdo->exec("TRUNCATE TABLE tipos_inasistencia");
        echo "✓ tipos_inasistencia vaciada ($countT registros eliminados)\n";
    }

    // Políticas de inasistencia: vaciar
    $tables = $pdo->query("SHOW TABLES LIKE 'politicas_inasistencia'")->fetchAll();
    if (!empty($tables)) {
        $countPI = $pdo->query("SELECT COUNT(*) FROM politicas_inasistencia")->fetchColumn();
        $pdo->exec("TRUNCATE TABLE politicas_inasistencia");
        echo "✓ politicas_inasistencia vaciada ($countPI registros eliminados)\n";
    }

    // Categorías: vaciar
    $tables = $pdo->query("SHOW TABLES LIKE 'categorias'")->fetchAll();
    if (!empty($tables)) {
        $countC = $pdo->query("SELECT COUNT(*) FROM categorias")->fetchColumn();
        $pdo->exec("TRUNCATE TABLE categorias");
        echo "✓ categorias vaciada ($countC registros eliminados)\n";
    }

    // Periodos académicos: vaciar
    $tables = $pdo->query("SHOW TABLES LIKE 'periodos_academicos'")->fetchAll();
    if (!empty($tables)) {
        $countPA = $pdo->query("SELECT COUNT(*) FROM periodos_academicos")->fetchColumn();
        $pdo->exec("TRUNCATE TABLE periodos_academicos");
        echo "✓ periodos_academicos vaciada ($countPA registros eliminados)\n";
    }

    // Logs y sesiones: vaciar
    $pdo->exec("TRUNCATE TABLE logs_sistema");
    echo "✓ logs_sistema vaciada\n";
    $pdo->exec("TRUNCATE TABLE sesiones_activas");
    echo "✓ sesiones_activas vaciada\n";

    $pdo->exec("SET FOREIGN_KEY_CHECKS = 1");

    // Verificar que los empleados NO fueron tocados
    echo "\n=== VERIFICACIÓN: EMPLEADOS INTACTOS ===\n";
    $stmt = $pdo->query("
        SELECT u.id_usuario, u.email, r.nombre_rol, e.nombres, e.apellidos
        FROM usuarios u
        JOIN roles r ON r.id_rol = u.id_rol
        JOIN empleados e ON e.id_usuario = u.id_usuario
    ");
    foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
        echo "  ✓ [{$row['nombre_rol']}] {$row['nombres']} {$row['apellidos']} ({$row['email']})\n";
    }

    echo "\n=== LIMPIEZA COMPLETADA ===\n";

} catch (PDOException $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
?>
