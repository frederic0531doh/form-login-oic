<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit();
}

require '../Database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../documents.php');
    exit();
}

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
$db = new Database();
$document = $id
    ? $db->query(
        "SELECT * FROM documents WHERE id_document = ? AND id_user = ?",
        [$id, $_SESSION['user_id']]
    )->fetch(PDO::FETCH_ASSOC)
    : null;

if (!$document) {
    $_SESSION['upload_error'] = 'Document introuvable.';
    header('Location: ../documents.php');
    exit();
}

$path = '../uploads/' . $document['company_slug'] . '/' . $document['stored_name'];

if (is_file($path)) {
    unlink($path);
}

$db->query("DELETE FROM documents WHERE id_document = ?", [$id]);

$_SESSION['upload_success'] = 'Le document a été supprimé.';
header('Location: ../documents.php');
exit();
