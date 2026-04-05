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
use App\Models\EvaluacionParModel;
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
        $db         = \Config\Database::connect();
        $anioActual = date('Y');
        $mesActual  = date('Y-m');

        // ── Tarjetas de resumen ───────────────────────────────────────────────
        $totalEmpleados          = $db->table('empleados')->countAllResults();
        $empleadosActivos        = $db->table('empleados')->where('estado', 'Activo')->countAllResults();

        // Inasistencias injustificadas del mes actual
        $inasistenciasMesActual  = $db->table('inasistencias')
            ->where('justificada', 0)
            ->like('fecha_inasistencia', $mesActual, 'after')
            ->countAllResults();

        // Solicitudes pendientes (todos los tipos)
        $solicitudesPendientes   = $db->table('solicitudes')
            ->where('estado', 'Pendiente')
            ->where('activo', 1)
            ->countAllResults();

        // Puestos abiertos
        $puestosAbiertos         = $db->table('puestos')
            ->where('estado', 'Abierto')
            ->where('activo', 1)
            ->countAllResults();

        // Capacitaciones activas
        $capacitacionesActivas   = $db->table('capacitaciones')
            ->where('estado', 'ACTIVA')
            ->countAllResults();

        // ── Últimas 8 inasistencias ───────────────────────────────────────────
        $ultimasInasistencias = $db->table('inasistencias i')
            ->select('e.nombres, e.apellidos, i.fecha_inasistencia, i.tipo_inasistencia, i.justificada, i.motivo')
            ->join('empleados e', 'e.id_empleado = i.empleado_id')
            ->orderBy('i.fecha_inasistencia', 'DESC')
            ->limit(8)
            ->get()->getResultArray();

        // ── Últimas 5 solicitudes recibidas ──────────────────────────────────
        $ultimasSolicitudes = $db->table('solicitudes s')
            ->select('s.tipo_solicitud, s.titulo, s.estado, s.fecha_solicitud, e.nombres, e.apellidos')
            ->join('empleados e', 'e.id_empleado = s.id_empleado', 'left')
            ->where('s.activo', 1)
            ->orderBy('s.fecha_solicitud', 'DESC')
            ->limit(5)
            ->get()->getResultArray();

        // ── Gráfico 1: Empleados por departamento (donut) ─────────────────────
        $empPorDepto = $db->table('empleados')
            ->select('COALESCE(departamento, "Sin asignar") as departamento, COUNT(*) as total')
            ->where('estado', 'Activo')
            ->groupBy('departamento')
            ->orderBy('total', 'DESC')
            ->get()->getResultArray();

        $chartDeptLabels = array_column($empPorDepto, 'departamento');
        $chartDeptData   = array_map('intval', array_column($empPorDepto, 'total'));

        // ── Gráfico 2: Inasistencias por mes del año actual (barras) ─────────
        $inaPorMes = $db->query("
            SELECT MONTH(fecha_inasistencia) AS mes, COUNT(*) AS total
            FROM inasistencias
            WHERE YEAR(fecha_inasistencia) = ?
            GROUP BY MONTH(fecha_inasistencia)
            ORDER BY mes
        ", [$anioActual])->getResultArray();

        $chartMeses = array_fill(0, 12, 0);
        foreach ($inaPorMes as $row) {
            $chartMeses[(int)$row['mes'] - 1] = (int)$row['total'];
        }

        // ── Gráfico 3: Estado de solicitudes (donut) ─────────────────────────
        $solPorEstado = $db->table('solicitudes')
            ->select('estado, COUNT(*) as total')
            ->where('activo', 1)
            ->groupBy('estado')
            ->get()->getResultArray();

        $chartSolLabels = array_column($solPorEstado, 'estado');
        $chartSolData   = array_map('intval', array_column($solPorEstado, 'total'));

        $data = [
            'titulo'  => 'Dashboard Admin Talento Humano',
            'usuario' => [
                'nombres'   => session()->get('nombres'),
                'apellidos' => session()->get('apellidos'),
                'rol'       => session()->get('nombre_rol'),
            ],
            // Tarjetas
            'totalEmpleados'         => $totalEmpleados,
            'empleadosActivos'       => $empleadosActivos,
            'inasistenciasMesActual' => $inasistenciasMesActual,
            'solicitudesPendientes'  => $solicitudesPendientes,
            'puestosAbiertos'        => $puestosAbiertos,
            'capacitacionesActivas'  => $capacitacionesActivas,
            // Tablas
            'ultimasInasistencias'   => $ultimasInasistencias,
            'ultimasSolicitudes'     => $ultimasSolicitudes,
            // Gráficos (json_encode aquí para no repetirlo en la vista)
            'chartDeptLabels'        => json_encode(empty($chartDeptLabels) ? ['Sin datos'] : $chartDeptLabels),
            'chartDeptData'          => json_encode(empty($chartDeptData)   ? [0]           : $chartDeptData),
            'chartInasistencias'     => json_encode($chartMeses),
            'chartSolLabels'         => json_encode(empty($chartSolLabels)  ? ['Sin datos'] : $chartSolLabels),
            'chartSolData'           => json_encode(empty($chartSolData)    ? [0]           : $chartSolData),
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
     * Eliminar (Deshabilitar) títulos masivamente
     */
    public function eliminarTitulosAcademicosMasivo()
    {
        if (!$this->request->isAJAX()) {
            return redirect()->back();
        }

        try {
            $ids = $this->request->getPost('ids');
            
            if (empty($ids) || !is_array($ids)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'No se seleccionaron títulos para eliminar'
                ]);
            }

            $tituloModel = new \App\Models\TituloAcademicoModel();
            $logModel = new \App\Models\LogSistemaModel();
            $idUsuario = session()->get('id_usuario');

            $db = \Config\Database::connect();
            $db->transStart();

            $eliminados = 0;
            $errores = 0;

            foreach ($ids as $idTitulo) {
                $titulo = $tituloModel->find($idTitulo);
                
                if (!$titulo) {
                    $errores++;
                    continue;
                }

                // Verificar si hay empleados usando este título, en cuyo caso omitir
                if ($tituloModel->contarEmpleadosConTitulo($idTitulo) > 0) {
                    $errores++;
                    continue;
                }

                // El usuario quiere "Eliminar" pero la DB maneja "Deshabilitar" (activo = 0)
                // Usamos la misma lógica original o delete() si es hard delete. Según el original es update()
                if ($tituloModel->update($idTitulo, ['activo' => 0])) {
                    $logModel->registrarLog(
                        $idUsuario,
                        'DESHABILITAR_TITULO_ACADEMICO_MASIVO',
                        'TITULOS_ACADEMICOS',
                        "Título académico deshabilitado en lote: {$titulo['nombre_titulo']}"
                    );
                    $eliminados++;
                } else {
                    $errores++;
                }
            }

            $db->transComplete();

            if ($db->transStatus() === false) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Error al ejecutar la eliminación masiva. Operación revertida.'
                ]);
            }

            if ($eliminados === 0 && $errores > 0) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'No se eliminó ningún título. Asegúrese de que no estén asignados a ningún empleado activo.'
                ]);
            }

            $mensajeExtra = $errores > 0 ? " (Omitidos/En Uso: {$errores})" : "";

            return $this->response->setJSON([
                'success' => true,
                'message' => "{$eliminados} título(s) eliminado(s) correctamente." . $mensajeExtra
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Error en eliminación masiva de títulos académicos: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error interno del servidor en borrado masivo'
            ]);
        }
    }

    /**
     * Obtener credenciales del empleado en tiempo real desde la BD
     */
    public function obtenerCredenciales($id)
    {
        try {
            $db = \Config\Database::connect();

            // Consulta fresca a la BD con datos en tiempo real
            $resultado = $db->table('empleados e')
                ->select('e.nombres, e.apellidos, u.email, u.cedula, u.activo, u.password_changed, r.nombre_rol')
                ->join('usuarios u', 'u.id_usuario = e.id_usuario')
                ->join('roles r', 'r.id_rol = u.id_rol', 'left')
                ->where('e.id_empleado', $id)
                ->get()
                ->getRowArray();
            
            if (!$resultado) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Empleado no encontrado o sin usuario vinculado'
                ]);
            }
            
            // Determinar estado de la contraseña
            $estadoPassword = $resultado['password_changed'] 
                ? 'El usuario ya cambió su contraseña' 
                : 'Contraseña por defecto (cédula)';

            $credenciales = [
                'nombre_completo' => $resultado['nombres'] . ' ' . $resultado['apellidos'],
                'email' => $resultado['email'],
                'cedula' => $resultado['cedula'],
                'rol' => $resultado['nombre_rol'] ?? 'Empleado',
                'password' => 'Contraseña encriptada por seguridad',
                'estado_password' => $estadoPassword,
                'activo' => $resultado['activo'] ? 'Activo' : 'Inactivo',
                'mensaje' => 'Datos extraídos en tiempo real de la base de datos.'
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
            if (empty($datos['nombres']) || empty($datos['apellidos']) || empty($datos['cedula']) || empty($datos['tipo_empleado']) || empty($datos['email'])) {
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
                    ->select('e.id_usuario, u.cedula as cedula_actual, u.email as email_actual')
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
                }

                // Verificar email duplicado
                $emailExiste = $db->table('usuarios')->where('email', $datos['email'])->where('id_usuario !=', $idUsuario)->get()->getRowArray();
                if ($emailExiste) {
                    return $this->response->setJSON(['success' => false, 'message' => 'El correo electrónico ' . $datos['email'] . ' ya está registrado por otro usuario']);
                }
                
                // Actualizar cédula y email en usuarios
                $db->table('usuarios')->where('id_usuario', $idUsuario)->update([
                    'cedula' => $datos['cedula'],
                    'email' => $datos['email']
                ]);
                
                // Actualizar empleado
                $estado = strtoupper($datos['estado'] ?? 'ACTIVO');
                $empleadoData = [
                    'nombres' => $datos['nombres'],
                    'apellidos' => $datos['apellidos'],
                    'tipo_empleado' => $tipoEmpleado,
                    'departamento' => $datos['departamento'] ?? 'Sin asignar',
                    'fecha_ingreso' => $datos['fecha_ingreso'] ?? date('Y-m-d'),
                    'salario' => $datos['salario'] ?? 0.00,
                    'estado' => $estado,
                    'telefono' => $datos['celular'] ?? ''
                ];
                
                $db->table('empleados')->where('id_empleado', $idEmpleado)->update($empleadoData);

                // Sincronizar usuarios.activo según el nuevo estado
                $activoUsuario = ($estado === 'ACTIVO') ? 1 : 0;
                $db->table('usuarios')->where('id_usuario', $idUsuario)->update(['activo' => $activoUsuario]);
                
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

                // Verificar que el email no exista
                $emailExiste = $db->table('usuarios')->where('email', $datos['email'])->get()->getRowArray();
                if ($emailExiste) {
                    return $this->response->setJSON(['success' => false, 'message' => 'El correo electrónico ' . $datos['email'] . ' ya está registrado']);
                }
                
                // Usar transacción para crear usuario + empleado de forma atómica
                $db->transStart();

                // 1. Crear usuario con la cédula como contraseña por defecto
                $usuarioData = [
                    'cedula' => $datos['cedula'],
                    'email' => $datos['email'],
                    'password_hash' => password_hash($datos['cedula'], PASSWORD_DEFAULT),
                    'id_rol' => 3, // Rol Empleado
                    'activo' => 1,
                    'password_changed' => 0
                ];

                $db->table('usuarios')->insert($usuarioData);
                $idUsuario = $db->insertID();

                // 2. Crear empleado vinculado al usuario
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
                    'telefono' => $datos['celular'] ?? '',
                    'activo' => 1
                ];

                $db->table('empleados')->insert($empleadoData);

                $db->transComplete();

                // Verificar que la transacción fue exitosa
                if ($db->transStatus() === false) {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'Error al crear el empleado. La operación fue revertida.'
                    ]);
                }

                return $this->response->setJSON([
                    'success' => true, 
                    'message' => 'Empleado creado exitosamente. Se han generado las credenciales de acceso.',
                    'id_usuario' => $idUsuario,
                    'tipo' => 'creacion',
                    'credenciales' => [
                        'email' => $datos['email'],
                        'password' => $datos['cedula']
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
     * Eliminar departamentos masivamente
     */
    public function eliminarDepartamentosMasivo()
    {
        if (!$this->request->isAJAX()) {
            return redirect()->back();
        }

        try {
            $ids = $this->request->getPost('ids');
            
            if (empty($ids) || !is_array($ids)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'No se seleccionaron departamentos para eliminar'
                ]);
            }

            $db = \Config\Database::connect();
            $db->transStart();

            $eliminados = 0;
            $errores = 0;

            foreach ($ids as $idDepartamento) {
                // Verificar si el departamento está en uso
                if ($this->departamentoModel->departamentoEnUso($idDepartamento)) {
                    $errores++;
                    continue;
                }

                // Eliminar departamento
                $this->departamentoModel->delete($idDepartamento);
                $eliminados++;
            }

            $db->transComplete();

            if ($db->transStatus() === false) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Error al ejecutar la eliminación en lote. La operación fue revertida.'
                ]);
            }

            if ($eliminados === 0 && $errores > 0) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'No se eliminó ninguno. ' . $errores . ' departamento(s) tienen empleados asignados.'
                ]);
            }

            $mensajeExtra = $errores > 0 ? " (Omitidos: {$errores} en uso)" : "";

            return $this->response->setJSON([
                'success' => true,
                'message' => $eliminados . ' departamento(s) eliminado(s) correctamente.' . $mensajeExtra
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Error en eliminación masiva de departamentos: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error interno del servidor en borrado masivo'
            ]);
        }
    }

    /**
     * Obtener departamentos activos para dropdown
     */
    public function obtenerDepartamentosActivos()
    {
        try {
            $departamentos = $this->departamentoModel->getDepartamentosActivos();
            
            return $this->response->setJSON([
                'success' => true,
                'data' => $departamentos
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
            $datos = $this->request->getJSON(true);
            
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
     * Eliminar puestos masivamente
     */
    public function eliminarPuestosMasivo()
    {
        if (!$this->request->isAJAX()) {
            return redirect()->back();
        }

        try {
            $ids = $this->request->getPost('ids');
            
            if (empty($ids) || !is_array($ids)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'No se seleccionaron puestos para eliminar'
                ]);
            }

            $db = \Config\Database::connect();
            $db->transStart();

            $eliminados = 0;
            $errores = 0;

            foreach ($ids as $idPuesto) {
                // Verificar si el puesto está en uso (tiene empleados o postulantes vinculados)
                if ($this->puestoModel->puestoEnUso($idPuesto)) {
                    $errores++;
                    continue;
                }

                // Eliminar puesto
                $this->puestoModel->delete($idPuesto);
                $eliminados++;
            }

            $db->transComplete();

            if ($db->transStatus() === false) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Error al ejecutar la eliminación en lote. La operación fue revertida.'
                ]);
            }

            if ($eliminados === 0 && $errores > 0) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'No se eliminó ninguno. ' . $errores . ' puesto(s) están en uso (tienen postulantes o empleados).'
                ]);
            }

            $mensajeExtra = $errores > 0 ? " (Omitidos: {$errores} en uso)" : "";

            return $this->response->setJSON([
                'success' => true,
                'message' => $eliminados . ' puesto(s) eliminado(s) correctamente.' . $mensajeExtra
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Error en eliminación masiva de puestos: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error interno del servidor en borrado masivo'
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
            $idPostulante = $this->request->getPost('id_postulante');
            $nuevoEstado = $this->request->getPost('nuevo_estado');
            $notas = $this->request->getPost('notas') ?? '';
            
            if (empty($idPostulante) || empty($nuevoEstado)) {
                return $this->response->setJSON(['success' => false, 'message' => 'ID de postulante y nuevo estado son requeridos']);
            }

            // Validar que el estado sea uno de los permitidos
            $estadosPermitidos = ['Pendiente', 'Aprobada', 'Rechazada', 'Contratado'];
            if (!in_array($nuevoEstado, $estadosPermitidos)) {
                return $this->response->setJSON(['success' => false, 'message' => 'Estado no válido']);
            }

            // Actualizar estado y notas
            $data = [
                'estado_postulacion' => $nuevoEstado,
                'notas_admin' => $notas
            ];

            $this->postulanteModel->update($idPostulante, $data);
            
            // Obtener datos del postulante para la respuesta
            $postulante = $this->postulanteModel->find($idPostulante);

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Estado de postulación actualizado exitosamente',
                'is_contratado' => ($nuevoEstado === 'Contratado'),
                'telefono' => $postulante['telefono'] ?? '',
                'nuevo_estado' => $nuevoEstado
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Error al cambiar estado de postulación: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al cambiar estado: ' . $e->getMessage()
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
        $estados = ['Pendiente', 'Aprobada', 'Rechazada', 'Contratado'];
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
            'totalPostulantes' => $postulanteModel->countAll(),
            'estados' => $estados,
            'puestos' => $puestos,
            'departamentos' => $departamentos,
            'filtros' => [
                'estado' => $estado,
                'puesto' => $puesto,
                'departamento' => $departamento,
                'busqueda' => $busqueda
            ],
            'estadisticas' => $estadisticas,
            'soloLectura' => $this->request->getGet('soloLectura') ? true : false
        ];

        return view('Roles/AdminTH/postulantes', $data);
    }

    /**
     * Eliminar postulante
     */
    public function eliminarPostulante()
    {
        if (!$this->request->is('post')) {
            return $this->response->setJSON(['success' => false, 'message' => 'Método no permitido']);
        }

        try {
            $id = $this->request->getPost('id_postulante');
            
            if (!$id) {
                return $this->response->setJSON(['success' => false, 'message' => 'ID de postulante no proporcionado']);
            }

            $postulanteModel = new \App\Models\PostulanteModel();
            $postulante = $postulanteModel->find($id);

            if (!$postulante) {
                return $this->response->setJSON(['success' => false, 'message' => 'Postulante no encontrado']);
            }

            $postulanteModel->delete($id);

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Postulante eliminado correctamente'
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Error al eliminar postulante: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al eliminar: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Exportar lista de CVs desde Google Drive
     */
    public function exportarCVsDrive()
    {
        try {
            $clientSecretsPath = WRITEPATH . 'client_secrets.json';
            $tokenPath = WRITEPATH . 'token.json';

            if (!file_exists($clientSecretsPath) || !file_exists($tokenPath)) {
                return view('postulacion/error', [
                    'titulo' => 'Drive no conectado',
                    'mensaje' => 'Google Drive no está conectado. Ve a Puestos de Trabajo y haz clic en "Conectar Google Drive".'
                ]);
            }

            $client = new \Google\Client();
            $client->setAuthConfig($clientSecretsPath);
            $client->addScope(\Google\Service\Drive::DRIVE_FILE);

            $accessToken = json_decode(file_get_contents($tokenPath), true);
            $client->setAccessToken($accessToken);

            // Renovar token si expiró
            if ($client->isAccessTokenExpired()) {
                if ($client->getRefreshToken()) {
                    $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
                    file_put_contents($tokenPath, json_encode($client->getAccessToken()));
                } else {
                    return view('postulacion/error', [
                        'titulo' => 'Token expirado',
                        'mensaje' => 'El token de Google Drive expiró. Reconecta desde el panel de Puestos.'
                    ]);
                }
            }

            $driveService = new \Google\Service\Drive($client);

            // Listar archivos en la carpeta de CVs
            $folderId = '1WBBRZjMLvRd0PctXRKM0F6_TIEKIEz8B';
            $results = $driveService->files->listFiles([
                'q' => "'" . $folderId . "' in parents and trashed = false",
                'fields' => 'files(id, name, webViewLink, createdTime, size)',
                'orderBy' => 'createdTime desc',
                'pageSize' => 100
            ]);

            $archivos = [];
            foreach ($results->getFiles() as $file) {
                $archivos[] = [
                    'id' => $file->getId(),
                    'nombre' => $file->getName(),
                    'enlace' => $file->getWebViewLink(),
                    'fecha' => $file->getCreatedTime(),
                    'tamano' => $file->getSize()
                ];
            }

            $data = [
                'titulo' => 'CVs en Google Drive',
                'archivos' => $archivos
            ];

            return view('Roles/AdminTH/exportar_drive', $data);

        } catch (\Exception $e) {
            log_message('error', 'Error al listar CVs de Drive: ' . $e->getMessage());
            return view('postulacion/error', [
                'titulo' => 'Error de Drive',
                'mensaje' => 'Error al conectar con Google Drive: ' . $e->getMessage()
            ]);
        }
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
        $db = \Config\Database::connect();
        $mesActual = date('Y-m');

        // 1. Inasistencias Recientes (últimas 5)
        $recientes = $db->table('inasistencias i')
            ->select('i.*, e.nombres as empleado_nombre, e.apellidos as empleado_apellido, e.tipo_empleado as empleado_tipo, e.departamento')
            ->join('empleados e', 'e.id_empleado = i.empleado_id', 'left')
            ->orderBy('i.fecha_inasistencia', 'DESC')
            ->limit(5)
            ->get()->getResultArray();

        // Limpiar para la vista
        foreach ($recientes as &$r) {
            $r['empleado_nombre'] = trim(($r['empleado_nombre'] ?? '') . ' ' . ($r['empleado_apellido'] ?? ''));
            $r['fecha'] = $r['fecha_inasistencia'];
            $r['tipo_nombre'] = $r['tipo_inasistencia'];
            $r['estado'] = $r['justificada'] ? 'JUSTIFICADA' : 'SIN_JUSTIFICAR';
        }

        // 2. Top Empleados (Este Mes)
        $topEmpleados = $db->table('inasistencias i')
            ->select('i.empleado_id, e.nombres, e.apellidos, e.tipo_empleado as tipo, e.departamento, u.email as correo, COUNT(i.id) as total_inasistencias, SUM(i.justificada) as justificadas')
            ->join('empleados e', 'e.id_empleado = i.empleado_id', 'left')
            ->join('usuarios u', 'u.id_usuario = e.id_usuario', 'left')
            ->like('i.fecha_inasistencia', $mesActual, 'after')
            ->groupBy('i.empleado_id, e.nombres, e.apellidos, e.tipo_empleado, e.departamento, u.email')
            ->orderBy('total_inasistencias', 'DESC')
            ->limit(5)
            ->get()->getResultArray();

        foreach ($topEmpleados as &$e) {
            $e['nombre'] = trim(($e['nombres'] ?? '') . ' ' . ($e['apellidos'] ?? ''));
            $e['sin_justificar'] = $e['total_inasistencias'] - $e['justificadas'];
        }

        // 3. Inasistencias por Departamento
        $deptos = $db->table('inasistencias i')
            ->select('e.departamento, COUNT(i.id) as total')
            ->join('empleados e', 'e.id_empleado = i.empleado_id', 'left')
            ->groupBy('e.departamento')
            ->get()->getResultArray();

        $labelsDeptos = [];
        $valoresDeptos = [];
        foreach ($deptos as $d) {
            $labelsDeptos[] = $d['departamento'] ?? 'Sin asignar';
            $valoresDeptos[] = (int) $d['total'];
        }

        // 4. Tendencia Semanal (Últimos 7 días)
        $fechaHace7Dias = date('Y-m-d', strtotime('-7 days'));
        $tendencia = $db->table('inasistencias')
            ->select('fecha_inasistencia, COUNT(id) as total')
            ->where('fecha_inasistencia >', $fechaHace7Dias)
            ->groupBy('fecha_inasistencia')
            ->orderBy('fecha_inasistencia', 'ASC')
            ->get()->getResultArray();

        $labelsTendencia = [];
        $valoresTendencia = [];
        // Rellenar ceros si faltan días
        for ($i = 6; $i >= 0; $i--) {
            $diaStr = date('Y-m-d', strtotime("-{$i} days"));
            $labelsTendencia[] = date('d/m', strtotime("-{$i} days"));
            
            $totalDia = 0;
            foreach ($tendencia as $t) {
                if ($t['fecha_inasistencia'] === $diaStr) {
                    $totalDia = (int) $t['total'];
                    break;
                }
            }
            $valoresTendencia[] = $totalDia;
        }

        $data = [
            'titulo' => 'Gestión de Inasistencias',
            'usuario' => [
                'nombres' => session()->get('nombres'),
                'apellidos' => session()->get('apellidos'),
                'rol' => session()->get('nombre_rol')
            ],
            'inasistencias_recientes' => $recientes,
            'empleados_criticos' => $topEmpleados,
            'graficos' => [
                'departamentos' => [
                    'labels' => empty($labelsDeptos) ? ['Sin Datos'] : $labelsDeptos,
                    'valores' => empty($valoresDeptos) ? [1] : $valoresDeptos
                ],
                'tendencia' => [
                    'labels' => $labelsTendencia,
                    'valores' => $valoresTendencia
                ]
            ],
            // Stats para los recuadros de arriba (opcional pero bueno tenerlos reales)
            'estadisticas' => [
                'total' => $db->table('inasistencias')->countAllResults(),
                'pendientes' => $db->table('inasistencias')->where('justificada', 0)->where('tipo_inasistencia', 'Permiso')->countAllResults(),
                'sin_justificar' => $db->table('inasistencias')->where('justificada', 0)->countAllResults(),
                'tasa_justificacion' => 0 // dummy for now, requires calculation
            ]
        ];

        // Calcular tasa si hay total
        if ($data['estadisticas']['total'] > 0) {
            $justificadas = $db->table('inasistencias')->where('justificada', 1)->countAllResults();
            $data['estadisticas']['tasa_justificacion'] = round(($justificadas / $data['estadisticas']['total']) * 100);
        }

        return view('Roles/AdminTH/inasistencias/dashboard', $data);
    }

    /**
     * Listar todas las inasistencias (Página "Ver todas")
     */
    public function listarInasistencias()
    {
        $db = \Config\Database::connect();
        
        $inasistencias = $db->table('inasistencias i')
            ->select('i.*, e.nombres, e.apellidos, e.departamento, e.tipo_empleado')
            ->join('empleados e', 'e.id_empleado = i.empleado_id', 'left')
            ->orderBy('i.fecha_inasistencia', 'DESC')
            ->get()->getResultArray();

        $data = [
            'titulo' => 'Historial de Inasistencias',
            'usuario' => [
                'nombres' => session()->get('nombres'),
                'apellidos' => session()->get('apellidos'),
                'rol' => session()->get('nombre_rol')
            ],
            'inasistencias' => $inasistencias
        ];

        return view('Roles/AdminTH/inasistencias/index', $data);
    }

    /**
     * Ver Detalles Inasistencia JSON (AJAX) - Para Modal
     */
    public function detalles($id)
    {
        $db = \Config\Database::connect();
        $inasistencia = $db->table('inasistencias i')
            ->select('i.*, e.nombres, e.apellidos, e.departamento, e.tipo_empleado')
            ->join('empleados e', 'e.id_empleado = i.empleado_id', 'left')
            ->where('i.id', $id)
            ->get()->getRowArray();

        if (!$inasistencia) {
            return $this->response->setJSON(['success' => false, 'message' => 'Inasistencia no encontrada.']);
        }

        // Formatear si es necesario
        $inasistencia['fecha_inasistencia'] = date('d/m/Y', strtotime($inasistencia['fecha_inasistencia']));
        if ($inasistencia['hora_inasistencia']) {
            $inasistencia['hora_inasistencia'] = date('H:i', strtotime($inasistencia['hora_inasistencia']));
        }

        return $this->response->setJSON(['success' => true, 'inasistencia' => $inasistencia]);
    }

    /**
     * Eliminar Inasistencia (AJAX DELETE)
     */
    public function eliminar($id)
    {
        try {
            $inasistenciaModel = new \App\Models\InasistenciaModel();
            
            // Verificar si existe
            $registro = $inasistenciaModel->find($id);
            if (!$registro) {
                return $this->response->setJSON(['success' => false, 'message' => 'Registro no encontrado.']);
            }

            // Opcional: solo permitir eliminar si no está justificada, o según negocio. 
            // Para admin, usualmente pueden eliminar cualquiera, se procede directo.
            
            $eliminado = $inasistenciaModel->delete($id);
            
            if ($eliminado) {
                return $this->response->setJSON(['success' => true, 'message' => 'Inasistencia eliminada correctamente.']);
            } else {
                return $this->response->setJSON(['success' => false, 'message' => 'No se pudo eliminar el registro.']);
            }
        } catch (\Exception $e) {
            return $this->response->setJSON(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
    }

    /**
     * Eliminar Inasistencias Masivamente (AJAX POST)
     */
    public function eliminarInasistenciasMasivo()
    {
        if (!$this->request->isAJAX()) return redirect()->back();

        try {
            $ids = $this->request->getPost('ids');
            if (empty($ids) || !is_array($ids)) {
                return $this->response->setJSON(['success' => false, 'message' => 'No se seleccionaron registros.']);
            }

            $inasistenciaModel = new \App\Models\InasistenciaModel();
            $logModel = new \App\Models\LogSistemaModel();
            
            $db = \Config\Database::connect();
            $db->transStart();

            $eliminados = 0;
            $errores = 0;

            foreach ($ids as $id) {
                // Verificar existencia
                $registro = $inasistenciaModel->find($id);
                if (!$registro) {
                    $errores++;
                    continue;
                }

                if ($inasistenciaModel->delete($id)) {
                    $logModel->registrarLog(
                        session()->get('id_usuario'),
                        'ELIMINAR_INASISTENCIA_MASIVA',
                        'INASISTENCIAS',
                        "Inasistencia eliminada en lote (ID: {$id}, Motivo: {$registro['motivo']})"
                    );
                    $eliminados++;
                } else {
                    $errores++;
                }
            }

            $db->transComplete();

            if ($db->transStatus() === false) {
                return $this->response->setJSON(['success' => false, 'message' => 'Error de base de datos durante el borrado.']);
            }

            if ($eliminados === 0 && $errores > 0) {
                return $this->response->setJSON(['success' => false, 'message' => 'No se eliminó ningún registro.']);
            }

            $msgExtra = $errores > 0 ? " ({$errores} omitidas)" : "";
            return $this->response->setJSON(['success' => true, 'message' => "{$eliminados} inasistencia(s) eliminada(s) correctamente.{$msgExtra}"]);
            
        } catch (\Exception $e) {
            log_message('error', 'Error en borrado masivo de inasistencias: ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
    }

    /**
     * Vista del formulario para registrar nueva inasistencia
     */
    public function registrarInasistencia()
    {
        $empleadoModel = new EmpleadoModel();

        $data = [
            'titulo' => 'Registrar Nueva Inasistencia',
            'usuario' => [
                'nombres' => session()->get('nombres'),
                'apellidos' => session()->get('apellidos'),
                'rol' => session()->get('nombre_rol')
            ],
            'empleados' => $empleadoModel->where('estado', 'Activo')->orderBy('apellidos', 'ASC')->findAll()
        ];

        return view('Roles/AdminTH/inasistencias/registrar', $data);
    }

    /**
     * Guardar nueva inasistencia (AJAX - POST)
     */
    public function guardarInasistencia()
    {
        try {
            $empleadoId       = $this->request->getPost('empleado_id');
            $fechaInasistencia = $this->request->getPost('fecha_inasistencia');
            $horaInasistencia  = $this->request->getPost('hora_inasistencia');
            $tipoInasistencia  = $this->request->getPost('tipo_inasistencia');
            $motivo            = $this->request->getPost('motivo');

            // Validaciones básicas
            if (!$empleadoId || !$fechaInasistencia || !$motivo) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Empleado, fecha e motivo son obligatorios'
                ]);
            }

            if (strlen(trim($motivo)) < 5) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'El motivo debe tener al menos 5 caracteres'
                ]);
            }

            $inasistenciaModel = new \App\Models\InasistenciaModel();

            $data = [
                'empleado_id'        => $empleadoId,
                'fecha_inasistencia' => $fechaInasistencia,
                'hora_inasistencia'  => $horaInasistencia ?: null,
                'motivo'             => trim($motivo),
                'tipo_inasistencia'  => $tipoInasistencia ?: 'Injustificada',
                'justificada'        => ($tipoInasistencia === 'Justificada') ? 1 : 0,
                'registrado_por'     => session()->get('id_usuario')
            ];

            $inasistenciaModel->insert($data);

            // Obtener total acumulado del empleado
            $totalAcumulado = $inasistenciaModel->obtenerTotalInasistencias($empleadoId);

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Inasistencia registrada correctamente. Total acumulado del empleado: ' . $totalAcumulado,
                'total_acumulado' => $totalAcumulado
            ]);

        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al guardar: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Editar Inasistencia (Mostrar formulario con datos cargados)
     */
    public function editarInasistencia($id)
    {
        $db = \Config\Database::connect();
        
        // Obtener la inasistencia actual
        $inasistencia = $db->table('inasistencias')->where('id', $id)->get()->getRowArray();
        
        if (!$inasistencia) {
            session()->setFlashdata('error', 'Inasistencia no encontrada.');
            return redirect()->to(base_url('admin-th/inasistencias'));
        }

        // Obtener empleados para el combo
        $empleados = $db->table('empleados')
            ->select('id_empleado, nombres, apellidos, departamento')
            ->where('estado', 'Activo')
            ->orderBy('apellidos', 'ASC')
            ->get()->getResultArray();

        $data = [
            'titulo' => 'Editar Inasistencia',
            'usuario' => [
                'nombres' => session()->get('nombres'),
                'apellidos' => session()->get('apellidos'),
                'rol' => session()->get('nombre_rol')
            ],
            'inasistencia' => $inasistencia,
            'empleados' => $empleados
        ];

        return view('Roles/AdminTH/inasistencias/editar', $data);
    }

    /**
     * Actualiza inasistencia (Proceso POST)
     */
    public function actualizarInasistencia($id = null)
    {
        // En algunas rutas puede venir por POST sin param ($id=null).
        if ($id === null) {
            $id = $this->request->getPost('id');
        }

        if (!$id) {
            return $this->response->setJSON(['success' => false, 'message' => 'ID no proporcionado.']);
        }

        $inasistenciaModel = new \App\Models\InasistenciaModel();
        
        $datos = [
            'empleado_id' => $this->request->getPost('empleado_id'),
            'fecha_inasistencia' => $this->request->getPost('fecha_inasistencia'),
            'hora_inasistencia' => $this->request->getPost('hora_inasistencia') ?: null,
            'tipo_inasistencia' => $this->request->getPost('tipo_inasistencia') ?: 'Injustificada',
            'motivo' => $this->request->getPost('motivo'),
            'justificada' => ($this->request->getPost('tipo_inasistencia') === 'Justificada') ? 1 : 0
        ];

        try {
            $inasistenciaModel->update($id, $datos);
            session()->setFlashdata('success', 'Inasistencia actualizada correctamente.');
            return $this->response->setJSON(['success' => true]);
        } catch (\Exception $e) {
            return $this->response->setJSON(['success' => false, 'message' => 'Error al actualizar: ' . $e->getMessage()]);
        }
    }

    /**
     * Listar inasistencias con acumulado (AJAX - JSON) - Req 4
     */
    public function listarInasistenciasJSON()
    {
        try {
            $inasistenciaModel = new \App\Models\InasistenciaModel();
            $inasistencias = $inasistenciaModel->obtenerInasistenciasConAcumulado();

            return $this->response->setJSON([
                'success' => true,
                'data'    => $inasistencias,
                'total'   => count($inasistencias)
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Obtener total histórico de inasistencias de un empleado (AJAX - JSON) - Req 4
     */
    public function totalInasistenciasJSON($empleadoId)
    {
        try {
            $inasistenciaModel = new \App\Models\InasistenciaModel();
            $total = $inasistenciaModel->obtenerTotalInasistencias($empleadoId);

            return $this->response->setJSON([
                'success' => true,
                'empleado_id' => $empleadoId,
                'total_inasistencias' => $total
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Generar Reporte de Inasistencias de un Empleado (Vista para Imprimir) - Req 3
     */
    public function reporteEmpleado($empleado_id)
    {
        $db = \Config\Database::connect();
        
        // Consultar datos del empleado (haciendo join con usuarios para la cédula)
        $empleado = $db->table('empleados e')
            ->select('e.id_empleado, e.nombres, e.apellidos, e.departamento, e.tipo_empleado, e.estado, u.cedula')
            ->join('usuarios u', 'u.id_usuario = e.id_usuario', 'left')
            ->where('e.id_empleado', $empleado_id)
            ->get()->getRowArray();

        if (!$empleado) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Empleado no encontrado.");
        }

        // Consultar historial de inasistencias completo
        $inasistencias = $db->table('inasistencias')
            ->where('empleado_id', $empleado_id)
            ->orderBy('fecha_inasistencia', 'DESC')
            ->get()->getResultArray();

        $data = [
            'empleado' => $empleado,
            'inasistencias' => $inasistencias
        ];

        return view('Roles/AdminTH/inasistencias/reporte_empleado', $data);
    }

    /**
     * Obtener perfil de empleado por AJAX - Req 2
     */
    public function obtenerPerfilEmpleado($empleado_id)
    {
        $empleadoModel = new \App\Models\EmpleadoModel();
        
        $empleado = $empleadoModel->getEmpleadoConUsuario($empleado_id);

        if (!$empleado) {
            return $this->response->setJSON(['success' => false, 'message' => 'Empleado no encontrado']);
        }

        // Formatear datos que podrían ser nulos o dar detalles extra
        $datos = [
            'success'            => true,
            'nombre_completo'    => $empleado['nombres'] . ' ' . $empleado['apellidos'],
            'cedula'             => $empleado['cedula'] ?? 'N/A',
            'correo'             => $empleado['email'] ?? 'N/A',
            'telefono'           => $empleado['telefono'] ?? 'N/A',
            'departamento'       => $empleado['departamento'] ?? 'N/A',
            'tipo_empleado'      => $empleado['tipo_empleado'] ?? 'N/A',
            'fecha_contratacion' => $empleado['fecha_ingreso'] ? date('d/m/Y', strtotime($empleado['fecha_ingreso'])) : 'N/A'
        ];

        return $this->response->setJSON($datos);
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
     * Eliminar Políticas Masivamente (AJAX POST)
     */
    public function eliminarPoliticasMasivo()
    {
        if (!$this->request->isAJAX()) return redirect()->back();

        try {
            $ids = $this->request->getPost('ids');
            if (empty($ids) || !is_array($ids)) {
                return $this->response->setJSON(['success' => false, 'message' => 'No se seleccionaron políticas.']);
            }

            // Según el controlador global parece estar cargado como $this->politicaInasistenciaModel
            // Si la vista está en desarrollo, probamos invocar el modelo explícitamente si existe
            // Para prevenir errores si no está importado globalmente
            $modelClass = '\App\Models\PoliticaInasistenciaModel';
            if (!class_exists($modelClass)) {
                return $this->response->setJSON(['success' => false, 'message' => 'El módulo de políticas no está completamente implementado.']);
            }

            $politicaModel = new $modelClass();
            $logModel = new \App\Models\LogSistemaModel();

            $db = \Config\Database::connect();
            $db->transStart();

            $eliminadas = 0;
            $errores = 0;

            foreach ($ids as $id) {
                $politica = $politicaModel->find($id);
                if (!$politica) {
                    $errores++;
                    continue;
                }

                if ($politicaModel->delete($id)) {
                    $logModel->registrarLog(
                        session()->get('id_usuario'),
                        'ELIMINAR_POLITICA_MASIVA',
                        'POLITICAS_INASISTENCIA',
                        "Política eliminada en lote (ID: {$id}, Nombre: {$politica['nombre_politica']})"
                    );
                    $eliminadas++;
                } else {
                    $errores++;
                }
            }

            $db->transComplete();

            if ($db->transStatus() === false) {
                return $this->response->setJSON(['success' => false, 'message' => 'Error de base de datos durante el borrado.']);
            }

            if ($eliminadas === 0 && $errores > 0) {
                return $this->response->setJSON(['success' => false, 'message' => 'No se eliminó ninguna política.']);
            }

            return $this->response->setJSON([
                'success' => true, 
                'message' => "{$eliminadas} política(s) eliminada(s) correctamente." . ($errores > 0 ? " ({$errores} omitidas)" : "")
            ]);
            
        } catch (\Exception $e) {
            log_message('error', 'Error en borrado masivo de políticas: ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'Error interno: ' . $e->getMessage()]);
        }
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
        $db = \Config\Database::connect();

        // Conteos para las tarjetas de resumen rápido
        $data = [
            'titulo'  => 'Reportes del Sistema',
            'usuario' => [
                'nombres'   => session()->get('nombres'),
                'apellidos' => session()->get('apellidos'),
                'rol'       => session()->get('nombre_rol'),
            ],
            'totalEmpleados'    => $db->table('empleados')->where('estado', 'Activo')->countAllResults(),
            'totalCapacitaciones' => $db->table('capacitaciones')->countAllResults(),
            'totalInasistencias'  => $db->table('inasistencias')->countAllResults(),
            'totalEvaluaciones'   => $db->table('evaluaciones')->countAllResults(),
            'departamentos' => $db->query("SELECT DISTINCT departamento FROM empleados WHERE departamento IS NOT NULL AND departamento != '' ORDER BY departamento")->getResultArray(),
        ];

        return view('Roles/AdminTH/reportes', $data);
    }

    /**
     * Exportar reporte en CSV con datos reales
     * URL: admin-th/reportes/exportar/{tipo}/{fechaInicio}/{fechaFin}
     */
    public function exportarReporte(string $tipo, string $fechaInicio, string $fechaFin)
    {
        $db          = \Config\Database::connect();
        $fechaInicio = urldecode($fechaInicio);
        $fechaFin    = urldecode($fechaFin);
        $filename    = "reporte_{$tipo}_" . date('Ymd_His') . '.csv';

        // Cabeceras HTTP para descarga CSV
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        $out = fopen('php://output', 'w');
        fprintf($out, chr(0xEF) . chr(0xBB) . chr(0xBF)); // BOM UTF-8

        switch ($tipo) {

            case 'empleados':
                fputcsv($out, ['ID', 'Apellidos', 'Nombres', 'Tipo', 'Departamento', 'Email', 'Cédula', 'Fecha Ingreso', 'Estado']);
                $rows = $db->query("
                    SELECT e.id_empleado, e.apellidos, e.nombres, e.tipo_empleado,
                           COALESCE(e.departamento,'Sin asignar') AS departamento,
                           u.email, u.cedula, e.fecha_ingreso, e.estado
                    FROM empleados e
                    LEFT JOIN usuarios u ON u.id_usuario = e.id_usuario
                    WHERE e.fecha_ingreso BETWEEN ? AND ?
                    ORDER BY e.apellidos
                ", [$fechaInicio, $fechaFin])->getResultArray();
                foreach ($rows as $r) {
                    fputcsv($out, [$r['id_empleado'], $r['apellidos'], $r['nombres'], $r['tipo_empleado'],
                        $r['departamento'], $r['email'], $r['cedula'], $r['fecha_ingreso'], $r['estado']]);
                }
                break;

            case 'capacitaciones':
                fputcsv($out, ['ID', 'Nombre', 'Tipo', 'Modalidad', 'Institución', 'Fecha Inicio', 'Fecha Fin', 'Horas', 'Estado']);
                $rows = $db->query("
                    SELECT id_capacitacion, nombre, tipo, modalidad, institucion,
                           fecha_inicio, fecha_fin, duracion_horas, estado
                    FROM capacitaciones
                    WHERE fecha_inicio BETWEEN ? AND ?
                    ORDER BY fecha_inicio DESC
                ", [$fechaInicio, $fechaFin])->getResultArray();
                foreach ($rows as $r) {
                    fputcsv($out, [$r['id_capacitacion'], $r['nombre'], $r['tipo'], $r['modalidad'],
                        $r['institucion'], $r['fecha_inicio'], $r['fecha_fin'], $r['duracion_horas'], $r['estado']]);
                }
                break;

            case 'inasistencias':
                fputcsv($out, ['ID', 'Empleado', 'Departamento', 'Fecha', 'Tipo', 'Motivo', 'Justificada']);
                $rows = $db->query("
                    SELECT i.id, CONCAT(e.apellidos,' ',e.nombres) AS empleado,
                           COALESCE(e.departamento,'Sin asignar') AS departamento,
                           i.fecha_inasistencia, i.tipo_inasistencia, i.motivo,
                           IF(i.justificada=1,'Sí','No') AS justificada
                    FROM inasistencias i
                    LEFT JOIN empleados e ON e.id_empleado = i.empleado_id
                    WHERE i.fecha_inasistencia BETWEEN ? AND ?
                    ORDER BY i.fecha_inasistencia DESC
                ", [$fechaInicio, $fechaFin])->getResultArray();
                foreach ($rows as $r) {
                    fputcsv($out, [$r['id'], $r['empleado'], $r['departamento'],
                        $r['fecha_inasistencia'], $r['tipo_inasistencia'], $r['motivo'], $r['justificada']]);
                }
                break;

            case 'evaluaciones':
                fputcsv($out, ['ID', 'Evaluación', 'Tipo', 'Empleado', 'Evaluador', 'Puntaje Total', 'Fecha', 'Estado']);
                $rows = $db->query("
                    SELECT ee.id_evaluacion_empleado,
                           ev.nombre AS evaluacion, ev.tipo_evaluacion,
                           CONCAT(e.apellidos,' ',e.nombres)  AS empleado,
                           CONCAT(ev2.apellidos,' ',ev2.nombres) AS evaluador,
                           ee.puntaje_total, ee.fecha_evaluacion, ev.estado
                    FROM evaluaciones_empleados ee
                    LEFT JOIN evaluaciones ev  ON ev.id_evaluacion  = ee.id_evaluacion
                    LEFT JOIN empleados e      ON e.id_empleado     = ee.id_empleado
                    LEFT JOIN empleados ev2    ON ev2.id_empleado   = ee.id_evaluador
                    WHERE ee.fecha_evaluacion BETWEEN ? AND ?
                    ORDER BY ee.fecha_evaluacion DESC
                ", [$fechaInicio, $fechaFin])->getResultArray();
                foreach ($rows as $r) {
                    fputcsv($out, [$r['id_evaluacion_empleado'], $r['evaluacion'], $r['tipo_evaluacion'],
                        $r['empleado'], $r['evaluador'], $r['puntaje_total'], $r['fecha_evaluacion'], $r['estado']]);
                }
                break;

            case 'solicitudes':
                fputcsv($out, ['ID', 'Empleado', 'Departamento', 'Tipo', 'Asunto', 'Estado', 'Fecha Solicitud', 'Comentarios']);
                $rows = $db->query("
                    SELECT s.id_solicitud, CONCAT(e.apellidos,' ',e.nombres) AS empleado,
                           COALESCE(e.departamento,'Sin asignar') AS departamento,
                           s.tipo_solicitud, s.titulo, s.estado,
                           s.fecha_solicitud, s.comentarios_resolucion
                    FROM solicitudes s
                    LEFT JOIN empleados e ON e.id_empleado = s.id_empleado
                    WHERE DATE(s.fecha_solicitud) BETWEEN ? AND ?
                      AND s.activo = 1
                    ORDER BY s.fecha_solicitud DESC
                ", [$fechaInicio, $fechaFin])->getResultArray();
                foreach ($rows as $r) {
                    fputcsv($out, [$r['id_solicitud'], $r['empleado'], $r['departamento'],
                        $r['tipo_solicitud'], $r['titulo'], $r['estado'],
                        $r['fecha_solicitud'], $r['comentarios_resolucion']]);
                }
                break;

            default:
                fputcsv($out, ['Error']);
                fputcsv($out, ['Tipo de reporte no reconocido: ' . $tipo]);
        }

        fclose($out);
        exit;
    }

    /**
     * Estadísticas
     */
    public function estadisticas()
    {
        $db         = \Config\Database::connect();
        $anioActual = (int) date('Y');
        $mesActual  = date('Y-m');
        $hoy        = date('Y-m-d');
        $inicioTrimestre = date('Y-m-d', strtotime('-3 months'));

        // ── Tarjetas de resumen ───────────────────────────────────────────────

        $totalEmpleados = $db->table('empleados')
            ->where('estado', 'Activo')
            ->countAllResults();

        $capActivas = $db->table('capacitaciones')
            ->where('estado', 'ACTIVA')
            ->countAllResults();

        $inasistenciasMes = $db->table('inasistencias')
            ->like('fecha_inasistencia', $mesActual, 'after')
            ->countAllResults();

        $evaluacionesPendientes = $db->table('evaluaciones')
            ->whereIn('estado', ['Planificada', 'En curso'])
            ->countAllResults();

        // ── Gráfico 1: Empleados por departamento ─────────────────────────────
        $empDepto = $db->table('empleados')
            ->select('COALESCE(departamento, "Sin asignar") AS depto, COUNT(*) AS total')
            ->where('estado', 'Activo')
            ->groupBy('departamento')
            ->orderBy('total', 'DESC')
            ->get()->getResultArray();

        $graficoDeptos = [
            'labels' => array_column($empDepto, 'depto'),
            'data'   => array_map('intval', array_column($empDepto, 'total')),
        ];

        // ── Gráfico 2: Capacitaciones por estado ──────────────────────────────
        $capEstado = $db->table('capacitaciones')
            ->select('estado, COUNT(*) AS total')
            ->groupBy('estado')
            ->get()->getResultArray();

        $graficoCapEstado = [
            'labels' => array_column($capEstado, 'estado'),
            'data'   => array_map('intval', array_column($capEstado, 'total')),
        ];

        // ── Gráfico 3: Inasistencias por mes (últimos 12 meses) ───────────────
        $inaMeses = $db->query("
            SELECT MONTH(fecha_inasistencia) AS mes, COUNT(*) AS total
            FROM inasistencias
            WHERE YEAR(fecha_inasistencia) = ?
            GROUP BY MONTH(fecha_inasistencia)
            ORDER BY mes
        ", [$anioActual])->getResultArray();

        $mesesData = array_fill(0, 12, 0);
        foreach ($inaMeses as $row) {
            $mesesData[(int)$row['mes'] - 1] = (int)$row['total'];
        }
        $graficoInasistenciasAnio = $mesesData;

        // ── Gráfico 4: Distribución de calificaciones de evaluaciones ─────────
        $calificaciones = $db->query("
            SELECT
                CASE
                    WHEN puntaje_total >= 9  THEN 'Excelente (9-10)'
                    WHEN puntaje_total >= 7  THEN 'Bueno (7-8)'
                    WHEN puntaje_total >= 5  THEN 'Regular (5-6)'
                    ELSE                          'Bajo (1-4)'
                END AS rango,
                COUNT(*) AS total
            FROM evaluaciones_empleados
            WHERE puntaje_total IS NOT NULL
            GROUP BY rango
            ORDER BY MIN(puntaje_total) DESC
        ")->getResultArray();

        // Si la tabla no existe o está vacía, usar fallback vacío
        $graficoCalificaciones = [
            'labels' => empty($calificaciones) ? [] : array_column($calificaciones, 'rango'),
            'data'   => empty($calificaciones) ? [] : array_map('intval', array_column($calificaciones, 'total')),
        ];

        // ── Top 5 empleados por capacitaciones inscritas ─────────────────────
        $topEmpleadosCap = $db->query("
            SELECT
                e.nombres,
                e.apellidos,
                COALESCE(e.departamento, 'Sin asignar') AS departamento,
                COUNT(ce.id)                             AS total_cap,
                SUM(ce.estado = 'COMPLETADA')            AS completadas,
                ROUND(
                    SUM(ce.estado = 'COMPLETADA') * 100.0
                    / NULLIF(COUNT(ce.id), 0)
                , 0)                                     AS porcentaje
            FROM empleados e
            INNER JOIN capacitaciones_empleados ce ON ce.empleado_id = e.id_empleado
            WHERE e.estado = 'Activo'
            GROUP BY e.id_empleado, e.nombres, e.apellidos, e.departamento
            ORDER BY total_cap DESC, completadas DESC
            LIMIT 5
        ")->getResultArray();

        // ── Top departamentos con mayor inasistencia (mes actual) ─────────────
        $topDeptosInasistencia = $db->query("
            SELECT
                COALESCE(e.departamento, 'Sin asignar')  AS departamento,
                COUNT(DISTINCT e.id_empleado)             AS total_empleados,
                COUNT(i.id)                               AS total_inasistencias,
                ROUND(
                    COUNT(i.id) * 100.0 /
                    NULLIF(COUNT(DISTINCT e.id_empleado), 0)
                , 1)                                      AS porcentaje
            FROM empleados e
            LEFT JOIN inasistencias i
                   ON i.empleado_id = e.id_empleado
                  AND i.fecha_inasistencia LIKE ?
            WHERE e.estado = 'Activo'
            GROUP BY e.departamento
            HAVING total_inasistencias > 0
            ORDER BY total_inasistencias DESC
            LIMIT 5
        ", [$mesActual . '%'])->getResultArray();

        // ── Métricas de rendimiento ───────────────────────────────────────────

        // Tasa de asistencia: días laborables del mes vs inasistencias
        $diasLaborables = 22; // estimado estándar
        $totalPosibles  = max(1, $totalEmpleados * $diasLaborables);
        $tasaAsistencia = round((1 - ($inasistenciasMes / $totalPosibles)) * 100, 1);
        $tasaAsistencia = max(0, min(100, $tasaAsistencia));

        // Participación en capacitaciones (último trimestre)
        $conCap = $db->query("
            SELECT COUNT(DISTINCT empleado_id) AS total
            FROM capacitaciones_empleados
            WHERE created_at >= ?
        ", [$inicioTrimestre])->getRowArray();
        $partCapacitaciones = $totalEmpleados > 0
            ? round(((int)($conCap['total'] ?? 0) / $totalEmpleados) * 100, 1)
            : 0;

        // Promedio de evaluaciones
        $avgEval = $db->query("
            SELECT ROUND(AVG(puntaje_total), 1) AS promedio
            FROM evaluaciones_empleados
            WHERE puntaje_total IS NOT NULL
        ")->getRowArray();
        $promedioEvaluaciones = $avgEval['promedio'] ?? 'N/A';

        // Tiempo promedio de respuesta de solicitudes (días)
        $tiempoResp = $db->query("
            SELECT ROUND(AVG(DATEDIFF(fecha_resolucion, fecha_solicitud)), 1) AS promedio
            FROM solicitudes
            WHERE fecha_resolucion IS NOT NULL
              AND activo = 1
        ")->getRowArray();
        $tiempoRespuesta = isset($tiempoResp['promedio']) && $tiempoResp['promedio'] !== null
            ? $tiempoResp['promedio'] . ' días'
            : 'N/A';

        $data = [
            'titulo'  => 'Estadísticas del Sistema',
            'usuario' => [
                'nombres'   => session()->get('nombres'),
                'apellidos' => session()->get('apellidos'),
                'rol'       => session()->get('nombre_rol'),
            ],
            // Tarjetas
            'totalEmpleados'        => $totalEmpleados,
            'capActivas'            => $capActivas,
            'inasistenciasMes'      => $inasistenciasMes,
            'evaluacionesPendientes' => $evaluacionesPendientes,
        // Gráficos (json_encode aquí para no repetirlo en la vista)
            'graficoDeptos'              => json_encode($graficoDeptos),
            'graficoCapEstado'           => json_encode($graficoCapEstado),
            'graficoInasistenciasAnio'   => json_encode($graficoInasistenciasAnio),
            'graficoCalificaciones'      => json_encode($graficoCalificaciones),
            // Tablas
            'topEmpleadosCap'        => $topEmpleadosCap,
            'topDeptosInasistencia'  => $topDeptosInasistencia,
            // Métricas
            'tasaAsistencia'         => $tasaAsistencia . '%',
            'partCapacitaciones'     => $partCapacitaciones . '%',
            'promedioEvaluaciones'   => $promedioEvaluaciones,
            'tiempoRespuesta'        => $tiempoRespuesta,
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

            // Protección: no se puede deshabilitar al Administrador de Talento Humano
            $db = \Config\Database::connect();
            $usuario = $db->table('usuarios')->where('id_usuario', $empleado['id_usuario'])->get()->getRowArray();
            if ($usuario && $usuario['id_rol'] == 2) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Acción denegada: No se puede alterar al perfil de Administrador principal.'
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

            // Protección: no se puede alterar al Administrador de Talento Humano
            $db = \Config\Database::connect();
            $usuario = $db->table('usuarios')->where('id_usuario', $empleado['id_usuario'])->get()->getRowArray();
            if ($usuario && $usuario['id_rol'] == 2) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Acción denegada: No se puede alterar al perfil de Administrador principal.'
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
     * Eliminar empleado y su usuario vinculado
     */
    public function eliminarEmpleado()
    {
        if (!$this->request->isAJAX()) {
            return redirect()->back();
        }

        try {
            $idEmpleado = $this->request->getPost('id_empleado');
            
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

            // Protección: no se puede eliminar al Administrador de Talento Humano
            $db = \Config\Database::connect();
            $usuario = $db->table('usuarios')->where('id_usuario', $empleado['id_usuario'])->get()->getRowArray();
            if ($usuario && $usuario['id_rol'] == 2) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Acción denegada: No se puede eliminar al perfil de Administrador principal.'
                ]);
            }

            $idUsuario = $empleado['id_usuario'];
            $nombreCompleto = $empleado['nombres'] . ' ' . $empleado['apellidos'];

            // Usar transacción para eliminar empleado + usuario
            $db->transStart();

            // 1. Eliminar empleado
            $db->table('empleados')->where('id_empleado', $idEmpleado)->delete();

            // 2. Eliminar usuario vinculado
            if ($idUsuario) {
                $db->table('usuarios')->where('id_usuario', $idUsuario)->delete();
            }

            $db->transComplete();

            if ($db->transStatus() === false) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Error al eliminar el empleado. La operación fue revertida.'
                ]);
            }

            // Registrar log
            $logModel = new LogSistemaModel();
            $logModel->registrarLog(
                session()->get('id_usuario'),
                'ELIMINAR_EMPLEADO',
                'EMPLEADOS',
                "Empleado eliminado: {$nombreCompleto}"
            );

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Empleado y sus credenciales eliminados correctamente'
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Error eliminando empleado: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error interno del servidor'
            ]);
        }
    }

    /**
     * Eliminar empleados masivamente
     */
    public function eliminarEmpleadosMasivo()
    {
        if (!$this->request->isAJAX()) {
            return redirect()->back();
        }

        try {
            $ids = $this->request->getPost('ids');
            
            if (empty($ids) || !is_array($ids)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'No se seleccionaron empleados para eliminar'
                ]);
            }

            $db = \Config\Database::connect();
            $db->transStart();

            $eliminados = 0;
            $errores = [];

            foreach ($ids as $idEmpleado) {
                // Obtener empleado
                $empleado = $this->empleadoModel->find($idEmpleado);
                if (!$empleado) {
                    continue;
                }

                // Protección: no se puede eliminar al Administrador de Talento Humano
                $usuario = $db->table('usuarios')->where('id_usuario', $empleado['id_usuario'])->get()->getRowArray();
                if ($usuario && $usuario['id_rol'] == 2) {
                    $errores[] = "Se omitió a " . $empleado['nombres'] . " (Es Administrador).";
                    continue;
                }

                $idUsuario = $empleado['id_usuario'];

                // 1. Eliminar empleado
                $db->table('empleados')->where('id_empleado', $idEmpleado)->delete();

                // 2. Eliminar usuario vinculado
                if ($idUsuario) {
                    $db->table('usuarios')->where('id_usuario', $idUsuario)->delete();
                }

                $eliminados++;
            }

            $db->transComplete();

            if ($db->transStatus() === false) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Error al ejecutar la eliminación en lote. La operación fue revertida.'
                ]);
            }

            if ($eliminados === 0 && !empty($errores)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'No se eliminó ninguno: ' . implode(' ', $errores)
                ]);
            }

            $mensajeExtra = !empty($errores) ? " (Omitidos: " . count($errores) . ")" : "";

            return $this->response->setJSON([
                'success' => true,
                'message' => $eliminados . ' registro(s) eliminado(s) correctamente.' . $mensajeExtra
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Error en eliminación masiva de empleados: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error interno del servidor en borrado masivo'
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
     * Obtener capacitaciones para la tabla
     */
    public function obtenerCapacitaciones()
    {
        try {
            $capacitacionModel = new CapacitacionModel();
            $capacitaciones = $capacitacionModel->getCapacitacionesConEstadisticas();

            return $this->response->setJSON([
                'success' => true,
                'capacitaciones' => $capacitaciones
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Error obteniendo capacitaciones: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al obtener capacitaciones: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Obtener capacitación específica
     */
    public function obtenerCapacitacion($idCapacitacion)
    {
        try {
            $capacitacionModel = new CapacitacionModel();
            $capacitacion = $capacitacionModel->find($idCapacitacion);

            if (!$capacitacion) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Capacitación no encontrada'
                ]);
            }

            // Agregar conteo de inscritos
            $capacitacion['total_inscritos'] = $capacitacionModel->contarInscritos($idCapacitacion);

            return $this->response->setJSON([
                'success' => true,
                'capacitacion' => $capacitacion
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Error obteniendo capacitación: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al obtener la capacitación: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Crear nueva capacitación
     */
    public function crearCapacitacion()
    {
        try {
            $nombre = $this->request->getPost('nombre');
            $duracion = $this->request->getPost('duracion');
            $modalidad = $this->request->getPost('modalidad');

            if (empty($nombre) || empty($duracion) || empty($modalidad)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Los campos Nombre, Duración y Modalidad son obligatorios'
                ]);
            }

            // Validar fechas si se proporcionan
            $fechaInicio = $this->request->getPost('fecha_inicio');
            $fechaFin = $this->request->getPost('fecha_fin');
            
            if ($fechaInicio && $fechaFin && strtotime($fechaFin) <= strtotime($fechaInicio)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'La fecha de fin debe ser posterior a la fecha de inicio'
                ]);
            }

            $capacitacionModel = new CapacitacionModel();
            
            $data = [
                'nombre' => $nombre,
                'descripcion' => $this->request->getPost('descripcion') ?? '',
                'duracion_horas' => (int) $duracion,
                'modalidad' => $modalidad,
                'estado' => $this->request->getPost('estado') ?? 'ACTIVA',
                'fecha_inicio' => $fechaInicio ?: date('Y-m-d'),
                'fecha_fin' => $fechaFin ?: null,
                'cupo_maximo' => $this->request->getPost('cupo_maximo') ?: 20,
                'creado_por' => session()->get('id_usuario')
            ];

            if ($capacitacionModel->insert($data, false)) {
                // Registrar log
                $logModel = new LogSistemaModel();
                $logModel->registrarLog(
                    session()->get('id_usuario'),
                    'CREAR_CAPACITACION',
                    'CAPACITACIONES',
                    "Capacitación creada: {$data['nombre']}"
                );

                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Capacitación creada correctamente'
                ]);
            } else {
                $errors = $capacitacionModel->errors();
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Error al crear la capacitación: ' . implode(', ', $errors)
                ]);
            }

        } catch (\Exception $e) {
            log_message('error', 'Error creando capacitación: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al crear capacitación: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Actualizar capacitación
     */
    public function actualizarCapacitacion()
    {
        try {
            $idCapacitacion = $this->request->getPost('id_capacitacion');
            $nombre = $this->request->getPost('nombre');
            $duracion = $this->request->getPost('duracion');
            $modalidad = $this->request->getPost('modalidad');

            if (empty($idCapacitacion) || empty($nombre) || empty($duracion) || empty($modalidad)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Los campos ID, Nombre, Duración y Modalidad son obligatorios'
                ]);
            }

            // Validar fechas si se proporcionan
            $fechaInicio = $this->request->getPost('fecha_inicio');
            $fechaFin = $this->request->getPost('fecha_fin');
            
            if ($fechaInicio && $fechaFin && strtotime($fechaFin) <= strtotime($fechaInicio)) {
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

            $data = [
                'nombre' => $nombre,
                'descripcion' => $this->request->getPost('descripcion') ?? '',
                'duracion_horas' => (int) $duracion,
                'modalidad' => $modalidad,
                'estado' => $this->request->getPost('estado') ?? $capacitacion['estado'],
                'fecha_inicio' => $fechaInicio ?: null,
                'fecha_fin' => $fechaFin ?: null,
                'cupo_maximo' => $this->request->getPost('cupo_maximo') ?: $capacitacion['cupo_maximo']
            ];

            if ($capacitacionModel->update($idCapacitacion, $data)) {
                // Registrar log
                $logModel = new LogSistemaModel();
                $logModel->registrarLog(
                    session()->get('id_usuario'),
                    'EDITAR_CAPACITACION',
                    'CAPACITACIONES',
                    "Capacitación editada: {$data['nombre']}"
                );

                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Capacitación actualizada correctamente'
                ]);
            } else {
                $errors = $capacitacionModel->errors();
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Error al actualizar: ' . implode(', ', $errors)
                ]);
            }

        } catch (\Exception $e) {
            log_message('error', 'Error editando capacitación: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al actualizar: ' . $e->getMessage()
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
        try {
            $idCapacitacion = $this->request->getPost('id_capacitacion');
            $nuevoEstado = $this->request->getPost('estado');

            $estadosValidos = ['ACTIVA', 'INACTIVA', 'EN_CURSO', 'COMPLETADA', 'CANCELADA'];
            
            if (!in_array($nuevoEstado, $estadosValidos)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Estado no válido. Estados permitidos: ' . implode(', ', $estadosValidos)
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
                    "Estado cambiado a {$nuevoEstado} para capacitación: {$capacitacion['nombre']}"
                );

                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Estado de capacitación actualizado a ' . $nuevoEstado
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
                'message' => 'Error al cambiar estado: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Eliminar capacitación (solo si es INACTIVA)
     */
    public function eliminarCapacitacion()
    {
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

            // Solo se pueden borrar capacitaciones INACTIVAS
            if ($capacitacion['estado'] !== 'INACTIVA') {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Solo se pueden borrar capacitaciones inactivas. Estado actual: ' . $capacitacion['estado']
                ]);
            }

            if ($capacitacionModel->delete($idCapacitacion)) {
                // Registrar log
                $logModel = new LogSistemaModel();
                $logModel->registrarLog(
                    session()->get('id_usuario'),
                    'ELIMINAR_CAPACITACION',
                    'CAPACITACIONES',
                    "Capacitación eliminada: {$capacitacion['nombre']}"
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
                'message' => 'Error al eliminar: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Eliminar capacitaciones masivamente
     */
    public function eliminarCapacitacionesMasivo()
    {
        if (!$this->request->isAJAX()) {
            return redirect()->back();
        }

        try {
            $ids = $this->request->getPost('ids');
            
            if (empty($ids) || !is_array($ids)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'No se seleccionaron capacitaciones para eliminar'
                ]);
            }

            $capacitacionModel = new CapacitacionModel();
            $logModel = new LogSistemaModel();
            $idUsuario = session()->get('id_usuario');

            $db = \Config\Database::connect();
            $db->transStart();

            $eliminados = 0;
            $errores = 0;

            foreach ($ids as $idCapacitacion) {
                $capacitacion = $capacitacionModel->find($idCapacitacion);
                
                // Solo eliminar si existe y está INACTIVA
                if (!$capacitacion || $capacitacion['estado'] !== 'INACTIVA') {
                    $errores++;
                    continue;
                }

                if ($capacitacionModel->delete($idCapacitacion)) {
                    $logModel->registrarLog(
                        $idUsuario,
                        'ELIMINAR_CAPACITACION_MASIVO',
                        'CAPACITACIONES',
                        "Capacitación eliminada en lote: {$capacitacion['nombre']}"
                    );
                    $eliminados++;
                } else {
                    $errores++;
                }
            }

            $db->transComplete();

            if ($db->transStatus() === false) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Error al ejecutar la eliminación en lote. Operación revertida.'
                ]);
            }

            if ($eliminados === 0 && $errores > 0) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'No se eliminó ninguna capacitación. Asegúrese de que estén en estado INACTIVA.'
                ]);
            }

            $mensajeExtra = $errores > 0 ? " (Omitidas/Activas: {$errores})" : "";

            return $this->response->setJSON([
                'success' => true,
                'message' => "{$eliminados} capacitación(es) eliminada(s) correctamente." . $mensajeExtra
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Error en eliminación masiva de capacitaciones: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error interno del servidor en borrado masivo'
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

    /**
     * Conectar Google Drive via OAuth2
     * Genera URL de auth o intercambia código por token
     */
    public function conectarGoogle()
    {
        try {
            $clientSecretsPath = WRITEPATH . 'client_secrets.json';
            
            if (!file_exists($clientSecretsPath)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'No se encontró el archivo client_secrets.json en writable/'
                ]);
            }

            $client = new \Google\Client();
            $client->setAuthConfig($clientSecretsPath);
            $client->setAccessType('offline');
            $client->setPrompt('select_account consent');
            $client->addScope(\Google\Service\Drive::DRIVE_FILE);
            $client->setRedirectUri(base_url('admin-th/conectar-google'));

            // Si hay código de Google, intercambiar por token
            $code = $this->request->getGet('code');
            
            if ($code) {
                $accessToken = $client->fetchAccessTokenWithAuthCode($code);
                
                if (isset($accessToken['error'])) {
                    return view('postulacion/error', [
                        'titulo' => 'Error de Autorización',
                        'mensaje' => 'Error al obtener token: ' . ($accessToken['error_description'] ?? $accessToken['error'])
                    ]);
                }

                // Guardar token
                $tokenPath = WRITEPATH . 'token.json';
                file_put_contents($tokenPath, json_encode($accessToken));

                // Redirigir al panel de puestos con mensaje de éxito
                return redirect()->to(base_url('admin-th/puestos'))
                    ->with('success', '¡Google Drive conectado exitosamente! Ya puedes recibir CVs.');
            }

            // Si no hay código, redirigir a Google para autorización
            $authUrl = $client->createAuthUrl();
            return redirect()->to($authUrl);

        } catch (\Exception $e) {
            log_message('error', 'Error al conectar Google: ' . $e->getMessage());
            return view('postulacion/error', [
                'titulo' => 'Error de Conexión',
                'mensaje' => 'Error al conectar con Google: ' . $e->getMessage()
            ]);
        }
    }

    // ==================== EVALUACIONES ENTRE PARES ====================

    /**
     * Vista principal de evaluaciones entre pares
     */
    public function evaluacionesPares()
    {
        return view('Roles/AdminTH/evaluaciones_pares');
    }

    /**
     * Obtener docentes para los selects
     */
    public function obtenerDocentes()
    {
        try {
            $empleadoModel = new EmpleadoModel();
            $docentes = $empleadoModel
                ->where('tipo_empleado', 'DOCENTE')
                ->where('activo', 1)
                ->select('id_empleado, nombres, apellidos, departamento')
                ->findAll();

            return $this->response->setJSON([
                'success' => true,
                'docentes' => $docentes
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al obtener docentes: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Obtener todas las evaluaciones entre pares
     */
    public function obtenerEvaluacionesPares()
    {
        try {
            $model = new EvaluacionParModel();
            $evaluaciones = $model->getTodasConNombres();

            return $this->response->setJSON([
                'success' => true,
                'evaluaciones' => $evaluaciones
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Asignar una evaluación entre pares
     */
    public function asignarEvaluacionPar()
    {
        try {
            $evaluadorId = $this->request->getPost('evaluador_id');
            $evaluadoId  = $this->request->getPost('evaluado_id');

            if (empty($evaluadorId) || empty($evaluadoId)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Debe seleccionar evaluador y evaluado'
                ]);
            }

            // Validación: no puede evaluarse a sí mismo
            if ($evaluadorId == $evaluadoId) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Un docente no puede evaluarse a sí mismo'
                ]);
            }

            // Validar que ambos sean docentes activos
            $empleadoModel = new EmpleadoModel();
            $evaluador = $empleadoModel->where('id_empleado', $evaluadorId)->where('tipo_empleado', 'DOCENTE')->first();
            $evaluado  = $empleadoModel->where('id_empleado', $evaluadoId)->where('tipo_empleado', 'DOCENTE')->first();

            if (!$evaluador || !$evaluado) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Ambos participantes deben ser docentes activos'
                ]);
            }

            $model = new EvaluacionParModel();
            $data = [
                'evaluador_id'     => $evaluadorId,
                'evaluado_id'      => $evaluadoId,
                'estado'           => 'pendiente',
                'fecha_asignacion' => date('Y-m-d H:i:s')
            ];

            if ($model->insert($data)) {
                $logModel = new LogSistemaModel();
                $logModel->registrarLog(
                    session()->get('id_usuario'),
                    'ASIGNAR_EVALUACION_PAR',
                    'EVALUACIONES_PARES',
                    "Evaluación asignada: {$evaluador['nombres']} {$evaluador['apellidos']} evalúa a {$evaluado['nombres']} {$evaluado['apellidos']}"
                );

                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Evaluación entre pares asignada correctamente'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Error al asignar la evaluación'
                ]);
            }

        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Eliminar una evaluación entre pares (solo si está pendiente)
     */
    public function eliminarEvaluacionPar()
    {
        try {
            $id = $this->request->getPost('id');
            $model = new EvaluacionParModel();
            $eval = $model->find($id);

            if (!$eval) {
                return $this->response->setJSON(['success' => false, 'message' => 'Evaluación no encontrada']);
            }

            if ($eval['estado'] !== 'pendiente') {
                return $this->response->setJSON(['success' => false, 'message' => 'Solo se pueden eliminar evaluaciones pendientes']);
            }

            $model->delete($id);
            return $this->response->setJSON(['success' => true, 'message' => 'Evaluación eliminada correctamente']);

        } catch (\Exception $e) {
            return $this->response->setJSON(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
    }

    // ==================== GESTIÓN DE EVALUACIONES ====================

    /**
     * Obtener todas las evaluaciones (AJAX)
     */
    public function obtenerEvaluaciones()
    {
        try {
            $db = \Config\Database::connect();

            // Traer evaluaciones de evaluaciones_empleados con JOINs
            $evaluaciones = $db->table('evaluaciones_empleados ee')
                ->select('ee.id_evaluacion_empleado as id, ee.id_evaluacion, ee.id_empleado, ee.id_evaluador,
                          ee.fecha_evaluacion, ee.puntaje_total, ee.observaciones,
                          e.nombre as nombre_evaluacion, e.tipo_evaluacion, e.estado,
                          emp.nombres as nombres_empleado, emp.apellidos as apellidos_empleado,
                          CONCAT(evaluador.nombres, " ", evaluador.apellidos) as nombre_evaluador')
                ->join('evaluaciones e', 'e.id_evaluacion = ee.id_evaluacion', 'left')
                ->join('empleados emp', 'emp.id_empleado = ee.id_empleado', 'left')
                ->join('empleados evaluador', 'evaluador.id_empleado = ee.id_evaluador', 'left')
                ->orderBy('ee.fecha_evaluacion', 'DESC')
                ->get()
                ->getResultArray();

            return $this->response->setJSON([
                'success' => true,
                'evaluaciones' => $evaluaciones
            ]);

        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al cargar evaluaciones: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Obtener una evaluación específica (AJAX)
     */
    public function obtenerEvaluacion2($id)
    {
        try {
            $db = \Config\Database::connect();
            $eval = $db->table('evaluaciones_empleados ee')
                ->select('ee.*, e.nombre as nombre_evaluacion, e.tipo_evaluacion, e.estado as evaluacion_estado,
                          emp.nombres as nombres_empleado, emp.apellidos as apellidos_empleado,
                          CONCAT(evaluador.nombres, " ", evaluador.apellidos) as nombre_evaluador')
                ->join('evaluaciones e', 'e.id_evaluacion = ee.id_evaluacion', 'left')
                ->join('empleados emp', 'emp.id_empleado = ee.id_empleado', 'left')
                ->join('empleados evaluador', 'evaluador.id_empleado = ee.id_evaluador', 'left')
                ->where('ee.id_evaluacion_empleado', $id)
                ->get()
                ->getRowArray();

            if (!$eval) {
                return $this->response->setJSON(['success' => false, 'message' => 'Evaluación no encontrada']);
            }

            return $this->response->setJSON(['success' => true, 'evaluacion' => $eval]);

        } catch (\Exception $e) {
            return $this->response->setJSON(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
    }

    /**
     * Crear una evaluación (AJAX) - Unificado para general y entre pares
     */
    public function crearEvaluacion()
    {
        try {
            $tipo = $this->request->getPost('tipo_evaluacion');
            $empleadoId = $this->request->getPost('empleado_id');
            $fecha = $this->request->getPost('fecha_evaluacion') ?: date('Y-m-d');
            $observaciones = $this->request->getPost('observaciones');

            if (empty($tipo) || empty($empleadoId)) {
                return $this->response->setJSON(['success' => false, 'message' => 'Tipo de evaluación y empleado son obligatorios']);
            }

            $db = \Config\Database::connect();
            $evalModel = new EvaluacionModel();
            $empleadoModel = new EmpleadoModel();

            // ========================
            // AUTOEVALUACIÓN
            // ========================
            if ($tipo === 'Autoevaluación') {
                // 1. Crear evaluación padre con estado válido
                $idEvaluacion = $evalModel->insert([
                    'nombre'          => 'Autoevaluación',
                    'descripcion'     => 'Autoevaluación del empleado',
                    'tipo_evaluacion' => 'Autoevaluación',
                    'fecha_inicio'    => $fecha,
                    'fecha_fin'       => $fecha,
                    'estado'          => 'Planificada'
                ]);

                if (!$idEvaluacion) {
                    return $this->response->setJSON(['success' => false, 'message' => 'Error al crear la evaluación']);
                }

                // 2. Insertar registro: evaluador = evaluado (se evalúa a sí mismo)
                $db->table('evaluaciones_empleados')->insert([
                    'id_evaluacion'    => $idEvaluacion,
                    'id_empleado'      => $empleadoId,
                    'id_evaluador'     => $empleadoId,
                    'fecha_evaluacion' => $fecha,
                    'puntaje_total'    => null,
                    'observaciones'    => $observaciones ?: null
                ]);

                $logModel = new LogSistemaModel();
                $logModel->registrarLog(
                    session()->get('id_usuario'),
                    'CREAR_AUTOEVALUACION',
                    'EVALUACIONES',
                    'Autoevaluación creada para empleado ID ' . $empleadoId
                );

                return $this->response->setJSON(['success' => true, 'message' => 'Autoevaluación creada correctamente']);
            }

            // ========================
            // EVALUACIÓN 360°
            // ========================
            if ($tipo === 'Evaluación 360') {
                // Obtener TODOS los empleados activos EXCEPTO el evaluado
                $evaluadores = $empleadoModel
                    ->where('id_empleado !=', $empleadoId)
                    ->where('activo', 1)
                    ->findAll();

                if (empty($evaluadores)) {
                    return $this->response->setJSON(['success' => false, 'message' => 'No hay otros empleados activos para asignar como evaluadores']);
                }

                // 1. Crear evaluación padre con estado válido
                $idEvaluacion = $evalModel->insert([
                    'nombre'          => 'Evaluación 360°',
                    'descripcion'     => 'Evaluación 360 - Todos los empleados evalúan',
                    'tipo_evaluacion' => 'Evaluación 360',
                    'fecha_inicio'    => $fecha,
                    'fecha_fin'       => $fecha,
                    'estado'          => 'Planificada'
                ]);

                if (!$idEvaluacion) {
                    return $this->response->setJSON(['success' => false, 'message' => 'Error al crear la evaluación padre']);
                }

                // 2. insertBatch: un registro por cada evaluador
                $batchData = [];
                foreach ($evaluadores as $evaluador) {
                    $batchData[] = [
                        'id_evaluacion'    => $idEvaluacion,
                        'id_empleado'      => $empleadoId,
                        'id_evaluador'     => $evaluador['id_empleado'],
                        'fecha_evaluacion' => $fecha,
                        'puntaje_total'    => null,
                        'observaciones'    => $observaciones ?: null
                    ];
                }
                $db->table('evaluaciones_empleados')->insertBatch($batchData);

                $total = count($batchData);

                $logModel = new LogSistemaModel();
                $logModel->registrarLog(
                    session()->get('id_usuario'),
                    'CREAR_EVALUACION_360',
                    'EVALUACIONES',
                    "Evaluación 360° creada con {$total} evaluadores"
                );

                return $this->response->setJSON([
                    'success' => true,
                    'message' => "Evaluación 360° creada exitosamente. Se generaron {$total} evaluaciones."
                ]);
            }

            return $this->response->setJSON(['success' => false, 'message' => 'Tipo de evaluación no válido. Use Autoevaluación o Evaluación 360.']);

        } catch (\Exception $e) {
            return $this->response->setJSON(['success' => false, 'message' => 'Error SQL: ' . $e->getMessage()]);
        }
    }

    /**
     * Actualizar evaluación (AJAX)
     */
    public function actualizarEvaluacion2()
    {
        try {
            $id = $this->request->getPost('id');
            $db = \Config\Database::connect();

            $data = [
                'puntaje_total' => $this->request->getPost('puntaje_total'),
                'observaciones' => $this->request->getPost('observaciones'),
                'fecha_evaluacion' => $this->request->getPost('fecha_evaluacion') ?: date('Y-m-d')
            ];

            $db->table('evaluaciones_empleados')->where('id_evaluacion_empleado', $id)->update($data);

            return $this->response->setJSON(['success' => true, 'message' => 'Evaluación actualizada']);

        } catch (\Exception $e) {
            return $this->response->setJSON(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
    }

    /**
     * Cambiar estado de evaluación (AJAX)
     */
    public function cambiarEstadoEvaluacion2()
    {
        try {
            $id = $this->request->getPost('id_evaluacion');
            $estado = $this->request->getPost('estado');

            // Validar que el estado sea uno de los permitidos por la BD
            $estadosValidos = ['Planificada', 'En curso', 'Finalizada'];
            if (!in_array($estado, $estadosValidos)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Estado no válido. Use: ' . implode(', ', $estadosValidos)
                ]);
            }

            $db = \Config\Database::connect();
            $ee = $db->table('evaluaciones_empleados')->where('id_evaluacion_empleado', $id)->get()->getRowArray();
            if ($ee) {
                $db->table('evaluaciones')->where('id_evaluacion', $ee['id_evaluacion'])->update(['estado' => $estado]);
            }

            return $this->response->setJSON(['success' => true, 'message' => 'Estado cambiado a ' . $estado]);

        } catch (\Exception $e) {
            return $this->response->setJSON(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
    }

    /**
     * Eliminar evaluación (AJAX)
     */
    public function eliminarEvaluacion2()
    {
        try {
            $id = $this->request->getPost('id_evaluacion');
            $db = \Config\Database::connect();
            $db->table('evaluaciones_empleados')->where('id_evaluacion_empleado', $id)->delete();

            return $this->response->setJSON(['success' => true, 'message' => 'Evaluación eliminada correctamente']);

        } catch (\Exception $e) {
            return $this->response->setJSON(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
    }

    /**
     * Obtener empleados para los selects (AJAX)
     */
    public function obtenerEmpleadosJSON()
    {
        try {
            $empleadoModel = new EmpleadoModel();
            $tipo = $this->request->getGet('tipo');

            if ($tipo) {
                $empleados = $empleadoModel->where('tipo_empleado', $tipo)->where('activo', 1)
                    ->select('id_empleado, nombres, apellidos, tipo_empleado, departamento')->findAll();
            } else {
                $empleados = $empleadoModel->where('activo', 1)
                    ->select('id_empleado, nombres, apellidos, tipo_empleado, departamento')->findAll();
            }

            return $this->response->setJSON(['success' => true, 'empleados' => $empleados]);

        } catch (\Exception $e) {
            return $this->response->setJSON(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
    }

    /**
     * Eliminar evaluaciones masivamente (AJAX)
     */
    public function eliminarEvaluacionesMasivo()
    {
        try {
            $ids = $this->request->getPost('ids');

            if (empty($ids) || !is_array($ids)) {
                return $this->response->setJSON(['success' => false, 'message' => 'No se recibieron IDs para eliminar']);
            }

            $db = \Config\Database::connect();
            $db->table('evaluaciones_empleados')
               ->whereIn('id_evaluacion_empleado', $ids)
               ->delete();

            $total = count($ids);

            $logModel = new LogSistemaModel();
            $logModel->registrarLog(
                session()->get('id_usuario'),
                'ELIMINAR_EVALUACIONES_MASIVO',
                'EVALUACIONES',
                "Se eliminaron {$total} evaluaciones"
            );

            return $this->response->setJSON([
                'success' => true,
                'message' => "{$total} evaluación(es) eliminada(s) correctamente"
            ]);

        } catch (\Exception $e) {
            return $this->response->setJSON(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
    }

    /**
     * Obtener resultados globales consolidados de un empleado (AJAX)
     * Algoritmo: Autoevaluación(/25) + Promedio360(/25) = Calificación Final(/50) → %
     */
    public function obtenerResultadosGlobales($empleadoId)
    {
        try {
            $db = \Config\Database::connect();
            $empleadoModel = new EmpleadoModel();

            // Obtener datos del empleado
            $empleado = $empleadoModel->find($empleadoId);
            if (!$empleado) {
                return $this->response->setJSON(['success' => false, 'message' => 'Empleado no encontrado']);
            }

            $nombreEmpleado = ($empleado['nombres'] ?? '') . ' ' . ($empleado['apellidos'] ?? '');

            // 1. Buscar autoevaluación completada (evaluador = evaluado)
            $autoeval = $db->table('evaluaciones_empleados ee')
                ->select('ee.puntaje_total')
                ->join('evaluaciones e', 'e.id_evaluacion = ee.id_evaluacion')
                ->where('ee.id_empleado', $empleadoId)
                ->where('ee.id_evaluador', $empleadoId)
                ->where('ee.puntaje_total >', 0)
                ->orderBy('ee.fecha_evaluacion', 'DESC')
                ->get()
                ->getRowArray();

            $puntajeAuto = $autoeval ? floatval($autoeval['puntaje_total']) : null;

            // 2. Buscar evaluaciones 360 completadas (donde es evaluado, evaluador != evaluado)
            $eval360 = $db->table('evaluaciones_empleados ee')
                ->select('AVG(ee.puntaje_total) as promedio, COUNT(*) as total')
                ->join('evaluaciones e', 'e.id_evaluacion = ee.id_evaluacion')
                ->where('ee.id_empleado', $empleadoId)
                ->where('ee.id_evaluador !=', $empleadoId)
                ->where('ee.puntaje_total >', 0)
                ->get()
                ->getRowArray();

            $promedio360 = ($eval360 && $eval360['total'] > 0) ? round(floatval($eval360['promedio']), 1) : null;
            $total360 = $eval360 ? intval($eval360['total']) : 0;

            // 3. Calificación final
            $calAuto = $puntajeAuto ?? 0;
            $cal360 = $promedio360 ?? 0;
            $calificacionFinal = round($calAuto + $cal360, 1);
            $porcentaje = round(($calificacionFinal / 50) * 100, 1);

            if ($puntajeAuto === null && $promedio360 === null) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'No hay evaluaciones completadas para este empleado'
                ]);
            }

            return $this->response->setJSON([
                'success' => true,
                'resultados' => [
                    'nombre_empleado'    => $nombreEmpleado,
                    'autoevaluacion'     => $puntajeAuto,
                    'promedio_360'       => $promedio360,
                    'total_360'          => $total360,
                    'calificacion_final' => $calificacionFinal,
                    'porcentaje'         => $porcentaje
                ]
            ]);

        } catch (\Exception $e) {
            return $this->response->setJSON(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
    }

    // ================================================================
    // EVALUACIONES ESTUDIANTILES — Generación y gestión de tokens
    // ================================================================

    /**
     * Vista principal del panel de evaluaciones estudiantiles
     */
    public function evaluacionesEstudiantiles()
    {
        $db = \Config\Database::connect();

        // Evaluaciones activas para el select
        $evaluaciones = $db->table('evaluaciones')
            ->select('id_evaluacion, nombre')
            ->orderBy('id_evaluacion', 'DESC')
            ->get()
            ->getResultArray();

        // Docentes activos para el select
        $docentes = $db->table('empleados')
            ->select('id_empleado, nombres, apellidos')
            ->where('estado', 'Activo')
            ->whereIn('tipo_empleado', ['Docente', 'DOCENTE'])
            ->orderBy('apellidos', 'ASC')
            ->get()
            ->getResultArray();

        // Si no hay docentes con tipo estricto, traer todos los activos
        if (empty($docentes)) {
            $docentes = $db->table('empleados')
                ->select('id_empleado, nombres, apellidos')
                ->where('estado', 'Activo')
                ->orderBy('apellidos', 'ASC')
                ->get()
                ->getResultArray();
        }

        $data = [
            'titulo'       => 'Evaluaciones Estudiantiles',
            'usuario'      => [
                'nombres'  => session()->get('nombres'),
                'apellidos' => session()->get('apellidos'),
                'rol'      => session()->get('nombre_rol')
            ],
            'evaluaciones' => $evaluaciones,
            'docentes'     => $docentes,
        ];

        return view('Roles/AdminTH/evaluaciones_estudiantiles', $data);
    }

    /**
     * Generar N tokens para evaluación estudiantil (POST AJAX)
     */
    public function generarTokensEstudiantiles()
    {
        if (!$this->request->is('post')) {
            return $this->response->setJSON(['success' => false, 'message' => 'Método no permitido']);
        }

        try {
            $idEvaluacion = (int)$this->request->getPost('id_evaluacion');
            $idDocente    = (int)$this->request->getPost('id_docente');
            $grupoCurso   = trim($this->request->getPost('grupo_curso'));
            $cantidad     = (int)$this->request->getPost('cantidad');
            $diasVigencia = (int)($this->request->getPost('dias_vigencia') ?: 7);

            if (!$idEvaluacion || !$idDocente || !$grupoCurso || $cantidad < 1) {
                return $this->response->setJSON(['success' => false, 'message' => 'Todos los campos son obligatorios.']);
            }

            if ($cantidad > 100) {
                return $this->response->setJSON(['success' => false, 'message' => 'Máximo 100 links por generación.']);
            }

            $fechaExpiracion = date('Y-m-d H:i:s', strtotime("+{$diasVigencia} days"));

            $tokenModel = new \App\Models\TokenEvaluacionModel();
            $tokens = $tokenModel->generarTokens($idEvaluacion, $idDocente, $grupoCurso, $cantidad, $fechaExpiracion);

            return $this->response->setJSON([
                'success' => true,
                'message' => "Se generaron {$cantidad} links exitosamente. Vigencia: {$diasVigencia} días.",
                'total_generados' => count($tokens),
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Error al generar tokens estudiantiles: ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
    }

    /**
     * Obtener tokens agrupados por docente/curso (GET AJAX)
     */
    public function obtenerTokensEstudiantiles()
    {
        try {
            $tokenModel = new \App\Models\TokenEvaluacionModel();
            $grupos = $tokenModel->getTokensAgrupados();

            return $this->response->setJSON([
                'success' => true,
                'grupos'  => $grupos,
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Error al obtener tokens: ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
    }


    /**
     * Endpoint API para gráficos de inasistencias en el dashboard AdminTH
     */
    /**
     * Generar reporte de inasistencias con filtros
     */
    public function reporteInasistencias()
    {
        $db = \Config\Database::connect();
        
        // Obtener filtros de la petición
        $fechaInicio = $this->request->getGet('fecha_inicio');
        $fechaFin    = $this->request->getGet('fecha_fin');
        $departamento = $this->request->getGet('departamento');
        $empleadoId  = $this->request->getGet('empleado_id');
        
        // Construir query con filtros
        $builder = $db->table('inasistencias i')
            ->select('i.*, e.nombres, e.apellidos, u.cedula, e.departamento, e.tipo_empleado, u.email as correo')
            ->join('empleados e', 'e.id_empleado = i.empleado_id', 'left')
            ->join('usuarios u', 'u.id_usuario = e.id_usuario', 'left')
            ->orderBy('i.fecha_inasistencia', 'DESC');
        
        // Aplicar filtros
        if ($fechaInicio) {
            $builder->where('i.fecha_inasistencia >=', $fechaInicio);
        }
        if ($fechaFin) {
            $builder->where('i.fecha_inasistencia <=', $fechaFin);
        }
        if ($departamento && $departamento !== 'todos') {
            $builder->where('e.departamento', $departamento);
        }
        if ($empleadoId) {
            $builder->where('i.empleado_id', $empleadoId);
        }
        
        $inasistencias = $builder->get()->getResultArray();
        
        // Calcular estadísticas del reporte
        $totalInasistencias = count($inasistencias);
        $justificadas = array_filter($inasistencias, fn($i) => $i['justificada'] == 1);
        $sinJustificar = $totalInasistencias - count($justificadas);
        
        $data = [
            'titulo' => 'Reporte de Inasistencias',
            'usuario' => [
                'nombres' => session()->get('nombres'),
                'apellidos' => session()->get('apellidos'),
                'rol' => session()->get('nombre_rol')
            ],
            'inasistencias' => $inasistencias,
            'filtros' => [
                'fecha_inicio' => $fechaInicio,
                'fecha_fin' => $fechaFin,
                'departamento' => $departamento,
                'empleado_id' => $empleadoId
            ],
            'estadisticas' => [
                'total' => $totalInasistencias,
                'justificadas' => count($justificadas),
                'sin_justificar' => $sinJustificar,
                'tasa_justificacion' => $totalInasistencias > 0 ? round((count($justificadas) / $totalInasistencias) * 100, 2) : 0
            ],
            'departamentos' => $db->table('empleados')->select('departamento')->distinct()->get()->getResultArray()
        ];
        
        return view('Roles/AdminTH/inasistencias/reporte', $data);
    }
    
    /**
     * Exportar reporte de inasistencias a Excel/PDF
     */
    public function exportarInasistencias()
    {
        $db = \Config\Database::connect();
        
        // Obtener filtros
        $fechaInicio = $this->request->getGet('fecha_inicio');
        $fechaFin    = $this->request->getGet('fecha_fin');
        $departamento = $this->request->getGet('departamento');
        $formato     = $this->request->getGet('formato') ?? 'excel';
        
        // Construir query
        $builder = $db->table('inasistencias i')
            ->select('i.*, e.nombres, e.apellidos, u.cedula, e.departamento, e.tipo_empleado')
            ->join('empleados e', 'e.id_empleado = i.empleado_id', 'left')
            ->join('usuarios u', 'u.id_usuario = e.id_usuario', 'left')
            ->orderBy('i.fecha_inasistencia', 'DESC');
        
        if ($fechaInicio) $builder->where('i.fecha_inasistencia >=', $fechaInicio);
        if ($fechaFin) $builder->where('i.fecha_inasistencia <=', $fechaFin);
        if ($departamento && $departamento !== 'todos') $builder->where('e.departamento', $departamento);
        
        $inasistencias = $builder->get()->getResultArray();
        
        if ($formato === 'excel') {
            // Generar CSV
            $filename = 'reporte_inasistencias_' . date('Y-m-d_His') . '.csv';
            header('Content-Type: text/csv; charset=utf-8');
            header('Content-Disposition: attachment; filename="' . $filename . '"');
            
            $output = fopen('php://output', 'w');
            fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF)); // UTF-8 BOM
            
            // Encabezados
            fputcsv($output, ['ID', 'Empleado', 'Cédula', 'Departamento', 'Tipo Empleado', 'Fecha', 'Hora', 'Tipo Inasistencia', 'Motivo', 'Justificada']);
            
            // Datos
            foreach ($inasistencias as $i) {
                fputcsv($output, [
                    $i['id'],
                    $i['apellidos'] . ' ' . $i['nombres'],
                    $i['cedula'],
                    $i['departamento'],
                    $i['tipo_empleado'],
                    $i['fecha_inasistencia'],
                    $i['hora_inasistencia'] ?? 'N/A',
                    $i['tipo_inasistencia'],
                    $i['motivo'],
                    $i['justificada'] == 1 ? 'Sí' : 'No'
                ]);
            }
            
            fclose($output);
            exit;
        }
        
        // Si es PDF, retornar vista para imprimir
        return view('Roles/AdminTH/inasistencias/reporte_pdf', [
            'inasistencias' => $inasistencias,
            'fecha_generacion' => date('d/m/Y H:i:s')
        ]);
    }
    
    /**
     * Revisar y aprobar/rechazar justificación
     */
    public function revisarJustificacion()
    {
        if (!$this->request->isAJAX()) {
            return redirect()->back();
        }
        
        $inasistenciaId = $this->request->getPost('inasistencia_id');
        $accion = $this->request->getPost('accion'); // 'aprobar' o 'rechazar'
        $observaciones = $this->request->getPost('observaciones') ?? '';
        
        if (!$inasistenciaId || !$accion) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Datos incompletos'
            ]);
        }
        
        $db = \Config\Database::connect();
        
        // Verificar que la inasistencia existe
        $inasistencia = $db->table('inasistencias')
            ->where('id', $inasistenciaId)
            ->get()
            ->getRowArray();
        
        if (!$inasistencia) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Inasistencia no encontrada'
            ]);
        }
        
        // Actualizar estado según la acción
        $updateData = [
            'fecha_revision' => date('Y-m-d H:i:s'),
            'revisado_por' => session()->get('id_usuario')
        ];
        
        if ($accion === 'aprobar') {
            $updateData['justificada'] = 1;
            $updateData['tipo_inasistencia'] = 'Justificada';
            $updateData['observaciones_revision'] = $observaciones;
        } else {
            $updateData['justificada'] = 0;
            $updateData['tipo_inasistencia'] = 'Injustificada';
            $updateData['observaciones_revision'] = $observaciones;
        }
        
        $resultado = $db->table('inasistencias')
            ->where('id', $inasistenciaId)
            ->update($updateData);
        
        if ($resultado) {
            // Registrar en log
            $db->table('logs_sistema')->insert([
                'usuario_id' => session()->get('id_usuario'),
                'accion' => 'REVISION_JUSTIFICACION',
                'descripcion' => "Justificación {$accion}da para inasistencia ID: {$inasistenciaId}",
                'fecha' => date('Y-m-d H:i:s')
            ]);
            
            return $this->response->setJSON([
                'success' => true,
                'message' => $accion === 'aprobar' ? 'Justificación aprobada exitosamente' : 'Justificación rechazada'
            ]);
        }
        
        return $this->response->setJSON([
            'success' => false,
            'message' => 'Error al actualizar el registro'
        ]);
    }
    
    /**
     * Obtener datos de justificación para modal
     */
    public function obtenerJustificacion()
    {
        if (!$this->request->isAJAX()) {
            return redirect()->back();
        }
        
        $inasistenciaId = $this->request->getGet('id');
        
        if (!$inasistenciaId) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'ID no proporcionado'
            ]);
        }
        
        $db = \Config\Database::connect();
        
        $inasistencia = $db->table('inasistencias i')
            ->select('i.*, e.nombres, e.apellidos, u.cedula, e.departamento, e.tipo_empleado')
            ->join('empleados e', 'e.id_empleado = i.empleado_id', 'left')
            ->join('usuarios u', 'u.id_usuario = e.id_usuario', 'left')
            ->where('i.id', $inasistenciaId)
            ->get()
            ->getRowArray();
        
        if (!$inasistencia) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Inasistencia no encontrada'
            ]);
        }
        
        return $this->response->setJSON([
            'success' => true,
            'data' => $inasistencia
        ]);
    }

    /**
     * Retorna las inasistencias del mes actual de un empleado (AJAX)
     */
    public function getDetallesInasistenciasEmpleadoMes(int $idEmpleado)
    {
        if (!$this->request->isAJAX()) {
            return redirect()->back();
        }

        try {
            $db         = \Config\Database::connect();
            $inicioMes  = date('Y-m-01');
            $finMes     = date('Y-m-t');

            $inasistencias = $db->table('inasistencias')
                ->select('id, fecha_inasistencia, tipo_inasistencia, motivo, justificada')
                ->where('empleado_id', $idEmpleado)
                ->where('fecha_inasistencia >=', $inicioMes)
                ->where('fecha_inasistencia <=', $finMes)
                ->orderBy('fecha_inasistencia', 'ASC')
                ->get()
                ->getResultArray();

            return $this->response->setJSON([
                'success' => true,
                'data'    => $inasistencias
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al obtener los datos: ' . $e->getMessage()
            ]);
        }
    }

    public function getEstadisticasGlobalesInasistencias()
    {
        try {
            $db = \Config\Database::connect();
            
            // Inasistencias por Departamento
            $deptos = $db->table('inasistencias i')
                ->select('COALESCE(e.departamento, "Sin asignar") as departamento, COUNT(i.id) as cantidad')
                ->join('empleados e', 'e.id_empleado = i.empleado_id', 'left')
                ->groupBy('e.departamento')
                ->orderBy('cantidad', 'DESC')
                ->get()->getResultArray();
                
            $labelsDeptos  = [];
            $valoresDeptos = [];
            foreach ($deptos as $d) {
                $labelsDeptos[]  = $d['departamento'];
                $valoresDeptos[] = (int) $d['cantidad'];
            }

            // Tendencia últimos 7 días (rellena ceros para días sin registros)
            $fechaHace7Dias = date('Y-m-d', strtotime('-6 days'));
            $rows = $db->table('inasistencias')
                ->select('DATE(fecha_inasistencia) as dia, COUNT(id) as cantidad')
                ->where('fecha_inasistencia >=', $fechaHace7Dias)
                ->groupBy('dia')
                ->orderBy('dia', 'ASC')
                ->get()->getResultArray();

            // Indexar por fecha para búsqueda rápida
            $porFecha = [];
            foreach ($rows as $r) {
                $porFecha[$r['dia']] = (int) $r['cantidad'];
            }

            $labelsTendencia  = [];
            $valoresTendencia = [];
            for ($i = 6; $i >= 0; $i--) {
                $diaStr            = date('Y-m-d', strtotime("-{$i} days"));
                $labelsTendencia[] = date('d/m', strtotime("-{$i} days"));
                $valoresTendencia[] = $porFecha[$diaStr] ?? 0;
            }

            return $this->response->setJSON([
                'success'      => true,
                'departamentos' => ['labels' => $labelsDeptos,  'valores' => $valoresDeptos],
                'tendencia'     => ['labels' => $labelsTendencia, 'valores' => $valoresTendencia]
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}