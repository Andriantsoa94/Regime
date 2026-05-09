<?php

use App\Controllers\AuthController;
use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('/home', 'Home::index');

$routes->get('/login', 'AuthController::index');
$routes->post('/login', 'AuthController::login');
$routes->get('/inscription', 'AuthController::register');
$routes->post('/inscrire', 'AuthController::store');
$routes->post('/logout', 'AuthController::logout');
$routes->get('/sante', 'SanteController::index', ['filter' => 'auth']);
$routes->post('/sante', 'SanteController::save', ['filter' => 'auth']);