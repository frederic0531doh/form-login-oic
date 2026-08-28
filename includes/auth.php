<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

require __DIR__ . '/../Database.php';

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

$userInitial = strtoupper(substr($user['username'], 0, 1));
