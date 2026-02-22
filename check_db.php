<?php
$pdo = new PDO('mysql:host=localhost;dbname=talent_human_db;charset=utf8mb4', 'root', '');
$tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
echo "=== TABLES ===\n";
foreach ($tables as $t) echo "- $t\n";

$check = ['inasistencias','capacitaciones','solicitudes_capacitacion','departamentos','empleados'];
foreach ($check as $tbl) {
    if (in_array($tbl, $tables)) {
        echo "\n=== $tbl ===\n";
        $cols = $pdo->query("SHOW COLUMNS FROM $tbl")->fetchAll(PDO::FETCH_ASSOC);
        foreach ($cols as $c) echo "  {$c['Field']} ({$c['Type']}) {$c['Null']} {$c['Key']}\n";
        $count = $pdo->query("SELECT COUNT(*) FROM $tbl")->fetchColumn();
        echo "  RECORDS: $count\n";
    } else {
        echo "\n=== $tbl === NOT EXISTS\n";
    }
}
