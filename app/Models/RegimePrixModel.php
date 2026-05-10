<?php

namespace App\Models;

use CodeIgniter\Model;

class RegimePrixModel extends Model
{
    protected $table      = 'regime_prix';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = ['regime_id', 'duree_jours', 'prix'];

    public function getByRegime(int $id): array
    {
        return $this->where('regime_id', $id)->orderBy('duree_jours')->findAll();
    }

    public function deleteByRegime(int $id): void
    {
        $this->where('regime_id', $id)->delete();
    }

    public function getPrice(int $regimeId, int $duree): ?array
    {
        return $this->where('regime_id', $regimeId)->where('duree_jours', $duree)->first();
    }
}
