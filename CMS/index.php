<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Login</title>
    <link href="../../cms/style/css/layout.css" rel="stylesheet" type="text/css">
    <link href="../../cms/style/css/nav.css" rel="stylesheet" type="text/css">
    <link href="../../cms/style/css/table_forms_buttons.css" rel="stylesheet" type="text/css">
</head>
<body>
<div class="login">
    <h1>Login</h1>
    <?php
    session_start();
    if (isset($_SESSION['error_message'])) {
        echo '<div class="error-message">' . $_SESSION['error_message'] . '</div>';
        unset($_SESSION['error_message']);
    }

    // Benutzereingaben merken
    $username = isset($_SESSION['login_username']) ? $_SESSION['login_username'] : '';
    $password = isset($_SESSION['login_password']) ? $_SESSION['login_password'] : '';
    ?>
    <form action="../../cms/funktionen/authenticate.php" method="post">
        <label for="username">
            <i class="fas fa-user"></i>
        </label>
        <input type="text" name="username" placeholder="Benutzername" id="username" value="<?php echo htmlspecialchars($username); ?>" required>
        <label for="password">
            <i class="fas fa-lock"></i>
        </label>
        <input type="password" name="password" placeholder="Passwort" id="password" value="<?php echo htmlspecialchars($password); ?>" required>
        <input type="submit" value="Login">
    </form>
</div>
</body>
</html>
