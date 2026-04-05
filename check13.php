<?php
$method = <<<PHP

    /**
     * Reporte de inasistencias de un empleado específico
     */
    public function reporteEmpleado(\$id)
    {
        \$db = \Config\Database::connect();
        
        \$empleado = \$db->table('empleados e')
            ->select('e.*, u.cedula')
            ->join('usuarios u', 'u.id_usuario = e.id_usuario', 'left')
            ->where('e.id_empleado', \$id)
            ->get()->getRowArray();
            
        // Obtener historial de inasistencias de este empleado
        \$builder = \$db->table('inasistencias')
            ->where('empleado_id', \$id);
            
        // Aplicar filtros GET
        \$fecha_inicio = \$this->request->getGet('fecha_inicio');
        \$fecha_fin = \$this->request->getGet('fecha_fin');
        \$tipo = \$this->request->getGet('tipo');
        
        if (\$fecha_inicio) \$builder->where('fecha_inasistencia >=', \$fecha_inicio);
        if (\$fecha_fin) \$builder->where('fecha_inasistencia <=', \$fecha_fin);
        if (\$tipo) \$builder->where('tipo_inasistencia', \$tipo);
        
        \$historial = \$builder->orderBy('fecha_inasistencia', 'DESC')->get()->getResultArray();
        
        \$data = [
            'empleado' => \$empleado,
            'inasistencias' => \$historial
        ];
        return view('Roles/AdminTH/inasistencias/reporte_empleado', \$data);
    }
PHP;

$content = file_get_contents('c:/xampp/htdocs/TalentoHumano/app/Controllers/AdminTH/AdminTHController.php');
$content = rtrim($content);
$content = rtrim($content, '}');
$content .= "\n" . $method . "\n}\n";
file_put_contents('c:/xampp/htdocs/TalentoHumano/app/Controllers/AdminTH/AdminTHController.php', $content);
echo "Added reporteEmpleado";
