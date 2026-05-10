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

// -----------------routes ajoutees par Irina - aza misy manoratra ato aloha----------------------------------------------------------------

// Profile - Profil utilisateur
$routes->get('/profile', 'ProfileController::index', ['filter' => 'auth']);
$routes->get('/profile/edit', 'ProfileController::edit', ['filter' => 'auth']);
$routes->post('/profile/update', 'ProfileController::update', ['filter' => 'auth']);
$routes->get('/profile/change-password', 'ProfileController::changePassword', ['filter' => 'auth']);
$routes->post('/profile/change-password', 'ProfileController::changePassword', ['filter' => 'auth']);

// API Profile
$routes->get('/api/profile/get', 'ProfileController::apiGetProfile', ['filter' => 'auth']);

// -----------------------------------------------------------------------------------------------------------------------


$routes->get('/sante', 'SanteController::index', ['filter' => 'auth']);
$routes->post('/sante', 'SanteController::save', ['filter' => 'auth']);