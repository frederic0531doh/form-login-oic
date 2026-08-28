<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit();
}

require '../Database.php';
require '../includes/functions.php';

function redirect_with_error(string $message): void
{
    $_SESSION['upload_error'] = $message;
    header('Location: ../documents.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../documents.php');
    exit();
}

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    redirect_with_error('Document invalide.');
}

$db = new Database();
$document = $db->query(
    "SELECT * FROM documents WHERE id_document = ? AND id_user = ?",
    [$id, $_SESSION['user_id']]
)->fetch(PDO::FETCH_ASSOC);

if (!$document) {
    redirect_with_error('Document introuvable.');
}

if (!isset($_FILES['document'])) {
    redirect_with_error('Veuillez sélectionner un fichier.');
}

$documentName = trim($_POST['document_name'] ?? '');

if ($documentName === '') {
    redirect_with_error('Veuillez indiquer un nom pour le document.');
}

$file = $_FILES['document'];
$error = validate_document_file($file);

if ($error !== null) {
    redirect_with_error($error);
}

$uploadDir = '../uploads/' . $document['company_slug'] . '/';

if (!is_dir($uploadDir) && !mkdir($uploadDir, 0755, true) && !is_dir($uploadDir)) {
    redirect_with_error("Impossible d'accéder au répertoire de l'entreprise.");
}

$extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
$originalName = build_document_name($documentName, $extension);
$storedName = uniqid('doc_', true) . '.' . $extension;
$destination = $uploadDir . $storedName;

if (!move_uploaded_file($file['tmp_name'], $destination)) {
    redirect_with_error("Impossible d'enregistrer le fichier.");
}

$oldPath = $uploadDir . $document['stored_name'];
if (is_file($oldPath)) {
    unlink($oldPath);
}

$db->query(
    "UPDATE documents SET original_name = ?, stored_name = ?, file_size = ?, uploaded_at = CURRENT_TIMESTAMP WHERE id_document = ?",
    [$originalName, $storedName, $file['size'], $id]
);

$_SESSION['upload_success'] = 'Le document a été remplacé avec succès.';
header('Location: ../documents.php');
exit();
