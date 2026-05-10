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

        <label for="taille">Taille (cm) :</label><br>
        <input type="number" step="0.01" name="taille" id="taille" value="<?= esc($tailleValue) ?>"><br>

        <label for="poids">Poids (kg) :</label><br>
        <input type="number" step="0.01" name="poids" id="poids" value="<?= esc($poidsValue) ?>"><br>

        <label>Mon objectif</label>
            <div>
                <div>
                    <label>
                        <input type="radio" name="objectif" value="reduire" required <?= old('objectif')=='reduire'?'checked':'' ?>>
                        <div class="objectif-card">
                            <i></i>
                            <div style="font-size:.8rem">Perdre du poids</div>
                        </div>
                    </label>
                </div>
                <div>
                    <label>
                        <input type="radio" name="objectif" value="augmenter" <?= old('objectif')=='augmenter'?'checked':'' ?>>
                        <div class="objectif-card">
                            <i></i>
                            <div style="font-size:.8rem">Prendre du poids</div>
                        </div>
                    </label>
                </div>
                <div>
                    <label>
                        <input type="radio" name="objectif" value="imc_ideal" <?= old('objectif')=='imc_ideal'?'checked':'' ?>>
                        <div style="font-size:.8rem">IMC ideal</div>
                    </label>
                </div>
            </div>
        <button type="button" onclick="window.location.href='/inscrire'">Retour</button>
        <button type="submit">Enregistrer</button>
    </form>
</body>
</html>