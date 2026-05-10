<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
    <div class="container">
        <h1>Connexion</h1>
        <?php $flashError = session()->getFlashdata('error'); ?>
        <?php if (!empty($flashError)) : ?>
            <div class="alert error">
                <?= esc($flashError) ?>
            </div>
        <?php endif; ?>
        <?php if (isset($erreur)) : ?>
            <div class="alert error">
                <?= esc($erreur) ?>
            </div>
        <?php endif; ?>
        <form action="/login" method="post">
            <?= csrf_field() ?>
            <label for="email">Email :</label>
            <input type="email" name="email" id="email" required>
            <label for="pass">Mot de passe :</label>
            <input type="password" name="pass" id="pass" required>
            <button type="submit">Se connecter</button>
        </form>
    </div>
</body>
</html>