<?php
$content = file_get_contents('c:/xampp/htdocs/TalentoHumano/app/Controllers/AdminTH/AdminTHController.php');

// Fix duplicate join
$search = "->join('empleados e', 'e.id_empleado = i.empleado_id', 'left')\n            ->join('empleados e', 'e.id_empleado = i.empleado_id', 'left')";
$replace = "->join('empleados e', 'e.id_empleado = i.empleado_id', 'left')\n            ->join('usuarios u', 'u.id_usuario = e.id_usuario', 'left')";
$content = str_replace($search, $replace, $content);

// Ensure the select for topEmpleados includes u.email as correo
$search_select = "->select('i.empleado_id, e.nombres, e.apellidos, e.tipo_empleado as tipo, e.departamento, COUNT(i.id) as total_inasistencias, SUM(i.justificada) as justificadas')";
$replace_select = "->select('i.empleado_id, e.nombres, e.apellidos, e.tipo_empleado as tipo, e.departamento, u.email as correo, COUNT(i.id) as total_inasistencias, SUM(i.justificada) as justificadas')";
$content = str_replace($search_select, $replace_select, $content);

file_put_contents('c:/xampp/htdocs/TalentoHumano/app/Controllers/AdminTH/AdminTHController.php', $content);
echo "Fixed duplicate join\n";
