<?php
include("../../cms/config/db.php");
session_start();

// Verbindung zur Datenbank herstellen
$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if (mysqli_connect_errno()) {
    $error_message = 'Failed to connect to MySQL: ' . mysqli_connect_error();
    display_error($error_message);
    exit();
}

// Überprüfen, ob die Login-Daten gesendet wurden
if (!isset($_POST['username'], $_POST['password'])) {
    $error_message = 'Bitte füllen Sie sowohl das Benutzername- als auch das Passwortfeld aus!';
    display_error($error_message);
    exit();
}

// Benutzereingaben in Session speichern, damit sie bei einem Fehler beibehalten werden
$_SESSION['login_username'] = $_POST['username'];
$_SESSION['login_password'] = $_POST['password'];

// SQL-Abfrage vorbereiten zur Vermeidung von SQL-Injection
if ($stmt = $con->prepare('SELECT id, password, role, status FROM accounts WHERE username = ?')) {
    $stmt->bind_param('s', $_POST['username']);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // Benutzerdaten abrufen
        $stmt->bind_result($id, $password, $role, $status);
        $stmt->fetch();

        // Überprüfen, ob der Benutzer deaktiviert ist oder den Aktivierungsprozess nicht abgeschlossen hat
        if ($status === 'deactivated') {
            $error_message = 'Ihr Konto wurde deaktiviert. Bitte kontaktieren Sie den Administrator.';
            display_error($error_message);
            exit();
        } elseif ($status === 'inactive') {
            $error_message = 'Ihr Konto ist noch nicht aktiviert. Bitte überprüfen Sie Ihre E-Mails.';
            display_error($error_message);
            exit();
        }

        // Passwort überprüfen
        if (password_verify($_POST['password'], $password)) {
            // Erfolgreiche Authentifizierung, Sitzungen erstellen
            session_regenerate_id();
            $_SESSION['loggedin'] = TRUE;
            $_SESSION['name'] = $_POST['username'];
            $_SESSION['id'] = $id;
            $_SESSION['role'] = $role;

            // Erfolgreicher Login, Session-Daten für Eingabe zurücksetzen
            unset($_SESSION['login_username']);
            unset($_SESSION['login_password']);

            header('Location: ../home.php');
            exit();
        } else {
            $error_message = 'Falscher Benutzername oder Passwort!';
            display_error($error_message);
        }
    } else {
        $error_message = 'Falscher Benutzername oder Passwort!';
        display_error($error_message);
    }

    $stmt->close();
} else {
    $error_message = 'Fehler beim Verarbeiten der Anfrage.';
    display_error($error_message);
}

function display_error($message) {
    $_SESSION['error_message'] = $message;
    header('Location: ../../cms/index.php');
    exit();
}
?>
