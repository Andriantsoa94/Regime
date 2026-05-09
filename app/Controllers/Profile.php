<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\SanteModel;

class Profile extends BaseController
{
    public function index()
    {
        if(!session()->has('user'))
        {
            return redirect()->to('/login');
        }

        $userId = session()->get('user')['id'];

        $userModel = new UserModel();
        $santeModel = new SanteModel();

        $user = $userModel->find($userId);

        $sante = $santeModel
                    ->where('user_id', $userId)
                    ->first();

        $imc = null;

        if($sante)
        {
            $tailleMetre = $sante['taille'] / 100;

            $imc = $sante['poids'] / ($tailleMetre * $tailleMetre);
        }

        return view('profile/profile', [
            'user' => $user,
            'sante' => $sante,
            'imc' => $imc
        ]);
    }

    public function update()
    {
        $userId = session()->get('user')['id'];

        $nom = $this->request->getPost('nom');
        $email = $this->request->getPost('email');
        $genre = $this->request->getPost('genre');
        $objectif = $this->request->getPost('objectif');

        $taille = $this->request->getPost('taille');
        $poids = $this->request->getPost('poids');

        $userModel = new UserModel();
        $santeModel = new SanteModel();

        $userModel->update($userId, [
            'nom' => $nom,
            'email' => $email,
            'genre' => $genre,
            'objectif' => $objectif
        ]);

        $sante = $santeModel
                    ->where('user_id', $userId)
                    ->first();

        if($sante)
        {
            $santeModel->update($sante['id'], [
                'taille' => $taille,
                'poids' => $poids
            ]);
        }
        else
        {
            $santeModel->insert([
                'user_id' => $userId,
                'taille' => $taille,
                'poids' => $poids
            ]);
        }

        return redirect()->to('/profile');
    }
}