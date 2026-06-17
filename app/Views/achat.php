<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <div id="saisir-achat">
        <form>
            <h1>Produit</h1>
            <select name="produit" id="produit">
                <option value="" id="selection-produit">Sélectionnez un produit</option>
                <?php foreach ($produits as $produit): ?>
                    <option value="<?= $produit['id'] ?>" qtte="<?= $produit['quantite_stock'] ?>">
                        <?= $produit['designation'] ?></option>
                <?php endforeach; ?>
            </select>
            <input type="number" name="qtte" id="qtte" min="1" placeholder="Quantité">
            <input type="submit" onclick="ajouterLigne()" value="Ajouter" id="saisir-confirmer">
        </form>
    </div>
    <div id="table-achat">

    </div>
</body>
</html>
<script src="/js/achat.js"></script>