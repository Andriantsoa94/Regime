<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div></div>
    <a href="/admin/activites/create" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Nouvelle activité</a>
</div>

<div class="card">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead>
                <tr><th>Nom</th><th>Intensité</th><th>Cal/h</th><th>Durée</th><th>Séances/sem</th><th>Objectifs</th><th></th></tr>
            </thead>
            <tbody>
            <?php if (empty($activites)): ?>
                <tr><td colspan="7" class="text-center text-muted py-4">Aucune activité</td></tr>
            <?php endif; ?>
            <?php
            $ic = ['faible'=>'success','modere'=>'warning','intense'=>'danger'];
            foreach ($activites as $a):
            ?>
            <tr>
                <td class="fw-semibold"><?= esc($a['nom']) ?></td>
                <td><span class="badge bg-<?= $ic[$a['intensite']] ?? 'secondary' ?>"><?= ucfirst($a['intensite']) ?></span></td>
                <td><?= $a['calories_par_heure'] ?></td>
                <td><?= $a['duree_recommandee'] ?> min</td>
                <td><?= $a['seances_par_semaine'] ?>x</td>
                <td><span class="text-muted small"><?= esc($a['type_objectif'] ?? '') ?></span></td>
                <td>
                    <a href="/admin/activites/edit/<?= $a['id'] ?>" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
                    <form method="POST" action="/admin/activites/delete/<?= $a['id'] ?>" class="d-inline"
                          onsubmit="return confirm('Supprimer cette activité ?')">
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
