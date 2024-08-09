<?php
session_start();
require_once 'classes/LDAPAuth.php';

$ldapAuth = new LDAPAuth();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if ($ldapAuth->authenticate($username, $password)) {
        $_SESSION['user'] = $username;
        header("Location: userlist.php");
        exit();
    } else {
        $error = "Invalid login credentials.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
    <div class="login-container">
        <h1>Login</h1>
        <form method="POST">
            <label>Username: <input type="text" name="username" required></label><br>
            <label>Password: <input type="password" name="password" required></label><br>
            <button type="submit">Login</button>
        </form>
        <?php if (isset($error)) { echo "<p>$error</p>"; } ?>
    </div>
</body>
</html>
