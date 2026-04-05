<?php
$content = file_get_contents('c:/xampp/htdocs/TalentoHumano/app/Controllers/AdminTH/AdminTHController.php');
$content .= "}\n";
file_put_contents('c:/xampp/htdocs/TalentoHumano/app/Controllers/AdminTH/AdminTHController.php', $content);
echo "Added brace";
