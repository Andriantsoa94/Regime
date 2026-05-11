<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Admin') ?> | Rezim Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root { --primary:#6f2da8; --sidebar-w:230px; }
        body { background:#f4f6f9; margin:0; }
        #sidebar { position:fixed;top:0;left:0;height:100vh;width:var(--sidebar-w);
            background:linear-gradient(180deg,#3d1070 0%,#6f2da8 100%);color:#fff;z-index:100;overflow-y:auto; }
        #sidebar .brand { padding:1.25rem 1rem;font-size:1.1rem;font-weight:800;border-bottom:1px solid rgba(255,255,255,.12); }
        .nav-sect { padding:.4rem 1rem;font-size:.68rem;text-transform:uppercase;color:rgba(255,255,255,.45);margin-top:.8rem; }
        #sidebar a { display:flex;align-items:center;gap:.55rem;padding:.55rem 1rem;color:rgba(255,255,255,.8);
            text-decoration:none;border-radius:8px;margin:2px .5rem;transition:.15s; }
        #sidebar a:hover,#sidebar a.active { background:rgba(255,255,255,.15);color:#fff; }
        #main { margin-left:var(--sidebar-w); }
        #topbar { background:#fff;padding:.7rem 1.4rem;box-shadow:0 1px 6px rgba(0,0,0,.07);display:flex;align-items:center;justify-content:space-between; }
        #content { padding:1.4rem; }
        .stat-card { background:#fff;border-radius:12px;padding:1.1rem;box-shadow:0 2px 10px rgba(0,0,0,.06); }
        .stat-icon { width:46px;height:46px;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:1.35rem; }
        .card { border:none;border-radius:12px;box-shadow:0 2px 10px rgba(0,0,0,.06); }
        .card-header { background:#fff;border-bottom:1px solid #f0f0f0;font-weight:600;border-radius:12px 12px 0 0 !important; }
        .btn-primary { background:var(--primary);border-color:var(--primary); }
        .table th { font-size:.78rem;text-transform:uppercase;color:#999;font-weight:700; }
    </style>
</head>
<body>
<div id="sidebar">
    <div class="brand"><i class="bi bi-heart-pulse-fill me-2"></i>Rezim<div style="font-size:.68rem;opacity:.6;font-weight:400">Administration</div></div>
    <div class="nav-sect">Navigation</div>
    <a href="/admin/dashboard" class="<?= strpos(current_url(),'dashboard')!==false?'active':'' ?>"><i class="bi bi-speedometer2"></i>Tableau de bord</a>
    <div class="nav-sect">Gestion</div>
    <a href="/admin/regimes"   class="<?= strpos(current_url(),'regimes')!==false?'active':'' ?>"><i class="bi bi-journal-medical"></i>Régimes</a>
    <a href="/admin/activites" class="<?= strpos(current_url(),'activites')!==false?'active':'' ?>"><i class="bi bi-bicycle"></i>Activités Sportives</a>
    <a href="/admin/codes"     class="<?= strpos(current_url(),'codes')!==false?'active':'' ?>"><i class="bi bi-qr-code"></i>Codes Portefeuille</a>
    <a href="/admin/users"     class="<?= strpos(current_url(),'users')!==false?'active':'' ?>"><i class="bi bi-people"></i>Utilisateurs</a>
    <div class="nav-sect">Système</div>
    <a href="/admin/parametres" class="<?= strpos(current_url(),'parametres')!==false?'active':'' ?>"><i class="bi bi-gear"></i>Paramètres</a>
    <a href="/admin/logout" style="color:rgba(255,100,100,.85);margin-top:.5rem"><i class="bi bi-box-arrow-left"></i>Déconnexion</a>
</div>

<div id="main">
    <div id="topbar">
        <h6 class="mb-0 fw-bold"><?= esc($title ?? '') ?></h6>
        <span class="text-muted small"><i class="bi bi-person-circle me-1"></i><?= esc((session()->get('user')['prenom'] ?? '') . ' ' . (session()->get('user')['nom'] ?? '')) ?></span>
    </div>
    <div id="content">
        <?php foreach (['success'=>'success','error'=>'danger','info'=>'info'] as $flash=>$bsColor): ?>
            <?php if ($msg = session()->getFlashdata($flash)): ?>
                <div class="alert alert-<?= $bsColor ?> alert-dismissible fade show">
                    <?= esc($msg) ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
        <?php if ($errors = session()->getFlashdata('errors')): ?>
            <div class="alert alert-danger"><ul class="mb-0 ps-3"><?php foreach ((array)$errors as $e): ?><li><?= esc($e) ?></li><?php endforeach; ?></ul></div>
        <?php endif; ?>

        <?= $this->renderSection('content') ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<?= $this->renderSection('scripts') ?>
</body>
</html>
