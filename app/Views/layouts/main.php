<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Rezim') ?> | Rezim</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root { --primary: #6f2da8; --primary-light: #9b59d0; }
        body { background: #f5f6fa; min-height: 100vh; }
        .navbar { background: #fff; box-shadow: 0 2px 10px rgba(0,0,0,.07); }
        .navbar-brand { font-weight: 800; font-size: 1.4rem; color: var(--primary) !important; }
        .nav-link { color: #555 !important; font-weight: 500; }
        .nav-link:hover, .nav-link.active { color: var(--primary) !important; }
        .btn-primary { background: var(--primary); border-color: var(--primary); }
        .btn-primary:hover { background: var(--primary-light); border-color: var(--primary-light); }
        .card { border: none; border-radius: 14px; box-shadow: 0 2px 14px rgba(0,0,0,.06); }
        .card-header { background: var(--primary); color: #fff; border-radius: 14px 14px 0 0 !important; font-weight: 600; }
        .gold-badge { background: linear-gradient(135deg,#f0b429,#e08a00); color:#fff; padding:2px 10px; border-radius:20px; font-size:.75rem; font-weight:700; }
        footer { background:#fff; border-top:1px solid #eee; padding:1rem; text-align:center; color:#aaa; font-size:.83rem; margin-top:3rem; }
        .alert { border-radius: 10px; }
        .imc-circle { width:110px;height:110px;border-radius:50%;display:flex;flex-direction:column;align-items:center;justify-content:center;margin:0 auto;font-weight:800; }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg mb-4">
    <div class="container">
        <a class="navbar-brand" href="/dashboard">
            <i class="bi bi-heart-pulse-fill me-1" style="color:var(--primary)"></i>Rezim
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMain">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navMain">
            <ul class="navbar-nav me-auto gap-1">
                <li class="nav-item"><a class="nav-link" href="/dashboard"><i class="bi bi-speedometer2 me-1"></i>Tableau de bord</a></li>
                <li class="nav-item"><a class="nav-link" href="/mes-regimes"><i class="bi bi-journal-check me-1"></i>Mes Régimes</a></li>
                <li class="nav-item"><a class="nav-link" href="/profile"><i class="bi bi-person-circle me-1"></i>Profil</a></li>
                <li class="nav-item"><a class="nav-link" href="/wallet"><i class="bi bi-wallet2 me-1"></i>Portefeuille</a></li>
            </ul>
            <ul class="navbar-nav align-items-center gap-2">
                <?php if (session()->get('user')['is_gold'] ?? false): ?>
                    <li class="nav-item"><span class="gold-badge"><i class="bi bi-star-fill me-1"></i>Gold</span></li>
                <?php endif; ?>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                        <i class="bi bi-person-fill me-1"></i>
                        <?= esc((session()->get('user')['prenom'] ?? '') . ' ' . (session()->get('user')['nom'] ?? '')) ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="/profile"><i class="bi bi-gear me-2"></i>Mon profil</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="/logout" class="d-inline">
                                <?= csrf_field() ?>
                                <button class="dropdown-item text-danger"><i class="bi bi-box-arrow-right me-2"></i>Déconnexion</button>
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container pb-5">
    <?php foreach (['success'=>'success','error'=>'danger','info'=>'info','warning'=>'warning'] as $flash=>$bsColor): ?>
        <?php if ($msg = session()->getFlashdata($flash)): ?>
            <div class="alert alert-<?= $bsColor ?> alert-dismissible fade show">
                <i class="bi bi-<?= $bsColor==='success'?'check-circle':'exclamation-triangle' ?> me-2"></i><?= esc($msg) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
    <?php endforeach; ?>
    <?php if ($errors = session()->getFlashdata('errors')): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <ul class="mb-0 ps-3">
                <?php foreach ((array)$errors as $e): ?><li><?= esc($e) ?></li><?php endforeach; ?>
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?= $this->renderSection('content') ?>
</div>

<footer>&copy; <?= date('Y') ?> Rezim &mdash; ETU003885 ETU003940 ETU004320</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<?= $this->renderSection('scripts') ?>
</body>
</html>
