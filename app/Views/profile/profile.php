<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header"><i class="bi bi-person-circle me-2" style="color:#6f2da8"></i>Mon Profil</div>
            <div class="card-body">
                <form method="POST" action="/profile/update">
                    <?= csrf_field() ?>
                    <h6 class="fw-bold text-muted mb-3">Informations personnelles</h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Prénom</label>
                            <input type="text" name="prenom" class="form-control" value="<?= esc($user['prenom'] ?? '') ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Nom</label>
                            <input type="text" name="nom" class="form-control" value="<?= esc($user['nom'] ?? '') ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Email</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                <input type="email" name="email" class="form-control" value="<?= esc($user['email'] ?? '') ?>" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Genre</label>
                            <select name="genre" class="form-select">
                                <option value="homme" <?= ($user['genre'] ?? '') == 'homme' ? 'selected' : '' ?>>Homme</option>
                                <option value="femme" <?= ($user['genre'] ?? '') == 'femme' ? 'selected' : '' ?>>Femme</option>
                                <option value="autre" <?= ($user['genre'] ?? '') == 'autre' ? 'selected' : '' ?>>Autre</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Date de naissance</label>
                            <input type="date" name="date_naissance" class="form-control"
                                value="<?= esc($user['date_naissance'] ?? '') ?>"
                                max="<?= date('Y-m-d', strtotime('-10 years')) ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Objectif</label>
                            <select name="objectif" class="form-select">
                                <option value="">-- Aucun --</option>
                                <option value="reduire"   <?= ($user['objectif'] ?? '') == 'reduire'   ? 'selected' : '' ?>>Perdre du poids</option>
                                <option value="augmenter" <?= ($user['objectif'] ?? '') == 'augmenter' ? 'selected' : '' ?>>Prendre du poids</option>
                                <option value="imc_ideal" <?= ($user['objectif'] ?? '') == 'imc_ideal' ? 'selected' : '' ?>>IMC idéal</option>
                            </select>
                        </div>
                    </div>

                    <h6 class="fw-bold text-muted mb-3">Informations de santé</h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Taille (cm)</label>
                            <div class="input-group">
                                <input type="number" name="taille" class="form-control" step="0.1" min="50" max="300"
                                       value="<?= esc($sante['taille'] ?? '') ?>" id="taille">
                                <span class="input-group-text">cm</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Poids (kg)</label>
                            <div class="input-group">
                                <input type="number" name="poids" class="form-control" step="0.1" min="10" max="500"
                                       value="<?= esc($sante['poids'] ?? '') ?>" id="poids">
                                <span class="input-group-text">kg</span>
                            </div>
                        </div>
                        <?php if ($imc): ?>
                        <div class="col-12">
                            <div class="alert alert-info py-2 mb-0">
                                <i class="bi bi-activity me-1"></i>
                                IMC actuel : <strong><?= $imc ?></strong>
                                <span class="ms-2 badge bg-<?= $imcColor ?? 'secondary' ?>"><?= esc($imcCat ?? '') ?></span>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>

                    <h6 class="fw-bold text-muted mb-3">Sécurité (laisser vide pour ne pas changer)</h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Nouveau mot de passe</label>
                            <input type="password" name="password" class="form-control" minlength="6" placeholder="••••••">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Confirmation</label>
                            <input type="password" name="confirm_pass" class="form-control" minlength="6" placeholder="••••••">
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary fw-semibold px-4">
                        <i class="bi bi-check-circle me-2"></i>Enregistrer les modifications
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
