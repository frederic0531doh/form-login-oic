<?php
session_start();
require '../Database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = trim($_POST['full_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    $_SESSION['active_panel'] = 'register';
    $_SESSION['old_register_name'] = $full_name;
    $_SESSION['old_register_email'] = $email;

    if ($full_name === '' || $email === '' || $password === '') {
        $_SESSION['register_error'] = "Veuillez remplir tous les champs.";
        header('Location: ../index.php');
        exit();
    }

    $db = new Database();

    // Vérifier si l'utilisateur existe déjà dans la base de données
    $existingUser = $db->query("SELECT * FROM users WHERE email = ?", [$email])->fetch(PDO::FETCH_ASSOC);

    if ($existingUser) {
        // L'utilisateur existe déjà, afficher un message d'erreur
        $_SESSION['register_error'] = "Cet utilisateur existe déjà.";
    } else {
        // Insérer le nouvel utilisateur dans la base de données
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $db->query(
            "INSERT INTO users (full_name, email, password) VALUES (?, ?, ?)",
            [$full_name, $email, $hashedPassword]
        );
        unset($_SESSION['old_register_name'], $_SESSION['old_register_email']);
        $_SESSION['register_success'] = "Inscription réussie ! Vous pouvez maintenant vous connecter.";
    }

    header('Location: ../index.php');
    exit();
}

header('Location: ../index.php');
exit();
