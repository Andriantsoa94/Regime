<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
</head>
<body>
    <?php $success = session()->getFlashdata('success'); ?>
    <?php if (!empty($success)) : ?>
        <div style="color: green; margin-bottom: 15px; border: 1px solid green; padding: 10px;">
            <?= esc($success) ?>
        </div>
    <?php endif; ?>

    <?php $error = session()->getFlashdata('error'); ?>
    <?php if (!empty($error)) : ?>
        <div style="color: red; margin-bottom: 15px; border: 1px solid red; padding: 10px;">
            <?= esc($error) ?>
        </div>
    <?php endif; ?>

    <form action="/inscrire" method="post">
        <?= csrf_field() ?>
        <label for="nom">Nom :</label><br>
        <input type="text" name="nom" id="nom" value="<?= old('nom') ?>" required><br>

        <label for="email">Email :</label><br>
        <input type="email" name="email" id="email" value="<?= old('email') ?>" required><br>

        <label for="pass">Mot de passe :</label><br>
        <input type="password" name="pass" id="pass" required><br>

        <button type="submit">S'inscrire</button>
    </form>
</body>
</html>