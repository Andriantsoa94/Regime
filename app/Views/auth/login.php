<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php $flashError = session()->getFlashdata('error'); ?>
    <?php if (!empty($flashError)) : ?>
        <div style="color: red; margin-bottom: 15px; border: 1px solid red; padding: 10px;">
            <?= esc($flashError) ?>
        </div>
    <?php endif; ?>
    <?php if (isset($erreur)) : ?>
        <div style="color: red; margin-bottom: 15px; border: 1px solid red; padding: 10px;">
            <?= esc($erreur) ?>
        </div>
    <?php endif; ?>
    <form action="/login" method="post">
        <?= csrf_field() ?>
        <label for="email">Email :</label><br>
        <input type="email" name="email" id="email"><br>
        <label for="pass">Password :</label><br>
        <input type="password" name="pass" id="pass"><br>
        <button type="submit">Connect To Regime</button>
    </form>
</body>
</html>