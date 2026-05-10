<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\SanteModel;
use App\Models\RegimeModel;
use App\Models\ActivityModel;

class Home extends BaseController
{
    public function index()
    {
        if (session()->get('user')) {
            return redirect()->to('/dashboard');
        }
        return view('front/accueil', ['title' => 'NutriPlan – Votre guide nutrition personnalisé']);
    }

    public function dashboard()
    {
        $userId      = session()->get('user')['id'];
        $userModel   = new UserModel();
        $santeModel  = new SanteModel();
        $regimeModel = new RegimeModel();
        $actModel    = new ActivityModel();

        $user  = $userModel->find($userId);
        $sante = $santeModel->where('user_id', $userId)->first();

        $imc      = null;
        $imcCat   = null;
        $imcColor = null;

        if ($sante && $sante['taille'] > 0) {
            $imc      = round($sante['poids'] / (($sante['taille'] / 100) ** 2), 1);
            $imcCat   = $this->imcCategorie($imc);
            $imcColor = $this->imcColor($imc);
        }

        $suggestions = [];
        $activites   = [];
        $objectif    = $user['objectif'] ?? null;

        if ($objectif) {
            $rawRegimes = $regimeModel->getForObjectif($objectif);
            $grouped    = [];
            foreach ($rawRegimes as $row) {
                $rid = $row['id'];
                if (!isset($grouped[$rid])) {
                    $grouped[$rid]             = $row;
                    $grouped[$rid]['prix_list'] = [];
                }
                $grouped[$rid]['prix_list'][] = [
                    'duree_jours' => $row['duree_jours'],
                    'prix'        => $row['prix'],
                ];
            }
            $suggestions = array_values($grouped);
            $activites   = $actModel->getForObjectif($objectif);
        }

        return view('front/dashboard', [
            'title'       => 'Tableau de Bord',
            'user'        => $user,
            'sante'       => $sante,
            'imc'         => $imc,
            'imcCat'      => $imcCat,
            'imcColor'    => $imcColor,
            'suggestions' => $suggestions,
            'activites'   => $activites,
        ]);
    }

    public function setObjectif()
    {
        $userId   = session()->get('user')['id'];
        $objectif = $this->request->getPost('objectif');

        if (!in_array($objectif, ['augmenter', 'reduire', 'imc_ideal'])) {
            return redirect()->back()->with('error', 'Objectif invalide.');
        }

        (new UserModel())->update($userId, ['objectif' => $objectif]);

        $userData             = session()->get('user');
        $userData['objectif'] = $objectif;
        session()->set('user', $userData);

        return redirect()->to('/dashboard')->with('success', 'Objectif mis à jour.');
    }

    private function imcCategorie(float $imc): string
    {
        if ($imc < 18.5) return 'Insuffisance pondérale';
        if ($imc < 25)   return 'Poids normal';
        if ($imc < 30)   return 'Surpoids';
        if ($imc < 35)   return 'Obésité modérée';
        return 'Obésité sévère';
    }

    private function imcColor(float $imc): string
    {
        if ($imc < 18.5) return 'info';
        if ($imc < 25)   return 'success';
        if ($imc < 30)   return 'warning';
        return 'danger';
    }
}
