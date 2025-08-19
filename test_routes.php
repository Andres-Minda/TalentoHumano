<?php
// Script de prueba para verificar rutas
echo "=== PRUEBA DE RUTAS ===\n";

// Verificar si las clases existen
if (class_exists('App\Controllers\SuperAdminController')) {
    echo "✅ SuperAdminController existe\n";
} else {
    echo "❌ SuperAdminController NO existe\n";
}

if (class_exists('App\Controllers\DocenteController')) {
    echo "✅ DocenteController existe\n";
} else {
    echo "❌ DocenteController NO existe\n";
}

// Verificar si los métodos existen
$superAdmin = new \App\Controllers\SuperAdminController();
$docente = new \App\Controllers\DocenteController();

if (method_exists($superAdmin, 'respaldo')) {
    echo "✅ Método respaldo() existe\n";
} else {
    echo "❌ Método respaldo() NO existe\n";
}

if (method_exists($superAdmin, 'reportesSistema')) {
    echo "✅ Método reportesSistema() existe\n";
} else {
    echo "❌ Método reportesSistema() NO existe\n";
}

if (method_exists($docente, 'capacitaciones')) {
    echo "✅ Método capacitaciones() existe\n";
} else {
    echo "❌ Método capacitaciones() NO existe\n";
}

// Verificar si las vistas existen
$respaldoView = APPPATH . 'Views/Roles/SuperAdministrador/respaldo.php';
$reportesView = APPPATH . 'Views/Roles/SuperAdministrador/reportes_sistema.php';
$capacitacionesView = APPPATH . 'Views/Roles/Docente/capacitaciones.php';

if (file_exists($respaldoView)) {
    echo "✅ Vista respaldo.php existe\n";
} else {
    echo "❌ Vista respaldo.php NO existe\n";
}

if (file_exists($reportesView)) {
    echo "✅ Vista reportes_sistema.php existe\n";
} else {
    echo "❌ Vista reportes_sistema.php NO existe\n";
}

if (file_exists($capacitacionesView)) {
    echo "✅ Vista capacitaciones.php existe\n";
} else {
    echo "❌ Vista capacitaciones.php NO existe\n";
}

echo "\n=== FIN DE PRUEBA ===\n";
?> 