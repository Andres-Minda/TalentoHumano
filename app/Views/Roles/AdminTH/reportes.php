<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>
<div class="page-wrapper">
    <div class="page-content">
        <!-- Breadcrumb -->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Reportes</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="<?= site_url('admin-th/dashboard') ?>">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Reportes</li>
                    </ol>
                </nav>
            </div>
        </div>

        <!-- Page Header -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Centro de Reportes</h4>
                    </div>
                    <div class="card-body">
                        <!-- Filtros de Reportes -->
                        <div class="row mb-4">
                            <div class="col-md-3">
                                <label class="form-label">Tipo de Reporte</label>
                                <select class="form-select" id="tipoReporte">
                                    <option value="">Seleccionar tipo...</option>
                                    <option value="empleados">Empleados</option>
                                    <option value="capacitaciones">Capacitaciones</option>
                                    <option value="evaluaciones">Evaluaciones</option>
                                    <option value="inasistencias">Inasistencias</option>
                                    <option value="solicitudes">Solicitudes</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Fecha Desde</label>
                                <input type="date" class="form-control" id="fechaDesde">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Fecha Hasta</label>
                                <input type="date" class="form-control" id="fechaHasta">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Formato</label>
                                <select class="form-select" id="formatoReporte">
                                    <option value="pdf">PDF</option>
                                    <option value="excel">Excel</option>
                                    <option value="csv">CSV</option>
                                </select>
                            </div>
                        </div>

                        <!-- Botones de Acción -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <button type="button" class="btn btn-primary" onclick="generarReporte()">
                                    <i class="ti ti-file-report"></i> Generar Reporte
                                </button>
                                <button type="button" class="btn btn-secondary" onclick="previsualizarReporte()">
                                    <i class="ti ti-eye"></i> Previsualizar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Reportes Predefinidos -->
        <div class="row">
            <div class="col-md-6 col-xl-3">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="card-title text-white">Reporte de Empleados</h6>
                                <p class="card-text">Listado completo de empleados activos</p>
                            </div>
                            <div class="align-self-center">
                                <i class="ti ti-users fs-3"></i>
                            </div>
                        </div>
                        <button class="btn btn-light btn-sm mt-2" onclick="generarReportePredefinido('empleados')">
                            Generar
                        </button>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-xl-3">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="card-title text-white">Reporte de Capacitaciones</h6>
                                <p class="card-text">Capacitaciones del mes actual</p>
                            </div>
                            <div class="align-self-center">
                                <i class="ti ti-school fs-3"></i>
                            </div>
                        </div>
                        <button class="btn btn-light btn-sm mt-2" onclick="generarReportePredefinido('capacitaciones')">
                            Generar
                        </button>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-xl-3">
                <div class="card bg-warning text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="card-title text-white">Reporte de Inasistencias</h6>
                                <p class="card-text">Inasistencias del mes</p>
                            </div>
                            <div class="align-self-center">
                                <i class="ti ti-calendar-x fs-3"></i>
                            </div>
                        </div>
                        <button class="btn btn-light btn-sm mt-2" onclick="generarReportePredefinido('inasistencias')">
                            Generar
                        </button>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-xl-3">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="card-title text-white">Reporte de Evaluaciones</h6>
                                <p class="card-text">Evaluaciones pendientes</p>
                            </div>
                            <div class="align-self-center">
                                <i class="ti ti-clipboard-check fs-3"></i>
                            </div>
                        </div>
                        <button class="btn btn-light btn-sm mt-2" onclick="generarReportePredefinido('evaluaciones')">
                            Generar
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Historial de Reportes -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Historial de Reportes Generados</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped" id="tablaHistorial">
                                <thead>
                                    <tr>
                                        <th>Fecha</th>
                                        <th>Tipo</th>
                                        <th>Formato</th>
                                        <th>Generado por</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody id="tbodyHistorial">
                                    <!-- Datos simulados -->
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
    cargarHistorialReportes();
    
    // Establecer fecha actual por defecto
    const hoy = new Date().toISOString().split('T')[0];
    document.getElementById('fechaHasta').value = hoy;
    
    // Fecha desde hace 30 días
    const hace30Dias = new Date();
    hace30Dias.setDate(hace30Dias.getDate() - 30);
    document.getElementById('fechaDesde').value = hace30Dias.toISOString().split('T')[0];
});

function cargarHistorialReportes() {
    // Datos simulados del historial
    const historial = [
        { fecha: '2025-01-20', tipo: 'Empleados', formato: 'PDF', generadoPor: 'Admin TH', estado: 'Completado' },
        { fecha: '2025-01-19', tipo: 'Capacitaciones', formato: 'Excel', generadoPor: 'Admin TH', estado: 'Completado' },
        { fecha: '2025-01-18', tipo: 'Inasistencias', formato: 'CSV', generadoPor: 'Admin TH', estado: 'Completado' }
    ];

    const tbody = document.getElementById('tbodyHistorial');
    tbody.innerHTML = '';

    historial.forEach(reporte => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${formatearFecha(reporte.fecha)}</td>
            <td>${reporte.tipo}</td>
            <td><span class="badge bg-secondary">${reporte.formato}</span></td>
            <td>${reporte.generadoPor}</td>
            <td><span class="badge bg-success">${reporte.estado}</span></td>
            <td>
                <button class="btn btn-sm btn-outline-primary" onclick="descargarReporte('${reporte.fecha}', '${reporte.tipo}')">
                    <i class="ti ti-download"></i>
                </button>
                <button class="btn btn-sm btn-outline-secondary" onclick="verReporte('${reporte.fecha}', '${reporte.tipo}')">
                    <i class="ti ti-eye"></i>
                </button>
            </td>
        `;
        tbody.appendChild(row);
    });
}

function formatearFecha(fecha) {
    return new Date(fecha).toLocaleDateString('es-ES');
}

function generarReporte() {
    const tipoReporte = document.getElementById('tipoReporte').value;
    const fechaDesde = document.getElementById('fechaDesde').value;
    const fechaHasta = document.getElementById('fechaHasta').value;
    const formato = document.getElementById('formatoReporte').value;

    if (!tipoReporte) {
        alert('Seleccione un tipo de reporte');
        return;
    }

    if (!fechaDesde || !fechaHasta) {
        alert('Seleccione el rango de fechas');
        return;
    }

    // Simular generación de reporte
    alert(`Generando reporte de ${tipoReporte} desde ${fechaDesde} hasta ${fechaHasta} en formato ${formato.toUpperCase()}`);
    
    // Aquí implementarías la lógica real para generar el reporte
    console.log('Generando reporte:', { tipoReporte, fechaDesde, fechaHasta, formato });
}

function previsualizarReporte() {
    const tipoReporte = document.getElementById('tipoReporte').value;
    
    if (!tipoReporte) {
        alert('Seleccione un tipo de reporte');
        return;
    }

    // Aquí implementarías la lógica para previsualizar
    alert('Funcionalidad de previsualización en desarrollo');
}

function generarReportePredefinido(tipo) {
    // Simular generación de reporte predefinido
    alert(`Generando reporte predefinido de ${tipo}`);
    
    // Aquí implementarías la lógica real
    console.log('Generando reporte predefinido:', tipo);
}

function descargarReporte(fecha, tipo) {
    // Simular descarga de reporte
    alert(`Descargando reporte de ${tipo} del ${fecha}`);
}

function verReporte(fecha, tipo) {
    // Simular visualización de reporte
    alert(`Abriendo reporte de ${tipo} del ${fecha}`);
}
</script>
<?= $this->endSection() ?>
