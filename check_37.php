<?php
$content = file_get_contents('c:/xampp/htdocs/TalentoHumano/app/Controllers/AdminTH/AdminTHController.php');

$search = "->where('empleado_id', \$empleado_id)\n            ->orderBy('fecha_inasistencia', 'DESC');";
$replace = "->where('empleado_id', \$empleado_id);
            
        // Filtros (fechas, tipo) opcionales (Req. reporte)
        if (\$this->request->getGet('fecha_inicio')) {
            \$inasistencias->where('fecha_inasistencia >=', \$this->request->getGet('fecha_inicio'));
        }
        if (\$this->request->getGet('fecha_fin')) {
            \$inasistencias->where('fecha_inasistencia <=', \$this->request->getGet('fecha_fin'));
        }
        if (\$this->request->getGet('tipo_inasistencia')) {
            \$inasistencias->where('tipo_inasistencia', \$this->request->getGet('tipo_inasistencia'));
        }
        \$inasistencias->orderBy('fecha_inasistencia', 'DESC');";

$pos = strpos($content, $search);
if ($pos !== false) {
    $content = substr_replace($content, $replace, $pos, strlen($search));
    file_put_contents('c:/xampp/htdocs/TalentoHumano/app/Controllers/AdminTH/AdminTHController.php', $content);
    echo "reporteEmpleado filters added.";
} else {
    echo "Could not find reporteEmpleado search string!";
}
