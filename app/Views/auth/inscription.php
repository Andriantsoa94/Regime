<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
</head>
<body>
    <form action="/inscrire" method="post">
        <?= csrf_field() ?>
        <label for="name">Name :</label><br>
        <input type="name" name="name" id="name"><br>
        <label for="email">Email :</label><br>
        <input type="email" name="email" id="email"><br>
        <label for="pass">Password :</label><br>
        <input type="password" name="pass" id="pass"><br>
        <button type="submit">Join Regime</button>
    </form>
</body>
</html>