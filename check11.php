<?php
$method = <<<PHP

    /**
     * Endpoint API para grÃ¡ficos de inasistencias en el dashboard AdminTH
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

$content = file_get_contents('c:/xampp/htdocs/TalentoHumano/app/Controllers/AdminTH/AdminTHController.php');
$content = preg_replace('/}\s*$/', "\n$method\n}", $content);
file_put_contents('c:/xampp/htdocs/TalentoHumano/app/Controllers/AdminTH/AdminTHController.php', $content);
echo "Appended getEstadisticasGlobales";
