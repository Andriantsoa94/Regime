<?php

use App\Controllers\AuthController;
use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('/home', 'Home::index');

// -----------------routes d'authentification utilisateurs-----------------

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

// ----------------- Routes admmin -----------------
$routes->get('/admin',                              'Admin\DashboardController::index',       ['filter' => 'admin']);
$routes->get('/admin/dashboard',                    'Admin\DashboardController::index',       ['filter' => 'admin']);

$routes->get('/admin/regimes',                      'Admin\RegimesController::index',         ['filter' => 'admin']);
$routes->get('/admin/regimes/create',               'Admin\RegimesController::create',        ['filter' => 'admin']);
$routes->post('/admin/regimes/store',               'Admin\RegimesController::store',         ['filter' => 'admin']);
$routes->get('/admin/regimes/edit/(:num)',           'Admin\RegimesController::edit/$1',       ['filter' => 'admin']);
$routes->post('/admin/regimes/update/(:num)',        'Admin\RegimesController::update/$1',     ['filter' => 'admin']);
$routes->post('/admin/regimes/delete/(:num)',        'Admin\RegimesController::delete/$1',     ['filter' => 'admin']);

$routes->get('/admin/activites',                    'Admin\ActivitesController::index',       ['filter' => 'admin']);
$routes->get('/admin/activites/create',             'Admin\ActivitesController::create',      ['filter' => 'admin']);
$routes->post('/admin/activites/store',             'Admin\ActivitesController::store',       ['filter' => 'admin']);
$routes->get('/admin/activites/edit/(:num)',         'Admin\ActivitesController::edit/$1',     ['filter' => 'admin']);
$routes->post('/admin/activites/update/(:num)',      'Admin\ActivitesController::update/$1',   ['filter' => 'admin']);
$routes->post('/admin/activites/delete/(:num)',      'Admin\ActivitesController::delete/$1',   ['filter' => 'admin']);

$routes->get('/admin/codes',                        'Admin\CodesController::index',           ['filter' => 'admin']);
$routes->post('/admin/codes/store',                 'Admin\CodesController::store',           ['filter' => 'admin']);
$routes->post('/admin/codes/purge',                 'Admin\CodesController::purge',           ['filter' => 'admin']);
$routes->post('/admin/codes/delete/(:num)',          'Admin\CodesController::delete/$1',       ['filter' => 'admin']);

$routes->get('/admin/users',                        'Admin\UsersController::index',           ['filter' => 'admin']);
$routes->post('/admin/users/toggle-gold/(:num)',     'Admin\UsersController::toggleGold/$1',   ['filter' => 'admin']);

$routes->get('/admin/parametres',                   'Admin\ParametresController::index',      ['filter' => 'admin']);
$routes->post('/admin/parametres/update',           'Admin\ParametresController::update',     ['filter' => 'admin']);

// ----------------- Routes front-office(regime et wallet) -----------------

$routes->get('/wallet',                 'WalletController::index',    ['filter' => 'auth']);
$routes->post('/wallet/recharge',       'WalletController::recharge', ['filter' => 'auth']);
$routes->post('/wallet/gold',           'WalletController::buyGold',  ['filter' => 'auth']);

$routes->get('/regime/(:num)',          'RegimeController::detail/$1',    ['filter' => 'auth']);
$routes->post('/regime/subscribe',      'RegimeController::subscribe',     ['filter' => 'auth']);
$routes->get('/regime/(:num)/pdf',      'RegimeController::exportPdf/$1',  ['filter' => 'auth']);
$routes->get('/mes-regimes',            'RegimeController::mesRegimes',    ['filter' => 'auth']);

$routes->get('/dashboard',              'Home::dashboard',      ['filter' => 'auth']);