<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php if (isset($erreur)) : ?>
        <div style="color: red; margin-bottom: 15px; border: 1px solid red; padding: 10px;">
        <?= $erreur ?>
    <?php endif;?>
    <form action="/login" method="post">
        <label for="email">Email :</label><br>
        <input type="email" name="email" id="email"><br>
        <label for="pass">Password :</label><br>
        <input type="password" name="pass" id="pass"><br>
        <button type="submit">Connect To Regime</button>
    </form>
</body>
</html>