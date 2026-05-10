<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\CodeWalletModel;

class WalletController extends BaseController
{
    private float $goldPrice = 29000;

    public function index()
    {
        $userId    = session()->get('user')['id'];
        $userModel = new UserModel();
        $user      = $userModel->find($userId);

        return view('front/wallet', [
            'title'      => 'Mon Portefeuille',
            'user'       => $user,
            'goldPrice'  => $this->goldPrice,
            'historique' => [],
        ]);
    }

    public function recharge()
    {
        $codeModel = new CodeWalletModel();
        $userModel = new UserModel();
        $userId    = session()->get('user')['id'];
        $code      = strtoupper(trim($this->request->getPost('code')));

        $codeData = $codeModel->findByCode($code);

        if (!$codeData) {
            return redirect()->back()->with('error', 'Code invalide ou inexistant.');
        }
        if ($codeData['is_used']) {
            return redirect()->back()->with('error', 'Ce code a déjà été utilisé.');
        }

        $codeModel->useCode($codeData['id'], $userId);

        $user      = $userModel->find($userId);
        $newSolde  = (float)$user['solde'] + (float)$codeData['montant'];
        $userModel->update($userId, ['solde' => $newSolde]);

        // Update session solde
        $sessionUser          = session()->get('user');
        $sessionUser['solde'] = $newSolde;
        session()->set('user', $sessionUser);

        return redirect()->back()->with('success', number_format($codeData['montant'], 0, ',', ' ') . ' Ar crédités sur votre portefeuille !');
    }

    public function buyGold()
    {
        $userId    = session()->get('user')['id'];
        $userModel = new UserModel();
        $user      = $userModel->find($userId);

        if ($user['is_gold']) {
            return redirect()->back()->with('info', 'Vous êtes déjà membre Gold.');
        }

        if ((float)$user['solde'] < $this->goldPrice) {
            return redirect()->back()->with('error', 'Solde insuffisant. Il vous faut ' . number_format($this->goldPrice, 0, ',', ' ') . ' Ar.');
        }

        $newSolde = (float)$user['solde'] - $this->goldPrice;
        $userModel->update($userId, [
            'is_gold'      => 1,
            'gold_paid_at' => date('Y-m-d H:i:s'),
            'solde'        => $newSolde,
        ]);

        $sessionUser            = session()->get('user');
        $sessionUser['is_gold'] = true;
        $sessionUser['solde']   = $newSolde;
        session()->set('user', $sessionUser);

        return redirect()->back()->with('success', 'Félicitations ! Vous êtes maintenant membre Gold avec 15% de remise.');
    }
}
