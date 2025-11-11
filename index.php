<?php

session_start();

$errors = [
    'login' => $_SESSION['login_error'] ?? '',
    'register' => $_SESSION['register_error'] ?? '',
    'signup' => $_SESSION['register_error'] ?? ''
];

$active_form = $_SESSION['active_form'] ?? 'login';

session_unset();

function showError($error){
    return !empty($error) ? "<p class='error-message'>$error</p>" : "";
}

function isActiveForm($form_name, $active_form){
    return $form_name === $active_form ? 'active' : '';
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login System</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <form class="form-box <?= isActiveForm('login',$active_form); ?>" action="LoginSystem.php" method="post" id="login-form" >
            <h1>Login</h1>
            <?= showError($errors['login']); ?>
            <label for="username">Email:</label>
            <input type="email" id="email" name="email" required>
            <br>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            <br>
            <button type="submit" name="login">Login</button>
            <p>Don't have an account? <a href="#" onclick="showForm('register-form')"> Sign Up</a></p>
        </form>

        <form class="form-box <?= isActiveForm('register',$active_form); ?>" action="LoginSystem.php" method="post" id="register-form">
            <h1>Sign Up</h1>
            <?= showError($errors['signup']); ?>
            <label for="username">Username:</label>
            <input type="username" id="username" name="username" required>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            <br>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            <br>
            <button type="submit" name="register">Sign Up</button>
            <p>Already have an account?<a href="#" onclick="showForm('login-form')"> Log in</a></p>
        </form>

        <script src="script.js"></script>
</body>
</html>
