<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion | Rezim</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body{background:linear-gradient(135deg,#3d1070,#6f2da8 55%,#9b59d0);min-height:100vh;display:flex;align-items:center;}
        .auth-card{border:none;border-radius:18px;box-shadow:0 20px 60px rgba(0,0,0,.3);}
        .btn-primary{background:#6f2da8;border-color:#6f2da8;padding:.7rem;}
        .btn-primary:hover{background:#4a1a7a;border-color:#4a1a7a;}
        .form-control:focus{border-color:#6f2da8;box-shadow:0 0 0 .2rem rgba(111,45,168,.2);}
    </style>
</head>
<body>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-sm-10 col-md-6 col-lg-4">
            <div class="card auth-card p-4">
                <div class="text-center mb-4">
                    <div style="font-size:2rem;font-weight:800;color:#6f2da8">
                        <i class="bi bi-heart-pulse-fill me-1"></i>Rezim
                    </div>
                    <p class="text-muted small mt-1">Votre espace nutrition personnalisé</p>
                </div>

                <?php if ($error = session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger py-2"><i class="bi bi-exclamation-triangle me-1"></i><?= esc($error) ?></div>
                <?php endif; ?>
                <?php if (isset($erreur)): ?>
                    <div class="alert alert-danger py-2"><i class="bi bi-exclamation-triangle me-1"></i>Email ou mot de passe incorrect.</div>
                <?php endif; ?>
                <?php if ($success = session()->getFlashdata('success')): ?>
                    <div class="alert alert-success py-2"><i class="bi bi-check-circle me-1"></i><?= esc($success) ?></div>
                <?php endif; ?>

                <form method="POST" action="/login">
                    <?= csrf_field() ?>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Email</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                            <input type="email" name="email" class="form-control" placeholder="votre@email.com" required autofocus>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Mot de passe</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-lock"></i></span>
                            <input type="password" name="pass" id="passLogin" class="form-control" placeholder="••••••" required>
                            <button type="button" class="btn btn-outline-secondary" id="togglePass" tabindex="-1">
                                <i class="bi bi-eye" id="eyeLogin"></i>
                            </button>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 fw-semibold">
                        <i class="bi bi-box-arrow-in-right me-2"></i>Se connecter
                    </button>
                </form>

                <hr class="my-3">
                <div class="text-center small">
                    Pas encore de compte ?
                    <a href="/register" class="fw-bold" style="color:#6f2da8">S'inscrire</a>
                </div>
                <div class="text-center mt-2">
                    <a href="/admin/login" class="text-muted small">
                        <i class="bi bi-shield-lock me-1"></i>Accès Administration
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.getElementById('togglePass').addEventListener('click', function() {
    const input = document.getElementById('passLogin');
    const icon  = document.getElementById('eyeLogin');
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.replace('bi-eye', 'bi-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.replace('bi-eye-slash', 'bi-eye');
    }
});
</script>
</body>
</html>
