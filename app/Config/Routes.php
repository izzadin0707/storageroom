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

$routes->group('category', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'Settings\CategoryController::index');
    $routes->post('save', 'Settings\CategoryController::save');
    $routes->post('delete', 'Settings\CategoryController::delete');
    $routes->post('table', 'Settings\CategoryController::datatable');
    $routes->post('select', 'Settings\CategoryController::select');
});

$routes->group('type', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'Settings\TypeController::index');
    $routes->post('save', 'Settings\TypeController::save');
    $routes->post('delete', 'Settings\TypeController::delete');
    $routes->post('table', 'Settings\TypeController::datatable');
    $routes->post('select', 'Settings\TypeController::select');
});

$routes->group('location', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'Main\LocationController::index');
    $routes->post('save', 'Main\LocationController::save');
    $routes->post('delete', 'Main\LocationController::delete');
    $routes->post('table', 'Main\LocationController::datatable');
    $routes->post('select', 'Main\LocationController::select');
});

$routes->group('product', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'Main\ProductController::index');
    $routes->post('save', 'Main\ProductController::save');
    $routes->post('delete', 'Main\ProductController::delete');
    $routes->post('table', 'Main\ProductController::datatable');
    $routes->post('select', 'Main\ProductController::select');
});

$routes->group('storage', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'Main\StorageController::index');
    $routes->post('save', 'Main\StorageController::save');
    $routes->post('delete', 'Main\StorageController::delete');
    $routes->post('table', 'Main\StorageController::datatable');
    $routes->post('detailtable', 'Main\StorageController::detailtable');
    $routes->post('select', 'Main\StorageController::select');
});

$routes->group('history', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'Main\HistoryController::index');
    $routes->post('table', 'Main\HistoryController::datatable');
});
