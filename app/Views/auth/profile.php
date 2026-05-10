<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Profil</title>
</head>
<body>
    <div class="container">
        <?php $success = session()->getFlashdata('success'); ?>
        <?php if (!empty($success)) : ?>
            <div class="alert alert-success">
                <?= esc($success) ?>
            </div>
        <?php endif; ?>

        <?php $error = session()->getFlashdata('error'); ?>
        <?php if (!empty($error)) : ?>
            <div class="alert alert-error">
                <?= esc($error) ?>
            </div>
        <?php endif; ?>

        <h1>Mon Profil</h1>

        <form action="/profile/update" method="post">
            <?= csrf_field() ?>

            <div class="form-group">
                <label for="nom">Nom :</label>
                <input type="text" name="nom" id="nom" value="<?= esc($user['nom'] ?? '') ?>" required>
            </div>

            <div class="form-group">
                <label for="email">Email :</label>
                <input type="email" name="email" id="email" value="<?= esc($user['email'] ?? '') ?>" required>
            </div>

            <div class="form-group">
                <label for="genre">Genre :</label>
                <select name="genre" id="genre" required>
                    <option value="">-- Sélectionner --</option>
                    <option value="homme" <?= ($user['genre'] ?? '') === 'homme' ? 'selected' : '' ?>>Homme</option>
                    <option value="femme" <?= ($user['genre'] ?? '') === 'femme' ? 'selected' : '' ?>>Femme</option>
                    <option value="autre" <?= ($user['genre'] ?? '') === 'autre' ? 'selected' : '' ?>>Autre</option>
                </select>
            </div>

            <div class="form-group">
                <label for="objectif">Objectif :</label>
                <textarea name="objectif" id="objectif"><?= esc($user['objectif'] ?? '') ?></textarea>
            </div>

            <div class="form-group">
                <label for="password">Nouveau mot de passe:</label>
                <input type="password" name="password" id="password">
            </div>

            <button type="submit">Mettre à jour le profil</button>
        </form>

        <div class="back-link">
            <a href="/home">Retour à l'accueil</a>
        </div>
    </div>
</body>
</html>
