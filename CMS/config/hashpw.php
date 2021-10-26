<?php
include("config/db.php");
$password = "password";
$username = "username";
if ( !isset($_POST['username'], $_POST['password']) ) {
	// Could not get the data that should have been sent.
	exit('PBitte Benutzername und Password Felder ausfüllen!');
$option = [ "cost" => 15 ];
$pwhash = password_hash($password, PASSWORD_BCRYPT, $option);

$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if ( mysqli_connect_errno() ) {
	// If there is an error with the connection, stop the script and display the error.
	exit('Failed to connect to MySQL: ' . mysqli_connect_error());
	
$stmt = $con->prepare('SELECT id, password FROM accounts WHERE username = ?')
echo strlen($pwhash) . " characters<br>" . $pwhash;
$stmt->INSERT INTO `accounts` (`id`, `username`, `password`, `email`) VALUES ('id+1', '$username', '$pwhash', '');

echo 'Benutzer erfolgreich angelegt!';

?>