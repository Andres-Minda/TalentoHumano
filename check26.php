<?php
$lines = file('c:/xampp/htdocs/TalentoHumano/app/Controllers/AdminTH/AdminTHController.php');
for ($i = count($lines)-20; $i < count($lines); $i++) {
    echo ($i+1) . ': ' . trim($lines[$i]) . "\n";
}
