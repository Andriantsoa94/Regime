<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Infos sante</title>
</head>
<body>
    <?php $success = session()->getFlashdata('success'); ?>
    <?php if (!empty($success)) : ?>
        <div style="color: green; margin-bottom: 15px; border: 1px solid green; padding: 10px;">
            <?= esc($success) ?>
        </div>
    <?php endif; ?>

    <?php $errors = session('errors') ?? []; ?>
    <?php if (!empty($errors)) : ?>
        <div style="color: red; margin-bottom: 15px; border: 1px solid red; padding: 10px;">
            <ul>
                <?php foreach ($errors as $message) : ?>
                    <li><?= esc($message) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <?php
        $genreValue = old('genre') ?? ($user['genre'] ?? '');
        $tailleValue = old('taille') ?? ($sante['taille'] ?? '');
        $poidsValue = old('poids') ?? ($sante['poids'] ?? '');
    ?>

    <h1>Informations sante</h1>
    <form action="/sante" method="post">
        <?= csrf_field() ?>
        <label for="genre">Genre :</label><br>
        <input type="radio" name="genre" value="homme" id="genre_homme" <?= ($genreValue == 'homme') ? 'checked' : '' ?>> <label for="genre_homme">Homme</label><br>
        <input type="radio" name="genre" value="femme" id="genre_femme" <?= ($genreValue == 'femme') ? 'checked' : '' ?>> <label for="genre_femme">Femme</label><br>
        <input type="radio" name="genre" value="autre" id="genre_autre" <?= ($genreValue == 'autre') ? 'checked' : '' ?>> <label for="genre_autre">Autre</label><br>
        <br>

        <label for="taille">Taille (cm) :</label><br>
        <input type="number" step="0.01" name="taille" id="taille" value="<?= esc($tailleValue) ?>"><br>

        <label for="poids">Poids (kg) :</label><br>
        <input type="number" step="0.01" name="poids" id="poids" value="<?= esc($poidsValue) ?>"><br>

        <button type="submit">Enregistrer</button>
    </form>
</body>
</html>
