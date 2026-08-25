<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

$error = $_SESSION['change_password_error'] ?? null;
unset($_SESSION['change_password_error']);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <title>Nouveau mot de passe | OIC</title>
    <link rel="shortcut icon" href="assets/img/logo-oic.jpg" type="image/x-icon">
</head>

<body>

    <div class="container single-panel">
        <div class="form-container standalone">
            <form method="post" action="controllers/change_password.php">
                <h1>Nouveau mot de passe</h1>
                <p>C'est votre première connexion. Veuillez définir un nouveau mot de passe avant de continuer.</p>
                <?php if ($error): ?>
                    <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>
                <input type="password" name="old_password" placeholder="Ancien mot de passe (temporaire)" required />
                <input type="password" name="new_password" placeholder="Nouveau mot de passe" required />
                <input type="password" name="confirm_password" placeholder="Confirmer le nouveau mot de passe" required />
                <button>Valider</button>
            </form>
        </div>
    </div>

</body>

</html>
