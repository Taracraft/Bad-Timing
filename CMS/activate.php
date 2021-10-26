<?php
// We need to use sessions, so you should always start sessions using the below code.
session_start();
// If the user is not logged in redirect to the login page...
if (!isset($_SESSION['loggedin'])) {
    header('Location: index.html');
    exit;
}
include("config/db.php");
include("style/template/header.php");
// Try and connect using the info above.
$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if (mysqli_connect_errno()) {
// If there is an error with the connection, stop the script and display the error.
exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}
// First we check if the email and code exists...
if (isset($_GET['email'], $_GET['code'])) {
if ($stmt = $con->prepare('SELECT * FROM accounts WHERE email = ? AND activation_code = ?')) {
$stmt->bind_param('ss', $_GET['email'], $_GET['code']);
$stmt->execute();
// Store the result so we can check if the account exists in the database.
$stmt->store_result();
if ($stmt->num_rows > 0) {
// Account exists with the requested email and code.
if ($stmt = $con->prepare('UPDATE accounts SET activation_code = ? WHERE email = ? AND activation_code = ?')) {
// Set the new activation code to 'activated', this is how we can check if the user has activated their account.
$newcode = 'activated';
$stmt->bind_param('sss', $newcode, $_GET['email'], $_GET['code']);
$stmt->execute();
echo 'Dein Account wurde Aktiviert! Du kannst dich nun  <a href="index.html">Einloggen</a>!';
}
} else {
echo 'Dieser Account wurde bereits Aktiviert oder exestiert nicht!';
}
}
}
if ($account['activation_code'] == 'activated') {
    // account is activated
    // Display home page etc
} else {
    // account is not activated
    // redirect user or display an error
}
include("style/template/footer.php");
?>