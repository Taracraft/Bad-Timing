<?
// We need to use sessions, so you should always start sessions using the below code.
session_start();
// If the user is not logged in redirect to the login page...
if (!isset($_SESSION['loggedin'])) {
	header('Location: index.html');
	exit;
}
include("../../cms/config/db.php");
include("../../cms/style/template/header.php");
?>
<div class="content">
<h2>Benutzer-Liste</h2>
<?
// Create connection
$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
// Check connection
if ($con->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT id, username, password, email FROM accounts";
$result = $con->query($sql);

if ($result->num_rows > 0) {
  // output data of each row
  while($row = $result->fetch_assoc()) {
    echo "id: " . $row["id"]. " - Name: " . $row["username"]. " " . $row["password"]. "email: " . $row["email"]. "<br>";
  }
} else {
  echo "0 results";
}
?>
</div>
<?
include("../../cms/style/template/footer.php");
?>