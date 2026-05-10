<?php

use App\Controllers\AuthController;
use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('/home', 'Home::index');
$routes->get('/admin', 'Home::admin', ['filter' => 'role:1,admin']);

$routes->get('/login', 'AuthController::index');
$routes->post('/login', 'AuthController::login');
$routes->get('/inscription', 'AuthController::register');
$routes->post('/inscrire', 'AuthController::store');
$routes->post('/logout', 'AuthController::logout');

// -----------------routes ajoutees par Irina - aza misy manoratra ato aloha----------------------------------------------------------------

$routes->get('/profile', 'ProfileController::index', ['filter' => 'auth']);
$routes->post('/profile/update', 'ProfileController::update', ['filter' => 'auth']);

// -----------------------------------------------------------------------------------------------------------------------


$routes->get('/sante', 'SanteController::index', ['filter' => 'auth']);
$routes->post('/sante', 'SanteController::save', ['filter' => 'auth']);