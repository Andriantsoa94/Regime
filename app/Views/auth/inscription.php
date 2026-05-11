<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
    <div class="container">
        <h1>Inscription</h1>
        <?php $success = session()->getFlashdata('success'); ?>
        <?php if (!empty($success)) : ?>
            <div class="alert success">
                <?= esc($success) ?>
            </div>
        <?php endif; ?>

        <?php $error = session()->getFlashdata('error'); ?>
        <?php if (!empty($error)) : ?>
            <div class="alert error">
                <?= esc($error) ?>
            </div>
        <?php endif; ?>

        <form action="/inscrire" method="post">
            <?= csrf_field() ?>
            <label for="nom">Nom :</label>
            <input type="text" name="nom" id="nom" value="<?= old('nom') ?>" required>

            <label for="email">Email :</label>
            <input type="email" name="email" id="email" value="<?= old('email') ?>" required>

            <div class="radio-group">
                <label>Genre :</label>
                <label>
                    <input type="radio" name="genre" value="homme" id="genre_homme" <?= (old('genre') == 'homme') ? 'checked' : '' ?> required> Homme
                </label>
                <label>
                    <input type="radio" name="genre" value="femme" id="genre_femme" <?= (old('genre') == 'femme') ? 'checked' : '' ?>> Femme
                </label>
                <label>
                    <input type="radio" name="genre" value="autre" id="genre_autre" <?= (old('genre') == 'autre') ? 'checked' : '' ?>> Autre
                </label>
            </div>

            <label for="pass">Mot de passe :</label>
            <input type="password" name="pass" id="pass" required>

            <button type="submit">S'inscrire</button>
        </form>
    </div>
</body>
</html>