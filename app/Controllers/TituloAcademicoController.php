<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\TituloAcademicoModel;
use App\Models\EmpleadoModel;

class TituloAcademicoController extends Controller
{
    protected $tituloAcademicoModel;
    protected $empleadoModel;

    public function __construct()
    {
        $this->tituloAcademicoModel = new TituloAcademicoModel();
        $this->empleadoModel = new EmpleadoModel();
    }

    /**
     * Vista principal de títulos académicos (para administradores)
     */
    public function index()
    {
        $data = [
            'title' => 'Gestión de Títulos Académicos',
            'titulos' => $this->tituloAcademicoModel->getTitulosCompletos(),
            'empleados' => $this->empleadoModel->getEmpleadosActivos(),
            'estadisticas' => $this->tituloAcademicoModel->getEstadisticas()
        ];

        return view('titulos_academicos/index', $data);
    }

    /**
     * Formulario para crear nuevo título académico
     */
    public function crear()
    {
        $data = [
            'title' => 'Nuevo Título Académico',
            'empleados' => $this->empleadoModel->getEmpleadosActivos(),
            'tipos' => ['Tercer Nivel', 'Cuarto Nivel', 'Ph.D.', 'Doctorado', 'Maestría', 'Especialización', 'Otro'],
            'paises' => $this->getPaises()
        ];

        return view('titulos_academicos/crear', $data);
    }

    /**
     * Guardar nuevo título académico
     */
    public function guardar()
    {
        // Validar datos
        $rules = [
            'empleado_id' => 'required|integer|greater_than[0]',
            'universidad' => 'required|min_length[2]|max_length[255]',
            'tipo_titulo' => 'required|in_list[Tercer Nivel,Cuarto Nivel,Ph.D.,Doctorado,Maestría,Especialización,Otro]',
            'nombre_titulo' => 'required|min_length[5]|max_length[255]',
            'fecha_obtencion' => 'required|valid_date',
            'pais' => 'permit_empty|min_length[2]|max_length[100]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Procesar archivo si se subió
        $archivoTitulo = '';
        $file = $this->request->getFile('archivo_titulo');
        
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            $file->move(WRITEPATH . 'uploads/titulos_academicos/', $newName);
            $archivoTitulo = $newName;
        }

        // Guardar título académico
        $datosTitulo = [
            'id_empleado' => $this->request->getPost('empleado_id'),
            'universidad' => $this->request->getPost('universidad'),
            'tipo_titulo' => $this->request->getPost('tipo_titulo'),
            'nombre_titulo' => $this->request->getPost('nombre_titulo'),
            'fecha_obtencion' => $this->request->getPost('fecha_obtencion'),
            'pais' => $this->request->getPost('pais'),
            'archivo_titulo' => $archivoTitulo,
            'observaciones' => $this->request->getPost('observaciones'),
            'creado_por' => session()->get('id_usuario')
        ];

        $idTitulo = $this->tituloAcademicoModel->insert($datosTitulo);

        if ($idTitulo) {
            return redirect()->to('/titulos-academicos')->with('mensaje', 'Título académico registrado exitosamente');
        } else {
            return redirect()->back()->withInput()->with('error', 'Error al registrar el título académico');
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
            'title' => 'Editar Título Académico',
            'titulo' => $titulo,
            'empleados' => $this->empleadoModel->getEmpleadosActivos(),
            'tipos' => ['Tercer Nivel', 'Cuarto Nivel', 'Ph.D.', 'Doctorado', 'Maestría', 'Especialización', 'Otro'],
            'paises' => $this->getPaises()
        ];

        return view('titulos_academicos/editar', $data);
    }

    /**
     * Actualizar título académico
     */
    public function actualizar($id)
    {
        // Validar datos
        $rules = [
            'empleado_id' => 'required|integer|greater_than[0]',
            'universidad' => 'required|min_length[2]|max_length[255]',
            'tipo_titulo' => 'required|in_list[Tercer Nivel,Cuarto Nivel,Ph.D.,Doctorado,Maestría,Especialización,Otro]',
            'nombre_titulo' => 'required|min_length[5]|max_length[255]',
            'fecha_obtencion' => 'required|valid_date',
            'pais' => 'permit_empty|min_length[2]|max_length[100]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Procesar archivo si se subió
        $archivoTitulo = '';
        $file = $this->request->getFile('archivo_titulo');
        
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            $file->move(WRITEPATH . 'uploads/titulos_academicos/', $newName);
            $archivoTitulo = $newName;
        }

        // Preparar datos
        $datos = [
            'id_empleado' => $this->request->getPost('empleado_id'),
            'universidad' => $this->request->getPost('universidad'),
            'tipo_titulo' => $this->request->getPost('tipo_titulo'),
            'nombre_titulo' => $this->request->getPost('nombre_titulo'),
            'fecha_obtencion' => $this->request->getPost('fecha_obtencion'),
            'pais' => $this->request->getPost('pais'),
            'observaciones' => $this->request->getPost('observaciones')
        ];

        if ($archivoTitulo) {
            $datos['archivo_titulo'] = $archivoTitulo;
        }

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
        if ($this->tituloAcademicoModel->delete($id)) {
            return redirect()->to('/titulos-academicos')->with('mensaje', 'Título académico eliminado exitosamente');
        } else {
            return redirect()->to('/titulos-academicos')->with('error', 'Error al eliminar el título académico');
        }
    }

    /**
     * Ver títulos académicos de un empleado específico
     */
    public function empleado($idEmpleado)
    {
        $empleado = $this->empleadoModel->find($idEmpleado);
        
        if (!$empleado) {
            return redirect()->to('/titulos-academicos')->with('error', 'Empleado no encontrado');
        }

        $data = [
            'title' => 'Títulos Académicos de ' . $empleado['nombres'] . ' ' . $empleado['apellidos'],
            'empleado' => $empleado,
            'titulos' => $this->tituloAcademicoModel->getTitulosPorEmpleado($idEmpleado),
            'estadisticas' => $this->tituloAcademicoModel->getEstadisticasPorEmpleado($idEmpleado)
        ];

        return view('titulos_academicos/empleado', $data);
    }

    /**
     * Obtener estadísticas de títulos académicos
     */
    public function getEstadisticas()
    {
        $estadisticas = $this->tituloAcademicoModel->getEstadisticas();
        return $this->response->setJSON($estadisticas);
    }

    /**
     * Buscar títulos académicos
     */
    public function buscar()
    {
        $termino = $this->request->getGet('termino');
        $tipo = $this->request->getGet('tipo');
        $universidad = $this->request->getGet('universidad');

        $resultados = $this->tituloAcademicoModel->buscarTitulos($termino, $tipo, $universidad);

        $data = [
            'title' => 'Resultados de Búsqueda',
            'resultados' => $resultados,
            'termino' => $termino,
            'tipo' => $tipo,
            'universidad' => $universidad,
            'tipos' => ['Tercer Nivel', 'Cuarto Nivel', 'Ph.D.', 'Doctorado', 'Maestría', 'Especialización', 'Otro']
        ];

        return view('titulos_academicos/buscar', $data);
    }

    /**
     * Reporte de títulos académicos por período
     */
    public function reporte()
    {
        $fechaInicio = $this->request->getGet('fecha_inicio') ?? date('Y-01-01');
        $fechaFin = $this->request->getGet('fecha_fin') ?? date('Y-12-31');

        $data = [
            'title' => 'Reporte de Títulos Académicos',
            'fecha_inicio' => $fechaInicio,
            'fecha_fin' => $fechaFin,
            'titulos' => $this->tituloAcademicoModel->getTitulosPorPeriodo($fechaInicio, $fechaFin),
            'estadisticas' => $this->tituloAcademicoModel->getEstadisticasPorPeriodo($fechaInicio, $fechaFin)
        ];

        return view('titulos_academicos/reporte', $data);
    }

    /**
     * Lista de países para el formulario
     */
    private function getPaises()
    {
        return [
            'Ecuador', 'Colombia', 'Perú', 'Chile', 'Argentina', 'Brasil', 'México', 'España', 'Estados Unidos',
            'Canadá', 'Reino Unido', 'Francia', 'Alemania', 'Italia', 'Países Bajos', 'Bélgica', 'Suiza',
            'Austria', 'Suecia', 'Noruega', 'Dinamarca', 'Finlandia', 'Polonia', 'República Checa', 'Hungría',
            'Rumania', 'Bulgaria', 'Grecia', 'Portugal', 'Irlanda', 'Islandia', 'Luxemburgo', 'Malta',
            'Chipre', 'Eslovenia', 'Eslovaquia', 'Croacia', 'Letonia', 'Lituania', 'Estonia', 'Otro'
        ];
    }
}
