<?php $sidebar = 'sidebar_admin_th'; ?>
<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>

<div class="page-wrapper">
    <div class="page-content">
        <!-- Header -->
        <div class="row">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-flex align-items-center justify-content-between">
                        <h4 class="mb-0"><i class="bi bi-file-earmark-bar-graph"></i> Reportes del Sistema</h4>
                        <div class="page-title-right">
                            <span class="text-muted">Genera y visualiza reportes detallados</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Report Types Selection -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-list-check"></i> Tipos de Reportes Disponibles
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <div class="card border-primary h-100">
                                    <div class="card-body text-center">
                                        <i class="bi bi-people text-primary" style="font-size: 2.5rem;"></i>
                                        <h6 class="mt-2">Reporte de Empleados</h6>
                                        <p class="text-muted small">Estadísticas generales y detalladas de empleados</p>
                                        <button class="btn btn-primary btn-sm" onclick="generarReporteEmpleados()">
                                            <i class="bi bi-download"></i> Generar
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="card border-success h-100">
                                    <div class="card-body text-center">
                                        <i class="bi bi-mortarboard text-success" style="font-size: 2.5rem;"></i>
                                        <h6 class="mt-2">Reporte de Capacitaciones</h6>
                                        <p class="text-muted small">Análisis de capacitaciones y participación</p>
                                        <button class="btn btn-success btn-sm" onclick="generarReporteCapacitaciones()">
                                            <i class="bi bi-download"></i> Generar
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="card border-info h-100">
                                    <div class="card-body text-center">
                                        <i class="bi bi-clipboard-data text-info" style="font-size: 2.5rem;"></i>
                                        <h6 class="mt-2">Reporte de Evaluaciones</h6>
                                        <p class="text-muted small">Desempeño y resultados de evaluaciones</p>
                                        <button class="btn btn-info btn-sm" onclick="generarReporteEvaluaciones()">
                                            <i class="bi bi-download"></i> Generar
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="card border-warning h-100">
                                    <div class="card-body text-center">
                                        <i class="bi bi-calendar-check text-warning" style="font-size: 2.5rem;"></i>
                                        <h6 class="mt-2">Reporte de Inasistencias</h6>
                                        <p class="text-muted small">Control de asistencia y justificaciones</p>
                                        <button class="btn btn-warning btn-sm" onclick="generarReporteInasistencias()">
                                            <i class="bi bi-download"></i> Generar
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Custom Report Builder -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-tools"></i> Constructor de Reportes Personalizados
                        </h5>
                    </div>
                    <div class="card-body">
                        <form id="formReportePersonalizado">
                            <div class="row">
                                <div class="col-md-3">
                                    <label for="tipoReporte" class="form-label">Tipo de Reporte</label>
                                    <select class="form-select" id="tipoReporte" required>
                                        <option value="">Seleccionar tipo</option>
                                        <option value="empleados">Empleados</option>
                                        <option value="capacitaciones">Capacitaciones</option>
                                        <option value="evaluaciones">Evaluaciones</option>
                                        <option value="inasistencias">Inasistencias</option>
                                        <option value="departamentos">Por Departamentos</option>
                                        <option value="puestos">Por Puestos</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="fechaInicio" class="form-label">Fecha Inicio</label>
                                    <input type="date" class="form-control" id="fechaInicio" required>
                                </div>
                                <div class="col-md-3">
                                    <label for="fechaFin" class="form-label">Fecha Fin</label>
                                    <input type="date" class="form-control" id="fechaFin" required>
                                </div>
                                <div class="col-md-3">
                                    <label for="formatoReporte" class="form-label">Formato</label>
                                    <select class="form-select" id="formatoReporte" required>
                                        <option value="pdf">PDF</option>
                                        <option value="excel">Excel</option>
                                        <option value="csv">CSV</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <label for="filtrosAdicionales" class="form-label">Filtros Adicionales</label>
                                    <div id="filtrosAdicionales">
                                        <!-- Los filtros se cargarán dinámicamente según el tipo de reporte -->
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="columnasReporte" class="form-label">Columnas del Reporte</label>
                                    <div id="columnasReporte">
                                        <!-- Las columnas se cargarán dinámicamente según el tipo de reporte -->
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-gear"></i> Generar Reporte Personalizado
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary ms-2" onclick="limpiarFormulario()">
                                        <i class="bi bi-arrow-clockwise"></i> Limpiar
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Report History -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-clock-history"></i> Historial de Reportes Generados
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover" id="tablaHistorialReportes">
                                <thead>
                                    <tr>
                                        <th>Fecha Generación</th>
                                        <th>Tipo de Reporte</th>
                                        <th>Parámetros</th>
                                        <th>Formato</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td colspan="6" class="text-center text-muted">
                                            <i class="bi bi-hourglass-split"></i> Cargando historial...
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
                </div>
            </div>
        </div>

        <!-- Report Preview Modal -->
        <div class="modal fade" id="modalVistaPrevia" tabindex="-1" aria-labelledby="modalVistaPreviaLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalVistaPreviaLabel">
                            <i class="bi bi-eye"></i> Vista Previa del Reporte
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div id="contenidoVistaPrevia">
                            <!-- El contenido de la vista previa se cargará aquí -->
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="bi bi-x-circle"></i> Cerrar
                        </button>
                        <button type="button" class="btn btn-primary" onclick="descargarReporte()">
                            <i class="bi bi-download"></i> Descargar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    cargarHistorialReportes();
    configurarFiltrosDinamicos();
    
    // Configurar fechas por defecto (último mes)
    const hoy = new Date();
    const haceUnMes = new Date(hoy.getFullYear(), hoy.getMonth() - 1, hoy.getDate());
    
    document.getElementById('fechaInicio').value = haceUnMes.toISOString().split('T')[0];
    document.getElementById('fechaFin').value = hoy.toISOString().split('T')[0];
});

function configurarFiltrosDinamicos() {
    const tipoReporte = document.getElementById('tipoReporte');
    tipoReporte.addEventListener('change', function() {
        cargarFiltrosAdicionales(this.value);
        cargarColumnasReporte(this.value);
    });
}

function cargarFiltrosAdicionales(tipo) {
    const container = document.getElementById('filtrosAdicionales');
    let html = '';
    
    switch(tipo) {
        case 'empleados':
            html = `
                <div class="row">
                    <div class="col-md-6">
                        <label class="form-label">Departamento</label>
                        <select class="form-select" id="filtroDepartamento">
                            <option value="">Todos los departamentos</option>
                            <option value="1">Administración</option>
                            <option value="2">Docencia</option>
                            <option value="3">Tecnología</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Estado</label>
                        <select class="form-select" id="filtroEstadoEmpleado">
                            <option value="">Todos los estados</option>
                            <option value="ACTIVO">Activo</option>
                            <option value="INACTIVO">Inactivo</option>
                        </select>
                    </div>
                </div>
            `;
            break;
        case 'capacitaciones':
            html = `
                <div class="row">
                    <div class="col-md-6">
                        <label class="form-label">Tipo de Capacitación</label>
                        <select class="form-select" id="filtroTipoCapacitacion">
                            <option value="">Todos los tipos</option>
                            <option value="OBLIGATORIA">Obligatoria</option>
                            <option value="OPCIONAL">Opcional</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Estado</label>
                        <select class="form-select" id="filtroEstadoCapacitacion">
                            <option value="">Todos los estados</option>
                            <option value="ACTIVA">Activa</option>
                            <option value="COMPLETADA">Completada</option>
                            <option value="CANCELADA">Cancelada</option>
                        </select>
                    </div>
                </div>
            `;
            break;
        case 'evaluaciones':
            html = `
                <div class="row">
                    <div class="col-md-6">
                        <label class="form-label">Tipo de Evaluación</label>
                        <select class="form-select" id="filtroTipoEvaluacion">
                            <option value="">Todos los tipos</option>
                            <option value="DESEMPEÑO">Desempeño</option>
                            <option value="COMPETENCIAS">Competencias</option>
                            <option value="ENTRE_PARES">Entre Pares</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Rango de Calificación</label>
                        <select class="form-select" id="filtroRangoCalificacion">
                            <option value="">Todos los rangos</option>
                            <option value="1-3">1-3 (Bajo)</option>
                            <option value="4-6">4-6 (Medio)</option>
                            <option value="7-10">7-10 (Alto)</option>
                        </select>
                    </div>
                </div>
            `;
            break;
        case 'inasistencias':
            html = `
                <div class="row">
                    <div class="col-md-6">
                        <label class="form-label">Tipo de Inasistencia</label>
                        <select class="form-select" id="filtroTipoInasistencia">
                            <option value="">Todos los tipos</option>
                            <option value="JUSTIFICADA">Justificada</option>
                            <option value="NO_JUSTIFICADA">No Justificada</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Departamento</label>
                        <select class="form-select" id="filtroDepartamentoInasistencia">
                            <option value="">Todos los departamentos</option>
                            <option value="1">Administración</option>
                            <option value="2">Docencia</option>
                            <option value="3">Tecnología</option>
                        </select>
                    </div>
                </div>
            `;
            break;
        default:
            html = '<p class="text-muted">Selecciona un tipo de reporte para ver los filtros disponibles</p>';
    }
    
    container.innerHTML = html;
}

function cargarColumnasReporte(tipo) {
    const container = document.getElementById('columnasReporte');
    let html = '';
    
    switch(tipo) {
        case 'empleados':
            html = `
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="colId" checked>
                    <label class="form-check-label" for="colId">ID</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="colNombres" checked>
                    <label class="form-check-label" for="colNombres">Nombres</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="colApellidos" checked>
                    <label class="form-check-label" for="colApellidos">Apellidos</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="colEmail" checked>
                    <label class="form-check-label" for="colEmail">Email</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="colDepartamento" checked>
                    <label class="form-check-label" for="colDepartamento">Departamento</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="colPuesto" checked>
                    <label class="form-check-label" for="colPuesto">Puesto</label>
                </div>
            `;
            break;
        case 'capacitaciones':
            html = `
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="colTitulo" checked>
                    <label class="form-check-label" for="colTitulo">Título</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="colTipo" checked>
                    <label class="form-check-label" for="colTipo">Tipo</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="colFechaInicio" checked>
                    <label class="form-check-label" for="colFechaInicio">Fecha Inicio</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="colFechaFin" checked>
                    <label class="form-check-label" for="colFechaFin">Fecha Fin</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="colEstado" checked>
                    <label class="form-check-label" for="colEstado">Estado</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="colParticipantes" checked>
                    <label class="form-check-label" for="colParticipantes">Participantes</label>
                </div>
            `;
            break;
        default:
            html = '<p class="text-muted">Selecciona un tipo de reporte para ver las columnas disponibles</p>';
    }
    
    container.innerHTML = html;
}

function cargarHistorialReportes() {
    // Simular carga de historial
    setTimeout(() => {
        const tbody = document.querySelector('#tablaHistorialReportes tbody');
        tbody.innerHTML = `
            <tr>
                <td>2025-01-02 10:30</td>
                <td>
                    <span class="badge bg-primary">Empleados</span>
                </td>
                <td>Último mes, Departamento: Todos</td>
                <td>PDF</td>
                <td>
                    <span class="badge bg-success">Completado</span>
                </td>
                <td>
                    <button class="btn btn-sm btn-outline-primary" onclick="descargarReporteHistorial(1)">
                        <i class="bi bi-download"></i>
                    </button>
                    <button class="btn btn-sm btn-outline-info" onclick="verVistaPrevia(1)">
                        <i class="bi bi-eye"></i>
                    </button>
                </td>
            </tr>
            <tr>
                <td>2025-01-01 15:45</td>
                <td>
                    <span class="badge bg-success">Capacitaciones</span>
                </td>
                <td>Último trimestre, Estado: Activas</td>
                <td>Excel</td>
                <td>
                    <span class="badge bg-success">Completado</span>
                </td>
                <td>
                    <button class="btn btn-sm btn-outline-primary" onclick="descargarReporteHistorial(2)">
                        <i class="bi bi-download"></i>
                    </button>
                    <button class="btn btn-sm btn-outline-info" onclick="verVistaPrevia(2)">
                        <i class="bi bi-eye"></i>
                    </button>
                </td>
            </tr>
        `;
    }, 1000);
}

// Funciones para generar reportes específicos
function generarReporteEmpleados() {
    Swal.fire({
        title: 'Generando Reporte de Empleados',
        text: 'Por favor espera...',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
    
    // Simular generación de reporte
    setTimeout(() => {
        Swal.fire({
            icon: 'success',
            title: 'Reporte Generado',
            text: 'El reporte de empleados se ha generado exitosamente',
            confirmButtonText: 'Descargar',
            showCancelButton: true,
            cancelButtonText: 'Cerrar'
        }).then((result) => {
            if (result.isConfirmed) {
                descargarReporte();
            }
        });
    }, 2000);
}

function generarReporteCapacitaciones() {
    Swal.fire({
        title: 'Generando Reporte de Capacitaciones',
        text: 'Por favor espera...',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
    
    setTimeout(() => {
        Swal.fire({
            icon: 'success',
            title: 'Reporte Generado',
            text: 'El reporte de capacitaciones se ha generado exitosamente',
            confirmButtonText: 'Descargar',
            showCancelButton: true,
            cancelButtonText: 'Cerrar'
        }).then((result) => {
            if (result.isConfirmed) {
                descargarReporte();
            }
        });
    }, 2000);
}

function generarReporteEvaluaciones() {
    Swal.fire({
        title: 'Generando Reporte de Evaluaciones',
        text: 'Por favor espera...',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
    
    setTimeout(() => {
        Swal.fire({
            icon: 'success',
            title: 'Reporte Generado',
            text: 'El reporte de evaluaciones se ha generado exitosamente',
            confirmButtonText: 'Descargar',
            showCancelButton: true,
            cancelButtonText: 'Cerrar'
        }).then((result) => {
            if (result.isConfirmed) {
                descargarReporte();
            }
        });
    }, 2000);
}

function generarReporteInasistencias() {
    Swal.fire({
        title: 'Generando Reporte de Inasistencias',
        text: 'Por favor espera...',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
    
    setTimeout(() => {
        Swal.fire({
            icon: 'success',
            title: 'Reporte Generado',
            text: 'El reporte de inasistencias se ha generado exitosamente',
            confirmButtonText: 'Descargar',
            showCancelButton: true,
            cancelButtonText: 'Cerrar'
        }).then((result) => {
            if (result.isConfirmed) {
                descargarReporte();
            }
        });
    }, 2000);
}

// Manejo del formulario de reporte personalizado
document.getElementById('formReportePersonalizado').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const tipo = document.getElementById('tipoReporte').value;
    const fechaInicio = document.getElementById('fechaInicio').value;
    const fechaFin = document.getElementById('fechaFin').value;
    const formato = document.getElementById('formatoReporte').value;
    
    if (!tipo || !fechaInicio || !fechaFin || !formato) {
        Swal.fire({
            icon: 'error',
            title: 'Campos Requeridos',
            text: 'Por favor completa todos los campos obligatorios'
        });
        return;
    }
    
    generarReportePersonalizado(tipo, fechaInicio, fechaFin, formato);
});

function generarReportePersonalizado(tipo, fechaInicio, fechaFin, formato) {
    Swal.fire({
        title: 'Generando Reporte Personalizado',
        text: 'Procesando datos y generando reporte...',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
    
    // Simular generación de reporte personalizado
    setTimeout(() => {
        Swal.fire({
            icon: 'success',
            title: 'Reporte Generado',
            text: `El reporte de ${tipo} se ha generado exitosamente en formato ${formato.toUpperCase()}`,
            confirmButtonText: 'Ver Vista Previa',
            showCancelButton: true,
            cancelButtonText: 'Descargar'
        }).then((result) => {
            if (result.isConfirmed) {
                mostrarVistaPrevia(tipo, fechaInicio, fechaFin);
            } else {
                descargarReporte();
            }
        });
    }, 3000);
}

function mostrarVistaPrevia(tipo, fechaInicio, fechaFin) {
    const modal = new bootstrap.Modal(document.getElementById('modalVistaPrevia'));
    const contenido = document.getElementById('contenidoVistaPrevia');
    
    // Simular contenido de vista previa
    contenido.innerHTML = `
        <div class="text-center mb-3">
            <h6>Vista Previa del Reporte: ${tipo.toUpperCase()}</h6>
            <p class="text-muted">Período: ${fechaInicio} a ${fechaFin}</p>
        </div>
        <div class="table-responsive">
            <table class="table table-sm table-bordered">
                <thead class="table-light">
                    <tr>
                        <th>Columna 1</th>
                        <th>Columna 2</th>
                        <th>Columna 3</th>
                        <th>Columna 4</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Dato de ejemplo 1</td>
                        <td>Dato de ejemplo 2</td>
                        <td>Dato de ejemplo 3</td>
                        <td>Dato de ejemplo 4</td>
                    </tr>
                    <tr>
                        <td>Dato de ejemplo 5</td>
                        <td>Dato de ejemplo 6</td>
                        <td>Dato de ejemplo 7</td>
                        <td>Dato de ejemplo 8</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="alert alert-info">
            <i class="bi bi-info-circle"></i> Esta es una vista previa del reporte. Los datos reales se generarán al momento de la descarga.
        </div>
    `;
    
    modal.show();
}

function descargarReporte() {
    Swal.fire({
        icon: 'success',
        title: 'Descarga Iniciada',
        text: 'El reporte se está descargando...',
        timer: 2000,
        showConfirmButton: false
    });
}

function descargarReporteHistorial(id) {
    Swal.fire({
        icon: 'success',
        title: 'Descarga Iniciada',
        text: 'El reporte se está descargando...',
        timer: 2000,
        showConfirmButton: false
    });
}

function verVistaPrevia(id) {
    Swal.fire({
        icon: 'info',
        title: 'Vista Previa',
        text: 'Función de vista previa en desarrollo',
        confirmButtonText: 'Entendido'
    });
}

function limpiarFormulario() {
    document.getElementById('formReportePersonalizado').reset();
    document.getElementById('filtrosAdicionales').innerHTML = '<p class="text-muted">Selecciona un tipo de reporte para ver los filtros disponibles</p>';
    document.getElementById('columnasReporte').innerHTML = '<p class="text-muted">Selecciona un tipo de reporte para ver las columnas disponibles</p>';
    
    // Restaurar fechas por defecto
    const hoy = new Date();
    const haceUnMes = new Date(hoy.getFullYear(), hoy.getMonth() - 1, hoy.getDate());
    
    document.getElementById('fechaInicio').value = haceUnMes.toISOString().split('T')[0];
    document.getElementById('fechaFin').value = hoy.toISOString().split('T')[0];
}
</script>

<?= $this->endSection() ?>
