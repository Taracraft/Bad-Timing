<?php
include("../../cms/config/db.php");
session_start();

$response = ['status' => 'error', 'message' => ''];

// Verbindung zur Datenbank herstellen
$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);

if (mysqli_connect_errno()) {
    $response['message'] = 'Fehler bei der Verbindung zur Datenbank: ' . mysqli_connect_error();
    echo json_encode($response);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_POST['user_id'];
    $new_email = $_POST['new_email'];

    if (filter_var($new_email, FILTER_VALIDATE_EMAIL)) {
        $stmt = $con->prepare('UPDATE accounts SET email = ? WHERE id = ?');
        $stmt->bind_param('si', $new_email, $user_id);
        if ($stmt->execute()) {
            echo '<div class="success-message">E-Mail-Adresse erfolgreich geändert!</div>';
        } else {
            echo '<div class="error-message">Fehler beim Aktualisieren der E-Mail-Adresse!</div>';
        }
        $stmt->close();
    } else {
        echo '<div class="error-message">Ungültige E-Mail-Adresse!</div>';
    }
}

$con->close();
?>
