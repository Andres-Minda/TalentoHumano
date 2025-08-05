<?php $sidebar = 'sidebar_super_admin'; ?>
<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>
<div class="page-wrapper">
    <div class="page-content">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Reportes del Sistema</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="<?= base_url('super-admin/dashboard') ?>">Dashboard</a></li>
                            <li class="breadcrumb-item active">Reportes del Sistema</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Estadísticas de Reportes -->
            <div class="col-xl-3 col-md-6">
                <div class="card card-animate">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h6 class="text-muted mb-3">Total de Reportes</h6>
                                <h2 class="mb-0"><?= $totalReportes ?? 45 ?></h2>
                                <p class="text-muted mb-0">Generados este mes</p>
                            </div>
                            <div class="flex-shrink-0 text-end">
                                <i class="bi bi-file-earmark-text text-primary" style="font-size: 2rem;"></i>
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
                                <h6 class="text-muted mb-3">Descargas</h6>
                                <h2 class="mb-0"><?= $totalDescargas ?? 128 ?></h2>
                                <p class="text-muted mb-0">Reportes descargados</p>
                            </div>
                            <div class="flex-shrink-0 text-end">
                                <i class="bi bi-download text-success" style="font-size: 2rem;"></i>
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
                                <h6 class="text-muted mb-3">Tiempo Promedio</h6>
                                <h2 class="mb-0"><?= $tiempoPromedio ?? '2.3s' ?></h2>
                                <p class="text-muted mb-0">Generación de reportes</p>
                            </div>
                            <div class="flex-shrink-0 text-end">
                                <i class="bi bi-speedometer2 text-info" style="font-size: 2rem;"></i>
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
                                <h6 class="text-muted mb-3">Almacenamiento</h6>
                                <h2 class="mb-0"><?= $almacenamiento ?? '156 MB' ?></h2>
                                <p class="text-muted mb-0">Reportes almacenados</p>
                            </div>
                            <div class="flex-shrink-0 text-end">
                                <i class="bi bi-hdd-stack text-warning" style="font-size: 2rem;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Generador de Reportes -->
            <div class="col-xl-8">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-0">
                            <i class="bi bi-graph-up text-primary me-2"></i>
                            Generador de Reportes
                        </h4>
                    </div>
                    <div class="card-body">
                        <form id="formGenerarReporte">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="tipoReporte" class="form-label">Tipo de Reporte</label>
                                        <select class="form-select" id="tipoReporte" name="tipoReporte" required>
                                            <option value="">Seleccionar tipo...</option>
                                            <option value="global">Reporte Global Completo</option>
                                            <option value="usuarios">Reporte de Usuarios</option>
                                            <option value="empleados">Reporte de Empleados</option>
                                            <option value="departamentos">Reporte de Departamentos</option>
                                            <option value="capacitaciones">Reporte de Capacitaciones</option>
                                            <option value="asistencias">Reporte de Asistencias</option>
                                            <option value="permisos">Reporte de Permisos</option>
                                            <option value="nominas">Reporte de Nóminas</option>
                                            <option value="sistema">Reporte del Sistema</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="formatoReporte" class="form-label">Formato</label>
                                        <select class="form-select" id="formatoReporte" name="formatoReporte" required>
                                            <option value="txt">Archivo de Texto (.txt)</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="fechaInicio" class="form-label">Fecha de Inicio</label>
                                        <input type="date" class="form-control" id="fechaInicio" name="fechaInicio">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="fechaFin" class="form-label">Fecha de Fin</label>
                                        <input type="date" class="form-control" id="fechaFin" name="fechaFin">
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="filtros" class="form-label">Filtros Adicionales</label>
                                <textarea class="form-control" id="filtros" name="filtros" rows="3" placeholder="Filtros específicos (opcional)..."></textarea>
                            </div>
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="incluirGraficos" name="incluirGraficos" checked>
                                    <label class="form-check-label" for="incluirGraficos">
                                        Incluir gráficos y estadísticas
                                    </label>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-file-earmark-text me-2"></i>
                                Generar Reporte
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Reportes Rápidos -->
            <div class="col-xl-4">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-0">
                            <i class="bi bi-lightning text-warning me-2"></i>
                            Reportes Rápidos
                        </h4>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <button class="btn btn-outline-primary" onclick="generarReporteRapido('usuarios')">
                                <i class="bi bi-people me-2"></i>
                                Usuarios Activos
                            </button>
                            <button class="btn btn-outline-success" onclick="generarReporteRapido('empleados')">
                                <i class="bi bi-person-badge me-2"></i>
                                Empleados por Departamento
                            </button>
                            <button class="btn btn-outline-info" onclick="generarReporteRapido('capacitaciones')">
                                <i class="bi bi-mortarboard me-2"></i>
                                Capacitaciones del Mes
                            </button>
                            <button class="btn btn-outline-warning" onclick="generarReporteRapido('asistencias')">
                                <i class="bi bi-calendar-check me-2"></i>
                                Asistencias Semanales
                            </button>
                            <button class="btn btn-outline-danger" onclick="generarReporteRapido('sistema')">
                                <i class="bi bi-gear me-2"></i>
                                Estado del Sistema
                            </button>
                            <button class="btn btn-outline-dark" onclick="generarReporteGlobal()">
                                <i class="bi bi-globe me-2"></i>
                                Reporte Global Completo
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Gráficos de Estadísticas -->
        <div class="row">
            <div class="col-xl-6">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-0">
                            <i class="bi bi-pie-chart text-info me-2"></i>
                            Reportes por Tipo
                        </h4>
                    </div>
                    <div class="card-body">
                        <div id="chartReportesPorTipo" style="height: 300px;"></div>
                    </div>
                </div>
            </div>
            <div class="col-xl-6">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-0">
                            <i class="bi bi-bar-chart text-success me-2"></i>
                            Generación Mensual
                        </h4>
                    </div>
                    <div class="card-body">
                        <div id="chartGeneracionMensual" style="height: 300px;"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Historial de Reportes -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-0">
                            <i class="bi bi-clock-history text-secondary me-2"></i>
                            Historial de Reportes
                        </h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Fecha</th>
                                        <th>Tipo</th>
                                        <th>Formato</th>
                                        <th>Tamaño</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($historialReportes ?? [] as $reporte): ?>
                                    <tr>
                                        <td><?= $reporte['fecha'] ?></td>
                                        <td>
                                            <span class="badge bg-<?= $reporte['tipo'] === 'usuarios' ? 'primary' : ($reporte['tipo'] === 'empleados' ? 'success' : 'info') ?>">
                                                <?= ucfirst($reporte['tipo']) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary">
                                                <?= strtoupper($reporte['formato']) ?>
                                            </span>
                                        </td>
                                        <td><?= $reporte['tamaño'] ?></td>
                                        <td>
                                            <span class="badge bg-<?= $reporte['estado'] === 'completado' ? 'success' : 'warning' ?>">
                                                <?= ucfirst($reporte['estado']) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <button type="button" class="btn btn-sm btn-outline-primary" title="Descargar" onclick="descargarReporte(<?= $reporte['id'] ?? 1 ?>)">
                                                    <i class="bi bi-download"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline-info" title="Vista Previa">
                                                    <i class="bi bi-eye"></i>
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
    // Formulario de generación de reportes
    document.getElementById('formGenerarReporte').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        const btn = this.querySelector('button[type="submit"]');
        const originalText = btn.innerHTML;
        btn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Generando reporte...';
        btn.disabled = true;

        // Enviar formulario y descargar archivo
        fetch('<?= base_url('super-admin/reportes-sistema/generar') ?>', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            if (response.ok) {
                return response.blob();
            }
            throw new Error('Error al generar reporte');
        })
        .then(blob => {
            // Crear enlace de descarga
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `reporte_${formData.get('tipoReporte')}_${new Date().toISOString().slice(0,19).replace(/:/g, '-')}.txt`;
            document.body.appendChild(a);
            a.click();
            window.URL.revokeObjectURL(url);
            document.body.removeChild(a);
            
            btn.innerHTML = originalText;
            btn.disabled = false;
            alert('Reporte generado y descargado exitosamente');
        })
        .catch(error => {
            console.error('Error:', error);
            btn.innerHTML = originalText;
            btn.disabled = false;
            alert('Error al generar el reporte');
        });
    });

    // Función para generar reporte global
    window.generarReporteGlobal = function() {
        const btn = event.target;
        const originalText = btn.innerHTML;
        btn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Generando...';
        btn.disabled = true;

        const formData = new FormData();
        formData.append('tipoReporte', 'global');
        formData.append('formatoReporte', 'txt'); // Changed to txt for text file
        formData.append('incluirGraficos', 'on');

        fetch('<?= base_url('super-admin/reportes-sistema/global') ?>', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            if (response.ok) {
                return response.blob();
            }
            throw new Error('Error al generar reporte global');
        })
        .then(blob => {
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `reporte_global_${new Date().toISOString().slice(0,19).replace(/:/g, '-')}.txt`; // Changed to txt
            document.body.appendChild(a);
            a.click();
            window.URL.revokeObjectURL(url);
            document.body.removeChild(a);
            
            btn.innerHTML = originalText;
            btn.disabled = false;
            alert('Reporte global generado y descargado exitosamente');
        })
        .catch(error => {
            console.error('Error:', error);
            btn.innerHTML = originalText;
            btn.disabled = false;
            alert('Error al generar el reporte global');
        });
    };

    // Función para generar reporte rápido
    function generarReporteRapido(tipo) {
        const btn = event.target;
        const originalText = btn.innerHTML;
        btn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Generando...';
        btn.disabled = true;

        const formData = new FormData();
        formData.append('tipoReporte', tipo);
        formData.append('formatoReporte', 'txt'); // Changed to txt for text file
        formData.append('incluirGraficos', 'on');

        fetch('<?= base_url('super-admin/reportes-sistema/generar') ?>', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            if (response.ok) {
                return response.blob();
            }
            throw new Error('Error al generar reporte');
        })
        .then(blob => {
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `reporte_${tipo}_${new Date().toISOString().slice(0,19).replace(/:/g, '-')}.txt`; // Changed to txt
            document.body.appendChild(a);
            a.click();
            window.URL.revokeObjectURL(url);
            document.body.removeChild(a);
            
            btn.innerHTML = originalText;
            btn.disabled = false;
            alert(`Reporte de ${tipo} generado y descargado exitosamente`);
        })
        .catch(error => {
            console.error('Error:', error);
            btn.innerHTML = originalText;
            btn.disabled = false;
            alert('Error al generar el reporte');
        });
    }

    // Función para descargar reporte existente
    window.descargarReporte = function(id) {
        const url = id ? `<?= base_url('super-admin/reportes-sistema/descargar') ?>/${id}` : '<?= base_url('super-admin/reportes-sistema/descargar') ?>';
        
        fetch(url)
        .then(response => {
            if (response.ok) {
                return response.blob();
            }
            throw new Error('Error al descargar reporte');
        })
        .then(blob => {
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `reporte_usuarios_${new Date().toISOString().slice(0,19).replace(/:/g, '-')}.txt`; // Changed to txt
            document.body.appendChild(a);
            a.click();
            window.URL.revokeObjectURL(url);
            document.body.removeChild(a);
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al descargar el reporte');
        });
    };

    // Inicializar gráficos
    initCharts();
});

function initCharts() {
    // Gráfico de reportes por tipo
    const optionsReportesPorTipo = {
        series: [44, 55, 13, 43, 22],
        chart: {
            width: 380,
            type: 'pie',
        },
        labels: ['Usuarios', 'Empleados', 'Departamentos', 'Capacitaciones', 'Sistema'],
        responsive: [{
            breakpoint: 480,
            options: {
                chart: {
                    width: 200
                },
                legend: {
                    position: 'bottom'
                }
            }
        }]
    };

    const chartReportesPorTipo = new ApexCharts(document.querySelector("#chartReportesPorTipo"), optionsReportesPorTipo);
    chartReportesPorTipo.render();

    // Gráfico de generación mensual
    const optionsGeneracionMensual = {
        series: [{
            name: 'Reportes Generados',
            data: [30, 40, 35, 50, 49, 60, 70, 91, 125, 150, 180, 200]
        }],
        chart: {
            height: 300,
            type: 'line',
            zoom: {
                enabled: false
            }
        },
        dataLabels: {
            enabled: false
        },
        stroke: {
            curve: 'straight'
        },
        title: {
            text: 'Generación Mensual de Reportes',
            align: 'left'
        },
        grid: {
            row: {
                colors: ['#f3f3f3', 'transparent'],
                opacity: 0.5
            },
        },
        xaxis: {
            categories: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
        }
    };

    const chartGeneracionMensual = new ApexCharts(document.querySelector("#chartGeneracionMensual"), optionsGeneracionMensual);
    chartGeneracionMensual.render();
}
</script>
<?= $this->endSection() ?> 