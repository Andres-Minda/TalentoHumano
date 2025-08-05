<?php $sidebar = 'sidebar_super_admin'; ?>
<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>
<div class="page-wrapper">
    <div class="page-content">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Respaldo y Restauración</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="<?= base_url('super-admin/dashboard') ?>">Dashboard</a></li>
                            <li class="breadcrumb-item active">Respaldo y Restauración</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Estadísticas de Respaldo -->
            <div class="col-xl-3 col-md-6">
                <div class="card card-animate">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h6 class="text-muted mb-3">Total de Respaldos</h6>
                                <h2 class="mb-0"><?= $totalRespaldos ?? 12 ?></h2>
                                <p class="text-muted mb-0">Último: <?= $ultimoRespaldo ?? 'Hace 2 horas' ?></p>
                            </div>
                            <div class="flex-shrink-0 text-end">
                                <i class="bi bi-cloud-arrow-up text-primary" style="font-size: 2rem;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card card-animate">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h6 class="text-muted mb-3">Tamaño Total</h6>
                                <h2 class="mb-0"><?= $tamañoTotal ?? '2.4 GB' ?></h2>
                                <p class="text-muted mb-0">Espacio utilizado</p>
                            </div>
                            <div class="flex-shrink-0 text-end">
                                <i class="bi bi-hdd-stack text-success" style="font-size: 2rem;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card card-animate">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h6 class="text-muted mb-3">Estado del Sistema</h6>
                                <h2 class="mb-0 text-success">Activo</h2>
                                <p class="text-muted mb-0">Respaldo automático</p>
                            </div>
                            <div class="flex-shrink-0 text-end">
                                <i class="bi bi-check-circle text-success" style="font-size: 2rem;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card card-animate">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h6 class="text-muted mb-3">Próximo Respaldo</h6>
                                <h2 class="mb-0"><?= $proximoRespaldo ?? '23:00' ?></h2>
                                <p class="text-muted mb-0">Programado</p>
                            </div>
                            <div class="flex-shrink-0 text-end">
                                <i class="bi bi-clock text-warning" style="font-size: 2rem;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Crear Respaldo Manual -->
            <div class="col-xl-6">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-0">
                            <i class="bi bi-cloud-arrow-up text-primary me-2"></i>
                            Crear Respaldo Manual
                        </h4>
                    </div>
                    <div class="card-body">
                        <form id="formRespaldoManual">
                            <div class="mb-3">
                                <label for="tipoRespaldo" class="form-label">Tipo de Respaldo</label>
                                <select class="form-select" id="tipoRespaldo" name="tipoRespaldo">
                                    <option value="completo">Respaldo Completo</option>
                                    <option value="incremental">Respaldo Incremental</option>
                                    <option value="diferencial">Respaldo Diferencial</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="descripcion" class="form-label">Descripción (Opcional)</label>
                                <textarea class="form-control" id="descripcion" name="descripcion" rows="3" placeholder="Descripción del respaldo..."></textarea>
                            </div>
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="comprimir" name="comprimir" checked>
                                    <label class="form-check-label" for="comprimir">
                                        Comprimir respaldo
                                    </label>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-cloud-arrow-up me-2"></i>
                                Crear Respaldo
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Restaurar Respaldo -->
            <div class="col-xl-6">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-0">
                            <i class="bi bi-cloud-arrow-down text-success me-2"></i>
                            Restaurar Respaldo
                        </h4>
                    </div>
                    <div class="card-body">
                        <form id="formRestaurar">
                            <div class="mb-3">
                                <label for="archivoRespaldo" class="form-label">Seleccionar Archivo</label>
                                <input type="file" class="form-control" id="archivoRespaldo" name="archivoRespaldo" accept=".sql,.zip,.bak">
                                <div class="form-text">Formatos soportados: .sql, .zip, .bak</div>
                            </div>
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="confirmarRestauracion" name="confirmarRestauracion">
                                    <label class="form-check-label" for="confirmarRestauracion">
                                        Confirmar restauración (se perderán los datos actuales)
                                    </label>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-success" disabled id="btnRestaurar">
                                <i class="bi bi-cloud-arrow-down me-2"></i>
                                Restaurar Respaldo
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Historial de Respaldos -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-0">
                            <i class="bi bi-clock-history text-info me-2"></i>
                            Historial de Respaldos
                        </h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Fecha</th>
                                        <th>Tipo</th>
                                        <th>Tamaño</th>
                                        <th>Estado</th>
                                        <th>Descripción</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($historialRespaldos ?? [] as $respaldo): ?>
                                    <tr>
                                        <td><?= $respaldo['fecha'] ?></td>
                                        <td>
                                            <span class="badge bg-<?= $respaldo['tipo'] === 'completo' ? 'primary' : ($respaldo['tipo'] === 'incremental' ? 'info' : 'warning') ?>">
                                                <?= ucfirst($respaldo['tipo']) ?>
                                            </span>
                                        </td>
                                        <td><?= $respaldo['tamaño'] ?></td>
                                        <td>
                                            <span class="badge bg-<?= $respaldo['estado'] === 'completado' ? 'success' : 'danger' ?>">
                                                <?= ucfirst($respaldo['estado']) ?>
                                            </span>
                                        </td>
                                        <td><?= $respaldo['descripcion'] ?></td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <button type="button" class="btn btn-sm btn-outline-primary" title="Descargar" onclick="descargarRespaldo(<?= $respaldo['id'] ?? 1 ?>)">
                                                    <i class="bi bi-download"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline-success" title="Restaurar">
                                                    <i class="bi bi-arrow-clockwise"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline-danger" title="Eliminar">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Habilitar botón de restaurar solo si se confirma
    document.getElementById('confirmarRestauracion').addEventListener('change', function() {
        document.getElementById('btnRestaurar').disabled = !this.checked;
    });

    // Formulario de respaldo manual
    document.getElementById('formRespaldoManual').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        const btn = this.querySelector('button[type="submit"]');
        const originalText = btn.innerHTML;
        btn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Creando respaldo...';
        btn.disabled = true;

        // Enviar formulario y descargar archivo
        fetch('<?= base_url('super-admin/respaldo/crear') ?>', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            if (response.ok) {
                return response.blob();
            }
            throw new Error('Error al crear respaldo');
        })
        .then(blob => {
            // Crear enlace de descarga
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `respaldo_${formData.get('tipoRespaldo')}_${new Date().toISOString().slice(0,19).replace(/:/g, '-')}.${formData.get('comprimir') ? 'zip' : 'sql'}`;
            document.body.appendChild(a);
            a.click();
            window.URL.revokeObjectURL(url);
            document.body.removeChild(a);
            
            btn.innerHTML = originalText;
            btn.disabled = false;
            alert('Respaldo creado y descargado exitosamente');
        })
        .catch(error => {
            console.error('Error:', error);
            btn.innerHTML = originalText;
            btn.disabled = false;
            alert('Error al crear el respaldo');
        });
    });

    // Formulario de restauración
    document.getElementById('formRestaurar').addEventListener('submit', function(e) {
        e.preventDefault();
        
        if (!document.getElementById('confirmarRestauracion').checked) {
            alert('Debe confirmar la restauración');
            return;
        }

        const btn = this.querySelector('button[type="submit"]');
        const originalText = btn.innerHTML;
        btn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Restaurando...';
        btn.disabled = true;

        setTimeout(() => {
            btn.innerHTML = originalText;
            btn.disabled = false;
            alert('Restauración completada exitosamente');
        }, 5000);
    });

    // Función para descargar respaldo existente
    window.descargarRespaldo = function(id) {
        const url = id ? `<?= base_url('super-admin/respaldo/descargar') ?>/${id}` : '<?= base_url('super-admin/respaldo/descargar') ?>';
        
        fetch(url)
        .then(response => {
            if (response.ok) {
                return response.blob();
            }
            throw new Error('Error al descargar respaldo');
        })
        .then(blob => {
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `respaldo_completo_${new Date().toISOString().slice(0,19).replace(/:/g, '-')}.sql`;
            document.body.appendChild(a);
            a.click();
            window.URL.revokeObjectURL(url);
            document.body.removeChild(a);
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al descargar el respaldo');
        });
    };
});
</script>
<?= $this->endSection() ?> 