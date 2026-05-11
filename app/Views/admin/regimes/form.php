<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<div class="row justify-content-center">
<div class="col-lg-9">
<div class="card">
    <div class="card-header d-flex justify-content-between">
        <span><?= isset($regime) ? 'Modifier' : 'Créer' ?> un régime</span>
        <a href="/admin/regimes" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i>Retour</a>
    </div>
    <div class="card-body">
        <form method="POST" action="<?= isset($regime) ? '/admin/regimes/update/' . $regime['id'] : '/admin/regimes/store' ?>">
            <?= csrf_field() ?>
            <div class="mb-3">
                <label class="form-label fw-semibold">Nom du régime <span class="text-danger">*</span></label>
                <input type="text" name="nom" class="form-control" required
                       value="<?= esc($regime['nom'] ?? old('nom')) ?>">
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Description</label>
                <textarea name="description" class="form-control" rows="3"><?= esc($regime['description'] ?? old('description')) ?></textarea>
            </div>

            <h6 class="fw-bold mt-4 mb-2">Composition alimentaire (total = 100%)</h6>
            <div class="row g-3 mb-3">
                <div class="col-6 col-md-3">
                    <label class="form-label fw-semibold">Viande %</label>
                    <input type="number" name="pct_viande" class="form-control pct" min="0" max="100"
                           value="<?= $regime['pct_viande'] ?? old('pct_viande', 25) ?>" required>
                </div>
                <div class="col-6 col-md-3">
                    <label class="form-label fw-semibold">Poisson %</label>
                    <input type="number" name="pct_poisson" class="form-control pct" min="0" max="100"
                           value="<?= $regime['pct_poisson'] ?? old('pct_poisson', 25) ?>" required>
                </div>
                <div class="col-6 col-md-3">
                    <label class="form-label fw-semibold">Volaille %</label>
                    <input type="number" name="pct_volaille" class="form-control pct" min="0" max="100"
                           value="<?= $regime['pct_volaille'] ?? old('pct_volaille', 25) ?>" required>
                </div>
                <div class="col-6 col-md-3">
                    <label class="form-label fw-semibold">Légumes %</label>
                    <input type="number" name="pct_legumes" class="form-control pct" min="0" max="100"
                           value="<?= $regime['pct_legumes'] ?? old('pct_legumes', 25) ?>" required>
                </div>
            </div>
            <div id="pct-warn" class="alert alert-warning d-none py-2">La somme doit être égale à 100%.</div>

            <h6 class="fw-bold mt-4 mb-2">Objectifs concernés</h6>
            <div class="d-flex gap-3 mb-4">
                <?php
                $existingObjectifs = explode(',', $regime['type_objectif'] ?? old('type_objectif', ''));
                foreach (['reduire'=>'Perdre du poids','augmenter'=>'Prendre du poids','imc_ideal'=>'IMC idéal'] as $val=>$lbl):
                ?>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="type_objectif[]"
                           id="obj_<?= $val ?>" value="<?= $val ?>"
                           <?= in_array($val, $existingObjectifs) ? 'checked' : '' ?>>
                    <label class="form-check-label" for="obj_<?= $val ?>"><?= $lbl ?></label>
                </div>
                <?php endforeach; ?>
            </div>

            <h6 class="fw-bold mt-4 mb-2">Tarification par durée</h6>
            <div id="prix-list">
                <?php
                $prixList = $regime['prix_list'] ?? [['duree_jours'=>7,'prix'=>''],['duree_jours'=>14,'prix'=>''],['duree_jours'=>30,'prix'=>'']];
                foreach ($prixList as $i => $p):
                ?>
                <div class="row g-2 mb-2 prix-row">
                    <div class="col-5">
                        <div class="input-group">
                            <input type="number" name="duree[]" class="form-control" placeholder="Jours" min="1" value="<?= $p['duree_jours'] ?>" required>
                            <span class="input-group-text">jours</span>
                        </div>
                    </div>
                    <div class="col-5">
                        <div class="input-group">
                            <input type="number" name="prix[]" class="form-control" placeholder="Prix" min="0" value="<?= $p['prix'] ?>" required>
                            <span class="input-group-text">Ar</span>
                        </div>
                    </div>
                    <div class="col-2">
                        <button type="button" class="btn btn-outline-danger btn-remove"><i class="bi bi-trash"></i></button>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <button type="button" id="add-prix" class="btn btn-outline-primary btn-sm mb-4">
                <i class="bi bi-plus me-1"></i>Ajouter une durée
            </button>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary px-4 fw-semibold">
                    <i class="bi bi-check-circle me-1"></i><?= isset($regime) ? 'Enregistrer' : 'Créer' ?>
                </button>
                <a href="/admin/regimes" class="btn btn-outline-secondary">Annuler</a>
            </div>
        </form>
    </div>
</div>
</div>
</div>

<script>
document.getElementById('add-prix').addEventListener('click', function() {
    const tpl = `<div class="row g-2 mb-2 prix-row">
        <div class="col-5"><div class="input-group"><input type="number" name="duree[]" class="form-control" placeholder="Jours" min="1" required><span class="input-group-text">jours</span></div></div>
        <div class="col-5"><div class="input-group"><input type="number" name="prix[]" class="form-control" placeholder="Prix" min="0" required><span class="input-group-text">Ar</span></div></div>
        <div class="col-2"><button type="button" class="btn btn-outline-danger btn-remove"><i class="bi bi-trash"></i></button></div>
    </div>`;
    document.getElementById('prix-list').insertAdjacentHTML('beforeend', tpl);
});

document.getElementById('prix-list').addEventListener('click', function(e) {
    if (e.target.closest('.btn-remove')) {
        const rows = document.querySelectorAll('.prix-row');
        if (rows.length > 1) e.target.closest('.prix-row').remove();
    }
});

function checkPct() {
    const sum = Array.from(document.querySelectorAll('.pct')).reduce((s, i) => s + (+i.value || 0), 0);
    document.getElementById('pct-warn').classList.toggle('d-none', sum === 100);
}
document.querySelectorAll('.pct').forEach(i => i.addEventListener('input', checkPct));
</script>

<?= $this->endSection() ?>
