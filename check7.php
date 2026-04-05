<?php
$lines = file('c:/xampp/htdocs/TalentoHumano/app/Controllers/AdminTH/AdminTHController.php');
$clean = array_slice($lines, 0, 4506); // Keep everything before the duplicate block! Actually line 4507 was the brace }
file_put_contents('c:/xampp/htdocs/TalentoHumano/app/Controllers/AdminTH/AdminTHController.php', implode('', $clean) . "}\n");
echo "Reverted";
