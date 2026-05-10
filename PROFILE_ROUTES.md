// ============================================================
// ROUTES POUR LE PROFILE CONTROLLER - app/Config/Routes.php
// ============================================================

// À ajouter dans app/Config/Routes.php :

$routes->group('profile', ['filter' => 'auth'], function($routes) {
    // Afficher le profil
    $routes->get('', 'Profile::index');
    $routes->get('index', 'Profile::index');
    
    // Afficher et mettre à jour le profil
    $routes->get('edit', 'Profile::edit');
    $routes->post('update', 'Profile::update');
    
    // Gestion du mot de passe
    $routes->get('change-password', function() {
        return view('profile/change_password');
    });
    $routes->post('change-password', 'Profile::changePassword');
});

// API Routes (pour mobile/frontend)
$routes->group('api/profile', ['filter' => 'auth', 'namespace' => 'App\Controllers'], function($routes) {
    // Récupérer le profil en JSON
    $routes->get('get', 'Profile::apiGetProfile');
});

// Raccourcis
$routes->get('profile', 'Profile::index');
$routes->get('profile/edit', 'Profile::edit');
$routes->post('profile/update', 'Profile::update');
