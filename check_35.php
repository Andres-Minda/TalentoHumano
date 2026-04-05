<?php
$content = file_get_contents('c:/xampp/htdocs/TalentoHumano/app/Controllers/AdminTH/AdminTHController.php');

// 1. inasistencias() fix
$search1 = "->select('i.empleado_id, e.nombres, e.apellidos, e.tipo_empleado as tipo, e.departamento, COUNT(i.id) as total_inasistencias, SUM(i.justificada) as justificadas')";
$replace1 = "->select('i.empleado_id, e.nombres, e.apellidos, e.tipo_empleado as tipo, e.departamento, u.email as correo, COUNT(i.id) as total_inasistencias, SUM(i.justificada) as justificadas')";

$search2 = "->join('empleados e', 'e.id_empleado = i.empleado_id', 'left')";
$pos = strpos($content, $search1);
if ($pos !== false) {
    $content = substr_replace($content, $replace1, $pos, strlen($search1));
    $posJoin = strpos($content, $search2, $pos);
    if ($posJoin !== false) {
        $content = substr_replace($content, "->join('empleados e', 'e.id_empleado = i.empleado_id', 'left')\n            ->join('usuarios u', 'u.id_usuario = e.id_usuario', 'left')", $posJoin, strlen($search2));
    }
}

// 2. listarInasistencias() fix
$search3 = "->select('i.*, e.nombres, e.apellidos, e.departamento, e.tipo_empleado')";
$replace3 = "->select('i.*, e.nombres, e.apellidos, e.departamento, e.tipo_empleado, u.email as correo')";
$pos3 = strpos($content, $search3);
if ($pos3 !== false) {
    $content = substr_replace($content, $replace3, $pos3, strlen($search3));
    $posJoin3 = strpos($content, $search2, $pos3);
    if ($posJoin3 !== false) {
        $content = substr_replace($content, "->join('empleados e', 'e.id_empleado = i.empleado_id', 'left')\n            ->join('usuarios u', 'u.id_usuario = e.id_usuario', 'left')", $posJoin3, strlen($search2));
    }
}

file_put_contents('c:/xampp/htdocs/TalentoHumano/app/Controllers/AdminTH/AdminTHController.php', $content);
echo "Robust replacements applied";
