<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\RegimeModel;
use App\Models\ActivityModel;
use App\Models\CodeWalletModel;
use App\Models\UserRegimeModel;

class DashboardController extends BaseController
{
    public function index()
    {
        $userModel       = new UserModel();
        $regimeModel     = new RegimeModel();
        $actModel        = new ActivityModel();
        $codeModel       = new CodeWalletModel();
        $userRegimeModel = new UserRegimeModel();

        $db = \Config\Database::connect();

        $totalUsers  = $userModel->where('role_id !=', 1)->countAllResults();
        $goldUsers   = $userModel->where('is_gold', 1)->countAllResults();
        $hommes      = $userModel->where('genre', 'homme')->where('role_id !=', 1)->countAllResults();
        $femmes      = $userModel->where('genre', 'femme')->where('role_id !=', 1)->countAllResults();
        $newMonth    = $userModel->where('MONTH(created_at)', date('m'))
                                 ->where('YEAR(created_at)', date('Y'))
                                 ->where('role_id !=', 1)->countAllResults();

        $totalRegimes   = $regimeModel->countAllResults();
        $totalActivites = $actModel->countAllResults();
        $codeStats      = $codeModel->getStats();

        $totalSouscriptions = $userRegimeModel->countAllResults();
        $activesSouscriptions = $userRegimeModel->where('statut', 'actif')->countAllResults();
        $revenue = $db->query('SELECT IFNULL(SUM(prix_paye),0) as s FROM user_regimes')->getRow()->s ?? 0;

        $monthlyRevenue = array_fill(0, 12, 0);
        $monthlyData = $db->query(
            'SELECT MONTH(created_at) as mois, SUM(prix_paye) as total
             FROM user_regimes WHERE YEAR(created_at) = ? GROUP BY MONTH(created_at)',
            [date('Y')]
        )->getResultArray();
        foreach ($monthlyData as $m) {
            $monthlyRevenue[(int)$m['mois'] - 1] = (float)$m['total'];
        }

        $objectifStats = $db->query(
            'SELECT objectif, COUNT(*) as total FROM users
             WHERE role_id != 1 AND objectif IS NOT NULL GROUP BY objectif'
        )->getResultArray();

        return view('admin/dashboard', [
            'title'               => 'Tableau de Bord',
            'totalUsers'          => $totalUsers,
            'goldUsers'           => $goldUsers,
            'hommes'              => $hommes,
            'femmes'              => $femmes,
            'newMonth'            => $newMonth,
            'totalRegimes'        => $totalRegimes,
            'totalActivites'      => $totalActivites,
            'codeStats'           => $codeStats,
            'totalSouscriptions'  => $totalSouscriptions,
            'activesSouscriptions'=> $activesSouscriptions,
            'revenue'             => $revenue,
            'monthlyRevenue'      => $monthlyRevenue,
            'objectifStats'       => $objectifStats,
        ]);
    }
}
