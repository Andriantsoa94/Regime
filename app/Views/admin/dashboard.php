<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<!-- Stats row -->
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card d-flex align-items-center gap-3">
            <div class="stat-icon bg-primary bg-opacity-10"><i class="bi bi-people-fill text-primary"></i></div>
            <div><div class="text-muted small">Utilisateurs</div><div class="fs-4 fw-bold"><?= $totalUsers ?></div></div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card d-flex align-items-center gap-3">
            <div class="stat-icon bg-warning bg-opacity-10"><i class="bi bi-star-fill text-warning"></i></div>
            <div><div class="text-muted small">Membres Gold</div><div class="fs-4 fw-bold"><?= $goldUsers ?></div></div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card d-flex align-items-center gap-3">
            <div class="stat-icon bg-success bg-opacity-10"><i class="bi bi-cash-stack text-success"></i></div>
            <div><div class="text-muted small">Revenu total</div><div class="fs-4 fw-bold"><?= number_format($revenue ?? 0, 0, ',', ' ') ?> Ar</div></div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card d-flex align-items-center gap-3">
            <div class="stat-icon bg-info bg-opacity-10"><i class="bi bi-journal-medical text-info"></i></div>
            <div><div class="text-muted small">Souscriptions</div><div class="fs-4 fw-bold"><?= $totalSouscriptions ?? 0 ?></div></div>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    <!-- Genre chart -->
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-header">Répartition par genre</div>
            <div class="card-body d-flex align-items-center justify-content-center">
                <canvas id="genreChart" style="max-height:200px"></canvas>
            </div>
        </div>
    </div>
    <!-- Objectif chart -->
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-header">Objectifs des utilisateurs</div>
            <div class="card-body d-flex align-items-center justify-content-center">
                <canvas id="objChart" style="max-height:200px"></canvas>
            </div>
        </div>
    </div>
    <!-- Revenue chart -->
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-header">Revenus mensuels (<?= date('Y') ?>)</div>
            <div class="card-body">
                <canvas id="revChart" style="max-height:200px"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Quick links -->
<div class="row g-3">
    <div class="col-md-3">
        <a href="/admin/regimes" class="card text-decoration-none">
            <div class="card-body text-center py-3">
                <i class="bi bi-journal-medical fs-2" style="color:#6f2da8"></i>
                <div class="fw-semibold mt-1">Régimes <span class="badge bg-light text-dark"><?= $totalRegimes ?? 0 ?></span></div>
            </div>
        </a>
    </div>
    <div class="col-md-3">
        <a href="/admin/activites" class="card text-decoration-none">
            <div class="card-body text-center py-3">
                <i class="bi bi-bicycle fs-2 text-success"></i>
                <div class="fw-semibold mt-1">Activités <span class="badge bg-light text-dark"><?= $totalActivites ?? 0 ?></span></div>
            </div>
        </a>
    </div>
    <div class="col-md-3">
        <a href="/admin/codes" class="card text-decoration-none">
            <div class="card-body text-center py-3">
                <i class="bi bi-qr-code fs-2 text-info"></i>
                <div class="fw-semibold mt-1">Codes <span class="badge bg-success"><?= $codeStats['available'] ?? 0 ?> dispo</span></div>
            </div>
        </a>
    </div>
    <div class="col-md-3">
        <a href="/admin/users" class="card text-decoration-none">
            <div class="card-body text-center py-3">
                <i class="bi bi-people fs-2 text-warning"></i>
                <div class="fw-semibold mt-1">Utilisateurs <span class="badge bg-warning text-dark"><?= $goldUsers ?> Gold</span></div>
            </div>
        </a>
    </div>
</div>

<?= $this->endSection() ?>
<?= $this->section('scripts') ?>
<script>
const purple = '#6f2da8', purpleLight = 'rgba(111,45,168,.15)';

new Chart(document.getElementById('genreChart'), {
    type: 'doughnut',
    data: {
        labels: ['Hommes', 'Femmes', 'Autres'],
        datasets: [{ data: [<?= $hommes ?? 0 ?>, <?= $femmes ?? 0 ?>, <?= max(0, ($totalUsers ?? 0) - ($hommes ?? 0) - ($femmes ?? 0)) ?>],
            backgroundColor: ['#0d6efd','#e83e8c','#6c757d'] }]
    },
    options: { plugins: { legend: { position: 'bottom' } }, cutout: '65%' }
});

const objData = <?= json_encode($objectifStats ?? []) ?>;
new Chart(document.getElementById('objChart'), {
    type: 'doughnut',
    data: {
        labels: objData.map(o => o.objectif === 'reduire' ? 'Perdre' : o.objectif === 'augmenter' ? 'Prendre' : 'IMC idéal'),
        datasets: [{ data: objData.map(o => parseInt(o.total)), backgroundColor: ['#dc3545','#198754','#0d6efd'] }]
    },
    options: { plugins: { legend: { position: 'bottom' } }, cutout: '65%' }
});

const revData = <?= json_encode($monthlyRevenue ?? array_fill(0, 12, 0)) ?>;
new Chart(document.getElementById('revChart'), {
    type: 'bar',
    data: {
        labels: ['Jan','Fév','Mar','Avr','Mai','Jui','Jul','Aoû','Sep','Oct','Nov','Déc'],
        datasets: [{ label: 'Ar', data: revData,
            backgroundColor: purpleLight, borderColor: purple, borderWidth: 2 }]
    },
    options: { plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true } } }
});
</script>
<?= $this->endSection() ?>
