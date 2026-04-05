<?php
$lines = file('c:/xampp/htdocs/TalentoHumano/app/Views/Roles/AdminTH/inasistencias/index.php');
for ($i = 100; $i < 150; $i++) {
    echo ($i+1) . ': ' . trim($lines[$i]) . "\n";
}
