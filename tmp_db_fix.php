<?php
$mysqli = new mysqli("localhost", "root", "", "talent_human_db");
if ($mysqli->connect_errno) {
    echo "Fallo al conectar a MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
    exit;
}
$mysqli->query("ALTER TABLE periodos_academicos ADD COLUMN updated_at DATETIME NULL;");

$res = $mysqli->query("DESCRIBE periodos_academicos");
while ($row = $res->fetch_assoc()) {
    echo $row['Field'] . "\n";
}
