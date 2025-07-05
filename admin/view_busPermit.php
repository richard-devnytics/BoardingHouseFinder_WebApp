<?php
// Sanitize the input
$file_path = isset($_GET['file']) ? $_GET['file'] : '';
$file_path = realpath($file_path);

// Base directory to limit access (change to your desired base directory)
$base_dir = realpath('uploads');

// Check if the file exists and is within the base directory
if ($file_path && strpos($file_path, $base_dir) === 0 && file_exists($file_path)) {
    $file_info = pathinfo($file_path);
    $file_extension = strtolower($file_info['extension']);
    
    // Determine the correct content type
    switch ($file_extension) {
        case 'jpg':
        case 'jpeg':
            $content_type = 'image/jpeg';
            break;
        case 'png':
            $content_type = 'image/png';
            break;
        case 'gif':
            $content_type = 'image/gif';
            break;
        case 'pdf':
            $content_type = 'application/pdf';
            break;
        case 'txt':
            $content_type = 'text/plain';
            break;
        default:
            die('Unsupported file type.');
    }
    
    // Output the file with the correct headers
    header('Content-Type: ' . $content_type);
    header('Content-Length: ' . filesize($file_path));
    readfile($file_path);
} else {
    echo 'File not found or access denied.';
}
?>
