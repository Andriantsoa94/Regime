<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */


$routes->get('/', 'Home::index');
$routes->get('/home', 'Home::index');

$routes->get('/login',          'AuthController::index');
$routes->post('/login',         'AuthController::login');
$routes->post('/logout',        'AuthController::logout');
$routes->get('/register',       'RegisterController::step1');
$routes->post('/register',      'RegisterController::doStep1');
$routes->get('/register/step2', 'RegisterController::step2');
$routes->post('/register/step2','RegisterController::doStep2');

$routes->get('/admin/login',    'Admin\AuthController::index');
$routes->post('/admin/login',   'Admin\AuthController::login');
$routes->get('/admin/logout',   'Admin\AuthController::logout');

$routes->get('/dashboard',              'Home::dashboard',      ['filter' => 'auth']);
$routes->post('/dashboard/objectif',    'Home::setObjectif',    ['filter' => 'auth']);

$routes->get('/profile',                'Profile::index',       ['filter' => 'auth']);
$routes->post('/profile/update',        'Profile::update',      ['filter' => 'auth']);

$routes->get('/sante',                  'SanteController::index', ['filter' => 'auth']);
$routes->post('/sante',                 'SanteController::save',  ['filter' => 'auth']);

$routes->get('/wallet',                 'WalletController::index',    ['filter' => 'auth']);
$routes->post('/wallet/recharge',       'WalletController::recharge', ['filter' => 'auth']);
$routes->post('/wallet/gold',           'WalletController::buyGold',  ['filter' => 'auth']);

$routes->get('/regime/(:num)',          'RegimeController::detail/$1',    ['filter' => 'auth']);
$routes->post('/regime/subscribe',      'RegimeController::subscribe',     ['filter' => 'auth']);
$routes->get('/regime/(:num)/pdf',      'RegimeController::exportPdf/$1',  ['filter' => 'auth']);
$routes->get('/mes-regimes',            'RegimeController::mesRegimes',    ['filter' => 'auth']);

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
