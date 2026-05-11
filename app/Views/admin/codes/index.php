<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<div class="row g-3 mb-4">
    <!-- Générer des codes -->
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-header"><i class="bi bi-plus-circle me-2"></i>Générer des codes</div>
            <div class="card-body">
                <form method="POST" action="/admin/codes/store">
                    <?= csrf_field() ?>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Montant (Ar) <span class="text-danger">*</span></label>
                        <input type="number" name="montant" class="form-control" min="1000" step="1000"
                               placeholder="5000" required value="<?= old('montant', 5000) ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Quantité</label>
                        <input type="number" name="quantite" class="form-control" min="1" max="100"
                               value="<?= old('quantite', 5) ?>">
                    </div>
                    <button type="submit" class="btn btn-primary w-100 fw-semibold">
                        <i class="bi bi-qr-code me-1"></i>Générer
                    </button>
                </form>
                <form method="POST" action="/admin/codes/purge" class="mt-2"
                      onsubmit="return confirm('Supprimer tous les codes utilisés ?')">
                    <?= csrf_field() ?>
                    <button class="btn btn-outline-danger w-100 btn-sm"><i class="bi bi-trash me-1"></i>Purger les utilisés</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Stats -->
    <div class="col-md-8">
        <div class="row g-3 h-100">
            <div class="col-6">
                <div class="stat-card text-center">
                    <div class="fs-2 fw-bold text-primary"><?= $stats['total'] ?? 0 ?></div>
                    <div class="text-muted small">Total codes</div>
                </div>
            </div>
            <div class="col-6">
                <div class="stat-card text-center">
                    <div class="fs-2 fw-bold text-success"><?= $stats['available'] ?? 0 ?></div>
                    <div class="text-muted small">Disponibles</div>
                </div>
            </div>
            <div class="col-6">
                <div class="stat-card text-center">
                    <div class="fs-2 fw-bold text-secondary"><?= $stats['used'] ?? 0 ?></div>
                    <div class="text-muted small">Utilisés</div>
                </div>
            </div>
            <div class="col-6">
                <div class="stat-card text-center">
                    <div class="fs-2 fw-bold" style="color:#6f2da8"><?= number_format($stats['totalAmount'] ?? 0, 0, ',', ' ') ?> Ar</div>
                    <div class="text-muted small">Valeur utilisée</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span>Liste des codes</span>
        <form method="POST" action="/admin/codes/purge" class="d-inline"
              onsubmit="return confirm('Supprimer tous les codes utilisés ?')">
            <?= csrf_field() ?>
            <button class="btn btn-outline-danger btn-sm"><i class="bi bi-trash me-1"></i>Purger les utilisés</button>
        </form>
    </div>
    <div class="card-body p-0">
        <table class="table table-sm table-hover mb-0">
            <thead><tr><th>Code</th><th>Montant</th><th>Statut</th><th>Utilisé le</th><th>Utilisateur</th></tr></thead>
            <tbody>
            <?php foreach ($codes as $c): ?>
            <tr>
                <td><code><?= esc($c['code']) ?></code></td>
                <td class="fw-bold"><?= number_format($c['montant'], 0, ',', ' ') ?> Ar</td>
                <td>
                    <?php if ($c['is_used']): ?>
                        <span class="badge bg-secondary">Utilisé</span>
                    <?php else: ?>
                        <span class="badge bg-success">Disponible</span>
                    <?php endif; ?>
                </td>
                <td class="text-muted small"><?= $c['used_at'] ? date('d/m/Y H:i', strtotime($c['used_at'])) : '—' ?></td>
                <td class="text-muted small"><?= $c['prenom'] ? esc($c['prenom'] . ' ' . $c['nom']) : '—' ?></td>
            </tr>
            <?php endforeach; ?>
            <?php if (empty($codes)): ?>
                <tr><td colspan="5" class="text-center text-muted py-4">Aucun code</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>
