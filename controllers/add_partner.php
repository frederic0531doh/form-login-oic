<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit();
}

require '../Database.php';

function redirect_with_error(string $message): void
{
    $_SESSION['partner_error'] = $message;
    header('Location: ../partners.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../partners.php');
    exit();
}

$companyName = trim($_POST['company_name'] ?? '');
$contact = trim($_POST['contact'] ?? '');
$email = trim($_POST['email'] ?? '');

if ($companyName === '' || $contact === '' || $email === '') {
    redirect_with_error('Veuillez renseigner la raison sociale, le contact et l\'adresse mail.');
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    redirect_with_error('Adresse mail invalide.');
}

$db = new Database();
$db->query(
    "INSERT INTO partners (id_user, company_name, contact, email) VALUES (?, ?, ?, ?)",
    [$_SESSION['user_id'], $companyName, $contact, $email]
);

$_SESSION['partner_success'] = 'Le partenaire a été ajouté avec succès.';
header('Location: ../partners.php');
exit();
