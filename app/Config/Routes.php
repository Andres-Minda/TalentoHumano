<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Rutas de autenticación
$routes->get('login', 'AuthController::index');
$routes->post('auth/attemptLogin', 'AuthController::attemptLogin');


$routes->get('logout', 'AuthController::logout');
$routes->get('access-denied', 'AuthController::accessDenied');

// Rutas para Super Administrador
$routes->get('super-admin/dashboard', 'SuperAdminController::dashboard');
$routes->get('super-admin/usuarios', 'SuperAdminController::usuarios');
$routes->post('super-admin/usuarios/crear', 'SuperAdminController::crearUsuario');
$routes->post('super-admin/usuarios/toggle-status', 'SuperAdminController::toggleUserStatus');
$routes->post('super-admin/usuarios/eliminar', 'SuperAdminController::eliminarUsuario');
$routes->get('super-admin/roles', 'SuperAdminController::roles');
$routes->get('super-admin/departamentos', 'SuperAdminController::departamentos');
$routes->get('super-admin/configuracion', 'SuperAdminController::configuracion');
$routes->get('super-admin/backup', 'SuperAdminController::backup');
$routes->get('super-admin/reportes', 'SuperAdminController::reportes');
$routes->get('super-admin/logs', 'SuperAdminController::logs');
$routes->get('super-admin/perfil', 'SuperAdminController::perfil');
$routes->post('super-admin/perfil/actualizar', 'SuperAdminController::actualizarPerfil');
$routes->get('super-admin/cuenta', 'SuperAdminController::cuenta');
$routes->post('super-admin/cuenta/cambiar-password', 'SuperAdminController::cambiarPassword');
$routes->post('super-admin/cuenta/configurar-notificaciones', 'SuperAdminController::configurarNotificaciones');
$routes->post('super-admin/cuenta/configurar-privacidad', 'SuperAdminController::configurarPrivacidad');
$routes->post('super-admin/cuenta/cerrar-sesiones', 'SuperAdminController::cerrarSesiones');

// Rutas para Administrador de Talento Humano
$routes->get('admin-th/dashboard', 'AdminTHController::dashboard');
$routes->get('admin-th/empleados', 'AdminTHController::empleados');
$routes->get('admin-th/departamentos', 'AdminTHController::departamentos');
$routes->get('admin-th/puestos', 'AdminTHController::puestos');
$routes->get('admin-th/vacantes', 'AdminTHController::vacantes');
$routes->get('admin-th/candidatos', 'AdminTHController::candidatos');
$routes->get('admin-th/contratos', 'AdminTHController::contratos');
$routes->get('admin-th/capacitaciones', 'AdminTHController::capacitaciones');
$routes->get('admin-th/empleados-capacitaciones', 'AdminTHController::empleadosCapacitaciones');
$routes->get('admin-th/evaluaciones', 'AdminTHController::evaluaciones');
$routes->get('admin-th/competencias', 'AdminTHController::competencias');
$routes->get('admin-th/asistencias', 'AdminTHController::asistencias');
$routes->get('admin-th/permisos', 'AdminTHController::permisos');
$routes->get('admin-th/nominas', 'AdminTHController::nominas');
$routes->get('admin-th/beneficios', 'AdminTHController::beneficios');
$routes->get('admin-th/reportes', 'AdminTHController::reportes');
$routes->get('admin-th/perfil', 'AdminTHController::perfil');
$routes->post('admin-th/perfil/actualizar', 'AdminTHController::actualizarPerfil');
$routes->get('admin-th/cuenta', 'AdminTHController::cuenta');
$routes->post('admin-th/cuenta/cambiar-password', 'AdminTHController::cambiarPassword');
$routes->post('admin-th/cuenta/configurar-notificaciones', 'AdminTHController::configurarNotificaciones');
$routes->post('admin-th/cuenta/configurar-privacidad', 'AdminTHController::configurarPrivacidad');
$routes->post('admin-th/cuenta/cerrar-sesiones', 'AdminTHController::cerrarSesiones');

// Rutas para Docente
$routes->get('docente/dashboard', 'DocenteController::dashboard');
$routes->get('docente/perfil', 'DocenteController::perfil');
$routes->post('docente/perfil/actualizar', 'DocenteController::actualizarPerfil');
$routes->get('docente/documentos', 'DocenteController::documentos');
$routes->post('docente/documentos/subir', 'DocenteController::subirDocumento');
$routes->get('docente/documentos/ver/(:num)', 'DocenteController::verDocumento/$1');
$routes->get('docente/documentos/descargar/(:num)', 'DocenteController::descargarDocumento/$1');
$routes->post('docente/documentos/eliminar/(:num)', 'DocenteController::eliminarDocumento/$1');
$routes->get('docente/capacitaciones', 'DocenteController::capacitaciones');
$routes->get('docente/certificados', 'DocenteController::certificados');
$routes->get('docente/evaluaciones', 'DocenteController::evaluaciones');
$routes->get('docente/competencias', 'DocenteController::competencias');
$routes->get('docente/asistencias', 'DocenteController::asistencias');
$routes->get('docente/permisos', 'DocenteController::permisos');
$routes->get('docente/nomina', 'DocenteController::nomina');
$routes->get('docente/beneficios', 'DocenteController::beneficios');
$routes->get('docente/solicitudes', 'DocenteController::solicitudes');
$routes->get('docente/nueva-solicitud', 'DocenteController::nuevaSolicitud');
$routes->get('docente/cuenta', 'DocenteController::cuenta');
$routes->post('docente/cuenta/cambiar-password', 'DocenteController::cambiarPassword');
$routes->post('docente/cuenta/configurar-notificaciones', 'DocenteController::configurarNotificaciones');
$routes->post('docente/cuenta/configurar-privacidad', 'DocenteController::configurarPrivacidad');
$routes->post('docente/cuenta/cerrar-sesiones', 'DocenteController::cerrarSesiones');

// Ruta de prueba
$routes->get('test', 'TestController::index');

// Test route for dropdown functionality
$routes->get('test-dropdown', function() {
    return view('test_dropdown');
});

// Test route for system with ITSI structure
$routes->get('test-system', function() {
    return view('test_system');
});

// Test route for sidebar detection
$routes->get('test-sidebar', function() {
    return view('test_sidebar');
});

// Ruta por defecto
$routes->get('/', 'AuthController::index');
