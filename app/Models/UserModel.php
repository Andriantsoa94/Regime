<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'nom',
        'prenom',
        'email',
        'genre',
        'password',
        'role_id',
        'objectif',
        'date_naissance',
        'is_gold',
        'gold_paid_at',
        'solde',
        'created_at',
        'updated_at',
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
    protected $validationRules      = [
        'nom' => 'required|min_length[2]|max_length[100]',
        'prenom' => 'permit_empty|min_length[2]|max_length[100]',
        'email' => 'required|valid_email|is_unique[users.email,id,{id}]',
        'genre' => 'permit_empty|in_list[homme,femme,autre]',
        'password' => 'required|min_length[8]',
        'objectif' => 'permit_empty|max_length[500]',
        'date_naissance' => 'permit_empty|valid_date[Y-m-d]',
    ];
    protected $validationMessages   = [
        'email' => [
            'is_unique' => 'Cet email est déjà enregistré',
            'valid_email' => 'Veuillez entrer une adresse email valide',
        ],
        'password' => [
            'min_length' => 'Le mot de passe doit contenir au moins 8 caractères',
        ],
    ];
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
     * Retrouver un utilisateur par email
     * 
     * @param string $email Email de l'utilisateur
     * @return array|null Données utilisateur ou null
     */
    public function getUserByEmail($email)
    {
        return $this->where('email', $email)->first();
    }

    /**
     * Vérifier si un email existe
     * 
     * @param string $email Email à vérifier
     * @param int|null $excludeId ID à exclure (pour les updates)
     * @return bool true si l'email existe
     */
    public function emailExists($email, $excludeId = null)
    {
        $query = $this->where('email', $email);
        if ($excludeId) {
            $query->where('id !=', $excludeId);
        }
        return $query->countAllResults() > 0;
    }

    /**
     * Récupérer un utilisateur avec ses données de santé
     * 
     * @param int $userId ID de l'utilisateur
     * @return array|null Utilisateur avec joiture santé
     */
    public function getUserWithSante($userId)
    {
        $db = \Config\Database::connect();
        return $db->query(
            "SELECT u.*, s.taille, s.poids 
             FROM users u 
             LEFT JOIN sante s ON s.user_id = u.id 
             WHERE u.id = ?",
            [$userId]
        )->getRow('array');
    }

    /**
     * Mettre à jour le mot de passe d'un utilisateur
     * 
     * @param int $userId ID de l'utilisateur
     * @param string $newPassword Nouveau mot de passe (en clair)
     * @return bool Succès ou échec
     */
    public function updatePassword($userId, $newPassword)
    {
        return $this->update($userId, [
            'password' => password_hash($newPassword, PASSWORD_BCRYPT)
        ]);
    }

    /**
     * Vérifier le mot de passe d'un utilisateur
     * 
     * @param int $userId ID de l'utilisateur
     * @param string $password Mot de passe à vérifier
     * @return bool true si le mot de passe est correct
     */
    public function verifyPassword($userId, $password)
    {
        $user = $this->find($userId);
        if (!$user) {
            return false;
        }
        return password_verify($password, $user['password']);
    }

    /**
     * Récupérer les statistiques d'un utilisateur
     * 
     * @param int $userId ID de l'utilisateur
     * @return array Statistiques (régimes, dépenses, etc.)
     */
    public function getUserStats($userId)
    {
        $db = \Config\Database::connect();
        return $db->query(
            "SELECT 
                COUNT(ur.id) as total_regimes,
                SUM(CASE WHEN ur.statut = 'actif' THEN 1 ELSE 0 END) as regimes_actifs,
                SUM(ur.prix_paye) as total_depense
             FROM users u
             LEFT JOIN user_regimes ur ON ur.user_id = u.id
             WHERE u.id = ?",
            [$userId]
        )->getRow('array');
    }

    /**
     * Récupérer les utilisateurs avec filtre
     * 
     * @param string|null $genre Filtrer par genre
     * @param int|null $roleId Filtrer par rôle
     * @return array Tableau d'utilisateurs
     */
    public function getFiltered($genre = null, $roleId = null)
    {
        $query = $this;

        if ($genre) {
            $query = $query->where('genre', $genre);
        }

        if ($roleId) {
            $query = $query->where('role_id', $roleId);
        }

        return $query->findAll();
    }
}
}
