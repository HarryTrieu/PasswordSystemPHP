<?php
// Serve avatar image as proper binary with correct headers.
// Used chatgpt for this. 

session_start();
require_once 'config.php';

$email = $_GET['email'] ?? '';
if (empty($email) && isset($_SESSION['email'])) {
    $email = $_SESSION['email'];
}

if (empty($email)) {
    http_response_code(400);
    exit('Missing email');
}

$stmt = $conn->prepare("SELECT avatar FROM users WHERE email=? LIMIT 1");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows === 0) {
    $stmt->close();
    http_response_code(404);
    exit('No avatar found');
}

$stmt->bind_result($avatar);
$stmt->fetch();
$stmt->close();

if ($avatar === null || $avatar === '') {
    http_response_code(404);
    exit('No avatar found');
}

// Detect MIME type
$mime = 'image/jpeg';
if (function_exists('finfo_buffer')) {
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    if ($finfo) {
        $detected = finfo_buffer($finfo, $avatar);
        if ($detected) $mime = $detected;
        finfo_close($finfo);
    }
}

// Optional download
if (isset($_GET['download'])) {
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="avatar"');
} else {
    header("Content-Type: $mime");
}
header('Content-Length: ' . strlen($avatar));
echo $avatar;
?>
