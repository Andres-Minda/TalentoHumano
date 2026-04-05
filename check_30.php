<?php
$lines = file('c:/xampp/htdocs/TalentoHumano/app/Controllers/AdminTH/AdminTHController.php');
for ($i = 1605; $i < 1630; $i++) {
    echo ($i+1) . ': ' . trim($lines[$i]) . "\n";
}
