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
        if (\$this->request->getGet('tipo')) {
            \$inasistencias->where('tipo_inasistencia', \$this->request->getGet('tipo'));
        }
        \$inasistencias->orderBy('fecha_inasistencia', 'DESC');";

$content = str_replace($search, $replace, $content);
file_put_contents('c:/xampp/htdocs/TalentoHumano/app/Controllers/AdminTH/AdminTHController.php', $content);
echo "Filters added to reporteEmpleado";
