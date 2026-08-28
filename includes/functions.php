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
