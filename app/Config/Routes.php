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
    $routes->get('/', 'UserController::index');
});
