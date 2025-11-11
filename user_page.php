<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['email'])) {
    header('Location: index.php');
    exit();
}

$nameCol = 'name';
$colRes = $conn->query("SHOW COLUMNS FROM users LIKE 'username'");
if ($colRes && $colRes->num_rows > 0) {
    $nameCol = 'username';
}

$msg = '';
$email = $conn->real_escape_string($_SESSION['email']);

//change username (requires current password)
if (isset($_POST['change_username'])) {
    $newName = trim($_POST['new_username'] ?? '');
    $curPass = $_POST['current_password_u'] ?? '';
    if ($newName === '' || $curPass === '') {
        $msg = 'Enter new username and your password.';
    } else {
        $res = $conn->query("SELECT password FROM users WHERE email='$email'");
        if ($res && $row = $res->fetch_assoc()) {
            if (password_verify($curPass, $row['password'])) {
                $newNameEsc = $conn->real_escape_string($newName);
                $conn->query("UPDATE users SET $nameCol='$newNameEsc', updated_at=NOW() WHERE email='$email'");
                $_SESSION['username'] = $newName;
                $msg = 'Username updated.';
            } else {
                $msg = 'Current password is incorrect.';
            }
        }
    }
}

//change password (requires current password)
if (isset($_POST['change_password'])) {
    $curPass = $_POST['current_password_p'] ?? '';
    $newPass = $_POST['new_password'] ?? '';
    if ($curPass === '' || $newPass === '') {
        $msg = 'Enter current and new password.';
    } else {
        $res = $conn->query("SELECT password FROM users WHERE email='$email'");
        if ($res && $row = $res->fetch_assoc()) {
            if (password_verify($curPass, $row['password'])) {
                $newHash = $conn->real_escape_string(password_hash($newPass, PASSWORD_DEFAULT));
                $conn->query("UPDATE users SET password='$newHash', updated_at=NOW() WHERE email='$email'");
                $msg = 'Password updated.';
            } else {
                $msg = 'Current password is incorrect.';
            }
        }
    }
}

// upload avatar 
if (isset($_POST['upload_avatar'])) {
    if (!isset($_FILES['avatar']) || $_FILES['avatar']['error'] !== UPLOAD_ERR_OK) {
        $msg = 'Please select a valid image file.';
    } else {
        $imageData = file_get_contents($_FILES['avatar']['tmp_name']);
        $imageData = $conn->real_escape_string($imageData);

        $query = "UPDATE users SET avatar='$imageData', updated_at=NOW() WHERE email='$email'";
        if ($conn->query($query)) {
            $msg = 'Avatar uploaded successfully.';
        } else {
            $msg = 'Database update failed: ' . $conn->error;
        }
    }
}


//fresh user details
$user = null;
$res = $conn->query("SELECT id, $nameCol AS display_name, email, role, last_activity, created_at, updated_at ,avatar FROM users WHERE email='$email'");
if ($res) {
    $user = $res->fetch_assoc();
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="user.css"> 
    <title>User</title>
</head>
<body>
    <div class="profile-container">
        <h1>Hello <span class="username"><?= htmlspecialchars($user['display_name'] ?? ($_SESSION['email'] ?? 'User')) ?></span>, this is the user page</h1>
        <?php if (!empty($msg)): ?>
            <p><?= htmlspecialchars($msg) ?></p>
        <?php endif; ?>

        <?php if ($user): ?>
            <?php if (!empty($user['avatar'])): ?>
                <img class="avatar" src="get_avatar.php" alt="Profile Picture" height="300" width="400">
            <?php else: ?>
                <p><strong>Profile Picture:</strong> None uploaded yet.</p>
            <?php endif; ?>
            <p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
            <p><strong>Role:</strong> <?= htmlspecialchars($user['role']) ?></p>
            <p><strong>Last activity:</strong> <?= htmlspecialchars($user['last_activity'] ?? '') ?></p>
            <p><strong>Created at:</strong> <?= htmlspecialchars($user['created_at'] ?? '') ?></p>
            <p><strong>Updated at:</strong> <?= htmlspecialchars($user['updated_at'] ?? '') ?></p>
        <?php endif; ?>

        <h3>Upload Profile Picture</h3>
        <form method="post" enctype="multipart/form-data">
            <input type="file" name="avatar" accept="image/*">
            <button type="submit" name="upload_avatar">Upload</button>
        </form>

        <h3>Change Username</h3>
        <form method="post">
            <input type="text" name="new_username" placeholder="New username" required>
            <input type="password" name="current_password_u" placeholder="Current password" required>
            <button type="submit" name="change_username">Change Username</button>
        </form>

        <h3>Change Password</h3>
        <form method="post">
            <input type="password" name="current_password_p" placeholder="Current password" required>
            <input type="password" name="new_password" placeholder="New password" required>
            <button type="submit" name="change_password">Change Password</button>
        </form>

        <button onclick="window.location.href='logout.php'">Logout</button>
    </div>        
</body>
</html>