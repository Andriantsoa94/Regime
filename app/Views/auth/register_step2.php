<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription Étape 2 | Rezim</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body{background:linear-gradient(135deg,#3d1070,#9b59d0);min-height:100vh;display:flex;align-items:center;}
        .auth-card{border:none;border-radius:18px;box-shadow:0 20px 60px rgba(0,0,0,.3);}
        .step{width:36px;height:36px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-weight:700;}
        .btn-primary{background:#6f2da8;border-color:#6f2da8;}
        .form-control:focus,.form-select:focus{border-color:#6f2da8;box-shadow:0 0 0 .2rem rgba(111,45,168,.2);}
        .objectif-card{border:2px solid #dee2e6;border-radius:12px;padding:1rem;cursor:pointer;transition:.2s;text-align:center;}
        .objectif-card:hover{border-color:#6f2da8;background:rgba(111,45,168,.05);}
        input[type=radio]:checked+.objectif-card{border-color:#6f2da8;background:rgba(111,45,168,.1);}
    </style>
</head>
<body>
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-7 col-lg-5">
            <div class="card auth-card p-4">
                <div class="text-center mb-3">
                    <div style="font-size:1.8rem;font-weight:800;color:#6f2da8"><i class="bi bi-heart-pulse-fill me-1"></i>Rezim</div>
                </div>

                <div class="d-flex justify-content-center align-items-center gap-2 mb-4">
                    <div class="step bg-success text-white"><i class="bi bi-check"></i></div>
                    <div style="flex:1;height:2px;background:#198754"></div>
                    <div class="step bg-primary text-white">2</div>
                </div>

                <h6 class="fw-bold mb-3"><i class="bi bi-activity me-2" style="color:#6f2da8"></i>Informations de santé & objectif</h6>

                <?php if ($errors = session()->getFlashdata('errors')): ?>
                    <div class="alert alert-danger py-2">
                        <ul class="mb-0 ps-3"><?php foreach ((array)$errors as $e): ?><li><?= esc($e) ?></li><?php endforeach; ?></ul>
                    </div>
                <?php endif; ?>

                <form method="POST" action="/register/step2">
                    <?= csrf_field() ?>
                    <div class="row g-3 mb-3">
                        <div class="col-6">
                            <label class="form-label fw-semibold">Taille (cm) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" name="taille" id="taille" class="form-control" placeholder="175" min="50" max="300" step="0.1" value="<?= old('taille') ?>" required>
                                <span class="input-group-text">cm</span>
                            </div>
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-semibold">Poids (kg) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" name="poids" id="poids" class="form-control" placeholder="70" min="10" max="500" step="0.1" value="<?= old('poids') ?>" required>
                                <span class="input-group-text">kg</span>
                            </div>
                        </div>
                    </div>

                    <div id="imc-preview" class="alert alert-info d-none mb-3 py-2">
                        <i class="bi bi-calculator me-1"></i>IMC estimé : <strong id="imc-val"></strong>
                    </div>

                    <label class="form-label fw-semibold mb-2">Mon objectif <span class="text-danger">*</span></label>
                    <div class="row g-2 mb-4">
                        <div class="col-4">
                            <label class="d-block">
                                <input type="radio" name="objectif" value="reduire" class="d-none" required <?= old('objectif')=='reduire'?'checked':'' ?>>
                                <div class="objectif-card">
                                    <i class="bi bi-arrow-down-circle fs-2 text-danger"></i>
                                    <div class="fw-semibold mt-1" style="font-size:.8rem">Perdre du poids</div>
                                </div>
                            </label>
                        </div>
                        <div class="col-4">
                            <label class="d-block">
                                <input type="radio" name="objectif" value="augmenter" class="d-none" <?= old('objectif')=='augmenter'?'checked':'' ?>>
                                <div class="objectif-card">
                                    <i class="bi bi-arrow-up-circle fs-2 text-success"></i>
                                    <div class="fw-semibold mt-1" style="font-size:.8rem">Prendre du poids</div>
                                </div>
                            </label>
                        </div>
                        <div class="col-4">
                            <label class="d-block">
                                <input type="radio" name="objectif" value="imc_ideal" class="d-none" <?= old('objectif')=='imc_ideal'?'checked':'' ?>>
                                <div class="objectif-card">
                                    <i class="bi bi-bullseye fs-2 text-primary"></i>
                                    <div class="fw-semibold mt-1" style="font-size:.8rem">IMC idéal</div>
                                </div>
                            </label>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 fw-semibold">
                        <i class="bi bi-check-circle me-2"></i>Créer mon compte
                    </button>
                    <a href="/register" class="btn btn-outline-secondary w-100 mt-2"><i class="bi bi-arrow-left me-1"></i>Retour</a>
                </form>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
const t = document.getElementById('taille'), p = document.getElementById('poids');
const imcDiv = document.getElementById('imc-preview'), imcVal = document.getElementById('imc-val');
function calcIMC() {
    const tv = parseFloat(t.value), pv = parseFloat(p.value);
    if (tv > 50 && pv > 10) { imcVal.textContent = (pv/((tv/100)**2)).toFixed(1); imcDiv.classList.remove('d-none'); }
}
t.addEventListener('input', calcIMC); p.addEventListener('input', calcIMC);
</script>
</body>
</html>
