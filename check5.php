<?php
$lines = file('c:/xampp/htdocs/TalentoHumano/app/Controllers/AdminTH/AdminTHController.php');
for ($i = 1730; $i < 1790; $i++) {
    echo ($i+1) . ': ' . trim($lines[$i]) . "\n";
}
