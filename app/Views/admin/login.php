<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion Admin | NutriPlan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body{background:linear-gradient(135deg,#1a0040,#3d1070 55%,#6f2da8);min-height:100vh;display:flex;align-items:center;}
        .auth-card{border:none;border-radius:18px;box-shadow:0 20px 60px rgba(0,0,0,.4);}
        .btn-primary{background:#6f2da8;border-color:#6f2da8;}
        .btn-primary:hover{background:#4a1a7a;border-color:#4a1a7a;}
        .form-control:focus{border-color:#6f2da8;box-shadow:0 0 0 .2rem rgba(111,45,168,.2);}
        .shield{font-size:3rem;color:#6f2da8;}
    </style>
</head>
<body>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-sm-10 col-md-6 col-lg-4">
            <div class="card auth-card p-4">
                <div class="text-center mb-4">
                    <i class="bi bi-shield-lock-fill shield"></i>
                    <div style="font-size:1.5rem;font-weight:800;color:#6f2da8" class="mt-1">Administration</div>
                    <p class="text-muted small mt-1">NutriPlan — Accès réservé</p>
                </div>

                <?php if ($error = session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger py-2"><i class="bi bi-exclamation-triangle me-1"></i><?= esc($error) ?></div>
                <?php endif; ?>

                <form method="POST" action="/admin/login">
                    <?= csrf_field() ?>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Email</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                            <input type="email" name="email" class="form-control" placeholder="admin@exemple.com" required autofocus>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Mot de passe</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-lock"></i></span>
                            <input type="password" name="password" class="form-control" placeholder="••••••" required>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 fw-semibold">
                        <i class="bi bi-shield-check me-2"></i>Connexion Admin
                    </button>
                </form>

                <hr class="my-3">
                <div class="text-center">
                    <a href="/login" class="text-muted small"><i class="bi bi-arrow-left me-1"></i>Retour espace client</a>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
