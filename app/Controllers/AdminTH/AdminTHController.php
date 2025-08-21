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
    }

    /**
     * Dashboard del Admin Talento Humano
     */
    public function dashboard()
    {
        $data = [
            'titulo' => 'Dashboard Admin Talento Humano',
            'usuario' => [
                'nombres' => session()->get('nombres'),
                'apellidos' => session()->get('apellidos'),
                'rol' => session()->get('nombre_rol')
            ]
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
     * Mi perfil
     */
    public function perfil()
    {
        $data = [
            'titulo' => 'Mi Perfil',
            'usuario' => [
                'nombres' => session()->get('nombres'),
                'apellidos' => session()->get('apellidos'),
                'rol' => session()->get('nombre_rol')
            ]
        ];

        return view('Roles/AdminTH/perfil', $data);
    }

    /**
     * Configuración de cuenta
     */
    public function cuenta()
    {
        $data = [
            'titulo' => 'Configuración de Cuenta',
            'usuario' => [
                'nombres' => session()->get('nombres'),
                'apellidos' => session()->get('apellidos'),
                'rol' => session()->get('nombre_rol')
            ]
        ];

        return view('Roles/AdminTH/cuenta', $data);
    }
}
