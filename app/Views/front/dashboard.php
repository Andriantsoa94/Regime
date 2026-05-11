<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="row g-3 mb-4">
    <!-- IMC -->
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-header"><i class="bi bi-activity me-2"></i>Mon IMC</div>
            <div class="card-body text-center py-4">
                <?php if ($imc): ?>
                    <div class="imc-circle bg-<?= $imcColor ?> bg-opacity-10 border border-3 border-<?= $imcColor ?> mb-3">
                        <span class="fs-2 fw-bold text-<?= $imcColor ?>"><?= $imc ?></span>
                        <small class="text-<?= $imcColor ?>">IMC</small>
                    </div>
                    <span class="badge bg-<?= $imcColor ?> fs-6 px-3"><?= esc($imcCat) ?></span>
                    <div class="row mt-3">
                        <div class="col-6 border-end">
                            <div class="text-muted small">Poids</div>
                            <div class="fw-bold"><?= $sante['poids'] ?> kg</div>
                        </div>
                        <div class="col-6">
                            <div class="text-muted small">Taille</div>
                            <div class="fw-bold"><?= $sante['taille'] ?> cm</div>
                        </div>
                    </div>
                    <div class="mt-2"><small class="text-muted">IMC idéal : 18.5 – 24.9</small></div>
                <?php else: ?>
                    <i class="bi bi-person-badge fs-1 text-muted d-block mb-2"></i>
                    <p class="text-muted small">Complétez votre profil santé pour voir votre IMC</p>
                    <a href="/sante" class="btn btn-primary btn-sm">Renseigner ma santé</a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Objectif -->
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-header"><i class="bi bi-bullseye me-2"></i>Mon Objectif</div>
            <div class="card-body">
                <?php
                $objMap = [
                    'reduire'   => ['label'=>'Perdre du poids',    'icon'=>'arrow-down-circle','color'=>'danger'],
                    'augmenter' => ['label'=>'Prendre du poids',   'icon'=>'arrow-up-circle',  'color'=>'success'],
                    'imc_ideal' => ['label'=>"Atteindre l'IMC idéal",'icon'=>'bullseye',        'color'=>'primary'],
                ];
                $obj = $objMap[$user['objectif'] ?? ''] ?? null;
                if ($obj):
                ?>
                <div class="text-center py-2">
                    <i class="bi bi-<?= $obj['icon'] ?> fs-1 text-<?= $obj['color'] ?>"></i>
                    <h5 class="mt-1 fw-bold"><?= $obj['label'] ?></h5>
                </div>
                <?php endif; ?>
                <form method="POST" action="/dashboard/objectif" class="mt-2">
                    <?= csrf_field() ?>
                    <select name="objectif" class="form-select form-select-sm mb-2">
                        <option value="reduire"   <?= ($user['objectif']??'')=='reduire'  ?'selected':'' ?>>Perdre du poids</option>
                        <option value="augmenter" <?= ($user['objectif']??'')=='augmenter'?'selected':'' ?>>Prendre du poids</option>
                        <option value="imc_ideal" <?= ($user['objectif']??'')=='imc_ideal'?'selected':'' ?>>IMC idéal</option>
                    </select>
                    <button type="submit" class="btn btn-outline-primary btn-sm w-100">Changer l'objectif</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Portefeuille -->
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-header"><i class="bi bi-wallet2 me-2"></i>Portefeuille</div>
            <div class="card-body text-center py-4">
                <div class="fs-2 fw-bold" style="color:#6f2da8"><?= number_format($user['solde'] ?? 0, 0, ',', ' ') ?> Ar</div>
                <div class="text-muted small mb-3">Solde disponible</div>
                <?php if ($user['is_gold'] ?? false): ?>
                    <span class="badge fs-6 px-3 py-2" style="background:linear-gradient(135deg,#f0b429,#e08a00)">
                        <i class="bi bi-star-fill me-1"></i>Membre Gold – 15%
                    </span>
                <?php else: ?>
                    <a href="/wallet" class="btn btn-warning btn-sm w-100 mb-2">
                        <i class="bi bi-star me-1"></i>Passer Gold (29 000 Ar)
                    </a>
                <?php endif; ?>
                <a href="/wallet" class="btn btn-outline-secondary btn-sm w-100 mt-2">
                    <i class="bi bi-plus-circle me-1"></i>Recharger
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Régimes suggérés -->
<?php if (!empty($suggestions)): ?>
<h5 class="fw-bold mb-3"><i class="bi bi-journal-medical me-2" style="color:#6f2da8"></i>Régimes suggérés pour vous</h5>
<div class="row g-3 mb-5">
    <?php foreach ($suggestions as $r): ?>
    <div class="col-md-6 col-lg-4">
        <div class="card h-100">
            <div class="card-body">
                <h6 class="fw-bold"><?= esc($r['nom']) ?></h6>
                <p class="text-muted small"><?= esc(substr($r['description'] ?? '', 0, 90)) ?>...</p>
                <div class="d-flex flex-wrap gap-1 mb-2">
                    <span class="badge bg-danger"><?= $r['pct_viande'] ?>% Viande</span>
                    <span class="badge bg-info"><?= $r['pct_poisson'] ?>% Poisson</span>
                    <span class="badge bg-warning text-dark"><?= $r['pct_volaille'] ?>% Volaille</span>
                    <span class="badge bg-success"><?= $r['pct_legumes'] ?>% Légumes</span>
                </div>
                <?php foreach ($r['prix_list'] as $p): ?>
                <?php $prix = ($user['is_gold'] ?? false) ? $p['prix'] * 0.85 : $p['prix']; ?>
                <div class="d-flex justify-content-between border-bottom py-1 small">
                    <span class="fw-semibold"><?= $p['duree_jours'] ?> jours</span>
                    <span class="text-primary fw-bold">
                        <?= number_format($prix, 0, ',', ' ') ?> Ar
                        <?php if ($user['is_gold'] ?? false): ?>
                            <span class="badge bg-warning text-dark ms-1" style="font-size:.6rem">-15%</span>
                        <?php endif; ?>
                    </span>
                </div>
                <?php endforeach; ?>
                <a href="/regime/<?= $r['id'] ?>" class="btn btn-primary btn-sm w-100 mt-2">
                    <i class="bi bi-eye me-1"></i>Voir le régime
                </a>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>

<!-- Activités sportives -->
<?php if (!empty($activites)): ?>
<h5 class="fw-bold mb-3"><i class="bi bi-bicycle me-2" style="color:#6f2da8"></i>Activités sportives recommandées</h5>
<div class="row g-3">
    <?php $ic = ['faible'=>'success','modere'=>'warning','intense'=>'danger']; ?>
    <?php foreach ($activites as $a): ?>
    <div class="col-md-6 col-lg-4">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-1">
                    <h6 class="fw-bold mb-0"><?= esc($a['nom']) ?></h6>
                    <span class="badge bg-<?= $ic[$a['intensite']]??'secondary' ?>"><?= ucfirst($a['intensite']) ?></span>
                </div>
                <p class="text-muted small mb-2"><?= esc($a['description']) ?></p>
                <div class="row text-center g-0">
                    <div class="col-4"><div class="small text-muted">Cal/h</div><div class="fw-bold text-danger"><?= $a['calories_par_heure'] ?></div></div>
                    <div class="col-4"><div class="small text-muted">Durée</div><div class="fw-bold"><?= $a['duree_recommandee'] ?> min</div></div>
                    <div class="col-4"><div class="small text-muted">Séances/sem</div><div class="fw-bold text-primary"><?= $a['seances_par_semaine'] ?>x</div></div>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>

<?php if (!($user['objectif'] ?? null)): ?>
<div class="text-center py-5">
    <i class="bi bi-clipboard-heart fs-1 text-muted d-block mb-3"></i>
    <h5 class="text-muted">Choisissez un objectif pour voir les suggestions personnalisées</h5>
</div>
<?php endif; ?>

<?= $this->endSection() ?>
