<?php
$lines = file('c:/xampp/htdocs/TalentoHumano/app/Controllers/AdminTH/AdminTHController.php');
for ($i = 1710; $i < 1735; $i++) {
    echo ($i+1) . ': ' . trim($lines[$i]) . "\n";
}
