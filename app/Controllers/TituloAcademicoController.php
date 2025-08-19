<?php

namespace App\Controllers;

use App\Models\TituloAcademicoModel;
use App\Models\EmpleadoModel;

class TituloAcademicoController extends BaseController
{
    protected $tituloAcademicoModel;
    protected $empleadoModel;

    public function __construct()
    {
        $this->tituloAcademicoModel = new TituloAcademicoModel();
        $this->empleadoModel = new EmpleadoModel();
    }

    /**
     * Vista principal de títulos académicos
     */
    public function index()
    {
        $data = [
            'titulo' => 'Gestión de Títulos Académicos',
            'titulos' => $this->tituloAcademicoModel->getTitulosConEmpleado()
        ];

        return view('titulos_academicos/index', $data);
    }

    /**
     * Formulario para crear nuevo título académico
     */
    public function crear()
    {
        $data = [
            'titulo' => 'Agregar Nuevo Título Académico',
            'empleados' => $this->empleadoModel->where('activo', 1)->findAll()
        ];

        return view('titulos_academicos/crear', $data);
    }

    /**
     * Guardar nuevo título académico
     */
    public function guardar()
    {
        // Validar datos del formulario
        $rules = [
            'empleado_id'      => 'required|integer',
            'universidad'      => 'required|min_length[2]|max_length[255]',
            'tipo_titulo'      => 'required|in_list[Tercer nivel,Cuarto nivel,Doctorado/PhD]',
            'nombre_titulo'    => 'required|min_length[5]|max_length[255]',
            'fecha_obtencion'  => 'required|valid_date',
            'pais'             => 'permit_empty|min_length[2]|max_length[100]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Validar que la fecha de obtención no sea futura
        if (!$this->tituloAcademicoModel->validarFechaObtencion($this->request->getPost('fecha_obtencion'))) {
            return redirect()->back()->withInput()->with('error', 'La fecha de obtención no puede ser futura');
        }

        // Procesar archivo si se subió
        $archivoCertificado = '';
        $file = $this->request->getFile('archivo_certificado');
        
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            $file->move(WRITEPATH . 'uploads/titulos/', $newName);
            $archivoCertificado = $newName;
        }

        // Preparar datos
        $datos = [
            'empleado_id'      => $this->request->getPost('empleado_id'),
            'universidad'      => $this->request->getPost('universidad'),
            'tipo_titulo'      => $this->request->getPost('tipo_titulo'),
            'nombre_titulo'    => $this->request->getPost('nombre_titulo'),
            'fecha_obtencion'  => $this->request->getPost('fecha_obtencion'),
            'pais'             => $this->request->getPost('pais'),
            'archivo_certificado' => $archivoCertificado
        ];

        // Guardar título académico
        if ($this->tituloAcademicoModel->insert($datos)) {
            return redirect()->to('/titulos-academicos')->with('mensaje', 'Título académico agregado exitosamente');
        } else {
            return redirect()->back()->withInput()->with('error', 'Error al agregar el título académico');
        }
    }

    /**
     * Formulario para editar título académico
     */
    public function editar($id)
    {
        $titulo = $this->tituloAcademicoModel->find($id);
        
        if (!$titulo) {
            return redirect()->to('/titulos-academicos')->with('error', 'Título académico no encontrado');
        }

        $data = [
            'titulo' => 'Editar Título Académico',
            'titulo_academico' => $titulo,
            'empleados' => $this->empleadoModel->where('activo', 1)->findAll()
        ];

        return view('titulos_academicos/editar', $data);
    }

    /**
     * Actualizar título académico
     */
    public function actualizar($id)
    {
        $titulo = $this->tituloAcademicoModel->find($id);
        
        if (!$titulo) {
            return redirect()->to('/titulos-academicos')->with('error', 'Título académico no encontrado');
        }

        // Validar datos del formulario
        $rules = [
            'empleado_id'      => 'required|integer',
            'universidad'      => 'required|min_length[2]|max_length[255]',
            'tipo_titulo'      => 'required|in_list[Tercer nivel,Cuarto nivel,Doctorado/PhD]',
            'nombre_titulo'    => 'required|min_length[5]|max_length[255]',
            'fecha_obtencion'  => 'required|valid_date',
            'pais'             => 'permit_empty|min_length[2]|max_length[100]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Validar que la fecha de obtención no sea futura
        if (!$this->tituloAcademicoModel->validarFechaObtencion($this->request->getPost('fecha_obtencion'))) {
            return redirect()->back()->withInput()->with('error', 'La fecha de obtención no puede ser futura');
        }

        // Procesar archivo si se subió uno nuevo
        $archivoCertificado = $titulo['archivo_certificado']; // Mantener archivo existente
        $file = $this->request->getFile('archivo_certificado');
        
        if ($file && $file->isValid() && !$file->hasMoved()) {
            // Eliminar archivo anterior si existe
            if ($archivoCertificado && file_exists(WRITEPATH . 'uploads/titulos/' . $archivoCertificado)) {
                unlink(WRITEPATH . 'uploads/titulos/' . $archivoCertificado);
            }
            
            $newName = $file->getRandomName();
            $file->move(WRITEPATH . 'uploads/titulos/', $newName);
            $archivoCertificado = $newName;
        }

        // Preparar datos
        $datos = [
            'empleado_id'      => $this->request->getPost('empleado_id'),
            'universidad'      => $this->request->getPost('universidad'),
            'tipo_titulo'      => $this->request->getPost('tipo_titulo'),
            'nombre_titulo'    => $this->request->getPost('nombre_titulo'),
            'fecha_obtencion'  => $this->request->getPost('fecha_obtencion'),
            'pais'             => $this->request->getPost('pais'),
            'archivo_certificado' => $archivoCertificado
        ];

        // Actualizar título académico
        if ($this->tituloAcademicoModel->update($id, $datos)) {
            return redirect()->to('/titulos-academicos')->with('mensaje', 'Título académico actualizado exitosamente');
        } else {
            return redirect()->back()->withInput()->with('error', 'Error al actualizar el título académico');
        }
    }

    /**
     * Eliminar título académico
     */
    public function eliminar($id)
    {
        $titulo = $this->tituloAcademicoModel->find($id);
        
        if (!$titulo) {
            return redirect()->to('/titulos-academicos')->with('error', 'Título académico no encontrado');
        }

        // Eliminar archivo si existe
        if ($titulo['archivo_certificado'] && file_exists(WRITEPATH . 'uploads/titulos/' . $titulo['archivo_certificado'])) {
            unlink(WRITEPATH . 'uploads/titulos/' . $titulo['archivo_certificado']);
        }

        if ($this->tituloAcademicoModel->delete($id)) {
            return redirect()->to('/titulos-academicos')->with('mensaje', 'Título académico eliminado exitosamente');
        } else {
            return redirect()->to('/titulos-academicos')->with('error', 'Error al eliminar el título académico');
        }
    }

    /**
     * Vista de títulos de un empleado específico
     */
    public function porEmpleado($empleadoId)
    {
        $empleado = $this->empleadoModel->find($empleadoId);
        
        if (!$empleado) {
            return redirect()->to('/empleados')->with('error', 'Empleado no encontrado');
        }

        $data = [
            'titulo' => 'Títulos Académicos de ' . $empleado['nombres'] . ' ' . $empleado['apellidos'],
            'empleado' => $empleado,
            'titulos' => $this->tituloAcademicoModel->getTitulosPorEmpleado($empleadoId)
        ];

        return view('titulos_academicos/por_empleado', $data);
    }

    /**
     * Descargar certificado
     */
    public function descargarCertificado($id)
    {
        $titulo = $this->tituloAcademicoModel->find($id);
        
        if (!$titulo || !$titulo['archivo_certificado']) {
            return redirect()->to('/titulos-academicos')->with('error', 'Archivo no encontrado');
        }

        $rutaArchivo = WRITEPATH . 'uploads/titulos/' . $titulo['archivo_certificado'];
        
        if (!file_exists($rutaArchivo)) {
            return redirect()->to('/titulos-academicos')->with('error', 'Archivo no encontrado en el servidor');
        }

        return $this->response->download($rutaArchivo, null);
    }

    /**
     * Obtener estadísticas de títulos académicos (AJAX)
     */
    public function getEstadisticas()
    {
        $estadisticas = $this->tituloAcademicoModel->getEstadisticasTitulos();
        return $this->response->setJSON($estadisticas);
    }

    /**
     * Buscar títulos por universidad (AJAX)
     */
    public function buscarPorUniversidad()
    {
        $universidad = $this->request->getGet('universidad');
        $titulos = $this->tituloAcademicoModel->buscarPorUniversidad($universidad);
        return $this->response->setJSON($titulos);
    }

    /**
     * Obtener títulos recientes (AJAX)
     */
    public function getTitulosRecientes()
    {
        $titulos = $this->tituloAcademicoModel->getTitulosRecientes();
        return $this->response->setJSON($titulos);
    }
}
