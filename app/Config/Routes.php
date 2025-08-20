<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Rutas problemáticas movidas al principio para evitar conflictos
$routes->get('super-admin/respaldo', 'SuperAdminController::respaldo');
$routes->get('super-admin/reportes-sistema', 'SuperAdminController::reportesSistema');

// Rutas para descarga de respaldos
$routes->post('super-admin/respaldo/crear', 'SuperAdminController::crearRespaldo');
$routes->get('super-admin/respaldo/descargar/(:num)', 'SuperAdminController::descargarRespaldo/$1');
$routes->get('super-admin/respaldo/descargar', 'SuperAdminController::descargarRespaldo');

// Rutas para descarga de reportes
$routes->post('super-admin/reportes-sistema/generar', 'SuperAdminController::generarReporte');
$routes->post('super-admin/reportes-sistema/global', 'SuperAdminController::generarReporteGlobal');
$routes->get('super-admin/reportes-sistema/descargar/(:num)', 'SuperAdminController::descargarReporte/$1');
$routes->get('super-admin/reportes-sistema/descargar', 'SuperAdminController::descargarReporte');

// Ruta de prueba para descarga
$routes->get('test-descarga', 'SuperAdminController::testDescarga');

// Rutas de autenticación
$routes->get('login', 'AuthController::index');
$routes->post('auth/attemptLogin', 'AuthController::attemptLogin');
$routes->get('logout', 'AuthController::logout');

// Rutas del sistema de talento humano
$routes->group('', ['filter' => 'auth'], function($routes) {
    
    // Dashboard principal
    $routes->get('/', 'DashboardController::index');
    $routes->get('dashboard', 'DashboardController::index');
    
    // Gestión de empleados
    $routes->group('empleados', function($routes) {
        $routes->get('/', 'EmpleadoController::index');
        $routes->get('crear', 'EmpleadoController::crear');
        $routes->post('guardar', 'EmpleadoController::guardar');
        $routes->get('editar/(:num)', 'EmpleadoController::editar/$1');
        $routes->post('actualizar/(:num)', 'EmpleadoController::actualizar/$1');
        $routes->get('eliminar/(:num)', 'EmpleadoController::eliminar/$1');
        $routes->get('perfil/(:num)', 'EmpleadoController::perfil/$1');
        
        // Rutas AJAX
        $routes->get('get-departamentos', 'EmpleadoController::getDepartamentos');
        $routes->get('get-por-tipo', 'EmpleadoController::getEmpleadosPorTipo');
        $routes->get('get-por-departamento', 'EmpleadoController::getEmpleadosPorDepartamento');
    });
    
    // Gestión de títulos académicos
    $routes->group('titulos-academicos', function($routes) {
        $routes->get('/', 'TituloAcademicoController::index');
        $routes->get('crear', 'TituloAcademicoController::crear');
        $routes->post('guardar', 'TituloAcademicoController::guardar');
        $routes->get('editar/(:num)', 'TituloAcademicoController::editar/$1');
        $routes->post('actualizar/(:num)', 'TituloAcademicoController::actualizar/$1');
        $routes->get('eliminar/(:num)', 'TituloAcademicoController::eliminar/$1');
        $routes->get('por-empleado/(:num)', 'TituloAcademicoController::porEmpleado/$1');
        $routes->get('descargar/(:num)', 'TituloAcademicoController::descargarCertificado/$1');
        
        // Rutas AJAX
        $routes->get('estadisticas', 'TituloAcademicoController::getEstadisticas');
        $routes->get('buscar-universidad', 'TituloAcademicoController::buscarPorUniversidad');
        $routes->get('recientes', 'TituloAcademicoController::getTitulosRecientes');
    });
    
    // Gestión de capacitaciones
    $routes->group('capacitaciones', function($routes) {
        $routes->get('/', 'CapacitacionController::index');
        $routes->get('empleados', 'CapacitacionController::empleados');
        $routes->get('disponibles', 'CapacitacionController::disponibles');
        $routes->get('crear', 'CapacitacionController::crear');
        $routes->post('guardar', 'CapacitacionController::guardar');
        $routes->get('editar/(:num)', 'CapacitacionController::editar/$1');
        $routes->post('actualizar/(:num)', 'CapacitacionController::actualizar/$1');
        $routes->get('eliminar/(:num)', 'CapacitacionController::eliminar/$1');
    });
    
    // Sistema de evaluaciones
    $routes->group('evaluaciones', function($routes) {
        $routes->get('/', 'EvaluacionController::index');
        $routes->get('crear', 'EvaluacionController::crear');
        $routes->post('guardar', 'EvaluacionController::guardar');
        $routes->get('responder/(:num)', 'EvaluacionController::responder/$1');
        $routes->post('guardar-respuesta', 'EvaluacionController::guardarRespuesta');
        $routes->get('resultados/(:num)', 'EvaluacionController::resultados/$1');
    });
    
    // Control de inasistencias
    $routes->group('inasistencias', function($routes) {
        $routes->get('/', 'InasistenciaController::index');
        $routes->get('crear', 'InasistenciaController::crear');
        $routes->post('guardar', 'InasistenciaController::guardar');
        $routes->get('editar/(:num)', 'InasistenciaController::editar/$1');
        $routes->post('actualizar/(:num)', 'InasistenciaController::actualizar/$1');
        $routes->get('eliminar/(:num)', 'InasistenciaController::eliminar/$1');
    });
    
    // Solicitudes generales
    $routes->group('solicitudes', function($routes) {
        $routes->get('/', 'SolicitudController::index');
        $routes->get('crear', 'SolicitudController::crear');
        $routes->post('guardar', 'SolicitudController::guardar');
        $routes->get('ver/(:num)', 'SolicitudController::ver/$1');
        $routes->post('responder/(:num)', 'SolicitudController::responder/$1');
    });
    
    // Gestión de vacantes y postulantes
    $routes->group('reclutamiento', function($routes) {
        $routes->get('/', 'ReclutamientoController::index');
        $routes->get('vacantes', 'ReclutamientoController::vacantes');
        $routes->get('postulantes', 'ReclutamientoController::postulantes');
        $routes->get('aplicaciones', 'ReclutamientoController::aplicaciones');
        $routes->get('crear-vacante', 'ReclutamientoController::crearVacante');
        $routes->post('guardar-vacante', 'ReclutamientoController::guardarVacante');
    });
    
    // Nómina (solo listados informativos)
    $routes->group('nomina', function($routes) {
        $routes->get('/', 'NominaController::index');
        $routes->get('docentes-tiempo-completo', 'NominaController::docentesTiempoCompleto');
        $routes->get('docentes-medio-tiempo', 'NominaController::docentesMedioTiempo');
        $routes->get('docentes-tiempo-parcial', 'NominaController::docentesTiempoParcial');
        $routes->get('administrativos', 'NominaController::administrativos');
        $routes->get('directivos', 'NominaController::directivos');
        $routes->get('auxiliares', 'NominaController::auxiliares');
    });
    
    // Perfil unificado del empleado
    $routes->get('perfil-empleado', 'PerfilEmpleadoController::index');
    $routes->get('perfil-empleado/documentos', 'PerfilEmpleadoController::documentos');
    $routes->get('perfil-empleado/capacitaciones', 'PerfilEmpleadoController::capacitaciones');
    $routes->get('perfil-empleado/evaluaciones', 'PerfilEmpleadoController::evaluaciones');
    $routes->get('perfil-empleado/inasistencias', 'PerfilEmpleadoController::inasistencias');
    $routes->get('perfil-empleado/permisos', 'PerfilEmpleadoController::permisos');
    
    // Rutas por rol específico
    $routes->group('admin-th', ['filter' => 'role:AdministradorTalentoHumano'], function($routes) {
        $routes->get('dashboard', 'AdminTHController::dashboard');
        
        // Gestión de empleados
        $routes->get('empleados', 'AdminTHController::empleados');
        $routes->get('departamentos', 'AdminTHController::departamentos');
        $routes->get('puestos', 'AdminTHController::puestos');
        
        // Reclutamiento
        $routes->get('vacantes', 'AdminTHController::vacantes');
        $routes->get('candidatos', 'AdminTHController::candidatos');
        $routes->get('contratos', 'AdminTHController::contratos');
        
        // Capacitación
        $routes->get('capacitaciones', 'AdminTHController::capacitaciones');
        $routes->get('empleados-capacitaciones', 'AdminTHController::empleadosCapacitaciones');
        
        // Evaluaciones
        $routes->get('evaluaciones', 'AdminTHController::evaluaciones');
        $routes->get('competencias', 'AdminTHController::competencias');
        
        // Asistencias y permisos
        $routes->get('asistencias', 'AdminTHController::asistencias');
        $routes->get('permisos', 'AdminTHController::permisos');
        
        // Sistema de Inasistencias
        $routes->group('inasistencias', function($routes) {
            $routes->get('/', 'AdminInasistenciaController::dashboard');
            $routes->get('listar', 'AdminInasistenciaController::listarInasistencias');
            $routes->get('registrar', 'AdminInasistenciaController::registrarInasistencia');
            $routes->post('guardar', 'AdminInasistenciaController::guardarInasistencia');
            $routes->get('editar/(:num)', 'AdminInasistenciaController::editarInasistencia/$1');
            $routes->post('actualizar/(:num)', 'AdminInasistenciaController::actualizarInasistencia/$1');
            $routes->get('ver/(:num)', 'AdminInasistenciaController::verInasistencia/$1');
            $routes->get('revisar-justificacion/(:num)', 'AdminInasistenciaController::revisarJustificacion/$1');
            $routes->post('aprobar-justificacion/(:num)', 'AdminInasistenciaController::aprobarJustificacion/$1');
            $routes->post('rechazar-justificacion/(:num)', 'AdminInasistenciaController::rechazarJustificacion/$1');
            $routes->get('reportes', 'AdminInasistenciaController::generarReporte');
            $routes->get('politicas', 'AdminInasistenciaController::gestionarPoliticas');
            $routes->get('reporte-empleado/(:num)', 'AdminInasistenciaController::reporteEmpleado/$1');
            
            // API endpoints
            $routes->get('api/estadisticas', 'AdminInasistenciaController::getEstadisticas');
            $routes->get('api/por-departamento', 'AdminInasistenciaController::getPorDepartamento');
            $routes->get('api/tendencia', 'AdminInasistenciaController::getTendencia');
            $routes->get('api/empleados-criticos', 'AdminInasistenciaController::getEmpleadosCriticos');
        });
        
        // Nómina
        $routes->get('nominas', 'AdminTHController::nominas');
        
        // Reportes y configuración
        $routes->get('reportes', 'AdminTHController::reportes');
        $routes->get('perfil', 'AdminTHController::perfil');
        $routes->get('cuenta', 'AdminTHController::cuenta');
        
        // AJAX para actualizaciones
        $routes->post('actualizar-perfil', 'AdminTHController::actualizarPerfil');
        $routes->post('cambiar-password', 'AdminTHController::cambiarPassword');
        $routes->post('configurar-notificaciones', 'AdminTHController::configurarNotificaciones');
        $routes->post('configurar-privacidad', 'AdminTHController::configurarPrivacidad');
        $routes->post('cerrar-sesiones', 'AdminTHController::cerrarSesiones');
    });
    
    $routes->group('empleado', ['filter' => 'role:Docente'], function($routes) {
        $routes->get('dashboard', 'EmpleadoController::dashboard');
        $routes->get('mi-perfil', 'EmpleadoController::miPerfil');
        $routes->get('mis-evaluaciones', 'EmpleadoController::misEvaluaciones');
        $routes->get('perfil', 'EmpleadoController::perfil');
        $routes->get('documentos', 'EmpleadoController::documentos');
        $routes->get('documentos/ver/(:num)', 'EmpleadoController::verDocumento/$1');
        $routes->get('documentos/descargar/(:num)', 'EmpleadoController::descargarDocumento/$1');
        $routes->get('capacitaciones', 'EmpleadoController::capacitaciones');
        $routes->get('certificados', 'EmpleadoController::certificados');
        $routes->get('evaluaciones', 'EmpleadoController::evaluaciones');
        $routes->get('competencias', 'EmpleadoController::competencias');
        $routes->get('asistencias', 'EmpleadoController::asistencias');
        $routes->get('permisos', 'EmpleadoController::permisos');
        
        // Sistema de Inasistencias para Empleados
        $routes->group('inasistencias', function($routes) {
            $routes->get('/', 'InasistenciaController::dashboard');
            $routes->get('mis-inasistencias', 'InasistenciaController::misInasistencias');
            $routes->get('ver/(:num)', 'InasistenciaController::verInasistencia/$1');
            $routes->post('subir-justificacion', 'InasistenciaController::subirJustificacion');
            $routes->get('reporte', 'InasistenciaController::reporteInasistencias');
            
            // API endpoints
            $routes->get('api/estadisticas', 'InasistenciaController::getEstadisticas');
            $routes->get('api/por-periodo', 'InasistenciaController::getPorPeriodo');
        });
        $routes->get('nomina', 'EmpleadoController::nomina');

        $routes->get('solicitudes', 'EmpleadoController::solicitudes');
        $routes->get('nueva-solicitud', 'EmpleadoController::nuevaSolicitud');
        $routes->get('cuenta', 'EmpleadoController::cuenta');
        
        // Funcionalidades específicas por tipo de empleado
        $routes->get('evaluaciones-estudiantiles', 'EmpleadoController::evaluacionesEstudiantiles');
        $routes->get('metodologia-ensenanza', 'EmpleadoController::metodologiaEnsenanza');
        $routes->get('investigacion', 'EmpleadoController::investigacion');
        $routes->get('gestion-procesos', 'EmpleadoController::gestionProcesos');
        $routes->get('reportes-administrativos', 'EmpleadoController::reportesAdministrativos');
        $routes->get('gestion-equipo', 'EmpleadoController::gestionEquipo');
        $routes->get('indicadores-gestion', 'EmpleadoController::indicadoresGestion');
        
        // Nuevas funcionalidades para empleados
        $routes->get('titulos-academicos', 'EmpleadoController::titulosAcademicos');
        $routes->get('capacitaciones-individuales', 'EmpleadoController::capacitacionesIndividuales');
        $routes->get('evaluaciones-empleado', 'EmpleadoController::evaluacionesEmpleado');
        $routes->get('control-inasistencias', 'EmpleadoController::controlInasistencias');
        $routes->get('solicitudes-generales', 'EmpleadoController::solicitudesGenerales');
        $routes->get('permisos-vacaciones', 'EmpleadoController::permisosVacaciones');
        
        // POST routes para empleado
        $routes->post('perfil/actualizar', 'EmpleadoController::actualizarPerfil');
        $routes->post('documentos/subir', 'EmpleadoController::subirDocumento');
        $routes->post('documentos/eliminar/(:num)', 'EmpleadoController::eliminarDocumento/$1');
        $routes->post('asistencias/guardar', 'EmpleadoController::guardarAsistencia');
        $routes->post('cuenta/cambiar-password', 'EmpleadoController::cambiarPassword');
        $routes->post('cuenta/configurar-notificaciones', 'EmpleadoController::configurarNotificaciones');
        $routes->post('cuenta/configurar-privacidad', 'EmpleadoController::configurarPrivacidad');
        $routes->post('cuenta/cerrar-sesiones', 'EmpleadoController::cerrarSesiones');
    });
    
    $routes->group('super-admin', ['filter' => 'role:SuperAdministrador'], function($routes) {
        $routes->get('dashboard', 'SuperAdminController::dashboard');
        $routes->get('usuarios', 'SuperAdminController::usuarios');
        $routes->post('usuarios/crear', 'SuperAdminController::crearUsuario');
        $routes->post('usuarios/toggle-status', 'SuperAdminController::toggleUserStatus');
        $routes->post('usuarios/eliminar', 'SuperAdminController::eliminarUsuario');
        $routes->get('roles', 'SuperAdminController::roles');
        $routes->get('departamentos', 'SuperAdminController::departamentos');
        $routes->get('configuracion', 'SuperAdminController::configuracion');
        $routes->get('logs', 'SuperAdminController::logs');
        $routes->get('perfil', 'SuperAdminController::perfil');
        $routes->post('perfil/actualizar', 'SuperAdminController::actualizarPerfil');
        $routes->get('cuenta', 'SuperAdminController::cuenta');
        $routes->post('cuenta/cambiar-password', 'SuperAdminController::cambiarPassword');
        $routes->post('cuenta/configurar-notificaciones', 'SuperAdminController::configurarNotificaciones');
        $routes->post('cuenta/configurar-privacidad', 'SuperAdminController::configurarPrivacidad');
        $routes->post('cuenta/cerrar-sesiones', 'SuperAdminController::cerrarSesiones');
        $routes->get('backup', 'SuperAdminController::backup');
        $routes->get('configuracion-sistema', 'SuperAdminController::configuracionSistema');
    });
});

// Rutas públicas para postulantes
$routes->group('postulantes', function($routes) {
    $routes->get('registro', 'PostulanteController::registro');
    $routes->post('registro', 'PostulanteController::guardarRegistro');
    $routes->get('login', 'PostulanteController::login');
    $routes->post('login', 'PostulanteController::autenticar');
    $routes->get('vacantes-disponibles', 'PostulanteController::vacantesDisponibles');
    $routes->post('aplicar-vacante', 'PostulanteController::aplicarVacante');
    
    // Rutas protegidas para postulantes logueados
    $routes->group('', ['filter' => 'auth'], function($routes) {
        $routes->get('dashboard', 'PostulanteController::dashboard');
        $routes->get('estado-aplicaciones', 'PostulanteController::estadoAplicaciones');
        $routes->get('perfil', 'PostulanteController::perfil');
        $routes->post('perfil/actualizar', 'PostulanteController::actualizarPerfil');
        $routes->get('logout', 'PostulanteController::logout');
        $routes->get('descargar-archivo/(:any)/(:num)', 'PostulanteController::descargarArchivo/$1/$2');
    });
});

// Rutas de error
$routes->get('error/403', 'ErrorController::accessDenied');
$routes->get('error/404', 'ErrorController::notFound');
$routes->get('error/500', 'ErrorController::serverError');

// Test routes específicas para verificar el problema
$routes->get('test-respaldo-direct', function() {
    return "Test Respaldo Direct funcionando - " . date('Y-m-d H:i:s');
});

$routes->get('test-reportes-direct', function() {
    return "Test Reportes Direct funcionando - " . date('Y-m-d H:i:s');
});

// Ruta por defecto
$routes->get('(:any)', 'ErrorController::notFound');
