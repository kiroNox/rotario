<?php

$backupDir = '../backup';

if (!is_dir($backupDir)) {
    echo json_encode(['resultado' => 'error', 'mensaje' => 'Directorio de backups no encontrado']);
    exit;
}

$backups = array_diff(scandir($backupDir), ['.', '..']);

$backupList = [];

foreach ($backups as $backup) {
    if (is_file("$backupDir/$backup")) {
        $backupList[] = [
            'filename' => $backup,
            'filepath' => "$backupDir/$backup",
            'filesize' => filesize("$backupDir/$backup"),
            'filemtime' => date("Y-m-d H:i:s", filemtime("$backupDir/$backup"))
        ];
    }
}

echo json_encode(['resultado' => 'exito', 'backups' => $backupList]);

?>