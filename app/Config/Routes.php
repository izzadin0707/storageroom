<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/login', 'LoginController::index', ['filter' => 'noauth']);
$routes->post('/login-process', 'LoginController::login', ['filter' => 'noauth']);
$routes->post('/logout-process', 'LoginController::logout', ['filter' => 'auth']);

$routes->get('/', 'Home::index');

$routes->group('users', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'Settings\UserController::index');
    $routes->post('table', 'Settings\UserController::datatable');
    $routes->post('save', 'Settings\UserController::save');
    $routes->post('delete', 'Settings\UserController::delete');
});

$routes->group('role', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'Settings\RoleController::index');
    $routes->post('save', 'Settings\RoleController::save');
    $routes->post('delete', 'Settings\RoleController::delete');
    $routes->post('table', 'Settings\RoleController::datatable');
    $routes->post('select', 'Settings\RoleController::select');
});
