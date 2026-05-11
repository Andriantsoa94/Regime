<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;

class AuthController extends BaseController
{
    public function index()
    {
        $user = session()->get('user');
        if ($user && in_array($user['role'] ?? '', ['admin']) || ($user['role_id'] ?? 0) == 1) {
            return redirect()->to('/admin/dashboard');
        }
        return view('front/accueil', ['title' => 'Administration - Connexion']);
    }

    public function login()
    {
        $userModel = new UserModel();
        $email     = $this->request->getPost('email');
        $pass      = $this->request->getPost('password');

        $user = $userModel->where('email', $email)->where('role_id', 1)->first();

        if (!$user || !password_verify($pass, $user['password'])) {
            return redirect()->back()->with('error', 'Identifiants administrateur incorrects.')->withInput();
        }

        session()->set('user', [
            'id'      => $user['id'],
            'nom'     => $user['nom'],
            'prenom'  => $user['prenom'] ?? '',
            'email'   => $user['email'],
            'genre'   => $user['genre'],
            'objectif'=> $user['objectif'],
            'role'    => 'admin',
            'role_id' => 1,
            'is_gold' => false,
            'solde'   => 0.0,
        ]);

        return redirect()->to('/admin/dashboard');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('front/accueil');
    }
}
