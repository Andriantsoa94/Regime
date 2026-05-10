<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\RegimeModel;
use App\Models\RegimePrixModel;

class RegimesController extends BaseController
{
    private RegimeModel $regimeModel;
    private RegimePrixModel $prixModel;

    public function __construct()
    {
        $this->regimeModel = new RegimeModel();
        $this->prixModel   = new RegimePrixModel();
    }

    public function index()
    {
        return view('admin/regimes/index', [
            'title'   => 'Gestion des Régimes',
            'regimes' => $this->regimeModel->getWithPrix(),
        ]);
    }

    public function create()
    {
        return view('admin/regimes/form', ['title' => 'Créer un Régime']);
    }

    public function store()
    {
        $rules = [
            'nom'          => 'required|min_length[3]',
            'pct_viande'   => 'required|numeric',
            'pct_poisson'  => 'required|numeric',
            'pct_volaille' => 'required|numeric',
            'pct_legumes'  => 'required|numeric',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->with('errors', $this->validator->getErrors())->withInput();
        }

        $typeObjectif = is_array($this->request->getPost('type_objectif'))
            ? implode(',', $this->request->getPost('type_objectif'))
            : ($this->request->getPost('type_objectif') ?? '');

        $regimeId = $this->regimeModel->insert([
            'nom'          => $this->request->getPost('nom'),
            'description'  => $this->request->getPost('description'),
            'pct_viande'   => $this->request->getPost('pct_viande'),
            'pct_poisson'  => $this->request->getPost('pct_poisson'),
            'pct_volaille' => $this->request->getPost('pct_volaille'),
            'pct_legumes'  => $this->request->getPost('pct_legumes'),
            'type_objectif'=> $typeObjectif,
            'actif'        => 1,
        ]);

        $durees = (array)$this->request->getPost('duree');
        $prix   = (array)$this->request->getPost('prix');
        foreach ($durees as $k => $duree) {
            if ($duree && isset($prix[$k]) && $prix[$k] !== '') {
                $this->prixModel->insert(['regime_id' => $regimeId, 'duree_jours' => (int)$duree, 'prix' => (float)$prix[$k]]);
            }
        }

        return redirect()->to('/admin/regimes')->with('success', 'Régime créé avec succès.');
    }

    public function edit(int $id)
    {
        $regime = $this->regimeModel->getOneWithPrix($id);
        if (!$regime) {
            return redirect()->to('/admin/regimes')->with('error', 'Régime introuvable.');
        }
        return view('admin/regimes/form', ['title' => 'Modifier le Régime', 'regime' => $regime]);
    }

    public function update(int $id)
    {
        $typeObjectif = is_array($this->request->getPost('type_objectif'))
            ? implode(',', $this->request->getPost('type_objectif'))
            : ($this->request->getPost('type_objectif') ?? '');

        $this->regimeModel->update($id, [
            'nom'          => $this->request->getPost('nom'),
            'description'  => $this->request->getPost('description'),
            'pct_viande'   => $this->request->getPost('pct_viande'),
            'pct_poisson'  => $this->request->getPost('pct_poisson'),
            'pct_volaille' => $this->request->getPost('pct_volaille'),
            'pct_legumes'  => $this->request->getPost('pct_legumes'),
            'type_objectif'=> $typeObjectif,
            'actif'        => $this->request->getPost('actif') ?? 1,
        ]);

        $this->prixModel->deleteByRegime($id);
        $durees = (array)$this->request->getPost('duree');
        $prix   = (array)$this->request->getPost('prix');
        foreach ($durees as $k => $duree) {
            if ($duree && isset($prix[$k]) && $prix[$k] !== '') {
                $this->prixModel->insert(['regime_id' => $id, 'duree_jours' => (int)$duree, 'prix' => (float)$prix[$k]]);
            }
        }

        return redirect()->to('/admin/regimes')->with('success', 'Régime mis à jour.');
    }

    public function delete(int $id)
    {
        $this->prixModel->deleteByRegime($id);
        $this->regimeModel->delete($id);
        return redirect()->to('/admin/regimes')->with('success', 'Régime supprimé.');
    }
}
