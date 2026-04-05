<?php
$content = file('c:/xampp/htdocs/TalentoHumano/app/Controllers/AdminTH/AdminTHController.php');
$idx = 0;
foreach ($content as $i => $line) {
    if (strpos($line, 'GESTIÓN DE INASISTENCIAS (MÓDULO ADMINTH)') !== false) {
        $idx = $i;
        break;
    }
}
if ($idx > 0) {
    $content = array_slice($content, 0, $idx - 2);
    file_put_contents('c:/xampp/htdocs/TalentoHumano/app/Controllers/AdminTH/AdminTHController.php', implode('', $content) . "\n}\n");
    echo "Fixed";
}
