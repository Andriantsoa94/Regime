<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="row g-3">
    <!-- Solde -->
    <div class="col-md-5">
        <div class="card h-100">
            <div class="card-header"><i class="bi bi-wallet2 me-2"></i>Mon Portefeuille</div>
            <div class="card-body text-center py-4">
                <div class="fs-1 fw-bold mb-1" style="color:#6f2da8"><?= number_format($user['solde'] ?? 0, 0, ',', ' ') ?> Ar</div>
                <div class="text-muted mb-4">Solde disponible</div>
                <?php if ($user['is_gold'] ?? false): ?>
                    <div class="py-3 px-4 rounded-3 mb-3" style="background:linear-gradient(135deg,#f0b429,#e08a00)">
                        <i class="bi bi-star-fill text-white fs-3 d-block mb-1"></i>
                        <span class="text-white fw-bold fs-5">Membre Gold</span>
                        <div class="text-white small opacity-75">-15% sur tous les régimes</div>
                    </div>
                <?php else: ?>
                    <div class="border rounded-3 p-3 mb-3">
                        <i class="bi bi-star fs-3 d-block mb-1" style="color:#e08a00"></i>
                        <div class="fw-bold">Passer au Gold</div>
                        <div class="text-muted small mb-2">Bénéficiez de -15% sur tous les régimes</div>
                        <div class="fw-bold fs-5 mb-2" style="color:#6f2da8">29 000 Ar</div>
                        <form method="POST" action="/wallet/gold">
                            <?= csrf_field() ?>
                            <button type="submit" class="btn btn-warning w-100 fw-semibold"
                                <?= ($user['solde'] ?? 0) < 29000 ? 'disabled' : '' ?>>
                                <i class="bi bi-star-fill me-1"></i>Activer Gold
                            </button>
                        </form>
                        <?php if (($user['solde'] ?? 0) < 29000): ?>
                            <div class="text-danger small mt-1">Solde insuffisant (il manque <?= number_format(29000 - ($user['solde'] ?? 0), 0, ',', ' ') ?> Ar)</div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Recharger -->
    <div class="col-md-7">
        <div class="card mb-3">
            <div class="card-header"><i class="bi bi-plus-circle me-2"></i>Recharger avec un code</div>
            <div class="card-body">
                <form method="POST" action="/wallet/recharge">
                    <?= csrf_field() ?>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-qr-code"></i></span>
                        <input type="text" name="code" class="form-control form-control-lg"
                               placeholder="CODE-XXXX-XXXX" required
                               style="text-transform:uppercase;letter-spacing:.05em">
                        <button type="submit" class="btn btn-primary px-4 fw-semibold">
                            <i class="bi bi-arrow-right-circle me-1"></i>Recharger
                        </button>
                    </div>
                    <div class="form-text">Entrez le code reçu pour créditer votre portefeuille.</div>
                </form>
            </div>
        </div>

        <!-- Historique -->
        <?php if (!empty($historique)): ?>
        <div class="card">
            <div class="card-header"><i class="bi bi-clock-history me-2"></i>Historique des transactions</div>
            <div class="table-responsive">
                <table class="table table-sm mb-0">
                    <thead><tr><th>Date</th><th>Type</th><th>Montant</th><th>Détail</th></tr></thead>
                    <tbody>
                    <?php foreach ($historique as $h): ?>
                    <tr>
                        <td class="text-muted small"><?= date('d/m/Y', strtotime($h['date'])) ?></td>
                        <td>
                            <?php if ($h['type'] === 'recharge'): ?>
                                <span class="badge bg-success">Recharge</span>
                            <?php elseif ($h['type'] === 'gold'): ?>
                                <span class="badge bg-warning text-dark">Gold</span>
                            <?php else: ?>
                                <span class="badge bg-primary">Achat</span>
                            <?php endif; ?>
                        </td>
                        <td class="fw-bold <?= $h['type'] === 'recharge' ? 'text-success' : 'text-danger' ?>">
                            <?= $h['type'] === 'recharge' ? '+' : '-' ?><?= number_format($h['montant'], 0, ',', ' ') ?> Ar
                        </td>
                        <td class="text-muted small"><?= esc($h['detail'] ?? '') ?></td>
                    </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php else: ?>
        <div class="card">
            <div class="card-body text-center text-muted py-4">
                <i class="bi bi-receipt fs-1 d-block mb-2"></i>
                Aucune transaction pour le moment.
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<?= $this->endSection() ?>
