<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div></div>
    <a href="/admin/regimes/create" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Nouveau régime</a>
</div>

<div class="card">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Viande</th>
                    <th>Poisson</th>
                    <th>Volaille</th>
                    <th>Légumes</th>
                    <th>Objectifs</th>
                    <th>Prix</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
            <?php if (empty($regimes)): ?>
                <tr><td colspan="8" class="text-center text-muted py-4">Aucun régime</td></tr>
            <?php endif; ?>
            <?php foreach ($regimes as $r): ?>
            <tr>
                <td class="fw-semibold"><?= esc($r['nom']) ?></td>
                <td><span class="badge bg-danger"><?= $r['pct_viande'] ?>%</span></td>
                <td><span class="badge bg-info"><?= $r['pct_poisson'] ?>%</span></td>
                <td><span class="badge bg-warning text-dark"><?= $r['pct_volaille'] ?>%</span></td>
                <td><span class="badge bg-success"><?= $r['pct_legumes'] ?>%</span></td>
                <td><span class="text-muted small"><?= esc($r['type_objectif']) ?></span></td>
                <td>
                    <?php foreach ($r['prix_list'] as $p): ?>
                        <span class="badge bg-light text-dark border"><?= $p['duree_jours'] ?>j: <?= number_format($p['prix'], 0, ',', ' ') ?></span>
                    <?php endforeach; ?>
                </td>
                <td>
                    <a href="/admin/regimes/edit/<?= $r['id'] ?>" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
                    <form method="POST" action="/admin/regimes/delete/<?= $r['id'] ?>" class="d-inline"
                          onsubmit="return confirm('Supprimer ce régime ?')">
                        <?= csrf_field() ?>
                        <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>
