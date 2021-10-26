<?
// We need to use sessions, so you should always start sessions using the below code.
session_start();
// If the user is not logged in redirect to the login page...
if (!isset($_SESSION['loggedin'])) {
	header('Location: index.html');
	exit;
}
include("../../cms/style/template/header.php");
include("config/db.php");

?>

?>		<div class="login">
			<h1>Login</h1>
			<form action="../../cms/admin/acc_add.php" method="post">
				<label for="username">
					<i class="fas fa-user"></i>
				</label>
				
				<input type="text" name="username" placeholder="Benutzername" id="username" erfoderlich>
				<label for="email">
					<i class="fas fa-user"></i>
				</label>
				
				<input type="text" name="email" placeholder="E-Mail Adresse" id="email" erfoderlich>

				<label for="password">
					<i class="fas fa-lock"></i>
				</label>
				<input type="password" name="password" placeholder="Password" id="password" erforderlich>
				<input type="submit" value="Login">
			</form>
		<?
if ( !isset($_POST['username'], $_POST['password']) ) {
	// Could not get the data that should have been sent.
	exit('Bitte Benutzername und Password Felder ausfüllen!');
$option = [ "cost" => 15 ];
$pwhash = password_hash($password, PASSWORD_BCRYPT, $option);

$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
// Check connection
if ($con->connect_error) {
  die("Connection failed: " . $con->connect_error);
}

$sql = INSERT INTO accounts (id+1, username, password, email){
	VALUES (id+1, $username, $pwhash, $email);}
$sqld=$sql->execute();
$sqld->store_result();

echo 'Benutzer erfolgreich angelegt!';

include("../../cms/style/template/footer.php");
?>
