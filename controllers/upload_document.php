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

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_FILES['document'])) {
    header('Location: ../documents.php');
    exit();
}

$idPartner = filter_input(INPUT_POST, 'id_partner', FILTER_VALIDATE_INT);

if (!$idPartner) {
    redirect_with_error('Veuillez choisir un partenaire.');
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

$db = new Database();
$partner = $db->query(
    "SELECT * FROM partners WHERE id_partner = ? AND id_user = ?",
    [$idPartner, $_SESSION['user_id']]
)->fetch(PDO::FETCH_ASSOC);

if (!$partner) {
    redirect_with_error('Partenaire introuvable.');
}

$companySlug = sanitize_directory_name($partner['company_name']);
$uploadDir = '../uploads/' . $companySlug . '/';

if (!is_dir($uploadDir) && !mkdir($uploadDir, 0755, true) && !is_dir($uploadDir)) {
    redirect_with_error("Impossible de créer le répertoire du partenaire.");
}

$extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
$originalName = build_document_name($documentName, $extension);
$storedName = uniqid('doc_', true) . '.' . $extension;
$destination = $uploadDir . $storedName;

if (!move_uploaded_file($file['tmp_name'], $destination)) {
    redirect_with_error("Impossible d'enregistrer le fichier.");
}

$db->query(
    "INSERT INTO documents (id_user, id_partner, company_slug, original_name, stored_name, file_size) VALUES (?, ?, ?, ?, ?, ?)",
    [$_SESSION['user_id'], $partner['id_partner'], $companySlug, $originalName, $storedName, $file['size']]
);

$_SESSION['upload_success'] = 'Le document a été envoyé avec succès.';
header('Location: ../documents.php');
exit();
