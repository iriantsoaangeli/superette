<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Saisie achat — Superette</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>

<!-- ═══════════════════ HEADER ═══════════════════════════ -->
<header id="app-header">
    <a href="<?= site_url('accueil') ?>" class="header-brand">
        <span class="brand-dot"></span> Superette
    </a>
    <div class="header-user">
        👤 <?= esc(session()->get('user_nom') ?? 'Caissier') ?>
    </div>
    <div class="header-right">
        <?php if (!empty($caisse_nom)): ?>
            <span class="caisse-badge">🖥️ <?= esc($caisse_nom) ?></span>
        <?php else: ?>
            <span class="caisse-badge no-caisse">Aucune caisse</span>
        <?php endif; ?>
        <a href="<?= site_url('accueil') ?>" class="btn-choisir-caisse">🔄 Changer de caisse</a>
    </div>
</header>

<div class="page-wrapper">

    <!-- Flash messages -->
    <?php if (session()->getFlashdata('error')): ?>
        <div class="flash-error">⚠️ <?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('success')): ?>
        <div class="flash-success">✅ <?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>

    <h2 class="page-title">🧾 Saisie d'achat</h2>

    <!-- ═══════════════ FORMULAIRE AJOUT ════════════════ -->
    <div id="saisir-achat">
        <div class="app-panel">
            <h1>Ajouter un produit</h1>

            <select name="produit" id="produit">
                <option value="">Sélectionnez un produit…</option>
                <?php foreach ($produits as $produit): ?>
                    <option value="<?= $produit['id'] ?>"
                            qtte="<?= $produit['quantite_stock'] ?>"
                            prix="<?= $produit['prix'] ?>">
                        <?= esc($produit['designation']) ?>
                        (stock : <?= $produit['quantite_stock'] ?>)
                    </option>
                <?php endforeach; ?>
            </select>

            <input type="number" name="qtte" id="qtte" min="1" placeholder="Qté">

            <input type="submit" id="saisir-confirmer" value="＋ Ajouter">

            <button id="resetBtn" title="Vider le panier">🗑 Vider</button>
        </div>
    </div>

    <!-- ═══════════════ TABLEAU ══════════════════════════ -->
    <div class="table-wrapper">
        <table id="table-achat" class="app-table">
            <thead>
                <tr>
                    <th>Libellé</th>
                    <th>Prix unitaire</th>
                    <th>Quantité</th>
                    <th>Total</th>
                    <th style="display:none">ids</th>
                </tr>
            </thead>
            <tbody id="table-body">
                <!-- Lignes ajoutées par JS -->
            </tbody>
        </table>

        <div class="table-footer">
            <div class="total-display">
                Total : <span id="total-general">0.00</span> €
            </div>
            <button type="button" id="cloturer-achat">
                💳 Clôturer l'achat
            </button>
        </div>
    </div>

</div>

<!-- Toast retour AJAX -->
<div id="toast"></div>

<script>
    const AJAX_CLOTURER_URL = "<?= site_url('achat/cloturer') ?>";
</script>
<script src="/js/achat-table.js"></script>
<script src="/js/achat.js"></script>

</body>
</html>
