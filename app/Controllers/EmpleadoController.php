<?php

namespace App\Controllers;

use App\Models\EmpleadoModel;
use App\Models\TituloAcademicoModel;
use App\Models\CapacitacionEmpleadoModel;
use App\Models\UsuarioModel;

class EmpleadoController extends BaseController
{
    protected $empleadoModel;
    protected $tituloAcademicoModel;
    protected $capacitacionModel;
    protected $usuarioModel;

    public function __construct()
    {
        $this->empleadoModel = new EmpleadoModel();
        $this->tituloAcademicoModel = new TituloAcademicoModel();
        $this->capacitacionModel = new CapacitacionEmpleadoModel();
        $this->usuarioModel = new UsuarioModel();
    }

    /**
     * Vista principal de empleados
     */
    public function index()
    {
        $data = [
            'titulo' => 'Gestión de Empleados',
            'empleados' => $this->empleadoModel->getEmpleadosConUsuario()
        ];

        return view('empleados/index', $data);
    }

    /**
     * Formulario para crear nuevo empleado
     */
    public function crear()
    {
        $data = [
            'titulo' => 'Crear Nuevo Empleado',
            'usuarios' => $this->usuarioModel->where('rol !=', 'SUPER_ADMIN')->findAll()
        ];

        return view('empleados/crear', $data);
    }

    /**
     * Guardar nuevo empleado
     */
    public function guardar()
    {
        // Validar datos del formulario
        $rules = [
            'usuario_id' => 'required|integer',
            'nombres' => 'required|min_length[2]|max_length[255]',
            'apellidos' => 'required|min_length[2]|max_length[255]',
            'cedula' => 'required|min_length[10]|max_length[20]|is_unique[empleados.cedula]',
            'tipo_empleado' => 'required|in_list[DOCENTE,ADMINISTRATIVO,DIRECTIVO,AUXILIAR]',
            'departamento' => 'required|min_length[2]|max_length[255]',
            'fecha_ingreso' => 'required|valid_date'
        ];

        // Validar tipo de docente si es DOCENTE
        if ($this->request->getPost('tipo_empleado') === 'DOCENTE') {
            $rules['tipo_docente'] = 'required|in_list[Tiempo completo,Medio tiempo,Tiempo parcial]';
        }

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Preparar datos
        $datos = [
            'usuario_id' => $this->request->getPost('usuario_id'),
            'nombres' => $this->request->getPost('nombres'),
            'apellidos' => $this->request->getPost('apellidos'),
            'cedula' => $this->request->getPost('cedula'),
            'tipo_empleado' => $this->request->getPost('tipo_empleado'),
            'departamento' => $this->request->getPost('departamento'),
            'fecha_ingreso' => $this->request->getPost('fecha_ingreso'),
            'salario' => $this->request->getPost('salario'),
            'activo' => 1
        ];

        // Asignar tipo de docente solo si es DOCENTE
        if ($this->request->getPost('tipo_empleado') === 'DOCENTE') {
            $datos['tipo_docente'] = $this->request->getPost('tipo_docente');
        }

        // Asignar departamento automático para DIRECTIVO y AUXILIAR
        if (in_array($this->request->getPost('tipo_empleado'), ['DIRECTIVO', 'AUXILIAR'])) {
            $datos['departamento'] = 'Departamento ITSI';
        }

        // Guardar empleado
        if ($this->empleadoModel->insert($datos)) {
            return redirect()->to('/empleados')->with('mensaje', 'Empleado creado exitosamente');
        } else {
            return redirect()->back()->withInput()->with('error', 'Error al crear el empleado');
        }
    }

    /**
     * Formulario para editar empleado
     */
    public function editar($id)
    {
        $empleado = $this->empleadoModel->find($id);
        
        if (!$empleado) {
            return redirect()->to('/empleados')->with('error', 'Empleado no encontrado');
        }

        $data = [
            'titulo' => 'Editar Empleado',
            'empleado' => $empleado,
            'usuarios' => $this->usuarioModel->where('rol !=', 'SUPER_ADMIN')->findAll()
        ];

        return view('empleados/editar', $data);
    }

    /**
     * Actualizar empleado
     */
    public function actualizar($id)
    {
        $empleado = $this->empleadoModel->find($id);
        
        if (!$empleado) {
            return redirect()->to('/empleados')->with('error', 'Empleado no encontrado');
        }

        // Validar datos del formulario
        $rules = [
            'usuario_id' => 'required|integer',
            'nombres' => 'required|min_length[2]|max_length[255]',
            'apellidos' => 'required|min_length[2]|max_length[255]',
            'cedula' => "required|min_length[10]|max_length[20]|is_unique[empleados.cedula,id,$id]",
            'tipo_empleado' => 'required|in_list[DOCENTE,ADMINISTRATIVO,DIRECTIVO,AUXILIAR]',
            'departamento' => 'required|min_length[2]|max_length[255]',
            'fecha_ingreso' => 'required|valid_date'
        ];

        // Validar tipo de docente si es DOCENTE
        if ($this->request->getPost('tipo_empleado') === 'DOCENTE') {
            $rules['tipo_docente'] = 'required|in_list[Tiempo completo,Medio tiempo,Tiempo parcial]';
        }

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Preparar datos
        $datos = [
            'usuario_id' => $this->request->getPost('usuario_id'),
            'nombres' => $this->request->getPost('nombres'),
            'apellidos' => $this->request->getPost('apellidos'),
            'cedula' => $this->request->getPost('cedula'),
            'tipo_empleado' => $this->request->getPost('tipo_empleado'),
            'departamento' => $this->request->getPost('departamento'),
            'fecha_ingreso' => $this->request->getPost('fecha_ingreso'),
            'salario' => $this->request->getPost('salario')
        ];

        // Asignar tipo de docente solo si es DOCENTE
        if ($this->request->getPost('tipo_empleado') === 'DOCENTE') {
            $datos['tipo_docente'] = $this->request->getPost('tipo_docente');
        } else {
            $datos['tipo_docente'] = null;
        }

        // Asignar departamento automático para DIRECTIVO y AUXILIAR
        if (in_array($this->request->getPost('tipo_empleado'), ['DIRECTIVO', 'AUXILIAR'])) {
            $datos['departamento'] = 'Departamento ITSI';
        }

        // Actualizar empleado
        if ($this->empleadoModel->update($id, $datos)) {
            return redirect()->to('/empleados')->with('mensaje', 'Empleado actualizado exitosamente');
        } else {
            return redirect()->back()->withInput()->with('error', 'Error al actualizar el empleado');
        }
    }

    /**
     * Eliminar empleado (desactivar)
     */
    public function eliminar($id)
    {
        if ($this->empleadoModel->update($id, ['activo' => 0])) {
            return redirect()->to('/empleados')->with('mensaje', 'Empleado eliminado exitosamente');
        } else {
            return redirect()->to('/empleados')->with('error', 'Error al eliminar el empleado');
        }
    }

    /**
     * Vista de perfil del empleado
     */
    public function perfil($id)
    {
        $empleado = $this->empleadoModel->find($id);
        
        if (!$empleado) {
            return redirect()->to('/empleados')->with('error', 'Empleado no encontrado');
        }

        $data = [
            'titulo' => 'Perfil del Empleado',
            'empleado' => $empleado,
            'titulos' => $this->tituloAcademicoModel->getTitulosPorEmpleado($id),
            'capacitaciones' => $this->capacitacionModel->getCapacitacionesPorEmpleado($id)
        ];

        return view('empleados/perfil', $data);
    }

    /**
     * Obtener departamentos según tipo de empleado (AJAX)
     */
    public function getDepartamentos()
    {
        $tipoEmpleado = $this->request->getGet('tipo_empleado');
        
        if ($tipoEmpleado === 'DOCENTE') {
            $departamentos = ['Departamento General'];
        } elseif ($tipoEmpleado === 'ADMINISTRATIVO') {
            $departamentos = [
                'Recursos Humanos',
                'Contabilidad',
                'Tecnología',
                'Académico',
                'Administrativo',
                'Vinculación'
            ];
        } else {
            $departamentos = ['Departamento ITSI'];
        }

        return $this->response->setJSON($departamentos);
    }

    /**
     * Obtener empleados por tipo (AJAX)
     */
    public function getEmpleadosPorTipo()
    {
        $tipo = $this->request->getGet('tipo');
        $empleados = $this->empleadoModel->getEmpleadosPorTipo($tipo);
        
        return $this->response->setJSON($empleados);
    }

    /**
     * Obtener empleados por departamento (AJAX)
     */
    public function getEmpleadosPorDepartamento()
    {
        $departamento = $this->request->getGet('departamento');
        $empleados = $this->empleadoModel->getEmpleadosPorDepartamento($departamento);
        
        return $this->response->setJSON($empleados);
    }
}
