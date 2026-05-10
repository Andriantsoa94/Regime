<?php

namespace App\Models;

use CodeIgniter\Model;

class ActivityModel extends Model
{
    protected $table      = 'activites';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = [
        'nom', 'description', 'calories_par_heure', 'intensite',
        'duree_recommandee', 'seances_par_semaine', 'type_objectif', 'actif',
    ];

    protected $useTimestamps = true;

    public function getForObjectif(string $objectif): array
    {
        if ($objectif === 'augmenter') {
            return $this->where('actif', 1)->where('intensite', 'intense')->findAll();
        }
        if ($objectif === 'reduire') {
            return $this->where('actif', 1)->whereIn('intensite', ['modere', 'intense'])->findAll();
        }
        return $this->where('actif', 1)->findAll();
    }
}
