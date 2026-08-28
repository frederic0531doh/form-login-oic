<?php

// Construit un nom de répertoire sûr à partir de la raison sociale : retire les
// caractères invalides pour un système de fichiers et empêche toute tentative
// de traversée de répertoire (ex. "..").
function sanitize_directory_name(string $name): string
{
    $name = trim($name);
    $name = preg_replace('/[\/\\\\:\*\?"<>\|\x00-\x1F]/', '', $name);
    $name = str_replace('..', '', $name);
    $name = trim($name, " .\t\n\r\0\x0B");

    return $name !== '' ? $name : 'entreprise';
}

function format_file_size(int $bytes): string
{
    if ($bytes >= 1048576) {
        return round($bytes / 1048576, 2) . ' Mo';
    }
    if ($bytes >= 1024) {
        return round($bytes / 1024, 1) . ' Ko';
    }

    return $bytes . ' o';
}

const DOCUMENT_MAX_FILE_SIZE = 10 * 1024 * 1024; // 10 Mo
const DOCUMENT_ALLOWED_EXTENSIONS = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'txt', 'zip', 'jpg', 'jpeg', 'png'];

// Valide un fichier envoyé via $_FILES et renvoie un message d'erreur, ou null si tout va bien.
function validate_document_file(array $file): ?string
{
    if ($file['error'] !== UPLOAD_ERR_OK) {
        return "Une erreur est survenue pendant l'envoi du fichier.";
    }

    if ($file['size'] > DOCUMENT_MAX_FILE_SIZE) {
        return 'Le fichier dépasse la taille maximale autorisée (10 Mo).';
    }

    $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

    if (!in_array($extension, DOCUMENT_ALLOWED_EXTENSIONS, true)) {
        return 'Type de fichier non autorisé.';
    }

    return null;
}

// Nettoie le nom saisi par l'utilisateur et y ajoute l'extension réelle du fichier si besoin.
function build_document_name(string $documentName, string $extension): string
{
    $documentName = preg_replace('/[\x00-\x1F\x7F]/', '', trim($documentName));

    if (strtolower(substr($documentName, -strlen($extension) - 1)) !== '.' . $extension) {
        $documentName .= '.' . $extension;
    }

    return $documentName;
}
