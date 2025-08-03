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
$routes->get('super-admin/roles', 'SuperAdminController::roles');

// Rutas para Administrador de Talento Humano
$routes->get('admin-th/dashboard', 'AdminTHController::dashboard');
$routes->get('admin-th/empleados', 'AdminTHController::empleados');
$routes->get('admin-th/nominas', 'AdminTHController::nominas');
$routes->get('admin-th/evaluaciones', 'AdminTHController::evaluaciones');

// Rutas para Docente
$routes->get('docente/dashboard', 'DocenteController::dashboard');
$routes->get('docente/perfil', 'DocenteController::perfil');
$routes->get('docente/capacitaciones', 'DocenteController::capacitaciones');

// Ruta de prueba
$routes->get('test', 'TestController::index');



// Ruta por defecto
$routes->get('/', 'AuthController::index');
