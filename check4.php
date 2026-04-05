<?php
$lines = file('c:/xampp/htdocs/TalentoHumano/app/Controllers/AdminTH/AdminTHController.php');
foreach ($lines as $i => $l) {
    if (strpos($l, 'public function inasistencias') !== false) echo ($i+1) . " - " . trim($l) . "\n";
    if (strpos($l, 'public function listarInasistencias') !== false) echo ($i+1) . " - " . trim($l) . "\n";
    if (strpos($l, 'public function registrarInasistencia') !== false) echo ($i+1) . " - " . trim($l) . "\n";
    if (strpos($l, 'public function detalles(') !== false) echo ($i+1) . " - " . trim($l) . "\n";
    if (strpos($l, 'public function eliminarInasistencia') !== false) echo ($i+1) . " - " . trim($l) . "\n";
    if (strpos($l, 'public function editarInasistencia(') !== false) echo ($i+1) . " - " . trim($l) . "\n";
    if (strpos($l, 'public function actualizarInasistencia(') !== false) echo ($i+1) . " - " . trim($l) . "\n";
    if (strpos($l, 'public function eliminar(') !== false) echo ($i+1) . " - " . trim($l) . "\n";
}
