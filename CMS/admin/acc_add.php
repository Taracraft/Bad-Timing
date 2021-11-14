<?php
// We need to use sessions, so you should always start sessions using the below code.
session_start();
// If the user is not logged in redirect to the login page...
if (!isset($_SESSION['loggedin'])) {
    header('Location: ../../cms/index.html');
    exit;
}
include("../../cms/config/db.php");
include("../../cms/style/template/header.php");
include("../../cms/style/template/nav.php");
?>
<div class="login">
    <h1>Hinzuf&uuml;gen</h1>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" autocomplete="off">
        <label for="username">
            <i class="fas fa-user"></i>
        </label>
        <input type="text" name="username" placeholder="Benutzername" id="username" required>
        <label for="password">
            <i class="fas fa-lock"></i>
        </label>
        <input type="password" name="password" placeholder="Passwort" id="password" required>
        <label for="email">
            <i class="fas fa-envelope"></i>
        </label>
        <input type="text" name="email" placeholder="Email-Adresse" id="email" required>
        <input type="submit" value="Hinzuf&uuml;gen">
    </form>
</div>
<?
// Try and connect using the info above.
$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if (mysqli_connect_errno()) {
    // If there is an error with the connection, stop the script and display the error.
    exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}
// Now we check if the data was submitted, isset() function will check if the data exists.
if (!isset($_POST['username'], $_POST['password'], $_POST['email'])) {
    // Could not get the data that should have been sent.
    exit('Bitte alles Ausf&uuml;llen!');
}
// Make sure the submitted registration values are not empty.
if (empty($_POST['username']) || empty($_POST['password']) || empty($_POST['email'])) {
    // One or more values are empty.
    exit('Bitte alles Ausf&uuml;llen');
}
if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
    exit('Dies ist keine E-Mailadresse!');
}
if (preg_match('/^[a-zA-Z0-9]+$/', $_POST['username']) == 0) {
    exit('Benutzername entspricht nicht den Anforderungen!');
}
if (strlen($_POST['password']) > 20 || strlen($_POST['password']) < 5) {
    exit('Passwort muss zwischen 5 und 20 Zeichen lang sein!');
}
// We need to check if the account with that username exists.
if ($stmt = $con->prepare('SELECT id, password FROM accounts WHERE username = ?')) {
    // Bind parameters (s = string, i = int, b = blob, etc), hash the password using the PHP password_hash function.
    $stmt->bind_param('s', $_POST['username']);
    $stmt->execute();
    $stmt->store_result();
    // Store the result so we can check if the account exists in the database.
    if ($stmt->num_rows > 0) {
        // Username already exists
        echo 'Benutzername gibt es schon!';
    } else {
        // Username doesnt exists, insert new account
        if ($stmt = $con->prepare('INSERT INTO accounts (username, password, email, activation_code) VALUES (?, ?, ?, ?)')) {
    // We do not want to expose passwords in our database, so hash the password and use password_verify when a user logs in.
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $uniqid = uniqid();
            $stmt->bind_param('ssss', $_POST['username'], $password, $_POST['email'], $uniqid);
    $stmt->execute();
            $from    = 'noreply@bad-timing.eu';
            $subject = 'Account Aktivierung Erforderlich';
            $headers = 'From: ' . $from . "\r\n" . 'Reply-To: ' . $from . "\r\n" . 'X-Mailer: PHP/' . phpversion() . "\r\n" . 'MIME-Version: 1.0' . "\r\n" . 'Content-Type: text/html; charset=UTF-8' . "\r\n";
// Update the activation variable below
            $activate_link = 'https://www.bad-timing.eu/cms/activate.php?email=' . $_POST['email'] . '&code=' . $uniqid;
            $message = '<p>Bitte klicke auf den folgenden Link zum aktivieren deines Accounts: <a href="' . $activate_link . '">' . $activate_link . '</a></p>';
            mail($_POST['email'], $subject, $message, $headers);
            echo 'Überprüfe dein E-Mail-Postfach zum Aktivieren!';
} else {
    // Something is wrong with the sql statement, check to make sure accounts table exists with all 3 fields.
    echo 'Kann das statement nicht erstellen, Kontaktiere den Systemadministrator(1)!';
}
    }
    $stmt->close();
} else {
    // Something is wrong with the sql statement, check to make sure accounts table exists with all 3 fields.
    echo 'Kann das statement nicht erstellen, Kontaktiere den Systemadministrator(1)!';
}
$con->close();

?>


<?
include("../../cms/style/template/footer.php");
?>
#diesisteinkommentar