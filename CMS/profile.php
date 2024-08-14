<?php
// Start der Session und inkludieren der notwendigen Dateien
include("config/db.php");
include('funktionen/funktionen.php');
session_start();

// Überprüfen, ob der Benutzer eingeloggt ist
if (!isset($_SESSION['loggedin'])) {
    header('Location: ../../cms/index.php');
    exit;
}

// Verbindung zur Datenbank herstellen
$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if (mysqli_connect_errno()) {
    exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}

// Funktion zum Abrufen der Benutzerdaten
function getUserData($con, $userId) {
    $stmt = $con->prepare('SELECT password, email FROM accounts WHERE id = ?');
    $stmt->bind_param('i', $userId);
    $stmt->execute();
    $stmt->bind_result($password, $email);
    $stmt->fetch();
    $stmt->close();
    return ['password' => $password, 'email' => $email];
}

// Benutzerdaten aus der Datenbank abrufen
$userData = getUserData($con, $_SESSION['id']);

// Variable für die Rückmeldung
$feedback = '';

// Wenn das Formular abgeschickt wurde, die Daten aktualisieren
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Überprüfen, ob sich die E-Mail geändert hat
    if (isset($_POST['email']) && $_POST['email'] !== $userData['email']) {
        $feedback .= update_email($con, $_SESSION['id'], $_POST['email']);
    }

    // Überprüfen, ob das Passwortfeld ausgefüllt wurde und sich vom gespeicherten Passwort unterscheidet
    if (isset($_POST['password']) && !empty($_POST['password'])) {
        if (!password_verify($_POST['password'], $userData['password'])) {
            $feedback .= '<br>' . update_password($con, $_SESSION['id'], $_POST['password']);
        } else {
            $feedback .= '<br><div class="error-message">Das neue Passwort darf nicht mit dem alten übereinstimmen!</div>';
        }
    }

    // Benutzerdaten nach dem Update erneut abrufen
    $userData = getUserData($con, $_SESSION['id']);
}

// Inkludieren der Template Dateien für Header und Navigation
include("style/template/header.php");
include("style/template/nav.php");
?>
<div class="login">
    <h1>Profile Page</h1>
    <?php if (!empty($feedback)): ?>
        <div class="feedback"><?= $feedback ?></div>
    <?php endif; ?>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" autocomplete="off">
        <label for="username">Benutzername (nicht änderbar):</label>
        <input type="text" name="username" placeholder="Benutzername" id="username" value="<?=$_SESSION['name']?>" disabled>

        <label for="password">Passwort ändern:</label>
        <input type="password" name="password" placeholder="Passwort" id="password">

        <label for="email">E-Mail ändern:</label>
        <input type="text" name="email" placeholder="Email-Adresse" id="email" required value="<?=$userData['email']?>">

        <input type="submit" value="Daten Updaten">
    </form>
</div>
<?php
include("style/template/footer.php");
?>
