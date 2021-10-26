<?php
// We need to use sessions, so you should always start sessions using the below code.
include("config/db.php");
session_start();
// If the user is not logged in redirect to the login page...
if (!isset($_SESSION['loggedin'])) {
	header('Location: index.html');
	exit;
}
$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if (mysqli_connect_errno()) {
	exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}
// We don't have the password or email info stored in sessions so instead we can get the results from the database.
$stmt = $con->prepare('SELECT password, email FROM accounts WHERE id = ?');
// In this case we can use the account ID to get the account info.
$stmt->bind_param('i', $_SESSION['id']);
$stmt->execute();
$stmt->bind_result($password, $email);
$stmt->fetch();
$stmt->close();
include("style/template/header.php");
?>

		<div class="content">
			<h2>Profile Page</h2>
			<div>
				<p>Deine Account Informationen:</p>
				<table>
					<tr>
						<td>Benutzername:</td>
						<td><?=$_SESSION['name']?></td>
					</tr>
					<tr>
						<td>Password(Verschlüsselt:</td>
						<td><?=$password?></td>
					</tr>
					<tr>
						<td>E-Mail-Adresse:</td>
						<td><?=$email?></td>
					</tr>
				</table>
			</div>
		</div>
<?
include("style/template/footer.php");
?>