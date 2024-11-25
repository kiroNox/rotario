<?php
// Controlador para descargar un PDF desde el servidor

// Ruta del archivo PDF que se desea descargar
$pdfPath = 'manual.pdf'; // Cambia esta ruta por la real en tu servidor

// Verificar si el archivo existe
if (file_exists($pdfPath)) {
    // Establecer encabezados para la descarga del archivo
    header('Content-Description: File Transfer');
    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment; filename="' . basename($pdfPath) . '"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($pdfPath));

    // Limpiar el búfer de salida y enviar el contenido del archivo
    ob_clean();
    flush();
    readfile($pdfPath);
    exit;
} else {
    // Si el archivo no existe, mostrar un mensaje de error
    http_response_code(404);
    echo 'El archivo solicitado no se encuentra en el servidor.';
    exit;
}
?>

