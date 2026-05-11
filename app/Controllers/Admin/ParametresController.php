<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class ParametresController extends BaseController
{
    public function index()
    {
        return view('admin/parametres/index', [
            'title'  => 'Paramètres',
            'params' => [
                'gold_price'    => session()->get('gold_price') ?? 29000,
                'gold_discount' => session()->get('gold_discount') ?? 15,
            ],
        ]);
    }

    public function update()
    {
        // Parameters stored in session for simplicity
        $goldPrice = (float)$this->request->getPost('gold_price');
        if ($goldPrice <= 0) {
            return redirect()->back()->with('error', 'Prix Gold invalide.');
        }
        $goldDiscount = (int)$this->request->getPost('gold_discount');
        session()->set('gold_price', $goldPrice);
        session()->set('gold_discount', $goldDiscount ?: 15);
        return redirect()->back()->with('success', 'Paramètres mis à jour.');
    }
}
