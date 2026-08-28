<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit();
}

require '../Database.php';
require '../includes/functions.php';

const MAX_FILE_SIZE = 10 * 1024 * 1024; // 10 Mo
const ALLOWED_EXTENSIONS = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'txt', 'zip', 'jpg', 'jpeg', 'png'];

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

$documentName = trim($_POST['document_name'] ?? '');

if ($documentName === '') {
    redirect_with_error('Veuillez indiquer un nom pour le document.');
}

// Retire les caractères de contrôle (retours à la ligne, etc.) pour éviter
// toute injection dans les en-têtes HTTP lors du téléchargement du fichier.
$documentName = preg_replace('/[\x00-\x1F\x7F]/', '', $documentName);

$file = $_FILES['document'];

if ($file['error'] !== UPLOAD_ERR_OK) {
    redirect_with_error("Une erreur est survenue pendant l'envoi du fichier.");
}

if ($file['size'] > MAX_FILE_SIZE) {
    redirect_with_error('Le fichier dépasse la taille maximale autorisée (10 Mo).');
}

$extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

if (!in_array($extension, ALLOWED_EXTENSIONS, true)) {
    redirect_with_error('Type de fichier non autorisé.');
}

$db = new Database();
$user = $db->query("SELECT * FROM users WHERE id_user = ?", [$_SESSION['user_id']])->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    header('Location: ../index.php');
    exit();
}

$companySlug = sanitize_directory_name($user['company_name']);
$uploadDir = '../uploads/' . $companySlug . '/';

if (!is_dir($uploadDir) && !mkdir($uploadDir, 0755, true) && !is_dir($uploadDir)) {
    redirect_with_error("Impossible de créer le répertoire de l'entreprise.");
}

$originalName = $documentName;
if (strtolower(substr($originalName, -strlen($extension) - 1)) !== '.' . $extension) {
    $originalName .= '.' . $extension;
}
$storedName = uniqid('doc_', true) . '.' . $extension;
$destination = $uploadDir . $storedName;

if (!move_uploaded_file($file['tmp_name'], $destination)) {
    redirect_with_error("Impossible d'enregistrer le fichier.");
}

$db->query(
    "INSERT INTO documents (id_user, company_slug, original_name, stored_name, file_size) VALUES (?, ?, ?, ?, ?)",
    [$user['id_user'], $companySlug, $originalName, $storedName, $file['size']]
);

$_SESSION['upload_success'] = 'Le document a été envoyé avec succès.';
header('Location: ../documents.php');
exit();
