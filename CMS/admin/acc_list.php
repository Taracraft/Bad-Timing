<?
// We need to use sessions, so you should always start sessions using the below code.
session_start();
// If the user is not logged in redirect to the login page...
if (!isset($_SESSION['loggedin'])) {
	header('Location: index.html');
	exit;
}
include("../../cms/style/template/header.php");
?>
<div class="content">
<h2>Benutzer-Liste</h2>
$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if ( mysqli_connect_errno() ) {
	// If there is an error with the connection, stop the script and display the error.
	exit('Failed to connect to MySQL: ' . mysqli_connect_error());}
$stmt = $con->prepare('SELECT id, password FROM accounts WHERE username = ?');

$result = mysql_query("SELECT id, name FROM mytable");

while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
    printf("ID: %s  username: %s", $row["id"], $row["username"]);
}

mysql_free_result($result);
</div>
<?
include("../../cms/style/template/footer.php");
?>