<?php
// Carga las librerías de Composer
require __DIR__ . '/vendor/autoload.php';

$client = new Google\Client();
// Ruta al archivo que descargaste en el Paso 1
$client->setAuthConfig(__DIR__ . '/writable/client_secrets.json');
$client->setAccessType('offline'); 
$client->setPrompt('select_account consent');
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
    
    // Guardamos el token en la carpeta writable
    $tokenPath = __DIR__ . '/writable/token.json';
    file_put_contents($tokenPath, json_encode($accessToken));
    
    echo "<h2>¡ÉXITO TOTAL!</h2>";
    echo "El archivo <b>token.json</b> se ha creado correctamente en: <code>$tokenPath</code><br>";
    echo "Ya puedes borrar este archivo (obtener_token.php) por seguridad.";
}