// ============================================================
// ROUTES POUR LES VUES SQL - app/Config/Routes.php
// ============================================================

// À ajouter dans app/Config/Routes.php :

$routes->group('regime', ['filter' => 'auth'], function($routes) {
    // Tableau de bord personnel
    $routes->get('dashboard', 'RegimeViewController::dashboard');
    
    // Liste des régimes de l'utilisateur
    $routes->get('my-regimes', 'RegimeViewController::myRegimes');
    
    // Historique avec détails de prix
    $routes->get('history', 'RegimeViewController::history');
    
    // Détail d'un régime specific
    $routes->get('detail/:num', 'RegimeViewController::detail/$1');
    
    // Achat d'un régime
    $routes->post('purchase', 'RegimeViewController::purchaseRegime');
});

// Routes ADMIN
$routes->group('admin/regime', ['filter' => 'auth', 'filter' => 'role:admin'], function($routes) {
    $routes->get('expiring', 'RegimeViewController::adminExpiringRegimes');
    $routes->get('popular', 'RegimeViewController::adminPopularRegimes');
    $routes->get('statistics', 'RegimeViewController::adminAllStatistics');
});

// API Routes (pour mobile/frontend)
$routes->group('api/regime', ['filter' => 'auth', 'namespace' => 'App\Controllers'], function($routes) {
    $routes->get('active', 'RegimeViewController::apiGetActiveRegimes');
    $routes->get('dashboard', 'RegimeViewController::apiGetDashboard');
    $routes->get('check-expiring', 'RegimeViewController::apiCheckExpiring');
});

// Route de test (à supprimer en production)
$routes->get('test/views', 'RegimeViewController::testViews');
