<?php
$dashboard = file_get_contents('c:/xampp/htdocs/TalentoHumano/app/Views/Roles/AdminTH/inasistencias/dashboard.php');
if (preg_match('/<div class="modal fade" id="modalVerPerfil".*?<\/div>.*?<\/div>.*?<\/div>/is', $dashboard, $matches)) {
    $modalHTML = $matches[0];
    $index = file_get_contents('c:/xampp/htdocs/TalentoHumano/app/Views/Roles/AdminTH/inasistencias/index.php');
    if (strpos($index, 'id="modalVerPerfil"') === false) {
        $index = str_replace('<!-- Modal Ver Detalles -->', $modalHTML . "\n<!-- Modal Ver Detalles -->", $index);
        file_put_contents('c:/xampp/htdocs/TalentoHumano/app/Views/Roles/AdminTH/inasistencias/index.php', $index);
        echo "Modal added to index.php. ";
    }
}

if (preg_match('/const botonesVerPerfil = document\.querySelectorAll\(.*?} \)\);/is', $dashboard, $scriptMatches)) {
    // We already copied this JS logic logic? Oh wait, in dashboard it ended with `});` not `} ));`
}
