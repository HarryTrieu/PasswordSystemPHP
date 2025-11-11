<?php 

session_start();
require_once 'config.php';

if (isset($_POST['register'])) {
    $name = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    
    // read substring,if its @coccoc.com then admin,else user
    $role = (strpos($email, '@coccoc.com') !== false) ? 'admin' : 'user';

    $checkmail = $conn->query("SELECT email FROM users WHERE email='$email' ");
    if ($checkmail->num_rows > 0) {
        $_SESSION["register_error"] = "Email already exists.";
        $_SESSION["active_form"] = "register";
    } else {

        $colCheck = $conn->query("SHOW COLUMNS FROM users LIKE 'username'");
        $nameCol = ($colCheck && $colCheck->num_rows > 0) ? 'username' : 'name';
        $conn->query("INSERT INTO users ($nameCol, email, password, role, last_activity) VALUES ('$name', '$email', '$password', '$role', NOW())");
        
        $_SESSION["username"] = $name;
        $_SESSION["email"] = $email;
        $_SESSION["role"] = $role;
        
        // endpoint based on role
        if (strpos($email, '@coccoc.com') !== false) {
            header("Location: admin_page.php");
        } else {
            header("Location: user_page.php");
        }
        exit();
    }

    header("Location: index.php");
    exit();
}

if (isset($_POST["login"])) {
    $email = $_POST["email"];
    $password = $_POST["password"];

    $result = $conn->query("SELECT * FROM users WHERE email='$email' ");
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            // Update last_activity on successful login
            $conn->query("UPDATE users SET last_activity = NOW() WHERE email='$email'");
            $_SESSION["username"] = $user['username'] ?? ($user['name'] ?? '');
            $_SESSION["email"] = $user['email'];
            $_SESSION["role"] = $user['role'] ?? 'user';

            if ($user['role'] == 'admin') {
                header("Location: admin_page.php");
            } else {
                header("Location: user_page.php");
            }
            exit();
        } else {
            $_SESSION["login_error"] = "Invalid email or password.";
            $_SESSION["active_form"] = "login";
        }
    } else {
        $_SESSION["login_error"] = "Invalid email or password.";
        $_SESSION["active_form"] = "login";
    }

    header("Location: index.php");
    exit();
}
?>