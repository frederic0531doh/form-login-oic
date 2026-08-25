<?php
session_start();

// Erreurs de connexion : gérées via la session (une session est déjà nécessaire pour authentifier l'utilisateur).
$loginError = $_SESSION['login_error'] ?? null;
$oldLoginEmail = $_SESSION['old_login_email'] ?? '';

unset($_SESSION['login_error'], $_SESSION['old_login_email']);

// Erreurs/succès d'inscription : transmis via l'URL, car l'inscription ne démarre pas de session.
$registerErrorMessages = [
    'fields' => "Veuillez remplir tous les champs.",
    'invalid_email' => "Adresse e-mail invalide.",
    'exists' => "Cet utilisateur existe déjà.",
];
$registerError = $registerErrorMessages[$_GET['reg_error'] ?? ''] ?? null;
$registerSuccess = isset($_GET['reg_success'])
    ? "Inscription réussie ! Un mot de passe temporaire vous a été envoyé par e-mail."
    : null;
$activePanel = $_GET['panel'] ?? null;
$oldCompanyName = $_GET['old_company_name'] ?? '';
$oldUsername = $_GET['old_username'] ?? '';
$oldRegisterEmail = $_GET['old_email'] ?? '';
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
                <input type="text" name="company_name" placeholder="Raison sociale" value="<?= htmlspecialchars($oldCompanyName) ?>" required />
                <input type="text" name="username" placeholder="Nom d'utilisateur" value="<?= htmlspecialchars($oldUsername) ?>" required />
                <input type="email" name="email" placeholder="Adresse mail" value="<?= htmlspecialchars($oldRegisterEmail) ?>" required />
                <button>Inscription</button>
            </form>
        </div>
        <div class="form-container sign-in">
            <form method="post" action="controllers/login.php">
                <h1>Se connecter</h1>
                <?php if ($loginError): ?>
                    <div class="alert alert-error"><?= htmlspecialchars($loginError) ?></div>
                <?php endif; ?>
                <?php if ($registerSuccess): ?>
                    <div class="alert alert-success"><?= htmlspecialchars($registerSuccess) ?></div>
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
