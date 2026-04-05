<?php
$methods = <<<PHP

    /**
     * ==========================================
     * GESTIÓN DE INASISTENCIAS (MÓDULO ADMINTH)
     * ==========================================
     */

    public function inasistencias()
    {
        \$db = \Config\Database::connect();

        \$total = \$db->table('inasistencias')->countAllResults();
        \$pendientes = \$db->table('inasistencias')->where('justificada', 0)->countAllResults();
        \$sin_justificar = \$db->table('inasistencias')->where('tipo_inasistencia', 'Injustificada')->countAllResults();
        \$tasa_justificacion = \$total > 0 ? round(((\$total - \$pendientes) / \$total) * 100) : 0;

        \$builderRecientes = \$db->table('inasistencias i')->select('i.*, e.nombres, e.apellidos, e.tipo_empleado, e.departamento')->join('empleados e', 'e.id_empleado = i.empleado_id', 'left')->orderBy('i.fecha_inasistencia', 'DESC')->limit(5);
        \$inasistencias_recientes = [];
        foreach (\$builderRecientes->get()->getResultArray() as \$row) {
            \$inasistencias_recientes[] = [
                'id' => \$row['id'],
                'empleado_nombre' => \$row['nombres'] . ' ' . \$row['apellidos'],
                'empleado_tipo' => \$row['tipo_empleado'],
                'fecha' => \$row['fecha_inasistencia'],
                'tipo_nombre' => \$row['tipo_inasistencia'],
                'estado' => (\$row['justificada'] == 1) ? 'JUSTIFICADA' : 'PENDIENTE',
                'departamento' => \$row['departamento']
            ];
        }

        \$builderCriticos = \$db->table('inasistencias i')
            ->select('e.id_empleado, e.nombres, e.apellidos, e.tipo_empleado, e.departamento, COUNT(i.id) as total_inasistencias, SUM(CASE WHEN i.justificada = 1 THEN 1 ELSE 0 END) as justificadas, SUM(CASE WHEN i.justificada = 0 THEN 1 ELSE 0 END) as sin_justificar')
            ->join('empleados e', 'e.id_empleado = i.empleado_id', 'left')
            ->groupBy('e.id_empleado')
            ->orderBy('total_inasistencias', 'DESC')
            ->limit(5);
        \$empleados_criticos = [];
        foreach (\$builderCriticos->get()->getResultArray() as \$row) {
             \$empleados_criticos[] = [
                 'empleado_id' => \$row['id_empleado'],
                 'nombre' => \$row['nombres'] . ' ' . \$row['apellidos'],
                 'tipo' => \$row['tipo_empleado'],
                 'departamento' => \$row['departamento'],
                 'total_inasistencias' => \$row['total_inasistencias'],
                 'justificadas' => \$row['justificadas'],
                 'sin_justificar' => \$row['sin_justificar']
             ];
        }

        // Estadísticas para gráficos
        \$deptos = \$db->table('inasistencias i')->select('e.departamento, COUNT(i.id) as cantidad')->join('empleados e', 'e.id_empleado = i.empleado_id', 'left')->groupBy('e.departamento')->get()->getResultArray();
        \$labelsDeptos = []; \$valoresDeptos = [];
        foreach (\$deptos as \$d) {
            \$labelsDeptos[] = \$d['departamento'] ?? 'N/A';
            \$valoresDeptos[] = (int)\$d['cantidad'];
        }

        \$tendencia = \$db->table('inasistencias')->select('fecha_inasistencia, COUNT(id) as cantidad')->groupBy('fecha_inasistencia')->orderBy('fecha_inasistencia', 'ASC')->limit(7)->get()->getResultArray();
        \$labelsTendencia = []; \$valoresTendencia = [];
        foreach (\$tendencia as \$t) {
            \$labelsTendencia[] = date('d/m', strtotime(\$t['fecha_inasistencia']));
            \$valoresTendencia[] = (int)\$t['cantidad'];
        }

        \$data = [
            'titulo' => 'Dashboard Inasistencias',
            'estadisticas' => [
                'total' => \$total,
                'tendencia_total' => 0,
                'pendientes' => \$pendientes,
                'sin_justificar' => \$sin_justificar,
                'tasa_justificacion' => \$tasa_justificacion
            ],
            'alertas' => [],
            'inasistencias_recientes' => \$inasistencias_recientes,
            'empleados_criticos' => \$empleados_criticos,
            'graficos' => [
                'departamentos' => ['labels' => \$labelsDeptos, 'valores' => \$valoresDeptos],
                'tendencia' => ['labels' => \$labelsTendencia, 'valores' => \$valoresTendencia]
            ]
        ];

        return view('Roles/AdminTH/inasistencias/dashboard', \$data);
    }

    public function getEstadisticasGlobalesInasistencias()
    {
        // En dashboard.php los datos ya se generan en PHP en el index y se pasan al array \$graficos.
        // Pero si quieren usar fetch() dinámicamente, aquí está el endpoint.
        try {
            \$db = \Config\Database::connect();
            
            \$deptos = \$db->table('inasistencias i')->select('e.departamento, COUNT(i.id) as cantidad')->join('empleados e', 'e.id_empleado = i.empleado_id', 'left')->groupBy('e.departamento')->get()->getResultArray();
            \$labelsDeptos = []; \$valoresDeptos = [];
            foreach (\$deptos as \$d) {
                \$labelsDeptos[] = \$d['departamento'] ?? 'N/A';
                \$valoresDeptos[] = (int)\$d['cantidad'];
            }

            \$tendencia = \$db->table('inasistencias')->select('fecha_inasistencia, COUNT(id) as cantidad')->groupBy('fecha_inasistencia')->orderBy('fecha_inasistencia', 'ASC')->limit(7)->get()->getResultArray();
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

    public function detalles(\$id)
    {
        try {
            \$db = \Config\Database::connect();
            \$inasistencia = \$db->table('inasistencias i')
                ->select('i.*, e.nombres, e.apellidos')
                ->join('empleados e', 'e.id_empleado = i.empleado_id', 'left')
                ->where('i.id', \$id)
                ->get()->getRowArray();

            if (!\$inasistencia) {
                return \$this->response->setJSON(['success' => false, 'message' => 'Inasistencia no encontrada.']);
            }
            return \$this->response->setJSON(['success' => true, 'inasistencia' => \$inasistencia]);
        } catch (\Exception \$e) {
            return \$this->response->setJSON(['success' => false, 'message' => \$e->getMessage()]);
        }
    }

    public function eliminar(\$id)
    {
        try {
            \$this->inasistenciaModel->delete(\$id);
            return \$this->response->setJSON(['success' => true, 'message' => 'Eliminado exitosamente']);
        } catch (\Exception \$e) {
            return \$this->response->setJSON(['success' => false, 'message' => \$e->getMessage()]);
        }
    }

    public function listarInasistencias()
    {
        \$db = \Config\Database::connect();
        \$inasistencias = \$db->table('inasistencias i')
            ->select('i.*, e.nombres, e.apellidos, e.tipo_empleado, e.departamento')
            ->join('empleados e', 'e.id_empleado = i.empleado_id', 'left')
            ->orderBy('i.fecha_inasistencia', 'DESC')
            ->get()->getResultArray();
        return view('Roles/AdminTH/inasistencias/index', ['inasistencias' => \$inasistencias]);
    }

    public function editarInasistencia(\$id)
    {
        \$db = \Config\Database::connect();
        \$inasistencia = \$db->table('inasistencias i')
            ->select('i.*, e.nombres, e.apellidos')
            ->join('empleados e', 'e.id_empleado = i.empleado_id', 'left')
            ->where('i.id', \$id)->get()->getRowArray();
        return view('Roles/AdminTH/inasistencias/editar', ['inasistencia' => \$inasistencia]);
    }

    public function actualizarInasistencia(\$id = null)
    {
        if (!\$id) {
            \$id = \$this->request->getPost('id');
        }
        \$data = [
            'justificada' => \$this->request->getPost('justificada') ? 1 : 0,
            'tipo_inasistencia' => \$this->request->getPost('tipo_inasistencia'),
            'motivo' => \$this->request->getPost('motivo')
        ];
        if (\$this->inasistenciaModel->update(\$id, \$data)) {
            return redirect()->to('/admin-th/inasistencias/listar')->with('success', 'Inasistencia actualizada.');
        }
        return redirect()->back()->with('error', 'Error al actualizar.');
    }

    public function obtenerPerfilEmpleado(\$id)
    {
        try {
            \$db = \Config\Database::connect();
            \$empleado = \$db->table('empleados e')
                ->select('e.*, u.email as correo, u.cedula')
                ->join('usuarios u', 'u.id_usuario = e.id_usuario', 'left')
                ->where('e.id_empleado', \$id)
                ->get()->getRowArray();
            if (\$empleado) {
                return \$this->response->setJSON([
                    'success' => true,
                    'nombre_completo' => \$empleado['nombres'] . ' ' . \$empleado['apellidos'],
                    'tipo_empleado' => \$empleado['tipo_empleado'],
                    'cedula' => \$empleado['cedula'],
                    'departamento' => \$empleado['departamento'],
                    'correo' => \$empleado['correo'],
                    'telefono' => \$empleado['telefono'],
                    'fecha_contratacion' => \$empleado['fecha_ingreso']
                ]);
            }
            return \$this->response->setJSON(['success' => false, 'message' => 'Empleado no encontrado']);
        } catch (\Exception \$e) {
            return \$this->response->setJSON(['success' => false, 'message' => \$e->getMessage()]);
        }
    }

    public function reporteEmpleado(\$id)
    {
        \$db = \Config\Database::connect();
        \$empleado = \$db->table('empleados e')->where('id_empleado', \$id)->get()->getRowArray();
        
        // Obtener historial de inasistencias de este empleado
        \$historial = \$db->table('inasistencias')
            ->where('empleado_id', \$id)
            ->orderBy('fecha_inasistencia', 'DESC')
            ->get()->getResultArray();
        
        \$data = [
            'titulo' => 'Reporte de Empleado',
            'empleado' => \$empleado,
            'historial' => \$historial
        ];
        return view('Roles/AdminTH/inasistencias/reporte_empleado', \$data);
    }
PHP;

$content = file_get_contents('c:/xampp/htdocs/TalentoHumano/app/Controllers/AdminTH/AdminTHController.php');
$content = rtrim($content); // Remove whitespace/newlines from end
$content = rtrim($content, '}'); // Remove last curly brace
$content .= "\n" . $methods . "\n}\n";
file_put_contents('c:/xampp/htdocs/TalentoHumano/app/Controllers/AdminTH/AdminTHController.php', $content);
echo "Appended";
