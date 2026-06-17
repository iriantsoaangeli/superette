<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil - Sélection de la Caisse</title>
</head>
<body>

    <h1>Gestion de la Caisse - Supermarché</h1>
    <p>Veuillez sélectionner la caisse sur laquelle vous allez travailler aujourd'hui.</p>

    <?php if (session()->getFlashdata('error')) : ?>
        <div style="color: red; margin-bottom: 15px;">
            <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>

    <form action="<?= site_url('caisse/selectionner') ?>" method="post">
        <?= csrf_field() ?> <label for="caisse_id">Choisir une caisse active :</label>
        <select name="caisse_id" id="caisse_id" required>
            <option value="">-- Sélectionnez une caisse --</option>
            
            <?php if (!empty($caisses) && is_array($caisses)): ?>
                <?php foreach ($caisses as $caisse): ?>
                    <option value="<?= esc($caisse['id']) ?>">
                        <?= esc($caisse['nom_caisse']) ?> (<?= esc($caisse['statut']) ?>)
                    </option>
                <?php endforeach; ?>
            <?php else: ?>
                <option value="" disabled>Aucune caisse ouverte disponible</option>
            <?php endif; ?>
            
        </select>

        <button type="submit">Ouvrir la session de caisse</button>
    </form>

</body>
</html>