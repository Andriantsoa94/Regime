<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\SanteModel;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * Controller Profile
 * Gère les profils utilisateurs
 */
class Profile extends BaseController
{
    protected UserModel $userModel;
    protected SanteModel $santeModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->santeModel = new SanteModel();
    }

    /**
     * Affiche le profil de l'utilisateur connecté
     * 
     * @return string Vue du profil
     */
    public function index()
    {
        // Vérifier que l'utilisateur est authentifié
        if (!auth()->loggedIn()) {
            return redirect()->to('/login');
        }

        $userId = auth()->id();

        try {
            // Récupérer l'utilisateur
            $user = $this->userModel->find($userId);
            if (!$user) {
                throw new \Exception('Utilisateur non trouvé');
            }

            // Récupérer les données de santé
            $sante = $this->santeModel
                        ->where('user_id', $userId)
                        ->first();

            // Calculer l'IMC si les données existent
            $imc = $this->calculateIMC($sante);

            // Déterminer la catégorie IMC
            $imcCategory = $this->getIMCCategory($imc);

            return view('profile/profile', [
                'user' => $user,
                'sante' => $sante,
                'imc' => $imc,
                'imc_category' => $imcCategory
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Erreur lors de l\'affichage du profil: ' . $e->getMessage());
            return redirect()->to('/dashboard')->with('error', 'Erreur lors du chargement du profil');
        }
    }

    /**
     * Affiche le formulaire d'édition du profil
     * 
     * @return string Vue du formulaire d'édition
     */
    public function edit()
    {
        if (!auth()->loggedIn()) {
            return redirect()->to('/login');
        }

        $userId = auth()->id();

        try {
            $user = $this->userModel->find($userId);
            $sante = $this->santeModel
                        ->where('user_id', $userId)
                        ->first();

            return view('profile/edit', [
                'user' => $user,
                'sante' => $sante
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Erreur lors de l\'ouverture du formulaire d\'édition: ' . $e->getMessage());
            return redirect()->to('/profile')->with('error', 'Erreur lors du chargement du formulaire');
        }
    }

    /**
     * Met à jour le profil de l'utilisateur
     * Valide et sauvegarde les données personnelles et de santé
     * 
     * @return ResponseInterface Redirection avec message
     */
    public function update()
    {
        if (!auth()->loggedIn()) {
            return redirect()->to('/login')->with('error', 'Vous devez être connecté');
        }

        $userId = auth()->id();

        // Récupérer les données POST
        $nom = trim($this->request->getPost('nom'));
        $prenom = trim($this->request->getPost('prenom'));
        $email = trim($this->request->getPost('email'));
        $genre = $this->request->getPost('genre');
        $objectif = trim($this->request->getPost('objectif') ?? '');
        $date_naissance = $this->request->getPost('date_naissance');

        $taille = $this->request->getPost('taille');
        $poids = $this->request->getPost('poids');

        // Validation des champs personnels
        $validationRules = [
            'nom' => 'required|min_length[2]|max_length[100]',
            'prenom' => 'required|min_length[2]|max_length[100]',
            'email' => 'required|valid_email|max_length[100]',
            'genre' => 'required|in_list[homme,femme,autre]',
            'date_naissance' => 'permit_empty|valid_date[Y-m-d]',
        ];

        if (!$this->validate($validationRules)) {
            return redirect()->back()
                           ->withInput()
                           ->with('errors', $this->validator->getErrors());
        }

        // Validation des champs de santé (si fournis)
        $santeRules = [
            'taille' => 'permit_empty|numeric|greater_than[50]|less_than[300]',
            'poids' => 'permit_empty|numeric|greater_than[20]|less_than[300]',
        ];

        if (!$this->validate($santeRules)) {
            return redirect()->back()
                           ->withInput()
                           ->with('errors', $this->validator->getErrors());
        }

        try {
            // Vérifier que l'email n'existe pas ailleurs
            $existingUser = $this->userModel
                                ->where('email', $email)
                                ->where('id !=', $userId)
                                ->first();

            if ($existingUser) {
                return redirect()->back()
                               ->withInput()
                               ->with('error', 'Cet email est déjà utilisé par un autre compte');
            }

            // Mettre à jour les infos utilisateur
            $userUpdateData = [
                'nom' => $nom,
                'email' => $email,
                'genre' => $genre,
                'objectif' => $objectif,
            ];

            // Ajouter prénom et date si fournis
            if ($prenom) {
                $userUpdateData['prenom'] = $prenom;
            }
            if ($date_naissance) {
                $userUpdateData['date_naissance'] = $date_naissance;
            }

            $this->userModel->update($userId, $userUpdateData);

            // Mettre à jour ou créer les données de santé
            if ($taille || $poids) {
                $sante = $this->santeModel
                            ->where('user_id', $userId)
                            ->first();

                $santeData = [];
                if ($taille) $santeData['taille'] = $taille;
                if ($poids) $santeData['poids'] = $poids;

                if ($sante) {
                    $this->santeModel->update($sante['id'], $santeData);
                } else {
                    $santeData['user_id'] = $userId;
                    $this->santeModel->insert($santeData);
                }
            }

            // Mettre à jour la session avec les nouvelles données
            $updatedUser = $this->userModel->find($userId);
            if ($updatedUser) {
                session()->set('user', $updatedUser);
            }

            return redirect()->to('/profile')
                           ->with('success', 'Profil mis à jour avec succès');
        } catch (\Exception $e) {
            log_message('error', 'Erreur lors de la mise à jour du profil: ' . $e->getMessage());
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Erreur lors de la mise à jour du profil');
        }
    }

    /**
     * Change le mot de passe de l'utilisateur
     * 
     * @return ResponseInterface Redirection avec message
     */
    public function changePassword()
    {
        if (!auth()->loggedIn()) {
            return redirect()->to('/login');
        }

        // Validation des mots de passe
        $rules = [
            'current_password' => 'required|min_length[6]',
            'new_password' => 'required|min_length[8]|differs[current_password]',
            'confirm_password' => 'required|matches[new_password]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->with('errors', $this->validator->getErrors());
        }

        $userId = auth()->id();
        $currentPassword = $this->request->getPost('current_password');
        $newPassword = $this->request->getPost('new_password');

        try {
            $user = $this->userModel->find($userId);

            // Vérifier le mot de passe actuel
            if (!password_verify($currentPassword, $user['password'])) {
                return redirect()->back()->with('error', 'Le mot de passe actuel est incorrect');
            }

            // Mettre à jour le mot de passe
            $this->userModel->update($userId, [
                'password' => password_hash($newPassword, PASSWORD_BCRYPT)
            ]);

            return redirect()->to('/profile')
                           ->with('success', 'Mot de passe modifié avec succès');
        } catch (\Exception $e) {
            log_message('error', 'Erreur lors du changement de mot de passe: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Erreur lors du changement de mot de passe');
        }
    }

    /**
     * Calcule l'IMC (Indice de Masse Corporelle)
     * IMC = poids (kg) / (taille (m))^2
     * 
     * @param array|null $sante Données de santé
     * @return float|null IMC ou null
     */
    protected function calculateIMC($sante)
    {
        if (!$sante || !$sante['taille'] || !$sante['poids']) {
            return null;
        }

        $tailleMetre = $sante['taille'] / 100;
        return round($sante['poids'] / ($tailleMetre * $tailleMetre), 2);
    }

    /**
     * Détermine la catégorie IMC
     * 
     * @param float|null $imc Indice de masse corporelle
     * @return string|null Catégorie IMC
     */
    protected function getIMCCategory($imc)
    {
        if ($imc === null) {
            return null;
        }

        if ($imc < 18.5) {
            return 'insuffisant';
        } elseif ($imc < 25) {
            return 'normal';
        } elseif ($imc < 30) {
            return 'surpoids';
        } elseif ($imc < 35) {
            return 'obese_classe1';
        } elseif ($imc < 40) {
            return 'obese_classe2';
        } else {
            return 'obese_classe3';
        }
    }

    /**
     * API: Récupère les données du profil en JSON
     * 
     * @return ResponseInterface JSON response
     */
    public function apiGetProfile()
    {
        if (!auth()->loggedIn()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Non authentifié'
            ])->setStatusCode(401);
        }

        $userId = auth()->id();

        try {
            $user = $this->userModel->find($userId);
            $sante = $this->santeModel->where('user_id', $userId)->first();
            $imc = $this->calculateIMC($sante);

            return $this->response->setJSON([
                'success' => true,
                'data' => [
                    'user' => $user,
                    'sante' => $sante,
                    'imc' => $imc,
                ]
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Erreur lors du chargement du profil'
            ])->setStatusCode(500);
        }
    }
}