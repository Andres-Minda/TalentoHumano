<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Inasistencias - <?= esc($empleado['nombres'] . ' ' . $empleado['apellidos']) ?></title>
    <!-- Bootstrap CSS for clean print styling -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
            font-size: 14px;
        }
        .report-header {
            border-bottom: 2px solid #343a40;
            margin-bottom: 20px;
            padding-bottom: 10px;
        }
        .report-title {
            font-weight: bold;
            text-transform: uppercase;
        }
        .employee-info {
            background-color: #e9ecef;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 25px;
        }
        .empty-state {
            text-align: center;
            padding: 30px;
            color: #6c757d;
            font-style: italic;
        }
        /* Estilos específicos para impresión */
        @media print {
            body { 
                background-color: #fff; 
                margin: 0; 
                padding: 0; 
            }
            .container { 
                width: 100%; 
                max-width: 100%; 
                padding: 0; 
            }
            .no-print { 
                display: none !important; 
            }
            .table-bordered th, .table-bordered td {
                border: 1px solid #dee2e6 !important;
            }
            .badge {
                border: 1px solid #000;
                color: #000 !important;
                background-color: transparent !important;
            }
        }
    </style>
</head>
<body>

<div class="container my-5 p-4 bg-white shadow-sm border rounded">
    
    <div class="row report-header align-items-center">
        <div class="col-8">
            <h3 class="report-title mb-1">Empresa / Instituto Demo</h3>
            <p class="mb-0 text-muted">Departamento de Talento Humano</p>
        </div>
        <div class="col-4 text-end">
            <h5 class="mb-0">Reporte de Inasistencias</h5>
            <small><b>Fecha de Emisión:</b> <?= date('d/m/Y H:i') ?></small>
        </div>
    </div>

    <div class="employee-info row">
        <div class="col-md-6">
            <p class="mb-1"><strong>Empleado:</strong> <?= esc($empleado['apellidos'] . ' ' . $empleado['nombres']) ?></p>
            <p class="mb-1"><strong>Cédula / ID:</strong> <?= esc($empleado['cedula'] ?? 'N/A') ?></p>
        </div>
        <div class="col-md-6 text-md-end">
            <p class="mb-1"><strong>Departamento:</strong> <?= esc($empleado['departamento'] ?? 'N/A') ?></p>
            <p class="mb-1"><strong>Estado:</strong> <?= esc($empleado['estado'] ?? 'Desconocido') ?></p>
        </div>
    </div>

    <h5 class="mb-3">Historial de Faltas Registradas</h5>

    <?php if (empty($inasistencias)): ?>
        <div class="empty-state border border-dashed rounded">
            Este empleado no tiene registros de inasistencias en el sistema.
        </div>
    <?php else: ?>
        <table class="table table-bordered table-striped table-sm">
            <thead class="table-dark">
                <tr>
                    <th style="width: 5%;">#</th>
                    <th style="width: 15%;">Fecha</th>
                    <th style="width: 10%;">Hora</th>
                    <th style="width: 15%;">Tipo</th>
                    <th style="width: 40%;">Motivo Registrado</th>
                    <th style="width: 15%;">Estado O.</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($inasistencias as $index => $ina): ?>
                    <tr>
                        <td class="text-center"><?= $index + 1 ?></td>
                        <td><?= date('d/m/Y', strtotime($ina['fecha_inasistencia'])) ?></td>
                        <td><?= $ina['hora_inasistencia'] ? date('H:i', strtotime($ina['hora_inasistencia'])) : 'N/A' ?></td>
                        <td><?= esc($ina['tipo_inasistencia']) ?></td>
                        <td><?= esc($ina['motivo']) ?></td>
                        <td class="text-center">
                            <?php if ($ina['justificada'] == 1): ?>
                                <span class="badge bg-success">Justificada</span>
                            <?php else: ?>
                                <span class="badge bg-warning text-dark">Sin Justificar</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        
        <div class="row mt-4 pt-3 border-top text-center no-print">
            <div class="col-12">
                <button onclick="window.print()" class="btn btn-primary"><i class="bi bi-printer me-2"></i>Imprimir Reporte</button>
                <button onclick="window.close()" class="btn btn-outline-secondary ms-2">Cerrar Pestaña</button>
            </div>
        </div>
    <?php endif; ?>

    <div class="mt-5 pt-5 row text-center">
        <div class="col border-top border-dark mx-5 pt-2">
            <strong>Firma del Administrador / TH</strong>
        </div>
        <div class="col border-top border-dark mx-5 pt-2">
            <strong>Firma del Empleado</strong>
        </div>
    </div>

</div>

<!-- Bootstrap Bundle JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<!-- Print triggering -->
<script>
    window.onload = function() {
        // Lanza la ventana de impresión un segundo después de cargar (opcional)
        setTimeout(function(){
            window.print();
        }, 500);
    };
</script>
</body>
</html>
