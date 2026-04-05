<?php
$content = file_get_contents('c:/xampp/htdocs/TalentoHumano/app/Controllers/AdminTH/AdminTHController.php');

// 1. In listarInasistencias(), add u.email as correo
$search = "->select('i.*, e.nombres, e.apellidos, e.departamento, e.tipo_empleado')\n            ->join('empleados e', 'e.id_empleado = i.empleado_id', 'left')";
$replace = "->select('i.*, e.nombres, e.apellidos, e.departamento, e.tipo_empleado, u.email as correo')\n            ->join('empleados e', 'e.id_empleado = i.empleado_id', 'left')\n            ->join('usuarios u', 'u.id_usuario = e.id_usuario', 'left')";
$content = str_replace($search, $replace, $content);

// 2. In reporteEmpleado(), add filters
$search_re = "->where('empleado_id', \$empleado_id)\n            ->orderBy('fecha_inasistencia', 'DESC');";
$replace_re = "->where('empleado_id', \$empleado_id);
            
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
$content = str_replace($search_re, $replace_re, $content);

// 3. Re-append getEstadisticasGlobalesInasistencias
$method = <<<PHP

    /**
     * Endpoint API para gráficos de inasistencias en el dashboard AdminTH
     */
    public function getEstadisticasGlobalesInasistencias()
    {
        try {
            \$db = \Config\Database::connect();
            
            // Inasistencias por Departamento
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

            // Tendencia diaria
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
echo "Restored all backend changes";
