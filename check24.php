<?php
$lines = file('c:/xampp/htdocs/TalentoHumano/app/Controllers/AdminTH/AdminTHController.php');
for ($i = 2040; $i < 2070; $i++) {
    echo ($i+1) . ': ' . trim($lines[$i]) . "\n";
}
