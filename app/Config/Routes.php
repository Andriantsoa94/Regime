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
$routes->post('/logout', 'AuthController::logout');

// -----------------routes ajoutees par Irina - aza misy manoratra ato aloha----------------------------------------------------------------

$routes->get('/profile', 'Profile::index');
$routes->post('/profile/update', 'Profile::update');

// -----------------------------------------------------------------------------------------------------------------------


$routes->get('/sante', 'SanteController::index', ['filter' => 'auth']);
$routes->post('/sante', 'SanteController::save', ['filter' => 'auth']);