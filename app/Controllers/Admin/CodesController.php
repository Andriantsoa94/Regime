<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\CodeWalletModel;

class CodesController extends BaseController
{
    private CodeWalletModel $model;

    public function __construct()
    {
        $this->model = new CodeWalletModel();
    }

    public function index()
    {
        $codes = \Config\Database::connect()->query(
            'SELECT c.*, u.nom, u.prenom FROM codes_wallet c
             LEFT JOIN users u ON u.id = c.used_by
             ORDER BY c.created_at DESC'
        )->getResultArray();

        return view('admin/codes/index', [
            'title' => 'Codes Portefeuille',
            'codes' => $codes,
            'stats' => $this->model->getStats(),
        ]);
    }

    public function store()
    {
        $montant = (float)$this->request->getPost('montant');
        $qty     = min((int)$this->request->getPost('quantite'), 50);

        if ($montant <= 0) {
            return redirect()->back()->with('error', 'Montant invalide.');
        }

        for ($i = 0; $i < $qty; $i++) {
            $code = 'CODE-' . strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 4))
                           . '-' . strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 4));
            $this->model->insert(['code' => $code, 'montant' => $montant, 'created_at' => date('Y-m-d H:i:s')]);
        }

        return redirect()->back()->with('success', $qty . ' code(s) généré(s) avec succès.');
    }

    public function purge()
    {
        $this->model->where('is_used', 1)->delete();
        return redirect()->back()->with('success', 'Codes utilisés supprimés.');
    }

    public function delete(int $id)
    {
        $code = $this->model->find($id);
        if ($code && $code['is_used']) {
            return redirect()->back()->with('error', 'Impossible de supprimer un code déjà utilisé.');
        }
        $this->model->delete($id);
        return redirect()->back()->with('success', 'Code supprimé.');
    }
}
