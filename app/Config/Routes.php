<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

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
    $routes->get('perfil-empleado/beneficios', 'PerfilEmpleadoController::beneficios');
    
    // Rutas por rol específico
    $routes->group('admin-th', ['filter' => 'role:ADMIN_TH'], function($routes) {
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
        
        // Nómina y beneficios
        $routes->get('nominas', 'AdminTHController::nominas');
        $routes->get('beneficios', 'AdminTHController::beneficios');
        
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
    
    $routes->group('docente', ['filter' => 'role:DOCENTE'], function($routes) {
        $routes->get('dashboard', 'DocenteController::dashboard');
        $routes->get('mi-perfil', 'DocenteController::miPerfil');
        $routes->get('mis-evaluaciones', 'DocenteController::misEvaluaciones');
    });
    
    $routes->group('super-admin', ['filter' => 'role:SUPER_ADMIN'], function($routes) {
        $routes->get('dashboard', 'SuperAdminController::dashboard');
        $routes->get('usuarios', 'SuperAdminController::usuarios');
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
});

// Rutas de error
$routes->get('error/403', 'ErrorController::accessDenied');
$routes->get('error/404', 'ErrorController::notFound');
$routes->get('error/500', 'ErrorController::serverError');

// Ruta por defecto
$routes->get('(:any)', 'ErrorController::notFound');
