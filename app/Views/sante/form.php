<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Infos sante</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
    <div class="container">
        <h1>Informations sante</h1>
        <?php $success = session()->getFlashdata('success'); ?>
        <?php if (!empty($success)) : ?>
            <div class="alert success">
                <?= esc($success) ?>
            </div>
        <?php endif; ?>

        <?php $errors = session('errors') ?? []; ?>
        <?php if (!empty($errors)) : ?>
            <div class="alert error">
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

        <form action="/sante" method="post">
            <?= csrf_field() ?>

            <label for="taille">Taille (cm) :</label>
            <input type="number" step="0.01" name="taille" id="taille" value="<?= esc($tailleValue) ?>">

            <label for="poids">Poids (kg) :</label>
            <input type="number" step="0.01" name="poids" id="poids" value="<?= esc($poidsValue) ?>">

            <div class="objectif-group">
                <label>Mon objectif</label>
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

            <div class="buttons">
                
                <a href='/inscription'>Retour</a>
                <button type="submit">Acceder au sites</button>
            </div>
        </form>
    </div>
</body>
</html>