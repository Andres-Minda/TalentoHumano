<?php

namespace App\Controllers;

use App\Models\PuestoModel;
use App\Models\PostulanteModel;
use CodeIgniter\Controller;

/**
 * Controlador de postulaciones públicas.
 *
 * La subida de CVs a Google Drive utiliza OAuth2 con la cuenta personal del administrador.
 * Credenciales: writable/client_secrets.json
 * Token:        writable/token.json (generado por obtener_token.php)
 * Carpeta de destino en Drive: 1WBBRZjMLvRd0PctXRKM0F6_TIEKIEz8B
 *
 * PARA EVITAR QUE EL TOKEN EXPIRE CADA 7 DÍAS:
 *   1. Google Cloud Console → APIs & Services → OAuth consent screen
 *   2. Clic en "PUBLISH APP" para pasar de Prueba a Producción
 *   3. Re-ejecutar obtener_token.php para obtener un refresh_token permanente
 *   Motivo: en modo "Prueba", Google revoca los refresh_tokens cada 7 días.
 */
class PostulacionController extends Controller
{
    protected $puestoModel;
    protected $postulanteModel;

    public function __construct()
    {
        $this->puestoModel = new PuestoModel();
        $this->postulanteModel = new PostulanteModel();
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
     * Procesar postulación completa: subir CV a Drive + guardar datos en BD
     */
    public function procesarPostulacion()
    {
        if (!$this->request->is('post')) {
            return $this->response->setJSON(['success' => false, 'message' => 'Método no permitido']);
        }

        $db = \Config\Database::connect();

        try {
            $datos = $this->request->getPost();
            $idPuesto = $datos['id_puesto'] ?? null;
            $urlPostulacion = $datos['url_postulacion'] ?? '';
            
            // Validar datos requeridos
            $camposRequeridos = [
                'nombres', 'apellidos', 'cedula', 'email', 'telefono',
                'fecha_nacimiento', 'genero', 'estado_civil', 'direccion',
                'ciudad', 'provincia', 'nacionalidad', 'disponibilidad_inmediata'
            ];

            foreach ($camposRequeridos as $campo) {
                if (empty($datos[$campo])) {
                    return $this->response->setJSON(['success' => false, 'message' => "El campo $campo es obligatorio"]);
                }
            }

            // Buscar el puesto por ID o por URL
            $puesto = null;
            if ($idPuesto) {
                $puesto = $this->puestoModel->find($idPuesto);
            }
            if (!$puesto && $urlPostulacion) {
                $puesto = $this->puestoModel->getPuestoPorUrl($urlPostulacion);
            }
            if (!$puesto) {
                return $this->response->setJSON(['success' => false, 'message' => 'Oferta de trabajo no válida']);
            }

            // Bloquear POST si el puesto no está Abierto
            if ($puesto['estado'] !== 'Abierto' || ($puesto['activo'] ?? 1) != 1) {
                return $this->response->setJSON(['success' => false, 'message' => 'Este puesto no acepta postulaciones en este momento.']);
            }

            // Verificar si ya se postuló con esta cédula
            $postulacionExistente = $this->postulanteModel->where('cedula', $datos['cedula'])
                                                         ->where('id_puesto', $puesto['id_puesto'])
                                                         ->first();
            
            if ($postulacionExistente) {
                return $this->response->setJSON(['success' => false, 'message' => 'Ya se ha postulado a esta oferta con esta cédula']);
            }

            // ===== PASO 1: Subir CV a Google Drive (OAuth2 con token del usuario) =====
            $cvPath = '';
            $file = $this->request->getFile('cv');
            
            if ($file && $file->isValid() && $file->getError() === UPLOAD_ERR_OK) {
                // Validar tipo de archivo
                $allowedTypes = ['pdf', 'doc', 'docx'];
                $extension = strtolower($file->getExtension());
                
                if (!in_array($extension, $allowedTypes)) {
                    return $this->response->setJSON(['success' => false, 'message' => 'Solo se permiten archivos PDF, DOC o DOCX']);
                }

                // Validar tamaño (máximo 5MB)
                if ($file->getSize() > 5 * 1024 * 1024) {
                    return $this->response->setJSON(['success' => false, 'message' => 'El archivo no puede exceder 5MB']);
                }

                // Generar nombre con el nombre del candidato
                $nombreCompleto = ($datos['nombres'] ?? 'Sin') . ' ' . ($datos['apellidos'] ?? 'Nombre');
                $nombreLimpio = preg_replace('/[^A-Za-z0-9_]/', '_', $nombreCompleto);
                $nombreFinalPDF = 'CV_' . $nombreLimpio . '_' . date('Ymd_His') . '.' . $extension;

                // Verificar archivos de credenciales OAuth2
                $clientSecretsPath = WRITEPATH . 'client_secrets.json';
                $tokenPath = WRITEPATH . 'token.json';

                if (!file_exists($clientSecretsPath)) {
                    throw new \Exception('No se encontró client_secrets.json en writable/. Descarga las credenciales OAuth2 desde Google Cloud Console.');
                }
                if (!file_exists($tokenPath)) {
                    throw new \Exception('Google Drive no está conectado. El administrador debe ejecutar obtener_token.php para autorizar la cuenta.');
                }

                // Configurar Google Client con OAuth2
                $client = new \Google\Client();
                $client->setAuthConfig($clientSecretsPath);
                $client->addScope(\Google\Service\Drive::DRIVE_FILE);

                // Cargar token guardado
                $accessToken = json_decode(file_get_contents($tokenPath), true);
                $client->setAccessToken($accessToken);

                // Si el token expiró, renovar automáticamente con refresh_token
                if ($client->isAccessTokenExpired()) {
                    if ($client->getRefreshToken()) {
                        $newToken = $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());

                        // Verificar si la renovación falló (invalid_grant u otro error)
                        if (isset($newToken['error'])) {
                            log_message('critical', '[Google Drive] Fallo al renovar token: ' . json_encode($newToken) . '. Ejecutar obtener_token.php para re-autorizar.');
                            throw new \Exception('TOKEN_INVALID_GRANT');
                        }

                        // Guardar el token renovado
                        file_put_contents($tokenPath, json_encode($client->getAccessToken()));
                        log_message('info', 'Token de Google Drive renovado automáticamente.');
                    } else {
                        throw new \Exception('El token de Google Drive expiró y no tiene refresh_token. Ejecutar obtener_token.php para re-autorizar.');
                    }
                }

                $driveService = new \Google\Service\Drive($client);

                // Metadatos del archivo especificando la carpeta PADRE
                $fileMetadata = new \Google\Service\Drive\DriveFile([
                    'name'    => $nombreFinalPDF,
                    'parents' => ['1WBBRZjMLvRd0PctXRKM0F6_TIEKIEz8B']
                ]);

                // Leer el contenido del archivo temporal
                $content = file_get_contents($file->getTempName());

                // EJECUTAR LA SUBIDA (FORZANDO MULTIPART)
                $uploadedFile = $driveService->files->create($fileMetadata, [
                    'data'       => $content,
                    'mimeType'   => 'application/pdf',
                    'uploadType' => 'multipart',
                    'fields'     => 'id, webViewLink'
                ]);

                // Hacer el archivo público (lectura para cualquiera)
                $permission = new \Google\Service\Drive\Permission([
                    'role' => 'reader',
                    'type' => 'anyone'
                ]);
                $driveService->permissions->create($uploadedFile->id, $permission);

                // Guardar el webViewLink para insertar en BD
                $cvPath = $uploadedFile->webViewLink;

                log_message('info', 'CV subido a Drive exitosamente: ' . $cvPath);
            }

            // ===== PASO 2: Guardar postulante en BD (DESPUÉS de Drive) =====
            $db->transStart();

            $postulacionData = [
                'id_usuario' => null,
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
                'cv_path' => $cvPath,
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

            $insertResult = $this->postulanteModel->insert($postulacionData);

            // Verificar si el insert falló por validación del modelo
            if ($insertResult === false) {
                $errores = $this->postulanteModel->errors();
                $db->transRollback();
                log_message('error', 'Validación falló al insertar postulante: ' . json_encode($errores));
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Error de validación: ' . implode(', ', $errores)
                ]);
            }

            // Reducir vacantes disponibles
            $this->puestoModel->actualizarVacantes($puesto['id_puesto'], 1);
            $db->transComplete();

            if ($db->transStatus() === false) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Error al guardar la postulación. La operación fue revertida.'
                ]);
            }

            return $this->response->setJSON([
                'success' => true,
                'message' => '¡Postulación enviada con éxito! Su CV ha sido recibido.'
            ]);

        } catch (\Google\Service\Exception $e) {
            // ── Error específico de la API de Google Drive ──
            $db->transRollback();
            $errorBody = $e->getMessage();

            if (strpos($errorBody, 'invalid_grant') !== false) {
                log_message('critical', '[Google Drive] Token revocado/expirado. Ejecutar obtener_token.php. Detalle: ' . $errorBody);
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'La conexión con Google Drive ha expirado. El administrador debe renovarla desde el panel. (Código: DRV-AUTH)'
                ]);
            }

            log_message('error', '[Google Drive] Error de API: ' . $errorBody);
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error temporal al subir su CV al servidor. Por favor, intente nuevamente en unos minutos. (Código: DRV-API)'
            ]);

        } catch (\Exception $e) {
            // ── Error general (BD, validación, token, etc.) ──
            $db->transRollback();
            $errorMsg = $e->getMessage();

            if (strpos($errorMsg, 'TOKEN_INVALID_GRANT') !== false || strpos($errorMsg, 'invalid_grant') !== false) {
                log_message('critical', '[Google Drive] Token inválido. Ejecutar obtener_token.php. Detalle: ' . $errorMsg);
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'La conexión con Google Drive ha expirado. El administrador debe renovarla desde el panel. (Código: DRV-AUTH)'
                ]);
            }

            log_message('error', 'Error al procesar postulación: ' . $errorMsg);
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Ocurrió un error inesperado al procesar su postulación. Por favor, intente nuevamente. (Código: SYS-ERR)'
            ]);
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

