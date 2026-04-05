<?php
$dashboard = file_get_contents('c:/xampp/htdocs/TalentoHumano/app/Views/Roles/AdminTH/inasistencias/dashboard.php');
$startIdx = strpos($dashboard, '<div class="modal fade" id="modalVerPerfil"');
if ($startIdx !== false) {
    echo substr($dashboard, $startIdx, 1500); // output the first 1500 chars to inspect
}
