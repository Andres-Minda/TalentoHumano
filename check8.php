<?php
$lines = file('c:/xampp/htdocs/TalentoHumano/app/Controllers/AdminTH/AdminTHController.php');
for ($i = 1965; $i < 1980; $i++) {
    echo ($i+1) . ': ' . trim($lines[$i]) . "\n";
}
