<?
// We need to use sessions, so you should always start sessions using the below code.
session_start();
// If the user is not logged in redirect to the login page...
if (!isset($_SESSION['loggedin'])) {
    header('Location: ../../cms/index.php');
    exit;
}
include("../../cms/config/db.php");
include("../../cms/style/template/header.php");
?>
<?

// Create connection
$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
// Check connection
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

$sql = "SELECT username, FROM accounts";
$result = $con->query($sql);
?>

    <div class="login">
        <h1>Hinzuf&uuml;gen</h1>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" autocomplete="off">
            <label for="username">
                <i class="fas fa-user"></i>
            </label>
            <input type="text" name="username" placeholder="Benutzername" id="username" required>
            <input type="submit" value="Speichern">
        </form>
    </div>



    </div>
<?
include("../../cms/style/template/footer.php");
?>