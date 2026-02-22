<?php

namespace App\Controllers;

use App\Models\PuestoModel;
use App\Models\PostulanteModel;
use App\Models\UsuarioModel;
use CodeIgniter\Controller;

class PostulacionController extends Controller
{
    protected $puestoModel;
    protected $postulanteModel;
    protected $usuarioModel;

    public function __construct()
    {
        $this->puestoModel = new PuestoModel();
        $this->postulanteModel = new PostulanteModel();
        $this->usuarioModel = new UsuarioModel();
    }

    /**
     * Mostrar formulario de postulación para un puesto específico
     */
    public function mostrarFormulario($param1 = null, $param2 = null)
    {
        // Determinar el ID del puesto y la URL de postulación
        $idPuesto = null;
        $urlPostulacion = null;
        
        if ($param1 && $param2) {
            // Si se reciben dos parámetros, el primero es el ID y el segundo es la URL
            $idPuesto = (int)$param1;
            $urlPostulacion = $param2;
        } elseif ($param1) {
            // Si solo se recibe un parámetro, intentar extraer el ID
            if (is_numeric($param1)) {
                $idPuesto = (int)$param1;
            } else {
                $urlPostulacion = $param1;
            }
        }
        
        // Si tenemos el ID del puesto, buscar por ID primero
        if ($idPuesto) {
            $puesto = $this->puestoModel->getPuestoParaPostulacion($idPuesto);
            if (!$puesto) {
                return view('postulacion/error', [
                    'mensaje' => 'La oferta de trabajo no está disponible o ha expirado.',
                    'titulo' => 'Oferta No Disponible'
                ]);
            }
        } else {
            // Si no tenemos ID, buscar por URL
            $puesto = $this->puestoModel->getPuestoPorUrl($urlPostulacion);
            if (!$puesto) {
                return view('postulacion/error', [
                    'mensaje' => 'La oferta de trabajo no está disponible o ha expirado.',
                    'titulo' => 'Oferta No Disponible'
                ]);
            }
        }

        // Verificar si el puesto está activo y abierto
        if ($puesto['estado'] !== 'Abierto' || $puesto['activo'] != 1) {
            return view('postulacion/error', [
                'mensaje' => 'Esta oferta de trabajo ya no está disponible para postulaciones.',
                'titulo' => 'Oferta Cerrada'
            ]);
        }

        // Verificar fecha límite
        if (strtotime($puesto['fecha_limite']) < time()) {
            return view('postulacion/error', [
                'mensaje' => 'El plazo para postularse a esta oferta ha expirado.',
                'titulo' => 'Plazo Expirado'
            ]);
        }

        // Verificar vacantes disponibles
        if ($puesto['vacantes_disponibles'] <= 0) {
            return view('postulacion/error', [
                'mensaje' => 'No hay vacantes disponibles para esta oferta.',
                'titulo' => 'Sin Vacantes'
            ]);
        }

        $data = [
            'titulo' => 'Postulación: ' . $puesto['titulo'],
            'puesto' => $puesto,
            'url_postulacion' => $urlPostulacion
        ];

        return view('postulacion/formulario', $data);
    }

    /**
     * Procesar postulación
     */
    public function procesarPostulacion()
    {
        if (!$this->request->is('post')) {
            return redirect()->back()->with('error', 'Método no permitido');
        }

        try {
            $datos = $this->request->getPost();
            $urlPostulacion = $datos['url_postulacion'] ?? '';
            
            // Validar datos requeridos
            $camposRequeridos = [
                'nombres', 'apellidos', 'cedula', 'email', 'telefono',
                'fecha_nacimiento', 'genero', 'estado_civil', 'direccion',
                'ciudad', 'provincia', 'nacionalidad', 'disponibilidad_inmediata'
            ];

            foreach ($camposRequeridos as $campo) {
                if (empty($datos[$campo])) {
                    return redirect()->back()->with('error', "El campo $campo es obligatorio");
                }
            }

            // Buscar el puesto
            $puesto = $this->puestoModel->getPuestoPorUrl($urlPostulacion);
            if (!$puesto) {
                return redirect()->back()->with('error', 'Oferta de trabajo no válida');
            }

            // Verificar si ya se postuló con esta cédula
            $postulacionExistente = $this->postulanteModel->where('cedula', $datos['cedula'])
                                                         ->where('id_puesto', $puesto['id_puesto'])
                                                         ->first();
            
            if ($postulacionExistente) {
                return redirect()->back()->with('error', 'Ya se ha postulado a esta oferta con esta cédula');
            }

            // Crear o buscar usuario
            $usuario = $this->usuarioModel->where('cedula', $datos['cedula'])->first();
            
            if (!$usuario) {
                // Crear nuevo usuario
                $emailUsuario = $datos['cedula'] . '@postulante.itsi.edu.ec';
                $passwordUsuario = '123456'; // Contraseña por defecto
                
                $usuarioData = [
                    'cedula' => $datos['cedula'],
                    'email' => $emailUsuario,
                    'password' => password_hash($passwordUsuario, PASSWORD_DEFAULT),
                    'id_rol' => 10, // Rol de postulante
                    'activo' => 1,
                    'password_changed' => 0
                ];
                
                $this->usuarioModel->insert($usuarioData);
                $idUsuario = $this->usuarioModel->insertID;
                
                // Guardar credenciales para mostrar al usuario
                $credenciales = [
                    'email' => $emailUsuario,
                    'password' => $passwordUsuario
                ];
            } else {
                $idUsuario = $usuario['id_usuario'];
                $credenciales = null;
            }

            // Preparar datos de postulación
            $postulacionData = [
                'id_usuario' => $idUsuario,
                'id_puesto' => $puesto['id_puesto'],
                'nombres' => $datos['nombres'],
                'apellidos' => $datos['apellidos'],
                'cedula' => $datos['cedula'],
                'email' => $datos['email'],
                'telefono' => $datos['telefono'],
                'fecha_nacimiento' => $datos['fecha_nacimiento'],
                'genero' => $datos['genero'],
                'estado_civil' => $datos['estado_civil'],
                'direccion' => $datos['direccion'],
                'ciudad' => $datos['ciudad'],
                'provincia' => $datos['provincia'],
                'codigo_postal' => $datos['codigo_postal'] ?? '',
                'nacionalidad' => $datos['nacionalidad'],
                'estado_postulacion' => 'Pendiente',
                'fecha_postulacion' => date('Y-m-d'),
                'cv_path' => $datos['cv_path'] ?? '',
                'carta_motivacion' => $datos['carta_motivacion'] ?? '',
                'experiencia_laboral' => $datos['experiencia_laboral'] ?? '',
                'educacion' => $datos['educacion'] ?? '',
                'habilidades' => $datos['habilidades'] ?? '',
                'idiomas' => $datos['idiomas'] ?? '',
                'certificaciones' => $datos['certificaciones'] ?? '',
                'referencias' => $datos['referencias'] ?? '',
                'disponibilidad_inmediata' => $datos['disponibilidad_inmediata'],
                'expectativa_salarial' => $datos['expectativa_salarial'] ?? null,
                'notas_admin' => '',
                'activo' => 1
            ];

            // Guardar postulación
            $this->postulanteModel->insert($postulacionData);

            // Reducir vacantes disponibles
            $this->puestoModel->actualizarVacantes($puesto['id_puesto'], 1);

            // Mostrar confirmación con credenciales si es nuevo usuario
            $data = [
                'titulo' => 'Postulación Exitosa',
                'puesto' => $puesto,
                'postulacion' => $postulacionData,
                'credenciales' => $credenciales
            ];

            return view('postulacion/confirmacion', $data);

        } catch (\Exception $e) {
            log_message('error', 'Error al procesar postulación: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al procesar la postulación. Por favor, intente nuevamente.');
        }
    }

    /**
     * Subir archivo CV
     */
    public function subirCV()
    {
        if (!$this->request->is('post')) {
            return $this->response->setJSON(['success' => false, 'message' => 'Método no permitido']);
        }

        try {
            $file = $this->request->getFile('cv');
            
            if (!$file->isValid() || $file->getError() !== UPLOAD_ERR_OK) {
                return $this->response->setJSON(['success' => false, 'message' => 'Error al subir el archivo']);
            }

            // Validar tipo de archivo
            $allowedTypes = ['pdf', 'doc', 'docx'];
            $extension = $file->getExtension();
            
            if (!in_array(strtolower($extension), $allowedTypes)) {
                return $this->response->setJSON(['success' => false, 'message' => 'Solo se permiten archivos PDF, DOC o DOCX']);
            }

            // Validar tamaño (máximo 5MB)
            if ($file->getSize() > 5 * 1024 * 1024) {
                return $this->response->setJSON(['success' => false, 'message' => 'El archivo no puede exceder 5MB']);
            }

            // Generar nombre único
            $newName = 'cv_' . time() . '_' . $file->getRandomName();
            
            // Mover archivo a carpeta de uploads
            $uploadPath = FCPATH . 'public/uploads/cv/';
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }
            
            $file->move($uploadPath, $newName);
            
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Archivo subido exitosamente',
                'filename' => $newName,
                'path' => 'uploads/cv/' . $newName
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Error al subir CV: ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'Error al subir el archivo']);
        }
    }

    /**
     * Verificar estado de postulación
     */
    public function verEstado($cedula, $idPuesto)
    {
        try {
            $postulacion = $this->postulanteModel->where('cedula', $cedula)
                                                ->where('id_puesto', $idPuesto)
                                                ->first();
            
            if (!$postulacion) {
                return $this->response->setJSON(['success' => false, 'message' => 'Postulación no encontrada']);
            }

            $puesto = $this->puestoModel->find($idPuesto);
            
            $data = [
                'success' => true,
                'postulacion' => $postulacion,
                'puesto' => $puesto
            ];

            return $this->response->setJSON($data);

        } catch (\Exception $e) {
            log_message('error', 'Error al ver estado de postulación: ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'Error al consultar el estado']);
        }
    }
}

