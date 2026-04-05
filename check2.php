<?php
$lines = file('c:/xampp/htdocs/TalentoHumano/app/Controllers/AdminTH/AdminTHController.php');
foreach ($lines as $i => $l) {
    if (strpos($l, 'public function inasistencias(') !== false) echo ($i+1) . "\n";
}
