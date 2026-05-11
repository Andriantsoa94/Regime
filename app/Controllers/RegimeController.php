<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\SanteModel;
use App\Models\RegimeModel;
use App\Models\RegimePrixModel;
use App\Models\ActivityModel;
use App\Models\UserRegimeModel;

class RegimeController extends BaseController
{
    public function detail(int $id)
    {
        $regimeModel = new RegimeModel();
        $userModel   = new UserModel();
        $actModel    = new ActivityModel();

        $regime = $regimeModel->getOneWithPrix($id);
        if (!$regime || !$regime['actif']) {
            return redirect()->to('/dashboard')->with('error', 'Régime introuvable.');
        }

        $userId = session()->get('user')['id'];
        $user   = $userModel->find($userId);

        return view('front/regime_detail', [
            'title'    => $regime['nom'],
            'regime'   => $regime,
            'user'     => $user,
            'activites'=> $actModel->getForObjectif($user['objectif'] ?? 'imc_ideal'),
        ]);
    }

    public function subscribe()
    {
        $userId          = session()->get('user')['id'];
        $userModel       = new UserModel();
        $prixModel       = new RegimePrixModel();
        $userRegimeModel = new UserRegimeModel();

        $regimeId = (int)$this->request->getPost('regime_id');
        $duree    = (int)$this->request->getPost('duree_jours');

        $prixRow = $prixModel->getPrice($regimeId, $duree);
        if (!$prixRow) {
            return redirect()->back()->with('error', 'Option de durée invalide.');
        }

        $prix = (float)$prixRow['prix'];
        $user = $userModel->find($userId);

        if ($user['is_gold']) {
            $prix = $prix * 0.85;
        }

        if ((float)$user['solde'] < $prix) {
            return redirect()->to('/wallet')->with('error', 'Solde insuffisant. Rechargez votre portefeuille.');
        }

        $newSolde = (float)$user['solde'] - $prix;
        $userModel->update($userId, ['solde' => $newSolde]);

        $userRegimeModel->insert([
            'user_id'    => $userId,
            'regime_id'  => $regimeId,
            'duree_jours'=> $duree,
            'prix_paye'  => $prix,
            'date_debut' => date('Y-m-d'),
            'date_fin'   => date('Y-m-d', strtotime("+{$duree} days")),
            'statut'     => 'actif',
        ]);

        $sessionUser          = session()->get('user');
        $sessionUser['solde'] = $newSolde;
        session()->set('user', $sessionUser);

        return redirect()->to('/mes-regimes')->with('success', 'Souscription au régime enregistrée avec succès.');
    }

    public function mesRegimes()
    {
        $userId          = session()->get('user')['id'];
        $userModel       = new UserModel();
        $userRegimeModel = new UserRegimeModel();

        return view('front/mes_regimes', [
            'title'   => 'Mes Régimes',
            'user'    => $userModel->find($userId),
            'regimes' => $userRegimeModel->getByUser($userId),
        ]);
    }

    public function exportPdf(int $regimeId)
    {
        $userId     = session()->get('user')['id'];
        $userModel  = new UserModel();
        $santeModel = new SanteModel();
        $actModel   = new ActivityModel();

        $db  = \Config\Database::connect();
        $sub = $db->query(
            'SELECT r.*, r.nom as regime_nom
             FROM regimes r WHERE r.id = ?',
            [$regimeId]
        )->getRowArray();

        if (!$sub) {
            return redirect()->back()->with('error', 'Régime introuvable.');
        }
        // Fill missing keys so the PDF generation code below works unchanged
        $sub['date_debut']  = date('Y-m-d');
        $sub['date_fin']    = date('Y-m-d', strtotime('+30 days'));
        $sub['duree_jours'] = 30;
        $sub['prix_paye']   = 0;
        $sub['variation_min'] = 0;
        $sub['variation_max'] = 0;

        $user      = $userModel->find($userId);
        $sante     = $santeModel->where('user_id', $userId)->first();
        $activites = $actModel->getForObjectif($user['objectif'] ?? 'imc_ideal');

        $imc    = null;
        $imcCat = '';
        if ($sante && $sante['taille'] > 0) {
            $imc    = round($sante['poids'] / (($sante['taille'] / 100) ** 2), 1);
            $imcCat = $this->imcCategorie($imc);
        }

        // Use FPDF (already in project at fpdf186/)
        require_once ROOTPATH . 'fpdf186/fpdf.php';

        $pdf = new \FPDF();
        $pdf->AddPage();
        $pdf->SetFont('Helvetica', 'B', 18);
        $pdf->SetFillColor(111, 45, 168);
        $pdf->SetTextColor(255, 255, 255);
        $pdf->Cell(0, 14, 'Plan de Regime Alimentaire', 0, 1, 'C', true);

        $pdf->SetFont('Helvetica', '', 9);
        $pdf->SetTextColor(150, 150, 150);
        $pdf->Cell(0, 6, 'NutriPlan - Projet S4 ITU - Genere le ' . date('d/m/Y'), 0, 1, 'C');
        $pdf->Ln(4);

        // Patient info
        $pdf->SetFont('Helvetica', 'B', 12);
        $pdf->SetTextColor(111, 45, 168);
        $pdf->Cell(0, 8, 'Informations du patient', 0, 1);
        $pdf->SetDrawColor(111, 45, 168);
        $pdf->Line(10, $pdf->GetY(), 200, $pdf->GetY());
        $pdf->Ln(2);

        $pdf->SetFont('Helvetica', '', 10);
        $pdf->SetTextColor(50, 50, 50);
        $prenom = $user['prenom'] ?? '';
        $pdf->Cell(95, 7, 'Nom : ' . $prenom . ' ' . $user['nom'], 1, 0, 'L', false);
        $pdf->Cell(95, 7, 'Genre : ' . ucfirst($user['genre']), 1, 1, 'L', false);

        if ($sante) {
            $pdf->Cell(95, 7, 'Taille : ' . $sante['taille'] . ' cm', 1, 0, 'L', false);
            $pdf->Cell(95, 7, 'Poids : ' . $sante['poids'] . ' kg', 1, 1, 'L', false);
        }
        if ($imc) {
            $pdf->Cell(95, 7, 'IMC : ' . $imc, 1, 0, 'L', false);
            $pdf->Cell(95, 7, 'Categorie : ' . $imcCat, 1, 1, 'L', false);
        }
        $pdf->Ln(4);

        // Regime info
        $pdf->SetFont('Helvetica', 'B', 12);
        $pdf->SetTextColor(111, 45, 168);
        $pdf->Cell(0, 8, 'Regime : ' . $sub['regime_nom'], 0, 1);
        $pdf->Line(10, $pdf->GetY(), 200, $pdf->GetY());
        $pdf->Ln(2);

        $pdf->SetFont('Helvetica', '', 10);
        $pdf->SetTextColor(50, 50, 50);
        $pdf->Cell(63, 7, 'Debut : ' . date('d/m/Y', strtotime($sub['date_debut'])), 1, 0);
        $pdf->Cell(63, 7, 'Fin : ' . date('d/m/Y', strtotime($sub['date_fin'])), 1, 0);
        $pdf->Cell(64, 7, 'Duree : ' . $sub['duree_jours'] . ' jours', 1, 1);
        $pdf->Cell(95, 7, 'Prix paye : ' . number_format($sub['prix_paye'], 0, ',', ' ') . ' Ar', 1, 0);
        $pdf->Cell(95, 7, 'Variation : ' . $sub['variation_min'] . ' / +' . $sub['variation_max'] . ' kg', 1, 1);
        $pdf->Ln(4);

        // Composition
        $pdf->SetFont('Helvetica', 'B', 12);
        $pdf->SetTextColor(111, 45, 168);
        $pdf->Cell(0, 8, 'Composition alimentaire', 0, 1);
        $pdf->Line(10, $pdf->GetY(), 200, $pdf->GetY());
        $pdf->Ln(2);

        $pdf->SetFont('Helvetica', 'B', 10);
        $pdf->SetTextColor(255, 255, 255);
        $pdf->SetFillColor(80, 80, 80);
        $pdf->Cell(47, 7, 'Viande', 1, 0, 'C', true);
        $pdf->Cell(47, 7, 'Poisson', 1, 0, 'C', true);
        $pdf->Cell(47, 7, 'Volaille', 1, 0, 'C', true);
        $pdf->Cell(49, 7, 'Legumes/Autres', 1, 1, 'C', true);

        $pdf->SetFont('Helvetica', 'B', 12);
        $pdf->SetTextColor(50, 50, 50);
        $pdf->SetFillColor(240, 240, 240);
        $pdf->Cell(47, 10, $sub['pct_viande'] . '%', 1, 0, 'C', true);
        $pdf->Cell(47, 10, $sub['pct_poisson'] . '%', 1, 0, 'C', true);
        $pdf->Cell(47, 10, $sub['pct_volaille'] . '%', 1, 0, 'C', true);
        $pdf->Cell(49, 10, $sub['pct_legumes'] . '%', 1, 1, 'C', true);
        $pdf->Ln(4);

        // Activites
        if (!empty($activites)) {
            $pdf->SetFont('Helvetica', 'B', 12);
            $pdf->SetTextColor(111, 45, 168);
            $pdf->Cell(0, 8, 'Activites sportives recommandees', 0, 1);
            $pdf->Line(10, $pdf->GetY(), 200, $pdf->GetY());
            $pdf->Ln(2);

            $pdf->SetFont('Helvetica', 'B', 9);
            $pdf->SetTextColor(255, 255, 255);
            $pdf->SetFillColor(80, 80, 80);
            $pdf->Cell(60, 7, 'Activite', 1, 0, 'C', true);
            $pdf->Cell(30, 7, 'Intensite', 1, 0, 'C', true);
            $pdf->Cell(32, 7, 'Duree/seance', 1, 0, 'C', true);
            $pdf->Cell(32, 7, 'Seances/sem', 1, 0, 'C', true);
            $pdf->Cell(36, 7, 'Cal/heure', 1, 1, 'C', true);

            $pdf->SetFont('Helvetica', '', 9);
            $pdf->SetTextColor(50, 50, 50);
            foreach ($activites as $a) {
                $pdf->Cell(60, 6, $a['nom'], 1, 0);
                $pdf->Cell(30, 6, ucfirst($a['intensite']), 1, 0, 'C');
                $pdf->Cell(32, 6, $a['duree_recommandee'] . ' min', 1, 0, 'C');
                $pdf->Cell(32, 6, $a['seances_par_semaine'] . 'x', 1, 0, 'C');
                $pdf->Cell(36, 6, $a['calories_par_heure'] . ' kcal', 1, 1, 'C');
            }
        }

        // Footer
        $pdf->Ln(8);
        $pdf->SetFont('Helvetica', 'I', 8);
        $pdf->SetTextColor(150, 150, 150);
        $pdf->Cell(0, 5, 'NutriPlan - Projet S4 ITU - ' . date('d/m/Y H:i'), 0, 0, 'C');

        $pdf->Output('D', 'plan-regime-' . preg_replace('/[^a-z0-9]/i', '-', $sub['regime_nom']) . '.pdf');
        exit;
    }

    private function imcCategorie(float $imc): string
    {
        if ($imc < 18.5) return 'Insuffisance ponderale';
        if ($imc < 25)   return 'Poids normal';
        if ($imc < 30)   return 'Surpoids';
        if ($imc < 35)   return 'Obesite moderee';
        return 'Obesite severe';
    }
}
