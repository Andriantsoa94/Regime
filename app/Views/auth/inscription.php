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
        
    </form>
</body>
</html>