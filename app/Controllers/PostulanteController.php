<?php

namespace App\Controllers;

use App\Models\PostulanteModel;
use App\Models\VacanteModel;

class PostulanteController extends BaseController
{
    protected $postulanteModel;
    protected $vacanteModel;

    public function __construct()
    {
        $this->postulanteModel = new PostulanteModel();
        $this->vacanteModel = new VacanteModel();
    }

    /**
     * Página de registro para nuevos postulantes
     */
    public function registro()
    {
        $data = [
            'title' => 'Registro de Postulante',
            'departamentos' => $this->vacanteModel->getDepartamentosDisponibles()
        ];

        return view('postulantes/registro', $data);
    }

    /**
     * Procesa el registro de un nuevo postulante
     */
    public function guardarRegistro()
    {
        // Validar datos del formulario
        $rules = [
            'cedula' => 'required|min_length[10]|max_length[10]',
            'nombres' => 'required|min_length[2]|max_length[100]',
            'apellidos' => 'required|min_length[2]|max_length[100]',
            'email' => 'required|valid_email',
            'telefono' => 'required|min_length[7]|max_length[15]',
            'direccion' => 'required|min_length[10]|max_length[300]',
            'fecha_nacimiento' => 'required|valid_date',
            'estado_civil' => 'required|in_list[Soltero,Casado,Divorciado,Viudo,Unión Libre]',
            'genero' => 'required|in_list[Masculino,Femenino,Otro]',
            'nivel_educativo' => 'required|in_list[Primaria,Secundaria,Técnico,Universitario,Postgrado]',
            'experiencia_laboral' => 'required|min_length[10]|max_length[1000]',
            'disponibilidad' => 'required|in_list[Inmediata,15 días,30 días,60 días,90 días]',
            'salario_esperado' => 'required|numeric|greater_than[0]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Verificar si la cédula o email ya existen
        if ($this->postulanteModel->cedulaExiste($this->request->getPost('cedula'))) {
            return redirect()->back()->withInput()->with('error', 'La cédula ya está registrada en el sistema');
        }

        if ($this->postulanteModel->emailExiste($this->request->getPost('email'))) {
            return redirect()->back()->withInput()->with('error', 'El email ya está registrado en el sistema');
        }

        // Procesar archivos subidos
        $archivos = $this->procesarArchivos();

        // Preparar datos para insertar
        $data = [
            'cedula' => $this->request->getPost('cedula'),
            'nombres' => $this->request->getPost('nombres'),
            'apellidos' => $this->request->getPost('apellidos'),
            'email' => $this->request->getPost('email'),
            'telefono' => $this->request->getPost('telefono'),
            'direccion' => $this->request->getPost('direccion'),
            'fecha_nacimiento' => $this->request->getPost('fecha_nacimiento'),
            'estado_civil' => $this->request->getPost('estado_civil'),
            'genero' => $this->request->getPost('genero'),
            'nivel_educativo' => $this->request->getPost('nivel_educativo'),
            'titulo_academico' => $this->request->getPost('titulo_academico'),
            'universidad' => $this->request->getPost('universidad'),
            'experiencia_laboral' => $this->request->getPost('experiencia_laboral'),
            'disponibilidad' => $this->request->getPost('disponibilidad'),
            'salario_esperado' => $this->request->getPost('salario_esperado'),
            'estado' => 'Activo'
        ];

        // Agregar rutas de archivos si se subieron
        if ($archivos) {
            $data = array_merge($data, $archivos);
        }

        // Insertar postulante
        if ($this->postulanteModel->insert($data)) {
            return redirect()->to('/postulantes/login')->with('success', 'Registro exitoso. Ya puedes iniciar sesión.');
        } else {
            return redirect()->back()->withInput()->with('error', 'Error al registrar. Intenta nuevamente.');
        }
    }

    /**
     * Página de login para postulantes
     */
    public function login()
    {
        $data = [
            'title' => 'Login - Postulante'
        ];

        return view('postulantes/login', $data);
    }

    /**
     * Autentica al postulante
     */
    public function autenticar()
    {
        $cedula = $this->request->getPost('cedula');
        $email = $this->request->getPost('email');

        // Buscar postulante por cédula o email
        $postulante = $this->postulanteModel->where('cedula', $cedula)
                                           ->orWhere('email', $email)
                                           ->where('estado', 'Activo')
                                           ->first();

        if ($postulante) {
            // Crear sesión para postulante
            session()->set([
                'isLoggedIn' => true,
                'id_postulante' => $postulante['id_postulante'],
                'tipo_usuario' => 'postulante',
                'nombres' => $postulante['nombres'],
                'apellidos' => $postulante['apellidos'],
                'email' => $postulante['email'],
                'cedula' => $postulante['cedula']
            ]);

            return redirect()->to('/postulantes/dashboard');
        } else {
            return redirect()->back()->withInput()->with('error', 'Credenciales incorrectas o cuenta inactiva');
        }
    }

    /**
     * Dashboard del postulante
     */
    public function dashboard()
    {
        if (!session()->get('isLoggedIn') || session()->get('tipo_usuario') !== 'postulante') {
            return redirect()->to('/postulantes/login');
        }

        $postulanteId = session()->get('id_postulante');
        $postulante = $this->postulanteModel->find($postulanteId);
        $aplicaciones = $this->postulanteModel->getHistorialAplicaciones($postulanteId);
        $vacantesRecientes = $this->vacanteModel->getVacantesActivas(5);

        $data = [
            'title' => 'Dashboard - Postulante',
            'postulante' => $postulante,
            'aplicaciones' => $aplicaciones,
            'vacantes_recientes' => $vacantesRecientes
        ];

        return view('postulantes/dashboard', $data);
    }

    /**
     * Muestra vacantes disponibles
     */
    public function vacantesDisponibles()
    {
        $filtros = [
            'departamento' => $this->request->getGet('departamento'),
            'tipo' => $this->request->getGet('tipo'),
            'busqueda' => $this->request->getGet('busqueda')
        ];

        $vacantes = $this->vacanteModel->getVacantesConFiltros($filtros);
        $departamentos = $this->vacanteModel->getDepartamentosDisponibles();

        $data = [
            'title' => 'Vacantes Disponibles',
            'vacantes' => $vacantes,
            'departamentos' => $departamentos,
            'filtros' => $filtros
        ];

        return view('postulantes/vacantes_disponibles', $data);
    }

    /**
     * Aplica a una vacante específica
     */
    public function aplicarVacante()
    {
        if (!session()->get('isLoggedIn') || session()->get('tipo_usuario') !== 'postulante') {
            return redirect()->to('/postulantes/login');
        }

        $vacanteId = $this->request->getPost('id_vacante');
        $postulanteId = session()->get('id_postulante');

        // Verificar si ya aplicó a esta vacante
        $aplicacionExistente = $this->db->table('postulaciones')
                                       ->where('id_vacante', $vacanteId)
                                       ->where('id_postulante', $postulanteId)
                                       ->first();

        if ($aplicacionExistente) {
            return redirect()->back()->with('error', 'Ya has aplicado a esta vacante');
        }

        // Crear nueva aplicación
        $data = [
            'id_vacante' => $vacanteId,
            'id_postulante' => $postulanteId,
            'fecha_aplicacion' => date('Y-m-d H:i:s'),
            'estado' => 'Pendiente'
        ];

        if ($this->db->table('postulaciones')->insert($data)) {
            return redirect()->back()->with('success', 'Aplicación enviada exitosamente');
        } else {
            return redirect()->back()->with('error', 'Error al enviar la aplicación');
        }
    }

    /**
     * Muestra el estado de las aplicaciones del postulante
     */
    public function estadoAplicaciones()
    {
        if (!session()->get('isLoggedIn') || session()->get('tipo_usuario') !== 'postulante') {
            return redirect()->to('/postulantes/login');
        }

        $postulanteId = session()->get('id_postulante');
        $aplicaciones = $this->postulanteModel->getHistorialAplicaciones($postulanteId);

        $data = [
            'title' => 'Estado de Aplicaciones',
            'aplicaciones' => $aplicaciones
        ];

        return view('postulantes/estado_aplicaciones', $data);
    }

    /**
     * Muestra el perfil del postulante
     */
    public function perfil()
    {
        if (!session()->get('isLoggedIn') || session()->get('tipo_usuario') !== 'postulante') {
            return redirect()->to('/postulantes/login');
        }

        $postulanteId = session()->get('id_postulante');
        $postulante = $this->postulanteModel->find($postulanteId);

        $data = [
            'title' => 'Mi Perfil',
            'postulante' => $postulante
        ];

        return view('postulantes/perfil', $data);
    }

    /**
     * Actualiza el perfil del postulante
     */
    public function actualizarPerfil()
    {
        if (!session()->get('isLoggedIn') || session()->get('tipo_usuario') !== 'postulante') {
            return redirect()->to('/postulantes/login');
        }

        $postulanteId = session()->get('id_postulante');
        
        // Validar datos
        $rules = [
            'nombres' => 'required|min_length[2]|max_length[100]',
            'apellidos' => 'required|min_length[2]|max_length[100]',
            'telefono' => 'required|min_length[7]|max_length[15]',
            'direccion' => 'required|min_length[10]|max_length[300]',
            'experiencia_laboral' => 'required|min_length[10]|max_length[1000]',
            'disponibilidad' => 'required|in_list[Inmediata,15 días,30 días,60 días,90 días]',
            'salario_esperado' => 'required|numeric|greater_than[0]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Procesar archivos si se subieron nuevos
        $archivos = $this->procesarArchivos();
        
        // Preparar datos para actualizar
        $data = [
            'nombres' => $this->request->getPost('nombres'),
            'apellidos' => $this->request->getPost('apellidos'),
            'telefono' => $this->request->getPost('telefono'),
            'direccion' => $this->request->getPost('direccion'),
            'experiencia_laboral' => $this->request->getPost('experiencia_laboral'),
            'disponibilidad' => $this->request->getPost('disponibilidad'),
            'salario_esperado' => $this->request->getPost('salario_esperado')
        ];

        // Agregar archivos si se subieron
        if ($archivos) {
            $data = array_merge($data, $archivos);
        }

        // Actualizar postulante
        if ($this->postulanteModel->update($postulanteId, $data)) {
            // Actualizar sesión
            session()->set([
                'nombres' => $data['nombres'],
                'apellidos' => $data['apellidos']
            ]);

            return redirect()->back()->with('success', 'Perfil actualizado exitosamente');
        } else {
            return redirect()->back()->withInput()->with('error', 'Error al actualizar el perfil');
        }
    }

    /**
     * Cierra la sesión del postulante
     */
    public function logout()
    {
        session()->destroy();
        return redirect()->to('/postulantes/login')->with('success', 'Sesión cerrada exitosamente');
    }

    /**
     * Procesa los archivos subidos por el postulante
     */
    private function procesarArchivos()
    {
        $archivos = [];
        $uploadPath = FCPATH . 'uploads/postulantes/';

        // Crear directorio si no existe
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        // Procesar CV
        $cv = $this->request->getFile('archivo_cv');
        if ($cv && $cv->isValid() && !$cv->hasMoved()) {
            $cvName = 'cv_' . time() . '_' . $cv->getRandomName();
            $cv->move($uploadPath, $cvName);
            $archivos['archivo_cv'] = $cvName;
        }

        // Procesar cédula
        $cedula = $this->request->getFile('archivo_cedula');
        if ($cedula && $cedula->isValid() && !$cedula->hasMoved()) {
            $cedulaName = 'cedula_' . time() . '_' . $cedula->getRandomName();
            $cedula->move($uploadPath, $cedulaName);
            $archivos['archivo_cedula'] = $cedulaName;
        }

        // Procesar título
        $titulo = $this->request->getFile('archivo_titulo');
        if ($titulo && $titulo->isValid() && !$titulo->hasMoved()) {
            $tituloName = 'titulo_' . time() . '_' . $titulo->getRandomName();
            $titulo->move($uploadPath, $tituloName);
            $archivos['archivo_titulo'] = $tituloName;
        }

        // Procesar referencias
        $referencias = $this->request->getFile('archivo_referencias');
        if ($referencias && $referencias->isValid() && !$referencias->hasMoved()) {
            $refName = 'referencias_' . time() . '_' . $referencias->getRandomName();
            $referencias->move($uploadPath, $refName);
            $archivos['archivo_referencias'] = $refName;
        }

        return $archivos;
    }

    /**
     * Permite descargar archivos del postulante
     */
    public function descargarArchivo($tipo, $postulanteId)
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/postulantes/login');
        }

        // Verificar que el usuario solo pueda descargar sus propios archivos
        if (session()->get('tipo_usuario') === 'postulante' && session()->get('id_postulante') != $postulanteId) {
            return redirect()->to('/postulantes/login');
        }

        $postulante = $this->postulanteModel->find($postulanteId);
        if (!$postulante) {
            return redirect()->back()->with('error', 'Postulante no encontrado');
        }

        $archivo = null;
        $nombreArchivo = '';

        switch ($tipo) {
            case 'cv':
                $archivo = $postulante['archivo_cv'];
                $nombreArchivo = 'CV_' . $postulante['nombres'] . '_' . $postulante['apellidos'] . '.pdf';
                break;
            case 'cedula':
                $archivo = $postulante['archivo_cedula'];
                $nombreArchivo = 'Cedula_' . $postulante['nombres'] . '_' . $postulante['apellidos'] . '.pdf';
                break;
            case 'titulo':
                $archivo = $postulante['archivo_titulo'];
                $nombreArchivo = 'Titulo_' . $postulante['nombres'] . '_' . $postulante['apellidos'] . '.pdf';
                break;
            case 'referencias':
                $archivo = $postulante['archivo_referencias'];
                $nombreArchivo = 'Referencias_' . $postulante['nombres'] . '_' . $postulante['apellidos'] . '.pdf';
                break;
            default:
                return redirect()->back()->with('error', 'Tipo de archivo no válido');
        }

        if (!$archivo) {
            return redirect()->back()->with('error', 'Archivo no encontrado');
        }

        $filePath = FCPATH . 'uploads/postulantes/' . $archivo;
        
        if (!file_exists($filePath)) {
            return redirect()->back()->with('error', 'Archivo no encontrado en el servidor');
        }

        return $this->response->download($filePath, $nombreArchivo);
    }
}
