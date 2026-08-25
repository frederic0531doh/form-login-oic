<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

require 'Database.php';

$db = new Database();
$user = $db->query("SELECT * FROM users WHERE id_user = ?", [$_SESSION['user_id']])->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    session_unset();
    session_destroy();
    header('Location: index.php');
    exit();
}

if ((int) $user['must_change_password'] === 1) {
    header('Location: change_password.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="assets/css/dashboard.css">
    <title>Tableau de bord | OIC</title>
    <link rel="shortcut icon" href="assets/img/logo-oic.jpg" type="image/x-icon">
</head>

<body>

    <div class="dashboard-container">
        <header class="dashboard-header">
            <img src="assets/img/logo-oic.jpg" height="80px" alt="Logo OIC">
            <a href="controllers/logout.php" class="logout-btn">
                <i class="fa-solid fa-right-from-bracket"></i> Déconnexion
            </a>
        </header>

        <main>
            <div class="welcome-card">
                <div class="avatar"><?= htmlspecialchars(strtoupper(substr($user['username'], 0, 1))) ?></div>
                <h1>Bienvenue, <?= htmlspecialchars($user['username']) ?> !</h1>
                <p>Heureux de vous revoir sur votre espace personnel OIC.</p>
            </div>

            <div class="info-grid">
                <div class="info-card">
                    <i class="fa-solid fa-building"></i>
                    <div>
                        <span class="label">Raison sociale</span>
                        <span class="value"><?= htmlspecialchars($user['company_name']) ?></span>
                    </div>
                </div>
                <div class="info-card">
                    <i class="fa-solid fa-id-badge"></i>
                    <div>
                        <span class="label">Nom d'utilisateur</span>
                        <span class="value"><?= htmlspecialchars($user['username']) ?></span>
                    </div>
                </div>
                <div class="info-card">
                    <i class="fa-solid fa-envelope"></i>
                    <div>
                        <span class="label">Adresse mail</span>
                        <span class="value"><?= htmlspecialchars($user['email']) ?></span>
                    </div>
                </div>
                <div class="info-card">
                    <i class="fa-solid fa-hashtag"></i>
                    <div>
                        <span class="label">Identifiant</span>
                        <span class="value">#<?= htmlspecialchars($user['id_user']) ?></span>
                    </div>
                </div>
            </div>
        </main>
    </div>

</body>

</html>
