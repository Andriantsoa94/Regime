<?php

namespace App\Models;

use CodeIgniter\Model;

class CodeWalletModel extends Model
{
    protected $table      = 'codes_wallet';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = ['code', 'montant', 'is_used', 'used_by', 'used_at', 'created_at'];

    public function findByCode(string $code): ?array
    {
        return $this->where('code', $code)->first();
    }

    public function useCode(int $id, int $userId): void
    {
        $this->update($id, ['is_used' => 1, 'used_by' => $userId, 'used_at' => date('Y-m-d H:i:s')]);
    }

    public function getStats(): array
    {
        $total     = $this->countAllResults();
        $used      = $this->where('is_used', 1)->countAllResults();
        $available = $total - $used;
        $totalAmount = $this->db->query('SELECT IFNULL(SUM(montant),0) as s FROM codes_wallet WHERE is_used=1')->getRow()->s ?? 0;
        return compact('total', 'used', 'available', 'totalAmount');
    }
}
