<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;

class AuthController extends BaseController
{
    public function index()
    {
        return view('auth/login');
    }

    public function login()
    {
        $model = new UserModel();
        $email = $this->request->getPost('email');
        $pass = $this->request->getPost('pass');
        $user = $model->where('email', $email)->first();
        $userPassword = $user['password'] ?? '';
        $passMatch = password_verify($pass, $userPassword) || hash_equals((String) $userPassword, (String) $pass);

        if (!$user || !$passMatch) {
            return view('auth/login', [
                'erreur' => 'auth refuser',
            ]);
        }
        if (!password_get_info($userPassword)['algo']) {
            $model->update($user['id'], ['password' => password_hash($pass, PASSWORD_DEFAULT)]);
        }

        $role = $user['role'] ?? ($user['role_id'] ?? null);
        session()->set('user', [
            'id' => $user['id'],
            'nom' => $user['nom'],
            'email' => $user['email'],
            'genre' => $user['genre'],
            'objectif' => $user['objectif'],
            'role' => $role,
        ]);

        return redirect()->to('/home');
    }

    public function logout() {
        return redirect()->to('/login');
        return redirect()->to('auth/login');
    }
}
