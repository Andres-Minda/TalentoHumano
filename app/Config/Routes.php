<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Rutas de autenticación
$routes->get('/', 'AuthController::index');
$routes->get('/login', 'AuthController::index');
$routes->post('auth/attemptLogin', 'AuthController::attemptLogin');
$routes->get('auth/logout', 'AuthController::logout');

// Rutas para Super Administrador
$routes->group('super-admin', ['filter' => 'auth'], function($routes) {
    $routes->get('dashboard', 'SuperAdmin\SuperAdminController::dashboard');
    $routes->get('usuarios', 'SuperAdmin\SuperAdminController::usuarios');
    $routes->get('roles', 'SuperAdmin\SuperAdminController::roles');
    $routes->get('configuracion', 'SuperAdmin\SuperAdminController::configuracion');
    $routes->get('respaldos', 'SuperAdmin\SuperAdminController::respaldos');
    $routes->get('logs', 'SuperAdmin\SuperAdminController::logs');
    $routes->get('estadisticas', 'SuperAdmin\SuperAdminController::estadisticas');
    $routes->get('perfil', 'SuperAdmin\SuperAdminController::perfil');
    $routes->get('cuenta', 'SuperAdmin\SuperAdminController::cuenta');
});

// Rutas para Admin Talento Humano
$routes->group('admin-th', ['filter' => 'auth'], function($routes) {
    $routes->get('dashboard', 'AdminTH\AdminTHController::dashboard');
    $routes->get('empleados', 'AdminTH\AdminTHController::empleados');
    $routes->get('empleados/obtener', 'AdminTH\AdminTHController::obtenerEmpleados');
    $routes->get('empleados/obtener/(:num)', 'AdminTH\AdminTHController::obtenerEmpleado/$1');
    $routes->get('empleados/credenciales/(:num)', 'AdminTH\AdminTHController::obtenerCredenciales/$1');
    $routes->post('empleados/guardar', 'AdminTH\AdminTHController::guardarEmpleado');
    $routes->get('departamentos', 'AdminTH\AdminTHController::departamentos');
    $routes->get('departamentos/obtener', 'AdminTH\AdminTHController::obtenerDepartamentos');
    $routes->get('departamentos/obtener/(:num)', 'AdminTH\AdminTHController::obtenerDepartamento/$1');
    $routes->post('departamentos/guardar', 'AdminTH\AdminTHController::guardarDepartamento');
    $routes->post('departamentos/eliminar', 'AdminTH\AdminTHController::eliminarDepartamento');
    $routes->get('departamentos/activos', 'AdminTH\AdminTHController::obtenerDepartamentosActivos');
    $routes->get('puestos', 'AdminTH\AdminTHController::puestos');
    $routes->get('puestos/obtener', 'AdminTH\AdminTHController::obtenerPuestos');
    $routes->get('puestos/obtener/(:num)', 'AdminTH\AdminTHController::obtenerPuesto/$1');
    $routes->post('puestos/guardar', 'AdminTH\AdminTHController::guardarPuesto');
    $routes->post('puestos/eliminar', 'AdminTH\AdminTHController::eliminarPuesto');
    $routes->post('puestos/generar-url', 'AdminTH\AdminTHController::generarUrlPostulacion');
    $routes->get('puestos/(:num)/postulantes', 'AdminTH\AdminTHController::obtenerPostulantesPuesto/$1');
    $routes->post('postulaciones/cambiar-estado', 'AdminTH\AdminTHController::cambiarEstadoPostulacion');
$routes->get('capacitaciones', 'AdminTH\AdminTHController::capacitaciones');

// Rutas para postulaciones públicas (se moverán al final)
    $routes->get('titulos-academicos', 'AdminTH\AdminTHController::titulosAcademicos');
    $routes->get('evaluaciones', 'AdminTH\AdminTHController::evaluaciones');
    $routes->get('inasistencias', 'AdminTH\AdminTHController::inasistencias');
    $routes->get('politicas-inasistencia', 'AdminTH\AdminTHController::politicasInasistencia');
    $routes->get('solicitudes-capacitacion', 'AdminTH\AdminTHController::solicitudesCapacitacion');
    $routes->get('reportes', 'AdminTH\AdminTHController::reportes');
    $routes->get('estadisticas', 'AdminTH\AdminTHController::estadisticas');
    $routes->get('perfil', 'AdminTH\AdminTHController::perfil');
    $routes->get('cuenta', 'AdminTH\AdminTHController::cuenta');
});

// Rutas para Empleados
$routes->group('empleado', ['filter' => 'auth'], function($routes) {
    $routes->get('dashboard', 'Empleado\EmpleadoController::dashboard');
    $routes->get('mi-perfil', 'Empleado\EmpleadoController::miPerfil');
    $routes->get('cuenta', 'Empleado\EmpleadoController::cuenta');
    $routes->post('cambiar-password', 'Empleado\EmpleadoController::cambiarPassword');
    $routes->get('capacitaciones', 'Empleado\EmpleadoController::capacitaciones');
    $routes->get('titulos-academicos', 'Empleado\EmpleadoController::titulosAcademicos');
    $routes->get('evaluaciones', 'Empleado\EmpleadoController::evaluaciones');
    $routes->get('inasistencias', 'Empleado\EmpleadoController::inasistencias');
    $routes->get('solicitudes-capacitacion', 'Empleado\EmpleadoController::solicitudesCapacitacion');
    $routes->get('permisos-vacaciones', 'Empleado\EmpleadoController::permisosVacaciones');
    $routes->get('competencias', 'Empleado\EmpleadoController::competencias');
    $routes->get('asistencias', 'Empleado\EmpleadoController::asistencias');
    $routes->get('documentos', 'Empleado\EmpleadoController::documentos');
    $routes->get('solicitudes-generales', 'Empleado\EmpleadoController::solicitudesGenerales');
});

// Rutas para API
$routes->group('api', ['filter' => 'auth'], function($routes) {
    // API de empleados
    $routes->get('empleados', 'Api\EmpleadoApiController::index');
    $routes->get('empleados/(:num)', 'Api\EmpleadoApiController::show/$1');
    $routes->post('empleados', 'Api\EmpleadoApiController::create');
    $routes->put('empleados/(:num)', 'Api\EmpleadoApiController::update/$1');
    $routes->delete('empleados/(:num)', 'Api\EmpleadoApiController::delete/$1');
    
    // API de capacitaciones
    $routes->get('capacitaciones', 'Api\CapacitacionApiController::index');
    $routes->get('capacitaciones/(:num)', 'Api\CapacitacionApiController::show/$1');
    $routes->post('capacitaciones', 'Api\CapacitacionApiController::create');
    $routes->put('capacitaciones/(:num)', 'Api\CapacitacionApiController::update/$1');
    $routes->delete('capacitaciones/(:num)', 'Api\CapacitacionApiController::delete/$1');
    
    // API de evaluaciones
    $routes->get('evaluaciones', 'Api\EvaluacionApiController::index');
    $routes->get('evaluaciones/(:num)', 'Api\EvaluacionApiController::show/$1');
    $routes->post('evaluaciones', 'Api\EvaluacionApiController::create');
    $routes->put('evaluaciones/(:num)', 'Api\EvaluacionApiController::update/$1');
    $routes->delete('evaluaciones/(:num)', 'Api\EvaluacionApiController::delete/$1');
});

// Rutas para reportes
$routes->group('reportes', ['filter' => 'auth'], function($routes) {
    $routes->get('empleados', 'Reportes\EmpleadoReporteController::index');
    $routes->get('capacitaciones', 'Reportes\CapacitacionReporteController::index');
    $routes->get('evaluaciones', 'Reportes\EvaluacionReporteController::index');
    $routes->get('inasistencias', 'Reportes\InasistenciaReporteController::index');
    $routes->get('exportar/(:any)', 'Reportes\ExportarController::exportar/$1');
});

// Rutas para configuración del sistema
$routes->group('configuracion', ['filter' => 'auth'], function($routes) {
    $routes->get('sistema', 'Configuracion\SistemaController::index');
    $routes->get('usuarios', 'Configuracion\UsuarioController::index');
    $routes->get('roles', 'Configuracion\RolController::index');
    $routes->get('permisos', 'Configuracion\PermisoController::index');
});

// Rutas para archivos y assets
$routes->get('sistema/(:any)', 'AssetsController::serve/$1');

// Rutas de error - usar configuración estándar de CodeIgniter 4

// Rutas de desarrollo (solo en entorno de desarrollo)
if (ENVIRONMENT === 'development') {
    $routes->get('test', 'TestController::index');
    $routes->get('debug', 'DebugController::index');
}

// Rutas para postulaciones públicas (deben ir al final para no interferir con otras rutas)
$routes->get('postulacion-([0-9]+)-(.+)', 'PostulacionController::mostrarFormulario/$1-$2');
$routes->get('admin-th/postulacion-([0-9]+)-(.+)', 'PostulacionController::mostrarFormulario/$1-$2');
$routes->post('postulacion/procesar', 'PostulacionController::procesarPostulacion');
$routes->post('postulacion/subir-cv', 'PostulacionController::subirCV');
$routes->get('postulacion/estado/(:segment)/(:num)', 'PostulacionController::verEstado/$1/$2');
