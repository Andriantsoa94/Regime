<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;

class UsersController extends BaseController
{
    public function index()
    {
        $db = \Config\Database::connect();
        $users = $db->query(
            'SELECT u.*, s.poids, s.taille FROM users u
             LEFT JOIN sante s ON s.user_id = u.id
             WHERE u.role_id != 1 ORDER BY u.created_at DESC'
        )->getResultArray();

        return view('admin/users/index', [
            'title' => 'Utilisateurs',
            'users' => $users,
        ]);
    }

    public function toggleGold(int $id)
    {
        $userModel = new UserModel();
        $user = $userModel->find($id);
        if (!$user) return redirect()->back()->with('error', 'Utilisateur introuvable.');

        $newGold = $user['is_gold'] ? 0 : 1;
        $userModel->update($id, [
            'is_gold'      => $newGold,
            'gold_paid_at' => $newGold ? date('Y-m-d H:i:s') : null,
        ]);

        return redirect()->back()->with('success', $newGold ? 'Statut Gold activé.' : 'Statut Gold désactivé.');
    }
}
