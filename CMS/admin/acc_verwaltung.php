<?php
session_start();

// Funktion zur Anzeige von Fehlermeldungen
function display_error($message) {
    echo '<!DOCTYPE html>
    <html>
    <head>
        <meta charset="utf-8">
        <title>Fehler</title>
        <link href="../../cms/style/css/layout.css" rel="stylesheet" type="text/css">
        <link href="../../cms/style/css/nav.css" rel="stylesheet" type="text/css">
        <link href="../../cms/style/css/table_forms_buttons.css" rel="stylesheet" type="text/css">
    </head>
    <body>
        <div class="error-message">' . $message . '</div>
    </body>
    </html>';
    exit();
}

// Überprüfen, ob der Benutzer eingeloggt ist und Admin-Rechte hat
if (!isset($_SESSION['loggedin'])) {
    header('Location: ../../cms/index.php?error=notloggedin');
    exit();
} elseif ($_SESSION['role'] !== 'admin') {
    display_error('Sie haben keine Berechtigung, diese Seite zu sehen. Bitte melden Sie sich als Administrator an.');
}

include("../../cms/style/template/header.php");
include("../../cms/style/template/nav.php");
?>

<div class="content">
    <h2>Admin-Bereich</h2>
    <p><a href="../../cms/admin/acc_add.php"><i class="fas fa-user-circle"></i> Benutzer hinzufügen</a></p>
    <p><a href="../../cms/admin/acc_list.php"><i class="fas fa-user-circle"></i> Benutzer Editieren/Löschen</a></p>
</div>

<?php
include("../../cms/style/template/footer.php");
?>
