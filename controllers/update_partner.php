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

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
$companyName = trim($_POST['company_name'] ?? '');
$contact = trim($_POST['contact'] ?? '');
$email = trim($_POST['email'] ?? '');

if (!$id) {
    redirect_with_error('Partenaire invalide.');
}

if ($companyName === '' || $contact === '' || $email === '') {
    redirect_with_error('Veuillez renseigner la raison sociale, le contact et l\'adresse mail.');
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    redirect_with_error('Adresse mail invalide.');
}

$db = new Database();
$partner = $db->query(
    "SELECT id_partner FROM partners WHERE id_partner = ? AND id_user = ?",
    [$id, $_SESSION['user_id']]
)->fetch(PDO::FETCH_ASSOC);

if (!$partner) {
    redirect_with_error('Partenaire introuvable.');
}

$db->query(
    "UPDATE partners SET company_name = ?, contact = ?, email = ? WHERE id_partner = ?",
    [$companyName, $contact, $email, $id]
);

$_SESSION['partner_success'] = 'Le partenaire a été mis à jour avec succès.';
header('Location: ../partners.php');
exit();
