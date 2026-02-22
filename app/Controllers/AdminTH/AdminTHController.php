<?php

namespace App\Controllers\AdminTH;

use App\Models\EmpleadoModel;
use App\Models\DepartamentoModel;
use App\Models\PuestoModel;
use App\Models\CapacitacionModel;
use App\Models\TituloAcademicoModel;
use App\Models\EvaluacionModel;
use App\Models\InasistenciaModel;
use App\Models\PoliticaInasistenciaModel;
use App\Models\SolicitudCapacitacionModel;
use App\Models\PostulanteModel;
use App\Models\UsuarioModel;
use App\Models\LogSistemaModel;
use App\Models\NotificacionModel;
use CodeIgniter\Controller;

class AdminTHController extends Controller
{
    protected $empleadoModel;
    protected $departamentoModel;
    protected $puestoModel;
    protected $capacitacionModel;
    protected $tituloAcademicoModel;
    protected $evaluacionModel;
    protected $inasistenciaModel;
    protected $politicaInasistenciaModel;
    protected $solicitudCapacitacionModel;
    protected $postulanteModel;
    protected $notificacionModel;

    public function __construct()
    {
        $this->empleadoModel = new EmpleadoModel();
        $this->departamentoModel = new DepartamentoModel();
        $this->puestoModel = new PuestoModel();
        $this->capacitacionModel = new CapacitacionModel();
        $this->tituloAcademicoModel = new TituloAcademicoModel();
        $this->evaluacionModel = new EvaluacionModel();
        $this->inasistenciaModel = new InasistenciaModel();
        $this->politicaInasistenciaModel = new PoliticaInasistenciaModel();
        $this->solicitudCapacitacionModel = new SolicitudCapacitacionModel();
        $this->postulanteModel = new PostulanteModel();
        $this->notificacionModel = new NotificacionModel();
    }

    /**
     * Dashboard del Admin Talento Humano
     */
    public function dashboard()
    {
        $db = \Config\Database::connect();

        // --- Tarjetas de resumen ---
        $totalEmpleados = $db->table('empleados')->countAllResults();
        $empleadosActivos = $db->table('empleados')->where('estado', 'Activo')->countAllResults();
        $inasistenciasPendientes = $db->table('inasistencias')->where('justificada', 0)->countAllResults();
        $capacitacionesActivas = $db->table('capacitaciones')->where('estado', 'En curso')->countAllResults();

        // --- Últimas 5 Inasistencias ---
        $ultimasInasistencias = $db->table('inasistencias i')
            ->select('e.nombres, e.apellidos, i.fecha_inasistencia, i.tipo_inasistencia, i.justificada')
            ->join('empleados e', 'e.id_empleado = i.empleado_id')
            ->orderBy('i.fecha_inasistencia', 'DESC')
            ->limit(5)
            ->get()->getResultArray();

        // --- Últimas 5 inscripciones a capacitaciones ---
        $solicitudesCapacitacion = $db->table('capacitaciones_empleados ce')
            ->select('e.nombres, e.apellidos, ce.nombre_capacitacion as capacitacion, ce.estado')
            ->join('empleados e', 'e.id_empleado = ce.empleado_id')
            ->orderBy('ce.created_at', 'DESC')
            ->limit(5)
            ->get()->getResultArray();

        // --- Empleados por departamento (gráfico donut) ---
        $empPorDepto = $db->table('empleados')
            ->select('departamento, COUNT(*) as total')
            ->where('estado', 'Activo')
            ->groupBy('departamento')
            ->get()->getResultArray();

        $chartDeptLabels = array_column($empPorDepto, 'departamento');
        $chartDeptData   = array_map('intval', array_column($empPorDepto, 'total'));

        // --- Inasistencias por mes del año actual (gráfico barras) ---
        $anioActual = date('Y');
        $inaPorMes = $db->query("
            SELECT MONTH(fecha_inasistencia) as mes, COUNT(*) as total
            FROM inasistencias
            WHERE YEAR(fecha_inasistencia) = ?
            GROUP BY MONTH(fecha_inasistencia)
            ORDER BY mes
        ", [$anioActual])->getResultArray();

        $chartMeses = array_fill(0, 12, 0);
        foreach ($inaPorMes as $row) {
            $chartMeses[(int)$row['mes'] - 1] = (int)$row['total'];
        }

        $data = [
            'titulo' => 'Dashboard Admin Talento Humano',
            'usuario' => [
                'nombres' => session()->get('nombres'),
                'apellidos' => session()->get('apellidos'),
                'rol' => session()->get('nombre_rol')
            ],
            'totalEmpleados'          => $totalEmpleados,
            'empleadosActivos'        => $empleadosActivos,
            'inasistenciasPendientes' => $inasistenciasPendientes,
            'capacitacionesActivas'   => $capacitacionesActivas,
            'ultimasInasistencias'    => $ultimasInasistencias,
            'solicitudesCapacitacion' => $solicitudesCapacitacion,
            'chartDeptLabels'         => json_encode($chartDeptLabels),
            'chartDeptData'           => json_encode($chartDeptData),
            'chartInasistencias'      => json_encode($chartMeses),
        ];

        return view('Roles/AdminTH/dashboard', $data);
    }

    /**
     * Gestión de empleados
     */
    public function empleados()
    {
        $data = [
            'titulo' => 'Gestión de Empleados',
            'usuario' => [
                'nombres' => session()->get('nombres'),
                'apellidos' => session()->get('apellidos'),
                'rol' => session()->get('nombre_rol')
            ],
            'empleados' => $this->empleadoModel->getEmpleadosConUsuario()
        ];

        return view('Roles/AdminTH/empleados', $data);
    }

    /**
     * Obtener empleados en formato JSON para AJAX
     */
    public function obtenerEmpleados()
    {
        try {
            $empleados = $this->empleadoModel->getEmpleadosConUsuario(['mostrar_todos' => true]);
            
            return $this->response->setJSON([
                'success' => true,
                'empleados' => $empleados
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error al obtener empleados: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al obtener empleados: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Obtener estadísticas para gráficos de empleados
     */
    public function obtenerEstadisticasEmpleados()
    {
        try {
            // Estadísticas por departamento
            $empleadosPorDepartamento = $this->empleadoModel->getEmpleadosPorDepartamento();
            
            // Estadísticas por tipo de empleado
            $empleadosPorTipo = $this->empleadoModel->getEmpleadosPorTipo();
            
            return $this->response->setJSON([
                'success' => true,
                'estadisticas' => [
                    'porDepartamento' => $empleadosPorDepartamento,
                    'porTipo' => $empleadosPorTipo
                ]
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error al obtener estadísticas de empleados: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al obtener estadísticas: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Obtener estadísticas para gráficos de departamentos
     */
    public function obtenerEstadisticasDepartamentos()
    {
        try {
            // Estadísticas de departamentos por estado
            $departamentosPorEstado = $this->departamentoModel->getDepartamentosPorEstado();
            
            // Estadísticas de empleados por departamento
            $empleadosPorDepartamento = $this->empleadoModel->getEmpleadosPorDepartamento();
            
            return $this->response->setJSON([
                'success' => true,
                'estadisticas' => [
                    'porEstado' => $departamentosPorEstado,
                    'empleadosPorDepartamento' => $empleadosPorDepartamento
                ]
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error al obtener estadísticas de departamentos: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al obtener estadísticas: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Obtener empleado por ID para edición
     */
    public function obtenerEmpleado($id)
    {
        try {
            $empleado = $this->empleadoModel->getEmpleadoConUsuario($id);
            
            if (!$empleado) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Empleado no encontrado'
                ]);
            }
            
            return $this->response->setJSON([
                'success' => true,
                'empleado' => $empleado
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error al obtener empleado: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al obtener empleado: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Obtener credenciales originales del empleado
     */
    public function obtenerCredenciales($id)
    {
        try {
            $empleado = $this->empleadoModel->getEmpleadoConUsuario($id);
            
            if (!$empleado) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Empleado no encontrado'
                ]);
            }
            
            // Obtener credenciales originales (email y contraseña por defecto)
            $credenciales = [
                'email' => $empleado['cedula'] . '@itsi.edu.ec',
                'password' => '123456',
                'mensaje' => 'Estas son las credenciales originales de creación de cuenta. Si el empleado ya cambió su contraseña, estas credenciales ya no son válidas.'
            ];
            
            return $this->response->setJSON([
                'success' => true,
                'credenciales' => $credenciales
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error al obtener credenciales: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al obtener credenciales: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Guardar o actualizar empleado
     */
    public function guardarEmpleado()
    {
        // Verificar si es una petición POST
        if (!$this->request->is('post')) {
            return $this->response->setJSON(['success' => false, 'message' => 'Método no permitido']);
        }

        try {
            // Obtener datos del formulario
            $datos = $this->request->getPost();
            
            // Validar datos requeridos
            if (empty($datos['nombres']) || empty($datos['apellidos']) || empty($datos['cedula']) || empty($datos['tipo_empleado'])) {
                return $this->response->setJSON(['success' => false, 'message' => 'Todos los campos marcados con * son obligatorios']);
            }

            // Si el tipo es "OTRO", usar el valor personalizado
            $tipoEmpleado = $datos['tipo_empleado'];
            if ($tipoEmpleado === 'OTRO') {
                if (empty($datos['tipo_empleado_personalizado'])) {
                    return $this->response->setJSON(['success' => false, 'message' => 'Debe especificar el tipo personalizado']);
                }
                $tipoEmpleado = $datos['tipo_empleado_personalizado'];
            }

            $db = \Config\Database::connect();
            
            // Verificar si es una edición o creación
            if (!empty($datos['id_empleado'])) {
                // ES UNA EDICIÓN - Actualizar empleado existente
                $idEmpleado = $datos['id_empleado'];
                
                // Obtener el empleado actual para verificar si cambió la cédula
                $empleadoActual = $db->table('empleados e')
                    ->select('e.id_usuario, u.cedula as cedula_actual')
                    ->join('usuarios u', 'u.id_usuario = e.id_usuario')
                    ->where('e.id_empleado', $idEmpleado)
                    ->get()
                    ->getRowArray();
                
                if (!$empleadoActual) {
                    return $this->response->setJSON(['success' => false, 'message' => 'Empleado no encontrado']);
                }
                
                $idUsuario = $empleadoActual['id_usuario'];
                $cedulaActual = $empleadoActual['cedula_actual'];
                
                // Si cambió la cédula, verificar que no exista otra
                if ($datos['cedula'] !== $cedulaActual) {
                    $cedulaExiste = $db->table('usuarios')->where('cedula', $datos['cedula'])->where('id_usuario !=', $idUsuario)->get()->getRowArray();
                    if ($cedulaExiste) {
                        return $this->response->setJSON(['success' => false, 'message' => 'La cédula ' . $datos['cedula'] . ' ya está registrada por otro usuario']);
                    }
                    
                    // Actualizar cédula y email en usuarios
                    $db->table('usuarios')->where('id_usuario', $idUsuario)->update([
                        'cedula' => $datos['cedula'],
                        'email' => $datos['cedula'] . '@itsi.edu.ec'
                    ]);
                }
                
                // Actualizar empleado
                $empleadoData = [
                    'nombres' => $datos['nombres'],
                    'apellidos' => $datos['apellidos'],
                    'tipo_empleado' => $tipoEmpleado,
                    'departamento' => $datos['departamento'] ?? 'Sin asignar',
                    'fecha_ingreso' => $datos['fecha_ingreso'] ?? date('Y-m-d'),
                    'salario' => $datos['salario'] ?? 0.00,
                    'estado' => $datos['estado'] ?? 'Activo'
                ];
                
                $db->table('empleados')->where('id_empleado', $idEmpleado)->update($empleadoData);
                
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Empleado actualizado exitosamente',
                    'tipo' => 'actualizacion'
                ]);
                
            } else {
                // ES UNA CREACIÓN - Crear nuevo empleado
                
                // Verificar que la cédula no exista
                $cedulaExiste = $db->table('usuarios')->where('cedula', $datos['cedula'])->get()->getRowArray();
                if ($cedulaExiste) {
                    return $this->response->setJSON(['success' => false, 'message' => 'La cédula ' . $datos['cedula'] . ' ya está registrada']);
                }
                
                // Crear usuario primero
                $usuarioData = [
                    'cedula' => $datos['cedula'],
                    'email' => $datos['cedula'] . '@itsi.edu.ec',
                    'password_hash' => password_hash('123456', PASSWORD_DEFAULT),
                    'id_rol' => 3,
                    'activo' => 1
                ];

                // Insertar usuario y obtener ID
                $db->table('usuarios')->insert($usuarioData);
                $idUsuario = $db->insertID();

                // Crear empleado
                $empleadoData = [
                    'id_usuario' => $idUsuario,
                    'nombres' => $datos['nombres'],
                    'apellidos' => $datos['apellidos'],
                    'tipo_empleado' => $tipoEmpleado,
                    'departamento' => $datos['departamento'] ?? 'Sin asignar',
                    'fecha_ingreso' => $datos['fecha_ingreso'] ?? date('Y-m-d'),
                    'salario' => $datos['salario'] ?? 0.00,
                    'estado' => $datos['estado'] ?? 'Activo',
                    'fecha_nacimiento' => date('Y-m-d', strtotime('-25 years')),
                    'genero' => 'No especificado',
                    'estado_civil' => 'Soltero',
                    'direccion' => 'Por definir',
                    'telefono' => 'Por definir',
                    'activo' => 1
                ];

                // Insertar empleado
                $db->table('empleados')->insert($empleadoData);

                return $this->response->setJSON([
                    'success' => true, 
                    'message' => 'Empleado creado exitosamente. Credenciales de acceso: Email: ' . $datos['cedula'] . '@itsi.edu.ec, Contraseña: 123456',
                    'id_usuario' => $idUsuario,
                    'tipo' => 'creacion',
                    'credenciales' => [
                        'email' => $datos['cedula'] . '@itsi.edu.ec',
                        'password' => '123456'
                    ]
                ]);
            }

        } catch (\Exception $e) {
            log_message('error', 'Error al guardar empleado: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false, 
                'message' => 'Error al guardar empleado: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Gestión de departamentos
     */
    public function departamentos()
    {
        $data = [
            'titulo' => 'Gestión de Departamentos',
            'usuario' => [
                'nombres' => session()->get('nombres'),
                'apellidos' => session()->get('apellidos'),
                'rol' => session()->get('nombre_rol')
            ],
            'departamentos' => $this->departamentoModel->findAll()
        ];

        return view('Roles/AdminTH/departamentos', $data);
    }

    /**
     * Obtener departamentos en formato JSON para AJAX
     */
    public function obtenerDepartamentos()
    {
        try {
            $departamentos = $this->departamentoModel->findAll();
            
            return $this->response->setJSON([
                'success' => true,
                'departamentos' => $departamentos
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error al obtener departamentos: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al obtener departamentos: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Obtener departamento por ID para edición
     */
    public function obtenerDepartamento($id)
    {
        try {
            $departamento = $this->departamentoModel->getDepartamentoCompleto($id);
            
            if (!$departamento) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Departamento no encontrado'
                ]);
            }
            
            return $this->response->setJSON([
                'success' => true,
                'departamento' => $departamento
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error al obtener departamento: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al obtener departamento: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Guardar o actualizar departamento
     */
    public function guardarDepartamento()
    {
        if (!$this->request->is('post')) {
            return $this->response->setJSON(['success' => false, 'message' => 'Método no permitido']);
        }

        try {
            $datos = $this->request->getPost();
            
            if (empty($datos['nombre'])) {
                return $this->response->setJSON(['success' => false, 'message' => 'El nombre del departamento es obligatorio']);
            }

            $db = \Config\Database::connect();
            
            if (!empty($datos['id_departamento'])) {
                // ES UNA EDICIÓN
                $idDepartamento = $datos['id_departamento'];
                
                // Verificar si el nombre ya existe en otro departamento
                $nombreExiste = $db->table('departamentos')
                    ->where('nombre', $datos['nombre'])
                    ->where('id_departamento !=', $idDepartamento)
                    ->get()
                    ->getRowArray();
                
                if ($nombreExiste) {
                    return $this->response->setJSON(['success' => false, 'message' => 'Ya existe un departamento con ese nombre']);
                }
                
                // Actualizar departamento
                $departamentoData = [
                    'nombre' => $datos['nombre'],
                    'descripcion' => $datos['descripcion'] ?? '',
                    'responsable' => $datos['responsable'] ?? '',
                    'email_contacto' => $datos['email_contacto'] ?? '',
                    'telefono' => $datos['telefono'] ?? '',
                    'ubicacion' => $datos['ubicacion'] ?? '',
                    'estado' => $datos['estado'] ?? 'Activo',
                    'activo' => ($datos['estado'] === 'Activo') ? 1 : 0
                ];
                
                $db->table('departamentos')->where('id_departamento', $idDepartamento)->update($departamentoData);
                
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Departamento actualizado exitosamente',
                    'tipo' => 'actualizacion'
                ]);
                
            } else {
                // ES UNA CREACIÓN
                
                // Verificar que el nombre no exista
                $nombreExiste = $db->table('departamentos')->where('nombre', $datos['nombre'])->get()->getRowArray();
                if ($nombreExiste) {
                    return $this->response->setJSON(['success' => false, 'message' => 'Ya existe un departamento con ese nombre']);
                }
                
                // Crear departamento
                $departamentoData = [
                    'nombre' => $datos['nombre'],
                    'descripcion' => $datos['descripcion'] ?? '',
                    'responsable' => $datos['responsable'] ?? '',
                    'email_contacto' => $datos['email_contacto'] ?? '',
                    'telefono' => $datos['telefono'] ?? '',
                    'ubicacion' => $datos['ubicacion'] ?? '',
                    'estado' => $datos['estado'] ?? 'Activo',
                    'activo' => 1
                ];
                
                $db->table('departamentos')->insert($departamentoData);
                
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Departamento creado exitosamente',
                    'tipo' => 'creacion'
                ]);
            }

        } catch (\Exception $e) {
            log_message('error', 'Error al guardar departamento: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al guardar departamento: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Eliminar departamento
     */
    public function eliminarDepartamento()
    {
        if (!$this->request->is('post')) {
            return $this->response->setJSON(['success' => false, 'message' => 'Método no permitido']);
        }

        try {
            $idDepartamento = $this->request->getPost('id_departamento');
            
            if (empty($idDepartamento)) {
                return $this->response->setJSON(['success' => false, 'message' => 'ID de departamento requerido']);
            }

            // Verificar si el departamento está en uso
            if ($this->departamentoModel->departamentoEnUso($idDepartamento)) {
                return $this->response->setJSON(['success' => false, 'message' => 'No se puede eliminar el departamento porque tiene empleados asignados']);
            }

            // Eliminar departamento
            $this->departamentoModel->delete($idDepartamento);
            
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Departamento eliminado exitosamente'
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Error al eliminar departamento: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al eliminar departamento: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Obtener departamentos activos para dropdown
     */
    public function obtenerDepartamentosActivos()
    {
        try {
            $departamentos = $this->departamentoModel->getDepartamentosParaDropdown();
            
            return $this->response->setJSON([
                'success' => true,
                'departamentos' => $departamentos
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error al obtener departamentos activos: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al obtener departamentos activos: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Gestión de puestos
     */
    public function puestos()
    {
        $data = [
            'titulo' => 'Gestión de Puestos',
            'usuario' => [
                'nombres' => session()->get('nombres'),
                'apellidos' => session()->get('apellidos'),
                'rol' => session()->get('nombre_rol')
            ],
            'puestos' => $this->puestoModel->getPuestosConDepartamento()
        ];

        return view('Roles/AdminTH/puestos', $data);
    }

    /**
     * Obtener puestos en formato JSON para AJAX
     */
    public function obtenerPuestos()
    {
        try {
            $puestos = $this->puestoModel->getPuestosConDepartamento();
            
            return $this->response->setJSON([
                'success' => true,
                'data' => $puestos
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error al obtener puestos: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al obtener puestos: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Obtener puesto por ID para edición
     */
    public function obtenerPuesto($id)
    {
        try {
            $puesto = $this->puestoModel->getPuestoCompleto($id);
            
            if (!$puesto) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Puesto no encontrado'
                ]);
            }
            
            return $this->response->setJSON([
                'success' => true,
                'puesto' => $puesto
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error al obtener puesto: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al obtener puesto: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Guardar o actualizar puesto
     */
    public function guardarPuesto()
    {
        if (!$this->request->is('post')) {
            return $this->response->setJSON(['success' => false, 'message' => 'Método no permitido']);
        }

        try {
            $datos = $this->request->getPost();
            
            if (empty($datos['titulo'])) {
                return $this->response->setJSON(['success' => false, 'message' => 'El título del puesto es obligatorio']);
            }

            $db = \Config\Database::connect();
            
            if (!empty($datos['id_puesto'])) {
                // ES UNA EDICIÓN
                $idPuesto = $datos['id_puesto'];
                
                // Actualizar puesto
                $puestoData = [
                    'titulo' => $datos['titulo'],
                    'descripcion' => $datos['descripcion'] ?? '',
                    'id_departamento' => $datos['id_departamento'],
                    'tipo_contrato' => $datos['tipo_contrato'],
                    'salario_min' => $datos['salario_min'] ?? 0,
                    'salario_max' => $datos['salario_max'] ?? 0,
                    'experiencia_requerida' => $datos['experiencia_requerida'] ?? '',
                    'educacion_requerida' => $datos['educacion_requerida'] ?? '',
                    'habilidades_requeridas' => $datos['habilidades_requeridas'] ?? '',
                    'responsabilidades' => $datos['responsabilidades'] ?? '',
                    'beneficios' => $datos['beneficios'] ?? '',
                    'estado' => $datos['estado'] ?? 'Abierto',
                    'activo' => ($datos['estado'] === 'Abierto') ? 1 : 0,
                    'fecha_limite' => $datos['fecha_limite'] ?? date('Y-m-d', strtotime('+30 days')),
                    'vacantes_disponibles' => $datos['vacantes_disponibles'] ?? 1,
                    'nivel_experiencia' => $datos['nivel_experiencia'],
                    'modalidad_trabajo' => $datos['modalidad_trabajo'],
                    'ubicacion_trabajo' => $datos['ubicacion_trabajo'] ?? ''
                ];
                
                $db->table('puestos')->where('id_puesto', $idPuesto)->update($puestoData);
                
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Puesto actualizado exitosamente',
                    'tipo' => 'actualizacion'
                ]);
                
            } else {
                // ES UNA CREACIÓN
                
                // Crear puesto
                $puestoData = [
                    'titulo' => $datos['titulo'],
                    'descripcion' => $datos['descripcion'] ?? '',
                    'id_departamento' => $datos['id_departamento'],
                    'tipo_contrato' => $datos['tipo_contrato'],
                    'salario_min' => $datos['salario_min'] ?? 0,
                    'salario_max' => $datos['salario_max'] ?? 0,
                    'experiencia_requerida' => $datos['experiencia_requerida'] ?? '',
                    'educacion_requerida' => $datos['educacion_requerida'] ?? '',
                    'habilidades_requeridas' => $datos['habilidades_requeridas'] ?? '',
                    'responsabilidades' => $datos['responsabilidades'] ?? '',
                    'beneficios' => $datos['beneficios'] ?? '',
                    'estado' => $datos['estado'] ?? 'Abierto',
                    'activo' => 1,
                    'fecha_limite' => $datos['fecha_limite'] ?? date('Y-m-d', strtotime('+30 days')),
                    'vacantes_disponibles' => $datos['vacantes_disponibles'] ?? 1,
                    'nivel_experiencia' => $datos['nivel_experiencia'],
                    'modalidad_trabajo' => $datos['modalidad_trabajo'],
                    'ubicacion_trabajo' => $datos['ubicacion_trabajo'] ?? ''
                ];
                
                $db->table('puestos')->insert($puestoData);
                $idPuesto = $db->insertID();
                
                // Generar URL única para postulación
                $urlPostulacion = $this->puestoModel->generarUrlPostulacion($idPuesto);
                
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Puesto creado exitosamente. URL de postulación generada.',
                    'tipo' => 'creacion',
                    'id_puesto' => $idPuesto,
                    'url_postulacion' => $urlPostulacion
                ]);
            }

        } catch (\Exception $e) {
            log_message('error', 'Error al guardar puesto: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al guardar puesto: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Eliminar puesto
     */
    public function eliminarPuesto()
    {
        if (!$this->request->is('post')) {
            return $this->response->setJSON(['success' => false, 'message' => 'Método no permitido']);
        }

        try {
            $idPuesto = $this->request->getPost('id_puesto');
            
            if (empty($idPuesto)) {
                return $this->response->setJSON(['success' => false, 'message' => 'ID de puesto requerido']);
            }

            // Verificar si el puesto está en uso
            if ($this->puestoModel->puestoEnUso($idPuesto)) {
                return $this->response->setJSON(['success' => false, 'message' => 'No se puede eliminar el puesto porque tiene empleados asignados']);
            }

            // Eliminar puesto
            $this->puestoModel->delete($idPuesto);
            
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Puesto eliminado exitosamente'
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Error al eliminar puesto: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al eliminar puesto: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Generar URL de postulación para puesto existente
     */
    public function generarUrlPostulacion()
    {
        if (!$this->request->is('post')) {
            return $this->response->setJSON(['success' => false, 'message' => 'Método no permitido']);
        }

        try {
            // Obtener datos JSON del request
            $jsonData = $this->request->getJSON(true);
            $idPuesto = $jsonData['id_puesto'] ?? null;
            
            if (empty($idPuesto)) {
                return $this->response->setJSON(['success' => false, 'message' => 'ID de puesto requerido']);
            }

            $urlPostulacion = $this->puestoModel->generarUrlPostulacion($idPuesto);
            
            if ($urlPostulacion) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'URL de postulación generada exitosamente',
                    'url_postulacion' => $urlPostulacion
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'No se pudo generar la URL de postulación'
                ]);
            }

        } catch (\Exception $e) {
            log_message('error', 'Error al generar URL de postulación: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al generar URL de postulación: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Obtener postulantes para un puesto específico
     */
    public function obtenerPostulantesPuesto($idPuesto)
    {
        try {
            $postulantes = $this->postulanteModel->getPostulantesPorPuesto($idPuesto);
            
            return $this->response->setJSON([
                'success' => true,
                'data' => $postulantes
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error al obtener postulantes: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al obtener postulantes: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Cambiar estado de postulación
     */
    public function cambiarEstadoPostulacion()
    {
        if (!$this->request->is('post')) {
            return $this->response->setJSON(['success' => false, 'message' => 'Método no permitido']);
        }

        try {
            // Obtener datos JSON del request
            $jsonData = $this->request->getJSON(true);
            $idPostulante = $jsonData['id_postulante'] ?? null;
            $nuevoEstado = $jsonData['nuevo_estado'] ?? null;
            
            if (empty($idPostulante) || empty($nuevoEstado)) {
                return $this->response->setJSON(['success' => false, 'message' => 'ID de postulante y nuevo estado son requeridos']);
            }

            $resultado = $this->postulanteModel->cambiarEstadoPostulacion($idPostulante, $nuevoEstado);
            
            if ($resultado) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Estado de postulación actualizado exitosamente'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'No se pudo actualizar el estado de la postulación'
                ]);
            }

        } catch (\Exception $e) {
            log_message('error', 'Error al cambiar estado de postulación: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al cambiar estado de postulación: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Vista de gestión de postulantes
     */
    public function postulantes()
    {
        // Verificar si el usuario está autenticado y tiene el rol correcto
        if (!session()->get('id_usuario') || session()->get('id_rol') != 2) {
            return redirect()->to('/login');
        }

        $postulanteModel = new \App\Models\PostulanteModel();
        $puestoModel = new \App\Models\PuestoModel();
        $departamentoModel = new \App\Models\DepartamentoModel();

        // Obtener filtros de la URL
        $estado = $this->request->getGet('estado') ?? '';
        $puesto = $this->request->getGet('puesto') ?? '';
        $departamento = $this->request->getGet('departamento') ?? '';
        $busqueda = $this->request->getGet('busqueda') ?? '';

        // Obtener datos para los filtros
        $estados = ['Pendiente', 'En revisión', 'Aprobada', 'Rechazada', 'Contratado'];
        $puestos = $puestoModel->findAll();
        $departamentos = $departamentoModel->findAll();

        // Construir la consulta base
        $postulantes = $postulanteModel->getPostulantesConPuesto();

        // Aplicar filtros
        if ($estado) {
            $postulantes = array_filter($postulantes, function($p) use ($estado) {
                return $p['estado_postulacion'] === $estado;
            });
        }

        if ($puesto) {
            $postulantes = array_filter($postulantes, function($p) use ($puesto) {
                return $p['id_puesto'] == $puesto;
            });
        }

        if ($departamento) {
            $postulantes = array_filter($postulantes, function($p) use ($departamento) {
                return $p['id_departamento'] == $departamento;
            });
        }

        if ($busqueda) {
            $postulantes = array_filter($postulantes, function($p) use ($busqueda) {
                return stripos($p['nombres'], $busqueda) !== false ||
                       stripos($p['apellidos'], $busqueda) !== false ||
                       stripos($p['cedula'], $busqueda) !== false ||
                       stripos($p['email'], $busqueda) !== false;
            });
        }

        // Obtener estadísticas
        $estadisticas = $postulanteModel->getEstadisticasPostulaciones();

        $data = [
            'titulo' => 'Gestión de Postulantes',
            'postulantes' => array_values($postulantes), // Reindexar array
            'estados' => $estados,
            'puestos' => $puestos,
            'departamentos' => $departamentos,
            'filtros' => [
                'estado' => $estado,
                'puesto' => $puesto,
                'departamento' => $departamento,
                'busqueda' => $busqueda
            ],
            'estadisticas' => $estadisticas
        ];

        return view('Roles/AdminTH/postulantes', $data);
    }

    /**
     * Ver detalles de un postulante
     */
    public function verPostulante($idPostulante)
    {
        if (!session()->get('usuario_id') || session()->get('rol') !== 'admin_th') {
            return redirect()->to('auth/login');
        }

        $postulanteModel = new \App\Models\PostulanteModel();
        $postulante = $postulanteModel->getPostulanteCompleto($idPostulante);

        if (!$postulante) {
            return redirect()->to('admin-th/postulantes')->with('error', 'Postulante no encontrado');
        }

        $data = [
            'titulo' => 'Detalles del Postulante',
            'postulante' => $postulante
        ];

        return view('Roles/AdminTH/ver_postulante', $data);
    }



    /**
     * Descargar CV del postulante
     */
    public function descargarCV($idPostulante)
    {
        if (!session()->get('id_usuario') || session()->get('id_rol') != 2) {
            return redirect()->to('/login');
        }

        $postulanteModel = new \App\Models\PostulanteModel();
        $postulante = $postulanteModel->find($idPostulante);

        if (!$postulante || !$postulante['cv_path']) {
            return redirect()->to('admin-th/postulantes')->with('error', 'CV no encontrado');
        }

        $rutaCV = FCPATH . $postulante['cv_path'];
        
        if (!file_exists($rutaCV)) {
            return redirect()->to('admin-th/postulantes')->with('error', 'Archivo CV no encontrado');
        }

        $nombreArchivo = basename($postulante['cv_path']);
        
        return $this->response->download($rutaCV, $nombreArchivo);
    }

    /**
     * Exportar postulantes a CSV
     */
    public function exportarPostulantes()
    {
        if (!session()->get('id_usuario') || session()->get('id_rol') != 2) {
            return redirect()->to('/login');
        }

        $postulanteModel = new \App\Models\PostulanteModel();
        $postulantes = $postulanteModel->getPostulantesConPuesto();

        $filename = 'postulantes_' . date('Y-m-d_H-i-s') . '.csv';
        
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        $output = fopen('php://output', 'w');
        
        // Encabezados
        fputcsv($output, [
            'ID', 'Nombres', 'Apellidos', 'Cédula', 'Email', 'Teléfono',
            'Puesto', 'Departamento', 'Estado', 'Fecha Postulación',
            'Disponibilidad', 'Expectativa Salarial'
        ]);

        // Datos
        foreach ($postulantes as $postulante) {
            fputcsv($output, [
                $postulante['id_postulante'],
                $postulante['nombres'],
                $postulante['apellidos'],
                $postulante['cedula'],
                $postulante['email'],
                $postulante['telefono'],
                $postulante['titulo_puesto'],
                $postulante['nombre_departamento'],
                $postulante['estado_postulacion'],
                $postulante['fecha_postulacion'],
                $postulante['disponibilidad_inmediata'],
                $postulante['expectativa_salarial'] ?? 'No especificada'
            ]);
        }
        
        fclose($output);
        exit;
    }

    /**
     * Gestión de capacitaciones
     */
    public function capacitaciones()
    {
        $data = [
            'titulo' => 'Gestión de Capacitaciones',
            'usuario' => [
                'nombres' => session()->get('nombres'),
                'apellidos' => session()->get('apellidos'),
                'rol' => session()->get('nombre_rol')
            ],
            'capacitaciones' => $this->capacitacionModel->findAll()
        ];

        return view('Roles/AdminTH/capacitaciones', $data);
    }

    /**
     * Gestión de títulos académicos
     */
    public function titulosAcademicos()
    {
        $data = [
            'titulo' => 'Gestión de Títulos Académicos',
            'usuario' => [
                'nombres' => session()->get('nombres'),
                'apellidos' => session()->get('apellidos'),
                'rol' => session()->get('nombre_rol')
            ],
            'titulos' => $this->tituloAcademicoModel->findAll()
        ];

        return view('Roles/AdminTH/titulos_academicos', $data);
    }

    /**
     * Gestión de evaluaciones
     */
    public function evaluaciones()
    {
        $data = [
            'titulo' => 'Gestión de Evaluaciones',
            'usuario' => [
                'nombres' => session()->get('nombres'),
                'apellidos' => session()->get('apellidos'),
                'rol' => session()->get('nombre_rol')
            ],
            'evaluaciones' => $this->evaluacionModel->findAll()
        ];

        return view('Roles/AdminTH/evaluaciones', $data);
    }

    /**
     * Gestión de inasistencias
     */
    public function inasistencias()
    {
        $data = [
            'titulo' => 'Gestión de Inasistencias',
            'usuario' => [
                'nombres' => session()->get('nombres'),
                'apellidos' => session()->get('apellidos'),
                'rol' => session()->get('nombre_rol')
            ],
            'inasistencias' => $this->inasistenciaModel->getInasistenciasConEmpleado()
        ];

        return view('Roles/AdminTH/inasistencias/dashboard', $data);
    }

    /**
     * Gestión de políticas de inasistencia
     */
    public function politicasInasistencia()
    {
        $data = [
            'titulo' => 'Políticas de Inasistencia',
            'usuario' => [
                'nombres' => session()->get('nombres'),
                'apellidos' => session()->get('apellidos'),
                'rol' => session()->get('nombre_rol')
            ],
            'politicas' => $this->politicaInasistenciaModel->findAll()
        ];

        return view('Roles/AdminTH/politicas_inasistencia', $data);
    }

    /**
     * Gestión de solicitudes de capacitación
     */
    public function solicitudesCapacitacion()
    {
        $data = [
            'titulo' => 'Solicitudes de Capacitación',
            'usuario' => [
                'nombres' => session()->get('nombres'),
                'apellidos' => session()->get('apellidos'),
                'rol' => session()->get('nombre_rol')
            ],
            'solicitudes' => $this->solicitudCapacitacionModel->getSolicitudesConEmpleado()
        ];

        return view('Roles/AdminTH/solicitudes_capacitacion', $data);
    }

    /**
     * Reportes
     */
    public function reportes()
    {
        $data = [
            'titulo' => 'Reportes',
            'usuario' => [
                'nombres' => session()->get('nombres'),
                'apellidos' => session()->get('apellidos'),
                'rol' => session()->get('nombre_rol')
            ]
        ];

        return view('Roles/AdminTH/reportes', $data);
    }

    /**
     * Estadísticas
     */
    public function estadisticas()
    {
        $data = [
            'titulo' => 'Estadísticas',
            'usuario' => [
                'nombres' => session()->get('nombres'),
                'apellidos' => session()->get('apellidos'),
                'rol' => session()->get('nombre_rol')
            ]
        ];

        return view('Roles/AdminTH/estadisticas', $data);
    }
    
    /**
     * Acceso rápido
     */
    public function accesoRapido()
    {
        $data = [
            'titulo' => 'Acceso Rápido',
            'usuario' => [
                'nombres' => session()->get('nombres'),
                'apellidos' => session()->get('apellidos'),
                'rol' => session()->get('nombre_rol')
            ]
        ];

        return view('Roles/AdminTH/acceso_rapido', $data);
    }
    


    /**
     * Mi Perfil (unificado: datos personales + seguridad)
     */
    public function miPerfil()
    {
        $data = [
            'titulo' => 'Mi Perfil',
            'usuario' => [
                'nombres' => session()->get('nombres'),
                'apellidos' => session()->get('apellidos'),
                'rol' => session()->get('nombre_rol')
            ]
        ];

        return view('Roles/AdminTH/mi_perfil', $data);
    }

    /**
     * Actualizar perfil del administrador
     */
    public function actualizarPerfil()
    {
        if (!$this->request->is('post')) {
            return $this->response->setJSON(['success' => false, 'message' => 'Método no permitido']);
        }

        try {
            $json = $this->request->getJSON(true);
            if (!$json) {
                $json = $this->request->getPost();
            }
            
            $userId = session()->get('id_usuario');
            
            $nombres = trim($json['nombres'] ?? '');
            $apellidos = trim($json['apellidos'] ?? '');
            $email = trim($json['email'] ?? '');
            
            if (empty($nombres) || empty($apellidos) || empty($email)) {
                return $this->response->setJSON(['success' => false, 'message' => 'Nombres, apellidos y email son obligatorios']);
            }
            
            $db = \Config\Database::connect();
            
            // Verificar que el email no esté duplicado
            $existeEmail = $db->table('usuarios')
                ->where('email', $email)
                ->where('id_usuario !=', $userId)
                ->countAllResults();
            
            if ($existeEmail > 0) {
                return $this->response->setJSON(['success' => false, 'message' => 'El email ya está registrado por otro usuario']);
            }
            
            // Actualizar en tabla empleados
            $db->table('empleados')
                ->where('id_usuario', $userId)
                ->update([
                    'nombres' => $nombres,
                    'apellidos' => $apellidos
                ]);
            
            // Actualizar email en tabla usuarios
            $db->table('usuarios')
                ->where('id_usuario', $userId)
                ->update(['email' => $email]);
            
            // Actualizar sesión
            session()->set('nombres', $nombres);
            session()->set('apellidos', $apellidos);
            session()->set('email', $email);
            
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Perfil actualizado correctamente'
            ]);
            
        } catch (\Exception $e) {
            log_message('error', 'Error al actualizar perfil AdminTH: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al actualizar el perfil: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Cambiar contraseña del administrador
     */
    public function cambiarPassword()
    {
        if (!$this->request->is('post')) {
            return $this->response->setJSON(['success' => false, 'message' => 'Método no permitido']);
        }

        try {
            $json = $this->request->getJSON(true);
            if (!$json) {
                $json = $this->request->getPost();
            }
            
            $userId = session()->get('id_usuario');
            
            if (empty($json['password_actual']) || empty($json['password_nuevo']) || empty($json['password_confirmar'])) {
                return $this->response->setJSON(['success' => false, 'message' => 'Todos los campos son obligatorios']);
            }
            
            if ($json['password_nuevo'] !== $json['password_confirmar']) {
                return $this->response->setJSON(['success' => false, 'message' => 'Las contraseñas nuevas no coinciden']);
            }
            
            if (strlen($json['password_nuevo']) < 6) {
                return $this->response->setJSON(['success' => false, 'message' => 'La contraseña debe tener al menos 6 caracteres']);
            }
            
            $db = \Config\Database::connect();
            $usuario = $db->table('usuarios')->where('id_usuario', $userId)->get()->getRowArray();
            
            if (!$usuario) {
                return $this->response->setJSON(['success' => false, 'message' => 'Usuario no encontrado']);
            }
            
            if (!password_verify($json['password_actual'], $usuario['password_hash'])) {
                return $this->response->setJSON(['success' => false, 'message' => 'La contraseña actual es incorrecta']);
            }
            
            $db->table('usuarios')->where('id_usuario', $userId)->update([
                'password_hash' => password_hash($json['password_nuevo'], PASSWORD_DEFAULT),
                'password_changed' => 1
            ]);
            
            session()->set('password_changed', 1);
            
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Contraseña cambiada exitosamente'
            ]);
            
        } catch (\Exception $e) {
            log_message('error', 'Error al cambiar contraseña AdminTH: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al cambiar la contraseña'
            ]);
        }
    }

    // ==================== GESTIÓN DE EMPLEADOS AVANZADA ====================

    /**
     * Deshabilitar empleado
     */
    public function deshabilitarEmpleado()
    {
        if (!$this->request->isAJAX()) {
            return redirect()->back();
        }

        try {
            $idEmpleado = $this->request->getPost('id_empleado');
            $motivo = $this->request->getPost('motivo', FILTER_SANITIZE_STRING) ?: 'Deshabilitado por administrador';
            
            if (!$idEmpleado) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'ID de empleado requerido'
                ]);
            }

            // Obtener empleado
            $empleado = $this->empleadoModel->find($idEmpleado);
            if (!$empleado) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Empleado no encontrado'
                ]);
            }

            // Actualizar estado del empleado
            $dataEmpleado = [
                'estado' => 'INACTIVO',
                'fecha_fin' => date('Y-m-d'),
                'observaciones' => $motivo
            ];

            if ($this->empleadoModel->update($idEmpleado, $dataEmpleado)) {
                // Deshabilitar usuario asociado si existe
                if ($empleado['id_usuario']) {
                    $usuarioModel = new UsuarioModel();
                    $usuarioModel->update($empleado['id_usuario'], ['activo' => 0]);
                }

                // Registrar log
                $logModel = new LogSistemaModel();
                $logModel->registrarLog(
                    session()->get('id_usuario'),
                    'DESHABILITAR_EMPLEADO',
                    'EMPLEADOS',
                    "Empleado deshabilitado: {$empleado['nombres']} {$empleado['apellidos']} - Motivo: {$motivo}"
                );

                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Empleado deshabilitado correctamente'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Error al deshabilitar el empleado'
                ]);
            }

        } catch (\Exception $e) {
            log_message('error', 'Error deshabilitando empleado: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error interno del servidor'
            ]);
        }
    }

    /**
     * Habilitar empleado
     */
    public function habilitarEmpleado()
    {
        if (!$this->request->isAJAX()) {
            return redirect()->back();
        }

        try {
            $idEmpleado = $this->request->getPost('id_empleado');
            $motivo = $this->request->getPost('motivo', FILTER_SANITIZE_STRING) ?: 'Reactivado por administrador';
            
            if (!$idEmpleado) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'ID de empleado requerido'
                ]);
            }

            // Obtener empleado
            $empleado = $this->empleadoModel->find($idEmpleado);
            if (!$empleado) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Empleado no encontrado'
                ]);
            }

            // Actualizar estado del empleado
            $dataEmpleado = [
                'estado' => 'ACTIVO',
                'fecha_fin' => null,
                'observaciones' => $motivo
            ];

            if ($this->empleadoModel->update($idEmpleado, $dataEmpleado)) {
                // Habilitar usuario asociado si existe
                if ($empleado['id_usuario']) {
                    $usuarioModel = new UsuarioModel();
                    $usuarioModel->update($empleado['id_usuario'], ['activo' => 1]);
                }

                // Registrar log
                $logModel = new LogSistemaModel();
                $logModel->registrarLog(
                    session()->get('id_usuario'),
                    'HABILITAR_EMPLEADO',
                    'EMPLEADOS',
                    "Empleado habilitado: {$empleado['nombres']} {$empleado['apellidos']} - Motivo: {$motivo}"
                );

                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Empleado habilitado correctamente'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Error al habilitar el empleado'
                ]);
            }

        } catch (\Exception $e) {
            log_message('error', 'Error habilitando empleado: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error interno del servidor'
            ]);
        }
    }

    /**
     * Obtener historial de cambios de estado de un empleado
     */
    public function obtenerHistorialEmpleado($idEmpleado)
    {
        if (!$this->request->isAJAX()) {
            return redirect()->back();
        }

        try {
            $logModel = new LogSistemaModel();
            
            // Buscar logs relacionados con este empleado
            $logs = $logModel->select('logs_sistema.*, usuarios.cedula, usuarios.email')
                            ->join('usuarios', 'usuarios.id_usuario = logs_sistema.id_usuario', 'left')
                            ->where('modulo', 'EMPLEADOS')
                            ->like('descripcion', "ID: {$idEmpleado}", 'both')
                            ->orderBy('fecha_accion', 'DESC')
                            ->limit(20)
                            ->findAll();

            $historial = [];
            foreach ($logs as $log) {
                $historial[] = [
                    'fecha' => date('d/m/Y H:i:s', strtotime($log['fecha_accion'])),
                    'accion' => $log['accion'],
                    'descripcion' => $log['descripcion'],
                    'usuario' => $log['cedula'] ?? 'Sistema',
                    'ip_address' => $log['ip_address']
                ];
            }

            return $this->response->setJSON([
                'success' => true,
                'historial' => $historial
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Error obteniendo historial: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al obtener el historial'
            ]);
        }
    }

    /**
     * Reporte de empleados inactivos
     */
    public function reporteEmpleadosInactivos()
    {
        try {
            $empleadosInactivos = $this->empleadoModel->where('estado', 'INACTIVO')
                                                    ->orderBy('fecha_fin', 'DESC')
                                                    ->findAll();

            $data = [
                'titulo' => 'Reporte de Empleados Inactivos',
                'usuario' => [
                    'nombres' => session()->get('nombres'),
                    'apellidos' => session()->get('apellidos'),
                    'rol' => session()->get('nombre_rol')
                ],
                'empleados_inactivos' => $empleadosInactivos,
                'total_inactivos' => count($empleadosInactivos)
            ];

            return view('Roles/AdminTH/reportes/empleados_inactivos', $data);

        } catch (\Exception $e) {
            log_message('error', 'Error generando reporte: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al generar el reporte');
        }
    }

    /**
     * Exportar empleados inactivos a CSV
     */
    public function exportarEmpleadosInactivos()
    {
        try {
            $empleadosInactivos = $this->empleadoModel->where('estado', 'INACTIVO')
                                                    ->orderBy('fecha_fin', 'DESC')
                                                    ->findAll();

            // Generar CSV
            $filename = 'empleados_inactivos_' . date('Y-m-d_H-i-s') . '.csv';
            $filepath = WRITEPATH . 'exports/' . $filename;
            
            // Crear directorio si no existe
            if (!is_dir(WRITEPATH . 'exports/')) {
                mkdir(WRITEPATH . 'exports/', 0755, true);
            }
            
            $file = fopen($filepath, 'w');
            
            // Encabezados
            fputcsv($file, [
                'ID',
                'Cédula',
                'Nombres',
                'Apellidos',
                'Tipo Empleado',
                'Departamento',
                'Fecha Inicio',
                'Fecha Fin',
                'Estado',
                'Observaciones'
            ]);
            
            // Datos
            foreach ($empleadosInactivos as $empleado) {
                fputcsv($file, [
                    $empleado['id_empleado'],
                    $empleado['cedula'],
                    $empleado['nombres'],
                    $empleado['apellidos'],
                    $empleado['tipo_empleado'],
                    $empleado['departamento'],
                    $empleado['fecha_inicio'],
                    $empleado['fecha_fin'],
                    $empleado['estado'],
                    $empleado['observaciones']
                ]);
            }
            
            fclose($file);

            // Registrar log
            $logModel = new LogSistemaModel();
            $logModel->registrarLog(
                session()->get('id_usuario'),
                'EXPORTAR_EMPLEADOS_INACTIVOS',
                'REPORTES',
                'Exportación de empleados inactivos a CSV'
            );
            
            return $this->response->download($filepath, null, true);
            
        } catch (\Exception $e) {
            log_message('error', 'Error exportando empleados inactivos: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al exportar el reporte');
        }
    }

    // ==================== GESTIÓN DE CAPACITACIONES ====================

    /**
     * Obtener capacitaciones para DataTable
     */
    public function obtenerCapacitaciones()
    {
        if (!$this->request->isAJAX()) {
            return redirect()->back();
        }

        try {
            $capacitacionModel = new CapacitacionModel();
            $capacitaciones = $capacitacionModel->getCapacitacionesConEstadisticas();

            $data = [];
            foreach ($capacitaciones as $capacitacion) {
                $data[] = [
                    'id_capacitacion' => $capacitacion['id_capacitacion'],
                    'titulo' => $capacitacion['titulo'],
                    'descripcion' => $capacitacion['descripcion'] ?? 'Sin descripción',
                    'fecha_inicio' => date('d/m/Y', strtotime($capacitacion['fecha_inicio'])),
                    'fecha_fin' => date('d/m/Y', strtotime($capacitacion['fecha_fin'])),
                    'cupo_maximo' => $capacitacion['cupo_maximo'],
                    'inscritos' => $capacitacion['total_inscritos'] ?? 0,
                    'estado' => $this->obtenerEstadoCapacitacion($capacitacion),
                    'acciones' => $this->generarAccionesCapacitacion($capacitacion)
                ];
            }

            return $this->response->setJSON([
                'data' => $data
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Error obteniendo capacitaciones: ' . $e->getMessage());
            return $this->response->setJSON([
                'error' => 'Error al obtener capacitaciones'
            ]);
        }
    }

    /**
     * Obtener capacitación específica
     */
    public function obtenerCapacitacion($idCapacitacion)
    {
        if (!$this->request->isAJAX()) {
            return redirect()->back();
        }

        try {
            $capacitacionModel = new CapacitacionModel();
            $capacitacion = $capacitacionModel->getCapacitacionCompleta($idCapacitacion);

            if (!$capacitacion) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Capacitación no encontrada'
                ]);
            }

            return $this->response->setJSON([
                'success' => true,
                'capacitacion' => $capacitacion
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Error obteniendo capacitación: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al obtener la capacitación'
            ]);
        }
    }

    /**
     * Crear nueva capacitación
     */
    public function crearCapacitacion()
    {
        if (!$this->request->isAJAX()) {
            return redirect()->back();
        }

        try {
            $rules = [
                'titulo' => 'required|min_length[5]|max_length[200]',
                'descripcion' => 'permit_empty|max_length[1000]',
                'fecha_inicio' => 'required|valid_date',
                'fecha_fin' => 'required|valid_date',
                'cupo_maximo' => 'required|integer|greater_than[0]',
                'instructor' => 'required|min_length[3]|max_length[100]',
                'lugar' => 'required|min_length[3]|max_length[200]',
                'horario' => 'required|min_length[5]|max_length[100]'
            ];

            if (!$this->validate($rules)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Datos de entrada inválidos',
                    'errors' => $this->validator->getErrors()
                ]);
            }

            // Validar que fecha_fin sea posterior a fecha_inicio
            $fechaInicio = strtotime($this->request->getPost('fecha_inicio'));
            $fechaFin = strtotime($this->request->getPost('fecha_fin'));
            
            if ($fechaFin <= $fechaInicio) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'La fecha de fin debe ser posterior a la fecha de inicio'
                ]);
            }

            $capacitacionModel = new CapacitacionModel();
            $data = [
                'titulo' => $this->request->getPost('titulo'),
                'descripcion' => $this->request->getPost('descripcion'),
                'fecha_inicio' => $this->request->getPost('fecha_inicio'),
                'fecha_fin' => $this->request->getPost('fecha_fin'),
                'cupo_maximo' => $this->request->getPost('cupo_maximo'),
                'instructor' => $this->request->getPost('instructor'),
                'lugar' => $this->request->getPost('lugar'),
                'horario' => $this->request->getPost('horario'),
                'estado' => 'PROGRAMADA',
                'creado_por' => session()->get('id_usuario'),
                'fecha_creacion' => date('Y-m-d H:i:s')
            ];

            if ($capacitacionModel->insert($data)) {
                // Registrar log
                $logModel = new LogSistemaModel();
                $logModel->registrarLog(
                    session()->get('id_usuario'),
                    'CREAR_CAPACITACION',
                    'CAPACITACIONES',
                    "Capacitación creada: {$data['titulo']}"
                );

                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Capacitación creada correctamente'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Error al crear la capacitación'
                ]);
            }

        } catch (\Exception $e) {
            log_message('error', 'Error creando capacitación: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error interno del servidor'
            ]);
        }
    }

    /**
     * Actualizar capacitación
     */
    public function actualizarCapacitacion()
    {
        if (!$this->request->isAJAX()) {
            return redirect()->back();
        }

        try {
            $idCapacitacion = $this->request->getPost('id_capacitacion');
            
            $rules = [
                'id_capacitacion' => 'required|integer',
                'titulo' => 'required|min_length[5]|max_length[200]',
                'descripcion' => 'permit_empty|max_length[1000]',
                'fecha_inicio' => 'required|valid_date',
                'fecha_fin' => 'required|valid_date',
                'cupo_maximo' => 'required|integer|greater_than[0]',
                'instructor' => 'required|min_length[3]|max_length[100]',
                'lugar' => 'required|min_length[3]|max_length[200]',
                'horario' => 'required|min_length[5]|max_length[100]'
            ];

            if (!$this->validate($rules)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Datos de entrada inválidos',
                    'errors' => $this->validator->getErrors()
                ]);
            }

            // Validar que fecha_fin sea posterior a fecha_inicio
            $fechaInicio = strtotime($this->request->getPost('fecha_inicio'));
            $fechaFin = strtotime($this->request->getPost('fecha_fin'));
            
            if ($fechaFin <= $fechaInicio) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'La fecha de fin debe ser posterior a la fecha de inicio'
                ]);
            }

            $capacitacionModel = new CapacitacionModel();
            $capacitacion = $capacitacionModel->find($idCapacitacion);
            
            if (!$capacitacion) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Capacitación no encontrada'
                ]);
            }

            // Verificar que no se pueda editar si ya tiene inscritos
            $totalInscritos = $capacitacionModel->contarInscritos($idCapacitacion);
            if ($totalInscritos > 0) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'No se puede editar una capacitación que ya tiene inscritos'
                ]);
            }

            $data = [
                'titulo' => $this->request->getPost('titulo'),
                'descripcion' => $this->request->getPost('descripcion'),
                'fecha_inicio' => $this->request->getPost('fecha_inicio'),
                'fecha_fin' => $this->request->getPost('fecha_fin'),
                'cupo_maximo' => $this->request->getPost('cupo_maximo'),
                'instructor' => $this->request->getPost('instructor'),
                'lugar' => $this->request->getPost('lugar'),
                'horario' => $this->request->getPost('horario')
            ];

            if ($capacitacionModel->update($idCapacitacion, $data)) {
                // Registrar log
                $logModel = new LogSistemaModel();
                $logModel->registrarLog(
                    session()->get('id_usuario'),
                    'EDITAR_CAPACITACION',
                    'CAPACITACIONES',
                    "Capacitación editada: {$data['titulo']}"
                );

                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Capacitación actualizada correctamente'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Error al actualizar la capacitación'
                ]);
            }

        } catch (\Exception $e) {
            log_message('error', 'Error editando capacitación: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error interno del servidor'
            ]);
        }
    }

    /**
     * Cancelar capacitación
     */
    public function cancelarCapacitacion()
    {
        if (!$this->request->isAJAX()) {
            return redirect()->back();
        }

        try {
            $idCapacitacion = $this->request->getPost('id_capacitacion');
            $motivo = $this->request->getPost('motivo');

            if (empty($motivo)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Debe especificar un motivo para cancelar la capacitación'
                ]);
            }

            $capacitacionModel = new CapacitacionModel();
            $capacitacion = $capacitacionModel->find($idCapacitacion);
            
            if (!$capacitacion) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Capacitación no encontrada'
                ]);
            }

            // Verificar que no esté ya cancelada
            if ($capacitacion['estado'] === 'CANCELADA') {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'La capacitación ya está cancelada'
                ]);
            }

            if ($capacitacionModel->update($idCapacitacion, [
                'estado' => 'CANCELADA',
                'motivo_cancelacion' => $motivo,
                'fecha_cancelacion' => date('Y-m-d H:i:s')
            ])) {
                // Registrar log
                $logModel = new LogSistemaModel();
                $logModel->registrarLog(
                    session()->get('id_usuario'),
                    'CANCELAR_CAPACITACION',
                    'CAPACITACIONES',
                    "Capacitación cancelada: {$capacitacion['titulo']} - Motivo: {$motivo}"
                );

                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Capacitación cancelada correctamente'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Error al cancelar la capacitación'
                ]);
            }

        } catch (\Exception $e) {
            log_message('error', 'Error cancelando capacitación: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error interno del servidor'
            ]);
        }
    }

    /**
     * Obtener estado de capacitación
     */
    private function obtenerEstadoCapacitacion($capacitacion)
    {
        $fechaActual = time();
        $fechaInicio = strtotime($capacitacion['fecha_inicio']);
        $fechaFin = strtotime($capacitacion['fecha_fin']);

        if ($capacitacion['estado'] === 'CANCELADA') {
            return '<span class="badge badge-danger">Cancelada</span>';
        }

        if ($fechaActual < $fechaInicio) {
            return '<span class="badge badge-info">Programada</span>';
        } elseif ($fechaActual >= $fechaInicio && $fechaActual <= $fechaFin) {
            return '<span class="badge badge-success">En Curso</span>';
        } else {
            return '<span class="badge badge-secondary">Finalizada</span>';
        }
    }

    /**
     * Cambiar estado de capacitación
     */
    public function cambiarEstadoCapacitacion()
    {
        if (!$this->request->isAJAX()) {
            return redirect()->back();
        }

        try {
            $idCapacitacion = $this->request->getPost('id_capacitacion');
            $nuevoEstado = $this->request->getPost('estado');

            $estadosValidos = ['PROGRAMADA', 'EN_CURSO', 'COMPLETADA', 'CANCELADA', 'INACTIVA'];
            
            if (!in_array($nuevoEstado, $estadosValidos)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Estado no válido'
                ]);
            }

            $capacitacionModel = new CapacitacionModel();
            $capacitacion = $capacitacionModel->find($idCapacitacion);
            
            if (!$capacitacion) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Capacitación no encontrada'
                ]);
            }

            if ($capacitacionModel->update($idCapacitacion, ['estado' => $nuevoEstado])) {
                // Registrar log
                $logModel = new LogSistemaModel();
                $logModel->registrarLog(
                    session()->get('id_usuario'),
                    'CAMBIAR_ESTADO_CAPACITACION',
                    'CAPACITACIONES',
                    "Estado cambiado a {$nuevoEstado} para capacitación: {$capacitacion['titulo']}"
                );

                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Estado de capacitación actualizado correctamente'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Error al actualizar el estado'
                ]);
            }

        } catch (\Exception $e) {
            log_message('error', 'Error cambiando estado de capacitación: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error interno del servidor'
            ]);
        }
    }

    /**
     * Eliminar capacitación
     */
    public function eliminarCapacitacion()
    {
        if (!$this->request->isAJAX()) {
            return redirect()->back();
        }

        try {
            $idCapacitacion = $this->request->getPost('id_capacitacion');

            $capacitacionModel = new CapacitacionModel();
            $capacitacion = $capacitacionModel->find($idCapacitacion);
            
            if (!$capacitacion) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Capacitación no encontrada'
                ]);
            }

            // Verificar que no tenga inscritos
            $totalInscritos = $capacitacionModel->contarInscritos($idCapacitacion);
            if ($totalInscritos > 0) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'No se puede eliminar una capacitación que tiene inscritos'
                ]);
            }

            // Verificar que no esté en curso o finalizada
            if (in_array($capacitacion['estado'], ['EN_CURSO', 'COMPLETADA'])) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'No se puede eliminar una capacitación en curso o finalizada'
                ]);
            }

            if ($capacitacionModel->delete($idCapacitacion)) {
                // Registrar log
                $logModel = new LogSistemaModel();
                $logModel->registrarLog(
                    session()->get('id_usuario'),
                    'ELIMINAR_CAPACITACION',
                    'CAPACITACIONES',
                    "Capacitación eliminada: {$capacitacion['titulo']}"
                );

                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Capacitación eliminada correctamente'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Error al eliminar la capacitación'
                ]);
            }

        } catch (\Exception $e) {
            log_message('error', 'Error eliminando capacitación: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error interno del servidor'
            ]);
                }
    }

    /**
     * Generar HTML de acciones para cada capacitación
     */
    private function generarAccionesCapacitacion($capacitacion)
    {
        $idCapacitacion = $capacitacion['id_capacitacion'];
        $estado = $capacitacion['estado'];
        $fechaActual = time();
        $fechaInicio = strtotime($capacitacion['fecha_inicio']);
        
        $acciones = '<div class="btn-group" role="group">';
        
        // Botón ver detalles
        $acciones .= '<button type="button" class="btn btn-sm btn-info" onclick="verCapacitacion(' . $idCapacitacion . ')" title="Ver Detalles">
                        <i class="fas fa-eye"></i>
                      </button>';
        
        // Botón editar (solo si está programada y no ha comenzado)
        if ($estado === 'PROGRAMADA' && $fechaActual < $fechaInicio) {
            $acciones .= '<button type="button" class="btn btn-sm btn-primary" onclick="editarCapacitacion(' . $idCapacitacion . ')" title="Editar">
                            <i class="fas fa-edit"></i>
                          </button>';
        }
        
        // Botón cancelar (solo si está programada)
        if ($estado === 'PROGRAMADA') {
            $acciones .= '<button type="button" class="btn btn-sm btn-warning" onclick="cancelarCapacitacion(' . $idCapacitacion . ')" title="Cancelar">
                            <i class="fas fa-times"></i>
                          </button>';
        }
        
        // Botón ver inscritos
        $acciones .= '<button type="button" class="btn btn-sm btn-success" onclick="verInscritos(' . $idCapacitacion . ')" title="Ver Inscritos">
                        <i class="fas fa-users"></i>
                      </button>';
        
        $acciones .= '</div>';
        
        return $acciones;
    }

    // ==================== GESTIÓN DE TÍTULOS ACADÉMICOS ====================

    /**
     * Obtener títulos académicos para DataTable
     */
    public function obtenerTitulosAcademicos()
    {
        if (!$this->request->isAJAX()) {
            return redirect()->back();
        }

        try {
            $tituloModel = new TituloAcademicoModel();
            $titulos = $tituloModel->getTitulosConEstadisticas();

            $data = [];
            foreach ($titulos as $titulo) {
                $data[] = [
                    'id_titulo' => $titulo['id_titulo'],
                    'nombre_titulo' => $titulo['nombre_titulo'],
                    'nivel_academico' => $titulo['nivel_academico'],
                    'institucion' => $titulo['institucion'],
                    'descripcion' => $titulo['descripcion'] ?? 'Sin descripción',
                    'total_empleados' => $titulo['total_empleados'] ?? 0,
                    'activo' => $titulo['activo'] ? 'Activo' : 'Inactivo',
                    'acciones' => $this->generarAccionesTitulo($titulo)
                ];
            }

            return $this->response->setJSON([
                'data' => $data
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Error obteniendo títulos académicos: ' . $e->getMessage());
            return $this->response->setJSON([
                'error' => 'Error al obtener títulos académicos'
            ]);
        }
    }

    /**
     * Obtener título académico específico
     */
    public function obtenerTituloAcademico($idTitulo)
    {
        if (!$this->request->isAJAX()) {
            return redirect()->back();
        }

        try {
            $tituloModel = new TituloAcademicoModel();
            $titulo = $tituloModel->find($idTitulo);

            if (!$titulo) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Título académico no encontrado'
                ]);
            }

            return $this->response->setJSON([
                'success' => true,
                'titulo' => $titulo
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Error obteniendo título académico: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al obtener el título académico'
            ]);
        }
    }

    /**
     * Crear nuevo título académico
     */
    public function crearTituloAcademico()
    {
        if (!$this->request->isAJAX()) {
            return redirect()->back();
        }

        try {
            $rules = [
                'nombre_titulo' => 'required|min_length[3]|max_length[200]|is_unique[catalogo_titulos_academicos.nombre_titulo]',
                'nivel_academico' => 'required|in_list[TECNICO,LICENCIATURA,MAESTRIA,DOCTORADO]',
                'institucion' => 'required|min_length[3]|max_length[200]',
                'descripcion' => 'permit_empty|max_length[500]'
            ];

            if (!$this->validate($rules)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Datos de entrada inválidos',
                    'errors' => $this->validator->getErrors()
                ]);
            }

            $tituloModel = new TituloAcademicoModel();
            $data = [
                'nombre_titulo' => $this->request->getPost('nombre_titulo'),
                'nivel_academico' => $this->request->getPost('nivel_academico'),
                'institucion' => $this->request->getPost('institucion'),
                'descripcion' => $this->request->getPost('descripcion'),
                'activo' => 1
            ];

            if ($tituloModel->insert($data)) {
                // Registrar log
                $logModel = new LogSistemaModel();
                $logModel->registrarLog(
                    session()->get('id_usuario'),
                    'CREAR_TITULO_ACADEMICO',
                    'TITULOS_ACADEMICOS',
                    "Título académico creado: {$data['nombre_titulo']}"
                );

                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Título académico creado correctamente'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Error al crear el título académico'
                ]);
            }

        } catch (\Exception $e) {
            log_message('error', 'Error creando título académico: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error interno del servidor'
            ]);
        }
    }

    /**
     * Editar título académico
     */
    public function editarTituloAcademico()
    {
        if (!$this->request->isAJAX()) {
            return redirect()->back();
        }

        try {
            $idTitulo = $this->request->getPost('id_titulo');
            
            $rules = [
                'id_titulo' => 'required|integer',
                'nombre_titulo' => "required|min_length[3]|max_length[200]|is_unique[catalogo_titulos_academicos.nombre_titulo,id_titulo,{$idTitulo}]",
                'nivel_academico' => 'required|in_list[TECNICO,LICENCIATURA,MAESTRIA,DOCTORADO]',
                'institucion' => 'required|min_length[3]|max_length[200]',
                'descripcion' => 'permit_empty|max_length[500]'
            ];

            if (!$this->validate($rules)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Datos de entrada inválidos',
                    'errors' => $this->validator->getErrors()
                ]);
            }

            $tituloModel = new TituloAcademicoModel();
            $titulo = $tituloModel->find($idTitulo);
            
            if (!$titulo) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Título académico no encontrado'
                ]);
            }

            $data = [
                'nombre_titulo' => $this->request->getPost('nombre_titulo'),
                'nivel_academico' => $this->request->getPost('nivel_academico'),
                'institucion' => $this->request->getPost('institucion'),
                'descripcion' => $this->request->getPost('descripcion')
            ];

            if ($tituloModel->update($idTitulo, $data)) {
                // Registrar log
                $logModel = new LogSistemaModel();
                $logModel->registrarLog(
                    session()->get('id_usuario'),
                    'EDITAR_TITULO_ACADEMICO',
                    'TITULOS_ACADEMICOS',
                    "Título académico editado: {$data['nombre_titulo']}"
                );

                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Título académico actualizado correctamente'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Error al actualizar el título académico'
                ]);
            }

        } catch (\Exception $e) {
            log_message('error', 'Error editando título académico: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error interno del servidor'
            ]);
        }
    }

    /**
     * Deshabilitar título académico
     */
    public function deshabilitarTituloAcademico()
    {
        if (!$this->request->isAJAX()) {
            return redirect()->back();
        }

        try {
            $idTitulo = $this->request->getPost('id_titulo');
            $tituloModel = new TituloAcademicoModel();
            $titulo = $tituloModel->find($idTitulo);
            
            if (!$titulo) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Título académico no encontrado'
                ]);
            }

            // Verificar si hay empleados usando este título
            $empleadosConTitulo = $tituloModel->contarEmpleadosConTitulo($idTitulo);
            if ($empleadosConTitulo > 0) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => "No se puede deshabilitar el título porque tiene {$empleadosConTitulo} empleado(s) asignado(s)"
                ]);
            }

            if ($tituloModel->update($idTitulo, ['activo' => 0])) {
                // Registrar log
                $logModel = new LogSistemaModel();
                $logModel->registrarLog(
                    session()->get('id_usuario'),
                    'DESHABILITAR_TITULO_ACADEMICO',
                    'TITULOS_ACADEMICOS',
                    "Título académico deshabilitado: {$titulo['nombre_titulo']}"
                );

                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Título académico deshabilitado correctamente'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Error al deshabilitar el título académico'
                ]);
            }

        } catch (\Exception $e) {
            log_message('error', 'Error deshabilitando título académico: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error interno del servidor'
            ]);
        }
    }

    /**
     * Habilitar título académico
     */
    public function habilitarTituloAcademico()
    {
        if (!$this->request->isAJAX()) {
            return redirect()->back();
        }

        try {
            $idTitulo = $this->request->getPost('id_titulo');
            $tituloModel = new TituloAcademicoModel();
            
            if ($tituloModel->update($idTitulo, ['activo' => 1])) {
                $titulo = $tituloModel->find($idTitulo);
                
                // Registrar log
                $logModel = new LogSistemaModel();
                $logModel->registrarLog(
                    session()->get('id_usuario'),
                    'HABILITAR_TITULO_ACADEMICO',
                    'TITULOS_ACADEMICOS',
                    "Título académico habilitado: {$titulo['nombre_titulo']}"
                );

                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Título académico habilitado correctamente'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Error al habilitar el título académico'
                ]);
            }

        } catch (\Exception $e) {
            log_message('error', 'Error habilitando título académico: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error interno del servidor'
            ]);
        }
    }

    /**
     * Generar HTML de acciones para cada título académico
     */
    private function generarAccionesTitulo($titulo)
    {
        $idTitulo = $titulo['id_titulo'];
        $esActivo = $titulo['activo'];
        
        $acciones = '<div class="btn-group" role="group">';
        
        // Botón editar
        $acciones .= '<button type="button" class="btn btn-sm btn-primary" onclick="editarTitulo(' . $idTitulo . ')" title="Editar">
                        <i class="fas fa-edit"></i>
                      </button>';
        
        // Botón habilitar/deshabilitar
        if ($esActivo) {
            $acciones .= '<button type="button" class="btn btn-sm btn-warning" onclick="deshabilitarTitulo(' . $idTitulo . ')" title="Deshabilitar">
                            <i class="fas fa-times"></i>
                          </button>';
        } else {
            $acciones .= '<button type="button" class="btn btn-sm btn-success" onclick="habilitarTitulo(' . $idTitulo . ')" title="Habilitar">
                            <i class="fas fa-check"></i>
                          </button>';
        }
        
        // Botón ver empleados
        $acciones .= '<button type="button" class="btn btn-sm btn-info" onclick="verEmpleadosTitulo(' . $idTitulo . ')" title="Ver Empleados">
                        <i class="fas fa-users"></i>
                      </button>';
        
        $acciones .= '</div>';
        
        return $acciones;
    }
}
