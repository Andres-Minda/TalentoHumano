<?php
$mysqli = new mysqli("localhost", "root", "", "talent_human_db");
if ($mysqli->connect_errno) {
    die("Fallo al conectar a MySQL: " . $mysqli->connect_error);
}

$tables_res = $mysqli->query("SHOW TABLES");
$output = "MODELO DE BASE DE DATOS: talent_human_db\n";
$output .= "========================================\n\n";

while ($table_row = $tables_res->fetch_array()) {
    $table = $table_row[0];
    $output .= "TABLA: $table\n";
    $output .= str_repeat("-", strlen("TABLA: $table")) . "\n";
    
    // Get columns
    $cols_res = $mysqli->query("SHOW COLUMNS FROM `$table`");
    $output .= "Columnas:\n";
    while ($col = $cols_res->fetch_assoc()) {
        $output .= "  - " . $col['Field'] . " (" . $col['Type'] . ")";
        if ($col['Null'] === 'NO') $output .= " NOT NULL";
        if ($col['Key'] === 'PRI') $output .= " [PK]";
        if ($col['Key'] === 'UNI') $output .= " [UNIQUE]";
        if ($col['Extra']) $output .= " " . $col['Extra'];
        $output .= "\n";
    }
    
    // Get foreign keys
    $fk_query = "SELECT COLUMN_NAME, REFERENCED_TABLE_NAME, REFERENCED_COLUMN_NAME 
                 FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE 
                 WHERE TABLE_SCHEMA = 'talent_human_db' AND TABLE_NAME = '$table' AND REFERENCED_TABLE_NAME IS NOT NULL";
    $fk_res = $mysqli->query($fk_query);
    if ($fk_res && $fk_res->num_rows > 0) {
        $output .= "\nRelaciones (Claves Foráneas):\n";
        while ($fk = $fk_res->fetch_assoc()) {
            $output .= "  -> " . $fk['COLUMN_NAME'] . " referencia a " . $fk['REFERENCED_TABLE_NAME'] . " (" . $fk['REFERENCED_COLUMN_NAME'] . ")\n";
        }
    }
    
    $output .= "\n";
}

file_put_contents(__DIR__ . "/modelo_base_datos.txt", $output);
echo "Success\n";
