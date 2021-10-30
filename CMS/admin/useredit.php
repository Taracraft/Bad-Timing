<?
// We need to use sessions, so you should always start sessions using the below code.
session_start();
// If the user is not logged in redirect to the login page...
if (!isset($_SESSION['loggedin'])) {
    header('Location: ../../cms/index.html');
    exit;
}
include("../../cms/config/db.php");
include("../../cms/style/template/header.php");
?>
    <div class="login">
        <h1>Hinzuf&uuml;gen</h1>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" autocomplete="off">
            <label for="username">
                <i class="fas fa-user"></i>
            </label>
            <input type="text" name="username" placeholder="Benutzername" id="username" required>
            <input type="submit" value="Hinzuf&uuml;gen">
        </form>
    </div>



    </div>
<?
include("../../cms/style/template/footer.php");
?>