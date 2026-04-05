<?php
$lines = file('c:/xampp/htdocs/TalentoHumano/app/Controllers/AdminTH/AdminTHController.php');
foreach ($lines as $i => $l) {
    if (stripos($l, 'reporteEmpleado') !== false && stripos($l, 'function') !== false) {
        echo 'Line ' . ($i+1) . ': ' . trim($l) . "\n";
    }
}
