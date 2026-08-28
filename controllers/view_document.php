<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit();
}

require '../Database.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    http_response_code(400);
    exit('Document invalide.');
}

$db = new Database();
$document = $db->query(
    "SELECT * FROM documents WHERE id_document = ? AND id_user = ?",
    [$id, $_SESSION['user_id']]
)->fetch(PDO::FETCH_ASSOC);

if (!$document) {
    http_response_code(404);
    exit('Document introuvable.');
}

$path = realpath('../uploads/' . $document['company_slug'] . '/' . $document['stored_name']);
$uploadsRoot = realpath('../uploads');

if ($path === false || $uploadsRoot === false || strpos($path, $uploadsRoot) !== 0 || !is_file($path)) {
    http_response_code(404);
    exit('Fichier introuvable.');
}

$mimeTypes = [
    'pdf' => 'application/pdf',
    'jpg' => 'image/jpeg',
    'jpeg' => 'image/jpeg',
    'png' => 'image/png',
    'txt' => 'text/plain',
];
$extension = strtolower(pathinfo($document['stored_name'], PATHINFO_EXTENSION));
$mimeType = $mimeTypes[$extension] ?? 'application/octet-stream';
$disposition = isset($mimeTypes[$extension]) ? 'inline' : 'attachment';

header('Content-Type: ' . $mimeType);
header('Content-Length: ' . filesize($path));
header('Content-Disposition: ' . $disposition . '; filename="' . str_replace('"', '', $document['original_name']) . '"');
header('X-Content-Type-Options: nosniff');

readfile($path);
exit();
