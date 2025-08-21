<?php

namespace App\Controllers;

use App\Models\PostulanteModel;
use App\Models\PuestoModel;
use App\Models\UsuarioModel;

class PostulanteController extends BaseController
{
    protected $postulanteModel;
    protected $puestoModel;
    protected $usuarioModel;

    public function __construct()
    {
        $this->postulanteModel = new PostulanteModel();
        $this->puestoModel = new PuestoModel();
        $this->usuarioModel = new UsuarioModel();
    }

    /**
     * Dashboard principal del postulante
     */
    public function dashboard()
    {
        // Verificar que el usuario esté logueado y sea postulante
        if (!session()->get('logged_in') || session()->get('id_rol') != 10) {
            return redirect()->to('login');
        }

        $idUsuario = session()->get('id_usuario');
        
        // Obtener todas las postulaciones del usuario
        $postulaciones = $this->postulanteModel->getPostulacionesPorUsuario($idUsuario);
        
        // Obtener estadísticas
        $estadisticas = $this->postulanteModel->getEstadisticasUsuario($idUsuario);
        
        $data = [
            'titulo' => 'Dashboard del Postulante',
            'postulaciones' => $postulaciones,
            'estadisticas' => $estadisticas
        ];
        
        return view('postulante/dashboard', $data);
    }

    /**
     * Ver detalles de una postulación específica
     */
    public function verPostulacion($idPostulacion)
    {
        if (!session()->get('logged_in') || session()->get('id_rol') != 10) {
            return redirect()->to('login');
        }

        $idUsuario = session()->get('id_usuario');
        
        // Obtener la postulación y verificar que pertenezca al usuario
        $postulacion = $this->postulanteModel->getPostulacionCompleta($idPostulacion);
        
        if (!$postulacion || $postulacion['id_usuario'] != $idUsuario) {
            return redirect()->to('postulante/dashboard')->with('error', 'Postulación no encontrada');
        }
        
        $data = [
            'titulo' => 'Detalles de Postulación',
            'postulacion' => $postulacion
        ];
        
        return view('postulante/ver_postulacion', $data);
    }

    /**
     * Editar una postulación existente
     */
    public function editarPostulacion($idPostulacion)
    {
        if (!session()->get('logged_in') || session()->get('id_rol') != 10) {
            return redirect()->to('login');
        }

        $idUsuario = session()->get('id_usuario');
        
        // Obtener la postulación y verificar que pertenezca al usuario
        $postulacion = $this->postulanteModel->getPostulacionCompleta($idPostulacion);
        
        if (!$postulacion || $postulacion['id_usuario'] != $idUsuario) {
            return redirect()->to('postulante/dashboard')->with('error', 'Postulación no encontrada');
        }
        
        // Verificar que la postulación esté en estado editable
        if (!in_array($postulacion['estado_postulacion'], ['Pendiente', 'En revisión'])) {
            return redirect()->to('postulante/dashboard')->with('error', 'Esta postulación no se puede editar');
        }
        
        $data = [
            'titulo' => 'Editar Postulación',
            'postulacion' => $postulacion
        ];
        
        return view('postulante/editar_postulacion', $data);
    }

    /**
     * Actualizar una postulación
     */
    public function actualizarPostulacion()
    {
        if (!session()->get('logged_in') || session()->get('id_rol') != 10) {
            return $this->response->setJSON(['success' => false, 'message' => 'No autorizado']);
        }

        $idUsuario = session()->get('id_usuario');
        $idPostulacion = $this->request->getPost('id_postulacion');
        
        // Verificar que la postulación pertenezca al usuario
        $postulacion = $this->postulanteModel->find($idPostulacion);
        if (!$postulacion || $postulacion['id_usuario'] != $idUsuario) {
            return $this->response->setJSON(['success' => false, 'message' => 'Postulación no encontrada']);
        }
        
        // Preparar datos para actualización
        $datos = [
            'nombres' => $this->request->getPost('nombres'),
            'apellidos' => $this->request->getPost('apellidos'),
            'telefono' => $this->request->getPost('telefono'),
            'direccion' => $this->request->getPost('direccion'),
            'ciudad' => $this->request->getPost('ciudad'),
            'provincia' => $this->request->getPost('provincia'),
            'codigo_postal' => $this->request->getPost('codigo_postal'),
            'carta_motivacion' => $this->request->getPost('carta_motivacion'),
            'experiencia_laboral' => $this->request->getPost('experiencia_laboral'),
            'educacion' => $this->request->getPost('educacion'),
            'habilidades' => $this->request->getPost('habilidades'),
            'idiomas' => $this->request->getPost('idiomas'),
            'certificaciones' => $this->request->getPost('certificaciones'),
            'referencias' => $this->request->getPost('referencias'),
            'disponibilidad_inmediata' => $this->request->getPost('disponibilidad_inmediata'),
            'expectativa_salarial' => $this->request->getPost('expectativa_salarial'),
            'updated_at' => date('Y-m-d H:i:s')
        ];
        
        // Manejar subida de nuevo CV si se proporciona
        $cv = $this->request->getFile('cv');
        if ($cv && $cv->isValid() && !$cv->hasMoved()) {
            $nuevoNombre = 'cv_' . $idUsuario . '_' . time() . '.' . $cv->getExtension();
            $cv->move(ROOTPATH . 'public/uploads/cv/', $nuevoNombre);
            $datos['cv_path'] = 'uploads/cv/' . $nuevoNombre;
            
            // Eliminar CV anterior si existe
            if ($postulacion['cv_path'] && file_exists(ROOTPATH . 'public/' . $postulacion['cv_path'])) {
                unlink(ROOTPATH . 'public/' . $postulacion['cv_path']);
            }
        }
        
        try {
            if ($this->postulanteModel->update($idPostulacion, $datos)) {
                return $this->response->setJSON([
                    'success' => true, 
                    'message' => 'Postulación actualizada correctamente'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false, 
                    'message' => 'Error al actualizar la postulación'
                ]);
            }
        } catch (\Exception $e) {
            log_message('error', 'Error al actualizar postulación: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false, 
                'message' => 'Error interno del servidor'
            ]);
        }
    }

    /**
     * Subir nuevo CV
     */
    public function subirCV()
    {
        if (!session()->get('logged_in') || session()->get('id_rol') != 10) {
            return $this->response->setJSON(['success' => false, 'message' => 'No autorizado']);
        }

        $idUsuario = session()->get('id_usuario');
        $idPostulacion = $this->request->getPost('id_postulacion');
        
        // Verificar que la postulación pertenezca al usuario
        $postulacion = $this->postulanteModel->find($idPostulacion);
        if (!$postulacion || $postulacion['id_usuario'] != $idUsuario) {
            return $this->response->setJSON(['success' => false, 'message' => 'Postulación no encontrada']);
        }
        
        $cv = $this->request->getFile('cv');
        
        if (!$cv || !$cv->isValid()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Archivo no válido']);
        }
        
        // Validar tipo de archivo
        $tiposPermitidos = ['pdf', 'doc', 'docx'];
        if (!in_array($cv->getExtension(), $tiposPermitidos)) {
            return $this->response->setJSON([
                'success' => false, 
                'message' => 'Solo se permiten archivos PDF, DOC o DOCX'
            ]);
        }
        
        // Validar tamaño (máximo 5MB)
        if ($cv->getSize() > 5 * 1024 * 1024) {
            return $this->response->setJSON([
                'success' => false, 
                'message' => 'El archivo no debe superar 5MB'
            ]);
        }
        
        try {
            $nuevoNombre = 'cv_' . $idUsuario . '_' . time() . '.' . $cv->getExtension();
            $cv->move(ROOTPATH . 'public/uploads/cv/', $nuevoNombre);
            
            // Actualizar la base de datos
            $datos = [
                'cv_path' => 'uploads/cv/' . $nuevoNombre,
                'updated_at' => date('Y-m-d H:i:s')
            ];
            
            if ($this->postulanteModel->update($idPostulacion, $datos)) {
                // Eliminar CV anterior si existe
                if ($postulacion['cv_path'] && file_exists(ROOTPATH . 'public/' . $postulacion['cv_path'])) {
                    unlink(ROOTPATH . 'public/' . $postulacion['cv_path']);
                }
                
                return $this->response->setJSON([
                    'success' => true, 
                    'message' => 'CV actualizado correctamente',
                    'cv_path' => 'uploads/cv/' . $nuevoNombre
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false, 
                    'message' => 'Error al actualizar el CV'
                ]);
            }
        } catch (\Exception $e) {
            log_message('error', 'Error al subir CV: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false, 
                'message' => 'Error interno del servidor'
            ]);
        }
    }

    /**
     * Cambiar contraseña del usuario postulante
     */
    public function cambiarPassword()
    {
        if (!session()->get('logged_in') || session()->get('id_rol') != 10) {
            return $this->response->setJSON(['success' => false, 'message' => 'No autorizado']);
        }

        $idUsuario = session()->get('id_usuario');
        $passwordActual = $this->request->getPost('password_actual');
        $passwordNuevo = $this->request->getPost('password_nuevo');
        $passwordConfirmar = $this->request->getPost('password_confirmar');
        
        // Validar que la contraseña actual sea correcta
        $usuario = $this->usuarioModel->find($idUsuario);
        if (!password_verify($passwordActual, $usuario['password'])) {
            return $this->response->setJSON([
                'success' => false, 
                'message' => 'La contraseña actual es incorrecta'
            ]);
        }
        
        // Validar que las contraseñas nuevas coincidan
        if ($passwordNuevo !== $passwordConfirmar) {
            return $this->response->setJSON([
                'success' => false, 
                'message' => 'Las contraseñas nuevas no coinciden'
            ]);
        }
        
        // Validar longitud mínima
        if (strlen($passwordNuevo) < 8) {
            return $this->response->setJSON([
                'success' => false, 
                'message' => 'La contraseña debe tener al menos 8 caracteres'
            ]);
        }
        
        try {
            $datos = [
                'password' => password_hash($passwordNuevo, PASSWORD_DEFAULT),
                'password_changed' => 1,
                'updated_at' => date('Y-m-d H:i:s')
            ];
            
            if ($this->usuarioModel->update($idUsuario, $datos)) {
                // Actualizar sesión
                session()->set('password_changed', 1);
                
                return $this->response->setJSON([
                    'success' => true, 
                    'message' => 'Contraseña cambiada correctamente'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false, 
                    'message' => 'Error al cambiar la contraseña'
                ]);
            }
        } catch (\Exception $e) {
            log_message('error', 'Error al cambiar contraseña: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false, 
                'message' => 'Error interno del servidor'
            ]);
        }
    }

    /**
     * Cerrar sesión
     */
    public function logout()
    {
        session()->destroy();
        return redirect()->to('login')->with('message', 'Sesión cerrada correctamente');
    }
}
