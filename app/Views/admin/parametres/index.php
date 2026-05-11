<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<div class="row justify-content-center">
<div class="col-lg-6">
    <div class="card">
        <div class="card-header"><i class="bi bi-gear me-2"></i>Paramètres du système</div>
        <div class="card-body">
            <form method="POST" action="/admin/parametres/update">
                <?= csrf_field() ?>
                <h6 class="fw-bold text-muted mb-3">Abonnement Gold</h6>
                <div class="mb-4">
                    <label class="form-label fw-semibold">Prix de l'abonnement Gold (Ar)</label>
                    <div class="input-group">
                        <input type="number" name="gold_price" class="form-control form-control-lg"
                               value="<?= $params['gold_price'] ?? 29000 ?>" min="1000" step="1000" required>
                        <span class="input-group-text">Ar</span>
                    </div>
                    <div class="form-text">Les membres Gold bénéficient de -15% sur tous les régimes.</div>
                </div>

                <h6 class="fw-bold text-muted mb-3">Remise Gold</h6>
                <div class="mb-4">
                    <label class="form-label fw-semibold">Taux de remise Gold (%)</label>
                    <div class="input-group">
                        <input type="number" name="gold_discount" class="form-control form-control-lg"
                               value="<?= $params['gold_discount'] ?? 15 ?>" min="1" max="99" required>
                        <span class="input-group-text">%</span>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary px-4 fw-semibold">
                    <i class="bi bi-check-circle me-1"></i>Enregistrer
                </button>
            </form>
        </div>
    </div>
</div>
</div>

<?= $this->endSection() ?>
