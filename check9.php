<?php
$lines = file('c:/xampp/htdocs/TalentoHumano/app/Controllers/AdminTH/AdminTHController.php');
for ($i = 1590; $i < 1650; $i++) {
    echo ($i+1) . ': ' . trim($lines[$i]) . "\n";
}
