<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-journal-medical me-2" style="color:#6f2da8"></i><?= esc($regime['nom']) ?></span>
                <a href="/dashboard" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i>Retour</a>
            </div>
            <div class="card-body">
                <p class="text-muted"><?= esc($regime['description'] ?? '') ?></p>

                <h6 class="fw-bold mt-3 mb-2">Composition alimentaire</h6>
                <div class="row g-2 mb-3">
                    <div class="col-6 col-md-3">
                        <div class="text-center border rounded p-2">
                            <div class="fs-4 fw-bold text-danger"><?= $regime['pct_viande'] ?>%</div>
                            <div class="small text-muted">Viande</div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="text-center border rounded p-2">
                            <div class="fs-4 fw-bold text-info"><?= $regime['pct_poisson'] ?>%</div>
                            <div class="small text-muted">Poisson</div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="text-center border rounded p-2">
                            <div class="fs-4 fw-bold text-warning"><?= $regime['pct_volaille'] ?>%</div>
                            <div class="small text-muted">Volaille</div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="text-center border rounded p-2">
                            <div class="fs-4 fw-bold text-success"><?= $regime['pct_legumes'] ?>%</div>
                            <div class="small text-muted">Légumes</div>
                        </div>
                    </div>
                </div>

                <?php if (!empty($regime['prix_list'])): ?>
                <h6 class="fw-bold mt-4 mb-2">Choisir une durée</h6>
                <form method="POST" action="/regime/subscribe">
                    <?= csrf_field() ?>
                    <input type="hidden" name="regime_id" value="<?= $regime['id'] ?>">
                    <div class="row g-2">
                    <?php foreach ($regime['prix_list'] as $p): ?>
                        <?php $prix = ($user['is_gold'] ?? false) ? $p['prix'] * 0.85 : $p['prix']; ?>
                        <div class="col-md-4">
                            <label class="d-block">
                                <input type="radio" name="duree_jours" value="<?= $p['duree_jours'] ?>" class="d-none" required>
                                <div class="border rounded-3 p-3 text-center cursor-pointer prix-card" style="cursor:pointer;transition:.2s">
                                    <div class="fw-bold fs-5"><?= $p['duree_jours'] ?> jours</div>
                                    <div class="text-primary fw-bold">
                                        <?= number_format($prix, 0, ',', ' ') ?> Ar
                                        <?php if ($user['is_gold'] ?? false): ?>
                                            <span class="badge bg-warning text-dark ms-1" style="font-size:.6rem">-15%</span>
                                        <?php endif; ?>
                                    </div>
                                    <?php if (($user['solde'] ?? 0) < $prix): ?>
                                        <div class="text-danger" style="font-size:.72rem">Solde insuffisant</div>
                                    <?php endif; ?>
                                </div>
                            </label>
                        </div>
                    <?php endforeach; ?>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 mt-3 fw-semibold">
                        <i class="bi bi-cart-check me-2"></i>Souscrire à ce régime
                    </button>
                    <div class="text-muted text-center small mt-1">Solde actuel : <?= number_format($user['solde'] ?? 0, 0, ',', ' ') ?> Ar</div>
                </form>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- Actions -->
        <div class="card mb-3">
            <div class="card-body">
                <a href="/regime/<?= $regime['id'] ?>/pdf" class="btn btn-outline-danger w-100 mb-2" target="_blank">
                    <i class="bi bi-file-earmark-pdf me-1"></i>Exporter en PDF
                </a>
                <?php if ($user['is_gold'] ?? false): ?>
                    <div class="alert alert-warning py-2 mb-0 text-center small">
                        <i class="bi bi-star-fill me-1"></i>Membre Gold — -15% appliqué automatiquement
                    </div>
                <?php else: ?>
                    <a href="/wallet" class="btn btn-warning w-100 btn-sm">
                        <i class="bi bi-star me-1"></i>Passer Gold pour -15%
                    </a>
                <?php endif; ?>
            </div>
        </div>

        <?php if (!empty($activites)): ?>
        <div class="card">
            <div class="card-header"><i class="bi bi-bicycle me-2"></i>Activités recommandées</div>
            <div class="card-body p-0">
                <?php $ic = ['faible'=>'success','modere'=>'warning','intense'=>'danger']; ?>
                <?php foreach ($activites as $a): ?>
                <div class="p-3 border-bottom">
                    <div class="d-flex justify-content-between mb-1">
                        <span class="fw-semibold small"><?= esc($a['nom']) ?></span>
                        <span class="badge bg-<?= $ic[$a['intensite']] ?? 'secondary' ?>"><?= ucfirst($a['intensite']) ?></span>
                    </div>
                    <div class="small text-muted"><?= $a['calories_par_heure'] ?> cal/h · <?= $a['duree_recommandee'] ?> min · <?= $a['seances_par_semaine'] ?>x/sem</div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<style>
input[type=radio]:checked+.prix-card { border-color:#6f2da8 !important; background:rgba(111,45,168,.07); }
</style>

<?= $this->endSection() ?>
