<?php

namespace App\Models;

use CodeIgniter\Model;

class UserRegimeModel extends Model
{
    protected $table            = 'user_regimes';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'user_id',
        'regime_id',
        'duree_jours',
        'prix_paye',
        'date_debut',
        'date_fin',
        'statut',
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    /**
     * Récupérer tous les régimes d'un utilisateur
     * 
     * @param int $user_id L'ID de l'utilisateur
     * @param string $statut Filtrer par statut (optionnel): 'actif', 'termine', 'annule'
     * @return array Tableau des régimes de l'utilisateur avec détails
     */
    public function getUserRegimes($user_id, $statut = null)
    {
        $query = $this->select('user_regimes.*, regimes.nom, regimes.description, regimes.type_objectif')
                      ->join('regimes', 'regimes.id = user_regimes.regime_id', 'left')
                      ->where('user_regimes.user_id', $user_id)
                      ->orderBy('user_regimes.date_debut', 'DESC');

        if ($statut !== null) {
            $query->where('user_regimes.statut', $statut);
        }

        return $query->findAll();
    }

    /**
     * Récupérer les régimes actifs d'un utilisateur
     * 
     * @param int $user_id L'ID de l'utilisateur
     * @return array Tableau des régimes actifs
     */
    public function getActiveRegimes($user_id)
    {
        return $this->select('user_regimes.*, regimes.nom, regimes.description')
                    ->join('regimes', 'regimes.id = user_regimes.regime_id', 'left')
                    ->where('user_regimes.user_id', $user_id)
                    ->where('user_regimes.statut', 'actif')
                    ->findAll();
    }

    /**
     * Récupérer l'historique complet des régimes d'un utilisateur
     * 
     * @param int $user_id L'ID de l'utilisateur
     * @return array Tableau des régimes terminés ou annulés
     */
    public function getHistoriqueRegimes($user_id)
    {
        return $this->select('user_regimes.*, regimes.nom, regimes.description')
                    ->join('regimes', 'regimes.id = user_regimes.regime_id', 'left')
                    ->where('user_regimes.user_id', $user_id)
                    ->whereIn('user_regimes.statut', ['termine', 'annule'])
                    ->orderBy('user_regimes.date_fin', 'DESC')
                    ->findAll();
    }

    /**
     * Sauvegarder un régime acheté pour un utilisateur
     * 
     * @param int $user_id ID de l'utilisateur
     * @param int $regime_id ID du régime
     * @param int $duree_jours Durée du régime en jours
     * @param float $prix_paye Prix payé
     * @param string $date_debut Date de début (format: YYYY-MM-DD)
     * @param string $date_fin Date de fin (format: YYYY-MM-DD)
     * @return int|bool ID de l'enregistrement ou false si erreur
     */
    public function sauvegarderRegime($user_id, $regime_id, $duree_jours, $prix_paye, $date_debut, $date_fin)
    {
        $data = [
            'user_id'     => $user_id,
            'regime_id'   => $regime_id,
            'duree_jours' => $duree_jours,
            'prix_paye'   => $prix_paye,
            'date_debut'  => $date_debut,
            'date_fin'    => $date_fin,
            'statut'      => 'actif',
        ];

        return $this->insert($data);
    }

    /**
     * Mettre à jour le statut d'un régime
     * 
     * @param int $regime_user_id ID de la liaison user_regime
     * @param string $statut Nouveau statut: 'actif', 'termine', 'annule'
     * @return bool Succès de la mise à jour
     */
    public function updateStatut($regime_user_id, $statut)
    {
        return $this->update($regime_user_id, ['statut' => $statut]);
    }

    /**
     * Vérifier si un utilisateur a un régime actif
     * 
     * @param int $user_id ID de l'utilisateur
     * @return bool true si un régime actif existe
     */
    public function hasActiveRegime($user_id)
    {
        return $this->where('user_id', $user_id)
                    ->where('statut', 'actif')
                    ->countAllResults() > 0;
    }

    /**
     * Compter les régimes d'une catégorie pour un utilisateur
     * 
     * @param int $user_id ID de l'utilisateur
     * @param string $statut Statut à filtrer
     * @return int Nombre de régimes
     */
    public function countRegimes($user_id, $statut = null)
    {
        $query = $this->where('user_id', $user_id);
        
        if ($statut !== null) {
            $query->where('statut', $statut);
        }

        return $query->countAllResults();
    }

    /**
     * Récupérer le coût total des régimes d'un utilisateur
     * 
     * @param int $user_id ID de l'utilisateur
     * @return float Coût total
     */
    public function getCoutTotal($user_id)
    {
        $result = $this->selectSum('prix_paye')
                       ->where('user_id', $user_id)
                       ->first();

        return $result['prix_paye'] ?? 0;
    }

    /**
     * Récupérer un régime spécifique avec tous les détails
     * 
     * @param int $regime_user_id ID de la liaison user_regime
     * @return array|null Détails du régime
     */
    public function getRegimeDetails($regime_user_id)
    {
        return $this->select('user_regimes.*, regimes.nom, regimes.description, regimes.type_objectif, users.nom as user_nom, users.email')
                    ->join('regimes', 'regimes.id = user_regimes.regime_id', 'left')
                    ->join('users', 'users.id = user_regimes.user_id', 'left')
                    ->where('user_regimes.id', $regime_user_id)
                    ->first();
    }

    // ===== MÉTHODES UTILISANT LES VUES SQL =====

    /**
     * Récupérer tous les régimes d'un utilisateur via la vue complète
     * 
     * @param int $user_id ID de l'utilisateur
     * @return array Détails complets via view_user_regimes_details
     */
    public function getRegimesViaView($user_id)
    {
        $db = \Config\Database::connect();
        return $db->query("SELECT * FROM view_user_regimes_details WHERE user_id = ?", [$user_id])->getResultArray();
    }

    /**
     * Récupérer les régimes actifs d'un utilisateur via la vue
     * 
     * @param int $user_id ID de l'utilisateur
     * @return array Régimes actifs et non expirés
     */
    public function getActiveRegimesViaView($user_id)
    {
        $db = \Config\Database::connect();
        return $db->query("SELECT * FROM view_user_active_regimes WHERE user_id = ?", [$user_id])->getResultArray();
    }

    /**
     * Récupérer l'historique d'un utilisateur via la vue
     * 
     * @param int $user_id ID de l'utilisateur
     * @return array Régimes terminés ou annulés
     */
    public function getHistoriqueViaView($user_id)
    {
        $db = \Config\Database::connect();
        return $db->query("SELECT * FROM view_user_regime_history WHERE user_id = ?", [$user_id])->getResultArray();
    }

    /**
     * Récupérer les statistiques d'un utilisateur
     * 
     * @param int $user_id ID de l'utilisateur
     * @return array Statistiques complètes
     */
    public function getUserStatistics($user_id)
    {
        $db = \Config\Database::connect();
        return $db->query("SELECT * FROM view_user_regime_statistics WHERE id = ?", [$user_id])->getRow('array');
    }

    /**
     * Récupérer les régimes en cours d'expiration (7 jours)
     * 
     * @return array Régimes expirant bientôt de tous les utilisateurs
     */
    public function getExpiringRegimes()
    {
        $db = \Config\Database::connect();
        return $db->query("SELECT * FROM view_expiring_regimes")->getResultArray();
    }

    /**
     * Récupérer les régimes expirants pour un utilisateur spécifique
     * 
     * @param int $user_id ID de l'utilisateur
     * @return array Régimes expirant dans 7 jours
     */
    public function getUserExpiringRegimes($user_id)
    {
        $db = \Config\Database::connect();
        return $db->query("SELECT * FROM view_expiring_regimes WHERE user_id = ?", [$user_id])->getResultArray();
    }

    /**
     * Récupérer les régimes expirés (dépassant la date de fin)
     * 
     * @return array Tous les régimes expirés
     */
    public function getExpiredRegimes()
    {
        $db = \Config\Database::connect();
        return $db->query("SELECT * FROM view_expired_regimes")->getResultArray();
    }

    /**
     * Récupérer le dashboard complet d'un utilisateur
     * 
     * @param int $user_id ID de l'utilisateur
     * @return array Informations du tableau de bord personnel
     */
    public function getUserDashboard($user_id)
    {
        $db = \Config\Database::connect();
        return $db->query("SELECT * FROM view_user_dashboard WHERE id = ?", [$user_id])->getRow('array');
    }

    /**
     * Récupérer les régimes populaires (les plus vendus)
     * 
     * @param int $limit Nombre de régimes à retourner
     * @return array Les régimes les plus populaires
     */
    public function getPopularRegimes($limit = 10)
    {
        $db = \Config\Database::connect();
        return $db->query("SELECT * FROM view_regime_popularity LIMIT ?", [$limit])->getResultArray();
    }

    /**
     * Récupérer les dépenses mensuelles d'un utilisateur
     * 
     * @param int $user_id ID de l'utilisateur
     * @return array Dépenses par mois/année
     */
    public function getUserMonthlySpendings($user_id)
    {
        $db = \Config\Database::connect();
        return $db->query("SELECT * FROM view_user_monthly_spending WHERE id = ?", [$user_id])->getResultArray();
    }

    /**
     * Récupérer les régimes avec informations de prix
     * 
     * @param int $user_id ID de l'utilisateur
     * @return array Régimes avec détails de prix et éventuelles remises
     */
    public function getRegimesWithPricing($user_id)
    {
        $db = \Config\Database::connect();
        return $db->query("SELECT * FROM view_user_regimes_with_pricing WHERE user_id = ?", [$user_id])->getResultArray();
    }

    /**
     * Comparer le prix payé avec le prix catalogue
     * 
     * @param int $regime_user_id ID de la liaison user_regime
     * @return array Informations de prix et remise
     */
    public function getRegimePricingDetails($regime_user_id)
    {
        $db = \Config\Database::connect();
        return $db->query("SELECT * FROM view_user_regimes_with_pricing WHERE regime_user_id = ?", [$regime_user_id])->getRow('array');
    }

    /**
     * Récupérer toutes les statistiques de régimes (pour admin)
     * 
     * @return array Statistiques de tous les utilisateurs
     */
    public function getAllStatistics()
    {
        $db = \Config\Database::connect();
        return $db->query("SELECT * FROM view_user_regime_statistics ORDER BY total_regimes DESC")->getResultArray();
    }
}
