<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tsy aiko – Votre guide nutrition personnalisé</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root { --purple: #6f2da8; --purple-dark: #4a1a7a; --purple-light: #9b59d0; }

        body { font-family: 'Segoe UI', sans-serif; background: #f5f6fa; }

        /* Navbar */
        .navbar-brand { font-weight: 800; font-size: 1.4rem; color: var(--purple) !important; }
        .nav-link { color: #555 !important; font-weight: 500; }
        .nav-link:hover { color: var(--purple) !important; }
        .btn-outline-purple { border: 2px solid var(--purple); color: var(--purple); font-weight: 600; border-radius: 8px; }
        .btn-outline-purple:hover { background: var(--purple); color: #fff; }
        .btn-purple { background: var(--purple); color: #fff; font-weight: 600; border-radius: 8px; border: none; }
        .btn-purple:hover { background: var(--purple-dark); color: #fff; }

        /* Hero */
        .hero {
            background: linear-gradient(135deg, #3d1070, #6f2da8 55%, #9b59d0);
            min-height: 88vh;
            display: flex;
            align-items: center;
            color: #fff;
            position: relative;
            overflow: hidden;
        }
        .hero::before {
            content: '';
            position: absolute;
            width: 600px; height: 600px;
            border-radius: 50%;
            background: rgba(255,255,255,.05);
            top: -200px; right: -200px;
        }
        .hero::after {
            content: '';
            position: absolute;
            width: 400px; height: 400px;
            border-radius: 50%;
            background: rgba(255,255,255,.05);
            bottom: -150px; left: -100px;
        }
        .hero-title { font-size: clamp(2rem, 5vw, 3.2rem); font-weight: 800; line-height: 1.2; }
        .hero-sub { font-size: 1.15rem; opacity: .88; max-width: 500px; }
        .hero-badge {
            display: inline-block;
            background: rgba(255,255,255,.15);
            border: 1px solid rgba(255,255,255,.3);
            border-radius: 50px;
            padding: .3rem 1rem;
            font-size: .85rem;
            margin-bottom: 1rem;
        }

        /* Features */
        .feature-icon {
            width: 64px; height: 64px;
            border-radius: 16px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.7rem;
            background: linear-gradient(135deg, #f3e8ff, #e0ccff);
            color: var(--purple);
            margin-bottom: 1rem;
        }
        .feature-card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(111,45,168,.08);
            transition: transform .2s, box-shadow .2s;
            height: 100%;
        }
        .feature-card:hover { transform: translateY(-4px); box-shadow: 0 8px 30px rgba(111,45,168,.15); }

        /* Stats */
        .stats-section { background: linear-gradient(135deg, #3d1070, #6f2da8); color: #fff; }
        .stat-number { font-size: 2.5rem; font-weight: 800; }

        /* CTA */
        .cta-section { background: #fff; }
        .cta-card {
            background: linear-gradient(135deg, #f3e8ff, #e8d5ff);
            border-radius: 24px;
            border: none;
        }

        /* Footer */
        footer { background: #1a0a30; color: rgba(255,255,255,.6); }
        footer a { color: rgba(255,255,255,.7); text-decoration: none; }
        footer a:hover { color: #fff; }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg bg-white shadow-sm sticky-top">
    <div class="container">
        <a class="navbar-brand" href="/">
            <i class="bi bi-heart-pulse-fill me-1"></i>Tsy aiko
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navMenu">
            <ul class="navbar-nav me-auto gap-1">
                <li class="nav-item"><a class="nav-link" href="#fonctionnalites">Fonctionnalités</a></li>
                <li class="nav-item"><a class="nav-link" href="#comment">Comment ça marche</a></li>
            </ul>
            <div class="d-flex gap-2">
                <a href="/login" class="btn btn-outline-purple">Se connecter</a>
                <a href="/register" class="btn btn-purple">Créer un compte</a>
            </div>
        </div>
    </div>
</nav>

<!-- Hero -->
<section class="hero">
    <div class="container position-relative" style="z-index:1">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <div class="hero-badge">
                    <i class="bi bi-stars me-1"></i>Nutrition personnalisée
                </div>
                <h1 class="hero-title mb-3">
                    Votre régime,<br>
                    <span style="color:#d4a8ff">votre objectif.</span>
                </h1>
                <p class="hero-sub mb-4">
                    Tsy aiko vous accompagne avec des programmes alimentaires adaptés à votre corps, votre mode de vie et vos objectifs de santé.
                </p>
                <div class="d-flex flex-wrap gap-3">
                    <a href="/register" class="btn btn-light fw-bold px-4 py-2" style="border-radius:10px;color:var(--purple)">
                        <i class="bi bi-person-plus me-2"></i>Commencer gratuitement
                    </a>
                    <a href="/login" class="btn btn-outline-light fw-semibold px-4 py-2" style="border-radius:10px">
                        <i class="bi bi-box-arrow-in-right me-2"></i>Se connecter
                    </a>
                </div>
            </div>
            <div class="col-lg-6 text-center d-none d-lg-block">
                <div style="font-size:9rem;opacity:.15;line-height:1">
                    <i class="bi bi-heart-pulse"></i>
                </div>
                <div class="row g-3 mt-n5">
                    <div class="col-6">
                        <div class="card p-3 text-start" style="border-radius:16px;border:none;background:rgba(255,255,255,.12);backdrop-filter:blur(10px);">
                        
                            <div class="fw-bold text-white mt-1">Régimes sur mesure</div>
                            <div style="font-size:.8rem;opacity:.8">Adaptés à votre objectif</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="card p-3 text-start mt-4" style="border-radius:16px;border:none;background:rgba(255,255,255,.12);backdrop-filter:blur(10px);">
                            
                            <div class="fw-bold text-white mt-1">Suivi IMC</div>
                            <div style="font-size:.8rem;opacity:.8">Votre santé en chiffres</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="card p-3 text-start" style="border-radius:16px;border:none;background:rgba(255,255,255,.12);backdrop-filter:blur(10px);">
                            
                            <div class="fw-bold text-white mt-1">Activités physiques</div>
                            <div style="font-size:.8rem;opacity:.8">Complémentaires au régime</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="card p-3 text-start mt-4" style="border-radius:16px;border:none;background:rgba(255,255,255,.12);backdrop-filter:blur(10px);">
                            
                            <div class="fw-bold text-white mt-1">Statut Gold</div>
                            <div style="font-size:.8rem;opacity:.8">Accès premium illimité</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Fonctionnalités -->
<section class="py-6 py-md-7" id="fonctionnalites" style="padding:80px 0">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold" style="color:#1a0a30">Tout ce dont vous avez besoin</h2>
            <p class="text-muted">Une plateforme complète pour atteindre vos objectifs nutritionnels</p>
        </div>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card feature-card p-4">
                    <div class="feature-icon"><i class="bi bi-person-lines-fill"></i></div>
                    <h5 class="fw-bold">Profil santé complet</h5>
                    <p class="text-muted mb-0">Enregistrez votre taille, poids et suivez l'évolution de votre indice de masse corporelle au fil du temps.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card feature-card p-4">
                    <div class="feature-icon"><i class="bi bi-journal-text"></i></div>
                    <h5 class="fw-bold">Régimes personnalisés</h5>
                    <p class="text-muted mb-0">Des programmes alimentaires conçus selon votre objectif : perdre du poids, en prendre ou atteindre l'IMC idéal.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card feature-card p-4">
                    <div class="feature-icon"><i class="bi bi-activity"></i></div>
                    <h5 class="fw-bold">Activités physiques</h5>
                    <p class="text-muted mb-0">Des suggestions d'exercices adaptés à votre objectif pour maximiser les résultats de votre régime.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card feature-card p-4">
                    <div class="feature-icon"><i class="bi bi-wallet2"></i></div>
                    <h5 class="fw-bold">Portefeuille intégré</h5>
                    <p class="text-muted mb-0">Rechargez votre solde et accédez à vos régimes préférés directement depuis la plateforme.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card feature-card p-4">
                    <div class="feature-icon"><i class="bi bi-file-earmark-pdf"></i></div>
                    <h5 class="fw-bold">Export PDF</h5>
                    <p class="text-muted mb-0">Téléchargez votre programme de régime en PDF pour le consulter où que vous soyez, même hors ligne.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card feature-card p-4">
                    <div class="feature-icon"><i class="bi bi-star-fill"></i></div>
                    <h5 class="fw-bold">Abonnement Gold</h5>
                    <p class="text-muted mb-0">Débloquez l'accès illimité à tous les régimes et fonctionnalités premium avec le statut Gold.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Comment ça marche -->
<section style="background:#f0e6ff;padding:80px 0" id="comment">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold" style="color:#1a0a30">Comment ça marche ?</h2>
            <p class="text-muted">En 3 étapes simples, lancez votre parcours nutrition</p>
        </div>
        <div class="row g-4 justify-content-center">
            <div class="col-md-4 text-center">
                <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3 fw-bold text-white" style="width:56px;height:56px;background:var(--purple);font-size:1.3rem">1</div>
                <h5 class="fw-bold">Créez votre compte</h5>
                <p class="text-muted">Inscrivez-vous en 2 minutes avec vos informations personnelles et vos données de santé.</p>
            </div>
            <div class="col-md-4 text-center">
                <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3 fw-bold text-white" style="width:56px;height:56px;background:var(--purple);font-size:1.3rem">2</div>
                <h5 class="fw-bold">Choisissez votre objectif</h5>
                <p class="text-muted">Définissez si vous souhaitez réduire, augmenter ou stabiliser votre poids.</p>
            </div>
            <div class="col-md-4 text-center">
                <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3 fw-bold text-white" style="width:56px;height:56px;background:var(--purple);font-size:1.3rem">3</div>
                <h5 class="fw-bold">Suivez votre programme</h5>
                <p class="text-muted">Accédez à vos régimes personnalisés et suivez vos progrès depuis votre tableau de bord.</p>
            </div>
        </div>
    </div>
</section>

<!-- CTA -->
<section class="cta-section" style="padding:80px 0">
    <div class="container">
        <div class="card cta-card p-5 text-center">
            <h2 class="fw-bold mb-3" style="color:#1a0a30">Prêt à transformer votre alimentation ?</h2>
            <p class="text-muted mb-4 fs-5">Rejoignez NutriPlan et commencez votre parcours vers une meilleure santé dès aujourd'hui.</p>
            <div class="d-flex justify-content-center gap-3 flex-wrap">
                <a href="/register" class="btn btn-purple btn-lg px-5">
                    <i class="bi bi-person-plus me-2"></i>Créer mon compte
                </a>
                <a href="/login" class="btn btn-outline-purple btn-lg px-5">
                    <i class="bi bi-box-arrow-in-right me-2"></i>Se connecter
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Footer -->
<footer style="padding:40px 0">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6 mb-3 mb-md-0">
                <div class="fw-bold text-white mb-1" style="font-size:1.2rem">
                    <i class="bi bi-heart-pulse-fill me-1" style="color:#9b59d0"></i>Tsy aiko
                </div>
                <small>Votre espace nutrition personnalisé</small>
            </div>
            <div class="col-md-6 text-md-end">
                <small>&copy; <?= date('Y') ?> Tsy aiko. Tous droits réservés.</small>
            </div>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
