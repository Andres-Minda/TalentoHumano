<?php
$lines = file('c:/xampp/htdocs/TalentoHumano/app/Controllers/AdminTH/AdminTHController.php');
foreach ($lines as $i => $l) {
    if (strpos($l, 'public function') !== false && stripos($l, 'estadisticas') !== false) echo ($i+1) . " - " . trim($l) . "\n";
}
