<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<div class="row justify-content-center">
<div class="col-lg-7">
<div class="card">
    <div class="card-header d-flex justify-content-between">
        <span><?= isset($activite) ? 'Modifier' : 'Créer' ?> une activité sportive</span>
        <a href="/admin/activites" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i>Retour</a>
    </div>
    <div class="card-body">
        <form method="POST" action="<?= isset($activite) ? '/admin/activites/update/' . $activite['id'] : '/admin/activites/store' ?>">
            <?= csrf_field() ?>
            <div class="mb-3">
                <label class="form-label fw-semibold">Nom <span class="text-danger">*</span></label>
                <input type="text" name="nom" class="form-control" required
                       value="<?= esc($activite['nom'] ?? old('nom')) ?>">
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Description</label>
                <textarea name="description" class="form-control" rows="3"><?= esc($activite['description'] ?? old('description')) ?></textarea>
            </div>
            <div class="row g-3 mb-3">
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Intensité <span class="text-danger">*</span></label>
                    <select name="intensite" class="form-select" required>
                        <?php foreach (['faible'=>'Faible','modere'=>'Modéré','intense'=>'Intense'] as $v=>$l): ?>
                            <option value="<?= $v ?>" <?= ($activite['intensite'] ?? old('intensite')) == $v ? 'selected' : '' ?>><?= $l ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Calories/heure <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <input type="number" name="calories_par_heure" class="form-control" min="0" required
                               value="<?= $activite['calories_par_heure'] ?? old('calories_par_heure') ?>">
                        <span class="input-group-text">cal</span>
                    </div>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Durée recommandée <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <input type="number" name="duree_recommandee" class="form-control" min="1" required
                               value="<?= $activite['duree_recommandee'] ?? old('duree_recommandee') ?>">
                        <span class="input-group-text">min</span>
                    </div>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Séances/semaine <span class="text-danger">*</span></label>
                    <input type="number" name="seances_par_semaine" class="form-control" min="1" max="7" required
                           value="<?= $activite['seances_par_semaine'] ?? old('seances_par_semaine', 3) ?>">
                </div>
            </div>

            <label class="form-label fw-semibold mb-2">Objectifs concernés</label>
            <div class="d-flex gap-3 mb-4">
                <?php
                $existingObjectifs = explode(',', $activite['type_objectif'] ?? old('type_objectif', ''));
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

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary px-4 fw-semibold">
                    <i class="bi bi-check-circle me-1"></i><?= isset($activite) ? 'Enregistrer' : 'Créer' ?>
                </button>
                <a href="/admin/activites" class="btn btn-outline-secondary">Annuler</a>
            </div>
        </form>
    </div>
</div>
</div>
</div>

<?= $this->endSection() ?>
