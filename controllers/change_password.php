<?php
session_start();
require '../Database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $oldPassword = $_POST['old_password'] ?? '';
    $newPassword = $_POST['new_password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    $db = new Database();
    $user = $db->query("SELECT * FROM users WHERE id_user = ?", [$_SESSION['user_id']])->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        session_unset();
        session_destroy();
        header('Location: ../index.php');
        exit();
    }

    if (!password_verify($oldPassword, $user['password'])) {
        $_SESSION['change_password_error'] = "L'ancien mot de passe est incorrect.";
        header('Location: ../change_password.php');
        exit();
    }

    if (strlen($newPassword) < 8) {
        $_SESSION['change_password_error'] = "Le nouveau mot de passe doit contenir au moins 8 caractères.";
        header('Location: ../change_password.php');
        exit();
    }

    if ($newPassword !== $confirmPassword) {
        $_SESSION['change_password_error'] = "Les mots de passe ne correspondent pas.";
        header('Location: ../change_password.php');
        exit();
    }

    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
    $db->query(
        "UPDATE users SET password = ?, must_change_password = 0 WHERE id_user = ?",
        [$hashedPassword, $user['id_user']]
    );

    header('Location: ../dashboard.php');
    exit();
}

header('Location: ../change_password.php');
exit();
