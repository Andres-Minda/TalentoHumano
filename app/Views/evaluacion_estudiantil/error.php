<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= esc($titulo ?? 'Error') ?> — ISTI</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: #f4f6fa;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .error-card {
            max-width: 480px;
            border: none;
            border-radius: 16px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.08);
            text-align: center;
            padding: 3rem 2rem;
        }
        .error-icon {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.5rem;
        }
    </style>
</head>
<body>
    <div class="card error-card">
        <div class="error-icon bg-danger bg-opacity-10 mx-auto">
            <i class="bi bi-exclamation-triangle-fill text-danger" style="font-size: 2.5rem;"></i>
        </div>
        <h3 class="mb-3"><?= esc($titulo ?? 'Error') ?></h3>
        <p class="text-muted mb-4"><?= esc($mensaje ?? 'Ha ocurrido un error inesperado.') ?></p>
        <hr>
        <small class="text-muted">
            <i class="bi bi-building me-1"></i>Instituto Superior Tecnológico ISTI
        </small>
    </div>
</body>
</html>
