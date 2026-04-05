<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>
<div class="page-wrapper">
    <div class="page-content">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between mb-4 mt-2">
                    <div class="d-flex align-items-center">
                        <a href="<?= base_url('empleado/inasistencias') ?>" class="btn btn-outline-secondary btn-sm me-3" title="Volver al Dashboard">
                            <i class="ti ti-arrow-left"></i> Volver
                        </a>
                        <h4 class="page-title mb-0">Análisis de Inasistencias</h4>
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Generar Reporte</h4>
                        <p class="card-subtitle">Análisis detallado de tu historial de inasistencias</p>
                    </div>
                    <div class="card-body">
                        <!-- Filtros de fecha -->
                        <div class="row mb-4">
                            <div class="col-md-3">
                                <label for="fecha_inicio" class="form-label">Fecha de Inicio</label>
                                <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio">
                            </div>
                            <div class="col-md-3">
                                <label for="fecha_fin" class="form-label">Fecha de Fin</label>
                                <input type="date" class="form-control" id="fecha_fin" name="fecha_fin">
                            </div>
                            <div class="col-md-3">
                                <label for="tipo_inasistencia" class="form-label">Tipo de Inasistencia</label>
                                <select class="form-select" id="tipo_inasistencia" name="tipo_inasistencia">
                                    <option value="">Todos los tipos</option>
                                    <option value="1">Justificada</option>
                                    <option value="2">No Justificada</option>
                                    <option value="3">Pendiente de Revisión</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">&nbsp;</label>
                                <button type="button" class="btn btn-primary d-block w-100" onclick="generarReporte()">
                                    <i class="ti ti-chart-bar"></i> Generar Reporte
                                </button>
                            </div>
                        </div>

                        <!-- Estadísticas generales -->
                        <div class="row mb-4">
                            <div class="col-md-3">
                                <div class="card bg-primary text-white">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0">
                                                <i class="ti ti-calendar-off fs-1"></i>
                                            </div>
                                            <div class="flex-grow-1 ms-3">
                                                <h4 class="mb-0" id="total_inasistencias">0</h4>
                                                <p class="mb-0">Total Inasistencias</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-success text-white">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0">
                                                <i class="ti ti-check fs-1"></i>
                                            </div>
                                            <div class="flex-grow-1 ms-3">
                                                <h4 class="mb-0" id="inasistencias_justificadas">0</h4>
                                                <p class="mb-0">Justificadas</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-warning text-white">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0">
                                                <i class="ti ti-clock fs-1"></i>
                                            </div>
                                            <div class="flex-grow-1 ms-3">
                                                <h4 class="mb-0" id="inasistencias_pendientes">0</h4>
                                                <p class="mb-0">Pendientes</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-danger text-white">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0">
                                                <i class="ti ti-x fs-1"></i>
                                            </div>
                                            <div class="flex-grow-1 ms-3">
                                                <h4 class="mb-0" id="inasistencias_no_justificadas">0</h4>
                                                <p class="mb-0">No Justificadas</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Gráfico de inasistencias por mes -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title">Inasistencias por Mes</h5>
                                    </div>
                                    <div class="card-body">
                                        <div id="chartInasistenciasMensuales" style="height: 300px;"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Gráfico de tipos de inasistencia -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title">Distribución por Tipo</h5>
                                    </div>
                                    <div class="card-body">
                                        <div id="chartTiposInasistencia" style="height: 250px;"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title">Tendencia Anual</h5>
                                    </div>
                                    <div class="card-body">
                                        <div id="chartTendenciaAnual" style="height: 250px;"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tabla de inasistencias -->
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title">Detalle de Inasistencias</h5>
                                        <div class="card-actions">
                                            <button type="button" class="btn btn-outline-primary btn-sm" onclick="exportarReporte()">
                                                <i class="ti ti-download"></i> Exportar
                                            </button>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-striped" id="tablaInasistencias">
                                                <thead>
                                                    <tr>
                                                        <th>Fecha</th>
                                                        <th>Tipo</th>
                                                        <th>Motivo</th>
                                                        <th>Estado</th>
                                                        <th>Justificación</th>
                                                        <th>Acciones</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="tbodyInasistencias">
                                                    <!-- Los datos se cargarán dinámicamente -->
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para ver detalles -->
<div class="modal fade" id="modalDetallesInasistencia" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detalles de la Inasistencia</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="modalBodyDetalles">
                <!-- Contenido dinámico -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
let chartInasistenciasMensuales, chartTiposInasistencia, chartTendenciaAnual;

document.addEventListener('DOMContentLoaded', function() {
    // Inicializar gráficos
    inicializarGraficos();
    
    // Cargar reporte inicial
    generarReporte();
});

function inicializarGraficos() {
    // Gráfico de inasistencias por mes
    const optionsInasistenciasMensuales = {
        series: [{
            name: 'Inasistencias',
            data: []
        }],
        chart: {
            type: 'bar',
            height: 300,
            toolbar: {
                show: false
            }
        },
        plotOptions: {
            bar: {
                horizontal: false,
                columnWidth: '55%',
                endingShape: 'rounded'
            },
        },
        dataLabels: {
            enabled: false
        },
        stroke: {
            show: true,
            width: 2,
            colors: ['transparent']
        },
        xaxis: {
            categories: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic']
        },
        yaxis: {
            title: {
                text: 'Cantidad de Inasistencias'
            }
        },
        fill: {
            opacity: 1
        },
        tooltip: {
            y: {
                formatter: function (val) {
                    return val + " inasistencias"
                }
            }
        }
    };

    chartInasistenciasMensuales = new ApexCharts(document.querySelector("#chartInasistenciasMensuales"), optionsInasistenciasMensuales);
    chartInasistenciasMensuales.render();

    // Gráfico de tipos de inasistencia
    const optionsTiposInasistencia = {
        series: [],
        chart: {
            type: 'donut',
            height: 250
        },
        labels: ['Justificadas', 'No Justificadas', 'Pendientes'],
        colors: ['#28a745', '#dc3545', '#ffc107'],
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

    chartTiposInasistencia = new ApexCharts(document.querySelector("#chartTiposInasistencia"), optionsTiposInasistencia);
    chartTiposInasistencia.render();

    // Gráfico de tendencia anual
    const optionsTendenciaAnual = {
        series: [{
            name: 'Inasistencias',
            data: []
        }],
        chart: {
            type: 'line',
            height: 250,
            toolbar: {
                show: false
            }
        },
        stroke: {
            curve: 'smooth',
            width: 3
        },
        xaxis: {
            categories: []
        },
        yaxis: {
            title: {
                text: 'Cantidad de Inasistencias'
            }
        },
        colors: ['#007bff'],
        markers: {
            size: 5,
            colors: ['#007bff'],
            strokeColors: '#fff',
            strokeWidth: 2
        }
    };

    chartTendenciaAnual = new ApexCharts(document.querySelector("#chartTendenciaAnual"), optionsTendenciaAnual);
    chartTendenciaAnual.render();
}

function generarReporte() {
    const fechaInicio = document.getElementById('fecha_inicio').value;
    const fechaFin = document.getElementById('fecha_fin').value;
    const tipoInasistencia = document.getElementById('tipo_inasistencia').value;

    mostrarLoading();
    
    // Convertir el tipo seleccionado al formato manejado por la BD o el filtro
    let tipoParam = '';
    if (tipoInasistencia === '1') tipoParam = 'JUSTIFICADA';
    if (tipoInasistencia === '2') tipoParam = 'NO_JUSTIFICADA';
    if (tipoInasistencia === '3') tipoParam = 'PENDIENTE_JUSTIFICACION';

    const params = new URLSearchParams();
    if (fechaInicio) params.append('fecha_desde', fechaInicio);
    if (fechaFin) params.append('fecha_hasta', fechaFin);
    if (tipoParam) params.append('tipo', tipoParam);

    fetch(`<?= base_url('empleado/inasistencias/obtener-mis-inasistencias') ?>?${params.toString()}`)
    .then(r => r.json())
    .then(data => {
        if(!data.success) {
            Swal.fire({icon: 'error', title: 'Error', text: data.message || 'Error al obtener datos'});
            ocultarLoading();
            return;
        }

        const noJusti = (data.total || 0) - (data.justificadas || 0) - (data.pendientes || 0);

        // 1. Estadísticas
        document.getElementById('total_inasistencias').textContent = data.total || 0;
        document.getElementById('inasistencias_justificadas').textContent = data.justificadas || 0;
        document.getElementById('inasistencias_pendientes').textContent = data.pendientes || 0;
        document.getElementById('inasistencias_no_justificadas').textContent = noJusti;

        // 2. Gráficos
        // Gráfico Mensual (Array de 12 meses)
        let mensuales = Array(12).fill(0);
        (data.data || []).forEach(item => {
            const fecha = new Date(item.fecha_inasistencia);
            const mes = fecha.getMonth(); // 0-11
            if(!isNaN(mes)) mensuales[mes]++;
        });
        chartInasistenciasMensuales.updateSeries([{ name: 'Inasistencias', data: mensuales }]);

        // Gráfico de Tipos: [Justificadas, No Justificadas, Pendientes]
        chartTiposInasistencia.updateSeries([
            parseInt(data.justificadas || 0),
            noJusti,
            parseInt(data.pendientes || 0)
        ]);

        // Gráfico Tendencia: Usaremos los mismos meses para dar una curva
        chartTendenciaAnual.updateSeries([{ name: 'Inasistencias', data: mensuales }]);

        // 3. Tabla
        actualizarTabla(data.data || []);
        
        ocultarLoading();
    })
    .catch(error => {
        console.error('Error:', error);
        ocultarLoading();
    });
}

function actualizarEstadisticas(estadisticas) {
    document.getElementById('total_inasistencias').textContent = estadisticas.total;
    document.getElementById('inasistencias_justificadas').textContent = estadisticas.justificadas;
    document.getElementById('inasistencias_pendientes').textContent = estadisticas.pendientes;
    document.getElementById('inasistencias_no_justificadas').textContent = estadisticas.noJustificadas;
}

function actualizarGraficos(datos) {
    // Actualizar gráfico mensual
    chartInasistenciasMensuales.updateSeries([{
        name: 'Inasistencias',
        data: datos.inasistenciasMensuales
    }]);

    // Actualizar gráfico de tipos
    chartTiposInasistencia.updateSeries(datos.tiposInasistencia);

    // Actualizar gráfico de tendencia
    chartTendenciaAnual.updateSeries([{
        name: 'Inasistencias',
        data: datos.tendenciaAnual
    }]);
}

function actualizarTabla(inasistencias) {
    const tbody = document.getElementById('tbodyInasistencias');
    tbody.innerHTML = '';

    inasistencias.forEach(i => {
        const row = document.createElement('tr');
        
        const isJustificada = parseInt(i.justificada) === 1;
        
        let badgeColor = 'secondary';
        if(isJustificada) badgeColor = 'success';
        else if (i.tipo_inasistencia === 'PENDIENTE_JUSTIFICACION') badgeColor = 'warning';
        else badgeColor = 'danger';

        let justificacionTxt = i.archivo_justificacion ? 'Documento Adjunto' : 'Sin documentar';

        row.innerHTML = `
            <td>${formatearFecha(i.fecha_inasistencia)}</td>
            <td>
                <span class="badge bg-${badgeColor}">
                    ${i.tipo_inasistencia || '—'}
                </span>
            </td>
            <td>${i.motivo || '—'}</td>
            <td>
                <span class="badge bg-${badgeColor}">
                    ${isJustificada ? 'Justificada' : 'Sin Justificar / Rechazada'}
                </span>
            </td>
            <td>${justificacionTxt}</td>
            <td>
                <a href="<?= base_url('empleado/inasistencias/ver/') ?>${i.id}" class="btn btn-sm btn-outline-primary" title="Ver Detalle">
                    <i class="ti ti-eye"></i>
                </a>
            </td>
        `;
        tbody.appendChild(row);
    });
}

function getBadgeColor(tipo) {
    switch(tipo) {
        case 'Justificada': return 'success';
        case 'No Justificada': return 'danger';
        case 'Pendiente': return 'warning';
        default: return 'secondary';
    }
}

function getEstadoBadgeColor(estado) {
    switch(estado) {
        case 'Aprobada': return 'success';
        case 'Rechazada': return 'danger';
        case 'Pendiente': return 'warning';
        default: return 'secondary';
    }
}

function formatearFecha(fecha) {
    return new Date(fecha).toLocaleDateString('es-ES');
}

function verDetalles(id) {
    // Simular obtención de detalles
    const detalles = {
        id: id,
        fecha: '2025-01-15',
        tipo: 'Justificada',
        motivo: 'Consulta médica',
        estado: 'Aprobada',
        justificacion: 'Certificado médico presentado',
        observaciones: 'Documentación completa y válida',
        fechaRegistro: '2025-01-15 08:30:00',
        registradoPor: 'Admin Talento Humano'
    };

    const modalBody = document.getElementById('modalBodyDetalles');
    modalBody.innerHTML = `
        <div class="row">
            <div class="col-md-6">
                <p><strong>ID:</strong> ${detalles.id}</p>
                <p><strong>Fecha:</strong> ${formatearFecha(detalles.fecha)}</p>
                <p><strong>Tipo:</strong> 
                    <span class="badge bg-${getBadgeColor(detalles.tipo)}">${detalles.tipo}</span>
                </p>
                <p><strong>Motivo:</strong> ${detalles.motivo}</p>
            </div>
            <div class="col-md-6">
                <p><strong>Estado:</strong> 
                    <span class="badge bg-${getEstadoBadgeColor(detalles.estado)}">${detalles.estado}</span>
                </p>
                <p><strong>Justificación:</strong> ${detalles.justificacion}</p>
                <p><strong>Observaciones:</strong> ${detalles.observaciones}</p>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-md-6">
                <p><strong>Fecha de Registro:</strong> ${detalles.fechaRegistro}</p>
            </div>
            <div class="col-md-6">
                <p><strong>Registrado por:</strong> ${detalles.registradoPor}</p>
            </div>
        </div>
    `;

    const modal = new bootstrap.Modal(document.getElementById('modalDetallesInasistencia'));
    modal.show();
}

function exportarReporte() {
    // Simular exportación
    alert('Funcionalidad de exportación en desarrollo');
}

function mostrarLoading() {
    // Implementar loading si es necesario
}

function ocultarLoading() {
    // Ocultar loading si es necesario
}
</script>
<?= $this->endSection() ?>
