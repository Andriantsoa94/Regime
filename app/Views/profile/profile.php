

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    
<h1>Mon Profil</h1>

<form action="/profile/update" method="post">

    <label>Nom</label>
    <br>
    <input type="text" name="nom" value="<?= $user['nom'] ?>">
    <br>
    <label>Email</label>
    <br>
    <input type="email" name="email" value="<?= $user['email'] ?>">
    <br>
    <label>Genre</label>
    <br>
    <select name="genre">
        <option value="homme" <?= $user['genre'] == 'homme' ? 'selected' : '' ?>>
            Homme
        </option>
        <option value="femme" <?= $user['genre'] == 'femme' ? 'selected' : '' ?>>
            Femme
        </option>
        <option value="autre" <?= $user['genre'] == 'autre' ? 'selected' : '' ?>>
            Autre
        </option>

    </select>

    <br>
    <label>Objectif</label>
    <br>
    <textarea name="objectif"><?= $user['objectif'] ?></textarea>

    <br>
    <label>Taille (cm)</label>
    <br>
    <input type="number" step="0.01" name="taille" value="<?= $sante['taille'] ?? '' ?>">
    <br>
    <label>Poids (kg)</label>
    <br>
    <input type="number" step="0.01" name="poids" value="<?= $sante['poids'] ?? '' ?>">
    <br>
    <?php if ($imc): ?>

        <h3>
            IMC :
            <?= number_format($imc, 2) ?>
        </h3>

    <?php endif; ?>

    <button type="submit">
        Modifier Profil
    </button>

</form>
</body>
</html>
