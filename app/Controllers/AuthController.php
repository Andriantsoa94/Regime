<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\SanteModel;

class AuthController extends BaseController
{
    public function index()
    {
        if (session()->get('user')) {
            return redirect()->to('/dashboard');
        }
        return view('auth/login');
    }

    public function login()
    {
        $model = new UserModel();
        $email = $this->request->getPost('email');
        $pass  = $this->request->getPost('pass');
        $user  = $model->where('email', $email)->first();

        $userPassword = $user['password'] ?? '';
        $passMatch = password_verify($pass, $userPassword) || hash_equals((string)$userPassword, (string)$pass);

        if (!$user || !$passMatch) {
            return redirect()->back()->with('error', 'Email ou mot de passe incorrect.')->withInput();
        }

        // Migrate plain text password to bcrypt
        if (!password_get_info($userPassword)['algo']) {
            $model->update($user['id'], ['password' => password_hash($pass, PASSWORD_DEFAULT)]);
        }

        // Determine role
        $role = $user['role'] ?? null;
        if (!$role) {
            $db = \Config\Database::connect();
            $roleRow = $db->query('SELECT label FROM role WHERE id = ?', [$user['role_id']])->getRowArray();
            $role = $roleRow['label'] ?? 'client';
        }

        session()->set('user', [
            'id'       => $user['id'],
            'nom'      => $user['nom'],
            'prenom'   => $user['prenom'] ?? '',
            'email'    => $user['email'],
            'genre'    => $user['genre'],
            'objectif' => $user['objectif'],
            'role'     => $role,
            'role_id'  => $user['role_id'],
            'is_gold'  => (bool)($user['is_gold'] ?? false),
            'solde'    => (float)($user['solde'] ?? 0),
        ]);

        if ($role === 'admin') {
            return redirect()->to('/admin/dashboard');
        }
        return redirect()->to('/dashboard');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/');
    }
}
