<?php
session_start();

// Überprüfen, ob der Benutzer eingeloggt ist und Admin-Rechte hat
if (!isset($_SESSION['loggedin']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../../cms/index.html');
    exit;
}

include("../../cms/config/db.php");
include("../../cms/funktionen/funktionen.php");
include("../../cms/style/template/header.php");
include("../../cms/style/template/nav.php");

// Variablen für Formularwerte
$username = $password = $email = $role = '';
$username_error = $password_error = $email_error = '';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Formular-Daten verarbeiten
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];
    $role = $_POST['role'];

    // Funktion zur Benutzerregistrierung aufrufen
    $message = add_user($con, $username, $password, $email, $role);

    // Überprüfen, ob die Validierungsfehler zurückgegeben wurden
    if ($message === 'Benutzername gibt es schon!') {
        $username_error = 'input-error';
    } elseif ($message === 'E-Mail-Adresse ist bereits registriert!') {
        $email_error = 'input-error';
    } elseif (strpos($message, 'Passwort') !== false) {
        $password_error = 'input-error';
    }
}

$con->close();
?>
<div class="login">
    <h1>Benutzer hinzufügen</h1>
    <?php if ($message): ?>
        <p><?php echo $message; ?></p>
    <?php endif; ?>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" autocomplete="off">
        <label for="username">
            <i class="fas fa-user"></i>
        </label>
        <input type="text" name="username" placeholder="Benutzername" id="username" value="<?php echo htmlspecialchars($username); ?>" class="<?php echo $username_error; ?>" required>
        <label for="password">
            <i class="fas fa-lock"></i>
        </label>
        <input type="password" name="password" placeholder="Passwort" id="password" class="<?php echo $password_error; ?>" required>
        <label for="email">
            <i class="fas fa-envelope"></i>
        </label>
        <input type="text" name="email" placeholder="Email-Adresse" id="email" value="<?php echo htmlspecialchars($email); ?>" class="<?php echo $email_error; ?>" required>
        <div style="text-align: left; width: 100%;">
            <label for="role">
                <i class="fas fa-user-tag"></i> Berechtigung:
            </label>
        </div>
        <select name="role" id="role" required>
            <option value="user" <?php echo $role == 'user' ? 'selected' : ''; ?>>Benutzer</option>
            <option value="admin" <?php echo $role == 'admin' ? 'selected' : ''; ?>>Administrator</option>
        </select>
        <input type="submit" value="Hinzufügen">
    </form>
</div>

<?php include("../../cms/style/template/footer.php"); ?>
