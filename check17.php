<?php
$content = file_get_contents('c:/xampp/htdocs/TalentoHumano/app/Controllers/AdminTH/AdminTHController.php');
$content = str_replace(
    "->select('i.*, e.nombres, e.apellidos, e.departamento, e.tipo_empleado')\n            ->join('empleados e', 'e.id_empleado = i.empleado_id', 'left')",
    "->select('i.*, e.nombres, e.apellidos, e.departamento, e.tipo_empleado, u.email as correo')\n            ->join('empleados e', 'e.id_empleado = i.empleado_id', 'left')\n            ->join('usuarios u', 'u.id_usuario = e.id_usuario', 'left')",
    $content
);
file_put_contents('c:/xampp/htdocs/TalentoHumano/app/Controllers/AdminTH/AdminTHController.php', $content);
echo "Replaced listarInasistencias select";
