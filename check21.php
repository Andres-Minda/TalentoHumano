<?php
$lines = file('c:/xampp/htdocs/TalentoHumano/app/Views/Roles/AdminTH/inasistencias/dashboard.php');
for ($i = 480; $i < 515; $i++) {
    echo ($i+1) . ': ' . trim($lines[$i]) . "\n";
}
