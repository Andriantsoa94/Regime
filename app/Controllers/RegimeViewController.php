<?php

namespace App\Controllers;

use App\Models\UserRegimeModel;
use CodeIgniter\HTTP\ResponseInterface;

class RegimeViewController extends BaseController
{
    protected UserRegimeModel $userRegimeModel;

    public function __construct()
    {
        $this->userRegimeModel = new UserRegimeModel();
    }

    /**
     * Tableau de bord personnel de l'utilisateur
     * Affiche: régimes actifs, prochaine expiration, historique
     */
    public function dashboard()
    {
        $userId = auth()->user()->id;

        $data = [
            'dashboard'        => $this->userRegimeModel->getUserDashboard($userId),
            'active_regimes'   => $this->userRegimeModel->getActiveRegimesViaView($userId),
            'expiring_soon'    => $this->userRegimeModel->getUserExpiringRegimes($userId),
            'statistics'       => $this->userRegimeModel->getUserStatistics($userId),
        ];

        return view('regime/dashboard', $data);
    }

    /**
     * Liste complète des régimes d'un utilisateur
     * Avec filtrage par statut si nécessaire
     */
    public function myRegimes()
    {
        $userId = auth()->user()->id;

        $data = [
            'all_regimes'   => $this->userRegimeModel->getRegimesViaView($userId),
            'active'        => $this->userRegimeModel->getActiveRegimesViaView($userId),
            'history'       => $this->userRegimeModel->getHistoriqueViaView($userId),
        ];

        return view('regime/my_regimes', $data);
    }

    /**
     * Historique détaillé avec informations de prix
     * Pour afficher ce que l'utilisateur a payé et les éventuelles remises
     */
    public function history()
    {
        $userId = auth()->user()->id;

        $data = [
            'history' => $this->userRegimeModel->getRegimesWithPricing($userId),
            'spending' => $this->userRegimeModel->getUserMonthlySpendings($userId),
            'total_cost' => $this->userRegimeModel->getCoutTotal($userId),
        ];

        return view('regime/history', $data);
    }

    /**
     * Détails d'un régime spécifique
     */
    public function detail($regimeUserId)
    {
        $userId = auth()->user()->id;
        
        $regime = $this->userRegimeModel->getRegimePricingDetails($regimeUserId);

        // Vérifier que l'utilisateur accède à son propre régime
        if (!$regime || $regime['user_id'] != $userId) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        return view('regime/detail_regime', ['regime' => $regime]);
    }

    /**
     * ADMIN: Régimes en cours d'expiration (7 jours max)
     * Utile pour envoyer des notifications si nécessaire
     */
    public function adminExpiringRegimes()
    {
        if (!auth()->user() || auth()->user()->role_id != 1) {
            return $this->response->setStatusCode(403);
        }

        $data = [
            'expiring' => $this->userRegimeModel->getExpiringRegimes(),
            'expired'  => $this->userRegimeModel->getExpiredRegimes(),
        ];

        return view('admin/regimes_expiring', $data);
    }

    /**
     * ADMIN: Régimes populaires
     * Top 10 des régimes les plus vendus avec statistiques
     */
    public function adminPopularRegimes()
    {
        if (!auth()->user() || auth()->user()->role_id != 1) {
            return $this->response->setStatusCode(403);
        }

        $data = [
            'popular' => $this->userRegimeModel->getPopularRegimes(10),
        ];

        return view('admin/popular_regimes', $data);
    }

    /**
     * ADMIN: Statistiques globales de tous les utilisateurs
     */
    public function adminAllStatistics()
    {
        if (!auth()->user() || auth()->user()->role_id != 1) {
            return $this->response->setStatusCode(403);
        }

        $data = [
            'statistics' => $this->userRegimeModel->getAllStatistics(),
        ];

        return view('admin/all_statistics', $data);
    }

    /**
     * API: Récupérer les régimes actifs en JSON
     */
    public function apiGetActiveRegimes()
    {
        $userId = auth()->user()->id;
        $regimes = $this->userRegimeModel->getActiveRegimesViaView($userId);

        return $this->response->setJSON([
            'success' => true,
            'data' => $regimes,
            'count' => count($regimes),
        ]);
    }

    /**
     * API: Récupérer le dashboard en JSON
     */
    public function apiGetDashboard()
    {
        $userId = auth()->user()->id;

        return $this->response->setJSON([
            'success' => true,
            'dashboard' => $this->userRegimeModel->getUserDashboard($userId),
            'statistics' => $this->userRegimeModel->getUserStatistics($userId),
        ]);
    }

    /**
     * API: Vérifier les régimes proches de l'expiration
     */
    public function apiCheckExpiring()
    {
        $userId = auth()->user()->id;
        $expiring = $this->userRegimeModel->getUserExpiringRegimes($userId);

        if (empty($expiring)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Aucun régime n\'expire bientôt',
                'expiring' => [],
            ]);
        }

        return $this->response->setJSON([
            'success' => true,
            'warning' => true,
            'message' => count($expiring) . ' régime(s) expirent dans 7 jours',
            'expiring' => $expiring,
        ]);
    }

    /**
     * Achat d'un régime - Enregistrer via la vue et le modèle
     */
    public function purchaseRegime()
    {
        $userId = auth()->user()->id;
        
        $regimeId = $this->request->getPost('regime_id');
        $dureeJours = $this->request->getPost('duree_jours');
        $prixPaye = $this->request->getPost('prix_paye');

        // Calculer les dates
        $dateDebut = date('Y-m-d');
        $dateFin = date('Y-m-d', strtotime("+{$dureeJours} days"));

        try {
            $this->userRegimeModel->sauvegarderRegime(
                $userId,
                $regimeId,
                $dureeJours,
                $prixPaye,
                $dateDebut,
                $dateFin
            );

            return redirect()->to('/regime/my-regimes')
                           ->with('success', 'Régime acheté avec succès!');
        } catch (\Exception $e) {
            return redirect()->back()
                           ->with('error', 'Erreur lors de l\'achat du régime');
        }
    }

    /**
     * Afficher les faits pour le contrôle qualité
     */
    public function testViews()
    {
        $userId = 1; // Pour tester

        $output = "
        <h1>Test des Vues SQL</h1>
        
        <h2>1. getUserDashboard(1)</h2>
        <pre>" . json_encode($this->userRegimeModel->getUserDashboard($userId), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "</pre>
        
        <h2>2. getActiveRegimesViaView(1)</h2>
        <pre>" . json_encode($this->userRegimeModel->getActiveRegimesViaView($userId), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "</pre>
        
        <h2>3. getUserStatistics(1)</h2>
        <pre>" . json_encode($this->userRegimeModel->getUserStatistics($userId), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "</pre>
        
        <h2>4. getPopularRegimes(5)</h2>
        <pre>" . json_encode($this->userRegimeModel->getPopularRegimes(5), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "</pre>
        
        <h2>5. getExpiringRegimes()</h2>
        <pre>" . json_encode($this->userRegimeModel->getExpiringRegimes(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "</pre>
        ";

        return $output;
    }
}
