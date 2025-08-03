<div class="row">
    <div class="col-md-6">
        <h6 class="text-muted">Información del Documento</h6>
        <table class="table table-borderless">
            <tr>
                <td><strong>Nombre:</strong></td>
                <td><?= $documento['nombre'] ?></td>
            </tr>
            <tr>
                <td><strong>Categoría:</strong></td>
                <td><?= $documento['categoria_nombre'] ?></td>
            </tr>
            <tr>
                <td><strong>Descripción:</strong></td>
                <td><?= $documento['descripcion'] ?></td>
            </tr>
            <tr>
                <td><strong>Estado:</strong></td>
                <td>
                    <span class="badge bg-<?= $documento['estado'] == 'Aprobado' ? 'success' : ($documento['estado'] == 'Pendiente' ? 'warning' : 'danger') ?>">
                        <?= $documento['estado'] ?>
                    </span>
                </td>
            </tr>
        </table>
    </div>
    <div class="col-md-6">
        <h6 class="text-muted">Detalles Técnicos</h6>
        <table class="table table-borderless">
            <tr>
                <td><strong>Tipo de archivo:</strong></td>
                <td><?= $documento['tipo_archivo'] ?></td>
            </tr>
            <tr>
                <td><strong>Tamaño:</strong></td>
                <td><?= $documento['tamaño'] ?></td>
            </tr>
            <tr>
                <td><strong>Fecha de subida:</strong></td>
                <td><?= date('d/m/Y H:i', strtotime($documento['fecha_subida'])) ?></td>
            </tr>
            <tr>
                <td><strong>Empleado:</strong></td>
                <td><?= $documento['empleado_nombres'] . ' ' . $documento['empleado_apellidos'] ?></td>
            </tr>
        </table>
    </div>
</div>

<div class="row mt-3">
    <div class="col-12">
        <h6 class="text-muted">Vista Previa</h6>
        <div class="border rounded p-3 bg-light text-center">
            <i class="bi bi-file-earmark-text" style="font-size: 3rem; color: #6c757d;"></i>
            <p class="mt-2 mb-0"><?= $documento['nombre'] ?></p>
            <small class="text-muted">Vista previa no disponible</small>
        </div>
    </div>
</div> 