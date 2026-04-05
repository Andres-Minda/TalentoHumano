<?php
$lines = file('c:/xampp/htdocs/TalentoHumano/app/Controllers/AdminTH/AdminTHController.php');
foreach ($lines as $i => $l) {
    if (stripos($l, 'obtenerPerfilEmpleado') !== false) {
        echo 'Line ' . ($i+1) . ': ' . trim($l) . "\n";
    }
}
