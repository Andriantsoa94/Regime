<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<div class="card">
    <div class="card-header">
        <input type="text" id="search" class="form-control form-control-sm w-auto d-inline-block" placeholder="Rechercher…">
    </div>
    <div class="card-body p-0">
        <table class="table table-hover mb-0" id="usersTable">
            <thead>
                <tr><th>Nom</th><th>Email</th><th>Genre</th><th>Objectif</th><th>IMC</th><th>Solde</th><th>Gold</th><th>Inscrit le</th><th></th></tr>
            </thead>
            <tbody>
            <?php foreach ($users as $u): ?>
            <tr>
                <td class="fw-semibold"><?= esc(($u['prenom'] ?? '') . ' ' . $u['nom']) ?></td>
                <td><?= esc($u['email']) ?></td>
                <td><?= ucfirst($u['genre'] ?? '—') ?></td>
                <td>
                    <?php
                    $objLabels = ['reduire'=>'Perdre','augmenter'=>'Prendre','imc_ideal'=>'IMC idéal'];
                    echo esc($objLabels[$u['objectif'] ?? ''] ?? '—');
                    ?>
                </td>
                <td>
                    <?php if (!empty($u['poids']) && !empty($u['taille'])): ?>
                        <?= round($u['poids'] / (($u['taille']/100)**2), 1) ?>
                    <?php else: ?>—<?php endif; ?>
                </td>
                <td><?= number_format($u['solde'] ?? 0, 0, ',', ' ') ?> Ar</td>
                <td>
                    <?php if ($u['is_gold'] ?? false): ?>
                        <span class="badge bg-warning text-dark"><i class="bi bi-star-fill me-1"></i>Gold</span>
                    <?php else: ?>
                        <span class="badge bg-light text-muted">Standard</span>
                    <?php endif; ?>
                </td>
                <td class="text-muted small"><?= $u['created_at'] ? date('d/m/Y', strtotime($u['created_at'])) : '—' ?></td>
                <td>
                    <form method="POST" action="/admin/users/toggle-gold/<?= $u['id'] ?>" class="d-inline">
                        <?= csrf_field() ?>
                        <button class="btn btn-sm <?= ($u['is_gold'] ?? false) ? 'btn-outline-secondary' : 'btn-outline-warning' ?>">
                            <?= ($u['is_gold'] ?? false) ? 'Retirer Gold' : 'Ajouter Gold' ?>
                        </button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php if (empty($users)): ?>
                <tr><td colspan="9" class="text-center text-muted py-4">Aucun utilisateur</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
document.getElementById('search').addEventListener('input', function() {
    const q = this.value.toLowerCase();
    document.querySelectorAll('#usersTable tbody tr').forEach(row => {
        row.style.display = row.textContent.toLowerCase().includes(q) ? '' : 'none';
    });
});
</script>

<?= $this->endSection() ?>
