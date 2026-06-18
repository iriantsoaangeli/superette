<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion — Superette</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body class="login-page">

    <div class="login-card">
        <div class="login-logo">
            <div class="logo-icon">🛒</div>
            <h1>Superette</h1>
            <p>Système de caisse</p>
        </div>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="flash-error">⚠️ <?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>

        <hr class="login-divider">

        <form action="<?= site_url('connexion') ?>" method="post">
            <?= csrf_field() ?>

            <div class="form-group">
                <label for="username">Identifiant</label>
                <input type="text" name="username" id="username"
                       placeholder="Ex : admin" required
                       value="<?= old('username') ?>">
            </div>

            <div class="form-group">
                <label for="password">Mot de passe</label>
                <input type="password" name="password" id="password"
                       placeholder="••••••••" required>
            </div>

            <button type="submit" class="btn btn-primary">
                🔑 Se connecter
            </button>
        </form>

        <div class="login-hint">
            <strong>Comptes de test :</strong><br>
            admin / <strong>admin123</strong> &nbsp;|&nbsp; caissier / <strong>caisse123</strong>
        </div>
    </div>

</body>
</html>
