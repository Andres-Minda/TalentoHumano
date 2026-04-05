<?php
$content = file_get_contents('c:/xampp/htdocs/TalentoHumano/app/Config/Routes.php');
$content = str_replace(
    "\$routes->get('inasistencias/listar-json', 'AdminTH\AdminTHController::listarInasistenciasJSON');",
    "\$routes->get('inasistencias/listar-json', 'AdminTH\AdminTHController::listarInasistenciasJSON');\n    \$routes->get('inasistencias/estadisticas-globales', 'AdminTH\AdminTHController::getEstadisticasGlobalesInasistencias');",
    $content
);
file_put_contents('c:/xampp/htdocs/TalentoHumano/app/Config/Routes.php', $content);
echo "Routes updated";
