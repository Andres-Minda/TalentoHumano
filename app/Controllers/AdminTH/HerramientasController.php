<?php

namespace App\Controllers\AdminTH;

use App\Controllers\BaseController;
use Config\Database;

/**
 * HerramientasController
 *
 * Controlador de herramientas administrativas exclusivo del rol AdminTH.
 * Reemplaza de forma segura los scripts sueltos de la raíz:
 *   - credenciales_finales.php  → credenciales()
 *   - export_schema.php         → exportarSchema()
 *   - obtener_token.php         → googleToken()
 *
 * Todas las rutas están protegidas por los filtros 'auth' y 'role:AdministradorTalentoHumano'
 * definidos en app/Config/Routes.php.
 */
class HerramientasController extends BaseController
{
    // ─────────────────────────────────────────────────────────────────────────
    // MÉTODO AUXILIAR PRIVADO: datos comunes para las vistas
    // ─────────────────────────────────────────────────────────────────────────

    private function datosVista(string $titulo): array
    {
        return [
            'titulo'  => $titulo,
            'usuario' => [
                'nombres'   => session()->get('nombres'),
                'apellidos' => session()->get('apellidos'),
                'rol'       => session()->get('nombre_rol'),
            ],
        ];
    }

    // ─────────────────────────────────────────────────────────────────────────
    // 1. CREDENCIALES DEL SISTEMA
    // Reemplaza: credenciales_finales.php
    // Ruta: GET admin-th/herramientas/credenciales
    // ─────────────────────────────────────────────────────────────────────────

    public function credenciales(): string
    {
        $db = Database::connect();

        // Consultar todos los usuarios con su rol y datos de empleado
        $usuarios = $db->table('usuarios u')
            ->select('u.id_usuario, u.cedula, u.email, u.id_rol, u.activo,
                      u.password_changed, r.nombre_rol,
                      e.nombres, e.apellidos, e.tipo_empleado, e.departamento')
            ->join('roles r', 'r.id_rol = u.id_rol', 'left')
            ->join('empleados e', 'e.id_usuario = u.id_usuario', 'left')
            ->orderBy('u.id_rol')->orderBy('u.id_usuario')
            ->get()->getResultArray();

        // Agrupar por rol para mejor presentación en la vista
        $usuariosPorRol = [];
        foreach ($usuarios as $usuario) {
            $rolNombre = $usuario['nombre_rol'] ?? 'Sin Rol';
            if (! isset($usuariosPorRol[$rolNombre])) {
                $usuariosPorRol[$rolNombre] = [];
            }
            $usuariosPorRol[$rolNombre][] = $usuario;
        }

        $data = $this->datosVista('Credenciales del Sistema');
        $data['usuariosPorRol'] = $usuariosPorRol;
        $data['totalUsuarios']  = count($usuarios);

        return view('Roles/AdminTH/herramientas/credenciales', $data);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // 2. EXPORTAR ESQUEMA DE BASE DE DATOS
    // Reemplaza: export_schema.php
    // Ruta: GET admin-th/herramientas/exportar-schema
    // El esquema se genera "al vuelo" y se descarga como .txt — no se escribe
    // ningún archivo en disco, evitando la exposición pública del original.
    // ─────────────────────────────────────────────────────────────────────────

    public function exportarSchema()
    {
        $db = Database::connect();

        // Obtener nombre real de la BD desde la config activa
        $dbName = $db->getDatabase();

        $output  = "MODELO DE BASE DE DATOS: {$dbName}\n";
        $output .= str_repeat('=', 50) . "\n";
        $output .= "Generado: " . date('Y-m-d H:i:s') . " — por Usuario ID: " . session()->get('id_usuario') . "\n\n";

        $tables = array_map('current', $db->query('SHOW TABLES')->getResultArray());

        foreach ($tables as $table) {
            $output .= "TABLA: {$table}\n";
            $output .= str_repeat('-', strlen("TABLA: {$table}")) . "\n";

            // Columnas
            $cols = $db->query("SHOW COLUMNS FROM `{$table}`")->getResultArray();
            $output .= "Columnas:\n";
            foreach ($cols as $col) {
                $notNull = $col['Null'] === 'NO' ? ' NOT NULL' : '';
                $pk      = $col['Key'] === 'PRI' ? ' [PK]' : '';
                $uni     = $col['Key'] === 'UNI' ? ' [UNIQUE]' : '';
                $extra   = $col['Extra'] ? ' ' . $col['Extra'] : '';
                $output .= "  - {$col['Field']} ({$col['Type']}){$notNull}{$pk}{$uni}{$extra}\n";
            }

            // Claves foráneas
            $fks = $db->query("
                SELECT COLUMN_NAME, REFERENCED_TABLE_NAME, REFERENCED_COLUMN_NAME
                FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
                WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ? AND REFERENCED_TABLE_NAME IS NOT NULL
            ", [$dbName, $table])->getResultArray();

            if (! empty($fks)) {
                $output .= "\nRelaciones (Claves Foráneas):\n";
                foreach ($fks as $fk) {
                    $output .= "  -> {$fk['COLUMN_NAME']} referencia a {$fk['REFERENCED_TABLE_NAME']} ({$fk['REFERENCED_COLUMN_NAME']})\n";
                }
            }

            $output .= "\n";
        }

        // Descarga directa al navegador sin escribir archivo en disco
        $filename = 'schema_' . $dbName . '_' . date('Ymd_His') . '.txt';
        return $this->response->download($filename, $output);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // 3. AUTORIZACIÓN OAUTH2 GOOGLE DRIVE
    // Reemplaza: obtener_token.php
    // Ruta: GET  admin-th/herramientas/google-token
    //       GET  admin-th/herramientas/google-token?code=XXXXX  (callback OAuth)
    // ─────────────────────────────────────────────────────────────────────────

    public function googleToken(): string
    {
        // Verificar que la librería Google Client esté disponible
        if (! class_exists('Google\Client')) {
            $data = $this->datosVista('Google Drive — Token OAuth2');
            $data['error'] = 'La librería Google Client no está instalada. Ejecuta: composer require google/apiclient';
            return view('Roles/AdminTH/herramientas/google_token', $data);
        }

        $secretsPath = WRITEPATH . 'client_secrets.json';
        $tokenPath   = WRITEPATH . 'token.json';

        // Verificar que exista el archivo de credenciales
        if (! file_exists($secretsPath)) {
            $data = $this->datosVista('Google Drive — Token OAuth2');
            $data['error'] = "No se encontró el archivo <code>client_secrets.json</code> en <code>writable/</code>. "
                . "Descárgalo desde Google Cloud Console y colócalo en esa carpeta.";
            return view('Roles/AdminTH/herramientas/google_token', $data);
        }

        $client = new \Google\Client();
        $client->setAuthConfig($secretsPath);
        $client->setAccessType('offline');
        $client->setPrompt('select_account consent');
        $client->setIncludeGrantedScopes(true);
        $client->setScopes([\Google\Service\Drive::DRIVE_FILE]);

        // La URL de callback debe apuntar a ESTA ruta del controlador
        $client->setRedirectUri(site_url('admin-th/herramientas/google-token'));

        $code = $this->request->getGet('code');

        $data = $this->datosVista('Google Drive — Token OAuth2');
        $data['tokenPath']   = $tokenPath;
        $data['secretsPath'] = $secretsPath;
        $data['authUrl']     = null;
        $data['accessToken'] = null;
        $data['tokenError']  = null;
        $data['error']       = null;

        if (! $code) {
            // Paso 1: Mostrar el enlace de autorización
            $data['authUrl'] = $client->createAuthUrl();
        } else {
            // Paso 2: Procesar el código de retorno de Google
            $accessToken = $client->fetchAccessTokenWithAuthCode($code);

            if (isset($accessToken['error'])) {
                $data['tokenError'] = [
                    'code'        => $accessToken['error'],
                    'description' => $accessToken['error_description'] ?? 'Sin descripción.',
                ];
            } else {
                // Guardar token en writable/ (NUNCA en la raíz pública)
                file_put_contents($tokenPath, json_encode($accessToken, JSON_PRETTY_PRINT));
                $data['accessToken']    = $accessToken;
                $data['hasRefreshToken'] = isset($accessToken['refresh_token']);
            }
        }

        return view('Roles/AdminTH/herramientas/google_token', $data);
    }
}
