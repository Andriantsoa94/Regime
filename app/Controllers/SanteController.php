<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\SanteModel;

class SanteController extends BaseController
{
    public function index()
    {
        $user = session()->get('user');
        if (!$user) {
            return redirect()->to('/login');
        }

        $model = new SanteModel();
        $sante = $model->where('user_id', $user['id'])->first();

        return view('sante/form', [
            'sante' => $sante,
            'user' => $user,
        ]);
    }

    public function save()
    {
        $user = session()->get('user');
        if (!$user) {
            return redirect()->to('/login');
        }

        $rules = [
            'taille' => 'required|numeric',
            'poids' => 'required|numeric',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'user_id' => $user['id'],
            'taille' => (float) $this->request->getPost('taille'),
            'poids' => (float) $this->request->getPost('poids'),
        ];

        $model = new SanteModel();
        $existing = $model->where('user_id', $user['id'])->first();
        if ($existing) {
            $model->update($existing['id'], $data);
        } else {
            $model->insert($data);
        }

        return redirect()->to('/sante')->with('success', 'Infos sante enregistrees.');
    }
}
