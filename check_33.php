<?php
$content = file_get_contents('c:/xampp/htdocs/TalentoHumano/app/Controllers/AdminTH/AdminTHController.php');

// 1. inasistencias() fix
$content = str_replace(
    "->select('i.empleado_id, e.nombres, e.apellidos, e.tipo_empleado as tipo, e.departamento, COUNT(i.id) as total_inasistencias, SUM(i.justificada) as justificadas')\n            ->join('empleados e', 'e.id_empleado = i.empleado_id', 'left')\n            ->like('i.fecha_inasistencia', \$mesActual, 'after')",
    "->select('i.empleado_id, e.nombres, e.apellidos, e.tipo_empleado as tipo, e.departamento, u.email as correo, COUNT(i.id) as total_inasistencias, SUM(i.justificada) as justificadas')\n            ->join('empleados e', 'e.id_empleado = i.empleado_id', 'left')\n            ->join('usuarios u', 'u.id_usuario = e.id_usuario', 'left')\n            ->like('i.fecha_inasistencia', \$mesActual, 'after')",
    $content
);

// 2. listarInasistencias() fix
$content = str_replace(
    "->select('i.*, e.nombres, e.apellidos, e.departamento, e.tipo_empleado')\n            ->join('empleados e', 'e.id_empleado = i.empleado_id', 'left')",
    "->select('i.*, e.nombres, e.apellidos, e.departamento, e.tipo_empleado, u.email as correo')\n            ->join('empleados e', 'e.id_empleado = i.empleado_id', 'left')\n            ->join('usuarios u', 'u.id_usuario = e.id_usuario', 'left')",
    $content
);

// 3. reporteEmpleado() fix
$content = str_replace(
    "->where('empleado_id', \$empleado_id)\n            ->orderBy('fecha_inasistencia', 'DESC');",
    "->where('empleado_id', \$empleado_id);
            
        // Filtros opcionales (GET)
        if (\$this->request->getGet('fecha_inicio')) {
            \$inasistencias->where('fecha_inasistencia >=', \$this->request->getGet('fecha_inicio'));
        }
        if (\$this->request->getGet('fecha_fin')) {
            \$inasistencias->where('fecha_inasistencia <=', \$this->request->getGet('fecha_fin'));
        }
        if (\$this->request->getGet('tipo')) {
            \$inasistencias->where('tipo_inasistencia', \$this->request->getGet('tipo'));
        }
        \$inasistencias->orderBy('fecha_inasistencia', 'DESC');",
    $content
);

// 4. getEstadisticasGlobalesInasistencias() append
$method = <<<PHP

    /**
     * Endpoint API para gráficos estadísticos
     */
    public function getEstadisticasGlobalesInasistencias()
    {
        try {
            \$db = \Config\Database::connect();
            
            \$deptos = \$db->table('inasistencias i')
                ->select('e.departamento, COUNT(i.id) as cantidad')
                ->join('empleados e', 'e.id_empleado = i.empleado_id', 'left')
                ->groupBy('e.departamento')
                ->orderBy('cantidad', 'DESC')
                ->get()->getResultArray();
                
            \$labelsDeptos = []; \$valoresDeptos = [];
            foreach (\$deptos as \$d) {
                \$labelsDeptos[] = \$d['departamento'] ?? 'N/A';
                \$valoresDeptos[] = (int)\$d['cantidad'];
            }

            \$tendencia = \$db->table('inasistencias')
                ->select('fecha_inasistencia, COUNT(id) as cantidad')
                ->groupBy('fecha_inasistencia')
                ->orderBy('fecha_inasistencia', 'ASC')
                ->limit(10)
                ->get()->getResultArray();
                
            \$labelsTendencia = []; \$valoresTendencia = [];
            foreach (\$tendencia as \$t) {
                \$labelsTendencia[] = date('d/m', strtotime(\$t['fecha_inasistencia']));
                \$valoresTendencia[] = (int)\$t['cantidad'];
            }

            return \$this->response->setJSON([
                'success' => true,
                'departamentos' => ['labels' => \$labelsDeptos, 'valores' => \$valoresDeptos],
                'tendencia' => ['labels' => \$labelsTendencia, 'valores' => \$valoresTendencia]
            ]);
        } catch (\Exception \$e) {
            return \$this->response->setJSON(['success' => false, 'message' => \$e->getMessage()]);
        }
    }
PHP;

$content = preg_replace('/}\s*$/', "\n$method\n}", $content);

file_put_contents('c:/xampp/htdocs/TalentoHumano/app/Controllers/AdminTH/AdminTHController.php', $content);
echo "All applied successfully";
