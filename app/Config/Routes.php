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
$routes->post('auth/cerrar-todas-sesiones', 'AuthController::cerrarTodasLasSesiones');




// Rutas para Admin Talento Humano
$routes->group('admin-th', ['filter' => 'auth'], function($routes) {
    $routes->get('dashboard', 'AdminTH\AdminTHController::dashboard');
    
    // Gestión de empleados
    $routes->get('empleados', 'AdminTH\AdminTHController::empleados');
    $routes->get('empleados/obtener', 'AdminTH\AdminTHController::obtenerEmpleados');
    $routes->get('empleados/obtener/(:num)', 'AdminTH\AdminTHController::obtenerEmpleado/$1');
    $routes->get('empleados/credenciales/(:num)', 'AdminTH\AdminTHController::obtenerCredenciales/$1');
    $routes->post('empleados/guardar', 'AdminTH\AdminTHController::guardarEmpleado');
    $routes->get('empleados/estadisticas', 'AdminTH\AdminTHController::obtenerEstadisticasEmpleados');
    $routes->post('empleados/deshabilitar', 'AdminTH\AdminTHController::deshabilitarEmpleado');
    $routes->post('empleados/habilitar', 'AdminTH\AdminTHController::habilitarEmpleado');
    $routes->get('empleados/historial/(:num)', 'AdminTH\AdminTHController::obtenerHistorialEmpleado/$1');
    $routes->get('empleados/reporte-inactivos', 'AdminTH\AdminTHController::reporteEmpleadosInactivos');
    $routes->get('empleados/exportar-inactivos', 'AdminTH\AdminTHController::exportarEmpleadosInactivos');
    
    // Gestión de departamentos
    $routes->get('departamentos', 'AdminTH\AdminTHController::departamentos');
    $routes->get('departamentos/obtener', 'AdminTH\AdminTHController::obtenerDepartamentos');
    $routes->get('departamentos/obtener/(:num)', 'AdminTH\AdminTHController::obtenerDepartamento/$1');
    $routes->post('departamentos/guardar', 'AdminTH\AdminTHController::guardarDepartamento');
    $routes->post('departamentos/eliminar', 'AdminTH\AdminTHController::eliminarDepartamento');
    $routes->get('departamentos/activos', 'AdminTH\AdminTHController::obtenerDepartamentosActivos');
    $routes->get('departamentos/estadisticas', 'AdminTH\AdminTHController::obtenerEstadisticasDepartamentos');
    
    // Gestión de puestos
    $routes->get('puestos', 'AdminTH\AdminTHController::puestos');
    $routes->get('puestos/obtener', 'AdminTH\AdminTHController::obtenerPuestos');
    $routes->get('puestos/obtener/(:num)', 'AdminTH\AdminTHController::obtenerPuesto/$1');
    $routes->post('puestos/guardar', 'AdminTH\AdminTHController::guardarPuesto');
    $routes->post('puestos/eliminar', 'AdminTH\AdminTHController::eliminarPuesto');
    $routes->post('puestos/generar-url', 'AdminTH\AdminTHController::generarUrlPostulacion');
    $routes->get('puestos/(:num)/postulantes', 'AdminTH\AdminTHController::obtenerPostulantesPuesto/$1');
    
    // Gestión de postulantes
    $routes->get('postulantes', 'AdminTH\AdminTHController::postulantes');
    $routes->get('postulantes/(:num)', 'AdminTH\AdminTHController::verPostulante/$1');
    $routes->get('postulantes/(:num)/cv', 'AdminTH\AdminTHController::descargarCV/$1');
    $routes->get('postulantes/exportar', 'AdminTH\AdminTHController::exportarPostulantes');
    $routes->post('postulaciones/cambiar-estado', 'AdminTH\AdminTHController::cambiarEstadoPostulacion');
    
    // Gestión de capacitaciones
    $routes->get('capacitaciones', 'AdminTH\AdminTHController::capacitaciones');
    $routes->get('capacitaciones/obtener', 'AdminTH\AdminTHController::obtenerCapacitaciones');
    $routes->get('capacitaciones/obtener/(:num)', 'AdminTH\AdminTHController::obtenerCapacitacion/$1');
    $routes->post('capacitaciones/crear', 'AdminTH\AdminTHController::crearCapacitacion');
    $routes->post('capacitaciones/actualizar', 'AdminTH\AdminTHController::actualizarCapacitacion');
    $routes->post('capacitaciones/cambiar-estado', 'AdminTH\AdminTHController::cambiarEstadoCapacitacion');
    $routes->post('capacitaciones/eliminar', 'AdminTH\AdminTHController::eliminarCapacitacion');
    
    // Gestión de títulos académicos
    $routes->get('titulos-academicos', 'AdminTH\AdminTHController::titulosAcademicos');
    $routes->get('titulos-academicos/obtener', 'AdminTH\AdminTHController::obtenerTitulosAcademicos');
    $routes->get('titulos-academicos/obtener/(:num)', 'AdminTH\AdminTHController::obtenerTituloAcademico/$1');
    $routes->post('titulos-academicos/crear', 'AdminTH\AdminTHController::crearTituloAcademico');
    $routes->post('titulos-academicos/editar', 'AdminTH\AdminTHController::editarTituloAcademico');
    $routes->post('titulos-academicos/deshabilitar', 'AdminTH\AdminTHController::deshabilitarTituloAcademico');
    $routes->post('titulos-academicos/habilitar', 'AdminTH\AdminTHController::habilitarTituloAcademico');
    
    // Gestión de evaluaciones
    $routes->get('evaluaciones', 'AdminTH\AdminTHController::evaluaciones');
    
    // Gestión de inasistencias
    $routes->get('inasistencias', 'AdminTH\AdminTHController::inasistencias');
    $routes->get('inasistencias/registrar', 'AdminTH\AdminTHController::registrarInasistencia');
    $routes->post('inasistencias/guardar', 'AdminTH\AdminTHController::guardarInasistencia');
    $routes->get('inasistencias/editar/(:num)', 'AdminTH\AdminTHController::editarInasistencia/$1');
    $routes->post('inasistencias/actualizar', 'AdminTH\AdminTHController::actualizarInasistencia');
    $routes->post('inasistencias/cambiar-estado', 'AdminTH\AdminTHController::cambiarEstadoInasistencia');
    $routes->get('inasistencias/justificar/(:num)', 'AdminTH\AdminTHController::justificarInasistencia/$1');
    $routes->post('inasistencias/procesar-justificacion', 'AdminTH\AdminTHController::procesarJustificacion');
    $routes->get('inasistencias/reporte', 'AdminTH\AdminTHController::reporteInasistencias');
    $routes->get('inasistencias/exportar', 'AdminTH\AdminTHController::exportarInasistencias');
    
    // Políticas de inasistencia
    $routes->get('politicas-inasistencia', 'AdminTH\AdminTHController::politicasInasistencia');
    
    // Solicitudes de capacitación
    $routes->get('solicitudes-capacitacion', 'AdminTH\AdminTHController::solicitudesCapacitacion');
    
    // Reportes y estadísticas
    $routes->get('reportes', 'AdminTH\AdminTHController::reportes');
    $routes->get('estadisticas', 'AdminTH\AdminTHController::estadisticas');
    
    // Acceso rápido
    $routes->get('acceso-rapido', 'AdminTH\AdminTHController::accesoRapido');
    
    // Perfil unificado (datos personales + seguridad)
    $routes->get('mi-perfil', 'AdminTH\AdminTHController::miPerfil');
    $routes->post('actualizar-perfil', 'AdminTH\AdminTHController::actualizarPerfil');
    $routes->post('cambiar-contrasena', 'AdminTH\AdminTHController::cambiarPassword');
});

// Rutas para Empleados (incluye todos los tipos: Docente, Administrativo, Directivo, Auxiliar)
$routes->group('empleado', ['filter' => 'auth'], function($routes) {
    $routes->get('dashboard', 'Empleado\EmpleadoController::dashboard');
    $routes->get('mi-perfil', 'Empleado\EmpleadoController::miPerfil');
    $routes->post('cambiar-password', 'Empleado\EmpleadoController::cambiarPassword');
    $routes->post('actualizar-perfil', 'Empleado\EmpleadoController::actualizarPerfil');
    
    // Capacitaciones
    $routes->get('capacitaciones', 'Empleado\EmpleadoController::capacitaciones');
    
    // Títulos académicos
    $routes->get('titulos-academicos', 'Empleado\EmpleadoController::titulosAcademicos');
    
    // Evaluaciones
    $routes->get('evaluaciones', 'Empleado\EmpleadoController::evaluaciones');
    
    // Inasistencias
    $routes->get('inasistencias', 'Empleado\EmpleadoController::inasistencias');
    $routes->get('inasistencias/mis-inasistencias', 'Empleado\EmpleadoController::misInasistencias');
    $routes->post('inasistencias/justificar', 'Empleado\EmpleadoController::justificarInasistencia');
    $routes->get('inasistencias/ver/(:num)', 'Empleado\EmpleadoController::verInasistencia/$1');
    $routes->get('inasistencias/estadisticas', 'Empleado\EmpleadoController::estadisticasInasistencias');
    
    // Solicitudes de capacitación
    $routes->get('solicitudes-capacitacion', 'Empleado\EmpleadoController::solicitudesCapacitacion');
    
    // Permisos y vacaciones
    $routes->get('permisos-vacaciones', 'Empleado\EmpleadoController::permisosVacaciones');
    
    // Competencias
    $routes->get('competencias', 'Empleado\EmpleadoController::competencias');
    
    // Asistencias
    $routes->get('asistencias', 'Empleado\EmpleadoController::asistencias');
    
    // Documentos
    $routes->get('documentos', 'Empleado\EmpleadoController::documentos');
    
    // Solicitudes generales
    $routes->get('solicitudes-generales', 'Empleado\EmpleadoController::solicitudesGenerales');
    
    // Acceso rápido
    $routes->get('acceso-rapido', 'Empleado\EmpleadoController::accesoRapido');
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
    
    // API de inasistencias
    $routes->get('inasistencias', 'Api\InasistenciaApiController::index');
    $routes->get('inasistencias/(:num)', 'Api\InasistenciaApiController::show/$1');
    $routes->post('inasistencias', 'Api\InasistenciaApiController::create');
    $routes->put('inasistencias/(:num)', 'Api\InasistenciaApiController::update/$1');
    $routes->delete('inasistencias/(:num)', 'Api\InasistenciaApiController::delete/$1');
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

// Rutas de desarrollo (solo en entorno de desarrollo)
if (ENVIRONMENT === 'development') {
    $routes->get('test', 'TestController::index');
    $routes->get('debug', 'DebugController::index');
}

// Rutas para postulaciones públicas (deben ir al final para no interferir con otras rutas)
$routes->get('postulacion-([0-9]+)-(.+)', 'PostulacionController::mostrarFormulario/$1/$2');
$routes->get('admin-th/postulacion-([0-9]+)-(.+)', 'PostulacionController::mostrarFormulario/$1/$2');
$routes->post('postulacion/procesar', 'PostulacionController::procesarPostulacion');
$routes->post('postulacion/subir-cv', 'PostulacionController::subirCV');
$routes->get('postulacion/estado/(:segment)/(:num)', 'PostulacionController::verEstado/$1/$2');
