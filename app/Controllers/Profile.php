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
        $imcColor = 'secondary';
        $imcCat   = '';

        if ($sante) {
            $tailleMetre = $sante['taille'] / 100;
            $imcVal = $sante['poids'] / ($tailleMetre * $tailleMetre);
            $imc    = round($imcVal, 1);
            if ($imcVal < 18.5)      { $imcColor = 'warning'; $imcCat = 'Insuffisance pondérale'; }
            elseif ($imcVal < 25)    { $imcColor = 'success'; $imcCat = 'Poids normal'; }
            elseif ($imcVal < 30)    { $imcColor = 'warning'; $imcCat = 'Surpoids'; }
            else                      { $imcColor = 'danger';  $imcCat = 'Obésité'; }
        }

        return view('profile/profile', [
            'user' => $user,
            'sante' => $sante ?? [],
            'imc' => $imc,
            'imcColor' => $imcColor,
            'imcCat' => $imcCat
        ]);
    }

     public function update()
    {
        $userId = session()->get('user')['id'];

        $userModel  = new UserModel();
        $santeModel = new SanteModel();

        $updateData = [
            'prenom'         => $this->request->getPost('prenom'),
            'nom'            => $this->request->getPost('nom'),
            'email'          => $this->request->getPost('email'),
            'genre'          => $this->request->getPost('genre'),
            'objectif'       => $this->request->getPost('objectif'),
            'date_naissance' => $this->request->getPost('date_naissance') ?: null,
        ];

        $newPass = $this->request->getPost('password');
        $confirmPass = $this->request->getPost('confirm_pass');
        if ($newPass && $newPass === $confirmPass) {
            $updateData['password'] = password_hash($newPass, PASSWORD_BCRYPT);
        }

        $taille = $this->request->getPost('taille');
        $poids  = $this->request->getPost('poids');

        $userModel->update($userId, $updateData);

        // Update session
        $sessionUser = session()->get('user');
        $sessionUser['prenom']   = $updateData['prenom'];
        $sessionUser['nom']      = $updateData['nom'];
        $sessionUser['objectif'] = $updateData['objectif'];
        session()->set('user', $sessionUser);

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