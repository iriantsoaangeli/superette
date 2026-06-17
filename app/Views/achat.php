<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="/css/style.css">
</head>

<body>
    <?php if (!empty($caisse_nom)): ?>
        <div id="caisse-selectionnee">
            <strong>Caisse choisie :</strong> <?= esc($caisse_nom) ?>
        </div>
    <?php endif; ?>

    <div id="saisir-achat">
        <form class="app-panel">
            <h1>Produit</h1>
            <button onclick="resetTable(document.getElementById('table-achat'))" id="resetBtn">Reset</button>
            <select name="produit" id="produit">
                <option value="" id="selection-produit">Sélectionnez un produit</option>
                <?php foreach ($produits as $produit): ?>
                    <option value="<?= $produit['id'] ?>" qtte="<?= $produit['quantite_stock'] ?>" prix="<?= $produit['prix'] ?>"><?= $produit['designation'] ?></option>
                    <?= $produit['designation'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <input type="number" name="qtte" id="qtte" min="1" placeholder="Quantité">
            <input type="submit"
                onclick="ajouterLigne(document.getElementById('table-achat'), document.getElementById('produit'), document.getElementById('qtte'))"
                value="Ajouter" id="saisir-confirmer">
        </form>
    </div>
    <div>
        <table id="table-achat" class="app-table">
            <thead>
                <tr>
                    <th>Libellé</th>
                    <th>Prix</th>
                    <th>Quantité</th>
                    <th>Total</th>
                    <th style="display:none">ids</th>
                </tr>
            </thead>
            <tbody id="table-body">
                <!-- Les lignes seront ajoutées ici par JavaScript -->
            </tbody>
        </table>
    </div>
</body>

</html>
<script src="/js/achat-table.js"></script>
<script src="/js/achat.js"></script>
