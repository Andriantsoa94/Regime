<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;

class ProfileController extends BaseController
{
    public function index()
    {
        // Vérifier que l'utilisateur est connecté
        $user = session()->get('user');
        if (!$user) {
            return redirect()->to('/login');
        }

        // Charger les données de l'utilisateur depuis la BD
        $model = new UserModel();
        $userData = $model->find($user['id']);

        return view('auth/profile', [
            'user' => $userData,
        ]);
    }

    public function update()
    {
        // Vérifier que l'utilisateur est connecté
        $user = session()->get('user');
        if (!$user) {
            return redirect()->to('/login');
        }

        $model = new UserModel();

        // Récupérer les données du formulaire
        $nom = $this->request->getPost('nom');
        $email = $this->request->getPost('email');
        $genre = $this->request->getPost('genre');
        $objectif = $this->request->getPost('objectif');
        $password = $this->request->getPost('password');

        // Préparer les données à mettre à jour
        $updateData = [
            'nom' => $nom,
            'email' => $email,
            'genre' => $genre,
            'objectif' => $objectif,
        ];

        // Si un nouveau mot de passe est fourni
        if (!empty($password)) {
            $updateData['password'] = password_hash($password, PASSWORD_DEFAULT);
        }

        // Mettre à jour dans la base de données
        if ($model->update($user['id'], $updateData)) {
            // Mettre à jour la session
            session()->set('user', [
                'id' => $user['id'],
                'nom' => $nom,
                'email' => $email,
                'genre' => $genre,
                'objectif' => $objectif,
                'role' => $user['role'],
            ]);

            return redirect()->to('/profile')->with('success', 'Profil mis à jour avec succès');
        } else {
            return redirect()->to('/profile')->with('error', 'Erreur lors de la mise à jour du profil');
        }
    }
}
