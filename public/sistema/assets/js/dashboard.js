/*
 * Sistema de Talento Humano - Dashboard JavaScript
 * Basado en el sistema ITSI
 */

(function($) {
    'use strict';

    // ==============================================================
    // Inicialización del Dashboard
    // ==============================================================
    $(document).ready(function() {
        initializeDashboard();
        setupDashboardEvents();
        loadDashboardData();
    });

    // ==============================================================
    // Función de inicialización del dashboard
    // ==============================================================
    function initializeDashboard() {
        // Inicializar gráficos si están disponibles
        if (typeof ApexCharts !== 'undefined') {
            initializeCharts();
        }
        
        // Inicializar contadores animados
        initializeCounters();
        
        // Configurar actualizaciones automáticas
        setupAutoRefresh();
        
        // Configurar filtros de fecha
        setupDateFilters();
    }

    // ==============================================================
    // Configuración de eventos del dashboard
    // ==============================================================
    function setupDashboardEvents() {
        // Botón de actualizar dashboard
        $(document).on('click', '#btnRefreshDashboard', function() {
            loadDashboardData();
        });

        // Filtros de fecha
        $(document).on('change', '#dateRange, #startDate, #endDate', function() {
            loadDashboardData();
        });

        // Filtros de departamento
        $(document).on('change', '#departmentFilter', function() {
            loadDashboardData();
        });

        // Filtros de empleado
        $(document).on('change', '#employeeFilter', function() {
            loadDashboardData();
        });

        // Exportar datos
        $(document).on('click', '#btnExportDashboard', function() {
            exportDashboardData();
        });

        // Imprimir dashboard
        $(document).on('click', '#btnPrintDashboard', function() {
            printDashboard();
        });
    }

    // ==============================================================
    // Carga de datos del dashboard
    // ==============================================================
    function loadDashboardData() {
        showLoading('Cargando datos del dashboard...');
        
        // Obtener filtros actuales
        var filters = getDashboardFilters();
        
        // Cargar estadísticas generales
        loadGeneralStats(filters);
        
        // Cargar estadísticas por departamento
        loadDepartmentStats(filters);
        
        // Cargar estadísticas por empleado
        loadEmployeeStats(filters);
        
        // Cargar gráficos
        loadCharts(filters);
        
        // Cargar actividad reciente
        loadRecentActivity(filters);
        
        // Ocultar loading
        setTimeout(function() {
            hideLoading();
        }, 1000);
    }

    // ==============================================================
    // Obtención de filtros del dashboard
    // ==============================================================
    function getDashboardFilters() {
        return {
            dateRange: $('#dateRange').val(),
            startDate: $('#startDate').val(),
            endDate: $('#endDate').val(),
            department: $('#departmentFilter').val(),
            employee: $('#employeeFilter').val(),
            period: $('#periodFilter').val()
        };
    }

    // ==============================================================
    // Carga de estadísticas generales
    // ==============================================================
    function loadGeneralStats(filters) {
        $.ajax({
            url: baseUrl + 'dashboard/getEstadisticas',
            type: 'POST',
            data: filters,
            success: function(response) {
                if (response.success) {
                    updateGeneralStats(response.data);
                }
            },
            error: function() {
                console.error('Error al cargar estadísticas generales');
            }
        });
    }

    // ==============================================================
    // Actualización de estadísticas generales
    // ==============================================================
    function updateGeneralStats(data) {
        // Actualizar contadores principales
        updateCounter('#totalEmpleados', data.total_empleados);
        updateCounter('#empleadosActivos', data.empleados_activos);
        updateCounter('#empleadosInactivos', data.empleados_inactivos);
        updateCounter('#totalDepartamentos', data.total_departamentos);
        
        // Actualizar porcentajes
        updatePercentage('#porcentajeActivos', data.porcentaje_activos);
        updatePercentage('#porcentajeInactivos', data.porcentaje_inactivos);
        
        // Actualizar indicadores de estado
        updateStatusIndicators(data.estados);
    }

    // ==============================================================
    // Carga de estadísticas por departamento
    // ==============================================================
    function loadDepartmentStats(filters) {
        $.ajax({
            url: baseUrl + 'dashboard/getEstadisticasDepartamentos',
            type: 'POST',
            data: filters,
            success: function(response) {
                if (response.success) {
                    updateDepartmentStats(response.data);
                }
            },
            error: function() {
                console.error('Error al cargar estadísticas por departamento');
            }
        });
    }

    // ==============================================================
    // Actualización de estadísticas por departamento
    // ==============================================================
    function updateDepartmentStats(data) {
        var $container = $('#departmentStats');
        $container.empty();
        
        data.forEach(function(dept) {
            var deptHtml = `
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-primary shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                        ${dept.nombre}
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        ${dept.total_empleados} empleados
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="bi bi-building fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            $container.append(deptHtml);
        });
    }

    // ==============================================================
    // Carga de estadísticas por empleado
    // ==============================================================
    function loadEmployeeStats(filters) {
        $.ajax({
            url: baseUrl + 'dashboard/getEstadisticasEmpleados',
            type: 'POST',
            data: filters,
            success: function(response) {
                if (response.success) {
                    updateEmployeeStats(response.data);
                }
            },
            error: function() {
                console.error('Error al cargar estadísticas por empleado');
            }
        });
    }

    // ==============================================================
    // Actualización de estadísticas por empleado
    // ==============================================================
    function updateEmployeeStats(data) {
        var $container = $('#employeeStats');
        $container.empty();
        
        // Top empleados por rendimiento
        if (data.top_rendimiento) {
            var topHtml = `
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="m-0 font-weight-bold text-primary">Top Empleados por Rendimiento</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Empleado</th>
                                        <th>Departamento</th>
                                        <th>Puntuación</th>
                                    </tr>
                                </thead>
                                <tbody>
            `;
            
            data.top_rendimiento.forEach(function(emp) {
                topHtml += `
                    <tr>
                        <td>${emp.nombre} ${emp.apellido}</td>
                        <td>${emp.departamento}</td>
                        <td><span class="badge bg-success">${emp.puntuacion}</span></td>
                    </tr>
                `;
            });
            
            topHtml += `
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            `;
            
            $container.append(topHtml);
        }
    }

    // ==============================================================
    // Carga de gráficos
    // ==============================================================
    function loadCharts(filters) {
        // Gráfico de empleados por departamento
        loadDepartmentChart(filters);
        
        // Gráfico de tendencias de empleo
        loadEmploymentTrendsChart(filters);
        
        // Gráfico de distribución por edad
        loadAgeDistributionChart(filters);
        
        // Gráfico de distribución por género
        loadGenderDistributionChart(filters);
    }

    // ==============================================================
    // Gráfico de empleados por departamento
    // ==============================================================
    function loadDepartmentChart(filters) {
        $.ajax({
            url: baseUrl + 'dashboard/getChartDepartamentos',
            type: 'POST',
            data: filters,
            success: function(response) {
                if (response.success && typeof ApexCharts !== 'undefined') {
                    createDepartmentChart(response.data);
                }
            }
        });
    }

    // ==============================================================
    // Creación del gráfico de departamentos
    // ==============================================================
    function createDepartmentChart(data) {
        var options = {
            series: data.series,
            chart: {
                type: 'donut',
                height: 300
            },
            labels: data.labels,
            colors: ['#00367c', '#28a745', '#ffc107', '#dc3545', '#17a2b8', '#6c757d'],
            legend: {
                position: 'bottom'
            },
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
        
        var chart = new ApexCharts(document.querySelector("#departmentChart"), options);
        chart.render();
    }

    // ==============================================================
    // Gráfico de tendencias de empleo
    // ==============================================================
    function loadEmploymentTrendsChart(filters) {
        $.ajax({
            url: baseUrl + 'dashboard/getChartTendencias',
            type: 'POST',
            data: filters,
            success: function(response) {
                if (response.success && typeof ApexCharts !== 'undefined') {
                    createTrendsChart(response.data);
                }
            }
        });
    }

    // ==============================================================
    // Creación del gráfico de tendencias
    // ==============================================================
    function createTrendsChart(data) {
        var options = {
            series: [{
                name: 'Empleados Activos',
                data: data.activos
            }, {
                name: 'Empleados Inactivos',
                data: data.inactivos
            }],
            chart: {
                type: 'area',
                height: 300,
                toolbar: {
                    show: false
                }
            },
            colors: ['#28a745', '#dc3545'],
            dataLabels: {
                enabled: false
            },
            stroke: {
                curve: 'smooth',
                width: 2
            },
            xaxis: {
                categories: data.categories
            },
            yaxis: {
                title: {
                    text: 'Número de Empleados'
                }
            },
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.7,
                    opacityTo: 0.9,
                    stops: [0, 90, 100]
                }
            }
        };
        
        var chart = new ApexCharts(document.querySelector("#trendsChart"), options);
        chart.render();
    }

    // ==============================================================
    // Carga de actividad reciente
    // ==============================================================
    function loadRecentActivity(filters) {
        $.ajax({
            url: baseUrl + 'dashboard/getActividadReciente',
            type: 'POST',
            data: filters,
            success: function(response) {
                if (response.success) {
                    updateRecentActivity(response.data);
                }
            },
            error: function() {
                console.error('Error al cargar actividad reciente');
            }
        });
    }

    // ==============================================================
    // Actualización de actividad reciente
    // ==============================================================
    function updateRecentActivity(data) {
        var $container = $('#recentActivity');
        $container.empty();
        
        data.forEach(function(activity) {
            var activityHtml = `
                <div class="activity-item d-flex align-items-center mb-3">
                    <div class="activity-icon me-3">
                        <i class="bi bi-${activity.icon} text-${activity.color}"></i>
                    </div>
                    <div class="activity-content flex-grow-1">
                        <div class="activity-text">${activity.text}</div>
                        <div class="activity-time text-muted small">${formatTimeAgo(activity.timestamp)}</div>
                    </div>
                </div>
            `;
            $container.append(activityHtml);
        });
    }

    // ==============================================================
    // Funciones de utilidad
    // ==============================================================
    
    // Actualizar contador con animación
    function updateCounter(selector, value) {
        var $element = $(selector);
        var currentValue = parseInt($element.text()) || 0;
        
        $({ Counter: currentValue }).animate({
            Counter: value
        }, {
            duration: 1000,
            easing: 'swing',
            step: function() {
                $element.text(Math.ceil(this.Counter));
            }
        });
    }

    // Actualizar porcentaje
    function updatePercentage(selector, value) {
        $(selector).text(value + '%');
    }

    // Actualizar indicadores de estado
    function updateStatusIndicators(estados) {
        // Implementar según necesidad
    }

    // Formatear tiempo transcurrido
    function formatTimeAgo(timestamp) {
        var now = new Date();
        var time = new Date(timestamp);
        var diff = Math.floor((now - time) / 1000);
        
        if (diff < 60) return 'Hace un momento';
        if (diff < 3600) return 'Hace ' + Math.floor(diff / 60) + ' minutos';
        if (diff < 86400) return 'Hace ' + Math.floor(diff / 3600) + ' horas';
        if (diff < 2592000) return 'Hace ' + Math.floor(diff / 86400) + ' días';
        
        return time.toLocaleDateString('es-ES');
    }

    // ==============================================================
    // Configuración de actualizaciones automáticas
    // ==============================================================
    function setupAutoRefresh() {
        // Actualizar cada 5 minutos
        setInterval(function() {
            loadDashboardData();
        }, 5 * 60 * 1000);
    }

    // ==============================================================
    // Configuración de filtros de fecha
    // ==============================================================
    function setupDateFilters() {
        // Configurar selector de rango de fechas
        if (typeof flatpickr !== 'undefined') {
            flatpickr("#startDate, #endDate", {
                dateFormat: "Y-m-d",
                locale: "es"
            });
        }
        
        // Configurar selector de período
        $('#periodFilter').on('change', function() {
            var period = $(this).val();
            var today = new Date();
            var startDate, endDate;
            
            switch(period) {
                case 'today':
                    startDate = endDate = today;
                    break;
                case 'week':
                    startDate = new Date(today.getTime() - 7 * 24 * 60 * 60 * 1000);
                    endDate = today;
                    break;
                case 'month':
                    startDate = new Date(today.getFullYear(), today.getMonth(), 1);
                    endDate = today;
                    break;
                case 'quarter':
                    var quarter = Math.floor(today.getMonth() / 3);
                    startDate = new Date(today.getFullYear(), quarter * 3, 1);
                    endDate = today;
                    break;
                case 'year':
                    startDate = new Date(today.getFullYear(), 0, 1);
                    endDate = today;
                    break;
            }
            
            if (startDate && endDate) {
                $('#startDate').val(startDate.toISOString().split('T')[0]);
                $('#endDate').val(endDate.toISOString().split('T')[0]);
                loadDashboardData();
            }
        });
    }

    // ==============================================================
    // Inicialización de contadores animados
    // ==============================================================
    function initializeCounters() {
        // Contadores que se animan al hacer scroll
        var counters = document.querySelectorAll('.counter');
        var options = {
            threshold: 0.7
        };
        
        var observer = new IntersectionObserver(function(entries) {
            entries.forEach(function(entry) {
                if (entry.isIntersecting) {
                    var target = entry.target;
                    var finalValue = parseInt(target.getAttribute('data-target'));
                    
                    var counter = 0;
                    var timer = setInterval(function() {
                        counter += Math.ceil(finalValue / 100);
                        target.textContent = counter;
                        
                        if (counter >= finalValue) {
                            target.textContent = finalValue;
                            clearInterval(timer);
                        }
                    }, 20);
                    
                    observer.unobserve(target);
                }
            });
        }, options);
        
        counters.forEach(function(counter) {
            observer.observe(counter);
        });
    }

    // ==============================================================
    // Inicialización de gráficos
    // ==============================================================
    function initializeCharts() {
        // Los gráficos se cargan dinámicamente
        // Esta función puede usarse para configuraciones globales
    }

    // ==============================================================
    // Exportar datos del dashboard
    // ==============================================================
    function exportDashboardData() {
        var filters = getDashboardFilters();
        var format = $('#exportFormat').val() || 'pdf';
        
        var url = baseUrl + 'dashboard/exportar?format=' + format;
        
        // Agregar filtros como parámetros
        Object.keys(filters).forEach(function(key) {
            if (filters[key]) {
                url += '&' + key + '=' + encodeURIComponent(filters[key]);
            }
        });
        
        // Descargar archivo
        window.open(url, '_blank');
    }

    // ==============================================================
    // Imprimir dashboard
    // ==============================================================
    function printDashboard() {
        // Ocultar elementos no imprimibles
        $('.no-print').hide();
        
        // Imprimir
        window.print();
        
        // Mostrar elementos nuevamente
        $('.no-print').show();
    }

    // ==============================================================
    // Variables globales
    // ==============================================================
    var baseUrl = window.baseUrl || '/';
    
    // Hacer funciones disponibles globalmente
    window.Dashboard = {
        refresh: loadDashboardData,
        export: exportDashboardData,
        print: printDashboard
    };

})(jQuery);