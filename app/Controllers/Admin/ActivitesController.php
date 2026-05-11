<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ActivityModel;

class ActivitesController extends BaseController
{
    private ActivityModel $model;

    public function __construct()
    {
        $this->model = new ActivityModel();
    }

    public function index()
    {
        return view('admin/activites/index', ['title' => 'Activités Sportives', 'activites' => $this->model->findAll()]);
    }

    public function create()
    {
        return view('admin/activites/form', ['title' => 'Ajouter une Activité']);
    }

    public function store()
    {
        $rules = [
            'nom'                => 'required|min_length[3]',
            'calories_par_heure' => 'required|numeric',
            'intensite'          => 'required|in_list[faible,modere,intense]',
            'duree_recommandee'  => 'required|integer',
            'seances_par_semaine'=> 'required|integer',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->with('errors', $this->validator->getErrors())->withInput();
        }

        $typeObjectif = is_array($this->request->getPost('type_objectif'))
            ? implode(',', $this->request->getPost('type_objectif'))
            : ($this->request->getPost('type_objectif') ?? '');

        $this->model->insert([
            'nom'                => $this->request->getPost('nom'),
            'description'        => $this->request->getPost('description'),
            'calories_par_heure' => $this->request->getPost('calories_par_heure'),
            'intensite'          => $this->request->getPost('intensite'),
            'duree_recommandee'  => $this->request->getPost('duree_recommandee'),
            'seances_par_semaine'=> $this->request->getPost('seances_par_semaine'),
            'type_objectif'      => $typeObjectif,
            'actif'              => 1,
        ]);

        return redirect()->to('/admin/activites')->with('success', 'Activité ajoutée.');
    }

    public function edit(int $id)
    {
        $activite = $this->model->find($id);
        if (!$activite) return redirect()->to('/admin/activites')->with('error', 'Introuvable.');
        return view('admin/activites/form', ['title' => 'Modifier l\'Activité', 'activite' => $activite]);
    }

    public function update(int $id)
    {
        $typeObjectif = is_array($this->request->getPost('type_objectif'))
            ? implode(',', $this->request->getPost('type_objectif'))
            : ($this->request->getPost('type_objectif') ?? '');

        $this->model->update($id, [
            'nom'                => $this->request->getPost('nom'),
            'description'        => $this->request->getPost('description'),
            'calories_par_heure' => $this->request->getPost('calories_par_heure'),
            'intensite'          => $this->request->getPost('intensite'),
            'duree_recommandee'  => $this->request->getPost('duree_recommandee'),
            'seances_par_semaine'=> $this->request->getPost('seances_par_semaine'),
            'type_objectif'      => $typeObjectif,
            'actif'              => $this->request->getPost('actif') ?? 1,
        ]);
        return redirect()->to('/admin/activites')->with('success', 'Activité mise à jour.');
    }

    public function delete(int $id)
    {
        $this->model->delete($id);
        return redirect()->to('/admin/activites')->with('success', 'Activité supprimée.');
    }
}
