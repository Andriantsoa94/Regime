<?php

namespace App\Models;

use CodeIgniter\Model;

class RegimeModel extends Model
{
    protected $table            = 'regimes';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'nom', 'description', 'pct_viande', 'pct_poisson', 'pct_volaille',
        'pct_legumes', 'variation_min', 'variation_max', 'type_objectif', 'actif',
    ];

    public function getWithPrix(): array
    {
        $regimes = $this->findAll();
        foreach ($regimes as &$r) {
            $r['prix_list'] = $this->db->query(
                'SELECT * FROM regime_prix WHERE regime_id = ? ORDER BY duree_jours', [$r['id']]
            )->getResultArray();
        }
        return $regimes;
    }

    public function getForObjectif(string $objectif): array
    {
        return $this->db->query(
            'SELECT r.*, rp.duree_jours, rp.prix
            FROM regimes r
            JOIN regime_prix rp ON rp.regime_id = r.id
            WHERE r.actif = 1 AND FIND_IN_SET(?, r.type_objectif)
            ORDER BY r.id, rp.duree_jours',
            [$objectif]
        )->getResultArray();
    }

    public function getOneWithPrix(int $id): ?array
    {
        $regime = $this->find($id);
        if (!$regime) return null;

        $regime['prix_list'] = $this->db->query(
            'SELECT * FROM regime_prix WHERE regime_id = ? ORDER BY duree_jours',
            [$id]
        )->getResultArray();

        return $regime;
    }

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = false;
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
}
