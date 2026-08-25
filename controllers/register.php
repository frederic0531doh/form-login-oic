<?php
// On ne démarre pas de session pendant l'inscription : le visiteur n'est pas
// encore authentifié, donc aucune donnée ne doit être stockée côté session.
// Les messages de retour transitent par l'URL (query string) vers index.php.
require '../Database.php';
require '../Mailer.php';

function redirect_to_register(string $errorCode, array $old): void
{
    header('Location: ../index.php?' . http_build_query(array_merge(
        ['panel' => 'register', 'reg_error' => $errorCode],
        $old
    )));
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $companyName = trim($_POST['company_name'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');

    $old = [
        'old_company_name' => $companyName,
        'old_username' => $username,
        'old_email' => $email,
    ];

    if ($companyName === '' || $username === '' || $email === '') {
        redirect_to_register('fields', $old);
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        redirect_to_register('invalid_email', $old);
    }

    $db = new Database();

    $existingUser = $db->query("SELECT id_user FROM users WHERE email = ?", [$email])->fetch(PDO::FETCH_ASSOC);

    if ($existingUser) {
        redirect_to_register('exists', $old);
    }

    // Mot de passe temporaire généré automatiquement, à changer à la première connexion.
    $generatedPassword = bin2hex(random_bytes(5));
    $hashedPassword = password_hash($generatedPassword, PASSWORD_DEFAULT);

    $db->query(
        "INSERT INTO users (company_name, username, email, password, must_change_password) VALUES (?, ?, ?, ?, 1)",
        [$companyName, $username, $email, $hashedPassword]
    );

    $mailer = new Mailer();
    $mailer->send(
        $email,
        'Votre compte OIC a été créé',
        "Bonjour {$username},\n\n"
            . "Votre compte a été créé avec succès.\n"
            . "Voici votre mot de passe temporaire : {$generatedPassword}\n\n"
            . "Il vous sera demandé de le remplacer par un nouveau mot de passe lors de votre première connexion."
    );

    header('Location: ../index.php?reg_success=1');
    exit();
}

header('Location: ../index.php');
exit();
