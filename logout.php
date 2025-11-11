<?php
session_start();
require_once 'config.php';

// last activity
if (isset($_SESSION['email'])) {
    $email = $conn->real_escape_string($_SESSION['email']);
    $conn->query("UPDATE users SET last_activity = NOW() WHERE email='$email'");
}


session_unset();
session_destroy();

header('Location: index.php');
exit();
?>