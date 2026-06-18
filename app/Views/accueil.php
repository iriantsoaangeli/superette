<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Choisir une caisse — Superette</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>

<header id="app-header">
    <a href="<?= site_url('accueil') ?>" class="header-brand">
        <span class="brand-dot"></span> Superette
    </a>
    <div class="header-user">
        👤 <?= esc(session()->get('user_nom') ?? 'Caissier') ?>
    </div>
    <div class="header-right">
        <a href="<?= site_url('/') ?>" class="btn-choisir-caisse">🚪 Déconnexion</a>
    </div>
</header>

<div class="page-wrapper">
    <h2 class="page-title">🖥️ Sélection de la caisse</h2>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="flash-error">⚠️ <?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>

    <div class="card">
        <div class="card__title">Caisses disponibles</div>

        <?php if (!empty($caisses) && is_array($caisses)): ?>
            <form action="<?= site_url('caisse/selectionner') ?>" method="post" id="form-caisse">
                <?= csrf_field() ?>
                <input type="hidden" name="caisse_id" id="caisse_id_input" value="">

                <div class="caisse-grid">
                    <?php foreach ($caisses as $caisse): ?>
                        <div class="caisse-card"
                             onclick="selectionnerCaisse(<?= $caisse['id'] ?>, this)">
                            <div class="caisse-icon">🖥️</div>
                            <div class="caisse-name"><?= esc($caisse['nom_caisse']) ?></div>
                            <div class="caisse-status">● Ouverte</div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div style="margin-top:1.5rem; display:flex; justify-content:flex-end;">
                    <button type="submit" class="btn btn-success" id="btn-valider" disabled>
                        ✅ Ouvrir la session de caisse
                    </button>
                </div>
            </form>
        <?php else: ?>
            <div class="flash-warning">⚠️ Aucune caisse ouverte disponible.</div>
        <?php endif; ?>
    </div>
</div>

<script>
function selectionnerCaisse(id, el) {
    document.querySelectorAll('.caisse-card').forEach(c => c.classList.remove('selected'));
    el.classList.add('selected');
    document.getElementById('caisse_id_input').value = id;
    document.getElementById('btn-valider').disabled = false;
}
</script>

</body>
</html>
