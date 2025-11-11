<?php
session_start();
// Require login and admin role
if (!isset($_SESSION['email'])) {
    header('Location: index.php');
    exit();
}
if (($_SESSION['role'] ?? 'user') !== 'admin') {
    header('Location: user_page.php');
    exit();
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin</title>
</head>
<body style="background:fff;">
    <h1>Hello <span><?= htmlspecialchars($_SESSION['username'] ?? ($_SESSION['email'] ?? 'Admin')) ?></span>, this is the admin page</h1>
    <button onclick="window.location.href = 'logout.php'">Logout</button>
</body>
</html>