<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;
use CodeIgniter\HTTP\ResponseInterface;
use Config\View;

class AuthController extends BaseController
{
    public function index()
    {
        return View('auth/login');
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
                'erreur' => 'auth refuser'
            ]);
        }
        if (!password_get_info($userPassword)['algo']) {
            $model->update($user['id'], ['password' => password_hash($pass, PASSWORD_DEFAULT)]);
        }

        session()->set(
            'user',
            [
                'id' => $user['id'],
                'nom' => $user['password'],
                'genre' => $user['genre'],
                'objectif'=> $user['objectif']
            ]
        );

        return redirect()->to('/accueil');
    }

    public function logout() {
        session()->destroy();
        return redirect()->to('auth/login');
    }
}
