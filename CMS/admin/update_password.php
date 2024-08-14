<?php
include("../../cms/config/db.php");
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_POST['user_id'];
    $new_password = $_POST['new_password'];

    // Password hashing for security
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

    if ($con) {
        $stmt = $con->prepare('UPDATE accounts SET password = ? WHERE id = ?');
        $stmt->bind_param('si', $hashed_password, $user_id);
        if ($stmt->execute()) {
            echo '<div class="success-message">Passwort erfolgreich geändert!</div>';
        } else {
            echo '<div class="error-message">Fehler beim Aktualisieren des Passworts!</div>';
        }
        $stmt->close();
    } else {
        echo '<div class="error-message">Datenbankverbindung nicht hergestellt!</div>';
    }
}
$con->close();
?>
