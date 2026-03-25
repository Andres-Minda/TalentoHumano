<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Evaluación Docente — ISTI</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        :root {
            --primary: #206bc4;
            --primary-light: #e8f0fe;
            --success: #2fb344;
            --bg-body: #f4f6fa;
        }
        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg-body);
            min-height: 100vh;
        }
        .eval-header {
            background: linear-gradient(135deg, #206bc4 0%, #1a56a0 100%);
            color: #fff;
            padding: 2rem 0;
        }
        .eval-header .badge-grupo {
            background: rgba(255,255,255,0.2);
            font-size: 0.85rem;
            padding: 0.4em 0.8em;
        }
        .eval-card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.06);
            margin-bottom: 1.5rem;
        }
        .eval-card .card-header {
            background: var(--primary-light);
            border-bottom: 2px solid var(--primary);
            border-radius: 12px 12px 0 0 !important;
            font-weight: 600;
            color: var(--primary);
        }
        .likert-group {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }
        .likert-group .btn-check:checked + .btn {
            background: var(--primary);
            color: #fff;
            border-color: var(--primary);
        }
        .likert-group .btn {
            min-width: 48px;
            font-weight: 600;
            border-radius: 8px !important;
        }
        .pregunta-item {
            padding: 1rem 0;
            border-bottom: 1px solid #eee;
        }
        .pregunta-item:last-child { border-bottom: none; }
        .footer-eval {
            background: #fff;
            border-top: 1px solid #e0e0e0;
            padding: 1rem 0;
            text-align: center;
            color: #999;
            font-size: 0.8rem;
        }
        .anonimo-badge {
            background: var(--success);
            color: #fff;
            padding: 0.3em 0.8em;
            border-radius: 20px;
            font-size: 0.75rem;
        }
        @media (max-width: 576px) {
            .likert-group .btn { min-width: 40px; font-size: 0.85rem; }
        }
    </style>
</head>
<body>

<!-- Header -->
<div class="eval-header">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-auto">
                <div class="bg-white rounded-circle d-flex align-items-center justify-content-center" style="width:56px;height:56px;">
                    <i class="bi bi-mortarboard-fill text-primary fs-3"></i>
                </div>
            </div>
            <div class="col">
                <h4 class="mb-1">Evaluación Docente</h4>
                <p class="mb-1 opacity-75">
                    <i class="bi bi-person-badge me-1"></i>
                    <strong><?= esc($docente['nombres'] . ' ' . $docente['apellidos']) ?></strong>
                </p>
                <span class="badge eval-header .badge-grupo">
                    <i class="bi bi-people me-1"></i><?= esc($tokenData['grupo_curso'] ?? 'Sin grupo') ?>
                </span>
                <span class="anonimo-badge ms-2">
                    <i class="bi bi-shield-lock me-1"></i>Evaluación Anónima
                </span>
            </div>
        </div>
    </div>
</div>

<!-- Contenido principal -->
<div class="container py-4">

    <!-- Instrucciones -->
    <div class="alert alert-info border-0 shadow-sm" role="alert">
        <div class="d-flex align-items-start">
            <i class="bi bi-info-circle-fill fs-4 me-3 text-primary"></i>
            <div>
                <strong>Instrucciones:</strong> Evalúa al docente en cada aspecto usando la escala del 1 al 5.
                Tu identidad es <strong>completamente anónima</strong>. Solo se registrarán tus respuestas de forma agregada.
                <div class="mt-2">
                    <span class="badge bg-danger">1</span> Deficiente
                    <span class="badge bg-warning text-dark ms-1">2</span> Regular
                    <span class="badge bg-secondary ms-1">3</span> Aceptable
                    <span class="badge bg-info ms-1">4</span> Bueno
                    <span class="badge bg-success ms-1">5</span> Excelente
                </div>
            </div>
        </div>
    </div>

    <form id="formEvaluacion">
        <?php if (!empty($categorias)): ?>
            <?php foreach ($categorias as $cat): ?>
                <div class="card eval-card">
                    <div class="card-header">
                        <i class="bi bi-bookmark-fill me-2"></i><?= esc($cat['nombre']) ?>
                        <?php if (!empty($cat['descripcion'])): ?>
                            <small class="d-block fw-normal text-muted mt-1"><?= esc($cat['descripcion']) ?></small>
                        <?php endif; ?>
                    </div>
                    <div class="card-body">
                        <?php if (isset($preguntasPorCategoria[$cat['id']])): ?>
                            <?php foreach ($preguntasPorCategoria[$cat['id']] as $idx => $pregunta): ?>
                                <div class="pregunta-item">
                                    <label class="form-label fw-medium mb-2">
                                        <?= ($idx + 1) ?>. <?= esc($pregunta['pregunta']) ?>
                                    </label>
                                    <div class="likert-group">
                                        <?php for ($v = 1; $v <= 5; $v++): ?>
                                            <input type="radio" class="btn-check" name="respuestas[<?= $pregunta['id'] ?>]"
                                                   id="p<?= $pregunta['id'] ?>_v<?= $v ?>" value="<?= $v ?>" required>
                                            <label class="btn btn-outline-primary btn-sm" for="p<?= $pregunta['id'] ?>_v<?= $v ?>">
                                                <?= $v ?>
                                            </label>
                                        <?php endfor; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="text-muted mb-0">No hay preguntas en esta categoría.</p>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="alert alert-warning">
                <i class="bi bi-exclamation-triangle me-2"></i>
                No se han configurado categorías o preguntas de evaluación. Contacta al administrador.
            </div>
        <?php endif; ?>

        <!-- Observaciones opcionales -->
        <div class="card eval-card">
            <div class="card-header">
                <i class="bi bi-chat-text me-2"></i>Observaciones (Opcional)
            </div>
            <div class="card-body">
                <textarea class="form-control" name="observaciones" rows="3"
                          placeholder="Escribe comentarios adicionales sobre el desempeño del docente..."></textarea>
                <small class="text-muted">Tus comentarios son anónimos y serán utilizados para mejorar la calidad educativa.</small>
            </div>
        </div>

        <!-- Botón enviar -->
        <div class="text-center mb-4">
            <button type="button" class="btn btn-primary btn-lg px-5 shadow" onclick="enviarEvaluacion()">
                <i class="bi bi-send me-2"></i>Enviar Evaluación
            </button>
        </div>
    </form>
</div>

<!-- Footer -->
<div class="footer-eval">
    <i class="bi bi-shield-check me-1"></i>
    Esta evaluación es completamente anónima y confidencial — Instituto Superior Tecnológico ISTI
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
function enviarEvaluacion() {
    const form = document.getElementById('formEvaluacion');
    const fd = new FormData(form);

    // Verificar que se respondieron todas las preguntas
    const radios = form.querySelectorAll('input[type="radio"]');
    const grupos = new Set();
    radios.forEach(r => grupos.add(r.name));

    let sinResponder = 0;
    grupos.forEach(nombre => {
        const seleccionado = form.querySelector(`input[name="${nombre}"]:checked`);
        if (!seleccionado) sinResponder++;
    });

    if (sinResponder > 0) {
        Swal.fire({
            icon: 'warning',
            title: 'Preguntas sin responder',
            text: `Tienes ${sinResponder} pregunta(s) sin responder. Por favor, completa todas las preguntas.`,
            confirmButtonColor: '#206bc4'
        });
        return;
    }

    Swal.fire({
        title: '¿Enviar evaluación?',
        text: 'Una vez enviada no podrás modificar tus respuestas.',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#2fb344',
        cancelButtonColor: '#6c757d',
        confirmButtonText: '<i class="bi bi-send"></i> Sí, enviar',
        cancelButtonText: 'Cancelar'
    }).then(result => {
        if (result.isConfirmed) {
            // Mostrar loading
            Swal.fire({
                title: 'Enviando...',
                text: 'Registrando tu evaluación de forma anónima',
                allowOutsideClick: false,
                allowEscapeKey: false,
                didOpen: () => Swal.showLoading()
            });

            fetch('<?= site_url('evaluacion-estudiantil/' . $token) ?>', {
                method: 'POST',
                body: fd
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Gracias!',
                        html: '<p>Tu evaluación ha sido registrada de manera <strong>anónima</strong>.</p><p class="text-muted">Puedes cerrar esta ventana.</p>',
                        confirmButtonColor: '#2fb344',
                        confirmButtonText: 'Entendido',
                        allowOutsideClick: false
                    }).then(() => {
                        document.body.innerHTML = `
                            <div class="d-flex flex-column align-items-center justify-content-center min-vh-100 text-center p-4" style="background:var(--bg-body)">
                                <div class="bg-white rounded-4 shadow p-5" style="max-width:480px">
                                    <div class="bg-success bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-4" style="width:80px;height:80px">
                                        <i class="bi bi-check-circle-fill text-success" style="font-size:2.5rem"></i>
                                    </div>
                                    <h3 class="mb-3">¡Evaluación Completada!</h3>
                                    <p class="text-muted">Tu evaluación anónima ha sido registrada exitosamente. Gracias por tu participación.</p>
                                    <hr>
                                    <small class="text-muted"><i class="bi bi-shield-lock me-1"></i>Tu identidad es completamente confidencial.</small>
                                </div>
                            </div>`;
                    });
                } else {
                    Swal.fire({ icon: 'error', title: 'Error', text: data.message, confirmButtonColor: '#206bc4' });
                }
            })
            .catch(() => {
                Swal.fire({ icon: 'error', title: 'Error de conexión', text: 'No se pudo enviar. Verifica tu conexión e intenta nuevamente.', confirmButtonColor: '#206bc4' });
            });
        }
    });
}
</script>
</body>
</html>
