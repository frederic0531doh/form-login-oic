<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit();
}

require '../Database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../partners.php');
    exit();
}

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
$db = new Database();
$partner = $id
    ? $db->query(
        "SELECT id_partner FROM partners WHERE id_partner = ? AND id_user = ?",
        [$id, $_SESSION['user_id']]
    )->fetch(PDO::FETCH_ASSOC)
    : null;

if (!$partner) {
    $_SESSION['partner_error'] = 'Partenaire introuvable.';
    header('Location: ../partners.php');
    exit();
}

$db->query("DELETE FROM partners WHERE id_partner = ?", [$id]);

$_SESSION['partner_success'] = 'Le partenaire a été supprimé.';
header('Location: ../partners.php');
exit();
