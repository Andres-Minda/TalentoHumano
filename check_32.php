<?php
$lines = file('c:/xampp/htdocs/TalentoHumano/app/Controllers/AdminTH/AdminTHController.php');
for ($i = 1610; $i < 1625; $i++) {
    echo ($i+1) . ': ' . trim($lines[$i]) . "\n";
}
