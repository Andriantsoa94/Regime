<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\SanteModel;

class RegisterController extends BaseController
{
    public function step1()
    {
        if (session()->get('user')) {
            return redirect()->to('/dashboard');
        }
        return view('auth/register_step1', ['title' => 'Inscription – Étape 1']);
    }

    public function doStep1()
    {
        $rules = [
            'nom'            => 'required|min_length[2]',
            'prenom'         => 'required|min_length[2]',
            'email'          => 'required|valid_email|is_unique[users.email]',
            'password'       => 'required|min_length[6]',
            'genre'          => 'required|in_list[homme,femme,autre]',
            'date_naissance' => 'required|valid_date',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->with('errors', $this->validator->getErrors())->withInput();
        }

        session()->set('register_step1', [
            'nom'            => $this->request->getPost('nom'),
            'prenom'         => $this->request->getPost('prenom'),
            'email'          => $this->request->getPost('email'),
            'password'       => $this->request->getPost('password'),
            'genre'          => $this->request->getPost('genre'),
            'date_naissance' => $this->request->getPost('date_naissance'),
        ]);

        return redirect()->to('/register/step2');
    }

    public function step2()
    {
        if (!session()->get('register_step1')) {
            return redirect()->to('/register');
        }
        return view('auth/register_step2', ['title' => 'Inscription – Étape 2']);
    }

    public function doStep2()
    {
        $step1 = session()->get('register_step1');
        if (!$step1) {
            return redirect()->to('/register');
        }

        $rules = [
            'taille'   => 'required|numeric|greater_than[50]|less_than[300]',
            'poids'    => 'required|numeric|greater_than[10]|less_than[500]',
            'objectif' => 'required|in_list[augmenter,reduire,imc_ideal]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->with('errors', $this->validator->getErrors())->withInput();
        }

        $userModel  = new UserModel();
        $santeModel = new SanteModel();

        $userId = $userModel->insert([
            'nom'            => $step1['nom'],
            'prenom'         => $step1['prenom'],
            'email'          => $step1['email'],
            'password'       => password_hash($step1['password'], PASSWORD_BCRYPT),
            'genre'          => $step1['genre'],
            'date_naissance' => $step1['date_naissance'],
            'objectif'       => $this->request->getPost('objectif'),
            'role_id'        => 3, // client
            'solde'          => 0,
            'is_gold'        => 0,
        ]);

        $santeModel->insert([
            'user_id' => $userId,
            'taille'  => $this->request->getPost('taille'),
            'poids'   => $this->request->getPost('poids'),
        ]);

        session()->remove('register_step1');

        session()->set('user', [
            'id'       => $userId,
            'nom'      => $step1['nom'],
            'prenom'   => $step1['prenom'],
            'email'    => $step1['email'],
            'genre'    => $step1['genre'],
            'objectif' => $this->request->getPost('objectif'),
            'role'     => 'client',
            'role_id'  => 3,
            'is_gold'  => false,
            'solde'    => 0.0,
        ]);

        return redirect()->to('/dashboard')->with('success', 'Bienvenue ! Votre compte a été créé avec succès.');
    }
}
