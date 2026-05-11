<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="fw-bold mb-0"><i class="bi bi-journal-check me-2" style="color:#6f2da8"></i>Mes Régimes</h5>
            <a href="/dashboard" class="btn btn-outline-secondary btn-sm"><i class="bi bi-plus me-1"></i>Voir les suggestions</a>
        </div>

        <?php if (empty($regimes)): ?>
        <div class="text-center py-5">
            <i class="bi bi-journal-x fs-1 text-muted d-block mb-3"></i>
            <h5 class="text-muted">Vous n'avez pas encore souscrit à un régime</h5>
            <a href="/dashboard" class="btn btn-primary mt-2"><i class="bi bi-search me-1"></i>Explorer les régimes</a>
        </div>
        <?php else: ?>
        <div class="row g-3">
            <?php foreach ($regimes as $r): ?>
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h6 class="fw-bold mb-0"><?= esc($r['regime_nom'] ?? $r['nom'] ?? '') ?></h6>
                            <?php
                            $debut = strtotime($r['date_debut']);
                            $fin = $debut + ($r['duree_jours'] * 86400);
                            $now = time();
                            $actif = $now >= $debut && $now <= $fin;
                            ?>
                            <span class="badge bg-<?= $actif ? 'success' : ($now < $debut ? 'info' : 'secondary') ?>">
                                <?= $actif ? 'En cours' : ($now < $debut ? 'À venir' : 'Terminé') ?>
                            </span>
                        </div>
                        <div class="small text-muted mb-2"><?= esc(substr($r['description'] ?? '', 0, 80)) ?><?= strlen($r['description'] ?? '') > 80 ? '...' : '' ?></div>
                        <div class="row text-center g-0 border rounded p-2 mb-3">
                            <div class="col-4">
                                <div class="small text-muted">Durée</div>
                                <div class="fw-bold"><?= $r['duree_jours'] ?> j</div>
                            </div>
                            <div class="col-4 border-start border-end">
                                <div class="small text-muted">Début</div>
                                <div class="fw-bold"><?= date('d/m/Y', $debut) ?></div>
                            </div>
                            <div class="col-4">
                                <div class="small text-muted">Fin</div>
                                <div class="fw-bold"><?= date('d/m/Y', $fin) ?></div>
                            </div>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="/regime/<?= $r['regime_id'] ?>" class="btn btn-outline-primary btn-sm flex-fill">
                                <i class="bi bi-eye me-1"></i>Détails
                            </a>
                            <a href="/regime/<?= $r['regime_id'] ?>/pdf" class="btn btn-outline-danger btn-sm" target="_blank">
                                <i class="bi bi-file-pdf"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</div>

<?= $this->endSection() ?>
