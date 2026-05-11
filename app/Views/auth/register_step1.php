<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription Étape 1 | NutriPlan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body{background:linear-gradient(135deg,#3d1070,#9b59d0);min-height:100vh;display:flex;align-items:center;}
        .auth-card{border:none;border-radius:18px;box-shadow:0 20px 60px rgba(0,0,0,.3);}
        .step{width:36px;height:36px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:.9rem;}
        .btn-primary{background:#6f2da8;border-color:#6f2da8;}
        .form-control:focus,.form-select:focus{border-color:#6f2da8;box-shadow:0 0 0 .2rem rgba(111,45,168,.2);}
    </style>
</head>
<body>
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-7 col-lg-5">
            <div class="card auth-card p-4">
                <div class="text-center mb-3">
                    <div style="font-size:1.8rem;font-weight:800;color:#6f2da8"><i class="bi bi-heart-pulse-fill me-1"></i>Rezime</div>
                    <p class="text-muted small">Créez votre compte</p>
                </div>

                <!-- Indicateur étapes -->
                <div class="d-flex justify-content-center align-items-center gap-2 mb-4">
                    <div class="step bg-primary text-white">1</div>
                    <div style="flex:1;height:2px;background:#dee2e6"></div>
                    <div class="step bg-light text-muted border">2</div>
                </div>

                <h6 class="fw-bold mb-3"><i class="bi bi-person me-2" style="color:#6f2da8"></i>Informations personnelles</h6>

                <?php if ($errors = session()->getFlashdata('errors')): ?>
                    <div class="alert alert-danger py-2">
                        <ul class="mb-0 ps-3">
                            <?php foreach ((array)$errors as $e): ?><li><?= esc($e) ?></li><?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <form method="POST" action="/register" id="form1">
                    <?= csrf_field() ?>
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label fw-semibold">Prénom <span class="text-danger">*</span></label>
                            <input type="text" name="prenom" class="form-control" value="<?= old('prenom') ?>" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-semibold">Nom <span class="text-danger">*</span></label>
                            <input type="text" name="nom" class="form-control" value="<?= old('nom') ?>" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                            <input type="email" name="email" class="form-control" value="<?= old('email') ?>" required>
                        </div>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Genre <span class="text-danger">*</span></label>
                            <select name="genre" class="form-select" required>
                                <option value="">-- Choisir --</option>
                                <option value="homme" <?= old('genre')=='homme'?'selected':'' ?>>Homme</option>
                                <option value="femme" <?= old('genre')=='femme'?'selected':'' ?>>Femme</option>
                                <option value="autre" <?= old('genre')=='autre'?'selected':'' ?>>Autre</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Date de naissance <span class="text-danger">*</span></label>
                            <input type="date" name="date_naissance" class="form-control" value="<?= old('date_naissance') ?>" max="<?= date('Y-m-d', strtotime('-10 years')) ?>" required>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Mot de passe <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-lock"></i></span>
                            <input type="password" name="password" id="password" class="form-control" minlength="6" placeholder="6 caractères minimum" required>
                            <button type="button" class="btn btn-outline-secondary" id="togglePassword" tabindex="-1">
                                <i class="bi bi-eye" id="eyeIcon"></i>
                            </button>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 fw-semibold">
                        Suivant <i class="bi bi-arrow-right ms-1"></i>
                    </button>
                </form>
                <div class="text-center mt-3 small">
                    Déjà inscrit ? <a href="/login" style="color:#6f2da8;font-weight:600">Se connecter</a>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.getElementById('togglePassword').addEventListener('click', function() {
    const input = document.getElementById('password');
    const icon  = document.getElementById('eyeIcon');
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
