<?php
$lines = file('c:/xampp/htdocs/TalentoHumano/app/Views/Roles/AdminTH/inasistencias/index.php');
for ($i = 210; $i < 240; $i++) {
    echo ($i+1) . ': ' . trim($lines[$i]) . "\n";
}
