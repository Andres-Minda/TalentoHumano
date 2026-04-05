<?php
$content = file_get_contents('c:/xampp/htdocs/TalentoHumano/app/Controllers/AdminTH/AdminTHController.php');

$search = "->join('empleados e', 'e.id_empleado = i.empleado_id', 'left')";
$replace = "->join('empleados e', 'e.id_empleado = i.empleado_id', 'left')\n            ->join('usuarios u', 'u.id_usuario = e.id_usuario', 'left')";

$content = str_replace(
    "->select('i.empleado_id, e.nombres, e.apellidos, e.tipo_empleado as tipo, e.departamento, COUNT(i.id) as total_inasistencias, SUM(i.justificada) as justificadas')",
    "->select('i.empleado_id, e.nombres, e.apellidos, e.tipo_empleado as tipo, e.departamento, u.email as correo, COUNT(i.id) as total_inasistencias, SUM(i.justificada) as justificadas')",
    $content
);

$content = str_replace(
    "->like('i.fecha_inasistencia', \$mesActual, 'after')",
    $replace . "\n            ->like('i.fecha_inasistencia', \$mesActual, 'after')",
    $content
);

file_put_contents('c:/xampp/htdocs/TalentoHumano/app/Controllers/AdminTH/AdminTHController.php', $content);
echo "Replaced";
