<?php
session_start();
require '../Database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($email === '' || $password === '') {
        $_SESSION['login_error'] = "Veuillez renseigner votre e-mail et votre mot de passe.";
        $_SESSION['old_login_email'] = $email;
        header('Location: ../index.php');
        exit();
    }

    $db = new Database();
    $user = $db->query("SELECT * FROM users WHERE email = ?", [$email])->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        // L'utilisateur est authentifié avec succès
        $_SESSION['user_id'] = $user['id_user'];
        $_SESSION['full_name'] = $user['full_name'];
        header('Location: ../dashboard.php'); // Rediriger vers la page du tableau de bord
        exit();
    } else {
        // L'authentification a échoué
        $_SESSION['login_error'] = "Adresse e-mail ou mot de passe incorrect.";
        $_SESSION['old_login_email'] = $email;
        header('Location: ../index.php');
        exit();
    }
}

header('Location: ../index.php');
exit();
