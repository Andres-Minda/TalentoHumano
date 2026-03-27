<?php
/**
 * obtener_token.php — Script de autorización OAuth2 para Google Drive
 *
 * Uso: Abrir en navegador → Hacer clic en "AUTORIZAR MI CUENTA" → Pegar el código.
 *
 * IMPORTANTE: Asegúrate de que la app en Google Cloud Console esté en estado "Producción"
 * (no "Prueba") para que el refresh_token no expire cada 7 días.
 * Ver guía completa en PostulacionController.php.
 */

// Carga las librerías de Composer
require dirname(__DIR__) . '/vendor/autoload.php';

$client = new Google\Client();
// Ruta al archivo que descargaste en el Paso 1
$client->setAuthConfig(dirname(__DIR__) . '/writable/client_secrets.json');

// access_type=offline  → Garantiza la obtención de un refresh_token
// prompt=consent        → Fuerza la pantalla de consentimiento para regenerar el refresh_token
$client->setAccessType('offline'); 
$client->setPrompt('select_account consent');
$client->setIncludeGrantedScopes(true); // Soporte incremental de scopes
$client->setScopes([Google\Service\Drive::DRIVE_FILE]);

// Si no hay un código de Google en la URL, mostramos el link
if (!isset($_GET['code'])) {
    $authUrl = $client->createAuthUrl();
    echo "<h1>Paso 2: Autorización de Google</h1>";
    echo "<ol>";
    echo "<li>Haz clic en este enlace: <a href='$authUrl' target='_blank'>AUTORIZAR MI CUENTA</a></li>";
    echo "<li>Inicia sesión con tu cuenta de Gmail (la que tiene espacio).</li>";
    echo "<li>Google te dirá que la app no es segura. Haz clic en <b>'Configuración avanzada'</b> y luego en <b>'Ir a TalentoHumano (no seguro)'</b>.</li>";
    echo "<li>Copia el código largo que te dará Google.</li>";
    echo "<li>Pégalo al final de la URL de esta página así: <code>?code=AQUÍ_TU_CÓDIGO</code> y pulsa Enter.</li>";
    echo "</ol>";
} else {
    // Si ya tenemos el código, generamos el token
    $accessToken = $client->fetchAccessTokenWithAuthCode($_GET['code']);
    
    // Validar que el token no contenga errores antes de guardarlo
    if (isset($accessToken['error'])) {
        echo "<h2 style='color:red;'>❌ ERROR AL OBTENER TOKEN</h2>";
        echo "<p><b>Error:</b> " . htmlspecialchars($accessToken['error']) . "</p>";
        echo "<p><b>Descripción:</b> " . htmlspecialchars($accessToken['error_description'] ?? 'Sin descripción') . "</p>";
        echo "<p>Posibles causas:</p>";
        echo "<ul>";
        echo "<li>El código de autorización ya fue usado (solo se puede usar una vez).</li>";
        echo "<li>El código expiró (tiene una validez muy corta).</li>";
        echo "<li>Las credenciales en client_secrets.json no coinciden con la app de Google Cloud.</li>";
        echo "</ul>";
        echo "<p><a href='obtener_token.php'>Intentar de nuevo</a></p>";
        exit;
    }

    // Guardamos el token en la carpeta writable
    $tokenPath = dirname(__DIR__) . '/writable/token.json';
    file_put_contents($tokenPath, json_encode($accessToken));
    
    echo "<h2 style='color:green;'>✅ ¡ÉXITO TOTAL!</h2>";
    echo "El archivo <b>token.json</b> se ha creado correctamente en: <code>$tokenPath</code><br><br>";
    
    // Verificar que se obtuvo el refresh_token (crítico para renovación automática)
    if (isset($accessToken['refresh_token'])) {
        echo "<p style='color:green;'>✅ <b>refresh_token obtenido correctamente.</b> La renovación automática funcionará.</p>";
    } else {
        echo "<p style='color:orange;'>⚠️ <b>No se obtuvo refresh_token.</b> Esto puede ocurrir si ya autorizaste antes. ";
        echo "Para forzar un nuevo refresh_token, revoca el acceso en <a href='https://myaccount.google.com/permissions' target='_blank'>myaccount.google.com/permissions</a> y vuelve a ejecutar este script.</p>";
    }
    
    echo "<br><p>Ya puedes borrar este archivo (obtener_token.php) por seguridad.</p>";
}