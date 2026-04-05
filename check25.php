<?php
$lines = file('c:/xampp/htdocs/TalentoHumano/app/Controllers/AdminTH/AdminTHController.php');
$clean = array_slice($lines, 0, 4554); // Cut off everything after exactly line 4554 (which should be just before my appended reporteEmpleado)
file_put_contents('c:/xampp/htdocs/TalentoHumano/app/Controllers/AdminTH/AdminTHController.php', implode('', $clean) . "}\n");
echo "Removed appended reporteEmpleado";
