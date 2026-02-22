<?php
/**
 * Verifica que el dashboard no tenga errores de sintaxis ni queries rotos
 */
$pdo = new PDO('mysql:host=localhost;dbname=talent_human_db;charset=utf8mb4', 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

echo "=== VERIFICACIÓN DEL DASHBOARD ===\n\n";

$pass = 0; $fail = 0;
function ok($msg) { global $pass; $pass++; echo "✓ $msg\n"; }
function no($msg) { global $fail; $fail++; echo "✗ $msg\n"; }

// 1. Sintaxis PHP del controlador
exec('C:\xampp\php\php.exe -l "' . __DIR__ . '\app\Controllers\AdminTH\AdminTHController.php" 2>&1', $out, $code);
$code === 0 ? ok("AdminTHController.php - sin errores de sintaxis") : no("AdminTHController.php - ERROR: " . implode('', $out));

// 2. Sintaxis PHP de la vista
exec('C:\xampp\php\php.exe -l "' . __DIR__ . '\app\Views\Roles\AdminTH\dashboard.php" 2>&1', $out2, $code2);
$code2 === 0 ? ok("dashboard.php - sin errores de sintaxis") : no("dashboard.php - ERROR: " . implode('', $out2));

// 3. Query: Total empleados
try {
    $r = $pdo->query("SELECT COUNT(*) FROM empleados")->fetchColumn();
    ok("Total empleados: $r");
} catch(Exception $e) { no("Query empleados: " . $e->getMessage()); }

// 4. Query: Empleados activos
try {
    $r = $pdo->query("SELECT COUNT(*) FROM empleados WHERE estado = 'Activo'")->fetchColumn();
    ok("Empleados activos: $r");
} catch(Exception $e) { no("Query activos: " . $e->getMessage()); }

// 5. Query: Inasistencias pendientes
try {
    $r = $pdo->query("SELECT COUNT(*) FROM inasistencias WHERE justificada = 0")->fetchColumn();
    ok("Inasistencias pendientes: $r");
} catch(Exception $e) { no("Query inasistencias: " . $e->getMessage()); }

// 6. Query: Capacitaciones activas
try {
    $r = $pdo->query("SELECT COUNT(*) FROM capacitaciones WHERE estado = 'En curso'")->fetchColumn();
    ok("Capacitaciones activas: $r");
} catch(Exception $e) { no("Query capacitaciones: " . $e->getMessage()); }

// 7. Query: Últimas inasistencias con JOIN
try {
    $r = $pdo->query("
        SELECT e.nombres, e.apellidos, i.fecha_inasistencia, i.tipo_inasistencia
        FROM inasistencias i
        JOIN empleados e ON e.id_empleado = i.empleado_id
        ORDER BY i.fecha_inasistencia DESC LIMIT 5
    ")->fetchAll();
    ok("Query inasistencias con JOIN: " . count($r) . " resultados");
} catch(Exception $e) { no("Query inasistencias JOIN: " . $e->getMessage()); }

// 8. Query: capacitaciones_empleados existe?
try {
    $cols = $pdo->query("SHOW COLUMNS FROM capacitaciones_empleados")->fetchAll(PDO::FETCH_COLUMN);
    ok("Tabla capacitaciones_empleados existe. Columnas: " . implode(', ', $cols));
} catch(Exception $e) { no("capacitaciones_empleados: " . $e->getMessage()); }

// 9. Query: Empleados por departamento
try {
    $r = $pdo->query("
        SELECT departamento, COUNT(*) as total 
        FROM empleados WHERE estado = 'Activo' 
        GROUP BY departamento
    ")->fetchAll(PDO::FETCH_ASSOC);
    ok("Empleados por departamento: " . count($r) . " grupos");
    foreach($r as $row) echo "  - {$row['departamento']}: {$row['total']}\n";
} catch(Exception $e) { no("Query departamentos: " . $e->getMessage()); }

// 10. Query: Inasistencias por mes
try {
    $r = $pdo->query("
        SELECT MONTH(fecha_inasistencia) as mes, COUNT(*) as total
        FROM inasistencias WHERE YEAR(fecha_inasistencia) = " . date('Y') . "
        GROUP BY MONTH(fecha_inasistencia)
    ")->fetchAll(PDO::FETCH_ASSOC);
    ok("Inasistencias por mes: " . count($r) . " meses con datos");
} catch(Exception $e) { no("Query por mes: " . $e->getMessage()); }

// 11. Vista no tiene datos hardcodeados
$view = file_get_contents(__DIR__ . '/app/Views/Roles/AdminTH/dashboard.php');
if (strpos($view, "Juan Pérez") === false && strpos($view, "Ana Martínez") === false && strpos($view, "'45'") === false) {
    ok("Vista sin datos mock/hardcodeados");
} else {
    no("Vista todavía contiene datos hardcodeados");
}

// 12. Vista usa variables PHP del controlador
if (strpos($view, '$totalEmpleados') !== false && strpos($view, '$ultimasInasistencias') !== false) {
    ok("Vista usa variables dinámicas del controlador");
} else {
    no("Vista no usa variables dinámicas");
}

// 13. Vista tiene mensajes vacíos
if (strpos($view, 'No hay inasistencias registradas') !== false && strpos($view, 'No hay solicitudes de capacitación') !== false) {
    ok("Vista muestra mensajes cuando no hay datos");
} else {
    no("Vista no muestra mensajes de datos vacíos");
}

echo "\n=== RESULTADO: $pass pasaron, $fail fallaron ===\n";
if ($fail === 0) echo "¡TODO CORRECTO!\n";
