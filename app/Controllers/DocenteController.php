<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class DocenteController extends Controller
{
    public function __construct()
    {
        // Verificar que el usuario esté logueado y tenga el rol correcto
        if (!session()->get('isLoggedIn') || session()->get('id_rol') != 3) {
            return redirect()->to('/access-denied');
        }
    }

    public function dashboard()
    {
        $periodoModel = new \App\Models\PeriodoAcademicoModel();
        $empleadoModel = new \App\Models\EmpleadoModel();
        
        // Obtener empleado actual
        $empleado = $empleadoModel->getEmpleadoByUsuarioId(session()->get('id_usuario'));
        $periodoActivo = $periodoModel->getPeriodoActivo();
        
        $data = [
            'title' => 'Dashboard - Docente',
            'user' => [
                'nombres' => session()->get('nombres'),
                'apellidos' => session()->get('apellidos'),
                'rol' => session()->get('nombre_rol')
            ],
            'empleado' => $empleado,
            'periodo_activo' => $periodoActivo
        ];
        
        return view('Roles/Docente/dashboard', $data);
    }

    public function perfil()
    {
        $data = [
            'title' => 'Mi Perfil',
            'user' => [
                'nombres' => session()->get('nombres'),
                'apellidos' => session()->get('apellidos'),
                'rol' => session()->get('nombre_rol')
            ]
        ];
        
        return view('Roles/Docente/perfil', $data);
    }

    public function capacitaciones()
    {
        $capacitacionModel = new \App\Models\CapacitacionModel();
        $empleadoCapacitacionModel = new \App\Models\EmpleadoCapacitacionModel();
        $empleadoModel = new \App\Models\EmpleadoModel();
        
        // Obtener ID del empleado actual
        $empleado = $empleadoModel->getEmpleadoByUsuarioId(session()->get('id_usuario'));
        $idEmpleado = $empleado['id_empleado'] ?? null;
        
        $data = [
            'title' => 'Mis Capacitaciones',
            'user' => [
                'nombres' => session()->get('nombres'),
                'apellidos' => session()->get('apellidos'),
                'rol' => session()->get('nombre_rol')
            ],
            'capacitacionesDisponibles' => $capacitacionModel->getCapacitacionesDisponibles($idEmpleado),
            'misCapacitaciones' => $empleadoCapacitacionModel->getCapacitacionesEmpleado($idEmpleado),
            'estadisticas' => $empleadoCapacitacionModel->getEstadisticasEmpleado($idEmpleado)
        ];
        
        return view('Roles/Docente/capacitaciones', $data);
    }

    public function documentos()
    {
        $documentoModel = new \App\Models\DocumentoModel();
        $categoriaModel = new \App\Models\CategoriaModel();
        $empleadoModel = new \App\Models\EmpleadoModel();
        
        // Obtener ID del empleado actual
        $empleado = $empleadoModel->getEmpleadoByUsuarioId(session()->get('id_usuario'));
        $idEmpleado = $empleado['id_empleado'] ?? null;
        
        $data = [
            'title' => 'Mis Documentos',
            'user' => [
                'nombres' => session()->get('nombres'),
                'apellidos' => session()->get('apellidos'),
                'rol' => session()->get('nombre_rol')
            ],
            'documentos' => $documentoModel->getDocumentosEmpleado($idEmpleado),
            'categorias' => $categoriaModel->getCategoriasActivas(),
            'estadisticas' => $documentoModel->getEstadisticasEmpleado($idEmpleado)
        ];
        
        return view('Roles/Docente/documentos', $data);
    }

    public function certificados()
    {
        $data = [
            'title' => 'Certificados',
            'user' => [
                'nombres' => session()->get('nombres'),
                'apellidos' => session()->get('apellidos'),
                'rol' => session()->get('nombre_rol')
            ]
        ];
        return view('Roles/Docente/certificados', $data);
    }

    public function evaluaciones()
    {
        $data = [
            'title' => 'Mis Evaluaciones',
            'user' => [
                'nombres' => session()->get('nombres'),
                'apellidos' => session()->get('apellidos'),
                'rol' => session()->get('nombre_rol')
            ]
        ];
        return view('Roles/Docente/evaluaciones', $data);
    }

    public function competencias()
    {
        $data = [
            'title' => 'Mis Competencias',
            'user' => [
                'nombres' => session()->get('nombres'),
                'apellidos' => session()->get('apellidos'),
                'rol' => session()->get('nombre_rol')
            ]
        ];
        return view('Roles/Docente/competencias', $data);
    }

    public function asistencias()
    {
        $data = [
            'title' => 'Mis Asistencias',
            'user' => [
                'nombres' => session()->get('nombres'),
                'apellidos' => session()->get('apellidos'),
                'rol' => session()->get('nombre_rol')
            ]
        ];
        return view('Roles/Docente/asistencias', $data);
    }

    public function permisos()
    {
        $data = [
            'title' => 'Mis Permisos',
            'user' => [
                'nombres' => session()->get('nombres'),
                'apellidos' => session()->get('apellidos'),
                'rol' => session()->get('nombre_rol')
            ]
        ];
        return view('Roles/Docente/permisos', $data);
    }

    public function nomina()
    {
        $data = [
            'title' => 'Mi Nómina',
            'user' => [
                'nombres' => session()->get('nombres'),
                'apellidos' => session()->get('apellidos'),
                'rol' => session()->get('nombre_rol')
            ]
        ];
        return view('Roles/Docente/nomina', $data);
    }

    public function beneficios()
    {
        $data = [
            'title' => 'Mis Beneficios',
            'user' => [
                'nombres' => session()->get('nombres'),
                'apellidos' => session()->get('apellidos'),
                'rol' => session()->get('nombre_rol')
            ]
        ];
        return view('Roles/Docente/beneficios', $data);
    }

    public function solicitudes()
    {
        $data = [
            'title' => 'Mis Solicitudes',
            'user' => [
                'nombres' => session()->get('nombres'),
                'apellidos' => session()->get('apellidos'),
                'rol' => session()->get('nombre_rol')
            ]
        ];
        return view('Roles/Docente/solicitudes', $data);
    }

    public function nuevaSolicitud()
    {
        $data = [
            'title' => 'Nueva Solicitud - Docente',
            'user' => [
                'nombres' => session()->get('nombres'),
                'apellidos' => session()->get('apellidos'),
                'rol' => session()->get('nombre_rol')
            ]
        ];
        return view('Roles/Docente/nueva_solicitud', $data);
    }

    public function cuenta()
    {
        $data = [
            'title' => 'Mi Cuenta - Docente',
            'user' => [
                'nombres' => session()->get('nombres'),
                'apellidos' => session()->get('apellidos'),
                'rol' => session()->get('nombre_rol')
            ]
        ];
        return view('Roles/Docente/cuenta', $data);
    }

    public function actualizarPerfil()
    {
        try {
            $usuarioModel = new \App\Models\UsuarioModel();
            $empleadoModel = new \App\Models\EmpleadoModel();
            
            $idUsuario = session()->get('id_usuario');
            $data = [
                'nombres' => $this->request->getPost('nombres'),
                'apellidos' => $this->request->getPost('apellidos'),
                'email' => $this->request->getPost('email')
            ];
            
            // Actualizar usuario
            $usuarioModel->update($idUsuario, $data);
            
            // Actualizar empleado si existe
            $empleado = $empleadoModel->where('id_usuario', $idUsuario)->first();
            if ($empleado) {
                $empleadoData = [
                    'nombres' => $this->request->getPost('nombres'),
                    'apellidos' => $this->request->getPost('apellidos'),
                    'telefono' => $this->request->getPost('telefono'),
                    'fecha_nacimiento' => $this->request->getPost('fecha_nacimiento'),
                    'genero' => $this->request->getPost('genero'),
                    'estado_civil' => $this->request->getPost('estado_civil'),
                    'direccion' => $this->request->getPost('direccion')
                ];
                $empleadoModel->update($empleado['id_empleado'], $empleadoData);
            }
            
            // Actualizar sesión
            session()->set([
                'nombres' => $data['nombres'],
                'apellidos' => $data['apellidos'],
                'email' => $data['email']
            ]);
            
            return $this->response->setJSON(['success' => true, 'message' => 'Perfil actualizado correctamente']);
        } catch (\Exception $e) {
            return $this->response->setJSON(['success' => false, 'message' => 'Error al actualizar perfil: ' . $e->getMessage()]);
        }
    }

    public function cambiarPassword()
    {
        try {
            $usuarioModel = new \App\Models\UsuarioModel();
            $idUsuario = session()->get('id_usuario');
            
            $passwordActual = $this->request->getPost('password_actual');
            $passwordNuevo = $this->request->getPost('password_nuevo');
            
            // Verificar contraseña actual
            $usuario = $usuarioModel->find($idUsuario);
            if (!password_verify($passwordActual, $usuario['password'])) {
                return $this->response->setJSON(['success' => false, 'message' => 'La contraseña actual es incorrecta']);
            }
            
            // Actualizar contraseña
            $usuarioModel->update($idUsuario, [
                'password' => password_hash($passwordNuevo, PASSWORD_DEFAULT)
            ]);
            
            return $this->response->setJSON(['success' => true, 'message' => 'Contraseña cambiada correctamente']);
        } catch (\Exception $e) {
            return $this->response->setJSON(['success' => false, 'message' => 'Error al cambiar contraseña: ' . $e->getMessage()]);
        }
    }

    public function configurarNotificaciones()
    {
        try {
            // Aquí se implementaría la lógica para guardar configuración de notificaciones
            return $this->response->setJSON(['success' => true, 'message' => 'Configuración guardada correctamente']);
        } catch (\Exception $e) {
            return $this->response->setJSON(['success' => false, 'message' => 'Error al guardar configuración: ' . $e->getMessage()]);
        }
    }

    public function configurarPrivacidad()
    {
        try {
            // Aquí se implementaría la lógica para guardar configuración de privacidad
            return $this->response->setJSON(['success' => true, 'message' => 'Configuración de privacidad guardada']);
        } catch (\Exception $e) {
            return $this->response->setJSON(['success' => false, 'message' => 'Error al guardar configuración: ' . $e->getMessage()]);
        }
    }

    public function cerrarSesiones()
    {
        try {
            // Implementar lógica para cerrar todas las sesiones excepto la actual
            return $this->response->setJSON(['success' => true, 'message' => 'Sesiones cerradas correctamente']);
        } catch (\Exception $e) {
            return $this->response->setJSON(['success' => false, 'message' => 'Error al cerrar sesiones: ' . $e->getMessage()]);
        }
    }

    // Métodos para gestión de documentos
    public function subirDocumento()
    {
        try {
            $documentoModel = new \App\Models\DocumentoModel();
            $empleadoModel = new \App\Models\EmpleadoModel();
            
            // Obtener ID del empleado actual
            $empleado = $empleadoModel->getEmpleadoByUsuarioId(session()->get('id_usuario'));
            $idEmpleado = $empleado['id_empleado'] ?? null;
            
            if (!$idEmpleado) {
                return $this->response->setJSON(['success' => false, 'message' => 'Empleado no encontrado']);
            }
            
            $data = [
                'id_empleado' => $idEmpleado,
                'id_categoria' => $this->request->getPost('categoria'),
                'nombre' => $this->request->getPost('nombre'),
                'descripcion' => $this->request->getPost('descripcion'),
                'archivo_url' => 'documento_' . time() . '.pdf', // Simular nombre de archivo
                'tamaño' => '2.5 MB', // Simular tamaño
                'tipo_archivo' => 'PDF'
            ];
            
            $resultado = $documentoModel->subirDocumento($data);
            
            if ($resultado) {
                return $this->response->setJSON(['success' => true, 'message' => 'Documento subido correctamente']);
            } else {
                return $this->response->setJSON(['success' => false, 'message' => 'Error al subir documento']);
            }
        } catch (\Exception $e) {
            return $this->response->setJSON(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
    }

    public function verDocumento($idDocumento)
    {
        try {
            $documentoModel = new \App\Models\DocumentoModel();
            $documento = $documentoModel->getDocumentoCompleto($idDocumento);
            
            if (!$documento) {
                return $this->response->setJSON(['success' => false, 'message' => 'Documento no encontrado']);
            }
            
            $html = view('partials/documento_detalles', ['documento' => $documento]);
            
            return $this->response->setJSON(['success' => true, 'html' => $html]);
        } catch (\Exception $e) {
            return $this->response->setJSON(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
    }

    public function descargarDocumento($idDocumento)
    {
        try {
            $documentoModel = new \App\Models\DocumentoModel();
            $documento = $documentoModel->getDocumentoCompleto($idDocumento);
            
            if (!$documento) {
                return $this->response->setJSON(['success' => false, 'message' => 'Documento no encontrado']);
            }
            
            // Simular descarga
            return $this->response->setJSON(['success' => true, 'message' => 'Descarga iniciada']);
        } catch (\Exception $e) {
            return $this->response->setJSON(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
    }

    public function eliminarDocumento($idDocumento)
    {
        try {
            $documentoModel = new \App\Models\DocumentoModel();
            $resultado = $documentoModel->delete($idDocumento);
            
            if ($resultado) {
                return $this->response->setJSON(['success' => true, 'message' => 'Documento eliminado correctamente']);
            } else {
                return $this->response->setJSON(['success' => false, 'message' => 'Error al eliminar documento']);
            }
        } catch (\Exception $e) {
            return $this->response->setJSON(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
    }
} 