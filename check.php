<?php
$lines = file('c:/xampp/htdocs/TalentoHumano/app/Controllers/AdminTH/AdminTHController.php');
for ($i = 4505; $i < count($lines); $i++) {
    echo ($i+1) . ': ' . trim($lines[$i]) . "\n";
}
