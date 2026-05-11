<?php

namespace App\Models;

use CodeIgniter\Model;

class UserRegimeModel extends Model
{
    protected $table      = 'user_regimes';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = [
        'user_id', 'regime_id', 'duree_jours', 'prix_paye',
        'date_debut', 'date_fin', 'statut',
    ];

    public function getByUser(int $userId): array
    {
        return $this->db->query(
            'SELECT ur.*, r.nom as regime_nom, r.description, r.type_objectif,
                    r.pct_viande, r.pct_poisson, r.pct_volaille, r.pct_legumes,
                    r.variation_min, r.variation_max
             FROM user_regimes ur
             JOIN regimes r ON r.id = ur.regime_id
             WHERE ur.user_id = ?
             ORDER BY ur.created_at DESC',
            [$userId]
        )->getResultArray();
    }
}
