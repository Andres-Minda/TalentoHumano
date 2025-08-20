<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error 404 - Página no encontrada</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            color: #333;
        }
        .error-container {
            background: white;
            padding: 3rem;
            border-radius: 15px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            text-align: center;
            max-width: 500px;
            width: 90%;
        }
        .error-code {
            font-size: 6rem;
            font-weight: bold;
            color: #667eea;
            margin: 0;
            line-height: 1;
        }
        .error-title {
            font-size: 1.5rem;
            color: #333;
            margin: 1rem 0;
        }
        .error-message {
            color: #666;
            margin-bottom: 2rem;
            line-height: 1.6;
        }
        .back-button {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 25px;
            text-decoration: none;
            display: inline-block;
            font-weight: 500;
            transition: transform 0.3s ease;
        }
        .back-button:hover {
            transform: translateY(-2px);
            color: white;
            text-decoration: none;
        }
        .icon {
            font-size: 4rem;
            color: #667eea;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="icon">🔍</div>
        <h1 class="error-code">404</h1>
        <h2 class="error-title">Página no encontrada</h2>
        <p class="error-message">
            Lo sentimos, la página que estás buscando no existe o ha sido movida.
            <br>
            Verifica la URL o regresa al inicio.
        </p>
        <a href="<?= base_url() ?>" class="back-button">
            ← Volver al inicio
        </a>
    </div>
</body>
</html>
