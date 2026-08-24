<?php
session_start();

$loginError = $_SESSION['login_error'] ?? null;
$registerError = $_SESSION['register_error'] ?? null;
$registerSuccess = $_SESSION['register_success'] ?? null;
$activePanel = $_SESSION['active_panel'] ?? null;
$oldLoginEmail = $_SESSION['old_login_email'] ?? '';
$oldRegisterName = $_SESSION['old_register_name'] ?? '';
$oldRegisterEmail = $_SESSION['old_register_email'] ?? '';

unset(
    $_SESSION['login_error'],
    $_SESSION['register_error'],
    $_SESSION['register_success'],
    $_SESSION['active_panel'],
    $_SESSION['old_login_email'],
    $_SESSION['old_register_name'],
    $_SESSION['old_register_email']
);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <title>Page de connexion | OIC</title>
    <link rel="shortcut icon" href="assets/img/logo-oic.jpg" type="image/x-icon">
</head>

<body>

    <div class="container<?= $activePanel === 'register' ? ' active' : '' ?>" id="container">
        <div class="form-container sign-up">
            <form method="post" action="controllers/register.php">
                <h1>Créer un compte</h1>
                <?php if ($registerError): ?>
                    <div class="alert alert-error"><?= htmlspecialchars($registerError) ?></div>
                <?php endif; ?>
                <?php if ($registerSuccess): ?>
                    <div class="alert alert-success"><?= htmlspecialchars($registerSuccess) ?></div>
                <?php endif; ?>
                <input type="text" name="full_name" placeholder="Nom complet" value="<?= htmlspecialchars($oldRegisterName) ?>" required />
                <input type="email" name="email" placeholder="Adresse mail" value="<?= htmlspecialchars($oldRegisterEmail) ?>" required />
                <input type="password" name="password" placeholder="Mot de passe" required />
                <button>Inscription</button>
            </form>
        </div>
        <div class="form-container sign-in">
            <form method="post" action="controllers/login.php">
                <h1>Se connecter</h1>
                <?php if ($loginError): ?>
                    <div class="alert alert-error"><?= htmlspecialchars($loginError) ?></div>
                <?php endif; ?>
                <input type="email" name="email" placeholder="Adresse mail" value="<?= htmlspecialchars($oldLoginEmail) ?>" required />
                <input type="password" name="password" placeholder="Mot de passe" required />
                <a href="#">Vous avez oublié votre mot de passe ?</a>
                <button>Connexion</button>
            </form>
        </div>
        <div class="toggle-container">
            <div class="toggle">
                <div class="toggle-panel toggle-left">
                    <img src="assets/img/logo-oic-light.png" height="70px" alt="Logo OIC light">
                    <p>Saisissez vos informations personnelles afin d'accéder à l'ensemble des fonctionnalités du site.</p>
                    <button class="hidden" id="login">Se connecter</button>
                </div>
                <div class="toggle-panel toggle-right">
                    <img src="assets/img/logo-oic-light.png" height="70px" alt="Logo OIC light">
                    <br/>
                    <p>Inscrivez-vous en renseignant vos informations.</p>
                    <button class="hidden" id="register">Créer un compte</button>
                </div>
            </div>
        </div>
    </div>

    <script src="assets/js/script.js"></script>
</body>

</html>
