<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>

<div class="page-wrapper">
    <div class="page-content">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <h4 class="page-title mb-0">Reporte de Inasistencias</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="<?= base_url('empleado/dashboard') ?>">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="<?= base_url('empleado/inasistencias') ?>">Inasistencias</a></li>
                            <li class="breadcrumb-item active">Reporte</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filtros de Período -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row align-items-end">
                            <div class="col-md-3">
                                <label for="periodo" class="form-label">Período</label>
                                <select class="form-select" id="periodo" name="periodo">
                                    <option value="mes_actual">Mes Actual</option>
                                    <option value="mes_anterior">Mes Anterior</option>
                                    <option value="trimestre_actual">Trimestre Actual</option>
                                    <option value="semestre_actual">Semestre Actual</option>
                                    <option value="anio_actual" selected>Año Actual</option>
                                    <option value="anio_anterior">Año Anterior</option>
                                    <option value="personalizado">Personalizado</option>
                                </select>
                            </div>
                            <div class="col-md-3" id="fechasPersonalizadas" style="display: none;">
                                <label for="fecha_desde" class="form-label">Fecha Desde</label>
                                <input type="date" class="form-control" id="fecha_desde" name="fecha_desde">
                            </div>
                            <div class="col-md-3" id="fechasPersonalizadas2" style="display: none;">
                                <label for="fecha_hasta" class="form-label">Fecha Hasta</label>
                                <input type="date" class="form-control" id="fecha_hasta" name="fecha_hasta">
                            </div>
                            <div class="col-md-3">
                                <button type="button" class="btn btn-primary w-100" id="btnGenerarReporte">
                                    <i class="bi bi-graph-up me-2"></i>Generar Reporte
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Resumen Ejecutivo -->
        <div class="row" id="resumenEjecutivo" style="display: none;">
            <div class="col-xl-3 col-md-6">
                <div class="card mini-stats-wid">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-grow-1">
                                <p class="text-muted fw-medium">Total Inasistencias</p>
                                <h4 class="mb-0" id="totalInasistencias">0</h4>
                            </div>
                            <div class="flex-shrink-0 align-self-center">
                                <div class="mini-stat-icon avatar-sm rounded-circle bg-primary">
                                    <span class="avatar-title">
                                        <i class="bi bi-calendar-x font-size-24"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card mini-stats-wid">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-grow-1">
                                <p class="text-muted fw-medium">Justificadas</p>
                                <h4 class="mb-0 text-success" id="totalJustificadas">0</h4>
                            </div>
                            <div class="flex-shrink-0 align-self-center">
                                <div class="mini-stat-icon avatar-sm rounded-circle bg-success">
                                    <span class="avatar-title">
                                        <i class="bi bi-check-circle font-size-24"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card mini-stats-wid">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-grow-1">
                                <p class="text-muted fw-medium">Sin Justificar</p>
                                <h4 class="mb-0 text-warning" id="totalSinJustificar">0</h4>
                            </div>
                            <div class="flex-shrink-0 align-self-center">
                                <div class="mini-stat-icon avatar-sm rounded-circle bg-warning">
                                    <span class="avatar-title">
                                        <i class="bi bi-exclamation-triangle font-size-24"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card mini-stats-wid">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-grow-1">
                                <p class="text-muted fw-medium">Porcentaje Justificadas</p>
                                <h4 class="mb-0 text-info" id="porcentajeJustificadas">0%</h4>
                            </div>
                            <div class="flex-shrink-0 align-self-center">
                                <div class="mini-stat-icon avatar-sm rounded-circle bg-info">
                                    <span class="avatar-title">
                                        <i class="bi bi-percent font-size-24"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Gráficos -->
        <div class="row" id="graficos" style="display: none;">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-pie-chart text-primary me-2"></i>
                            Distribución por Estado
                        </h5>
                    </div>
                    <div class="card-body">
                        <canvas id="graficoEstado" width="400" height="200"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-bar-chart text-success me-2"></i>
                            Inasistencias por Mes
                        </h5>
                    </div>
                    <div class="card-body">
                        <canvas id="graficoMensual" width="400" height="200"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Análisis por Tipo -->
        <div class="row" id="analisisTipo" style="display: none;">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-list-ul text-info me-2"></i>
                            Análisis por Tipo de Inasistencia
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-centered table-nowrap mb-0">
                                <thead>
                                    <tr>
                                        <th>Tipo de Inasistencia</th>
                                        <th>Total</th>
                                        <th>Justificadas</th>
                                        <th>Sin Justificar</th>
                                        <th>Porcentaje Justificadas</th>
                                        <th>Tendencia</th>
                                    </tr>
                                </thead>
                                <tbody id="tablaAnalisisTipo">
                                    <!-- Se llena dinámicamente -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Comparación con Políticas -->
        <div class="row" id="comparacionPoliticas" style="display: none;">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-shield-check text-warning me-2"></i>
                            Comparación con Políticas de Inasistencia
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="alert alert-info">
                                    <h6 class="alert-heading">
                                        <i class="bi bi-info-circle me-2"></i>
                                        Límites Establecidos
                                    </h6>
                                    <div id="limitesPoliticas">
                                        <!-- Se llena dinámicamente -->
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="alert" id="estadoPoliticas">
                                    <!-- Se llena dinámicamente -->
                                </div>
                            </div>
                        </div>
                        
                        <div class="row mt-3">
                            <div class="col-12">
                                <div class="progress-group">
                                    <div class="d-flex justify-content-between mb-1">
                                        <span>Progreso del Año</span>
                                        <span id="progresoAnio">0%</span>
                                    </div>
                                    <div class="progress">
                                        <div class="progress-bar" id="barraProgreso" role="progressbar" 
                                             style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <small class="text-muted">
                                        <span id="textoProgreso">0 de 0 días utilizados</span>
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recomendaciones -->
        <div class="row" id="recomendaciones" style="display: none;">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-lightbulb text-warning me-2"></i>
                            Recomendaciones y Alertas
                        </h5>
                    </div>
                    <div class="card-body">
                        <div id="listaRecomendaciones">
                            <!-- Se llena dinámicamente -->
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Acciones -->
        <div class="row" id="acciones" style="display: none;">
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center">
                        <h6 class="card-title mb-3">Acciones Disponibles</h6>
                        <div class="d-flex justify-content-center gap-2 flex-wrap">
                            <button type="button" class="btn btn-primary" onclick="exportarPDF()">
                                <i class="bi bi-file-pdf me-2"></i>Exportar PDF
                            </button>
                            <button type="button" class="btn btn-success" onclick="exportarExcel()">
                                <i class="bi bi-file-excel me-2"></i>Exportar Excel
                            </button>
                            <button type="button" class="btn btn-info" onclick="imprimirReporte()">
                                <i class="bi bi-printer me-2"></i>Imprimir
                            </button>
                            <a href="<?= base_url('empleado/inasistencias/mis-inasistencias') ?>" 
                               class="btn btn-outline-secondary">
                                <i class="bi bi-list me-2"></i>Ver Lista Completa
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Carga -->
<div class="modal fade" id="modalCarga" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center">
                <div class="spinner-border text-primary mb-3" role="status">
                    <span class="visually-hidden">Cargando...</span>
                </div>
                <h6>Generando Reporte</h6>
                <p class="text-muted">Por favor espera mientras se procesa la información...</p>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
let chartEstado, chartMensual;

document.addEventListener('DOMContentLoaded', function() {
    // Cambio de período
    document.getElementById('periodo').addEventListener('change', function() {
        const valor = this.value;
        const fechasPersonalizadas = document.getElementById('fechasPersonalizadas');
        const fechasPersonalizadas2 = document.getElementById('fechasPersonalizadas2');
        
        if (valor === 'personalizado') {
            fechasPersonalizadas.style.display = 'block';
            fechasPersonalizadas2.style.display = 'block';
        } else {
            fechasPersonalizadas.style.display = 'none';
            fechasPersonalizadas2.style.display = 'none';
        }
    });

    // Generar reporte
    document.getElementById('btnGenerarReporte').addEventListener('click', function() {
        generarReporte();
    });

    // Generar reporte inicial
    generarReporte();
});

function generarReporte() {
    const periodo = document.getElementById('periodo').value;
    const fechaDesde = document.getElementById('fecha_desde').value;
    const fechaHasta = document.getElementById('fecha_hasta').value;
    
    // Mostrar modal de carga
    const modalCarga = new bootstrap.Modal(document.getElementById('modalCarga'));
    modalCarga.show();
    
    // Preparar parámetros
    const params = new URLSearchParams();
    params.append('periodo', periodo);
    if (periodo === 'personalizado') {
        if (fechaDesde) params.append('fecha_desde', fechaDesde);
        if (fechaHasta) params.append('fecha_hasta', fechaHasta);
    }
    
    // Llamada AJAX
    fetch('<?= base_url('empleado/inasistencias/reporte') ?>?' + params.toString())
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                mostrarReporte(data.data);
            } else {
                alert('Error al generar el reporte: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al generar el reporte');
        })
        .finally(() => {
            modalCarga.hide();
        });
}

function mostrarReporte(data) {
    // Mostrar secciones
    document.getElementById('resumenEjecutivo').style.display = 'block';
    document.getElementById('graficos').style.display = 'block';
    document.getElementById('analisisTipo').style.display = 'block';
    document.getElementById('comparacionPoliticas').style.display = 'block';
    document.getElementById('recomendaciones').style.display = 'block';
    document.getElementById('acciones').style.display = 'block';
    
    // Actualizar resumen ejecutivo
    document.getElementById('totalInasistencias').textContent = data.resumen.total || 0;
    document.getElementById('totalJustificadas').textContent = data.resumen.justificadas || 0;
    document.getElementById('totalSinJustificar').textContent = data.resumen.sin_justificar || 0;
    
    const porcentaje = data.resumen.total > 0 ? 
        Math.round((data.resumen.justificadas / data.resumen.total) * 100) : 0;
    document.getElementById('porcentajeJustificadas').textContent = porcentaje + '%';
    
    // Crear gráficos
    crearGraficoEstado(data.graficos.estado);
    crearGraficoMensual(data.graficos.mensual);
    
    // Actualizar análisis por tipo
    actualizarAnalisisTipo(data.analisis_tipo);
    
    // Actualizar comparación con políticas
    actualizarComparacionPoliticas(data.politicas);
    
    // Actualizar recomendaciones
    actualizarRecomendaciones(data.recomendaciones);
}

function crearGraficoEstado(data) {
    const ctx = document.getElementById('graficoEstado').getContext('2d');
    
    if (chartEstado) {
        chartEstado.destroy();
    }
    
    chartEstado = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: data.labels,
            datasets: [{
                data: data.valores,
                backgroundColor: ['#28a745', '#ffc107', '#dc3545', '#6c757d'],
                borderWidth: 2,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
}

function crearGraficoMensual(data) {
    const ctx = document.getElementById('graficoMensual').getContext('2d');
    
    if (chartMensual) {
        chartMensual.destroy();
    }
    
    chartMensual = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: data.labels,
            datasets: [{
                label: 'Inasistencias',
                data: data.valores,
                backgroundColor: '#007bff',
                borderColor: '#0056b3',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });
}

function actualizarAnalisisTipo(data) {
    const tbody = document.getElementById('tablaAnalisisTipo');
    tbody.innerHTML = '';
    
    data.forEach(item => {
        const row = document.createElement('tr');
        const porcentaje = item.total > 0 ? Math.round((item.justificadas / item.total) * 100) : 0;
        
        row.innerHTML = `
            <td><span class="badge bg-info">${item.tipo}</span></td>
            <td><strong>${item.total}</strong></td>
            <td><span class="text-success">${item.justificadas}</span></td>
            <td><span class="text-warning">${item.sin_justificar}</span></td>
            <td><span class="badge bg-${porcentaje >= 80 ? 'success' : porcentaje >= 60 ? 'warning' : 'danger'}">${porcentaje}%</span></td>
            <td>
                ${item.tendencia === 'mejora' ? '<i class="bi bi-arrow-up text-success"></i>' : 
                  item.tendencia === 'empeora' ? '<i class="bi bi-arrow-down text-danger"></i>' : 
                  '<i class="bi bi-dash text-secondary"></i>'}
            </td>
        `;
        tbody.appendChild(row);
    });
}

function actualizarComparacionPoliticas(data) {
    const limitesDiv = document.getElementById('limitesPoliticas');
    const estadoDiv = document.getElementById('estadoPoliticas');
    
    // Límites
    limitesDiv.innerHTML = `
        <p><strong>Límite Anual:</strong> ${data.limite_anual || 'No definido'} días</p>
        <p><strong>Límite Consecutivas:</strong> ${data.limite_consecutivas || 'No definido'} días</p>
        <p><strong>Días Restantes:</strong> ${data.dias_restantes || 0} días</p>
    `;
    
    // Estado
    let estadoClase = 'alert-info';
    let estadoTexto = 'Dentro de los límites';
    let estadoIcono = 'bi-check-circle';
    
    if (data.porcentaje_utilizado >= 90) {
        estadoClase = 'alert-danger';
        estadoTexto = 'Límite crítico alcanzado';
        estadoIcono = 'bi-exclamation-triangle';
    } else if (data.porcentaje_utilizado >= 75) {
        estadoClase = 'alert-warning';
        estadoTexto = 'Aproximándose al límite';
        estadoIcono = 'bi-exclamation-circle';
    }
    
    estadoDiv.className = `alert ${estadoClase}`;
    estadoDiv.innerHTML = `
        <h6 class="alert-heading">
            <i class="bi ${estadoIcono} me-2"></i>
            ${estadoTexto}
        </h6>
        <p class="mb-0">Has utilizado el ${data.porcentaje_utilizado || 0}% de tu límite anual.</p>
    `;
    
    // Progreso
    document.getElementById('progresoAnio').textContent = `${data.porcentaje_utilizado || 0}%`;
    document.getElementById('barraProgreso').style.width = `${data.porcentaje_utilizado || 0}%`;
    document.getElementById('barraProgreso').setAttribute('aria-valuenow', data.porcentaje_utilizado || 0);
    document.getElementById('textoProgreso').textContent = `${data.dias_utilizados || 0} de ${data.limite_anual || 0} días utilizados`;
    
    // Color de la barra
    const barra = document.getElementById('barraProgreso');
    if (data.porcentaje_utilizado >= 90) {
        barra.className = 'progress-bar bg-danger';
    } else if (data.porcentaje_utilizado >= 75) {
        barra.className = 'progress-bar bg-warning';
    } else {
        barra.className = 'progress-bar bg-success';
    }
}

function actualizarRecomendaciones(data) {
    const listaDiv = document.getElementById('listaRecomendaciones');
    listaDiv.innerHTML = '';
    
    if (data.length === 0) {
        listaDiv.innerHTML = `
            <div class="alert alert-success">
                <i class="bi bi-check-circle me-2"></i>
                <strong>¡Excelente!</strong> Tu registro de inasistencias está dentro de los parámetros esperados.
            </div>
        `;
        return;
    }
    
    data.forEach(recomendacion => {
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${recomendacion.tipo}`;
        alertDiv.innerHTML = `
            <i class="bi bi-${recomendacion.icono} me-2"></i>
            <strong>${recomendacion.titulo}</strong><br>
            ${recomendacion.descripcion}
        `;
        listaDiv.appendChild(alertDiv);
    });
}

function exportarPDF() {
    // Implementar exportación a PDF
    alert('Función de exportación a PDF en desarrollo');
}

function exportarExcel() {
    // Implementar exportación a Excel
    alert('Función de exportación a Excel en desarrollo');
}

function imprimirReporte() {
    window.print();
}
</script>

<style>
@media print {
    .btn, .modal, .breadcrumb, #filtros {
        display: none !important;
    }
    
    .card {
        border: 1px solid #000 !important;
        box-shadow: none !important;
        break-inside: avoid;
    }
    
    .progress-group {
        break-inside: avoid;
    }
}

.progress-group {
    margin-bottom: 1rem;
}

.progress {
    height: 0.5rem;
}

.alert {
    border-radius: 0.5rem;
}

.table th {
    background-color: #f8f9fa;
    border-top: none;
}

.badge {
    font-size: 0.875em;
}
</style>

<?= $this->endSection() ?>
